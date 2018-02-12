<?php
/* 
 * Main page of the application. If anybody is registered into the database those are displayed.
 * 
 * Data is shown in a table.
 * 
 * Provides a link to do a new registration.
 */
?>


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

<a href="/">Return to register Page</a>
<br>
<br>

<?php
/* Show visitor list */

if ( $_SERVER [ 'REQUEST_METHOD' ] == 'GET' ) {
    if ( $_GET [ 'pos' ] ) {
        $get_pos = $_GET [ 'pos' ];
    } else {
        $get_pos = '0';
    }

    if ( $_GET [ 'all' ] ) {
        $get_all = $_GET [ 'all' ];
    } else {
        $get_all = '0';
    }

    debug_log ( __FILE__, __LINE__, $get_pos );
    debug_log ( __FILE__, __LINE__, $get_all );
}

if ( $get_all == "0" ) {
    $sqlshown='select * from visitors where ( DATE(NOW()) BETWEEN fromdate AND todate ) AND active=1 LIMIT ' . $get_pos . ', ' . $numbereachpagedisplayed;
    $headline ='Visitors currently in building';
} else {
    $sqlshown='select * from visitors where ( DATE(NOW()) BETWEEN fromdate AND todate ) LIMIT ' . $get_pos . ', ' . $numbereachpagedisplayed;
    $headline ='All registered Visitors';
}

$rettime = mysqli_query ( $link, 'select count(*) from visitors where DATE(NOW()) BETWEEN fromdate AND todate' );  
$arraytime = mysqli_fetch_array ( $rettime );
$numbertime = $arraytime [ 0 ];

$retactive = mysqli_query ( $link, 'select count(*) from visitors where ( DATE(NOW()) BETWEEN fromdate AND todate ) AND active=1' );
$arrayactive = mysqli_fetch_array ( $retactive );
$numberactive = $arrayactive [ 0 ];

if ( $get_all == "0" ) {
    $numbertotal = $numberactive;
} else {
    $numbertotal = $numbertime;
}


echo '<b>';
echo 'Visitors <u>registered</u>: ' . $numbertime;
echo '<br>';
echo 'Visitors <u>signed in</u>: ' . $numberactive;
echo '</b>';

$retshown = mysqli_query ( $link, $sqlshown );
$numbershown = mysqli_num_rows ( $retshown );

if ( $numbershown > 0 ) {  
    echo '<h1>' . $headline . '</h1>';
    
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

        while ( $row = mysqli_fetch_assoc ( $retshown ) ) {  
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

    debug_log ( __FILE__, __LINE__, $get_pos );
    debug_log ( __FILE__, __LINE__, $numbereachpagedisplayed );
    debug_log ( __FILE__, __LINE__, $numbertotal );

    if ( $get_pos + $numbereachpagedisplayed < $numbertotal ) {
        echo '<p><a href="list.php?pos=' . ( $get_pos + $numbereachpagedisplayed ) . '&all=' . $get_all . '">Next</a></p>';
    }

} else {
    echo "No registered visitors!";  
}

mysqli_free_result ( $rettime );
mysqli_free_result ( $retactive );
mysqli_free_result ( $retshown );
mysqli_close ( $link );
?>

</body>
</html>
 
