<?php
    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\Exception;
    use PHPMailer\PHPMailer\SMTP;

    require 'mailer/Exception.php';
    require 'mailer/PHPMailer.php';
    require 'mailer/SMTP.php';

    function __mail($mailSubject, $mailBody, $mailRecipient, $mailSender){
        $mail = new PHPMailer();
            $mail->SMTPDebug = 3;
            $mail->isSMTP();
            $mail->SMTPAuth = true;
            $mail->Host = 'smtpout.secureserver.net';
            $mail->Username = 'no.reply@risksafe.co';
            $mail->Password = 'ytU4&Da-D6';
            $mail->SMTPSecure = 'tls';
            $mail->Port = 587;
            $mail->Priority = 1;
            $mail->AddCustomHeader("X-MSMail-Priority: High");
      
      $mail->setFrom($mailSender, 'RiskSafe');
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
    
    function _resetPass($sender, $recipient, $subject, $otp, $name, $site, $help){
        require 'mail_templates.inc.php';
        $body = reset_password_tenplate($name, $otp, $site, $help);
        $sent = __mail($subject, $body, $recipient, $sender);
        
        return $sent;
    }
    
    function _createAcc($sender, $recipient, $subject, $otp, $name, $site, $help){
        require 'mail_templates.inc.php';
        $body = confirm_mail_tenplate($name, $otp, $site, $help);
        $sent = __mail($subject, $body, $recipient, $sender);
        
        return $sent;
    }

    function _reg($sender, $recipient, $subject, $otp, $name, $site, $help){
        require 'mail_templates.inc.php';
        $body = _confirm_mail_tenplate($name, $otp, $site, $help);
        $sent = __mail($subject, $body, $recipient, $sender);
        
        return $sent;
    }
?>