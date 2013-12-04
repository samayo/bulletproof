### ImageUploader
#####A newbie-friendly php class to upload images, securely.
````php
include 'ImageUpload.php';
$imageUploader = new ImageUploader();

if($_FILES){
    $result = $imageUploader
            ->setFileType(array("jpg", "gif")) //mention only the type of files, to be uploaded.
            ->setFileSize(array("min"=>10, "max"=>30000)) //the file size in bytes. ! 30000 bytes = 30kb
            ->setImageDimensions("max-height"=>450, "max-width"=>550) //height and width of image in pixels
            ->setFolder('uploads/') //the directory upload the images into
            ->upload($_FILES['logo'], 'my_profile'); //the file to upload, and a new file name
            
            echo $result; //my_profile.jpg
}
````




#### What makes this secure?

* Checks if there are any errors in  `$_FILES[]['error']`.
* Uses `splFileInfo::getExtension()` method to get the *real* file extension/Mime type,
* Checks if MIME type exists in the expected file types ie. `array('jpg', 'png', 'gif', 'jpeg')`
* Checks `getimagesize();` to see if the image has a width/height measurable in pixels, only images have pixels
* It uses `is_uploaded_file()`to check for secure upload HTTP Post method .(another way of security check)


#### License ?

Screw licenses. I would love any feedbacks though.

###TODO? 
* Option to force resize files
* Option to watermark images
* handle errors with exceptions 
