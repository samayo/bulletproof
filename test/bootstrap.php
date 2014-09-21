<?php
namespace ImageUploader;

require_once(dirname(__FILE__).'/../src/bulletproof.php');

use ImageUploader\BulletProof;

class BulletProofOverride extends BulletProof
{
    public function isUploadedFile($file)
    {
        return file_exists($file);
    }

    public function moveUploadedFile($uploaded_file, $new_file) {
        return copy($uploaded_file,$new_file);
    }
}