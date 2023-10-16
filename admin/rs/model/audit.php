<?php

include_once("../config.php");
include_once("db.php");

require '../../vendor/autoload.php';

// Include the AWS SDK for PHP
//require '../../vendor/aws/aws-autoloader.php';

// use Aws\Ses\SesClient;
// use Aws\Exception\AwsException;


use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

class audit
{


	public function __construct()
	{
	}

	public function listAudits($start, $end)
	{

		$db = new db;
		$conn = $db->connect();
		$query = "SELECT * FROM as_auditcontrols WHERE con_user=" . $_SESSION["userid"] . " ORDER BY idcontrol DESC, con_date DESC LIMIT " . $start . ", " . $end;
		if ($result = $conn->query($query)) {
			$data = array();
			while ($row = $result->fetch_assoc()) {
				$data[] = $row;
			}
			$response = $data;
		} else {
			$response = false;
		}
		$db->disconnect($conn);
		return $response;
	}
	public function listSubControl($cat, $selected = null)
	{
		$db = new db;
		$conn = $db->connect();
		$response = '<select name="subcontrol" id="subcontrol" class="form-control" required>';
		$response .= '<option value="">Please select control...</option>';
		$query = "SELECT * FROM as_subcontrol WHERE audit_id = " . $cat . " ORDER BY idsubcontrol";

		$result = $conn->query($query);

		while ($row = $result->fetch_assoc()) {
			$response .= '<option value="' . $row["idsubcontrol"] . '"';
			if ($row["idsubcontrol"] == $selected) { // Updated condition to check against $selected
				$response .= ' selected';
			}
			$response .= '>' . $row["sub_name"] . '</option>';
		}
		$response .= "</select>";
		$db->disconnect($conn);
		return $response;
	}

	public function listAuditControlsForReport($startDate, $endDate)
	{

		$db = new db;
		$conn = $db->connect();
		$query = "SELECT * FROM as_auditcontrols WHERE con_user=" . $_SESSION["userid"] . " AND con_date >= "
			. " '" . date("Y-m-d", strtotime($startDate)) . "' AND con_date <= '" . date("Y-m-d", strtotime($endDate)) . "' ORDER BY idcontrol DESC, con_date DESC";
		if ($result = $conn->query($query)) {
			$data = array();
			while ($row = $result->fetch_assoc()) {
				$data[] = $row;
			}
			$response = $data;
		} else {
			$response = false;
		}
		$db->disconnect($conn);
		return $response;
	}


	//LISTS ALL USERS CONTROLS TO COMBO BOX
	public function listAllControls($user)
	{

		$db = new db;
		$conn = $db->connect();
		$query = "SELECT * FROM as_ascontrols LEFT JOIN as_assessment ON as_ascontrols.ct_assessment=as_assessment.idassessment  WHERE as_assessment.as_user=" . $user . " ORDER BY as_ascontrols.ct_descript";

		$result = $conn->query($query);
		$response = "";
		while ($row = $result->fetch_assoc()) {
			$response .= '<option value="' . $row["idcontrol"] . '"';
			$response .= '>' . $row["ct_descript"] . '</option>';
		}
		$db->disconnect($conn);
		return $response;
	}

	public function listCriteria($id, $start, $end)
	{

		$db = new db;
		$conn = $db->connect();
		$query = "SELECT * FROM as_auditcriteria WHERE cri_control=" . $id . " ORDER BY idcriteria LIMIT " . $start . ", " . $end;

		if ($result = $conn->query($query)) {
			$data = array();
			while ($row = $result->fetch_assoc()) {
				$data[] = $row;
			}
			$response = $data;
		} else {
			$response = false;
		}
		$db->disconnect($conn);
		return $response;
	}

	public function listCriteriaForReport($id)
	{

		$db = new db;
		$conn = $db->connect();
		$query = "SELECT * FROM as_auditcriteria WHERE cri_control=" . $id . " ORDER BY idcriteria";

		if ($result = $conn->query($query)) {
			$data = array();
			while ($row = $result->fetch_assoc()) {
				$data[] = $row;
			}
			$response = $data;
		} else {
			$response = false;
		}
		$db->disconnect($conn);
		return $response;
	}

