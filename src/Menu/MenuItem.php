<?php

namespace Johannes\Menu;

class MenuItem extends MenuEntry {
	
	public function __construct($text, $url, $icon = null) {
		super(self::MENU_ITEM, $text);
		$this->url = $url;
		$this->icon = $icon;
	}
	
	public function jsonSerialize (){
		return [
				'text' => $this->text,
				'icon' => $this->icon,
				'url' => $this->url
		];
	}
}
