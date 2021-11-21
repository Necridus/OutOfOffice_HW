<?php  
header("Content-type: text/html; charset=utf-8");  
?> 
<!DOCTYPE HTML>
<html>
<head>
<meta charset="utf-8">
</head>
    <body>
    <h1>
    <?php
        $dbName = 'blan_DF9YEV';
        $connection =  mysqli_connect("127.0.0.1", "blan_DF9YEV", "8hu5NlJh");

        if ( ! $connection )
            die( "Nem lehet csatlakozni a MySQL kiszolgálóhoz! " . mysqli_error() );

        $connection->set_charset("utf8");

        mysqli_select_db($connection, $dbName) 
            or die ( "Nem lehet megnyitni a következő adatbázist: $dbName".mysqli_error());
            
    ?>
    </h1>
</body>
</html>
