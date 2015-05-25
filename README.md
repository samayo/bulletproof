## BULLETPROOF [![Build Status](https://travis-ci.org/samayo/bulletproof.svg?branch=master)](https://travis-ci.org/samayo/bulletproof.svg?branch=master)
[![Latest Stable Version](https://poser.pugx.org/bullet-proof/image-uploader/v/stable.svg)](https://packagist.org/packages/bullet-proof/image-uploader) [![Latest Unstable Version](https://poser.pugx.org/bullet-proof/image-uploader/v/unstable.svg)](https://packagist.org/packages/bullet-proof/image-uploader) [![License](https://poser.pugx.org/bullet-proof/image-uploader/license.svg)](https://packagist.org/packages/bullet-proof/image-uploader)    
=======================================

A single-class library to upload, crop, resize and watermark images in PHP with a bulletproof security.

### INSTALL
using git
````bash
git clone https://github.com/samayo/bulletproof.git
````
using composer
````bash
php composer.phar require samayo/bulletproof:2.0.*
````
or directly download as zip format from http://...

Assuming your HTML form looks like this. 
<form method="POST" enctype="multipart/form-data">
	<input type="hidden" name="MAX_FILE_SIZE" value="1000000"/>
	<input type="file" name="ikea"/>
	<input type="submit" value="upload"/>
</form>
````
Simply require the class and do yo thang
````php 
<?php
require_once  "src/bulletproof.php";

$image = new Bulletproof\Image($_FILES);
#### Simple example

/* check $_FILES['name']['ikea'] exists */
if($image["ikea"]){

	// upload the image
	$upload = $image->upload(); 

	// $upload returns false for failure
	if($upload){
		// OK
	}else{
		echo $image["error"]; // check for errors
	}
}
````
Thanks to the default configurations set inside the Bulletproof class, the above is a simple way to upload an image with all the security you need, including some image dimention, size, mime type limits. 
	Also image renaming, and folder creation (for image storage) are handled on the fly. To override all these settings, keep reading.

### 5 setter methods for configuration
Below are 5 methods to help you check for image dimension, size and mime type also for changing image name, and creating folder for storage.
````php  
// pass string if you want image to be renamed
->setName($name); 

// set min/max upload size limit (in bytes) 
->setSize($min, $max); 

// define acceptable mime types (in array)
->setMime(array($jpeg, $gif));  

// pass string name to create folder and optional chmod 
->setLocation($folderName, $optionalPermission); 

// set max width/height limit in pixels
->setDimension($min, $max);  
````
### 8 getter methods for getting image info
Following methods help you get some info about image, before or after upload. 
````php 
// get image name
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
### Example 1: some getters and setters in action 
````php 
<?php 
$image = new Bulletproof\Image($_FILES);

/* let's call some setters */
$image->setName('kitten')
      ->setMime(['png', 'gif'])
      ->setLocation('lolz');

if($image['ikea']){

	if($image->upload()){

		/* get some info after upload */
		echo $image->getName(); // kitten
		echo $image->getMime(); // gif
		echo $image->getLocation(); // lolz
		echo $image->getFullPath(); // lolz/kitten.gif
	}
}
```` 
### Example 2: create your own messages / errors. 
To use you own error messages instead of the ones set by default, use 
exceptions as seen below
````php 
<?php  

if($image["ikea"]){
	
	if($image->getSize() > 10000){
		throw new \Exception('image too tall'); 
	}

	if($image->getMime() !== 'png'){
		throw new \Exception('sorry we only accept png'); 
	}

	if($image->getHeight() > 1000){
		throw new \Exception('image too tall'); 
	}

	if ($image->getLocation() != 'images') {
		// you can also do this \0/
		$image->setLocation('images'); 
	} 
	
	$upload = $image->upload(); 
	// since we did not assign a name, it will be auto generated
	echo $upload->getFullPath(); // images/12212343_dasdasdasdadas.gif


}
````
#### Why is this library secure? 
* uses `exif_imagetype()` to get the true image `.extension` / mime type
* uses `getimagesize();` to check if image has a valid width/height measurable in pixels.
* Strips invalid characters from image name.  
* Generates folder with chmod ugo+rw for storage.


#### Todo
* <del> Allow Image Resizing </del> Done.
* <del> Allow Image Watermarking </del> Done.
* <del> Allow Image Cropping </del> Done.
* <del> Handle Errors with Exceptions </del> Done.
* <del> Backward compatability for PHP 5.3 </del> Done. 
* [Single Responsibility Principle](http://en.wikipedia.org/wiki/Single_responsibility_principle) ain't gonna happen. 

#### License  
MIT