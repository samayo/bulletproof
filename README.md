## BULLETPROOF [![Build Status](https://travis-ci.org/samayo/bulletproof.svg?branch=master)](https://travis-ci.org/samayo/bulletproof.svg?branch=master)

[![Latest Stable Version](https://poser.pugx.org/samayo/bulletproof/v/stable.svg)](https://packagist.org/packages/bullet-proof/image-uploader) [![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/samayo/bulletproof/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/samayo/bulletproof/?branch=master)


Bulletproof is a single-class library to securely upload images in PHP.    

Additionally, there are separate functions to help you resize, shrink, watermark images. 

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
Or [download it manually][bulletproof_archive] based on the archived version of release-cycles.

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
```php 
require_once  "path/to/bulletproof.php";

$image = new Bulletproof\Image($_FILES);

if($image["ikea"]){

	if($image->upload()){
		// OK
	}else{
		echo $image["error"]; 
	}
}
```
##### Setting Properties
Methods for defining allowed size, dimensions, mime types, location and image name
````php  
// call if you want to set new image name manually
$image->setName($name); 

// define min/max size limits for upload (size in bytes) 
$image->setSize($min, $max); 

// define acceptable mime types
$image->setMime(array($jpeg, $gif));  

// set max width/height limits (in pixels)
$image->setDimension($width, $height); 

// pass name (and optional chmod) to create folder for storage
$image->setLocation($folderName, $optionalPermission);  
````

##### Getting Properties
Methods for getting image info before and / or after upload. 
````php 
// get the provided or auto-generated image name
$image->getName();

// get the image size (in bytes)
$image->getSize();

// get the image mime (extension)
$image->getMime();

// get the image width in pixels
$image->getWidth();

// get the image height in pixels
$image->getHeight();

// get image location (folder where images are uploaded)
$image->getLocation();

// get the full image path. ex 'images/logo.jpg'
$image->getFullPath();

// get the json format value of all the above information
$image->getJson();
````
##### Setting and Getting values, .. 
To set and get image info, before or after image upload, use as: 
````php 
$image = new Bulletproof\Image($_FILES);

$image->setName("samayo")
      ->setMime(["png", "gif"])
      ->setLocation("avatars");

if($image["ikea"]){
	if($image->upload()){
		echo $image->getName(); // samayo
		echo $image->getMime(); // gif
		echo $image->getLocation(); // avatars
		echo $image->getFullPath(); // avatars/samayo.gif
	}
}
```` 
##### Creating custom responses
To create your own errors and responses, instead of the default error messages, use exceptions:
````php 
 try{

   if($image->getMime() !== "png"){
      throw new \Exception(" Image should be a 'png' type ");
   }

   if($image->getSize() < 1000){
      throw new \Exception(" Image size too small ");
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

##### Image Manipulation
To watermark, crop, resize images, checkout the separate list of functions in [`src/utils`][utils]

##### What makes this secure?  
* Uses **[`exif_imagetype()`][exif_imagetype_link]** to get the true image mime (`.extension`)
* Uses **[`getimagesize()`][getimagesize_link]** to check if image has a valid height / width in pixels.
* Sanitized images names, strict folder permissions and more... 

##### License: MIT
[utils]: https://github.com/samayo/bulletproof/tree/master/src/utils
[bulletproof_archive]: http://github.com/samayo/bulletproof/releases
[exif_imagetype_link]: http://php.net/manual/de/function.exif-imagetype.php
[getimagesize_link]: http://php.net/manual/en/function.getimagesize.php
