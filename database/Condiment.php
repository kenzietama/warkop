<?php
namespace App\database;
class Condiment extends CondimentDecorator {
	private string $name;
	private int $price;

	public function __construct(MenuComponent $menuComponent, string $name, int $price) {
		parent::__construct($menuComponent);
		$this->name = $name;
		$this->price = $price;
	}

	public function getDescription(): string {
		return $this->menuComponent->getDescription() . ", " . $this->name;
	}

	public function getCost(): int {
		return $this->menuComponent->getCost() + $this->price;
	}
}
