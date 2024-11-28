<?php
require_once "init/init.php";
use App\database\Database;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	$data = json_decode(file_get_contents("php://input"), true);

	$totalPrice = $data['totalPrice'] ?? 0;
	$orders = $data['orders'] ?? [];

	if ($totalPrice <= 0 || empty($orders)) {
		echo json_encode(['success' => false, 'message' => 'Data pesanan tidak valid.']);
		exit;
	}

	$db = Database::getInstance();
	$conn = $db->getConnection();

	try {
		// Start transaction
		$conn->beginTransaction();

		// Insert into `receipt` table
		$stmt = $conn->prepare("INSERT INTO receipt (timestamp, total) VALUES (NOW(), ?)");
		$stmt->execute([$totalPrice]);
		$receiptId = $conn->lastInsertId();

		// Insert into `receipt_has_menu` table
		$stmt = $conn->prepare("INSERT INTO receipt_has_menu (receipt_id, menu_id, menu_qty) 
                                SELECT ?, id, ? FROM menus WHERE name = ?");
		foreach ($orders as $order) {
			$stmt->execute([$receiptId, $order['qty'], $order['name']]);
		}

		// Commit transaction
		$conn->commit();

		echo json_encode(['success' => true]);
	} catch (Exception $e) {
		// Rollback transaction on error
		$conn->rollBack();
		echo json_encode(['success' => false, 'message' => $e->getMessage()]);
	}
}
