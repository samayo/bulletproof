### ImageUploader
###A newbie-friendly php class to upload images, securely.

````php
require_once 'ImageUpload.php';
$newUpload = new BulletProof\ImageUploader();


/**
 * Example 1: Upload images by forcing user to upload certain/fixed sizes.
 */
if($_FILES){
    $result = $newUpload
        ->fileTypes(array("jpg", "gif")) //mention only the type of files, to be uploaded.
        ->fileSizeLimit(array("min"=>10, "max"=>30000)) //the file size in bytes. ! 30000 bytes = 30kb
        ->imageDimension("max-height"=>450, "max-width"=>550) //height and width of image in pixels
        ->uploadTo('uploads/') //the directory upload the images into
        ->save($_FILES['logo'], 'my_profile'); //the file to upload, and a new file name
        echo $result;  
}
````
````php
/**
 * Example 2: Crop/Resize images before uploading. 
 */
if($_FILES){
    $result = $newUpload
        ->fileTypes(array("jpg", "gif", "png", "jpeg"))
        ->resizeImage(array("height"=>100, "width"=>100)) //<-- this allows to resize all image to 100x100px
        ->fileSizeLimit(array("max"=>900000, "min"=>100))
        ->uploadTo('uploads/')
        ->save($_FILES['logo']);
        echo $result;  
    }
````
````php
/**
 * Example 3: Upload non image files. ex: .mp3
 */
if($_FILES){
    $result = $newUpload
        ->fileTypes(array("mp3"))
        ->fileSizeLimit(array("max"=>900000, "min"=>100))
        ->uploadTo('uploads/')
        ->save($_FILES['mp3']);
        echo $result;  
    }
````

#### What makes this secure?

* Checks if there are any errors in `$_FILES[]['error']`.
* Uses `pathinfo($_FILES['name'], PATHINFO_EXTENSION)` method to get the *real* file extension/Mime type,
* Checks if MIME type exists in the expected file types ie. `array('jpg', 'png', 'gif', 'jpeg')`
* Checks `getimagesize();` to see if the image has a width/height measurable in pixels, only images have pixels
* It uses `is_uploaded_file()`to check for secure upload HTTP Post method .(another way of security check)


#### License ?

Screw licenses. I would love any feedbacks though.

###TODO? 
* Option to force resize files
* Option to watermark images
* handle errors with exceptions 
