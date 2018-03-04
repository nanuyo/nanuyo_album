<?php

	if (isset($_POST['getData'])) {

		require 'database.php';
		
		      
		$sql = $conn -> prepare("SELECT name, about FROM country LIMIT :start, :limit");
		$sql->bindValue(':start', (int) trim($_POST['start']), PDO::PARAM_INT);
		$sql->bindValue(':limit', (int) trim($_POST['limit']), PDO::PARAM_INT);
		$sql -> execute() or die(print_r($sql->errorInfo()));;
	    $count = $sql -> rowCount();
	
		if ($count) {
			$response = "";

			while($data = $sql->fetch()) {
				$response .= '
					<div>
						<h2>'.$data['name'].'</h2>
						<p>'.$data['about'].'</p>
					</div>
				';
			}

			exit($response);
		} else
			exit('reachedMax');
	}
?>