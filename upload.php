<?php
    if(isset($_FILES['file']['tmp_name']))
    {
		$target_dir = $_POST['Folder'];
        // Number of uploaded files
        $num_files = count($_FILES['file']['tmp_name']);
		$time = time();
		if (empty($_POST["Folder"])) {
			$target_dir = $time;
		}
		
        /** loop through the array of files ***/
        for($i=0; $i < $num_files;$i++)
        {
			$target_file =  $_FILES["file"]["name"][$i];
			$allow=array('jpg','jpeg','png','bmp','tiff');
			echo $target_file;
			$FileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
			
            // check if there is a file in the array
            if(!in_array(strtolower($FileType), $allow)){
				echo " error in uploading<br> ";
				exit;
			}
            else
            {
                // copy the file to the specified dir 
                if(file_exists('bin/'.$target_dir)){
					@copy($_FILES['file']['tmp_name'][$i],'bin/'.$target_dir.'/'.$_FILES['file']['name'][$i]);
					echo " Uploaded in existing directory<br> ";
                }
                else
                {
                    mkdir('bin/'.$target_dir);
					@copy($_FILES['file']['tmp_name'][$i],'bin/'.$target_dir.'/'.$_FILES['file']['name'][$i]);
					echo " Uploaded in a new directory<br> ";
                }
            }
        }
    }
?>