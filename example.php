<?php
/**
* A small, secure & fast image uploader class written in all-static
* class to give an extra boost in performance.
* @author     Simon _eQ <https://github.com/simon-eQ>
* @license    Public domain. Do anything you want with it.
*
*/


include 'BulletProof2.php';

$BulletProof = new BulletProof(array('jpg', 'png', 'gif', 'jpeg'));

if($_FILES):


$result = $BulletProof->setImageDimensions(array('max-height'=>150, 'max-width'=>150))
                      ->setFileSize(array('max-size'=>4000, 'min-size'=>1))
                      ->setUploadDir('uploads/')
                      ->upload($_FILES['logo']);



echo $result; //242i42923.jpg

endif;




?>

<form method="POST" enctype="multipart/form-data">
<input type="hidden" name="MAX_FILE_SIZE" value="30000" />
<input type="file" name="logo" id="file"><br>
<input type="submit" name="submit" value="Submit">
</form>
