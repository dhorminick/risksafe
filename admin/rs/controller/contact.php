<?php

// include_once dirname(__FILE__) . '/../Classes/vendor/phpmailer/phpmailer/src/PHPMailer.php';
// require_once dirname(__FILE__) . '/../Classes/vendor/phpmailer/phpmailer/src/SMTP.php';

require('../../vendor/autoload.php');

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;

include_once("../config.php");
include_once("../model/db.php");
include_once("../model/contact.php");


$contact = new contact;

if (isset($_POST["action"]) && $_POST["action"] == "contact") {

    $name = sanitizePlus($_POST["name"]);
    $email = sanitizePlus($_POST["email"]);
    $subject = sanitizePlus($_POST["subject"]);
    $question = sanitizePlus($_POST["question"]);

    if ($contact->addContact($name, $email, $subject, $question)) {
        $mail = new PHPMailer(false);
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->Port = 587;
        $mail->SMTPAuth = true;
        $mail->Username = 'jay@risksafe.co'; // Replace with your Mailtrap username
        $mail->Password = 'Welcome901#@!'; // Replace with your Mailtrap password
        //BBs35JSmbWjWfi+7/v+e03CcORG4h181ZvMVlpD+pDoo

        $mail->SMTPSecure = 'TLS';
        $mail->SMTPDebug = SMTP::DEBUG_OFF;
        $mail->Debugoutput = 'html';
        // Email content

        $mail->setfrom($email, ucwords($name));
        $mail->addAddress('jay@risksafe.co');
        //$mailto:mail->addaddress('shwetachauhan035@gmail.com');
        $mail->Subject = 'Contact Mail';
        // Email body

        //$body=// Email message (HTML content)
        $body = '<!DOCTYPE html>
            <html>
            
            <head>
                <meta charset="UTF-8">
                <title>Email Verification</title>
            </head>
            
            <body>
                <table align="center" border="0" cellpadding="0" cellspacing="0" width="600" style="border-collapse: collapse;">
                    <tr>
                        <td align="center" bgcolor="#f9f9f9" style="padding: 40px 0 30px 0;">
                            <img src="https://risksafe.co/img/logo.png" alt="RiskSafe" width="100">
                        </td>
                    </tr>
                    <tr>
                        <td align="center" bgcolor="#ffffff" style="padding: 40px 20px 40px 20px; font-family: Arial, sans-serif; font-size: 16px; line-height: 1.6; color: #666666;">
                            <p>Name: ' . $name . '</p>           
                            <p>Email: ' . $email . '</p>           
                            <p>Subject: ' . $subject . '</p>           
                            <p>Question: ' . $question . '</p>           
                            <p>Best regards,<br>Risksafe Team</p>
                        </td>
                    </tr>
                    <tr>
                        <td bgcolor="#f9f9f9" align="center" style="padding: 20px 0 30px 0; font-family: Arial, sans-serif; font-size: 12px; color: #666666;">
                            <p>&copy; <?php echo date("Y"); ?>RiskSafe. All rights reserved.</p>
                        
                        </td>
                    </tr>
                </table>
            </body>
            
            </html>';
        $mail->Body = $body;
        // Set the email body as HTML
        $mail->isHTML(true);

        if ($mail->send()) {
            header("Location: ../../contact.php?response=msg#sg");
        } else {
            echo 'Mailer Error: ' . $mail->ErrorInfo;
        }
        header("Location: ../../contact.php?response=msg#sg");
        exit();
    } else {
        header("Location: ../../contact.php?response=error#sg");
        exit();
    }
}

if (isset($_REQUEST["action"]) && $_REQUEST["action"] == "list") {

    $start = '0';
    $length = '10';

    $db = new db;
    $conn = $db->connect();

    // Get the paginated list of users and total count from the listUser() method
    $result = $contact->listcontact($start, $length);
    $list = $result["data"];
    $num = $result["num_total"];

    $fulldata = array();
    $data = array();

    // $fulldata["draw"] = isset($_REQUEST["draw"]) ? intval($_REQUEST["draw"]) : 1;
    $fulldata["recordsTotal"] = $num;
    $fulldata["recordsFiltered"] = $num;

    if ($list !== false) {
        foreach ($list as $item) {
            $response = array();

            if ($item["read_status"] == 0) {
                $response["nr"] = '<span style="font-weight: 600;">' . $item["id"] . '</span>';
                $response["Name"] = '<span style="font-weight: 600;">' . $item["name"] . '</span>';
                $response["Email"] = '<span style="font-weight: 600;">' . $item["email"] . '</span>';
                $response["Subject"] = '<span style="font-weight: 600;">' . $item["subject"] . '</span>';
                $response["Question"] = '<span style="font-weight: 600;">' . $item["question"] . '</span>';
                $response["Date"] = '<span style="font-weight: 600;">' . $item["date"] . '</span>';
            } else {
                $response["nr"] = $item["id"];
                $response["Name"] = $item["name"];
                $response["Email"] = $item["email"];
                $response["Subject"] = $item["subject"];
                $response["Question"] = $item["question"];
                $response["Date"] = $item["date"];
            }

            $response["link"] = '<div style="text-align: center;">
               
                <a title="Delete" class="btn btn-xs btn-danger" href="javascript:del(\'' . $item["id"] . '\');"><i class="glyphicon glyphicon-trash"></i></a></div>';


            $data[] = array_values($response);
        }
    }

    $fulldata["data"] = $data;

    echo json_encode($fulldata);
    exit();
}
// status update
if (isset($_REQUEST["action"]) && $_REQUEST["action"] == "statusupdate") {
    $id = $_REQUEST['id'];
    $db = new db;
    $conn = $db->connect();
    $query = "UPDATE as_contact
   SET read_status = '1'
   WHERE id = " . $id . "";
    $result = $conn->query($query);
    echo 1;
}

//DELETE readnotification
if (isset($_REQUEST["action"]) && $_REQUEST["action"] == "deletecontact") {

    $contact->deletecontact($_REQUEST["id"]);
}
