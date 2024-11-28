<!doctype html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport"
		  content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
	<meta http-equiv="X-UA-Compatible" content="ie=edge">
	<title>Document</title>
	<style>
        footer {
            position: fixed;
            bottom: 0;
            left: 0;
            width: 100%;
            z-index: 1000; /* Ensures it stays on top */
        }

        .navbar {
            display: flex;
            justify-content: space-around;
            background-color: #d8a96e;
            font-family: 'Arial', sans-serif;
            width: 100%;
        }

        .navbar a {
            text-align: center;
            color: #333;
            text-decoration: none;
            font-size: 18px;
            font-weight: bold;
        }

        .navbar img {
            width: 60px;
            height: auto;
            margin-bottom: 5px;
        }

        .navbar p {
            margin-top: -5px;
            font-size: 18px;
            color: #333;
        }

	</style>
</head>
<body>
	<footer>
		<div class="navbar">
			<a href="Statistik.php" title="Chart">
				<img src="gambar/chart.png" alt="chart">
				<p>Statistik</p>
			</a>
			<a href="Stok.php" title="Stok">
				<img src="gambar/bag.svg" alt="Stok">
				<p>Stok</p>
			</a>
			<a href="Kasir.php" title="Kasir">
				<img src="gambar/paper.svg" alt="Kasir">
				<p>Kasir</p>
			</a>
		</div>
	</footer>
</body>
</html>
