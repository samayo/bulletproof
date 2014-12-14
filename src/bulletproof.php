<?php
/**
 * BULLETPROOF,  
 *
 * This is a one-file solution for a quick and safe way of
 * uploading, watermarking, cropping and resizing images
 * during and after uploads with PHP with best security.
 *
 * This class is heavily commented, to be as much friendly as possible.
 * Please help out by posting out some bugs/flaws if you encounter any. Thanks!
 *
 * @category    Image uploader
 * @package     BulletProof
 * @version     1.4.0
 * @author      samayo 
 * @link        https://github.com/samayo/BulletProof
 * @license     Luke 3:11 ( Free )
 */
namespace ImageUploader;

class ImageUploaderException extends \Exception {}


class BulletProof
{

    /*
    |--------------------------------------------------------------------------
    | Image Upload Properties
    \--------------------------------------------------------------------------*/

    /**
     * Set a group of default image types to upload.
     * @var array
     */
    protected $imageType = array("jpg", "jpeg", "png", "gif");

    /**
     * Set a default file size to upload. Values are in bytes. Remember: 1kb ~ 1000 bytes.
     * @var array
     */
    protected $imageSize = array("min" => 1, "max" => 30000);

    /**
     * Set a default min & maximum height & width for image to upload.
     * @var array
     */
    protected $imageDimension = array("height"=>1000, "width"=>1000);

    /**
     * Set a default folder to upload images, if it does not exist, it will be created.
     * @var string
     */
    protected $uploadDir = "uploads";
    
    /**
     * To get the real image/mime type. i.e gif, jpeg, png, ....
     * @var string
     */
    protected $getMimeType;


    /*
    |--------------------------------------------------------------------------
    | Image Resize and Crop Properties
    \------------------------------------------------------------------------*/

    /**
     * Image dimensions for resizing or shrinking ex: array("height"=>100, "width"=>100)
     * @var array
     */
    protected $shrinkImageTo = array();

    /**
     * Whether or not to keep the ratio of the original image while resizing
     * @var boolean
     */
    protected $shrinkRatio;

    /**
     * Whether or not to allow upsizing of an image when applying the shrink command.
     * @var boolean
     */
    protected $shrinkUpsize;

    /**
     * New image dimensions for image cropping ex: array("height"=>100, "width"=>100)
     * @var array
     */
    protected $cropImageTo  = array();


    /*
    |--------------------------------------------------------------------------
    | Image Watermark and Crop Properties
    \-------------------------------------------------------------------------*/

    /**
     * Name of the image to use as a watermark. ( best to use a png  image )
     * @var string
     */
    protected $getWatermark;

    /**
     * Watermark Position. (Where to put the watermark). ex: 'center', 'top-right', 'bottom-left'....
     * @var string
     */
    protected $getWatermarkPosition;

    /**
     * Size ( Width & Height ) of the watermark ex: 'array("height"=>40, "width"=>20)'.
     * @var array
     */
    protected $getWatermarkDimensions;


    /*
    |--------------------------------------------------------------------------
    | Image Upload Methods
    \--------------------------------------------------------------------------*/

    /**
     * Stores image types to upload
     *
     * @param array $fileTypes -  ex: ['jpg', 'doc', 'txt'].
     * @return $this
     */
    public function fileTypes(array $fileTypes)
    {
        $this->imageType = $fileTypes;
        return $this;
    }

    /**
     * Minimum and Maximum allowed image size for upload (in bytes),
     *
     * @param array $fileSize - ex: ['min'=>500, 'max'=>1000]
     * @return $this
     */
    public function limitSize(array $fileSize)
    {
        $this->imageSize = $fileSize;
        return $this;
    }

    /**
     * Default & maximum allowed height and width image to download.
     *
     * @param array $dimensions
     * @return $this
     */
    public function limitDimension(array $dimensions){
        $this->imageDimension = $dimensions;
        return $this;
    }

    /**
     * Get the real image's Extension/mime type
     *
     * @param $imageName
     * @return mixed
     * @throws ImageUploaderException
     */
    protected function getMimeType($imageName)
    {   
        if(!file_exists($imageName)){
            throw new ImageUploaderException("Image " . $imageName . " does not exist");
        }

        $listOfMimeTypes = array(
        1 => "gif", "jpeg", "png",  "swf", "psd",
             "bmp", "tiff", "tiff", "jpc", "jp2",
             "jpx", "jb2",  "swc",  "iff", "wbmp",
             "xmb", "ico"
        );

        if(isset($listOfMimeTypes[ exif_imagetype($imageName) ])){
            return $listOfMimeTypes[ exif_imagetype($imageName) ];
        }
    }

