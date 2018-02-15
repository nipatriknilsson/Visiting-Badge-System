<?php

/* 
 * Display a page to register a new visitor
 * 
 * JQuery is used to display date input and to check if all data is entered. If not the submit button is disabled.
 */

?>

<?php
    require_once 'imageresize.php';
    require_once 'settings.php';
    include_once 'debug.php';
    ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register Visit</title>

    <link rel="stylesheet" href="/jquery-ui/jquery-ui.css">
    <script src="/jquery/jquery.min.js"></script>
    <script src="/jquery-ui/jquery-ui.js"></script>
  
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="/bootstrap/bootstrap.min.css" crossorigin="anonymous">

    <style>
        .myText {
            position: relative;
            z-index: 5;
            color: #000;
            font-size: 100%;
            padding: 20px;
        }

        .myImage {
            position: absolute;
            z-index: -1;
            top: 0;
            bottom: 0;
            left: 0;
            right: 0;
            background: url('/alle.jpg') center center;
            opacity: .1;
            width: 100%;
            height: 100%;
        }
    </style>

    <script>
        $( function() {
            $( "#fromdate" ).datepicker( { minDate: "0", dateFormat: "yy-mm-dd", firstDay: 1 } ).val();
        } );
        $( function() {
            $( "#todate" ).datepicker( { minDate: "0", dateFormat: "yy-mm-dd", firstDay: 1 } ).val();
        } );

        function updateUI () {
            enabled=true;

            if ( $( '#fullname' ).val () == "" ) {
                enabled=false;
            }

            if ( $( '#errand' ).val () == "" ) {
                enabled=false;
            }

            if ( $( '#fromdate' ).val () == "" ) {
                enabled=false;
            }

            if ( $( '#todate' ).val () == "" ) {
                enabled=false;
            }

            if ( $( '#photo' ).val () == "" ) {
                enabled=false;
            }

            $( '#submit' ).prop('disabled', !enabled );
        }

        $( document ).ready ( function () {
            $( '#fullname' ).on ( 'keyup', function (e) {
                updateUI ();
            });
            $( '#phone' ).on ( 'keyup', function (e) {
                updateUI ();
            });
            $( '#errand' ).on ( 'keyup', function (e) {
                updateUI ();
            });
            $( '#fromdate' ).on ( 'change', function (e) {
                updateUI ();
            });
            $( '#todate' ).on ( 'change', function (e) {
                updateUI ();
            });
            $( '#photo' ).on ( 'change', function (e) {
                updateUI ();
            });
        });
    </script>
