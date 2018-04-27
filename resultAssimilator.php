<?php

	// error_reporting(E_ALL);
	// ini_set('display_errors', 1);


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

		if(mysqli_num_rows($resHandler) < 10){

			return false;
		}
		else{

			$counter = 0;

			while($res = mysqli_fetch_assoc($resHandler)){

				$counter ++;

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



			// imagejpeg($image1, "VisualLogs/".$imghash1.".jpg");
			// imagejpeg($image2, "VisualLogs/".$imghash2.".jpg");

			return true;

		}
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

		$res1 = "images/".$taskhash."/".$imghash1.".JPG";
		$res2 = "images/".$taskhash."/".$imghash2.".JPG";


		$createPto = shell_exec('cpto_gen -o /home/xprilion/webroot/distpano/results/project.pto /home/xprilion/webroot/distpano/'.$res1.' /home/xprilion/webroot/distpano/'.$res2);


		// $output = "<pre>".shell_exec("./runner.sh")."</pre>";
		// echo $output;

		shell_exec("ccpclean -o /home/xprilion/webroot/distpano/results/project.pto /home/xprilion/webroot/distpano/results/project.pto");
		shell_exec("clinefind -o /home/xprilion/webroot/distpano/results/project.pto /home/xprilion/webroot/distpano/results/project.pto");
		shell_exec("cautooptimiser -a -m -l -s -o /home/xprilion/webroot/distpano/results/project.pto /home/xprilion/webroot/distpano/results/project.pto");
		shell_exec("cpano_modify --canvas=AUTO --crop=AUTO -o /home/xprilion/webroot/distpano/results/project.pto /home/xprilion/webroot/distpano/results/project.pto");
		shell_exec("chugin_executor --stitching --prefix=prefix /home/xprilion/webroot/distpano/results/project.pto");

		$sql = "UPDATE staged SET status = '2' WHERE img_id = '$imgid1'";
		mysqli_query($conn, $sql);

		$sql = "UPDATE staged SET status = '2' WHERE img_id = '$imgid2'";
		mysqli_query($conn, $sql);

		copy('/home/xprilion/Videos/project.pto', '/home/xprilion/webroot/distpano/results/project.pto');
		copy('/home/xprilion/Videos/output.pto', '/home/xprilion/webroot/distpano/results/output.pto');
		copy('/home/xprilion/Videos/out.tif', '/home/xprilion/webroot/distpano/results/'.$taskhash.'.tif');
		copy('/home/xprilion/Videos/img1.jpg', '/home/xprilion/webroot/distpano/VisualLogs/'.$imghash1.'.jpg');
		copy('/home/xprilion/Videos/img2.jpg', '/home/xprilion/webroot/distpano/VisualLogs/'.$imghash2.'.jpg');

	}

	// checkResult(2, 1, 2, "JPG", "JPG");
	// stitchQuick(2, 1, 2, "JPG", "JPG");

	// india@123