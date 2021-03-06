<?php
/**
* Multi file upload example
* @author Resalat Haque
* @link http://www.w3bees.com/2013/02/multiple-file-upload-with-php.html
**/
include('connection.php');
$valid_formats = array("jpg", "png", "gif", "zip", "bmp");
$max_file_size = 1024*10000; //100 kb
$path = "uploads/"; // Upload directory
$count = 0;

if(isset($_POST) and $_SERVER['REQUEST_METHOD'] == "POST"){
	// Loop $_FILES to execute all files
	foreach ($_FILES['files']['name'] as $f => $name) {   
	    if ($_FILES['files']['error'][$f] == 4) {
	        continue; // Skip file if any error found
	    }	       
	    if ($_FILES['files']['error'][$f] == 0) {	           
	        if ($_FILES['files']['size'][$f] > $max_file_size) {
	            $message[] = "$name is too large!.";
	            continue; // Skip large files
	        }
			elseif( ! in_array(pathinfo($name, PATHINFO_EXTENSION), $valid_formats) ){
				$message[] = "$name is not a valid format";
				continue; // Skip invalid file formats
			}
	        else{ // No error found! Move uploaded files 
			    mysql_query('INSERT INTO images (images) VALUES("'.$name.'")',$connection);
	            if(move_uploaded_file($_FILES["files"]["tmp_name"][$f], $path.$name)) {
	            	$count++; // Number of successfully uploaded files
	            }
	        }
	    }
	}
}
?>

<!doctype html>
<html lang="en">
<head>
	
	<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
	<script src="script.js"></script>


</head>
<body>
	<div class="wrap">
		<?php
		# error messages
		if (isset($message)) {
			foreach ($message as $msg) {
				printf("<p class='status'>%s</p></ br>\n", $msg);
			}
		}
		# success message
		if($count !=0){
			printf("<p class='status'>%d files added successfully!</p>\n", $count);
		}
		?>
		<p>Max file size 100kb, Valid formats jpg, png, gif</p>
		<br />
		<br />
		<!-- Multiple file upload html form-->
		<form action="" method="post" enctype="multipart/form-data">
			<!--<div id="filediv"><input type="file" name="files[]" multiple="multiple"></div>-->
			<div id="filediv"><input type="file" name="files[]" id="file"></div>
			<input type="button" id="add_more" class="upload" value="Add More Files"/>
			<input type="submit" value="Upload">
		</form>
</div>
</body>
</html>