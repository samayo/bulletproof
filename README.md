## BULLETPROOF [![Build Status](https://travis-ci.org/samayo/bulletproof.svg?branch=master)](https://travis-ci.org/samayo/bulletproof.svg?branch=master)
[![Latest Stable Version](https://poser.pugx.org/bullet-proof/image-uploader/v/stable.svg)](https://packagist.org/packages/bullet-proof/image-uploader) [![Latest Unstable Version](https://poser.pugx.org/bullet-proof/image-uploader/v/unstable.svg)](https://packagist.org/packages/bullet-proof/image-uploader) [![License](https://poser.pugx.org/bullet-proof/image-uploader/license.svg)](https://packagist.org/packages/bullet-proof/image-uploader)    
=======================================

A single-class library to upload images in PHP with a bulletproof security.

### INSTALL
using git
````bash
git clone https://github.com/samayo/bulletproof.git
````
using composer
````bash
php composer.phar require samayo/bulletproof:2.0.*
````
download as zip format from [source file][bulletproof_link]

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
To upload based on size, dimension, mimetype and more use any of the below config methods
#### Setting image properties
The following methods help to dimension, size and mime type & image name creating folder
````php  
// Call if you need to manually rename images
->setName($name); 

// define min/max upload size limit (in bytes) 
->setSize($min, $max); 

// define acceptable mime types (in array)
->setMime(array($jpeg, $gif));  

// pass string name to create folder and optional chmod 
->setLocation($folderName, $optionalPermission); 

// set max width/height limit in pixels
->setDimension($min, $max);  
````
#### Getting image properties
To get all image info, before or after upload use any of the below
````php 
// get image name (w/o mime or location)
->getName();

// get image size in bytes
->getSize();

// get image mimetype
->getMime();

// get image width
->getWidth();

// get image height
->getHeight();

// get image location
->getLocation();

// get full path. ex: // images/table.jpg
->getFullPath();

// get a json format value of all the above information
->getJson();
````
### usage #1: some getters and setters in action 
use getters and setters combination for uploading
````php 
<?php 
$image = new Bulletproof\Image($_FILES);

/* let"s call some setters */
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
### usage #2: create your own messages / errors. 
To use you own error messages instead of the ones set by default, use 
exceptions as seen below
````php 
<?php  

if($image["ikea"]){

	if($image->getMime() !== "png"){
		throw new \Exception("we only accept png"); 
	}

	if($image->getHeight() > 1000){
		throw new \Exception("image too tall"); 
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
* filters image name and folders are created with 0666 permission


#### License  
MIT

[bulletproof_link]: http://github.com/samayo/bulletproof
[exif_imagetype_link]: http://php.net/manual/de/function.exif-imagetype.php
[getimagesize_link]: http://php.net/manual/en/function.getimagesize.php