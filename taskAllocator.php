<?php

	//Author: xprilion

	//Algorithm: Task allocator

	/////////////////////////////// OUTLINE ///////////////////////////////////
	/*
	*	1. Fetch two unqiue images of same collection
	*		a. get an image from 'staged' where status is 0
	*		b. get another image from 'staged' where status is 0 such that all
	*			images h-v pairs are not exhausted in 'action'
	*
	*	2. determine central-most strips to match. Return h-v pair.
	*
	*	3. img1 -> image with h strip
	*	   img2 -> image with v strip
	*
	*	4.
	*
	*
	*
	*
	*
	*
	*/
	///////////////////////////////////////////////////////////////////////////

	///////////////////////////// CONVENTIONS /////////////////////////////////
	/*
	*	The image with larger imageID is the V image. The one with smaller
	*	imageID is always the H image. This simplifies things a lot. Trust me.
	*
	*
	*/
	///////////////////////////////////////////////////////////////////////////

	// error_reporting(E_ALL);
	// ini_set('display_errors', 1);

	include('head.php'); // Include the DB connection
	include('functions.php'); // Include the powerful functions ripped straight off stackoverflow <3

	$debug = false;

	// Step 1

	$sql["getImg1"] = "SELECT * FROM staged WHERE status = '0'";

	if(!$resHandle["getImg1"] = mysqli_query($conn, $sql["getImg1"])){
		echo mysqli_error($conn);
	}

	if(mysqli_num_rows($resHandle["getImg1"]) != 0){

		$img1 = mysqli_fetch_assoc($resHandle["getImg1"]);

		$taskID = $img1["task_id"];

		$stage1ID = $img1["stage_id"];

		$img1ID = $img1["img_id"];

		$totalSplits1["h"] = $img1["h_splits"];
		//$totalSplits1["v"] = $img1["v_splits"];

		$img1_h_avail = Array();
		//$img1_v_avail = Array();


		$a = 1;
		$b = 1;

		$maxa = 9;
		$maxb = 9;

		$seqa = Array();
		$seqb = Array();

		$seqa[] = round($maxa/2);
		$now = round($maxa/2);

		$seqb[] = round($maxb/2);
		$now = round($maxb/2);

		for($i=0;$i<=$maxa/2;$i++){
			$img1_h_avail[] = $now + $i;
			$img1_h_avail[] = $now - $i;
		}

		// for($i=0;$i<$totalSplits1["v"];$i++){
		// 	$img1_v_avail[] = $i+1;
		// }

		if($debug){
			print_r($img1);
		}
		// echo "<br>";
		// print_r($img1_h_avail);
		// echo "<br>";
		// print_r($img1_v_avail);

		if($debug){
			echo "<br>";
		}


		$sql["getImg2"] = "SELECT * FROM staged WHERE status = 0  AND task_id = '$taskID' AND stage_id != '$stage1ID'";
		$resHandle["getImg2"] = mysqli_query($conn, $sql["getImg2"]);

		$imgHID = -1;
		$imgVID = -1;

		while($img2 = mysqli_fetch_assoc($resHandle["getImg2"])){

			$img2ID = $img2["img_id"];

			//$totalSplits2["h"] = $img2["h_splits"];
			$totalSplits2["v"] = $img2["v_splits"];

			//$img2_h_avail = Array();
			$img2_v_avail = Array();

			// for($i=0;$i<$totalSplits2["h"];$i++){
			// 	$img2_h_avail[] = $i+1;
			// }



			for($i=0;$i<=$maxa/2;$i++){
				$img2_v_avail[] = $now + $i;
				$img2_v_avail[] = $now - $i;
			}

			if($debug){
				print_r($img2);
			}
			// echo "<br>";
			// print_r($img2_h_avail);
			// echo "<br>";
			// print_r($img2_v_avail);
			if($debug){
				echo "<hr>";
			}

			////////////////////////////////  TIME TO CHECK LARGER AND SWAP IF NEEDED!!

			$imgH = Array(); $imgHID = -1; $img_h_avail = Array();
			$imgV = Array(); $imgVID = -1; $img_v_avail = Array();

			if($img1ID > $img2ID){
				$imgHID = $img2ID;
				$imgH = $img2;
				$img_h_avail = $img2_v_avail;

				$imgVID = $img1ID;
				$imgV = $img1;
				$img_v_avail = $img1_h_avail;
			}
			else{
				$imgHID = $img1ID;
				$imgH = $img1;
				$img_h_avail = $img1_h_avail;

				$imgVID = $img2ID;
				$imgV = $img2;
				$img_v_avail = $img2_v_avail;
			}


			$allPairs = cartesian(Array($img_h_avail, $img_v_avail));

			//print_r($allPairs);

			$hashPairs = Array();

			for($i=0;$i<count($allPairs);$i++){
				$hash = "";
				$hash .= $allPairs[$i][0]."-".$allPairs[$i][1];
				$hashPairs[$hash] = 0;
			}

			if($debug){

				print_r($hashPairs);

				echo "<br><b>";
				echo count($hashPairs);
				echo "</b>";

			}

			$sql["getHV"] = "SELECT * FROM action WHERE (imgid1 = '$imgHID' AND imgid2 = '$imgVID')";

			$resHandle["getHV"] = mysqli_query($conn, $sql["getHV"]);

			if($debug){
				echo "<hr>";
			}

			while($hv = mysqli_fetch_assoc($resHandle["getHV"])){

				if($debug){
					print_r($hv);
				}

				$_img1id = $hv["imgid1"];
				$_img2id = $hv["imgid2"];

				$_h = $hv["h"];
				$_v = $hv["v"];

				$hash = "";
				$hash .= $_h."-".$_v;

				$hashPairs[$hash] = 1;

				if($debug){
					echo "<br>";
				}
			}

			if($debug){
				echo "<hr>";

				print_r($hashPairs);

				echo "<br><b>";
				echo count($hashPairs);
				echo "</b>";
			}

			if($debug){
				echo "<hr>";

				foreach($hashPairs as $hPk => $hPv){
					if($hPv==1){
						echo "found";
						echo " | ".$hPk;
						echo "<br>";
					}
				}

				echo "<br><br>";
			}

			$outH = -1; $outV = -1;

			foreach($hashPairs as $hPk => $hPv){
				if($hPv==0){


					if($debug){
						echo "found";
						echo " | ".$hPk;
						echo "<br>";
					}

					$hpos = strpos($hPk, "-");

					// echo "".$hpos."";

					$outH = substr($hPk, 0, $hpos);
					$outV = substr($hPk, $hpos+1);

					break;
				}
			}

			if($outH != -1 && $outV != -1){
				break;
			}

		}


		if($debug){
			echo "<hr>";
		}

		$sql["getImgHhash"] = "SELECT * FROM pending WHERE id = $imgHID";
		$resHandle["getImgHhash"] = mysqli_query($conn, $sql["getImgHhash"]);
		$img1hash = mysqli_fetch_assoc($resHandle["getImgHhash"]);

		$sql["getImgVhash"] = "SELECT * FROM pending WHERE id = $imgVID";
		$resHandle["getImgVhash"] = mysqli_query($conn, $sql["getImgVhash"]);
		$img2hash = mysqli_fetch_assoc($resHandle["getImgVhash"]);

		$sql["getTaskHash"] = "SELECT * FROM tasks WHERE taskid = $taskID";
		$resHandle["getTaskHash"] = mysqli_query($conn, $sql["getTaskHash"]);
		$thash = mysqli_fetch_assoc($resHandle["getTaskHash"]);

		$thash = $thash["hashname"];

		$res = Array("status"=>0, "msg" => Array("taskhash"=>$thash, "imghash1"=>$img1hash["name_hash"], "imghash2"=>$img2hash["name_hash"], "h"=>$outH, "v"=>$outV, "ext1"=>$img1hash["ext"], "ext2"=>$img2hash["ext"]));

	}
	else{
		$res = Array("status"=>9);
	}

	$json = json_encode($res);
	echo $json;

?>