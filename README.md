## BULLETPROOF [![Build Status](https://travis-ci.org/samayo/bulletproof.svg?branch=master)](https://travis-ci.org/samayo/bulletproof.svg?branch=master)
[![Latest Stable Version](https://poser.pugx.org/bullet-proof/image-uploader/v/stable.svg)](https://packagist.org/packages/bullet-proof/image-uploader) [![Latest Unstable Version](https://poser.pugx.org/bullet-proof/image-uploader/v/unstable.svg)](https://packagist.org/packages/bullet-proof/image-uploader) [![License](https://poser.pugx.org/bullet-proof/image-uploader/license.svg)](https://packagist.org/packages/bullet-proof/image-uploader)    
=======================================

A one-class library to upload, crop, resize, watermark images in PHP with a bulletproof security. 

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

### Simple example

````php
<!-- Assuming you have a form like this.  -->
<form method="POST" enctype="multipart/form-data">
	<input type="hidden" name="MAX_FILE_SIZE" value="1000000" />
	<input type="file" name="ikea" />
	<input type="submit" value="upload" />
</form>

<?php
/* first require the bulletproof class  */
require_once  "src/bulletproof.php";

/* instantiate the class and pass $_FILES as seen here */
$image = new Bulletproof\Image($_FILES);

/* Check if $_FILE['ikea'] exists */
if($image["ikea"]){

	// upload the image
	$upload = $image->upload(); 

	// check upload status
	if($upload){
		// OK
	}else{
		echo $image["e"]; // see whats wrong
	}
}
````
Thanks to the default configurations set inside the Bulletproof class, that is the simplest way to upload an image with all the security you need, including image size, pixel, type limitations. It will also, create a folder for storage, and rename the uploaded image. If you want to take a total control over the image size, dimentions, image type, folder and name, you can use any of the total 17 public methods as seen below.

### Configuration setters 
# there are 5 public methods to allow you restrict uploads and change name, location. 
````php 
<?php 
// pass value if you want image to be renamed
// else image will have unique + random name
->setName($name); 

// define min/max upload size limit (in bytes) 
->setSize($min, $max); 

// define acceptable mime types (in arrays)
->setType(array($jpeg, $gif));  

// create/choose directory for image storage
->setLocation($folderName); 

// define max width/height limit in pixels
->setDimension($min, $max);  

//quick example. 
$image = new Bulletproof\Image($_FILES);

$image->setName('person');
$image->setSize(100, 1000);
$image->setType(['png', 'gif']);
$image->setLocation('images');
$image->setDimension(300, 400);

if($image['ikea']){
	// you can set or override again the settings here.
	$upload = $image->upload(); 

	if($upload){ 
		// OK
	}else{
		// ERR! 
	}
}
```` 
## 9 Methods for getting image/upload info
with the following method, you can get image info, before or after upload

// get image info, such as getting the size, width, mimetype 
// for database storage, or custom error handling.
````php
->getSize(); // get image size (in bytes)
->getName(); // get the image rename (without mime type)
->getWidth(); // get image width in pixels
->getHeight(); // get image height in pixels
->getFullPath(); // get full path ex: 'uploads/foo.png'
->getMime(); // get mimetype only ex: 'jpeg'
->getLocation(); // get location ex: 'uploads' 
->getJson(); //get json format of image info. (dir, mime, location, size, dimension...)
->getError(); // get upload error (if any)
````
The above methods can be used, for before and after. 
useful to handle errors by yourself, and get image data
````php
if($image["ikea"]){

	// check size
	if($image->getSize() > 10000){
		echo "Image must be less than 10kb"; 
	}

	// check image extension
	if($image->getMime() !== 'gif'){
		echo "sorry, we only accept gif images"; 
	}

	// check height
	if($image->getHeight() > 1000){
		echo "wow, take that wallpaper somewhere else :)"; 
	}

	// set upload location, and image name then upload
	$upload = $image->setLocation('uploads')->setName('simon')->upload(); 

	if($upload){
		// example values 
		echo $image->getName(); // simon
		echo $image->getSize() // 56630
		echo $image->getMime() // gif
		echo $image->getWidth() // 400 
		echo $image->getHeight() // 520 
		echo $image->getLocation() // uploads
		echo $image->getFullPath();  // uploads/simon.gif
		echo $image->getJson() //  a:6:{s:4:"name";s:28:"simon";s:6:"height";i:24;s:5:......}

		/* you can even add watermark to your image now. */
		$watermark = $image->watermark('logo.png', $image->getFullPath());

	}else{
		echo $image["e"]; 
	}
}

# asks / manipulation 

// methods used for image manipulation.
->crop(); // crop image
->upload(); // upload
->remove(); // delete image
->change();  // change without the need to upload
->resize(); // resize / shrink the image
->watermark(); // add watermark to an image
````
# Watermarking 
````php 
$image = new Bulletproof\Image($_FILES);

if($image["ikea"]){

	$logo = 'path/to/logo.png';

	$watermark = $image->watermark($logo, "center")->upload(); 

	if($watermark){

		$fullPath = $watermark->getFullPath(); 
	
	}else{
		echo $image["error"]; 
	}
}

````
#REMOVE 
````php 
$remove = $image->remove('/location/image.jpeg'); 
````

# CROP 
````php 

if($image["foo"]){
	$watermark = $image->watermark('logo.png', 'center')->upload(); 
	$crop = $image->crop(199, 100)->upload();

	if($upload){

	}else{

	}
}
````

# RESIZE 
````php 

if($image["foo"]){
	$watermark = $image->watermark('logo.png', 'center')->upload(); 
	
	/* if you want to keep the image ratio, pass third parameter as true*/
	$resize = $image->resize(100, 100)->upload(); 

	if($resize){

	}else{

	}
}
````

#### Why is this library secure? 
* It uses `exif_imagetype()` method to get the **real** image `.extension` (mime type)
* Uses `getimagesize();` to check if  image has a valid width/height measurable in pixels.
* Strips invalid characters from image name. 
* Generates folder with chmod 0666 for storage. 


#### Todo
* <del> Allow Image Resizing </del> Done.
* <del> Allow Image Watermarking </del> Done.
* <del> Allow Image Cropping </del> Done.
* <del> Handle Errors with Exceptions </del> Done.
* <del> Backward compatability for PHP 5.3 </del> Done. 
* [Single Responsibility Principle](http://en.wikipedia.org/wiki/Single_responsibility_principle) ain't gonna happen. 

#### License  
[Luke 3:11](http://www.kingjamesbibleonline.org/Luke-3-11/) ( Free; No license! )
