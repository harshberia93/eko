<?php
	session_start();
	if (!isset($_SESSION['username'])) {
		header('Location: login.php');
		exit(0);
	}
	$username = $_SESSION['username'];
?>

<!DOCTYPE html>
<html lang="en">
	<head>
		
	</head>

	<body>
		<div class="container-fluid">
			<div class="row-fluid">

			<?php 
				include 'navbar.html'
			?>
				<div class="span6 offset3">
					<ul class="nav nav-pills nav-stacked">
						<?php
							include('config.php');
							$query = "select name, id from plot_inf where username='$username'";
							$result = mysql_query($query) or die(mysql_error);
							while ($row = mysql_fetch_array($result)) {
								$node_name = $row['name'];
								echo "<li><a href='edit_node_no.php?id=".$row['id']."'>".$node_name."</a></li>";
							}
						?>
					</ul>
				</div>
			</div>
		</div>
	</body>
</html>