<?php
 
require_once('php/XMLAccount.php'); 
require_once('php/formChecker.php'); 
$key = $_GET['key'];
$user = FormChecker::validate($key);
if($user){
	$checker = new FormChecker();
	if($checker->createCatalog()){
		$xml = $checker->getCatalog();
		$user->activate();
		$xml->update($user);
	}
}
	
header("Location:https://photos.croak.fr/signin.php");
?>

 