<?php
    ob_start();
    header("Content-type: text/html; charset=utf-8");

    session_start();
    if (!$_SESSION['Loggedin'])
    {
        header("Location:Login.php");
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
    </head>

    <script>
            function ChooseConditionalBGColor(rowNumber){
               
                var containerName = "StatusTD" + rowNumber;
                var hiddenContainerName = "status" + rowNumber;

                var status = document.getElementById(hiddenContainerName).value;

				if (status == 'Függőben')
				{
                    document.getElementById(containerName).className = "bg-warning";
				}
				else if (status == 'Elfogadva')
                {
                    document.getElementById(containerName).className = "bg-success";
				}
                else if (status == 'Elutasítva')
                {
                    document.getElementById(containerName).className = "bg-danger";
                }
            }
        </script>

    <body class="bodyBackground fontStyle">

        <div class="row d-flex justify-content-start fixed-top p-0 m-0">
                <a href=MainAdminPage.php class="col-1 text-center btn btn-secondary fw-bold">
                    < Vissza
                </a>
        </div>
        <div class="d-flex justify-content-center">
            <div class="commonContainer rounded col-10">
                    <h1 class="text-center text-uppercase fw-bold">
                        Benyújtott szabadságok
                    </h1>

                    <?php
                    $queryRequests = mysqli_query($connection, "SELECT Users.Name, Requests.StartDate, Requests.EndDate, Requests.Status FROM Requests JOIN Users ON Requests.UserID = Users.ID WHERE Requests.ValidTo IS NULL");
					?>

					<table class="col-12 table table-striped table-bordered table-hover text-center">
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
							while($row = mysqli_fetch_assoc($queryRequests))
							{
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
                            <td id="<?php echo ("StatusTD$rowNumber");?>">
								<?php echo ($row['Status']); ?>

                                <input type="hidden" id="<?php echo ('status'.$rowNumber)?>" value="<?php echo ($row['Status']); ?>">
                                
							</td>
                            <td>
								<?php echo "Módosítás"; ?>
							</td>
						</tr>
						
						<?php
                            echo ('<script type="text/javascript"> ChooseConditionalBGColor('.$rowNumber.'); </script>');
                            $rowNumber = $rowNumber + 1;
							}
                        ?>
						</table>
						                    
            </div>
        <div>
        
        <div class="row d-flex justify-content-end fixed-bottom p-0 m-0">
                    <a href=Logout.php class="col-1 text-end btn btn-secondary fw-bold">
                        Kijelentkezés
                    </a>
            </div>
    </body>
</html>
