<?php
ob_start();
header("Content-type: text/html; charset=utf-8");

session_start();
if (!$_SESSION['Loggedin']) {
    header("Location:Login.php");
    exit;
}
require_once('Connect.php');
?>
<!DOCTYPE HTML>
<html>

<head>
    <meta charset="utf-8">
    <title>OutOfOfficeManager - Out Of Office</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="OOOstyle.css">
    <link rel="shortcut icon" href="images/favicon.ico">
</head>


<body class="bodyBackground fontFormat fw-bold" data-bs-toggle="modal" data-bs-target="#exampleModal">

    </div>

    <div class="row d-flex justify-content-start fixed-top p-0 m-0">
        <a href=OutOfOfficeManager.php class="col-1 text-center btn btn-secondary fw-bold">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-arrow-left" viewBox="0 0 16 16">
                <path fill-rule="evenodd" d="M15 8a.5.5 0 0 0-.5-.5H2.707l3.147-3.146a.5.5 0 1 0-.708-.708l-4 4a.5.5 0 0 0 0 .708l4 4a.5.5 0 0 0 .708-.708L2.707 8.5H14.5A.5.5 0 0 0 15 8z"></path>
            </svg>
            Vissza
        </a>
    </div>

    <div class="d-flex justify-content-center">
        <div class="commonContainer rounded col-10">
            <form action="OutOfOfficeManager.php" method="POST">
                <div class="row m-0 p-0">
                    <table class="col-12 table table-striped table-bordered table-hover text-center align-middle">
                        <tr class="thead-dark fw-bold text-uppercase">
                            <td>
                                Dolgozó
                            </td>
                            <td>
                                Szabadság kezdete
                            </td>
                            <td>
                                Szabadság vége
                            </td>
                            <td>
                                Állapot
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <?php echo ($_POST['Name']); ?>
                            </td>
                            <td>
                                <?php echo ($_POST['StartDate']); ?>
                            </td>
                            <td>
                                <?php echo ($_POST['EndDate']); ?>
                            </td>
                            <td>
                                <?php echo ($_POST['Status']); ?>
                            </td>
                        </tr>
                    </table>
                </div>
                <div class="row mt-2 mb-2 text-center">
                    <label>
                        Állapot változtatása a következőre:
                        <select class="custom-select p-0" name="selectedStatus">
                            <option value="Függőben">Függőben</option>
                            <option value="Elutasítva">Elutasítva</option>
                            <option value="Elfogadva">Elfogadva</option>
                        </select>
                    </label>
                </div>

                <input type="hidden" name="ID" value="<?php echo ($_POST['ID']) ?>">
                <input type="hidden" name="UserID" value="<?php echo ($_POST['UserID']) ?>">
                <input type="hidden" name="StartDate" value="<?php echo ($_POST['StartDate']) ?>">
                <input type="hidden" name="EndDate" value="<?php echo ($_POST['EndDate']) ?>">
                <input type="hidden" name="Status" value="<?php echo ($_POST['Status']) ?>">

                <div class="modal-footer">
                    <input type="submit" class="btn btn-primary" name="submitChange" value="Mentés">
                </div>
        </div>
    </div>

    <div class="row d-flex justify-content-end fixed-bottom p-0 m-0">
        <a href=Logout.php class="col-1 text-end btn btn-secondary fw-bold">
            Kijelentkezés
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-box-arrow-right" viewBox="0 0 16 16">
                <path fill-rule="evenodd" d="M10 12.5a.5.5 0 0 1-.5.5h-8a.5.5 0 0 1-.5-.5v-9a.5.5 0 0 1 .5-.5h8a.5.5 0 0 1 .5.5v2a.5.5 0 0 0 1 0v-2A1.5 1.5 0 0 0 9.5 2h-8A1.5 1.5 0 0 0 0 3.5v9A1.5 1.5 0 0 0 1.5 14h8a1.5 1.5 0 0 0 1.5-1.5v-2a.5.5 0 0 0-1 0v2z" />
                <path fill-rule="evenodd" d="M15.854 8.354a.5.5 0 0 0 0-.708l-3-3a.5.5 0 0 0-.708.708L14.293 7.5H5.5a.5.5 0 0 0 0 1h8.793l-2.147 2.146a.5.5 0 0 0 .708.708l3-3z" />
            </svg>
        </a>
    </div>
</body>

</html>