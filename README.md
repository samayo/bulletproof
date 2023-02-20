## BULLETPROOF [![Test](https://github.com/samayo/bulletproof/actions/workflows/php.yml/badge.svg)](https://github.com/samayo/bulletproof/actions/workflows/php.yml)

[![Latest Stable Version](https://poser.pugx.org/samayo/bulletproof/v/stable.svg?format=flat-square)](https://packagist.org/packages/samayo/bulletproof) [![Total Downloads](https://poser.pugx.org/samayo/bulletproof/downloads?format=flat-square)](https://packagist.org/packages/samayo/bulletproof?format=flat-square) [![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/samayo/bulletproof/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/samayo/bulletproof/?branch=master)  [![License](https://poser.pugx.org/samayo/bulletproof/license)](https://packagist.org/packages/fastpress/framework)

Bulletproof is a single-class PHP library to upload images securely.

Installation
-----

Install using git
```bash
$ git clone https://github.com/samayo/bulletproof.git
```
Install using Composer
```bash
$ composer require samayo/bulletproof:5.0.*
```
Or [download it manually][bulletproof_archive] in a ZIP format

Usage
-----

To quickly upload images, use the following HTML & PHP code:

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
For more options or configurations, check the following examples:


Configs
-----

#### Setting Properties
Methods to set restriction on the image name, size, type, etc.. to upload
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

#### Getting Properties
Methods to retrieve image data before/after upload. 
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

#### Extended Configuration Usage
How to use the property setters and getters. 
```php 
$image = new Bulletproof\Image($_FILES);

$image->setName("samayo")
      ->setMime(["gif"])
      ->setStorage(__DIR__ . "/avatars");

if($image["pictures"]){
  if($image->upload()){
    echo $image->getName(); // samayo   
    echo $image->getMime(); // gif
    echo $image->getStorage(); // avatars
    echo $image->getPath(); // avatars/samayo.gif
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
      echo $image->getPath();
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
[bulletproof_archive]: https://github.com/samayo/bulletproof/releases
[exif_imagetype_link]: https://php.net/manual/function.exif-imagetype.php
[getimagesize_link]: https://php.net/manual/function.getimagesize.php
