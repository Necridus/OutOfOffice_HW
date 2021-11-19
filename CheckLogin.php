<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<link rel="stylesheet" type="text/css" href="style.css">
		<title>Login</title>
	</head>
	<body>
		<?php
			if(isset($_POST['submit']))
			{
				$username = mysqli_real_escape_string($connection, $_POST['username']);
				$password = sha1(mysqli_real_escape_string($connection, $_POST['password']));

                $queryUser = "SELECT * FROM Users WHERE username='$username' AND password='$password' LIMIT 1";
				$queryUser = mysqli_query($connection, $queryUser);
                $userRecord = mysqli_fetch_assoc($queryUser);

				if (mysqli_num_rows($queryUser) == 1)
				{
					session_start();
					$_SESSION['Username']=$username;
					$_SESSION['Loggedin']=true;

                    if ($userRecord['IsAdmin'] == true)
                    {
                        $_SESSION['IsAdmin'] = true;
                        header("location:AdminMenu.php");
                    }
                    else
                    {
                        $_SESSION['IsAdmin'] = false;
                        echo 'sima user';
                        // header("location: KELL A FÁJL NEVE .php");
                    }
				}
				else
				{
					header("Location:Login.php?nomatch=true");	
					exit;
				}
			}
			else
			{
				header("location:Login.php");
				exit;
			}
		?>
	</body>
</html>