## BULLETPROOF ![](https://github.com/samayo/bulletproof/actions/workflows/php.yml/badge.svg)

[![Latest Stable Version](https://poser.pugx.org/samayo/bulletproof/v/stable.svg?format=flat-square)](https://packagist.org/packages/samayo/bulletproof) [![Total Downloads](https://poser.pugx.org/samayo/bulletproof/downloads?format=flat-square)](https://packagist.org/packages/samayo/bulletproof?format=flat-square) [![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/samayo/bulletproof/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/samayo/bulletproof/?branch=master)  [![Gitter chat](https://img.shields.io/badge/gitter-join--chat-blue.svg)](https://gitter.im/fastpress/fastpress) [![License](https://poser.pugx.org/samayo/bulletproof/license)](https://packagist.org/packages/fastpress/framework)

Bulletproof is a single-class library to upload images in PHP with security.

Install
-----

Using git
```bash
$ git clone https://github.com/samayo/bulletproof.git
```
Or composer
```bash
$ composer require samayo/bulletproof:4.0.*
```
Or [download it manually][bulletproof_archive] based on the archived version of release-cycles.

Usage
-----

Create an HTML form like this. 
```html
<form method="POST" enctype="multipart/form-data">
  <input type="hidden" name="MAX_FILE_SIZE" value="1000000"/>
  <input type="file" name="pictures" accept="image/*"/>
  <input type="submit" value="upload"/>
</form>
```
And copy & paste the following code to upload the image
```php 
require_once  "path/to/bulletproof.php";

$image = new Bulletproof\Image($_FILES);

if($image["pictures"]){
  $upload = $image->upload(); 

  if($upload){
    echo $upload->getFullPath(); // uploads/cat.gif
  }else{
    echo $image->getError(); 
  }
}
```
For more flexibility, check the options and examples below.


Configs
-----

#### Setting Properties
Before uploading, you can use these methods to restrict the image size, dimensions, mime types, location...
```php  
// Pass a custom name, or it will be auto-generated
$image->setName($name);

// define the min/max image upload size (size in bytes) 
$image->setSize($min, $max);

// define allowed mime types to upload
$image->setMime(array('jpeg', 'gif'));

// set the max width/height limit of images to upload (limit in pixels)
$image->setDimension($width, $height);

// pass name (and optional chmod) to create folder for storage
$image->setLocation($folderName, $optionalPermission);
```

#### Getting Properties
Methods for getting image info before/after upload. 
```php 
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
```

#### Customized example
This will set image constrains and return output after upload
```php 
$image = new Bulletproof\Image($_FILES);

$image->setName("samayo")
      ->setMime(["gif"])
      ->setLocation(__DIR__ . "/avatars");

if($image["pictures"]){
  if($image->upload()){
    echo $image->getName(); // samayo
    echo $image->getMime(); // gif
    echo $image->getLocation(); // avatars
    echo $image->getFullPath(); // avatars/samayo.gif
  }
}
``` 

#### Image Manipulation
To crop, resize or watermak images, use functions stored in [`src/utils`][utils]

#### Creating custom errors
Use php exceptions to define custom error responses
```php 
if($image['pictures']){
  try {
    if($image->getMime() !== 'png'){
      throw new \Exception('Only PNG image types are allowed');
    }

    // check size, width, height...

    if(!$image->upload()){
      throw new \Exception($image->getError());
    } else {
      echo $image->getFullPath();
    }
    
  } catch (\Exception $e){
    echo "Error " . $e->getMessage();
  }
}
```

#### What makes this secure?  
* Uses **[`exif_imagetype()`][exif_imagetype_link]** to get the true image mime (`.extension`)
* Uses **[`getimagesize()`][getimagesize_link]** to check if image has a valid height / width in pixels.
* Sanitized images names, strict folder permissions and more... 

### License: MIT
[utils]: https://github.com/samayo/bulletproof/tree/master/src/utils
[bulletproof_archive]: http://github.com/samayo/bulletproof/releases
[exif_imagetype_link]: http://php.net/manual/de/function.exif-imagetype.php
[getimagesize_link]: http://php.net/manual/en/function.getimagesize.php
