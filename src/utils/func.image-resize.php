<?php
/**
 * bulletproof\utils\resize
 *
 * Image resize function for bulletproof library
 *
 * PHP support 5.3+
 *
 * @package     bulletproof
 * @version     3.2.0
 * @author      https://twitter.com/_samayo
 * @link        https://github.com/samayo/bulletproof
 * @license     MIT
 */


namespace Bulletproof\Utils;

/*
 *
 * @param      string      $image        Path to the image
 * @param      string      $mimeType     The mime type of the image
 * @param      integer     $imgWidth     The original image width
 * @param      integer     $imgHeight    The original image height
 * @param      integer     $newWidth     The new image width (to be resized to)
 * @param      integer     $newHeight    The new image height (to be resized to)
 * @param      boolean     $ratio        Whether to keep the ratio or no
 * @param      boolean     $upsize       Whether to allow upsizing of the image or no
 * @param      boolean     $cropToSize   Whether to crop image to size or no. If set to true $ratio is ignored.
 * @param      array       $quality      Sets what quality should be used for
 *                                       JPEG or what compression should be used
 *                                       for PNG. See following example: *
 *                                              array(
 *                                                   'jpg' => array(
 *                                                                  'orig'     => true,  // (bool) whether to use same quality as original image (requires ImageMagick)
 *                                                                  'fallback' => 80,    // (int)  fallback if original image quality can not be detected or is not set. Accepted values are 1-100
 *                                                                  'max'      => 85,    // (int)  Maximal quality, if detected quality is more than this value, than this will be used. Accepted values are 1-100
 *                                                                  'min'      => 60     // (int)  Minimal quality, if detected quality is less than this value, than this will be used. Accepted values are 1-100
 *                                                                  ),
 *                                                   'png' => 9 // (int) PNG compression level. Accepted values are 0-9. Default is zlib's default which is currently ( 11/2018 ) equal to 6
 *                                                  );
 *
 *                                       Any of the values can be left out. See following example:
 *                                               array( 'jpg' => array( 'fallback' => 80 ) ) // this will set JPG quality to 80 on JPGs
 *
 * @param      string      $destination  Accepts either folder path or file.
 *                                       Script can handle both relative and
 *                                       absolute paths. Folder path needs to
 *                                       end with '/' (forward slash). If left empty (or set to false) it will overwrite original image
 * @param      integer     $permission   The permission for destination folder (if it will be created by script)
 *
 * @throws     \Exception  (description)
 *
 * @return     boolean     True on success false on failure
 *
 */
