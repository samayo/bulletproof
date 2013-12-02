<?php
/***************************************************************************
 *  EXAMPLE 1                                                              *
 ***************************************************************************
 * First example enables you to upload different file sizes, dimensions into
 * different upload directories. This can be good, if your website allows
 * users to upload with different types of restrictions.
 * Meaning on each page you put this script on, you get to declare different
 * options for your uploader.
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





/***************************************************************************
 *  EXAMPLE 1                                                              *
 ***************************************************************************
 * This example allows you to set only one option, meaning whenever you use
 * `$Obj->upload()` all files to be uploaded must follow the global setting
 * you have desalted in your constructor. So, this is only usefull, if you
 * are going to upload all files with only one setting ie size, dimensions
 * directory....
 * 
 */


$Obj = new BulletProof(array('jpg', 'png', 'gif', 'jpeg'),
                       array('max-height'=>150, 'max-width'=>150),
                       array('max-size'=>4000, 'min-size'=>1),
                       'uploads/');

if($_FILES){
    $Obj->upload($_FILES['logo'], 'passport_pic');
        echo $result; //passport_pic.jpg
}
