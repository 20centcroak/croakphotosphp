<?php
require_once 'php/XMLAccount.php';
require_once 'php/manageLogs.php';

class FormChecker{
	
	private $_processJSErrors;
	private $_xml;
	
	public function createCatalog(){
		$xml = XMLAccount::xmlAccountFactory();
		if ($xml==null){
			session_start();
			$_SESSION["msg"]="Account creation has failed - please try again";
			return false;
		}
		$this->_xml = $xml;
		return true;
	}
	
	public function getCatalog(){
		return $this->_xml;
	}
	
	public function check($xml, $name, $email, $password){
		
		$processJSErrors =  ' onload="trigErrors(';
		$noError = true;
	  
		if (!FormChecker::nameOK($name)) {
			session_start();
			$_SESSION["msg"]="Wrong name";
			$processJSErrors=$processJSErrors."1,";
			$noError = false;
		}
		else{
			$processJSErrors=$processJSErrors."0,";
		}
		if (!FormChecker::emailOK($email)) {
			session_start();
			$_SESSION["msg"]="Wrong email";
			$processJSErrors=$processJSErrors."1,";
			$noError = false;
		}
		else{
			$processJSErrors=$processJSErrors."0,";
		}
		if (strlen($password)<6) {
			$processJSErrors=$processJSErrors.'1)"';
			$noError = false;
		}
		else{
			$processJSErrors=$processJSErrors.'0)"';
		}
		
		$this->_processJSErrors = $processJSErrors;
		
		if($noError){
			$users = $xml->getUsers();
			foreach($users as $user){
				$n = $user->name();
				$em = $user->email();
				if($user->name() == $name || $user->email() == $email){
					session_start();
					$_SESSION["msg"]="this user name or email is already used";
					return false;
				}
			}
		}		
		return $noError;
	}
	
	public static function checkSignIn($name, $password, $xml){
		session_start();
		
		if(!FormChecker::nameOK($name)){
			if(!FormChecker::emailOK($name)){
				$_SESSION["msg"]="No such account name in our database. Please verify your name or mail address.";
				return false;
			}
		}

		$user = FormChecker::getUser($name, $xml->getUsers());
		if(!$user){
			return false;
		}
		
		if(!Encryption::decrypt($password, $user->password())){			
			$_SESSION["msg"]="Wrong password";
			return false;
		}
		
		$_SESSION["user"] = $user->name();	
		
		$user->updateTime();
		$xml->update($user);
		
		return true;		
	}
	
	public static function checkPassRecovery($name, $password, $xml){
		session_start();
		
		if (!FormChecker::nameOK($name)) {
			$_SESSION["msg"]="No such account name in our database. Please verify your name";
			return;
		}
		if (strlen($password)<6) {
			$_SESSION["msg"]="Password must be at least 6-character long";
			return;
		}
		
		return true;		
	}
	
	public static function checkRecovery($email, $xml){
		if(!FormChecker::emailOK($email)){
			session_start();
			$_SESSION["msg"]="Wrong e-mail";
			return false;
		}
		
		$user = FormChecker::getUser($email, $xml->getUsers());
		if(!$user){
			return false;
		}
		
		FormChecker::sendRecoveryMail($user, $xml);		
	}
		
	public static function nameOK($name){
		return (preg_match("/^[a-zA-Z0-9]+[a-zA-Z0-9_-]+$/",$name) && strlen($name)>=3 && strlen($name)<20);
	}
	
	public static function emailOK($email){
		return (preg_match("/^[a-zA-Z0-9._-]+@[a-z0-9._-]{2,}\.[a-z]{2,4}$/",$email));
	}
	
	public static function getUser($name, $users, $noActivates){
		foreach($users as $user){
			if(($user->name()===$name || $user->email()===$name)){
				if($noActivates || $user->activated()){
					return $user;
				}else{
					session_start();
					$_SESSION["msg"]="Your account has not been activated yet. Please check your email to finalize your inscription.";
					return false;
				}				
			}
		}
		session_start();
		$_SESSION["msg"]="No such account name in our database. Please verify your name or mail address.";
		return false;
	}
	
	public function getJSError(){
		return $this->_processJSErrors;
	}
	
	public static function createAccount($xml, $name, $email, $password){
		session_start();
		$options = Encryption::options();
		$hash = Encryption::encrypt($password, $name, $options);
		$key = md5($name.microtime(TRUE)*100000);
		$user = User::create($name, $hash, $email, $key);
		if(!$xml->addUser($user)){
			$_SESSION["msg"]="Account creation failed - please try again";
			return;
		}
		FormChecker::sendMail($user);
		header("Location:https://photos.croak.fr/signupSuccess.php");		
	}
	
