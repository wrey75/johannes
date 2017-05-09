<?php

namespace Johannes;

use Concerto\std;
use phpDocumentor\Reflection\Types\This;
use Johannes\Loader\FSLoader;

/**
 * The CMS engine. This class is in charge of the rendering, the log, the database
 * access and many other stuff related to the CMS.
 * 
 * @author wrey75
 *
 */
class CMSEngine {
	
	const MENU_ENTRIES = "menus";
	const CSS_FILE = "css";
	const JS_FILE = "js";
	const SITE_CONTACT_EMAIL = "site.contact.email";
		
	/**
	 * The file paths. Used to find the requested objects.
	 * Works exactly like the path under the operating
	 * systems. The path of the CMS itself is automatically
	 * appended.
	 *  
	 * @var string[]
	 */
	public $path = [];
	
	/** 
	 * The template selected.
	 * 
	 * @var string
	 */
	protected $templateName;
	
	protected $remote = FALSE;
	
	/**
	 * The configuration for the CMS.
	 * 
	 * @var array
	 */
	protected $config;
	
	protected $js_code = "";
	
// 	/**
// 	 * Theme directory. Where the "themes" directory is.
// 	 * 
// 	 * @var string
// 	 */
// 	protected $themeDirectory;
	
	/**
	 * The ROOT folder for themes, plugins, etc. 
	 * @var S
	 */
	protected $ROOT_DIR;
	
	/**
	 * The theme directory.
	 * @var string
	 */
	protected $THEME_DIR;
	
    /**
     *  The data model. This array concentrate all
     *  the information needed to display the stuff.
     *
     *  The model is a grape of data including
     *  objects. The resolution is made by Mustache.
     */
    protected $model = null;
    
    /**
     * The theme selected.
     * 
     * @var string
     */
    protected $theme = "basic";

//     /**
//      *  Mustache itself.
//      *  
//      *  @var \Mustache_Engine $m
//      */
//     protected $m;
	
	public function __construct($config = []) {
		if( is_string($config) ){
			// Use a JSON file. You should NOT use this method for
			// security reasons.
			$data = file_get_contents($config);
			$config = json_decode($data);
		}
// 		$defaultConfig = [
// 				'DOCUMENT_ROOT' => $_SERVER['DOCUMENT_ROOT'],
// 				'theme' => "tiny",
// 				'slim' => []
// 		];
// 		$defaultConfig['CMS_ROOT'] = $defaultConfig['DOCUMENT_ROOT'] . "/cms";
// 		$defaultConfig['THEME_ROOT'] = $defaultConfig['CMS_ROOT'] . "/themes";
// 		$this->config = array_merge($defaultConfig, $config);

		$this->ROOT_DIR = (@$config['dir.root'] ?: $_SERVER['DOCUMENT_ROOT'] . "/cms");
		$this->THEME_DIR = (@$config['dir.themes'] ?: $this->ROOT_DIR . "/themes");
		$this->theme = (@$config['theme'] ?: "basic");
		$this->push(self::SITE_CONTACT_EMAIL, @$_SERVER["SERVER_ADMIN"]);
	}

	/**
	 * Select the theme to use. The theme is a directory
	 * stored in the theme directory.
	 * 
	 * @param string $theme the theme.
	 */
	public function setTheme($theme) {
		if( !$this->getThemeDirectory($theme)){
			throw new CMSException("Theme '$theme' can not be found.");
		}
		$this->theme = $theme;
	}
		
    /**
     * Add an entry to the model.
     *
     * @param string $key the key. Can be "." separated.
     * @param any $object the object to add.
     *
     */
    public function push($key, $value){
    	if (is_null($key)) {
    		return $array = $value;
    	}
    	
    	$keys = explode('.', $key);
    	
    	$array = &$this->model;
    	while (count($keys) > 1) {
    		$key = array_shift($keys);
    	
    		// If the key doesn't exist at this depth, we will just create an empty array
    		// to hold the next value, allowing us to create the arrays to hold final
    		// values at the correct depth. Then we'll keep digging into the array.
    		if (! isset($array[$key]) || ! is_array($array[$key])) {
    			$array[$key] = [];
    		}
    	
    		$array = &$array[$key];
    	}
    	
    	$array[array_shift($keys)] = $value;
    	return $this;
    }

    /**
     * Scan the array passed to add the "idx" value which is
     * the index.
     * 
     * @param string $key
     * @param array $value
     */
    public function pushWithIndex($key, $value){
    	$newArray = array();
    	$nb = 0;
    	foreach ($value as $k => $v){
    		$nb++;
    		$newArray[] = array_merge($v, ['idx' => $nb ]);
    	}
    	$this->push($key, $newArray);
    }
    	
