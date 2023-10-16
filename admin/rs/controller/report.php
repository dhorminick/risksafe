<?php
	
	include_once("../controller/auth.php");
	include_once("../config.php");
	include_once("../model/db.php");
	include_once("../model/report.php");
	
	$report=new report();
	
	//LIST OF REPORTS
	if (isset($_REQUEST["action"]) && $_REQUEST["action"]=="list") {
	
		$db=new db;
		$conn=$db->connect();
		$num=$db->rowCount($conn, "as_assessment", "as_user", $_SESSION["userid"]);
		
		$list=$report->listReports($_REQUEST["start"],$_REQUEST["length"]);
		
		$fulldata=array();
		$data=array();
			
		$fulldata["draw"]=$_REQUEST["draw"];
		$fulldata["recordsTotal"]=$num;
		$fulldata["recordsFiltered"]=$num;
	
		foreach ($list as $item) {
			$response=array();
			$response["number"]=$item["as_number"];
			$response["team"]=$item["as_team"];
			$response["task"]=$item["as_task"];
			$response["date"]=date("m/d/Y", strtotime($item["as_date"]));
			$response["link"] = '<div style="text-align: center;"><a href="report.php?id=' . $item["idassessment"] . '"><button title="View risk assessment report" type="button" class="btn btn-xs btn-info">View report</button></a></div>';
			$data[] = array_values($response);
		}
		
		$fulldata["data"]=$data;
		
		echo json_encode($fulldata);
	
	}

?>