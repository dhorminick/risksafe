<?php
    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\Exception;
    use PHPMailer\PHPMailer\SMTP;

    require 'mailer/Exception.php';
    require 'mailer/PHPMailer.php';
    require 'mailer/SMTP.php';
    
    require 'mail_templates.inc.php';

    function mailUser($mailSubject, $mailBody, $mailRecipient, $mailSender){
        $mail = new PHPMailer();
        $mail->SMTPDebug = 0;
        $mail->isSMTP();
        $mail->SMTPAuth = true;
        
        $mail->Host = 'smtp.gmail.com';
        $mail->Username = 'risksafetechnology@gmail.com';
        $mail->Password = 'uypefdfjhdtktwda'; #uype fdfj hdtk twda
        $mail->SMTPSecure = 'ssl';
        $mail->Port = 465;
        $mail->Priority = 1;
        $mail->AddCustomHeader("X-MSMail-Priority: High");
  
          $mail->setFrom('risksafetechnology@gmail.com', 'RiskSafe');
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

    function _resetPass($sender, $recipient, $subject, $otp, $name, $page_fb, $page_ig, $page_yt, $page_x, $siteMainLocation, $page_wt, $site, $help){

        $body = reset_password_tenplate($name, $otp, $page_fb, $page_ig, $page_yt, $page_x, $siteMainLocation, $page_wt, $site, $help);
        $sent = mailUser($subject, $body, $recipient, $sender);
        
        return $sent;
    }
    
    function _createAcc($sender, $recipient, $subject, $otp, $name, $page_fb, $page_ig, $page_yt, $page_x, $siteMainLocation, $page_wt, $site, $help){
        
        $body = confirm_mail_tenplate($name, $otp, $page_fb, $page_ig, $page_yt, $page_x, $siteMainLocation, $page_wt, $site, $help);
        $sent = mailUser($subject, $body, $recipient, $sender);
        
        return $sent;
    }

    function _reg($sender, $recipient, $subject, $otp, $name, $site, $help){
        
        $body = _confirm_mail_tenplate($name, $otp, $site, $help);
        $sent = mailUser($subject, $body, $recipient, $sender);

        return $sent;
    }
    
    function _sendAdminMailForBookDemo($mailSender, $mailRecipient, $mailSubject, $data){
        
        $body = _book_demo_template($data);
        $sent = mailUser($mailSubject, $body, $mailRecipient, $mailSender);

        return $sent;
    }
    
    function _sendAdminMailForContact($mailSender, $mailRecipient, $mailSubject, $data){
        
        $body = _contact_us_template($data);
        $sent = mailUser($mailSubject, $body, $mailRecipient, $mailSender);

        return $sent;
    }
    
    function _sendNotifMail($full_link, $datetime, $msg, $recipient, $type, $case, $site){

        $body = _notif_email($case, $msg, $full_link, $site);
        $subject = $type.' - RiskSafe';
        $sender = 'no.reply@risksafe.co';
        
        $sent = mailUser($subject, $body, $recipient, $sender);
        
        return $sent;
    }
?>