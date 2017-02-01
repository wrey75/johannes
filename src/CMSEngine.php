<?php

namespace Johannes;

use Slim\Handlers\NotAllowed;
use Concerto\std;

/**
 * The CMS engine. This class is in charge of the rendering, the log, the database
 * access and many other stuff related to the CMS.
 * 
 * @author wrey
 *
 */
class CMSEngine {
	
	const DEFAULT_CONFIG = [
			'theme' => "tiny",
			'slim' => []	
	];
	
	/**
	 * The file paths. Used to find the requested objects.
	 * Works exactly like the path under the operating
	 * systems. The path of the CMS itself is automatically
	 * appended.
	 *  
	 * @var string
	 */
	public $path = [];
	
	/**
	 * The configuration for the CMS.
	 * 
	 * @var array
	 */
	protected $config;
	
	/**
	 * The Slim application. To help a quick development,
	 * we rely on the Slim framework.
	 * 
	 * @var \Slim\App
	 */
	protected $slim;

    /**
     *  The data model. This array concentrate all
     *  the information needed to display the stuff.
     *
     *  The model is a grape of data including
     *  objects. The resolution is made by Mustache.
     */
    protected $model = [];

    /**
     *  Mustache itself.
     */
    protected $m;
	
	public function __construct($config = []) {
		if( is_string($config) ){
			// Use a JSON file. You should NOT use this method for
			// security reasons.
			$data = file_get_contents($config);
			$config = json_decode($data);
		}
		$this->config = array_merge(self::DEFAULT_CONFIG, $config);
		$this->init();
	}

    /**
     * Add an entry to the model.
     *
     * @param string $key the key.
     * @param any $object the object to add.
     *
     */
    public function push($key, $object){
        $this->model[$key] = $object;
    }
	
	/**
	 * Initialize the CMS.
	 */
	protected function init() {
		$this->path = [ __DIR__ . "/.." ];
		$conf = isset($this->config["slim"]) ? $this->config["slim"] : [];
		$this->slim = new \Slim\App($conf);

		$conf = isset($this->config["mustache"]) ? $this->config["mustache"] : [];
		$this->m = new \Mustache_Engine($conf);
	}


	/**
	 * Add a folder into the path. You should add your current
	 * directory as a minimal requirement. The folders must
	 * be added in the reverse order of priority (those with low
	 * priority first).
	 * 
	 * @param string $folder the absolute folder path. 
	 */
	public function addPath( $folder ){
		if( $folder[0] != "/" ){
			throw new CMSException("Your path must be absolute (use __DIR__ if necessary)");
		}
		array_unshift($this->path, $folder);
	}


	/**
	 * Select the theme.
	 * 
	 */
	public function selectTheme($theme){
		$this->config['theme'] = $theme;
	}
	
	/**
	 * Scan all the path to find the disk path for
	 * the requested object.
	 * 
	 * @return the absolute path or NULL if not 
	 * found.
	 */
	public function findInPath( $file ){
		if( $file[0] != '/') $file = "/$file";
		foreach( $this->path as $dir ){
			$path = $dir . $file;
			if( file_exists($path) ){
				return $path;
			}
		}
		return NULL;
	}
	
	/**
	 * Load the theme.
	 * 
	 * @param string $theme the theme.
	 */
	protected function loadTheme( $theme ){
		$themeIndex = findInPath( "/themes/$theme/index.php" );
		if( !$themeIndex ){
			throw new CMSException("Theme '$theme' not found (looking for index.php).");
		}
		
		include $themeIndex;
		
		$class_name = "\\" . std::capitalizeFirst($theme) . "Theme";
		if( class_exists( $class_name ) ){
			throw new CMSException("Class '$class_name' not loaded.");
		}
		
		$themeObj = new $class_name;
		if( !($themeObj instanceof ITheme) ){
			throw new CMSException("Class '$class_name' does NOT implement " . ITheme::class);
		}
		
		return $themeObj;
	}
	
	/**
	 * Run the CMS. This method will render the page (or
	 * redirect to another page depending the behaviour).
	 * 
	 */
	public function run(){
		$this->loadTheme( $this->config['theme'] );
		$this->slim->run();
	}
}
