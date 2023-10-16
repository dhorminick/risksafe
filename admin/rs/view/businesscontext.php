<?php

include_once("../controller/auth.php");
include_once("../config.php");
include_once('../model/users.php');

$user=new users;
// echo($_SESSION["userid"]);
// exit;
 $datainfo=$user->getContext($_SESSION["userid"]);
// print_r("hwllo");


if (isset($_REQUEST["response"]) and $_REQUEST["response"]=="true") {
	$msg="Busines context information updated successfully.";
	$msgClass="alert-success";
}

if (isset($_REQUEST["response"]) and $_REQUEST["response"]=="err") {
	$msg="Error updating business context information.";
	$msgClass="alert-danger";
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
    <h1 class="page-header">Business Context</h1>
    
  <div class="">
  	
  			<div class="alert <?php if (isset($msgClass)) echo $msgClass;?>" id="notify" <?php if (!isset($msg)) { ?>style="display: none;"<?php } ?>>
            	<?php if (isset($msg)) echo $msg;?>
          	</div>
  	
    <div class="panel panel-default">
      <div class="panel-body">
        <form role="form" id="form" action="../controller/users.php">
      		
          <div class="form-group">
            <label>Business Strategies and Objectives</label>
            <textarea name="objectives" class="form-control" placeholder="Enter business strategies and objectives..."><?php echo isset($datainfo["cx_objectives"]) ? $datainfo["cx_objectives"] : ''; ?></textarea>
          </div>
          <div class="form-group">
            <label>Critical Business Processes</label>
            <textarea name="processes" class="form-control" placeholder="Enter critical processes..."><?php echo isset($datainfo["cx_processes"]) ? $datainfo["cx_processes"]:'';?></textarea>
          </div>
                             <div class="form-group">
            <label>Products &amp; Services</label>
            <textarea name="products" class="form-control" placeholder="Enter products & services..."><?php echo isset($datainfo["cx_products"])? $datainfo["cx_products"]:'' ;?></textarea>            
          </div>
                             <div class="form-group">
            <label>Projects &amp; Initiatives</label>
            <textarea name="projects" class="form-control" placeholder="Enter projecta & initiatives..."><?php echo isset($datainfo["cx_projects"]) ? $datainfo["cx_projects"]:'';?></textarea>            
          </div>
                             <div class="form-group">
            <label>Key / Critical Technology Systems</label>
            <textarea name="systems" class="form-control" placeholder="Enter key systems..."><?php echo isset($datainfo["cx_systems"])? $datainfo["cx_systems"]:'';?></textarea>             
          </div>
                             <div class="form-group">
            <label>Key Relationships (Internal &amp; 3rd Party Outsourcing)</label>
            <textarea name="relationships" class="form-control" placeholder="Enter key relationships..."><?php echo isset($datainfo["cx_relation"]) ? $datainfo["cx_relation"]:'';?></textarea>            
          </div>
                             <div class="form-group">
            <label>Previous Internal Losses</label>
            <textarea name="internallosses" class="form-control" placeholder="Enter previous internal losses..."><?php echo isset($datainfo["cx_internallosses"]) ? $datainfo["cx_internallosses"] : '';?></textarea>            
          </div>
                             <div class="form-group">
            <label>Relevant External Loss Events</label>
            <textarea name="externallosses" class="form-control" placeholder="Enter previous external losses..."><?php echo isset($datainfo["cx_externallosses"]) ? $datainfo["cx_externallosses"]:'';?></textarea>            
          </div>
                             <div class="form-group">
            <label>Competitors</label>
            <textarea name="competitors" class="form-control" placeholder="Enter competitors..."><?php echo isset($datainfo["cx_competitors"]) ? $datainfo["cx_competitors"]:'';?></textarea>
          </div>
                             <div class="form-group">
            <label>Changes in External Environment</label>
            <textarea name="environment" class="form-control" placeholder="Enter changes in the environment..."><?php echo isset($datainfo["cx_environment"]) ? $datainfo["cx_environment"]: '';?></textarea>
          </div>
                             <div class="form-group">
            <label>Regulatory Environment</label>
            <textarea name="regulatory" class="form-control" placeholder="Enter regulatory environment..."><?php echo isset($datainfo["cx_regulatory"])? $datainfo["cx_regulatory"]:'';?></textarea>            
          </div>
          <div class="form-group">
            <button type="submit" class="btn btn-md btn-info" id="btn_save">Save Business Context</button>
            <input name="action" type="hidden" value="context" />
            <input name="id" type="hidden" value="<?php echo $datainfo["idcontext"];?>" />
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
    $( "#date" ).datepicker();
  });
    
}); 
  </script>
</script>
</body>
</html>