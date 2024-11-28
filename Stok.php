<?php
require_once "init/init.php";

use App\database\MenuService;

// Check if it's a POST request
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	$menuService = new MenuService();

	// Decode the JSON data sent via AJAX
	$stocks = json_decode($_POST['stocks'], true);

	// Call the updateStock method
	$success = $menuService->updateStock($stocks);

	// Send a JSON response back to the client
	header('Content-Type: application/json');
	echo json_encode(['success' => $success]);
	exit;
}

// Fetch all ingredients for GET requests
$menuService = new MenuService();
$items = $menuService->getIngredients(); // Fetch all ingredients
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Halaman Stok Coffee Shop</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/Stok.css">
</head>
<body>

<!-- Judul Halaman -->
<div class="title">
    Ketersediaan Stok
</div>

<div class="main container">
    <div class="stock-list">
		<?php
		foreach ($items as $item) {
			echo '<div class="stock-item">';
			echo '<div class="item-name">' . htmlspecialchars($item["name"]) . " - Rp" . htmlspecialchars($item["price"]) . '</div>';
			echo '<div class="stock-control">';
			echo '<button class="stock-btn" onclick="changeStock(' . htmlspecialchars($item["id"]) . ', 1)">+</button>';
			echo '<span id="stock-amount-' . htmlspecialchars($item["id"]) . '" class="stock-amount" data-id="' . htmlspecialchars($item["id"]) . '">' . htmlspecialchars($item["qty"]) . '</span>';
			echo '<button class="stock-btn" onclick="changeStock(' . htmlspecialchars($item["id"]) . ', -1)">-</button>';
			echo '</div>';
			echo '</div>';
		}
		?>
    </div>
    <!-- Save Changes Button -->
    <div class="save-button-container mt-3">
        <button class="btn btn-success" onclick="saveAllChanges()">Save Changes</button>
    </div>
</div>

<!-- Script JavaScript untuk logika pesanan -->
<script>
    // Increase or decrease stock locally
    function changeStock(id, delta) {
        const stockAmount = document.getElementById(`stock-amount-${id}`);
        let currentStock = parseInt(stockAmount.innerText);
        let newStock = currentStock + delta;

        if (newStock >= 0) { // Prevent negative stock
            stockAmount.innerText = newStock;
        }
    }

    // Save all stock changes to the server
    function saveAllChanges() {
        const stockElements = document.querySelectorAll('.stock-amount');
        const stocks = [];

        stockElements.forEach(element => {
            const id = element.getAttribute('data-id');
            const qty = parseInt(element.innerText);

            stocks.push({ id: parseInt(id), qty });
        });

        // Send data via AJAX
        const xhr = new XMLHttpRequest();
        xhr.open("POST", "", true); // Current PHP file
        xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

        xhr.onload = function () {
            if (xhr.status === 200) {
                const response = JSON.parse(xhr.responseText);
                if (response.success) {
                    alert("Stock updated successfully!");
                } else {
                    alert("Error: " + response.error);
                }
            } else {
                alert("Error updating stock.");
            }
        };

        xhr.send(`stocks=${JSON.stringify(stocks)}`);
    }
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>

</body>

<?php include "footer.php" ?>

</html>
