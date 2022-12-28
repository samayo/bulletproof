## BULLETPROOF [![Test](https://github.com/samayo/bulletproof/actions/workflows/php.yml/badge.svg)](https://github.com/samayo/bulletproof/actions/workflows/php.yml)

[![Latest Stable Version](https://poser.pugx.org/samayo/bulletproof/v/stable.svg?format=flat-square)](https://packagist.org/packages/samayo/bulletproof) [![Total Downloads](https://poser.pugx.org/samayo/bulletproof/downloads?format=flat-square)](https://packagist.org/packages/samayo/bulletproof?format=flat-square) [![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/samayo/bulletproof/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/samayo/bulletproof/?branch=master)  [![License](https://poser.pugx.org/samayo/bulletproof/license)](https://packagist.org/packages/fastpress/framework)

A single-file PHP library to upload images securely.

Installation
-----

Using git
```bash
$ git clone https://github.com/samayo/bulletproof.git
```
Using Composer
```bash
$ composer require samayo/bulletproof:5.1.*
```
Or [download it manually][bulletproof_archive] in a ZIP format

Usage
-----

Use the following example to quickly upload an image

```html
<form method="POST" enctype="multipart/form-data">
  <input type="hidden" name="MAX_FILE_SIZE" value="1000000"/>
  <input type="file" name="pictures" accept="image/*"/>
  <input type="submit" value="upload"/>
</form>
```
```php 
require_once  "path/to/bulletproof.php";

$image = new Bulletproof\Image($_FILES);

if($image["pictures"]){
  $upload = $image->upload(); 

  if($upload){
    echo $upload->getPath(); // uploads/cat.gif
  }else{
    echo $image->getError(); 
  }
}
```

Configuration
-----
Settings to upload images with more options

#### Set options
Settings used before uploading image to set options
```php  
// To provide a name for the image. If unused, image name will be auto-generated.
$image->setName($name);

// To set the min/max image size to upload (in bytes)
$image->setSize($min, $max);

// To define a list of allowed image types to upload
$image->setMime(array('jpeg', 'gif'));

// To set the max image height/width to upload (limit in pixels)
$image->setDimension($width, $height);

// To create a folder name to store the uploaded image, with optional chmod permission
$image->setStorage($folderName, $optionalPermission);
```

#### Get options
Settings used after uploading image to set options
```php 
// To get the image name
$image->getName();

// To get the image size (in bytes)
$image->getSize();

// To get the image mime (extension)
$image->getMime();

// To get the image width in pixels
$image->getWidth();

// To get the image height in pixels
$image->getHeight();

// To get image location (folder where images are uploaded)
$image->getStorage();

// To get the full image path. ex 'images/logo.jpg'
$image->getPath();

// To get the json format value of all the above information
$image->getJson();
```

#### Upload example with more options
How to use the property setters and getters. 
```php 
$image = new Bulletproof\Image($_FILES);

$image->setName("dog")
      ->setMime(["jpg"])
      ->setStorage(__DIR__ . "/uploads");

if($image["pictures"]){
  if($image->upload()){
    echo $image->getName(); // dog   
    echo $image->getMime(); // jpg
    echo $image->getStorage(); // uploads
    echo $image->getPath(); // uploads/dog.jpg
  }
}
``` 

#### Creating custom errors
How to use exceptions to catch errors
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
      $path = $image->getPath();
    }
    
  } catch (\Exception $e){
    echo "Image upload error: " . $e->getMessage();
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
