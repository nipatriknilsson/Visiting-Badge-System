<?php
/* 
 * List data requested. Data is shown in a table.
 * 
 * Enables the visitor to leave the building.
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

    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="/bootstrap/bootstrap.min.css" crossorigin="anonymous">
</head>
<body class="container">
<?php

$link = mysqli_connect ( $servername, $username, $password, $database );

/* check connection */
if ( ! $link ) {
    debug_log ( __FILE__, __LINE__, mysqli_connect_error () );
    printf("Connect failed: %s\n", mysqli_connect_error () );
    die ();
}

?>

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

    if ( $_GET [ 'filter' ] != "" ) {
        $get_filter = strval ( $_GET [ 'filter' ] );
    } else {
        $get_filter = '';
    }

    debug_log ( __FILE__, __LINE__, $get_filter );
    debug_log ( __FILE__, __LINE__, $get_pos );
    debug_log ( __FILE__, __LINE__, $get_all );
}

if ( $get_all == "0" ) {
    $sqlshown='select * from visitors where ( DATE(NOW()) BETWEEN fromdate AND todate ) AND LOWER(fullname) LIKE LOWER(\'%' . $get_filter . '%\') AND active=1 LIMIT ' . $get_pos . ', ' . $numbereachpagedisplayed;

    $headline ='Visitors currently in building';
} else {
    $sqlshown='select * from visitors where ( DATE(NOW()) BETWEEN fromdate AND todate ) AND LOWER(fullname) LIKE LOWER(\'%' . $get_filter . '%\') LIMIT ' . $get_pos . ', ' . $numbereachpagedisplayed;

    $headline ='All registered Visitors';
}

$rettime = mysqli_query ( $link, 'select count(*) from visitors where DATE(NOW()) BETWEEN fromdate AND todate' );  
$arraytime = mysqli_fetch_array ( $rettime );
$numbertime = $arraytime [ 0 ];
mysqli_free_result ( $rettime );

$retactive = mysqli_query ( $link, 'select count(*) from visitors where ( DATE(NOW()) BETWEEN fromdate AND todate ) AND active=1' );
$arrayactive = mysqli_fetch_array ( $retactive );
$numberactive = $arrayactive [ 0 ];
mysqli_free_result ( $retactive );

if ( $get_filter == "" ) {
    if ( $get_all == "0" ) {
        $numbertotal = $numberactive;
    } else {
        $numbertotal = $numbertime;
    }
} else {
    if ( $get_all == "0" ) {
        $retfilter = mysqli_query ( $link, 'select count(*) from visitors where ( DATE(NOW()) BETWEEN fromdate AND todate ) AND LOWER(fullname) LIKE LOWER(\'%' . $get_filter . '%\')' );
    } else {
        $retfilter = mysqli_query ( $link, 'select count(*) from visitors where ( DATE(NOW()) BETWEEN fromdate AND todate ) AND LOWER(fullname) LIKE LOWER(\'%' . $get_filter . '%\') AND active=1' );
    }

    $arrayfilter = mysqli_fetch_array ( $retfilter );
    $numbertotal = $arrayfilter [ 0 ];

    mysqli_free_result ( $retfilter );
}

echo '<div class="row">';
    echo '<div class="col-lg-4">';
        echo '<a href="/">Return to register Page</a>';
    echo '</div>';
    
    echo '<div class="col-lg-4 text-center">';
        echo '<b>Visitors <u>registered</u>: ' . $numbertime . '</b>';
    echo '</div>';

    echo '<div class="col-lg-4 text-right">';
        echo '<b>Visitors <u>signed in</u>: ' . $numberactive . '</b>';
    echo '</div>';
echo '</div>';

echo '<br>';

$retshown = mysqli_query ( $link, $sqlshown );
$numbershown = mysqli_num_rows ( $retshown );

echo '<div class="row">';
    echo '<div class="col-lg-4">';
        echo '<h3>' . $headline . '</h1>';
    echo '</div>';

    echo '<div class="col-lg-offset-1">';
        echo '<form action="#">';
        echo '<input type="text" name="filter" value="' . $get_filter . '">';
        echo '<input type="text" hidden name="pos" value="0">';
        echo '<input type="text" hidden name="all" value="' . $get_all . '">';
        echo '<input type="submit" value="Filter by Full Name">';
        echo '</form>';
    echo '</div>';
echo '</div>';


if ( $numbershown > 0 ) {

//Table header
    echo '<div class="row">';
    echo '<table class="showregistered col-lg-12">';
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
    echo '</div>';

    debug_log ( __FILE__, __LINE__, $get_pos );
    debug_log ( __FILE__, __LINE__, $numbereachpagedisplayed );
    debug_log ( __FILE__, __LINE__, $numbertotal );

    if ( $get_pos + $numbereachpagedisplayed < $numbertotal ) {
        echo '<p><a href="list.php?filter=' . $get_filter . '&pos=' . ( $get_pos + $numbereachpagedisplayed ) . '&all=' . $get_all . '">Next</a></p>';
    }

} else {
    echo "Nothing to display!";  
}

mysqli_free_result ( $retshown );
mysqli_close ( $link );
?>

<script src="/jquery/jquery.min.js"></script>
<script src="/popper/popper.min.js"></script>
<script src="/bootstrap/bootstrap.min.js"></script>

</body>
</html>
 
