<?php
/**
 * BULLETPROOF
 *
 * Bulletproof is a single-class PHP library to upload images securely.
 *
 * PHP support 5.3+
 *
 * @package     bulletproof
 * @version     3.2.0
 * @author      https://twitter.com/_samayo
 * @link        https://github.com/samayo/bulletproof
 * @license     MIT
 */
namespace BulletProof;

class Image implements \ArrayAccess
{
    /**
     * @var string The new image name, to be provided or will be generated.
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
    protected $mime;

    /**
     * @var string The full image path (dir + image + mime)
     */
    protected $fullPath;

    /**
     * @var string The folder or image storage location
     */
    protected $location;

    /**
     * @var array A json format of all information about an image
     */
    protected $serialize = array();

    /**
     * @var array The min and max image size allowed for upload (in bytes)
     */
    protected $size = array(100, 500000);

    /**
     * @var array The max height and width image allowed
     */
    protected $dimensions = array(5000, 5000);

    /**
     * @var array The mime types allowed for upload
     */
    protected $mimeTypes = array('jpeg', 'png', 'gif', 'jpg');

    /**
     * @var array list of known image types
     */
    protected $imageMimes = array(
        1 => 'gif', 'jpeg', 'png', 'swf', 'psd',
        'bmp', 'tiff', 'tiff', 'jpc', 'jp2', 'jpx',
        'jb2', 'swc', 'iff', 'wbmp', 'xbm', 'ico'
    );
    /**
     * @var array error messages strings
     */
    protected $common_upload_errors = array(
        UPLOAD_ERR_OK         => '',
        UPLOAD_ERR_INI_SIZE   => 'Image is larger than the specified amount set by the server',
        UPLOAD_ERR_FORM_SIZE  => 'Image is larger than the specified amount specified by browser',
        UPLOAD_ERR_PARTIAL    => 'Image could not be fully uploaded. Please try again later',
        UPLOAD_ERR_NO_FILE    => 'Image is not found',
        UPLOAD_ERR_NO_TMP_DIR => 'Can\'t write to disk, due to server configuration ( No tmp dir found )',
        UPLOAD_ERR_CANT_WRITE => 'Failed to write file to disk. Please check you file permissions',
        UPLOAD_ERR_EXTENSION  => 'A PHP extension has halted this file upload process'
    );
    /**
     * @var array storage for the $_FILES global array
     */
    private $_files = array();
    /**
     * @var string storage for any errors
     */
    private $error = '';

    /**
     * @param array $_files represents the $_FILES array passed as dependency
     */
    public function __construct(array $_files = array())
    {
        /* check if php_exif is enabled */
        if (!function_exists('exif_imagetype')) {
            $this->error = 'Function \'exif_imagetype\' Not found. Please enable \'php_exif\' in your PHP.ini';
            return null;
        }

        $this->_files = $_files;
    }

    /**
     * @param mixed $offset
     * @param mixed $value
     */
    public function offsetSet($offset, $value)
    {
    }

    /**
     * @param mixed $offset
     * @return null
     */
    public function offsetExists($offset)
    {
    }

    /**
     * @param mixed $offset
     */
    public function offsetUnset($offset)
    {
    }

    /**
     * Gets array value \ArrayAccess
     *
     * @param mixed $offset
     *
     * @return string|boolean
     */
    public function offsetGet($offset)
    {   
        /* return error if requested */
        if ($offset == 'error') {
            return $this->error;
        }
        
        /* return false if $image['key'] isn't found */
        if (!isset($this->_files[$offset])) {
            return false;
        }

        $this->_files = $this->_files[$offset];

        /* check for common upload errors */
        if (isset($this->_files['error'])) {
            $this->error = $this->commonUploadErrors($this->_files['error']);
        }

        return true;
    }

