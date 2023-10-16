<?php

include_once("../controller/auth.php");
include_once("../config.php");
include_once('../model/audit.php');
include_once('../model/assessment.php');

if (isset($_REQUEST["response"]) and $_REQUEST["response"]=="true") {
	if ($_REQUEST["action"]=="editcriteria") $msg="Criteria updated sucessfully.";
	if ($_REQUEST["action"]=="addcriteria") $msg="Criteria added sucessfully.";
}

if (isset($_REQUEST["response"]) and $_REQUEST["response"]=="err") {
	$msg="An error occured, please try again.";
}

$audit=new audit();

if ($_REQUEST["action"]=="editcriteria") {
	$edit=true;
	$id=$_REQUEST["id"];	
	$datainfo=$audit->getCriteria($_REQUEST["id"]);
 

} else {
	$id=-1;
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
    <h1 class="page-header">Test Criteria</h1>
  <div class="col-lg-9 col-md-12">
    <div class="panel panel-default">
      <div class="panel-body">
        <form role="form" id="form" action="../controller/audit.php" method="post"
      <div class="alert alert-info" id="notify" <?php if (!isset($msg)) { ?>style="display: none;"<?php } ?>>
            <?php if (isset($msg)) echo $msg;?>
          </div>

                   
         <div class="form-group">
            <label>Test Question</label>
            <input name="question" type="text" maxlength="255" class="form-control" placeholder="Enter question..." required value="<?php if ($edit) echo $datainfo["cri_question"];?>">
          
          </div>

          <div class="form-group">
            <label>Test Procedure</label>
            <textarea rows="4" name="procedure" maxlength="255" class="form-control" placeholder="Enter test procedure..." required><?php if ($edit) echo $datainfo["cri_procedure"];?></textarea>

          
            
          </div>
        
                 <div class="form-group">
            <label>Expected Outcome</label>
            <input name="expected" type="text" maxlength="255" class="form-control" placeholder="Enter expected outcome..." required value="<?php if ($edit && isset($datainfo["cri_expected"])) echo $datainfo["cri_expected"];?>">
          
          </div>
          
          <div class="form-group">
            <label>Outcome</label>
<select name="outcome" class="form-control">
<option value="0" <?php if ($edit and $datainfo["cri_outcome"]=="0") echo ' selected';?>>N/A</option>
<option value="1" <?php if ($edit and $datainfo["cri_outcome"]=="1") echo ' selected';?>>Pass</option>
<option value="2" <?php if ($edit and $datainfo["cri_outcome"]=="2") echo ' selected';?>>Fail</option>
</select>
 </div>
          <div class="form-group">
            <label>Notes</label>
            <textarea rows="4" name="notes" class="form-control"><?php if ($edit && isset($datainfo["cri_notes"])) echo $datainfo["cri_notes"];?></textarea>

          
          </div>

          <div class="form-group">
          	  <button type="submit" class="btn btn-md btn-info" id="btn_save">Save</button>
          	  <button type="button" class="btn btn-md btn-warning" id="btn_cancel">Cancel</button>          
	          <input name="action" type="hidden" value="<?php echo $_REQUEST["action"];?>" />
	          <input name="id" type="hidden" value="<?php if ($edit) echo $id;?>" />
	          <input name="control" type="hidden" value="<?php echo $_REQUEST["control"];?>" />
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

	
	$("#btn_cancel").click(function(e) {
        $(location).attr("href","auditcriteria.php?id=<?php echo $_REQUEST["control"]?>");
    });

});



</script>

</body>
</html>