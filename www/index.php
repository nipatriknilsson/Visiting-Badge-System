<?php
    require_once 'settings.php';
    include_once 'debug.php';
    ?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <title>Visitor Registration System</title>
    <link rel="stylesheet" type="text/css" media="screen" href="main.css" />
    <style type="text/css">
        table.showregistered {
            font-family: verdana,arial,sans-serif;
            font-size:11px;
            color:#333333;
            border-width: 1px;
            border-color: #666666;
            border-collapse: collapse;
        }
        table.showregistered th {
            border-width: 1px;
            padding: 8px;
            border-style: solid;
            border-color: #666666;
            background-color: #dedede;
        }
        table.showregistered td {
            border-width: 1px;
            padding: 8px;
            border-style: solid;
            border-color: #666666;
            background-color: #ffffff;
        }
    </style>

</head>
<body>
<?php

$link = mysqli_connect ( $servername, $username, $password, $database );

/* check connection */
if ( ! $link ) {
    debug_log ( __FILE__, __LINE__, mysqli_connect_error () );
    printf("Connect failed: %s\n", mysqli_connect_error () );
    die ();
}

?>

<a href="register.php">New Register</a>
<br>
<br>

<?php
/* Show visitor list */

$retval = mysqli_query ( $link, $sqlqueryallcurrent );  
$numberofrows = mysqli_num_rows ( $retval );

$retactive = mysqli_query ( $link, 'select count(*) from visitors where ( DATE(NOW()) BETWEEN fromdate AND todate ) AND active=1' );
$retactiverow = mysqli_fetch_array ( $retactive );
$numberofactive = $retactiverow [ 0 ];


if ( $numberofrows > 0 ) {  
    echo '<b>';
    echo 'Visitors <u>registered</u>: ' . $numberofrows;
    echo '<br>';
    echo 'Visitors <u>signed in</u>: ' . $numberofactive;
    echo '<br>';
    echo '<br>';
    echo '</b>';

    echo 'Registered visitors:';
    
    //Table header
    echo '<table class="showregistered">';
        echo '<tr>';
            echo '<th>';
                echo 'ID';
            echo '</th>';

            echo '<th>';
                echo 'Photo';
            echo '</th>';
            
            echo '<th>';
                echo 'Full Name';
            echo '</th>';
            
            echo '<th>';
                echo 'Phone';
            echo '</th>';
            
            echo '<th>';
                echo 'Errand';
            echo '</th>';
            
            echo '<th>';
                echo 'From Date';
            echo '</th>';
            
            echo '<th>';
                echo 'To Date';
            echo '</th>';

            echo '<th>';
                echo 'Status';
            echo '</th>';
        echo '</tr>';

        while ( $row = mysqli_fetch_assoc ( $retval ) ) {  
            echo '<tr>';
                echo '<td>';
                    echo $row [ "id" ];
                echo '</td>';

                echo '<td>';
                    echo '<img src="/uploads/' . $row [ "photothumb" ] . '">';
                echo '</td>';

                echo '<td>';
                    echo $row [ "fullname" ];
                echo '</td>';

                echo '<td>';
                    echo $row [ "phone" ];
                echo '</td>';

                echo '<td>';
                    echo $row [ "errand" ];
                echo '</td>';

                echo '<td>';
                    echo $row [ "fromdate" ];
                echo '</td>';

                echo '<td>';
                    echo $row [ "todate" ];
                echo '</td>';

                echo '<td>';
                    if ( $row [ "active" ] == 1 ) {
                        echo '<form method="POST" action="/changestatus.php" enctype="multipart/form-data">';
                        echo '    <b style="color:green;">Signed In</b>';
                        echo '    <input type="hidden" name="id" value="' . $row [ "id" ] . '">';
                        echo '    <input type="hidden" name="status" value="0">';
                        echo '    <input type="submit" id="submit" value="Leave">';
                        echo '</form>';
                    } else {
                        echo '<form method="POST" action="/changestatus.php" enctype="multipart/form-data">';
                        echo '    <b style="color:red;">Signed Out</b>';
                        echo '    <input type="hidden" name="id" value="' . $row [ "id" ] . '">';
                        echo '    <input type="hidden" name="status" value="1">';
                        echo '    <input type="submit" id="submit" value="Enter">';
                        echo '</form>';
                    }
                echo '</td>';
        } //end of while 

    echo '</table>';
} else {
    echo "No registered visitors!";  
}

mysqli_free_result ( $retval );
mysqli_close ( $link );
?>

</body>
</html>
 
