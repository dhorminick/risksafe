<?php
    function rowCount($conn, $table, $cond, $value) {
	
		$query="SELECT * FROM $table WHERE $cond = '$value'";
		$result=$conn->query($query);
		if ($result->num_rows > 0) {
			return $result->num_rows;
		} else {
			return false;	
		}
	
	}

	function listInsurances($start, $end, $company_id, $conn) {
	
		$query="SELECT * FROM as_insurance WHERE c_id = '$company_id' ORDER BY idinsurance DESC, is_date DESC LIMIT $start, $end";
		$result=$conn->query($query);
		if ($result->num_rows > 0) {	
			while ($row=$result->fetch_assoc()) {
				$data[]=$row;
			}
			$response=$data;
		} else {
			$response=false;	
		}
		return $response;	
	}
?>