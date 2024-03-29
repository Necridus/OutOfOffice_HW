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
    <title>UserManager - Out Of Office</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="OOOstyle.css">
    <link rel="shortcut icon" href="images/favicon.ico">
</head>



<body class="bodyBackground fontFormat">

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
            <?php
            function deleteuser()
            {
                //A függvény nem látja a globális változókat ($connection)

                include('Connect.php');

                $id = $_GET['UserToBeDeleted'];
                $deleteRequestsQuery = "UPDATE Requests SET Validto = NOW() WHERE USERID=$id";

                if ($connection->query($deleteRequestsQuery) === TRUE) {
                    $deleteUserQuery = "UPDATE Users SET ValidTo = NOW() WHERE ID=$id";
                    if ($connection->query($deleteUserQuery) === TRUE) {
                        echo "<script>alert('A felhasználó az adatbázisból eltávolításra került!')</script>";
                    } else {
                        echo "<script>alert('Nem sikerült eltávolítani a felhasználót: ". $connection->error."!')</script>";
                    }
                } else {
                    echo "<script>alert('Nem sikerült eltávolítani a felhasználót: ". $connection->error."!')</script>";
                }
            }
            if (isset($_GET['UserToBeDeleted'])) 
            {
                
                deleteuser();
              
            }

            $queryAllUsers = mysqli_query($connection, "SELECT Users.ID, Users.Name, Users.Password, Users.UserName, Users.EmailAddress, JobTitles.JobTitle, Users.ValidFrom, Users.ValidTo, Users.IsAdmin FROM Users 
            JOIN JobTitles ON Users.JobTitle_FK = JobTitles.ID WHERE Users.ValidTo IS NULL ORDER BY Users.Name");

            if (mysqli_num_rows($queryAllUsers) != 0) {

            ?>
                <h1 class="text-center text-uppercase fw-bold">Felhasználók</h1>
                <table class="col-12 table table-striped table-bordered table-hover text-center align-middle">
                    <tr><td colspan='8'> <a href='CreateUser.php' class="btn btn-info btn-block">Új felhasználó hozzáadása</a></td></tr>
                    <tr class="thead-dark fw-bold text-uppercase">
                        <td>Dolgozó</td>
                        <td>Felhasználónév</td>
                        <td>Email</td>
                        <td>Pozíció</td>
                        <td>Admin</td>
                        <td>Érvényesség kezdete</td>
                        <td colspan="2">
                            Műveletek
                        </td>
                    </tr>
                    <?php
                    $rowNumber = 1;
                    while ($row = mysqli_fetch_assoc($queryAllUsers)) {
                    ?>
                        <tr>
                            <td>
                                <?php echo ($row['Name']); ?>
                            </td>
                            <td>
                                <?php echo ($row['UserName']); ?>
                            </td>
                            <td>
                                <?php echo ($row['EmailAddress']); ?>
                            </td>
                            <td>
                                <?php echo ($row['JobTitle']); ?>
                            </td>
                            <td>
                                <?php
                                if ($row['IsAdmin'] == 1) {
                                    echo 'Igen';
                                } else {
                                    echo 'Nem';
                                };
                                ?>
                            </td>
                            <td>
                                <?php echo ($row['ValidFrom']); ?>
                            </td>

                            <td>
                                <form action="ModifyUser.php" method="POST">
                                    <input type="hidden" name="ID" value="<?php echo ($row['ID']); ?>">
                                    <input type="hidden" name="EmailAddress" value="<?php echo ($row['EmailAddress']); ?>">
                                    <input type="hidden" name="UserName" value="<?php echo ($row['UserName']); ?>">
                                    <input type="hidden" name="Password" value="<?php echo ($row['Password']); ?>">
                                    <input type="hidden" name="Name" value="<?php echo ($row['Name']); ?>">
                                    <input type="hidden" name="JobTitle_FK" value="<?php echo ($row['JobTitle']); ?>">
                                    <input type="hidden" name="IsAdmin" value="<?php echo ($row['IsAdmin']); ?>">
                                    <input type="hidden" name="ValidFrom" value="<?php echo ($row['ValidFrom']); ?>">
                                    <input type="hidden" name="ValidTo" value="<?php echo ($row['ValidTo']); ?>">

                                    <input type="submit" class="btn btn-outline-dark" value="Módosítás">

                                </form>
                            </td>
                            <td>
                                <form method="post" action='UserManager.php?UserToBeDeleted=<?php echo ($row['ID']); ?>'>
                                <input type="submit" class="btn btn-outline-danger" onClick="return confirm('Biztosan törli?')" <?php if($row['UserName'] == $_SESSION['Username']) echo ("disabled"); ?> value="Törlés">                                </form>
                                </form>
                            </td>
                        </tr>
                    <?php

                        $rowNumber = $rowNumber + 1;
                    }
                } else {
                    ?>

                    <h4>Nincs elérhető felhasználó az adatbázisban</h4>

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