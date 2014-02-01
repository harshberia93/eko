<?php
	include ('config.php');
	include ('security.php');
	$username = make_safe($_GET['username']);
	$date = make_safe($_GET['date']);
	$p1_sum = $p2_sum = $p3_sum = $p4_sum = 0;
	$num = 0.0;
	
	for ($i=0; $i<4; $i++) {
                $c = 1;
		$p1 = make_safe($_GET[4*$i+1]);
		$p2 = make_safe($_GET[4*$i+2]);
		$p3 = make_safe($_GET[4*$i+3]);
		$p4 = make_safe($_GET[4*$i+4]);
		$node = "Node ".($i+2);
		$id_node = $i + 2 + 2;
		if ($p1 == '') {
			$day_today = $date;
			$day_yesterday = date('Y-m-d',strtotime($day_yesterday . "-1 day"));
		// 	$all_nodes = array("Node 2", "Node 3", "Node 4", "Node 5"); // All nodes

		// 	for ($j=0; $j < count($all_nodes); $j++) { // Now we have to subtract differences for three other nodes
		// 		$node_present = $all_nodes[$j];
		// 		$id_node_present = $node_present + 2;
		// 		$p1_present = (($node_present[5] - 2)*4)+1;
		// 		$p2_present = (($node_present[5] - 2)*4)+2;
		// 		$p3_present = (($node_present[5] - 2)*4)+3;
		// 		$p4_present = (($node_present[5] - 2)*4)+4;
		// 		$p1_present = $_GET['$p1_present'];
		// 		$p2_present = $_GET['$p2_present'];
		// 		$p3_present = $_GET['$p3_present'];
		// 		$p4_present = $_GET['$p4_present'];
		// 		if ($all_nodes[$j] == $node or $p1_present == '') { // To prevent case where node is working node 
		// 			continue; // Or for node whose data is not available today
		// 		}
		// 		$query1 = "select * from readings where date = '$day_yesterday' and interpolated = 'false' and id_plot_inf = '$id_node_present'";
		// 		$result1 = mysql_query($query1) or die(mysql_error());
		// 		if (mysql_num_rows($result1) == 1) {
		// 			$row1 = mysql_fetch_array($result1);
		// 			$p1_past = $row1['port1'];
		// 			$p2_past = $row1['port2'];
		// 			$p3_past = $row1['port3'];
		// 			$p4_past = $row1['port4'];
		// 			$p1_sum += $p1_past - $p1_present;
		// 			$p2_sum += $p2_past - $p2_present;
		// 			$p3_sum += $p3_past - $p3_present;
		// 			$p4_sum += $p4_past - $p4_present;
		// 			$num += 1;
		// 		}
		// 	}

			$query2 = "select * from readings where date = '$day_yesterday' and id_plot_inf = '$id_node'";
			$result2 = mysql_query($query2) or die(mysql_error());
			$row2 = mysql_fetch_array($result2);
			$p1 = $row2['port1'];
			$p2 = $row2['port2'];
			$p3 = $row2['port3'];
			$p4 = $row2['port4'];
			$c = 0;
		}
		echo $node."<br>";
		echo $p1." ".$p2." ".$p3." ".$p4."<br>";
		$id_plot_inf = $i + 4;
		if ($c == 1) {
			$query = "insert into readings (id_plot_inf, port1, port2, port3, port4, date, interpolated) values ('$id_plot_inf', '$p1', '$p2', '$p3', '$p4', '$date', 'false')";
			mysql_query($query) or die(mysql_error());
		} else {
			$query = "insert into readings (id_plot_inf, port1, port2, port3, port4, date, interpolated) values ('$id_plot_inf', '$p1', '$p2', '$p3', '$p4', '$date', 'true')";
			mysql_query($query) or die(mysql_error());
		}
	}
	echo "Great! Its done for today!";
?>