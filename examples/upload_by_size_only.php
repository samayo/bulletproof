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
 * UPLOAD WITH A SPECIFIC SIZE 
 * 
 * This will check the size of the image (in bytes), as specified in the 'limitSize()' method.
 * Pass values in bytes, and don't forget "min", "max". 
 * remember. 1 kb ~ 1000 bytes. In this example, only an image less than 42Kb can be uploaded
 *
 */

if($_FILES){
	echo $bulletProof
		->limitSize(array("min"=>1, "max"=>42000))
		->upload($_FILES['picture'], "cars_picture");
}





 /* Always use the try/catch block to handle errors */
 }catch(\ImageUploader\ImageUploaderException $e){
     echo $e->getMessage();
 }


