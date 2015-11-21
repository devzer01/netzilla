<?php
require_once('classes/top.class.php');

function sendRegisterMail($email, $subject, $message)
{
	$emails_arr = array(
							array(
									'host' => "smtp.gmail.com",
									'port' => "587",
									'username' => "lovely.singles@gmail.com",
									'password' => "sXTd7m8n"
									),
							array(
									'host' => "smtp.gmail.com",
									'port' => "587",
									'username' => "ls.registrierung@gmail.com",
									'password' => "Q7WagU5jNPv:"
									),
							array(
									'host' => "smtp.gmail.com",
									'port' => "587",
									'username' => "ls.registrierung1@gmail.com",
									'password' => "iamsocutE"
									),
							array(
									'host' => "smtp.gmail.com",
									'port' => "587",
									'username' => "ls.registrierung2@gmail.com",
									'password' => "mandyMO0re"
									),
						);

	if(file_exists("register_email_index.txt"))
	{
		$index = file_get_contents("register_email_index.txt");
	}

	if(is_numeric($index))
	{
		if($index > count($emails_arr))
		{
			$index = 0;
		}
	}
	else
	{
		$index = 0;
	}

	$recipients = $email;
	$params["host"] = $emails_arr[$index]['host'];
	$params["port"] = $emails_arr[$index]['port'];
	$params["auth"] = true;
	$params["username"] = $emails_arr[$index]['username'];
	$params["password"] = $emails_arr[$index]['password'];
	$headers['MIME-Version'] = '1.0';
	$headers['Content-type'] = 'text/html; charset=utf8';
	$headers['From'] = $params["username"];
	$headers['To'] = $email;
	$headers['Subject'] = $subject;

	//$mail = Mail::factory("smtp", $params);
	//$result = $mail->send($recipients, $headers, $message);
	file_put_contents("register_email_index.txt", $index+1);

	if (PEAR::isError($result))
		return false;
	else
		return true;
}

sendRegisterMail("zerocoolz_phai@hotmail.com", "TEST SUBJECT", "TEST MSG");
?>