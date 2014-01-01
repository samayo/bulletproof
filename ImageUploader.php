<?php
namespace BulletProof;
/**
 * BulletProof ImageUploder:
 * With this class, you can Resize, add Watermarks and Upload images with best security.
 *
 * Development is still on going to add more features. You can upload files too, but
 * the class is made for image uploads, Therefore, pls
 * use at your own risk, and help by positing out some bugs/flaws as much as
 * possible.
 *
 * @author     Simon _eQ <https://github.com/simon-eQ>
 * 
 */


class ImageUploader
{

    /**
     * Set a group of default files types to upload.
     * @var array
     */
    private $setFileTypes = array("gif", "jpg", "png", "jpeg", "tiff");

    /**
     * Set the min & max file upload size in bytes. Remember: ~30kb === 30000bytes
     * @var array
     */
    private $setFileSize = array("min" => 100, "max" => 30000);

    /**
     * Store width/height image dimensions, in pixels. ex: 100*100
     * @var array
     */
    private $setImageDimensions = array();

    /**
     * Getting width/height of the watermark helps calculate its footprint position
     * @var
     */
    private $getWatermarkDimension = array();

    /**
     * Set new size to crop/resize the image. ex: array('width'=>100, 'height'=>100);
     * @var array
     */
    private $setImageDimensionsForResizing = array();

    /**
     * Set a folder to upload all files into.
     * @var
     */
    private $setUploadDirectory;

    /**
     * Set an image to use as a watermark/stamp on top of other images
     * @var
     */
    private $setImageToWatermark;

    /**
     * Set a text to use as a watermark (alternative to image watermarking)
     * @var
     */
    private $setTextToWatermark;

    /**
     * Set a position for watermark in words ex: top-right, top-left, center, bottom-right...
     * @var
     */
    private $setWatermarkPosition;

    /**
     * Store the real file extension for multiple call/re-use inside methods..
     * @var
     */
    private $getRealFileExtension;


    /**
     * Set file types that must be uploaded. ex: array('png', 'gif', 'jpg').
     * @param array $fileTypes
     * @return $this
     */
    public function setFileTypes(array $fileTypes)
    {
        $this->setFileTypes = $fileTypes;
        return $this;
    }

    /**
     * Set min & max file size in pixels. ex ['min'=>500, 'max'=>500]
     * @param array $fileSize
     * @return $this
     */
    public function setSizeLimit(array $fileSize)
    {
        $this->setFileSize = $fileSize;
        return $this;
    }

    /**
     * Pass Width & Height of images for uploading
     * @param array $setImageDimensions
     * @return $this
     */
    public function setImageSize(array $setImageDimensions)
    {
        $this->setImageDimensions = $setImageDimensions;
        return $this;
    }

    /**
     * Tell PHP where to put all the uploaded files into. ex: 'pictures/'
     * @param $folderToUpload
     * @return $this
     */
    public function uploadTo($folderToUpload)
    {
        $this->setUploadDirectory = $folderToUpload;
        return $this;
    }

    /**
     * Set width * height in pixels to  resize/crop all images accordingly.
     * @param array $imageDimensions
     * @return $this
     */
    public function resizeImageTo(array $imageDimensions)
    {
        $this->setImageDimensionsForResizing = $imageDimensions;
        return $this;
    }


    /**
     * Function to set the watermark and the position.
     * @param $imageOrTextToWatermark
     * @param $watermarkPosition
     * @return $this
     * @throws \ErrorException
     */
    public function watermark($imageOrTextToWatermark, $watermarkPosition)
    {
        /**
         * no file security check is needed, as the logo is always in your server
         * we'll check if file exists only to determine if watermark is text/image
         * MAKE sure to put a valid image in your folder
         */
        if (file_exists($imageOrTextToWatermark)) {
            $this->setImageToWatermark = $imageOrTextToWatermark;
            $this->getWatermarkDimension = $this->getImagePixels($imageOrTextToWatermark);
            $this->setWatermarkPosition = $watermarkPosition;
            return $this;
        }

        /** if no file is found, treat the argument as if a text to be watermarked **/
        if (is_int($imageOrTextToWatermark) || is_string($imageOrTextToWatermark)) {
            $this->setTextToWatermark = $imageOrTextToWatermark;
            $this->getWatermarkDimension = array(10, 10);
            $this->setWatermarkPosition = $watermarkPosition;
            return $this;
        }

        return "Method " . __FUNCTION__ . " called without passing a valid image/string to watermark"; 
    }

