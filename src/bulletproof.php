<?php
/**
 * BulletProof
 *
 * A one-file / one-class solution for a simple and very secure way of
 * uploading images in PHP, also included: Image watermarking, Cropping, Resizing 
 * with fluent queries, for validating and customizing 
 * mime, name, size, dimension, location, height, width ... 
 *
 * PHP support minimum 5.3
 *
 * Make sure to contribute whatever you can. 
 *
 * @package     BulletProof Image Upload, Manipulation Lib
 * @version     2.0.0
 * @author      SamaYo | @sama_io
 * @link        https://github.com/samayo/BulletProof
 * @license     Free | http://www.kingjamesbibleonline.org/Luke-3-11
 */
namespace BulletProof;

class Image implements \ArrayAccess, \Serializable
{

    protected $name       = null;  
    protected $width      = null;
    protected $height     = null; 
    protected $imageMime  = null; 
    protected $fullPath   = null;
    protected $location   = "bulletproof";
    protected $dimensions = array(444, 444);
    protected $size       = array(100, 50000);
    protected $mimeTypes  = array("jpeg", "png");
    private   $serialize  = array();

    /* methods to call for validation purposes*/
    protected $watermark            = null;
    protected $watermarkPosition    = "";

    protected $imageCropSize        = array();

    protected $imageShrinkSize      = array();

    protected $imageResizeRatio      = array();
    protected $imageResizeDimensions = array();
    protected $imageResizeScaleUp    = array();

    /* methods to call for validation purposes*/
    protected $imageMimeTypes = array(
        1 => "gif", "jpeg", "png",  "swf", "psd", 
             "bmp", "tiff", "jpc", "jp2", "jpx", 
             "jb2", "swc",  "iff", "wbmp", "xmb", "ico"
        );

    private $image, $error = false;

public function __construct(array $files = []){
    $this->image = $files;
}
protected function getImageMime($tmp_name){  

     if(!file_exists($tmp_name)){
       $this->error  = "file does not exist"; 
        return ;
     }

    if(isset($this->imageMimeTypes [exif_imagetype($tmp_name)])){
        $this->imageMime = $this->imageMimeTypes [exif_imagetype($tmp_name)];
         
        return $this->imageMime;
    }
    return false; 
}
public function offsetSet($offset, $value) {
    if (is_null($offset)){
        $this->image[] = $value;
    }else{
        $this->image[$offset] = $value;
    }
}
public function offsetExists($offset) {
    return array_key_exists($this->image[$offset], $this->image);
    /*das*/
}
public function offsetUnset($offset) {
    /**/
    unset($this->image[$offset]);
}
public function offsetGet($offset) {

    if($offset === "error"){
        $this->error; 
    }
   
    if(isset($this->image[$offset])){
        $this->image = $this->image[$offset];
        return true; 
    }
        return false; 
}
protected function uploadErrors($e){
    $errors = array(
        UPLOAD_ERR_OK           => " \o/ ",
        UPLOAD_ERR_INI_SIZE     => "File is larger than the specified amount set by the server",
        UPLOAD_ERR_FORM_SIZE    => "File is larger than the specified amount specified by browser",
        UPLOAD_ERR_PARTIAL      => "File could not be fully uploaded. Please try again later",
        UPLOAD_ERR_NO_FILE      => "File is not found",
        UPLOAD_ERR_NO_TMP_DIR   => "Can't write to disk, due to server configuration ( No tmp dir found )",
        UPLOAD_ERR_CANT_WRITE   => "Failed to write file to disk. Please check you file permissions",
        UPLOAD_ERR_EXTENSION    => "A PHP extension has halted this file upload process"
    );
    
    $this->error  = $errors[$e]; 

}
public function setName($isNameGiven = null){
    if ($isNameGiven) {
        $this->name = filter_var($isNameGiven, FILTER_SANITIZE_STRING);
    }else{
        $this->name = uniqid(true)."_".str_shuffle(implode(range("e", "q")));   
    }

    return $this; 
}
public function setType(array $fileTypes){
    $this->mimeTypes = $fileTypes;
    return $this; 
}
public function setSize($min, $max = null){
    $this->size = array($min, $max);
    return $this; 
}
public function setLocation($dir = null){   

    if (!file_exists($dir) && !is_dir($dir)) {
        $createFolder = mkdir("" . $dir, 0666, true);
        if (!$createFolder) {
            $this->error  = "Folder " . $dir . " could not be created";
             return ;
        }
    }

    $this->location = $dir;
    return $this;
}

public function setDimension($maxWidth, $maxHeight){
    $this->dimensions = array($maxWidth, $maxHeight);
    return $this; 
}
public function name(){
    if(null == $this->name){
        $this->name = uniqid(true)."_".str_shuffle(implode(range("E", "Q")));   
    }
    return $this->name; 
}
public function fullPath(){
    return $this->fullPath = $this->location . '/' . $this->name . '.'. $this->imageMime;
  
}
public function size(){
    /**/
    return (int) $this->image['size']; 
}  
public function height(){
    if($this->height != null){
        return $this->height; 
    }
    list($width, $height) = $this->dimensions($this->image["tmp_name"]);  
    return $height; 
}  
public function width(){
   if($this->width != null){
        return $this->width; 
    }
    list($width, $height) = $this->dimensions($this->image["tmp_name"]); 
    return $width; 
}  
public function location(){
    /**/
    return $this->location; 
}
public function mimeType(){
    return $this->imageMime;
}
private function dimensions($image){

    if(!file_exists($image)) return ;
    list($width, $height) = getImageSize($image); 

    return array("height" => $height, "width" => $width);
}
public function remove($fileToDelete){

    if (file_exists($fileToDelete) && !unlink($fileToDelete)) {
        $this->error  = "File may have been deleted or does not exist" ;
        return ;
    }
    /*dasd*/
    return true;
}



