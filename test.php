<pre><form method="POST" enctype="multipart/form-data">
    <input type="hidden" name="MAX_FILE_SIZE" value="1000000"/>
    <input type="file" name="ikea"/>
    <input type="submit" value="upload"/>
</form>

<?php 


require_once  "src/bulletproof.php";

$image = new Bulletproof\Image($_FILES);

if($image["ikea"]){
    $image->setName('foo')->setLocation('dani');
    if($image->upload()){
        
// get the provided or auto-generated image name
echo " === name: " .$image->getName() . PHP_EOL;

// get the image size (in bytes)
echo " === size: " .$image->getSize() . PHP_EOL;

// get the image mimetype (extension)
echo " === mime: " .$image->getMime() . PHP_EOL;

// get the image width in pixels
echo " === width: " .$image->getWidth() . PHP_EOL;

// get the image height in pixels
echo " ===  height: " .$image->getHeight() . PHP_EOL;

// get image location or folder name
echo " === location: " .$image->getLocation() . PHP_EOL;

// get the full image path. ex 'images/logo.jpg'
echo " === fullpath: " .$image->getFullPath() . PHP_EOL;

// get the json format value of all the above information
echo " === json: " .$image->getJson() . PHP_EOL;
 

    }else{
        
    }
}

//var_dump($image["error"]);