	public function listTypes($selected)
	{

		$db = new db;
		$conn = $db->connect();
		$query = "SELECT * FROM as_controls ORDER BY id";
		$result = $conn->query($query);
		$response = "";
		while ($row = $result->fetch_assoc()) {
			$response .= '<option data-id="' . $row["id"] . '" value="' . $row["control_name"] . '"';
			if ($selected == $row["control_name"]) $response .= ' selected';
			$response .= '>' . $row["control_name"] . '</option>';
		}

		$db->disconnect($conn);
		return $response;
	}
	public function getSubNames($controlId)
	{
		$db = new db;
		$conn = $db->connect();

		// Prepare and execute the query to fetch sub_names based on the selected control ID
		$stmt = $conn->prepare("SELECT sub_name FROM as_subcontrol WHERE audit_id = ?");
		$stmt->bind_param("i", $controlId);
		$stmt->execute();
		$result = $stmt->get_result();

		$subNames = [];
		while ($row = $result->fetch_assoc()) {
			$subNames[] = $row['sub_name'];
		}

		$db->disconnect($conn);
		return json_encode($subNames);
	}





	//ADD NEW AUDIT

	public function addAudit($company, $industry, $team, $task, $assessor, $site, $date, $time, $street, $building, $zipcode, $state, $country, $existing, $control, $audi_treatment, $Effectivness, $freq, $subControl, $next)
	{

		$db = new db();
		$conn = $db->connect();


		$typeOfControl = '';
		if ($control != '') {
			$typeOfControl = $control;
		} else {
			$typeOfControl = $existing;
		}

		$query = "INSERT INTO as_auditcontrols VALUES (0, " .
			$_SESSION["userid"] . ", " .
			"'" . $company . "', " .
			"'" . $industry . "', " .
			"'" . $team . "', " .
			"'" . $task . "', " .
			"'" . $assessor . "', " .
			"'" . $site . "', " .
			"'" . date("Y-m-d", strtotime($date)) . "', " .
			"'" . $time . "', " .
			"'" . $street . "', " .
			"'" . $building . "', " .
			"'" . $zipcode . "', " .
			"'" . $state . "', " .
			"'" . $country . "', " .
			"'" . $typeOfControl . "', " .
			"'" . $audi_treatment . "', " .
			"'" . $Effectivness . "', " .
			"'" . $subControl . "', " .
			"'" . date("Y-m-d", strtotime($next)) . "', " .
			"0, 0, 0, " .
			$freq . ");";


		if ($conn->query($query)) {
			$response = $conn->insert_id;
		} else {
			$response = false;
		}

		$db->disconnect($conn);
		return $response;
	}

	//EDIT AUDIT
	public function editAudit($id, $company, $industry, $team, $task, $assessor, $site, $date, $time, $street, $building, $zipcode, $state, $country, $control, $audi_treatment)
	{

		$db = new db();
		$conn = $db->connect();

		if (!$conn) {
			// Handle connection error
			die("Database connection error: " . mysqli_connect_error());
		}

		// Prepare the update query with placeholders for parameters
		$query = "UPDATE as_auditcontrols SET " .
			"con_company=?, " .
			"con_industry=?, " .
			"con_team=?, " .
			"con_task=?, " .
			"con_assessor=?, " .
			"con_site=?, " .
			"con_date=?, " .
			"con_time=?, " .
			"con_street=?, " .
			"con_building=?, " .
			"con_zipcode=?, " .
			"con_state=?, " .
			"con_country=?, " .
			"con_control=?, " .
			"aud_treatment=? WHERE idcontrol=?";

		// Create a prepared statement
		$stmt = $conn->prepare($query);

		// Bind parameters to the prepared statement
		$stmt->bind_param(
			"sssssssssssssssi",
			$company,
			$industry,
			$team,
			$task,
			$assessor,
			$site,
			date("Y-m-d", strtotime($date)),
			$time,
			$street,
			$building,
			$zipcode,
			$state,
			$country,
			$control,
			$audi_treatment,
			$id
		);

		// Execute the prepared statement
		if ($stmt->execute()) {
			$response = true;
		} else {
			// Handle query execution error
			echo "Error: " . $stmt->error;
			$response = false;
		}

		// Close the prepared statement and the database connection
		$stmt->close();
		$db->disconnect($conn);

		return $response;
	}


	//UPDATE CONTROL EFFECTIVNESS
	public function updateEffect($id, $effect, $observation, $rootcause, $treatment, $frequency)
	{

		$db = new db();
		$conn = $db->connect();

		$query = "UPDATE as_auditcontrols SET " .
			" con_effect='" . $effect . "', " .
			" con_observation='" . $observation . "', " .
			" con_rootcause='" . $rootcause . "', " .
			" con_treatment='" . $treatment . "', " .
			" con_frequency='" . $frequency . "' WHERE idcontrol=" . $id;


		if ($conn->query($query)) {
			$response = true;
		} else {
			$response = false;
		}

		$db->disconnect($conn);
		return $response;
	}