    /**
     * Handy method for getting image dimensions (W & H) in pixels.
     *
     * @param $getImage - The image name
     * @return array
     */
    protected function getPixels($getImage)
    {
        list($width, $height) = getImageSize($getImage);
        return array("width"=>$width, "height"=>$height);
    }

    /**
     * Calculate the new size of the image.
     * Has the ability to keep the original ratio of the image. Can prevent upsizing of an image.
     *
     * @param array $oldImage
     * @return array
     */
    protected function getNewImageSize($oldImage)
    {

        // If the ratio needs to be kept.
        if ($this->shrinkRatio) {
            $width = $this->shrinkImageTo["width"];
            // First, calculate the height.
            $height = intval($width / $oldImage["width"] * $oldImage["height"]);

            // If the height is too large, set it to the maximum height and calculate the width.
            if ($height > $this->shrinkImageTo["height"]) {

                $height = $this->shrinkImageTo["height"];
                $width = intval($height / $oldImage["height"] * $oldImage["width"]);
            }

            // If we don't allow upsizing check if the new width or height are too big.
            if (! $this->shrinkUpsize) {
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
            $width = $this->shrinkImageTo["width"];
            $height = $this->shrinkImageTo["height"];
        }

        return array(
            "width" => $width,
            "height" => $height
        );
    }

    /**
     * Rename file either from method or by generating a random one.
     *
     * @param $isNameProvided - A new name for the file. 
     * @return string
     */
    protected function imageRename($isNameProvided)
    {
        if ($isNameProvided) {
            return $isNameProvided . "." . $this->getMimeType;
        }
        return uniqid(true)."_".str_shuffle(implode(range("E", "Q"))) . "." . $this->getMimeType;
    }

    /**
     * Get the specified upload dir, if it does not exist, create a new one.
     *
     * @param $directoryName - directory name where you want your files to be uploaded
     * @return $this
     * @throws ImageUploaderException
     */
    public function uploadDir($directoryName)
    {
        if (!file_exists($directoryName) && !is_dir($directoryName)) {
            $createFolder = mkdir("" . $directoryName, 0666, true);
            if (!$createFolder) {
                throw new ImageUploaderException("Folder " . $directoryName . " could not be created");
            }
        }
        $this->uploadDir = $directoryName;
        return $this;
    }

    /**
     * For getting common error messages from FILES[] array during upload.
     *
     * @return array
     */
    protected function commonUploadErrors($key)
    {
        $uploadErrors = array(
            UPLOAD_ERR_OK           => "...",
            UPLOAD_ERR_INI_SIZE     => "File is larger than the specified amount set by the server",
            UPLOAD_ERR_FORM_SIZE    => "File is larger than the specified amount specified by browser",
            UPLOAD_ERR_PARTIAL      => "File could not be fully uploaded. Please try again later",
            UPLOAD_ERR_NO_FILE      => "File is not found",
            UPLOAD_ERR_NO_TMP_DIR   => "Can't write to disk, due to server configuration ( No tmp dir found )",
            UPLOAD_ERR_CANT_WRITE   => "Failed to write file to disk. Please check you file permissions",
            UPLOAD_ERR_EXTENSION    => "A PHP extension has halted this file upload process"
        );

        return $uploadErrors[$key];
    }


    /*
    |--------------------------------------------------------------------------
    | Image Watermark Methods
    \--------------------------------------------------------------------------*/

    /**
     * Get the watermark image and its position.
     *
     * @param $watermark - the watermark name, ex: 'logo.png'
     * @param $watermarkPosition - position to put the watermark, ex: 'center'
     * @return $this
     * @throws ImageUploaderException
     */
    public function watermark($watermark, $watermarkPosition = null)
    {
        if (!file_exists($watermark)) {
            throw new ImageUploaderException(" Please provide valid image to use as watermark ");
        }
        $this->getWatermark = $watermark;
        $this->getWatermarkPosition = $watermarkPosition;
        return $this;
    }

    /**
     * Calculate position and apply image watermark.
     *
     * The objective is to let position of watermarking be passed in simple English words like:
     * 'center', 'right-top', 'bottom-left'.. as the second argument for the 'watermark()' method
     * then take that word and do the real offset & marginal-calculation in this method.
     *
     * @param $imageName
     * @throws ImageUploaderException
     */
    protected function applyWatermark($imageName)
    {
        if (!$this->getWatermark) {
            return ;
        }

        // Calculate the watermark position
        $image      = $this->getPixels($imageName); 
        $watermark  = $this->getPixels($this->getWatermark);

        switch ($this->getWatermarkPosition) {
            case "center":
                $marginBottom  =   round($image["height"] / 2);
                $marginRight   =   round($image["width"] / 2) - round($watermark["width"] / 2);
                break;

            case "top-left":
                $marginBottom  =   round($image["height"] - $watermark["height"]);
                $marginRight   =   round($image["width"] - $watermark["width"]);
                break;

            case "bottom-left":
                $marginBottom  =   5;
                $marginRight   =   round($image["width"] - $watermark["width"]);
                break;

            case "top-right":
                $marginBottom  =   round($image["height"] - $watermark["height"]);
                $marginRight   =   5;
                break;

            default:
                $marginBottom  =   2;
                $marginRight   =   2;
                break;
        }

        // Apply the watermark using the calculated position
        $this->getWatermarkDimensions = $this->getPixels($this->getWatermark);

        $imageType = $this->getMimeType($imageName);
        $watermark = imagecreatefrompng($this->getWatermark);


        switch ($imageType) {
            case "jpeg":
            case "jpg":
                $createImage = imagecreatefromjpeg($imageName);
                break;

            case "png":
                $createImage = imagecreatefrompng($imageName);
                break;

            case "gif":
                $createImage = imagecreatefromgif($imageName);
                break;

            default:
                $createImage = imagecreatefromjpeg($imageName);
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
                 imagejpeg($createImage, $imageName);
                break;

            case "png":
                 imagepng($createImage, $imageName);
                break;

            case "gif":
                 imagegif($createImage, $imageName);
                break;

            default:
                throw new ImageUploaderException("A watermark can only be applied to: jpeg, jpg, gif, png images ");
                break;
        }
    }
    

    /*
    |--------------------------------------------------------------------------
    | Image Shrink/Resize Properties
    \--------------------------------------------------------------------------*/

    /**
     * Get the Width and Height of the image image to shrink (in pixels)
     *
     * @param array $setImageDimensions
     * @return $this
     */
    public function shrink(array $setImageDimensions, $ratio = false, $upsize = true)
    {
        $this->shrinkImageTo = $setImageDimensions;
        $this->shrinkRatio = $ratio;
        $this->shrinkUpsize = $upsize;
        return $this;
    }

    /**
     * Shrink the image.
     *
     * @param $fileName - the file name
     * @param $imageName - the file to upload
     * @throws ImageUploaderException
     */
    protected function applyShrink($fileName, $imageName)
    {

        if (!$this->shrinkImageTo) {
            return;
        }

        $oldImage = $this->getPixels($imageName);
        $newImage = $this->getNewImageSize($oldImage, false);

        $imgString = file_get_contents($imageName);

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

        $mimeType = $this->getMimeType($fileName);

        switch ($mimeType) {
            case "jpeg":
            case "jpg":
                imagejpeg($tmp, $imageName, 100);
                break;
            case "png":
                imagepng($tmp, $imageName, 0);
                break;
            case "gif":
                imagegif($tmp, $imageName);
                break;
            default:
                throw new ImageUploaderException(" Only jpg, jpeg, png and gif files can be resized ");
                break;
        }
    }


    /*
    |--------------------------------------------------------------------------
    | Image Crop Methods. 
    \--------------------------------------------------------------------------*/

    /**
     * Get size dimensions to use for new image cropping
     *
     * @param array $imageCropValues
     * @return $this
     */
    public function crop(array $imageCropValues)
    {
        $this->cropImageTo = $imageCropValues;
        return $this;
    }

    /**
     * Apply crop image, from the given size
     *
     * @param $imageName
     * @param $tmp_name
     * @return resource
     * @throws ImageUploaderException
     */
    protected function applyCrop($imageName, $tmp_name)
    {

        if (!$this->cropImageTo) {
            return ;
        }

        $mimeType = $this->getMimeType($imageName);

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
                throw new ImageUploaderException(" Only gif, jpg, jpeg and png files can be cropped ");
                break;
        }

        // Uploaded image pixels.
        $image = $this->getPixels($tmp_name);
        $crop = $this->cropImageTo;

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
            throw new ImageUploaderException("Failed to crop image. Please pass the right parameters");
        } else {
            imagejpeg($temp, $tmp_name);
        }

    }


    /*
    |--------------------------------------------------------------------------
    | Not Upload Related. 
    \--------------------------------------------------------------------------*/

    /**
     * Without uploading, just crop/watermark/shrink all images in your folders
     *
     * @param $action - the task.. ex: 'crop', 'watermark', 'shrink'...
     * @param $imageName - the image you want to change. Provide full path pls.
     * @throws ImageUploaderException
     */
    public function change($action, $imageName){

        if(empty($action) || !file_exists($imageName)){
            throw new ImageUploaderException(__FUNCTION__." needs two arguments. the Task and Image name");
        }

        if($action == "watermark" && 
            $this->getWatermark)
        {
            $this->applyWatermark($imageName);
            return true;
        }

        if($action == "shrink" &&
            $this->shrinkImageTo)
        {
            $this->applyShrink($imageName, $imageName);
            return true;
        }

        if($action == "crop" && 
            $this->cropImageTo)
        {
            $this->applyCrop($imageName, $imageName);
            return true;
        }
        
        throw new ImageUploaderException("Unknown directive given to function ". __FUNCTION__);
        
    }

    /**
     * Simple file check and delete wrapper.
     *
     * @param $fileToDelete
     * @return bool
     * @throws ImageUploaderException
     */
    public function deleteFile($fileToDelete){
        if (file_exists($fileToDelete) && !unlink($fileToDelete)) {
            throw new ImageUploaderException("File may have been deleted or does not exist");
        }
        return true;
    }

    /**
     * Final image uploader method, to check for errors and upload
     *
     * @param $fileToUpload
     * @param null $isNameProvided
     * @return string
     * @throws ImageUploaderException
     */
    public function upload($fileToUpload, $isNameProvided = null)
    {
       
       if(!function_exists('exif_imagetype')){
        throw new ImageUploaderException("Function 'exif_imagetype' Not found.");
       }

         // Check if any errors are thrown by the FILES[] array
        if ($fileToUpload["error"]) {
            throw new ImageUploaderException($this->commonUploadErrors($fileToUpload["error"]));
        }

        // First get the real file extension
        $this->getMimeType = $this->getMimeType($fileToUpload["tmp_name"]);

        // Check if this file type is allowed for upload
        if (!in_array($this->getMimeType, $this->imageType)) {
            throw new ImageUploaderException(" This is not allowed file type!
             Please only upload ( " . implode(", ", $this->imageType) . " ) file types");
        }

        //Check if size (in bytes) of the image are above or below of defined in 'limitSize()' 
        if ($fileToUpload["size"] < $this->imageSize["min"] ||
            $fileToUpload["size"] > $this->imageSize["max"]
        ) {
            throw new ImageUploaderException("File sizes must be between " .
                implode(" to ", $this->imageSize) . " bytes");
        }

        // check if image is valid pixel-wise.
        $pixel = $this->getPixels($fileToUpload["tmp_name"]);
        
        if($pixel["width"] < 4 || $pixel["height"] < 4){
            throw new ImageUploaderException("This file is either too small or corrupted to be an image");
        }

        if($pixel["height"] > $this->imageDimension["height"] || 
            $pixel["width"] > $this->imageDimension["width"])
        {
            throw new ImageUploaderException("Image pixels/size must be below ". 
                implode(", ", $this->imageDimension). " pixels");
        }

        // Assign given name or generate a new one.
        $newFileName = $this->imageRename($isNameProvided);

        // create upload directory if it does not exist
        $this->uploadDir($this->uploadDir);

        // watermark, shrink and crop 
        $this->applyWatermark($fileToUpload["tmp_name"]);
        $this->applyShrink($fileToUpload["tmp_name"], $fileToUpload["tmp_name"]);
        $this->applyCrop($fileToUpload["tmp_name"], $fileToUpload["tmp_name"]);

        // Security check, to see if file was uploaded with HTTP_POST 
        $checkSafeUpload = $this->isUploadedFile($fileToUpload["tmp_name"]);

        // Upload the file
        $moveUploadedFile = $this->moveUploadedFile($fileToUpload["tmp_name"], $this->uploadDir . "/" . $newFileName);

        if ($checkSafeUpload && $moveUploadedFile) {
            return $this->uploadDir . "/" . $newFileName; 
        }else{
            throw new ImageUploaderException(" File could not be uploaded. Unknown error occurred. ");
        }
    }

    public function isUploadedFile($file)
    {
        return is_uploaded_file($file);
    }

    public function moveUploadedFile($uploaded_file, $new_file) {
        return move_uploaded_file($uploaded_file, $new_file);
    }
}
