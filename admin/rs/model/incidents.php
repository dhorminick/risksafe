<?php

include_once("../config.php");
include_once("db.php");
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
require '../../vendor/autoload.php';

class incidents {
	
	public function __construct(){}
	
	public function listIncidents($start, $end) {
	
		$db=new db;
		$conn=$db->connect();
		$query="SELECT * FROM as_incidents WHERE in_user=".$_SESSION["userid"]." ORDER BY idincident DESC, in_date DESC LIMIT " . $start . ", " . $end;
		if ($result=$conn->query($query)) {	
			$data=array();
			while ($row=$result->fetch_assoc()) {
				$data[]=$row;
			}
			$response=$data;
		} else {
			$response=false;	
		}
		$db->disconnect($conn);
		return $response;
	
	}
	
	public function listIncidentsForReport($startDate, $endDate) {
	
		$db=new db;
		$conn=$db->connect();
		$query="SELECT * FROM as_incidents WHERE in_user=".$_SESSION["userid"]." AND in_date >= "
		." '".date("Y-m-d", strtotime($startDate))."' AND in_date <= '".date("Y-m-d", strtotime($endDate))."' ORDER BY idincident DESC, in_date DESC";
		if ($result=$conn->query($query)) {	
			$data=array();
			while ($row=$result->fetch_assoc()) {
				$data[]=$row;
			}
			$response=$data;
		} else {
			$response=false;	
		}
		$db->disconnect($conn);
		return $response;
	
	}
		
	//ADD NEW incident
	public function addIncident($title, $date, $reported, $team, $financial, $injuries,$complaints, $compliance,$descript, $impact, $priority, $status) {
	
		$db=new db();
		$conn=$db->connect();
		
		$query="INSERT INTO as_incidents VALUES (0, " . 
				"" . $_SESSION["userid"] . ", " .
				"'" . $title . "', " .
				"'" . date("Y-m-d", strtotime($date)) . "', " .
				"'" . $reported . "', " .
				"'" . $team . "', " .
				"'" . $financial . "', " .	
				"'" . $injuries . "', " .
				"'" . $complaints . "', " .	
				"'" . $compliance . "', " .				
				"'" . str_replace("'", "\'", $descript) . "', " .
				"'" . str_replace("'", "\'", $impact) . "', " .
				"'" . $priority . "', " .
				"'" . $status . "');";
				
		if ($conn->query($query)) {
			$response=$conn->insert_id;
		} else {
			$response=false;	
		}
		
		$db->disconnect($conn);
		return $response;
		
	}
	
	//EDIT incident
	public function editIncident($id, $title, $date, $reported, $team, $financial, $injuries, $complaints, $compliance, $descript, $impact, $priority, $status) {
	
		$db=new db();
		$conn=$db->connect();
		
		//idincident,in_user,in_title,in_date,in_reported,in_team,in_financial,in_injuries,in_complaints,in_compliance,in_descript,in_impact,in_priority,in_status
		$query="UPDATE as_incidents SET " . 
				" in_title='" . $title . "', " .
				" in_date='" . date("Y-m-d", strtotime($date)) . "', " .
				" in_reported='" . $reported . "', " .
				" in_team='" . $team . "', " .
				" in_financial='" . $financial . "', " .
				" in_injuries='" . $injuries . "', " .
				" in_complaints='" . $complaints . "', " .
				" in_compliance='" . $compliance . "', " .
				" in_descript='" . str_replace("'", "\'", $descript) . "', " .
				" in_impact='" . str_replace("'", "\'", $impact) . "', " .
				" in_priority='" . $priority."', " .
				" in_status='" . $status . "' " . " WHERE idincident=" . $id;
		
		
		if ($conn->query($query)) {
			$response=true;
		} else {
			$response=false;	
		}
		
		$db->disconnect($conn);
		return $response;	
	}
			