	public function addCriteria($control, $question, $procedure, $expected, $outcome, $notes)
	{

		$db = new db();
		$conn = $db->connect();
		$query = "INSERT INTO as_auditcriteria (cri_control, cri_question, cri_procedure, cri_expected, cri_outcome, cri_notes) 
						VALUES (?, ?, ?, ?, ?, ?)";

		$stm = $conn->prepare($query);
		$stm->bind_param(
			"isssis",
			$control,
			$question,
			$procedure,
			$expected,
			$outcome,
			$notes
		);
		if ($stm->execute()) {
			$response = $conn->insert_id;
		} else {
			// Handle query execution error
			echo "Error: " . $stm->error;
			$response = false;
		}

		// Close the prepared statement and the database connection
		$stm->close();
		$db->disconnect($conn);

		return $response;
	}


	public function editCriteria($id, $control, $question, $procedure, $expected, $outcome, $notes)
	{

		$db = new db();
		$conn = $db->connect();

		$query = "UPDATE as_auditcriteria SET "
			. "cri_control=?, "
			. "cri_question=?, "
			. "cri_procedure=?, "
			. "cri_expected=?, "
			. "cri_outcome=?, "
			. "cri_notes=? WHERE idcriteria=?";

		$stmt = $conn->prepare($query);
		$stmt->bind_param(
			"isssisi",
			$control,
			$question,
			$procedure,
			$expected,
			$outcome,
			$notes,
			$id
		);
		if ($stmt->execute()) {
			$response = true;
		} else {
			echo "Error: " . $stmt->error;
			$response = false;
		}

		$db->disconnect($conn);
		return $response;
	}

	public function deleteAudit($id)
	{

		$db = new db();
		$conn = $db->connect();

		$query = "DELETE FROM as_auditcontrols WHERE idcontrol=" . $id . ";";
		$query .= "DELETE FROM as_criteria WHERE cri_audit=" . $id . ";";

		if ($conn->multi_query($query)) {
			$response = true;
		} else {
			$response = false;
		}

		$db->disconnect($conn);
		return $response;
	}


	public function deleteCriteria($id)
	{

		$db = new db();
		$conn = $db->connect();

		$query = "DELETE FROM as_auditcriteria WHERE idcriteria=" . $id . ";";

		if ($conn->query($query)) {
			$response = true;
		} else {
			$response = false;
		}

		$db->disconnect($conn);
		return $response;
	}


	public function getControlInfo($id)
	{

		$db = new db;
		$conn = $db->connect();

		$query = "SELECT * FROM as_ascontrols LEFT JOIN as_assessment ON as_ascontrols.ct_assessment=as_assessment.idassessment  WHERE as_ascontrols.idcontrol=" . $id;

		$info = array();

		$result = $conn->query($query);
		if ($row = $result->fetch_assoc()) {
			$info["control"] = $row["ct_descript"];
			$info["team"] = $row["as_team"];
			$info["task"] = $row["as_task"];
			$info["assessor"] = $row["as_assessor"];
		}

		$db->disconnect($conn);
		return json_encode($info);
	}

	public function getAudit($id)
	{

		$db = new db();
		$conn = $db->connect();

		$query = "SELECT * FROM as_auditcontrols WHERE idcontrol=" . $id;

		if ($result = $conn->query($query)) {
			$row = $result->fetch_assoc();
			$response = $row;
		} else {
			$response = false;
		}

		$db->disconnect($conn);
		return $response;
	}

	public function getCriteria($id)
	{

		$db = new db();
		$conn = $db->connect();

		$query = "SELECT * FROM as_auditcriteria WHERE idcriteria=" . $id;

		if ($result = $conn->query($query)) {
			$row = $result->fetch_assoc();
			$response = $row;
		} else {
			$response = false;
		}

		$db->disconnect($conn);
		return $response;
	}

	public function getControlName($id)
	{

		$db = new db();
		$conn = $db->connect();
		$result = $conn->query("SELECT * FROM as_ascontrols WHERE idcontrol=" . $id);
		$row = $result->fetch_assoc();
		$response = $row["ct_descript"];
		$db->disconnect($conn);
		return $response;
	}


	public function getNext($date, $freq)
	{

		if ($freq == 0) {
			$next = "Not set";
		} else {
			$next = strtotime($date) + ($freq * 24 * 60 * 60);
			$next = date("m/d/Y", $next);
		}
		return $next;
	}

