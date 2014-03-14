<?php

require_once "ImageUploader\BulletProof.php";
$bulletProof = new ImageUploader\BulletProof;


/*---------------------------------------------------------------
 |	                 SOME WORKING EXAMPLES: 					 |
 |															     |
 |       Uncoment any block of code you want and give it a try   |
 ---------------------------------------------------------------*/


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

//if($_FILES){
//   echo $bulletProof->upload($_FILES['picture']);
//}






/**
 * UPLOAD SPECIFIC IMAGE + CUSTOM NAME + CUSTOM UPLOAD DIRECTORY
 * 
 * This will upload ONLY the file/image types specified in the 'fileTypes()' method.
 * In this case, the image to be uploaded will be 'gif', it will be uploaded 
 * a folder called 'my_pictures' and will be named 'awesome'
 */

// if($_FILES){
// 	echo $bulletProof
//      ->folder("foo") # upload to 'my_pictures' folder, if it does not exit create it!
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
// 		->folder("batman")
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













/*---------------------------------------------------------------------------
 |	                 SOME WORKING EXAMPLES: 					                       |
 |																             | 
 |	          ################# READ PLEASE  ###################	         |
 |       The below functions allow you to delete/crop/shrink/watermak        |
 |       an image only that has already been UPLOADED, maybe a minute        |
 |       ago or a year ago, in short its like physically checking an image   |
 ----------------------------------------------------------------------------*/

/**
 * DELETING/REMOVING A IMAGE/FILE
 */
#$delete = $bulletProof->deleteFile("images/1531e6a564521f_IJLPKONFQMEGH.png");



/**
 * CROP IMAGES
 */
// $crop = $bulletProof
// 	->folder("my_folder")
// 	->crop(array("height"=>10, "width"=>10))
// 	->change("crop", "1531e894e1665e_JGKIQFLEONMHP.jpeg");







/**
 * WATERMARK IMAGES
 */
// $crop = $bulletProof
// 	->folder("croped_images")
// 	->watermark("logo.png", "center")
// 	->change("watermark", "my_pictures/awesome.gif");







/**
 * SHRINK IMAGES
 */
// $crop = $bulletProof
// 	->folder("croped_images")
// 	->shrink(array("height"=>30, "width"=>50))
// 	->change("shrink", "my_pictures/awesome.gif");







 }catch(\ImageUploader\ImageUploaderException $e){
     echo $e->getMessage();
 }



?>


<form method="POST" enctype="multipart/form-data">
    <input type="hidden" name="MAX_FILE_SIZE" value="11111000" />
    <input name="picture" type="file" />
    <input type="submit" value="submit" id="submit" />
</form>