function resize($image, $mimeType, $imgWidth, $imgHeight, $newWidth, $newHeight, $ratio = false, $upsize = true, $cropToSize = false, $quality = array(), $destination = false, $permission = 0755)
{
    // Checks whether image cropping is enabled
    if ($cropToSize) {
        $source_aspect_ratio = $imgWidth / $imgHeight;
        $thumbnail_aspect_ratio = $newWidth / $newHeight;

        // Adjust cropping area and position depending on original and cropped image
        if ($thumbnail_aspect_ratio < $source_aspect_ratio) {
            $src_h = $imgHeight;
            $src_w = $imgHeight * $thumbnail_aspect_ratio;
            $src_x = (int) (($imgWidth - $src_w)/2);
            $src_y = 0;
        } else {
            $src_w = $imgWidth;
            $src_h = (int) ($imgWidth / $thumbnail_aspect_ratio);
            $src_x = 0;
            $src_y = (int) (($imgHeight - $src_h)/2);
        }

        // Checks whether image upsizing is enabled
        if (!$upsize) {
            $newHeightOrig = $newHeight;
            $newWidthOrig = $newWidth;

            // If the given width is larger than the image width, then resize it
            if ($newWidth > $imgWidth) {
                $newWidth = $imgWidth;
                $newHeight = (int) ($newWidth / $imgWidth * $imgHeight);
                if ($newHeight > $newHeightOrig) {
                    $newHeight = $newHeightOrig;
                    $src_h = $newHeight;
                    $src_y = (int) (($imgHeight - $src_h)/2);
                }

                $src_x=0;
                $src_w = $imgWidth;
            }

            // If the given height is larger then the image height, then resize it.
            if ($newHeightOrig > $imgHeight) {
                $newHeight = $imgHeight;
                $src_y=0;
                $src_h = $imgHeight;
                $src_w = (int) ($src_h * ( $newWidth / $newHeight ));
                $src_x = (int) (($imgWidth - $src_w)/2);
            }
        }
    } else {

        // First, calculate the height.
        $height = (int) ($newWidth / $imgWidth * $imgHeight);  //  75

        // If the height is too large, set it to the maximum height and calculate the width.
        if ($height > $newHeight) {
            $height = $newHeight;
            $newWidth = (int) ($height / $imgHeight * $imgWidth);
        }

        // If we don't allow upsizing check if the new width or height are too big.
        if (!$upsize) {
            // If the given width is larger than the image width, then resize it
            if ($newWidth > $imgWidth) {
                $newWidth = $imgWidth;
                $newHeight = (int) ($newWidth / $imgWidth * $imgHeight);
            }

            // If the given height is larger then the image height, then resize it.
            if ($newHeight > $imgHeight) {
                $newHeight = $imgHeight;
                $newWidth = (int) ($height / $imgHeight * $imgWidth);
            }
        }

        if ($ratio == true) {
            $source_aspect_ratio = $imgWidth / $imgHeight;
            $thumbnail_aspect_ratio = $newWidth / $newHeight;
            if ($imgWidth <= $newWidth && $imgHeight <= $newHeight) {
                $newWidth = $imgWidth;
                $newHeight = $imgHeight;
            } elseif ($thumbnail_aspect_ratio > $source_aspect_ratio) {
                $newWidth = (int) ($newHeight * $source_aspect_ratio);
                $newHeight = $newHeight;
            } else {
                $newWidth = $newWidth;
                $newHeight = (int) ($newWidth / $source_aspect_ratio);
            }
        }

        $src_x = 0;
        $src_y = 0;
        $src_w = $imgWidth;
        $src_h = $imgHeight;
    }

    $imgString = file_get_contents($image);

    $imageFromString = imagecreatefromstring($imgString);
    $tmp = imagecreatetruecolor($newWidth, $newHeight);
    imagealphablending($tmp, false);
    imagesavealpha($tmp, true);
    imagecopyresampled(
        $tmp,
        $imageFromString,
        0,
        0,
        $src_x,
        $src_y,
        $newWidth,
        $newHeight,
        $src_w,
        $src_h
    );

    if ($destination !== false) {  // checks if was destination provided
        if(substr( $destination, -1 ) == '/' ) { // check whether prowided destination was folder or file.
            $create = !is_dir($destination) ? @mkdir($destination, $permission, true) : true; // if it was folder, it will crtete it if needed
            if ($create) {
                $path_parts = pathinfo($image);
                $destination = $destination . $path_parts['basename'];
            } else {
                return false; // TODO: throw error/exception
            }
        }

    } else {
        $destination = $image;
    }

    $path_parts = pathinfo($destination);
    $create = !is_dir($path_parts['dirname'].'/') ? @mkdir($path_parts['dirname'].'/', $permission, true) : true;
    if (!$create) {
        return false; // TODO: throw error/exception
    }

    switch ($mimeType) {
        case "jpeg":
        case "jpg":
            $q = 90; // function's default value - if everything else fails, this is used.
            if( false !== $image ) {
                if ((!empty($quality['jpg']['fallback'])) AND ( (int) $quality['jpg']['fallback'] ) AND ( $quality['jpg']['fallback'] > 0 ) AND ( $quality['jpg']['fallback'] <=100 ) ) {
                    $q = $quality['jpg']['fallback'];
                }

                if ((!empty($quality['jpg']['orig'])) AND true ===$quality['jpg']['orig'] ){
                    if (extension_loaded('imagick')){
                        $im = new \Imagick($image);
                        $q = $im->getImageCompressionQuality();
                    }
                }

                if((!empty($quality['jpg']['max'])) AND $quality['jpg']['max'] < $q ){
                    $q = $quality['jpg']['max'];
                }

                if((!empty($quality['jpg']['min'])) AND $quality['jpg']['min'] > $q ){
                    $q = $quality['jpg']['min'];
                }
            }
            return imagejpeg($tmp, $destination, $q );
            break;
        case "png":
            if ((!empty($quality['png'])) AND ( (int) $quality['png'] ) AND ( $quality['png'] >= -1 ) AND ( $quality['png'] <=9 ) ) {
                $q = $quality['png'];
            } else {
                $q = -1; // -1 is zlib's default value which is currently ( 11/2018 ) equal to 6
            }
            return imagepng($tmp, $destination, $q );
            break;
        case "gif":
            return imagegif($tmp, $destination);
            break;
        default:
            throw new \Exception(" Only jpg, jpeg, png and gif files can be resized ");
            break;
    }
}
