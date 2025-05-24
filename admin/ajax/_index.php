<?php
# risks

// function calculateRating($sum) {
	
// 		switch ($sum) {
		
// 			case ($sum === 4):
// 				$response=1;
// 				break;
				
// 			case ($sum>4 and $sum<7):
// 				$response=2;
// 				break;
				
// 			case ($sum==7):
// 				$response=3;
// 				break;
				
// 			case ($sum>7):
// 				$response=4;
// 				break;
// 		}
		
// 		return $response;
//}

function __getIndustryTitle($id, $con){
        if($id == ''){
           $response = 'None Selected'; 
        }else{
            $query="SELECT * FROM updated_risk WHERE module = '$id'";
    		$result=$con->query($query);
    		if ($result->num_rows > 0) {	
    			$row=$result->fetch_assoc();
    			$response = $row['name'];
    		}else{
    			$response = 'Error!!';
    		}
        }
		return $response;
    }

function getTopRisks($c_id, $con){
    $veryHighCount = 0;
    $highCount = 0;
    $lowCount = 0;
    $mediumCount = 0;
    
    $query = "SELECT * FROM as_assessment_new WHERE c_id = '$c_id' ORDER BY likelihood ASC, consequence DESC LIMIT 5";
    $result = $con->query($query);
    if ($result->num_rows > 0) {
        $data = [];
        
        while($row = $result->fetch_assoc()){
            array_push($data, $row);
        }
        
        return $data;
    }else{
        return false;
    }
    
}

function getCustomRisksTitle($id, $con){
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

function getSiteRisksTitle($id, $con){
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

function _getRisks_New($id, $con){
		$query="SELECT * FROM updated_risk WHERE r_id = '$id'";
		$result=$con->query($query);
		if ($result->num_rows > 0) {	
			$row=$result->fetch_assoc();
			$response = $row['name'];
		}else{
			$response = 'Error!!';
		}
		return $response;
    }

function getRiskTitle($type, $id, $con){
    if($type && strtolower($type) === 'site'){
        return _getRisks_New($id, $con);
    }else if($type && strtolower($type) === 'custom'){
        return getCustomRisksTitle($id, $con);
    }else{
        return 'Error';
    }
}

function _getRating($num){
    if($num === 4 || $num === '4'){
        $response = '<span class="txt-red">Very High</span>';
    }else if($num === 3 || $num === '3'){
        $response = '<span class="txt-orange">High</span>';
    }else if($num === 2 || $num === '2'){
        $response = '<span class="txt-yellow">Medium</span>';
    }else if($num === 1 || $num === '1'){
        $response = '<span class="txt-green">Low</span>';
    }else{
        $response = '<span>Not Specified</span>';
    }
    
    return $response;
}

function _getIndustryTitle($id, $con){
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

function getRisksData($c_id, $con){
    $veryHighCount = 0;
    $highCount = 0;
    $lowCount = 0;
    $mediumCount = 0;
    
    $query = "SELECT * FROM as_assessment_new WHERE c_id = '$c_id'";
    $result = $con->query($query);
    if ($result->num_rows > 0) {

        while ($row=$result->fetch_assoc()) {
        	if($row['rating'] === 4 || $row['rating'] === '4'){
        	    $veryHighCount++;
        	}else if($row['rating'] === 3 || $row['rating'] === '3'){
        	    $highCount++;
        	}else if($row['rating'] === 2 || $row['rating'] === '2'){
        	    $lowCount++;
        	}else if($row['rating'] === 1 || $row['rating'] === '1'){
        	    $mediumCount++;
        	}
        }  
    }
    
    $sum = $veryHighCount + $highCount + $lowCount + $mediumCount;
    
    $riskTotal = array(
        'veryHigh' => $veryHighCount,
        'high' => $highCount,
        'low' => $lowCount,
        'medium' => $mediumCount,
        'sum' => $sum,
        'toprisks' => getTopRisks($c_id, $con)
    );
    
    return $riskTotal;
   
}

function calcPercentage($value, $total){
    if($total === null || $total === 'null' || $total <= 0){
        return 0;
    }
    
    return round(($value / $total) * 100, 2);
}

function countDataChart($company_id, $like, $consequence, $conn) {
		$query="SELECT * FROM as_assessment_new WHERE likelihood='$like' AND consequence='$consequence' AND c_id = '$company_id'";
		$response="";
		$result=$conn->query($query);
		if ($result->num_rows > 0) {
			$response=$result->num_rows;	 
		}else{
            $response = 0;
        }
		return $response;
	}
	
	function countDataLikelihood($company_id, $like, $conn) {
		$query="SELECT * FROM as_assessment_new WHERE likelihood = '$like' AND c_id = '$company_id'";
		$result=$conn->query($query);
		if ($result->num_rows > 0) {
			$response=$result->num_rows;	
		} else {
			$response=0;
		}
		return $response;
	}
	
	function countDataConsequence($company_id, $consequence, $conn) {
		$query="SELECT * FROM as_assessment_new WHERE consequence = '$consequence' AND c_id = '$company_id'";
		$result=$conn->query($query);
		if ($result->num_rows > 0) {
			$response=$result->num_rows;	
		} else {
			$response=0;
		}
		return $response;
	}
	
#audit
	
function getAuditData($c_id, $con){
    $unassessed = 0;
    $effective = 0;
    $ineffective = 0;
    
    $query = "SELECT * FROM as_auditcontrols WHERE c_id = '$c_id'";
    $result = $con->query($query);
    if ($result->num_rows > 0) {

        while ($row=$result->fetch_assoc()) {
        	if($row['con_effect'] === 0 || $row['con_effect'] === '0'){
        	    $unassessed++;
        	}else if($row['con_effect'] === 1 || $row['con_effect'] === '1'){
        	    $ineffective++;
        	}else if($row['con_effect'] === 2 || $row['con_effect'] === '2'){
        	    $effective++;
        	}
        }  
    }
    
    $sum = $ineffective + $effective + $unassessed;
    $chart = [$ineffective, $effective, $unassessed];
    
    $riskTotal = array(
        'unassessed' => $unassessed,
        'effective' => $effective,
        'ineffective' => $ineffective,
        'sum' => $sum,
        'chart' => $chart
    );
    
    return $riskTotal;
   
}

?>