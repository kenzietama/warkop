<?php
namespace App\database;
interface MenuComponent {
	public function getDescription(): string;
	public function getCost(): int;
}
