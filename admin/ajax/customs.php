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
	
	function getIndustryTitle($id, $con){
        if($id == ''){
           $response = 'None Selected'; 
        }else{
            $query="SELECT * FROM as_newrisk_industry WHERE industry_id = '$id'";
    		$result=$con->query($query);
    		if ($result->num_rows > 0) {	
    			$row=$result->fetch_assoc();
    			$response = $row['title'];
    		}else{
    			$response = 'Error!!';
    		}
        }
		return $response;
    }
	
	function getIndustries($id, $con){
        if($id == ''){
           $selected = 'null'; 
        }else{
            $selected = $id;
        }
            
    		$query="SELECT * FROM as_newrisk_industry ORDER BY id";
    		$result=$con->query($query);
    		if ($result->num_rows > 0) {	
    			$response='<select name="industry" class="form-control" required>';
    			while ($row=$result->fetch_assoc()) {
    				$response.='<option value="' . $row["industry_id"] . '"';
    				if ($row["industry_id"]==$selected) $response.=' selected';
    				$response.='>' . ucwords($row["title"]) . '</option>';
    			}
    			$response.="</select>";
    		}else{
    			$response = 'Error!!';
    		}
    		
        

		return $response;
    }
    
    function listCustomControls($start, $end, $company_id, $conn) {
	
		$query="SELECT * FROM as_customcontrols WHERE c_id = '$company_id' ORDER BY id DESC LIMIT $start, $end";
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
	function listControlsForReport($startDate, $endDate, $company_id, $conn) {
	
		$query="SELECT * FROM as_customcontrols WHERE c_id = '$company_id' AND cus_date >= "
		." '".date("Y-m-d", strtotime($startDate))."' AND cus_date <= '".date("Y-m-d", strtotime($endDate))."' ORDER BY id DESC, cus_date DESC";
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
    function listCustomTreatments($start, $end, $company_id, $conn) {
	
		$query="SELECT * FROM as_customtreatments WHERE c_id = '$company_id' ORDER BY id DESC LIMIT $start, $end";
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

    function listTypes($selected, $conn){

		$query = "SELECT * FROM as_controls ORDER BY id";
		$result = $conn->query($query);
		$response = "";
		while ($row = $result->fetch_assoc()) {
			$response .= '<option data-id="' . $row["id"] . '" value="' . $row["id"] . '"';
			if ($selected == $row["id"]) $response .= ' selected';
			$response .= '>' . $row["control_name"] . '</option>';
		}

		
		return $response;
	}

    function listTTypes($selected, $conn){

		#$query = "SELECT * FROM as_listtreatment ORDER BY id";
		$query = "SELECT * FROM as_controls ORDER BY id";
		$result = $conn->query($query);
		$response = "";
		while ($row = $result->fetch_assoc()) {
			$response .= '<option data-id="' . $row["id"] . '" value="' . $row["id"] . '"';
			if ($selected == $row["id"]) {$response .= ' selected';}else{}
			$response .= '>' . $row["control_name"] . '</option>';
		}

		
		return $response;
	}
	
	function listControlsCategory($id, $con) {
	    if ($id == 'null'){
	        $response = 'No Category Selected';
	    }else{
		$query="SELECT * FROM as_controls WHERE id = '$id'";
		$result = $con->query($query);
		if($result->num_rows > 0){
		    $row=$result->fetch_assoc();
		    $response = $row['control_name'];
		}else{
		    $response = 'Error!';
		}
	    }
	    
	    return $response;
	}
	
	function listTreatmentCategory($id, $con) {
	    if ($id == 'null'){
	        $response = 'No Category Selected';
	    }else{
		$query="SELECT * FROM as_customtreatments WHERE treatment_id = '$id'";
		$result = $con->query($query);
		if($result->num_rows > 0){
		    $row=$result->fetch_assoc();
		    $response = $row['title'];
		}else{
		    $response = 'Error!';
		}
	    }
	    
	    return $response;
	}
	
	function listControlsCategoryRep($id, $con) {
	    
	    if ($id == 'null' || $id == 0){
	        $response = 'No Category Selected';
	    }else{
	
    		$query="SELECT * FROM as_controls WHERE id = '$id'";
    		$result = $con->query($query);
    		if($result->num_rows > 0){
    		    $row=$result->fetch_assoc();
    		    $response = $row['control_name'];
    		}else{
    		    $response = 'No Category Selected';
    		}
	    }
	    
	    return $response;
	}
	
	function getNext($date, $freq) {

		if ($freq == 0) {
			$next = "Not set";
		} else if ($freq == 5){
		    $next = 'No Follow Up!';
		} else if ($freq == 1){
		    $next = 1;
		    $next = strtotime($date) + ($freq * 24 * 60 * 60);
    		$next = date("m/d/Y", $next);
		} else if ($freq == 2){
		    $next = 7;
		    $next = strtotime($date) + ($freq * 24 * 60 * 60);
    		$next = date("m/d/Y", $next);
		} else if ($freq == 3){
		    $next = 30;
		    $next = strtotime($date) + ($freq * 24 * 60 * 60);
    		$next = date("m/d/Y", $next);
		} else if ($freq == 4){
		    $next = 365;
		    $next = strtotime($date) + ($freq * 24 * 60 * 60);
    		$next = date("m/d/Y", $next);
		}
		
		return $next;
	}

	function getFrequency($freq) {

		if ($freq == 5) {
			return "Single Application";
		} else if ($freq == 1) {
			return "Daily Application";
		} else if ($freq == 2) {
			return "Weekly Application";
		} else if ($freq == 3) {
			return "Monthly Application";
		} else if ($freq == 4) {
			return "Yearly Application";
		} else {
			return "Single Application";
		}
	}
	

	function getEffectiveness($effe) {
		if ($effe == 1) {
			return "Not Effective";
		} else if ($effe == 2) {
			return "Barely Effective";
		} else if ($effe == 3) {
			return "Mildly Effective";
		} else if ($effe == 4) {
			return "Highly Effective";
		}else{
		    return "Effective";
		}
	}
	
	function switchFreq($f){
	    switch ($f) {
                case 1:
                    $response = 'Daily Controls';
                    break;
                
                case 2:
                    $response = 'Weekly Controls';
                    break;
                
                case 3:
                    $response = 'Fort-Nightly Controls';
                    break;
                    
                case 4:
                    $response = 'Monthly Controls';
                    break;
                
                case 5:
                    $response = 'Semi-Annually Controls';
                    break;
                
                case 6:
                    $response = 'Annually Controls';
                    break;
                    
                case 7:
                    $response = 'As Required';
                    break;
                
                default:
                    $response = 'Error!';
                    break;
            }
        return $response;
	}
	
	function switchEff($e){
	    switch ($e) {
                case 1:
                    $response = 'Effective';
                    break;
                
                case 2:
                    $response = 'InEffective';
                    break;
                
                case 3:
                    $response = 'Unassessed';
                    break;
                
                default:
                    $response = 'Error!';
                    break;
            }
        return $response;
	}
?>