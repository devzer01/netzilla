<?php
include"classes/top.class.php";
$pay = " $90";
$type = "silver";
$username = "tana77";

//$mail_from = "tantavan77@gmail.com";
$mail_from = "no-reply@herzoase.com";
$mail_subject = "You are ".$type." member";
$email_to = "tananarak7@yahoo.com";
//$email_to = "tantavan77@gmail.com";

$smarty->assign('pay',$pay);
$smarty->assign('type',$type);
$smarty->assign('username',$username);
$smarty->assign('url_web', URL_WEB);
$mail_message =  $smarty->fetch('membership_extend.tpl');


/*$header="From: ".$mail_from ."\n";
$header.= "Reply-To: ".$mail_from ."\n";
$header.= "MIME-Version: 1.0\n";
$header.= "Content-Type: multipart/alternative; boundary=\"$mime_boundary\"\n";
$header.= "--$mime_boundary\n";
$header.= "Content-Type: text/html; charset=iso-8859-1\n";
$header.= "Content-Transfer-Encoding: 8bit\n\n";
$header.="X-Mailer: PHP/" . phpversion();

if (!(mail($email_to,$mail_subject,$mail_message,$header))) {
	echo "Fails";
	
}else{
	echo "OK";
	}*/

if(funcs::sendMail($email_to, $mail_subject, $mail_message, $mail_from))
{
	echo "OK";
}
else echo "Fails";


?>
