## Suploader
============
DON'T USE YET! Still trying to improve/secure it more... 
````php
include 'Suploder.php';

$Obj = new Suploder;
if($_FILES){
      $result = $Obj->setFileType(array("jpg", "gif"))
                    ->setFileSize(array("min"=>1, "max"=>100))
                    ->setImageDimensions("max-height"=>450, "max-width"=>550)
                    ->setFolder('uploads/')
                    ->upload($_FILES['logo'], 'my_profile');
                    echo $result; //my_profile.jpg
}
````




### What makes this Secure? 

* It checks the for all errors thrown by the `$_FILES[]['error']` array. 
* It uses the `splFileInfo::getExtension()` method to get the real file extension/Mime type, `$_FILES[]['type']` is a plus
* Checks if the file/Mime type exists, inside the option given by you i.e. `array('jpg', 'png', 'gif', 'jpeg')`
* Checks `getimagesize($fileToUpload['tmp_name']);` to see if the image has a width/height measurable in pixels, if not then it is unlikely to be an image. 
* It uses `is_uploaded_file($fileToUpload['tmp_name'])` to check if file is uploaded through HTTP Post.(another way of security check)


#### License ? 

Screw licenses. I would love any feedbacks though. 

#### Whats next ? 
If I am still enthusiastic about this for the next couple of days, I will add costume exception handler, to better handle errors, and watermarking capabilities for images. 
