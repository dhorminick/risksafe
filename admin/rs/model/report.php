<?php

include_once("../config.php");
include_once("db.php");

class report {

	
	public function __construct(){
		
		
	}
	
	public function listReports($start, $end) {
	
		$db=new db;
		$conn=$db->connect();
		$query="SELECT * FROM as_assessment WHERE as_user=".$_SESSION["userid"]." ORDER BY as_date DESC LIMIT " . $start . ", " . $end;
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
	
	public function getReport($id) {
	
		$db=new db;
		$conn=$db->connect();
		$query="SELECT * FROM as_assessment WHERE idassessment=".$id;
		if ($result=$conn->query($query)) {	
			$data=array();
			$row=$result->fetch_assoc();
			$response=$row;
			// print_r($row);
		} else {
			$response=false;	
		}
		$db->disconnect($conn);
		return $response;
	
	}
	
	function getApproval($id) {
		
		switch ($id) {
		  case 1:
		 	return "In progress";
		  	break;
		  case 2:
		  	return "Approved";
		  	break;
		  case 3:
		  	return "Closed";
		  	break;
		}

	}
	
	function countChart($id, $like, $consequence) {
		$db=new db;
		$conn=$db->connect();
		
		$query="SELECT * FROM as_details WHERE as_assessment=".$id." AND as_like=".$like." AND as_consequence=".$consequence;
		$response="";
		if ($result=$conn->query($query)) {
			if ($result->num_rows>0)	 {
				$response=$result->num_rows;	
			} 
		}
		
		$db->disconnect($conn);
		return $response;
	}
	
	function countLikelihood($id, $like) {
		$db=new db;
		$conn=$db->connect();
		
		$query="SELECT * FROM as_details WHERE as_assessment=".$id." AND as_like=".$like;
		if ($result=$conn->query($query)) {
			$response=$result->num_rows;	
		} else {
			$response=false;
		}
		
		$db->disconnect($conn);
		return $response;
	}
	
	function countConsequence($id, $consequence) {
		$db=new db;
		$conn=$db->connect();
		
		$query="SELECT * FROM as_details WHERE as_assessment=".$id." AND as_consequence=".$consequence;
		if ($result=$conn->query($query)) {
			$response=$result->num_rows;	
		} else {
			$response=false;
		}
		
		$db->disconnect($conn);
		return $response;
	}
	
	function countRisks($id, $rating) {
		$db=new db;
		$conn=$db->connect();
		
		$query="SELECT * FROM as_details WHERE as_assessment=".$id." AND as_rating=".$rating;
		if ($result=$conn->query($query)) {
			$response=$result->num_rows;	
		} else {
			$response=false;
		}
		
		$db->disconnect($conn);
		return $response;
	}
	
	function countControls($id) {
		$db=new db;
		$conn=$db->connect();
		
		$query="SELECT * FROM as_ascontrols WHERE ct_assessment=".$id;
		if ($result=$conn->query($query)) {
			$response=$result->num_rows;	
		} else {
			$response=false;
		}
		
		$db->disconnect($conn);
		return $response;
	}
	
	function countTreatments($id) {
		$db=new db;
		$conn=$db->connect();
		
		$query="SELECT * FROM as_astreat WHERE tr_assessment=".$id;
		if ($result=$conn->query($query)) {
			$response=$result->num_rows;	
		} else {
			$response=false;
		}
		
		$db->disconnect($conn);
		return $response;
	}
	
}

?>