<?php

	session_start();
	
	if (!isset($_SESSION['successful_registration']))
	{
		header('Location: gra.php');
		exit();
	}
	else
	{
		unset($_SESSION['successful_registration']);
	}

?>

<!DOCTYPE HTML>
<html lang="pl">
<head>
	<meta charset="utf-8" />
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
	<title>Osadnicy - gra przeglądarkowa</title>
</head>

<body>
	
	Successful registration!
	<a href="index.php">Login</a>

</body>
</html>