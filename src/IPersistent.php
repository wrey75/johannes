<?php

/**
 * This interface is needed for every object you
 * want to persist. This is the basic of
 * the storage manager for all data manipulated
 * by the CMS.
 *
 * 
 */
interface IPersistent {

    /**
     * Get the data for the object.
     *
     * @return array an array which explain the object
     * structure.
     *
     */
    public function getMetadata();
}

