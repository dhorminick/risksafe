<?php
    function listBIA($start, $end, $company_id, $conn) {
	
		$query="SELECT * FROM as_bia WHERE c_id = '$company_id' ORDER BY idbia DESC LIMIT " . $start . ", " . $end;
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
	
	function listBIAForReport($company_id, $conn) {		
		$query="SELECT * FROM as_bia WHERE c_id = '$company_id' ORDER BY idbia DESC";	
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
	
	function listBIAForReportCustom($company_id, $conn, $limit) {		
		$query="SELECT * FROM as_bia WHERE c_id = '$company_id' ORDER BY idbia DESC LIMIT $limit";	
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
		
	function deleteBIA($id, $company_id, $conn) {
	
		$query="DELETE FROM as_bia WHERE bia_id = '$id' AND c_id = '$company_id'";		
		
		if ($conn->multi_query($query)) {
			$response=true;
		} else {
			$response=false;	
		}
		
		
		return $response;		
	}

	function getBIA($id, $conn) {
	
		$query="SELECT * FROM as_bia WHERE bia_id = '$id'";
		$result=$conn->query($query);
		if ($result->num_rows > 0) {
			$row=$result->fetch_assoc();
			$response=$row;
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
?>