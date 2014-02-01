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
		<link href="css/bootstrap.min.css" rel="stylesheet">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<link href="css/bootstrap-responsive.min.css" rel="stylesheet">
	</head>

	<body>
		<div class="container-fluid">
			<div class="row-fluid">

				<div class="span6 offset3">
					<ul class="nav nav-tabs">
						<li style="padding-right: 10%" class="active"><a href="node.php">Node</a></li>
						<li style="padding-right: 10%"><a href="graph.php">Graph</a></li>
						<li><a href="decision.php">Decision</a></li>
					</ul>
				</div>

				<div class="span6 offset3">
					<h2 class="text-center">Node</h2>
					<ul class="nav nav-tabs">
						<li style="padding-right: 10%"><a href="add_node.php">Add a Node</a></li>
						<li class="active" style="padding-right: 10%"><a href="edit_node.php">Edit a Node</a></li>
						<li><a href="delete_node.php">Delete a Node</a></li>
					</ul>
				</div>

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