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
		'ingredients' => $decoratedMenu->getDescription(),
		'price' => $decoratedMenu->getCost(),
	];
}

// Output the associative array for debugging or further use
//print_r($menuList);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Halaman Kasir Coffee Shop</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/Kasir.css">
</head>
<body>

<!-- Judul Halaman -->
<div class="title">
    Ketersediaan Menu
</div>

<!-- Kontainer Menu -->
<div class="menu-container">
    <!-- Menu Items -->
    <?php foreach($menuList as $menu) : ?>
    <div class="menu-item">
        <img src="gambar/<?= $menu['img'] ?>" alt="<?= $menu['name'] ?>>">
        <div class="menu-info">
            <h2><?= $menu['name'] ?></h2>
                <p>
                    <?= $menu['ingredients'] ?>
<!--                    --><?php //foreach ($menu['ingredients'] as $row) : ?>
<!--                    --><?php //= $row . "<br>" ?>
<!--                    --><?php //endforeach; ?>
                </p>
            <p class="price"><?= "Rp" . $menu['price'] ?></p>
        </div>
        <button class="add-btn" onclick="addToOrder('<?= $menu['name'] ?>', <?= $menu['price'] ?>)">+</button>
    </div>
    <?php endforeach; ?>

<!--    <div class="menu-item">-->
<!--        <img src="gambar/KopiGulaAren.png" alt="Kopi Gula Aren">-->
<!--        <div class="menu-info">-->
<!--            <h2>Kopi Gula Aren</h2>-->
<!--            <p>• Kopi<br>• Gula Aren<br>• Susu</p>-->
<!--            <p class="price">20.000</p>-->
<!--        </div>-->
<!--        <button class="add-btn" onclick="addToOrder('Kopi Gula Aren', 20000)">+</button>-->
<!--    </div>-->
<!---->
<!--    <div class="menu-item">-->
<!--        <img src="gambar/NasgorNugget.png" alt="Nasi Goreng Nugget">-->
<!--        <div class="menu-info">-->
<!--            <h2>Nasi Goreng Nugget</h2>-->
<!--            <p>• Nasi<br>• Nugget</p>-->
<!--            <p class="price">15.000</p>-->
<!--        </div>-->
<!--        <button class="add-btn" onclick="addToOrder('Nasi Goreng Nugget', 15000)">+</button>-->
<!--    </div>-->
<!---->
<!--    <div class="menu-item">-->
<!--        <img src="gambar/Affogato.png" alt="Affogato">-->
<!--        <div class="menu-info">-->
<!--            <h2>Affogato</h2>-->
<!--            <p>• Kopi<br>• Gula Pasir<br>• Ice Cream</p>-->
<!--            <p class="price">30.000</p>-->
<!--        </div>-->
<!--        <button class="add-btn" onclick="addToOrder('Affogato', 30000)">+</button>-->
<!--    </div>-->
<!---->
<!--    <div class="menu-item">-->
<!--        <img src="gambar/NasgorAyam.jpeg" alt="Nasi Goreng Ayam">-->
<!--        <div class="menu-info">-->
<!--            <h2>Nasi Goreng Ayam</h2>-->
<!--            <p>• Nasi<br>• Ayam</p>-->
<!--            <p class="price">20.000</p>-->
<!--        </div>-->
<!--        <button class="add-btn" onclick="addToOrder('Nasi Goreng Ayam', 20000)">+</button>-->
<!--    </div>-->
<!---->
<!--    <div class="menu-item">-->
<!--        <img src="gambar/Latte.jpg" alt="Latte">-->
<!--        <div class="menu-info">-->
<!--            <h2>Latte</h2>-->
<!--            <p>• Kopi<br>• Susu</p>-->
<!--            <p class="price">15.000</p>-->
<!--        </div>-->
<!--        <button class="add-btn" onclick="addToOrder('Latte', 15000)">+</button>-->
<!--    </div>-->
<!---->
<!--    <div class="menu-item">-->
<!--        <img src="gambar/NasgorSpesial.jpg" alt="Nasi Goreng Spesial">-->
<!--        <div class="menu-info">-->
<!--            <h2>Nasi Goreng Spesial</h2>-->
<!--            <p>• Nasi<br>• Ayam<br>• Sosis<br>• Nugget</p>-->
<!--            <p class="price">30.000</p>-->
<!--        </div>-->
<!--        <button class="add-btn" onclick="addToOrder('Nasi Goreng Spesial', 30000)">+</button>-->
<!--    </div>-->
<!---->
<!--    <div class="menu-item">-->
<!--        <img src="gambar/Mocha.jpg" alt="Mocha">-->
<!--        <div class="menu-info">-->
<!--            <h2>Mocha</h2>-->
<!--            <p>• Kopi<br>• Coklat<br>• Susu</p>-->
<!--            <p class="price">25.000</p>-->
<!--        </div>-->
<!--        <button class="add-btn" onclick="addToOrder('Mocha', 25000)">+</button>-->
<!--    </div>-->
</div>

<!-- Sidebar untuk daftar pesanan -->
<div class="sidebar">
    <h2>Daftar Pesanan</h2>
    <ul id="orderList" class="list-unstyled"></ul>
    <div class="total-price">
        Total Harga: Rp <span id="totalPrice">0</span>
    </div>
    <button id="payButton" class="btn btn-success mt-4" onclick="pay()">
        Bayar
    </button>
</div>

<!-- Script JavaScript untuk logika pesanan -->
<script>
    let totalPrice = 0;

    function addToOrder(itemName, itemPrice) {
        const orderList = document.getElementById("orderList");

        const listItem = document.createElement("li");
        listItem.classList.add("order-item");
        listItem.innerHTML = `${itemName} - Rp ${itemPrice.toLocaleString()}
                                  <button class="remove-btn" onclick="removeFromOrder(this, ${itemPrice})">&times;</button>`;
        orderList.appendChild(listItem);

        totalPrice += itemPrice;
        updateTotalPrice();
    }

    function removeFromOrder(button, itemPrice) {
        const listItem = button.parentElement;
        listItem.remove();
        totalPrice -= itemPrice;
        updateTotalPrice();
    }

    function updateTotalPrice() {
        document.getElementById("totalPrice").textContent = totalPrice.toLocaleString();
    }

    function pay() {
        const orderList = document.querySelectorAll('.order-item');
        if (orderList.length === 0) {
            alert("Tidak ada pesanan untuk dibayar.");
            return;
        }

        const orders = [];
        orderList.forEach(item => {
            const details = item.textContent.trim().split(' - Rp');
            const name = details[0].trim();
            const price = parseInt(details[1].replace(/\D/g, ''));
            const qty = 1; // Assuming 1 qty per item; modify as needed
            orders.push({ name, price, qty });
        });

        const orderData = {
            totalPrice,
            orders,
        };

        const xhr = new XMLHttpRequest();
        xhr.open("POST", "save_order.php", true);
        xhr.setRequestHeader("Content-Type", "application/json");

        xhr.onload = function () {
            if (xhr.status === 200) {
                const response = JSON.parse(xhr.responseText);
                if (response.success) {
                    alert("Pesanan berhasil disimpan!");
                    resetOrder();
                } else {
                    alert("Terjadi kesalahan: " + response.message);
                }
            } else {
                alert("Error saat menyimpan pesanan.");
            }
        };

        xhr.send(JSON.stringify(orderData));
    }


    function resetOrder() {
        document.getElementById("orderList").innerHTML = '';
        totalPrice = 0;
        updateTotalPrice();
    }
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>

</body>

<?php include "footer.php"?>

</html>
