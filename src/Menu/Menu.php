<?php

namespace Johannes\Menu;

class Menu extends MenuEntry {
	
	public $entries;
	
	public function __construct( $text ){
		parent::__construct(self::MENU, $text);
		$this->entries = [];
	}

	/**
	 *	Add a sub menu.
	 *
	 * @param string $text the text for the entry.
	 * @param Menu $submenu a sub menu.
	 *
	 */	
	public function addMenuEntry( $entry ){
		$this->entries[] = $entry;
		return $this;
	}

	/**
	 * Add an item. This is a hortcut to add simple item entries.
	 * 
	 * @param string $url the URL.
	 * @param string $text the plain text
	 * @return \Johannes\Menu\Menu returns the menu itself to chain.
	 */
	public function addItem( $url, $text){
		$this->entries[] = new MenuItem($url, $text);
		return $this;
	}
	
	/**
	 * Get the items of this menu.
	 * 
	 * @return array[MenuEntry] an array of menu entries.
	 */
	public function items(){
		return $this->entries;
	}

}
