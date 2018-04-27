<?php

	include('head.php');

	$cjson = $_GET["cid"];

	$time = time();

	$sql = "";

	$cj = substr($cjson, 0, strlen($cjson)-1);

	$cid = $cj;

	$sql = "UPDATE clients SET status = '9' WHERE cid = '$cid'";
	if(!($conn->query($sql))){
		echo $conn->error;
	}

	$cid = $conn->insert_id;

	$res = Array("status"=>"Stopped");

	$response = json_encode($res);

	echo $response;

