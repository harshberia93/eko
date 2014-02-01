<?php
							$username = "a";
							include('config.php');
							
							$day_today = date('Y-m-d'); // Today's day
							echo $day_today."<br>";

							$query = "select * from plot_inf where username='$username'";
							$result = mysql_query($query) or die(mysql_error());

							while ($row = mysql_fetch_array($result)) {
								$id_plot_inf = $row['id'];
								$depth_p1 = $row['port1']; // Depth of Port 1
								$depth_p2 = $row['port2']; // Depth of Port 2
								$depth_p3 = $row['port3']; // Depth of Port 3
								$depth_p4 = $row['port4']; // Depth of Port 4
								$fc = $row['fc']; // Field Capacity
								$pwp = $row['pwp']; // Permanent Wilting Point
								$query1 = "select * from readings where id_plot_inf = '$id_plot_inf' and date = '$day_today'";
								$result1 = mysql_query($query1) or die(mysql_error());

								$row1 = mysql_fetch_array($result1);
								$date = $row1['date'];
								$p1 = round($row1['port1'], 1); // Moisture reading at port 1 in percent
								$p2 = round($row1['port2'], 1); // Moisture reading at port 2 in percent
								$p3 = round($row1['port3'], 1); // Moisture reading at port 3 in percent
								//$p4 = round($row1['port4'], 1); // Moisture reading at port 4 in centibar
								echo $p1." ".$p2." ".$p3."<br>";
								//$p4 = round(35.269 - (1.388 * $p4 * $p4 * $p4), 1); // Moisture reading at port 4 in percent
								$plot_id = $row1['id_plot_inf'];

								$mad = $row['mad']; // Maximum allowable Depletion is 50%
								$root_depth = $row['root_depth']; // Root depth in cm
								$ad = ($fc - $pwp) * $root_depth / 10; // Allowable moisture depletion in mm
								$ad = round($ad * $mad / 100, 1);

								$smd1 = ($fc - $p1)*$depth_p1/10; // Soil Moisture Depletion for port 1 in mm
								$smd2 = ($fc - $p2)*($depth_p2-$depth_p1)/10; // Soil Moisture Depletion for port 2 in mm
								$smd3 = ($fc - $p3)*($depth_p3-$depth_p2)/10; // Soil Moisture Depletion for port 3 in mm
								//$smd4 = ($fc - $p4)*($depth_p4-$depth_p3)/10; // Soil Moisture Depletion for port 4 in mm
								$smd = round($smd1 + $smd2 + $smd3, 1); // Total Soil Moisture Depletion
								echo $smd."<br>";
								if ($smd < $ad) {
									$content = "Hi,
Plot Id: $plot_id
The soil content as on $date is
Port 1 : $p1 %
Port 2 : $p2 %
Port 3 : $p3 %

Soil Moisture Depletion for Port 1: $smd1 mm
Soil Moisture Depletion for Port 2: $smd2 mm
Soil Moisture Depletion for Port 3: $smd3 mm
Overall Soil Depletion for Node 1 : $smd mm
MAD : $mad %
Allowable Moisture Depletion : $ad mm

There is no need of irrigation for now.
								";
									$content_sms = "
Plot Id: $plot_id,Port 1:$p1%, Port 2:$p2%, Port 3:$p3%
Total SMD:$smd mm, Allowable SMD:$ad mm
No need for irrigation.
									";
								} else {
									$rain_forecast = file_get_contents('http://eko.nssc.in/forecast.php');
									$content = "Hi,
Plot Id: $plot_id
The soil content as on $date is
Port 1 : $p1 %
Port 2 : $p2 %
Port 3 : $p3 %

Soil Moisture Depletion for Port 1: $smd1 mm
Soil Moisture Depletion for Port 2: $smd2 mm
Soil Moisture Depletion for Port 3: $smd3 mm
Overall Soil Depletion for Node 1 : $smd mm
MAD : $mad %
Allowable Moisture Depletion : $ad mm
Rainfall in the next 5 days : $rain_forecast mm

Irrigation applied should be : $ad mm
								";
								$content_sms = "
Plot Id: $plot_id,Port 1:$p1%,Port 2:$p2%,Port 3:$p3%
Total SMD:$smd mm, Allowable SMD:$ad mm
Rain next 5 days:$rain_forecast mm
Irrigation:$ad mm
								";
								}
								mail("nsr@agfe.iitkgp.ernet.in", "Soil Moisture Content Report", $content);
							   	mail("bristiarun@gmail.com", "Soil Moisture Content Report", $content);
								mail("harsh.beria93@gmail.com", "Soil Moisture Content Report", $content);
								mail("viswarup004@gmail.com", "Soil Moisture Content Report", $content);

								// create a new cURL resource
								$ch = curl_init();

								// set URL and other appropriate options
								curl_setopt($ch, CURLOPT_URL, "http://api.znisms.com/post/smsv3.asp?userid=NSRIIT&apikey=3f0173dd0f9aa07356726594f60320fa&message=".urlencode($content_sms)."&senderid=NSRIIT&sendto=9434010850");
								curl_setopt($ch, CURLOPT_HEADER, 0);

								// grab URL and pass it to the browser
								curl_exec($ch);

								// close cURL resource, and free up system resources
								// curl_close($ch);

								echo $content."<br>".$content_sms."<br>";

							}
							
?>	