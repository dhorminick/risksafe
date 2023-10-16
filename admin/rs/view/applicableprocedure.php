<?php

include_once("../controller/auth.php");
include_once("../config.php");
include_once('../model/applicableProcedure.php');

if (isset($_REQUEST["response"]) and $_REQUEST["response"]=="true") {
	$msg="applicable saved successfully.";
}

if (isset($_REQUEST["response"]) and $_REQUEST["response"]=="err") {
	$msg="Error saving applicable, please try again.";
}

$apply=new ApplicableProcedure();
if ($_REQUEST["action"]=="edit") {
	$edit=true;
	$info=$apply->getApplicableProcedure($_REQUEST["id"]);
  //print_r($info);
 
} else {
	$edit=false;	
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<?php include_once("header.php");?>
</head>
<body>
<!-- header -->
<div id="top-nav" class="navbar navbar-inverse navbar-static-top" style="background-color:#09F;border:0;">
  <div class="container-fluid">
    <div class="navbar-header">
      <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse"> <span class="icon-bar"></span> <span class="icon-bar"></span> <span class="icon-bar"></span> </button>
      <a class="navbar-brand" href="main.php" style="font-weight:900;color:#fff;"><?php echo APP_TITLE;?></a> </div>
    <div class="navbar-collapse collapse">
      <ul class="nav navbar-nav navbar-right">
        <li class="dropdown"> <a class="dropdown-toggle" role="button" data-toggle="dropdown" href="#" style="font-weight:900;color:#fff;"><i class="glyphicon glyphicon-user"></i> Account <span class="caret"></span></a>
          <ul id="g-account-menu" class="dropdown-menu" role="menu">
			<?php include_once("menu_top.php");?>
          </ul>
        </li>
      </ul>
    </div>
  </div>
  <!-- /container --> 
</div>
<!-- /Header --> 

<!-- Main -->
<div class="container-fluid">
<div class="row">
  <div class="col-lg-3 col-sm-12"> 
    <!-- Left column --> 
	<?php include_once("menu.php");?>
  <!-- /col-3 -->
  </div>
  <div class="col-lg-9 col-md-12">
    <h1 class="page-header">
    <?php
	if ($edit) {
		echo 'Edit Applicable Procedure
    ';
	} else {
		echo 'New Applicable Procedure
    ';	
	}
	?>
    </h1>
  <div class="col-lg-9 col-md-12">
    <div class="panel panel-default">
      <div class="panel-body">
      <form role="form" id="policyForm" action="../controller/applicableprocedure.php" method="POST">
      <div class="alert alert-info" id="notify" <?php if (!isset($msg)) { ?>style="display: none;"<?php } ?>>
            <?php if (isset($msg)) echo $msg;?>
          </div>
  <div class="form-group">
    <label for="procedureTitle">Procedure Title</label>
    <input type="text" class="form-control" id="procedureTitle" name="procedureTitle" value="<?php if ($edit) echo $info['ProcedureTitle'];?>" required>
  </div>
  <div class="form-group">
    <label for="procedureNumber">Procedure Number</label>
    <input type="text" class="form-control" id="procedureNumber" name="procedureNumber" value="<?php if ($edit) echo $info['ProcedureNumber'];?>" required>
  </div>
  <div class="form-group">
    <label for="procedureDescription">Procedure Description</label>
    <textarea class="form-control" id="procedureDescription" name="procedureDescription" rows="4" value="" required><?php if ($edit) echo $info['ProcedureDescription'];?></textarea>
  </div>
  <div class="form-group">
    <label for="procedureEffectiveDate">Procedure Effective Date</label>
    <input type="date" class="form-control" id="procedureEffectiveDate" name="procedureEffectiveDate" value="<?php if ($edit) echo $info['ProcedureEffectiveDate'];?>" required>
  </div>
  <div class="form-group">
    <label for="procedureReviewDate">Procedure Review Date</label>
    <input type="date" class="form-control" id="procedureReviewDate" name="procedureReviewDate" value="<?php if ($edit) echo $info['ProcedureReviewDate'];?>" required>
  </div>
  <div class="form-group">
    <label for="applicability">Applicability</label>
    <textarea class="form-control" id="applicability" name="applicability" rows="4" required><?php if ($edit) echo $info['Applicability'];?></textarea>
  </div>
  <div class="form-group">
    <label for="ComplianceRequirements">Procedure Compliance Requirements</label>
    <textarea class="form-control" id="ComplianceRequirements" name="ComplianceRequirements" rows="4" required><?php if ($edit) echo $info['ComplianceRequirements'];?></textarea>
  </div>
   <div class="form-group">
    <label for="resources">Resources</label>
    <textarea class="form-control" id="resources" name="resources" rows="4" required><?php if ($edit) echo $info['Resources'];?></textarea>
  </div>
  <div class="form-group">
    <label for="procedureApproval">Procedure Approval</label>
    <input type="text" class="form-control" id="procedureApproval" name="procedureApproval" value="<?php if ($edit) echo $info['ProcedureApproval'];?>" required>
  </div>
  <div class="form-group">
    <label for="procedureReview">Procedure Review</label>
    <textarea class="form-control" id="procedureReview" name="procedureReview" rows="4" value="" required><?php if ($edit) echo $info['ProcedureReview'];?></textarea>
  </div>
  <div class="form-group">
    <label for="procedureAcknowledgment">Procedure Acknowledgment</label>
    <div class="form-check">
<input class="form-check-input" type="checkbox" id="procedureAcknowledgment" name="procedureAcknowledgment" <?php if ($edit && $info['ProcedureAcknowledgment'] == '1') echo 'checked="checked"'; ?> required>
      <label class="form-check-label" for="procedureAcknowledgment">I acknowledge the policy</label>
    </div>
  </div>
  <button type="submit" class="btn btn-md btn-info" id="btn_save">Save Applicable Procedure</button>
          		<button type="button" class="btn btn-md btn-warning" id="btn_cancel">Cancel</button>
          		<input name="action" type="hidden" value="<?php echo $_REQUEST["action"];?>"  />
          		<input name="id" type="hidden" value="<?php if ($edit) echo $info["id"];?>"  />
</form>

      </div>
    </div>
  </div>
  </div>
  <!--/col-span-9--> 
</div>  
</div>

<!-- /Main -->

<?php include_once("footer.php");?>
<script>
$(document).ready(function(e) {
 
	$(function() {
		$("#start, #due").datepicker();
	});
	
	
	$("#btn_cancel").click(function(e) {
		$(location).attr("href","applicableprocedures.php");
    });
	
	$("#existing").change(function(e){
		
    	if ($("#existing").val()=="-1") {
			$("#treatment").val('');
			$("#team").val('');
			$("#assessor").val('');
		} else {
			
			$.ajax({
			  method: "POST",
			  url: "../controller/treatment.php?action=gettreatmentinfo&id="+$("#existing").val(),
			  async: false
			})
			  .done(function( msg ) {
				arr=JSON.parse(msg);
				$("#treatment").val(arr.treatment);
				$("#team").val(arr.team);
				$("#assessor").val(arr.assessor)
			  });
		}
    });
    
}); 
  </script>
</script>
</body>
</html>