<?php

session_start();

//echo $_SERVER['HTTP_REFERER'] ;


if (!isset($_SESSION['user_id'])) {
	header("Location: ../login/login.php");
}




if(isset($_POST['submit'])) 
{
	require '../php/database.php';
	
	// $file = $_FILES['files'];
	// print_r($file);

    $user_id = $_SESSION['user_id'];
	$fileName = $_FILES['files']['name'];
	$fileTmpName = $_FILES['files']['tmp_name'];
	$fileSize = $_FILES['files']['size'];
	$fileError = $_FILES['files']['error'];
	$fileType = $_FILES['files']['type'];

	$fileExt = explode('.',$fileName);
	$fileActualExt = strtolower(end($fileExt));
	$allowed = array('jpg', 'jpeg', 'png', 'pdf');
	if(in_array($fileActualExt, $allowed)){
		if($fileError === 0){
			if($fileSize < 10000000) {
				$fileNameNew = uniqid('',true).".".$fileActualExt;
				$fileDestination = '../uploads/'.$fileNameNew;
				move_uploaded_file($fileTmpName, $fileDestination);
				$sql = "INSERT INTO files (user_id, name, size, type, url, title, description) VALUES (:user_id, :fileNameNew, :fileSize, :fileType, :fileDestination, :fileName, :fileTmpName )";

                $stmt = $conn -> prepare($sql);
                $stmt -> bindParam(':user_id', $user_id);
				$stmt -> bindParam(':fileNameNew', $fileNameNew);
				$stmt -> bindParam(':fileSize', $fileSize);
				$stmt -> bindParam(':fileType',  $fileType);
				$stmt -> bindParam(':fileDestination', $fileDestination);
				$stmt -> bindParam(':fileName',  $fileName);
				$stmt -> bindParam(':fileTmpName',  $fileTmpName);
				$stmt -> execute();


				header("Location: ../index.php?uploadsuccess");
			}else{
				echo "Your files is too big!";
			}
			
		}else {
			echo " There was an error";
		}
	}else{
		echo "You can not upload files of this type";
	}
}
?>

<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="../assets/ico/favicon.ico">

    <title>Upload photo</title>

    <!-- Bootstrap core CSS -->
    <link href="../dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom styles for this template -->
	<link href="https://fonts.googleapis.com/css?family=Playfair+Display:700,900" rel="stylesheet">
    <link href="../album.css" rel="stylesheet">
  </head>

  <body class="text-center">
    <div class="container">
        <div class="row">
            <div class="col-md-4">
                <div class="card mb-4 box-shadow">

                    <img id="pic1" class="card-img-top" data-src="holder.js/100px225?theme=thumb&bg=55595c&fg=eceeef&text=Thumbnail" alt="Card image cap">
                                
                    <form action="upload.php", method="post" enctype="multipart/form-data">
                        <input onchange="readURL(this);" type="file" value="select" name="files" >
                        <button type="submit" name="submit" class="btn btn-sm btn-outline-secondary">Submit</button>
                        <a class="btn btn-sm btn-outline-secondary" href="../index.php">Cancel</a>                  
                    </form>

                </div>
            </div>
        </div>
    </div>
    
    

					
		 <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
    <script>window.jQuery || document.write('<script src="../assets/js/vendor/jquery-slim.min.js"><\/script>')</script>
    <script src="../assets/js/vendor/popper.min.js"></script>
    <script src="../dist/js/bootstrap.min.js"></script>
    <script src="../assets/js/vendor/holder.min.js"></script>
    <script>
      Holder.addTheme('thumb', {
        bg: '#55595c',
        fg: '#eceeef',
        text: 'Thumbnail'
      });
	  
	  function readURL(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();

            reader.onload = function (e) {
                $('#pic1')
                    .attr('src', e.target.result)
                    .width(350)
                    .height(200);
            };

            reader.readAsDataURL(input.files[0]);
        }
    }
    </script>
  </body>
</html>
