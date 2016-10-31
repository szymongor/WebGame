<?php

	session_start();
	
	if ((isset($_SESSION['logged_on'])) && ($_SESSION['logged_on']==true))
	{
		header('Location: game.php');
		exit();
	}

?>

<!DOCTYPE HTML>
<html lang="pl">
<head>
	<meta charset="utf-8" />
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
	<title>Sign in</title>
	<link  rel="stylesheet" href="style.css" type="text/css"/>
</head>

<body>
	
	<div id="container">
		<form action="zaloguj.php" method="post">
		
			<input type="text" name="login" placeholder="Login" /> 
			<br /> <br />
			<input type="password" name="password"  placeholder="Password"/>
			<br /><br />
			<input type="submit" value="Sign in" />
	
			<div id="Register">
			<button type="button" onclick="location.href='register.php'">Register</button>
			</div>
			<div id="Forgot">
			<button type="button">Forgot password?</button>
			</div>
			
			<div id="LoginError">
				<?php
					if(isset($_SESSION['error'])) 
					{
						echo "<br />".$_SESSION['error'];
						unset($_SESSION['error']);
					}
				?>
			</div>
		
		</form>
	</div>
	

</body>
</html>