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
    <script src="http://code.jquery.com/jquery-1.9.1.min.js"></script>
    <meta charset="utf-8">
    <title>Create Request - Out Of Office</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="OOOstyle.css">
</head>
<script>
    function validateForm() {
        let startDate = document.forms["myForm"]["startDate"].value;
        let endDate = document.forms["myForm"]["endDate"].value;
        var today = new Date().toISOString().substr(0, 10);
        if (startDate == "" || endDate == "") {
            alert("A dátumokat ki kell tölteni");
            return false;
        } else {
            if (startDate > endDate) {
                alert("A kező dátumnak korábban kell lennie mint a befejező dátumnak");
                return false;
            }
            if (startDate < today || endDate < today){
                alert("A kiválasztott dátumoknak később kell lenniük mint a jelenlegi dátum");
                return false;
            }
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
            <h1 class="text-center fw-bold mb-5">Új szabadság létrehozása</h1>
            <form name="myForm" method="post" onsubmit="return validateForm()" action="<?php print $_SERVER['PHP_SELF'] ?>" class="d-flex justify-content-evenly">
                <p>Szabadság kezdete: <input type="date" name="startDate"></p>
                <p>Szabadság vége: <input type="date" name="endDate"></p>
                <p><input type="submit" value="Küld"></p>
            </form>

            <?php
            function getAdmins()
            {
                include('Connect.php');
                return mysqli_query($connection, "SELECT Users.EmailAddress, Users.UserName FROM Users WHERE isAdmin = 1 AND ValidTo IS NULL");
            }

            function sendMailAboutNewRequest($adminEmails, $startDate, $endDate, $username)
            {
                while ($row = mysqli_fetch_assoc($adminEmails)) {
                    $sendMail = mail($row['EmailAddress'], "New Request has been created", "Dear " . $row['UserName'] . "! \n\n New Out Of Office Request has been created by " . $username . " from " . $startDate . " to " . $endDate . "! \n Log in to http://portalbce.hu/DF9YEV/OutOfOffice_HW/Login.php to see the Request. \n\n This is an automated message sent by the server, please don't respond to this.");
                }
                if ($sendMail == true) {
                    echo '<script>alert("Message was sent successfully!")</script>';
                } else {
                    echo '<script>alert("Message could not be sent, there is probably no e-mail address added to this user!")</script>';
                }
            }

            $username = $_SESSION['Username'];

            //Visszaad egy tömböt, amelynek az elemei asszociatív tömbök a szabadságok kezdő és végdátumaival
            function getRequests()
            {
                //A függvény nem látja a globális változókat ($connection, $username)
                include('Connect.php');
                $username = $_SESSION['Username'];
                $queryMyRequests = mysqli_query($connection, "SELECT Requests.StartDate, Requests.EndDate FROM Requests JOIN Users ON Requests.UserID = Users.ID WHERE Users.Username = '" . $username . "' AND Requests.ValidTo IS NULL ORDER BY Requests.ID");
                $requests = [];
                if (mysqli_num_rows($queryMyRequests) > 0) {
                    while ($row = mysqli_fetch_assoc($queryMyRequests)) {
                        array_push($requests, $row);
                    }
                }
                return $requests;
            }
            //TO DO
            //Megvizsgálja, hogy az adott kezdő és befejező dátummal rendelkező szabadság átfedésben van-e egy másikkal
            function hasOverlap($newStartDate, $newEndDate, $requests)
            {
                for ($i = 0; $i < count($requests); $i++) {
                    $start = $requests[$i]['StartDate'];
                    $end = $requests[$i]['EndDate'];
                    if ($newStartDate <= $end && $newEndDate >= $start) {
                        return true;
                    }
                }
                return false;
            }

            if (!empty($_POST)) {
                $startDate = $_POST['startDate'];
                $endDate = $_POST['endDate'];
                $validFrom = date("Y-m-d H:i:s");
                $userID = $connection->query("SELECT Users.ID FROM Users WHERE UserName = '$username' AND ValidTo IS NULL")->fetch_object()->ID;
                if (!hasOverlap($startDate, $endDate, getRequests())) {
                    $result = mysqli_query($connection, "INSERT INTO Requests (UserID, StartDate, EndDate, Status, ValidFrom, ValidTo) VALUES ('$userID','$startDate','$endDate','Függőben','$validFrom', NULL)");
                    if ($result) {
                        echo "<script>alert('Szabadság kérelem hozzáadva')</script>";
                        //sendMailAboutNewRequest(getAdmins(),$startDate,$endDate,$username);
                    } else {
                        echo "<script>alert('Nem sikerült továbbítani a kérést az adatbázis számára')</script>";
                    }
                } else {
                    echo "<script>alert('Ez a szabadság átfedésben van egy másikkal')</script>";
                }
            }
            ?>

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