    /**
     * The variable you push will be rendered remotely
     * if the value is a callback AND the remote rendering
     * is active. 
     * 
     * @param string $key the key
     * @param callable $value the callback for generating the data.
     */
    public function pushRemote($key, $value){
    	if( !is_callable($value) ){
    		// The rendering is not ameded.
    		$this->push($key, $value);
    	}
    	else {
    		$this->push($key, new RemoteRenderer($value) );
    	}
    }
    
    public function getServerName() {
    	// Protocol
    	$secure_connection = false;
    	if(isset($_SERVER['HTTPS'])) {
		    if ($_SERVER['HTTPS'] == "on") {
		        $secure_connection = true;
		    }
		}
    	if( $secure_connection ){
    		$protocol = "https:";
    		$defaultPort = 443;
    	}
    	else {
    		$protocol = "http:";
    		$defaultPort = 80;
    	}
    	
    	// Port
    	$port = $_SERVER['SERVER_PORT'];
    	$portNumber = "";
    	if($port && (intval($port) != intval($defaultPort) )){
    		$portNumber = ":$port";
    	}
    	
    	// Server name
    	$name = $_SERVER["SERVER_NAME"];
    	return "$protocol//$name$portNumber";
    }
    
	/**
	 * Initialize the CMS.
	 */
	public function init() {
		$this->path = [ __DIR__ . "/.." ];
		// $conf = isset($this->config["slim"]) ? $this->config["slim"] : [];
		// $this->slim = new \Slim\App($conf);

		$mustacheConf = isset($this->config["mustache"]) ? $this->config["mustache"] : [];
		
		$this->model = [
				'_' => [], // translations
				'head' => [],
				'files' => [
						self::CSS_FILE => [],
						self::JS_FILE => [],
						],
				'i18n' => [],
				'system' => [
						"ie8-compatibility" => function() {
								return file_get_contents( __DIR__ . "/scripts/ie8-compatibility.html" );
								},
						"version" => function() {
								return $this->getVersion();
								}
						],
				'page' => [
					'url' => $_SERVER['REQUEST_URI'],
					'server' => $this->getServerName(),
					'now' => time(),
				]
		];
		
// 		$mustacheConf = [
// 				// 'loader' => new \Mustache_Loader_FilesystemLoader($this->config['THEME_ROOT'], ['extension' => '.html'])
// 		];
// 		$this->m = new \Mustache_Engine($mustacheConf);
	}

	/**
	 * This function will select the template to use. This method must be called 
	 * after the theme has been selected.
	 * 
	 * @param string $template the template to use. Must be part of the 
	 * theme.
	 * 
	 */
	public function useTemplate( $template )
	{
		$this->templateName = $template;
	}
	
// 	/**
// 	 * Add a folder into the path. You should add your current
// 	 * directory as a minimal requirement. The folders must
// 	 * be added in the reverse order of priority (those with low
// 	 * priority first).
// 	 * 
// 	 * @param string $folder the absolute folder path. 
// 	 */
// 	public function addPath( $folder ){
// 		if( $folder[0] != "/" ){
// 			$folder = $DOCUMENT_ROOT . "/" . $folder;
// 		}
// 		array_unshift($this->path, $folder);
// 	}
	
	/**
	 * Set the HTML title.
	 * 
	 * @param string $title the title of the page.
	 * @deprecated 
	 */
	public function setPageTitle( $title ){
		// if($title) $this->push("page.title", $title);
		// echo "** "; print_r( $this->model["page"] ); die;
 		if( !isset($this->model["title_page_html"]) ){
 			$this->push("title_page_html", $title);
 			if( !@$this->model['page']['title'] ) $this->push("page.title", $title);
 		}
	}
	
	public function setTitle( $title ){
		// echo "** "; print_r( $this->model["page"] ); die;
		if( !@$this->model['page']['title'] ) $this->push("page.title", $title);
		// echo "** "; print_r( $this->model["page"] ); die;
		if( !isset($this->model["title_page_html"]) ){
			$this->push("title_page_html", $title);
		}
	}

	/**
	 * Set the header and the subheader of the page. If not set,
	 * the title is copied from the title of the page.
	 * 
	 * @param string $title the title for the &lt;H1%gt; tag.
	 * @param string $subtitle the subtitle (if exists).
	 * @deprecated 
	 */
	public function setPageHeader( $title, $subtitle = "" ){
		$this->push("title_page_html", $title);
		if( !@$this->model['page']['title'] ) $this->push("page.title", $title);
		$this->push("subtitle_page", $subtitle);
		// echo "XX "; print_r( $this->model["page"] ); die;
	}
	
	
// 	/**
// 	 * Scan all the path to find the disk path for
// 	 * the requested object.
// 	 * 
// 	 * @return the absolute path or NULL if not 
// 	 * found.
// 	 */
// 	public function findInPath( $file ){
// 		$dir_list = [];
// 		if( $file[0] != '/') $file = "/$file";
// 		foreach( $this->path as $dir ){
// 			$path = $dir . $file;
// 			if( file_exists($path) ){
// 				return $path;
// 			}
// 			$dir_list[] = $path;
// 		}
// 		throw new CMSException("'$file' not found in:\n" . implode(",\n", $dir_list) . ".\n Please check.\n");
// 		return NULL;
// 	}

