<?php
ob_start();
header("Content-type: text/html; charset=utf-8");

session_start();
if (!$_SESSION['Loggedin']) {
    header("Location:Login.php");
    exit;
} elseif ($_SESSION['IsAdmin']) {
    header("Location:Login.php?noRights=true");
    exit;
}
require_once('Connect.php');
?>
<!DOCTYPE HTML>
<html>

<head>
    <meta charset="utf-8">
    <title>My Requests - Out Of Office</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="OOOstyle.css">
    <link rel="shortcut icon" href="images/favicon.ico">
</head>

<script>
    function ChooseConditionalBGColor(rowNumber) {

        var containerName = "StatusTD" + rowNumber;
        var hiddenContainerName = "status" + rowNumber;

        var status = document.getElementById(hiddenContainerName).value;

        if (status == 'Függőben') {
            document.getElementById(containerName).className = "bg-warning";
        } else if (status == 'Elfogadva') {
            document.getElementById(containerName).className = "bg-success";
        } else if (status == 'Elutasítva') {
            document.getElementById(containerName).className = "bg-danger";
        }
    }
</script>

<body class="bodyBackground fontFormat fw-bold">

    <div class="row d-flex justify-content-start fixed-top p-0 m-0">
        <a href=MainUserPage.php class="col-1 text-center btn btn-secondary fw-bold">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-arrow-left" viewBox="0 0 16 16">
                <path fill-rule="evenodd" d="M15 8a.5.5 0 0 0-.5-.5H2.707l3.147-3.146a.5.5 0 1 0-.708-.708l-4 4a.5.5 0 0 0 0 .708l4 4a.5.5 0 0 0 .708-.708L2.707 8.5H14.5A.5.5 0 0 0 15 8z"></path>
            </svg>
            Vissza
        </a>
    </div>

    <div class="d-flex justify-content-center">
        <div class="commonContainer rounded col-10">
            <h2 class="text-secondary fw-bold">Hello, <?php echo $_SESSION['Username'] ?>!</h2>
            <h1 class="text-center fw-bold">Jelenlegi/Közelgő szabadságaim</h1>
            <?php

            function deleteRequest()
            {
                //A függvény nem látja a globális változókat ($connection)
                include('Connect.php');
                $id = $_GET['requestToBeDeleted'];
                $deleteQuery = "UPDATE Requests SET ValidTo = NOW() WHERE ID=$id and ValidTo IS NULL";
                if ($connection->query($deleteQuery) === TRUE) {
                    echo "<script>alert('Szabadság kérelem eltávolítva!')</script>";
                } else {
                    echo "<script>alert('Nem sikerült eltávolítani a szabadság kérelmet: <?php echo $connection->error; ?>!')</script>";
                }
            }

            if (isset($_GET['requestToBeDeleted'])) {
                deleteRequest();
            }

            $username = $_SESSION['Username'];
            $queryMyUpcomingRequests = mysqli_query($connection, "SELECT Requests.ID, Requests.UserID, Requests.StartDate, Requests.EndDate, Requests.Status, Requests.ValidFrom FROM Requests JOIN Users ON Requests.UserID = Users.ID WHERE Users.Username = '" . $username . "' AND Requests.EndDate >= NOW() AND Requests.ValidTo IS NULL AND Users.ValidTo IS NULL ORDER BY Requests.ID");
            $queryMyPastRequests = mysqli_query($connection, "SELECT Requests.ID, Requests.UserID, Requests.StartDate, Requests.EndDate, Requests.Status, Requests.ValidFrom FROM Requests JOIN Users ON Requests.UserID = Users.ID WHERE Users.Username = '" . $username . "' AND Requests.EndDate < NOW() AND Requests.ValidTo IS NULL AND Users.ValidTo IS NULL ORDER BY Requests.ID");

            if (mysqli_num_rows($queryMyUpcomingRequests) != 0) {
            ?>
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
                        <td>
                            Műveletek
                        </td>
                    </tr>
                    <?php
                    $rowNumber = 0;

                    while ($row = mysqli_fetch_assoc($queryMyUpcomingRequests)) {
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
                            <td id="<?php echo ("StatusTD$rowNumber"); ?>">
                                <?php echo ($row['Status']); ?>
                                <input type="hidden" id="<?php echo ('status' . $rowNumber) ?>" value="<?php echo ($row['Status']); ?>">
                            </td>
                            <td>
                                <form method="post">
                                    <!--Forrás: https://stackoverflow.com/questions/19323010/execute-php-function-with-onclick-->
                                    <a  onclick="return confirm('Biztosan törölni szeretnéd ezt az elemet?');" class="btn btn-outline-danger" href='ListRequests.php?requestToBeDeleted=<?php echo ($row['ID']); ?>'>Törlés</a>
                                </form>
                            </td>
                        </tr>
                    <?php
                        echo ('<script type="text/javascript"> ChooseConditionalBGColor(' . $rowNumber . '); </script>');
                        $rowNumber = $rowNumber + 1;
                    }
                } else {
                    ?>
                    <h4 class="text-center">Nincs közelgő szabadságkérelmed!</h4>
                <?php
                }
                ?>
                </table>
        </div>
    </div>

    <div class="d-flex justify-content-center">
        <div class="commonContainer rounded col-10">
            <h1 class="text-center fw-bold">Korábbi szabadságaim</h1>
            <?php
            if (mysqli_num_rows($queryMyPastRequests) != 0) {
            ?>

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
                    while ($row = mysqli_fetch_assoc($queryMyPastRequests)) {

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
                } else {
                    ?>
                    <h4 class="text-center">Még nem voltak szabadságkérelmeid!</h4>
                <?php
                }
                ?>
                </table>
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