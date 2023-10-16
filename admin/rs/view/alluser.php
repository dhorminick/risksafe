<?php

include_once("../controller/auth.php");
include_once("../config.php");
include_once('../model/users.php');

$user=new users;
$data=$user->getUser($_SESSION["userid"]);


if (isset($_REQUEST["response"]) and $_REQUEST["response"]=="err_mail") {
	$msg="User with this e-mail already exists on the system";
}

if (isset($_REQUEST["response"]) and $_REQUEST["response"]=="true") {
	$msg="User profile information updated successfully";
}

if (isset($_REQUEST["response"]) and $_REQUEST["response"]=="err") {
	$msg="Error updating user profile information";
}

        
        


if (isset($_REQUEST["action"])) {
	if ($_REQUEST["action"] == "edit") {
		$edit = true;
		$info = $user->getUsers($_REQUEST["id"]);
		//print_r($info);
	} else {
		$edit = false;
	}
} else {
	$edit = false;
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
      <h1 class="page-header">User Profile</h1>
      
      
      <div class="">
      	
      	<div class="alert alert-info" id="notify" <?php if (!isset($msg)) { ?>style="display: none;"<?php } ?>>
                <?php if (isset($msg)) echo $msg;?>
              </div>
              
        <div class="panel panel-default">
          <div class="panel-body">
          
            <form role="form" class="form" id="form" action="../controller/users.php" method="post">
              
              <h3 class="subtitle">User Information</h3>
              <div class="form-group">
                <label>E-mail</label>
                <input name="email" type="email" maxlength="100" class="form-control" placeholder="Enter e-mail address..."  required value="<?php if(!empty($info['u_mail'])){echo $info['u_mail'];}?>" <?php if(!empty($info['u_mail'])){ echo "disabled";} ?>>
           
              </div>
              <div class="form-group">
                <label>Password</label>
                <input name="password" type="password" maxlength="100" class="form-control" placeholder="Enter password..."  required value="<?php if(!empty($info['u_password'])){echo $info['u_password'];}?>">
       
              </div>
              <div class="form-group">
                <label>Full name</label>
                <input name="name" type="text" maxlength="50" class="form-control" placeholder="Enter your full name..."  required value="<?php if(!empty($info['u_name'])){echo $info['u_name'];}?>">
   
              </div>
              <div class="form-group">
                <label>Pnone Number</label>
                <input name="phone" type="text" maxlength="50" class="form-control" placeholder="Enter your phone number..."  value="<?php if(!empty($info['u_phone'])){echo $info['u_phone'];}?>">
            
              </div>
              <div class="form-group">
                <label>Location</label>
                <input name="location" type="text" maxlength="50" class="form-control" placeholder="Enter your location..."   value="<?php if(!empty($info['u_location'])){echo $info['u_location'];}?>">
         
              </div>
              
              <h3 class="subtitle">Company Information</h3>
              <div class="form-group">
                <label>Company Name</label>
                <input name="company" type="text" maxlength="50" class="form-control" placeholder="Enter company name..." required value="<?php if(!empty($info['c_company'])){echo $info['c_company'];}?>">
              
              </div>
              <div class="form-group">
                <label>Company Address</label>
                <input name="companyaddress" type="text" maxlength="100" class="form-control" placeholder="Enter company address..."  value="<?php if(!empty($info['c_address'])){echo $info['c_address'];}?>">
             
              </div>
              <div class="form-group">
                <label>City</label>
                <input name="city" type="text" maxlength="50" class="form-control" placeholder="Enter company city..."  value="<?php if(!empty($info['c_city'])){echo $info['c_city'];}?>">
               
              </div>
              <div class="form-group">
                <label>State/Provnice</label>
                <input name="state" type="text" maxlength="30" class="form-control" placeholder="Enter state/province..."  value="<?php if(!empty($info['c_state'])){echo $info['c_state'];}?>">
             
              </div>
              <div class="form-group">
                <label>Post Code</label>
                <input name="postcode" type="number" maxlength="20" minlength="4" class="form-control" placeholder="Enter company post code..."  value="<?php if(!empty($info['c_postcode'])){echo $info['c_postcode'];}?>">
             
              </div>
              <div class="form-group">
                <label>Country</label>
                <input name="country" type="text" maxlength="50" class="form-control" placeholder="Enter country..." value="<?php if(!empty($info['c_country'])){echo $info['c_country'];}?>">
        
              </div>
              <div class="form-group">
                <button type="submit" class="btn btn-md btn-info" id="btn_save">Save</button>
                <input name="action" type="hidden" value="<?php echo $_REQUEST['action'];?>" />
                <input name="id" type="hidden" value="<?php echo $info["iduser"];?>" />
                <input name="email_old" type="hidden" value="<?php echo $info["u_mail"];?>" />
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