<?php

	error_reporting(E_ALL);
	ini_set('display_errors', 1);

	include('head.php');
	require_once("splitter.php");

	umask(0);

	$sql = "select * from tasks";
	$result = mysqli_query($conn,$sql);
	$num = mysqli_num_rows($result);
	$task = array();

	// echo "<table width=200 border=2><tr><th>id</th><th>name_hash</th><th>time</th>";

	while($row = mysqli_fetch_assoc($result)){
		$taskid = $row['taskid'];
		$files = array();
		$sql2 = "select  * from pending where task_id='$taskid'";
		$result2 = mysqli_query($conn,$sql2);
		$num2 = mysqli_num_rows($result2);
		while($row2 = mysqli_fetch_assoc($result2)){
			$files[] = $row2;

		}
		$task[] = array("taskid"=>$taskid, "oname"=>$row['oname'], "hashname"=>$row['hashname'], "no_files"=>count($files), "files"=>$files);

	}



	$res = Array("no_tasks"=>$num, "tasks"=>$task);
	// $out = array_values($res);
	//$json = json_encode($res, JSON_PRETTY_PRINT);
	$json = json_encode($res);

//	print "<pre>";
	print ($json);

	$taskList = $res["tasks"];

	foreach ($taskList as $task){
		$taskOname = $task["oname"];
		$taskHashname = $task["hashname"];
		$taskId = $task["taskid"];

		//echo " <br><b>$taskOname ($taskHashname - $taskId)</b>";
		$filesList = $task["files"];

		foreach($filesList as $imgfile){
			$imgOname = $imgfile["oname"];
			$imgHashname =$imgfile["name_hash"];
			$imgExt = $imgfile["ext"];
			$img_id = $imgfile["id"];

			//echo " <br>----> $imgOname.$imgExt ($imgHashname.$imgExt)";

			//splitH($imgName, $imgExt, $inPath, $outPath, $splits)
			//splitV($imgName, $imgExt, $inPath, $outPath, $splits)

			if (!is_dir("staged/".$taskHashname)){
				mkdir("staged/".$taskHashname, 0777);
			}

			splitH($imgHashname, $imgExt, "images/".$taskHashname, "staged/".$taskHashname, 9);
			splitV($imgHashname, $imgExt, "images/".$taskHashname, "staged/".$taskHashname, 9);

			$sql3 = "INSERT INTO staged(task_id, img_id, status) VALUES ('$taskId','$img_id','0')";
			$conn -> query($sql3);

			$sql4 = "UPDATE pending SET staged = '1' WHERE id = '$img_id'";
			mysqli_query($conn, $sql4);

		}
	}
//	print "</pre>";

?>