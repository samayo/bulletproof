## BULLETPROOF [![Build Status](https://travis-ci.org/samayo/bulletproof.svg?branch=master)](https://travis-ci.org/samayo/bulletproof.svg?branch=master)
[![Latest Stable Version](https://poser.pugx.org/bullet-proof/image-uploader/v/stable.svg)](https://packagist.org/packages/bullet-proof/image-uploader) [![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/samayo/bulletproof/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/samayo/bulletproof/?branch=master)
=======================================

Bulletproof is a single-class library to upload images in PHP with a with security.    

The previous repo with image watermark, resize, shrink.. features is moved to [`samayo/nautilus`][nautilus]

Install
-----

Using git
```bash
$ git clone https://github.com/samayo/bulletproof.git
```
Using composer
````bash
$ php composer.phar require samayo/bulletproof:2.0.*
````
Or [download it manually][bulletproof_archive] based on the archived version of release-cycles

Usage
-----

Create an HTML form like this. 
````html
<form method="POST" enctype="multipart/form-data">
	<input type="hidden" name="MAX_FILE_SIZE" value="1000000"/>
	<input type="file" name="ikea"/>
	<input type="submit" value="upload"/>
</form>
````
And simply require the class to upload
````php 
<?php

require_once  "path/to/bulletproof.php";

$image = new Bulletproof\Image($_FILES);

if($image["ikea"]){

	if($image->upload()){
		// OK
	}else{
		echo $image["error"]; 
	}
}
````
#### Setting Options
Methods for setting size, dimension, mime type, location and image name
````php  
// only call to rename image manually
$image->setName($name); 

// define min/max upload limit (size in bytes) 
$image->setSize($min, $max); 

// define acceptable mime types (in array)
$image->setMime(array($jpeg, $gif));  

// pass string name to create folder and optional chmod 
$image->setLocation($folderName, $optionalPermission); 

// set max width/height limit (in pixels)
$image->setDimension($width, $height);  
````

#### Getting Properties
Methods for getting image info before and / or after upload. 
````php 
// get the provided or auto-generated image name
$image->getName();

// get the image size (in bytes)
$image->getSize();

// get the image mimetype (extension)
$image->getMime();

// get the image width in pixels
$image->getWidth();

// get the image height in pixels
$image->getHeight();

// get image location or folder name
$image->getLocation();

// get the full image path. ex 'images/logo.jpg'
$image->getFullPath();

// get the json format value of all the above information
$image->getJson();
````
#### Setting and Getting values, .. 
To set and get image info, before or after image upload, do: 
````php 
<?php 

$image = new Bulletproof\Image($_FILES);

$image->setName("kitten"){
      ->setMime(["png", "gif"])
      ->setLocation("funny");

if($image["ikea"]){
	if($image->upload()){
		echo $image->getName(); // kitten
		echo $image->getMime(); // gif
		echo $image->getLocation(); // funny
		echo $image->getFullPath(); // funny/kitten.gif
	}
}
```` 
#### Creating custom responses
To create your own errors and responses, instead of the default class messages, use exceptions:
````php 
 try{

   if($image->getMime()  !== "png" && $image->getHeight() > 100 ){
      throw new \Exception(" Image should be png type, and ... ");
   }

   if($image->upload()){
      // OK
   }else{
     throw new \Exception($image["error"]);
   }

 }catch(\Exception $e){
      echo $e->getMessage(); 
 }
````
#### Why is this secure? 
* Uses **[`exif_imagetype()`][exif_imagetype_link]** to get the true image mime `.extension` type
* Uses **[`getimagesize()`][getimagesize_link]** to check if image has a valid height / width in pixels.
* Sanitized images, strict folder permissions and more... 

#### License: MIT

[bulletproof_archive]: http://github.com/samayo/bulletproof/releases
[nautilus]: http://github.com/samayo/nautilus
[exif_imagetype_link]: http://php.net/manual/de/function.exif-imagetype.php
[getimagesize_link]: http://php.net/manual/en/function.getimagesize.php
