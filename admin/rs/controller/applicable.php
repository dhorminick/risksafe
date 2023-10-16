<?php

include_once("../controller/auth.php");
include_once("../config.php");
include_once("../model/db.php");
include_once("../model/applicable.php");

$apply = new applicable();

// ADD Applicable
if (isset($_REQUEST["action"]) && $_REQUEST["action"] == "add") {

	$policyTitle = $_REQUEST["policyTitle"];
	$policyNumber = $_REQUEST["policyNumber"];
	$policyDescription = $_REQUEST["policyDescription"];
	$policyEffectiveDate = $_REQUEST["policyEffectiveDate"];
	$policyReviewDate = $_REQUEST["policyReviewDate"];
	$applicability = $_REQUEST["applicability"];
	$policyRequirements = $_REQUEST["policyRequirements"];
	$complianceResponsibility = $_REQUEST["complianceResponsibility"];
	$relatedDocuments = $_REQUEST["relatedDocuments"];
	$policyApproval = $_REQUEST["policyApproval"];
	$policyReviewRevisionHistory = $_REQUEST["policyReviewRevisionHistory"];
	$policyAcknowledgment = isset($_REQUEST["policyAcknowledgment"]) ? 1 : 0;


	$result = $apply->addApplicable(
		$policyTitle,
		$policyNumber,
		$policyDescription,
		$policyEffectiveDate,
		$policyReviewDate,
		$applicability,
		$policyRequirements,
		$complianceResponsibility,
		$relatedDocuments,
		$policyApproval,
		$policyReviewRevisionHistory,
		$policyAcknowledgment
	);

	if ($result) {
		header("Location: ../view/applicables.php?id=" . $result);
	} else {
		header("Location: ../view/applicables.php?response=err&action=add");
	}
}


// LIST APPLICABLES
if (isset($_REQUEST["action"]) && $_REQUEST["action"] == "list") {
	$db = new db();
	$conn = $db->connect();
	$num = $db->rowCount($conn, "policyfields", "policy_user_id", $_SESSION["userid"]);

	$list = $apply->listApplicable($_REQUEST["start"], $_REQUEST["length"]);


	$fulldata = array();
	$data = array();

	$fulldata["draw"] = $_REQUEST["draw"];
	$fulldata["recordsTotal"] = $num;
	$fulldata["recordsFiltered"] = $num;

	foreach ($list as $item) {
		$response = array();
		$response["nr"] = $item["id"]; // Change the field name according to your database schema
		$response["policyTitle"] = $item["PolicyTitle"];
		$response["policyNumber"] = $item["PolicyNumber"];
		$response["policyDescription"] = $item["PolicyDescription"];
		$response["policyEffectiveDate"] = $item["PolicyEffectiveDate"];
		$response["policyReviewDate"] = $item["PolicyReviewDate"];
		$response["link"] = '<div style="text-align: center;">
	  <a class="btn btn-xs btn-info" title="View" href="applicable-details.php?id=' . $item["id"] . '"><i class="glyphicon glyphicon-eye-open"></i></a>
		<a title="Edit" class="btn btn-xs btn-primary" href="applicable.php?action=edit&id=' . $item["id"] . '"><i class="glyphicon glyphicon-pencil"></i></a>
		<a title="Delete" class="btn btn-xs btn-danger" href="javascript:del(\'' . $item["id"] . '\');"><i class="glyphicon glyphicon-trash"></i></a>
		<a target="_blank" title="Download XLS" class="btn btn-warning btn-xs" href="applicables.php?action=downloadxls&id=' . $item["id"] . '"><i class="glyphicon glyphicon-download"></i></a>
        </div>';


		$data[] = array_values($response);
	}

	$fulldata["data"] = $data;

	echo json_encode($fulldata);
}

// EDIT APPLICABLES
if (isset($_REQUEST["action"]) && $_REQUEST["action"] == "edit") {
	$id = $_REQUEST["id"];
	$policyTitle = $_REQUEST["policyTitle"];
	$policyNumber = $_REQUEST["policyNumber"];
	$policyDescription = $_REQUEST["policyDescription"];
	$policyEffectiveDate = $_REQUEST["policyEffectiveDate"];
	$policyReviewDate = $_REQUEST["policyReviewDate"];
	$applicability = $_REQUEST["applicability"];
	$policyRequirements = $_REQUEST["policyRequirements"];
	$complianceResponsibility = $_REQUEST["complianceResponsibility"];
	$relatedDocuments = $_REQUEST["relatedDocuments"];
	$policyApproval = $_REQUEST["policyApproval"];
	$policyReviewRevisionHistory = $_REQUEST["policyReviewRevisionHistory"];
	$policyAcknowledgment = isset($_REQUEST["policyAcknowledgment"]) ? 1 : 0;

	$result = $apply->editApplicable(
		$id,
		$policyTitle,
		$policyNumber,
		$policyDescription,
		$policyEffectiveDate,
		$policyReviewDate,
		$applicability,
		$policyRequirements,
		$complianceResponsibility,
		$relatedDocuments,
		$policyApproval,
		$policyReviewRevisionHistory,
		$policyAcknowledgment
	);

	if ($result) {
		header("Location: ../view/applicables.php");
	} else {
		header("Location: ../view/applicable.php?response=err&action=edit");
	}
}

//Delete Applicable
if (isset($_REQUEST["action"]) && $_REQUEST["action"] == "delete") {

	echo $apply->deleteApplicable($_REQUEST["id"]);


}

if (isset($_REQUEST["action"]) && $_REQUEST["action"] == "policyreviewdate") {

	echo $apply->chronjobpolicyreviewdate();


}



