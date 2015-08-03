Image Manipilation functions. 
----- 
This folder contains a function-per-file lists for changing images. 

Currently, you can find a `resize()`, `crop()`, and `watermark()` functions.  

> **Note** These functions can be use with\without bulletproof. 


##### Resizing
```php 
// include bulletproof
require  "path/to/bulletproof.php";
// include image resize function
require 'src/utils/func.image-resize.php';

$image = new Bulletproof\Image($_FILES);

if($image["picture"]){
	// upload the image
	$upload = $image->upload(); 

	if($upload){
		// get the image properties and change it to array. 
		$get = json_decode($image->getJson(), true); 

		// the resize function takes 8 self-describing arguments. 
		// check the function on how to resize based on ratio
		$resize = Bulletproof\resize(
			$get['fullpath'], 
			$get['mime'],
			$get['width'],
			$get['height'],
			50,
			50
		);

		// now the uploaded image is cropped to 50x50 pixels. 
	}
}
```
#### Croping
```php 
	// include image crop function
	require 'src/utils/func.image-crop.php';
	
	// assuming the image is uploaded
	if($upload){
		// get the image properties and change it to array. 
		$get = json_decode($upload->getJson(), true); 

		// the crop function takes 6 self-describing arguments
		$crop = Bulletproof\crop(
			$get['fullpath'], 
			$get['mime'],
			$get['width'],
			$get['height'],
			50,
			50
		);

		// now the uploaded image is cropped to 50x50 pixels. 
	}
```
#### Watermark
```php 
// require the watermark function
require 'src/utils/func.image-watermark.php';

// assuming the image is uploaded
if($upload){
	// get full path of logo you want to use
    $logo = 'my-logo.png';
    // get the width and heigh of the logo
	list($logoWidth, $logoHeight) = getimagesize($logo);

	// watermark accepts 8 arguments. 
	// final arg is for where to place the watermark
	$watermark = Bulletproof\watermark(
			$get["fullpath"], 
			$get["mime"],
			$get["width"], 
			$get["height"],
			$logo, 
			$logoHeight,
			$logoWidth,
			"bottom-right"
		);
}
```
