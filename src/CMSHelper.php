<?php

namespace Johannes;

use Concerto\std;

/**
 * This class is an helper for rendering parts. You can use
 * the rendering provided by this class with the "cms" prefix.
 * Typically, the javascripts scripts and CSS will be rendered
 * with this class.
 * 
 * @author wrey
 *
 */
class CMSHelper {
	
	/**
	 * The CMS engine.
	 * 
	 * @var CMSEngine
	 */
	private $engine;
	
	public function __construct( CMSEngine $engine ){
		$this->engine = $engine;
	}
	
	/**
	 * Returns the links.
	 */
	public function css_links(){
		$ret = "";
		foreach( $this->engine->getFiles(CMSEngine::CSS_FILE) as $info){
			// var_dump($info);
			$ret .= std::tagln("link", [ 'rel' => 'stylesheet', 'href' => $info['url'] ] );
		}
		return $ret;
	}
	
}