<?php

namespace QMS;

class Renderer {

	protected $engine;
	protected $config;

	public function __construct() {
		$this->engine = new \Mustache_Engine;
		$this->config = new Configuration;
	}

	/**
	 *	Set the configuration. You should use this
	 *	method with care because the configuration
	 *	is intended to be done from "conventions".
	 *
	 *	@param array $config an key/value array containing
	 *		the configuration (can also be a JSON value). 
	 */
	public function setConfigurationClass( $config ){
		$this->config = $config;
	}

	public function renderPage(){
		$page = $_SERVER['REQUEST_URI'];
		$filename = $this->config->getTemplatePath( $page );
		$template = @file_get_contents( $filename );
		if( $template === FALSE ){
			echo $filename;
			$this->render404($page);
			return;
		}
		$html = $this->engine->render( $template, array() );
		echo $html;
	}

	protected function render404( $page ){
		http_response_code(404);
		header( "Content-type: text/html" );
		echo "<h1>Error 404</h1>\n";
		echo "<p>Page <strong>$page</strong> not found.</p>"; 
	}
}

