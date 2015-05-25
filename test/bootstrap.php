<?php
namespace ImageUploader;


require dirname(__DIR__) . '/vendor/autoload.php';


require_once(dirname(__FILE__).'/../src/bulletproof.php');

use BulletProof\Image;

class BulletProofOverride extends Image
{
    public function isUploadedFile($file)
    {
        return file_exists($file);
    }

    public function moveUploadedFile($uploaded_file, $new_file) {
        return copy($uploaded_file,$new_file);
    }
}