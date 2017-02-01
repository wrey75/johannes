<?php

namespace Johannes;

/**
 * This class manages the data.
 *
 */
class DataManager {

    /**
     *  Register the class.
     */
    public function register( $class ){
        $obj = new $class;
        if( !$obj instanceof IPersistent ){
            throw new CMSException("Your object must implement the IPersistent intercae.");
        }
        $metadata = $obj->getMetadata();
        
    }
    
}

