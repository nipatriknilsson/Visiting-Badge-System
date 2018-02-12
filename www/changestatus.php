<?php
/* 
 * Called from "index.php" when user changes status. Status is changed and then "index.php" is loaded with redirection.
 */
?>

<?php
    require_once 'settings.php';
    include_once 'debug.php';
    ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Change status</title>
</head>
<body>
<?php

if ( $_SERVER [ 'REQUEST_METHOD' ] == 'POST' ) {
    $post_id = $_POST [ 'id' ];
    $post_status = $_POST [ 'status' ];

    debug_log ( __FILE__, __LINE__, 'POST' );

    debug_log ( __FILE__, __LINE__, $post_id );
    debug_log ( __FILE__, __LINE__, gettype ( $post_id ) );

    debug_log ( __FILE__, __LINE__, $post_status );
    debug_log ( __FILE__, __LINE__, gettype ( $post_status ) );

    if ( gettype ( $post_id ) == "string" && gettype ( $post_id ) == "string" ) {
        $post_id = intval ( $post_id );
        $post_status = intval ( $post_status );

        debug_log ( __FILE__, __LINE__, 'OK' );

        $link = mysqli_connect ( $servername, $username, $password, $database );

        /* check connection */
        if ( ! $link ) {
            debug_log ( __FILE__, __LINE__, mysqli_connect_error () );
            printf("Connect failed: %s\n", mysqli_connect_error () );
            die ();
        }
    
        debug_log ( __FILE__, __LINE__, 'Connected' );

        $sqlquerysetstatus='update visitors set active=' . $post_status . ' where id=' . $post_id;
    
        debug_log ( __FILE__, __LINE__, $sqlquerysetstatus );

        $retval = mysqli_query ( $link, $sqlquerysetstatus );  
    
        debug_log ( __FILE__, __LINE__, $retval );

        mysqli_close ( $link );
    }
}

header("Location: /");
die();
?>
    
</body>
</html>