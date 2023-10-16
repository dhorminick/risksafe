<?php

include_once("../controller/auth.php");
include_once("../config.php");
include_once("../model/db.php");
include_once("../model/treatment.php");

$treat = new treatment();



//LIST OF TREATMENTS
if (isset($_REQUEST["action"]) && $_REQUEST["action"] == "list") {

	$db = new db;
	$conn = $db->connect();
	$num = $db->rowCount($conn, "as_treatments", "tre_user", $_SESSION["userid"]);
	$start = isset($_REQUEST["start"]) ? intval($_REQUEST["start"]) : 0;
    $length = isset($_REQUEST["length"]) ? intval($_REQUEST["length"]) : 10; // Default length if not provided
	$list = $treat->listTreatments($start, $length);

	$fulldata = array();
	$data = array();

	$fulldata["draw"] = $_REQUEST["draw"];
	$fulldata["recordsTotal"] = $num;
	$fulldata["recordsFiltered"] = $num;

	foreach ($list as $item) {

		$days = $treat->getDays($item["tre_start"], $item["tre_due"]);

		//STATUS
		switch ($item["tre_status"]) {
			case 1:
				$status = "In progress";
				break;
			case 2:
				$status = "Completed";
				break;
			case 3:
				$status = "Cancelled";
				break;
		}

		$response = array();
		$response["nr"] = $item["idtreatment"];
		$response["treatment"] = $item["tre_treatment"];
		$response["cost_benefits"] = $item["tre_cost_ben"];
		$response["progress"] = $item["tre_progress"];
		$response["owner"] = $item["tre_owner"];
		$response["start"] = date("m/d/Y", strtotime($item["tre_start"]));
		$response["due"] = date("m/d/Y", strtotime($item["tre_due"]));
		// $response["days"]=$days;
		$response["status"] = $status;
		$response["link"] = '<div style="display: flex;
			flex-direction: row;
			gap: 2%;">
			<a class="btn btn-xs btn-info" title="View" href="treatment-details.php?id=' . $item["idtreatment"] . '"><i class="glyphicon glyphicon-eye-open"></i></a>
			<a title="View Treatment Details" class="btn btn-xs btn-success" href="treatment.php?action=edit&id=' . $item["idtreatment"] . '"><i class=" glyphicon glyphicon-pencil"></i></a>
			<a title="Delete Treatment" class="btn btn-xs btn-danger" href="javascript:del(\'' . $item["idtreatment"] . '\');"><i class=" glyphicon glyphicon-remove"></i></a>
			<a target="_blank" title="Download XLS" class="btn btn-warning btn-xs" href="treatments.php?action=downloadxls&id=' . $item["idtreatment"] . '"><i class="glyphicon glyphicon-download"></i></a>
			</div>';
		$data[] = array_values($response);
	}

	$fulldata["data"] = $data;

	echo json_encode($fulldata);

}



//DELETE TREATMENT
if (isset($_REQUEST["action"]) && $_REQUEST["action"] == "delete") {

	echo $treat->deleteTreatment($_REQUEST["id"]);

}


//ADD TREATMENT
if (isset($_REQUEST["action"]) && $_REQUEST["action"] == "add") {

	if ($tr = $treat->addTreatment($_REQUEST["team"], $_REQUEST["assessor"], $_REQUEST["treatment"], $_REQUEST["existing"], $_REQUEST["cost_ben"], $_REQUEST["progress"], $_REQUEST["owner"], $_REQUEST["start"], $_REQUEST["due"], $_REQUEST["status"])) {
		header("Location: ../view/treatments.php?id=" . $tr);
	} else {
		header("Location: ../view/treatment.php?response=err&action=add");
	}

}

//EDIT TREATMENT
if (isset($_REQUEST["action"]) && $_REQUEST["action"] == "edit") {

	if ($treat->editTreatment($_REQUEST["id"], $_REQUEST["team"], $_REQUEST["assessor"], $_REQUEST["treatment"], $_REQUEST["existing"], $_REQUEST["cost_ben"], $_REQUEST["progress"], $_REQUEST["owner"], $_REQUEST["start"], $_REQUEST["due"], $_REQUEST["status"])) {
		header("Location: ../view/treatments.php");
	} else {
		header("Location: ../view/treatment.php?response=err&action=edit");
	}

}

//GET INFO ON TREATMENT
if (isset($_REQUEST["action"]) && $_REQUEST["action"] == "gettreatmentinfo") {

	echo $treat->getTreatmentInfo($_REQUEST["id"]);
}


if (isset($_REQUEST["action"]) && $_REQUEST["action"] == "treatmentmatchdate") {

	//	echo $treat->matchduedate();
		echo $treat->cronjobfunction();

}




?>