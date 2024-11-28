<?php
require_once "init/init.php";

use App\database\BaseIngredient;
use App\database\Condiment;
use App\database\MenuService;

$menuService = new MenuService();

// Fetch all menus
$menus = $menuService->getAllMenus();
//var_dump($menus);

//foreach ($menus as $menu) {
//	// Create the base ingredient
//	$baseIngredient = new BaseIngredient($menu['base_name'], $menu['base_price']);
//	$decoratedMenu = $baseIngredient;
//
//	// Add condiments dynamically
//	foreach ($menu['condiments'] as $condiment) {
//		$decoratedMenu = new Condiment($decoratedMenu, $condiment['condiment_name'], $condiment['condiment_price']);
////		echo $decoratedMenu->getDescription();
//	}
//
////	var_dump($decoratedMenu);
//
//	// Display the menu
//
////	$ingredients = $decoratedMenu->getDescription();
////	$ingredients = explode(",", $ingredients);
////	foreach ($ingredients as $ingredient) {
////		echo "<p>" . "• " . $ingredient . "</p><br>";
////	}
//
//	echo "<h3>Menu: " . $decoratedMenu->getDescription() . "</h3>";
//	echo "<p>Price: $" . $decoratedMenu->getCost() . "</p>";
//}

$menuList = [];

foreach ($menus as $menu) {
	// Create the base ingredient
	$baseIngredient = new BaseIngredient($menu['base_name'], $menu['base_price']);
	$decoratedMenu = $baseIngredient;

	// Add condiments dynamically
	foreach ($menu['condiments'] as $condiment) {
		$decoratedMenu = new Condiment($decoratedMenu, $condiment['condiment_name'], $condiment['condiment_price']);
	}

	// Add menu details to the associative array
	$menuList[] = [
		'name' => $menu['menu_name'],
		'img' => $menu['menu_img'],
		'ingredient' => $decoratedMenu->getDescription(),
		'price' => $decoratedMenu->getCost(),
	];
}

// Output the associative array for debugging or further use
print_r($menuList);