  public function error(){
    // if(defined(__CLASS__ . '\ERR_EXCEPTION') && self::ERR_EXCEPTION == true){
    //     throw new Exception($this->error); 
    // }
    return $this->error ; 
  }
 /**
     * Get the watermark image and its position.
     *
     * @param $watermark - the watermark name, ex: 'logo.png'
     * @param $watermarkCordinates - position to put the watermark, ex: 'center'
     * @return $this
     * @throws ImageErrorException
     */
    public function watermark($watermark, $position = "center")
    {
       

        if(!in_array($position, array("top-left", "bottom-left", "center", "bottom-right", "top-right"))){
           $this->error  = " $position is not a valid orientation to put for watermark";
        }

         if (!file_exists($watermark)) {
           $this->error  = " Please provide valid image to use as watermark ";
        }

        $this->watermark = $watermark;
        $this->watermarkPosition  = $position;
        return $this;
    }

  
    protected function applyWatermark(
        $imageType, 
        $image, 
        $watermark,
        $watermarkSize, 
        $position, 
        $name
        ){

        switch ($position) {
            case "center":
                $marginBottom  = round($image["height"] / 2);
                $marginRight   = round($image["width"] / 2) - round($watermarkSize["width"] / 2);
                break;

            case "top-left":
                $marginBottom  = round($image["height"] - $watermarkSize["height"]);
                $marginRight   = round($image["width"]  - $watermarkSize["width"]);
                break;

            case "bottom-left":
                $marginBottom  = 5;
                $marginRight   = round($image["width"] - $watermarkSize["width"]);
                break;

            case "top-right":
                $marginBottom  = round($image["height"] - $watermarkSize["height"]);
                $marginRight   = 5;
                break;

            default:
                $marginBottom  = 2;
                $marginRight   = 2;
                break;
        }

      
        $watermark = imagecreatefrompng($watermark);


        switch ($imageType) {
            case "jpeg":
            case "jpg":
                $createImage = imagecreatefromjpeg($name);
                break;

            case "png":
                $createImage = imagecreatefrompng($name);
                break;

            case "gif":
                $createImage = imagecreatefromgif($name);
                break;

            default:
                $createImage = imagecreatefromjpeg($name);
                break;
        }

        $sx = imagesx($watermark);
        $sy = imagesy($watermark);
        imagecopy(
            $createImage,
            $watermark,
            imagesx($createImage) - $sx - $marginRight,
            imagesy($createImage) - $sy - $marginBottom,
            0,
            0,
            imagesx($watermark),
            imagesy($watermark)
        );
    

        switch ($imageType) {
            case "jpeg":
            case "jpg":
                 imagejpeg($createImage, $name);
                break;

            case "png":
                 imagepng($createImage, $name);
                break;

            case "gif":
                 imagegif($createImage, $name);
                break;

            default:
                $this->error  = "A watermark can only be applied to: jpeg, jpg, gif, png images " ;
                 return ;
                break;
        }
    }
    

