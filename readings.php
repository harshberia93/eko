<?php
	session_start();
	if (!isset($_SESSION['username'])) {
		header('Location: login.php');
		exit(0);
	}
	$username = $_SESSION['username'];
	include('config.php');
	include('security.php');
	$node = make_safe($_POST['node']);
	$start_date = make_safe($_POST['start_date']);
	$end_date = make_safe($_POST['end_date']); 
	$query = "select * from readings where id_plot_inf='$node' and date >= '$start_date' and date <= '$end_date'";
	$result = mysql_query($query) or die(mysql_error());
	while ($row = mysql_fetch_array($result)) {
		$response[] = $row;
	}

	$res = array("Response" => $response);
	echo json_encode($res);

?>