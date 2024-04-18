<?php
    require ("layout/config.php");
    require 'layout/db.php';
    require 'layout/variablesandfunctions.php';   
    $message = [];
    $invalid_params = false;
    $empty = true;
    if (isset($_GET['auth']) && isset($_GET['e'])) {
        $authentication = true;
        $empty = true;
        $auth = sanitizePlus($_GET['auth']);
        $e = sanitizePlus($_GET['e']);
        $CheckIfUserExist = "SELECT * FROM users WHERE md5(crc32(md5(crc32(md5(crc32(md5(u_mail))))))) = '$e' AND u_otp = '$auth' LIMIT 1";
        $UserExist = $con->query($CheckIfUserExist);
        if ($UserExist->num_rows > 0) {
            $row = $UserExist->fetch_assoc();
            $u_complete = $row['u_complete'];
            $u_id = $row['u_id'];
            $em = $row['u_mail'];
            if ($u_complete == '1') {
                $user_authenticated = true;
            } else {
                $user_authenticated = false;
                $updateUser = "UPDATE users SET u_complete = 'true' WHERE u_mail = '$em' AND u_otp = '$auth' AND u_id = '$u_id' AND u_complete = 'false' LIMIT 1";
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
  <meta charset="UTF-8">
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport">
  <title>Register | <?php echo APP_TITLE; ?></title>
  <?php require 'layout/general_css.php' ?>
  <link rel="stylesheet" href="assets/css/index.custom.css">
  <link rel="stylesheet" href="assets/css/main.css">
</head>
<div id="totalHeight" style="position: absolute;width:100%;height:100%;"></div>
<body>
    <div class="loader"></div>
    <div id="app">
        <div class="main-wrapper main-wrapper-1">
        <div class="navbar-bg"></div>
        <?php require 'layout/header_main.php' ?>
        <?php #require 'layout/sidebar.php' ?>
        <!-- Main Content -->
        <div class="main-content">
            <section class="section">
            <div class="section-body">
                <div class="row firoiz" style="width: 100%;">
                    <?php if ($authentication == true){ ?>
                        <?php if($user_authenticated == true){ ?>
                            <div class="col-lg-6 col-12 fnhsgr8"> 
                                <?php include 'layout/alert.php'; ?>           	
                                <div class="login-panel card panel-default">
                                    <div class="card-header custom">
                                        <h3 class="card-header-h fu49zk">RiskSafe - Authentication</h3>
                                    </div>
                                    <div class="card-body">
                                        Account Already Authenticated, , Redirecting In
                                    </div>
                                    <div class="card-footer custom" style="text-align: center;">
                                        <a href="help.php#auth" class="bb">Help ?</a>
                                    </div>
                                </div>                
                                
                            </div>
                        <?php }else{ ?>
                            <?php if($auth_complete == true){ ?>
                                <div class="col-lg-6 col-12 fnhsgr8"> 
                                    <?php include 'layout/alert.php'; ?>           	
                                    <div class="login-panel card panel-default">
                                        <div class="card-header custom">
                                            <h3 class="card-header-h fu49zk">RiskSafe - Authentication</h3>
                                        </div>
                                        <div class="card-body">
                                            Account Email Address: <p>""</p> Verified Succesfully, Redirecting In 
                                        </div>
                                        <div class="card-footer custom" style="text-align: center;">
                                            <a href="help.php#auth" class="bb">Help ?</a>
                                        </div>
                                    </div>                
                                    
                                </div>
                            <?php }else{ ?>
                                <div class="col-lg-6 col-12 fnhsgr8"> 
                                    <?php include 'layout/alert.php'; ?>           	
                                    <div class="login-panel card panel-default">
                                        <div class="card-header custom">
                                            <h3 class="card-header-h fu49zk">RiskSafe - Authentication</h3>
                                        </div>
                                        <div class="card-body">
                                            Error 502: Authentication Error, Contact Our Support Team For More Info!!
                                        </div>
                                        <div class="card-footer custom" style="text-align: center;">
                                            <a href="help.php#auth" class="bb">Help ?</a>
                                        </div>
                                    </div>                
                                    
                                </div>
                            <?php } ?>
                        <?php } ?>
                    <?php }else{ ?>
                        <div class="col-lg-6 col-12 fnhsgr8"> 
                            <?php include 'layout/alert.php'; ?>           	
                            <div class="login-panel card panel-default">
                                <div class="card-header custom">
                                    <h3 class="card-header-h fu49zk">RiskSafe - Authentication</h3>
                                </div>
                                <div class="card-body">
                                    Error 402: Missing Parameters!!
                                </div>
                                <div class="card-footer custom" style="text-align: center;">
                                    <a href="help.php#auth" class="bb">Help ?</a>
                                </div>
                            </div>                
                            
                        </div>
                    <?php } ?>
                    <?php if($invalid_params == true){ ?>
                        <div class="col-lg-6 col-12 fnhsgr8"> 
                            <?php include 'layout/alert.php'; ?>           	
                            <div class="login-panel card panel-default">
                                <div class="card-header custom">
                                    <h3 class="card-header-h fu49zk">RiskSafe - Authentication</h3>
                                </div>
                                <div class="card-body">
                                    Error 402: Invalid Parameters!!
                                </div>
                                <div class="card-footer custom" style="text-align: center;">
                                    <a href="help.php#auth" class="bb">Help ?</a>
                                </div>
                            </div>                
                            
                        </div>
                    <?php }else{} ?>
                </div>            
            </div>
            </section>
        </div>
        <?php require 'layout/user_footer.php' ?>
        </footer>
        </div>
    </div>
    <?php require 'layout/general_js.php' ?>
    <script src="assets/js/auth.js"></script>
    <style>
        .fopaei{
            position: absolute !important;
            bottom: 0;
            /* border: 1px solid red; */
            width: 100%;
            padding: 10px;
        }
        .firoiz{
            width: 100%;display: flex;align-items:center;justify-content:center;
        }
        .card-body{
            text-align: center;
            padding: 30px 0px !important;
        }
    </style>
</body>
</html>