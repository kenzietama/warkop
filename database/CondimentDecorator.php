<?php
namespace App\database;
abstract class CondimentDecorator implements MenuComponent {
	protected MenuComponent $menuComponent;

	public function __construct(MenuComponent $menuComponent) {
		$this->menuComponent = $menuComponent;
	}

	abstract public function getDescription(): string;
	abstract public function getCost(): int;
}
