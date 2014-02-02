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
						<li style="padding-right: 10%"><a href="node.php">Node</a></li>
						<li style="padding-right: 10%"><a href="graph.php">Graph</a></li>
						<li class="active"><a href="#">Decision</a></li>
					</ul>
				</div>

				<div class="span6 offset3">
					<table border="1">
						<tr>
							<th>Day</th>
							<th>Port 1</th>
							<th>Port 2</th>
							<th>SMD, mm for Port 1</th>
							<th>SMD, mm for Port 2</th>
							<th>Total SMD, mm</th>
						</tr>
						<?php
							include('config.php');
							
							$day_today = date('y-m-d'); // Today's day
							$day_today_1 = str_replace('-', '/', $date);
							$day_three_months_ago = date('d',strtotime($day_today_1 . "-3 months"));

							$query = "select * from plot_inf where username='$username'";
							$result = mysql_query($query) or die(mysql_error());
							$row = mysql_fetch_array($result);
							$id_plot_inf = $row['id'];
							$depth_p1 = $row['port1']; // Depth of Port 1
							$depth_p2 = $row['port2']; // Depth of Port 2
							$fc = $row['fc']; // Field Capacity
							$pwp = $row['pwp']; // Permanent Wilting Point

							$query = "select * from readings where id_plot_inf = '$id_plot_inf'";
							$result = mysql_query($query) or die(mysql_error());
							while ($row = mysql_fetch_array($result)){
								$date = $row['date'];
								$p1 = $row['port1']; // Moisture reading at port 1 in percent
								$p2 = $row['port2']; // Moisture reading at port 2 in percent

								$mad = 50; // Maximum allowable Depletion is 50%
								$ad = ($fc - $pwp) * 45 / 10; // Allowable moisture depletion in mm. Here 45 represents the root depth in cm
								$ad = $ad * $mad / 100;

								$smd1 = ($fc - $p1)*$depth_p1/10; // Soil Moisture Depletion for port 1 in mm
								$smd2 = ($fc - $p2)*$depth_p2/10; // Soil Moisture Depletion for port 2 in mm
								$smd = $smd1 + $smd2; // Total Soil Moisture Depletion

								$day = date('d', strtotime($date));
								if ($day == $day_three_months_ago) {
									if ($smd < $ad) {
										$content = "Hi,
The soil content as on $date is
Port 1 : $p1 %
Port 2 : $p2 %

Soil Moisture Depletion for Port 1: $smd1 mm
Soil Moisture Depletion for Port 2: $smd2 mm
Overall Soil Depletion for Node 1 : $smd mm

Allowable Moisture Depletion : $ad mm

There is no need of irrigation for now.
									";
									} else {
										$rain_forecast = file_get_contents('http://eko.nssc.in/forecast.php');
										$content = "Hi,
The soil content as on $date is
Port 1 : $p1 %
Port 2 : $p2 %

Soil Moisture Depletion for Port 1: $smd1 mm
Soil Moisture Depletion for Port 2: $smd2 mm
Overall Soil Depletion for Node 1 : $smd mm

Allowable Moisture Depletion : $ad mm
Rainfall in the next 15 days : $rain_forecast mm

Irrigation applied should be : $ad mm
									";
									}
									
									//mail("nsr@agfe.iitkgp.ernet.in", "Soil Moisture Content Report", $content);
									//mail("harsh.beria93@gmail.com", "Soil Moisture Content Report", $content);
								}

								echo "<tr>";
								echo "<td style='padding: 5px 5px 5px 5px;'>$date</td>";
								echo "<td style='padding: 5px 5px 5px 5px;'>$p1</td>";
								echo "<td style='padding: 5px 5px 5px 5px;'>$p2</td>";
								echo "<td style='padding: 5px 5px 5px 5px;'>$smd1</td>";
								echo "<td style='padding: 5px 5px 5px 5px;'>$smd2</td>";
								echo "<td style='padding: 5px 5px 5px 5px;'>$smd</td>";
								echo "</tr>";
							}
						?>
					</table>
				</div>
			</div>
		</div>
	</body>
</html>
