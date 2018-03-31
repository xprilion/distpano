<?php

	include('../head.php');

	$sql = "SELECT * FROM pending WHERE staged = '1'";
	if(!$handle = mysqli_query($conn, $sql)){
		echo "err";
	}

	$stagedCount = mysqli_num_rows($handle);

	if(isset($_GET["out"])){
		echo $stagedCount;
	}


?>