<?php

/**
 * BULLETPROOF - ALL IN ONE, IMAGE UPlOAD/MANIPULATE 
 * 
 * @category BULLETPROOF
 * @license  Free / Luke 3:11
 * @version  1.0.0
 * @link     https://github.com/bivoc/bulletproof
 * @author   bivoc. ~ The force is strong with this one.
 *
 *
 * Below are some examples prepared to get you started. 
 * Uncomment any block of code and try the out examples 
 */
require_once "lib/BulletProof.php";
$bulletProof = new ImageUploader\BulletProof;



// Let the try/catch be, to handle all errors.
try{


/**
 * SIMPLE & DEFAULT UPLOAD
 *
 * This is the simplest way to upload an image. It will use the default methods of the class. 
 * Which means it will: 
 * > upload an image with (jpg, png, gif, jpeg) extensions only. 
 * > It will only upload file with sizes in-between 1Kb to 30Kb. 
 * > It will upload the images in a folder called "uploads", if you don't have such folder
 *   then it will be created with permission/chmod of '666'. 
 * > Uploaded image will also be given a unique & random name
 */

// if($_FILES){
//   $bulletProof
//   	->upload($_FILES['picture']);
// }



#-----------------------------------------------------------------------------------------------------#



/**
 * UPLOAD IMAGES WITH "SPECIFIC" TYPE, NAME, UPLOAD DIR.
 * 
 * This will upload ONLY the image types specified in the 'fileTypes()' method.
 * In this case, the image to be uploaded will be 'gif', it will be uploaded to
 * a folder called 'documents' or it'll be created if it does not exist 
 * and the image will be re-named  to 'awesome.gif'
 */

// if($_FILES){
// 	echo $bulletProof
//      ->uploadDir("documents") 
//      ->fileTypes(array('gif')) 
//      ->upload($_FILES["picture"], "awesome"); 
// }



#-----------------------------------------------------------------------------------------------------#



/**
 * UPLOAD WITH A SPECIFIC SIZE 
 * 
 * This will check the size of the image (in bytes), as specified in the 'limitSize()' method.
 * Pass values in bytes, and don't forget "min", "max". 
 * remember. 1 kb ~ 1000 bytes. In this example, only an image less than 42Kb can be uploaded
 *
 */

// if($_FILES){
// 	echo $bulletProof
// 		->limitSize(array("min"=>1, "max"=>42000))
// 		->upload($_FILES['picture'], "cars_picture");
// 	}




#-----------------------------------------------------------------------------------------------------


/**
 * ADD A WATERMARK TO IMAGE
 * 
 * This will add a watermark as specified in the 'watermark()' method. 
 * The first argument should always be the image and the second
 * should be the position (where to put the watermark). You can only pass 
 * 4 types of positions: 
 * top-right, bottom-right, center, 'top-left', 'bottom-left'
 *
 * This position obviously determines where the watermark appears in the image.
 * 
 */

// if($_FILES){
// 	echo $bulletProof
// 		->fileTypes(array("gif", "jpg", "jpeg", "png"))
// 		->uploadDir("watermark")
// 		->limitSize(array("min"=>1, "max"=>52000))
// 		->watermark("logo.png", "bottom-left")
// 		->upload($_FILES['picture']);
// 	}



#-----------------------------------------------------------------------------------------------------



/**
 * CROP IMAGES BY PIXELS. 
 * 
 * This will crop all images as specified in the 'crop()' method. 
 * Unless the the crop size is bigger than the actual image. In other words: 
 * If you have an image with 100px * 100px, if you want to crop it to 120px * 120px 
 * you can't and you shouldn't. (Or perhaps, extend the class and add your own method)
 *
 * The script will calculate the ratio and crop the image always from the center of the image. 
 * 
 */

// if($_FILES){
// 	echo $bulletProof
// 		->fileTypes(array("gif", "jpg", "jpeg", "png"))
// 		->crop(array("width"=>100, "height"=>100))
// 		->upload($_FILES['picture']);
// }




#-----------------------------------------------------------------------------------------------------


/**
 * RESIZE IMAGES BY PIXELS. 
 *
 * This simply will shrink the image, to the given size in the 'shrink()' method 
 */

// if($_FILES){
// 	echo $bulletProof
// 		->fileTypes(array("gif", "jpg", "jpeg", "png"))
// 		->limitSize(array("min"=>1, "max"=>1122000))
// 		->shrink(array("width"=>30, "height"=>30))
// 		->upload($_FILES['picture']);
// }





 /* Always use the try/catch block to handle errors */
 }catch(\ImageUploader\ImageUploaderException $e){
     echo $e->getMessage();
 }



?>

<!-- A simple form for the above example -->
<form method="POST" enctype="multipart/form-data">
    <input type="hidden" name="MAX_FILE_SIZE" value="30000" />
    <input type="file" name="picture"  />
    <input type="submit" value="upload" id="submit" />
</form>
