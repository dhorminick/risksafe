<?php
    #independent func

    function listCompliances($start, $length, $conn, $company) {
		$query = "SELECT * FROM as_compliancestandard WHERE c_id = '$company' LIMIT $start, $length";

		$result = $conn->query($query);

		if ($result->num_rows > 0) {
			$data = array();
			while ($row = $result->fetch_assoc()) {
				$data[] = $row;
			}

			return $data;
		} else {
			return false;
		}
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

    function listControlsLib($user, $conn){
        
        $query = "SELECT * FROM as_auditcontrols WHERE c_id = '$user' ORDER BY con_control";
        $result = $conn->query($query);
        $response = "";
        $optionText ='';
        // if($controldata!== null){
        //     // Use DOMDocument to extract the text content of the <option> element
        //     $dom = new DOMDocument();
        //     $dom->loadHTML($controldata);
            
        //     $optionText = $dom->getElementsByTagName('option')->item(0)->textContent;
        // }
    
        while ($row = $result->fetch_assoc()) {
            if ($row['con_control'] === $optionText) {
                continue;
            }

            // Generate HTML options for the dropdown menu
            $response .= '<option value="' . htmlspecialchars($row["idcontrol"]) . '">' . htmlspecialchars($row["con_control"]) . '</option>';
        }

        return $response;
    }

    function listControl($user,$conn){
        $controldata=null;
        $response="";
        $query="SELECT * FROM as_controls ORDER BY id";
        $response1 = listControlsLib($user, $conn);
        $result=$conn->query($query);
        while ($row=$result->fetch_assoc()) {
            $response.='<option value="' . $row["id"] . '">' . $row["control_name"] . '</option>';
        }
        return $response. $response1;
    }

    function listTreatmentsLib($user, $con) {
	
		$query="SELECT * FROM as_treatments WHERE c_id = '$user' ORDER BY tre_treatment";
		$result=$con->query($query);
		
			$response="";
			while ($row=$result->fetch_assoc()) {
				$response.='<option value="' . $row["idtreatment"] . '">' . $row["tre_treatment"] . '</option>';
			}
		
		return $response;
	
	}

    function listTreatments($id, $con) {
	
		if ($id == -1) {
			$sessAss = $_SESSION["assessment"];
			$query="SELECT * FROM as_astreat WHERE tr_tmpid = '$sessAss'";
		} else {
			$query="SELECT * FROM as_astreat WHERE tr_assessment = '$id'";
		}
			$result=$con->query($query);
			if ($result->num_rows > 0) {
				$response="";
				$response.='<table width="100%">';
				while ($row=$result->fetch_assoc()) {
					$response.='<tr><td style="padding:3px;">' . ucwords($row["tr_descript"]). '</td><td width="20"><button title="Delete treatment from risk" type="button" class="btn btn-xs btn-danger del-treats" data-description="'.ucwords($row["tr_descript"]).'" data-id="'.$row["rand_id"].'" data-toggle="modal" data-target="#del-treatment"><i class="fas fa-times"></i></button></td></tr>';
				}
				$response.='</table>';		
			} else {
				$response="";
				$response.='<option value="nan">No Treatments Created Yet!!</option>';
			}
			$response.='&nbsp;';
		
		return $response;
	
	}

    function listApplicable($start, $length, $company_id, $conn) {
        $query = "SELECT * FROM policyfields WHERE c_id = '$company_id' LIMIT $start, $length";
        $result = $conn->query($query);
        if ($result->num_rows > 0) {
            $data = [];
            while ($row = $result->fetch_assoc()) {
            $data[] = $row;
            }
        }else{
		    $data = false;
		}
		return $data;
    }

    function getApplicable($id,$conn) {

        $query = "SELECT * FROM policyfields WHERE p_id = '$id'";
        $result = $conn->query($query);
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $response = $row;
        } else {
            $response = false;
        }

        return $response;
    }
    function getApplicableProcedure($id, $conn) {

        $query = "SELECT * FROM as_procedures WHERE p_id = '$id'";
        $result = $conn->query($query);
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $response = $row;
        } else {
            $response = false;
        }

        return $response;
    }

    function listApplicableProcedures($start, $length, $company_id, $conn) {
        $query = "SELECT * FROM as_procedures WHERE c_id = '$company_id' LIMIT $start, $length";
        $result = $conn->query($query);
        if ($result->num_rows > 0) {
            $data = [];
            while ($row = $result->fetch_assoc()) {
            $data[] = $row;
            }
        }else{
		    $data = false;
		}
        return $data;
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
	
	function listCompanyTreatment($id, $con) {
	
		$response="";
		$query="SELECT * FROM as_customtreatments WHERE c_id = '$id'";
		$result=$con->query($query);
		if ($result->num_rows > 0) {
    		$response.='<option value="null" selected>No Custom Treatment Selected!!</option>';
    		while ($row=$result->fetch_assoc()) {
    			$response.='<option value="' . $row["treatment_id"] . '">' . $row["title"] . '</option>';
    		}   
		}else{
		    $response.='<option value="null" selected>No Custom Treatment Created Yet!!</option>';
		}
		return $response;
	
	}
    
    function listControlSelected($selected, $con){
		$response="";
		$query="SELECT * FROM as_controls ORDER BY id";
		#$response1 = listControlsLib($user, $con);
		$result=$con->query($query);
		while ($row=$result->fetch_assoc()) {
			$response.='<option value="' . $row["id"] . '"';
			if ($row["id"]==$selected) $response.=' selected';
			$response.='>' . $row["control_name"] . '</option>';
		}
		#return $response. $response1;
        return $response;
	}
	
	function listCompanyControlSelected($company_id, $id, $con) {
        
        if($id == 'null'){
            $response = listCompanyControl($company_id, $con);
        }else{
    		$response="";
    		$query="SELECT * FROM as_customcontrols WHERE c_id = '$company_id' AND control_id = '$id'";
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

    function listCompanyTreatmentSelected($company_id, $id, $con) {
        
        if($id == 'null'){
            $response = listCompanyTreatment($company_id, $con);
        }else{
    		$response="";
    		$query="SELECT * FROM as_customtreatments WHERE c_id = '$company_id' AND treatment_id = '$id'";
    		$result=$con->query($query);
    		if ($result->num_rows > 0) {
                $response.='<option value="null">No Custom Treatment Selected!!</option>';
        		while ($row=$result->fetch_assoc()) {
        			$response.='<option value="' . $row["treatment_id"] . '"';
                    if ($row["treatment_id"]==$id) $response.=' selected';
    			    $response.='>' . $row["title"] . '</option>';
        		}   
    		}else{
    		    $response.='<option value="null" selected>Error!!</option>';
    		}
        }
	
		return $response;
	
	}
	
	function getControlSelected($selected, $con){

        if($selected == 'null'){
            $response = 'None Selected';
        }else{
            $query="SELECT * FROM as_controls WHERE id = '$selected'";
            $result=$con->query($query);
            if ($result->num_rows > 0) {
                $row = $result->fetch_assoc();
                $response = $row["control_name"];
            }else{
                $response = 'Error!';
            }
        }
		return $response;
	}

    function getCompanyControlSelected($company_id, $id, $con) {
	
		if($id == 'null'){
            $response = 'None Selected';
        }else{
            $query="SELECT * FROM as_customcontrols WHERE c_id = '$company_id' AND control_id = '$id'";
            $result=$con->query($query);
            if ($result->num_rows > 0) {
                $row = $result->fetch_assoc();
                $response = $row["title"]; 
            }else{
                $response = 'Error!';
            }
        }
		return $response;
	
	}

    function getCompanyTreatmentSelected($company_id, $id, $con) {
	
		if($id == 'null'){
            $response = 'None Selected';
        }else{
            $query="SELECT * FROM as_customtreatments WHERE c_id = '$company_id' AND treatment_id = '$id'";
            $result=$con->query($query);
            if ($result->num_rows > 0) {
                $row = $result->fetch_assoc();
                $response = $row["title"]; 
            }else{
                $response = 'Error!';
            }
        }
		return $response;
	
	}
    #get request
?>