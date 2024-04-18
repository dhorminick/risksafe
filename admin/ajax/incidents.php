<?php
    function listIncidents($start, $end, $company_id, $conn) {
	
		$query="SELECT * FROM as_incidents WHERE c_id = '$company_id' ORDER BY idincident DESC, in_date DESC LIMIT " . $start . ", " . $end;
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
	
	function listIncidentsForReport($startDate, $endDate, $company_id, $conn) {
	
		$query="SELECT * FROM as_incidents WHERE c_id = '$company_id' AND in_date >= "
		." '".date("Y-m-d", strtotime($startDate))."' AND in_date <= '".date("Y-m-d", strtotime($endDate))."' ORDER BY idincident DESC, in_date DESC";
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
	
	function listIncidentsForReport_Custom($startDate, $endDate, $company_id, $conn) {
	
		$query="SELECT * FROM as_incidents WHERE in_date BETWEEN CAST('$startDate' as datetime) AND CAST('$endDate' as datetime) ORDER BY idincident DESC";
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
	function listIncidentsForReportCustom($company_id, $conn, $limit) {
	
		$query="SELECT * FROM as_incidents WHERE c_id = '$company_id' ORDER BY idincident DESC, in_date DESC LIMIT $limit";
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
		
	function deleteIncident($id, $company_id, $conn) {
	
		$query="DELETE FROM as_incidents WHERE c_id = '$company_id' AND in_id = '$id'";		
		
		if ($conn->multi_query($query)) {
			$response=true;
		} else {
			$response=false;	
		}
		
		return $response;		
	}

	function getIncident($id, $conn) {
	
		$query="SELECT * FROM as_incidents WHERE in_id='$id'";
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