	/**
	 * Returns the directory for the specified theme.
	 * 
	 * @param string $theme the name of the theme.
	 * @throws CMSException if case of an exception.
	 * @return string the directory.
	 */
 	protected function getThemeDirectory($theme) {
 		$dir = $this->THEME_DIR . "/$theme";
 		if(!is_dir($dir) ){
 			throw new CMSException("No directory '$theme' for this theme. Please check the configuration.");
 		}
 		if(!file_exists( "$dir/package.json" )){
 			throw new CMSException("No package provided for theme '$theme'.");
 		}
 		return $dir;
 	}
	
	/**
	 * Load the theme.
	 * 
	 * @param string $theme the theme.
	 */
	protected function loadTheme( $theme ){
		$dir = $this->getThemeDirectory($theme);
		
		include "$dir/index.php";
		
		$class_name = "\\" . std::capitalizeFirst($theme) . "Theme";
		if( !class_exists( $class_name ) ){
			throw new CMSException("Class '$class_name' not loaded.");
		}
	
		// Add the theme class.	
		$themeObj = new $class_name;
		if( !($themeObj instanceof ITheme) ){
			throw new CMSException("Class '$class_name' does NOT implement " . ITheme::class);
		}
		$themeObj->init($this);
		$this->push('theme', $themeObj);

		$fic = "$dir/" . $this->templateName . ".php";
		if( file_exists($fic) ){
			include "$fic";
			$class_name = std::capitalizeFirst($this->templateName) . "Template";
			if( preg_match("/^[0-9]/", $class_name) ) $class_name = "_" . $class_name;
			$class_name = "\\" . $class_name;
			if( !class_exists( $class_name ) ){
				throw new CMSException("Class '$class_name' not loaded for template '$this->templateName'.");
			}
			$tmplObj = new $class_name;
			$tmplObj->init($this);
			$this->push('template', $tmplObj);
		}
		
		return $themeObj;
	}
	
	/**
	 * Set the available languages which can be used in the
	 * application.
	 * 
	 * @param array $available array of languages.
	 * @param string $current current available
	 */
	public function setLanguages( $available = [], $current = NULL){
		$found = FALSE;
		foreach( $available as $infos ){
			if( $infos['code'] == $current ){
				$this->model['i18n']['current'] = $current;
				$found = TRUE;
			}
			else {
				$this->model['i18n']['available'][] = $infos;
			}
		}
		if( !$found ){
			$this->model['i18n']['current'] = array_shift($this->model['i18n']['available'])['code'];
		}
		
	}
	
