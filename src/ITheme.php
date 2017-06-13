<?php

namespace Johannes;

/**
 * The interface for a theme. Each theme must implements
 * all the methods decalred in this interface.
 * 
 * @author wrey
 *
 */
interface ITheme {
	
	/**
	 * Initialize the theme.
	 *
	 * @param CMSEngine $cms
	 */
	public function init( $cms );
}