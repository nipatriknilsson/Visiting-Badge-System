<?php

require_once 'settings.php';
require_once 'debug.php';

/*
Reduces an picture image size keeping ratio.

$sourcePathFilename Full path and file name to the picture image
$extension Extension of the image. It may or may not be given in $sourcePathFilename
$destinationPath Path where the converted image is to be saved.
$maxWidth Max width of the created image.
$maxHeight Max height of the created image.

@return: File name with extension of the created image without path 
*/

function resizeImage ( $sourcePathFilename, $extension, $destinationPath, $maxWidth, $maxHeight ) {
    $result = '';
    debug_log (  __FILE__, __LINE__, $sourcePathFilename );
    debug_log (  __FILE__, __LINE__, $extension );

    if ( $sourcePathFilename != '' && $extension != '' ) {
        if ( in_array ( $extension, array ( 'jpg', 'jpeg', 'png', 'gif' ) ) ) {
            switch ( $extension ) {
                case 'jpg':
                    $imageOldData = imagecreatefromjpeg ( $sourcePathFilename );
                    break;

                case 'jpeg':
                    $imageOldData = imagecreatefromjpeg ( $sourcePathFilename );
                    break;

                case 'png':
                    $imageOldData = imagecreatefrompng ( $sourcePathFilename );
                    break;

                case 'gif':
                    $imageOldData = imagecreatefromgif ( $sourcePathFilename );
                    break;

                default:
                    $imageOldData = imagecreatefromjpeg ( $sourcePathFilename );
            }

            $imageOldWidth = imagesx ( $imageOldData );
            $imageOldHeight = imagesy ( $imageOldData );

            $imageScale = min ( $maxWidth / $imageOldWidth, $maxHeight / $imageOldHeight );

            $imageNewWidth = ceil ( $imageScale * $imageOldWidth );
            $imageNewHeight = ceil ( $imageScale * $imageOldHeight );

            $imageNewData = imagecreatetruecolor ( $imageNewWidth, $imageNewHeight );

            $colorWhite = imagecolorallocate ( $imageNewData, 255, 255, 255 );
            imagefill ( $imageNewData, 0, 0, $colorWhite );

            imagecopyresampled ( $imageNewData, $imageOldData, 0, 0, 0, 0, $imageNewWidth, $imageNewHeight, $imageOldWidth, $imageOldHeight );

            ob_start ();
            imagejpeg ( $imageNewData, NULL, 100 );
            $imageNewDataString = ob_get_clean ();

            $imageNewFilename = hash ( 'sha256', $imageNewDataString ) . uniqid () . '.jpg';

            $imageNewPathFilename = $destinationPath . $imageNewFilename;

            if ( file_put_contents ( $imageNewPathFilename, $imageNewDataString ) !== false )
            {
                $result = $imageNewFilename;
            }

            imagedestroy ( $imageNewData );
            imagedestroy ( $imageOldData );
        }
    }

    return $result;
}

