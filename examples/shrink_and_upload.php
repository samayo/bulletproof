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
 * RESIZE IMAGES BY PIXELS. 
 *
 * This simply will shrink the image, to the given size in the 'shrink()' method 
 */

if($_FILES){
	echo $bulletProof
		->fileTypes(array("gif", "jpg", "jpeg", "png"))
		->limitSize(array("min"=>1, "max"=>2122000))
		->shrink(array("width"=>30, "height"=>30))
		->upload($_FILES['picture']);
}



 /* Always use the try/catch block to handle errors */
 }catch(\ImageUploader\ImageUploaderException $e){
     echo $e->getMessage();
 }