    /**
     * We can call this function multiple times to get file extensions
     * @param $fileName
     * @return string
     */
    protected function getFileExtension($fileName)
    {
        return strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
    }


    /**
     * Encapsulate this help to call it multiple times, instead of using
     * getImageSize() function various times, from within other methods.
     * @param $imageName
     * @return array
     */
    private function getImagePixels($imageName)
    {
        list($width, $height) = getImageSize($imageName);
        return array($width, $height);
    }


    /**
     * Create a costume array for each possible error that may be
     * thrown by the $_FILES[] array.
     * @return array
     */
    private function commonFileUploadErrors()
    {
        return [
            UPLOAD_ERR_OK => "...",
            UPLOAD_ERR_INI_SIZE => "File is larger than the specified amount set by the server",
            UPLOAD_ERR_FORM_SIZE => "Files is larger than the specified amount specified by browser",
            UPLOAD_ERR_PARTIAL => "File could not be fully uploaded. Please try again later",
            UPLOAD_ERR_NO_FILE => "File is not found",
            UPLOAD_ERR_NO_TMP_DIR => "Can't write to disk, as per server configuration",
            UPLOAD_ERR_CANT_WRITE => "Failed to write file to disk. Introduced in PHP",
            UPLOAD_ERR_EXTENSION => "A PHP extension has halted this file upload"
        ];
    }


    /**
     * Get image width and height for validation.
     * @param $fileName
     * @return string
     */
    private function validateImagePixels($fileName)
    {
        /**
         * get width and height for validation.
         */
        list($width, $height) = $this->setImageDimensions;
        list($allowedWidth, $allowedHeight) = $this->getImagePixels($fileName);

        /**
         * Check if width and height do not surpass the limit already assigned.
         */
        if ($width >= $allowedWidth || $height >= $allowedHeight) {
            return "Image must be less than " .
                $allowedHeight . " pixels height and " .
                $allowedWidth . " pixels in wide";
        }

        /**
         * If 'image' has no pixels, then it is likely to be invalid or corrupt. Even at 1px
         */
        if ($width <= 1 || $height <= 1) {
            return "This file is either too small or corrupted to be an image file";
        }

        return false;
    }


    /**
     * If file name is passed as the second argument, use it as a name for this file,
     * otherwise use a randome + uniqid as a nem
     */
    private function newFileName($isNameProvided)
    {
        if ($isNameProvided) {
            return $isNameProvided . "." . $this->getRealFileExtension;
        }

        return uniqid(str_shuffle(implode(range(1, 20)))) . "." . $this->getRealFileExtension;
    }


    /**
     * The objective is to let position of watermark be passed in words ex:
     * 'center', 'right-top', 'bottom-left', etc.. and then calculate the
     * position of the watermark
     * @param $getImageSize
     * @return array
     */
    private function calculateWatermarkPosition($getImageSize)
    {
        $size = $this->getImagePixels($getImageSize);
        $position = $this->setWatermarkPosition;
        $imageWidth = $size['1'];
        $imageHeight = $size['0'];

        list($watermarkHeight, $watermarkWidth) = $this->getWatermarkDimension;


        switch ($position) {
            case 'center':
                $bottomPosition = ($imageHeight / 2) - $watermarkHeight;
                $rightPosition = ($imageWidth / 2) - $watermarkWidth;
                break;

            case 'bottom-left':
                $bottomPosition = 0;
                $rightPosition = $imageWidth - $watermarkWidth;
                break;

            case 'top-left':
                $bottomPosition = $imageHeight - $watermarkHeight;
                $rightPosition = $imageWidth - $watermarkWidth;
                break;

            case 'top-right':
                $bottomPosition = $imageHeight - $watermarkHeight;
                $rightPosition = 0;
                break;

            default:
                $bottomPosition = 0;
                $rightPosition = 0;
                break;
        }

        return array($bottomPosition, $rightPosition);


    }

