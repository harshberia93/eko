<?php
	session_start();
	unset($_SESSION['username']);
	unset($_SESSION['id_users']);
	header('Location: login.php');
	exit(0);
?>