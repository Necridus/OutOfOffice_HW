<?php
    ob_start();
    header("Content-type: text/html; charset=utf-8");

    session_start();
    if (!$_SESSION['Loggedin'])
    {
        header("Location:Login.php");
        exit;
    }
?>
<!DOCTYPE HTML>
<html>
    <head>
        <meta charset="utf-8">
        <title>Admin - Out Of Office</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
        <link rel="stylesheet" href="OOOstyle.css">
    </head>
    <body class="bodyBackground fontFormat fw-bold">

            <div class="row d-flex justify-content-end fixed-bottom p-0 m-0">
                    <a href=Logout.php class="col-1 text-end btn btn-secondary fw-bold">
                        Kijelentkezés
                    </a>
            </div>
        </div>
    </body>
</html>