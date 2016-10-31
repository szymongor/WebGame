<?php

	session_start();
	
	if ((!isset($_POST['login'])) || (!isset($_POST['password'])))
	{
		header('Location: index.php');
		exit();
	}

	require_once "connect.php";

	$db_connect = @new mysqli($host, $db_user, $db_password, $db_name);
	
	if ($db_connect->connect_errno!=0)
	{
		echo "Error: ".$db_connect->connect_errno;
	}
	else
	{
		$login = $_POST['login'];
		$password = $_POST['password'];
		
		$login = htmlentities($login, ENT_QUOTES, "UTF-8");
	
		if ($result = @$db_connect->query(
		sprintf("SELECT * FROM users WHERE user='%s'",
		mysqli_real_escape_string($db_connect,$login))))
		{
			$users_number = $result->num_rows;
			if($users_number>0)
			{
				$row = $result->fetch_assoc();
				
				if(password_verify($password, $row['pass']) == true)
				{
					$_SESSION['logged_on'] = true;
					$_SESSION['id'] = $row['id'];
					$_SESSION['user'] = $row['user'];
					$_SESSION['drewno'] = $row['drewno'];
					$_SESSION['kamien'] = $row['kamien'];
					$_SESSION['zboze'] = $row['zboze'];
					$_SESSION['email'] = $row['email'];
					$_SESSION['dnipremium'] = $row['dnipremium'];
					
					unset($_SESSION['error']);
					$result->free_result();
					header('Location: game.php');
				} 
				else {				
					$_SESSION['error'] = '<span style="color:red">Nieprawidłowy login lub hasło!</span>';
					header('Location: index.php');				
				}
				
				
			} else {
				
				$_SESSION['error'] = '<span style="color:red">Nieprawidłowy login lub hasło!</span>';
				header('Location: index.php');
				
			}
			
		}
		
		$db_connect->close();
	}
	
?>