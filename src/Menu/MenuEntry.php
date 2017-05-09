<?php

namespace Johannes\Menu;

/**
 * An entry in the menu. Can be basically anything
 * (an item or a submenu).
 *
 */
abstract class MenuEntry {
	const MENU_SEPARATOR = "separator";
	const MENU_ITEM = "item";
	const MENU_MENU = "menu";

	protected $type = NULL;
	protected $id = NULL;
	protected $text = "";
	protected $url = NULL;
	protected $entries = [];
	protected $enabled;
	protected $class;

	public function __construct($type, $text = NULL){
		if( !$text ){
			// You must initialise the text for the menu.
			$this->id = uniqid();
			$text = "<entry " . $this->id . ">";
		}
		$this->text = $text;
		$this->enabled = TRUE;
		$this->type = $type;
	}

	public function isItem() {
		return( count($this->entries) == 0 );
	}

	public function isEnabled() {
		return $this->enabled;
	}
}