	public static function updatePassword($xml, $name, $password){
		session_start();
		
		$log = new ManageLogs();
		$log->dLog("update password");
		
		$options = Encryption::options();
		$hash = Encryption::encrypt($password, $name, $options);
		
		$log->addLog("users get");
		
		$user = FormChecker::getUser($name, $users, true);
		if($user==null){
			$_SESSION["msg"]="This user account does not exist";
			return;
		}

		$log->addLog("update");
		$user->update($user->alias(), $hash, $user->title(), $user->email(), true);
		$log->addLog("user updated");
		$xml->update($user);
		$log->addLog("xml updated");

		$_SESSION["msg"]="Your password has been sucessfully updated";
		header("Location:https://photos.croak.fr/admin.php");
	}

	public static function format_input($data) {
	  $data = trim($data);
	  $data = stripslashes($data);
	  $data = htmlspecialchars($data);
	  return $data;
	}
	
	public static function validate($key){
		session_start();
		$xml = XMLAccount::xmlAccountFactory();
		$users = $xml->getUsers();

		foreach($users as $user){
			$keydb = $user->key();
			
			if($user->activated()){
				$_SESSION["msg"]="Your account has already been validated";
				return null;
			}

			if($key===$keydb){
				$_SESSION["msg"]="Your account is now available";
				return $user;
			}
		}
		$_SESSION["msg"]="No corresponding account";
		return null;
	}
	
	private static function sendMail($user){
		
		$message_txt = "Welcome to Croak photos,\n
		Just one final step to validate your account, please click on the link below.\n 
		https://photos.croak.fr/activation.php?key=".urlencode($user->key())."\n
		---------------\n
		This is an automatic e-mail, please do not reply.";
		
		$boundary = "-----=".md5(rand());
		
		$header = "From: \"Croak Photos\"<cgi-mailer@kundenserver.de> \n";
		$header.= "Reply-to: \"Croak Photos\"<support@croak.fr> \n";
		$header.= "MIME-Version: 1.0 \n";
		$header.= "X-Priority: 3 \n";
		$header.= "Content-Type: multipart/alternative; \n boundary=\"$boundary\" \n";
		
		$message = "\n--$boundary\n";
		$message.= "Content-Type: text/plain; charset=\"ISO-8859-1\" \n";
		$message.= "Content-Transfer-Encoding: 8bit \n";

		$message.= "\n $message_txt \n";
		
		$message.= "\n--$boundary\n";
		$message.= "Content-Type: text/html; charset=\"ISO-8859-1\" \n";
		$message.= "Content-Transfer-Encoding: 8bit \n";
		
		$message.= "\n $message_txt \n";
		
		$message.= "\n--$boundary--\n";
		$message.= "\n--$boundary--\n";
		
		$log = new ManageLogs();
		$log->dLog("mail");
		$log->addLog($header);
		$log->addLog($message);
		
		mail($user->email(),"Validate you account",$message,$header);
	}
	
	private static function sendRecoveryMail($user, $xml){
	
		$user->update($user->alias(), $user->password(), $user->title(), $user->email(), false);
		$xml->update($user);
	
		
		$message_txt = "To change your password on Croak photos,\n
		please click on the link below.\n 
		https://photos.croak.fr/resetPassword.php?key=".urlencode($user->key())."\n
		---------------\n
		This is an automatic e-mail, please do not reply.";
		
		$boundary = "-----=".md5(rand());
		
		$header = "From: \"Croak Photos\"<cgi-mailer@kundenserver.de> \n";
		$header.= "Reply-to: \"Croak Photos\"<support@croak.fr> \n";
		$header.= "MIME-Version: 1.0 \n";
		$header.= "X-Priority: 3 \n";
		$header.= "Content-Type: multipart/alternative; \n boundary=\"$boundary\" \n";
		
		$message = "\n--$boundary\n";
		$message.= "Content-Type: text/plain; charset=\"ISO-8859-1\" \n";
		$message.= "Content-Transfer-Encoding: 8bit \n";

		$message.= "\n $message_txt \n";
		
		$message.= "\n--$boundary\n";
		$message.= "Content-Type: text/html; charset=\"ISO-8859-1\" \n";
		$message.= "Content-Transfer-Encoding: 8bit \n";
		
		$message.= "\n $message_txt \n";
		
		$message.= "\n--$boundary--\n";
		$message.= "\n--$boundary--\n";
		
		$log = new ManageLogs();
		$log->dLog("mail");
		$log->addLog($header);
		$log->addLog($message);
		
		mail($user->email(),"Validate you account",$message,$header);
	}
}