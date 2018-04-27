<?php

	include('head.php');

	$cjson = $_GET["cid"];

	$time = time();

	$sql = "";

	if(strlen(trim(rtrim(ltrim($cjson)))) == 0){
		$sql = "INSERT INTO clients (chash, last_active, status) VALUES ('demo', '$time', '0')";
		if(!($conn->query($sql))){
			echo $conn->error;
		}

		$cid = $conn->insert_id;

		$chash = md5($cid);
		$sql = "UPDATE clients SET chash = '$chash' WHERE cid = '$cid'";
		if(!mysqli_query($conn, $sql)){
			echo mysqli_error($conn);
		}

		$res = Array("chash"=>$chash, "cid"=>$cid);

		$response = json_encode($res);

		echo $response;

	}
	else{

		$cj = substr($cjson, 0, strlen($cjson)-1);

		$cid = $cj;

		$sql = "UPDATE clients SET status = '0', last_active = '$time' WHERE cid = '$cid'";
		$conn->query($sql);

		$chash = md5($cid);
		$res = Array("chash"=>$chash, "cid"=>$cid);

		$response = json_encode($res);

		echo $response;
	}

	//echo $sql;


// {"chash":"c4ca4238a0b923820dcc509a6f75849b","cid":1}