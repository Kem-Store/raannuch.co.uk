<?php
error_reporting(0);
$data = array(
	'error' => true,
	'message' => 'Save successful.'
);

try {
	include("../../libs/SyncDatabase.php"); 
	$base = new SyncDatabase();
	
    if (!isset($_SERVER['HTTP_X_REQUESTED_WITH'])) throw new Exception('HTTP_X_REQUESTED_WITH ');
	if(isset($_POST['action'])) {
		switch($_POST['action'])
		{
			case 'DELETE': 
				$sqlquery = "DELETE FROM product WHERE product_id=$_POST[product_id];";
				if($base->Query($sqlquery)) $data['error'] = false;	
				break;
			case 'SELECT': 
				$sqlquery = "SELECT category_id, name_en, name_th, price, size, recommend, visible, image_path FROM product WHERE product_id = $_POST[product_id]"; 
				$data['error'] = false;
				$data['message'] = $base->Query($sqlquery);
			break; 
		}
	} elseif($_POST['product_id']=='NEW') {
		$sqlquery = 'INSERT INTO product (category_id,name_en,name_th,price,size,recommend,visible,image_path) VALUES ';
		$sqlquery .= "($_POST[category_id],'$_POST[title_en]','$_POST[title_th]',$_POST[price],'$_POST[size]',$_POST[recommend],$_POST[show],'$_POST[image]');";
		if($base->Query($sqlquery)) $data['error'] = false;	
	} else {
		$sqlquery = "UPDATE product SET category_id=$_POST[category_id],name_en='$_POST[title_en]',name_th='$_POST[title_th]',price=$_POST[price],size='$_POST[size]',";
		$sqlquery .= "recommend=$_POST[recommend],visible=$_POST[show],image_path='$_POST[image]' WHERE product_id=$_POST[product_id]";
		if($base->Query($sqlquery)) $data['error'] = false;
	}
} catch (Exception $e) {
    $data['message'] = $e->getMessage();
}
echo json_encode($data);