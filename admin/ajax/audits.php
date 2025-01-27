<?php
    function listAudits($start, $end, $company_id, $conn){
		$query = "SELECT * FROM as_auditcontrols WHERE c_id = '$company_id' ORDER BY idcontrol DESC, con_date DESC LIMIT $start, $end";
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
	
	function _getCtrlTitle($id, $type, $con){
	    if($type === 'custom'){
	        $query="SELECT * FROM as_customcontrols WHERE control_id = '$id'";
    		$result=$con->query($query);
    		if ($result->num_rows > 0) {
    		    $row=$result->fetch_assoc();
    		    
        		return $row['title'];  
    		}else{
    		    return 'Error';
    		}
	    }else if($type === 'recommended'){
	        $query = "SELECT * FROM as_controls WHERE id = '$id'";
    		$result = $con->query($query);
    		
    		if ($result->num_rows > 0) {
    		    $row=$result->fetch_assoc();
    		    
        		return $row['control_name'];  
    		}else{
    		    return 'Error!!';
    		}
	    }else if($type === 'monitoring'){
	        $query = "SELECT * FROM as_monitoring WHERE m_id = '$id'";
    		$result = $con->query($query);
    		
    		if ($result->num_rows > 0) {
    		    $row=$result->fetch_assoc();
    		    
        		return $row['title'];  
    		}else{
    		    return 'Error!!';
    		}
	    }else{
	        return 'Error!';
	    }
	}
	
	function listCompanyControl($id, $con) {
	
		$response="";
		$query="SELECT * FROM as_customcontrols WHERE c_id = '$id'";
		$result=$con->query($query);
		if ($result->num_rows > 0) {
    		$response.='<option value="null" selected>No Custom Control Selected!!</option>';
    		while ($row=$result->fetch_assoc()) {
    			$response.='<option value="' . $row["control_id"] . '">' . $row["title"] . '</option>';
    		}   
		}else{
		    $response.='<option value="null" selected>No Custom Control Created Yet!!</option>';
		}
		return $response;
	
	}
	
	function listMonitorings($id, $con, $selected = null) {
	
		$response="";
		$query="SELECT * FROM as_monitoring WHERE c_id = '$id'";
		$result=$con->query($query);
		if ($result->num_rows > 0) {
		    if($selected === null){
		        $res = '<option value="null" selected>No Monitoring Selected!!</option>';
		    }else{
		        $res = '<option value="null">No Monitoring Selected!!</option>';
		    }
    		$response.= $res;
    		while ($row=$result->fetch_assoc()) {
    			#$response.='<option value="' . $row["m_id"] . '">' . ucfirst($row["title"]) . '</option>';
    			$response.='<option value="' . $row["m_id"] . '"';
                    if ( $row["m_id"] === $selected && $selected !== null && $selected !== 'null' ) $response.=' selected';
    			    $response.='>' . ucfirst($row["title"]) . '</option>';
    		}   
		}else{
		    $response.='<option value="null" selected>No Monitoring Created Yet!!</option>';
		}
		return $response;
	
	}
	
	function listCompanyControlSelected($company_id, $id, $con) {
        
        if($id == 'null'){
            $response = listCompanyControl($company_id, $con);
        }else{
    		$response="";
    		$query="SELECT * FROM as_customcontrols WHERE c_id = '$company_id'";
    		$result=$con->query($query);
    		if ($result->num_rows > 0) {
                $response.='<option value="null">No Custom Control Selected!!</option>';
        		while ($row=$result->fetch_assoc()) {
        			$response.='<option value="' . $row["control_id"] . '"';
                    if ($row["control_id"]==$id) $response.=' selected';
    			    $response.='>' . $row["title"] . '</option>';
        		}   
    		}else{
    		    $response.='<option value="null" selected>Error!!</option>';
    		}
        }
		return $response;
	
	}
	
	function listSubControl($cat, $conn){
		$response = '<select name="subcontrol" id="subcontrol" class="form-control" required>';
		$response .= '<option value="">Please select control...</option>';
		$query = "SELECT * FROM as_subcontrol WHERE audit_id = " . $cat . " ORDER BY idsubcontrol";

		$result = $conn->query($query);

		while ($row = $result->fetch_assoc()) {
			$response .= '<option value="' . $row["idsubcontrol"] . '"';
// 			if ($row["idsubcontrol"] == $selected) { // Updated condition to check against $selected
// 				$response .= ' selected';
// 			}
			$response .= '>' . $row["sub_name"] . '</option>';
		}
		$response .= "</select>";
		
		return $response;
	}
	function listAuditControlsForReport($startDate, $endDate, $conn){

		$query = "SELECT * FROM as_auditcontrols WHERE con_user=" . $_SESSION["userid"] . " AND con_date >= "
			. " '" . date("Y-m-d", strtotime($startDate)) . "' AND con_date <= '" . date("Y-m-d", strtotime($endDate)) . "' ORDER BY idcontrol DESC, con_date DESC";
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
    function rowCount($conn, $table, $cond, $value) {
	
		$query="SELECT * FROM $table WHERE $cond = '$value'";
		if ($result=$conn->query($query)) {
			return $result->num_rows;
		} else {
			return false;	
		}
	
	}

	//LISTS ALL USERS CONTROLS TO COMBO BOX
	function listAllControls($user, $conn){

		$query = "SELECT * FROM as_ascontrols LEFT JOIN as_assessment ON as_ascontrols.ct_assessment=as_assessment.idassessment  WHERE as_assessment.as_user=" . $user . " ORDER BY as_ascontrols.ct_descript";

		$result = $conn->query($query);
		$response = "";
		while ($row = $result->fetch_assoc()) {
			$response .= '<option value="' . $row["idcontrol"] . '"';
			$response .= '>' . $row["ct_descript"] . '</option>';
		}
		
		return $response;
	}
	function listCriteria($id, $start, $end, $conn){

		$query = "SELECT * FROM as_auditcriteria WHERE aud_id = '$id' ORDER BY idcriteria LIMIT $start,$end";
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
	function listCriteriaForReport($company_id, $conn){

		$query = "SELECT * FROM as_auditcriteria WHERE c_id = '$company_id' ORDER BY idcriteria";
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
	function listTypes($selected, $conn){

		$query = "SELECT * FROM as_controls ORDER BY id";
		$result = $conn->query($query);
		$response = "";
		while ($row = $result->fetch_assoc()) {
			$response .= '<option data-id="' . $row["id"] . '" value="' . $row["id"] . '"';
			if ($selected == $row["control_name"]) $response .= ' selected';
			$response .= '>' . $row["control_name"] . '</option>';
		}

		
		return $response;
	}
    function getSubNames($selected, $con) {
	
		$response='<select name="subControl" class="form-control" required>';
		$response.='<option value="0">Please select action to take...</option>';
		$query="SELECT * FROM as_subcontrol WHERE audit_id = '$selected'";
		$result=$con->query($query);
		while ($row=$result->fetch_assoc()) {
			$response.='<option value="' . $row["idsubcontrol"] . '"';
			#if ($row["idaction"]==$selected) $response.=' selected';
			$response.='>' . $row["sub_name"] . '</option>';
		}
		$response.="</select>";
		return $response;
	
	}
    function getNext($date, $freq){

		if ($freq == 0) {
			$next = "Not set";
		} else {
			$next = strtotime($date) + ($freq * 24 * 60 * 60);
			$next = date("m/d/Y", $next);
		}
		return $next;
	}
	
	function dateToDays($freq){

		if ($freq == 7) {
			$next = 'null';
		} else if ($freq == 1) {
			$next = 1;
		} else if ($freq == 2) {
			$next = 7;
		} else if ($freq == 3) {
			$next = 14;
		} else if ($freq == 4) {
			$next = 30;
		} else if ($freq == 5) {
			$next = 180;
		} else if ($freq == 6) {
			$next = 365;
		} else {
			$next = 'error';
		}
		
		return $next;
	}
	
	function nextDate($start_date,$interval_days,$output_format){
	    
	    $interval_days = dateToDays($interval_days);
	    
	    if($interval_days == 'error'){
	        return 'Not Specified';
	    }else if($interval_days == 'null'){
	        return 'Audited As Required';
	    }else{
            $start = strtotime($start_date);
            $end = strtotime(date('Y-m-d'));
            $days_ago = ($end - $start) / 24 / 60 / 60;
            if($days_ago < 0)return date($output_format,$start);
            $remainder_days = $days_ago % $interval_days;
            if($remainder_days > 0){
                $new_date_string = "+" . ($interval_days - $remainder_days) . " days";
            } else {
                $new_date_string = "today";
            }
            return date($output_format,strtotime($new_date_string));
	    }
    }
    
    function getAudit($id, $con){
        $id = strtoupper($id);
		$query = "SELECT * FROM as_auditcontrols WHERE c_id = '$id'";

		if ($result = $con->query($query)) {
			$row = $result->fetch_assoc();
			$response = $row;
		} else {
			$response = false;
		}

		return $response;
	}
    function getCriteria($id, $conn){

		$query = "SELECT * FROM as_auditcriteria WHERE idcriteria = '$id'";
        $result = $conn->query($query);
		if ($result->num_rows > 0) {
			$row = $result->fetch_assoc();
			$response = $row;
		} else {
			$response = false;
		}

		return $response;
	}

	function getControlName($id, $conn){

		$result = $conn->query("SELECT * FROM as_ascontrols WHERE idcontrol = '$id'");
		$row = $result->fetch_assoc();
		$response = $row["ct_descript"];
		return $response;
	}


	function getFrequency($freq){

		if ($freq == 0) {
			return "Not set";
		} else if ($freq == 1) {
			return "Daily";
		} else if ($freq == 7) {
			return "Weekly";
		} else if ($freq == 30) {
			return "Monthly";
		} else if ($freq == 182) {
			return "6 Monthly";
		} else if ($freq == 365) {
			return "Yearly";
		}
	}
	
	function get_Frequency($freq){

		if ($freq == 7) {
			return "As Required";
		} else if ($freq == 1) {
			return "Daily Controls";
		} else if ($freq == 2) {
			return "Weekly Controls";
		} else if ($freq == 3) {
			return "Fort-Nightly Controls";
		} else if ($freq == 4) {
			return "Monthly Controls";
		} else if ($freq == 5) {
			return "Semi-Annually Controls";
		} else if ($freq == 6) {
			return "Annually Controls";
		} else {
			return "Error!!";
		}
	}

	function getEffectiveness($effe){
		if ($effe == 0) {
			return "Not Assessed";
		} else if ($effe == 1) {
			return "Not Effective";
		} else if ($effe == 2) {
			return "Effective";
		} else{
            return "Error!!";
        }
	}

	function getOutcome($out){
		if ($out == 0) {
			return "N/A";
		} else if ($out == 1) {
			return "Pass";
		} else if ($out == 2) {
			return "Fail";
		} else{
            return "Error";
        }
	}

    #get requests
	if (isset($_POST["getSubControl"])) {
		include '../../layout/db.php';

		function sanitizePlus($data) {
			$data = trim($data);
			$data = stripslashes($data);
			$data = strip_tags($data);
			$data = htmlspecialchars($data);
			return $data;
		}
		
        function getToArray($array){
            $getArray = [];

            $convertArray = explode("&", $array);
            for ($i=0; $i < count($convertArray); $i++) { 
                $keyValue = explode('=', $convertArray[$i]);
                $getArray[$keyValue [0]] = $keyValue [1];
            }

            return $getArray;
        }

		$value = $_POST["getSubControl"];
        $getArray = getToArray($value);
		
        $selected = sanitizePlus($getArray['selected']);

		$response = getSubNames($selected, $con);
		echo $response;
    }
?>