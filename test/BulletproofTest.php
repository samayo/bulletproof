<?php

namespace TestBootstrap; 

class BulletproofTest extends \Bulletproof\Image {

	/**
     * Return true at this point since we can't upload files
     * during test (or can we? I don't know!)
     */
    public function isSaved($tmp, $desination)
    {
        return true;
    }

    /**
     * Prevent class from making new folder
     */
    public function setLocation($dir = "bulletproof", $optionalPermision = 0666){
    	$this->location = $dir;
    	return $this; 
    }
}