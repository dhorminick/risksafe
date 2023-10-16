<?php
require 'vendor/autoload.php';

$mail = new PHPMailer\PHPMailer\PHPMailer();

		$mail->isSMTP();
		$mail->Host = 'smtpout.secureserver.net';
		$mail->SMTPAuth = true;
		$mail->Port = 465;
		$mail->Username = 'jay@risksafe.co';
		$mail->Password = 'Welcome901#@!';


       $mail->setFrom('dev3.bdpl@gmail.com');
       $mail->addAddress('dev3.bdpl@gmail.com');
       $mail->addReplyTo('binarydata.jagroop@gmail.com');

	   $mail->isHTML(true);
	   $mail->Subject = 'Test Email';
	   $mail->Body = 'This is a test email sent using PHPMailer.';

if ($mail->send()) {
    echo 'Email sent successfully';
} else {
    echo 'Mailer Error: ' . $mail->ErrorInfo;
}

?>

<?php
// the message
$msg = "First line of text\nSecond line of text";

// use wordwrap() if lines are longer than 70 characters
$msg = wordwrap($msg,70);

// send email
mail("dev3.bdpl@gmail.com","My subject",$msg);
?>