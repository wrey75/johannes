<?php

namespace Johannes\Menu;

class MenuItem extends MenuEntry {
	
	public function __construct($url, $text, $icon = null) {
		parent::__construct(self::ITEM, $text);
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
