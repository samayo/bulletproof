<?php

namespace ImageUploader;

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
     * Create a set of default properties for image size, types, dimensions,
     * these settings can be overridden later through getters/setters
     * @var array
     */
    private $fileType = array("jpg", "png", "gif");
    private $fileSize = array("min" => 100, "max" => 30000);
    private $imageDimensions = array("max-height" => 1150, "max-width" => 1150);
    private $uploadFolder = "uploads/";


    /**
     * Set file size (in bytes) by passing an array value with max and min sizes
     * @param array $setFileSize ex: array("min"=>100, "max"=>30000);
     * @return $this
     */
    public function setFileSize(array $setFileSize)
    {
        $this->fileSize = $setFileSize;
        return $this;
    }

    /**
     * Set file MIME type you want users to upload
     * @param array $setFileType ex: array("jpg", "png");
     * @return $this
     */
    public function setFileType(array $setFileType)
    {
        $this->fileType = $setFileType;
        return $this;
    }

    /**
     * If this method set, then this class will assume every upload is
     * an image type, and will try to check dimensions from it to validate
     * it as an image. For uploading a non-image file, don't call this class.
     * @param array $setDimensions
     * @return $this
     */
    public function setImageDimensions(array $setDimensions)
    {
        $this->imageDimensions = $setDimensions;
        return $this;
    }

    /**
     * Set a folder/directory to upload the files into.
     * @param $folderName ex: 'uploads/'
     * @return $this
     */
    public function setFolder($folderName)
    {
        $this->uploadFolder = $folderName;
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
        $uploadFileTo = $newDirectory ? $newDirectory : init_get("file_uploads");


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
     * The final method that validates, renames and uploads the image/file.
     * @param $fileToUpload ex: $_FILES['name'];
     * @param $newFileName  ex: 'uploads/'
     * @return bool|string
     */
    public function upload($fileToUpload, $newFileName = null)
    {

        /**
         * First let's start with the easiest method, by checking if
         * $_FILES['name']['error'] is not '0'.  If so, then there is
         * an error thrown by the $_FILES array.
         */
        if ($fileToUpload['error']) {
            $errors = $this->commonFileUploadErrors();
            return $errors[$fileToUpload['error']];
        }


        /**
         * Since the file type provided by $_FILES is unreliable due to system/browser
         * variations, we will double check the real type/extension
         * with the SplFileInfo::getExtension(); method.
         */
        $splFileInfo = new SplFileInfo($fileToUpload['name']);
        $splFileExtension = $splFileInfo->getExtension();


        /**
         * Check if the given extension exists in the settings.
         */
        if (!in_array($splFileExtension, $this->fileExtensions)) {
            return "This is not allowed File type. Please only upload ("
                . implode(' ,', $this->fileExtensions) . ") file types";
        }


        /**
         * Check if the Min & Max file size is within the scope of what
         * is expected in the setFileSize() method.
         */
        if ($fileToUpload['size'] < $this->fileSize['min'] ||
            $fileToUpload['size'] > $this->fileSize['max']
        ) {
            return "Min & Max file sizes must be less in-between
                    " . (implode(" to ", $this->fileSize)) . " kilobytes";
        }


        /**
         * Important!
         * If a value is passed through setImageDimensions(); then, this class
         * will validate the uploaded material as if it was an image, by checking the height
         * and width for pixels. If value is set to NULL however, this class will
         * not check height/width. So, you can upload a normal file
         */
        if ($this->imageDimensions) {

            /**
             * Get width and height of the image file for validation
             */
            list($width, $height, $type, $attr) = getimagesize($fileToUpload['tmp_name']);

            if ($width > $this->imageDimensions['max-width'] ||
                $height > $this->imageDimensions['min-width']
            ) {
                return "Image must be less than "
                    . $this->imageDimensions['max-width'] . "pixels wide and"
                    . $this->imageDimensions['max-height'] . "pixels in height";
            }

            /**
             * If height/width are smalled than 1, the image is unlikely to be valid.
             */
            if ($height <= 1 || $width <= 1) {
                return "This file is either too small or corrupted to be an image file";
            }

        }


        /**
         * check weather, a new name is assigned for this file, through the second argument.
         */
        if ($newFileName) {

            /**
             * If a file name is set, then assign it and append the new extension obtained
             * from the SplFileInfo::getExtension();
             */
            $newFileName = $newFileName . "." . $splFileExtension;
            
        } else {


            /**
             * If not, create a 54 digit length id for the file.
             * by combining random string + a unique id.
             */
            $uniqid = uniqid(str_shuffle(implode(range(1, 10))), true);
            $newFileName = $uniqid . "." . $splFileExtension;
            
        }


        /**
         * According the the PHP manual, is_uploaded_file() is mandatory to check if file was
         * posted from HTTP POST metho,  as an additional security check.
         */
        $checkSafeUpload = is_uploaded_file($fileToUpload['tmp_name']);


        /**
         * Move the file to the new dir specified by user
         */
        $moveUploadFile = move_uploaded_file($fileToUpload['tmp_name'], $this->uploadFolder . '/' . $newFileName);


        /**
         * Check if every validation has gone as expected.
         * If true, return the new file name with its extension as a positive response.
         */
        if ($checkSafeUpload && $moveUploadFile) {
            return $newFileName;
        } else {

            /**
             * If file upload has not worked for any reason, then debug the server environment/permission
             * and its settings  etc.. for possible errors.
             */
            $checkServerForErrors = $this->debugEnvironment($this->uploadFolder);

            /**
             * If error is found from the debugEnvironment() return the error, otherwise show any error as a last resort
             */
            return $checkServerForErrors ? $checkServerForErrors : "Unknown error occured, please try later";
        }


    }

}
