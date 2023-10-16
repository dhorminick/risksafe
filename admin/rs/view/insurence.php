<?php

include_once("../controller/auth.php");
include_once("../config.php");
include_once('../model/insurence.php');


if (isset($_REQUEST["response"]) and $_REQUEST["response"]=="err") {
	$msg = "Error saving Insurance, please try again.";
}

$insurence = new insurence();
if ($_REQUEST["action"]=="edit") {
	$edit=true;
	$info=$insurence->getInsurance($_REQUEST["id"]);
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
		echo 'Edit Insurance ';
	} else {
		echo 'New Insurance';	
	}
	?>
    </h1>
    
  <div class="">
  	
  	<div class="alert alert-danger" id="notify" <?php if (!isset($msg)) { ?>style="display: none;"<?php } ?>>
    	<?php if (isset($msg)) echo $msg;?>
  	</div>
  	
    <div class="panel panel-default">
      <div class="panel-body">
        	<form role="form" id="form" action="../controller/insurence.php">
		      		
		          	
		          
		           <div class="form-group">
		            <label>Insurance Type</label>
		            <input value="<?php if ($edit) echo $info["is_type"];?>"  name="type" type="text" id="control" maxlength="255" class="form-control" placeholder="Enter insurance type..." required>
		        
		          </div>
	
		         <div class="form-group">
		            <label>Policy Coverage</label>
		            <textarea rows="3" class="form-control" placeholder="Enter policy coverage..." class="form-control" name="coverage"><?php if ($edit) echo $info["is_coverage"];?></textarea>		            		        
		          </div>
		                   <div class="form-group">
		            <label>Policy Exclusions</label>
		            <textarea rows="3" class="form-control" placeholder="Enter policy exclusions..." class="form-control" name="exclusions"><?php if ($edit) echo $info["is_exclusions"];?></textarea>		            		        
		          </div>
		                   <div class="form-group">
		            <label>Insurance Company and Contact</label>
		            <textarea rows="3" class="form-control" placeholder="Enter insurance company and contact details..." class="form-control" name="company"><?php if ($edit) echo $info["is_company"];?></textarea>		            		        
		          </div>
		                   <div class="form-group">
		            <label>Last Review Date</label>
		            <input name="date" type="text" maxlength="20" class="form-control readonly datepicker" placeholder="Select last review date..." required readonly style="cursor:pointer;" value="<?php if ($edit) { echo date("m/d/Y", strtotime($info["is_date"])); } else { echo date("m/d/Y");}?>">
		        
		          </div>
		            <div class="form-group">
		            <label>Details of Claims</label>
		            <textarea rows="3" class="form-control" placeholder="Enter details of claims..." class="form-control" name="details"><?php if ($edit) echo $info["is_details"];?></textarea>		            		        
		          </div>
		                   <div class="form-group">
		            <label>Follow-up Actions</label>
		            <textarea rows="3" class="form-control" placeholder="Enter follow-up actions..." class="form-control" name="actions"><?php if ($edit) echo $info["is_actions"];?></textarea>		        		       
		          </div>
		              
                	<div class="form-group">
                		<button type="submit" class="btn btn-md btn-info" id="btn_save">Save Insurance</button>
			          <button type="button" class="btn btn-md btn-warning" id="btn_cancel">Cancel</button>			          
			          <input name="action" type="hidden" value="<?php echo $_REQUEST["action"];?>"  />
			          <input name="id" type="hidden" value="<?php if ($edit) echo $info["idinsurance"];?>"  />
		          </div>
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
		$( ".datepicker" ).datepicker();
		
		$("#btn_cancel").click(function(){
	 		$(location).attr("href","../view/insurences.php");
	 	});
	});    
}); 
  </script>
</script>
</body>
</html>