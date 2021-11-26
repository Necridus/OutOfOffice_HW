<?php
ob_start();
header("Content-type: text/html; charset=utf-8");

session_start();
if (!$_SESSION['Loggedin']) {
    header("Location:Login.php");
    exit;
}
elseif ($_SESSION['IsAdmin']) {
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
        if (startDate == "" || endDate == "") {
            alert("A dátumokat ki kell tölteni");
            return false;
        } else if(startDate > endDate) {
            alert("Start date must be earlier than end date");
            return false;
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
            <form name="myForm" method="post"  onsubmit="return validateForm()" action="<?php print $_SERVER['PHP_SELF']?>" class="d-flex justify-content-evenly">
                <p>Szabadság kezdete: <input type="date" name="startDate"></p>
                <p>Szabadság vége: <input type="date" name="endDate"></p>
                <p><input type="submit" value="Küld"></p>
            </form>

    <?php
        $username = $_SESSION['Username'];
        function getArray() {
            //A függvény nem látja a globális változókat ($connection, $username)
            include('Connect.php');
            $username = $_SESSION['Username'];
            $queryMyRequests = mysqli_query($connection, "SELECT Requests.StartDate, Requests.EndDate FROM Requests JOIN Users ON Requests.UserID = Users.ID WHERE Users.Username = '" . $username . "' AND Requests.ValidTo IS NULL ORDER BY Requests.ID");
            $requests = [];
            if(mysqli_num_rows($queryMyRequests) > 0){  
                while($row = mysqli_fetch_assoc($queryMyRequests)){
                    array_push($requests,$row);
                }
            }
            return $requests;
        }
        //TO DO
        //Megvizsgálja, hogy az adott kezdő és befejező dátummal rendelkező szabadság átfedésben van-e egy másikkal
        function hasOverlap($startDate, $endDate, $requests){
            foreach ($requests as $start => $end) {
                /*print("<pre>");
                print_r($start);
                print_r($end);
                print("</pre>");
                echo "<br>";*/
                if($startDate <= $end && $endDate >= $start){
                    return true;
                }
            }
            return false;
        }
        
        if (!empty($_POST)){
            $startDate = $_POST['startDate'];
            $endDate = $_POST['endDate'];
            $validFrom = date("Y-m-d H:i:s");
            $userID = $connection->query("SELECT Users.ID FROM Users WHERE UserName = '$username'")->fetch_object()->ID;
            $result = mysqli_query($connection, "INSERT INTO Requests (UserID, StartDate, EndDate, Status, ValidFrom, ValidTo) VALUES ('$userID','$startDate','$endDate','Függőben','$validFrom', NULL)");
            if($result)
            {/*
                ?><p><?php
                echo hasOverlap($startDate, $endDate, getArray());
                ?></p><?php*/
            }
            else
            {
                echo "<script>alert('Nem sikerült továbbítani a kérést az adatbázis számára')</script>";
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