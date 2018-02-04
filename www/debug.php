<?php

require_once 'settings.php';

$debug = true;

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
