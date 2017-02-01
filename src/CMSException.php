<?php

namespace Johannes;

/**
 * A class for the exceptions trapped by the CMS.
 * 
 * @author wrey
 *
 */
class CMSException extends Exception {
	
	public function __construct($message, $code = 0, Exception $previous = null) {
		// make sure everything is assigned properly
		parent::__construct($message, $code, $previous);
	}
	
}
