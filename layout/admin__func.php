<?php
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

    function sendNotificationUser($company_id, $notification_message, $datetime, $notifier, $link, $type, $case, $id, $conn, $sitee){
        $link = $sitee.$link;
        $role = $GLOBALS['role'];
        // $adminemailaddr = 'jay@risksafe.co';
        $n_case_custom = $case.'-'.$type;
        $query_details = "INSERT INTO notification (n_message, n_datetime, n_sender, link, c_id, status, type, n_case, n_case_custom, role) VALUES ('$notification_message', '$datetime', '$notifier', '$link', '$company_id', 'unread', '$type', '$case', '$n_case_custom', '$role')";
        $query_completed = $conn->query($query_details);
        if ($query_completed) {
            $notified = 'true';
            #mail user and admin
            // $company_mail = $_SESSION['admin_mail'];
            
            // $return = notificationCustom($company_mail, $adminemailaddr, $type, $link, $case, $id, $conn);

            # add admin notification and send admin mail
            // $query_details = "INSERT INTO notification_admin (company_id, message, datetime, status, type) VALUES ('$company_id', '$notification_message', '$datetime', 'unread', '$type')";
            // $query_completed = $conn->query($query_details);
            // if ($query_completed) {
            //     $adminDb = 'true';
            //     $returnAdmin = notificationCustom($adminemailaddr, 'admin@risksafe.co', $type, $link, $case, $id, $conn);

            //     if ($returnAdmin['sent'] == 'true') {
            //         $adminMailed = 'true';
            //     } else {
            //         $adminMailed = 'false';
            //     }

            // }else{
            //     $adminMailed = 'false';
            //     $adminDb = 'false';
            // }

            // if ($return['sent'] == 'true') {
            //     $userMailed = 'true';
            // } else {
            //     $userMailed = 'false';
            // } 
            
        }else{
            $notified = 'false';
            // $userMailed = 'false';
            // $adminMailed = 'false';
        }

        // $returnArr = array(
        //     'user_mailed' => $userMailed,
        //     'admin_mailed' => $adminMailed,
        //     'notified' => $notified,
        //     'admin_db' => $adminDb
        // );
        
        return $notified;
    }

    function createNotification($company_id, $notification_message, $datetime, $notifier, $link, $type, $case, $conn, $sitee){
        $link = $sitee.$link;
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
?>