	public function deleteIncident($id) {
	
		$db=new db();
		$conn=$db->connect();		
		$query="DELETE FROM as_incidents WHERE idincident=".$id."";		
		
		if ($conn->multi_query($query)) {
			$response=true;
		} else {
			$response=false;	
		}
		
		$db->disconnect($conn);
		return $response;		
	}

	public function getIncident($id) {
	
		$db=new db();
		$conn=$db->connect();
		
		$query="SELECT * FROM as_incidents WHERE idincident=".$id;
		
		if ($result=$conn->query($query)) {
			$row=$result->fetch_assoc();
			$response=$row;
		} else {
			$response=false;	
		}
		
		$db->disconnect($conn);
		return $response;
		
	}		



	public function savenotification($notifyby, $message, $readstatus ,$status,$au) {
		
		$db=new db;
		$conn=$db->connect();
		//$date=date('Y-m-d');
		$query = "INSERT INTO as_notification (notification_by,notification_to,messageinfo,read_status,status)
		VALUES ('$notifyby','1','$message','$readstatus',0)";

		if ($conn->query($query)) {

			$response=true;	

			
			$this->sendNotificationEmail($notifyby,$au);
			$this->sendNotificationEmailadmin($au);
		} else {
			$response=false;
		}
		
		$db->disconnect($conn);
		return $response;
	}


