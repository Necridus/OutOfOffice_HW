<?php  
header("Content-type: text/html; charset=utf-8");  
?> 
<!DOCTYPE HTML>
<html>
<head>
<meta charset="utf-8">
<title>Connect</title>
</head>
    <body>
    <h1>
    <?php
        $dbName = 'blan_df9yev';
        $connection =  mysqli_connect("127.0.0.1", "blan_DF9YEV", "8hu5NlJh");

        if ( ! $connection )
            die( "Nem lehet csatlakozni a MySQL kiszolgálóhoz! " . mysqli_error() );
        else
            print "Sikerült csatlakozni!<br>";

        $connection->set_charset("utf8");

        mysqli_select_db($connection, $dbName) 
            or die ( "Nem lehet megnyitni a következő adatbázist: $dbName".mysqli_error());
            
        print "Sikeresen kiválasztott adatbázis: " . $dbName . "<br>";

        // print "<pre>";
        // print_r($connection);
        // print "</pre>";
    ?>
    </h1>
</body>
</html>
