<?php

namespace BulletProofTest;

require __DIR__ . '../../src/bulletproof.php';

class BulletProofOverride extends \Bulletproof\Image
{

	// prevent class from using move_file_upload(); function
    public function moveUploadedFile($tmp, $desination)
    {
        return true;
    }

    /* prevent class from creating a folder */
    public function setLocation($dir = "bulletproof", $optionalPermision = 0666){
    	$this->location = $dir;
    	return $this; 
    }
}

