<?php
	ob_start();

	session_start();
	
	$timezone = date_default_timezone_set("Europe/London");

	$con = mysqli_connect("192.168.2.175", "vince", "C!v!c4553631231", "slotify");

	if(mysqli_connect_errno()) {
		echo "Failed to connect: " . mysqli_connect_errno();
	}
?>
