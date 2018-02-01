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
        if ( $_SERVER [ 'REQUEST_METHOD' ] == 'POST' ) {
            $post_fullname = $_POST [ 'fullname' ];
            $post_fromdate = $_POST [ 'fromdate' ];
            $post_todate = $_POST [ 'todate' ];
            $post_photo = $_POST [ 'photo' ];

            echo '#';
            echo $post_fullname;
            echo '#';
            echo $post_fromdate;
            echo '#';
            echo $post_todate;
            echo '#';
            echo $post_photo;
            echo '#';

            $uploadOk = 1;
            $imageFileType = strtolower(pathinfo($_FILES["photo"]["name"],PATHINFO_EXTENSION));
            echo $imageFileType;
            // Check if image file is a actual image or fake image
            if(isset($_POST["submit"])) {
                $check = getimagesize($_FILES["photo"]["tmp_name"]);
                if($check !== false) {
                    echo "File is an image - " . $check["mime"] . ".";
                    $uploadOk = 1;
                } else {
                    echo "File is not an image.";
                    $uploadOk = 0;
                }
            }
            // Check file size
            if ($_FILES["photo"]["size"] > 1000000) {
                echo "Sorry, your file is too large.";
                $uploadOk = 0;
            }
            // Allow certain file formats
            if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
            && $imageFileType != "gif" ) {
                echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
                $uploadOk = 0;
            }
            // Check if $uploadOk is set to 0 by an error
            if ($uploadOk == 0) {
                echo "Sorry, your file was not uploaded.";
            // if everything is ok, try to upload file
            } else {
                $storedfilename = hash_file ( 'sha256', $_FILES [ "photo" ] [ "tmp_name" ] ) . uniqid ();
                $target_file = __DIR__ . '/uploads/' . basename ( $storedfilename . '.' . $imageFileType );
                echo $target_file;
                    if ( move_uploaded_file ( $_FILES["photo"]["tmp_name"], $target_file ) ) {
                    echo "The file ". basename( $_FILES["photo"]["name"]). " has been uploaded.";
                    echo "The file ". basename( $_FILES["photo"]["tmp_name"]). " has been uploaded.";
                } else {
                    echo "Sorry, there was an error uploading your file.";
                }
            }

            if ( post_fullname === "" &&  post_fromdate === "" && post_todate === "" ) {
                $displayform = 0;
                echo 'SUBMIT!';
    
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
            Arrival Date:
            <br>
            <input type="text" name="fromdate" id="fromdate" value="<?php echo $post_fromdate; ?>">
            <br>
            <br>
            Departure Date:
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