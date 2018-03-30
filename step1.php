<?php

	//Author: mayankgbrc, xprilion

	include('head.php');

	$targetDir = "images";
	$initialDir = "bin";
	$count=0;
	$task = array();

	// Open a directory, and read its contents

	$initialDirHandler = opendir($initialDir);

	while ((($taskName = readdir($initialDirHandler)) !== false )){
		//echo "1  file name is ".$taskName;
		if(!(($taskName==".") || ($taskName==".."))){

			$time = time();

			$targetDirHash = md5($taskName.$time);

			$sql2 = "INSERT INTO tasks (oname, hashname, created) VALUES('$taskName','$targetDirHash','$time')";

			if(!($conn -> query($sql2))){
				echo $conn -> error;
			}

			$taskID = $conn->insert_id;

			//echo "2  ihjhihjhijohijohijihfile name is ".$taskName;
			if($taskHandler = opendir($initialDir."/".$taskName) ){

				$imagesArray = array();
				//echo "3  ihjhihjhijohijohijihfile name is ".$taskName;
				while (($imageName = readdir($taskHandler)) !== false){
					//echo "4  ihjhihjhijohijohijihfile name is ".$taskName;
					if (!(is_dir($initialDir."/".$taskName."/".$imageName))){
						//echo "Current dir is ".$taskName;
						$extension = pathinfo($imageName, PATHINFO_EXTENSION);

						$name_hash = md5($imageName.$time);

						$targetDirPath = "images/".$targetDirHash;
						if(!file_exists($targetDirPath)){
							if(!(is_dir($targetDirPath))){
								mkdir($targetDirPath);
							}
						}
						// echo  $initialDir."/".$taskName."/".$imageName."<br>";
						// echo  $targetDirPath."/".$name_hash;
						// echo "<br>--------------------------------------<br>";
						copy($initialDir."/".$taskName."/".$imageName, $targetDirPath."/".$name_hash.".".$extension);
						//unlink ($initialDir.$imageName);

						$sql = "INSERT INTO pending (oname,name_hash, task_id, time,ext) VALUES('$imageName','$name_hash', '$taskID', '$time','$extension')";
						$imagesArray[] = array("oname"=>$imageName,"name_hash"=>$name_hash,"time"=>$time,"extension"=>$extension);
						if(!($conn -> query($sql))){
							echo $conn->error;
						}

					}

				}

				$task[] = array("folder_oname"=>$taskName, "folder_hash"=>$targetDirHash, "no_files"=>count($imagesArray), "files"=>$imagesArray);

			}
		}

	}


	$res = Array("no_tasks"=>count($task), "tasks"=>$task);

	$json = json_encode($res, JSON_PRETTY_PRINT);
	print "<pre>";
		print ($json);
	print "</pre>";
	closedir($initialDirHandler);

?>


