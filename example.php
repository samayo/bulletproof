EXAMPLE 1
<?php
/**
 * A small, secure & fast image uploader class written in all-static
 * class to give an extra boost in performance.
 * @author     Simon _eQ <https://github.com/simon-eQ>
 * @license    Public domain. Do anything you want with it.
 *
 */


include 'BulletProof.php';

$Obj = new BulletProof(array('jpg', 'png', 'gif', 'jpeg'));

if($_FILES):
    $result = $Obj->setImageDimensions(array('max-height'=>150, 'max-width'=>150))
        ->setFileSize(array('max-size'=>4000, 'min-size'=>1))
        ->setUploadDir('uploads/')
        ->upload($_FILES['logo']);
    echo $result; //242i42923.jpg
endif;
?>
<hr/>

EXAMPLE 2
<hr/>

<?php
include 'BulletProof.php';

$Obj = new BulletProof(array('jpg', 'png', 'gif', 'jpeg'),
                       array('max-height'=>150, 'max-width'=>150),
                       array('max-size'=>4000, 'min-size'=>1),
                       'uploads/');

if($_FILES){
        ->upload($_FILES['logo'], 'passport_pic');
        echo $result; //passport_pic.jpg
}
