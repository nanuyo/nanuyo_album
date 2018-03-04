<?php

session_start();


require '../php/database.php';



if (isset($_SESSION['user_id'])) {
	$records = $conn -> prepare('SELECT user_id, email, password FROM users WHERE user_id = :user_id');
	$records -> bindParam(':user_id', $_SESSION['user_id']);
	$records -> execute();
	$results = $records -> fetch(PDO::FETCH_ASSOC);

	$user = NULL;

	if (count($results) > 0) {
		$user = $results;
	}
}
else
{
  $message = 'no user id';
}

if(!empty($_POST['email']) && !empty($_POST['password'])):
	
	$records = $conn->prepare('SELECT user_id, email, password FROM users WHERE email = :email');
	$records->bindParam(':email', $_POST['email']);
	$records->execute();
	$results = $records->fetch(PDO::FETCH_ASSOC);

	$message = '';
	

    if ($results['email'] != $_POST['email']) {
		$message = "등록되지 않은 사용자 입니다";
		goto SKIP;
	}
	
	 if( password_verify($_POST['password'], $results['password']) ) {
   // if( $_POST['password'] == $results['password'] ) {
		$_SESSION['user_id'] = $results['user_id'];
    $_SESSION['username'] = $results['email'];
    
		header("Location:  ../index.php");

	} else {
		$message = '비밀번호가 틀립니다, 다시입력해 주세요';
	}

endif;

SKIP:
?>

<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="../assets/ico/favicon.ico">

    <title>Signin Template for Bootstrap</title>

    <!-- Bootstrap core CSS -->
    <link href="../dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="login.css" rel="stylesheet">
  </head>

  <body class="text-center">
    <form class="form-signin" action="login.php" method="POST">
      <img class="mb-4" src="https://getbootstrap.com/assets/brand/bootstrap-solid.svg" alt="" width="72" height="72">
      <h1 class="h3 mb-3 font-weight-normal">Please sign in</h1>
      <label for="inputEmail" class="sr-only">Email address</label>
      <input type="email" name="email" id="inputEmail" class="form-control" placeholder="Email address" required autofocus>
      <label for="inputPassword" class="sr-only">Password</label>
      <input type="password" name="password" id="inputPassword" class="form-control" placeholder="Password" required>
      <div class="checkbox mb-3">
        <label>
          <input type="checkbox" value="remember-me"> Remember me
        </label>
      </div>
      <button class="btn btn-lg btn-primary btn-block" type="submit">Sign in</button>

      <a href="../subscribe/subscribe.php">새로 등록</a>

      <p class="mt-5 mb-3 text-muted">&copy; 2017-2018</p>
      <?php if(!empty($message)): ?>
		<p><?= $message ?></p>
  <?php endif; ?>

    </form>

    
  
  </body>
</html>
