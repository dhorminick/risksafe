<?php

include_once("../config.php");
include_once("db.php");

class insurence {
	
	public function __construct(){
		
	}
	
	public function listInsurances($start, $end) {
	
		$db=new db;
		$conn=$db->connect();
		$query="SELECT * FROM as_insurance WHERE is_user=".$_SESSION["userid"]." ORDER BY idinsurance DESC, is_date DESC LIMIT " . $start . ", " . $end;
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
	
	
	//ADD NEW ins
	public function addInsurance($type, $coverage, $exclusions, $company, $date, $details, $actions) {
	
		$db=new db();
		$conn=$db->connect();
		
		$query="INSERT INTO as_insurance VALUES (0, " . 
				"" . $_SESSION["userid"] . ", " .
				"'" . $type . "', " .
				"'" . str_replace("'", "\'", $coverage) . "', " .
				"'" . str_replace("'", "\'", $exclusions) . "', " .
				"'" . str_replace("'", "\'", $company). "', " .
				"'" . date("Y-m-d", strtotime($date)) . "', " .
				"'" . str_replace("'", "\'", $details) . "', " .				
				"'" . str_replace("'", "\'", $actions) . "');";
		
		
		if ($conn->query($query)) {
			$response=$conn->insert_id;
		} else {
			$response=false;	
		}
		
		$db->disconnect($conn);
		return $response;
		
	}
	
	//EDIT 
	public function editInsurance($id, $type, $coverage, $exclusions, $company, $date, $details, $actions) {
	
		$db=new db();
		$conn=$db->connect();
		
		$query="UPDATE as_insurance SET " . 
				" is_type='" . $type . "', " .				
				" is_coverage='" . str_replace("'", "\'", $coverage) . "', " .
				" is_exclusions='" . str_replace("'", "\'", $exclusions) . "', " .
				" is_company='" . str_replace("'", "\'", $company) . "', " .				
				" is_date='" . date("Y-m-d", strtotime($date)) . "', " .
				" is_details='" . str_replace("'", "\'", $details) . "', " .				
				" is_actions='" . str_replace("'", "\'", $actions) . "' WHERE idinsurance=" . $id;

				
		
		
		if ($conn->query($query)) {
			$response=true;
		} else {
			$response=false;	
		}
		
		$db->disconnect($conn);
		return $response;
		
	}
	
	public function deleteInsurance($id) {
	
		$db=new db();
		$conn=$db->connect();		
		$query="DELETE FROM as_insurance WHERE idinsurance=".$id."";		
		
		if ($conn->multi_query($query)) {
			$response=true;
		} else {
			$response=false;	
		}
		
		$db->disconnect($conn);
		return $response;		
	}

	public function getInsurance($id) {
	
		$db=new db();
		$conn=$db->connect();		
		$query="SELECT * FROM as_insurance WHERE idinsurance=".$id;
		
		if ($result=$conn->query($query)) {
			$row=$result->fetch_assoc();
			$response=$row;
		} else {
			$response=false;	
		}
		
		$db->disconnect($conn);
		return $response;
		
	}	
	
}

?>