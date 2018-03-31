<?php

	$initialDir = "../bin";

	$binCount=0;
	$binFileCount=0;

	$initialDirHandler = opendir($initialDir);

	while ((($taskName = readdir($initialDirHandler)) !== false )){

		if(!(($taskName==".") || ($taskName==".."))){
			$binCount ++;


			if($taskHandler = opendir($initialDir."/".$taskName) ){

				while (($imageName = readdir($taskHandler)) !== false){

					if (!(is_dir($initialDir."/".$taskName."/".$imageName))){

						$binFileCount++;

					}

				}

			}
		}

	}

	if(isset($_GET["out"])){
		echo $binFileCount;
	}



?>