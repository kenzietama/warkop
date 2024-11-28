<?php

namespace App\database;
use PDO;
use PDOException;
use RuntimeException;

class Database
{
	private static ?Database $instance = null; // Static property to hold the single instance
	private PDO $connection; // Holds the PDO connection

	private function __construct()
	{
		// Private constructor to prevent direct instantiation
		$host = '127.0.0.1';
		$db = 'warkop';
		$user = 'root';
		$pass = '';
		$charset = 'utf8mb4';

		$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
		$options = [
			PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
			PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
			PDO::ATTR_EMULATE_PREPARES => false,
		];

		try {
			$this->connection = new PDO($dsn, $user, $pass, $options);
		} catch (PDOException $e) {
			throw new RuntimeException('Connection failed: ' . $e->getMessage());
		}
	}

	// Prevent cloning of the instance
	private function __clone()
	{
	}

	// Prevent unserializing of the instance
	public function __wakeup()
	{
		throw new RuntimeException("Cannot unserialize a singleton.");
	}

	// The static method to get the single instance
	public static function getInstance(): Database
	{
		if (self::$instance === null) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	// Method to get the PDO connection
	public function getConnection(): PDO
	{
		return $this->connection;
	}

	public function beginTransaction() {
		$this->connection->beginTransaction();
	}

	public function commit() {
		$this->connection->commit();
	}

	public function rollBack() {
		$this->connection->rollBack();
	}

}

// Usage
//try {
//	$dbInstance = database::getInstance();
//	$connection = $dbInstance->getConnection();
//
//	// Example query
//	$stmt = $connection->query("SELECT * FROM users");
//	while ($row = $stmt->fetch()) {
//		print_r($row);
//	}
//} catch (Exception $e) {
//	echo "Error: " . $e->getMessage();
//}


