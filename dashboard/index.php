<!DOCTYPEhtml>
<html>
	<head>
		<title>Dashboard</title>
		<link rel="stylesheet" href="css/w3.css" />
		<link rel="stylesheet" href="https://www.w3schools.com/lib/w3-theme-indigo.css">
		<script src="jquery.min.js"></script>
		<script>

			function checkBin(){
				$.get("countBin.php",
				{
				    out: "true"
				},
				function(data, status){
				    $('#binCount').empty().append(data);
				    if(data==0){
				    	$('#step1btn').prop("disabled", true);
				    }
				    else{
				    	$('#step1btn').prop("disabled", false);
				    }
				});
			}

			function checkImages(){
				$.get("countImages.php",
				{
				    out: "true"
				},
				function(data, status){
				    $('#imagesCount').empty().append(data);
				    if(data==0){
				    	$('#step2btn').prop("disabled", true);
				    }
				    else{
				    	$('#step2btn').prop("disabled", false);
				    }
				});
			}


			function checkStaged(){
				$.get("countStaged.php",
				{
				    out: "true"
				},
				function(data, status){
				    $('#stagedCount').empty().append(data);
				});
			}

			function checkClients(){
				$.get("countClients.php",
				{
				    out: "true"
				},
				function(data, status){
					//alert(data);
					var res = data.split(",");
				    $('#totalClients').empty().append(res[0]);
				    $('#activeClients').empty().append(res[1]);
				});
			}


			function goStep1(){
				$.get("../step1.php",
				{
				    out: "true"
				},
				function(data, status){
					//alert(data);
					console.log(data);
					checkBin();
					checkImages();
				});
			}

			function goStep2(){
				$.get("../step2.php",
				{
				    out: "true"
				},
				function(data, status){
					//alert(data);
					console.log(data);
					checkImages();
					checkStaged();
				});
			}

			setInterval(function(){ checkClients(); }, 1000);

		</script>
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
					<h2>Upload Tasks</h2>
					<h5>Enter the Folder name:</h5>   <input type="text" name="Folder" />
					<br><h5>Filename:</h5><input type="file" name="file[]" id="file" multiple/> <br><br>

					<button class="w3-btn w3-black w3-round w3-margin-bottom" type="submit" name="submit" value="Submit" />Upload</button>
				</form>
			  </div>
			  <div class="w3-col l6">

			  <?php

			  	include('../head.php');

			  	include('countBin.php');
			  	include('countImages.php');
			  	include('countStaged.php');
			  	include('countClients.php');

			  ?>


				<h2>Data chart</h2>
				<table border=2 >
					<tr>
						<th>-</th>
						<th>No of Files</th>
						<th>Action</th>
					</tr>
					<tr>
						<td>Bin</td>
						<td  id="binCount"><?php echo $binFileCount;  ?></td>
						<td><button class="w3-button w3-theme" id="step1btn" onclick="goStep1();" <?php if($binFileCount == 0){echo "disabled";} ?> >Queue</button>
						</td>
					</tr>
					<tr>
						<td>Images</td>
						<td id="imagesCount"><?php echo $imgCount;  ?></td>
						<td><button class="w3-button  w3-theme" id="step2btn" onclick="goStep2();"  <?php if($imgCount == 0){echo "disabled";} ?> >Stage</button></td>
					</tr>
					<tr>
						<td>Staged</td>
						<td id="stagedCount"><?php echo $stagedCount;  ?></td>
						<td></td>
					</tr>

					<tr>
						<th>-</th>
						<th>Total</th>
						<th>Active</th>
					</tr>
					<tr>
						<td>Clients</td>
						<td  id="totalClients"><?php echo $totalClientsCount;  ?></td>
						<td  id="activeClients"><?php echo $activeClientsCount; ?></td>
					</tr>

				</table>

				<button class="w3-button w3-theme" onclick="checkImages(); checkBin(); checkStaged(); checkClients(); ">Refresh</button>

			  </div>
			</div>

	</body>
</html>
