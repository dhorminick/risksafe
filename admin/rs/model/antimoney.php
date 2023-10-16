<?php

include_once("../config.php");
include_once("db.php");

class antimoney {

	
	public function __construct(){	
	}
	
	public function listAssessments($start, $end) {
	
		$db=new db;
		$conn=$db->connect();
		$query="SELECT * FROM as_aml WHERE as_user=".$_SESSION["userid"]." ORDER BY id DESC, as_date DESC LIMIT " . $start . ", " . $end;
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
		$query="SELECT * FROM aml_details LEFT JOIN aml_cat ON aml_cat.id=aml_details.aml_cat LEFT JOIN aml_subcat ON aml_subcat.idcat=aml_details.aml_subcat WHERE aml_details.as_assessment=".$assessmentId." ORDER BY aml_details.iddetail LIMIT " . $start . ", " . $end;
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
		$query="SELECT * FROM as_aml LEFT JOIN as_types ON as_aml.as_type  = as_types.idtype WHERE id=".$id;
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
		$query="SELECT * FROM aml_details LEFT JOIN aml_cat ON aml_cat.id=aml_details.aml_cat LEFT JOIN aml_subcat ON aml_subcat.idcat=aml_details.aml_subcat WHERE aml_details.iddetail=".$id;
		if ($result=$conn->query($query)) {	
			$row=$result->fetch_assoc();
			$response=$row;
			// print_r($response);
			// exit();
		} else {
			$response=false;	
		}
		$db->disconnect($conn);
		return $response;
	
	}
	
