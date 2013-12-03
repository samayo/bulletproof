<?php

namespace SecureUploader;

/**
 * A secure and simple image uploader class. You may upload files to,
 * but it is best suited for images such as jpg, gif, png types/variations.
 * @author     Simon _eQ <https://github.com/simon-eQ>
 * @license    Public domain. Do anything you want with it.
 */


class Suploader
{

    /**
     * Create a set of default properties, just in case if you are uploading
     * the same size, dimension and type of images to same folder. If not,
     * these values can be overridden later.
     * @var array
     */
    private $fileType            = array("jpg", "png", "gif");
    private $fileSize            = array("min"=>100, "max"=>30000);
    private $imageDimensions     = array("max-height"=>1150, "max-width"=>1150);
    private $uploadFolder        = "uploads/";


    /**
     * Set file size (in bytes) by giving an array value with max and min sizes
     * @param array $setFileSize ex: array("min"=>100, "max"=>30000);
     * @return $this
     */
    public  function setFileSize(array $setFileSize)
    {
        $this->fileSize =  $setFileSize;
        return $this;
    }

    /**
     * Set file MIME type, as many as you want
     * @param array $setFileType ex: array("jpg", "png");
     * @return $this
     */
    public function setFileType(array $setFileType)
    {
        $this->fileType = $setFileType;
        return $this;
    }

    /**
     * If this method is called and set, then this class will asume
     * you are trying to upload an image, so during file upload
     * image size validation will take place.
     * @param array $setDimensions
     * @return $this
     */
    public function setImageDimensions(array $setDimensions)
    {
        $this->imageDimensions = $setDimensions;
        return $this;
    }

    /**
     * Set a directory to upload the files into.
     * @param $folderName ex: 'uploads/'
     * @return $this
     */
    public function setFolder($folderName)
    {
        $this->uploadFolder = $folderName;
        return $this;
    }


    /**
     * Check for common file upload errors thrown by the $_FILE[]['error'] global
     * @return array
     */
    public function commonFileUploadErrors()
    {
        /**
         * We can use the key identifier from $_FILES[]['error'] to output
         * the corresponding errors messages. Damn I'm good! :D
         */
        return array(
            UPLOAD_ERR_OK           => "...",
            UPLOAD_ERR_INI_SIZE     => "File is larger than the specified amount set by the server",
            UPLOAD_ERR_FORM_SIZE    => "Files is larger than the specified amount specified by browser",
            UPLOAD_ERR_PARTIAL      => "File could not be fully uploaded. Please try again later",
            UPLOAD_ERR_NO_FILE      => "File is not found",
            UPLOAD_ERR_NO_TMP_DIR   => "Can't write to disk, as per server configuration",
            UPLOAD_ERR_EXTENSION    => "A PHP extension has halted this file upload process"
        );
    }




    /**
     * There are many reasons for a file upload not work, other than from the information
     * obtained by the $_FILES[]['error'] array, So, this function tends to debug server
     * environment for a possible cause of an error as a last resort, if file uploaded wasn't a success
     * @param null $newDirectory optional directory, if not specified this class will use tmp_name
     * @return string
     */
    public function debugEnviroment($newDirectory = null)
    {
        /**
         * If user has specified upload dir, check and debug it first otherwise,
         * check the default dir given by PHP
         */
        $uploadFileTo = $newDirectory ? $newDirectory : init_get("file_uploads");

        /**
         * check the directory (if) specified by user is indeed dir or not
         */
        if(!is_dir($uploadFileTo))
        {
            return "Please make sure this is a valid directory, or php 'file_uploads' is turned on";
        }

        /**
         * Still not sure how this is done. But, I am trying to check check if given
         * upload directory has write permissions
         */
        if(!substr(sprintf('%o', fileperms($uploadFileTo)), -4) != 0777)
        {
            return "Sorry, you don't have her majesty's permission to upload files on this server";
        }

    }


