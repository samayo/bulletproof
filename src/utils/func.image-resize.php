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

function resize($image, $mimeType, $imgWidth, $imgHeight, $newWidth, $newHeight, $ratio = false, $upsize = true)
{

    // First, calculate the height.
    $height = intval($newWidth / $imgWidth * $imgHeight);

    // If the height is too large, set it to the maximum height and calculate the width.
    if ($height > $newHeight) {
        $height = $newHeight;
        $newWidth = intval($height / $imgHeight * $imgWidth);
    }

    // If we don't allow upsizing check if the new width or height are too big.
    if (!$upsize) {
        // If the given width is larger than the image width, then resize it
        if ($newWidth > $imgWidth) {
            $newWidth = $imgWidth;
            $height = intval($newWidth / $imgWidth * $imgHeight);
        }

        // If the given height is larger then the image height, then resize it.
        if ($height > $imgHeight) {
            $height = $imgHeight;
            $newWidth = intval($height / $imgHeight * $imgWidth);
        }
    }

    if ($ratio == true) {
        $source_aspect_ratio = $imgWidth / $imgHeight;
        $thumbnail_aspect_ratio = $newWidth / $newHeight;
        if ($imgWidth <= $newWidth && $imgHeight <= $newHeight) {
            $newWidth = $imgWidth;
            $newHeight = $imgHeight;
        } elseif ($thumbnail_aspect_ratio > $source_aspect_ratio) {
            $newWidth = (int)($newHeight * $source_aspect_ratio);
            $newHeight = $newHeight;
        } else {
            $newWidth = $newWidth;
            $newHeight = (int)($newWidth / $source_aspect_ratio);
        }
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
        0,
        0,
        $newWidth,
        $newHeight,
        $imgWidth,
        $imgHeight
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
