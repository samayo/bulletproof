<?php

/*******************************************************************************
 *  Example: 1  How to upload images, by assigning new names                   *
 ********************************************************************************/

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


/*******************************************************************************
 *  Example: 1  How to upload files, without assigning new names               *
 ********************************************************************************/

if($_FILES){
    $result = $Obj->setFileType(array("txt", "doc"))
                  ->setFileSize(array("min"=>1, "max"=>100))
                  ->setImageDimensions(null) //Important if you don't this to be an image.
                  ->setFolder('uploads/')
                  ->upload($_FILES['logo']); //if you don't give a file name, a unique id will be generated

            echo $result; //98427398472334234234487248234823423.jpg
    }
