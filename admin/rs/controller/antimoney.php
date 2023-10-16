<?php
	
	include_once("../controller/auth.php");
	include_once("../config.php");
	include_once("../model/db.php");
	include_once("../model/antimoney.php");
	
	$assess=new antimoney;
	
	//NEW ASSESSMENT
	if (isset($_REQUEST["action"]) && $_REQUEST["action"]=="add") {
		
	  if ($id = $assess->addAssessment($_SESSION["userid"], $_REQUEST["type"], $_REQUEST["team"], $_REQUEST["task"],$_REQUEST["description"],$_REQUEST["owner"], $_REQUEST["date"],$_REQUEST["assessor"], $_REQUEST["approval"])) {
		  header("Location: ../view/antimoneydetail.php?action=adddetail&assessmentId=".$id);
	  } else {
		  header("Location: ../view/newantimoney.php?response=err");
	  }
		
	}
	
	//EDIT ASSESMENT 
	if (isset($_REQUEST["action"]) && $_REQUEST["action"]=="edit") {
		
	  if ($assess->editAssessment($_REQUEST["id"], $_REQUEST["team"], $_REQUEST["task"], $_REQUEST["description"],$_REQUEST["owner"],$_REQUEST["date"],$_REQUEST["assessor"], $_REQUEST["approval"])) {
		  	if (isset($_REQUEST["return"]) and $_REQUEST["return"]=="details") {
			 	header("Location: ../view/editantimoney.php?id=".$_REQUEST["id"]);
	  		} else {
				header("Location: ../view/antimones.php");
	  		}
	  } else {
		  header("Location: ../view/editantimoney.php?response=err&id=".$_REQUEST["id"]);
	  }
		
	}
	
	//ADD ASSESMENT DERTAILS
	if (isset($_REQUEST["action"]) && $_REQUEST["action"]=="adddetail") {
		
	  if ($assess->addAsDetail($_REQUEST["assessmentId"], $_REQUEST["risk"], $_REQUEST["hazard"], $_REQUEST["descript"],$_REQUEST["likelihood"],$_REQUEST["consequence"],$_REQUEST["effectiveness"], $_REQUEST["actiontake"],$_REQUEST["date"], $_REQUEST["owner"])) {
		  header("Location: ../view/antimones.php?id=".$_REQUEST["assessmentId"]."&response=true");
	  } else {
		  header("Location: ../view/editantimoney.php?response=err&action=adddetail&assessmentId=".$_REQUEST["assessmentId"]);
	  }
		
	}
	
	//EDIT ASSESMENT DERTAILS
	if (isset($_REQUEST["action"]) && $_REQUEST["action"]=="editdetail") {
		
	  if ($assess->editAsDetail($_REQUEST["id"], $_REQUEST["risk"], $_REQUEST["hazard"], $_REQUEST["descript"],$_REQUEST["likelihood"],$_REQUEST["consequence"],$_REQUEST["effectiveness"], $_REQUEST["actiontake"],$_REQUEST["date"], $_REQUEST["owner"])) {
		  header("Location: ../view/antimones.php?id=".$_REQUEST["assessmentId"]."&response=true");
	  } else {
		  header("Location: ../view/editantimoney.php?response=err&action=editdetail&id=".$_REQUEST["id"]."&adddetail&assessmentId=".$_REQUEST["assessmentId"]);
	  }		
	}
	
	
	//LIST HAZARDS
	if (isset($_REQUEST["action"]) && $_REQUEST["action"]=="listhazards") {
		
	  	echo $assess->listHazards($_REQUEST["cat"],$_REQUEST["selected"]);
		
	}
	
	//LIST RISKS
	if (isset($_REQUEST["action"]) && $_REQUEST["action"]=="listrisks") {
		
	  echo $assess->listRisks($_REQUEST["type"],$_REQUEST["selected"]);
		
	}
	
	//LIST TREATMENTS
	if (isset($_REQUEST["action"]) && $_REQUEST["action"]=="listtreat") {
		
	  echo $assess->listTreatments($_REQUEST["id"]);
		
	}
	
	//LIST CONTROLS
	if (isset($_REQUEST["action"]) && $_REQUEST["action"]=="listcontrols") {
		
	  echo $assess->listControls($_REQUEST["id"]);
		
	}
	
	//add CONTROLS
	if (isset($_REQUEST["action"]) && $_REQUEST["action"]=="addcontrol") {
	  
	  if ($_REQUEST["id"]=="-1") {
		  $assess->addControl($_REQUEST["id"],$_REQUEST["descript"],$_SESSION["aml"],$_SESSION["aml"]);
	  } else {
		  $assess->addControl($_REQUEST["id"],$_REQUEST["descript"],-1,$_SESSION["aml"]);
	  }
	}
	
	//ADD CONTROLS FROM LIB
	if (isset($_REQUEST["action"]) && $_REQUEST["action"]=="addlibcontrol") {
	  
	  $descript=$assess->getControlName($_REQUEST["id"]);
	  
	  
	  if ($_REQUEST["det"]=="-1") {
		  $assess->addControl($_REQUEST["det"],$descript,$_SESSION["aml"],$_SESSION["aml"]);
	  } else {
		  $assess->addControl($_REQUEST["det"],$descript,-1,$_SESSION["aml"]);
	  }
	}
	
	//DELETE CONTROLS
	if (isset($_REQUEST["action"]) && $_REQUEST["action"]=="deletecontrol") {
		  $assess->deleteControl($_REQUEST["id"]);

	}
	
	//ADD TREATMENTS
	if (isset($_REQUEST["action"]) && $_REQUEST["action"]=="addtreat") {
		
		if ($_REQUEST["id"]=="-1") {
			$assess->addTreatment($_REQUEST["id"],$_REQUEST["descript"],$_SESSION["aml"],$_SESSION["aml"]);
		} else {
			$assess->addTreatment($_REQUEST["id"],$_REQUEST["descript"],-1,$_SESSION["aml"]);
		}
		
	}
	
	//ADD TREATMENT FROM LIB
	if (isset($_REQUEST["action"]) && $_REQUEST["action"]=="addlibtreat") {
	  
	  $descript=$assess->getTreatmentName($_REQUEST["id"]);

	  if ($_REQUEST["det"]=="-1") {
		  $assess->addTreatment($_REQUEST["det"],$descript,$_SESSION["aml"],$_SESSION["aml"]);
	  } else {
		  $assess->addTreatment($_REQUEST["det"],$descript,-1,$_SESSION["aml"]);
	  }
	}
	
	//DELETE TREATMENTS
	if (isset($_REQUEST["action"]) && $_REQUEST["action"]=="deletetreat") {
		  $assess->deleteTreatment($_REQUEST["id"]);

	}
	
	//DELETE DETAIL
	if (isset($_REQUEST["action"]) && $_REQUEST["action"]=="deletedetail") {
		
	   $assess->deleteDetail($_REQUEST["id"]);
		
	}
	
	//DELETE ASSESSMENT
	if (isset($_REQUEST["action"]) && $_REQUEST["action"]=="deleteassess") {
		
	   $assess->deleteAssessment($_REQUEST["id"]);
		
	}
	
	//CALCULATE RATING
	if (isset($_REQUEST["action"]) && $_REQUEST["action"]=="rating") {
		
	  if ($_REQUEST["likelihood"]<>"" and $_REQUEST["consequence"]) {
		  $rating=$assess->calculateRating($_REQUEST["likelihood"],$_REQUEST["consequence"]);
		  switch ($rating) {
				case 1:
					echo '<span class="rat_low">Low</span>';
					break;  
				case 2:
					echo '<span class="rat_medium">Medium</span>';
					break;  
				case 3:
					echo '<span class="rat_high">High</span>';
					break;
				case 4:
					echo '<span class="rat_extreme">Extreme</span>';
					break;   
		  }
	  } else {
		  echo 'Please select Likelihood and Consequence...';
	  }
	}
	
    //LIST OF ASSESSMENT DETAILS
	if (isset($_REQUEST["action"]) && $_REQUEST["action"]=="listdetails") {
	
		$db=new db;
		$conn=$db->connect();
		$num=$db->rowCount($conn, "aml_details", "as_assessment",$_REQUEST["id"]);
		$start = isset($_REQUEST["start"]) ? $_REQUEST["start"] : 0;
        $length = isset($_REQUEST["length"]) ? $_REQUEST["length"] : 10;
		$list=$assess->listAssessment($_REQUEST["id"], $start, $length);
		
		$fulldata=array();
		$data=array();
			
        $fulldata["draw"] = isset($_REQUEST["draw"]) ? $_REQUEST["draw"] : 1;
        $fulldata["recordsTotal"]=$num;
		$fulldata["recordsFiltered"]=$num;
	
		foreach ($list as $item) {
			$response=array();
			$response["risk"]=$item["aml_cat_name"];
			$response["hazard"]=$item["aml_sub_name"];
			$response["link"] = '<div style="text-align: center;">
			<a title="View Risk Details" class="btn btn-xs btn-info" href="antimoneydetail.php?action=editdetail&assessmentId='.$_REQUEST["id"].'&id=' . $item["iddetail"] . '"><i class=" glyphicon glyphicon-eye-open"></i></a>&nbsp;
			<a title="Delete Risk" class="btn btn-xs btn-danger" href="javascript:del(\'' . $item["iddetail"] . '\')"><i class=" glyphicon glyphicon-remove"></i></a></div>';
			$data[] = array_values($response);
		}
		
		$fulldata["data"]=$data;
		
		echo json_encode($fulldata);
	
	}
	
	//LIST OF ASSESSMENT s
	if (isset($_REQUEST["action"]) && $_REQUEST["action"] == "listassess") {
        $start = isset($_REQUEST["start"]) ? $_REQUEST["start"] : 0;
        $length = isset($_REQUEST["length"]) ? $_REQUEST["length"] : 10;
        $db = new db;
        $conn = $db->connect();
        $num = $db->rowCount($conn, "as_aml", "as_user", $_SESSION["userid"]);
    
        $list = $assess->listAssessments($start, $length);
        $fulldata = array();
        $data = array();
    
        $fulldata["draw"] = isset($_REQUEST["draw"]) ? $_REQUEST["draw"] : 1;
        $fulldata["recordsTotal"] = $num;
        $fulldata["recordsFiltered"] = $num;
    
        if ($list) { // Check if $list is not false
            foreach ($list as $item) {
                $response = array();
                $response["nr"] = $item["id"];
                $response["team"] = $item["as_team"];
                $response["task"] = $item["as_task"];
                $response["date"] = date("m/d/Y", strtotime($item["as_date"]));
                $response["link"] = '<div style="text-align: center;">
                    <a class="btn btn-xs btn-info" title="View" href="antimoney.php?id=' . $item["id"] . '"><i class="glyphicon glyphicon-eye-open"></i></a>
                    <a class="btn btn-xs btn-success" title="Edit" href="editantimoney.php?id=' . $item["id"] . '"><i class="glyphicon glyphicon-pencil"></i></a>
                    <a class="btn btn-xs btn-danger" title="Delete" href="javascript:del(\'' . $item["id"] . '\')"><i class="glyphicon glyphicon-remove"></i></a>
                    <a target="_blank" title="Download XLS" class="btn btn-warning btn-xs" href="antimones.php?action=downloadxls&id=' . $item["id"] . '"><i class="glyphicon glyphicon-download"></i></a></div>';
                $data[] = array_values($response);
                
            }
        }
    
        $fulldata["data"] = $data;
      
        echo json_encode($fulldata);
    }
    

?>