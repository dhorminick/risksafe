<?php
require 'mail.php';
#funcs
    
    function __getAssessmentTreatment($type, $id, $company, $con){
        if($type == 'custom'){
            $response = ucfirst($id);
        }else if($type == 'saved'){
            $query="SELECT * FROM as_customtreatments WHERE c_id = '$company'";
    		$result=$con->query($query);
    		if ($result->num_rows > 0) {
        		$response = 'Treatment Error!!';
        		while ($row=$result->fetch_assoc()) {
        			if($row['treatment_id'] == strtolower($id)){
        			    $response = ucfirst($row['title']);
        			}
        		}  
    		}else{
    		    $response = 'Company Error!!';
    		}
        }else{
            $response = 'Treatment Type Error!!';
        }
        
        return $response;
    }
    
    function __listCompanyTreatmentSelected_New($company_id, $id, $con) {
        
        if($id == 'null'){
            $response = __listCompanyTreatment($company_id, $con);
        }else{
    		$response="";
    		$query="SELECT * FROM as_customtreatments WHERE c_id = '$company_id'";
    		$result=$con->query($query);
    		if ($result->num_rows > 0) {
    		    $response.='<select name="saved-treatment[]" class="form-control" required>';
        		while ($row=$result->fetch_assoc()) {
        			$response.='<option value="' . $row["treatment_id"] . '"';
                    if (strtolower($row["treatment_id"]) == strtolower($id)) $response.=' selected';
    			    $response.='>' . ucfirst($row["title"]) . '</option>';
        		}  
        		$response.='</select>';
    		}else{
    		    $response.='Error Fetching Saved Treatment!!';
    		}
        }
	
		return $response;
	
	}
	
    function __listCompanyTreatment($id, $con) {
	
		$response="";
		$query="SELECT * FROM as_customtreatments WHERE c_id = '$id'";
		$result=$con->query($query);
		if ($result->num_rows > 0) {
    		$response.='<option value="null" selected>No Custom Treatment Selected!!</option>';
    		while ($row=$result->fetch_assoc()) {
    			$response.='<option value="' . $row["treatment_id"] . '">' . $row["title"] . '</option>';
    		}   
		}else{
		    $response.='<option value="null" selected>No Custom Treatment Created Yet!!</option>';
		}
		return $response;
	
	}
	
	function __listCompanyIncidents($id, $con, $selected = null) {
	
		$response="";
		$query="SELECT * FROM as_incidents WHERE c_id = '$id'";
		$result=$con->query($query);
		if ($result->num_rows > 0) {
    		$response.='<option value="null" selected>No Incident Selected!!</option>';
    		while ($row=$result->fetch_assoc()) {
    // 			$response.='<option value="' . $row["in_id"] . '">' . $row["in_title"] . '</option>';
    			
    			$response.='<option value="' . $row["in_id"] . '"';
                if ($selected !== null && strtolower($selected) === strtolower($row["in_id"])) $response.=' selected';
    			$response.='>' . ucfirst($row["in_title"]) . '</option>';
    		}   
		}else{
		    $response.='<option value="null" selected>No Incident Created Yet!!</option>';
		}
		return $response;
	
	}
	
	function __getIncident($company_id, $id, $con) {
	
		$response="";
		$query="SELECT * FROM as_incidents WHERE c_id = '$company_id' AND in_id = '$id'";
		$result=$con->query($query);
		if ($result->num_rows > 0) {
    		$row=$result->fetch_assoc();
    		return $row['in_title'];
		}else{
		    return 'Error Fetching Incident!';
		}

	}
	
	function __listCompanyIncidents_Selected($id, $con, $selected = null) {
	
		$response="";
		$query="SELECT * FROM as_incidents WHERE c_id = '$id'";
		$result=$con->query($query);
		if ($result->num_rows > 0) {
		    $response.='<select name="incidents[]" class="form-control" required>';
    		$response.='<option value="null" selected>No Incident Selected!!</option>';
    		while ($row=$result->fetch_assoc()) {
    // 			$response.='<option value="' . $row["in_id"] . '">' . $row["in_title"] . '</option>';
    			
    			$response.='<option value="' . $row["in_id"] . '"';
                if ($selected !== null && strtolower($selected) === strtolower($row["in_id"])) $response.=' selected';
    			$response.='>' . ucfirst($row["in_title"]) . '</option>';
    		}   
		}else{
		    $response.='<option value="null" selected>No Incident Created Yet!!</option>';
		}
		$response.='</select>';
		
		return $response;
	
	}
	
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
    
    function __getCompanyData($id, $con){
        $query_details = "SELECT * FROM users WHERE company_id = '$id' LIMIT 1";
        $UserExist = $con->query($query_details);
        if ($UserExist->num_rows > 0) {
            $_data = $UserExist->fetch_assoc();
            
            return $_data;
        }else{
            return 'false';
        }
    }

    function createNotification($company_id, $notification_message, $datetime, $notifier, $link, $type, $case, $conn, $sitee){
        $company_data = __getCompanyData($company_id, $conn);
        if($company_data === 'false'){
            return 'false';
        }
        
        // $link = $link;
        $role = $GLOBALS['role'];
        $n_case_custom = $case.'-'.$type;
        $query_details = "INSERT INTO notification (n_message, n_datetime, n_sender, link, c_id, status, type, n_case, n_case_custom, role) VALUES ('$notification_message', '$datetime', '$notifier', '$link', '$company_id', 'unread', '$type', '$case', '$n_case_custom', '$role')";
        $query_completed = $conn->query($query_details);
        if ($query_completed) {
            
            #send notif email
            if($company_data['user_loginstatus'] === 0 || $company_data['user_loginstatus'] === '0'){
                
                $full_link = $sitee.$link;
                $_n_case_custom = $case.' '.$type;
                $_n_case_custom = ucwords($_n_case_custom);
                
                $sent = _sendNotifMail($full_link, $datetime, $notification_message, $company_data['u_mail'], $_n_case_custom, $type, $sitee);
                if($sent['sent'] === 'true'){
                    $notified = 'true';
                }else{
                    $notified = 'false - mail, error: '.$sent['error'];
                }
            }else{
                $notified = 'true';
            }
            
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

    function _getSelected($selected, $query){
	    if($selected == $query){
	        return 'selected';
	    }else{
	        return '';
	    }
	}

    function _listFrequencies($selected = null){
	    $response = '
	        <option value="1" '._getSelected($selected, 1).'>Daily Applications</option>
            <option value="2" '._getSelected($selected, 2).'>Weekly Applications</option>
            <option value="4" '._getSelected($selected, 4).'>Monthly Applications</option>
            <option value="5" '._getSelected($selected, 5).'>Quaterly Applications</option>
            <option value="8" '._getSelected($selected, 8).'>Half Yearly Applications</option>
            <option value="6" '._getSelected($selected, 6).'>Annually Applications</option>
            <option value="7" '._getSelected($selected, 7).'>As Required</option>
	    ';
	    
	    return $response;
	}
	
	function _getFrequencyTitle($freq){
	    if ($freq == 7) {
			return "As Required";
		} else if ($freq == 1 || strtolower($freq) == 'daily') {
			return "Daily Applications";
		} else if ($freq == 2 || strtolower($freq) == 'weekly') {
			return "Weekly Applications";
		} else if ($freq == 3) {
			return "Fort-Nightly Controls";
		} else if ($freq == 4 || strtolower($freq) == 'monthly') {
			return "Monthly Applications";
		} else if ($freq == 5) {
			return "Quaterly Applications";
		} else if ($freq == 8) {
			return "Half Yearly Applications";
		} else if ($freq == 6 || strtolower($freq) == 'annually') {
			return "Annually Applications";
		} else {
			return "None Specified";
		}
	}
	
	function _getEffectivenessTitle($effective){
	    $effect = strtolower($effective);
	    if ($effect === 'effective') {
			return "Effective";
		} else if ($effect === 'ineffective') {
			return "InEffective";
		} else {
			return "UnAssessed";
		}
	}
	
	function _listEffectiveness($selected = null){
	    
	    if($selected == null){
    	    $response = '
    	        <option value="Effective">Effective</option>
                <option value="InEffective">InEffective</option>
                <option value="UnAssessed">UnAssessed</option> 
    	    ';
	    }else{
	        $response = '
    	        <option value="Effective" '._getSelected(strtolower($selected), strtolower('Effective')).'>Effective</option>
                <option value="InEffective" '._getSelected(strtolower($selected), strtolower('InEffective')).'>InEffective</option>
                <option value="UnAssessed" '._getSelected(strtolower($selected), strtolower('UnAssessed')).'>UnAssessed</option> 
    	    ';
	    }
	    
	    return $response;
	}
	
	function _getStatus($effective){
	    $effect = strtolower($effective);
	    if ($effect === 'high') {
			return "High";
		} else if ($effect === 'low') {
			return "Low";
		} else {
			return "Medium";
		}
	}
	
	function _listStatus($selected = null){
	    
	    if($selected == null){
    	    $response = '
    	        <option value="High">High</option>
                <option value="Low">Low</option>
                <option value="Medium" selected>Medium</option> 
    	    ';
	    }else{
	        $response = '
    	        <option value="High" '._getSelected(strtolower($selected), strtolower('High')).'>High</option>
                <option value="Low" '._getSelected(strtolower($selected), strtolower('Low')).'>Low</option>
                <option value="Medium" '._getSelected(strtolower($selected), strtolower('Medium')).'>Medium</option> 
    	    ';
	    }
	    
	    return $response;
	}
	
	function _getTrend($effective){
	    $effect = strtolower($effective);
	    if ($effect === 'increasing') {
			return "Increasing";
		} else if ($effect === 'decreasing') {
			return "Decreasing";
		} else {
			return "Stable";
		}
	}
	
	function _listTrend($selected = null){
	    
	    if($selected == null){
    	    $response = '
    	        <option value="Increasing">Increasing</option>
                <option value="Decreasing">Decreasing</option>
                <option value="Stable" selected>Stable</option> 
    	    ';
	    }else{
	        $response = '
    	        <option value="Increasing" '._getSelected(strtolower($selected), strtolower('Increasing')).'>Increasing</option>
                <option value="Decreasing" '._getSelected(strtolower($selected), strtolower('Decreasing')).'>Decreasing</option>
                <option value="Stable" '._getSelected(strtolower($selected), strtolower('Stable')).'>Stable</option> 
    	    ';
	    }
	    
	    return $response;
	}
	
    function _getPriority($effective){
	    $effect = strtolower($effective);
	    if ($effect === 'lowest') {
			return "Lowest";
		} else if ($effect === 'high') {
			return "High";
		} else if ($effect === 'critical') {
			return "Critical";
		} else if ($effect === 'medium') {
			return "Medium";
		}else{
			return "Low";
		}
	}
	
	function _listPriority($selected = null){
	    
	    if($selected == null){
    	    $response = '
    	        <option value="Lowest">Lowest</option>
                <option value="Low" selected>Low</option>
                <option value="High">High</option>
                <option value="Medium">Medium</option>
                <option value="Critical">Critical</option>
    	    ';
	    }else{
	        $response = '
    	        <option value="Lowest" '._getSelected(strtolower($selected), strtolower('Lowest')).'>Lowest</option>
                <option value="Low" '._getSelected(strtolower($selected), strtolower('Low')).'>Low</option>
                <option value="High" '._getSelected(strtolower($selected), strtolower('High')).'>High</option> 
                <option value="Medium" '._getSelected(strtolower($selected), strtolower('Medium')).'>Medium</option>
                <option value="Critical" '._getSelected(strtolower($selected), strtolower('Critical')).'>Critical</option> 
    	    ';
	    }
	    
	    return $response;
	}	

?>