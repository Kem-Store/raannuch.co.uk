<?php
$data = array(
	'error' => true,
	'message' => 'Save billing successful.'
);

try {
	include("../libs/SyncDatabase.php"); 
	$base = new SyncDatabase();
	
    if (!isset($_SERVER['HTTP_X_REQUESTED_WITH'])) throw new Exception('HTTP_X_REQUESTED_WITH ');
	
	$auto_id = $base->Query("SELECT `AUTO_INCREMENT` FROM INFORMATION_SCHEMA.TABLES WHERE TABLE_SCHEMA = 'touno_raannuch' AND TABLE_NAME = 'billing_detail'");
	$auto_id = $auto_id[0][0];
	
	$sqlquery = 'INSERT INTO billing (firstname, lastname, address1, address2, zipcode, city, country, email, notes, tel, invoice_date, invoice_no, payment_term, delivery, vat, status) VALUES ';
	$sqlquery .= "('$_POST[firstname]','$_POST[lastname]','$_POST[address1]','$_POST[address2]','$_POST[zipcode]','$_POST[city]','$_POST[country]','$_POST[email]','$_POST[notes]',";
	$sqlquery .= "'$_POST[tel]', NOW(), '', '',$_POST[shipping], 0,'PENNING');";
	
	$billing_id = $base->Query($sqlquery);
	
	$base->Query("UPDATE billing SET invoice_no = CONCAT(CONCAT('N', DATE_FORMAT(NOW(),'%Y%m%d')), '$billing_id') WHERE billing_id = $billing_id ");
	
	$store = json_decode($_POST['store']);
	foreach($store as $item) {
		$sqlquery = 'INSERT INTO billing_detail (billing_id,product_id,description,price,qty) VALUES ';
		$sqlquery .= "($billing_id, $item->id, '$item->name', $item->price, $item->qty);";
		$base->Query($sqlquery);
	}
	
	$no = $base->Query("SELECT invoice_no FROM billing WHERE billing_id = $billing_id ");
	$to      = 'raannuchcouk@raannuch.co.uk';
	$subject = "Billing Order :: $no[0] (new)";
	$message = "Hi, I'm $_POST[firstname] $_POST[lastname].<br>ps. $_POST[notes]<br>Thank you.";
	$headers = "Content-type:text/html;charset=UTF-8\r\nFrom: contacts@raannuch.co.uk";

	if(mail($to, $subject, $message, $headers)) { echo 'success.'; }
	
	
	$data['error'] = false;	
} catch (Exception $e) {
    $data['message'] = $e->getMessage();
}

echo json_encode($data);
?>