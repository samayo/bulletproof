## BulletProof
============
#### A 100% Free, Fast, Simple and Secure, image/file uploading class.


You can upload any type of image/file although this class is preferably made for image upload.
by doing simply as: 
````php
$Obj = new BulletProof(array('jpg', 'png', 'gif', 'jpeg'), //accept only these type of files
                       array('max-height'=>150, 'max-width'=>150), //accept only dimensions specified here
                       array('max-size'=>40000, 'min-size'=>1), //accept only in-between these file
                       'uploads/'); //move all uploaded files into this directory. 

if($_FILES){
   $result = $Obj->upload($_FILES['logo'], 'passport-pic'); //name the file/image as 'passport-pic'
        echo $result; //passport_pic.jpg
}
````
Remember, if you omit the `$newName` argument from the `upload($fileName, $newName)` then, the class itself will 
generate and return a `74` digit randome + unique name to identify/submit into your database. 

Another thing to remember is that, if you used the script as show above, then all upload made by user will have to be
as same as specified by the constructor, if you don't want this, and need a seperate setting for another file upload 
on another page maybe, then you can do method-chaining wich will override any existing setting. Example:
````php
$Obj = new BulletProof(array('jpg', 'png', 'gif', 'jpeg'));
if($_FILES){
    $result = $Obj->setImageDimensions(array('max-height'=>150, 'max-width'=>150))
        ->setFileSize(array('max-size'=>4000, 'min-size'=>1))
        ->setUploadDir('uploads/')
        ->upload($_FILES['logo']);
    echo $result; //242i42923.jpg
}
````
Now with the above method, you can tell the script what to upload, when, how anytime you like. 



###What make this a bulletProof? 

* It checks the for all errors thrown by the `$_FILES[]['error']` array. 
* It uses the `splFileInfo::getExtension()` method to get the real file extension/Mime type, `$_FILES[]['type']` is a plus
* Checks if file type exists inside what user is only willing to accept. `array('jpg', 'png', 'gif', 'jpeg')`
* Checks `getimagesize($fileToUpload['tmp_name']);` to see the image has a width/height measurable in pixels. 
* It uses `is_uploaded_file($fileToUpload['tmp_name'])` to check if file is uploaded through HTTP Post.(another way of security check)








