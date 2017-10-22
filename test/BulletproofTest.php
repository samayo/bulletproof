<?php

namespace TestBootstrap; 

class BulletproofTest extends \Bulletproof\Image {

	/**
     * Return true at point since we can't upload files
     * during test. 
     */
    public function moveUploadedFile($tmp, $desination)
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