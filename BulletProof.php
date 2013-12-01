<?php
/**
 * A small, secure & fast image uploader class written in all-static
 * class to give an extra boost in performance.
 * @author     Simon _eQ <https://github.com/simon-eQ>
 * @license    Public domain. Do anything you want with it.
 * Don't ever forget to use at your own risk.
 */


class BulletProof
{
    /**
     * User defined, allowed image extensions to upload. ex: 'jpg, png, gif'
     * @var
     */
    static $allowedMimeTypes;

    /**
     * Set a max. height and Width of image
     * @var
     */
    static $allowedFileDimensions;

    /**
     * Max file size to upload. Must be less than server/browser
     * MAX_FILE_UPLOAD directives
     * @var
     */
    static $allowedMaxFileSize;

    /**
     * Set the new directory / folder to upload new image into.
     * @var
     */
    static $directoryToUpload;

    /**
     * MIME type of the upload image/file
     * @var
     */
    static $fileMimeType;

    /**
     * A new name you have chosen to give the image
     * @var
     */
    static $newFileName;


    /**
     * Set of rules passed by user to choose what/where/how to upload.
     * @param array $allowedMimeTypes
     * @param array $allowedFileDimensions
     * @param $allowedMaxFileSize
     * @param $directoryToUpload
     */
    static function options(array $allowedMimeTypes,
                            array $allowedFileDimensions,
                                  $allowedMaxFileSize,
                                  $directoryToUpload)
    {
        /**
         * Globalize the score of the directives, so we can call them from another method.
         */
        self::$allowedMimeTypes = $allowedMimeTypes;
        self::$allowedFileDimensions = $allowedFileDimensions;
        self::$allowedMaxFileSize = $allowedMaxFileSize;
        self::$directoryToUpload = $directoryToUpload;
    }


    /**
     * Native and possible PHP errors provided by the $_FILES[] super-global.
     * @return array
     */
    static function commonFileUploadErrors()
    {
        /**
         * We can use the key identifier from $_FILES['error'] to match this array's key and
         * output the corresponding errors. Damn I'm good!
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
     * There are many reasons for a file upload not work, other than from the information we
     * can get of the $_FILES array. So, this function tends to debug server environment for
     * a possible cause of an error (if any)
     * @param null $newDirectory optional directory, if not specified this class will use tmp_name
     * @return string
     */
    static function checkServerPermissions($newDirectory = null)
    {
        $uploadFileTo = $newDirectory ? $newDirectory : init_get("file_uploads");

        /**
         * if the directory (if) specified by user is indeed dir or not
         */
        if(!is_dir($uploadFileTo))
        {
            return "Please make sure this is a valid directory, or php 'file_uploads' is turned on";
        }


        /**
         * Check if given directory has write permissions
         */
      //  if(!substr(sprintf('%o', fileperms($uploadFileTo)), -4) != 0777)
      //  {
      //      return "Sorry, you don't have her majesty's permission to upload files on this server";
      //  }

    }


    /**
     * Upload given files, after a series of validations
     * @param $fileToUpload
     * @param $newFileName
     * @return bool|string
     */
    static function upload($fileToUpload, $newFileName = null)
    {

        /**
         * check if file's MIME type is specified in the allowed MIME types
         */
        $fileMimeType = substr($fileToUpload['type'], 6);
        if(!in_array($fileMimeType, self::$allowedMimeTypes))
        {
            return "This file type is not allowed.";
        }

        self::$fileMimeType = $fileMimeType;

        /**
         * show if there is any error
         */
        if($fileToUpload['error'])
        {
            $errors = self::commonFileUploadErrors();
            return $errors[$fileToUpload['error']];
        }

        /**
         * Check if size of the file is greater than specified
         */
        if($fileToUpload['size'] > self::$allowedMaxFileSize)
        {
            return "File size must be less than ".(self::$allowedMaxFileSize / 100)." Kbytes";
        }

        /**
         * Checking image dimension is an enhancement as a feature but a must & wise check from
         * a security point of view.
         */
        list($width, $height, $type, $attr) = getimagesize($fileToUpload['tmp_name']);

        if($width > self::$allowedFileDimensions['max-width'] ||
           $height > self::$allowedFileDimensions['max-height'])
        {
            return "Image must be less than ". self::$allowedFileDimensions['max-width']."pixels wide
                    and ". self::$allowedFileDimensions['max-height']."pixels in height";
        }

        /**
         * No monkey business
         */
        if($height <= 1 || $width <= 1)
        {
            return "This is invalid Image type";
        }

        /**
         * If user has provided a new file name, assign it. Otherwise,
         * use a default uniqid id, to avoid name collision
         */
        if($newFileName)
        {
            self::$newFileName = $newFileName;
        }
        else
        {
            self::$newFileName = uniqid();
        }

        /**
         * Upload file.
         */
        $newUploadDir = self::$directoryToUpload;
        $upload = move_uploaded_file($fileToUpload['tmp_name'], $newUploadDir.'/'.self::$newFileName.'.'.self::$fileMimeType);

        if($upload)
        {
           return true;
        }
        else
        {
            /**
             * If file upload fails, the debug server enviroment as a last resort before giving 'Unknown error'
             */
            $systemErrorCheck = self::checkServerPermissions($newUploadDir);
            return $systemErrorCheck ? $systemErrorCheck : "Unknown error occured. Please try again later";
        }


    }

}
