<?php
require $file_dir.'layout/variablesandfunctions.php';


#get device
$usr_agent = $_SERVER["HTTP_USER_AGENT"];
if(preg_match('/(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|iris|kindle|lge |maemo|midp|mmp|mobile.+firefox|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows ce|xda|xiino/i',$usr_agent) || preg_match('/1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i', substr($usr_agent,0,4))){
    $on_mobile = true;
}else{
    $on_mobile = false;
}

require $file_dir.'vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;

$userId = $_SESSION["userId"];
$userMail = $_SESSION["userMail"];
$paymentStatus = $_SESSION["userPaymentStatus"];
$u_datetime = $_SESSION["u_datetime"];
$u_expire = $_SESSION["u_expire"];
$company_name = $_SESSION["company_name"];
// $plan = $_SESSION["userPaymentPlan"];
$user_payment_plan = 'basic';
#incase more payment plans gets created
switch ($user_payment_plan) {
    case 'basic':
        $usermaxusers = 20;
        $userplanName = 'Basic';
        break;
    
    default:
        $usermaxusers = 20;
        $userplanName = 'Basic';
        break;
}

$today = date("Y-m-d H:i:s");
// $timeSpent = daysAgo($u_expire, $today);

$role = $_SESSION["role"];
$company_id = $_SESSION["company_id"];

$include_dir = '../rs/';
$accnt_dir = null;

#payment status
$CheckIfUserExist = "SELECT * FROM users WHERE company_id = '$company_id'";
$UserExist = $con->query($CheckIfUserExist);
if ($UserExist->num_rows > 0) {
    $datainfo = $UserExist->fetch_assoc();
    $___paymentStatus = $datainfo['payment_status'];
    $__next_payment = date('Y-m-d H:i:s', strtotime($datainfo['u_expire']));
    $__expiration = daysAgo($today, $__next_payment);
        
        if ($__expiration['left'] == 'days' &&  $__expiration['timeCalc'] <= '10' ) {
                $payment_countdown = true;
                if ($__expiration['timeCalc'] <= '0' ) {
                    $user__expired = true;
                    if($___paymentStatus !== 'expired'){
                        $UpdateUserStatus = "UPDATE users SET payment_status = 'expired' WHERE company_id = '$company_id'";
                        $UserStatus = $con->query($UpdateUserStatus);
                    }
                    header('Location: /admin/account/make-payment?expired=true');
                    exit();
                }
        }
}

function daysAgo ($oldTime, $newTime) {
	$timeCalc = strtotime($newTime) - strtotime($oldTime);
    $left = '';
	if ($timeCalc >= (60*60*24*2)){
        $left = 'days';
		$timeCalc = intval($timeCalc/60/60/24);
	}else if ($timeCalc >= (60*60*2)){
        $left = 'hours';
		$timeCalc = intval($timeCalc/60/60);
	}else if ($timeCalc >= (60*60)){
        $left = 'hour';
		$timeCalc = intval($timeCalc/60/60);
	}else if ($timeCalc >= 60*2){
        $left = 'minutes';
		$timeCalc = intval($timeCalc/60);
	}else if ($timeCalc >= 60){
        $left = 'minute';
		$timeCalc = intval($timeCalc/60);
	}else if ($timeCalc > 0){
        $left = 'seconds';
		$timeCalc .= " seconds";
	}
    $timeStamp = array(
        'timeCalc' => $timeCalc,
        'left' => $left
    );

	return $timeStamp;
}

#unread
$queryUnread = "SELECT COUNT(*) FROM notification WHERE c_id = '$company_id' AND status = 'unread'";       
$resultUnread = $con->query($queryUnread);

if ($resultUnread) {
    $rowUnread = $resultUnread->fetch_assoc();
    $unreadCount = $rowUnread['COUNT(*)'];
} else {
    $unreadCount = 0;
}

$queryUnread_t = "SELECT COUNT(*) FROM tickets WHERE c_id = '$company_id' AND status != 'closed'";       
$resultUnread_t = $con->query($queryUnread_t);

if ($resultUnread_t) {
    $rowUnread_t = $resultUnread_t->fetch_assoc();
    $unreadCount_t = $rowUnread_t['COUNT(*)'];
} else {
    $unreadCount_t = 0;
}

