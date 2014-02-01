<?php
	session_start();
	if (!isset($_SESSION['username'])) {
		header('Location: login.php');
		exit(0);
	}
	$username = $_SESSION['username'];
	include('config.php');
	include('security.php');
	$id = make_safe($_GET['id']);
	$query = "select * from plot_inf where id='$id'";
	$result = mysql_query($query) or die(mysql_error());
	$row = mysql_fetch_array($result);

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
						<li style="padding-right: 10%"><a href="node.php">Node</a></li>
						<li style="padding-right: 10%"><a href="graph.php">Graph</a></li>
						<li><a href="decision.php">Decision</a></li>
					</ul>
				</div>

				<div class="span6 offset3">
					<h2 class="text-center">Node</h2>
					<ul class="nav nav-tabs">
						<li style="padding-right: 10%"><a href="add_node.php">Add a Node</a></li>
						<li style="padding-right: 10%"><a href="edit_node.php">Edit a Node</a></li>
						<li><a href="delete_node.php">Delete a Node</a></li>
					</ul>
				</div>

				<div class="span6 offset3">
					<form action="#" method="post" class="form-horizontal">
						
						<div class="control-group">
							<label class="control-label">Name</label>
							<div class="controls">
								<input type="text" name="name" placeholder="Name of Node" value="<?php echo $row['name']; ?>" required />
							</div>
						</div>

						<div class="control-group">
							<label class="control-label">Type of Soil</label>
							<div class="controls">
								<select id="soil" name="soil">
									<?php
										$soils = array("Soil 1", "Soil 2", "Soil 3", "Soil 4", "Soil 5", "Soil 6", "Soil 7", "Soil 8",
														"Soil 9", "Soil 10", "Soil 11", "Soil 12");
										for($i=0; $i<count($soils); $i++) {
											if ($soils[$i] == $row['soil_type']) {
												echo "<option selected>".$row['soil_type']."</option>";
											} else {
												echo "<option>".$soils[$i]."</option>";
											}
										}
									?>
								</select>
							</div>
							<br>
							<label class="control-label">Field Capacity (%)</label>
							<div class="controls">
								<input type="text" name="fc" placeholder="Field Capacity" value="<?php echo $row['fc']; ?>" readonly required />
								<span class="lead" style="color: red; margin-left: 10px;"><?php echo $_GET['fc']?></span>
							</div>
							<br>
							<label class="control-label">Permanent Wilting Point (%)</label>
							<div class="controls">
								<input type="text" name="pwp" placeholder="Permanent Wilting Point" value="<?php echo $row['pwp']; ?>" readonly required />
								<span class="lead" style="color: red; margin-left: 10px;"></span>
							</div>
						</div>



						<div class="control-group">
							<label class="control-label">Phone Number</label>
							<div class="controls">
								<input type="text" name="phone" placeholder="10 Digit Phone Number" value="<?php echo $row['phone']; ?>" required />
								<span class="lead" style="color: red; margin-left: 10px;"></span>
							</div>
						</div>

						<div class="control-group">
							<label class="control-label">Crop Name</label>
							<div class="controls">
								<select name="crop">
									<?php
										$crops = array("Rice", "Wheat", "Others");
										for ($i=0; $i<count($crops); $i++) {
											if ($crops[$i] == $row['crop']) {
												echo "<option selected>$crops[$i]</options>";
											} else {
												echo "<option>$crops[$i]</options>";
											}
										}
									?>
								</select>
								<input type="text" name="others" id="others" placeholder="Other Crop Name" style="display: none" />
							</div>
						</div>

						<div class="control-group">
							<label class="control-label">Number of Ports</label>
							<div class="controls">
								<select name="port" id="port">
									<?php
										$ports = array('1', '2', '3', '4');
										for ($i=0; $i<count($ports); $i++) {
											if ($ports[$i] == $row['port_number']) {
												echo "<option selected>$ports[$i]</option>";
											} else {
												echo "<option>$ports[$i]</option>";
											}
										}
									?>
								</select>
							</div>
						</div>

						<div class="control-group" id="port1">
							<label class="control-label">Depth of Port 1 (cm)</label>
							<div class="controls">
								<input type="text" name="port1" placeholder="Depth at Port 1" value="<?php echo $row['port1']; ?>"/>
								<span class="lead" style="color: red; margin-left: 10px;"></span>
							</div>
						</div>

						<div class="control-group" id="port2" style="display: none;">
							<label class="control-label">Depth of Port 2 (cm)</label>
							<div class="controls">
								<input type="text" name="port2" placeholder="Depth at Port 2" value="<?php echo $row['port2']; ?>" />
								<span class="lead" style="color: red; margin-left: 10px;"></span>
							</div>
						</div>

						<div class="control-group" id="port3" style="display: none;">
							<label class="control-label">Depth of Port 3 (cm)</label>
							<div class="controls">
								<input type="text" name="port3" placeholder="Depth at Port 3" value="<?php echo $row['port3']; ?>"/>
								<span class="lead" style="color: red; margin-left: 10px;"></span>
							</div>
						</div>

						<div class="control-group" id="port4" style="display: none;">
							<label class="control-label">Depth of Port 4 (cm)</label>
							<div class="controls">
								<input type="text" name="port4" placeholder="Depth at Port 4" value="<?php echo $row['port4']; ?>"/>
								<span class="lead" style="color: red; margin-left: 10px;"><?php ?></span>
							</div>
						</div>

						<button type="submit" name="submit" class="btn btn-primary offset4">Submit</button>

					</form>
				</div>
			</div>
		</div>
	</body>

	<script type="text/javascript" src="http://code.jquery.com/jquery-2.0.3.min.js"></script>
	<script>
		// For Crop Name if crop name selected is Others
		$('#crop').change(function(){
			var crop_name = $('#crop option:selected').text();
			if (crop_name == 'Others') {
				$('#others').show();
			} else {
				$('#others').hide();
			}
		});

		// For displaying the right number of text box of depth at beginning
		switch ($('#port option:selected').text()) {
			case '1':    $('#port2').hide();
						 $('#port3').hide();
						 $('#port4').hide();
						 break;
			case '2': 	 $('#port2').show();
						 $('#port3').hide();
						 $('#port4').hide();
						 break;
			case '3': 	 $('#port2').show();
						 $('#port3').show();
						 $('#port4').hide();
						 break;
			case '4': 	 $('#port2').show();
						 $('#port3').show();
						 $('#port4').show();
						 break;
		}	

		// For displaying depths of the number of ports
		$('#port').change(function(){
			var port_num = $('#port option:selected').text();
			switch (port_num) {
				case '1':   $('#port2').hide();
							$('#port3').hide();
							$('#port4').hide();
							break;

                case '2':   $('#port2').show();
                			$('#port3').hide();
                			$('#port4').hide();
                			break;

                case '3':   $('#port2').show();
                			$('#port3').show();
                			$('#port4').hide();
                			break;

                case '4':   $('#port2').show();
                			$('#port3').show();
                			$('#port4').show();
                			break;
			}
		});

	</script>