    /**
     * Checks for the common upload errors
     *
     * @param $errors int error constant
     *
     * @return string
     */
    protected function commonUploadErrors($errors)
    {
        return $this->common_upload_errors[$errors];
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
     * Returns the full path of the image ex 'location/image.mime'
     *
     * @return string
     */
    public function getFullPath()
    {
        $this->fullPath = $this->location . '/' . $this->name . '.' . $this->mime;
        return $this->fullPath;
    }

    /**
     * Returns the image size in bytes
     *
     * @return int
     */
    public function getSize()
    {
        return (int)$this->_files['size'];
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
     * @return null|string
     */
    public function getMime()
    {
        if (!$this->mime) {
            return $this->getImageMime($this->_files['tmp_name']);
        }
        return $this->mime;
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
     * Gets the real image mime type
     *
     * @param $tmp_name string The upload tmp directory
     *
     * @return null|string
     */
    protected function getImageMime($tmp_name)
    {
        $mime = @$this->imageMimes[exif_imagetype($tmp_name)];

        if (!$mime) {
            return null;
        }

        return $mime;
    }

    /**
     * Returns error string or false if no errors occurred
     *
     * @return string|false
     */
    public function getError()
    {
        return $this->error != '' ? $this->error : false;
    }

    /**
     * This methods validates and uploads the image
     * @return false|Image
     */
    public function upload()
    {
        /* modify variable names for convenience */
        $image = $this;
        $files = $this->_files;

        if ($this->error || !isset($files['tmp_name'])) {
            return false;
        }

        /* check image for valid mime types and return mime */
        $image->mime = $image->getImageMime($files['tmp_name']);
        /* validate image mime type */
        if (!in_array($image->mime, $image->mimeTypes)) {
            $ext = implode(', ', $image->mimeTypes);
            $image->error = sprintf('Invalid File! Only (%s) image types are allowed', $ext);
            return false;
        }

        /* initialize image properties */
        $image->name = $image->getName();
        $image->width = $image->getWidth();
        $image->height = $image->getHeight();
        $image->location = $image->getLocation();

        /* get image sizes */
        list($minSize, $maxSize) = $image->size;

        /* check image size based on the settings */
        if ($files['size'] < $minSize || $files['size'] > $maxSize) {
            $min = intval($minSize / 1000) ?: 1;
            $max = intval($maxSize / 1000) ?: 1;
            $image->error = 'Image size should be at least ' . $min . ' KB, and no more than ' . $max . ' KB';
            return null;
        }

        /* check image dimension */
        list($allowedWidth, $allowedHeight) = $image->dimensions;

        if ($image->height > $allowedHeight || $image->width > $allowedWidth) {
            $image->error = 'Image height/width should be less than ' . $allowedHeight . '/' . $allowedWidth . ' pixels';
            return false;
        }

        if ($image->height < 2 || $image->width < 2) {
            $image->error = 'Image height/width too small or corrupted.';
            return false;
        }

        /* set and get folder name */
        $image->fullPath = $image->location . '/' . $image->name . '.' . $image->mime;

        /* gather image info for json storage */
        $image->serialize = array(
            'name' => $image->name,
            'mime' => $image->mime,
            'height' => $image->height,
            'width' => $image->width,
            'size' => $files['size'],
            'location' => $image->location,
            'fullpath' => $image->fullPath
        );

        if ($image->error === '') {
            $moveUpload = $image->moveUploadedFile($files['tmp_name'], $image->fullPath);
            if (false !== $moveUpload) {
                return $image;
            }
        }

        $image->error = 'Upload failed, Unknown error occured';
        return false;
    }

    /**
     * Returns the image name
     *
     * @return string
     */
    public function getName()
    {
        if (!$this->name) {
            return uniqid('', true) . '_' . str_shuffle(implode(range('e', 'q')));
        }

        return $this->name;
    }

    /**
     * Provide image name if not provided
     *
     * @param null $isNameProvided
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
     * Returns the image width
     *
     * @return int
     */
    public function getWidth()
    {
        if ($this->width != null) {
            return $this->width;
        }

        list($width) = getImageSize($this->_files['tmp_name']);
        return $width;
    }

    /**
     * Returns the image height in pixels
     *
     * @return int
     */
    public function getHeight()
    {
        if ($this->height != null) {
            return $this->height;
        }

        list(, $height) = getImageSize($this->_files['tmp_name']);
        return $height;
    }

    /**
     * Returns the storage / folder name
     *
     * @return string
     */
    public function getLocation()
    {
        if (!$this->location) {
            $this->setLocation();
        }

        return $this->location;
    }

    /**
     * Creates a location for upload storage
     *
     * @param $dir string the folder name to create
     * @param int $permission chmod permission
     *
     * @return $this
     */
    public function setLocation($dir = 'bulletproof', $permission = 0666)
    {

        if (!file_exists($dir) && !is_dir($dir) && !$this->location) {
            $createFolder = @mkdir('' . $dir, (int)$permission, true);
            if (!$createFolder) {
                $this->error = 'Error! Folder ' . $dir . ' could not be created';
                return false;
            }
        }

        /* check if we can create a file in the directory */
        if (!is_writable($dir)) {
            $this->error = 'The images directory \'' . $dir . '\' is not writable!';
            return false;
        }

        $this->location = $dir;
        return $this;
    }

    /**
     * Final upload method to be called, isolated for testing purposes
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