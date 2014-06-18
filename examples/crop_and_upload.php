<?php

/**
 * BULLETPROOF - ALL IN ONE, IMAGE UPLOAD/MANIPULATE. 
 * 
 * @category BULLETPROOF
 * @license  Free / Luke 3:11
 * @version  1.0.0
 * @link     https://github.com/bivoc/bulletproof
 * @author   bivoc. ~ The force is strong with this one.
 *
 */

// Require the main src file. 
require_once   "../src/BulletProof.php";

// Require the HTML form. 
require_once   "form.html";

// Create an instance of BulletProof
$bulletProof = new ImageUploader\BulletProof;

try{


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

if($_FILES){
	echo $bulletProof
		->fileTypes(array("gif", "jpg", "jpeg", "png"))
		->crop(array("width"=>100, "height"=>100))
		->upload($_FILES['picture']);
}


 /* Always use the try/catch block to handle errors */
 }catch(\ImageUploader\ImageUploaderException $e){
     echo $e->getMessage();
 }