    public function crop($height, $width)
    {
        $this->imageCropSize = array("height" => $height, "width" => $width);
        return $this;
    }

    
 

    protected function applyCrop($mimeType, $tmp_name, $image, $imageCropSize)
    {
        switch ($mimeType) {
            case "jpg":
            case "jpeg":
                $imageCreate = imagecreatefromjpeg($tmp_name);
                break;

            case "png":
                $imageCreate = imagecreatefrompng($tmp_name);
                break;

            case "gif":
                $imageCreate = imagecreatefromgif($tmp_name);
                break;

            default:
               $this->error  = " Only gif, jpg, jpeg and png files can be cropped ";
                return false; 
                break;
        }

        
        $crop = $imageCropSize;

        // The image offsets/coordination to crop the image.
        $widthTrim = ceil(($image["width"] - $crop["width"]) / 2);
        $heightTrim = ceil(($image["height"] - $crop["height"]) / 2);

        // Can't crop a 100X100 image, to 200X200. Image can only be cropped to smaller size.
        if ($widthTrim < 0 && $heightTrim < 0) {
            return ;
        }

        $temp = imagecreatetruecolor($crop["width"], $crop["height"]);
                imagecopyresampled(
                    $temp,
                    $imageCreate,
                    0,
                    0,
                    $widthTrim,
                    $heightTrim,
                    $crop["width"],
                    $crop["height"],
                    $crop["width"],
                    $crop["height"]
                );


        if (!$temp) {
            $this->error  = "Failed to crop image. Please pass the right parameters" ;
             return ;
        } else {
            imagejpeg($temp, $tmp_name);
        }

    }



    function upload(){
       
        $image = $this->image; 
        
        $imageWatermark = $this->watermark; 
        $imageCrop      =  $this->imageCropSize; 
        $imageResize    = $this->imageResizeDimensions;
        $name = $this->name(); 
      
        $location = $this->location();



        if(!function_exists('exif_imagetype')){
            $this->error = "Function 'exif_imagetype' Not found."; 
        }

        var_dump($image["error"]);
         if($image["error"]){
           $this->error  = $this->uploadErrors($image["error"]);
            return ;
        }

        $imageMime = $this->getImageMime($image["tmp_name"]); 
        

        if(!in_array($imageMime, $this->imageMimeTypes)){
           $mimes = implode(', ', $this->mimeTypes);
            $this->error  = "Invalid File! Only ($mimes) image types are allowed";
            return ;
        }
        
        $imageDimension = $this->dimensions($image["tmp_name"]);
  
              
        $this->height = $imageDimension["height"];
        $this->width = $imageDimension["width"];

        
    
       

        $mimeType = $this->imageMime;



      $this->fullPath = $this->location . '/'. $this->name . '.' . $imageMime;
 



         
       
        list($minSize, $maxSize) = $this->size;     

        if($image["size"] < $minSize){
           $this->error  = "Image size should be at least more than ". intval($minSize / 1000) ."kb ";
           return ;
        }

        if($image["size"] > $maxSize){
           $this->error  = "Image size should be less than ". intval($maxSize / 1000) ."kb ";
            return ;
        }



       list($maxHeight, $maxWidth)  = $this->dimensions;
      
        if($imageDimension["height"] > $maxHeight){
           $this->error  = "Image height should be less than ". $maxHeight . " pixels.";
            return ;
        }

        if($imageDimension["width"] > $maxWidth){
           $this->error  = "Image width should be less than ". $maxWidth . " pixels";
            return ;
        }



        $this->serialize = array(
            "name"     => $name, 
            "mime"     => $mimeType, 
            "height"   => $imageDimension["height"], 
            "width"    => $imageDimension["width"], 
            "size"     => $image["size"], 
            "location" => $location
        ); 



        /* WATERMARK */
       

         if(!empty($imageCrop) && !$this->image["error"]){
            $this->applyCrop(
                $imageMime, 
                $image["tmp_name"], 
                $imageDimension, 
                $imageCrop
            );
        }



      if(!empty($imageWatermark) && !$this->image["error"]){
            $this->applyWatermark(
                $imageMime, 
                $imageDimension,
                $imageWatermark,
                $this->dimensions($imageWatermark),
                $this->watermarkPosition,
                $image["tmp_name"]
            );
        }


        if(!empty($imageResize) && !$this->image["error"]){
            $this->applyResize(
                $image["tmp_name"]
             
            );
        }



       if(false == $this->error ){

            $moveUpload = $this->moveUploadedFile($image, $this->fullPath);
            if(false !== $moveUpload){
            	return $this; 
            }

            return false;  
       }

      return false; 
   
    }   