	public function getFrequency($freq)
	{

		if ($freq == 0) {
			return "Not set";
		} else if ($freq == 1) {
			return "Daily";
		} else if ($freq == 7) {
			return "Weekly";
		} else if ($freq == 30) {
			return "Monthly";
		} else if ($freq == 182) {
			return "6 Monthly";
		} else if ($freq == 365) {
			return "Yearly";
		}
	}

	public function getEffectiveness($effe)
	{
		if ($effe == 0) {
			return "Not Selected";
		} else if ($effe == 1) {
			return "Ineffective";
		} else if ($effe == 2) {
			return "Effective";
		}
	}

	public function getOutcome($out)
	{
		if ($out == 0) {
			return "N/A";
		} else if ($out == 1) {
			return "Pass";
		} else if ($out == 2) {
			return "Fail";
		}
	}


	public function savenotification($notifyby, $message, $readstatus, $status, $au)
	{

		$db = new db;
		$conn = $db->connect();
		//$date=date('Y-m-d');
		$query = "INSERT INTO as_notification (notification_by,notification_to,messageinfo,read_status,status)
		VALUES ('$notifyby','1','$message','$readstatus',0)";

		$response = true;
		try {
			if ($conn->query($query)) {
				$response = true;
				$this->sendNotificationEmail($notifyby, $au);
				$this->sendNotificationEmailadmin($au);
			} else {
				$response = false;
			}
		} finally {
			$db->disconnect($conn);
		}
		return $response;
	}


