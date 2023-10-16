<?php
require('../vendor/autoload.php');

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;

function mailUser($mailSubject, $mailBody, $mailRecipient, $mailSender){
  $mail = new PHPMailer();
  $mail->SMTPDebug = 0;
  $mail->isSMTP();
  $mail->Host = 'smtp.gmail.com';
  $mail->SMTPAuth = true;
  $mail->Username = 'dev3.bdpl@gmail.com';
  $mail->Password = 'binarydata000';
  $mail->SMTPSecure = 'tls';
  $mail->Port = 587;
  $mail->setFrom($mailSender);
  $mail->addAddress($mailRecipient);

  $mail->isHTML(true);
  $mail->Subject = $mailSubject;
  $mail->Body = $mailBody;
  
  if ($mail->send()) {
    $return = array(
      'sent' => 'true',
      'error' => 'none'
    );
  }else{
    $return = array(
      'sent' => 'false',
      'error' => $mail->ErrorInfo,
    );
  }

  return $return;
}
?>