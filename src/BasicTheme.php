<?php

namespace Johannes;

class BasicTheme implements ITheme {
	
	/** @var CMSEngine */
	protected $engine;
	
	
	public function init( $cms ){
		$this->engine = $cms;
	}
}