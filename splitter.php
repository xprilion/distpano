<?php

	// Author: xmavericks, xprilion




	function splitH($imgName, $imgExt, $inPath, $outPath, $splits){

		//echo "<b><big>".$inPath."/".$imgName.".".$imgExt."</big></b>";

		$info = getimagesize($inPath."/".$imgName.".".$imgExt);
		$width=$info[0];
		$height=$info[1];

		if (!is_dir($outPath."/".$imgName)){
			mkdir($outPath."/".$imgName, 0777);
		}

		if (!is_dir($outPath."/".$imgName."/h")){
			mkdir($outPath."/".$imgName."/h", 0777);
		}

		if (!is_dir($outPath."/".$imgName."/v")){
			mkdir($outPath."/".$imgName."/v", 0777);
		}

		$canvasHeight=($height)/$splits;

		//echo $inPath."/".$imgName.".".$imgExt;

		$img="";

		switch(mime_content_type($inPath."/".$imgName.".".$imgExt)) {
			case 'image/png':
				$img = imagecreatefrompng($inPath."/".$imgName.".".$imgExt);
				break;
			case 'image/gif':
				$img = imagecreatefromgif($inPath."/".$imgName.".".$imgExt);
				break;
			case 'image/jpeg':
				$img = imagecreatefromjpeg($inPath."/".$imgName.".".$imgExt);
				break;
			case 'image/bmp':
				$img = imagecreatefrombmp($inPath."/".$imgName.".".$imgExt);
				break;
			default:
				$img = null;
		}

		for($n=1;$n<=$splits;$n++){

			$output = imagecreatetruecolor($width, $canvasHeight);
			$background = imagecolorallocate($output, 255, 255, 255);
			imagefill($output, 0, 0, $background);
			//echo "<br><span style='color:red;'><b>".$outPath."/".$imgName."/h/".$n.".".$imgExt."<b></span><br>";
			imagecopy($output, $img, 0,  0,      0, ($n-1)*($canvasHeight), $width, $canvasHeight);
			imagejpeg($output, $outPath."/".$imgName."/h/".$n.".".$imgExt);
		}

	}

	function splitV($imgName, $imgExt, $inPath, $outPath, $splits){

		$info = getimagesize($inPath."/".$imgName.".".$imgExt);
		$width=$info[0];
		$height=$info[1];

		if (!is_dir($outPath."/".$imgName)){
			mkdir($outPath."/".$imgName, 0777);
		}
		if (!is_dir($outPath."/".$imgName)){
			mkdir($outPath."/".$imgName."/h", 0777);
		}

		if (!is_dir($outPath."/".$imgName)){
			mkdir($outPath."/".$imgName."/v", 0777);
		}

		$canvasWidth=($width)/$splits;

		$img="";

		switch(mime_content_type($inPath."/".$imgName.".".$imgExt)) {
			case 'image/png':
				$img = imagecreatefrompng($inPath."/".$imgName.".".$imgExt);
				break;
			case 'image/gif':
				$img = imagecreatefromgif($inPath."/".$imgName.".".$imgExt);
				break;
			case 'image/jpeg':
				$img = imagecreatefromjpeg($inPath."/".$imgName.".".$imgExt);
				break;
			case 'image/bmp':
				$img = imagecreatefrombmp($inPath."/".$imgName.".".$imgExt);
				break;
			default:
				$img = null;
		}

		for($n=1;$n<=$splits;$n++){
			$output = imagecreatetruecolor($canvasWidth, $height);
			$background = imagecolorallocate($output, 255, 255, 255);
			imagefill($output, 0, 0, $background);
			imagecopy($output, $img, 0,  0,      ($n-1)*($canvasWidth), 0, $canvasWidth, $height);
			imagejpeg($output,$outPath."/".$imgName."/v/".$n.".".$imgExt);
		}

	}