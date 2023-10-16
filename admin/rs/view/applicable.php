<?php

include_once("../controller/auth.php");
include_once("../config.php");
include_once('../model/applicable.php');

if (isset($_REQUEST["response"]) and $_REQUEST["response"]=="true") {
	$msg="applicable saved successfully.";
}

if (isset($_REQUEST["response"]) and $_REQUEST["response"]=="err") {
	$msg="Error saving applicable, please try again.";
}

$apply=new applicable();
if ($_REQUEST["action"]=="edit") {
	$edit=true;
	$info=$apply->getApplicable($_REQUEST["id"]);
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
		echo 'Edit Applicable Policy
    ';
	} else {
		echo 'New Applicable Policy
    ';	
	}
	?>
    </h1>
  <div class="col-lg-9 col-md-12">
    <div class="panel panel-default">
      <div class="panel-body">
      <form role="form" id="policyForm" action="../controller/applicable.php" method="POST">
      <div class="alert alert-info" id="notify" <?php if (!isset($msg)) { ?>style="display: none;"<?php } ?>>
            <?php if (isset($msg)) echo $msg;?>
          </div>
  <div class="form-group">
    <label for="policyTitle">Policy Title</label>
    <input type="text" class="form-control" id="policyTitle" name="policyTitle" value="<?php if ($edit) echo $info['PolicyTitle'];?>" required>
  </div>
  <div class="form-group">
    <label for="policyNumber">Policy Number</label>
    <input type="text" class="form-control" id="policyNumber" name="policyNumber" value="<?php if ($edit) echo $info['PolicyNumber'];?>" required>
  </div>
  <div class="form-group">
    <label for="policyDescription">Policy Description</label>
    <textarea class="form-control" id="policyDescription" name="policyDescription" rows="4" value="" required><?php if ($edit) echo $info['PolicyDescription'];?></textarea>
  </div>
  <div class="form-group">
    <label for="policyEffectiveDate">Policy Effective Date</label>
    <input type="date" class="form-control" id="policyEffectiveDate" name="policyEffectiveDate" value="<?php if ($edit) echo $info['PolicyEffectiveDate'];?>" required>
  </div>
  <div class="form-group">
    <label for="policyReviewDate">Policy Review Date</label>
    <input type="date" class="form-control" id="policyReviewDate" name="policyReviewDate" value="<?php if ($edit) echo $info['PolicyReviewDate'];?>" required>
  </div>
  <div class="form-group">
    <label for="applicability">Applicability</label>
    <textarea class="form-control" id="applicability" name="applicability" rows="4" required><?php if ($edit) echo $info['Applicability'];?></textarea>
  </div>
  <div class="form-group">
    <label for="policyRequirements">Policy Requirements</label>
    <textarea class="form-control" id="policyRequirements" name="policyRequirements" rows="4" required><?php if ($edit) echo $info['PolicyRequirements'];?></textarea>
  </div>
  <div class="form-group">
    <label for="complianceResponsibility">Compliance Responsibility</label>
    <textarea class="form-control" id="complianceResponsibility" name="complianceResponsibility" rows="4" required><?php if ($edit) echo $info['ComplianceResponsibility'];?></textarea>
  </div>
  <div class="form-group">
    <label for="relatedDocuments">Related Documents</label>
    <textarea class="form-control" id="relatedDocuments" name="relatedDocuments" rows="4" required><?php if ($edit) echo $info['RelatedDocuments'];?></textarea>
  </div>
  <div class="form-group">
    <label for="policyApproval">Policy Approval</label>
    <input type="text" class="form-control" id="policyApproval" name="policyApproval" value="<?php if ($edit) echo $info['RelatedDocuments'];?>" required>
  </div>
  <div class="form-group">
    <label for="policyReviewRevisionHistory">Policy Review and Revision History</label>
    <textarea class="form-control" id="policyReviewRevisionHistory" name="policyReviewRevisionHistory" rows="4" value="" required><?php if ($edit) echo $info['PolicyReviewRevisionHistory'];?></textarea>
  </div>
  <div class="form-group">
    <label for="policyAcknowledgment">Policy Acknowledgment</label>
    <div class="form-check">
<input class="form-check-input" type="checkbox" id="policyAcknowledgment" name="policyAcknowledgment" <?php if ($edit && $info['PolicyAcknowledgment'] == '1') echo 'checked="checked"'; ?> required>
      <label class="form-check-label" for="policyAcknowledgment">I acknowledge the policy</label>
    </div>
  </div>
  <button type="submit" class="btn btn-md btn-info" id="btn_save">Save Applicable</button>
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
		$(location).attr("href","applicables.php");
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