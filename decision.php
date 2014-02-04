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
		<?php 
			include 'navbar.html'
		?>

			<div class="row-fluid">
				<div class="span6 offset3">
					<table border="1">
						<tr>
							<th>Day</th>
							<th>Port 1</th>
							<th>Port 2</th>
							<th>Port 3</th>
							<th>Port 4</th>
							<th>SMD, mm for Port 1</th>
							<th>SMD, mm for Port 2</th>
							<th>SMD, mm for Port 3</th>
							<th>SMD, mm for Port 4</th>
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
							$depth_p3 = $row['port3']; // Depth of Port 1
							$depth_p4 = $row['port4']; // Depth of Port 2
							$fc = $row['fc']; // Field Capacity
							$pwp = $row['pwp']; // Permanent Wilting Point

							$query = "select * from readings where id_plot_inf = '$id_plot_inf'";
							$result = mysql_query($query) or die(mysql_error());
							while ($row = mysql_fetch_array($result)){
								$date = $row['date'];
								$p1 = $row['port1']; // Moisture reading at port 1 in percent
								$p2 = $row['port2']; // Moisture reading at port 2 in percent
								$p3 = $row['port3']; // Moisture reading at port 3 in percent
								$p4 = $row['port4']; // Moisture reading at port 4 in percent

								$mad = 50; // Maximum allowable Depletion is 50%
								$ad = ($fc - $pwp) * 45 / 10; // Allowable moisture depletion in mm. Here 45 represents the root depth in cm
								$ad = $ad * $mad / 100;

								$smd1 = ($fc - $p1)*$depth_p1/10; // Soil Moisture Depletion for port 1 in mm
								$smd2 = ($fc - $p2)*$depth_p2/10; // Soil Moisture Depletion for port 2 in mm
								$smd3 = ($fc - $p3)*$depth_p3/10; // Soil Moisture Depletion for port 1 in mm
								$smd4 = ($fc - $p4)*$depth_p4/10; // Soil Moisture Depletion for port 2 in mm
								$smd = $smd1 + $smd2 + $smd3 + $smd4; // Total Soil Moisture Depletion

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
								echo "<td style='padding: 5px 5px 5px 5px;'>$p3</td>";
								echo "<td style='padding: 5px 5px 5px 5px;'>$p4</td>";
								echo "<td style='padding: 5px 5px 5px 5px;'>$smd1</td>";
								echo "<td style='padding: 5px 5px 5px 5px;'>$smd2</td>";
								echo "<td style='padding: 5px 5px 5px 5px;'>$smd3</td>";
								echo "<td style='padding: 5px 5px 5px 5px;'>$smd4</td>";
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