    protected function getNewImageSize($oldImage)
    {

        // If the ratio needs to be kept.
        if ($this->imageResizeRatio) {
            $width = $this->imageResizeDimensions["width"];
            // First, calculate the height.
            $height = intval($width / $oldImage["width"] * $oldImage["height"]);

            // If the height is too large, set it to the maximum height and calculate the width.
            if ($height > $this->imageResizeDimensions["height"]) {

                $height = $this->imageResizeDimensions["height"];
                $width = intval($height / $oldImage["height"] * $oldImage["width"]);
            }

            // If we don't allow upsizing check if the new width or height are too big.
            if (! $this->imageResizeScaleUp) {
                // If the given width is larger then the image height, then resize it.
                if ($width > $oldImage["width"]) {
                    $width = $oldImage["width"];
                    $height = intval($width / $oldImage["width"] * $oldImage["height"]);
                }

                // If the given height is larger then the image height, then resize it.
                if ($height > $oldImage["height"]) {
                    $height = $oldImage["height"];
                    $width = intval($height / $oldImage["height"] * $oldImage["width"]);
                }
            }

        } else {
            $width = $this->imageResizeDimensions["width"];
            $height = $this->imageResizeDimensions["height"];
        }

        return array(
            "width" => $width,
            "height" => $height
        );
    }


    public function resize($height, $width, $keepRation = false)
    {
        $this->imageResizeRatio      = $keepRation;
        $this->imageResizeDimensions = array("height" => $height, "width" => $width);
        return $this;
    }

  
    protected function applyResize($fileName)
    {

        $oldImage = $this->dimensions($fileName);
        $newImage = $this->getNewImageSize($oldImage, false);

        $imgString = file_get_contents($fileName);

        $image = imagecreatefromstring($imgString);
        $tmp = imagecreatetruecolor($newImage["width"], $newImage["height"]);
        imagecopyresampled(
            $tmp,
            $image,
            0,
            0,
            0,
            0,
            $newImage["width"],
            $newImage["height"],
            $oldImage["width"],
            $oldImage["height"]
        );

        $mimeType = $this->getImageMime($fileName);

        switch ($mimeType) {
            case "jpeg":
            case "jpg":
                imagejpeg($tmp, $fileName, 100);
                break;
            case "png":
                imagepng($tmp, $fileName, 0);
                break;
            case "gif":
                imagegif($tmp, $fileName);
                break;
            default:
                $this->error  = " Only jpg, jpeg, png and gif files can be resized ";
                break;
        }
    }





    public function moveUploadedFile($image, $newImage) {
        return move_uploaded_file($image['tmp_name'], $newImage);
    }

     public function serialize() {
        return serialize($this->serialize);
    }
    public function unserialize($data) {
        return unserialize($data); 
    }

}
