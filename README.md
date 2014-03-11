# BULLETPROOF
#### SECURE PHP IMAGE UPLOADER
This class allows you to do two things.
First is to **upload image while** cropping/shrinking and watermarking the image
The second is, to do the same as above but without uploading.
It means, you can crop/resize/watermark any image any time.
Please check examples.php for a complete guide.

##### **Enable** `php_exif` extension in your php.ini before using this class.
=====
##### INCLUDING / INSTANTIATING THE CLASS
````php
/** As usual: Require and call the class only */

require_once "BulletProof.php";
$bulletProof = new ImageUploader\BulletProof;
````

##### SCENARIO 1: Uploading images with default settings. (Less code)
````php
/*
 *  This will use the default settings of the class and will upload only
 *  (jpg, gif, png, jpeg) images with size of from 0.1kb to max 30kbs
 *  It will also create a folder called "uploads" if it does not exist.
 */ 
if($_FILES){
    echo $bulletProof->upload($_FILES['picture']);
 }
````

##### SCENARIO 2: Upload images with specific size/type/dimensions (Moaarr code)
````php
/*
 * fileTypes() - What type of images to upload
 * limitSize() - Set the min and max image size limit (in bytes)
 * limitDimension() - Set the max height and width of image  (in pixels)
 * folder() - set a folder to store the uploads. It will be created automatically.
 * upload() - the final method that checkers everything and uploads the file
 *    The variable $bulletProof will contain the folder/name of the file.
 *    So, you can simply store it in db or echo it like  <img src='$bulletProof' />;
 */
echo $bulletProof
        ->fileTypes(array("png", "jpeg"))
        ->limitSize(array("min"=>1000, "max"=>100000))
        ->limitDimension(array("height"=>100, "width"=>100));
        ->folder("my_pictures")
        ->upload($_FILES['picture']);
````

##### SCENARIO 3: Shrink and upload image
````php
/*
 * shrink() - will shrink/resize the image to the given dimension
 */
$bulletProof
    ->fileTypes(array("jpg", "gif", "png", "jpeg"))
    ->shrink(array("height"=>100, "width"=>200))
    ->folder("shrinked_images")
    ->upload($_FILES["pictures"]);
````

##### SCENARIO 4: Watermark and update image
````php
/*
 * watermark() - will accept two arguments.
 *   First is the the image to use as watermark. (best to use PNG)
 *   second is the location where to put your watermark on the image.
 *   ex: 'center', 'bottom-right', 'bottom-left', 'top-left'...
 */
$bulletProof
    ->fileTypes(array("jpeg"))
    ->watermark('watermark.png', 'bottom-right'))
    ->folder("watermarked")
    ->upload($_FILES['logo']);
````


##### SCENARIO 5: Crop and upload image
````php
/*
 * crop() -width and height (in pixels) to crop the image.
 * crop is not like shrink, it simply will trim/crop the image
 * and return what is left, whereas shrink will not trim the image.
 */
$bulletProof
    ->fileTypes(array("jpeg"))
    ->crop(array("height"=>40, "width"=>50))
    ->folder("croped_folder")
    ->upload($_FILES['logo']);
````

Please check the examples.php for more functions and all tested examples.


#### NOTE:
 The `upload()` method accepts two arguments. First is the image, second (optional) is a new name for the image.
 If you provide a name, the image will be named accordingly, if not a unique name will be generated.
````php
// Uploaded file will be renamed 'cheeez' plus the file mime type.
->upload($_FILES['fileName'], 'cheeez');

// file will be named ex '1531e4b0e3bc82_EHIOLMPKQNJGF' plus the mime type
->upload($_FILES['fileName']);
````

The `change()` method is different from `upload()` and should not be mixed.
The `change()` will allow you to directly crop/resize/watermark an image that is already uploaded.

```php
// The change method will allow you to manipulate any image any time.

//CROP IMAGES
$change = $bulletProof
 	->crop(array("height"=>10, "width"=>10))
 	->change("crop", "my_pictures/awesome.gif");

// WATERMARK IMAGES
$change = $bulletProof
 	->watermark("logo.png", "center")
 	->change("watermark", "my_pictures/passport.gif");

// SHRINK IMAGES
$change = $bulletProof
 	->shrink(array("height"=>30, "width"=>50))
 	->change("shrink", "my_pictures/paris.jpg");
````

### What makes this secure?
* It checks & handles any errors thrown by `$_FILES[]['error']`.
* It uses `exif_imagetype()` method to get the *real* mime/image type,
* Checks if MIME type exists in the expected image types ie. `array('jpg', 'png', 'gif', 'jpeg')`
* Checks `getimagesize();` to see if the image has a valid width/height measurable in pixels.
* It uses `is_uploaded_file()` to check for secure upload HTTP Post method .(extra security check)



#### Whats next?
* <del>Allow image resizing</del> Done!
* <del>Allow image watermarking</del> Done! 
*  Allow text watermarking <-- discontinued!
* <del> Allow image cropping </del> Done!
* <del> Handle errors with exceptions </del> Done!



###License  
[Luke 3:11](http://www.kingjamesbibleonline.org/Luke-3-11/)
