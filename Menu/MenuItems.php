<?php

namespace Parabol\AdminCoreBundle\Menu;

/**
* 
*/
class MenuItems
{
	private $items;

	public function __construct()
	{
		$this->items = [];
	}

	public function addItem($item, $name)
	{
		$this->items[$name] = $item;
	}

	public function getItems()
	{
		return $this->items;
	}
}

