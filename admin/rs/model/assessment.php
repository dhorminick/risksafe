<?php

// include_once("../rs/config.php");
include_once("db.php");
// require '../../../../vendor/autoload.php';
// include("../mail.php");
// include("../../rs/mail.php");

// include_once("../rs/config.php");
// include_once("../rs/model/db.php");
// require '../../vendor/autoload.php';
// include("../rs/mail.php");


//require 'vendor/PHPMailer/PHPMailerAutoload.php';

// Include the AWS SDK for PHP
//require '../../vendor/aws/aws-autoloader.php';

// use Aws\Ses\SesClient;
// use Aws\Exception\AwsException;


use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

class assessment {

	
	public function __construct(){	
	}
	
	public function listAssessments($start, $end) {
	
		$db=new db;
		$conn=$db->connect();
		$query="SELECT * FROM as_assessment WHERE as_user=".$_SESSION["userid"]." ORDER BY idassessment DESC, as_date DESC LIMIT " . $start . ", " . $end;
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
	public function listAssessmentsOfAml($start, $end) {
	
		$db=new db;
		$conn=$db->connect();
		$query="SELECT * FROM as_assessment WHERE as_user=".$_SESSION["userid"]." AND as_type = 5 ORDER BY idassessment DESC, as_date DESC LIMIT " . $start . ", " . $end;
		// $query = "SELECT * FROM as_assessment WHERE as_user = ".$_SESSION["userid"]. " AND as_type=5 ORDER BY idassessment DESC, as_date DESC
		// LIMIT " . $start . "," .$end;
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
	
	public function listAssessment($assessmentId, $start, $end) {
	
		$db=new db;
		$conn=$db->connect();
		$query="SELECT * FROM as_details LEFT JOIN as_risks ON as_risks.idrisk=as_details.as_risk LEFT JOIN as_cat ON as_cat.idcat=as_details.as_hazard WHERE as_details.as_assessment=".$assessmentId." ORDER BY as_details.iddetail LIMIT " . $start . ", " . $end;
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
	
	public function getBusinessType($id) {
	
		$db=new db;
		$conn=$db->connect();
		$query="SELECT * FROM as_types WHERE idtype=" . $id;
		$result=$conn->query($query);
		if ($row=$result->fetch_assoc()) {
			$response=$row["ty_name"];
		}
		
		$db->disconnect($conn);
		return $response;
	
	}
	
	
	public function getAssessment($id) {
	
		$db=new db;
		$conn=$db->connect();
		$query="SELECT * FROM as_assessment LEFT JOIN as_types ON as_assessment.as_type  = as_types.idtype WHERE idassessment=".$id;
		if ($result=$conn->query($query)) {	
			$row=$result->fetch_assoc();
			$response=$row;
		} else {
			$response=false;	
		}
		$db->disconnect($conn);
		return $response;
	
	}
	
	public function getAssessmentDet($id) {
	
		$db=new db;
		$conn=$db->connect();
		$query="SELECT * FROM as_details LEFT JOIN as_risks ON as_risks.idrisk=as_details.as_risk LEFT JOIN as_cat ON as_cat.idcat=as_details.as_hazard WHERE as_details.iddetail=".$id;
		if ($result=$conn->query($query)) {	
			$row=$result->fetch_assoc();
			$response=$row;
		} else {
			$response=false;	
		}
		$db->disconnect($conn);
		return $response;
	
	}
	
	public function getAssessmentDetForReport($id) {
	
		$db=new db;
		$conn=$db->connect();
		$query="SELECT * FROM as_details LEFT JOIN as_risks ON as_risks.idrisk=as_details.as_risk "
				." LEFT JOIN as_cat ON as_cat.idcat=as_details.as_hazard LEFT JOIN as_like ON as_like.idlike=as_details.as_like "
				." LEFT JOIN as_consequence ON as_consequence.idconsequence = as_details.as_consequence "
				." LEFT JOIN as_actiontype ON as_actiontype.idaction=as_details.as_action WHERE as_details.as_assessment=".$id;
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
	
	public function deleteDetail($id) {
	
		$db=new db;
		$conn=$db->connect();
		$query="DELETE FROM as_details WHERE iddetail=".$id.";";
		$query.="DELETE FROM as_astreat WHERE tr_det=".$id.";";
		$query.="DELETE FROM as_ascontrols WHERE ct_det=".$id.";";
		if ($conn->multi_query($query)) {	
			$response=true;
		} else {
			$response=false;	
		}
		$db->disconnect($conn);
		return $response;
	
	}
	
	public function deleteAssessment($id) {
		try {
			$db = new db;
			$conn = $db->connect();
			$query = "SELECT * FROM as_details WHERE as_assessment=" . $id;
			$result = $conn->query($query);
			while ($row = $result->fetch_assoc()) {
				$query = "DELETE FROM as_astreat WHERE tr_det=" . $row["iddetail"] . ";";
				$query .= "DELETE FROM as_ascontrols WHERE ct_det=" . $row["iddetail"] . ";";
				if (!$conn->multi_query($query)) {
					throw new Exception("Failed to delete assessment details");
				}
				// clear the buffer before executing the next query
				while ($conn->next_result()) {}
			}
			$query = "DELETE FROM as_assessment WHERE idassessment=" . $id . ";";
			$query .= "DELETE FROM as_details WHERE as_assessment=" . $id . ";";
			if (!$conn->multi_query($query)) {
				throw new Exception("Failed to delete assessment");
			}
			// clear the buffer before closing the connection
			while ($conn->next_result()) {}
			$db->disconnect($conn);
			return true;
		} catch (Exception $e) {
			// Log the exception or handle it as per your requirement.
			return false;
		}
	}

	public function listTypes($selected) {
	
		$db=new db;
		$conn=$db->connect();
		$query="SELECT * FROM as_types ORDER BY idtype";
		$result=$conn->query($query);
		$response="";
		while ($row=$result->fetch_assoc()) {
			$response.='<option value="' . $row["idtype"] . '"';
			if ($selected==$row["idtype"]) $response.=' selected';
			$response.='>' . $row["ty_name"] . '</option>';
		}
		
		$db->disconnect($conn);
		return $response;
	
	}
	
	public function listLikelihood($selected) {
	
		$db=new db;
		$conn=$db->connect();
		$response='<select name="likelihood" id="likelihood" class="form-control" required>';
		$response.='<option value="">Please select likelihood...</option>';
		$query="SELECT * FROM as_like ORDER BY idlike";
		$result=$conn->query($query);
		while ($row=$result->fetch_assoc()) {
			$response.='<option value="' . $row["idlike"] . '"';
			if ($row["idlike"]==$selected) $response.=' selected';
			$response.='>' . $row["li_like"] . '</option>';
		}
		$response.="</select>";
		$db->disconnect($conn);
		return $response;
	
	}
	
	public function listConsequence($selected) {
	
		$db=new db;
		$conn=$db->connect();
		$response='<select name="consequence" id="consequence" class="form-control" required>';
		$response.='<option value="">Please select consequence...</option>';
		$query="SELECT * FROM as_consequence ORDER BY idconsequence";
		$result=$conn->query($query);
		while ($row=$result->fetch_assoc()) {
			$response.='<option value="' . $row["idconsequence"] . '"';
			if ($row["idconsequence"]==$selected) $response.=' selected';
			$response.='>' . $row["con_consequence"] . '</option>';
		}
		$response.="</select>";
		$db->disconnect($conn);
		return $response;
	
	}
	
	public function calculateRating($like, $consequence) {
	
		$db=new db;
		$conn=$db->connect();
		$result=$conn->query("SELECT * FROM as_consequence WHERE idconsequence=".$consequence);
		$con=$result->fetch_assoc();
		$result=$conn->query("SELECT * FROM as_like WHERE idlike=".$like);
		$li=$result->fetch_assoc();
		
		$sum=$li["li_value"]+$con["con_value"];
		if ($li["li_value"]==$con["con_value"]) $sum++; //when both 3 calse says medium but chart says high, so this is a hack
		switch ($sum) {
		
			case ($sum<=4):
				$response=1;
				break;
				
			case ($sum>4 and $sum<7):
				$response=2;
				break;
				
			case ($sum==7):
				$response=3;
				break;
				
			case ($sum>7):
				$response=4;
				break;
		}
		
		$db->disconnect($conn);
		return $response;
	
	}
	
	public function listActions($selected) {
	
		$db=new db;
		$conn=$db->connect();
		$response='<select name="actiontake" id="actiontake" class="form-control" required>';
		$response.='<option value="">Please select action to take...</option>';
		$query="SELECT * FROM as_actiontype ORDER BY idaction";
		$result=$conn->query($query);
		while ($row=$result->fetch_assoc()) {
			$response.='<option value="' . $row["idaction"] . '"';
			if ($row["idaction"]==$selected) $response.=' selected';
			$response.='>' . $row["ac_type"] . '</option>';
		}
		$response.="</select>";
		$db->disconnect($conn);
		return $response;
	
	}
	
	public function listHazards($cat, $selected) {
	
		$db=new db;
		$conn=$db->connect();
		$response='<select name="hazard" id="hazard" class="form-control" required>';
		$response.='<option value="">Please select risk sub category...</option>';
		$query="SELECT * FROM as_cat WHERE cat_risk=" . $cat . " ORDER BY idcat";
		$result=$conn->query($query);
		while ($row=$result->fetch_assoc()) {
			$response.='<option value="' . $row["idcat"] . '"';
			if ($row["idcat"]==$selected) $response.=' selected';
			$response.='>' . $row["cat_name"] . '</option>';
		}
		$response.="</select>";
		$db->disconnect($conn);
		return $response;
	
	}
	
	public function listRisks($type, $selected) {
	
		$db=new db;
		$conn=$db->connect();
		$response='<select name="risk" id="risk" class="form-control" required>';
		$response.='<option value="">Please select risk...</option>';
		$query="SELECT * FROM as_risks WHERE ri_type=" . $type . " ORDER BY idrisk";
		$result=$conn->query($query);
		while ($row=$result->fetch_assoc()) {
			$response.='<option value="' . $row["idrisk"] . '"';
			if ($row["idrisk"]==$selected) $response.=' selected';
			$response.='>' . $row["ri_name"] . '</option>';
		}
		$response.="</select>";
		
		$db->disconnect($conn);
		return $response;
	
	}
	
	public function listControlsLib($user) {
	
		$db=new db;
		$conn=$db->connect();
		$query="SELECT * FROM as_auditcontrols WHERE con_user=" . $user . " ORDER BY con_control";
		$result=$conn->query($query);
		$response="";
		while ($row=$result->fetch_assoc()) {
			$response.='<option value="' . $row["idcontrol"] . '">' . $row["con_control"] . '</option>';
		}
		
		$db->disconnect($conn);
		return $response;
	
	}
	
	public function listTreatmentsLib($user) {
	
		$db=new db;
		$conn=$db->connect();
		$query="SELECT * FROM as_treatments WHERE tre_user=" . $user . " ORDER BY tre_treatment";
		$result=$conn->query($query);
		$response="";
		while ($row=$result->fetch_assoc()) {
			$response.='<option value="' . $row["idtreatment"] . '">' . $row["tre_treatment"] . '</option>';
		}
		
		$db->disconnect($conn);
		return $response;
	
	}

	public function getControlName($id, $tableName="as_auditcontrols", $filter="idcontrol",$get="con_control") {
	
		$db=new db;
		$conn=$db->connect();
		$query="SELECT * FROM ".$tableName." WHERE ".$filter."=" . $id;
		$result=$conn->query($query);
		if ($row=$result->fetch_assoc()) {
			$response=$row[$get];
		}
		
		$db->disconnect($conn);
		return $response;
	
	}
	
	public function getTreatmentName($id) {
	
		$db=new db;
		$conn=$db->connect();
		$query="SELECT * FROM as_treatments WHERE idtreatment=" . $id;
		$result=$conn->query($query);
		if ($row=$result->fetch_assoc()) {
			$response=$row["tre_treatment"];
		}
		
		$db->disconnect($conn);
		return $response;
	
	}
	
	public function addControl($id, $descript="", $tmp="", $assessment="") {
	
		$db=new db;
		$conn=$db->connect();
		$query="INSERT INTO as_ascontrols (ct_det, ct_descript, ct_tmpid, ct_assessment) VALUES (" . $id . ", '" . $descript . "', " . $tmp . ", " . $assessment . ");";
		
		if ($conn->query($query)) {
			$response=true;		
		} else {
			$response=false;
		}
		
		$db->disconnect($conn);
		return $response;
	
	}

	public function listControl($user){
		$db=new db;
		$conn=$db->connect();
		$response="";
		$query="SELECT * FROM as_controls ORDER BY id";
		$response1 = $this->listControlsLib($user);
		$result=$conn->query($query);
		while ($row=$result->fetch_assoc()) {
			$response.='<option value="' . $row["id"] . '">' . $row["control_name"] . '</option>';
		}
		$db->disconnect($conn);
		return $response. $response1;
	}

	public function listControls($id) {
	
		$db=new db;
		$conn=$db->connect();
		if ($id==-1) {
			$query="SELECT * FROM as_ascontrols WHERE ct_tmpid=" . $_SESSION["assessment"] . ";";
		} else {
			$query="SELECT * FROM as_ascontrols WHERE ct_det=" . $id . ";";
		}
		
		$response="";
		
		if ($result=$conn->query($query)) {
			$response.='<table width="100%">';
			while ($row=$result->fetch_assoc()) {
				$response.='<tr><td style="padding:3px;">' . $row["ct_descript"]. '</td><td width="20"><a href="javascript:del(\'control\',' . $row["idcontrol"] . ');"><button title="Delete control from risk" type="button" class="btn btn-xs btn-danger"><i class=" glyphicon glyphicon-remove"></i></button></a></td></tr>';
			}
			$response.='</table>';		
		} else {
			$response=false;
		}
		
		$response.='&nbsp;';
		
		$db->disconnect($conn);
		return $response;
	
	}
	
	public function addTreatment($id, $descript, $tmp, $assessment) {
	
		$db=new db;
		$conn=$db->connect();
		$query="INSERT INTO as_astreat VALUES (0, " . $id . ", '" . $descript . "', " . $tmp . ", " . $assessment . ");";
		
		if ($conn->query($query)) {
			$response=true;		
		} else {
			$response=false;
		}
		
		$db->disconnect($conn);
		return $response;
	
	}
	
	
	public function deleteTreatment($id) {
	
		$db=new db;
		$conn=$db->connect();
		$query="DELETE FROM as_astreat WHERE idtreat=".$id;
		
		if ($conn->query($query)) {
			$response=true;		
		} else {
			$response=false;
		}
		
		$db->disconnect($conn);
		return $response;
	
	}
	
	public function deleteControl($id) {
	
		$db=new db;
		$conn=$db->connect();
		$query="DELETE FROM as_ascontrols WHERE idcontrol=".$id;
		
		if ($conn->query($query)) {
			$response=true;		
		} else {
			$response=false;
		}
		
		$db->disconnect($conn);
		return $response;
	
	}
	
	public function listTreatments($id) {
	
		$db=new db;
		$conn=$db->connect();
		if ($id==-1) {
			$query="SELECT * FROM as_astreat WHERE tr_tmpid=" . $_SESSION["assessment"] . ";";
		} else {
			$query="SELECT * FROM as_astreat WHERE tr_det=" . $id . ";";
		}
		$response="";
		
		if ($result=$conn->query($query)) {
			$response.='<table width="100%">';
			while ($row=$result->fetch_assoc()) {
				$response.='<tr><td style="padding:3px;">' . $row["tr_descript"]. '</td><td width="20"><a href="javascript:del(\'treatment\',' . $row["idtreat"] . ');"><button title="Delete treatment from risk" type="button" class="btn btn-xs btn-danger"><i class=" glyphicon glyphicon-remove"></i></button></a></td></tr>';
			}
			$response.='</table>';		
		} else {
			$response=false;
		}
		$response.='&nbsp;';
		
		$db->disconnect($conn);
		return $response;
	
	}
	
	public function listTreatmentsForReport($id) {
	
		$db=new db;
		$conn=$db->connect();
		$query="SELECT * FROM as_astreat WHERE tr_det=" . $id . ";";
		$response="";
		
		if ($result=$conn->query($query)) {
			while ($row=$result->fetch_assoc()) {
				$response.=$row['tr_descript']."\n";
			}		
		} else {
			$response=false;
		}				
		$db->disconnect($conn);
		return $response;
	
	}
	
	public function listControlsForReport($id) {
	
		$db=new db;
		$conn=$db->connect();
		$query="SELECT * FROM as_ascontrols WHERE ct_det=" . $id . ";";
		$response="";
		
		if ($result=$conn->query($query)) {
			while ($row=$result->fetch_assoc()) {
				$response.=$row['ct_descript']."\n";
			}		
		} else {
			$response=false;
		}				
		$db->disconnect($conn);
		return $response;
	
	}
	
	public function addAssessment($user, $type, $team, $task, $descript, $owner, $next, $assessor, $approval) {
		$db = new db;
		$conn = $db->connect();
	
		//generate number of assessment
		$query = "SELECT * FROM as_assessment WHERE as_user=" . $user . " ORDER BY as_number DESC LIMIT 0,1";
		$result = $conn->query($query);
		if ($row = $result->fetch_assoc()) {
			$number = $row["as_number"] + 1;
		} else {
			$number = 1;	
		}
	
		$date = date("Y-m-d");
		$next = date("Y-m-d", strtotime($next));
	
		$query = "INSERT INTO as_assessment VALUES (0, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, '0', ?)";
		$stmt = $conn->prepare($query);
	
		// Bind parameters to the prepared statement
		$stmt->bind_param("iisssisssss", $user, $type, $team, $task, $descript, $number, $owner, $next, $assessor, $approval, $next);
	
		// Execute the prepared statement
		if ($stmt->execute()) {
			$_SESSION["assessment"] = $conn->insert_id;
			$stmt->close();
			// Do not close the connection here
			return $conn->insert_id;
		} else {
			$response = false;
			$stmt->close();
			// Do not close the connection here
			return $response;
		}
	}
	
	
	public function editAssessment($id, $team, $task, $descript, $owner, $next, $assessor, $approval) {
		$db = new db;
		$conn = $db->connect();
	
		$next = date("Y-m-d", strtotime($next));
	
		// Prepare the SQL query with placeholders
		$query = "UPDATE as_assessment SET 
				  as_team = ?,
				  as_task = ?,
				  as_descript = ?,
				  as_owner = ?,
				  as_next = ?,
				  as_assessor = ?,
				  as_approval = ? 
				  WHERE idassessment = ?";
	
		// Prepare the statement
		$stmt = $conn->prepare($query);
	
		// Bind parameters to the statement
		$stmt->bind_param(
			"ssssssii", // Specify the data types of the parameters ("s" for string, "i" for integer)
			$team,
			$task,
			$descript,
			$owner,
			$next,
			$assessor,
			$approval,
			$id
		);
	
		// Execute the statement
		if ($stmt->execute()) {
			$response = true;
			if($approval=='3'){
				$this->sendemailnotification($id);
			}

		} else {
			$response = false;
		}
	
		// Close the statement and connection
		$stmt->close();
		$db->disconnect($conn);
	
		return $response;
	}
	
	
	public function addAsDetail($assessment, $risk, $hazard, $descript, $like, $consequence, $effect, $action, $date, $owner) {
		$db = new db;
		$conn = $db->connect();
	
		$date = date("Y-m-d", strtotime($date));
		$rating = $this->calculateRating($like, $consequence);
	
		$query = "INSERT INTO as_details VALUES (0, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
	      
		$stmt = $conn->prepare($query);
		$stmt->bind_param("iiissiisiss", $assessment, $risk, $hazard, $descript, $like, $consequence, $rating, $effect, $action, $date, $owner);
	
		if ($stmt->execute()) {
			$response = true;
			$det = $_SESSION["userid"];
			$conn->query("UPDATE as_astreat SET tr_tmpid=-1, tr_det=" . $det . " WHERE tr_tmpid=" . $_SESSION["assessment"]);
			$conn->query("UPDATE as_ascontrols SET ct_tmpid=-1, ct_det=" . $det . " WHERE ct_tmpid=" . $_SESSION["assessment"]);
		} else {
			$response = false;
		}
	
		$stmt->close();
		$db->disconnect($conn);
		return $response;
	}

	public function editAsDetail($id, $risk, $hazard, $descript, $like, $consequence, $effect, $action, $date, $owner) {
		
		$db=new db;
		$conn=$db->connect();
		
		$date=date("Y-m-d", strtotime($date));
		$rating=$this->calculateRating($like,$consequence);
	
		$query="UPDATE as_details SET as_risk=?,as_hazard=?,as_descript=?,as_like=?,as_consequence=?,as_rating=?,as_effect=?,as_action=?,as_duedate=?,as_owner=? WHERE iddetail=?";
			
		$stm = $conn->prepare($query);
		  
		$stm->bind_param("iisiiisissi",$risk,$hazard,$descript,$like,$consequence,$rating,$effect,$action,$date,$owner,$id);

		if ($stm->execute()) {
			$response=true;		
		} else {
			$response=false;
		}
		
		$db->disconnect($conn);
		return $response;
	}
	
	public function deleteTmpFields() {
		
		$db=new db;
		$conn=$db->connect();
		$conn->query("DELETE FROM as_astreat WHERE tr_tmpid<>-1");
		$conn->query("DELETE FROM as_ascontrols WHERE ct_tmpid<>-1");		
		return true;
	}
	
	public function getApproval($app){
		if($app == "1") {
			return "In Progress";
		} else if($app == "2"){
			return "Approved";
		}	else if($app == "3"){
			return "Closed";
		}
	}
	
	public function getRating($rating){
		switch ($rating) {
				case 1:
					return 'Low';
					break;  
				case 2:
					return 'Medium';
					break;  
				case 3:
					return 'High';
					break;
				case 4:
					return 'Extreme';
					break;   
		  }
	}


	public function savenotification($notifyby, $message, $readstatus ,$status,$assessmentid,$risk,$description,$date) {
		
		$db=new db;
		$conn=$db->connect();
		//$date=date('Y-m-d');
		$query = "INSERT INTO as_notification (notification_by,notification_to,messageinfo,read_status,status)
		VALUES ('$notifyby','1','$message','$readstatus',0)";

		if ($conn->query($query)) {
			
			$response=true;	
		//$this->sendNotificationEmail();
		//$last_id = $conn->insert_id;
		$this->mailsenttouser($notifyby,$assessmentid,$risk,$description,$date);
		$this->mailsenttoadmin($assessmentid,$risk,$description,$date);
		
		//$this->sendNotificationEmailadmin();
		} else {
			$response=false;
		}
		
		$db->disconnect($conn);
		return $response;
	}
	public function mailsenttouser($notifyby,$assessmentid,$risk,$description,$date){

		$db=new db;
		$conn=$db->connect();
		$query="SELECT * FROM users WHERE iduser=".$notifyby."";
	
		
		$query11="SELECT * FROM as_risks WHERE idrisk=".$risk."";
	
		if ($result = $conn->query($query)) {
			$row = $result->fetch_assoc();
			$response = $row;
	
			if ($result11 = $conn->query($query11)) {
				$row11 = $result11->fetch_assoc();
				$response = $row11;
	
			}
		} else {
			$response = false;
		}
		$db->disconnect($conn);
	
		// $mail = new PHPMailer(true);
		// // SMTP settings for Mailtrap
		// $mail->isSMTP();
		// $mail->Host = 'smtp-mail.outlook.com';
		// $mail->Port = 587;
		// $mail->SMTPAuth = true;
		// $mail->Username = 'jay@risksafe.co'; // Replace with your Mailtrap username
		// $mail->Password = 'Welcome901#@!'; // Replace with your Mailtrap password
		// $mail->SMTPSecure = 'TLS';
		
		$subject='Your Risk Has Been Successfully Created';
		
		// // Email content
		// $mail->setfrom('jay@risksafe.co', 'Risksafe');
		// //$mailto:mail->addaddress('jay@risksafe.co');
		// //$mailto:mail->addaddress('jay@risksafe.co');
		// $mail->Subject = $subject;
		// Email body
		//$mailto:adminemail='binarydata.jagroop@gmail.com';
		$adminemail=$row['u_mail'];
		
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
						We are pleased to inform you that your risk has been successfully created on our platform. Your proactive approach towards managing risks demonstrates your commitment to ensuring a safer and more secure environment.
						</p>
						<p>
							
						The details of the risk are as follows:
						<br>
						Risk ID: '.$assessmentid.'
						<br>
						Risk Title: '.$row11['ri_name'].'
						<br>
						Date Created: '.$date.'
						<br>
						Description: '.$description.'
						<br>
						</p>
					
						</p>
						<p>
						If you have any further questions or concerns, please do not hesitate to reach out to our support team . We value your input and are here to assist you every step of the way.</p>
	<p>
	Thank you for your trust in our risk management services. Together, we can create a safer environment for everyone involved.
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

		$mail = new MailSent();
		$mail->sentMail($body,$adminemail,$subject);
	
		// $mail->Body = $body;
		// $message = 'New Assessment Created';
		// // Set the email body as HTML
		// $mail->isHTML(true);
	
		// $mail->addAddress($adminemail);
	
		// if ($mail->send()) {
		// //echo 'mailsent';
		// return 1;
		// } else {
		// echo 'Mailer Error: ' . $mail->ErrorInfo;
		// }
	
	
	
	}
	
	public function mailsenttoadmin($assessmentid,$risk,$description,$date){
	
		
		$db=new db;
		$conn=$db->connect();
		
		$query11="SELECT * FROM as_risks WHERE idrisk=".$risk."";
	
			if ($result11 = $conn->query($query11)) {
				$row11 = $result11->fetch_assoc();
				$response = $row11;
	
		} else {
			$response = false;
		}
		$db->disconnect($conn);
	
	
		// $mail = new PHPMailer(true);
		// // SMTP settings for Mailtrap
		// $mail->isSMTP();
		// $mail->Host = 'smtp-mail.outlook.com';
		// $mail->Port = 587;
		// $mail->SMTPAuth = true;
		// $mail->Username = 'jay@risksafe.co'; // Replace with your Mailtrap username
		// $mail->Password = 'Welcome901#@!'; // Replace with your Mailtrap password
		// $mail->SMTPSecure = 'TLS';
		
		$subject='New Risk has been Created';
		//$mail->SMTPDebug = SMTP::DEBUG_SERVER;
		//$mail->Debugoutput = 'html';
	
		// Email content
		// $mail->setfrom('jay@risksafe.co', 'Risksafe');
		// $mail->addaddress('dev3.bdpl@gmail.com');
		//$mailto:mail->addaddress('jay@risksafe.co');
		//$mailto:mail->addaddress('jay@risksafe.co');
		// $mail->Subject = $subject;
		// Email body
		//$mailto:adminemail='binarydata.jagroop@gmail.com';
		$adminemail='jay@risksafe.co';
		//$body='testdata foe email';
	
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
						We are pleased to inform you that your risk has been successfully created on our platform. Your proactive approach towards managing risks demonstrates your commitment to ensuring a safer and more secure environment.
						</p>
								<p>
									
								The details of the risk are as follows:
								<br>
								Risk ID: '.$assessmentid.'
								<br>
								Risk Title: '.$row11['ri_name'].'
								<br>
								Date Created: '.$date.'
								<br>
								Description: '.$description.'
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
	
		// $mail->Body = $body;
		// $message = 'New Assessment Created';
		// // Set the email body as HTML
		// $mail->isHTML(true);
	
		// //$mail->addAddress($adminemail);
	
		// if ($mail->send()) {
		// //echo 'mailsent';
		// return 1;
		// } else {
		// echo 'Mailer Error: ' . $mail->ErrorInfo;
		// }

		$mail = new MailSent();
		$mail->sentMail($body,$adminemail,$subject);
	
	}
	
	
	public function sendemailnotification($id){
	
		$db=new db;
		$conn=$db->connect();
		$query="SELECT * FROM as_assessment WHERE idassessment=".$id."";
		if ($result = $conn->query($query)) {
			$row = $result->fetch_assoc();
			$response = $row['as_user'];
			echo $query11="SELECT * FROM users WHERE iduser=".$row['as_user']."";
			if ($result11 = $conn->query($query11)) {
				$row11 = $result11->fetch_assoc();
				$response = true;
			}
	
		}else{
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
		$subject='Risk Assessment Closed';
		// Email content
		$mail->setfrom('jay@risksafe.co', 'Risksafe Team');
		//$mailto:mail->addaddress('jay@risksafe.co');
		//$mailto:mail->addaddress('jay@risksafe.co');
		$mail->Subject = $subject;
	
		//implode(" ",$usermail);
	
		
		$mail->addAddress($row11['u_mail']);
		
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
						<p>Your Risk Assesment has been closed successfully .</p>
						
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
		echo 'mailsent';
		//return 1;
		} else {
		echo 'Mailer Error: ' . $mail->ErrorInfo;
		}
			
		
	}
	
	
	
	
	









	
	


	
}
