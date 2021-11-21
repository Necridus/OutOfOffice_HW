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
    <title>Admin - Out Of Office</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="OOOstyle.css">
</head>

<body class="bodyBackground fontFormat fw-bold">

    <div class="d-flex justify-content-center">
        <div class="commonContainer rounded col-10">
            <h2 class="text-secondary fw-bold">Hello, <?php echo $_SESSION['Username'] ?>!</h2>

            <?php
            $username = $_SESSION['Username'];
            $queryMyRequests = mysqli_query($connection, "SELECT Requests.ID, Requests.UserID, Requests.StartDate, Requests.EndDate, Requests.Status, Requests.ValidFrom FROM Requests JOIN Users ON Requests.UserID = Users.ID WHERE Users.Username = '" . $username . "' ORDER BY Requests.ID");
            if (mysqli_num_rows($queryMyRequests) != 0) {
            ?>
                <h1 class="text-center fw-bold">Szabadságaim</h1>

                <table class="col-12 table table-striped table-bordered table-hover text-center align-middle">
                    <tr class="thead-dark fw-bold text-uppercase">
                        <td>
                            Létrehozás dátuma
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
                    <?php
                    while ($row = mysqli_fetch_assoc($queryMyRequests)) {

                    ?>

                        <tr>
                            <td>
                                <?php echo ($row['ValidFrom']); ?>
                            </td>
                            <td>
                                <?php echo ($row['StartDate']); ?>
                            </td>
                            <td>
                                <?php echo ($row['EndDate']); ?>
                            </td>
                            <td>
                                <?php echo ($row['Status']); ?>
                            </td>
                        </tr>
                <?php
                    }
                }
                
                ?>
                </table>
                <?php
                /*
                //TO DO: szabadságlétrehozó oldal létrehozása
                    $startDate = $_POST['startDate'];
                    $endDate = $_POST['endDate'];
                    $validFrom = date("Y-m-d H:i:s");
                    $userID = $connection->query("SELECT Users.ID FROM Users WHERE UserName = '$username'")->fetch_object()->ID;
                    $createTimeOffRequestQuery = "INSERT INTO Requests (UserID, StartDate, EndDate, Status, ValidFrom, ValidTo) VALUES ('$userID','$startDate','$endDate','Függőben','$validFrom', NULL)";
                    */

                ?>
                <h1 class="text-center fw-bold">Új szabadság létrehozása</h1>
                <form method="POST" action="<?php print $_SERVER['PHP_SELF']; ?>">
                    Szabadság kezdete: <input type="date" name="startDate">
                    Szabadság vége: <input type="date" name="endDate">
                    <input type="submit" text="OK">
                </form>




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