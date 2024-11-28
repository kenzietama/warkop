<?php
//namespace init;
require_once "init.php";
//require_once 'Database.php'; // Path to your Database singleton
//require_once 'MenuService.php'; // Path to your MenuService class
//require_once 'BaseIngredient.php'; // BaseIngredient class
//require_once 'Condiment.php'; // Condiment class

use App\database\BaseIngredient;
use App\database\Condiment;
use App\database\MenuService;
//use Exception;

try {
	// Initialize the MenuService using the Database singleton
	$menuService = new MenuService();

	// Add ingredients
//	$menuService->addIngredient("Kopi", 5000);
//	$menuService->addIngredient("Gula Aren", 2000);
//	$menuService->addIngredient("Gula Pasir", 2000);
//	$menuService->addIngredient("Susu", 3000);
//	$menuService->addIngredient("Es Krim", 3000);
//	$menuService->addIngredient("Coklat", 3000);
//
//	$menuService->addIngredient("Nasi", 4000);
//	$menuService->addIngredient("Sosis", 3000);
//	$menuService->addIngredient("Nugget", 3000);
//	$menuService->addIngredient("Ayam", 5000);

	// Create a menu
//	$menuService->createMenu("Kopi Gula Aren", "KopiGulaAren.png","Kopi", ["Gula Aren", "Susu"]); // Base ingredient ID 1 (Coffee), condiment IDs 2 (Milk), 3 (Sugar)
//	$menuService->createMenu("Affogato", "Affogato.png","Kopi", ["Gula Pasir", "Es Krim"]);
//	$menuService->createMenu("Latte", "Latte.jpg","Kopi", ["Susu"]);
//	$menuService->createMenu("Mocha", "Mocha.jpg","Kopi", ["Coklat", "Susu"]);
//
//	$menuService->createMenu("Nasi Goreng Sosis", "NasgorSosis.png", "Nasi", ["Sosis"]);
//	$menuService->createMenu("Nasi Goreng Nugget", "NasgorNugget.png", "Nasi", ["Nugget"]);
//	$menuService->createMenu("Nasi Goreng Ayam", "NasgorAyam.jpg", "Nasi", ["Ayam"]);
//	$menuService->createMenu("Nasi Goreng Spesial", "NasgorSpesial.jpg", "Nasi", ["Sosis", "Nugget", "Ayam"]);

	$menus = $menuService->getAllMenus();

	var_dump($menus);

//	foreach ($menus as $menu) {
//		$baseIngredient = new BaseIngredient($menu['base_name'], $menu['base_price']);
//		$decoratedMenu = $baseIngredient;
//
//		foreach ($menu['condiments'] as $condiment) {
//			$decoratedMenu = new Condiment($decoratedMenu, $condiment['condiment_name'], $condiment['condiment_price']);
//		}
//	}

	// Fetch menu
//	$menu = $menuService->getMenu("Latte"); // Fetch the menu with ID 1
//
//	// Build menu dynamically using the decorator pattern
//	$baseIngredient = new BaseIngredient($menu['base_name'], $menu['base_price']);
//	$decoratedMenu = $baseIngredient;
//
////	var_dump($menu['condiments']);
//
//	foreach ($menu['condiments'] as $condiment) {
//		$decoratedMenu = new Condiment($decoratedMenu, $condiment['condiment_name'], $condiment['condiment_price']);
//	}

	// Output the dynamically built menu
//	echo "Menu: " . $decoratedMenu->getDescription() . PHP_EOL;
//	echo "Price: $" . $decoratedMenu->getCost() . PHP_EOL;

} catch (Exception $e) {
	echo "Error: " . $e->getMessage();
}
