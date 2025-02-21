<?php
    function getCustomRisks_New($id, $con){
		$query="SELECT * FROM as_customrisks WHERE risk_id = '$id'";
		$result=$con->query($query);
		if ($result->num_rows > 0) {	
			$row=$result->fetch_assoc();
			$response = $row['title'];
		}else{
			$response = 'Error!!';
		}
		return $response;
    }
    
    #independent fuctions
    function getAssessmentTreatment($type, $id, $company, $con){
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
    		    $response = 'Company Error!!';
    		}
        }else{
            $response = 'Treatment Type Error!!';
        }
        
        return $response;
    }
    
    function calculateRating($like, $consequence, $conn) {
	
		$result=$conn->query("SELECT * FROM as_consequence WHERE idconsequence='$consequence'");
		$con=$result->fetch_assoc();
		$result=$conn->query("SELECT * FROM as_like WHERE idlike='$like'");
		$li=$result->fetch_assoc();
		
		$sum=$li["li_value"]+$con["con_value"];
		if ($li["li_value"]==$con["con_value"]) $sum++; //when both 3 calse says medium but chart says high, so this is a hack
		switch ($sum) {
		
			case ($sum<=4):
				$response=1;
				break;
				
			case ($sum>4 and $sum<7):
				$response=2;
				break;
				
			case ($sum==7):
				$response=3;
				break;
				
			case ($sum>7):
				$response=4;
				break;
		}
		
		return $response;
	
	}
	function listControlsLib($user, $con) {
	
		$query="SELECT * FROM as_auditcontrols WHERE c_id = '$user' ORDER BY con_control";
		$result = $con->query($query);
		$response="";
		while ($row=$result->fetch_assoc()) {
			$response.='<option value="' . $row["idcontrol"] . '">' . $row["con_control"] . '</option>';
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


    function listRisks($type, $selected, $con){
		$query="SELECT * FROM as_risks WHERE ri_type = '$type' ORDER BY idrisk";
		$result=$con->query($query);
		if ($result->num_rows > 0) {	
			$response='<select name="risk" id="risk" class="form-control" required>';
			$response.='<option value="0">Please select risk...</option>';
			while ($row=$result->fetch_assoc()) {
				$response.='<option value="' . $row["idrisk"] . '"';
				if ($row["idrisk"]==$selected) $response.=' selected';
				$response.='>' . $row["ri_name"] . '</option>';
			}
			$response.="</select>";
		}else{
			$response = 'error';
		}
		return $response;
    }
    
    function listCustomRisks($company_id, $selected, $industry, $con){
		$query="SELECT * FROM as_customrisks WHERE c_id = '$company_id' AND industry = '$industry'";
		$result=$con->query($query);
		$response = '';
		if ($result->num_rows > 0) {	
			while ($row=$result->fetch_assoc()) {
				$response.='<option value="' . $row["risk_id"] . '"';
				if ($row["risk_id"] == $selected) $response.=' selected';
				$response.='>' . ucfirst($row["title"]) . '</option>';
			}
		}else{
		    $response = 'empty';
		}
		return $response;
    }
    
    function listKRI($company_id,  $con, $selected = null){
		$query="SELECT * FROM kri WHERE c_id = '$company_id'";
		$result=$con->query($query);
		$response = '';
		if ($result->num_rows > 0) {	
			while ($row=$result->fetch_assoc()) {
				$response.='<option value="' . $row["k_id"] . '"';
				if ($row["k_id"] == $selected && $selected !== null) $response.=' selected';
				$response.='>' . ucfirst($row["indicator"]) . '</option>';
			}
		}else{
		    $response = '<option value="null">No Key Risk Indicator Registered Yet!</option>';
		}
		return $response;
    }
    
    function listRisksNew($type, $selected, $company_id, $con, $null = false){
		$query="SELECT * FROM as_newrisk WHERE industry = '$type' ORDER BY id";
		$result=$con->query($query);
		$response='<select name="risk" id="risk" class="form-control" required>';
		if($null == false){
			$response.='<option value="0">Please select risk...</option>';
		}
		if(listCustomRisks($company_id, $selected, $type, $con) !== 'empty'){
			$response.= listCustomRisks($company_id, $selected, $type, $con);
		}
		if ($result->num_rows > 0) {	
			while ($row=$result->fetch_assoc()) {
				$response.='<option value="' . $row["risk_id"] . '"';
				if ($row["risk_id"]==$selected) $response.=' selected';
				$response.='>' . ucwords($row["title"]) . '</option>';
			}
		}
		$response.="</select>";
		return $response;
    }
    
    function getRisks_New($id, $con){
		$query="SELECT * FROM as_newrisk WHERE risk_id = '$id'";
		$result=$con->query($query);
		if ($result->num_rows > 0) {	
			$row=$result->fetch_assoc();
			$response = $row['title'];
		}else{
			$response = 'Error!!';
		}
		return $response;
    }
    
    function getHazards_New($id, $id_2, $con){
		$query="SELECT * FROM as_newrisk_sub WHERE risk_id = '$id'";
		$result=$con->query($query);
		if ($result->num_rows > 0) {
		    $response = 'Error';
		    
			$row=$result->fetch_assoc();
			$title = unserialize($row['title']);
		    
		    foreach($title as $value){
		        if($value['id'] == $id_2){
		            $response = ucwords($value['text']);
		        }
		    }
		}else{
			$response = 'Error!!';
		}
		
		return $response;
    }
    
    function getRisks($id, $con){
		$query="SELECT * FROM as_risks WHERE idrisk = '$id'";
		$result=$con->query($query);
		if ($result->num_rows > 0) {	
			$row=$result->fetch_assoc();
			$response = $row['ri_name'];
		}else{
			$response = 'Error!!';
		}
		return $response;
    }
    
    
    
    function getHazards($id, $con){
		$query="SELECT * FROM as_cat WHERE idcat = '$id'";
		$result=$con->query($query);
		if ($result->num_rows > 0) {	
			$row=$result->fetch_assoc();
			$response = $row['cat_name'];
		}else{
			$response = 'Error!!';
		}
		return $response;
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

    function listHazards($cat, $selected, $con) {
	
		$query="SELECT * FROM as_cat WHERE cat_risk = '$cat' ORDER BY idcat";
		$result=$con->query($query);
		if ($result->num_rows > 0) {	
			$response='<select name="hazard" id="hazard" class="form-control" required>';
			$response.='<option value="0">Please select risk sub category...</option>';
			while ($row=$result->fetch_assoc()) {
				$response.='<option value="' . $row["idcat"] . '"';
				if ($row["idcat"]==$selected) $response.=' selected';
				$response.='>' . $row["cat_name"] . '</option>';
			}
			$response.="</select>";
		}else{
			$response = 'error';
		}
		return $response;
	
	}
	
	function listHazardsNew($cat, $con) {
	
		$query="SELECT * FROM as_newrisk_sub WHERE risk_id = '$cat'";
		$result=$con->query($query);
		if ($result->num_rows > 0) {
		    $row=$result->fetch_assoc();
		    $subs = unserialize($row['title']);
		    
		    $response='<select name="hazard" id="hazard" class="form-control" required>';
		    foreach($subs as $value){
		        $response.='<option value="' . $value["id"] . '">' . ucwords($value['text']) . '</option>';
		    }
		    $response.="</select>";
		}else{
			$response = 'error';
		}
		return $response;
	
	}
	
	function listHazardsNewSelected($cat, $selected, $con) {
	
		$query="SELECT * FROM as_newrisk_sub WHERE risk_id = '$cat'";
		$result=$con->query($query);
		if ($result->num_rows > 0) {
		    $row=$result->fetch_assoc();
		    $subs = unserialize($row['title']);
		    
		    $response='<select name="hazard" id="hazard" class="form-control" required>';
		    foreach($subs as $value){
		        $response.='<option value="' . $value["id"] . '"';
				if ($value["id"] == $selected) $response.=' selected';
				$response.= '>' . ucfirst($value["text"]) . '</option>';
						    }
		    $response.="</select>";
		}else{
			$response = 'error';
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

    function listControls($id, $con) {
	
		if ($id == -1) {
			$sessAss = $_SESSION["assessment"];
			$query="SELECT * FROM as_ascontrols WHERE ct_tmpid = '$sessAss'";
		} else {
			$query="SELECT * FROM as_ascontrols WHERE ct_det = '$id'";
		}
		
		$result=$con->query($query);
		if ($result->num_rows > 0) {
			$response = "";
			$response.=$sessAss;
			$response.='<table width="100%">';
			while ($row=$result->fetch_assoc()) {
				$response.='<tr><td style="padding:3px;">' . ucwords($row["ct_descript"]). '</td><td width="20"><button title="Delete control from risk" type="button" class="btn btn-xs btn-danger del-ctrls" data-description="'.ucwords($row["ct_descript"]).'" data-id="'.$row["rand_id"].'" data-toggle="modal" data-target="#del-control"><i class="fas fa-times"></i></button></td></tr>';
			}
			$response.='</table>';		
			$response.='&nbsp;';
		} else {
			$response=false;
		}
		
		return $response;
	
	}

    function rating($likelihood, $consequence, $con){
        if ($likelihood <= 5 && $consequence <= 5) {
            $rating = calculateRating($likelihood, $consequence, $con);
            switch ($rating) {
                case 1:
                    $riskRating = '<span class="rat_low"><i class="fas fa-check-double"></i> Low</span>';
                    break;
                case 2:
                    $riskRating = '<span class="rat_medium"><i class="fas fa-check"></i> Medium</span>';
                    break;
                case 3:
                    $riskRating = '<span class="rat_high"><i class="fas fa-exclamation"></i> High</span>';
                    break;
                case 4:
                    $riskRating = '<span class="rat_extreme"><i class="fas fa-exclamation-triangle"></i> Extreme</span>';
                    break;
            }
        } else {
			$riskRating = 'Error!!';
            // echo 'Please select Likelihood and Consequence...';
        }
		return $riskRating;
    }

    function listLikelihood($selected, $con) {
	
		$query="SELECT * FROM as_like ORDER BY idlike";
		$result=$con->query($query);
		if ($result->num_rows > 0) {
			$response='<select name="likelihood" id="likelihood" class="form-control" required>';
			$response.='<option value="0">Please select likelihood...</option>';
			while ($row=$result->fetch_assoc()) {
				$response.='<option value="' . $row["idlike"] . '"';
				if ($row["idlike"]==$selected) $response.=' selected';
				$response.='>' . $row["li_like"] . '</option>';
			}
			$response.="</select>";
		}else{
			$response = 'error';
		}
		return $response;
	
	}

	function listConsequence($selected, $con) {
	
		$query="SELECT * FROM as_consequence ORDER BY idconsequence";
		$result=$con->query($query);
		if ($result->num_rows > 0) {
			$response='<select name="consequence" id="consequence" class="form-control" required>';
			$response.='<option value="0">Please select consequence...</option>';
			while ($row=$result->fetch_assoc()) {
				$response.='<option value="' . $row["idconsequence"] . '"';
				if ($row["idconsequence"]==$selected) $response.=' selected';
				$response.='>' . $row["con_consequence"] . '</option>';
			}
			$response.="</select>";
		}else{
			$response = 'error';
		}
		return $response;
	
	}
	
	function listLikelihood_Residual($selected, $con) {
	
		$query="SELECT * FROM as_like ORDER BY idlike";
		$result=$con->query($query);
		if ($result->num_rows > 0) {
			$response='<select name="likelihood_residual" id="likelihood_residual" class="form-control" required>';
			$response.='<option value="0">Please select likelihood...</option>';
			while ($row=$result->fetch_assoc()) {
				$response.='<option value="' . $row["idlike"] . '"';
				if ($row["idlike"]==$selected) $response.=' selected';
				$response.='>' . $row["li_like"] . '</option>';
			}
			$response.="</select>";
		}else{
			$response = 'error';
		}
		return $response;
	
	}

	function listConsequence_Residual($selected, $con) {
	
		$query="SELECT * FROM as_consequence ORDER BY idconsequence";
		$result=$con->query($query);
		if ($result->num_rows > 0) {
			$response='<select name="consequence_residual" id="consequence_residual" class="form-control" required>';
			$response.='<option value="0">Please select consequence...</option>';
			while ($row=$result->fetch_assoc()) {
				$response.='<option value="' . $row["idconsequence"] . '"';
				if ($row["idconsequence"]==$selected) $response.=' selected';
				$response.='>' . $row["con_consequence"] . '</option>';
			}
			$response.="</select>";
		}else{
			$response = 'error';
		}
		return $response;
	
	}

	function listControl($user, $con){
		$response="";
		$query="SELECT * FROM as_controls ORDER BY id";
		$response1 = listControlsLib($user, $con);
		$result=$con->query($query);
		while ($row=$result->fetch_assoc()) {
			$response.='<option value="' . $row["id"] . '">' . $row["control_name"] . '</option>';
		}
		return $response. $response1;
	}
	
	function listControl_New($id, $con){
		$response="";
		$query="SELECT * FROM as_newrisk WHERE risk_id = '$id'";
		$result=$con->query($query);
		if ($result->num_rows > 0) {
		    $row=$result->fetch_assoc();
    		$control_list = unserialize($row['control']);
    		$response.= '<select name="existing_ct[]" id="existing_ct" class="form-control" required>';
    		foreach($control_list as $control){
    		    $response.= '<option value="'.ucwords($control['id']).'">'.ucwords($control['text']).'</option>';
    		}
    		$response.= '</select>';
		}else{
		    $response.='<option value="null" selected>No Control Recommended For This Risk!!</option>';
		}
		
		return $response;
	}
	
	function listControl_NewSelected($id, $selected, $con){
		$response="";
		$query="SELECT * FROM as_newrisk WHERE risk_id = '$id'";
		$result=$con->query($query);
		if ($result->num_rows > 0) {
		    $row=$result->fetch_assoc();
    		$control_list = unserialize($row['control']);
    		$response.= '<select name="existing_ct[]" id="existing_ct" class="form-control" required>';
    		foreach($control_list as $control){
    		    $response.='<option value="' . $control['id'] . '"';
    			if (strtolower($control['id']) == strtolower($selected)) $response.=' selected';
    			$response.='>' . ucwords($control['text']) . '</option>';
    		    #$response.= '<option value="'.ucwords($control).'">'.ucwords($control).'</option>';
    		}
    		$response.= '</select>';
		}else{
		    $response.='<option value="null" selected>No Control Recommended For This Risk!!</option>';
		}
		
		return $response;
	}
	
	function getControlTitle($risk, $id, $con){
	    $response="";
		$query="SELECT * FROM as_newrisk WHERE risk_id = '$risk'";
		$result=$con->query($query);
		if ($result->num_rows > 0) {
		    $row=$result->fetch_assoc();
    		$control_list = unserialize($row['control']);
    		foreach($control_list as $control){
    			if (strtolower($control['id']) == strtolower($id)) {
    			    $response = $control['text'];
    			}
    		}
		}else{
		    $response = 'Error!!';
		}
		
		return $response;
	}
	
	function getControlTitle_Custom($risk, $id, $con){
	    return $id;
	}
	
	function getControlTitle_Saved($risk, $id, $con){
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

	function listActions($selected, $con) {
	
		$response='<select name="actiontake" id="actiontake" class="form-control" required>';
		$response.='<option value="">Please select action to take...</option>';
		$query="SELECT * FROM as_actiontype ORDER BY idaction";
		$result=$con->query($query);
		while ($row=$result->fetch_assoc()) {
			$response.='<option value="' . $row["idaction"] . '"';
			if ($row["idaction"]==$selected) $response.=' selected';
			$response.='>' . $row["ac_type"] . '</option>';
		}
		$response.="</select>";
		return $response;
	
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
	
	function getDescription($type, $con){
	    $query="SELECT * FROM as_newrisk WHERE risk_id = '$type' LIMIT 1";
	    $result=$con->query($query);
	    if ($result->num_rows > 0) {
    		$row=$result->fetch_assoc();
    		$response = ucfirst(html_entity_decode($row['description']));
		}else{
		    $response = 'Error!!';
		}
		return $response;
	}
	
	function getCustomData($risk, $con){
	    $query="SELECT * FROM as_customrisks WHERE risk_id = '$risk' LIMIT 1";
	    $result=$con->query($query);
	    if ($result->num_rows > 0) {
    		$row=$result->fetch_assoc();
    		$response = array(
    		    'desc' => ucfirst(html_entity_decode($row['description'])),
    		    'sub' => ucfirst(html_entity_decode($row['sub'])),
    		    'owner' => ucwords(html_entity_decode($row['owner']))
    		);

		}else{
		    
		    $response = array(
		        'desc' => 'Error Fetching Risk Description',
		        'sub' => 'Error Fetching Sub Risk...',
		        'owner' => 'Error Fetching Owner...'
		    );
		}
		
		return json_encode($response);
	}
	
	

	function addTreatment($id, $descript, $tmp, $assessment, $conn, $rand_id) {
		$query="INSERT INTO as_astreat (tr_det, tr_descript, tr_tmpid, tr_assessment, rand_id) VALUES ('$id', '$descript', '$tmp', '$assessment', '$rand_id')";
		
		if ($conn->query($query)) {
			$response=true;		
		} else {
			$response=false;
		}

		return $response;
	
	}

	function addControl($id, $descript, $tmp, $assessment, $con, $rand_id) {
		$query="INSERT INTO as_ascontrols (ct_det, ct_descript, ct_tmpid, ct_assessment, rand_id) VALUES ('$id', '$descript', '$tmp', '$assessment', '$rand_id')";
		
		if ($con->query($query)) {
			$response=true;		
		} else {
			$response=false;
		}

		return $response;
	
	}

	function deleteTreatment($id, $assessment, $con) {
	
		$query="DELETE FROM as_astreat WHERE tr_assessment = '$assessment' AND rand_id='$id'";
		
		if ($con->query($query)) {
			$response=true;		
		} else {
			$response=false;
		}

		return $response;
	
	}

	function deleteControl($id, $assessment, $con) {
	
		$query="DELETE FROM as_ascontrols WHERE ct_assessment = '$assessment' AND rand_id='$id'";
		
		if ($con->query($query)) {
			$response=true;		
		} else {
			$response=false;
		}

		return $response;
	
	}

	#get requests
	if (isset($_POST["getRisk"])) {
		include '../../layout/db.php';

		function sanitizePlus($_data) {
			$data = trim($_data);
			$data = stripslashes($data);
			$data = strip_tags($data);
			$data = htmlspecialchars($data);
			return $data;
		}
		
		$value = $_POST["getRisk"];
        $getArray = getToArray($value);
		
        $cat = sanitizePlus($getArray['category']);
        $selected = sanitizePlus($getArray['selected']);

		$response = listHazards($cat, $selected, $con);
		
		echo $response;
    }
    
    if (isset($_POST["getHazard"])) {
		include '../../layout/db.php';

		function sanitizePlus($data) {
			$data = trim($data);
			$data = stripslashes($data);
			$data = strip_tags($data);
			$data = htmlspecialchars($data);
			return $data;
		}
		
		$value = $_POST["getHazard"];
        $getArray = getToArray($value);
		
        $cat = sanitizePlus($getArray['category']);

		$response = listHazardsNew($cat, $con);
		
		echo $response;
    }
    
    if (isset($_POST["getControls"])) {
		include '../../layout/db.php';

		function sanitizePlus($data) {
			$data = trim($data);
			$data = stripslashes($data);
			$data = strip_tags($data);
			$data = htmlspecialchars($data);
			return $data;
		}
		
		$value = $_POST["getControls"];
        $getArray = getToArray($value);
		
        $risk = sanitizePlus($getArray['risk']);

		$response = listControl_New($risk, $con);
		
		echo $response;
    }
    
    if (isset($_POST["getCustomData"])) {
		include '../../layout/db.php';

		function sanitizePlus($data) {
			$data = trim($data);
			$data = stripslashes($data);
			$data = strip_tags($data);
			$data = htmlspecialchars($data);
			return $data;
		}
		
		$value = $_POST["getCustomData"];
        $getArray = getToArray($value);
		
        $risk = sanitizePlus($getArray['risk']);

		$response = getCustomData($risk, $con);
		
		echo $response;
    }
    
    
    if (isset($_POST["getDescription"])) {
		include '../../layout/db.php';

		function sanitizePlus($data) {
			$data = trim($data);
			$data = stripslashes($data);
			$data = strip_tags($data);
			$data = htmlspecialchars($data);
			return $data;
		}
		
		$value = $_POST["getDescription"];
        $getArray = getToArray($value);
		
        $cat = sanitizePlus($getArray['category']);

		$response = getDescription($cat, $con);
		
		echo $response;
    }
    
    
	if (isset($_POST["getRating"])) {
		include '../../layout/db.php';

		function sanitizePlus($data) {
			$data = trim($data);
			$data = stripslashes($data);
			$data = strip_tags($data);
			$data = htmlspecialchars($data);
			return $data;
		}
		
		$value = $_POST["getRating"];
        $getArray = getToArray($value);
		
        $consequence = sanitizePlus($getArray['consequence']);
        $likelihood = sanitizePlus($getArray['likelihood']);

		$response = rating($likelihood, $consequence, $con);
		echo $response;
		// echo $consequence.' '.$likelihood;
    }
    
    if (isset($_POST["getRating_r"])) {
		include '../../layout/db.php';

		function sanitizePlus($data) {
			$data = trim($data);
			$data = stripslashes($data);
			$data = strip_tags($data);
			$data = htmlspecialchars($data);
			return $data;
		}
		
		$value = $_POST["getRating_r"];
        $getArray = getToArray($value);
		
        $consequence = sanitizePlus($getArray['consequence']);
        $likelihood = sanitizePlus($getArray['likelihood']);

		$response = rating($likelihood, $consequence, $con);
		echo $response;
		// echo $consequence.' '.$likelihood;
    }

	if (isset($_POST["addTreatment"])) {
		include '../../layout/db.php';

		function sanitizePlus($data) {
			$data = trim($data);
			$data = stripslashes($data);
			$data = strip_tags($data);
			$data = htmlspecialchars($data);
			return $data;
		}

		function secure_random_string($length) { 
			$random_string = ''; 
			for($i = 0; $i < $length; $i++) { 
				$number = random_int(0, 36);  
				$character = base_convert($number, 10, 36);
				$random_string .= $character; 
			} 
					
			return $random_string;
		}

		$rand = secure_random_string(10);
		
		$value = $_POST["addTreatment"];
        $getArray = getToArray($value);
		
        $id = sanitizePlus($getArray['id']);
		$descript = sanitizePlus(ucwords($getArray['description']));
		$tmp = -1;
		$assessment = sanitizePlus($getArray['assessment']);

		$response = addTreatment($id, $descript, $tmp, $assessment, $con, $rand);
		if ($response == true) {
			$response = listTreatments($assessment, $con);
			echo $response;
		} else {
			echo 'Error';
		}
		
		
		// echo $consequence.' '.$likelihood;
    }

	if (isset($_POST["delTreatment"])) {
		include '../../layout/db.php';

		function sanitizePlus($data) {
			$data = trim($data);
			$data = stripslashes($data);
			$data = strip_tags($data);
			$data = htmlspecialchars($data);
			return $data;
		}
		
		$value = $_POST["delTreatment"];
        $getArray = getToArray($value);
		
        $id = sanitizePlus($getArray['id']);
		$assessment = sanitizePlus($getArray['assessment']);

		$response = deleteTreatment($id, $assessment, $con);
		if ($response == true) {
			$response = listTreatments($assessment, $con);
			echo $response;
		} else {
			echo 'Error';
		}
		
		
		// echo $consequence.' '.$likelihood;
    }

	if (isset($_POST["addControl"])) {
		include '../../layout/db.php';

		function sanitizePlus($data) {
			$data = trim($data);
			$data = stripslashes($data);
			$data = strip_tags($data);
			$data = htmlspecialchars($data);
			return $data;
		}

		function secure_random_string($length) { 
			$random_string = ''; 
			for($i = 0; $i < $length; $i++) { 
				$number = random_int(0, 36);  
				$character = base_convert($number, 10, 36);
				$random_string .= $character; 
			} 
					
			return $random_string;
		}

		$rand = secure_random_string(10);
		
		$value = $_POST["addTreatment"];
        $getArray = getToArray($value);
		
        $id = sanitizePlus($getArray['id']);
		$descript = sanitizePlus(ucwords($getArray['description']));
		$tmp = -1;
		$assessment = sanitizePlus($getArray['assessment']);

		$response = addTreatment($id, $descript, $tmp, $assessment, $con, $rand);
		if ($response == true) {
			$response = listTreatments($assessment, $con);
			echo $response;
		} else {
			echo 'Error';
		}
		
		
		// echo $consequence.' '.$likelihood;
    }

	if (isset($_POST["delControl"])) {
		include '../../layout/db.php';

		function sanitizePlus($__data) {
			$data = trim($__data);
			$data = stripslashes($data);
			$data = strip_tags($data);
			$data = htmlspecialchars($data);
			return $data;
		}
		
		$value = $_POST["delControl"];
        $getArray = getToArray($value);
		
        $id = sanitizePlus($getArray['id']);
		$assessment = sanitizePlus($getArray['assessment']);

		$response = deleteControl($id, $assessment, $con);
		if ($response == true) {
			$response = listTreatments($assessment, $con);
			echo $response;
		} else {
			echo 'Error';
		}
		
		
		// echo $consequence.' '.$likelihood;
    }


	#all assessment page

	function rowCount($conn, $table, $cond, $value) {
	
		$query="SELECT * FROM $table WHERE $cond = '$value'";
		$result=$conn->query($query);
		if ($result->num_rows > 0) {
			return $result->num_rows;
		} else {
			return false;	
		}
	
	}
	
	function rowCountTotal($conn, $table, $cond, $value, $second_value, $second_cond) {
	
		$query="SELECT * FROM $table WHERE $cond = '$value' AND $second_value = '$second_cond'";
		$result=$conn->query($query);
		if ($result->num_rows > 0) {
			return $result->num_rows;
		} else {
			return false;	
		}
	
	}

	function listAssessments($start, $end, $conn, $id) {
	
		$query="SELECT * FROM as_assessment WHERE c_id = '$id' ORDER BY idassessment DESC, as_date DESC LIMIT " . $start . ", " . $end;
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

	function listAssessmentsOfAml($start, $end, $conn, $id) {
	
		$query="SELECT * FROM as_assessment WHERE c_id = '$id' AND as_type = 5 ORDER BY idassessment DESC, as_date DESC LIMIT " . $start . ", " . $end;
		// $query = "SELECT * FROM as_assessment WHERE as_user = ".$_SESSION["userid"]. " AND as_type=5 ORDER BY idassessment DESC, as_date DESC
		// LIMIT " . $start . "," .$end;
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

	function listAssessment($assess_id, $start, $end, $conn) {
	
		$query="SELECT * FROM as_details LEFT JOIN as_risks ON as_risks.idrisk=as_details.as_risk LEFT JOIN as_cat ON as_cat.idcat=as_details.as_hazard WHERE as_details.as_id= '$assess_id' AND as_details_has_value = 'true' ORDER BY as_details.iddetail LIMIT $start , $end";
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

	function getAssessment($id, $conn) {
	
		$query="SELECT * FROM as_assessment LEFT JOIN as_types ON as_assessment.as_type  = as_types.idtype WHERE as_id = '$id'";
		$result=$conn->query($query);
		if ($result->num_rows > 0) {	
			$row=$result->fetch_assoc();
			$response=$row;
		} else {
			$response='false';	
		}

		return $response;
	
	}
	
	function listControlsForReport($id, $conn) {
	
		$query="SELECT * FROM as_ascontrols WHERE ct_det = '$id'";
		$response="";
		$result=$conn->query($query);
		if ($result->num_rows > 0) {
			while ($row=$result->fetch_assoc()) {
				$response.=$row['ct_descript']."\n";
			}		
		} else {
			$response=false;
		}
		return $response;
	
	}
	
	function listTreatmentsForReport($id, $conn) {
	
		$query="SELECT * FROM as_astreat WHERE tr_det= '$id'";
		$response="";
		$result=$conn->query($query);
		if ($result->num_rows > 0) {
			while ($row=$result->fetch_assoc()) {
				$response.=$row['tr_descript']."\n";
			}		
		} else {
			$response=false;
		}				
		return $response;
	
	}
	
	function getRating($rating){
		switch ($rating) {
				case 1:
					return 'Low';
					break;  
				case 2:
					return 'Medium';
					break;  
				case 3:
					return 'High';
					break;
				case 4:
					return 'Extreme';
					break;   
		  }
	}

	function getAssessmentDetForReport($id, $conn) {
	
		$query="SELECT * FROM as_details LEFT JOIN as_risks ON as_risks.idrisk=as_details.as_risk "
				." LEFT JOIN as_cat ON as_cat.idcat=as_details.as_hazard LEFT JOIN as_like ON as_like.idlike=as_details.as_like "
				." LEFT JOIN as_consequence ON as_consequence.idconsequence = as_details.as_consequence "
				." LEFT JOIN as_actiontype ON as_actiontype.idaction=as_details.as_action WHERE as_details.as_assessment='$id'";
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

	function getApproval($app){
		if($app == "1") {
			return "In Progress";
		} else if($app == "2"){
			return "Approved";
		}	else if($app == "3"){
			return "Closed";
		}
	}
	
	function countChart_New($id, $company_id, $like, $consequence, $conn) {
		$query="SELECT * FROM as_assessment_new WHERE assessment = '$id' AND likelihood='$like' AND consequence='$consequence' AND c_id = '$company_id'";
		$response="";
		$result=$conn->query($query);
		if ($result->num_rows > 0) {
			$response=$result->num_rows;	 
		}else{
            $response = false;
        }
		return $response;
	}
	
	function countLikelihood_New($id, $company_id, $like, $conn) {
		$query="SELECT * FROM as_assessment_new WHERE assessment= '$id' AND likelihood = '$like' AND c_id = '$company_id'";
		$result=$conn->query($query);
		if ($result->num_rows > 0) {
			$response=$result->num_rows;	
		} else {
			$response=false;
		}
		return $response;
	}
	
	function countConsequence_New($id, $company_id, $consequence, $conn) {
		$query="SELECT * FROM as_assessment_new WHERE assessment = '$id' AND consequence = '$consequence' AND c_id = '$company_id'";
		$result=$conn->query($query);
		if ($result->num_rows > 0) {
			$response=$result->num_rows;	
		} else {
			$response=false;
		}
		return $response;
	}
	
	function countRisks_New($id, $company_id, $rating, $conn) {
	    $query="SELECT * FROM as_assessment_new WHERE assessment = '$id' AND rating = '$rating' AND c_id = '$company_id'";
		$result=$conn->query($query);
		if ($result->num_rows > 0) {
			$response=$result->num_rows;	
		} else {
			$response=0;
		}
		return $response;
	}
	
	function countChart($id, $company_id, $like, $consequence, $conn) {
		$query="SELECT * FROM as_details WHERE as_assessment = '$id' AND as_like='$like' AND as_consequence='$consequence' AND c_id = '$company_id'";
		$response="";
		$result=$conn->query($query);
		if ($result->num_rows > 0) {
			$response=$result->num_rows;	 
		}else{
            $response = false;
        }
		return $response;
	}
	
	function countLikelihood($id, $company_id, $like, $conn) {
		$query="SELECT * FROM as_details WHERE as_assessment= '$id' AND as_like= '$like' AND c_id = '$company_id'";
		$result=$conn->query($query);
		if ($result->num_rows > 0) {
			$response=$result->num_rows;	
		} else {
			$response=false;
		}
		return $response;
	}
	
	function countConsequence($id, $company_id, $consequence, $conn) {
		$query="SELECT * FROM as_details WHERE as_assessment = '$id' AND as_consequence = '$consequence' AND c_id = '$company_id'";
		$result=$conn->query($query);
		if ($result->num_rows > 0) {
			$response=$result->num_rows;	
		} else {
			$response=false;
		}
		return $response;
	}
	
	function countRisks($id, $company_id, $rating, $conn) {
		$query="SELECT * FROM as_details WHERE as_assessment = '$id' AND as_rating = '$rating' AND c_id = '$company_id'";
		$result=$conn->query($query);
		if ($result->num_rows > 0) {
			$response=$result->num_rows;	
		} else {
			$response=false;
		}
		return $response;
	}
	
	function countControls($id, $conn) {
		$query="SELECT * FROM as_ascontrols WHERE ct_assessment='$id'";
		$result=$conn->query($query);
		if ($result->num_rows > 0) {
			$response=$result->num_rows;	
		} else {
			$response=false;
		}
		return $response;
	}
	
	function countTreatments($id, $conn) {
		$query="SELECT * FROM as_astreat WHERE tr_assessment = '$id'";
		$result=$conn->query($query);
		if ($result->num_rows > 0) {
			$response=$result->num_rows;	
		} else {
			$response=false;
		}
		return $response;
	}
	
	function getLikelihood($id, $con){
		$query="SELECT * FROM as_like WHERE idlike = '$id' LIMIT 1";
		$result=$con->query($query);
		if ($result->num_rows > 0) {	
            $row=$result->fetch_assoc();
			$response = $row["li_like"];
		}else{
			$response = 'Error 02!';
		}
		return $response;
    }

    function getConsequence($id, $con){
		$query="SELECT * FROM as_consequence WHERE idconsequence = '$id' LIMIT 1";
		$result=$con->query($query);
		if ($result->num_rows > 0) {	
            $row=$result->fetch_assoc();
			$response = $row["con_consequence"];
		}else{
			$response = 'Error 03!';
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
                    if ($row["control_id"] == $id) $response.=' selected';
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

    function getlistActions($id, $con) {
	
		$query="SELECT * FROM as_actiontype WHERE idaction = '$id' LIMIT 1";
		$result=$con->query($query);
        if($result->num_rows > 0){
            $row=$result->fetch_assoc();
            $response = $row["ac_type"];
        }else{
            $response = 'Error!';
        }
		return $response;
	}

    function listTypesAssessment($selected, $con){
        $query="SELECT * FROM as_types ORDER BY idtype";
        $result=$con->query($query);
        $response = "";
        while ($row=$result->fetch_assoc()) {
            $response.='<option value="' . $row["idtype"] . '"';
            if ($selected==$row["idtype"]) $response.=' selected';
            $response.='>' . $row["ty_name"] . '</option>';
        }

        return $response;
    }
?>