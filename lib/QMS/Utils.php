<?php

namespace QMS;

/**
 *	An helper class containing some very
 *	useful methods.
 *
 */	
class Utils {
	/**
	 *	Convert a JSON string to an array. If
	 *	the paraneter is already an array, nothing
	 *	done.
	 *
	 *	@param string the JSON value
	 *	@return array the decoded JSON value.
	 */
	static public function toArray($json){
		if( !$json ){
			// the object is NULL or an empty string
			return array();
		}
		else if( !is_array($json) ){
			return json_decode( $json, 1 );
		}
		return $json;
	}
}
