### ImageUploader
###A newbie-friendly php class to upload images, securely.

````php
/**
 * As usuall, first simply require the file, and instantiate the class. 
 */
require_once 'ImageUploader.php';
$newUpload = new BulletProof\ImageUploader();
````

````php
/**
 * Example 1: Upload images with specific width and height only. 
 */
if($_FILES){
$result = $newUpload
    ->fileTypes(array("jpg", "gif")) //file types to accept.
    ->fileSizeLimit(array("min"=>10, "max"=>30000)) //min - max file size in bytes
    ->imageDimension("max-height"=>450, "max-width"=>550) //height vs width of file in pixels
    ->uploadTo('uploads/') //the folder to upload the file
    ->save($_FILES['logo'], 'my_profile'); //the file to upload, and a new file name
        echo $result;  //my_profile.gif
}
````
````php
/**
 * Example 2: Crop/Resize images before uploading. 
 */
if($_FILES){
$result = $newUpload
    ->fileTypes(array("jpg", "gif", "png", "jpeg"))
    ->resizeImage(array("height"=>100, "width"=>100)) // Forcibly crop/resize image to 100x100px
    ->fileSizeLimit(array("max"=>900000, "min"=>100))
    ->uploadTo('uploads/')
    ->save($_FILES['logo']);
        echo $result; //1118921069587715213410141132611529ff56cbb7e5.jpg
    }
````
````php
/**
 * Example 3: Upload non image files. ex: .mp3
 */
if($_FILES){
$result = $newUpload
    ->fileTypes(array("mp3")) //chose file type 
    ->fileSizeLimit(array("max"=>900000, "min"=>100))
    ->uploadTo('uploads/')
    ->save($_FILES['mp3']);
        echo $result; //1613101516119211154412082387197529ff52d2fa04.mp3
    }
````

#### What makes this secure?
* It checks & handles any errors thrown by `$_FILES[]['error']`.
* It uses `pathinfo($fileName, PATHINFO_EXTENSION)` method to get the *real* file extension/Mime type,
* Verify if MIME type exists in the expected file types ie. `array('jpg', 'png', 'gif', 'jpeg')`
* Checks `getimagesize();` to see if the image has a valid width/height measurable in pixels.
* It uses `is_uploaded_file()` to check for secure upload HTTP Post method .(extra security check)



###What is next? 
* <del>Option to force resize files</del> Done!
* Option to watermark images
* handle errors with exceptions 
