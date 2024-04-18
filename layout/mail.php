<?php
    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\Exception;
    use PHPMailer\PHPMailer\SMTP;

    require 'mailer/Exception.php';
    require 'mailer/PHPMailer.php';
    require 'mailer/SMTP.php';

function mailUser($mailSubject, $mailBody, $mailRecipient, $mailSender){
    $mail = new PHPMailer();
        $mail->SMTPDebug = 0;
        $mail->isSMTP();
        $mail->SMTPAuth = true;
        $mail->Host = 'mail.portfolio.name.ng';
        $mail->Username = 'info@portfolio.name.ng';
        $mail->Password = '(57PIS&Q$&8b';
        $mail->SMTPSecure = 'ssl';
        $mail->Port = 465;
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
function ___sendmail($from, $to, $subject, $body){
        $header = "From: RiskSafe <".$from.">\r\n";
        $header.= "MIME-Version: 1.0\r\n";
        $header.= "Content-Type: text/html; charset=ISO-8859-1\r\n";
        $header.= "X-Priority: 1\r\n";
        
        if (mail ($to, $subject, $body, $header)) {
            $return = array(
            'sent' => 'true',
            'error' => 'none'
            );
        } else {
            $return = array(
            'sent' => 'false',
            'error' => 'Mail Error!!'
            );
        }
        
        return $return;
    }
    
    function _resetPass($sender, $recipient, $subject, $otp, $name, $page_fb, $page_ig, $page_yt, $page_x, $siteMainLocation, $page_wt, $site, $help){
        require 'mail_templates.inc.php';
        $body = reset_password_tenplate($name, $otp, $page_fb, $page_ig, $page_yt, $page_x, $siteMainLocation, $page_wt, $site, $help);
        $sent = mailUser($subject, $body, $recipient, $sender);
        
        return $sent;
    }
    
    function _createAcc($sender, $recipient, $subject, $otp, $name, $page_fb, $page_ig, $page_yt, $page_x, $siteMainLocation, $page_wt, $site, $help){
        require 'mail_templates.inc.php';
        $body = confirm_mail_tenplate($name, $otp, $page_fb, $page_ig, $page_yt, $page_x, $siteMainLocation, $page_wt, $site, $help);
        $sent = mailUser($subject, $body, $recipient, $sender);
        
        return $sent;
    }
?>