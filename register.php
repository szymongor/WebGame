<?php

	session_start();

	if(isset($_POST['email']))
	{
		$correctForm = true;

		//Check nickname
		$nick = $_POST['nick'];
		if((strlen($nick) < 3) || (strlen($nick) >20))
		{
			$correctForm = false;
			$_SESSION['e_nick']="Nick should have from 3 to 20 characters!";
		}

		if(ctype_alnum($nick)==false)
		{
			$correctForm = false;
			$_SESSION['e_nick']="Nick should contain only letters and numbers!";
		}

		//Check email
		$email = $_POST['email'];
		$emailB = filter_var($email,FILTER_SANITIZE_EMAIL);
		if((filter_var($emailB,FILTER_VALIDATE_EMAIL)==false) || ($emailB != $email))
		{
			$correctForm = false;
			$_SESSION['e_email']="Please enter a valid email";
		}


		//Check password
		$pass1 = $_POST['pass1'];
		$pass2 = $_POST['pass2'];

		if($pass1 != $pass2)
		{
			$correctForm = false;
			$_SESSION['e_pass']="The passwords are not the same!";
		}

		$pass_hash = password_hash($pass1, PASSWORD_DEFAULT);

		//Check rules accept
		if(!isset($_POST['rules']))
		{
			$correctForm = false;
			$_SESSION['e_rules']="Accept the rules!";
		}


		//recaptcha
		$secret = "6LdMWwkUAAAAAEAdYA2HzaaDbYXSendGhTiXCzyC";

		$g_check = file_get_contents('https://www.google.com/recaptcha/api/siteverify?secret='.$secret.'&response='.$_POST['g-recaptcha-response']);

		$g_response = json_decode($g_check);

		if($g_response->success == false)
		{
			$correctForm = false;
			$_SESSION['e_bot']="Confirm reCAPTCHA!";
		}

		if((strlen($pass1)<8) || (strlen($pass1)>20))
		{
			$correctForm = false;
			$_SESSION['e_pass']="Password should have from 8 to 20 characters!";
		}

		require_once "connect.php";
		mysqli_report(MYSQLI_REPORT_STRICT);
		try
		{
			$db_connect = new mysqli($host, $db_user, $db_password, $db_name);
			if($db_connect->connect_errno!=0)
			{
				throw new Exception(mysqli_connect_errno());
			}
			else
			{
				//Does email already exist in db?
				$result = $db_connect->query("SELECT id FROM users WHERE email='$email'");
				if(!$result) throw new Exception ($db_connect->error);

				$number_of_emails = $result->num_rows;
				if($number_of_emails > 0)
				{
					$correctForm = false;
					$_SESSION['e_email']="E-mail already registered!";
				}

				//Does nick already exist in db?
				$result = $db_connect->query("SELECT id FROM users WHERE user='$nick'");
				if(!$result) throw new Exception ($db_connect->error);

				$number_of_nicks = $result->num_rows;
				if($number_of_nicks > 0)
				{
					$correctForm = false;
					$_SESSION['e_nick']="Nick already registered!";
				}

				if($correctForm == true)
				{
					if($db_connect->query("INSERT INTO users VALUES (NULL, '$nick','$pass_hash','$email')"))
					{
						$_SESSION['successful_registration']=true;
						header('Location: welcome.php');
					}
					else
					{
						throw new Exception ($db_connect->error);
					}
					exit();
				}

				$db_connect->close();
			}
		}
		catch(Exception $e)
		{
			echo '<div class="error">Server error</div>';
			echo '<br />Informacja dev: '.$e;
		}



	}

?>

<!DOCTYPE HTML>
<html lang="pl">
<head>
	<meta charset="utf-8" />
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
	<title>Osadnicy - Rejestracja</title>
	<script src='https://www.google.com/recaptcha/api.js'></script>
	<link  rel="stylesheet" href="style.css" type="text/css"/>

</head>

<body>
	<div id="container">
		<form method="post">
			Nickname:<br /><input type="text" name="nick" placeholder="Nickname"><br />

			<?php
				if(isset($_SESSION['e_nick']))
				{
					echo '<div class="error">'.$_SESSION['e_nick'].'</div>';
					unset($_SESSION['e_nick']);
				}
			?>
			E-mail:<br /><input type="text" name="email" placeholder="E-mail"><br />

			<?php
				if(isset($_SESSION['e_email']))
				{
					echo '<div class="error">'.$_SESSION['e_email'].'</div>';
					unset($_SESSION['e_email']);
				}
			?>

			Password:<br /><input type="password" name="pass1" placeholder="Password"><br />
			<?php
				if(isset($_SESSION['e_pass']))
				{
					echo '<div class="error">'.$_SESSION['e_pass'].'</div>';
					unset($_SESSION['e_pass']);
				}
			?>

			Password again:<br /><input type="password" name="pass2" placeholder="Confirm password"><br />
			<label>
				<input type="checkbox" name="rules">Accept rules
			</label>
			<?php
				if(isset($_SESSION['e_rules']))
				{
					echo '<div class="error">'.$_SESSION['e_rules'].'</div>';
					unset($_SESSION['e_rules']);
				}
			?>

			<div class="g-recaptcha" data-sitekey="6LdMWwkUAAAAAOqKsbhNS1hGhcryP4T6L1uyXAwB"></div>
			<?php
				if(isset($_SESSION['e_bot']))
				{
					echo '<div class="error">'.$_SESSION['e_bot'].'</div>';
					unset($_SESSION['e_bot']);
				}
			?>

			<br />

			<input type="submit" value="Register">

		</form>
	</div>
</body>
</html>
