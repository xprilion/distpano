<?php

	include('../head.php');

	$sql = "SELECT * FROM clients ";
	$handle = mysqli_query($conn, $sql);

	$totalClientsCount = mysqli_num_rows($handle);


	$sql = "SELECT * FROM clients WHERE status = '0'";
	$handle = mysqli_query($conn, $sql);

	$activeClientsCount = mysqli_num_rows($handle);

	if(isset($_GET["out"])){
		echo $totalClientsCount.",".$activeClientsCount;
	}


?>