<?php
class Session
{
	public function __construct()
	{
		
	}
	
	public function setSession($name, $value)
	{
		$_SESSION[$name] = $value;
	}
	
	public function setCookie($name, $value, $minute)
	{
		if(!$minute) {
			setcookie($name, $value, $minute, '/');
		} else {
			setcookie($name, $value, time()+($minute*60), '/');
		}
	}
	public function Delete($name)
	{
		if(isset($_SESSION[$name])){
			unset($_SESSION[$name]);
		} elseif(isset($_COOKIE[$name])) {
			setcookie($name, '', 0, '/');
		}
	}
	
	public function Value($name)
	{
		if(isset($_SESSION[$name])){
			return $_SESSION[$name];
		} elseif(isset($_COOKIE[$name])) {
			return $_COOKIE[$name];
		} else {
			return false;
		}
	}
	
	public function __destruct()
	{
		//ob_end_flush();
	}
}
?>