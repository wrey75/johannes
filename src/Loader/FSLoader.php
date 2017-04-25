<?php

namespace Johannes\Loader;

use Johannes\CMSException;
use function Monolog\Handler\error_log;

/**
 * File loader for Mustache. Based on the original one
 * provided by Mustache.
 * 
 * @author wrey
 *
 */
class FSLoader implements \Mustache_Loader {
	
	private $baseDir;
	private $suffixes = []; // extension to use
	
	/**
	 * Store the templates in memory.
	 * 
	 * @var array
	 */
	private $templates = array();
	
	public function __construct($baseDir, array $options = array())
	{
		$suffix = (@$options['ext'] ?: ".html");
		
		$lang = strtolower(@$options['lang']) ?: "";
		if( strlen($lang) == 5 ){
			// With the language and the country
			$this->$suffixes[] = "." . substr($lang,0,2) . "_" . strtoupper(substr($lang,3,2)) . $suffix; 
			$this->$suffixes[] = "." . substr($lang,0,2) . $suffix;
		}
		else if( strlen($lang) == 2 ){
			// With only the language (should not be the case)
			$this->$suffixes[] = "." . $lang . $suffix;
		}
		$this->suffixes[] = $suffix;
		$this->baseDir = $baseDir;		
	}
	
	/**
	 * Load the file using the template caching.
	 * 
	 * @param string $ficname the filename (without the base directory).
	 * @return string the text of the file or null.
	 */
	public function loadFile($ficname){
		$fullPath = $this->baseDir . "/" . $ficname;
		if( !file_exists($fullPath) ){
			return NULL;
		}
		$data = file_get_contents( $fullPath );
		return $data;
	}
	
	/**
	 * Load a Template by name.
	 *
	 *     $loader = new FSLoader(dirname(__FILE__).'/views');
	 *     $loader->load('admin/dashboard'); // loads "./views/admin/dashboard.mustache";
	 *
	 * @param string $name
	 *
	 * @return string Mustache Template source
	 */
	public function load($name)
	{
		$data = @$this->templates[$name];
		if( $data ) return $data;
		
		foreach( $this->suffixes as $suffix ){
			$loaded = $this->loadFile($name . $suffix );
			if( $loaded ){
				$this->templates[$name] = $loaded;
				return $loaded;
			}
		}
		
		throw new CMSException("Can not load template '$name'.");
	}
	
}