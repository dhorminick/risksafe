<?php
    include_once("rs/config.php");
    require 'layout/db.php';
    require 'layout/variablesandfunctions.php';   
    $message = [];
    $invalid_params = false;
    if (isset($_GET['auth']) && isset($_GET['e'])) {
        $authentication = true;
        $auth = sanitizePlus($_GET['auth']);
        $e = sanitizePlus($_GET['e']);
        $CheckIfUserExist = "SELECT * FROM users WHERE weirdlyEncode(u_mail) = '$e' AND u_otp = '$auth' LIMIT 1";
        $UserExist = $con->query($CheckIfUserExist);
        if ($UserExist->num_rows > 0) {
            $row = $UserExist->fetch_assoc();
            $u_complete = $row['u_complete'];
            $u_id = $row['u_id'];
            if ($u_complete == '1') {
                $user_authenticated = true;
            } else {
                $user_authenticated = false;
                $updateUser = "UPDATE users SET u_complete = '1' WHERE u_mail = '$e' AND u_otp = '$auth' AND u_id = '$u_id' AND u_complete = '0' LIMIT 1";
                $userUpdated = $con->query($updateUser);
                if($userUpdated){
                    $auth_complete = true;
                }else{
                    $auth_complete = false;
                }
            }
            
        }else{
            $invalid_params = true;
        }
    } else {
        $authentication = false;
    }
    
?>
<!DOCTYPE html>
<html lang="en">
<head>
<?php include("layout/user_header.php");?>
<style>
    body {
    font-size: 14px;
    line-height: 1.42857143;
    color: #333;
    background-color: #fff;
    background: url(http://risksafe.co/img/intro-bg.png);
    background-size: cover;
    background-repeat: no-repeat;
}
    </style>
</head>
<div id="totalHeight"></div>
<body>
    <div class="container" >
        <div class="row firoiz" style="width: 100%;">
        	
        <?php if ($authentication == true){ ?>
            <?php if($user_authenticated == true){ ?>
                <div class="col-lg-6 col-12 fnhsgr8"> 
                    <?php include 'layout/alert.php'; ?>           	
                    <div class="login-panel panel panel-default">
                        <div class="panel-heading custom">
                            <h3 class="panel-title fu49zk">RiskSafe - Authentication</h3>
                        </div>
                        <div class="panel-body">
                            Account Already Authenticated, , Redirecting In
                        </div>
                        <div class="panel-footer custom" style="text-align: center;">
                            <a href="help.php#auth" class="bb">Help ?</a>
                        </div>
                    </div>                
                    
                </div>
            <?php }else{ ?>
                <?php if($auth_complete == true){ ?>
                    <div class="col-lg-6 col-12 fnhsgr8"> 
                        <?php include 'layout/alert.php'; ?>           	
                        <div class="login-panel panel panel-default">
                            <div class="panel-heading custom">
                                <h3 class="panel-title fu49zk">RiskSafe - Authentication</h3>
                            </div>
                            <div class="panel-body">
                                Account Email Address: <p>""</p> Verified Succesfully, Redirecting In 
                            </div>
                            <div class="panel-footer custom" style="text-align: center;">
                                <a href="help.php#auth" class="bb">Help ?</a>
                            </div>
                        </div>                
                        
                    </div>
                <?php }else{ ?>
                    <div class="col-lg-6 col-12 fnhsgr8"> 
                        <?php include 'layout/alert.php'; ?>           	
                        <div class="login-panel panel panel-default">
                            <div class="panel-heading custom">
                                <h3 class="panel-title fu49zk">RiskSafe - Authentication</h3>
                            </div>
                            <div class="panel-body">
                                Error 502: Authentication Error, Contact Our Support Team For More Info!!
                            </div>
                            <div class="panel-footer custom" style="text-align: center;">
                                <a href="help.php#auth" class="bb">Help ?</a>
                            </div>
                        </div>                
                        
                    </div>
                <?php } ?>
            <?php } ?>
        <?php }else{ ?>
            <div class="col-lg-6 col-12 fnhsgr8"> 
                <?php include 'layout/alert.php'; ?>           	
                <div class="login-panel panel panel-default">
                    <div class="panel-heading custom">
                        <h3 class="panel-title fu49zk">RiskSafe - Authentication</h3>
                    </div>
                    <div class="panel-body">
                        Error 402: Missing Parameters!!
                    </div>
                    <div class="panel-footer custom" style="text-align: center;">
						<a href="help.php#auth" class="bb">Help ?</a>
                    </div>
                </div>                
            	
            </div>
        <?php } ?>
        <?php if($invalid_params == true){ ?>
            <div class="col-lg-6 col-12 fnhsgr8"> 
                <?php include 'layout/alert.php'; ?>           	
                <div class="login-panel panel panel-default">
                    <div class="panel-heading custom">
                        <h3 class="panel-title fu49zk">RiskSafe - Authentication</h3>
                    </div>
                    <div class="panel-body">
                        Error 402: Invalid Parameters!!
                    </div>
                    <div class="panel-footer custom" style="text-align: center;">
                        <a href="help.php#auth" class="bb">Help ?</a>
                    </div>
                </div>                
                
            </div>
        <?php }else{} ?>
        </div>
    </div>
<?php include("layout/user_footer.php");?>
<style>
    .panel {
        min-height: 10px !important;
    }
    .panel-body{
        text-align: center;
    }
</style>
</body>
</html>