</head>
<body class="container">
    <div class="row">
        <div class="col-lg-4">
            <h1>Register new visitor</h1>
        </div>

        <div class="col-lg-4">
            <div><a href="list.php?pos=0&all=1&filter=">List of All registered visitors</a></div>
            <div><a href="list.php?pos=0&all=0&filter=">List of signed in visitors</a></div>
        </div>

    </div>

    <?php
        $displayform = 1;
        $displayrequried = 0;
        $post_fullname = '';
        $post_phone = '';
        $post_errand = '';
        $post_fromdate = '';
        $post_todate = '';

        if ( $_SERVER [ 'REQUEST_METHOD' ] == 'POST' ) {
            $post_fullname = $_POST [ 'fullname' ];
            $post_phone = $_POST [ 'phone' ];
            $post_errand = $_POST [ 'errand' ];
            $post_fromdate = $_POST [ 'fromdate' ];
            $post_todate = $_POST [ 'todate' ];
            $post_photo = $_FILES [ 'photo' ][ 'name' ];

            debug_log ( __FILE__, __LINE__, $post_fullname );
            debug_log ( __FILE__, __LINE__, $post_phone );
            debug_log ( __FILE__, __LINE__, $post_errand );
            debug_log ( __FILE__, __LINE__, $post_fromdate );
            debug_log ( __FILE__, __LINE__, $post_todate );
            debug_log ( __FILE__, __LINE__, $post_photo );

            $uploadOk = 1;
            $imageFileType = strtolower ( pathinfo ( $_FILES [ "photo" ] [ "name" ], PATHINFO_EXTENSION ) );
            debug_log ( __FILE__, __LINE__, $imageFileType );

            // Check if image file is a actual image or fake image

            $imageCheck = getimagesize ( $_FILES [ "photo" ][ "tmp_name" ] );
            if ( $imageCheck !== false ) {
                debug_log ( __FILE__, __LINE__, $imageCheck [ "mime" ] );
            } else {
                echo "<h1>File is not an image.</h1>";
                $uploadOk = 0;
            }

            // Check file size
            if ( $_FILES [ "photo" ][ "size" ] > 1000000 ) {
                echo "<h1>Sorry, your image is too large.</h1>";
                $uploadOk = 0;
            }

            // Check if $uploadOk is set to 0 by an error
            if ( $uploadOk == 0 ) {
                debug_log ( __FILE__, __LINE__, 'Not uploaded' );
            } else {
                debug_log ( __FILE__, __LINE__, 'Calling resize' );

                $targetFileThumb = resizeImage ( $_FILES [ "photo" ] [ "tmp_name" ], $imageFileType, $GLOBALS [ 'uploadPathStore' ], 50, 50 );
                debug_log ( __FILE__, __LINE__, $targetFileThumb );

                $targetFileFull = resizeImage ( $_FILES [ "photo" ] [ "tmp_name" ], $imageFileType, $GLOBALS [ 'uploadPathStore' ], 250, 250 );
                debug_log ( __FILE__, __LINE__, $targetFileFull );

                if ( $targetFileThumb != '' && $targetFileFull != '' ) {

                    $link = mysqli_connect ( $servername, $username, $password, $database );

                    /* check connection */
                    if ( ! $link ) {
                        printf ( "Connect failed: %s\n", mysqli_connect_error () );
                        die ();
                    }

                    $sqlinsert = 'INSERT INTO visitors ( ';
                    $sqlinsert .= 'fullname' . ', ';
                    $sqlinsert .= 'phone' . ', ' ;
                    $sqlinsert .= 'errand' . ', ' ;
                    $sqlinsert .= 'fromdate' . ', ';
                    $sqlinsert .= 'todate' . ', ';
                    $sqlinsert .= 'photothumb' . ', ';
                    $sqlinsert .= 'photobadge' . ', ';
                    $sqlinsert .= 'active';
                    $sqlinsert .= '  ) VALUES ( ';

                    $sqlinsert .= '\'' . mysqli_real_escape_string ( $link, $post_fullname ) . '\', ';
                    $sqlinsert .= '\'' . mysqli_real_escape_string ( $link, $post_phone ) . '\', ';
                    $sqlinsert .= '\'' . mysqli_real_escape_string ( $link, $post_errand ) . '\', ';
                    $sqlinsert .= '\'' . mysqli_real_escape_string ( $link, $post_fromdate ) . '\', ';
                    $sqlinsert .= '\'' . mysqli_real_escape_string ( $link, $post_todate ) . '\', ';
                    $sqlinsert .= '\'' . mysqli_real_escape_string ( $link, $targetFileThumb ).  '\', ';
                    $sqlinsert .= '\'' . mysqli_real_escape_string ( $link, $targetFileFull ) . '\', ';
                    $sqlinsert .= ' true )';
                     
                    debug_log ( __FILE__, __LINE__, "SQL insert: " . $sqlinsert );

                    $retval = mysqli_query ( $link, $sqlinsert );

                    if ( $retval === false ) {
                        printf ( "Insert failed: %s\n", mysqli_error ( $link ) );
                        die ();
                    }

                    mysqli_close ( $link );

                    debug_log ( __FILE__, __LINE__, "The file " . $_FILES["photo"]["name"] . " has been uploaded." );
                    debug_log ( __FILE__, __LINE__, "The file " . $_FILES["photo"]["tmp_name"] . " has been uploaded." );
                    debug_log ( __FILE__, __LINE__, "The file " . $targetFileThumb . " was uploaded." );
                    debug_log ( __FILE__, __LINE__, "The file " . $targetFileFull . " was uploaded." );

                    echo "<b>Print Your card</b>";
                    echo "<br>";
                    echo "<br>";

                    echo "<table style=\"min-width:300; max-width=200;min-height:200; max-height:200;\">";
                    echo "<tr>";
                    echo "<th>";
                    echo "<img src=\"/uploads/" .  $targetFileFull . "\">";
                    echo "</th>";
                    echo "<th>";
                    echo "<h2>$post_fullname</h2>";
                    echo "<b>$post_fromdate</b> -- <b>$post_todate</b>";
                    echo "<h2>$post_errand</h2>";
                    echo "</th>";
                    echo "</tr>";
                    echo "</table>";
                    echo "<br>";

                    echo "<a href=\"/\">Return to Welcome Page</a>";

                    exit;
                } else {
                    echo "Sorry, there was an error uploading your file.";
                }
            }

            if ( $post_fullname !== "" && $post_errand !== "" && $post_fromdate !== "" && $post_todate !== "" ) {
                $displayform = 0;
                debug_log ( __FILE__, __LINE__, 'SUBMIT!' );
    
            } else {
                $displayrequried = 1;
            }
        }
    ?>

    <?php if ( $displayrequried == 1 ): ?>
        <h2>All fielded are required</h2>
    <?php endif; ?>

    <?php if ( $displayform == 1 ): ?>
        <div class="row">
            <div class="col-lg-7">
                <div class="myText">
                    <div class="myImage"></div>
                    <form method="POST" action="#" enctype="multipart/form-data">
                        <fieldset>
                            Full Name:
                            <br>
                            <input type="text" name="fullname" id="fullname" value="<?php echo $post_fullname; ?>">
                            <br>
                            <br>
                            Phone:
                            <br>
                            <input type="text" name="phone" id="phone" value="<?php echo $post_phone; ?>">
                            <br>
                            <br>
                            Errand:
                            <br>
                            <input type="text" name="errand" id="errand" value="<?php echo $post_errand; ?>">
                            <br>
                            <br>
                            First Day of Visit:
                            <br>
                            <input type="text" name="fromdate" id="fromdate" value="<?php echo $post_fromdate; ?>">
                            <br>
                            <br>
                            Last Day of Visit:
                            <br>
                            <input type="text" name="todate" id="todate" value="<?php echo $post_todate; ?>">
                            <br>
                            <br>
                            Photo to Upload:
                            <br>
                            <input type="file" name="photo" id="photo" value="<?php echo $post_photo; ?>" accept=".gif,.jpg,.jpeg,.png">
                            <br>
                            <br>
                            <input type="submit" id="submit" value="Submit" disabled="disabled">
                        </fieldset>
                    </form>
                </div>
            </div>
        </div>
    <?php endif; ?>

    <script src="/popper/popper.min.js"></script>
    <script src="/bootstrap/bootstrap.min.js"></script>

</body>
</html>