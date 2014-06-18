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

if($_FILES){
  echo $bulletProof
  	->upload($_FILES['picture']);
}

/* If you want to rename uploaded image, please pass a second argument 
 * to upload like ->upload($_FILES['picture'], 'new-name-here');
 */

 /* Always use the try/catch block to handle errors */
 }catch(\ImageUploader\ImageUploaderException $e){
     echo $e->getMessage();
 }


