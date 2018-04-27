<?php

	include('../head.php');

	$sql = "SELECT * FROM pending WHERE staged = '0'";
	$handle = mysqli_query($conn, $sql);

	$imgCount = mysqli_num_rows($handle);

	if(isset($_GET["out"])){
		echo $imgCount;
	}

	//echo $imgCount;

?>