    /**
     * A final method to validate, rename and upload the file.
     * @param $fileToUpload
     * @param $newFileName
     * @return bool|string
     */
    public function upload($fileToUpload, $newFileName = null)
    {

        /**
         * First let's start with the easiest method, by checking if
         * $_FILES['name']['error'] is not '0'. (means there is an error)
         */
        if($fileToUpload['error'])
        {
            $errors = $this->commonFileUploadErrors();
            return $errors[$fileToUpload['error']];
        }


        /**
         * Since the file type provided by $_FILES is unreliable due to
         * system/browser variations, we will double check the real type/extension
         * with SplFileInfo::getExtension();
         */

        $splFileInfo       = new SplFileInfo($fileToUpload['name']);
        $splFileExtension  = $splFileInfo->getExtension();

        /**
         * get rid of the 'image/' part from ex: 'image/gif' to get the extension
         * so we can compare it with what is expected. ex: array('jpg','png',''gif)
         */
        $fileTypeExtension = substr($fileToUpload['type'], 6);

        /**
         * Since 'SplFileInfo::getExtension()' and FILES[]['type'] give often different
         * names even for the same file, ex: "jpeg vs jpg" ".doc vs application/msword"
         * We can't really check if both are identical, so the best bet is to check
         * if they are both inside the $allowedMimeTypes set by the user
         */
        if(!in_array($fileTypeExtension, $this->fileExtensions) ||
           !in_array($splFileExtension, $this->fileExtensions))
        {
            return "This is not allowed File type. Please only upload ("
                . implode(' ,', $this->fileExtensions) .") file types";
        }

        /**
         * Once file is validated and is OK, retain the real extension for a later use
         */
        $realFileExtension = ".".$splFileExtension;


        /**
         * Check if file size is within the scope of what the user has defined.
         */
        if($fileToUpload['size'] < $this->fileSize['min'] ||
            $fileToUpload['size'] > $this->fileSize['max'])
        {
            return "Min & Max file sizes must be less in-between
                    ".(implode(" to ", $this->fileSize))." kilobytes";
        }


        /**
         * IMPORTANT:
         * If the value for "$this->allowedFileDimensions", is set, it means the uploaded image must be
         * validated as an image. Meaning, we can check the dimensions of the image in pixels.
         * If not set however, checking for image dimensions is not relevant here
         */
        if($this->imageDimensions)
        {
            list($width, $height, $type, $attr) = getimagesize($fileToUpload['tmp_name']);

            if($width > $this->imageDimensions['max-width'] ||
               $height > $this->imageDimensions['min-width'])
            {
                return "Image must be less than "
                        .$this->imageDimensions['max-width']."pixels wide and"
                        .$this->imageDimensions['max-height']."pixels in height";
            }


            if($height <= 1 || $width <=1)
            {
                return "This file is either too small or corrupted to be an image file";
            }

        }

        /**
         * Check whether user has passed a second argument as a new name for this file
         */
        if($newFileName)
        {
            /**
             * If given a file name, then assign it and append the new extension obtained
             * from the SplFileInfo::getExtension();
             */
            $newFileName = $newFileName.$realFileExtension;
        }else{

            /**
             * If not, create a 54 digit length id for the file.
             * by combining random string + a unique id.
             */
            $uniqid = uniqid(str_shuffle(implode(range(1, 10))), true);
            $newFileName = $uniqid.$realFileExtension;
        }


        /**
         * According the the PHP manual, is_uploaded_file() is mandatory to check if file was
         * posted from HTTP POST metho,  as an additional security check.
         */
        $checkSafeUpload = is_uploaded_file($fileToUpload['tmp_name']);

        /**
         * Move the file to the new dir specified by user
         */
        $moveUploadFile = move_uploaded_file($fileToUpload['tmp_name'],
                                            $this->uploadFolder.'/'.
                                            $newFileName);


        /**
         * Check if every validation has gone as expected.
         * If true, return the new file name with the extension as a positive response.
         */
        if($checkSafeUpload && $moveUploadFile)
        {
            return $newFileName;
        }
        else
        {
            /**
             * If file upload has not worked for any reason, the debug the server environment and its
             * permissions, settings [more to be added] etc.. for possible errors.
             */
            $checkServerForErrors = $this->debugEnviroment($this->uploadFolder);

            /**
             * If error is found from the debugEnviroment() return the error, otherwise show any error as a last resort
             */
            return $checkServerForErrors ? $checkServerForErrors : "Unknown error occured, please try later";
        }


    }

}
