<?php
//
$to      = 'raannuchcouk@raannuch.co.uk';
$subject = "Contact :: $_POST[subject]";
$message = "Hi, I'm $_POST[name].<br><br>$_POST[message]<br><br>Contact me $_POST[email]";
$headers = "Content-type:text/html;charset=UTF-8\r\nFrom: contacts@raannuch.co.uk";

if(mail($to, $subject, $message, $headers)) {
	echo 'success.';
}
//// raannuchcouk@raannuch.co.uk -> Raannuchcouk123
//// contacts@raannuch.co.uk -> Contacts123
?> 
