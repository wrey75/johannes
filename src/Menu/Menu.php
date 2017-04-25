<?php

namespace Johannes\Menu;

class Menu implements JsonSerialize {
	
	public $entries;
	
	public function __construct( ){
		$this->text = $text;
		$this->entries = $entries;
	}

	/**
	 *	Add a sub menu.
	 *
	 * @param string $text the text for the entry.
	 * @param Menu $submenu a sub menu.
	 *
	 */	
	public function addSubMenu( $text, $submenu, $options = [] ){
		if( !is_array($submenu) ){
			$submenu = $submenu->jsonSerialize();
		}
		$submenu['text'] = $text;
		$submenu['type'] = Menu::class;
		$submenu['opts'] = $options;
		$this->entries[] = $submenu;
	}

	public function addMenuItem( $text, $url, $options = [] ){
		$submenu = [];
		$this->entries[] = $submenu;
	}
	
	public function jsonSerialize() {
		return $entries;
	}
	
}
