<?php

	$conn = new mysqli("localhost","sih","","sih");

	if ($conn->connect_error) {
		die("Connection failed: " . $conn->connect_error);
	}
	else{
		//echo "hey";
	}

?>