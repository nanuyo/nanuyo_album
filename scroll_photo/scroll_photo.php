<?php

	if (isset($_POST['getData'])) {

		require '../php/database.php';
		
		      
		$sql = $conn -> prepare("SELECT title, url, user_id, description FROM files LIMIT :start, :limit");
		$sql->bindValue(':start', (int) trim($_POST['start']), PDO::PARAM_INT);
		$sql->bindValue(':limit', (int) trim($_POST['limit']), PDO::PARAM_INT);
		$sql -> execute() or die(print_r($sql->errorInfo()));;
	    $count = $sql -> rowCount();
	
		if ($count) {
			$response = "";

			while($data = $sql->fetch()) {
				$response .= '
				<div class="col-md-4 " >
				<div class="card mb-4 box-shadow">
						
						<img id="pic1" class="card-img-top" src='.$data['url'].' data-src="holder.js/100px225?theme=thumb&bg=55595c&fg=eceeef&text=Thumbnail" alt="Card image cap">
						<div class="card-body">
						<h2>'.$data['title'].'</h2>
						<p class="card-text">'.$data['description'].'</p>
						<div class="d-flex justify-content-between align-items-center">
						  <div class="btn-group">
							<button type="button" class="btn btn-sm btn-outline-secondary">View</button>
							<button type="button" class="btn btn-sm btn-outline-secondary">Edit</button>
								</div>
						  <small class="text-muted">9 mins</small>
						</div>
					  </div>
				</div>
				</div>
				';
			}

			exit($response);
		} else
			exit('reachedMax');
	}
?>