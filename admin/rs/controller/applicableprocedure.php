<?php

include_once("../controller/auth.php");
include_once("../config.php");
include_once("../model/db.php");
include_once("../model/applicableProcedure.php");

$apply = new applicableProcedure();

// ADD Applicable
if (isset($_REQUEST["action"]) && $_REQUEST["action"] == "add") {

  $procedureTitle = $_REQUEST["procedureTitle"];
  $procedureNumber = $_REQUEST["procedureNumber"];
  $procedureDescription = $_REQUEST["procedureDescription"];
  $procedureEffectiveDate = $_REQUEST["procedureEffectiveDate"];
  $procedureReviewDate = $_REQUEST["procedureReviewDate"];
  $applicability = $_REQUEST["applicability"];
  $ComplianceRequirements = $_REQUEST["ComplianceRequirements"];
  $resources = $_REQUEST["resources"];
  $procedureApproval = $_REQUEST["procedureApproval"];
  $procedureReview = $_REQUEST["procedureReview"];
  $procedureAcknowledgment = isset($_REQUEST["procedureAcknowledgment"]) ? 1 : 0;


  $result = $apply->addApplicableProcedure(
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
  );

  if ($result) {
    header("Location: ../view/applicableprocedures.php?id=" . $result);
  } else {
    header("Location: ../view/applicableprocedures.php?response=err&action=add");
  }
}


// LIST APPLICABLES
if (isset($_REQUEST["action"]) && $_REQUEST["action"] == "list") {
  $db = new db();
  $conn = $db->connect();
  $num = $db->rowCount($conn, "as_procedures", "procedure_user_id", $_SESSION["userid"]);

  $list = $apply->listApplicableProcedures($_REQUEST["start"], $_REQUEST["length"]);


  $fulldata = array();
  $data = array();

  $fulldata["draw"] = $_REQUEST["draw"];
  $fulldata["recordsTotal"] = $num;
  $fulldata["recordsFiltered"] = $num;

  foreach ($list as $item) {
    $response = array();
    $response["nr"] = $item["id"]; // Change the field name according to your database schema
    $response["procedureTitle"] = $item["ProcedureTitle"];
    $response["procedureNumber"] = $item["ProcedureNumber"];
    $response["procedureDescription"] = $item["ProcedureDescription"];
    $response["procedureEffectiveDate"] = $item["ProcedureEffectiveDate"];
    $response["procedureReviewDate"] = $item["ProcedureReviewDate"];
    $response["link"] = '<div style="display: flex; flex-direction: row;gap: 4px;">
    <a class="btn btn-xs btn-info" title="View" href="applicableprocedure-details.php?id=' . $item["id"] . '"><i class="glyphicon glyphicon-eye-open"></i></a>
		<a title="Edit" class="btn btn-xs btn-primary" href="applicableprocedure.php?action=edit&id=' . $item["id"] . '"><i class="glyphicon glyphicon-pencil"></i></a>
		<a title="Delete" class="btn btn-xs btn-danger" href="javascript:del(\'' . $item["id"] . '\');"><i class="glyphicon glyphicon-trash"></i></a>
    <a target="_blank" title="Download XLS" class="btn btn-warning btn-xs" href="applicableprocedures.php?action=downloadxls&id=' . $item["id"] . '"><i class="glyphicon glyphicon-download"></i></a>
    </div>';
    $data[] = array_values($response);
  }

  $fulldata["data"] = $data;

  echo json_encode($fulldata);
}

// EDIT APPLICABLES
if (isset($_REQUEST["action"]) && $_REQUEST["action"] == "edit") {
  $id = $_REQUEST["id"];
  $procedureTitle = $_REQUEST["procedureTitle"];
  $procedureNumber = $_REQUEST["procedureNumber"];
  $procedureDescription = $_REQUEST["procedureDescription"];
  $procedureEffectiveDate = $_REQUEST["procedureEffectiveDate"];
  $procedureReviewDate = $_REQUEST["procedureReviewDate"];
  $applicability = $_REQUEST["applicability"];
  $ComplianceRequirements = $_REQUEST["ComplianceRequirements"];
  $resources = $_REQUEST["resources"];
  $procedureApproval = $_REQUEST["procedureApproval"];
  $procedureReview = $_REQUEST["procedureReview"];
  $procedureAcknowledgment = isset($_REQUEST["procedureAcknowledgment"]) ? 1 : 0;

  $result = $apply->editApplicableProcedure(
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
  );

  if ($result) {
    header("Location: ../view/applicableprocedures.php");
  } else {
    header("Location: ../view/applicableprocedure.php?response=err&action=edit");
  }
}

//Delete Applicable
if (isset($_REQUEST["action"]) && $_REQUEST["action"] == "delete") {

  echo $apply->deleteApplicableProcedure($_REQUEST["id"]);

}