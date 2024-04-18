<?php
require($file_dir.'vendor/autoload.php');
$adminemailaddr = 'jay@risksafe.co';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;

function mailUserCustom($mailSubject, $mailBody, $mailRecipient, $mailSender){
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
function notificationCustom($mailRecipient, $mailSender, $type){
  $today = date("N dS m Y");
  switch ($type) {
    case 'risk':
      # code...
      $mailSubject = 'New Risk Created On: '.$today;
      $body = '
        <!DOCTYPE html>
        <html lang="en">
          <head>
          </head>
          <body>
            <section style="font-family: Verdana,sans-serif;">
              <h2 style="color:#6777ef;">New Assessment Created:</h2>
              <table style="width: 100%;border-collapse: separate !important;">
                <tr>
                  <th style="text-align: right;border: 2px solid #e4e6fc;border-radius: 5px 0px 0px 5px !important;padding: 10px 15px !important;width: 30%;">Assessment Type :</th>
                  <td style="text-align: left;border: 2px solid #e4e6fc;border-radius: 0px 5px 5px 0px !important;padding: 10px 15px !important;width: 70%;font-weight: 400;"></td>
                </tr>
                <tr>
                  <th style="text-align: right;border: 2px solid #e4e6fc;border-radius: 5px 0px 0px 5px !important;padding: 10px 15px !important;width: 30%;">Assessment Task :</th>
                  <td style="text-align: left;border: 2px solid #e4e6fc;border-radius: 0px 5px 5px 0px !important;padding: 10px 15px !important;width: 70%;font-weight: 400;"></td>
                </tr>
                <tr>
                  <th style="text-align: right;border: 2px solid #e4e6fc;border-radius: 5px 0px 0px 5px !important;padding: 10px 15px !important;width: 30%;">Assessment Description :</th>
                  <td style="text-align: left;border: 2px solid #e4e6fc;border-radius: 0px 5px 5px 0px !important;padding: 10px 15px !important;width: 70%;font-weight: 400;"></td>
                </tr>
                <tr>
                  <th style="text-align: right;border: 2px solid #e4e6fc;border-radius: 5px 0px 0px 5px !important;padding: 10px 15px !important;width: 30%;">Assessor :</th>
                  <td style="text-align: left;border: 2px solid #e4e6fc;border-radius: 0px 5px 5px 0px !important;padding: 10px 15px !important;width: 70%;font-weight: 400;"></td>
                </tr>
                <tr>
                  <th style="text-align: right;border: 2px solid #e4e6fc;border-radius: 5px 0px 0px 5px !important;padding: 10px 15px !important;width: 30%;">Date Created :</th>
                  <td style="text-align: left;border: 2px solid #e4e6fc;border-radius: 0px 5px 5px 0px !important;padding: 10px 15px !important;width: 70%;font-weight: 400;"></td>
                </tr>
              </table>
              <a href=""><button style="margin-top:20px;font-weight: 600;font-size: 12px;line-height: 24px;padding: 0.3rem 0.8rem;letter-spacing: 0.5px;text-align: center;vertical-align: middle;-webkit-user-select: none;-moz-user-select: none;-ms-user-select: none;user-select: none;border: 1px solid transparent;border-radius: 0.25rem;background-color: #6777ef;border-color: transparent !important;color: #fff;">View Details</button></a>
            </section>
          </body>
        </html>
      ';
      $mailHeader = 'Assessment Notification';
      break;
    
    case 'incident':
      # code...
      $mailSubject = 'New Incident Created On: '.$today;
      $body = '
        <!DOCTYPE html>
        <html lang="en">
          <head>
          </head>
          <body>
            <section style="font-family: Verdana,sans-serif;">
              <h2 style="color:#6777ef;">New Incident Created:</h2>
              <table style="width: 100%;border-collapse: separate !important;">
                <tr>
                  <th style="text-align: right;border: 2px solid #e4e6fc;border-radius: 5px 0px 0px 5px !important;padding: 10px 15px !important;width: 30%;">Incident Title :</th>
                  <td style="text-align: left;border: 2px solid #e4e6fc;border-radius: 0px 5px 5px 0px !important;padding: 10px 15px !important;width: 70%;font-weight: 400;"></td>
                </tr>
                <tr>
                  <th style="text-align: right;border: 2px solid #e4e6fc;border-radius: 5px 0px 0px 5px !important;padding: 10px 15px !important;width: 30%;">Incident Priority :</th>
                  <td style="text-align: left;border: 2px solid #e4e6fc;border-radius: 0px 5px 5px 0px !important;padding: 10px 15px !important;width: 70%;font-weight: 400;"></td>
                </tr>
                <tr>
                  <th style="text-align: right;border: 2px solid #e4e6fc;border-radius: 5px 0px 0px 5px !important;padding: 10px 15px !important;width: 30%;">Assessment Description :</th>
                  <td style="text-align: left;border: 2px solid #e4e6fc;border-radius: 0px 5px 5px 0px !important;padding: 10px 15px !important;width: 70%;font-weight: 400;"></td>
                </tr>
                <tr>
                  <th style="text-align: right;border: 2px solid #e4e6fc;border-radius: 5px 0px 0px 5px !important;padding: 10px 15px !important;width: 30%;">Date Created :</th>
                  <td style="text-align: left;border: 2px solid #e4e6fc;border-radius: 0px 5px 5px 0px !important;padding: 10px 15px !important;width: 70%;font-weight: 400;"></td>
                </tr>
              </table>
              <a href=""><button style="margin-top:20px;font-weight: 600;font-size: 12px;line-height: 24px;padding: 0.3rem 0.8rem;letter-spacing: 0.5px;text-align: center;vertical-align: middle;-webkit-user-select: none;-moz-user-select: none;-ms-user-select: none;user-select: none;border: 1px solid transparent;border-radius: 0.25rem;background-color: #6777ef;border-color: transparent !important;color: #fff;">View Details</button></a>
            </section>
          </body>
        </html>
      ';
      $mailHeader = 'Incident Notification';
      break;
    
    case 'compliance':
      # code...
      $mailSubject = 'New Compliance Standard Created On: '.$today;
      $body = '
        <!DOCTYPE html>
        <html lang="en">
          <head>
          </head>
          <body>
            <section style="font-family: Verdana,sans-serif;">
              <h2 style="color:#6777ef;">New Assessment Created:</h2>
              <table style="width: 100%;border-collapse: separate !important;">
                <tr>
                  <th style="text-align: right;border: 2px solid #e4e6fc;border-radius: 5px 0px 0px 5px !important;padding: 10px 15px !important;width: 30%;">Assessment Type :</th>
                  <td style="text-align: left;border: 2px solid #e4e6fc;border-radius: 0px 5px 5px 0px !important;padding: 10px 15px !important;width: 70%;font-weight: 400;"></td>
                </tr>
                <tr>
                  <th style="text-align: right;border: 2px solid #e4e6fc;border-radius: 5px 0px 0px 5px !important;padding: 10px 15px !important;width: 30%;">Assessment Task :</th>
                  <td style="text-align: left;border: 2px solid #e4e6fc;border-radius: 0px 5px 5px 0px !important;padding: 10px 15px !important;width: 70%;font-weight: 400;"></td>
                </tr>
                <tr>
                  <th style="text-align: right;border: 2px solid #e4e6fc;border-radius: 5px 0px 0px 5px !important;padding: 10px 15px !important;width: 30%;">Assessment Description :</th>
                  <td style="text-align: left;border: 2px solid #e4e6fc;border-radius: 0px 5px 5px 0px !important;padding: 10px 15px !important;width: 70%;font-weight: 400;"></td>
                </tr>
                <tr>
                  <th style="text-align: right;border: 2px solid #e4e6fc;border-radius: 5px 0px 0px 5px !important;padding: 10px 15px !important;width: 30%;">Assessor :</th>
                  <td style="text-align: left;border: 2px solid #e4e6fc;border-radius: 0px 5px 5px 0px !important;padding: 10px 15px !important;width: 70%;font-weight: 400;"></td>
                </tr>
                <tr>
                  <th style="text-align: right;border: 2px solid #e4e6fc;border-radius: 5px 0px 0px 5px !important;padding: 10px 15px !important;width: 30%;">Date Created :</th>
                  <td style="text-align: left;border: 2px solid #e4e6fc;border-radius: 0px 5px 5px 0px !important;padding: 10px 15px !important;width: 70%;font-weight: 400;"></td>
                </tr>
              </table>
              <a href=""><button style="margin-top:20px;font-weight: 600;font-size: 12px;line-height: 24px;padding: 0.3rem 0.8rem;letter-spacing: 0.5px;text-align: center;vertical-align: middle;-webkit-user-select: none;-moz-user-select: none;-ms-user-select: none;user-select: none;border: 1px solid transparent;border-radius: 0.25rem;background-color: #6777ef;border-color: transparent !important;color: #fff;">View Details</button></a>
            </section>
          </body>
        </html>
      ';
      $mailHeader = 'Compliance Standard Notification';
      break;
    
    default:
      # code...
      break;
  }

  $mail = new PHPMailer();
  $mail->SMTPDebug = 0;
  $mail->isSMTP();
  $mail->Host = 'smtp.gmail.com';
  $mail->SMTPAuth = true;
  $mail->Username = 'dev3.bdpl@gmail.com';
  $mail->Password = 'binarydata000';
  $mail->SMTPSecure = 'tls';
  $mail->Port = 587;
  $mail->setFrom($mailSender, $mailHeader);
  $mail->addAddress($mailRecipient);

  $mail->isHTML(true);
  $mail->Subject = $mailSubject;
  $mail->Body = $body;
  
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