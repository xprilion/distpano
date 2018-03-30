<?php

	error_reporting(E_ALL);
	ini_set('display_errors', 1);


	function checkResult($taskid, $imgid1, $imgid2, $ext1, $ext2){

		include('head.php');


		$sql["getImgHhash"] = "SELECT * FROM pending WHERE id = '$imgid1'";
		$resHandle["getImgHhash"] = mysqli_query($conn, $sql["getImgHhash"]);
		$imghash1_r = mysqli_fetch_assoc($resHandle["getImgHhash"]);

		$imghash1 = $imghash1_r["name_hash"];


		$sql["getImgVhash"] = "SELECT * FROM pending WHERE id = '$imgid2'";
		$resHandle["getImgVhash"] = mysqli_query($conn, $sql["getImgVhash"]);
		$imghash2_r = mysqli_fetch_assoc($resHandle["getImgVhash"]);

		$imghash2 = $imghash2_r["name_hash"];


		$sql["getTaskHash"] = "SELECT * FROM tasks WHERE taskid = '$taskid'";
		$resHandle["getTaskHash"] = mysqli_query($conn, $sql["getTaskHash"]);
		$thash = mysqli_fetch_assoc($resHandle["getTaskHash"]);
		$taskhash = $thash["hashname"];

		// echo "images/".$taskhash."/".$imghash1.".".$ext1;

		list($width1, $height1) = getimagesize("images/".$taskhash."/".$imghash1.".".$ext1);
		list($width2, $height2) = getimagesize("images/".$taskhash."/".$imghash2.".".$ext2);


		// print_r($size1);
		// print_r($size2);

		echo $width1.", ".$height1; echo "<br>";
		echo $width2.", ".$height2;


		$fetchForImagePair = "SELECT * FROM action WHERE taskid = '$taskid' AND imgid1 = '$imgid1' AND imgid2 = '$imgid2' AND status = '2'";

		$resHandler = mysqli_query($conn, $fetchForImagePair);

		$image1 = imagecreatefromjpeg("images/".$taskhash."/".$imghash1.".".$ext1);
		$image2 = imagecreatefromjpeg("images/".$taskhash."/".$imghash2.".".$ext2);

		$ellipseColor = imagecolorallocate($image1, 255, 0, 0);

		if(mysqli_num_rows($resHandler) < 3){

			return;
		}
		else{

			while($res = mysqli_fetch_assoc($resHandler)){

				if ($res["result"] != '0'){
					print "<pre>";
					print_r($res);
					print "</pre>";

					$controlPoints = explode(";", $res["result"]);
					unset($controlPoints[count($controlPoints)-1]);

					print_r($controlPoints);

					// Output the image.

					foreach($controlPoints as $cp){

			            $pts = explode("|", $cp);

			            $ax1 = explode(",", $pts[0]);
			            $ax2 = explode(",", $pts[1]);

			            print_r($ax1);

			            imagefilledellipse($image1, $ax1[0], $ax1[1], 50, 50, $ellipseColor);
			            imagefilledellipse($image2, $ax1[0], $ax2[1], 50, 50, $ellipseColor);

			        }

				}

			}


		}

		imagejpeg($image1, "results/".$imghash1.".jpg");
		imagejpeg($image2, "results/".$imghash2.".jpg");
	}


	function stitchQuick($taskid, $imgid1, $imgid2, $ext1, $ext2){
		include('head.php');


		$sql["getImgHhash"] = "SELECT * FROM pending WHERE id = '$imgid1'";
		$resHandle["getImgHhash"] = mysqli_query($conn, $sql["getImgHhash"]);
		$imghash1_r = mysqli_fetch_assoc($resHandle["getImgHhash"]);

		$imghash1 = $imghash1_r["name_hash"];


		$sql["getImgVhash"] = "SELECT * FROM pending WHERE id = '$imgid2'";
		$resHandle["getImgVhash"] = mysqli_query($conn, $sql["getImgVhash"]);
		$imghash2_r = mysqli_fetch_assoc($resHandle["getImgVhash"]);

		$imghash2 = $imghash2_r["name_hash"];


		$sql["getTaskHash"] = "SELECT * FROM tasks WHERE taskid = '$taskid'";
		$resHandle["getTaskHash"] = mysqli_query($conn, $sql["getTaskHash"]);
		$thash = mysqli_fetch_assoc($resHandle["getTaskHash"]);
		$taskhash = $thash["hashname"];

		$res1 = "results/".$imghash1.".jpg";
		$res2 = "results/".$imghash2.".jpg";

		$createPto = shell_exec('pto_gen -o /home/xprilion/webroot/sih18server/results/project.pto /home/xprilion/webroot/sih18server/'.$res1.' /home/xprilion/webroot/sih18server/'.$res2);

	}

	checkResult(2, 1, 2, "JPG", "JPG");
	stitchQuick(2, 1, 2, "JPG", "JPG");

	// india@123