</html>

<?php

	if (isset($_POST['submit'])) {
		include ('security.php');
		echo "<script>alert('its working');</script>";
		
		$regex_float = "/^[-+]?[0-9]*\.?[0-9]+$/";
		$regex_num = "/^[789][0-9]{9}$/";

		$name = make_safe($_POST['name']);
		$soil_type = make_safe($_POST['soil']);
		$fc = make_safe($_POST['fc']);

		if (!preg_match($regex_float, $fc)) {
			header("Location: add_node.php?fc=Invalid Field Capacity");
			exit(0);
		}

		$pwp = make_safe($_POST['pwp']);
		if (!preg_match($regex_float, $pwp)) {
			header("Location: add_node.php?pwp=Invalid Permanent Wilting Point");
			exit(0);
		}

		$phone = make_safe($_POST['phone']);
		if (!preg_match($regex_num, $phone)) {
			header('location: add_node.php?phone=Invalid Phone Number');
			exit(0);
		}
		$crop = make_safe($_POST['crop']);
		$port_number = make_safe($_POST['port']);

		switch ($port_number) {
			case '1':	$port1 = make_safe($_POST['port1']);
						if (!preg_match($regex_float, $port1)) {
							header("Location: add_node.php?port1=Invalid Depth");
							exit(0);
						}
						$query = "update plot_inf 
								set name='$name', soil_type='$soil_type', fc='$fc', pwp='$pwp', crop='$crop',
								port_number='$port_number', port1='$port1', port2='0', port3='0', port4='0'
								where id='$id'
								";  
						mysql_query($query) or die(mysql_error());
						header('location: edit_node.php');
						exit(0);
						break;

			case '2':	$port1 = make_safe($_POST['port1']);
						$port2 = make_safe($_POST['port2']);
						if (!preg_match($regex_float, $port1)) {
							header("Location: add_node.php?port1=Invalid Depth");
							exit(0);
						} elseif (!preg_match($regex_float, $port2)) {
							header("Location: add_node.php?port2=Invalid Depth");
							exit(0);
						}
						$query = "update plot_inf 
								set name='$name', soil_type='$soil_type', fc='$fc', pwp='$pwp', crop='$crop',
								port_number='$port_number', port1='$port1', port2='$port2', port3='0', port4='0'
								where id='$id'
								";  
						mysql_query($query) or die(mysql_error());
						header('location: edit_node.php');
						exit(0);
						break;

			case '3':	$port1 = make_safe($_POST['port1']);
						$port2 = make_safe($_POST['port2']);
						$port3 = make_safe($_POST['port3']);
						if (!preg_match($regex_float, $port1)) {
							header("Location: add_node.php?port1=Invalid Depth");
							exit(0);
						} elseif (!preg_match($regex_float, $port2)) {
							header("Location: add_node.php?port2=Invalid Depth");
							exit(0);
						} elseif (!preg_match($regex_float, $port3)) {
							header("Location: add_node.php?port3=Invalid Depth");
							exit(0);
						}
						$query = "update plot_inf 
								set name='$name', soil_type='$soil_type', fc='$fc', pwp='$pwp', crop='$crop',
								port_number='$port_number', port1='$port1', port2='$port2', port3='$port3', port4='0'
								where id='$id'
								";  
						mysql_query($query) or die(mysql_error());
						header('location: edit_node.php');
						exit(0);
						break;

			case '4':	$port1 = make_safe($_POST['port1']);
						$port2 = make_safe($_POST['port2']);
						$port3 = make_safe($_POST['port3']);
						$port4 = make_safe($_POST['port4']);
						if (!preg_match($regex_float, $port1)) {
							header("Location: add_node.php?port1=Invalid Depth");
							exit(0);
						} elseif (!preg_match($regex_float, $port2)) {
							header("Location: add_node.php?port2=Invalid Depth");
							exit(0);
						} elseif (!preg_match($regex_float, $port3)) {
							header("Location: add_node.php?port3=Invalid Depth");
							exit(0);
						} elseif (!preg_match($regex_float, $port4)) {
							header("Location: add_node.php?port4=Invalid Depth");
							exit(0);
						}
						$query = "update plot_inf 
								set name='$name', soil_type='$soil_type', fc='$fc', pwp='$pwp', crop='$crop',
								port_number='$port_number', port1='$port1', port2='$port2', port3='$port3', port4='$port4'
								where id='$id'
								";  
						mysql_query($query) or die(mysql_error());
						header('location: edit_node.php');
						exit(0);
		}
	}
?>