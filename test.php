<?php

include 'layout/mail.php';
include 'layout/variablesandfunctions.php';
    $sender = 'info@portfolio.name.ng';
    $recipient = 'etiketochukwu@gmail.com';
    // etikesamueldominick@gmail.com
    $body = 'test mail';
    $subject = 'test subject';
    $name = 'Test';
    $otp = secure_random_string(20);
    $confirmation_link = $site__.'/auth?e='.weirdlyEncode($recipient).'&auth='.$otp;
    $mailSubject = 'RiskSafe - Confirm Account One-Time Password (OTP)';
    $mailRecipient = $recipient;
    $mailSender = $signUpSender;
    
    // $test = ___sendmail( $sender, $recipient, $subject, $body );
    // $test = _createAcc($mailSender, $mailRecipient, $mailSubject, $confirmation_link, $name, $page_fb, $page_ig, $page_yt, $page_x, $siteMainLocation, $page_wt, $site__, $signUpHelp);
    // var_dump($test);
    
    // echo filter_var('bob@example.com', FILTER_VALIDATE_EMAIL);
    // $header = "From: NewComersUnion <".$from.">\r\n";
    //     $header.= "MIME-Version: 1.0\r\n";
    //     $header.= "Content-Type: text/html; charset=ISO-8859-1\r\n";
    //     $header.= "X-Priority: 1\r\n";
    // if (mail ($recipient, $subject, $body, $header)) {
    //         $return = array(
    //         'sent' => 'true',
    //         'error' => 'none'
    //         );
    //     } else {
    //         $return = array(
    //         'sent' => 'false',
    //         'error' => 'Mail Error!!'
    //         );
    //     }
    //     var_dump($return);
        
        if (function_exists('mail')){
    print 'mail is defined';
}
else{
    print 'mail is undefined';
}
?>