<?php
$data = array(
	'error' => true,
	'message' => 'Save billing successful.',
	'billing' => NULL,
	'detail' => NULL
);

try {
	include("../../libs/SyncDatabase.php"); 
	$base = new SyncDatabase();
	
    if (!isset($_SERVER['HTTP_X_REQUESTED_WITH'])) throw new Exception('HTTP_X_REQUESTED_WITH ');
	switch($_POST['action']){
		case 'GET':
			$data['billing'] = $base->Query("SELECT * FROM billing WHERE billing_id = $_POST[billing_id]");
			$data['detail'] = $base->Query("SELECT * FROM billing_detail WHERE billing_id = $_POST[billing_id]");
		
			break;
		case 'SAVE':
			$bill = json_decode($_POST['bill']);
			
			$base->Query("UPDATE billing SET status = '$_POST[status]' WHERE billing_id = $bill->billing_id ");
			$base->Query("DELETE FROM billing_detail WHERE billing_id = $bill->billing_id ");
			
			$store = json_decode($_POST['store']);
			foreach($store as $item) {
				
				$sqlquery = 'INSERT INTO billing_detail (billing_id,product_id,description,price,qty) VALUES ';
				$sqlquery .= "($bill->billing_id, $item->id, '$item->name', $item->price, $item->qty);";
				$base->Query($sqlquery);
			}
		
			break;
	}
	$data['error'] = false;	
} catch (Exception $e) {
    $data['message'] = $e->getMessage();
}

echo json_encode($data);
?>