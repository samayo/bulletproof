## BULLETPROOF [![Build Status](https://travis-ci.org/samayo/bulletproof.svg?branch=master)](https://travis-ci.org/samayo/bulletproof.svg?branch=master)
[![Latest Stable Version](https://poser.pugx.org/bullet-proof/image-uploader/v/stable.svg)](https://packagist.org/packages/bullet-proof/image-uploader)  [![License](https://poser.pugx.org/bullet-proof/image-uploader/license.svg)](https://packagist.org/packages/bullet-proof/image-uploader)
=======================================

Bulletproof is a single-class library to upload images in PHP with a with security.    

The previous repo featuring image watermark, resize, shrink.. features is moved to [nautilus][nautilus]

Install
-----

using git
```bash
$ git clone https://github.com/samayo/bulletproof.git
```
using composer
````bash
$ php composer.phar require samayo/bulletproof:2.0.*
````
Manual Download
To download it manually, based on archived version of release cycles, checkout the [source download][bulletproof_archive]

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
Now simply require the class and upload
````php 
<?php

require_once  "path/to/bulletproof.php";

$image = new Bulletproof\Image($_FILES);

if($image["ikea"])

	$upload = $image->upload(); 

	if($upload){
		// success
	}else{
		// fail
	}
}
````
#### Setting upload options
Use these methods to set size, dimension, mime type, location and image name
````php  
// call if you need to manually rename images
$image->setName($name); 

// define min/max upload size limit (in bytes) 
$image->setSize($min, $max); 

// define acceptable mime types (in array)
$image->setMime(array($jpeg, $gif));  

// pass string name to create folder and optional chmod 
$image->setLocation($folderName, $optionalPermission); 

// set max width/height limit (in pixels)
$image->setDimension($width, $height);  
````

#### Getting upload, image properties
These methods allow you to get image info before and/or after upload. 
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
#### Using getters and setters together
To set and get image info, before and after image upload, use as: 
````php 
<?php 

$image = new Bulletproof\Image($_FILES);

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
#### Creating custom response messages
If you don't want bulletproof's defaul errors, you can set up yours like:
````php 
<?php  

try{
	
	if($image->getMime() !== "png"){
		throw new \Exception("only png are allowed ..");
	}

	if($image->getHeight() > 1000){
		throw new \Exception("image height should ..");
	}

	if(!$image->upload()){
		 throw new Exception($image["error"]);
	}

	}catch(\Exception $e){
		echo $e->getMessage(); 
	}

}
````
#### What is this library secure? 
* Uses [exif_imagetype][exif_imagetype_link] to get the true image `.extension` / mime type
* Uses [getimagesize][getimagesize_link] to check image for a valid height/width in pixels.
* Image names are sanitized, & storage folder is created with limited permissions..

#### License  
MIT

[bulletproof_archive]: http://github.com/samayo/bulletproof/releases
[nautilus]: http://github.com/samayo/nautilus
[exif_imagetype_link]: http://php.net/manual/de/function.exif-imagetype.php
[getimagesize_link]: http://php.net/manual/en/function.getimagesize.php