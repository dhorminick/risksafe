<?php

include_once("../config.php");
include_once("db.php");

class bia {

	
	public function __construct(){				
	}
	
	public function listBIA($start, $end) {
	
		$db=new db;
		$conn=$db->connect();
		$query="SELECT * FROM as_bia WHERE bia_user=".$_SESSION["userid"]." ORDER BY idbia DESC LIMIT " . $start . ", " . $end;
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
	
	public function listBIAForReport() {
	
		$db=new db;
		$conn=$db->connect();
		$query="SELECT * FROM as_bia WHERE bia_user=".$_SESSION["userid"]." ORDER BY idbia DESC";	
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
		
	//ADD NEW bia
	public function addBIA($activity, $descript, $priority, $impact, $time, $action, $resource) {
	
		$db=new db();
		$conn=$db->connect();
		
		$query="INSERT INTO as_bia VALUES (0, " . 
				"" . $_SESSION["userid"] . ", " .
				"'" . $activity . "', " .				
				"'" . str_replace("'", "\'", $descript) . "', " .
				"'" . $priority . "', " .
				"'" . str_replace("'", "\'", $impact) . "', " .
				"'" . $time . "', " .
				"'" . $action . "', " .				
				"'" . str_replace("'", "\'", $resource) . "');";
		
		if ($conn->query($query)) {
			$response=$conn->insert_id;
		} else {
			$response=false;	
		}
		
		$db->disconnect($conn);
		return $response;
		
	}
	
	//EDIT bia
	public function editBIA($id, $activity, $descript, $priority, $impact, $time, $action, $resource) {
	
		$db=new db();
		$conn=$db->connect();		
		$query="UPDATE as_bia SET " . 
				" bia_activity='" . $activity . "', " .
				" bia_descript='" . str_replace("'", "\'", $descript) . "', " .
				" bia_priority='" . $priority . "', " .
				" bia_impact='" . str_replace("'", "\'", $impact) . "', " .
				" bia_time='" . $time . "', " .
				" bia_action='" . $action . "', " .
				" bia_resource='" . str_replace("'", "\'", $resource) . "' WHERE idbia=" . $id;
				
		if ($conn->query($query)) {
			$response=true;
		} else {
			$response=false;	
		}
		
		$db->disconnect($conn);
		return $response;
		
	}
	
	public function deleteBIA($id) {
	
		$db=new db();
		$conn=$db->connect();		
		$query="DELETE FROM as_bia WHERE idbia=".$id."";		
		
		if ($conn->multi_query($query)) {
			$response=true;
		} else {
			$response=false;	
		}
		
		$db->disconnect($conn);
		return $response;		
	}

	public function getBIA($id) {
	
		$db=new db();
		$conn=$db->connect();
		
		$query="SELECT * FROM as_bia WHERE idbia=".$id;
		
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