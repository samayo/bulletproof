<?php
require_once "ImageUploader/BulletProof.php";
$bulletProof = new ImageUploader\BulletProof;



	/*
	|--------------------------------------------------------------------------
	| 			SIMPLE WORRKING EXAMPLES.
	|--------------------------------------------------------------------------
	|
	| This are some default codes made to demostrate some usages of the class.
	| Uncomment one you like and give it a try.   
	| leave the try/catch as is to handle errors. 
	|
	*/



try{

/**
 * SIMPLE & DEFAULT UPLOAD
 * - This minimal method uploads using the default settings of the class. 
 *
 * FILE TYPE AND SIZE
 * - This will upload a jpg, jpeg, png or gif files, between image sizes of 1 to 30kb
 *
 * DIRECTORY: 
 * - It will try to upload those files into a folder called "uploads"
 *   If the folder does not exist, it will created and the image will be uploaded into it. 
 *
 * NAME: 
 * - If you don't pass a second argument for the 'upload()' method  a randomname will 
 *  be generated instead.  ex: '9729117325181114111460111302586531cfab37d225.mimetype'
 */

// if($_FILES){
//   $bulletProof
//   	->upload($_FILES['picture']);
// }




/**
 * UPLOAD SPECIFIC IMAGE + CUSTOM NAME + CUSTOM UPLOAD DIRECTORY
 * 
 * This will upload ONLY the file/image types specified in the 'fileTypes()' method.
 * In this case, the image to be uploaded will be 'gif', it will be uploaded 
 * a folder called 'my_pictures' and will be named 'awesome'
 */

// if($_FILES){
// 	echo $bulletProof
//      ->uploadDir("foo") # upload to 'my_pictures' folder, if it does not exit create it!
//      ->fileTypes(array('gif')) # upload only gif files
//      ->upload($_FILES["picture"], "awesome"); # rename file/image to "awesome"
// }






/**
 * UPLOAD WITH A SPECIFIC SIZE 
 * 
 * This will check the size of the image, as specified in the 'limitSize()' method.
 * Pass values in bytes, and don't forget "min", "max". 
 * remember. 1 kb ~ 1000 bytes. 
 *
 */

// if($_FILES){
// 	echo $bulletProof
// 		->fileTypes(array("png", "jpeg"))
// 		->limitSize(array("min"=>1, "max"=>42000))
// 		->upload($_FILES['picture'], "passport_pic");
// 	}







/**
 * ADD A WATERMARK TO IMAGE
 * 
 * This will add a watermark specified in the 'watermark()' method. 
 * The first argument should always be the image and the second
 * the position. You can only pass 4 positions: 
 * top-right, bottom-right, center, 'top-left', 'bottom-left'
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







/**
 * CROP IMAGES BY PIXELS. 
 * 
 * This will crop all images as specified in the 'crop()' method. 
 * Unless the the crop size is bigger than the actual image. In other words: 
 * If you have an image with 100px * 100px, if you want to crop it to 120px * 120px 
 * you can't and you shouldn't. 
 *
 * The script will calculate the ratio and crop the image always from the center of the image. 
 * 
 */

// if($_FILES){
// 	echo $bulletProof
// 		->fileTypes(array("gif", "jpg", "jpeg", "png"))
// 		->limitSize(array("min"=>1, "max"=>1122000))
// 		->crop(array("width"=>100, "height"=>100))
// 		->upload($_FILES['picture']);
// }







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




/*
|--------------------------------------------------------------------------
| 			SIMPLE WORRKING EXAMPLES.
|--------------------------------------------------------------------------
|
| The below functions allow you to delete/crop/shrink/watermak   
| an image only that has already been UPLOADED, maybe a minute    
| ago or a year ago, in short its like physically checking an image 
|
*/



/**
 * DELETING/REMOVING A IMAGE/FILE
 */
#$delete = $bulletProof->deleteFile("uploads/c.jpeg");



/**
 * CROP IMAGES
 */
// $crop = $bulletProof
// 	->crop(array("height"=>110, "width"=>110))
// 	->change("crop", "uploads/wind_power.jpg");







/**
 * WATERMARK IMAGES
 */
// $watermark = $bulletProof
// 	->watermark("logo.png", "center")
// 	->change("watermark", "uploads/oldtimer.jpg");







/**
 * SHRINK IMAGES
 */
// $shrink = $bulletProof
// 	->shrink(array("height"=>130, "width"=>150))
// 	->change("shrink", "uploads/narcissus.jpg");




 }catch(\ImageUploader\ImageUploaderException $e){
     echo $e->getMessage();
 }



?>


<form method="POST" enctype="multipart/form-data">
    <input type="hidden" name="MAX_FILE_SIZE" value="30000" />
    <input name="picture" type="file" />
    <input type="submit" value="submit" id="submit" />
</form>
