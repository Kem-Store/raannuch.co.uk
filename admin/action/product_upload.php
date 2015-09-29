<?php
error_reporting(0);
$data = array(
	'error' => true,
	'message' => '',
	'filepath' => 'images/no-image.jpg'	
);
try {
    if (!isset($_SERVER['HTTP_X_REQUESTED_WITH'])) throw new Exception('HTTP_X_REQUESTED_WITH ');
    if ($_FILES["input-file"]["size"] > 5242880) throw new Exception("File size is too big!");
    switch(strtolower($_FILES['input-file']['type']))
    {
		//allowed file types
		case 'image/png': 
		case 'image/gif': 
		case 'image/jpeg': 
		case 'image/pjpeg':
			break;
		default:
			throw new Exception('Unsupported File!'); //output error
    }
    
	$date = new DateTime();
    $File_Name          = strtolower($_FILES['input-file']['name']);
    $File_Ext           = substr($File_Name, strrpos($File_Name, '.')); //get file extention
    $Random_Number      = $date->getTimestamp(); //Random number to be added to name.
    $NewFileName        = $Random_Number.$File_Ext; //new file name
    
    if(move_uploaded_file($_FILES['input-file']['tmp_name'], '../../images/tmp/'.$NewFileName )) { // do other stuff 
        $data['error'] = false;
        $data['filepath'] = 'images/tmp/'.$NewFileName;
    }else{
        throw new Exception('error uploading File!');
    }
} catch (Exception $e) {
    $data['message'] = $e->getMessage();
}
echo json_encode($data);