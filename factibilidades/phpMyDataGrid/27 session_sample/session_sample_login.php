<?php
	$user = $_POST['username'];
	$password = $_POST['password'];
	
	# es: Verificar que el nombre de usuario y contraseña sean validos
	# en: Verify that the username and password are valid
	if ($user== "demo" and $password == "demo"){
		session_start();
		$_SESSION['username'] = $user;
		header ('location: session_sample_internalpage.php');
	}else{
		header ('location: session_sample.php');
	}
?>