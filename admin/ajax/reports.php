<?php
    function listReports($start, $end, $conn, $company) {
	
		$query="SELECT * FROM as_assessment WHERE c_id = '$company' ORDER BY as_date DESC LIMIT  $start , $end";
		$result=$conn->query($query);
		if ($result->num_rows > 0) {	
			$data=array();
			while ($row=$result->fetch_assoc()) {
				$data[]=$row;
			}
			$response=$data;
		} else {
			$response=false;	
		}
		return $response;
	
	}

    function rowCount($conn, $table, $cond, $value) {
	
		$query="SELECT * FROM $table WHERE $cond = '$value'";
		$result=$conn->query($query);
		if ($result->num_rows > 0) {
			return $result->num_rows;
		} else {
			return false;	
		}
	
	}

	function countChart($id, $like, $consequence, $conn) {
		$query="SELECT * FROM as_details WHERE as_assessment= '$id' AND as_like='$like' AND as_consequence='$consequence'";
		$response="";
		$result=$conn->query($query);
		if ($result->num_rows > 0) {
			if ($result->num_rows>0)	 {
				$response=$result->num_rows;	
			} 
		}
		
		return $response;
	}
	function countRisks($id, $rating, $conn) {
		$query="SELECT * FROM as_details WHERE as_assessment='$id' AND as_rating='$rating'";
		$result=$conn->query($query);
		if ($result->num_rows > 0) {
			$response=$result->num_rows;	
		} else {
			$response=false;
		}
		
		return $response;
	}
	function countControls($id, $conn) {
		$query="SELECT * FROM as_ascontrols WHERE ct_assessment = '$id'";
		$result=$conn->query($query);
		if ($result->num_rows > 0) {
			$response=$result->num_rows;	
		} else {
			$response=false;
		}
		
		return $response;
	}
	function countTreatments($id, $conn) {
		$query="SELECT * FROM as_astreat WHERE tr_assessment='$id'";
		$result=$conn->query($query);
		if ($result->num_rows > 0) {
			$response=$result->num_rows;	
		} else {
			$response=false;
		}
		
		return $response;
	}
	
	function countLikelihood($id, $like, $conn) {
		$query="SELECT * FROM as_details WHERE as_assessment='$id' AND as_like='$like'";
		$result=$conn->query($query);
		if ($result->num_rows > 0) {
			$response=$result->num_rows;	
		} else {
			$response=false;
		}
		
		return $response;
	}
	function countConsequence($id, $consequence, $conn) {
		$query="SELECT * FROM as_details WHERE as_assessment= '$id' AND as_consequence='$consequence'";
		$result=$conn->query($query);
		if ($result->num_rows > 0) {
			$response=$result->num_rows;	
		} else {
			$response=false;
		}
		
		return $response;
	}
	
	function getReport($id, $conn) {
	
		$query="SELECT * FROM as_assessment WHERE as_id = '$id'";
		$result=$conn->query($query);
		if ($result->num_rows > 0) {	
			$data=array();
			$row=$result->fetch_assoc();
			$response = $row;
			// print_r($row);
		} else {
			$response=false;	
		}
		
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
	
?>