	public function getAssessmentDetForReport($id) {
	
		$db=new db;
		$conn=$db->connect();
		$query="SELECT * FROM aml_details LEFT JOIN aml_cat ON aml_cat.id=aml_details.aml_cat "
				." LEFT JOIN aml_subcat ON aml_subcat.idcat=aml_details.aml_subcat LEFT JOIN as_like ON as_like.idlike=aml_details.as_like "
				." LEFT JOIN as_consequence ON as_consequence.idconsequence = aml_details.as_consequence "
				." LEFT JOIN as_actiontype ON as_actiontype.idaction=aml_details.as_action WHERE aml_details.as_assessment=".$id;
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
		$query="DELETE FROM aml_details WHERE iddetail=".$id.";";
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
			$query = "SELECT * FROM aml_details WHERE as_assessment=" . $id;
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
			$query = "DELETE FROM as_aml WHERE id=" . $id . ";";
			$query .= "DELETE FROM aml_details WHERE as_assessment=" . $id . ";";
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
		$query="SELECT * FROM aml_subcat WHERE aml_cat=" . $cat . " ORDER BY idcat";
		$result=$conn->query($query);
		while ($row=$result->fetch_assoc()) {
			$response.='<option value="' . $row["idcat"] . '"';
			if ($row["idcat"]==$selected) $response.=' selected';
			$response.='>' . $row["aml_sub_name"] . '</option>';
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
		$query="SELECT * FROM aml_cat WHERE ri_type=" . $type . " ORDER BY id";
		$result=$conn->query($query);
		while ($row=$result->fetch_assoc()) {
			$response.='<option value="' . $row["id"] . '"';
			if ($row["id"]==$selected) $response.=' selected';
			$response.='>' . $row["aml_cat_name"] . '</option>';
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
	
	public function getControlName($id) {
	
		$db=new db;
		$conn=$db->connect();
		$query="SELECT * FROM as_auditcontrols WHERE idcontrol=" . $id;
		$result=$conn->query($query);
		if ($row=$result->fetch_assoc()) {
			$response=$row["con_control"];
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
	
	public function listControls($id) {
	
		$db=new db;
		$conn=$db->connect();
		if ($id==-1) {
			$query="SELECT * FROM as_ascontrols WHERE ct_tmpid=" . $_SESSION["aml"] . ";";
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
			$query="SELECT * FROM as_astreat WHERE tr_tmpid=" . $_SESSION["aml"] . ";";
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
		
		$db=new db;
		$conn=$db->connect();
		
		//generate number of assessment
		$query="SELECT * FROM as_aml WHERE as_user=" . $user . " ORDER BY as_number DESC LIMIT 0,1";
		$result=$conn->query($query);
		if ($row=$result->fetch_assoc()) {
			$number=$row["as_number"]+1;
		} else {
			$number=1;	
		}
		
		$date=date("Y-m-d");
		$next=date("Y-m-d", strtotime($next));
	
		$query="INSERT INTO as_aml VALUES (0, "
				. "" . $user . ", "
				. "" . $type . ", "
				. "'" . $team . "', "
				. "'" . $task . "', "
				. "'" . $descript . "', "
				. "'" . $number . "', "
				. "'" . $owner . "', "
				. "'" . $next . "', "
				. "'" . $assessor . "', "
				. "'" . $approval . "', "
				. "'0', "
				. "'" . $next . "');";
				
		if ($conn->query($query)) {
			//$response=true;	
			$_SESSION["aml"] = $conn->insert_id;
			return $conn->insert_id;
			//$_SESSION["risktype"]=$type;	
		} else {
			$response=false;
		}
		
		$db->disconnect($conn);
		return $response;
	}
	
	public function editAssessment($id, $team, $task, $descript, $owner, $next, $assessor, $approval) {
		
		$db=new db;
		$conn=$db->connect();
		
		$next=date("Y-m-d", strtotime($next));
	
		$query="UPDATE as_aml SET "
				. "as_team='" . $team . "', "
				. "as_task='" . $task . "', "
				. "as_descript='" . $descript . "', "
				. "as_owner='" . $owner . "', "
				. "as_next='" . $next . "', "
				. "as_assessor='" . $assessor . "', "
				. "as_approval=" . $approval . " WHERE id=".$id;

				
		if ($conn->query($query)) {
			$response=true;	
		} else {
			$response=false;
		}
		
		$db->disconnect($conn);
		return $response;
	}
	
	public function addAsDetail($assessment, $risk, $hazard, $descript, $like, $consequence, $effect, $action, $date, $owner) {
		
		$db=new db;
		$conn=$db->connect();
		
		$date=date("Y-m-d", strtotime($date));
		$rating=$this->calculateRating($like,$consequence);
	
		$query="INSERT INTO aml_details VALUES (0, "
				. "" . $assessment . ", "
				. "" . $risk . ", "
				. "" . $hazard . ", "
				. "'" . $descript . "', "
				. "" . $like . ", "
				. "" . $consequence . ", "
				. "" . $rating . ", "
				. "'" . $effect . "', "
				. "" . $action . ", "
				. "'" . $date . "', "
				. "'" . $owner . "');";
				
		if ($conn->query($query)) {
			$response=true;	
			$det=$conn->insert_id;
			$conn->query("UPDATE as_astreat SET tr_tmpid=-1, tr_det=".$det." WHERE tr_tmpid=" . $_SESSION["aml"]);
			$conn->query("UPDATE as_ascontrols SET ct_tmpid=-1, ct_det=".$det." WHERE ct_tmpid=" . $_SESSION["aml"]);	
		} else {
			$response=false;
		}
		
		$db->disconnect($conn);
		return $response;
	}
	
	public function editAsDetail($id, $risk, $hazard, $descript, $like, $consequence, $effect, $action, $date, $owner) {
		
		$db=new db;
		$conn=$db->connect();
		
		$date=date("Y-m-d", strtotime($date));
		$rating=$this->calculateRating($like,$consequence);
	
		$query="UPDATE aml_details SET "
				. "aml_cat =" . $risk . ", "
				. "aml_subcat=" . $hazard . ", "
				. "aml_descript='" . $descript . "', "
				. "as_like=" . $like . ", "
				. "as_consequence=" . $consequence . ", "
				. "as_rating=" . $rating . ", "
				. "as_effect='" . $effect . "', "
				. "as_action=" . $action . ", "
				. "as_duedate='" . $date . "', "
				. "as_owner='" . $owner . "' WHERE iddetail=".$id;
			
			echo $query;	
		if ($conn->query($query)) {
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
	
}

?>