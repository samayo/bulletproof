<?php
namespace BulletProof;

/**
 * ImageUploder: A simple and secure PHP Image uploader class.
 *
 * You may upload any files too, but for best performance, use this class
 * to upload images only. As, it is best made for checking & validating
 * images, such as the jpg, gif, png types/variations.
 * @author     Simon _eQ <https://github.com/simon-eQ>
 * @license    Public domain. No Licence.
 */



class ImageUploader
{

    /**
     * default image types
     * @var array
     */
    private $allowedFileTypes = array("gif", "jpg", "png");

    /**
     * Set a min & max file size in bytes. Remember: 30000bytes == 30kb
     * @var array
     */
    private $allowedFileSize = array("min" => 100, "max" => 30000);

    /**
     * default image dimension size, in pixels.
     * @var array
     */
    private $allowedImageDimensions = array();

    /**
     * folder to upload the files into.
     * @var
     */
    private $allowedUploadDirectory;

    /**
     * Simple text to place on uploaded images
     * @var
     */
    private $textToWatermark;

    /**
     * A png image to watermark uploaded images
     * @var
     */
    private $imageToWatermark;

    /**
     * Force resizing images, to a fixed width&height by pixels.
     * @var array
     */
    private $newImageResizeDimensions = array();


    /**
     * Pass file type that user must upload. ex: gif, png, tifff
     * @param array $setFileTypes
     * @return $this
     */
    public function fileTypes(array $setFileTypes)
    {
        $this->allowedFileTypes = $setFileTypes;
        return $this;
    }


    /**
     * Pass min & max file size to be uploaded, ex ['min-height'=>100, 'max-height'=>200]
     * @param array $setFileSizes
     * @return $this
     */
    public function fileSizeLimit(array $setFileSizes)
    {
        $this->allowedFileSize = $setFileSizes;
        return $this;
    }

    /**
     * Check uploaded images, again to what is set below.
     * @param array $setImageDimensions
     * @return $this
     */
    public function imageDimension(array $setImageDimensions)
    {
        $this->allowedImageDimensions = $setImageDimensions;
        return $this;
    }


    /**
     * Set if you need to tell PHP where to upload files/image into
     * @param $folderToUpload
     * @return $this
     */
    public function uploadTo($folderToUpload)
    {
        $this->allowedUploadDirectory = $folderToUpload;
        return $this;
    }


    /**
     * If set, it'll force shrink all images uploaded.
     * @param array $imageResizeDimensions
     * @return $this
     */
    public function resizeImage(array $imageResizeDimensions)
    {
        $this->newImageResizeDimensions = $imageResizeDimensions;
        return $this;
    }


    /**
     * Pass a text to watermark every uploaded image
     * @param $textToWrite
     * @return $this
     */
    public function watermarkText($textToWrite)
    {
        $this->textToWatermark = $textToWrite;
        return $this;
    }


    /**
     * Pass a PNG image to watermark all uploaded images
     * @param $imageToWatermark
     * @return $this
     */
    public function watermarkImage($imageToWatermark)
    {
        $this->imageToWatermark = $imageToWatermark;
        return $this;
    }


    /**
     * Create a costume array for each possible error that may be
     * thrown by the $_FILES[] array.
     * @return array
     */
    public function commonFileUploadErrors()
    {
        /**
         * We can use those keys as identifiers of the $_FILES[]['error'] value
         * to call the corresponding error messages. Damn I'm good! :D
         */
        return array(
            UPLOAD_ERR_OK => "...",
            UPLOAD_ERR_INI_SIZE => "File is larger than the specified amount set by the server",
            UPLOAD_ERR_FORM_SIZE => "Files is larger than the specified amount specified by browser",
            UPLOAD_ERR_PARTIAL => "File could not be fully uploaded. Please try again later",
            UPLOAD_ERR_NO_FILE => "File is not found",
            UPLOAD_ERR_NO_TMP_DIR => "Can't write to disk, as per server configuration",
            UPLOAD_ERR_CANT_WRITE => "Failed to write file to disk. Introduced in PHP",
            UPLOAD_ERR_EXTENSION => "A PHP extension has halted this file upload process"
        );
    }


    /**
     * There are many reasons for a file upload not work, other than from the information
     * obtained by the $_FILES[]['error'] array, So, this function tends to debug server
     * environment for a possible cause of an error, if file uploaded was not a success.
     * @param null $newDirectory optional directory, if not specified this method  will use tmp_name
     * @return string
     */
    public function debugEnvironment($newDirectory = null)
    {

        /**
         * If given a new directory to upload the files, then check and debug it first
         * otherwise, check the temporary default dir given by PHP i.e. 'tmp_name'
         */
        $uploadFileTo = $newDirectory ? $newDirectory : init_get("upload_tmp_dir");


        /**
         * Check if the given upload folder, is a valid directory
         */
        if (!is_dir($uploadFileTo)) {
            return "Please make sure this is a valid directory, or php 'file_uploads' is turned on";
        }


        /**
         * Still not sure how this is done. But, I am trying to check check if given
         * upload directory has write permissions
         */
        if (!substr(sprintf('%o', fileperms($uploadFileTo)), -4) != 0777) {
            return "Sorry, you don't have her majesty's permission to upload files on this server";
        }
    }