	public function sendNotificationEmail($notifyby,$au) {

		//$query ="SELECT * FROM  users WHERE iduser = '".$notifyby."';
		$db=new db;
		$conn=$db->connect();
		$query="SELECT * FROM users WHERE iduser=".$notifyby."";
		if ($result = $conn->query($query)) {
			$row = $result->fetch_assoc();
			$response = $row;

			$query11="SELECT * FROM as_incidents WHERE idincident=".$au."";
			if ($result11 = $conn->query($query11)) {
					$row11 = $result11->fetch_assoc();
					$response = $row11;
			}



		} else {
			$response = false;
		}
	
		$db->disconnect($conn);
	
	
		$mail = new PHPMailer(true);
		// SMTP settings for Mailtrap
		$mail->isSMTP();
		$mail->Host = 'smtp-mail.outlook.com';
		$mail->Port = 587;
		$mail->SMTPAuth = true;
		$mail->Username = 'jay@risksafe.co'; // Replace with your Mailtrap username
		$mail->Password = 'Welcome901#@!'; // Replace with your Mailtrap password
		$mail->SMTPSecure = 'TLS';
		$subject='New incident created';
		// Email content
		$mail->setfrom('jay@risksafe.co', 'Risksafe Team');
		$mail->addAddress($row['u_mail']);
		//$mail->addAddress('dev3.bdpl@gmail.com');
		//$mailto:mail->addaddress('jay@risksafe.co');
		$mail->Subject = $subject;
		// Email body
	
		//$body=// Email message (HTML content)
			//$body=// Email message (HTML content)
			$body='<!DOCTYPE html>
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
							<p>Dear '.$row['u_mail'].',</p>
							<p>
							We hope this message finds you well. We would like to inform you that a new incident has been successfully created on your risk-related site profile. Your vigilance in promptly reporting incidents contributes to our collective effort in maintaining a secure and risk-aware environment.
							</p>
							<tr>
							
							<td>Incident Id:</td> <td>'.$au.'</td>
							</tr>
						
							<tr>
							<td>
							Incident Title:
							</td><td>'.$row11['in_title'].'</td> 
							</tr>
							<tr>
							<td>
							Financial:
							</td><td>'.$row11['in_financial'].'</td> 
							</tr>
							<tr>
							<td>
							Complaints:
							</td>
							<td>
							'.$row11['in_complaints'].'
							</td>
							</tr>
							<tr>
							<td>
							Date Created:
							</td>
							<td>'.$row11['in_date'].'</td> 
							</tr>
						
							
						
						
							<p>
							Please be assured that your privacy and the confidentiality of any sensitive information related to this incident will be strictly maintained as per our established policies.</p>
<p>
Thank you for your immediate attention to this matter. Together, we can proactively address risks and ensure a secure environment for all stakeholders.
							</p>
							<p>Best regards,<br>Risksafe Team</p>
						
					
					<tr>
						<td bgcolor="#f9f9f9" align="center" style="padding: 20px 0 30px 0; font-family: Arial, sans-serif; font-size: 12px; color: #666666;">
							<p>&copy; <?php echo date("Y"); ?>RiskSafe. All rights reserved.</p>
						
						</td>
					</tr>
				</table>
			</body>
			
			</html>';
			
			
		
		$mail->Body = $body;
		$message = 'New Incident Created';
		// Set the email body as HTML
		$mail->isHTML(true);
	
		if ($mail->send()) {
		//echo 'mailsent';
		return 1;
		} else {
		echo 'Mailer Error: ' . $mail->ErrorInfo;
		}
			
		
	}
	
	
	public function sendNotificationEmailadmin($au) {

		$db=new db;
		$conn=$db->connect();
		$query="SELECT * FROM as_incidents WHERE idincident=".$au."";
		if ($result = $conn->query($query)) {
			$row = $result->fetch_assoc();
			$response = $row;

		} else {
			$response = false;
		}
	
		$db->disconnect($conn);


		$mail = new PHPMailer(true);
		// SMTP settings for Mailtrap
		$mail->isSMTP();
		$mail->Host = 'smtp-mail.outlook.com';
		$mail->Port = 587;
		$mail->SMTPAuth = true;
		$mail->Username = 'jay@risksafe.co'; // Replace with your Mailtrap username
		$mail->Password = 'Welcome901#@!'; // Replace with your Mailtrap password
		$mail->SMTPSecure = 'TLS';
		$subject='New incident created';
		// Email content
		$mail->setfrom('jay@risksafe.co', 'Risksafe');
		$mail->addaddress('dev3.bdpl@gmail.com');
		//$mailto:mail->addaddress('jay@risksafe.co');
		$mail->Subject = $subject;
		// Email body
		$adminemail='jay@risksafe.co';
	
		//$body=// Email message (HTML content)
		$body='<!DOCTYPE html>
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
							<p>Dear Admin,</p>
							<p>
							We hope this message finds you well. We would like to inform you that a new incident has been successfully created on your risk-related site profile. Your vigilance in promptly reporting incidents contributes to our collective effort in maintaining a secure and risk-aware environment.
							</p>
							<tr>
							
							<td>Incident Id:</td> <td>'.$au.'</td>
							</tr>
						
							<tr>
							<td>
							Incident Title:
							</td><td>'.$row['in_title'].'</td> 
							</tr>
							<tr>
							<td>
							Financial:
							</td><td>'.$row['in_financial'].'</td> 
							</tr>
							<tr>
							<td>
							Complaints:
							</td>
							<td>
							'.$row['in_complaints'].'
							</td>
							</tr>
							<tr>
							<td>
							Date Created:
							</td>
							<td>'.$row['in_date'].'</td> 
							</tr>
						
							
						
						
							<p>
							Please be assured that your privacy and the confidentiality of any sensitive information related to this incident will be strictly maintained as per our established policies.</p>
<p>
Thank you for your immediate attention to this matter. Together, we can proactively address risks and ensure a secure environment for all stakeholders.
							</p>
							<p>Best regards,<br>Risksafe Team</p>
						
					
					<tr>
						<td bgcolor="#f9f9f9" align="center" style="padding: 20px 0 30px 0; font-family: Arial, sans-serif; font-size: 12px; color: #666666;">
							<p>&copy; <?php echo date("Y"); ?>RiskSafe. All rights reserved.</p>
						
						</td>
					</tr>
				</table>
			</body>
			
			</html>';
	
		$mail->Body = $body;
		$message = 'New Assessment Created';
		// Set the email body as HTML
		$mail->isHTML(true);
	
		$mail->addAddress($adminemail);
	
	
		if ($mail->send()) {
		//echo 'mailsent';
		return 1;
		} else {
		echo 'Mailer Error: ' . $mail->ErrorInfo;
		}
			
		
	}




}


?>