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
    <title>Modify User - Out Of Office</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="OOOstyle.css">
    <link rel="shortcut icon" href="images/favicon.ico">
</head>
<script>
    function validateForm() {
        let nev = document.forms["ModForm"]["newname"].value;
        let felhasznalo = document.forms["ModForm"]["newusername"].value;
        let emailcim = document.forms["ModForm"]["newemail"].value;
        if (nev == "" || felhasznalo == "" || emailcim == "") {
            alert("Minden adatot meg kell adni!");
            return false;
        } else {
            return true;
        }
    }
</script>
<?php
function UserExists($selectedname)
{
    include('Connect.php');
    if (isset($_POST['submitUser'])) {
        $currentuserID = $_POST['ID'];
        $queryUsers = mysqli_query($connection, "SELECT UserName FROM Users WHERE ValidTo IS NULL AND ID NOT IN ('" . $currentuserID . "')");
        $users = [];
        if (mysqli_num_rows($queryUsers) > 0) {
            while ($row = mysqli_fetch_assoc($queryUsers)) 
            {
                array_push($users, $row);
            }
        }

        for ($i = 0; $i < count($users); $i++) 
        {
            if ($users[$i]['UserName'] == $selectedname) 
            {
                return true;
            }
        }

        return false;
    } 
    else 
    {
        return false;
    }
}

$username2 = $_POST['newusername'];
$name2 = $_POST['newname'];
$email2 = $_POST['newemail'];
$jobtitle2 = $_POST['newjobtitle'];
$admin2 = $_POST['newisadmin'];

$userID = $_POST['ID'];
$password = $_POST['Password'];

$validTo = date("Y-m-d H:i:s");
$validFrom = date("Y-m-d H:i:s");

if (!empty($_POST['submitUser'])) {

    if (!UserExists($username2)) {
        $queryModify = mysqli_query($connection, "SELECT * FROM Users WHERE ID = '$userID' AND ValidTo IS NULL");
        $modify = mysqli_fetch_assoc($queryModify);
        if (mysqli_num_rows($queryModify) != 0) {
            $setValidTo = "UPDATE Users set ValidTo = '$validTo' WHERE ID = '$userID' AND ValidTo IS NULL";

            if (!mysqli_query($connection, $setValidTo)) {
                die(mysqli_error($connection));
            }

            $insertIntoUsers = "INSERT INTO Users (ID, EmailAddress, UserName, Password, Name, JobTitle_FK, IsAdmin, ValidFrom, ValidTo) 
                                            VALUES ('$userID','$email2','$username2','$password','$name2', '$jobtitle2', '$admin2', '$validFrom', NULL)";
            $result = mysqli_query($connection, $insertIntoUsers);
            if ($result) {
                echo "<script>alert('Felhasználó adatai módosítva!')</script>";
                header("Location:UserManager.php");
            } else {
                echo "<script>alert('Nem sikerült a felhasználó adatait módosítani!')</script>";
                die(mysqli_error($connection));
            }
        } else {
            echo "<script>alert('Ez a felhasználónév már létezik!')</script>";
        }
    }
}

?>

<body class="bodyBackground fontFormat fw-bold" data-bs-toggle="modal" data-bs-target="#exampleModal">

    <div class="row d-flex justify-content-start fixed-top p-0 m-0">
        <a href=UserManager.php class="col-1 text-center btn btn-secondary fw-bold">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-arrow-left" viewBox="0 0 16 16">
                <path fill-rule="evenodd" d="M15 8a.5.5 0 0 0-.5-.5H2.707l3.147-3.146a.5.5 0 1 0-.708-.708l-4 4a.5.5 0 0 0 0 .708l4 4a.5.5 0 0 0 .708-.708L2.707 8.5H14.5A.5.5 0 0 0 15 8z"></path>
            </svg>
            Vissza
        </a>
    </div>

    <div class="d-flex justify-content-center">
        <div class="commonContainer rounded col-10">

            <div class="row m-0 p-0">
                <table class="col-12 table table-striped table-bordered table-hover text-center align-middle">
                    <tr class="thead-dark fw-bold text-uppercase">
                        <td>Dolgozó</td>
                        <td>Felhasználónév</td>
                        <td>Email</td>
                        <td>Pozíció</td>
                        <td>Admin</td>
                    </tr>
                    <?php

                    $selected = $_POST['JobTitle_FK'];

                    $jobTitleQuery = mysqli_query($connection, "SELECT JobTitles.ID FROM JobTitles WHERE JobTitles.JobTitle = '" . $selected . "'");
                    
                    $row = mysqli_fetch_assoc($jobTitleQuery);

                    $selectedvalue = $row['ID'];

                    ?>
                    <tr>
                        <form name="ModForm" method="post" onsubmit="return validateForm()" action="<?php print $_SERVER['PHP_SELF'] ?>" class="d-flex justify-content-evenly">
                            <td>
                                <input type="text" name="newname" placeholder="Név" value="<?php echo $_POST['Name']; ?>">
                            </td>
                            <td>
                                <input type="text" name="newusername" placeholder="Felhasználónév" value="<?php echo $_POST['UserName']; ?>">
                            </td>
                            <td>
                                <input type="email" name="newemail" placeholder="Email" value="<?php echo $_POST['EmailAddress']; ?>">
                            </td>
                            <td>
                                <select class="custom-select p-0" name="newjobtitle">
                                    <option value="1" <?php if ($selectedvalue == '1') echo ' selected '; ?>>Csoportvezető</option>
                                    <option value="2" <?php if ($selectedvalue == '2') echo ' selected '; ?>>Scrum Master</option>
                                    <option value="3" <?php if ($selectedvalue == '3') echo ' selected '; ?>>Fejlesztő</option>
                                    <option value="4" <?php if ($selectedvalue == '4') echo ' selected '; ?>>Product Owner</option>
                                    <option value="5" <?php if ($selectedvalue == '5') echo ' selected '; ?>>Tesztelő</option>
                                    <option value="6" <?php if ($selectedvalue == '6') echo ' selected '; ?>>Business Analyst</option>
 
                                </select>
                            </td>
                            <td>
                                <select class="custom-select p-0" name="newisadmin">
                                    <option value="1" <?php if ($_POST['IsAdmin']) echo ' selected '; ?>>Igen</option>
                                    <option value="0" <?php if (!$_POST['IsAdmin']) echo ' selected '; ?>>Nem</option>
                                </select>
                            </td>
                            <input type="hidden" name="ID" value="<?php echo $_POST['ID']; ?>">
                            <input type="hidden" name="Password" value="<?php echo $_POST['Password']; ?>">
                    </tr>
                </table>
                
                <div class="modal-footer">
                    <input type="submit" class="btn btn-primary" name="submitUser" value="Mentés">
                </div>
            </div>
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