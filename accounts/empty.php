<?php

class Encryption{
	
	public static function options(){
		return ['cost' => 12, 'salt' =>mcrypt_create_iv(22, MCRYPT_DEV_URANDOM)];
	}
	
	private static function strong($password){
		$length = strlen($password);
		$splits = str_split($password, $length/3);
		$ind1 = $length%3;
		$ind2 = $length/3;
		$splits[0] = $splits[0].utf8_encode("!-_ç$ind1");
		$splits[1] = $splits[1].utf8_encode("1973");
		$splits[2] = $splits[2].utf8_encode("Bc$ind2");
		
		$strong_pass = "";
		foreach($splits as $split){
			$strong_pass=$strong_pass.$split;
		}
		return $strong_pass;
	}
	
	public static function encrypt($password, $name, $options) {
		$strong_pass = Encryption::strong($password);		
		return password_hash($strong_pass, PASSWORD_BCRYPT, $options);
	}
	
	public static function decrypt($password, $hash){
		$strong_pass = Encryption::strong($password);
		return password_verify($strong_pass, $hash);
	}
	
}