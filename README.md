###  Secure PHP Image & File Uploader. 
##### Don't use this class yet. Wait for better, high-fying, state-of-the-art, industry-standard release :). 


#### Including / Instanciating the class 
````php
/** As usuall, first simply require the file, and instantiate the class.  */ 
require_once 'ImageUploader.php';
$newUpload = new BulletProof\ImageUploader();
````

#### Example 1: Uploading images with default settings. (Less code) 
````php
/**
 *  This will use the default settings of the class and will upload only
 *  (jpg, gif, png, jpeg) images with size of from 0.1kb to max 30kbs 
 */ 
if($_FILES){
$result = $newUpload
    ->uploadTo('uploads/')  
    ->save($_FILES['logo']); 
    echo $result; /** this will give you the file name to store/echo using <img> tag. **/
}
````
#### Example 2: Upload images with specific size/type/dimensions (Moaarr code)
````php
/** Will upload given filestypes, size and image size as shown here. **/
$result = $newUpload
    ->setFileTypes(array("jpg", "gif", "png", "jpeg"))
    ->setSizeLimit(array("min"=>1000, "max"=>100000))
    ->resizeImageTo(array("height"=>100, "width"=>100))
    ->uploadTo('uploads/')
    ->save($_FILES['logo']); 
````
#### Example 3: Upload images and resize
````php
/** the resizeImageTo() method resizes any image to what is specified. **/
$result = $newUpload
    ->setFileTypes(array("jpg", "gif", "png", "jpeg"))
    ->setSizeLimit(array("min"=>1000, "max"=>100000))
    ->resizeImageTo(array("height"=>100, "width"=>200))
    ->uploadTo('uploads/')
    ->save($_FILES['logo']); 
````
#### Example 4: Upload images after adding watermark
````php
/** the watermark() method accepts image/text to watermark and position (where to watermark it) **/
$result = $newUpload
    ->setFileTypes(array("jpg", "gif", "png", "jpeg"))
    ->setSizeLimit(array("min"=>1000, "max"=>100000))
    ->watermark('watermark.png', 'bottom-right'))
    ->uploadTo('uploads/')
    ->save($_FILES['logo']); 
````

#### Things to notice
 The `save()` method accepts two arguments. First is the file, second (optional) is a new name for the file
 If you provide a name, file will be named accordingly, if not a unique name will be generated. 
````php
/** Uploaded file will be renamed 'cheeez' plus the file extension **/
->save($_FILES['fileName'], 'cheeez'); 
/** file will be named ex '1118921069587715213410141132611529ff56cbb7e5' plus the file extension **/
->save($_FILES['fileName']); 
````

Image resizing is done by calculating the ratio of the given width and height. ex: `resizeImageTo(['height'=>100, 'width'=>100])` will not crop as specified, but will make sure the dimention of the image remain below the indicated size. If you upload an image with 800 x 400 it would be changed into 80x40 because those are below 100x100 ...

#### What makes this secure?
* It checks & handles any errors thrown by `$_FILES[]['error']`.
* It uses `pathinfo($fileName, PATHINFO_EXTENSION)` method to get the *real* file extension/Mime type,
* Verify if MIME type exists in the expected file types ie. `array('jpg', 'png', 'gif', 'jpeg')`
* Checks `getimagesize();` to see if the image has a valid width/height measurable in pixels.
* It uses `is_uploaded_file()` to check for secure upload HTTP Post method .(extra security check)



###What is next? 
* <del>Allow image resizing</del> Done!
* <del>Allow image watermarking</del> Done! 
* Allow text watermarking
* Handle errors with exceptions 





![FORK](http://i.imm.io/1m7EN.jpeg)     
Incase you have missed it. It means, you should not call methods like `->watermark()` or `resizeImageTo()` when you are uploading non-image files, such as .txt .mp3 files ... (As I have done that once..)


###License  
[Luke 3:11](http://www.kingjamesbibleonline.org/Luke-3-11/)
