<?php
class sendmailRouter extends controller{
  function index()
  {
	try {
		$mail =C('PHPMailer');
		$address = "huang@163.com";
		$mail->IsSMTP(); // set mailer to use SMTP
		$mail->Host = "smtp.126.com"; // specify main and backup server
		$mail->Port = 25;
		$mail->SMTPAuth = true; // turn on SMTP authentication
		$mail->Username = "topce@126.com"; // SMTP username
		$mail->Password = "*****"; // SMTP password

		$mail->From = "topce@126.com";
		$mail->FromName = "邮件测试";
		$mail->AddAddress("$address", "");
		//$mail->AddAddress(""); // name is optional
		//$mail->AddReplyTo("", "");

		//$mail->WordWrap = 50; // set word wrap to 50 characters
		//$mail->AddAttachment("/var/tmp/file.tar.gz"); // add attachments
		//$mail->AddAttachment("/tmp/image.jpg", "new.jpg"); // optional name
		//$mail->IsHTML(true); // set email format to HTML

		$mail->Subject = "PHPMailer 再次测试";
		$mail->Body = "Hello,这是我的测试松子的测试邮件";
		$mail->AltBody = "This is the body in plain text for non-HTML mail clients";
		if(!$mail->Send())
		{
		 echo "Message could not be sent. <p>";
		 echo "Mailer Error: " . $mail->ErrorInfo;
		 exit;
		}

		echo "Message has been sent";

		} catch (phpmailerException $e) {
		  echo $e->errorMessage();
		  echo("ssss");
		}
		Return false;
  }
}
?>