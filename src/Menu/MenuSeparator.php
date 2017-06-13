<?php

namespace Johannes\Menu;

/**
 * A menu separator
 * 
 * @author wrey
 *
 */
class MenuSeparator extends MenuEntry {

	public function __construct() {
		super(self::SEPARATOR, NULL);
	}

}
