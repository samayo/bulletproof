<?php
/**********************************
 *  EXAMPLE 1
 *********************************
 */


include 'BulletProof.php';

$Obj = new BulletProof(array('jpg', 'png', 'gif', 'jpeg'));

if($_FILES){
    $result = $Obj->setImageDimensions(array('max-height'=>150, 'max-width'=>150))
        ->setFileSize(array('max-size'=>4000, 'min-size'=>1))
        ->setUploadDir('uploads/')
        ->upload($_FILES['logo']);
    echo $result; //242i42923.jpg
}

/**********************************
 *  EXAMPLE 2
 *********************************
 */


$Obj = new BulletProof(array('jpg', 'png', 'gif', 'jpeg'),
                       array('max-height'=>150, 'max-width'=>150),
                       array('max-size'=>4000, 'min-size'=>1),
                       'uploads/');

if($_FILES){
        ->upload($_FILES['logo'], 'passport_pic');
        echo $result; //passport_pic.jpg
}
