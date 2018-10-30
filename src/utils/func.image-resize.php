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

function resize($image, $mimeType, $imgWidth, $imgHeight, $newWidth, $newHeight, $ratio = false, $upsize = true, $cropToSize = false)
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

    switch ($mimeType) {
        case "jpeg":
        case "jpg":
            imagejpeg($tmp, $image, 90);
            break;
        case "png":
            imagepng($tmp, $image, 0);
            break;
        case "gif":
            imagegif($tmp, $image);
            break;
        default:
            throw new \Exception(" Only jpg, jpeg, png and gif files can be resized ");
            break;
    }
}