    /**
     * Apply watermark.
     * @param $imageToWatermark
     * @param $watermarkPosition
     * @param $newName
     */
    private function applyWatermark($imageToWatermark, $watermarkPosition, $newName)
    {

        $watermark = $this->setImageToWatermark;
        list($marginBottom, $marginRight) = $watermarkPosition;

        $imageType = $this->getFileExtension($imageToWatermark);

        $stamp = imagecreatefrompng($watermark);

        switch ($imageType) {
            case 'jpg':
            case 'jpeg':
                $createImage = imagecreatefromjpeg($imageToWatermark);
                break;

            case 'png':
                $createImage = imagecreatefrompng($imageToWatermark);
                break;

            case 'gif':
                $createImage = imagecreatefromgif($imageToWatermark);
                break;

            default:
                $createImage = imagecreatefromjpeg($imageToWatermark);
                break;
        }


        $sx = imagesx($stamp);
        $sy = imagesy($stamp);
        imagecopy($createImage,
            $stamp,
            imagesx($createImage) - $sx - $marginRight,
            imagesy($createImage) - $sy - $marginBottom,
            0,
            0,
            imagesx($stamp),
            imagesy($stamp));
        imagepng($createImage, $this->setUploadDirectory . $newName);
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
        $this->getRealFileExtension = $this->getFileExtension($fileToUpload['name']);

        /**
         * Check if file extension exists, in the defined list of $allowedFileTypes
         */
        if (!in_array($this->getRealFileExtension, $this->setFileTypes)) {
            return "This is not allowed File type. Please only upload ("
                . implode(' ,', $this->setFileTypes) . ") file types";

        }

        /**
         * Check if $_FILE[]['error'] is set, and echo the corresponding error messages.
         */
        if ($fileToUpload['error']) {
            return ($this->commonFileUploadErrors()[$fileToUpload['error']]);
        }


        /**
         * Check if file min & max sizes do not exceed the limit passed
         */
        if ($fileToUpload['size'] <= $this->setFileSize['min'] ||
            $fileToUpload['size'] >= $this->setFileSize['max']
        ) {
            return "Files sizes must be in-between
                    " . (implode(" to ", $this->setFileSize)) . " kilobytes";
        }

        /**
         * If this variable is set, it means our script is trying to upload an image,
         * thus, it is important to validate the image by a given pixel value
         */
        if ($this->setImageDimensions) {
            $imageHasPixelError = $this->validateImagePixels($fileToUpload['tmp_name']);
            if ($imageHasPixelError) {
                return $imageHasPixelError;
            }
        }

        $newFileName = $this->newFileName($fileToRename);


        /**
         * If a value for newResizeDimensions is passed, then we'll
         * crop the image as indicated.
         */
        if ($this->setImageDimensionsForResizing) {
            list($width, $height) = getimagesize($fileToUpload['tmp_name']);
            $newWidth = ($height / $width) * $this->setImageDimensionsForResizing['width'];
            $newHeight = ($height / $width) * $this->setImageDimensionsForResizing['height'];

            /**
             * Read the binary data from the image file
             */
            $imgString = file_get_contents($fileToUpload['tmp_name']);

            /**
             * Create similar image file.
             */
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

            /**
             * Get image type to create image acordingly.
             */
            switch ($this->getRealFileExtension) {
                case 'jpeg':
                case 'jpg':
                    imagejpeg($tmp, $this->setUploadDirectory . $newFileName, 100);
                    break;
                case 'png':
                    imagepng($tmp, $this->setUploadDirectory . $newFileName, 0);
                    break;
                case 'gif':
                    imagegif($tmp, $this->setUploadDirectory . $newFileName);
                    break;
                default:
                    exit;
                    break;
            }
            return $this->setUploadDirectory;
        }


        /**
         * According the the PHP manual, is_uploaded_file() is mandatory to check if file was
         * posted from HTTP POST method,  as an additional security check.
         */
        $checkSafeUpload = is_uploaded_file($fileToUpload['tmp_name']);


        if ($this->setImageToWatermark) {

            $getWatermarkPosition = $this->calculateWatermarkPosition($fileToUpload['tmp_name']);
            $this->applyWatermark($fileToUpload['tmp_name'], $getWatermarkPosition, $newFileName);

            $moveUploadFile = move_uploaded_file(
                __DIR__ . $newFileName,
                $this->setUploadDirectory . '/' . $newFileName
            );
        } else {


            /**
             * Move the file to the new dir specified by user
             */
            $moveUploadFile = move_uploaded_file(
                $fileToUpload['tmp_name'],
                $this->setUploadDirectory . '/' . $newFileName
            );
        }

        /**
         * Check if every validation has gone as expected.
         * If true, return the new file name with its extension as a positive response.
         */
        if ($checkSafeUpload) {
            return $this->setUploadDirectory . $newFileName;
        } else {


            /**
             * If file upload has not worked for any reason, then debug the server environment/permission
             * and its settings  etc.. for possible errors.
             */
            $checkServerForErrors = $this->debugEnvironment($this->setUploadDirectory);


            /**
             * If error is found from the debugEnvironment() return the error, otherwise show any error as a last resort
             */
            return $checkServerForErrors ? $checkServerForErrors : "Unknown error occured, please try later";
        }
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
}
