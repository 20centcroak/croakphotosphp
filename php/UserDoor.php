<?php

include_once "XMLAccount.php";
include_once "XMLCat.php";
include_once "config.php"; 

class UserDoor{
	
	private $_config;
	private $_user;
	
	public function __construct($userName){
		//TODO gérer les erreurs qui peuvent apparaître lors de l'appel des fonctions ci-dessous
		//gets account settings of current user define by its name $name
		
		$log = new ManageLogs();
		$log->dlog("UserDoor for ".$userName);
		
		$accounts = XMLAccount::xmlAccountFactory();
		$user = $accounts->getUser($userName);
		$this->_user = $user;
		$folder = $user->getAbsoluteDirectory();

		//gets config file of current user
		$configName = $user->config();
		$this->_config = new Config($folder, $configName);
		if($this->_config==null){
			return null;
		}
		$this->defineGlobals();
	}
	
	private function defineGlobals(){
		session_start();
		$_SESSION["gallery"] = $this->_user->name();
		$_SESSION["title"] = $this->_user->title();
	}
	
	public function getUser(){
		return $this->_user;
	}
	
	public function getConfig(){
		return $this->_config;
	}

	public function getCatalog(){
		//gets user catalog
		return XMLCat::xmlCatFactory($this->_config);
	}
}

?>