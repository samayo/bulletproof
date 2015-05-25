<?php
/**
 * BulletProof
 *
 * A single class PHP library for secure image uploading.
 *
 * PHP support 5.3+
 *
 * @package     BulletProof
 * @version     2.0.0
 * @author      Samayo  /@sama_io
 * @link        https://github.com/samayo/BulletProof
 * @license     MIT
 */
namespace BulletProof;

class Image implements \ArrayAccess
{

    protected $name       = null;  
    protected $width      = null;
    protected $height     = null; 
    protected $imageMime  = null; 
    protected $fullPath   = null;
    protected $location   = "images";
    protected $dimensions = array(500, 5000);
    protected $size       = array(100, 50000);
    protected $mimeTypes  = array("jpeg", "png", "gif");
    protected $serialize  = array();

    /* methods to call for validation purposes*/
    protected $imageMimesList = array(
        1 => "gif", "jpeg", "png",  "swf", "psd", 
             "bmp", "tiff", "jpc", "jp2", "jpx", 
             "jb2", "swc",  "iff", "wbmp", "xmb", "ico"
        );

    private $image, $error = false;

    public function __construct(array $image = []){
        $this->image = $image;
    }
    
    protected function getImageMime($tmp_name){  

     if(!file_exists($tmp_name)){
       $this->error  = "file does not exist"; 
        return false;
     }

    if(isset($this->imageMimesList [exif_imagetype($tmp_name)])){
        $this->imageMime = $this->imageMimesList [exif_imagetype($tmp_name)];
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
      
    }
    public function offsetUnset($offset) {
     
        unset($this->image[$offset]);
    }
    public function offsetGet($offset) {

    if(isset($this->image[$offset]) && $offset !== 'error'){
        $this->image = $this->image[$offset];


        return true; 
    }
    if($offset == 'error'){
        return $this->error; 
    }

        return false; 
}
protected function uploadErrors($e){
    $errors = array(
        UPLOAD_ERR_OK           => " \o/ ",
        UPLOAD_ERR_INI_SIZE     => "Image is larger than the specified amount set by the server",
        UPLOAD_ERR_FORM_SIZE    => "Image is larger than the specified amount specified by browser",
        UPLOAD_ERR_PARTIAL      => "Image could not be fully uploaded. Please try again later",
        UPLOAD_ERR_NO_FILE      => "Image is not found",
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
        $this->name = uniqid(true) . "_" . str_shuffle(implode(range("e", "q")));   
    }

    return $this; 
}
public function setMime(array $fileTypes){
    $this->mimeTypes = $fileTypes;
    return $this; 
}
public function setSize($min, $max = null){
    $this->size = array($min, $max);
    return $this; 
}
public function setLocation($dir, $permission = 0666){   

    if (!file_exists($dir) && !is_dir($dir)) {
        $createFolder = @mkdir("" . $dir, (int) $permission, true);
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
public function getName(){
    if(null == $this->name){
        $this->name = uniqid(true) ."_". str_shuffle(implode(range("e", "q")));   
    }
    return $this->name; 
}
public function getFullPath(){
     $this->fullPath = $this->location . '/' . $this->name . '.'. $this->imageMime;
     return $this->fullPath;
  
}
public function getSize(){
    return (int) $this->image['size']; 
}  
public function getHeight(){
    if(null != $this->height){
        return $this->height; 
    }
    list($width, $height) = $this->dimensions($this->image["tmp_name"]);  
    return $height; 
}  
public function getWidth(){
   if($this->width != null){
        return $this->width; 
    }
    list($width, $height) = $this->dimensions($this->image["tmp_name"]); 
    return $width; 
}  
public function getLocation(){
    return $this->location; 
}
public function getJson(){
    return json_decode($this->serialize);
}
public function getMime(){
    return $this->imageMime;
}
private function dimensions($image){

    if(!file_exists($image)){
      return ;  
    } 

    list($width, $height) = getImageSize($image); 

    return array("height" => $height, "width" => $width);
}
public function remove($fileToDelete){

    if (file_exists($fileToDelete) && !unlink($fileToDelete)) {
        $this->error  = "File may have been deleted or does not exist" ;
        return ;
    }
   
    return true;
}



 


    function upload(){
 
        $image = $this->image; 
        

        $name = $this->getName(); 
        

        
        $location = $this->getLocation();



        if(!function_exists('exif_imagetype')){
            $this->error = "Function 'exif_imagetype' Not found.";  return ;
        }

      
         if($image["error"]){
           $this->error  = $this->uploadErrors($image["error"]);
            return ;
        }

        $imageMime = $this->getImageMime($image["tmp_name"]); 
        

        if(!in_array($imageMime, $this->mimeTypes)){
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
           $this->error  = "Image size should be at least more than ". intval($minSize / 1000) ." kb ";
           return ;
        }

        if($image["size"] > $maxSize){
           $this->error  = "Image size should be less than ". intval($maxSize / 1000) ." kb ";
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



       if(false == $this->error ){

            $moveUpload = $this->moveUploadedFile($image['tmp_name'], $this->fullPath);
            if(false !== $moveUpload){
            	return $this; 
            }

            return false;  
       }

      return false; 
   
    }
    public function moveUploadedFile($tmp_name, $destination) {
        return move_uploaded_file($tmp_name, $destination);
    }
}
