<?php
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
    function as_type($id, $con){
        $query="SELECT * FROM as_types WHERE idtype = '$id'";
        $result=$con->query($query);
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $riskType = $row['ty_name'];
        }else{
            $riskType = 'Error!';
        }

        return $riskType;
    }
    
    function has_data($table, $var, $id, $con){
        $query="SELECT * FROM $table WHERE c_id= '$id'";
        $result=$con->query($query);
        if ($result->num_rows > 0) {
            $has_data = true;
        }else{
            $has_data = false;
        }

        return $has_data;
    }

    function get_date($date){
        return date("D\. jS \of F Y", strtotime($date));
    }

    function as_risk($id, $con){
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

    function as_hazard($id, $con){
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

    // function as_rating($likelihood, $consequence, $con){
    //     if ($likelihood <= 5 && $consequence <= 5) {
    //         $rating = calculateRating($likelihood, $consequence, $con);
    //         switch ($rating) {
    //             case 1:
    //                 $riskRating = 'Low';
    //                 break;
    //             case 2:
    //                 $riskRating = 'Medium';
    //                 break;
    //             case 3:
    //                 $riskRating = 'High';
    //                 break;
    //             case 4:
    //                 $riskRating = 'Extreme';
    //                 break;
    //         }
    //     } else {
	// 		$riskRating = 'error';
    //         // echo 'Please select Likelihood and Consequence...';
    //     }
	// 	return $riskRating;
    // }

    function as_rating($rating){
        switch ($rating) {
            case 1:
                $riskRating = 'Low';
                break;
            case 2:
                $riskRating = 'Medium';
                break;
            case 3:
                $riskRating = 'High';
                break;
            case 4:
                $riskRating = 'Extreme';
                break;
        }

		return $riskRating;
    }
    
    function con_treat_status($id){
        switch ($id) {
            case 1:
                $riskRating = 'Completed';
                break;
            case 2:
                $riskRating = 'In Progress';
                break;
            case 3:
                $riskRating = 'Not Started';
                break;
            default:
                $riskRating = 'Not Specified';
        }

		return $riskRating;
    }

    function as_action($id, $con){
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

    function list_out($data){
        $response = '';
        foreach ($data as $arr) {
            $response .= $arr.'\n';
        }

        return $response;
    }
    
    function get_custom_control($id, $con){
        
        $query="SELECT * FROM as_customcontrols WHERE control_id = '$id' LIMIT 1";
		$result=$con->query($query);
        if($result->num_rows > 0){
            $row=$result->fetch_assoc();
            $response = $row["title"];
        }else{
            $response = 'Error!';
        }

        return $response;
    }
    
    function get_recommended_control($id, $con){
        
        $query="SELECT * FROM as_controls WHERE id = '$id' LIMIT 1";
		$result=$con->query($query);
        if($result->num_rows > 0){
            $row=$result->fetch_assoc();
            $response = $row["control_name"];
        }else{
            $response = 'Error!';
        }

        return $response;
    }
    
    function get_custom_treatment($id, $con){
        
        $query="SELECT * FROM as_customtreatments WHERE treatment_id = '$id' LIMIT 1";
		$result=$con->query($query);
        if($result->num_rows > 0){
            $row=$result->fetch_assoc();
            $response = $row["title"];
        }else{
            $response = 'Error!';
        }

        return $response;
    }
    
    function list_out_control($c_id, $id, $con){
        
        $query="SELECT * FROM as_details WHERE c_id = '$c_id' AND ri_id = '$id' LIMIT 1";
		$result=$con->query($query);
        if($result->num_rows > 0){
            $row=$result->fetch_assoc();
            $rc = $row["recommended_control"];
            $sc = $row["saved_control"];
            $cc = $row["custom_control"];
            
            $response = '';
            
            if($rc !== 'null'){
                $response .= ucwords(get_recommended_control($rc, $con))." \r\n";
            }
            if($sc !== 'null'){
                $response .= ucwords(get_custom_control($sc, $con))." \r\n";
            }
            if($cc !== 'null'){
                $cc = unserialize($cc);
                foreach ($cc as $arr) {
                    #$response .= $arr.'\n';
                    $response .= ucwords($arr)." \r\n";
                }
            }
            $response .= '';
            
        }else{
            $response = 'Error!';
        }
		return $response;
    }
    
    function list_out_treat($c_id, $id, $con){
        
        $query="SELECT * FROM as_details WHERE c_id = '$c_id' AND ri_id = '$id' LIMIT 1";
		$result=$con->query($query);
        if($result->num_rows > 0){
            $row=$result->fetch_assoc();
            $sc = $row["saved_treatment"];
            $cc = $row["custom_treatment"];
            
            $response = '';
            
            if($sc !== 'null'){
                $response .= ucwords(get_custom_treatment($sc, $con))." \r\n";
            }
            if($cc !== 'null'){
                $cc = unserialize($cc);
                foreach ($cc as $arr) {
                    #$response .= $arr.'\n';
                    $response .= ucwords($arr)." \r\n";
                }
            }
            $response .= '';
            
        }else{
            $response = 'Error!';
        }
		return $response;
    }
    
    function con_freq($freq) {

		if ($freq == 5) {
			return "Semi-Annually Application";
		} else if ($freq == 1) {
			return "Daily Application";
		} else if ($freq == 2) {
			return "Weekly Application";
		} else if ($freq == 3) {
			return "Fort-Nightly Application";
		} else if ($freq == 4) {
			return "Monthly Application";
		}else if ($freq == 6) {
			return "Annually Application";
		}else if ($freq == 7) {
			return "Applied As Required";
		} else {
			return "Single Application";
		}
	}
	
	function con_eff($effe) {
		if ($effe == 1) {
			return "Effective";
		} else if ($effe == 2) {
			return "InEffective";
		} else if ($effe == 3) {
			return "Unassessed";
		} else{
		    return "Effective";
		}
	}
	
	function con_category($id, $con) {
	    
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
?>