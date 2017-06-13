<?php

namespace Johannes\Menu;

use Concerto\std;

/**
 * An entry in the menu. Can be basically anything
 * (an item or a submenu).
 *
 */
abstract class MenuEntry implements \JsonSerializable {
	const SEPARATOR = "separator";
	const ITEM = "item";
	const MENU = "menu";

	protected $type = NULL;
	protected $id = NULL;
	protected $plainText = "";
	protected $url = NULL;
	protected $entries = [];
	protected $enabled;
	protected $class;
	protected $icon = null;

	public function __construct($type, $text = NULL){
		if( !$text ){
			// You must initialise the text for the menu.
			$this->id = uniqid();
			$text = "<entry " . $this->id . ">";
		}
		$this->plainText = $text;
		$this->enabled = TRUE;
		$this->type = $type;
	}

	public function isItem() {
		return( count($this->entries) == 0 );
	}

	public function isEnabled() {
		return $this->enabled;
	}
	
	/**
	 * Return TRUE if the item is a sub-menu.
	 */
	public function isMenu(){
		return $this->type == self::MENU;
	}
	
	/**
	 * The text to diplay (as UT-8 text).
	 * 
	 * @return string the text to display
	 */
	public function getText(){
		return $this->plainText;
	}

	public function getHtmlText(){
		return std::html($this->plainText);
	}
	
	/**
	 * Get the URL for the entry (if exists)
	 * 
	 * @return string the URL or NULL if not an item.
	 */
	public function getUrl(){
		return $this->url;
	}
	
	/**
	 * Get the type of the menu (SEPARATOR, MENU, ITEM).
	 * 
	 * @return string the menu entry type
	 */
	public function getType(){
		return $this->type;
	}

	/**
	 * Returns the icon.
	 * 
	 * @return string
	 */
	public function getIcon(){
		return $this->icon;
	}
	
	
	public function jsonSerialize() {
		return get_object_vars($this);
	}
	
}
