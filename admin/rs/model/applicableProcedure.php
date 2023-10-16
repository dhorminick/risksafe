<?php

include_once("db.php");

class ApplicableProcedure {
  
  // Add applicable entry
  public function addApplicableProcedure($procedureTitle, $procedureNumber, $procedureDescription, $procedureEffectiveDate, $procedureReviewDate, $applicability, $ComplianceRequirements,  $resources, $procedureApproval, $procedureReview, $procedureAcknowledgment) {
    $db = new db();
    $conn = $db->connect();
    
    $userId = $_SESSION["userid"];
    $query = "INSERT INTO as_procedures (procedure_user_id, ProcedureTitle, ProcedureNumber, ProcedureDescription, ProcedureEffectiveDate, ProcedureReviewDate, Applicability, ComplianceRequirements, Resources,  ProcedureApproval, ProcedureReview, ProcedureAcknowledgment) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

    $stmt = $conn->prepare($query);
    $stmt->bind_param("isssssssssss",$userId,$procedureTitle,$procedureNumber,$procedureDescription,$procedureEffectiveDate,$procedureReviewDate,$applicability,$ComplianceRequirements,$resources,$procedureApproval,$procedureReview,$procedureAcknowledgment);

    if($stmt->execute()){
    $response = true;
    }else{
      $response = false;
    }
    $stmt->close();
    $db->disconnect($conn);
    return $response;
  }
  
  // List applicable entries
  public function listApplicableProcedures($start, $length) {
    $db = new db();
    $conn = $db->connect();
    $userId = $_SESSION["userid"];
    // Write your query to fetch the applicable entries from the database
    $query = "SELECT * FROM as_procedures WHERE procedure_user_id = '$userId' LIMIT $start, $length";
    $result = $conn->query($query);
    $data = [];
    while ($row = $result->fetch_assoc()) {
      $data[] = $row;
    }
    $db->disconnect($conn);
    return $data;
  }

  // EDIT APPLICABLE
public function editApplicableProcedure(
    $id,
    $procedureTitle,
    $procedureNumber,
    $procedureDescription,
    $procedureEffectiveDate,
    $procedureReviewDate,
    $applicability,
    $ComplianceRequirements,
    $resources,
    $procedureApproval,
    $procedureReview,
    $procedureAcknowledgment
  ) {
    $db = new db();
    $conn = $db->connect();
    
    // Write your query to update the applicable entry in the database
    $query = "UPDATE as_procedures SET ProcedureTitle = ?, ProcedureNumber = ?, ProcedureDescription = ?, ProcedureEffectiveDate = ?, ProcedureReviewDate = ?, Applicability = ?, ComplianceRequirements = ?,  Resources = ?, ProcedureApproval = ?, ProcedureReview = ?, ProcedureAcknowledgment = ? WHERE id = ?";

    $stmt = $conn->prepare($query);
    $stmt->bind_param("sssssssssssi",$procedureTitle,$procedureNumber,$procedureDescription,$procedureEffectiveDate,$procedureReviewDate,$applicability,$ComplianceRequirements,$resources,$procedureApproval,$procedureReview,$procedureAcknowledgment,$id);
    if($stmt->execute()){
      $response = true;
    }else{
      $reponse = false;
    }
    $stmt->close();
    $db->disconnect($conn);
    return $response;
  }
  
  
  //delete applicable
  public function deleteApplicableProcedure($id) {
	
    $db=new db();
    $conn=$db->connect();
    
    $query="DELETE FROM as_procedures WHERE id=".$id.";";
    
    if ($conn->multi_query($query)) {
        $response=true;
    } else {
        $response=false;	
    }
    
    $db->disconnect($conn);
    return $response;
    
}

//get apply
public function getApplicableProcedure($id) {
    $db = new db();
    $conn = $db->connect();

    $query = "SELECT * FROM as_procedures WHERE id = " . $id;

    if ($result = $conn->query($query)) {
        $row = $result->fetch_assoc();
        $response = $row;
    } else {
        $response = false;
    }

    $db->disconnect($conn);
    return $response;
}


}
?>
