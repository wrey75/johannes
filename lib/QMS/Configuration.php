<?php

namespace QMS;

class Configuration {

	const PRODUCTION = "prod";
	const DEBUG = "debug";

	/** @var array the configuration used internally */
	private $data;

	public function __construct() {
		$this->data = [
			'mode' => 'PROD',
			'template_dir' => null
		];
	}

	public function load( $config ){
		$conf = Utils::toArray($config);

	}

	/**
	 *	Get the ROOT directory for all the user's files
	 *	used by QucikMS. By default, this the "base"
	 *	directory next to the directory serving the
	 *	web files.
	 *
	 */
	public function getRootDir(){
		return $_SERVER['DOCUMENT_ROOT'] . "/../base";
	}

	public function getTemplatePath( $page ){
		$path = $this->getRootDir();
		if( $page[0] != '/' ){
			$path .= "/";
		}
		$path .= $page;
		$path .= ".mustache";
		return $path; 
	}
}

