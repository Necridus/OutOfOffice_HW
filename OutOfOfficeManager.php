<?php
ob_start();
header("Content-type: text/html; charset=utf-8");

session_start();
if (!$_SESSION['Loggedin']) {
    header("Location:Login.php");
    exit;
} elseif (!$_SESSION['IsAdmin']) {
    header("Location:Login.php?noRights=true");
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


<?php
if (isset($_POST['submitChange'])) {
    $ID = $_POST['ID'];
    $userID = $_POST['UserID'];
    $startDate = $_POST['StartDate'];
    $endDate = $_POST['EndDate'];
    $status = $_POST['Status'];

    $validFrom = date("Y-m-d H:i:s");
    $validTo = date("Y-m-d H:i:s");

    $selectedStatus = $_POST['selectedStatus'];

    $queryRequest = mysqli_query($connection, "SELECT * FROM Requests WHERE ID = '$ID' AND ValidTo IS NULL");
    $request = mysqli_fetch_assoc($queryRequest);

    if (mysqli_num_rows($queryRequest) != 0) {
        if ($request['Status'] != $selectedStatus) {
            $setValidTo = "UPDATE Requests set ValidTo = '$validTo' WHERE ID = '$ID'";

            if (!mysqli_query($connection, $setValidTo)) {
                die(mysqli_error($connection));
            }

            $insertIntoRequests = "INSERT INTO Requests (UserID, StartDate, EndDate, Status, ValidFrom, ValidTo) VALUES ('$userID','$startDate','$endDate','$selectedStatus','$validFrom', NULL)";

            if (!mysqli_query($connection, $insertIntoRequests)) {
                die(mysqli_error($connection));
            }

            $queryUserDetails = mysqli_query($connection, "SELECT * FROM Users WHERE ID = '$userID' AND ValidTo IS NULL");
            $user = mysqli_fetch_assoc($queryUserDetails);

            $sendMail = mail($user['EmailAddress'], "Out Of Office Request with starting Date " . $startDate . " was modified", "Dear " . $user['Name'] . "! \n\n Your Request status is modified to: " . $selectedStatus . "! \n Log in to http://portalbce.hu/DF9YEV/OutOfOffice_HW/Login.php to see your Requests and their current states. \n\n This is an automated message sent by the server, please don't respond to this, contact the administrators instead.");

            if ($sendMail) {
                echo '<script>alert("E-mail sikeresen elküldve!")</script>';
            } else {
                echo '<script>alert("E-mail küldés sikertelen!")</script>';
            }
        }
    }
    unset($_POST['submitChange']);
}
?>
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
        <a href=MainAdminPage.php class="col-1 text-center btn btn-secondary fw-bold">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-arrow-left" viewBox="0 0 16 16">
                <path fill-rule="evenodd" d="M15 8a.5.5 0 0 0-.5-.5H2.707l3.147-3.146a.5.5 0 1 0-.708-.708l-4 4a.5.5 0 0 0 0 .708l4 4a.5.5 0 0 0 .708-.708L2.707 8.5H14.5A.5.5 0 0 0 15 8z"></path>
            </svg>
            Vissza
        </a>
    </div>
    <div class="d-flex justify-content-center">
        <div class="commonContainer rounded col-10">
            <h1 class="text-center text-uppercase fw-bold mb-4">
                Benyújtott szabadságkérések
            </h1>

            <h2 class="text-center fw-bold mt-4 mb-4">
                Jelenlegi/Jövőbeli szabadságkérések
            </h2>
            <?php
            $queryAllRequestsInTheFuture = mysqli_query($connection, "SELECT Users.Name, Requests.ID, Requests.UserID, Requests.StartDate, Requests.EndDate, Requests.Status FROM Requests 
                    JOIN Users ON Requests.UserID = Users.ID WHERE Requests.ValidTo IS NULL AND Requests.EndDate >= NOW() ORDER BY Requests.StartDate");
            
            if (mysqli_num_rows($queryAllRequestsInTheFuture) != 0) {
                ?>
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
                    <td colspan="2">
                        Állapot
                    </td>
                </tr>

                <?php
                $rowNumber = 0;
                while ($row = mysqli_fetch_assoc($queryAllRequestsInTheFuture)) {
                ?>
                    <tr>
                        <td>
                            <?php echo ($row['Name']); ?>
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
                            <form action="ChangeStatus.php" method="POST">
                                <input type="hidden" name="ID" value="<?php echo ($row['ID']); ?>">
                                <input type="hidden" name="Name" value="<?php echo ($row['Name']); ?>">
                                <input type="hidden" name="UserID" value="<?php echo ($row['UserID']); ?>">
                                <input type="hidden" name="StartDate" value="<?php echo ($row['StartDate']); ?>">
                                <input type="hidden" name="EndDate" value="<?php echo ($row['EndDate']); ?>">
                                <input type="hidden" name="Status" value="<?php echo ($row['Status']); ?>">
                                <input type="submit" class="btn btn-outline-dark" value="Módosítás">
                            </form>

                        </td>
                    </tr>

                <?php
                    echo ('<script type="text/javascript"> ChooseConditionalBGColor(' . $rowNumber . '); </script>');
                    $rowNumber = $rowNumber + 1;
                }
                ?>
            </table>
            <?php 
            }
            else {
                
            ?>
                <h4 class="text-center">Nincs közelgő szabadságkérelem!</h4>
                <?php
                }
            ?>
            <h2 class="text-center fw-bold mt-4 mb-4">
                Korábbi szabadságkérések
            </h2>

            <?php
            $queryAllRequestsInThePast = mysqli_query($connection, "SELECT Users.Name, Requests.ID, Requests.UserID, Requests.StartDate, Requests.EndDate, Requests.Status FROM Requests 
                    JOIN Users ON Requests.UserID = Users.ID WHERE Requests.ValidTo IS NULL AND Requests.EndDate < NOW() ORDER BY Requests.StartDate");
            
            if (mysqli_num_rows($queryAllRequestsInThePast) != 0) {
                ?>
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

                <?php
                while ($row = mysqli_fetch_assoc($queryAllRequestsInThePast)) {
                ?>
                    <tr>
                        <td>
                            <?php echo ($row['Name']); ?>
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
                    </tr>

                <?php
                    echo ('<script type="text/javascript"> ChooseConditionalBGColor(' . $rowNumber . '); </script>');
                    $rowNumber = $rowNumber + 1;
                }
                ?>
            </table>
            <?php 
            }
            else {
                ?>
                <h4 class="text-center">Nincs korábbi szabadságkérelem!</h4>
                <?php 
                }
            ?>
        </div>
        <div>

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