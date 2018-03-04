<?php

session_start();

require '../php/database.php';

$message = '';

// if (!isset($_SESSION['user_id'])) {
// 	header("Location: login.php");
// }

if (isset($_POST['cancel'])) {
	header("Location: ../index.php");
}

if (empty($_POST['email'])) {
	$message = '사용자명을 입력해 주세요';
	goto SKIP;
} else {
	$records = $conn -> prepare('SELECT user_id, email,password FROM users WHERE email = :email');
	$records -> bindParam(':email', $_POST['email']);
	$records -> execute();
	$results = $records -> fetch(PDO::FETCH_ASSOC);

	if ($results['email'] == $_POST['email']) {
		$message = "이미등록된 사용자입니다";
		goto SKIP;
	}
}

if (empty($_POST['password'])) {
	$message = '비밀번호를 입력해 주세요';
	goto SKIP;
}

if (empty($_POST['confirm_password'])) {
	$message = '재확인 비밀번호를 입력해 주세요';
	goto SKIP;
}

if ($_POST['password'] != $_POST['confirm_password']) {
	$message = '재확인 비밀번호가 같지 않습니다. 다시입력하세요';
	goto SKIP;
}

if (!empty($_POST['email']) && !empty($_POST['password']) && !empty($_POST['confirm_password'])) {
		// Enter the new user in the database
			$sql = "INSERT INTO users (email, password) VALUES (:email, :password)";
			$stmt = $conn -> prepare($sql);

			$stmt -> bindParam(':email', $_POST['email']);
      $stmt -> bindParam(':password', password_hash($_POST['password'], PASSWORD_BCRYPT));
     // $stmt -> bindParam(':password', $_POST['password']);

			if ($stmt -> execute()) {
				$records = $conn -> prepare('SELECT user_id,email,password FROM users WHERE email = :email');
				$records -> bindParam(':email', $_POST['email']);
				$records -> execute();
				$results = $records -> fetch(PDO::FETCH_ASSOC);

				$message = '';
				$_SESSION['user_id'] = $results['user_id'];
				$_SESSION['username'] = $results['email'];
				header("Location: ../index.php");
			} else {
				$message = '사용자 등록이 실패했습니다. 다시 등록하세요';
			}


} else {
	$message = '3항목 모두 입력해 주세요';
}

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

    <title>Subscribe</title>

    <!-- Bootstrap core CSS -->
    <link href="../dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="form-validation.css" rel="stylesheet">
  </head>

  <body class="bg-light">

    <div class="container">
      <div class="py-5 text-center">
        <img class="d-block mx-auto mb-4" src="https://getbootstrap.com/assets/brand/bootstrap-solid.svg" alt="" width="72" height="72">
        <h2>Subscribe</h2>
        <p class="lead">Below is an example form built entirely with Bootstrap's form controls. Each required form group has a validation state that can be triggered by attempting to submit the form without completing it.</p>
      </div>

      <!-- <div class="row"> -->
        <!-- <div class="col-md-4 order-md-2 mb-4"> -->
          <!-- <h4 class="d-flex justify-content-between align-items-center mb-3"> -->
            <!-- <span class="text-muted">Your cart</span> -->
            <!-- <span class="badge badge-secondary badge-pill">3</span> -->
          <!-- </h4> -->
          <!-- <ul class="list-group mb-3"> -->
            <!-- <li class="list-group-item d-flex justify-content-between lh-condensed"> -->
              <!-- <div> -->
                <!-- <h6 class="my-0">Product name</h6> -->
                <!-- <small class="text-muted">Brief description</small> -->
              <!-- </div> -->
              <!-- <span class="text-muted">$12</span> -->
            <!-- </li> -->
            <!-- <li class="list-group-item d-flex justify-content-between lh-condensed"> -->
              <!-- <div> -->
                <!-- <h6 class="my-0">Second product</h6> -->
                <!-- <small class="text-muted">Brief description</small> -->
              <!-- </div> -->
              <!-- <span class="text-muted">$8</span> -->
            <!-- </li> -->
            <!-- <li class="list-group-item d-flex justify-content-between lh-condensed"> -->
              <!-- <div> -->
                <!-- <h6 class="my-0">Third item</h6> -->
                <!-- <small class="text-muted">Brief description</small> -->
              <!-- </div> -->
              <!-- <span class="text-muted">$5</span> -->
            <!-- </li> -->
            <!-- <li class="list-group-item d-flex justify-content-between bg-light"> -->
              <!-- <div class="text-success"> -->
                <!-- <h6 class="my-0">Promo code</h6> -->
                <!-- <small>EXAMPLECODE</small> -->
              <!-- </div> -->
              <!-- <span class="text-success">-$5</span> -->
            <!-- </li> -->
            <!-- <li class="list-group-item d-flex justify-content-between"> -->
              <!-- <span>Total (USD)</span> -->
              <!-- <strong>$20</strong> -->
            <!-- </li> -->
          <!-- </ul> -->

          <!-- <form class="card p-2"> -->
            <!-- <div class="input-group"> -->
              <!-- <input type="text" class="form-control" placeholder="Promo code"> -->
              <!-- <div class="input-group-append"> -->
                <!-- <button type="submit" class="btn btn-secondary">Redeem</button> -->
              <!-- </div> -->
            <!-- </div> -->
          <!-- </form> -->
        <!-- </div> -->
        <div class="col-md-8 order-md-1">
          <h4 class="mb-3">Your information</h4>

          
		  <form class="needs-validation" action="subscribe.php" method="POST" novalidate>
 
