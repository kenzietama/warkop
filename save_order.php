<?php
require_once "init/init.php";
use App\database\Database;
use App\database\MenuService;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	$data = json_decode(file_get_contents("php://input"), true);

	$totalPrice = $data['totalPrice'] ?? 0;
	$orders = $data['orders'] ?? [];
//	var_dump($orders);

	if ($totalPrice <= 0 || empty($orders)) {
		echo json_encode(['success' => false, 'message' => 'Data pesanan tidak valid.']);
		exit;
	}

	$menuService = new MenuService();

	try {
		foreach ($orders as $order) {
			if (!$menuService->isInventoryAvailable($order['name'], $order['qty'])) {
				echo json_encode(['success' => false, 'message' => "Stok tidak mencukupi untuk menu: {$order['name']} {$order['qty']}"]);
				exit;
			}
		}

		$db = Database::getInstance();
		$conn = $db->getConnection();
//		$conn->beginTransaction();

		// Insert into receipt table
		$stmt = $conn->prepare("INSERT INTO receipt (timestamp, total) VALUES (NOW(), ?)");
		$stmt->execute([$totalPrice]);
		$receiptId = $conn->lastInsertId();

//		echo json_encode(['success' => false, 'message' => $receiptId]);
//		exit;

		// Insert into receipt_has_menu table
		$stmt = $conn->prepare("INSERT INTO receipt_has_menu (receipt_id, menu_id, menu_qty) 
                                SELECT ?, id, ? FROM menus WHERE name = ?");
		foreach ($orders as $order) {
			$stmt->execute([$receiptId, $order['qty'], $order['name']]);
			$menuService->reduceInventory($order['name'], $order['qty']);
		}

//		$conn->commit();

		echo json_encode(['success' => true]);
	} catch (Exception $e) {
//		$conn->rollBack();
		echo json_encode(['success' => false, 'message' => $e->getMessage()]);
	}
}