    /**
     * For simple function call. This will check if pixels exists and are within the
     * limit passed the the imageDimensions() method.
     * @param $fileName
     * @return string
     */
    public function findImagePixelErrors($fileName)
    {

        /**
         * get width and height for validation.
         */
        list($width, $height, $type, $attr) = getimagesize($fileName);

        $allowedMaxWidth = $this->allowedImageDimensions['max-width'];
        $allowedMaxHeight = $this->allowedImageDimensions['max-height'];

        /**
         * Check if width and height do not surpass the limit already assigned.
         */
        if ($width > $allowedMaxWidth || $height > $allowedMaxHeight) {
            return "Image must be less than " . $allowedMaxWidth . " pixels wide and " . $allowedMaxHeight . " pixels in height";
        }

        /**
         * If 'image' has no pixels, then it is likely to be invalid or corrupt. Even at 1px
         */
        if ($height <= 1 || $width <= 1) {
            return "This file is either too small or corrupted to be an image file";
        }
    }


    /**
     * The final method that validates, renames and uploads the image/file.
     * @param $fileToUpload
     * @param null $fileToRename
     * @return string
     */
    public function save($fileToUpload, $fileToRename = null)
    {

        /**
         * First get the real and reliable file extension with pathinfo();
         */
        $fileExtension = strtolower(pathinfo($fileToUpload['name'], PATHINFO_EXTENSION));


        /**
         * Check if file extension exists, in the defined list of $allowedFileTypes
         */
        if (!in_array($fileExtension, $this->allowedFileTypes)) {
            return "This is not allowed File type. Please only upload ("
                . implode(' ,', $this->allowedFileTypes) . ") file types";
        }


        /**
         * Check if $_FILE[]['error'] is set, and echo the corresponding error messages.
         */
        if ($fileToUpload['error']) {
            $errors = $this->commonFileUploadErrors();
            return $errors[$fileToUpload['error']];
        }


        /**
         * Check if file min & max sizes do not exceed the limit passed
         */
        if ($fileToUpload['size'] <= $this->allowedFileSize['min'] ||
            $fileToUpload['size'] >= $this->allowedFileSize['max']
        ) {
            return "Files sizes must be in-between
                    " . (implode(" to ", $this->allowedFileSize)) . " kilobytes";
        }

        /**
         * If this variable is set, it means our script is trying to upload an image,
         * thus, it is important to validate the image by a given pixel value
         */
        if ($this->allowedImageDimensions) {

            $imageHasPixelError = $this->findImagePixelErrors($fileToUpload['tmp_name']);

            if ($imageHasPixelError) {

                return $imageHasPixelError;

            }

        }


        /**
         * If file name is passed as the second argument, use it as a name for this file,
         * otherwise use a randome + uniqid as a nem
         */
        if (!$fileToRename) {
            $newFileName = uniqid(str_shuffle(implode(range(1, 20)))) . "." . $fileExtension;
        } else {
            $newFileName = $fileToRename . "." . $fileExtension;
        }


        /**
         * If a value for newResizeDimensions is passed, then we'll
         * crop the image as indicated.
         */
        if ($this->newImageResizeDimensions) {
            list($width, $height) = getimagesize($fileToUpload['tmp_name']);
            $newWidth = ($height / $width) * $this->newImageResizeDimensions['width'];
            $newHeight = ($height / $width) * $this->newImageResizeDimensions['height'];


            /* read binary data from image file */
            $imgString = file_get_contents($fileToUpload['tmp_name']);

            /* create image from string */
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
            switch ($fileExtension) {
                case 'jpeg':
                case 'jpg':
                    imagejpeg($tmp, $this->allowedUploadDirectory . $newFileName, 100);
                    break;
                case 'png':
                    imagepng($tmp, $this->allowedUploadDirectory . $newFileName, 0);
                    break;
                case 'gif':
                    imagegif($tmp, $this->allowedUploadDirectory . $newFileName);
                    break;
                default:
                    exit;
                    break;
            }
            return $this->allowedUploadDirectory;
        }


        /**
         * According the the PHP manual, is_uploaded_file() is mandatory to check if file was
         * posted from HTTP POST metho,  as an additional security check.
         */
        $checkSafeUpload = is_uploaded_file($fileToUpload['tmp_name']);


        /**
         * Move the file to the new dir specified by user
         */
        $moveUploadFile = move_uploaded_file(
            $fileToUpload['tmp_name'],
            $this->allowedUploadDirectory . '/' . $newFileName
        );



        /**
         * Check if every validation has gone as expected.
         * If true, return the new file name with its extension as a positive response.
         */
        if ($checkSafeUpload && $moveUploadFile) {
            return $this->allowedUploadDirectory;
        } else {


            /**
             * If file upload has not worked for any reason, then debug the server environment/permission
             * and its settings  etc.. for possible errors.
             */
            $checkServerForErrors = $this->debugEnvironment($this->allowedUploadDirectory);


            /**
             * If error is found from the debugEnvironment() return the error, otherwise show any error as a last resort
             */
            return $checkServerForErrors ? $checkServerForErrors : "Unknown error occured, please try later";
        }
    }
}
