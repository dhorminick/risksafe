<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;


class MailSent
{
    public function sentMail($body, $email, $subject)
    {

        $mail = new PHPMailer(true);
        // SMTP settings for Mailtrap
        $mail->isSMTP();
        $mail->Host = 'sandbox.smtp.mailtrap.io';
        $mail->Port = 587;
        $mail->SMTPAuth = true;
        $mail->Username = '7aa331c1243777'; // Replace with your Mailtrap username
        // $mail->Username = 'jay@risksafe.co'; // Replace with your Mailtrap username
        $mail->Password = '979d7e4cc02893'; // Replace with your Mailtrap password
        // $mail->Password = 'Welcome901#@!'; // Replace with your Mailtrap password
        $mail->SMTPSecure = 'TLS';
        // Email content
        $mail->setfrom('jay@risksafe.co', 'Risksafe');
        //$mailto:mail->addaddress('jay@risksafe.co');
        //$mailto:mail->addaddress('jay@risksafe.co');
        $mail->Subject = $subject;

        $mail->Body = $body;
        // Set the email body as HTML
        $mail->isHTML(true);

        $mail->addAddress($email);

        if ($mail->send()) {
            //echo 'mailsent';
            return true;
        } else {
            echo 'Mailer Error: ' . $mail->ErrorInfo;
            return false;
        }
    }
}