	/**
	 * Get the server root.
	 * 
	 * @return the server root.
	 * 
	 */
	protected function getServerRoot() {
		$protocol = (@$_SERVER['HTTPS'] == 'on' ? "https" : "http");
		$server = (@$_SERVER['SERVER_NAME'] ?: gethostname());
		$port = (@$_SERVER['SERVER_PORT'] ?: "80");
		$root = $protocol . "//" . $server;
		if( !(($protocol == "http" && $port == 80) || ($protocol == "https" && $port == 443)) ){
			$root .= ":$port";
		}
		return $root;
	}


		
	/**
	 * Run the CMS. This method will render the page (or
	 * redirect to another page depending the behaviour).
	 * 
	 */
	public function run(){
		$this->model['cms'] = new CMSHelper($this); 
		$conf = [
				'loader' => new FSLoader($this->getThemeDirectory($this->theme), ['ext'=>'.html'] ),
				'partials_loader' => new FSLoader($this->getThemeDirectory($this->theme) . "/views", ['ext'=>'.html'] ),
				'helpers' => [
						'urlencode' => function( $text ){ return urlencode($text); },
						'br' => function( $text ){ return str_replace("\n", "", std::html($text)); },
						'stream' => function( $text ){ return str_replace("\n", "", $text); },
						'trim' => function( $text ){ return trim(str_replace("\n", "", $text)); },
						'crypt' => [
								'base64' => function($text) { return base64_encode($text); },
								'sha1' => function($text) { return sha1($text); },
								'md5' => function($text) { return md5($text); },
								'password' => function($text) { return password_hash($text, PASSWORD_DEFAULT); },
								'hide' => function($text) {
									$len = std::len($text);
									$ret = "";
									while( $i++ < $len ) $ret .= "*";
									return $ret;
									},
								],
						'case' => [
								'upper' => function( $text ){ return std::upper($text); },
								'lower' => function( $text ){ return std::lower($text); },
								'capitalize' => function( $text ){ return std::capitalizeFirst($text); },
								'capitalizeAll' => function( $text ){
									$arr = explode(" ",$text);
									$capitalizedArray = [];
									foreach($arr as $str){
										$capitalizedArray[] = std::capitalizeFirst($str);
									}
									return implode(" ", $capitalizedArray);
								},
						],
						'link' => function( $input ){
									if( is_array($input) ){
										
										$html = @$input["text"];
										$attributes = [ 'href' => @$input['url'] ];
										foreach( ['id', 'class', 'target' ] as $v ){
											if( @$input[$v] ) $attributes[$v] = @$input[$v];
										}
										if( @$input["nofollow"] ){
											$attributes[] = "nofollow";
										}
										$tag = std::tag("a", $attributes) . $html . "</a>";
										// print_r($attributes); echo "**** " . std::html($tag); die;
									}
									else {
										$tag = std::link($input);
									}
									return $tag;
								}

				],
				'escape' => function($text) {
					// We convert the carriage return into a break in HTML.
					// Avoid this by using the stream filter.
					$html = std::html($text);
					return $html;
				},
				'strict_callables' => true,
				'logger' => new \Mustache_Logger_StreamLogger('/var/log/apache2/errors.log'),
				'pragmas' => [\Mustache_Engine::PRAGMA_FILTERS],
		];
		$m = new \Mustache_Engine($conf);
		
		$theme = $this->theme;
		$themeClass = $this->loadTheme( $this->theme );
		// var_dump($this->model); die;
		echo $m->render($this->templateName, $this->model);
	}
	
	/**
	 * Add a menu (through a renderer).
	 * 
	 * @param string $name name of the menu
	 * @param IMenuRenderer $renderer the renderer.
	 * 
	 */
	public function addMenu( $name, $renderer ){
		if( !$renderer instanceof IMenuRenderer ){
			throw new CMSException("addMenu() -- A IMenuRenderer was expected.");
		}
		$this->model[ self::MENU_ENTRIES ][$name] = function($text, Mustache_LambdaHelper $helper) {
			return $renderer->render($text);
		};
	}
	
	/**
	 * Add translations to the engine.
	 * 
	 * @param array $array an associative array where the key is the
	 * key for the tranlastion.
	 */
	public function pushTranslations( $array ){
		$this->model["_"] = array_merge($this->model["_"], $array);
	}
	
	/**
	 * Add a CSS file.
	 * 
	 * @param string $url the URL file (usually a relative path).
	 */
	public function addFile( $type, $url, $options = [] )
	{
		$options['url'] = $url;
		$this->model['files'][$type][] = $options;
	}
	
	public function getFiles( $type ){
		return $this->model['files'][$type];
	}

	/**
	 * Return the menu.
	 * 
	 * @param string $name the name of the menu. The top level menu is the 
	 * "top" menu.
	 */
	public function getMenu( $name ){
		return $this->model['menu'][$name];
	}


	
	/**
	 * Add JAVASCRIPT code at the bottom of the page.
	 * 
	 * @param string $code the Javascript code.
	 * @param boolean $async TRUE if must be set in jQuery "document-ready".
	 */
	public function addJavascript( $code, $async = TRUE ){
		if( $async ){
			$code = "\$( document ).ready(function() { \n" . $code . "\n});"; 
		}
		$this->addFile(self::JS_FILE, NULL, ['code' => "<script>\n $code\n </script>\n" ] );
	}
	
	public function addStylesheet( $url )
	{
		$this->addFile(self::CSS_FILE, $url);
	}
	
	public function addStylesheets( $urls )
	{
		foreach( $urls as $url ){
			$this->addStylesheet($url);
		}
	}
	
	/**
	 * Add a JS file.
	 * 
	 * @param string $url
	 */
	public function addJSFile( $url )
	{
		$this->addFile(self::JS_FILE, $url );
	}


	
	/**
	 * Set the ajax rending ON.
	 * 
	 * @param boolean $value set to TRUE to activate AJAX rendring.
	 */
	public function setRemoteRendering($value){
		$this->remote = $value;
	}

	public function isRemoteRendering(){
		return $this->remote;
	}

	/**
	 * Get the current version of the library.
	 */
	public function getVersion(){
		$file = __DIR__ . "/../composer.json";
		$json = @file_get_contents($file);
		if( $json ){
			$composer = json_decode($json, 1);
			return $composer["version"];
		}
		return "undefined";
	}
}
