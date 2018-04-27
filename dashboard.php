<?php

	include('head.php');
	$initialDir = "bin";
	$count=0;
	$count2=0;

	$initialDirHandler = opendir($initialDir);

	while ((($taskName = readdir($initialDirHandler)) !== false )){

		if(!(($taskName==".") || ($taskName==".."))){
			$count = $count+1;


			if($taskHandler = opendir($initialDir."/".$taskName) ){

				while (($imageName = readdir($taskHandler)) !== false){

					if (!(is_dir($initialDir."/".$taskName."/".$imageName))){

						$count2 = $count2 +1;

					}

				}

			}
		}

	}

	$initialDir = "images";
	$count11=0;
	$count12=0;

	$initialDirHandler = opendir($initialDir);

	while ((($taskName = readdir($initialDirHandler)) !== false )){

		if(!(($taskName==".") || ($taskName==".."))){
			$count11 = $count11 + 1;


			if($taskHandler = opendir($initialDir."/".$taskName) ){

				while (($imageName = readdir($taskHandler)) !== false){

					if (!(is_dir($initialDir."/".$taskName."/".$imageName))){

						$count12 = $count12 + 1;

					}

				}

			}
		}

	}

	$initialDir = "staged";
	$count21=0;
	$count22=0;

	$initialDirHandler = opendir($initialDir);

	while ((($taskName = readdir($initialDirHandler)) !== false )){

		if(!(($taskName==".") || ($taskName==".."))){
			$count21 = $count21 + 1;


			if($taskHandler = opendir($initialDir."/".$taskName) ){

				while (($imageName = readdir($taskHandler)) !== false){

					if ((is_dir($initialDir."/".$taskName."/".$imageName))){
						if(!(($imageName==".") || ($imageName==".."))){
							$count22 = $count22 + 1;
						}
					}

				}

			}
		}

	}


?>
<!DOCTYPEhtml>
<html>
	<head>
		<title>Uploader</title>
		<link rel="stylesheet" href="css/w3.css" />
		<link rel="stylesheet" href="https://www.w3schools.com/lib/w3-theme-indigo.css">
	</head>
	<body>
		<div class="w3-theme-d5 w3-container">
			<div class="w3-row "><br>
				<div class="w3-col l1">
					<img src="img/SIHlogo.png"  style="width:100%;">
				</div>
				<div class="w3-col l10 w3-container" style="">
					<h1>Panorama Creation using Distributed computing nodes</h1>

				</div>
				<div class="w3-col l1" >
					<img src="img/fusion2logo.png"  style="width:100%;">
				</div>
			</div><br>
		</div>
		<div class="w3-theme-d4">
			<div class="w3-row">
			  <div class="w3-col l6 w3-container">
				<form action="upload.php" method="post" enctype="multipart/form-data">
					<h2>Start the process</h2>
					<h5>Enter the Folder name:</h5>   <input type="text" name="Folder" />
					<br><h5>Filename:</h5><input type="file" name="file[]" id="file" multiple/> <br><br>

					<button class="w3-btn w3-black w3-round w3-margin-bottom" type="submit" name="submit" value="Submit" />Upload</button>
				</form>
			  </div>
			  <div class="w3-col l6">
				<h2>Data chart</h2>
				<table border=2 >
					<tr>
						<th>-</th>
						<th>No of Folders</th>
						<th>No of Files</th>
					</tr>
					<tr>
						<td>Bin</td>
						<td><?php echo $count  ?></td>
						<td><?php echo $count2  ?></td>
					</tr>
					<tr>
						<td>Images</td>
						<td><?php echo $count11  ?></td>
						<td><?php echo $count12  ?></td>
					</tr>
					<tr>
						<td>Staged</td>
						<td><?php echo $count22  ?></td>
						<td>-</td>
					</tr>
				</table>
			  </div>
			</div>
		</div>
		<div class="w3-theme-d3 w3-container">&nbsp;
			<h2>Done uploading??</h2>
			<div class="w3-row">



			 <?php
			if ($count2 == 0){
				echo '<div class="w3-col l2">
					<button class="w3-button w3-round-large w3-theme" disabled ><h3> Step 1 </h3></button>
					</div>';
			}
			else{
				echo '<div class="w3-col l2">
					<button class="w3-button w3-round-large w3-theme"><h3><a href="step1.php"> Step 1 </a></h3></button>
					</div>';
			}

			if ($count12 == 0){
				echo '<div class="w3-col l2">
					<button class="w3-button w3-round-large w3-theme" disabled ><h3> Step 2 </h3></button>
						</div>';
			}
			else{
				echo '<div class="w3-col l2">
						<button class="w3-button w3-round-large w3-theme"> <h3><a href="step2.php"> Step 2 </a></h3> </button>
					</div>';
			}

		?>
			</div><br>

		</div>

		<div class="w3-theme-d2">
			<div class="w3-row">
				<div class="w3-col l3"> &nbsp;</div>
				<h3><div class="w3-col l6 w3-center">
					Developed by :- <br>
					Anubhav Singh<br>
					Amod Kumar<br>
					Vipul Kumar<br>
					Shreya Paul<br>
					Reetika Gautam<br>
					Mayank Kumar Gupta<br>
				</div><h3>
				<h3><div class="w3-col l3 w3-center">
					Mentored by :- <br>
					Anupam Ghosh<br>
				</div><h3>

			</div>
		</div>


	</body>
</html>