	public function sendNotificationEmail($notifyby, $au)
	{

		//$query ="SELECT * FROM  users WHERE iduser = '".$notifyby."';
		$db = new db;
		$conn = $db->connect();
		$query = "SELECT * FROM users WHERE iduser=" . $notifyby . "";

		if ($result = $conn->query($query)) {
			$row = $result->fetch_assoc();
			$response = $row;
			$query11 = "SELECT * FROM as_auditcontrols WHERE idcontrol=" . $au . "";
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
		// $mail->Host = 'smtp-mail.outlook.com';
		$mail->Host = 'sandbox.smtp.mailtrap.io';
		$mail->Port = 587;
		$mail->SMTPAuth = true;
		$mail->Username = '7aa331c1243777'; // Replace with your Mailtrap username
		// $mail->Username = 'jay@risksafe.co'; // Replace with your Mailtrap username
		$mail->Password = '979d7e4cc02893'; // Replace with your Mailtrap password
		// $mail->Password = 'Welcome901#@!'; // Replace with your Mailtrap password
		$mail->SMTPSecure = 'TLS';
		$subject = 'New Audit created';
		// Email content
		$mail->setfrom('jay@risksafe.co', 'Risksafe');
		$mail->addAddress($row['u_mail']);
		//$mailto:mail->addaddress('shwetachauhan035@gmail.com');
		$mail->Subject = $subject;
		// Email body

		//$body=// Email message (HTML content)
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
							<p>Dear ' . $row['u_mail'] . ',</p>
							<p>
							We are excited to announce that a new audit has been created on our platform. Your dedication to maintaining the highest standards of compliance and accountability is commendable.
							</p>
							<tr>
							
							<td>Audit Id:</td> <td>' . $au . '</td>
							</tr>
						
							<tr>
							<td>
							Treatment:
							</td><td>' . $row11['aud_treatment'] . '</td> 
							</tr>
							<tr>
							<td>
							Company:
							</td><td>' . $row11['con_company'] . '</td> 
							</tr>
							<tr>
							<td>
							Industry type:
							</td>
							<td>
							' . $row11['con_industry'] . '
							</td>
							</tr>
							<tr>
							<td>
							Date Created:
							</td>
							<td>' . $row11['con_date'] . '</td> 
							</tr>
						
							<tr>
							<td>
							Next Date:
							</td>
							<td>
							' . $row11['con_next'] . '
							</td>
							</tr>
						<p>
							As a responsible stakeholder, we kindly request your active participation in this audit process. Your cooperation and timely responses are essential to ensure the success of the audit and the overall improvement of our processes.</p>
							</p>
							<p>
							We highly value your commitment to maintaining transparency and adherence to best practices within our organization. By working together, we can achieve even greater levels of excellence and efficiency.</p>
<p>
Thank you for your continued dedication to ensuring the success and integrity of our operations.
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


	public function sendNotificationEmailadmin($au)
	{
		$db = new db;
		$conn = $db->connect();

		$query11 = "SELECT * FROM as_auditcontrols WHERE idcontrol=" . $au . "";
		if ($result11 = $conn->query($query11)) {
			$row11 = $result11->fetch_assoc();
			$response = $row11;
		} else {
			$response = false;
		}
		$mail = new PHPMailer(true);
		// SMTP settings for Mailtrap
		$mail->isSMTP();
		// $mail->Host = 'smtp-mail.outlook.com';
		$mail->Host = 'sandbox.smtp.mailtrap.io';
		$mail->Port = 587;
		$mail->SMTPAuth = true;
		$mail->Username = '7aa331c1243777'; // Replace with your Mailtrap username
		// $mail->Username = 'jay@risksafe.co'; // Replace with your Mailtrap username
		$mail->Password = '979d7e4cc02893'; // Replace with your Mailtrap password
		// $mail->Password = 'Welcome901#@!'; // Replace with your Mailtrap password
		$mail->SMTPSecure = 'TLS';
		$subject = 'New audit created';
		// Email content
		$mail->setfrom('jay@risksafe.co', 'Risksafe');
		$mail->addaddress('dev3.bdpl@gmail.com');
		//$mailto:mail->addaddress('shwetachauhan035@gmail.com');
		//$mailto:mail->addaddress('shwetachauhan035@gmail.com');
		$mail->Subject = $subject;
		// Email body
		//$adminemail='shwetachauhan035@gmail.com';

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
							<p>Dear Admin,</p>
							<p>
							We are excited to announce that a new audit has been created on our platform. Your dedication to maintaining the highest standards of compliance and accountability is commendable.
							</p>
							<tr>
							
							<td>Audit Id:</td> <td>' . $au . '</td>
							</tr>
						
							<tr>
							<td>
							Treatment:
							</td><td>' . $row11['aud_treatment'] . '</td> 
							</tr>
							<tr>
							<td>
							Company:
							</td><td>' . $row11['con_company'] . '</td> 
							</tr>
							<tr>
							<td>
							Industry type:
							</td>
							<td>
							' . $row11['con_industry'] . '
							</td>
							</tr>
							<tr>
							<td>
							Date Created:
							</td>
							<td>' . $row11['con_date'] . '</td> 
							</tr>
						
							<tr>
							<td>
							Next Date:
							</td>
							<td>
							' . $row11['con_next'] . '
							</td>
							</tr>
				
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
		$message = 'New Audit created';
		// Set the email body as HTML
		$mail->isHTML(true);

		//$mail->addAddress($adminemail);


		if ($mail->send()) {
			//echo 'mailsent';
			return 1;
		} else {
			echo 'Mailer Error: ' . $mail->ErrorInfo;
		}
	}



	public function cronjobfunctionaudit()
	{

		$db = new db();
		$conn = $db->connect();
		$currentdate = date('Y-m-d');

		$query = "SELECT as_auditcontrols.*,users.iduser,users.u_mail FROM as_auditcontrols INNER JOIN users ON as_auditcontrols.con_user = users.iduser where as_auditcontrols.con_next<='$currentdate'";


		if ($result = $conn->query($query)) {
			while ($row = $result->fetch_assoc()) {
				$usermail[] = $row['u_mail'];
				$response = true;
			}
		} else {

			$response = false;
		}



		if ($response == 1) {
			$this->sendNotificationEmailuser($usermail);
		} else {
			header("Location: ../view/audits.php?status=0");
		}
	}


	function sendNotificationEmailuser($usermail)
	{


		$mail = new PHPMailer(true);
		// SMTP settings for Mailtrap
		$mail->isSMTP();
		$mail->Host = 'smtp-mail.outlook.com';
		$mail->Port = 587;
		$mail->SMTPAuth = true;
		$mail->Username = 'jay@risksafe.co'; // Replace with your Mailtrap username
		$mail->Password = 'Welcome901#@!'; // Replace with your Mailtrap password
		$mail->SMTPSecure = 'tls';
		$subject = 'Treatment due date';
		// Email content
		$mail->setfrom('jay@risksafe.co', 'Risksafe Team');

		$mail->Subject = $subject;

		//implode(" ",$usermail);
		foreach ($usermail as $recipientEmail) {
			$mail->addAddress($recipientEmail);
		}

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
						<p>Dear User,</p>
						<p>It has come to our notice that your audit is overdue. Ensuring timely completion of audits is crucial for maintaining the integrity of our system and adhering to industry standards. We kindly request your immediate attention to this matter.</p>
						<br>
						<p>Thank you for your attention to this matter, and we appreciate your ongoing support in making our platform safer and more effective for everyone.</p>
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
			//echo 'mailsent';
			header("Location: ../view/audits.php?status=1");
			exit();
			//return 1;
		} else {
			echo 'Mailer Error: ' . $mail->ErrorInfo;
		}
	}
}
