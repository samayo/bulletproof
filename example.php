<?php

/**
 * ImageUploader has default settings. like this:
 *
 * private $fileSize         = array("min"=>100, "max"=>30000);
 * private $fileExtensions   = array("jpg", "png", "gif");
 * private $imageDimensions  = array("max-height"=>1150, "max-width"=>1150);
 * private $uploadFolder     = "uploads/";
 *
 * Calling the function as seen below, will help you override the default settings.
 */




/***************************************************************************
 *  Example: 1  Naming and uploading an image                              *
 ***************************************************************************
 * You'll be above to assign a name for each upload upload like this:
 */

include_once 'ImageUploader.php';

$newImage = new ImageUploader();

    if($_FILES){
          $result = $Obj->setFileType(array("jpg", "gif"))
                        ->setFileSize(array("min"=>1, "max"=>100))
                        ->setImageDimensions("max-height"=>450, "max-width"=>550)
                        ->setFolder('uploads/')
                        ->upload($_FILES['logo'], 'some_name');
            echo $result; //some_name.jpg
    }


/***************************************************************************
 *  Example: 2  Uploading a non-image file, without assigning a new filename
 ***************************************************************************
 * You'll be above to assign a name for each upload upload like this:
 */

if($_FILES){
    $result = $Obj->setFileType(array("txt", "doc"))
                  ->setFileSize(array("min"=>1, "max"=>100))
                  ->setImageDimensions(null) //Important if you don't this to be an image.
                  ->setFolder('uploads/')
                  ->upload($_FILES['logo']); //if you don't give a file name, a unique id will be generated

            echo $result; //98427398472334234234487248234823423.jpg
    }
