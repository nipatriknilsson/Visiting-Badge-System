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

<a href="register.php">New Sign in</a>

<?php
/* Show visitor list */

$retval = mysqli_query ( $link, $sqlqueryall );  

$numberofrows = mysqli_num_rows ( $retval );

if ( $numberofrows > 0 ) {  
    echo 'Number of resistered visitors: ' + $numberofrows;
    echo '<br>';
    echo 'Number of Visitors signed in: ' + $numberofrows;
    echo '<br>';

    echo 'Registered visitors:';
    
    //Table header
    echo '<table>';
    echo '<tr>';
        echo '<th>';
            echo 'ID';
        echo '</th>';
        echo '<th>';
            echo 'Full Name';
        echo '</th>';
        echo '<th>';
            echo 'Action';
        echo '</th>';
    echo '</tr>';

    while ( $row = mysqli_fetch_assoc ( $retval )) {  
        echo '<tr>';
            echo '<th>';
                echo $row [ "id" ];
            echo '</th>';
            echo '<th>';
                echo '<input type="button" value="Enter">';
                echo '<input type="button" value="Leave">';
            echo '</th>';
    } //end of while 

    echo '</table>';
} else {
    echo "No registered visitors!";  
}

mysqli_close ( $link );
?>

</body>
</html>
 