<!--  
			<div class="row">
              <div class="col-md-6 mb-3">
                <label for="firstName">First name</label>
                <input type="text" class="form-control" id="firstname" name="firstname" placeholder="" value="" required>
                <div class="invalid-feedback">
                  Valid first name is required.
                </div>
              </div>
              <div class="col-md-6 mb-3">
                <label for="lastName">Last name</label>
                <input type="text" class="form-control" id="lastname" name="lastname" placeholder="" value="" required>
                <div class="invalid-feedback">
                  Valid last name is required.
                </div>
              </div>
            </div>

            <div class="mb-3">
              <label for="username">Username</label>
              <div class="input-group">
                <div class="input-group-prepend">
                  <span class="input-group-text">@</span>
                </div>
                <input type="text" class="form-control" id="username" placeholder="Username" required>
                <div class="invalid-feedback" style="width: 100%;">
                  Your username is required.
                </div>
              </div>
            </div>
-->
            <div class="mb-3">
              <!--<label for="email">Email <span class="text-muted">(Optional)</span></label>-->
			  <label for="email">Email</label>
              <input type="email" class="form-control" id="email" name="email" placeholder="you@example.com">
              <div class="invalid-feedback">
                Please enter a valid email address for shipping updates.
              </div>
            </div>
			
			<div class="mb-3">
			  <label for="password">Password</label>
              <input type="password" class="form-control" id="password" name="password" placeholder="">
              <div class="invalid-feedback">
                Please enter your password.
              </div>
            </div>
			
			<div class="mb-3">
			  <label for="confirm_password">Confirm Password</label>
              <input type="password" class="form-control" id="confirm_password" name="confirm_password" placeholder="">
              <div class="invalid-feedback">
                Please enter your password again.
              </div>
            </div>

            <!-- <div class="mb-3"> -->
              <!-- <label for="address">Address</label> -->
              <!-- <input type="text" class="form-control" id="address" placeholder="1234 Main St" required> -->
              <!-- <div class="invalid-feedback"> -->
                <!-- Please enter your shipping address. -->
              <!-- </div> -->
            <!-- </div> -->

            <!-- <div class="mb-3"> -->
              <!-- <label for="address2">Address 2 <span class="text-muted">(Optional)</span></label> -->
              <!-- <input type="text" class="form-control" id="address2" placeholder="Apartment or suite"> -->
            <!-- </div> -->

