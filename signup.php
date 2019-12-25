<?php

	require_once('accounts/empty.php');
	require_once('php/XMLAccount.php');
	require_once('php/usr.php');
	require_once('php/formChecker.php');

	if ($_SERVER["REQUEST_METHOD"] == "POST") {		
		require_once 'php/formChecker.php';		
		$name = FormChecker::format_input($_POST["inputName"]);
		$email = FormChecker::format_input($_POST["inputEmail"]);
		$password= FormChecker::format_input($_POST["inputPassword"]);
		$checker = new FormChecker();
		
		if($checker->createCatalog()){
			$xml = $checker->getCatalog();
			if($checker->check($xml, $name, $email, $password)){
				FormChecker::createAccount($xml, $name, $email, $password);
			}
			else {
				$processJSErrors = $checker->getJSError();
			}			
		}
	}
	session_start();

?>
<?php	require('header.htm'); ?>

<script src="js/sign.js"></script>

  <body class="bgimg"<?php echo $processJSErrors;?> >

    <div class="container">
	
		<?php $msg = $_SESSION["msg"]; include_once 'php/modalMsg.php' ;	?>
	
		<form class="form-signin" method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" onsubmit="return verifSignUp(this)" >
			<h2 class="form-signin-heading">Create your account</h2>
			<div class="input-group">
				<label for="inputName" class="sr-only">User name</label>
				<span class="input-group-addon"><i class="glyphicon glyphicon-user"></i></span>
				<input type="text" id="inputName" name="inputName" onblur="verifName(this)" class="form-control" placeholder="User Name" required autofocus >
			</div>
			<p id="nameError" class='hidden'>User name must contain at least 2 characters and less than 20 characters. The only allowed special characters are - and _</p>
			<div class="input-group">
				<label for="inputEmail" class="sr-only">Email address</label>
				<span class="input-group-addon"><i class="glyphicon glyphicon-envelope"></i></span>
				<input type="email" id="inputEmail" name="inputEmail" onblur="verifMail(this)"class="form-control" placeholder="Email address" required >
			</div>
			<p id="emailError" class='hidden'>Enter a valid e-mail address</p>
			<div class="input-group">
				<label for="inputPassword" class="sr-only">Password</label>
				<span class="input-group-addon"><i class="glyphicon glyphicon-lock"></i></span>
				<input type="password" id="inputPassword" name="inputPassword" onblur="verifPassword(this)" class="form-control" placeholder="Password" required>
			</div>
			<p id="passwordError" class='hidden'>Password should be at least 6 character long</p>
			<div class="input-group pass-repeat">
				<label for="repeatPassword" class="sr-only">Repeat password</label>
				<span class="input-group-addon"><i class="glyphicon glyphicon-lock"></i></span>
				<input type="password" id="repeatPassword" onblur="verifRepeatPassword(this)" class="form-control" placeholder="Repeat password" required>
			</div>
			<p id="passwordRepeatError" class='hidden'>Password repeated with differences</p>
			<button class="btn btn-lg btn-primary btn-block" type="submit">Create account</button>
		</form>
    </div>
<script>
	<?php
		
		if ($msg){
			echo"$('#myModal').modal('show');";
		}
		else {
			echo"$('#myModal').modal('hide');";
		}
	?>
</script>
  
  </body>
</html>
