### ImageUploader
###A newbie-friendly php class to upload images, securely.

As usuall, first simply require the file, and instantiate the class. 
````php
require_once 'ImageUploader.php';
$newUpload = new BulletProof\ImageUploader();
````
Example 1: Upload images with specific width and height only. 
````php
if($_FILES){
$result = $newUpload
    ->fileTypes(array("jpg", "gif"))  
    ->fileSizeLimit(array("min"=>10, "max"=>30000))  
    ->imageDimension("max-height"=>450, "max-width"=>550) #height & width of file in pixels
    ->uploadTo('uploads/')  
    ->save($_FILES['logo'], 'new_name'); 
        echo $result;  #new_name.gif
}
````
Example 2: Crop/Resize images before uploading. 
````php
$result = $newUpload
    ->fileTypes(array("jpg", "gif", "png", "jpeg"))
    ->fileSizeLimit(array("max"=>900000, "min"=>100))
    ->resizeImage(array("height"=>100, "width"=>100)) # crop/resize image to 100x100px
    ->uploadTo('uploads/')
    ->save($_FILES['logo']); 
        echo $result; #1118921069587715213410141132611529ff56cbb7e5.jpg
````
Example 3: Upload non image files. ex: .mp3
````php
$result = $newUpload
    ->fileTypes(array("mp3")) //chose file type 
    ->fileSizeLimit(array("max"=>900000, "min"=>100))
    ->uploadTo('uploads/')
    ->save($_FILES['mp3'], 'my_song');
        echo $result; #my_song.mp3
````
Remember, the `save()` method accepts two arguments. i.e. `->save($fileToUpload, $renameFile = null)`
depending on your needs, you may rename or leave the file to be rename as shown in the two examples.
````php
->save($_FILES['fileName'], 'cheeez') #Uploaded file will be renamed 'cheeez' .jpg/.png/.gif ..
````
````php
->save($_FILES['fileName']) #file will be named automatically ex '1118921069587715213410141132611529ff56cbb7e5.jpg'
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
