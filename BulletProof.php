<?php

namespace ImageUploader;

/**
 * BulletProof: A PHP Image Uploader.
 *
 * You can also: shrink/resize, add watermark, delete or crop Images,
 * during and after image uploads.
 *
 * This class is heavily commented, to be as much friendly as possible.
 *
 * Please help out by posting out some bugs/flaws if you encounter any. Thanks!
 *
 * @author Simon _eQ <https://github.com/simon-eQ>
 * @license Luke 3:11 ( Free )
 * @link https://github.com/simon-eQ/BulletProof
 *
 */


class ImageUploaderException extends \Exception
{
}


class BulletProof
{


    /*--------------------------------------------------------------------------
    |  UPLOAD: Image upload class properties                                   |
    *--------------------------------------------------------------------------*/

    /**
     * Set a group of default image types to upload.
     *
     * @var array
     */
    private $fileTypesToUpload  = [ "jpg", "jpeg", "png", "gif" ];

    /**
     * Set a default file size to upload. Values are in bytes. Remember: 1kb ~ 1000 bytes.
     *`
     * @var array
     */
    private $allowedUploadSize  = [ "min" => 1, "max" => 30000 ];

    /**
     * Default & maximum allowed height and width image to upload.
     *
     * @var array
     */
    private $imageDimension     = [ "height"=>1000, "width"=>1000 ];

    /**
     * Set a default folder to upload images, if it does not exist, it will be created.
     *
     * @var string
     */
    private $fileUploadDirectory = "uploads";

    /**
     * To get the real image/mime type. i.e gif, jpeg, png, ....
     *
     * @var string
     */
    private $getMimeType;



    /*--------------------------------------------------------------------------
    |   IMAGE RESIZE & CROP  |   Properties                                     |
    ---------------------------------------------------------------------------*/

    /**
     * Image dimensions for resizing or shrinking ex: array("height"=>100, "width"=>100)
     *
     * @var array
     */
    private $imageShrinkDimensions = [];

    /**
     * New image dimensions for image cropping ex: array("height"=>100, "width"=>100)
     *
     * @var array
     */
    private $imageCropDimension  = [];



    /*-----------------------------------------------------------------------------
    |    WATERMARK  | Image Watermark Properties                                   |
    ----------------------------------------    -----------------------------------*/

    /**
     * Name of the image to use as a watermark. ( best to use a png  image )
     *
     * @var
     */
    private $getImageToWatermark;

    /**
     * Watermark Position. (Where to put the watermark). ex: 'center', 'top-right', 'bottom-left'....
     *
     * @var
     */
    private $getWatermarkPosition;

    /**
     * Size, store ( Width & Height ) of the watermark ex: 'array("height"=>40, "width"=>20)'.
     *
     * @var
     */
    private $getWatermarkDimensions;



    /*--------------------------------------------------------------------------
    |    UPLOADING  | General Uploading Methods                                |
    ---------------------------------------------------------------------------*/