<!--
			<div class="mb-3">
              <label for="mobile">Mobile</label>
              <input type="text" class="form-control" id="mobile" placeholder="+82-10-xxxx-xxxx" required>
              <div class="invalid-feedback">
                Please enter your mobile number.
              </div>
            </div>
			
            <div class="row">
              <div class="col-md-5 mb-3">
                <label for="country">Country</label>
                <select class="custom-select d-block w-100" id="country" required>
                  <option value="">Choose...</option>
				  <option>Korea(South)</option>
                  <option>United States</option>
                </select>
                <div class="invalid-feedback">
                  Please select a valid country.
                </div>
              </div>
              <div class="col-md-4 mb-3">
                <label for="sex">Sex</label>
                <select class="custom-select d-block w-100" id="sex" required>
                  <option value="">Choose...</option>
                  <option>Male</option>
				  <option>Female</option>
                </select>
                <div class="invalid-feedback">
                  Please provide your sex.
                </div>
              </div>
              <div class="col-md-3 mb-3">
                <label for="age">Age</label>
                <input type="text" class="form-control" id="age" placeholder="" required>
                <div class="invalid-feedback">
                  Please enter your age.
                </div>
              </div>
            </div>
-->

<!--			
            <hr class="mb-4">
            <div class="custom-control custom-checkbox">
              <input type="checkbox" class="custom-control-input" id="same-address">
              <label class="custom-control-label" for="same-address">Shipping address is the same as my billing address</label>
            </div>
            <div class="custom-control custom-checkbox">
              <input type="checkbox" class="custom-control-input" id="save-info">
              <label class="custom-control-label" for="save-info">Save this information for next time</label>
            </div>
            <hr class="mb-4">

            <h4 class="mb-3">Payment</h4>

            <div class="d-block my-3">
              <div class="custom-control custom-radio">
                <input id="credit" name="paymentMethod" type="radio" class="custom-control-input" checked required>
                <label class="custom-control-label" for="credit">Credit card</label>
              </div>
              <div class="custom-control custom-radio">
                <input id="debit" name="paymentMethod" type="radio" class="custom-control-input" required>
                <label class="custom-control-label" for="debit">Debit card</label>
              </div>
              <div class="custom-control custom-radio">
                <input id="paypal" name="paymentMethod" type="radio" class="custom-control-input" required>
                <label class="custom-control-label" for="paypal">Paypal</label>
              </div>
            </div>
            <div class="row">
              <div class="col-md-6 mb-3">
                <label for="cc-name">Name on card</label>
                <input type="text" class="form-control" id="cc-name" placeholder="" required>
                <small class="text-muted">Full name as displayed on card</small>
                <div class="invalid-feedback">
                  Name on card is required
                </div>
              </div>
              <div class="col-md-6 mb-3">
                <label for="cc-number">Credit card number</label>
                <input type="text" class="form-control" id="cc-number" placeholder="" required>
                <div class="invalid-feedback">
                  Credit card number is required
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-md-3 mb-3">
                <label for="cc-expiration">Expiration</label>
                <input type="text" class="form-control" id="cc-expiration" placeholder="" required>
                <div class="invalid-feedback">
                  Expiration date required
                </div>
              </div>
              <div class="col-md-3 mb-3">
                <label for="cc-expiration">CVV</label>
                <input type="text" class="form-control" id="cc-cvv" placeholder="" required>
                <div class="invalid-feedback">
                  Security code required
                </div>
              </div>
            </div>
-->			
            <hr class="mb-4">
            <button class="btn btn-primary btn-lg btn-block" type="submit">Subscribe</button>

            <?php if(!empty($message)): ?>
		<p><?= $message ?></p>
  <?php endif; ?>
  
          </form>
			
	
        </div>
      </div>

      <footer class="my-5 pt-5 text-muted text-center text-small">
        <p class="mb-1">&copy; 2017-2018 Company Name</p>
        <ul class="list-inline">
          <li class="list-inline-item"><a href="#">Privacy</a></li>
          <li class="list-inline-item"><a href="#">Terms</a></li>
          <li class="list-inline-item"><a href="#">Support</a></li>
        </ul>
      </footer>
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
      // Example starter JavaScript for disabling form submissions if there are invalid fields
      (function() {
        'use strict';

        window.addEventListener('load', function() {
          // Fetch all the forms we want to apply custom Bootstrap validation styles to
          var forms = document.getElementsByClassName('needs-validation');

          // Loop over them and prevent submission
          var validation = Array.prototype.filter.call(forms, function(form) {
            form.addEventListener('submit', function(event) {
              if (form.checkValidity() === false) {
                event.preventDefault();
                event.stopPropagation();
              }
              form.classList.add('was-validated');
            }, false);
          });
        }, false);
      })();
    </script>
  </body>
</html>