#funcs
    function getUserDetailsWithId($company_id, $get_user_id, $con){
        #confirm user
        $ConfirmUserExist = "SELECT * FROM users WHERE company_id = '$company_id'";
        $ConfirmedUser = $con->query($ConfirmUserExist);
        if ($ConfirmedUser->num_rows > 0) {
            $row = $ConfirmedUser->fetch_assoc();
            $company_users = $row['company_users'];

            $company_users = unserialize($company_users);
            $companycount = count($company_users);

            $isInArray = in_array_custom($get_user_id, $company_users) ? 'found' : 'notfound';
            if($isInArray === 'found'){
                for ($rowArray = 0; $rowArray < $companycount; $rowArray++) {
                    // echo 'row - '.$row.', email = '.$arrayAll[$row]['email'].', password = '.$arrayAll[$row]['password'];
                    if($company_users[$rowArray]['id'] == $get_user_id){
                        $rowNumber = $rowArray;
                    }
                }
                $details = array(
                    'name' => $company_users[$rowNumber]['fullname'],
                    'email' => $company_users[$rowNumber]['email'],
                );
            }else{
                $details = 'error';
            }
        }else{
            $details = 'error';
        }
        return $details;
    }
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

    function mailUserCustomWithHeader($mailSubject, $mailBody, $mailRecipient, $mailSender, $mailSenderHeader, $mailRecieverHeader){
        $mail = new PHPMailer();
        $mail->SMTPDebug = 0;
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'dev3.bdpl@gmail.com';
        $mail->Password = 'binarydata000';
        $mail->SMTPSecure = 'tls';
        $mail->Port = 587;
        $mail->setFrom($mailSender, $mailSenderHeader);
        $mail->addAddress($mailRecipient, $mailRecieverHeader);

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

    function notificationCustom($mailRecipient, $mailSender, $type, $link, $case, $id, $conn){
        $today = date("N dS m Y");
        $year = date("Y");
        switch ($case) {
            case 'new':
                $subRisk = 'New Risk Assessment Created On: '.$today;
                $subIncident = 'New Incident Created On: '.$today;
                $subInsurance = 'New Insurance Created On: '.$today;
                $subCompliance = 'New Compliance Standard Created On: '.$today;

                $bodyRisk = 'New Risk Assessment Created';
                $bodyIncident = 'New Incident Created';
                $bodyInsurance = 'New Insurance Created';
                $bodyCompliance = 'New Compliance Standard Created';
                $new = true;
                $edit = false;
                break;

            case 'edit':
                $subRisk = 'Risk Assessment Details Modified On: '.$today;
                $subIncident = 'Incident Details Modified On: '.$today;
                $subInsurance = 'Insurance Details Modified On: '.$today;
                $subCompliance = 'Compliance Standard Details Modified On: '.$today;

                $bodyRisk = 'Risk Assessment Details Modified';
                $bodyIncident = 'Incident Details Modified';
                $bodyInsurance = 'Insurance Details Modified';
                $bodyCompliance = 'Compliance Standard Details Modified';

                $new = false;
                $edit = true;
                break;
            
            default:
                # code...
                break;
        }

        switch ($type) {
            case 'risk':
                #get details
                $query = "SELECT * FROM as_assessment WHERE as_id = '$id'";
                $verifyQuery = $conn->query($query);
                if ($verifyQuery->num_rows > 0) {	
                    $assessment_details = $verifyQuery->fetch_assoc();
                    $as_type = $assessment_details['as_type'];
                    $as_id = $assessment_details['as_id'];
                    $as_team = $assessment_details['as_team'];
                    $as_task = $assessment_details['as_task'];
                    $as_descript = $assessment_details['as_descript'];
                    $as_assessor = $assessment_details['as_assessor'];
                    $as_approval = $assessment_details['as_approval'];
                    $as_date = $assessment_details['as_date'];
                }else{
                    $as_id = 'Error!';
                    $as_type = 'Error!';
                    $as_team = 'Error!';
                    $as_task = 'Error!';
                    $as_descript = 'Error!';
                    $as_assessor = 'Error!';
                    $as_approval = 'Error!';
                    $as_date = 'Error!';
                }

                $as_id = strtoupper($as_id);
                // if ($new == true) {
                // } else if ($edit == true){
                //   $query = "SELECT * FROM as_risks WHERE idrisk = ".$id."";
                // }
                $mailSubject = $subRisk;
                $body = '
                <!DOCTYPE html>
                <html lang="en">
                    <head>
                    </head>
                    <body>
                    <section style="font-family: Verdana,sans-serif;">
                        <h2 style="color:#6777ef;">'.$bodyRisk.':</h2>
                        <table style="width: 100%;border-collapse: separate !important;">
                        <tr>
                            <th style="text-align: right;border: 2px solid #e4e6fc;border-radius: 5px 0px 0px 5px !important;padding: 10px 15px !important;width: 30%;">Assessment ID :</th>
                            <td style="text-align: left;border: 2px solid #e4e6fc;border-radius: 0px 5px 5px 0px !important;padding: 10px 15px !important;width: 70%;font-weight: 400;">'.$as_id.'</td>
                        </tr>
                        <tr>
                            <th style="text-align: right;border: 2px solid #e4e6fc;border-radius: 5px 0px 0px 5px !important;padding: 10px 15px !important;width: 30%;">Assessment Type :</th>
                            <td style="text-align: left;border: 2px solid #e4e6fc;border-radius: 0px 5px 5px 0px !important;padding: 10px 15px !important;width: 70%;font-weight: 400;">'.$as_type.'</td>
                        </tr>
                        <tr>
                            <th style="text-align: right;border: 2px solid #e4e6fc;border-radius: 5px 0px 0px 5px !important;padding: 10px 15px !important;width: 30%;">Assessment Task :</th>
                            <td style="text-align: left;border: 2px solid #e4e6fc;border-radius: 0px 5px 5px 0px !important;padding: 10px 15px !important;width: 70%;font-weight: 400;">'.$as_task.'</td>
                        </tr>
                        <tr>
                            <th style="text-align: right;border: 2px solid #e4e6fc;border-radius: 5px 0px 0px 5px !important;padding: 10px 15px !important;width: 30%;">Assessment Description :</th>
                            <td style="text-align: left;border: 2px solid #e4e6fc;border-radius: 0px 5px 5px 0px !important;padding: 10px 15px !important;width: 70%;font-weight: 400;">'.$as_descript.'</td>
                        </tr>
                        <tr>
                            <th style="text-align: right;border: 2px solid #e4e6fc;border-radius: 5px 0px 0px 5px !important;padding: 10px 15px !important;width: 30%;">Assessor :</th>
                            <td style="text-align: left;border: 2px solid #e4e6fc;border-radius: 0px 5px 5px 0px !important;padding: 10px 15px !important;width: 70%;font-weight: 400;">'.$as_assessor.'</td>
                        </tr>
                        <tr>
                            <th style="text-align: right;border: 2px solid #e4e6fc;border-radius: 5px 0px 0px 5px !important;padding: 10px 15px !important;width: 30%;">Date Created :</th>
                            <td style="text-align: left;border: 2px solid #e4e6fc;border-radius: 0px 5px 5px 0px !important;padding: 10px 15px !important;width: 70%;font-weight: 400;">'.$as_date.'</td>
                        </tr>
                        </table>
                        <a href="'.$link.'"><button style="margin-top:20px;font-weight: 600;font-size: 12px;line-height: 24px;padding: 0.3rem 0.8rem;letter-spacing: 0.5px;text-align: center;vertical-align: middle;-webkit-user-select: none;-moz-user-select: none;-ms-user-select: none;user-select: none;border: 1px solid transparent;border-radius: 0.25rem;background-color: #6777ef;border-color: transparent !important;color: #fff;">View Details</button></a>
                        <p style="font-weight: 400;">If you have any further questions or concerns, please do not hesitate to reach out to our support team . We value your input and are here to assist you every step of the way.</p>
                        <p style="font-weight: 400;">Thank you for your trust in our risk management services. Together, we can create a safer environment for everyone involved.</p>
                        <p style="font-weight: 400;">Best regards,<br>RiskSafe Team</p>
                        <p style="font-weight: 400;">&copy; '.$year.' RiskSafe. All rights reserved.</p>
                    </section>
                    </body>
                </html>
                ';
                $mailHeader = 'Assessment Notification';    
                
                break;
            
            case 'incident':
                # code...
                $query="SELECT * FROM as_incidents WHERE in_id = '$id'";
                    if ($result = $conn->query($query)) {
                            $row = $result->fetch_assoc();
                    $in_title = $row['in_title'];
                    $in_id = $row['in_id'];
                    $in_financial = $row['in_financial'];
                    $in_complaints = $row['in_complaints'];
                    $in_date = $row['in_date'];
                    }else{
                $in_title = 'Error!';
                $in_id = 'Error!';
                $in_financial = 'Error!';
                $in_complaints = 'Error!';
                $in_date = 'Error!';
                }
                $mailSubject = $subIncident;
                $body = '
                <!DOCTYPE html>
                <html lang="en">
                    <head>
                    </head>
                    <body>
                    <section style="font-family: Verdana,sans-serif;">
                        <h2 style="color:#6777ef;">'.$bodyIncident.':</h2>
                        <table style="width: 100%;border-collapse: separate !important;">
                        <tr>
                            <th style="text-align: right;border: 2px solid #e4e6fc;border-radius: 5px 0px 0px 5px !important;padding: 10px 15px !important;width: 30%;">Incident ID :</th>
                            <td style="text-align: left;border: 2px solid #e4e6fc;border-radius: 0px 5px 5px 0px !important;padding: 10px 15px !important;width: 70%;font-weight: 400;">'.$in_id.'</td>
                        </tr>
                        <tr>
                            <th style="text-align: right;border: 2px solid #e4e6fc;border-radius: 5px 0px 0px 5px !important;padding: 10px 15px !important;width: 30%;">Incident Title :</th>
                            <td style="text-align: left;border: 2px solid #e4e6fc;border-radius: 0px 5px 5px 0px !important;padding: 10px 15px !important;width: 70%;font-weight: 400;">'.$in_title.'</td>
                        </tr>
                        <tr>
                            <th style="text-align: right;border: 2px solid #e4e6fc;border-radius: 5px 0px 0px 5px !important;padding: 10px 15px !important;width: 30%;">Financial :</th>
                            <td style="text-align: left;border: 2px solid #e4e6fc;border-radius: 0px 5px 5px 0px !important;padding: 10px 15px !important;width: 70%;font-weight: 400;">'.$in_financial.'</td>
                        </tr>
                        <tr>
                            <th style="text-align: right;border: 2px solid #e4e6fc;border-radius: 5px 0px 0px 5px !important;padding: 10px 15px !important;width: 30%;">Complaints :</th>
                            <td style="text-align: left;border: 2px solid #e4e6fc;border-radius: 0px 5px 5px 0px !important;padding: 10px 15px !important;width: 70%;font-weight: 400;">'.$in_complaints.'</td>
                        </tr>
                        <tr>
                            <th style="text-align: right;border: 2px solid #e4e6fc;border-radius: 5px 0px 0px 5px !important;padding: 10px 15px !important;width: 30%;">Date Created :</th>
                            <td style="text-align: left;border: 2px solid #e4e6fc;border-radius: 0px 5px 5px 0px !important;padding: 10px 15px !important;width: 70%;font-weight: 400;">'.$in_date.'</td>
                        </tr>
                        </table>
                        <a href="'.$link.'"><button style="margin-top:20px;font-weight: 600;font-size: 12px;line-height: 24px;padding: 0.3rem 0.8rem;letter-spacing: 0.5px;text-align: center;vertical-align: middle;-webkit-user-select: none;-moz-user-select: none;-ms-user-select: none;user-select: none;border: 1px solid transparent;border-radius: 0.25rem;background-color: #6777ef;border-color: transparent !important;color: #fff;">View Details</button></a>
                        <p style="font-weight: 400;">Please be assured that your privacy and the confidentiality of any sensitive information related to this incident will be strictly maintained as per our established policies.</p>
                        <p style="font-weight: 400;">Thank you for your immediate attention to this matter. Together, we can proactively address risks and ensure a secure environment for all stakeholders.</p>
                        <p style="font-weight: 400;">Best regards,<br>RiskSafe Team</p>
                        <p style="font-weight: 400;">&copy; '.$year.' RiskSafe. All rights reserved.</p>
                    </section>
                    </body>
                </html>
                ';
                $mailHeader = 'Incident Notification';
                break;
            
            case 'insurance':
                #get details
                $query = "SELECT * FROM as_insurance WHERE in_id = '$id'";
                $verifyQuery = $conn->query($query);
                if ($verifyQuery->num_rows > 0) {	
                    $in = $verifyQuery->fetch_assoc();
                    $in_id = $in['in_id'];
                    $in_type = $in['is_type'];
                    $in_claims = $in['is_details'];
                    $in_date = $in['is_date'];
                }else{
                    $in_id = 'Error!';
                    $in_type = 'Error!';
                    $in_claims = 'Error!';
                    $in_date = 'Error!';
                }

                $in_id = strtoupper($in_id);
                // if ($new == true) {
                // } else if ($edit == true){
                //   $query = "SELECT * FROM as_risks WHERE idrisk = ".$id."";
                // }
                $mailSubject = $subInsurance;
                $body = '
                <!DOCTYPE html>
                <html lang="en">
                    <head>
                    </head>
                    <body>
                    <section style="font-family: Verdana,sans-serif;">
                        <h2 style="color:#6777ef;">'.$bodyInsurance.':</h2>
                        <table style="width: 100%;border-collapse: separate !important;">
                        <tr>
                            <th style="text-align: right;border: 2px solid #e4e6fc;border-radius: 5px 0px 0px 5px !important;padding: 10px 15px !important;width: 30%;">Insurance ID :</th>
                            <td style="text-align: left;border: 2px solid #e4e6fc;border-radius: 0px 5px 5px 0px !important;padding: 10px 15px !important;width: 70%;font-weight: 400;">'.$in_id.'</td>
                        </tr>
                        <tr>
                            <th style="text-align: right;border: 2px solid #e4e6fc;border-radius: 5px 0px 0px 5px !important;padding: 10px 15px !important;width: 30%;">Insurance Type :</th>
                            <td style="text-align: left;border: 2px solid #e4e6fc;border-radius: 0px 5px 5px 0px !important;padding: 10px 15px !important;width: 70%;font-weight: 400;">'.$in_type.'</td>
                        </tr>
                        <tr>
                            <th style="text-align: right;border: 2px solid #e4e6fc;border-radius: 5px 0px 0px 5px !important;padding: 10px 15px !important;width: 30%;">Details of Claims :</th>
                            <td style="text-align: left;border: 2px solid #e4e6fc;border-radius: 0px 5px 5px 0px !important;padding: 10px 15px !important;width: 70%;font-weight: 400;">'.$in_claims.'</td>
                        </tr>
                        <tr>
                            <th style="text-align: right;border: 2px solid #e4e6fc;border-radius: 5px 0px 0px 5px !important;padding: 10px 15px !important;width: 30%;">Last Review Date :</th>
                            <td style="text-align: left;border: 2px solid #e4e6fc;border-radius: 0px 5px 5px 0px !important;padding: 10px 15px !important;width: 70%;font-weight: 400;">'.$in_date.'</td>
                        </tr>
                        </table>
                        <a href="'.$link.'"><button style="margin-top:20px;font-weight: 600;font-size: 12px;line-height: 24px;padding: 0.3rem 0.8rem;letter-spacing: 0.5px;text-align: center;vertical-align: middle;-webkit-user-select: none;-moz-user-select: none;-ms-user-select: none;user-select: none;border: 1px solid transparent;border-radius: 0.25rem;background-color: #6777ef;border-color: transparent !important;color: #fff;">View Details</button></a>
                        <p style="font-weight: 400;">If you have any further questions or concerns, please do not hesitate to reach out to our support team . We value your input and are here to assist you every step of the way.</p>
                        <p style="font-weight: 400;">Best regards,<br>RiskSafe Team</p>
                        <p style="font-weight: 400;">&copy; '.$year.' RiskSafe. All rights reserved.</p>
                    </section>
                    </body>
                </html>
                ';
                $mailHeader = 'Insurance Notification';    
                
                break;
            
            case 'compliance':
                # code...
                $query = "SELECT * FROM as_compliancestandard WHERE compli_id = '$id'";
                    if ($result = $conn->query($query)) {
                        $row = $result->fetch_assoc();
                $com_compliancestandard = $row['com_compliancestandard'];
                $com_legislation = $row['com_legislation'];
                $com_training = $row['com_training'];
                    }else{
                $com_compliancestandard = 'Error!';
                $com_legislation = 'Error!';
                $com_training = 'Error!';
                }
                $mailSubject = $subCompliance;
                $body = '
                <!DOCTYPE html>
                <html lang="en">
                    <head>
                    </head>
                    <body>
                    <section style="font-family: Verdana,sans-serif;">
                        <h2 style="color:#6777ef;">'.$bodyCompliance.':</h2>
                        <p style="font-weight:400;">The details of the Compliance standard as follows:</p>
                        <table style="width: 100%;border-collapse: separate !important;">
                        <tr>
                            <th style="text-align: right;border: 2px solid #e4e6fc;border-radius: 5px 0px 0px 5px !important;padding: 10px 15px !important;width: 30%;">Compliance ID :</th>
                            <td style="text-align: left;border: 2px solid #e4e6fc;border-radius: 0px 5px 5px 0px !important;padding: 10px 15px !important;width: 70%;font-weight: 400;"></td>
                        </tr>
                        <tr>
                            <th style="text-align: right;border: 2px solid #e4e6fc;border-radius: 5px 0px 0px 5px !important;padding: 10px 15px !important;width: 30%;">Compliance Standard :</th>
                            <td style="text-align: left;border: 2px solid #e4e6fc;border-radius: 0px 5px 5px 0px !important;padding: 10px 15px !important;width: 70%;font-weight: 400;">'.$com_compliancestandard.'</td>
                        </tr>
                        <tr>
                            <th style="text-align: right;border: 2px solid #e4e6fc;border-radius: 5px 0px 0px 5px !important;padding: 10px 15px !important;width: 30%;">Legislation :</th>
                            <td style="text-align: left;border: 2px solid #e4e6fc;border-radius: 0px 5px 5px 0px !important;padding: 10px 15px !important;width: 70%;font-weight: 400;">'.$com_legislation.'</td>
                        </tr>
                        <tr>
                            <th style="text-align: right;border: 2px solid #e4e6fc;border-radius: 5px 0px 0px 5px !important;padding: 10px 15px !important;width: 30%;">Training :</th>
                            <td style="text-align: left;border: 2px solid #e4e6fc;border-radius: 0px 5px 5px 0px !important;padding: 10px 15px !important;width: 70%;font-weight: 400;">'.$com_training.'</td>
                        </tr>
                        </table>
                        <a href="'.$link.'"><button style="margin-top:20px;font-weight: 600;font-size: 12px;line-height: 24px;padding: 0.3rem 0.8rem;letter-spacing: 0.5px;text-align: center;vertical-align: middle;-webkit-user-select: none;-moz-user-select: none;-ms-user-select: none;user-select: none;border: 1px solid transparent;border-radius: 0.25rem;background-color: #6777ef;border-color: transparent !important;color: #fff;">View Details</button></a>
                        <p style="font-weight:400;">Best regards,<br>Risksafe Team</p>
                        <p style="font-weight:400;">&copy; '.$year.' RiskSafe. All rights reserved.</p>
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

        $sendMail = mailUserCustomWithHeader($mailSubject, $body, $mailRecipient, $mailSender, 'RiskSafe Team', $mailHeader);
        return $sendMail;
    }

    function sendNotificationUser($company_id, $notification_message, $datetime, $notifier, $link, $type, $case, $id, $conn){
        $link = 'https://portfolio.name.ng/'.$link;
        $role = $GLOBALS['role'];
        $adminemailaddr = 'jay@risksafe.co';
        $n_case_custom = $case.'-'.$type;
        $query_details = "INSERT INTO notification (n_message, n_datetime, n_sender, link, c_id, status, type, n_case, n_case_custom, role) VALUES ('$notification_message', '$datetime', '$notifier', '$link', '$company_id', 'unread', '$type', '$case', '$n_case_custom', '$role')";
        $query_completed = $conn->query($query_details);
        if ($query_completed) {
            $notified = 'true';
            #mail user and admin
            $company_mail = $_SESSION['admin_mail'];
            
            $return = notificationCustom($company_mail, $adminemailaddr, $type, $link, $case, $id, $conn);

            # add admin notification and send admin mail
            $query_details = "INSERT INTO notification_admin (company_id, message, datetime, status, type) VALUES ('$company_id', '$notification_message', '$datetime', 'unread', '$type')";
            $query_completed = $conn->query($query_details);
            if ($query_completed) {
                $adminDb = 'true';
                $returnAdmin = notificationCustom($adminemailaddr, 'admin@risksafe.co', $type, $link, $case, $id, $conn);

                if ($returnAdmin['sent'] == 'true') {
                    $adminMailed = 'true';
                } else {
                    $adminMailed = 'false';
                }

            }else{
                $adminMailed = 'false';
                $adminDb = 'false';
            }

            if ($return['sent'] == 'true') {
                $userMailed = 'true';
            } else {
                $userMailed = 'false';
            } 
            
        }else{
            $notified = 'false';
            $userMailed = 'false';
            $adminMailed = 'false';
        }

        $returnArr = array(
            'user_mailed' => $userMailed,
            'admin_mailed' => $adminMailed,
            'notified' => $notified,
            'admin_db' => $adminDb
        );
        
        return $returnArr;
    }

    function createNotification($company_id, $notification_message, $datetime, $notifier, $link, $type, $case, $conn){
        $link = 'https://portfolio.name.ng/'.$link;
        $role = $GLOBALS['role'];
        $n_case_custom = $case.'-'.$type;
        $query_details = "INSERT INTO notification (n_message, n_datetime, n_sender, link, c_id, status, type, n_case, n_case_custom, role) VALUES ('$notification_message', '$datetime', '$notifier', '$link', '$company_id', 'unread', '$type', '$case', '$n_case_custom', '$role')";
        $query_completed = $conn->query($query_details);
        if ($query_completed) {
            $notified = 'true';
        }else{
            $notified = 'false';
        }
        
        return $notified;
    }

    function createNotificationAdmin($company_id, $notification_message, $datetime, $type, $conn){
        $query_details = "INSERT INTO notification_admin (message, datetime, company_id, status, type) VALUES ('$notification_message', '$datetime', '$company_id', 'unread', '$type')";
        $query_completed = $conn->query($query_details);
        if ($query_completed) {
            $notified = 'true';
        }else{
            $notified = 'false';
        }
        
        return $notified;
    }

    function overdueTreatment($company_id, $id, $conn){
        $year = date("Y");
        #get company details
        $query = "SELECT * FROM users WHERE c_id = '$company_id' LIMIT 1";
		if ($result = $conn->query($query)) {
			$row = $result->fetch_assoc();
            $user_details = $row['user_details'];
            $c_mail = $row['u_mail'];
            $c = unserialize($user_details);
            $c_name = $c['company_name'];
		} else {
			$error = 'Unable To Fetch Company Data!';
            return $error;
            exit();
		}

        #get treatment details 
        $query = "SELECT * FROM as_treatments WHERE c_id = '$company_id' AND t_id = '$id'";
		if ($result = $conn->query($query)) {
			$row = $result->fetch_assoc();
            $tre_id = $row['t_id'];
            $tre_treatment = $row['tre_treatment'];
            $tre_start = $row['tre_start'];
            $tre_due = $row['tre_due'];
            $tre_status = $row['tre_status'];
            $tre_progress = $row['tre_progress'];
            $tre_cost_ben = $row['tre_cost_ben'];
            $tre_assessor = $row['tre_assessor'];
		} else {
			$error = 'Unable To Fetch Treatment Data!';
            return $error;
            exit();
		}

        #create notification
        $notification_message = 'Overdue Treatment';
        $datetime = date("Y-m-d H:i:s");
        $link = 'https://portfolio.name.ng/admin/monitoring/treatments?id='.$id;
        $notify = createNotification($company_id, $notification_message, $datetime, 'Admin', $link, 'treatment', 'overdue', $conn);

        if ($notify == 'true') {
            #mail user
            $mailSubject = 'Overdue Treatment';
            $mailRecipient = $c_mail;
            $mailSender = 'admin@risksafe.co';
            $mailBody = '
                <!DOCTYPE html>
                <html lang="en">
                <head>
                </head>
                <body>
                    <section style="font-family: Verdana,sans-serif;">
                    <p style="font-weight:bolder;font-size:16px;">'.ucwords($c_name).',</p>
                    <p style="font-weight:400;">We are reaching out to you regarding your treatment:</p>
                    <table style="width: 100%;border-collapse: separate !important;">
                        <tr>
                        <th style="text-align: right;border: 2px solid #e4e6fc;border-radius: 5px 0px 0px 5px !important;padding: 10px 15px !important;width: 30%;">Treatment ID :</th>
                        <td style="text-align: left;border: 2px solid #e4e6fc;border-radius: 0px 5px 5px 0px !important;padding: 10px 15px !important;width: 70%;font-weight: 400;">'.$tre_id.'</td>
                        </tr>
                        <tr>
                        <th style="text-align: right;border: 2px solid #e4e6fc;border-radius: 5px 0px 0px 5px !important;padding: 10px 15px !important;width: 30%;">Treatment :</th>
                        <td style="text-align: left;border: 2px solid #e4e6fc;border-radius: 0px 5px 5px 0px !important;padding: 10px 15px !important;width: 70%;font-weight: 400;">'.$tre_treatment.'</td>
                        </tr>
                        <tr>
                        <th style="text-align: right;border: 2px solid #e4e6fc;border-radius: 5px 0px 0px 5px !important;padding: 10px 15px !important;width: 30%;">Treatment Progress :</th>
                        <td style="text-align: left;border: 2px solid #e4e6fc;border-radius: 0px 5px 5px 0px !important;padding: 10px 15px !important;width: 70%;font-weight: 400;">'.$tre_progress.'</td>
                        </tr>
                        <tr>
                        <th style="text-align: right;border: 2px solid #e4e6fc;border-radius: 5px 0px 0px 5px !important;padding: 10px 15px !important;width: 30%;">Treatment Status :</th>
                        <td style="text-align: left;border: 2px solid #e4e6fc;border-radius: 0px 5px 5px 0px !important;padding: 10px 15px !important;width: 70%;font-weight: 400;">'.$tre_status.'</td>
                        </tr>
                        <tr>
                        <th style="text-align: right;border: 2px solid #e4e6fc;border-radius: 5px 0px 0px 5px !important;padding: 10px 15px !important;width: 30%;">Treatment Start Date :</th>
                        <td style="text-align: left;border: 2px solid #e4e6fc;border-radius: 0px 5px 5px 0px !important;padding: 10px 15px !important;width: 70%;font-weight: 400;">'.$tre_start.'</td>
                        </tr>
                        <tr>
                        <th style="text-align: right;border: 2px solid #e4e6fc;border-radius: 5px 0px 0px 5px !important;padding: 10px 15px !important;width: 30%;">Treatment Due Date :</th>
                        <td style="text-align: left;border: 2px solid #e4e6fc;border-radius: 0px 5px 5px 0px !important;padding: 10px 15px !important;width: 70%;font-weight: 400;">'.$tre_due.'</td>
                        </tr>
                    </table>
                    <a href="'.$link.'"><button style="margin-top:20px;font-weight: 600;font-size: 12px;line-height: 24px;padding: 0.3rem 0.8rem;letter-spacing: 0.5px;text-align: center;vertical-align: middle;-webkit-user-select: none;-moz-user-select: none;-ms-user-select: none;user-select: none;border: 1px solid transparent;border-radius: 0.25rem;background-color: #6777ef;border-color: transparent !important;color: #fff;">View Details</button></a>
                    <p style="font-weight:400;">Our records indicate that your treatment is currently overdue, and to ensure proper maintenance, your attention is bein notified immediately.</p>
                    <p style="font-weight:400;">Thank you for your attention to this matter. If you have any questions or need further assistance, please feel free to contact us at Risk Safe team.</p>
                    <p style="font-weight:400;">Best regards,<br>Risksafe Team</p>
                    <p style="font-weight:400;">&copy; '.$year.' RiskSafe. All rights reserved.</p>
                    </section>
                </body>
                </html>
            ';
            $mailUsr = mailUserCustom($mailSubject, $mailBody, $mailRecipient, $mailSender);
        } else {
            $error = 'Unable To Create Notification!';
            return $error;
            exit();
        }
        
    }
    
?>
