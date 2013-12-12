### ImageUploader
###A newbie-friendly php class to upload images, securely.


---- READ: This is a work in progress, It works like a charm, but it is far from achieving its goal. So, comeback later for more updates.  ----       


#### Including / Instanciating the class 
````php
/** As usuall, first simply require the file, and instantiate the class.  */ 
require_once 'ImageUploader.php';
$newUpload = new BulletProof\ImageUploader();
````

#### Example 1: Uploading images with defalt settings. (Less code) 
````php
/** This will upload only (jpg, gif, png, jpeg) files with size between 100bytes t0 30kb **/ 
if($_FILES){
$result = $newUpload
    ->uploadTo('uploads/')  
    ->save($_FILES['logo']); 
}
````
#### Example 2: Uploading images with specific size/type/dimensions (Moaarr code)
````php
/** Will upload filestypes, size and image size as specified here. **/
$result = $newUpload
    ->setFileTypes(array("jpg", "gif", "png", "jpeg"))
    ->setSizeLimit(array("min"=>1000, "min"=>100000))
    ->setImageSize(array("height"=>100, "width"=>100))
    ->uploadTo('uploads/')
    ->save($_FILES['logo']); 
````
#### Example 3: Croping/resizing images and upload 

````php
/** the resizeImageTo() method resizes any image to what is specified here ie. (100px 200px) **/
$result = $newUpload
    ->setFileTypes(array("jpg", "gif", "png", "jpeg"))
    ->setSizeLimit(array("min"=>1000, "min"=>100000))
    ->resizeImageTo(array("height"=>100, "width"=>200))
    ->uploadTo('uploads/')
    ->save($_FILES['logo']); 
````
#### Example 4: Upload images after adding watermarks
````php
/** the watermark() method accepts image/text to watermark and position (where to watermark it) **/
$result = $newUpload
    ->setFileTypes(array("jpg", "gif", "png", "jpeg"))
    ->setSizeLimit(array("min"=>1000, "min"=>100000))
    ->watermark('watermark.png', 'bottom-right'))
    ->uploadTo('uploads/')
    ->save($_FILES['logo']); 
````

#### Things to notice

* The `save()` method accepts two arguments. i.e. `->save($fileToUpload, $renameFile = null)`
depending on your needs, you may rename or leave the file to be rename as shown in the two examples.
````php
->save($_FILES['fileName'], 'cheeez') #Uploaded file will be renamed 'cheeez' .jpg/.png/.gif ..
````
````php
->save($_FILES['fileName']) #file will be named ex '1118921069587715213410141132611529ff56cbb7e5.jpg'
````

#### What makes this secure?
* It checks & handles any errors thrown by `$_FILES[]['error']`.
* It uses `pathinfo($fileName, PATHINFO_EXTENSION)` method to get the *real* file extension/Mime type,
* Verify if MIME type exists in the expected file types ie. `array('jpg', 'png', 'gif', 'jpeg')`
* Checks `getimagesize();` to see if the image has a valid width/height measurable in pixels.
* It uses `is_uploaded_file()` to check for secure upload HTTP Post method .(extra security check)



###What is next? 
* <del>Option to force resize files</del> Done!
* <del>Option to watermark images<del> Done! 
* apply text watermark to images
* handle errors with exceptions 




![FORK](http://i.imm.io/1m2WW.png)



##License 
[Luke 3:11](http://www.kingjamesbibleonline.org/Luke-3-11/)
