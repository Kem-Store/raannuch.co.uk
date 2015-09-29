<?php
ob_start();
session_start(); 
$obj = array(
	'onError'=>false, 
	'exMessage'=>NULL
); 

if(isset($_POST['ACCESS'])) {
	include("../../libs/SyncDatabase.php"); 
	include("../../libs/Session.php"); 
	if($_POST['username'] == 'admin' && $_POST['password'] == 'admin') {
		$cookie = new Session();
		$cookie->setCookie('ACCESS','Admin', 1440);
	} else {
		$obj['onError'] = true;
		if($_POST['username'] != 'admin') $obj['exMessage'] = "Username wong.";
		if($_POST['password'] != 'admin') $obj['exMessage'] = "Password wong";
	}
}
echo json_encode($obj);
?>