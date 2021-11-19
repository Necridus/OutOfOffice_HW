<?php ob_start();
header("Content-type: text/html; charset=utf-8");  
?>
<!DOCTYPE HTML>
<html>
    <head>
        <meta charset="utf-8">
    </head>
    <body>
        <?php
            session_start();

            if($_SESSION['Loggedin']){     
                session_destroy();         
                header("Location: login.php");
            } 
        ?>
    </body>
</html>