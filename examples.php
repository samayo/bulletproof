<?php

require_once "imageUploader.php";
$bulletProof = new ImageUploader\BulletProof;


/*---------------------------------------------------------------
 |	                 SOME WORKING EXAMPLES: 					 |
 |															     |
 |       Uncoment any block of code you want and give it a try   |
 ---------------------------------------------------------------*/


try{

/**
 * SIMPLE & DEFAULT UPLOAD
 * - This will use the default settings of the upload class, 
 *
 * FILE TYPE AND SIZE
 * - This will upload a jpg, jpeg, png or gif files, between 1 to 30kb
 *
 * DIRECTORY: 
 * - It will try to upload those files into a folder called "uploads"
 *   It it does not exist, it will create the folder and upload the images.
 *
 * NAME: 
 * - If you don't pass a second argument for the 'change()' method (as
 *   shown in the second example) a random name will be generated instead. 
 *  ex: '9729117325181114111460111302586531cfab37d225.jpg'
 */

// if($_FILES){
//    echo $bulletProof->upload($_FILES['picture']);
// }






/**
 * UPLOAD SPECIFIC FILE + CUSTOM NAME + CUSTOM UPLOAD DIRECTORY
 * 
 * This will upload ONLY the file/image types specified in the 'fileTypes()' method.
 * In this case, the image to be uploaded will be 'gif' only. named 'awesome'
 */

// if($_FILES){
// 	echo $bulletProof
// 		->fileTypes(array('gif')) # upload only gif files
//     	->folder("foo") # upload to 'my_pictures' folder, if it does not exit create it!
// 		->upload($_FILES["picture"], "awesome"); # rename file/image to "awesome"
// }






/**
 * SPECIFIC SIZE LIMIT
 * 
 * This will add file size check specified in the 'sizeLimit()' method.
 * only pass values in bytes, and don't forget "min", "max". 
 * remember. 1KB ~ 1000bytes. 
 *
 */

// if($_FILES){
// 	echo $bulletProof
// 		->fileTypes(array("png"))
// 		->limitSize(array("min"=>1, "max"=>22000))
// 		->upload($_FILES['picture'], "passport_pic");
// 	}







/**
 * ADD WATERMARK TO IMAGE
 * 
 * This will add a watermark specified in the 'watermark()' method. 
 * The first argument should always be the image (png) and the second
 * the position. You can only pass 4 positions: 
 * top-right, bottom-right, center, 'top-left', 'bottom-left'
 * 
 */

// if($_FILES){
// 	echo $bulletProof
// 		->fileTypes(array("gif", "jpg", "jpeg", "png"))
// 		->limitSize(array("min"=>1, "max"=>52000))
// 		->watermark("logo.png", "center")
// 		->folder("batman")
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
 * The script will calculate the ratio and crop the image always from the center. 
 * 
 */

// if($_FILES){
// 	echo $bulletProof
// 		->fileTypes(array("gif", "jpg", "jpeg", "png"))
// 		->limitSize(array("min"=>1, "max"=>1122000))
// 		->folder("new_folder")
// 		->crop(array("width"=>100, "height"=>100))
// 		->upload($_FILES['picture']);
// }







/**
 * RESIZE IMAGES BY PIXELS. 
 * 
 * This will crop all images as specified in the 'crop()' method. 
 * Unless the the crop size is bigger than the actual image. In other words: 
 * If you have an image with 100px * 100px, if you want to crop it to 120px * 120px 
 * you can't and you shouldn't. 
 *
 * The script will calculate the ratio and crop the image always from the center. 
 * 
 */

// if($_FILES){
// 	echo $bulletProof
// 		->folder("shrinked_images")
// 		->fileTypes(array("gif", "jpg", "jpeg", "png"))
// 		->limitSize(array("min"=>1, "max"=>1122000))
// 		->shrink(array("width"=>30, "height"=>30))
// 		->upload($_FILES['picture']);
// }













/*---------------------------------------------------------------------------
 |	                 SOME WORKING EXAMPLES: 					             |
 |																             | 
 |	          ################# READ PLEASE  ###################	         |
 |       The below functions allow you to delete/crop/shrink/watermak        |
 |       an image only that has already been UPLOADED, maybe a minute        |
 |       ago or a year ago, in short its like physically checking an image   |
 ----------------------------------------------------------------------------*/

/**
 * DELETING/REMOVING A IMAGE/FILE
 */
//$delete = $bulletProof->deleteFile("shrinked_images/1531e6a564521f_IJLPKONFQMEGH.png");



/**
 * CROP IMAGES
 */
// $crop = $bulletProof
// 	->folder("croped_images")
// 	->crop(array("height"=>10, "width"=>10))
// 	->change("crop", "my_pictures/awesome.gif");







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
