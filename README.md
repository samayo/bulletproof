## BULLETPROOF [![Build Status](https://travis-ci.org/samayo/bulletproof.svg?branch=master)](https://travis-ci.org/samayo/bulletproof.svg?branch=master)
[![Latest Stable Version](https://poser.pugx.org/bullet-proof/image-uploader/v/stable.svg)](https://packagist.org/packages/bullet-proof/image-uploader)[![License](https://poser.pugx.org/bullet-proof/image-uploader/license.svg)](https://packagist.org/packages/bullet-proof/image-uploader)    
=======================================

A single-class library to upload images in PHP with a bulletproof security.

### INSTALL
using git
````bash
$ git clone https://github.com/samayo/bulletproof.git
````
using composer
````bash
$ php composer.phar require samayo/bulletproof:2.0.*
````
or get the zip format from the [source download][bulletproof_link] page.

#### Simple example

Create an HTML form like this. 
````html
<form method="POST" enctype="multipart/form-data">
	<input type="hidden" name="MAX_FILE_SIZE" value="1000000"/>
	<input type="file" name="ikea"/>
	<input type="submit" value="upload"/>
</form>
````
Then simply require the class and upload
````php 
<?php

require_once  "src/bulletproof.php";

$image = new Bulletproof\Image($_FILES);

if($image["ikea"]){

	$upload = $image->upload(); 

	if($upload){
		// OK
	}else{
		echo $image["error"]; 
	}
}
````
#### Setting image properties
To define dimension, size, mime type, location and image name ... use any of the following:
````php  
// call if you need to manually rename images
$image->setName($name); 

// define min/max upload size limit (in bytes) 
$image->setSize($min, $max); 

// define acceptable mime types (in array)
$image->setMime(array($jpeg, $gif));  

// pass string name to create folder and optional chmod 
$image->setLocation($folderName, $optionalPermission); 

// set max width/height limit in pixels
$image->setDimension($width, $height);  
````
#### Getting image properties
To get all image info, before or after upload you can use the following:
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
#### usage #1: setting and getting properties
To define upload options and get image upload information, see example: 
````php 

$image = new Bulletproof\Image($_FILES);

/* define some values */
$image->setName("kitten")
      ->setMime(["png", "gif"])
      ->setLocation("lolz");

if($image["ikea"]){

	if($image->upload()){
		echo $image->getName(); // kitten
		echo $image->getMime(); // gif
		echo $image->getLocation(); // lolz
		echo $image->getFullPath(); // lolz/kitten.gif
	}
}
```` 
#### usage #2: Handling errors manually
To create you own error messages instead of the ones set by default, use 
exceptions
````php 
<?php  

if($image["ikea"]){

	if($image->getMime() !== "png"){
		throw new \Exception("we only accept png"); 
	}

	if($image->getHeight() > 1000){
		throw new \Exception("Image is too tall");
	}

	if ($image->getLocation() != "images") {
		// this is possible too
		$image->setLocation("images"); 
	} 
	
	if($image->upload()){
		// ok
	}
}
````
#### What makes bulletproof secure? 
* uses [exif_imagetype][exif_imagetype_link] to get the true image `.extension` / mime type
* checks image using [getimagesize][getimagesize_link] for a valid dimension in pixels.
* sanitizes image name and safe folder chmoding i.e. `uog+rw`


#### License  
MIT

[bulletproof_link]: http://github.com/samayo/bulletproof/releases
[exif_imagetype_link]: http://php.net/manual/de/function.exif-imagetype.php
[getimagesize_link]: http://php.net/manual/en/function.getimagesize.php