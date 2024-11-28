<?php
require_once "init/init.php";

use App\database\MenuService;

$menuService = new MenuService();

// Fetch data for sales statistics and stock levels
$salesStatistics = $menuService->getSalesStatistics();
$stockLevels = $menuService->getStockLevels();
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Statistik Coffee Shop</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link rel="stylesheet" href="css/Statistik.css">
</head>
<body>

    <!-- Judul Halaman -->
    <div class="title">
        Statistik Penjualan & Ketersediaan Stok
    </div>

    <div class="container">
        <!-- Chart Pie untuk Statistik Penjualan dan Ketersediaan Stok dalam 1 baris -->
        <div class="chart-row">
            <div class="chart-container">
                <h3>Statistik Penjualan Menu</h3>
                <canvas id="salesChart"></canvas>
            </div>
            <div class="chart-container">
                <h3>Ketersediaan Stok</h3>
                <canvas id="stockChart"></canvas>
            </div>
        </div>
    </div>

    <script>
        // Data Statistik Penjualan (contoh data)

        const salesData = {
            labels: <?= json_encode(array_column($salesStatistics, 'menu_name')) ?>,
            datasets: [{
                label: 'Penjualan Menu',
                data: <?= json_encode(array_column($salesStatistics, 'total_sold')) ?>,
                backgroundColor: ["#FF6384", "#36A2EB", "#FFCE56", "#4BC0C0", "#9966FF", "#FF9F40"]
            }]
        };

        // Data Ketersediaan Stok
        const stockData = {
            labels: <?= json_encode(array_column($stockLevels, 'ingredient_name')) ?>,
            datasets: [{
                label: 'Ketersediaan Stok',
                data: <?= json_encode(array_column($stockLevels, 'remaining_qty')) ?>,
                backgroundColor: ["#FF6384", "#36A2EB", "#FFCE56", "#4BC0C0", "#9966FF", "#FF9F40"]
            }]
        };

        // const salesData = {
        //     labels: ["Kopi Arabica", "Gula Aren", "Susu", "Ice Cream", "Donat", "Croissant"],
        //     datasets: [{
        //         label: 'Penjualan Menu',
        //         data: [120, 90, 75, 100, 50, 60], // contoh data penjualan
        //         backgroundColor: ["#FF6384", "#36A2EB", "#FFCE56", "#4BC0C0", "#9966FF", "#FF9F40"]
        //     }]
        // };
        //
        // // Data Ketersediaan Stok
        // const stockData = {
        //     labels: ["Air Putih", "Gula Pasir", "Kopi Arabica", "Gula Aren", "Nasi", "Susu", "Donat", "Ice Cream", "Croissant", "Nugget", "Kentang", "Sosis"],
        //     datasets: [{
        //         label: 'Ketersediaan Stok',
        //         data: [40, 40, 33, 33, 23, 23, 54, 54, 23, 23, 54, 54], // contoh data stok
        //         backgroundColor: ["#FF6384", "#36A2EB", "#FFCE56", "#4BC0C0", "#9966FF", "#FF9F40", "#FF6384", "#36A2EB", "#FFCE56", "#4BC0C0", "#9966FF", "#FF9F40"]
        //     }]
        // };

        // Konfigurasi Chart Penjualan
        // const salesConfig = {
        //     type: 'pie',
        //     data: salesData,
        //     options: {
        //         responsive: true,
        //         plugins: {
        //             legend: {
        //                 position: 'bottom',  // Menempatkan legenda di bawah chart
        //             },
        //             title: {
        //                 display: true,
        //                 text: 'Statistik Penjualan Menu'
        //             }
        //         }
        //     }
        // };
        //
        // // Konfigurasi Chart Stok
        // const stockConfig = {
        //     type: 'pie',
        //     data: stockData,
        //     options: {
        //         responsive: true,
        //         plugins: {
        //             legend: {
        //                 position: 'bottom',  // Menempatkan legenda di bawah chart
        //             },
        //             title: {
        //                 display: true,
        //                 text: 'Ketersediaan Stok'
        //             }
        //         }
        //     }
        // };

        const salesConfig = {
            type: 'pie',
            data: salesData,
            options: {
                responsive: true,
                plugins: {
                    legend: { position: 'bottom' },
                    title: { display: true, text: 'Statistik Penjualan Menu' }
                }
            }
        };

        const stockConfig = {
            type: 'pie',
            data: stockData,
            options: {
                responsive: true,
                plugins: {
                    legend: { position: 'bottom' },
                    title: { display: true, text: 'Ketersediaan Stok' }
                }
            }
        };

        // Render Chart Penjualan
        const salesChart = new Chart(
            document.getElementById('salesChart'),
            salesConfig
        );

        // Render Chart Stok
        const stockChart = new Chart(
            document.getElementById('stockChart'),
            stockConfig
        );
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>

<?php include "footer.php"?>

</html>
