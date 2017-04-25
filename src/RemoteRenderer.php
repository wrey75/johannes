<?php

namespace Johannes;

/**
 * Used to render stuff remotely
 * 
 * @author wrey
 *
 */
class RemoteRenderer {
	
	protected $callback;
	
	/** @var CMSEngine */
	protected $cms;
	
	public function __construct( $cms, $callback ){
		$this->cms = $cms;
		$this->callback = $callback;
	}
	
	/**
	 * This method is invoked to generate the expected value.
	 * Note this method is a callable and will call a callable.
	 * 
	 * 
	 * @param string $text the text
	 * @param Mustache_LambdaHelper $helper the helper.
	 * 
	 */
	public function __invoke($text, Mustache_LambdaHelper $helper ){
		if( $this->cms->hasRemoteRendering() ){
			// TODO: add the remote rendering
			return $this->callback($text, $helper);
		}
		else {
			// JUST RENDER -- Variables are NEVER remotely rendered because
			// we already get their value.
			return $this->callback($text, $helper);
		}
	}
}