    /**
     * For passing the image/mime types to upload.
     *
     * @param array $fileTypes -  ex: ['jpg', 'doc', 'txt'].
     * @return $this
     */
    public function fileTypes(array $fileTypes)
    {
        $this->fileTypesToUpload = $fileTypes;
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
        $this->allowedUploadSize = $fileSize;
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
     * Get the real file's Extension/mime type
     *
     * @param $imageName
     * @return mixed
     * @throws ImageUploaderException
     */
    public function getMimeType($imageName)
    {

        if(!file_exists($imageName)){
            throw new ImageUploaderException("File " . $imageName . " does not exist");
        }

        $listOfMimeTypes = [
        1 => "gif", "jpeg", "png",  "swf", "psd",
             "bmp", "tiff", "tiff", "jpc", "jp2",
             "jpx", "jb2",  "swc",  "iff", "wbmp",
             "xmb", "ico"
        ];

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
    private function getImagePixels($getImage)
    {
        list($width, $height) = getImageSize($getImage);
        return array($width, $height);
    }


    /**
     * Rename file either from method or by generating a random one.
     *
     * @param $isNameProvided - A new name for the file. 
     * @return string
     */
    private function createFileName($isNameProvided)
    {
        if ($isNameProvided) {
            return $isNameProvided . "." . $this->getMimeType;
        }
        return uniqid(true)."_".str_shuffle(implode(range("E", "Q"))) . "." . $this->getMimeType;
    }


    /**
     * Get the specified upload dir, if it does not exist, create a new one.
     *
     * @param $nameOfDir - directory name where you want your files to be uploaded
     * @return $this
     * @throws ImageUploaderException
     */
    public function folder($nameOfDir)
    {
        if (!file_exists($nameOfDir) && !is_dir($nameOfDir)) {
            $createFolder = mkdir("" . $nameOfDir, 0777);
            if (!$createFolder) {
                throw new ImageUploaderException("Folder " . $nameOfDir . " could not be created");
            }
        }

        $this->fileUploadDirectory = $nameOfDir;
        return $this;
    }


    /**
     * For getting common error messages from FILES[] array during upload.
     *
     * @return array
     */
    private function commonFileUploadErrors()
    {
        return [
            UPLOAD_ERR_OK           => "...",
            UPLOAD_ERR_INI_SIZE     => "File is larger than the specified amount set by the server",
            UPLOAD_ERR_FORM_SIZE    => "File is larger than the specified amount specified by browser",
            UPLOAD_ERR_PARTIAL      => "File could not be fully uploaded. Please try again later",
            UPLOAD_ERR_NO_FILE      => "File is not found",
            UPLOAD_ERR_NO_TMP_DIR   => "Can't write to disk, due to server configuration",
            UPLOAD_ERR_CANT_WRITE   => "Failed to write file to disk. Please check you file permissions",
            UPLOAD_ERR_EXTENSION    => "A PHP extension has halted this file upload process"
        ];
    }



    /*--------------------------------------------------------------------------
    |    WATERMARK  | Image Watermark methods                                   |
    *--------------------------------------------------------------------------*/

    /**
     * Get the watermark image and its position.
     *
     * @param $watermark - the watermark name, ex: 'logo.png'
     * @param $positionToWatermark - position to put the watermark, ex: 'center'
     * @return $this
     * @throws ImageUploaderException
     */
    public function watermark($watermark, $positionToWatermark)
    {
        if (!file_exists($watermark)) {
            throw new ImageUploaderException(" Please provide valid image to use as watermark ");
        }
        $this->getImageToWatermark = $watermark;
        $this->getWatermarkPosition = $positionToWatermark;
        return $this;
    }


    /**
     * Calculate position and apply image watermark.
     *
     * The objective is to let position of watermarking be passed in simple English words like:
     * 'center', 'right-top', 'bottom-left'.. as the second argument for the 'watermark()' method
     * then take that word and do the real offset & marginal-calculation in this method.
     *
     * @param $imageToUpload
     * @throws ImageUploaderException
     */
    private function applyImageWatermark($imageToUpload)
    {

        if (!$this->getImageToWatermark) {
            return;
        }

        // Calculate the watermark position
        $watermark  = $this->getImageToWatermark;
        $position   = $this->getWatermarkPosition;

        list($imgWidth, $imgHeight) = $this->getImagePixels($imageToUpload);
        list($watWidth, $watHeight) = $this->getImagePixels($watermark);

        switch ($position) {
            case "center":
                $bottomPosition     = (int)ceil($imgHeight / 2);
                $rightPosition      = (int)ceil($imgWidth / 2) - (int)ceil($watWidth / 2);
                break;

            case "bottom-left":
                $bottomPosition     = 5;
                $rightPosition      = (int)round($imgWidth - $watWidth);
                break;

            case "top-left":
                $bottomPosition     = (int)round($imgHeight - $watHeight);
                $rightPosition      = (int)round($imgWidth - $watWidth);
                break;

            case "top-right":
                $bottomPosition     = (int)round($imgHeight - $watHeight);
                $rightPosition      = 5;
                break;

            default:
                // bottom-right
                $bottomPosition     = 2;
                $rightPosition      = 2;
                break;
        }


        // Apply the watermark using the calculated position
        $this->getWatermarkDimensions = $this->getImagePixels($watermark);

        $imageType = $this->getMimeType($imageToUpload);
        $watermark = imagecreatefrompng($watermark);


        switch ($imageType) {
            case "jpeg":
            case "jpg":
                $createImage = imagecreatefromjpeg($imageToUpload);
                break;

            case "png":
                $createImage = imagecreatefrompng($imageToUpload);
                break;

            case "gif":
                $createImage = imagecreatefromgif($imageToUpload);
                break;

            default:
                $createImage = imagecreatefromjpeg($imageToUpload);
                break;
        }


        $sx = imagesx($watermark);
        $sy = imagesy($watermark);

        imagecopy(
            $createImage,
            $watermark,
            imagesx($createImage) - $sx - $rightPosition,
            imagesy($createImage) - $sy - $bottomPosition,
            0,
            0,
            imagesx($watermark),
            imagesy($watermark)
        );


        switch ($imageType) {
            case "jpeg":
            case "jpg":
                 imagejpeg($createImage, $imageToUpload);
                break;

            case "png":
                 imagepng($createImage, $imageToUpload);
                break;

            case "gif":
                 imagegif($createImage, $imageToUpload);
                break;

            default:
                throw new ImageUploaderException("A watermark can only be applied to: jpeg, jpg, gif, png images ");
                break;
        }
    }



    /*--------------------------------------------------------------------------
    |    SHRINK  | Image shrink/resize  methods                                |
    ---------------------------------------------------------------------------*/

    /**
     * Get the Width and Height of the image image to shrink (in pixels)
     *
     * @param array $setImageDimensions
     * @return $this
     */
    public function shrink(array $setImageDimensions)
    {
        $this->imageShrinkDimensions = $setImageDimensions;
        return $this;
    }


    /**
     * Shrink the image.
     *
     * @param $fileName - the file name
     * @param $fileToUpload - the file to upload
     * @throws ImageUploaderException
     */
    private function applyImageShrink($fileName, $fileToUpload)
    {

        if (!$this->imageShrinkDimensions) {
            return;
        }

        list($width, $height) = $this->getImagePixels($fileToUpload);

        $newWidth = $this->imageShrinkDimensions['width'];
        $newHeight = $this->imageShrinkDimensions['height'];


        $imgString = file_get_contents($fileToUpload);

        $image = imagecreatefromstring($imgString);
        $tmp = imagecreatetruecolor($newWidth, $newHeight);
        imagecopyresampled(
            $tmp,
            $image,
            0,
            0,
            0,
            0,
            $newWidth,
            $newHeight,
            $width,
            $height
        );

        $mimeType = $this->getMimeType($fileName);

        switch ($mimeType) {
            case "jpeg":
            case "jpg":
                imagejpeg($tmp, $fileToUpload, 100);
                break;
            case "png":
                imagepng($tmp, $fileToUpload, 0);
                break;
            case "gif":
                imagegif($tmp, $fileToUpload);
                break;
            default:
                throw new ImageUploaderException(" Only jpg, jpeg, png and gif files can be resized ");
                break;
        }
    }



    /*--------------------------------------------------------------------------
    |    CROPPING | Image cropping methods                                     |
    ---------------------------------------------------------------------------*/

    /**
     * Get size dimensions to use for new image cropping
     *
     * @param array $imageCropValues
     * @return $this
     */
    public function crop(array $imageCropValues)
    {
        $this->imageCropDimension = $imageCropValues;
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
    private function applyImageCropping($imageName, $tmp_name)
    {

        if (!$this->imageCropDimension) {
            return;
        }

        $mimeType = $this->getMimeType($imageName);

        switch ($mimeType) {
            case "jpg":
            case "jpeg":
                $image = imagecreatefromjpeg($tmp_name);
                break;

            case "png":
                $image = imagecreatefrompng($tmp_name);
                break;

            case "gif":
                $image = imagecreatefromgif($tmp_name);
                break;

            default:
                throw new ImageUploaderException(" Only gif, jpg, jpeg and png files can be cropped ");
                break;
        }


        // Uploaded image pixels.
        list($imgWidth, $imgHeight) = $this->getImagePixels($tmp_name);

        // Size given for cropping image.
        $heightToCrop = $this->imageCropDimension["height"];
        $widthToCrop = $this->imageCropDimension["width"];

        // The image offsets/coordination to crop the image.
        $widthTrim = ceil(($imgWidth - $widthToCrop) / 2);
        $heightTrim = ceil(($imgHeight - $heightToCrop) / 2);

        // Can't crop a 100X100 image, to 200X200. Image can only be cropped to smaller size.
        if ($widthTrim < 0 && $heightTrim < 0) {
            return ;
        }

        $temp = imagecreatetruecolor($widthToCrop, $heightToCrop);
                imagecopyresampled(
                    $temp,
                    $image,
                    0,
                    0,
                    $widthTrim,
                    $heightTrim,
                    $widthToCrop,
                    $heightToCrop,
                    $widthToCrop,
                    $heightToCrop
                );


        if (!$temp) {
            throw new ImageUploaderException("Failed to crop image. Please pass the right parameters");
        } else {
            imagejpeg($temp, $tmp_name);
        }

    }



    /*--------------------------------------------------------------------------
    |    Change file |  crop/watermark/shrink methods. without uploading        |
    ---------------------------------------------------------------------------*/

    /**
     * Without uploading, just crop/watermark/shrink all images in your folders
     *
     * @param $directive - the task.. ex: 'crop', 'watermark', 'shrink'...
     * @param $imageName - the image you want to change. Provide full path pls.
     * @throws ImageUploaderException
     */
    public function change($directive, $imageName){

        if(empty($directive) || !file_exists($imageName)){
            throw new ImageUploaderException(__FUNCTION__." Requires image name and array directive ");
        }

        $tasks = array("watermark", "shrink", "crop");
        switch ($directive) {
            case "watermark":
                 if(!$this->getWatermarkPosition || !$this->getImageToWatermark){
                    throw new ImageUploaderException("Please provide 'watermark' and 'position' by using the 'watermark()' method");
                 }
                 // Apply watermark to image
                $this->applyImageWatermark($imageName);
                break;

            case "shrink":
                if(!$this->imageShrinkDimensions){
                    throw new ImageUploaderException("Please provide 'width * height' dimensions by using 'shrink()' method ");
                 }
                 // Resize or crop the image
                $this->applyImageShrink($imageName, $imageName);
                break;

            case "crop":
                if(!$this->imageCropDimension){
                    throw new ImageUploaderException("Please provide 'width * height' dimensions by using 'shrink()' method ");
                 }
                // Crop the image
                $this->applyImageCropping($imageName, $imageName);
                break;

            default:
                 throw new ImageUploaderException(__FUNCTION__." Expects either ". implode(", ", $tasks)." as second argument");
                break;
        }
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
        // First get the real file extension
        $this->getMimeType = $this->getMimeType($fileToUpload["name"]);

        // Check if this file type is allowed for upload
        if (!in_array($this->getMimeType, $this->fileTypesToUpload)) {
            throw new ImageUploaderException(" This is not allowed file type!
             Please only upload ( " . implode(", ", $this->fileTypesToUpload) . " ) file types");
        }

        // Check if any errors are thrown by the FILES[] array
        if ($fileToUpload['error']) {
            throw new ImageUploaderException("ERROR " . $this->commonFileUploadErrors()[$fileToUpload['error']]);
        }

        // Check if size (in bytes) of the image is above or below of defined in 'sizeLimit()' 
        if ($fileToUpload["size"] <= $this->allowedUploadSize["min"] ||
            $fileToUpload["size"] >= $this->allowedUploadSize["max"]
        ) {
            throw new ImageUploaderException("File sizes must be between " .
                implode(" to ", $this->allowedUploadSize) . " bytes");
        }

        // check if image is valid pixel-wise.
        list($imgWidth, $imgHeight) = $this->getImagePixels($fileToUpload["name"]);
        if($imgWidth < 1 || $imgHeight < 1){
            throw new ImageUploaderException("This file is either too small or corrupted to be an image file");
        }

        if($imgWidth > $this->imageDimension["width"] || $imgHeight > $this->imageDimension["height"]){
            throw new ImageUploaderException("The allowed file dimensions are ". implode(", ", $this->imageDimension). " pixels");
        }

        // Assign given name or generate a new one.
        $newFileName = $this->createFileName($isNameProvided);


        $this->applyImageWatermark($fileToUpload["tmp_name"]);

        $this->applyImageShrink($fileToUpload["name"], $fileToUpload["tmp_name"]);

        $this->applyImageCropping($fileToUpload["name"], $fileToUpload["tmp_name"]);


        // Security check, to see if file was uploaded with HTTP_POST 
        $checkSafeUpload = is_uploaded_file($fileToUpload["tmp_name"]);


        // Upload the file
        $filePath = $this->fileUploadDirectory . "/" . $newFileName;
        $moveUploadedFile = move_uploaded_file( $fileToUpload["tmp_name"], $filePath);

        if ($checkSafeUpload && $moveUploadedFile) {
            return $filePath; 
        }else{
            throw new ImageUploaderException(" File could not be uploaded. Unknown error occurred. ");
        }
    }


}






