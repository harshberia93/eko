<?php
	session_start();
	if (isset($_SESSION['username'])) {
		header('Location: index.php');
		exit(0);
	}
?>

<!DOCTYPE html>
<html lang="en">
	<head>
		<link href="css/bootstrap.min.css" rel="stylesheet">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<link href="css/bootstrap-responsive.min.css" rel="stylesheet">
	</head>

	<body>
		<div class="container-fluid">
			<div class="row-fluid">
				<div class="span6 offset3">
					<h2 class="text-center">Smart Irrigation Alerts</h2>
					<hr>
					<form action="#check" class="form-horizontal" method="post">
						<div class="control-group">
							<label class="control-label">Username</label>
							<div class="controls">
								<input type="text" name="username" placeholder="Username" required/>
							</div>
						</div>

						<div class="control-group">
							<label class="control-label">Password</label>
							<div class="controls">
								<input type="password" name="password" placeholder="Password" required/>
							</div>
						</div>

						<div class="form-actions">
							<button type="submit" class="btn btn-large btn-primary offset1">Login</button>
						</div>
					</form>
			</div>
			</div>
		</div>
	</body>
</html>

<?php
	if ($_POST['username'] && $_POST['password']) {
		include ('config.php');
		include ('security.php');
		$username = make_safe($_POST['username']);
		$password = MD5(make_safe($_POST['password']));
		$result = mysql_query("select * from users where username = '$username' and password = '$password'");
		$row = mysql_fetch_array($result);
		if (mysql_num_rows($result) == 1) {
			$_SESSION['username'] = $row['username'];
			$_SESSION['id_users'] = $row['id'];
			header('Location: index.php');
			exit(0);
		} else {

		}
	}
?>