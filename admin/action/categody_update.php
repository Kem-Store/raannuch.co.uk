<?php
error_reporting(0);
$data = array(
	'error' => true,
	'message' => 'Save successful.'
);

try {
	include("../../libs/SyncDatabase.php"); 
	$base = new SyncDatabase();
	$sqlquery = "";
    if (!isset($_SERVER['HTTP_X_REQUESTED_WITH'])) throw new Exception('HTTP_X_REQUESTED_WITH ');
	if($_POST['category_id']=='NEW') {
		$sqlquery = "INSERT INTO category (name) VALUES ('$_POST[name]')";
	} else {
		$sqlquery = "UPDATE category set name='$_POST[name]' WHERE category_id = $_POST[category_id]";
	}
	if($base->Query($sqlquery)) $data['error'] = false;
} catch (Exception $e) {
    $data['message'] = $e->getMessage();
}
echo json_encode($data);