<?php

namespace BulletProofTest;


require_once __DIR__ . '/../src/bulletproof.php';

use BulletProof\Image;

class BulletProofOverride extends \BulletProof\Image
{

	// prevent class from using move_file_upload(); function
    public function moveUploadedFile()
    {
        return true;
    }

    /* prevent class from creating a folder */
    public function setLocation($dir = "bulletproof", $optionalPermision = 0666){

    	 

    	$this->location = $dir;
    	return $this; 
    }
}

