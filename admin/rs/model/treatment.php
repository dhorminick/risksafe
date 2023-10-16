<?php

include_once("../config.php");
include_once("db.php");
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
require '../../vendor/autoload.php';

class treatment
{


	public function __construct()
	{


	}

	public function listTreatments($start, $end)
	{

		$db = new db;
		$conn = $db->connect();
		$query = "SELECT * FROM as_treatments WHERE tre_user=" . $_SESSION["userid"] . " ORDER BY idtreatment DESC, tre_start DESC LIMIT " . $start . ", " . $end;
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


	//LISTS ALL USERS TREATMENTS TO COMBO BOX
	public function listAllTreatments($user)
	{

		$db = new db;
		$conn = $db->connect();
		$query = "SELECT * FROM as_astreat LEFT JOIN as_assessment ON as_astreat.tr_assessment=as_assessment.idassessment WHERE as_assessment.as_user=" . $user . " ORDER BY as_astreat.tr_descript";

		$result = $conn->query($query);
		$response = "";
		while ($row = $result->fetch_assoc()) {
			$response .= '<option value="' . $row["idtreat"] . '"';
			$response .= '>' . $row["tr_descript"] . '</option>';
		}
		$db->disconnect($conn);
		return $response;

	}

	public function listTreatmentsForReport()
	{

		$db = new db;
		$conn = $db->connect();
		$query = "SELECT * FROM as_treatments WHERE tre_user=" . $_SESSION["userid"] . " ORDER BY idtreatment DESC";
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

	//ADD NEW TREATMENT
	public function addTreatment($assessor, $team, $treatment, $existing, $cost_ben, $progress, $owner, $start, $due, $status)
	{

		$db = new db();
		$conn = $db->connect();

		$typeOftreatment = '';
		if ($treatment != '') {
			$typeOftreatment = $treatment;
		} else {
			$typeOftreatment = $existing;
		}


		$query = "INSERT INTO as_treatments VALUES (0, " .
			"" . $_SESSION["userid"] . ", " .
			"'" . $team . "', " .
			"'" . $assessor . "', " .
			"'" . $typeOftreatment . "', " .
			"'" . $cost_ben . "', " .
			"'" . str_replace("'", "\'", $progress) . "', " .
			"'" . $owner . "', " .
			"'" . date("Y-m-d", strtotime($start)) . "', " .
			"'" . date("Y-m-d", strtotime($due)) . "', " .
			"" . $status . ");";



		if ($conn->query($query)) {
			$response = $conn->insert_id;
		} else {
			$response = false;
		}

		$db->disconnect($conn);
		return $response;

	}

	//EDIT AUDIT
	public function editTreatment($id, $assessor, $team, $treatment, $existing, $cost_ben, $progress, $owner, $start, $due, $status)
	{

		$db = new db();
		$conn = $db->connect();

		$typeOftreatment = '';
		if ($treatment != '') {
			$typeOftreatment = $treatment;
		} else {
			$typeOftreatment = $existing;
		}


		$query = "UPDATE as_treatments SET " .
			" tre_assessor='" . $assessor . "', " .
			" tre_team='" . $team . "', " .
			" tre_treatment='" . $typeOftreatment . "', " .
			" tre_cost_ben='" . $cost_ben . "', " .
			" tre_progress='" . str_replace("'", "\'", $progress) . "', " .
			" tre_owner='" . $owner . "', " .
			" tre_start='" . date("Y-m-d", strtotime($start)) . "', " .
			" tre_due='" . date("Y-m-d", strtotime($due)) . "', " .
			" tre_status=" . $status . " WHERE idtreatment=" . $id;


		if ($conn->query($query)) {
			$response = true;
		} else {
			$response = false;
		}

		$db->disconnect($conn);
		return $response;

	}

	public function deleteTreatment($id)
	{

		$db = new db();
		$conn = $db->connect();

		$query = "DELETE FROM as_treatments WHERE idtreatment=" . $id . ";";

		if ($conn->multi_query($query)) {
			$response = true;
		} else {
			$response = false;
		}

		$db->disconnect($conn);
		return $response;

	}



	public function getTreatmentInfo($id)
	{

		$db = new db;
		$conn = $db->connect();

		$query = "SELECT * FROM as_astreat LEFT JOIN as_assessment ON as_astreat.tr_assessment=as_assessment.idassessment  WHERE as_astreat.idtreat=" . $id;

		$info = array();

		$result = $conn->query($query);
		if ($row = $result->fetch_assoc()) {
			$info["treatment"] = $row["tr_descript"];
			$info["team"] = $row["as_team"];
			$info["assessor"] = $row["as_assessor"];
		}

		$db->disconnect($conn);
		return json_encode($info);

	}

	public function getTreatment($id)
	{

		$db = new db();
		$conn = $db->connect();

		$query = "SELECT * FROM as_treatments WHERE idtreatment=" . $id;

		if ($result = $conn->query($query)) {
			$row = $result->fetch_assoc();
			$response = $row;
		} else {
			$response = false;
		}

		$db->disconnect($conn);
		return $response;

	}

	public function getTreatmentName($id)
	{

		$db = new db();
		$conn = $db->connect();
		$result = $conn->query("SELECT * FROM as_astreat WHERE idtreat=" . $id);
		$row = $result->fetch_assoc();
		$response = $row["tr_descript"];
		$db->disconnect($conn);
		return $response;
	}


	public function getDays($start, $due)
	{

		$next = strtotime($due) - strtotime($start);
		$next = ceil($next / 60 / 60 / 24);

		return $next;
	}

	public function cronjobfunction(){

		$db = new db();
		$conn = $db->connect();
		$currentdate=date('Y-m-d');

		   $query = "SELECT as_treatments.*,users.iduser,users.u_mail FROM as_treatments INNER JOIN users ON as_treatments.tre_user = users.iduser where as_treatments.tre_due<='$currentdate'";

		  if ($result = $conn->query($query)){
			while ($row = $result->fetch_assoc()) {
				$usermail[] = $row['u_mail'];
				$response = true;
			}

		}else{

			$response = false;

		}

			if($response==1){
				$this->sendNotificationEmailadmin($usermail);
			}else{
				header("Location: ../view/treatments.php?status=0");
			}
		}


	public function sendNotificationEmailadmin($usermail) {

			$mail = new PHPMailer(true);
			// SMTP settings for Mailtrap
			$mail->isSMTP();
			$mail->Host = 'smtp-mail.outlook.com';
			$mail->Port = 587;
			$mail->SMTPAuth = true;
			$mail->Username = 'jay@risksafe.co'; // Replace with your Mailtrap username
			$mail->Password = 'Welcome901#@!'; // Replace with your Mailtrap password
			$mail->SMTPSecure = 'tls';
			$subject='Treatment due date';
			// Email content
			$mail->setfrom('jay@risksafe.co', 'Risksafe Team');

			//$mailto:mail->addaddress('shwetachauhan035@gmail.com');
			//$mailto:mail->addaddress('shwetachauhan035@gmail.com');
			$mail->Subject = $subject;

			//implode(" ",$usermail);

			foreach ($usermail as $recipientEmail) {
				$mail->addAddress($recipientEmail);
			}
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
							<p>Dear User,</p>
							<p>We are reaching out to you regarding your treatment progress on our website related to risks, treatments, and incidents. Our records indicate that your treatment is currently overdue, and we want to ensure you receive the best care and support.</p>

							<p>Thank you for your attention to this matter. If you have any questions or need further assistance, please feel free to contact us at Risk Safe team.

							Wishing you good health and well-being.</p>
							
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
		//	echo 'mailsent';
			header("Location: ../view/treatments.php?status=1");
			exit();
			//return 1;
			} else {
			echo 'Mailer Error: ' . $mail->ErrorInfo;
			}
				
			
		}







	

}

?>