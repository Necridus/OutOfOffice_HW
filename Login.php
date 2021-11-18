<?php
    header("Content-type: text/html; charset=utf-8");
?>
<!DOCTYPE HTML>
<html>
    <head>
        <meta charset="utf-8">
        <title>Login - Out Of Office</title>
        
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
        <link rel="stylesheet" href="OOOstyle.css">
    </head>
    <body class="text-center bodyBackground fontFormat">
        
        <div class="loginPageContainer rounded">

            <div class="row">
                <div class="col-12 fw-bold text-uppercase">
                <h1>
                    Jelentkezzen be a folytatáshoz!
                </h1>       
                </div>
            </div>
            <div class="row">
                <div class="col-12">
                    <form action="CheckLogin.php" method="POST">
                        <table class="text-center d-flex justify-content-center">
                            <tr class="m-5">
                                <td class="fw-bold text-uppercase text-end">
                                    Felhasználónév:
                                </td>
                                <td>
                                    <input type="text" name="username">
                                </td>
                            </tr>
                            <tr class="m-2">
                                <td class="fw-bold text-uppercase text-end">
                                    Jelszó:
                                </td>
                                <td>
                                    <input type="password" name="password">
                                </td>
                            </tr>
                        </table>
                        <input type="submit" class="btn btn-primary m-2 mt-5 text-uppercase" value="Bejelentkezés">
                    </form>     
                </div>
            </div>

        </div>

    </body>
</html>
