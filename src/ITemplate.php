<?php

namespace Johannes;

/**
 * The interface for a template. Basically same as the
 * ITheme but nor mandatory. Can handle more stuff than
 * the theme itself.
 * 
 * @author wrey
 *
 */
interface ITemplate {
	
	/**
	 * Initialize the theme.
	 *
	 * @param CMSEngine $cms the engine
	 */
	public function init( $cms );
}
