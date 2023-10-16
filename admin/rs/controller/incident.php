<?php
	
	include_once("../controller/auth.php");
	include_once("../config.php");
	include_once("../model/db.php");
	include_once("../model/incidents.php");
	
	$incidents =new incidents();
	
	//LIST 
	
	if (isset($_REQUEST["action"]) && $_REQUEST["action"]=="list") {
	
		$db=new db;
		$conn=$db->connect();
		$num=$db->rowCount($conn, "as_incidents", "in_user", $_SESSION["userid"]);		
		$list=$incidents->listIncidents($_REQUEST["start"],$_REQUEST["length"]);		
		$fulldata=array();
		$data=array();
			
		$fulldata["draw"]=$_REQUEST["draw"];
		$fulldata["recordsTotal"]=$num;
		$fulldata["recordsFiltered"]=$num;
	
		foreach ($list as $item) {
			
			$response=array();
			$response["nr"]=$item["idincident"];
			$response["title"]=$item["in_title"];
			$response["team"]=$item["in_team"];
			$response["Status"]=$item["in_status"];
			$response["Priority"]=$item["in_priority"];
			$response["date"]=date("m/d/Y", strtotime($item["in_date"]));			
			$response["link"] = '<div style="text-align: center;">			
			<a class="btn btn-xs btn-success" href="incident.php?action=edit&id=' . $item["idincident"] . '"><i class=" glyphicon glyphicon-pencil"></i></a>&nbsp;
			<a class="btn btn-xs btn-danger" href="javascript:del(\'' . $item["idincident"] . '\');"><i class=" glyphicon glyphicon-remove"></i></a></div>';
			$data[] = array_values($response);
		}
		
		$fulldata["data"]=$data;
		
		echo json_encode($fulldata);
	
	}

	//DELETE
	if (isset($_REQUEST["action"]) && $_REQUEST["action"]=="delete") {		
		echo $incidents->deleteIncident($_REQUEST["id"]);		
	}
	
	//ADD
	// $title, $date, $reported, $team, $financial, $injuries,  $complaints,$compliance, $descript, $impact, $priority, $status
	if (isset($_REQUEST["action"]) && $_REQUEST["action"]=="add") {	
		$message='New Incident created Successfully';
	  if ($au=$incidents->addIncident($_REQUEST["title"],$_REQUEST["date"],$_REQUEST["reported"],$_REQUEST["team"],$_REQUEST["financial"],$_REQUEST["injuries"],$_REQUEST["complaints"],$_REQUEST["compliance"],$_REQUEST["descript"],$_REQUEST["impact"],$_REQUEST["priority"],$_REQUEST["status"])) {
		$notificationResult = $incidents->savenotification($_SESSION["userid"], $message, 0, 0,$au);
		if ($notificationResult) { 
		header("Location: ../view/incidents.php?response=success");
	 } } else {
		  header("Location: ../view/incident.php?response=err&action=add");
	  }
		
	}
	
	//EDIT AUDIT
	if (isset($_REQUEST["action"]) && $_REQUEST["action"]=="edit") {
		
	  if ($incidents->editIncident($_REQUEST["id"],$_REQUEST["title"],$_REQUEST["date"],$_REQUEST["reported"],$_REQUEST["team"],$_REQUEST["financial"],$_REQUEST["injuries"],$_REQUEST["complaints"],$_REQUEST["compliance"],$_REQUEST["descript"],$_REQUEST["impact"],$_REQUEST["priority"],$_REQUEST["status"])) {
	  	header("Location: ../view/incidents.php?response=success");	  				
	  } else {
		 header("Location: ../view/incident.php?response=err&action=add&id=".$_REQUEST["id"]);
	  }		
	}

?>