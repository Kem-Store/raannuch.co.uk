<?php
$data = array(
	'error' => true,
	'message' => 'Save billing successful.'
);

try {
	include("../libs/SyncDatabase.php"); 
	$base = new SyncDatabase();
    if (!isset($_SERVER['HTTP_X_REQUESTED_WITH'])) throw new Exception('HTTP_X_REQUESTED_WITH ');
	
	
	$title = $_POST['title'];
	$des = $_POST['des'];
	$base->Query("UPDATE contents SET description='$des' WHERE title_id = '$title';");	
	$data['error'] = false;	
} catch (Exception $e) {
    $data['message'] = $e->getMessage();
}

echo json_encode($data);
?>