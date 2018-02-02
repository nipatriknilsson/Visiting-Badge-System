<?php

require_once 'settings.php';
require_once 'debug.php';

function resizeImage ( $imageOldPath, $extension,  $maxWidth, $maxHeight ) {
    $result = '';
    debug_log (  __FILE__, __LINE__, $imageOldPath );
    debug_log (  __FILE__, __LINE__, $extension );

    if ( $imageOldPath != '' && $extension != '' ) {
        if ( in_array ( $extension, array ( 'jpg', 'jpeg', 'png', 'gif' ) ) ) {
            switch ( $extension ) {
                case 'jpg':
                    $imageOldData = imagecreatefromjpeg ( $imageOldPath );
                    break;

                case 'jpeg':
                    $imageOldData = imagecreatefromjpeg ( $imageOldPath );
                    break;

                case 'png':
                    $imageOldData = imagecreatefrompng ( $imageOldPath );
                    break;

                case 'gif':
                    $imageOldData = imagecreatefromgif ( $imageOldPath );
                    break;

                default:
                    $imageOldData = imagecreatefromjpeg ( $imageOldPath );
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

            $imageNewPathFilename = $GLOBALS [ 'uploadPathStore' ] . hash ( 'sha256', $imageNewDataString ) . uniqid () . '.jpg';

            if ( file_put_contents ( $imageNewPathFilename, $imageNewDataString ) !== false )
            {
                $result = $imageNewPathFilename;
            }

            imagedestroy ( $imageNewData );
            imagedestroy ( $imageOldData );
        }
    }

    return $result;
}

