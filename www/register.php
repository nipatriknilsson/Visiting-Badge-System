<?php
    require_once 'imageconvert.php';
    require_once 'settings.php';
    include_once 'debug.php';
    ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register Arrival</title>

    <link rel="stylesheet" href="jquery-ui/jquery-ui.css">
    <script src="jquery/jquery.min.js"></script>
    <script src="jquery-ui/jquery-ui.js"></script>
  
    <script>
        $( function() {
            $( "#fromdate" ).datepicker( { minDate: "0", dateFormat: "yy-mm-dd" } ).val();
        } );
        $( function() {
            $( "#todate" ).datepicker( { minDate: "0", dateFormat: "yy-mm-dd" } ).val();
        } );

        function updateUI () {
            enabled=true;

            if ( $( '#fullname' ).val () == "" ) {
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
<body>
    <?php
        $displayform = 1;
        $displayrequried = 0;
        $post_fullname = '';
        $post_fromdate = '';
        $post_todate = '';

        if ( $_SERVER [ 'REQUEST_METHOD' ] == 'POST' ) {
            $post_fullname = $_POST [ 'fullname' ];
            $post_fromdate = $_POST [ 'fromdate' ];
            $post_todate = $_POST [ 'todate' ];
            $post_photo = $_FILES [ 'photo' ][ 'name' ];

            debug_log ( __FILE__, __LINE__, $post_fullname );
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

                $targetFileThumb = resizeImage ( $_FILES [ "photo" ] [ "tmp_name" ], $imageFileType, 50, 50 );
                debug_log ( __FILE__, __LINE__, $targetFileThumb );

                $targetFileFull = resizeImage ( $_FILES [ "photo" ] [ "tmp_name" ], $imageFileType, 250, 250 );
                debug_log ( __FILE__, __LINE__, $targetFileFull );

                if ( $targetFileThumb != '' && $targetFileFull != '' ) {
                    debug_log ( __FILE__, __LINE__, "The file " . $_FILES["photo"]["name"] . " has been uploaded." );
                    debug_log ( __FILE__, __LINE__, "The file " . $_FILES["photo"]["tmp_name"] . " has been uploaded." );
                    debug_log ( __FILE__, __LINE__, "The file " . $targetFileThumb . " was uploaded." );
                    debug_log ( __FILE__, __LINE__, "The file " . $targetFileFull . " was uploaded." );

                    echo "Print your card";
                    exit;
                } else {
                    echo "Sorry, there was an error uploading your file.";
                }
            }

            if ( $post_fullname !== "" && $post_fromdate !== "" && $post_todate !== "" ) {
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
        <form method="POST" action="#" enctype="multipart/form-data">
        <fieldset>
            Full Name:
            <br>
            <input type="text" name="fullname" id="fullname" value="<?php echo $post_fullname; ?>">
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
    <?php endif; ?>

</body>
</html>