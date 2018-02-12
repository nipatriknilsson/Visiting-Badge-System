<?php

/* 
 * debug_log displays a message by calling Javascript console.log, but from inside of PHP. Call it as follows:
 * 
 * debug_log ( __FILE__, __LINE__, 'message' );
 */

if ( $GLOBALS [ 'debug' ] ) {
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
}

function debug_log ( $f, $l, $m ) {
    if ( $GLOBALS [ 'debug' ] ) {
        echo '<script> console.log("'. htmlentities ( $f . ' (' . $l . '): ' . $m ) . '"); </script>';
    }
};
