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
 * UPLOAD IMAGES WITH "SPECIFIC" TYPE, NAME, UPLOAD DIR.
 * 
 * This will upload ONLY the image types specified in the 'fileTypes()' method.
 * In this case, the image to be uploaded will be 'gif', it will be uploaded to
 * a folder called 'documents' or it'll be created if it does not exist 
 * and the image will be re-named  to 'awesome.gif'
 */

if($_FILES){
	echo $bulletProof
     ->uploadDir("documents") 
     ->fileTypes(array('gif')) 
     ->upload($_FILES["picture"], "awesome"); 
}




 /* Always use the try/catch block to handle errors */
 }catch(\ImageUploader\ImageUploaderException $e){
     echo $e->getMessage();
 }


