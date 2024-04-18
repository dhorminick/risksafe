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

    function getDays($start, $due){

		$next = strtotime($due) - strtotime($start);
		$next = ceil($next / 60 / 60 / 24);

		return $next;
	}

	function listTreatments($start, $end, $conpany_id, $conn){

		$query = "SELECT * FROM as_treatments WHERE c_id = '$conpany_id' ORDER BY idtreatment DESC, tre_start DESC LIMIT $start , $end";
		$result = $conn->query($query);
		if ($result->num_rows > 0) {
			$data = array();
			while ($row = $result->fetch_assoc()) {
				$data[] = $row;
			}
			$response = $data;
		} else {
			$response = false;
		}
		
		return $response;

	}

	//LISTS ALL USERS TREATMENTS TO COMBO BOX
	function listAllTreatments($user, $conn){
		$query = "SELECT * FROM as_astreat LEFT JOIN as_assessment ON as_astreat.tr_assessment=as_assessment.idassessment WHERE as_assessment.as_user=" . $user . " ORDER BY as_astreat.tr_descript";

		$result = $conn->query($query);
		$response = "";
		while ($row = $result->fetch_assoc()) {
			$response .= '<option value="' . $row["idtreat"] . '"';
			$response .= '>' . $row["tr_descript"] . '</option>';
		}
		
		return $response;

	}

	function listTreatmentsForReport($company_id, $conn){
		$query = "SELECT * FROM as_treatments WHERE tre_user=" . $_SESSION["userid"] . " ORDER BY idtreatment DESC";
		$result = $conn->query($query);
		if ($result->num_rows > 0) {
			$data = array();
			while ($row = $result->fetch_assoc()) {
				$data[] = $row;
			}
			$response = $data;
		} else {
			$response = false;
		}
		
		return $response;

	}

	function getTreatmentInfo($id, $conn){
		$query = "SELECT * FROM as_astreat LEFT JOIN as_assessment ON as_astreat.tr_assessment=as_assessment.idassessment  WHERE as_astreat.idtreat=" . $id;

		$info = array();

		$result = $conn->query($query);
		if ($row = $result->fetch_assoc()) {
			$info["treatment"] = $row["tr_descript"];
			$info["team"] = $row["as_team"];
			$info["assessor"] = $row["as_assessor"];
		}

		
		return json_encode($info);

	}

	function getTreatment($id, $conn){


		$query = "SELECT * FROM as_treatments WHERE idtreatment=" . $id;
        $result = $conn->query($query);
		if ($result->num_rows > 0) {
			$row = $result->fetch_assoc();
			$response = $row;
		} else {
			$response = false;
		}

		
		return $response;

	}

	function getTreatmentName($id, $effectivComplianceCount, $conn){

		$result = $conn->query("SELECT * FROM as_astreat WHERE idtreat=" . $id);
		$row = $result->fetch_assoc();
		$response = $row["tr_descript"];
		
		return $response;
	}

?>