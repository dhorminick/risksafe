<?php

include_once("../controller/auth.php");
include_once("../config.php");
include_once('../model/users.php');

$user=new users;
$data11=$user->getUser($_SESSION["userid"]);


if (isset($_REQUEST["response"]) and $_REQUEST["response"]=="errchkpwd") {
	$msg="Old Password is not correct";
}

if (isset($_REQUEST["response"]) and $_REQUEST["response"]=="true") {
	$msg="Password updated successfully";
}

if (isset($_REQUEST["response"]) and $_REQUEST["response"]=="erpwdmatch") {
	$msg="Password and confirm password is not match";
}

if (isset($_REQUEST["response"]) and $_REQUEST["response"]=="errsg") {
	$msg="Password  is not updated";
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
    <div class="col-lg-3 col-sm-12"> 
      <!-- Left column -->
      <?php include_once("menu.php");?>
    <!-- /col-3 --> 
  	</div>
  <div class="col-lg-9 col-sm-12">
    <div class="row">
      <h1 class="page-header">Change Password</h1>
      
      
      <div class="">
      	
      	<div class="alert alert-info" id="notify" <?php if (!isset($msg)) { ?>style="display: none;"<?php } ?>>
                <?php if (isset($msg)) echo $msg;?>
              </div>
              
        <div class="panel panel-default">
          <div class="panel-body">
          
            <form role="form" class="form" id="form" action="../controller/users.php" method="post">
              
              <h3 class="subtitle">Change Password</h3>
             
              <div class="form-group">
                <label>Old Password</label>
                <input name="old_pwd" type="password" maxlength="100" class="form-control" placeholder="Enter old password..."  required value="">
       
              </div>

              <div class="form-group">
                <label>New Password</label>
                <input name="new_pwd" type="password" maxlength="100" class="form-control" placeholder="Enter new password..."  required value="">
       
              </div>

              <div class="form-group">
                <label>Confirm Password</label>
                <input name="con_pwd" type="password" maxlength="100" class="form-control" placeholder="Enter confirm password..."  required value="">
       
              </div>
              <div class="form-group">
                <button type="submit" class="btn btn-md btn-info" id="btn_save">Save</button>
                <input name="action" type="hidden" value="changepassword" />
              
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
</body>
</html>