<?php
/**
 * BulletProof
 *
 * A single class PHP-library for secure image uploading.
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
    /**
     * @var string The new image name, provided or auto-generated.
     */
    protected $name;

    /**
     * @var int The image width in pixels
     */
    protected $width;

    /**
     * @var int The image height in pixels
     */
    protected $height;

    /**
     * @var string The image mime type (extension)
     */
    protected $imageMime;

    /**
     * @var string The full image path (dir + image + mime)
     */
    protected $fullPath;

    /**
     * @var string The folder or image storage location
     */
    protected $location;

    /**
     * @var array The max height and width image allowed for upload
     */
    protected $dimensions = array(500, 5000);

    /**
     * @var array The min and max image size allowed for upload (in bytes)
     */
    protected $size = array(100, 50000);

    /**
     * @var array The mime types allowed for upload
     */
    protected $mimeTypes = array("jpeg", "png", "gif");

    /**
     * @var array A json format of all information about an image
     */
    protected $serialize = array();

    /**
     * @var array list of known image types
     */
    protected $imageMimes = array(
        1 => "gif", "jpeg", "png", "swf", "psd",
        "bmp", "tiff", "jpc", "jp2", "jpx",
        "jb2", "swc", "iff", "wbmp", "xmb", "ico"
    );

    /**
     * @var array storage for the $_FILES global array
     */
    private $image = array();

    /**
     * @var string storage for any errors
     */
    private $error = '';

    /**
     * @param array $image | the $_FILES array passed as dependancy
     */
    public function __construct(array $image = [])
    {
        $this->image = $image;
    }

    /**
     * Gets the real image mime type
     *
     * @param $tmp_name string The upload tmp directory
     *
     * @return bool|string
     */
    protected function getImageMime($tmp_name)
    {
        if (isset($this->imageMimes [exif_imagetype($tmp_name)])) {
            return $this->imageMimes [exif_imagetype($tmp_name)];
        }
        return false;
    }

    /**
     * Sets array offset \ArrayAccess
     * unused methods
     */
    public function offsetSet($offset, $value){}
    public function offsetExists($offset){}
    public function offsetUnset($offset){}

    /**
     * Gets array value \ArrayAccess
     *
     * @param mixed $offset
     *
     * @return bool|mixed
     */
    public function offsetGet($offset)
    {
        if (isset($this->image[$offset]) && $offset !== 'error') {
            $this->image = $this->image[$offset];
            if(!file_exists($this->image["tmp_name"])){
                return false; 
            }
            return true;
        }
        
        if ($offset == 'error') {
            return $this->error;
        }

        return false;
    }

    /**
     * Renames image
     *
     * @param null $isNameGiven if null, image will be auto-generated
     *
     * @return $this
     */
    public function setName($isNameProvided = null)
    {
        if ($isNameProvided) {
            $this->name = filter_var($isNameProvided, FILTER_SANITIZE_STRING);
        }
        
        return $this;
    }

    /**
     * Define a mime type for uploading
     *
     * @param array $fileTypes
     *
     * @return $this
     */
    public function setMime(array $fileTypes)
    {
        $this->mimeTypes = $fileTypes;
        return $this;
    }

    /**
     * Define a min and max image size for uploading
     *
     * @param $min int minimum value in bytes
     * @param $max int maximum value in bytes
     *
     * @return $this
     */
    public function setSize($min, $max)
    {
        $this->size = array($min, $max);
        return $this;
    }

    /**
     * Creates a location for upload storage
     *
     * @param $dir string the folder name to create
     * @param int $permission chmod permission
     *
     * @return $this
     */
    public function setLocation($dir = "images", $permission = 0666)
    {
        if($this->location){
            return $this;
        }

        if (!file_exists($dir) && !is_dir($dir)) {
            $createFolder = @mkdir("" . $dir, (int)$permission, true);
            if (!$createFolder) {
                $this->error = "Folder " . $dir . " could not be created";
                return;
            }
        }

        $this->location = $dir;
        return $this;
    }

    /**
     * Sets acceptable max image height and width
     *
     * @param $maxWidth int max width value
     * @param $maxHeight int max height value
     *
     * @return $this
     */
    public function setDimension($maxWidth, $maxHeight)
    {
        $this->dimensions = array($maxWidth, $maxHeight);
        return $this;
    }

    /**
     * Returns the image name
     *
     * @return string
     */
    public function getName()
    {
        if (!$this->name) {
           return  uniqid(true) . "_" . str_shuffle(implode(range("e", "q")));
        }

        return $this->name;
    }

    /**
     * Returns the full path of the image ex 'location/image.mime'
     *
     * @return string
     */
    public function getFullPath()
    {
        $this->fullPath = $this->location . '/' . $this->name . '.' . $this->imageMime;
        return $this->fullPath;

    }

    /**
     * Returns the image size in bytes
     *
     * @return int
     */
    public function getSize()
    {
        return (int) $this->image['size'];
    }

    /**
     * Returns the image height in pixels
     *
     * @return int
     */
    public function getHeight()
    {
        if (null != $this->height) {
            return $this->height;
        }

        list(, $height) = getImageSize($this->image["tmp_name"]); 
        return $height;
    }

    /**
     * Returns the image width
     *
     * @return int
     */
    public function getWidth()
    {
        if ($this->width != null) {
            return $this->width;
        }

        list($width) = getImageSize($this->image["tmp_name"]); 
        return $width;
    }

    /**
     * Returns the storage / folder name
     *
     * @return string
     */
    public function getLocation()
    {
        return $this->location;
    }

    /**
     * Returns a JSON format of the image width, height, name, mime ...
     *
     * @return string
     */
    public function getJson()
    {
        return json_encode($this->serialize);
    }

    /**
     * Returns the image mime type
     *
     * @return string
     */
    public function getMime()
    {
        return $this->imageMime;
    }

    /**
     * Deletes image from storage
     *
     * @param $fileToDelete string folder name
     *
     * @return bool
     */
    public function remove($fileToDelete)
    {
        if (file_exists($fileToDelete) && !unlink($fileToDelete)) {
            $this->error = "File may have been deleted or does not exist";
            return false;
        }

        return true;
    }

    /**
     * Checks for the common upload errors
     *
     * @param $e int error constant
     */
    protected function uploadErrors($e)
    {
        $errors = array(
            UPLOAD_ERR_OK           => "",
            UPLOAD_ERR_INI_SIZE     => "Image is larger than the specified amount set by the server",
            UPLOAD_ERR_FORM_SIZE    => "Image is larger than the specified amount specified by browser",
            UPLOAD_ERR_PARTIAL      => "Image could not be fully uploaded. Please try again later",
            UPLOAD_ERR_NO_FILE      => "Image is not found",
            UPLOAD_ERR_NO_TMP_DIR   => "Can't write to disk, due to server configuration ( No tmp dir found )",
            UPLOAD_ERR_CANT_WRITE   => "Failed to write file to disk. Please check you file permissions",
            UPLOAD_ERR_EXTENSION    => "A PHP extension has halted this file upload process"
        );
        return $errors[$e];
    }

    /**
     * Main upload method.
     * This is where all the actual validation/upload takes place
     *
     * @return $this|bool
     */
    public function upload()
    {

        /* initialize image values to class properties */
        $this->name   = $this->getName();
        $this->height = $this->getHeight(); 
        $this->width  = $this->getWidth();
        $image        = $this->image;


        /* get image sizes */
        list($minSize, $maxSize) = $this->size;

        /* check for common upload errors */
        if($this->error = $this->uploadErrors($image["error"])){
            return ;
        }

        /* check image for valid mime types and return mime */
        $this->imageMime = $this->getImageMime($image["tmp_name"]);

        /* validate image mime type */
        if (!in_array($this->imageMime, $this->mimeTypes)) {
            $mimes = implode(', ', $this->mimeTypes);
            $this->error = "Invalid File! Only ($mimes) image types are allowed";
            return ;
        }     

        /* check image size based on the settings */
        if ($image["size"] < $minSize || $image["size"] > $maxSize) {
            $min = intval($minSize / 1000); $max = intval($maxSize / 1000);
            if(!$min){
                $min = 1; // to avoid getting '0kb' as size. 
            }
            $this->error = "Image size should be atleast more than min: $min and less than max: $max kb";
            return ;
        }

        /* check image dimension */
        list($maxHeight, $maxWidth) = $this->dimensions;

        if ($this->height > $maxHeight || $this->width > $maxWidth) {
            $this->error = "Image height/width should be less than ' $maxHeight \ $maxWidth ' pixels";
            return ;
        }
 
        /* set and get folder name */
        $this->fullPath = $this->setLocation()->getLocation(). "/" . $this->name . "." . $this->imageMime;

        /* gather image info for json storage */ 
        $this->serialize = array(
            "name"     => $this->name,
            "mime"     => $this->imageMime,
            "height"   => $this->height,
            "width"    => $this->width,
            "size"     => $image["size"],
            "location" => $this->location
        );

        if ($this->error === '') {
            $moveUpload = $this->moveUploadedFile($image['tmp_name'], $this->fullPath);
            if (false !== $moveUpload) {
                return $this;
            }
        }
        
        $this->error = "Upload failed, Unknown error occured";
        return false;
    }

    /**
     * Final method using php upload, isolated for testing purposes
     *
     * @param $tmp_name int the temporary location of the image file
     * @param $destination int upload destination
     *
     * @return bool
     */
    public function moveUploadedFile($tmp_name, $destination)
    {
        return move_uploaded_file($tmp_name, $destination);
    }
}
