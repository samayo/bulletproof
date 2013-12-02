## BulletProof
============
#### A Free, Fast, Simple and Secure, image/file uploading class.

You can upload any type of image/file but I recommend you use it to upload only images for now, 
as that is the reason why I made it, and tested it so far, even though it should upload any file.

Simple uploading process id done by, first declaring your set of commands through the constructor and calling the 
`upload()` method: 
````php
$Obj = new BulletProof(array('jpg', 'png', 'gif', 'jpeg'), //accept only these type of files
                       array('max-height'=>150, 'max-width'=>150), //accept only dimensions specified here
                       array('max-size'=>40000, 'min-size'=>1), //accept only in-between these file sizes
                       'uploads/'); //move all uploaded files into this directory. 

if($_FILES){
   $result = $Obj->upload($_FILES['logo'], 'passport-pic'); //name the file/image as 'passport-pic'
        echo $result; //passport_pic.jpg
}
````
Remember, if you omit the `$newName` argument from the `upload($fileName, $newName)` then, the class itself will 
generate and return a `74` string long random number combined with unique id to avoid collision as the name of the file, for you to make use of. (store in db, echo ... whatever..)

Another thing to remember is that, if you used the script as shown above, then all upload made by user will have to be
as same as specified by the constructor, (image height, width, size, upload dir) same settings will be used for different uploads. Sometimes this may not be the case, as you will only upload `100*100` for profile image, but `900*85` for a banner. SO,  If you don't want these restrictions, and need a seperate setting for another file upload on another page maybe, then you can do method-chaining wich will override any existing directives. Example:
````php
$Obj = new BulletProof(array('jpg', 'png', 'gif', 'jpeg'));
if($_FILES){
    $result = $Obj->setImageDimensions(array('max-height'=>150, 'max-width'=>150))
        ->setFileSize(array('max-size'=>4000, 'min-size'=>1))
        ->setUploadDir('uploads/')
        ->upload($_FILES['logo']);
    echo $result; //345212631129223425311217529118879612810120122102746529cc1c8d909c1.40357962.jpg
}
````
Now with the above method, you have only made one global setting, i.e. the file type you are willing to accept (which is very important enought to be made global) after that, you can tell the script what to upload, when, how anytime by accessing the `upload()`. method.  



### What makes this a bulletProof? 

* It checks the for all errors thrown by the `$_FILES[]['error']` array. 
* It uses the `splFileInfo::getExtension()` method to get the real file extension/Mime type, `$_FILES[]['type']` is a plus
* Checks if the file/Mime type exists, inside the option given by you i.e. `array('jpg', 'png', 'gif', 'jpeg')`
* Checks `getimagesize($fileToUpload['tmp_name']);` to see if the image has a width/height measurable in pixels, if not then it is unlikely to be an image. 
* It uses `is_uploaded_file($fileToUpload['tmp_name'])` to check if file is uploaded through HTTP Post.(another way of security check)


#### License ? 

Screw licenses. I would love any feedbacks though. 

#### Whats next ? 
If I am still enthusiastic about this for the next couple of days, I will add costume exception handler, to better handle errors, and watermarking capabilities for images. 
