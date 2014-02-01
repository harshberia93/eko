<?php
	// create a new cURL resource
	$ch = curl_init();

	// set URL and other appropriate options
	curl_setopt($ch, CURLOPT_URL, "http://api.znisms.com/post/smsv3.asp?userid=NSRIIT&apikey=3f0173dd0f9aa07356726594f60320fa&message=This&senderid=NSRIIT&sendto=9434010850");
	curl_setopt($ch, CURLOPT_HEADER, 0);

	// grab URL and pass it to the browser
	curl_exec($ch);

	// close cURL resource, and free up system resources
	curl_close($ch);
?>