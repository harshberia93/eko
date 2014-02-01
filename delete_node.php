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
		<form action="#delete" name="delete" method="post" class="form-horizontal">
		<div class="container-fluid">
			<?php 
				include 'navbar.html';
			?>
			
			<div class="row-fluid">
				
				<div class="span6 offset3">
					 
					<?php
						include('config.php');
						$query = "select * from plot_inf where username='$username'";
						$result = mysql_query($query) or die(mysql_error);
						while ($row = mysql_fetch_array($result)) {
							$node_name = $row['name'];
							$id = $row['id'];
							echo '<label class="checkbox inline">';
							echo '<input type="checkbox" name="nodename[]" value="'.$id.'">'.$node_name;
							echo '</label>';
						}
					?>

					<br><hr>

					<button type="button" class="btn btn-inverse offset2" onclick="javascript: checkAll();">Select All</button>&nbsp;
					<button type="submit" name="submit" class="btn btn-primary">Delete</button>
				</div>
			</div>
		</div>
		</form>
		<script>
			function checkAll()
			{
				var checkboxes = new Array();
				var formName = "delete";
				checkboxes = document.forms[formName].getElementsByTagName("input");
				for (var i = 0; i < checkboxes.length; i++) {
					if (checkboxes[i].type === 'checkbox') {
						checkboxes[i].checked = true;
					}
				}
			}
		</script>
	</body>
</html>
<?php
	if (isset($_POST['submit'])) {
		$nodename = $_POST['nodename'];
		$count = count($nodename);
		for ($i=0; $i<$count; $i++) {
			$query = "delete from plot_inf where id = '".$nodename[$i]."'";
			mysql_query($query) or die(mysql_error());
			echo "<script>window.location.reload()</script>";
		}
	}
?>