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

if($_FILES){
	echo $bulletProof
		->fileTypes(array("gif", "jpg", "jpeg", "png"))
		->uploadDir("watermark")
		->limitSize(array("min"=>1, "max"=>52000))
		->watermark("logo.png", "bottom-left")
		->upload($_FILES['picture']);
	}

 
 /* You must have a provide a watermark (logo.png) for this to work */

 /* Always use the try/catch block to handle errors */
 }catch(\ImageUploader\ImageUploaderException $e){
     echo $e->getMessage();
 }


