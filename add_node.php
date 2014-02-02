<?php
	session_start();
	include ('config.php');
	if (!isset($_SESSION['username'])) {
		header('Location: login.php');
		exit(0);
	}
	$username = $_SESSION['username'];
?>

<!DOCTYPE html>
<html lang="en">
	<head>
		<title>Add node</title>

	</head>

	<body>
		<div class="container">
			<div class="row">

				<?php
					include 'navbar.html'
				?>
				<div class="span6 offset3">
					<form action="#check" method="post" class="form-horizontal">
						
						<div class="form-group">
							<label class="control-label col-sm-2">Name</label>
							<div class="controls">
								<input type="text" name="name" placeholder="Name of Node" required />
							</div>
						</div>

						<div class="form-group">
							<label class="control-label col-sm-2">Type of Soil</label>
							<div class="controls">
								<select id="soil" name="soil">
									<option>Soil 1</option>
									<option>Soil 2</option>
									<option>Soil 3</option>
									<option>Soil 4</option>
									<option>Soil 5</option>
									<option>Soil 6</option>
									<option>Soil 7</option>
									<option>Soil 8</option>
									<option>Soil 9</option>
									<option>Soil 10</option>
									<option>Soil 11</option>
									<option>Soil 12</option>
								</select>
							</div>
							<br>
							<label class="control-label col-sm-2">Field Capacity (%)</label>
							<div class="controls">
								<input type="text" name="fc" placeholder="Field Capacity" value="34" readonly required />
								<span class="lead" style="color: red; margin-left: 10px;"><?php echo $_GET['fc']?></span>
							</div>
							<br>
							<label class="control-label col-sm-2">Permanent Wilting Point (%)</label>
							<div class="controls">
								<input type="text" name="pwp" placeholder="Permanent Wilting Point" value="16" readonly required />
								<span class="lead" style="color: red; margin-left: 10px;"></span>
							</div>
						</div>



						<div class="form-group">
							<label class="control-label col-sm-2">Phone Number</label>
							<div class="controls">
								<input type="text" name="phone" placeholder="10 Digit Phone Number" required />
								<span class="lead" style="color: red; margin-left: 10px;"></span>
							</div>
						</div>

						<div class="form-group">
							<label class="control-label col-sm-2">Crop Name</label>
							<div class="controls">
								<select name="crop">
									<option>Rice</option>
									<option>Wheat</option>
									<option>Others</option>
								</select>
								<input type="text" name="others" id="others" placeholder="Other Crop Name" style="display: none" />
							</div>
						</div>

						<div class="form-group">
							<label class="control-label col-sm-2">Number of Ports</label>
							<div class="controls">
								<select name="port" id="port">
									<option>1</option>
									<option>2</option>
									<option>3</option>
									<option>4</option>
								</select>
							</div>
						</div>

						<div class="form-group" id="port1">
							<label class="control-label col-sm-2">Depth of Port 1 (cm)</label>
							<div class="controls">
								<input type="text" name="port1" placeholder="Depth at Port 1" />
								<span class="lead" style="color: red; margin-left: 10px;"></span>
							</div>
						</div>

						<div class="form-group" id="port2" style="display: none;">
							<label class="control-label col-sm-2">Depth of Port 2 (cm)</label>
							<div class="controls">
								<input type="text" name="port2" placeholder="Depth at Port 2" />
								<span class="lead" style="color: red; margin-left: 10px;"></span>
							</div>
						</div>

						<div class="form-group" id="port3" style="display: none;">
							<label class="control-label col-sm-2">Depth of Port 3 (cm)</label>
							<div class="controls">
								<input type="text" name="port3" placeholder="Depth at Port 3" />
								<span class="lead" style="color: red; margin-left: 10px;"></span>
							</div>
						</div>

						<div class="form-group" id="port4" style="display: none;">
							<label class="control-label col-sm-2">Depth of Port 4 (cm)</label>
							<div class="controls">
								<input type="text" name="port4" placeholder="Depth at Port 4" />
								<span class="lead" style="color: red; margin-left: 10px;"></span>
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
							header("add_node.php?port1=Invalid Depth");
							exit(0);
						}
						$query = "insert into plot_inf 
								(username, name, soil_type, fc, pwp, crop, port_number, port1, phone)
								values
								('$username', '$name', '$soil_type', '$fc', '$pwp', '$crop', '$port_number', '$port1', '$phone')
								";
						mysql_query($query) or die(mysql_error());
						header('add_node.php');
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
						$query = "insert into plot_inf 
								(username, name, soil_type, fc, pwp, crop, port_number, port1, port2, phone)
								values
								('$username', '$name', '$soil_type', '$fc', '$pwp', '$crop', '$port_number', '$port1', '$port2', '$phone')
								";
						mysql_query($query) or die(mysql_error());
						header('add_node.php');
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
						$query = "insert into plot_inf 
								(username, name, soil_type, fc, pwp, crop, port_number, port1, port2, port3, phone)
								values
								('$username', '$name', '$soil_type', '$fc', '$pwp', '$crop', '$port_number', '$port1', '$port2', '$port3', '$phone')
								";
						mysql_query($query) or die(mysql_error());
						header('add_node.php');
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
						$query = "insert into plot_inf 
								(username, name, soil_type, fc, pwp, crop, port_number, port1, port2, port3, port4, phone)
								values
								('$username', '$name', '$soil_type', '$fc', '$pwp', '$crop', '$port_number', '$port1', '$port2', '$port3', '$port4', '$phone')
								";
						mysql_query($query) or die(mysql_error());
		}
	}
?>


		<style type='text/css'>
			.form-horizontal .control-label {
				    text-align: left;
				    
				}
		</style>