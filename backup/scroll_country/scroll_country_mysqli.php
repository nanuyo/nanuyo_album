<?php



	if (isset($_POST['getData'])) {

		
		$conn = new mysqli('localhost', 'junjun1971', 'Junjun1971', 'junjun1971');
		
		if ($conn->connect_errno) {
    // The connection failed. What do you want to do? 
    // You could contact yourself (email?), log the error, show a nice page, etc.
    // You do not want to reveal sensitive information

    // Let's try this:
    echo "Sorry, this website is experiencing problems.";

    // Something you should not do on a public site, but this example will show you
    // anyways, is print out MySQL error related information -- you might log this
    echo "Error: Failed to make a MySQL connection, here is why: \n";
    echo "Errno: " . $conn->connect_errno . "\n";
    echo "Error: " . $conn->connect_error . "\n";
    
    // You might want to show them something nice, but we will simply exit
    exit;
}


		$start = $conn->real_escape_string($_POST['start']);
		$limit = $conn->real_escape_string($_POST['limit']);

		$sql = $conn->query("SELECT name, about FROM country LIMIT $start, $limit");
		if ($sql->num_rows > 0) {
			$response = "";

			while($data = $sql->fetch_array()) {
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