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
    <script src="http://code.jquery.com/jquery-1.9.1.min.js"></script>
    <meta charset="utf-8">
    <title>Create User - Out Of Office</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="OOOstyle.css">
    <link rel="shortcut icon" href="images/favicon.ico">
</head>
<script>
    function validateForm() {
        let email = document.forms["NewForm"]["ujemail"].value;
        let felhasznalo = document.forms["NewForm"]["ujusername"].value;
        let jelszo = document.forms["NewForm"]["ujjelszo"].value;
        let dolgozo = document.forms["NewForm"]["ujdolgozo"].value;
        
        if (email == "" || felhasznalo == "" || jelszo == "" || dolgozo == "") {
            alert("Minden adatot meg kell adni!");
            return false;
        } else 
        {
            return true;
        }
    }
</script>

<?php
   
   function UserExists($selectedname)
   {
       include('Connect.php');
       if (isset($_POST['AddUser'])) {
           
           $queryUsers = mysqli_query($connection, "SELECT UserName FROM Users WHERE ValidTo IS NULL");
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

$queryMaxIDResult = mysqli_query($connection, "SELECT ID FROM Users WHERE ValidTo IS NULL ORDER BY Users.ID DESC LIMIT 1");

$maxIDRow = mysqli_fetch_assoc($queryMaxIDResult);

$newID = $maxIDRow['ID'] + 1;
$email = $_POST['ujemail'];
$username = $_POST['ujusername'];
$password = sha1($_POST['ujjelszo']);
$dolgozo = $_POST['ujdolgozo'];
$jobtitle = $_POST['ujjobtitle'];
$isadmin = $_POST['ujadmin'];

$validFrom = date("Y-m-d H:i:s");

if (!empty($_POST['AddUser'])) 
{

    if (!UserExists($username)) 
    {
            $insertIntoUsers = "INSERT INTO Users (ID, EmailAddress, UserName, Password, Name, JobTitle_FK, IsAdmin, ValidFrom, ValidTo) 
                                            VALUES ('$newID','$email','$username','$password','$dolgozo', '$jobtitle', '$admin', '$validFrom', NULL)";
            $result = mysqli_query($connection, $insertIntoUsers);
            if ($result) 
            {
                echo "<script>alert('Felhasználó sikeresen hozzáadva!')</script>";
                header("Location:UserManager.php");
            } else 
            {
                echo "<script>alert('Nem sikerült a felhasználót hozzáadni!')</script>";
                die(mysqli_error($connection));
            }
    }
    
    else 
    {
            echo "<script>alert('Ez a felhasználónév már létezik, válasszon másikat!')</script>";
    }
}
     
?>

<body class="bodyBackground fontFormat fw-bold">

    <div class="row d-flex justify-content-start fixed-top p-0 m-0">
        <a href=UserManager.php class="col-1 text-center btn btn-secondary fw-bold">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-arrow-left" viewBox="0 0 16 16">
                <path fill-rule="evenodd" d="M15 8a.5.5 0 0 0-.5-.5H2.707l3.147-3.146a.5.5 0 1 0-.708-.708l-4 4a.5.5 0 0 0 0 .708l4 4a.5.5 0 0 0 .708-.708L2.707 8.5H14.5A.5.5 0 0 0 15 8z"></path>
            </svg>
            Vissza
        </a>
    </div>

    

<div class="d-flex justify-content-center">
        <div class="commonContainer rounded col-8">
            <div class="row m-0 p-0">
            <h1 class="text-center fw-bold mb-5">Új felhasználó hozzáadása</h1>
            <form name="NewForm" method="post" onsubmit="return validateForm()" action="<?php print $_SERVER['PHP_SELF'] ?>" class="d-flex justify-content-evenly">
            <table class="col-8 table table-striped table-bordered table-hover text-center align-middle">
            <tr>
            <td>Email cím: </td>
            <td><input type="email" name="ujemail" placeholder="Email cím"></td>
            </tr>
            <tr>
            <td>Felhasználónév: </td>
            <td><input type="text" name="ujusername" placeholder="Felhasználónév"></td>
            </tr>
            <tr>
            <td>Jelszó: </td>
            <td><input type="password" name="ujjelszo" placeholder="Jelszó"></td>
            </tr>           
            <tr>
            <td>Dolgozó neve: </td>
            <td><input type="text" name="ujdolgozo" placeholder="Dolgozó neve"></td>
            
            </tr>
            <tr>
            <td>Pozíció: </td>
            <td>
                <select class="custom-select p-0" name="ujjobtitle">
                    <option value="1">Csoportvezető</option>
                    <option value="2">Scrum Master</option>
                    <option value="3">Fejlesztő</option>
                    <option value="4">Product Owner</option>
                    <option value="5">Tesztelő</option>
                    <option value="6">Business Analyst</option>
                </select>
            </td>        
            </tr>
            <tr>
            <td>Admin jogosultság: </td>
            <td>
                <select class="custom-select p-0" name="ujadmin">
                    <option value="1">Igen</option>
                    <option value="0">Nem</option>
                </select>
            </td>
            
            </tr>
            </table>
            </div>  
            <div class="modal-footer">
            <input type="submit" class="btn btn-primary" name="AddUser" value="Hozzáadás"> 
            </div>
            </form>
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