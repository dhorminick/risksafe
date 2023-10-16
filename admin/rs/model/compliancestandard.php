<?php
include_once("../config.php");
include_once("db.php");

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../../vendor/autoload.php';


class compliancestandard
{
	public function __construct()
	{
	}

	public function listCompliances($start, $length)
	{
		$db = new db();
		$conn = $db->connect();
		$query = "SELECT * FROM as_compliancestandard WHERE com_user_id = " . $_SESSION["userid"] . " LIMIT $start, $length";

		$result = $conn->query($query);

		if ($result !== false && $result->num_rows > 0) {
			$data = array();
			while ($row = $result->fetch_assoc()) {
				$data[] = $row;
			}

			$db->disconnect($conn);
			return $data;
		} else {
			$db->disconnect($conn);
			return false;
		}
	}

	public function addCompliance($compliancestandard, $legislation, $control, $training, $compliancestatus, $officer, $targetFilePath, $existing_tr, $existing_ct)
	{
		$db = new db();
		$conn = $db->connect();

		$userId = $_SESSION["userid"];
		$query = "INSERT INTO as_compliancestandard (com_user_id, com_compliancestandard, com_legislation, com_controls, com_training, co_status, com_officer, com_documentation,existing_tr,existing_ct) VALUES ('$userId', '$compliancestandard', '$legislation', '$control', '$training', '$compliancestatus', '$officer', '$targetFilePath','$existing_tr','$existing_ct')";
		$sql = mysqli_query($conn, $query);
		if ($sql) {
			return mysqli_insert_id($conn); // Return the ID of the newly inserted row
		} else {
			return false;
		}
	}

	public function editCompliance($id, $compliancestandard, $legislation, $control, $training, $compliancestatus, $officer, $targetFilePath, $existing_tr, $existing_ct)
	{
		$db = new db();
		$conn = $db->connect();

		// Construct the update query
		$query = "UPDATE as_compliancestandard SET com_compliancestandard = '$compliancestandard', com_legislation = '$legislation', com_controls = '$control', com_training = '$training', co_status = '$compliancestatus', com_officer = '$officer',existing_ct ='$existing_ct',existing_tr='$existing_tr'";

		// Check if a file is uploaded
		if ($fileName) {
			$query .= ", com_documentation = '$targetFilePath'";
		}

		$query .= " WHERE idcompliance = '$id'";

		$result = mysqli_query($conn, $query);

		return $result;
	}

	public function deleteCompliance($id)
	{
		$db = new db();
		$conn = $db->connect();

		$query = "DELETE FROM as_compliancestandard WHERE idcompliance = '$id'";

		$result = mysqli_query($conn, $query);

		return $result;
	}

	public function getControlData($id)
	{
		$db = new db();
		$conn = $db->connect();
		if ($id !== '-1') {
			$query = "SELECT * FROM as_auditcontrols WHERE idcontrol ='$id'";
			$result = mysqli_query($conn, $query);
			$response = "";
			while ($row = $result->fetch_assoc()) {

				$response .= '<option value="' . $row["idcontrol"] . '">' . $row["con_control"] . '</option>';
			}
		} else {
			$response = '<option value="-1" selected>Select and add an existing control</option>';
		}



		$db->disconnect($conn);
		return $response;
	}

