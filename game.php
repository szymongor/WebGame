<?php

	session_start();


	if (!isset($_SESSION['logged_on']))
	{
		header('Location: index.php');
		exit();
	}

?>
<!DOCTYPE HTML>
<html lang="pl">
<head>
	<meta charset="utf-8" />
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
	<title>Game</title>
	<link  rel="stylesheet" href="style.css" type="text/css"/>
	<script src="jquery\jquery-3.1.1.min.js"></script>
	<script src="JSClasses\MapView0.js"></script>
	<script src="JSClasses\DetailView.js"></script>
	<script src="JSClasses\ResourcesView.js"></script>
	<script src="JSClasses\ApiClient.js"></script>
	<script src="main.js"></script>

</head>

<body>



<div class="logo">
				<span style="color: #03b22f">Ultra</span>Gra
				<div style="clear:both;"></div>
</div>

<!--
<div class="nav">
			<ol>
				<li><a href="#">Strona główna</a></li>
				<li><a href="#">O grze sztynks</a></li>
				<li><a href="logout.php">Wyloguj</a></li>
			</ol>

</div>
-->

<div class="gameContainer">
	<div id="resourcesBar" onclick="showResources()">

	</div>

	<div clear="both"></div>
	<div id="gameOptions">
		<div class="gameOption" id="Conquer" onclick="conquer()">Conquer</div>
	</div>
	<div id="gameMap">

	</div>
	<div id="gameDetails">

	</div>

</div>

<?php

/*
<div class="gameDetailsOptionsContainer">
	<div class="gameDetailsOption" id="detailsMap" onclick="setDetailsMap()">Map</div>
	<div class="gameDetailsOption" id="detailsBuilding" onclick="setDetailsBuilding()">Building</div>
</div>
<div class="gameDetailsView" id="detailsView">

</div>
========================================

	echo "<p>Witaj ".$_SESSION['user'].'! [ <a href="logout.php">Wyloguj się!</a> ]</p>';
	echo "<p><b>Drewno</b>: ".$_SESSION['drewno'];
	echo " | <b>Kamień</b>: ".$_SESSION['kamien'];
	echo " | <b>Zboże</b>: ".$_SESSION['zboze']."</p>";

	echo "<p><b>E-mail</b>: ".$_SESSION['email'];
	echo "<br /><b>Dni premium</b>: ".$_SESSION['dnipremium']."</p>";
*/
?>

</script>

</body>
</html>
