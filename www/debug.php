<?php

require_once 'settings.php';

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

function debug_log ( $f, $l, $m ) {
    echo '<script> console.log("'. htmlentities ( $f . ' (' . $l . '): ' . $m ) . '"); </script>';
};

