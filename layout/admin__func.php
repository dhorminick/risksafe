<?php
require 'mail.php';
#funcs
    
    function __getAssessmentTreatment($type, $id, $company, $con){
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
    
    function __listCompanyTreatmentSelected_New($company_id, $id, $con) {
        
        if($id == 'null'){
            $response = __listCompanyTreatment($company_id, $con);
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
	
    function __listCompanyTreatment($id, $con) {
	
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
	
	function __listCompanyIncidents($id, $con, $selected = null) {
	
		$response="";
		$query="SELECT * FROM as_incidents WHERE c_id = '$id'";
		$result=$con->query($query);
		if ($result->num_rows > 0) {
    		$response.='<option value="null" selected>No Incident Selected!!</option>';
    		while ($row=$result->fetch_assoc()) {
    // 			$response.='<option value="' . $row["in_id"] . '">' . $row["in_title"] . '</option>';
    			
    			$response.='<option value="' . $row["in_id"] . '"';
                if ($selected !== null && strtolower($selected) === strtolower($row["in_id"])) $response.=' selected';
    			$response.='>' . ucfirst($row["in_title"]) . '</option>';
    		}   
		}else{
		    $response.='<option value="null" selected>No Incident Created Yet!!</option>';
		}
		return $response;
	
	}
	
	function __getIncident($company_id, $id, $con) {
	
		$response="";
		$query="SELECT * FROM as_incidents WHERE c_id = '$company_id' AND in_id = '$id'";
		$result=$con->query($query);
		if ($result->num_rows > 0) {
    		$row=$result->fetch_assoc();
    		return $row['in_title'];
		}else{
		    return 'Error Fetching Incident!';
		}

	}
	
	function __listCompanyIncidents_Selected($id, $con, $selected = null) {
	
		$response="";
		$query="SELECT * FROM as_incidents WHERE c_id = '$id'";
		$result=$con->query($query);
		if ($result->num_rows > 0) {
		    $response.='<select name="incidents[]" class="form-control" required>';
    		$response.='<option value="null" selected>No Incident Selected!!</option>';
    		while ($row=$result->fetch_assoc()) {
    // 			$response.='<option value="' . $row["in_id"] . '">' . $row["in_title"] . '</option>';
    			
    			$response.='<option value="' . $row["in_id"] . '"';
                if ($selected !== null && strtolower($selected) === strtolower($row["in_id"])) $response.=' selected';
    			$response.='>' . ucfirst($row["in_title"]) . '</option>';
    		}   
		}else{
		    $response.='<option value="null" selected>No Incident Created Yet!!</option>';
		}
		$response.='</select>';
		
		return $response;
	
	}
	
    function getUserDetailsWithId($company_id, $get_user_id, $con){
        #confirm user
        $ConfirmUserExist = "SELECT * FROM users WHERE company_id = '$company_id'";
        $ConfirmedUser = $con->query($ConfirmUserExist);
        if ($ConfirmedUser->num_rows > 0) {
            $row = $ConfirmedUser->fetch_assoc();
            $company_users = $row['company_users'];

            $company_users = unserialize($company_users);
            $companycount = count($company_users);

            $isInArray = in_array_custom($get_user_id, $company_users) ? 'found' : 'notfound';
            if($isInArray === 'found'){
                for ($rowArray = 0; $rowArray < $companycount; $rowArray++) {
                    // echo 'row - '.$row.', email = '.$arrayAll[$row]['email'].', password = '.$arrayAll[$row]['password'];
                    if($company_users[$rowArray]['id'] == $get_user_id){
                        $rowNumber = $rowArray;
                    }
                }
                $details = array(
                    'name' => $company_users[$rowNumber]['fullname'],
                    'email' => $company_users[$rowNumber]['email'],
                );
            }else{
                $details = 'error';
            }
        }else{
            $details = 'error';
        }
        return $details;
    }

    function sendNotificationUser($company_id, $notification_message, $datetime, $notifier, $link, $type, $case, $id, $conn, $sitee){
        $link = $sitee.$link;
        $role = $GLOBALS['role'];
        // $adminemailaddr = 'jay@risksafe.co';
        $n_case_custom = $case.'-'.$type;
        $query_details = "INSERT INTO notification (n_message, n_datetime, n_sender, link, c_id, status, type, n_case, n_case_custom, role) VALUES ('$notification_message', '$datetime', '$notifier', '$link', '$company_id', 'unread', '$type', '$case', '$n_case_custom', '$role')";
        $query_completed = $conn->query($query_details);
        if ($query_completed) {
            $notified = 'true';
        }else{
            $notified = 'false';
            
        }
        
        return $notified;
    }
    
    function __getCompanyData($id, $con){
        $query_details = "SELECT * FROM users WHERE company_id = '$id' LIMIT 1";
        $UserExist = $con->query($query_details);
        if ($UserExist->num_rows > 0) {
            $_data = $UserExist->fetch_assoc();
            
            return $_data;
        }else{
            return 'false';
        }
    }

    function createNotification($company_id, $notification_message, $datetime, $notifier, $link, $type, $case, $conn, $sitee){
        $company_data = __getCompanyData($company_id, $conn);
        if($company_data === 'false'){
            return 'false';
        }
        
        // $link = $link;
        $role = $GLOBALS['role'];
        $n_case_custom = $case.'-'.$type;
        $query_details = "INSERT INTO notification (n_message, n_datetime, n_sender, link, c_id, status, type, n_case, n_case_custom, role) VALUES ('$notification_message', '$datetime', '$notifier', '$link', '$company_id', 'unread', '$type', '$case', '$n_case_custom', '$role')";
        $query_completed = $conn->query($query_details);
        if ($query_completed) {
            
            #send notif email
            if($company_data['user_loginstatus'] === 0 || $company_data['user_loginstatus'] === '0'){
                
                $full_link = $sitee.$link;
                $_n_case_custom = $case.' '.$type;
                $_n_case_custom = ucwords($_n_case_custom);
                
                $sent = _sendNotifMail($full_link, $datetime, $notification_message, $company_data['u_mail'], $_n_case_custom, $type, $sitee);
                if($sent['sent'] === 'true'){
                    $notified = 'true';
                }else{
                    $notified = 'false - mail, error: '.$sent['error'];
                }
            }else{
                $notified = 'true';
            }
            
        }else{
            $notified = 'false';
        }
        
        return $notified;
    }

    function createNotificationAdmin($company_id, $notification_message, $datetime, $type, $conn){
        $query_details = "INSERT INTO notification_admin (message, datetime, company_id, status, type) VALUES ('$notification_message', '$datetime', '$company_id', 'unread', '$type')";
        $query_completed = $conn->query($query_details);
        if ($query_completed) {
            $notified = 'true';
        }else{
            $notified = 'false';
        }
        
        return $notified;
    }
    
    function daysAgo ($oldTime, $newTime) {
	$timeCalc = strtotime($newTime) - strtotime($oldTime);
    $left = '';
	if ($timeCalc >= (60*60*24*2)){
        $left = 'days';
		$timeCalc = intval($timeCalc/60/60/24);
	}else if ($timeCalc >= (60*60*2)){
        $left = 'hours';
		$timeCalc = intval($timeCalc/60/60);
	}else if ($timeCalc >= (60*60)){
        $left = 'hour';
		$timeCalc = intval($timeCalc/60/60);
	}else if ($timeCalc >= 60*2){
        $left = 'minutes';
		$timeCalc = intval($timeCalc/60);
	}else if ($timeCalc >= 60){
        $left = 'minute';
		$timeCalc = intval($timeCalc/60);
	}else if ($timeCalc > 0){
        $left = 'seconds';
		$timeCalc .= " seconds";
	}
    $timeStamp = array(
        'timeCalc' => $timeCalc,
        'left' => $left
    );

	return $timeStamp;
}

    function _getSelected($selected, $query){
	    if($selected == $query){
	        return 'selected';
	    }else{
	        return '';
	    }
	}

    function _listFrequencies($selected = null){
	    $response = '
	        <option value="1" '._getSelected($selected, 1).'>Daily Applications</option>
            <option value="2" '._getSelected($selected, 2).'>Weekly Applications</option>
            <option value="4" '._getSelected($selected, 4).'>Monthly Applications</option>
            <option value="5" '._getSelected($selected, 5).'>Quaterly Applications</option>
            <option value="8" '._getSelected($selected, 8).'>Half Yearly Applications</option>
            <option value="6" '._getSelected($selected, 6).'>Annually Applications</option>
            <option value="7" '._getSelected($selected, 7).'>As Required</option>
	    ';
	    
	    return $response;
	}
	
	function _getFrequencyTitle($freq){
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
	
	function _getEffectivenessTitle($effective){
	    $effect = strtolower($effective);
	    if ($effect === 'effective') {
			return "Effective";
		} else if ($effect === 'ineffective') {
			return "InEffective";
		} else {
			return "UnAssessed";
		}
	}
	
	function _listEffectiveness($selected = null){
	    
	    if($selected == null){
    	    $response = '
    	        <option value="Effective">Effective</option>
                <option value="InEffective">InEffective</option>
                <option value="UnAssessed">UnAssessed</option> 
    	    ';
	    }else{
	        $response = '
    	        <option value="Effective" '._getSelected(strtolower($selected), strtolower('Effective')).'>Effective</option>
                <option value="InEffective" '._getSelected(strtolower($selected), strtolower('InEffective')).'>InEffective</option>
                <option value="UnAssessed" '._getSelected(strtolower($selected), strtolower('UnAssessed')).'>UnAssessed</option> 
    	    ';
	    }
	    
	    return $response;
	}
	
	function _getStatus($effective){
	    $effect = strtolower($effective);
	    if ($effect === 'high') {
			return "High";
		} else if ($effect === 'low') {
			return "Low";
		} else {
			return "Medium";
		}
	}
	
	function _listStatus($selected = null){
	    
	    if($selected == null){
    	    $response = '
    	        <option value="High">High</option>
                <option value="Low">Low</option>
                <option value="Medium" selected>Medium</option> 
    	    ';
	    }else{
	        $response = '
    	        <option value="High" '._getSelected(strtolower($selected), strtolower('High')).'>High</option>
                <option value="Low" '._getSelected(strtolower($selected), strtolower('Low')).'>Low</option>
                <option value="Medium" '._getSelected(strtolower($selected), strtolower('Medium')).'>Medium</option> 
    	    ';
	    }
	    
	    return $response;
	}
	
	function _getTrend($effective){
	    $effect = strtolower($effective);
	    if ($effect === 'increasing') {
			return "Increasing";
		} else if ($effect === 'decreasing') {
			return "Decreasing";
		} else {
			return "Stable";
		}
	}
	
	function _listTrend($selected = null){
	    
	    if($selected == null){
    	    $response = '
    	        <option value="Increasing">Increasing</option>
                <option value="Decreasing">Decreasing</option>
                <option value="Stable" selected>Stable</option> 
    	    ';
	    }else{
	        $response = '
    	        <option value="Increasing" '._getSelected(strtolower($selected), strtolower('Increasing')).'>Increasing</option>
                <option value="Decreasing" '._getSelected(strtolower($selected), strtolower('Decreasing')).'>Decreasing</option>
                <option value="Stable" '._getSelected(strtolower($selected), strtolower('Stable')).'>Stable</option> 
    	    ';
	    }
	    
	    return $response;
	}
	
    function _getPriority($effective){
	    $effect = strtolower($effective);
	    if ($effect === 'lowest') {
			return "Lowest";
		} else if ($effect === 'high') {
			return "High";
		} else if ($effect === 'critical') {
			return "Critical";
		} else if ($effect === 'medium') {
			return "Medium";
		}else{
			return "Low";
		}
	}
	
	function _listPriority($selected = null){
	    
	    if($selected == null){
    	    $response = '
    	        <option value="Lowest">Lowest</option>
                <option value="Low" selected>Low</option>
                <option value="High">High</option>
                <option value="Medium">Medium</option>
                <option value="Critical">Critical</option>
    	    ';
	    }else{
	        $response = '
    	        <option value="Lowest" '._getSelected(strtolower($selected), strtolower('Lowest')).'>Lowest</option>
                <option value="Low" '._getSelected(strtolower($selected), strtolower('Low')).'>Low</option>
                <option value="High" '._getSelected(strtolower($selected), strtolower('High')).'>High</option> 
                <option value="Medium" '._getSelected(strtolower($selected), strtolower('Medium')).'>Medium</option>
                <option value="Critical" '._getSelected(strtolower($selected), strtolower('Critical')).'>Critical</option> 
    	    ';
	    }
	    
	    return $response;
	}	

?>