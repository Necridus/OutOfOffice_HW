<?php ob_start(); ?>
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
                require_once('Connect.php');
                
				$username = mysqli_real_escape_string($connection, $_POST['username']);
				$password = sha1(mysqli_real_escape_string($connection, $_POST['password']));

				$queryUser = mysqli_query($connection, "SELECT * FROM Users WHERE UserName='$username' AND Password='$password' AND ValidTo IS NULL");
                $userRecord = mysqli_fetch_assoc($queryUser);

				if (mysqli_num_rows($queryUser) == 1)
				{
					session_start();
					$_SESSION['Username']=$username;
					$_SESSION['Loggedin']=true;

                    if ($userRecord['IsAdmin'] == 1)
                    {
                        $_SESSION['IsAdmin'] = true;
                        header("location:MainAdminPage.php");
                    }
                    else
                    {
                        $_SESSION['IsAdmin'] = false;
                        header("location:UserPage.php");
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