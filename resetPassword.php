<?php

	require_once('accounts/empty.php');
	require_once('php/XMLAccount.php');
	require_once('php/usr.php');
	require_once('php/formChecker.php');
	include_once "php/manageLogs.php";
	session_start();
	
	if($_SERVER["REQUEST_METHOD"] == "GET") {
		$key = $_GET['key'];
		$user = FormChecker::validate($key);
		if ($user==null || strtotime('+7 days', $user->lastUpdate()) < strtotime('now')){			
			$_SESSION["msg"]="The password recovery link is out of date";
			header("Location:https://photos.croak.fr/passRecovery.php");
		}
		$userName = $user->name();
	}

	if ($_SERVER["REQUEST_METHOD"] == "POST") {		
		require_once 'php/formChecker.php';
		$name= FormChecker::format_input($_POST["inputName"]);
		$password= FormChecker::format_input($_POST["inputPassword"]);
		
		$log = new ManageLogs();
		
		$checker = new FormChecker();
		
		if($checker->createCatalog()){
			$xml = $checker->getCatalog();
			if($checker->checkPassRecovery($name,$password, $xml)){
				$log->addLog("checked");
				FormChecker::updatePassword($xml, $name, $password);
			}	
		}
	}

?>
<?php	require('header.htm'); ?>

<script src="js/sign.js"></script>

  <body class="bgimg"<?php echo $processJSErrors;?> >

    <div class="container">
	
		<?php $msg = $_SESSION["msg"]; include_once 'php/modalMsg.php' ;	?>
	
		<form class="form-signin" method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" onsubmit="return verifSignUp(this)" >
			<h2 class="form-signin-heading">Reset your password</h2>
			<div class="input-group">
				<label for="inputName" class="sr-only">User name</label>
				<span class="input-group-addon"><i class="glyphicon glyphicon-user"></i></span>
				<input type="text" id="inputName" name="inputName" class="form-control" value="<?php echo "$userName" ?>" >
			</div>
			<p id="nameError" class='hidden'>User name must contain at least 2 characters and less than 20 characters. The only allowed special characters are - and _</p>
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
			<button class="btn btn-lg btn-primary btn-block" type="submit">Reset</button>
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
