<?php
namespace App\database;
class BaseIngredient implements MenuComponent {
	private string $name;
	private int $price;

	public function __construct(string $name, int $price) {
		$this->name = $name;
		$this->price = $price;
	}

	public function getDescription(): string {
		return $this->name;
	}

	public function getCost(): int {
		return $this->price;
	}
}
