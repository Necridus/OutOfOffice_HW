<?php
    header("Content-type: text/html; charset=utf-8");
?>
<!DOCTYPE HTML>
<html>
    <head>
        <meta charset="utf-8">
        <title>Login - Out Of Office</title>
        <link rel="stylesheet" href="style.css">
    </head>
    <body class="textCenter bodyBackground">
        
        <div class="container">

            <div class="row">
                <div class="col-12">
                <h1>
                    Jelentkezzen be a folytatáshoz!
                </h1>       
                </div>
            </div>
            <div class="row">
                <div class="col-12">
                    <form action="CheckLogin.php" method="POST">
                        <table class="center">
                            <tr class="margin5px">
                                <td class="bold">
                                    Felhasználónév:
                                </td>
                                <td>
                                    <input type="text" name="username">
                                </td>
                            </tr>
                            <tr class="margin5px">
                                <td class="bold">
                                    Jelszó:
                                </td>
                                <td>
                                    <input type="password" name="password">
                                </td>
                            </tr>
                        </table>
                        <input type="submit" class="buttonStyle margin5px" value="Bejelentkezés">
                    </form>     
                </div>
            </div>

        </div>

    </body>
</html>