	public function getTreatmentData($id)
	{
		$db = new db();
		$conn = $db->connect();
		if ($id !== '-1') {
			$query = "SELECT * FROM as_treatments WHERE idtreatment ='$id'";
			$result = mysqli_query($conn, $query);
			$response = "";
			while ($row = $result->fetch_assoc()) {

				$response .= '<option value="' . $row["idtreatment"] . '">' . $row["tre_treatment"] . '</option>';
			}
		} else {
			$response = '<option value="-1" selected>Select and add an existing treatment</option>';
		}

		$db->disconnect($conn);
		return $response;
	}
	public function listTreatmentsLib($user, $treatmentdata =null)
	{
		$db = new db;
		$conn = $db->connect();
	
		// Using prepared statements to prevent SQL injection
		$stmt = $conn->prepare("SELECT * FROM as_treatments WHERE tre_user = ? ORDER BY tre_treatment");
		$stmt->bind_param("s", $user);
		$stmt->execute();
		$result = $stmt->get_result();
		
		$response = "";
		$optionText ='';
	   if($treatmentdata !==null){
		$dom = new DOMDocument();
		$dom->loadHTML($treatmentdata);
	
		$optionText = $dom->getElementsByTagName('option')->item(0)->textContent;
	   }
		
	
		while ($row = $result->fetch_assoc()) {
			
			if ($row['tre_treatment'] === $optionText) {
				continue;
			}
			
			$response .= '<option value="' . htmlspecialchars($row["idtreatment"]) . '">' .htmlspecialchars($row["tre_treatment"]). '</option>';
		}
	
		$stmt->close();
		$db->disconnect($conn);
		return $response;
	}





public function listControlsLib($user, $controldata = null )
{
    $db = new db;
    $conn = $db->connect();

    $query = "SELECT * FROM as_auditcontrols WHERE con_user=" . $user . " ORDER BY con_control";
    $result = $conn->query($query);
    $response = "";
	$optionText ='';
	if($controldata!== null){
		  // Use DOMDocument to extract the text content of the <option> element
		  $dom = new DOMDocument();
		  $dom->loadHTML($controldata);
		  
		  $optionText = $dom->getElementsByTagName('option')->item(0)->textContent;
	}
  
    while ($row = $result->fetch_assoc()) {
        if ($row['con_control'] === $optionText) {
            continue;
        }

        // Generate HTML options for the dropdown menu
        $response .= '<option value="' . htmlspecialchars($row["idcontrol"]) . '">' . htmlspecialchars($row["con_control"]) . '</option>';
    }

    $db->disconnect($conn);
    return $response;
}

public function listControl($user,$controldata=null){
	$db=new db;
	$conn=$db->connect();
	$response="";
	$query="SELECT * FROM as_controls ORDER BY id";
	$response1 = $this->listControlsLib($user,$controldata);
	$result=$conn->query($query);
	while ($row=$result->fetch_assoc()) {
		$response.='<option value="' . $row["id"] . '">' . $row["control_name"] . '</option>';
	}
	$db->disconnect($conn);
	return $response. $response1;
}




public function addControl($id, $descript, $tmp, $assessment) {
	
	$db=new db;
	$conn=$db->connect();
	$query="INSERT INTO as_ascontrols VALUES (0, " . $id . ", '" . $descript . "', " . $tmp . ", " . $assessment . ");";
	
	if ($conn->query($query)) {
		$response=true;		
	} else {
		$response=false;
	}
	
	$db->disconnect($conn);
	return $response;

}



	public function getCompliance($id)
	{
		$db = new db();
		$conn = $db->connect();

		$query = "SELECT * FROM as_compliancestandard WHERE idcompliance = '$id'";

		$result = mysqli_query($conn, $query);
		$row = mysqli_fetch_assoc($result);

		return $row;
	}

	public function savenotification($notifyby, $message, $readstatus, $status, $au)
	{

		$db = new db;
		$conn = $db->connect();
		//$date=date('Y-m-d');
		$query = "INSERT INTO as_notification (notification_by,notification_to,messageinfo,read_status,status)
		VALUES ('$notifyby','1','$message','$readstatus',0)";

		if ($conn->query($query)) {

			$response = true;


			$this->sendNotificationEmail($notifyby, $au);
			$this->sendNotificationEmailadmin($au);
		} else {
			$response = false;
		}

		$db->disconnect($conn);
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
			$query11 = "SELECT * FROM as_compliancestandard WHERE idcompliance=" . $au . "";
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
		$subject = 'New compliance standard created';
		// Email content
		$mail->setfrom('jay@risksafe.co', 'Risksafe Team');
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
						<p>Dear ' . $row['u_name'] . ',</p>
						<p>
						We are pleased to inform you that your Compliance standard has been successfully created on our platform. Your proactive approach towards managing Compliance standard demonstrates your commitment to ensuring a safer and more secure environment.
				</p>
						<p>
							
						The details of the Compliance standard as follows:
						<br>
						ID: &nbsp;&nbsp;&nbsp;&nbsp;' . $au . '
						<br>
						Compliance standard:&nbsp;&nbsp;&nbsp; ' . $row11['com_compliancestandard'] . '
						<br>
						Legislation:&nbsp;&nbsp;&nbsp; ' . $row11['com_legislation'] . '
						<br>
						Training:&nbsp;&nbsp;&nbsp; ' . $row11['com_training'] . '
						<br>
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
		$query = "SELECT * FROM as_compliancestandard WHERE idcompliance=" . $au . "";
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
		$mail->SMTPSecure = 'tls';
		$subject = 'New compliance standard created';
		// Email content
		$mail->setfrom('jay@risksafe.co', 'Risksafe Team');

		$mail->Subject = $subject;
		// Email body
		$adminemail = 'jay@risksafe.co';

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
				We are pleased to inform you that your Compliance standard has been successfully created on our platform. Your proactive approach towards managing Compliance standard demonstrates your commitment to ensuring a safer and more secure environment.
				</p>
						<p>
							
						The details of the compliance standard are as follows:
						<br>
						ID:&nbsp;&nbsp;&nbsp;&nbsp; ' . $au . '
						<br>
						Compliance standard:&nbsp;&nbsp;&nbsp;&nbsp; ' . $row['com_compliancestandard'] . '
						<br>
						Legislation:&nbsp;&nbsp;&nbsp;&nbsp; ' . $row['com_legislation'] . '
						<br>
						Training:&nbsp;&nbsp;&nbsp;&nbsp; ' . $row['com_training'] . '
						<br>
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
