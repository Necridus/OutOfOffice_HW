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
        <div class="commonContainer rounded col-10">
            <h1 class="text-center fw-bold mb-5">Új felhasználó hozzáadása</h1>
            <form name="MyForm" method="post" onsubmit="return validateForm()" action="<?php print $_SERVER['PHP_SELF'] ?>" class="d-flex justify-content-evenly">
                <p>Azonosító: </p><input type="number" name="az" placeholder="Azonosító"></p>
                <p>Email cím: <input type="text" name="emailcim" placeholder="Email cím"></p>
                <p>Felhasználónév: <input type="text" name="username" placeholder="Felhasználónév"></p>
                <p>Jelszó <input type="text" name="dolgozo" placeholder="Jelszó"></p>
                <p>Dolgozó neve: <input type="text" name="dolgozo" placeholder="Név"></p>
                <p>Jelszó: <input type="password" name="password" placeholder="Jelszó">
                <p>Pozíció: <select class="custom-select p-0" name="job">
                    <option value="1">Csoportvezető</option>
                    <option value="2">Scrum Master</option>
                    <option value="3">Fejlesztő</option>
                    <option value="4">Product Owner</option>
                    <option value="5">Tesztelő</option>
                    <option value="6">Business Analyst</option>
                </select>
            </p>

                <input type="submit" value="Hozzáadás"></p>
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