<?php

namespace BulletProofTest;


require_once __DIR__ . '/../src/bulletproof.php';

use BulletProof\Image;

class BulletProofOverride extends \BulletProof\Image
{

    public function moveUploadedFile()
    {
        return true;
    }
}

