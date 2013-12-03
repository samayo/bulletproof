<?php
/***************************************************************************
 *  EXAMPLE 1                                                              *
 ***************************************************************************
 * The Suploder class has default setting of
 *
 * private $fileSize         = array("min"=>100, "max"=>30000);
 * private $fileExtensions   = array("jpg", "png", "gif");
 * private $imageDimensions  = array("max-height"=>1150, "max-width"=>1150);
 * private $uploadFolder     = "uploads/";
 *
 * If you want to change those setting during upload, then see below.
 */


include 'Suploder.php';

$Obj = new Suploder;


if($_FILES){
      $result = $Obj->setFileType(array("jpg", "gif"))
                    ->setFileSize(array("min"=>1, "max"=>100))
                    ->setImageDimensions("max-height"=>450, "max-width"=>550)
                    ->setFolder('uploads/')
                    ->upload($_FILES['logo']);
    echo $result; //345212631129223425311217529118879612810120122102746529cc1c8d909c1.40357962.jpg
}


