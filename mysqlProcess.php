<?php
	$conn = mysql_connect("localhost", "root", "chan4cha2") or die(mysql_error());
	echo "Connected to MySQL<br />";
	mysql_select_db("handyman") or die(mysql_error());
	echo "Connected to Database";

	
?>
