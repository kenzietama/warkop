<?php

namespace App\database;
use Exception;
use PDO;

class MenuService {
	private PDO $pdo;

	public function __construct() {
		// Use the Database singleton to get the PDO connection
		$this->pdo = Database::getInstance()->getConnection();
	}

	// Get all ingredients
	public function getIngredients(): array {
		$stmt = $this->pdo->query("SELECT * FROM ingredients");
		return $stmt->fetchAll(PDO::FETCH_ASSOC);
	}

	// Add a new ingredient
	public function addIngredient(string $name, int $price, int $qty): void {
		$stmt = $this->pdo->prepare("INSERT INTO ingredients (name, price, qty) VALUES (:name, :price, :qty)");
		$stmt->execute(['name' => $name, 'price' => $price, "qty" => $qty]);
	}

	public function isInventoryAvailable(string $menuName, int $qty): bool {
		try {
			$stmt = $this->pdo->prepare("
                SELECT ingredients.qty
                FROM menus
				INNER JOIN ingredients ON menus.base_ingredient_id = ingredients.id
				WHERE menus.name = :menu_name1

				UNION ALL

				SELECT ingredients.qty
				FROM menu_condiments
				INNER join ingredients ON menu_condiments.condiment_id = ingredients.id
				INNER JOIN menus ON menu_condiments.menu_id = menus.id
				WHERE menus.name = :menu_name2;
            ");
			$stmt->execute(['menu_name1' => $menuName, 'menu_name2' => $menuName]);

			$ingredients = $stmt->fetchAll(PDO::FETCH_ASSOC);

			foreach ($ingredients as $ingredient) {
				if ($ingredient['qty'] < $qty) {
					return false;
				}
			}
			return true;
		} catch (Exception $e) {
			error_log($e->getMessage());
			return false;
		}
	}

	// Reduce inventory after processing an order
	public function reduceInventory(string $menuName, int $qty): bool {
		try {
			$this->pdo->beginTransaction();

			$stmt = $this->pdo->prepare("
                SELECT ingredients.id
                FROM menus
				INNER JOIN ingredients ON menus.base_ingredient_id = ingredients.id
				WHERE menus.name = :menu_name1

				UNION ALL

				SELECT ingredients.id
				FROM menu_condiments
				INNER join ingredients ON menu_condiments.condiment_id = ingredients.id
				INNER JOIN menus ON menu_condiments.menu_id = menus.id
				WHERE menus.name = :menu_name2;
            ");
			$stmt->execute(['menu_name1' => $menuName, 'menu_name2' => $menuName]);

			$ingredients = $stmt->fetchAll(PDO::FETCH_ASSOC);

			$updateStmt = $this->pdo->prepare("UPDATE ingredients SET qty = qty - :qty WHERE id = :id");

			foreach ($ingredients as $ingredient) {
				$updateStmt->execute([
					':qty' => $qty,
					':id' => $ingredient['id']
				]);
			}

			$this->pdo->commit();
			return true;
		} catch (Exception $e) {
			$this->pdo->rollBack();
			error_log($e->getMessage());
			return false;
		}
	}

	public function updateStock(array $stocks): bool {
		try {
			$this->pdo->beginTransaction();

			$stmt = $this->pdo->prepare("UPDATE ingredients SET qty = :qty WHERE id = :id");

			foreach ($stocks as $stock) {
				$stmt->execute([
					':qty' => $stock['qty'],
					':id' => $stock['id']
				]);
			}

			$this->pdo->commit();
			return true;
		} catch (Exception $e) {
			$this->pdo->rollBack();
			error_log($e->getMessage());
			return false;
		}
	}


	// Fetch sales statistics
	public function getSalesStatistics(): array {
		$sql = "
        SELECT 
            m.name AS menu_name, 
            SUM(rhm.menu_qty) AS total_sold 
        FROM receipt_has_menu rhm
        INNER JOIN menus m ON rhm.menu_id = m.id
        GROUP BY m.id, m.name
    ";
		$stmt = $this->pdo->prepare($sql);
		$stmt->execute();
		return $stmt->fetchAll(PDO::FETCH_ASSOC);
	}


	// Fetch ingredient stock levels
	public function getStockLevels(): array {
		$sql = "
        SELECT 
            name AS ingredient_name, 
            qty AS remaining_qty 
        FROM ingredients
    ";
		$stmt = $this->pdo->prepare($sql);
		$stmt->execute();
		return $stmt->fetchAll(PDO::FETCH_ASSOC);
	}


	// CRUD for menus
	public function getMenu(string $menuName): array {
		$stmt0 = $this->pdo->prepare("SELECT id FROM menus WHERE name = :name");
		$stmt0->execute(['name' => $menuName]);
		$menuId = $stmt0->fetchColumn();

		$stmt = $this->pdo->prepare("
			SELECT 
				m.name AS menu_name,
				m.img AS menu_img,
				i.name AS base_name,
				i.price AS base_price
			FROM menus m
			JOIN ingredients i ON m.base_ingredient_id = i.id
			WHERE m.id = :menu_id
        ");
		$stmt->execute(['menu_id' => $menuId]);
		$menu = $stmt->fetch(PDO::FETCH_ASSOC);

		if ($menu) {
			// Fetch condiments for the menu
			$stmt = $this->pdo->prepare("
            SELECT i.name AS condiment_name, i.price AS condiment_price 
            FROM menu_condiments mc
            JOIN ingredients i ON mc.condiment_id = i.id
            WHERE mc.menu_id = :menu_id
        ");
			$stmt->execute(['menu_id' => $menuId]);
			$menu['condiments'] = $stmt->fetchAll(PDO::FETCH_ASSOC);
		}
		var_dump($menu);

		return $menu;
	}


	public function getAllMenus(): array {
		// Fetch all menus with their base ingredients
		$stmt = $this->pdo->query("
            SELECT 
                m.id AS menu_id, 
                m.name AS menu_name,
                m.img AS menu_img,
                i.name AS base_name, 
                i.price AS base_price
            FROM menus m
            JOIN ingredients i ON m.base_ingredient_id = i.id
        ");
		$menus = $stmt->fetchAll(PDO::FETCH_ASSOC);

		// Fetch condiments for each menu
//		foreach ($menus as $menu) {
//			$stmt = $this->pdo->prepare("
//                SELECT i.name AS condiment_name, i.price AS condiment_price
//                FROM menu_condiments mc
//                JOIN ingredients i ON mc.condiment_id = i.id
//                WHERE mc.menu_id = :menu_id
//            ");
//			$stmt->execute(['menu_id' => $menu['menu_id']]);
//			$menu['condiments'] = $stmt->fetchAll(PDO::FETCH_ASSOC);
//		}
//
//		return $menus;

		// Fetch condiments for each menu
		foreach ($menus as $index => $menu) { // Use $index to modify $menus directly
			$stmt = $this->pdo->prepare("
            SELECT i.name AS condiment_name, i.price AS condiment_price 
            FROM menu_condiments mc
            JOIN ingredients i ON mc.condiment_id = i.id
            WHERE mc.menu_id = :menu_id
        ");
			$stmt->execute(['menu_id' => $menu['menu_id']]);
			$menus[$index]['condiments'] = $stmt->fetchAll(PDO::FETCH_ASSOC); // Update the specific menu
		}

		return $menus;
	}

	/**
	 * @throws Exception
	 */
	public function createMenu(string $name, string $img, string $baseIngredientName, array $condimentNames): void {
		$this->pdo->beginTransaction();

		try {
			$stmt0 = $this->pdo->prepare("SELECT id FROM ingredients WHERE name = :name");
			$stmt0->execute(['name' => $baseIngredientName]);
			$baseIngredientId = $stmt0->fetchColumn();

			$condimentIds = [];

			foreach ($condimentNames as $condimentName) {
				$stmt1 = $this->pdo->prepare("SELECT id FROM ingredients WHERE name = :name");
				$stmt1->execute(['name' => $condimentName]);

				$condimentId = $stmt1->fetchColumn();

				if ($condimentId) {
					$condimentIds[] = $condimentId;  // Store the condiment ID in the array if it exists
				} else {
					// Optionally, handle the case where the condiment name doesn't exist in the database
					echo "Condiment '$condimentName' not found in the database.<br>";
				}
			}

			$stmt = $this->pdo->prepare("INSERT INTO menus (name, img, base_ingredient_id) VALUES (:name, :img, :base_id)");
			$stmt->execute(['name' => $name, 'img' => $img, 'base_id' => $baseIngredientId]);
			$menuId = $this->pdo->lastInsertId();

			foreach ($condimentIds as $condimentId) {
				$stmt = $this->pdo->prepare("INSERT INTO menu_condiments (menu_id, condiment_id) VALUES (:menu_id, :condiment_id)");
				$stmt->execute(['menu_id' => $menuId, 'condiment_id' => $condimentId]);
			}

			$this->pdo->commit();
		} catch (Exception $e) {
			$this->pdo->rollBack();
			throw $e;
		}
	}
}
