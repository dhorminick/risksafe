<?php
    #independent func
    function getControlTitle_Custom($id){
	    return $id;
	}
	
	function listComplianceRecommendedControl_Selected($id, $selected, $con) {
	    
    		$response="";
            if($id === null || $id === ''){
                $response.='<option>Error Fetching Recommended Control!!</option>';
            }else{
                
        		$query="SELECT * FROM as_compliancedata WHERE compliance_id = '$id' LIMIT 1";
        		$result=$con->query($query);
        		if ($result->num_rows > 0) {
        		    $row=$result->fetch_assoc();
        		    $control = unserialize($row['controls']);
    		    
                    $response.='<select name="existing_ct[]" class="form-control" required>';
        		    foreach($control as $value){
        		        if($value['control'] && $value['control'] !== '' && $value['control'] !== null){
                			$response.='<option value="' . $value["id"] . '"'; if($value['id'] == $selected){ $response.= ' selected'; } $response.='>' . ucfirst($value["control"]) . '</option>';
        		        }
        		    }
            		$response.='</select>';
            		
            		$response = preg_replace('/\s+/', ' ', $response);
        		}else{
        		    $response.='<option>No Recommended Control Specified For The Selected Compliance!!</option>';
        		}
        		
            }
            
        
		return $response;
	
	}
	
	function createControls($arr){
	    if($arr === null || !$arr || $arr === ''){
	        return '<option>No Recommended Controls Specified For Selected Compliance!</option>'; #if changed update the function below using this text
	    }else if( !is_array(unserialize($arr)) ){
	        return '<option>Error Fetching Recommended Controls For Selected Compliance!</option>'; #same
	    }else{
	        $controls = unserialize($arr);
	        $response = '';
	        
    	    foreach($controls as $con){
    	        
    	        if($con['control'] && $con['control'] !== '' && $con['control'] !== null){
		        $response.='<option value="' . $con["id"] . '"';
                // if (strtolower($row["control_id"]) == strtolower($id)) $response.=' selected';
    			$response.='>' . ucfirst($con["control"]) . '</option>';
    	        }
		    }

    	   return $response;
	    }
	    
	    
	}
	
	function getModuleData($id, $con){
	    $query="SELECT * FROM as_compliancedata WHERE compliance_id = '$id' LIMIT 1";
	    $result=$con->query($query);
	    if ($result->num_rows > 0) {
    		$row=$result->fetch_assoc();
    		
    		if($row['officers'] && $row['officers'] !== null && $row['officers'] !== ''){
    		    $row['officers'] = html_entity_decode($row['officers']);
    		}else{
    		    $row['officers'] = ' ';
    		}
    		
    		$controls = createControls($row['controls']);
    		
    		if($controls === '<option>No Recommended Controls Specified For Selected Compliance!</option>' || $controls === '<option>Error Fetching Recommended Controls For Selected Compliance!</option>' ){
    		    $hasControl = false;
    		}else{
    		    $hasControl = true;
    		}
    		
    		$response = array(
    		    'obligation' => ucfirst(html_entity_decode($row['obligation'])),
    		    'requirements' => ucfirst(html_entity_decode($row['requirement'])),
    		    'officers' => ucfirst($row['officers']),
    		    'frequency' => listFrequencies($row['frequency']),
    		    'effectiveness' => listEffectiveness($row['effectiveness']),
    		    'reference' => html_entity_decode($row['reference']),
    		    'controls' => createControls($row['controls']),
    		    'hasData' => true,
    		    'hasControl' => $hasControl
    		);

		}else{
		    
		    $response = array(
		        'obligation' => 'Error Fetching Compliance Obligation...',
		        'requirements' => 'Error Fetching Compliance Requirements...',
                'officers' => 'Error!',
                'frequency' => 'Error!',
                'effectiveness' => 'Error!',
                'reference' => 'Error!',
                'controls' => 'Error!',
    		    'hasData' => false
		    );
		}
		
		return json_encode($response);
	}
	
	function getSelected($selected, $query){
	    if($selected == $query){
	        return 'selected';
	    }else{
	        return '';
	    }
	}
	
	function listFrequencies($selected = null){
	    $response = '
	        <option value="1" '.getSelected($selected, 1).'>Daily Applications</option>
            <option value="2" '.getSelected($selected, 2).'>Weekly Applications</option>
            <option value="4" '.getSelected($selected, 4).'>Monthly Applications</option>
            <option value="5" '.getSelected($selected, 5).'>Quaterly Applications</option>
            <option value="8" '.getSelected($selected, 8).'>Half Yearly Applications</option>
            <option value="6" '.getSelected($selected, 6).'>Annually Applications</option>
            <option value="7" '.getSelected($selected, 7).'>As Required</option>
	    ';
	    
	    return $response;
	}
	
	function getFrequencyTitle($freq){
	    if ($freq == 7) {
			return "As Required";
		} else if ($freq == 1 || strtolower($freq) == 'daily') {
			return "Daily Applications";
		} else if ($freq == 2 || strtolower($freq) == 'weekly') {
			return "Weekly Applications";
		} else if ($freq == 3) {
			return "Fort-Nightly Controls";
		} else if ($freq == 4 || strtolower($freq) == 'monthly') {
			return "Monthly Applications";
		} else if ($freq == 5) {
			return "Quaterly Applications";
		} else if ($freq == 8) {
			return "Half Yearly Applications";
		} else if ($freq == 6 || strtolower($freq) == 'annually') {
			return "Annually Applications";
		} else {
			return "None Specified";
		}
	}
	
	function getEffectivenessTitle($effective){
	    $effect = strtolower($effective);
	    if ($effect === 'effective') {
			return "Effective";
		} else if ($effect === 'ineffective') {
			return "InEffective";
		} else {
			return "UnAssessed";
		}
	}
	
	function listEffectiveness($selected = null){
	    
	    if($selected == null){
    	    $response = '
    	        <option value="Effective">Effective</option>
                <option value="InEffective">InEffective</option>
                <option value="UnAssessed">UnAssessed</option> 
    	    ';
	    }else{
	        $response = '
    	        <option value="Effective" '.getSelected(strtolower($selected), strtolower('Effective')).'>Effective</option>
                <option value="InEffective" '.getSelected(strtolower($selected), strtolower('InEffective')).'>InEffective</option>
                <option value="UnAssessed" '.getSelected(strtolower($selected), strtolower('UnAssessed')).'>UnAssessed</option> 
    	    ';
	    }
	    
	    return $response;
	}
	
	function listCompanyControlSelected_New($company_id, $id, $con) {
        
        if($id == 'null'){
            $response = listCompanyControl($company_id, $con);
        }else{
    		$response="";
    		$query="SELECT * FROM as_customcontrols WHERE c_id = '$company_id' AND control_id = '$id'";
    		$result=$con->query($query);
    		if ($result->num_rows > 0) {
                $response.='<select name="saved-control[]" class="form-control" required>';
        		while ($row=$result->fetch_assoc()) {
        			$response.='<option value="' . $row["control_id"] . '"';
                    if (strtolower($row["control_id"]) == strtolower($id)) $response.=' selected';
    			    $response.='>' . ucfirst($row["title"]) . '</option>';
        		}   
        		$response.='</select>';
    		}else{
    		    $response.='Error Fetching Saved Control!!';
    		}
        }
		return $response;
	
	}
	
	function listCompanyTreatmentSelected_New($company_id, $id, $con) {
        
        if($id == 'null'){
            $response = listCompanyTreatment($company_id, $con);
        }else{
    		$response="";
    		$query="SELECT * FROM as_customtreatments WHERE c_id = '$company_id'";
    		$result=$con->query($query);
    		if ($result->num_rows > 0) {
    		    $response.='<select name="saved-treatment[]" class="form-control" required>';
        		while ($row=$result->fetch_assoc()) {
        			$response.='<option value="' . $row["treatment_id"] . '"';
                    if (strtolower($row["treatment_id"]) == strtolower($id)) $response.=' selected';
    			    $response.='>' . ucfirst($row["title"]) . '</option>';
        		}  
        		$response.='</select>';
    		}else{
    		    $response.='Error Fetching Saved Treatment!!';
    		}
        }
	
		return $response;
	}
	
	
	function listControlSelected_New($selected, $con){
		$response="";
		$query="SELECT * FROM as_controls ORDER BY id";
		$result=$con->query($query);
		if ($result->num_rows > 0) {	
			$response='<select name="existing_ct[]" id="existing_ct" class="form-control" required>';
			while ($row=$result->fetch_assoc()) {
				$response.='<option value="' . $row["id"] . '"';
    			if ($row["id"]==$selected) $response.=' selected';
    			$response.='>' . $row["control_name"] . '</option>';
			}
			$response.="</select>";
		}else{
			$response = 'error';
		}
		
        return $response;
	}
	
	function getComplianceTreatment($type, $id, $company, $con){
        if($type == 'custom'){
            $response = ucfirst($id);
        }else if($type == 'saved'){
            $query="SELECT * FROM as_customtreatments WHERE c_id = '$company'";
    		$result=$con->query($query);
    		if ($result->num_rows > 0) {
        		$response = 'Treatment Error!!';
        		while ($row=$result->fetch_assoc()) {
        			if($row['treatment_id'] == strtolower($id)){
        			    $response = ucfirst($row['title']);
        			}
        		}  
    		}else{
    		    $response = 'Not Found!!';
    		}
        }else{
            $response = 'Treatment Type Error!!';
        }
        
        return $response;
    }
	
	function getControlTitle($id, $con){
	    $response="";
		$query="SELECT * FROM as_controls WHERE id =  '$id'";
		$result=$con->query($query);
		if ($result->num_rows > 0) {
		    $row=$result->fetch_assoc();
		    $response = $row["control_name"];
    // 		while ($row=$result->fetch_assoc()) {
    // 		    if(strtolower($id) === strtolower($row["id"])){
    // 		        $response = $row["control_name"];
    // 		    }

    //         }
		}else{
		    $response = 'Not Found!!';
		}
		
		return $response;
	}
	
	function getControlTitle_Saved($id, $con){
	    $response="";
		$query="SELECT * FROM as_customcontrols WHERE control_id = '$id'";
		$result=$con->query($query);
		if ($result->num_rows > 0) {
		    $row=$result->fetch_assoc();
		    $response = $row['title'];
		}else{
		    $response = 'Error!!';
		}
		
		return $response;
	}
	
    function getSelectedCompliance($selected, $con){

        if($selected === 'null'){
            $response = 'None Selected';
        }else{
            $query="SELECT * FROM as_compliancedata WHERE compliance_id = '$selected'";
            $result=$con->query($query);
            if ($result->num_rows > 0) {
                $row = $result->fetch_assoc();
                $response = $row["section"];
            }else{
                $response = 'Error!';
            }
        }
		return $response;
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
	

	if (isset($_POST["getModule"])) {
		include '../../layout/db.php';

		function sanitizePlus($data) {
			$data = trim($data);
			$data = stripslashes($data);
			$data = strip_tags($data);
			$data = htmlspecialchars($data);
			return $data;
		}
		
		$value = $_POST["getModule"];
        $getArray = getToArray($value);
		
        $id = sanitizePlus($getArray['module_id']);

		$response = getModuleData($id, $con);
		
		echo $response;
    }
    
    function moduleExist($module, $con){
        $query="SELECT * FROM as_compliance WHERE compliance_id = '$module'";
		$result=$con->query($query);
		if($result->num_rows > 0){
		    return true;
		}
		
		return false;
    }
    
    function listModules($con, $selected = null) {
	
		$query="SELECT * FROM as_compliance ORDER BY id";
		$result=$con->query($query);
		
			$response="";
			if($selected == 'null' || $selected == ''){
			    $response.= '<option value="none" selected>-- Select Compliance Module --</option>';
			}
			
			while ($row=$result->fetch_assoc()) {
			    $response.='<option value="' . $row["compliance_id"] . '"';
			    if ($selected !== null && $row["compliance_id"] === $selected){ $response.=' selected'; }
    			    $response.='>' . ucfirst(html_entity_decode($row["title"])) . '</option>';
    			    
			}
		
		return $response;
	
	}
    
	function listModuleCompliance($selected, $id, $con) {
	
		$query="SELECT * FROM as_compliancedata WHERE module = '$id' ORDER BY id";
		$result=$con->query($query);
		
			$response="";
			if($selected == 'null'){
			    $response.= '<option value="none" selected>-- Select Compliance Data --</option>';
			}
			
			while ($row=$result->fetch_assoc()) {
			    $response.='<option value="' . $row["compliance_id"] . '"';
                    if (strtolower($row["compliance_id"]) == strtolower($selected) && $selected !== 'null') $response.=' selected';
    			    $response.='>' . ucfirst($row["section"]) . '</option>';
    			    
				// $response.='<option value="' . $row["compliance_id"] . '">' . ucfirst($row["section"]) . '</option>';
			}
		
		return $response;
	
	}
	
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