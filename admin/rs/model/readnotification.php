<?php

include_once("../config.php");
include_once("db.php");


class readnotification {

	
	public function __construct(){	
	}
	
	public function listreadnotification($start, $end) {
	
		$db=new db;
		$conn=$db->connect();
		
		$query="SELECT * FROM as_notification  ORDER BY id DESC LIMIT $start , $end";
      
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
	public function deleteallnotification(){
		$db = new db;
		$conn = $db->connect();
		$query = "DELETE FROM as_notification";
		if($conn->query($query)){
			$response = true;
		}else{
			$response = false;
		}
		$db->disconnect($conn);
		return $response;
	}


    public function deletenotification($id) {
	
		$db=new db;
		$conn=$db->connect();
		$query="DELETE FROM as_notification WHERE id=".$id.";";
		
		if ($conn->query($query)) {	
			$response=true;
		} else {
			$response=false;	
		}
		$db->disconnect($conn);
		return $response;
	
	}
	



	
}

?>