<?php
    session_start();
    require 'layout/db.php';
    require 'layout/config.php';
    require 'layout/variablesandfunctions.php';     
    require 'layout/mail.php';  
    $file_dir = '';
    
    $signedIn = false;
    if (isset($_SESSION["loggedIn"]) == true || isset($_SESSION["loggedIn"]) === true) {
        $signedIn = true;
    } else {}
    
    $message = [];
    
    
    $toReset = false;
    $reseted = false;
    $nl_reseted = false; 
    $a_reseted = false;   
    
    if(isset($_GET['auth']) && isset($_GET['u']) && $_GET['auth'] !== '' && $_GET['u'] !== '' && $_GET['auth'] !== null && $_GET['u'] !== null) {
        $authe = true;
        $e = sanitizePlus($_GET['u']);
        $auth = sanitizePlus($_GET['auth']);

        #select all mails
        $CheckIfUserExist = "SELECT * FROM users WHERE md5(crc32(md5(crc32(md5(crc32(md5(u_mail))))))) = '$e' AND u_password = '$auth' AND reset_pass = 'true' LIMIT 1";
        $UserExist = $con->query($CheckIfUserExist);
        if ($UserExist->num_rows > 0) {
            $toReset = true;
            if (isset($_POST['reset_auth'])) {
                $n_p = sanitizePlus($_POST['new_password']);
                $c_n_p = sanitizePlus($_POST['confirm_new_password']);
                
                if($n_p == $c_n_p && $n_p !== ''){
                    #select all mails
                    $CheckIfUserExist = "SELECT * FROM users WHERE u_password = '$auth' AND md5(crc32(md5(crc32(md5(crc32(md5(u_mail))))))) = '$e' AND reset_pass = 'true' LIMIT 1";
                    $UserExist = $con->query($CheckIfUserExist);
                    if ($UserExist->num_rows > 0) {
                        $row = $UserExist->fetch_assoc();
                        $e_mail = $row['u_mail'];
                        $p_word = $row['u_password'];
                        $n_p = weirdlyEncode($n_p);
                        $updateUser = "UPDATE users SET u_password = '$n_p', reset_pass = 'false' WHERE u_mail = '$e_mail' AND u_password = '$p_word' AND reset_pass = 'true' LIMIT 1";
                        $userUpdated = $con->query($updateUser);
                        if($userUpdated){
                            $a_reseted = true;  
                            session_unset();
                            session_destroy();
                        }else{
                            $a_reseted = false;   
                            array_push($message, "Error 502: Error Reseting Passwords!!");
                        }
                    }
                }else{
                    array_push($message, "Error 402: New Passwords Don't Match!!");
                }
                
            }
        }else{
            $toReset = false;
            #error, user doesn't exist
            array_push($message, "Error 402: Authentication Token Doesn't Exist!!");
        }
    }else{
        $authe = false;
    }
    
    if (isset($_POST['reset_l'])) {
        $session_email = $_SESSION["userMail"];
        $o_p = sanitizePlus($_POST['old_password']);
        $n_p = sanitizePlus($_POST['new_password']);
        $c_n_p = sanitizePlus($_POST['confirm_new_password']);
        
        $o_p = weirdlyEncode($o_p);
        
        if($n_p == $c_n_p && $n_p !== ''){
            #select all mails
            $CheckIfUserExist = "SELECT * FROM users WHERE u_password = '$o_p' AND u_mail = '$session_email' LIMIT 1";
            $UserExist = $con->query($CheckIfUserExist);
            if ($UserExist->num_rows > 0) {
                $row = $UserExist->fetch_assoc();
                $activated = $row['u_complete'];
    
                if ($activated == 'true') {
                    $n_p = weirdlyEncode($n_p);
                    $updateUser = "UPDATE users SET u_password = '$n_p' WHERE u_mail = '$session_email' AND u_complete = 'true' LIMIT 1";
                    $userUpdated = $con->query($updateUser);
                    if($userUpdated){
                        $reseted = true;  
                        session_unset();
                        session_destroy();
                    }else{
                        $reseted = false;   
                        array_push($message, "Error 502: Error Reseting Passwords!!");
                    }
                } else {
                    array_push($message, "Account Error: Account Email Address Needs To Be Verified Before Password Reset!!");
                }
            }
        }else{
            array_push($message, "Error 402: New Passwords Don't Match!!");
        }
        
    }
    if (isset($_POST['reset_nl'])) {
        $email = sanitizePlus($_POST['email']);
        #select all mails
        $CheckIfUserExist = "SELECT * FROM users WHERE u_mail = '$email'";
        $UserExist = $con->query($CheckIfUserExist);
        if ($UserExist->num_rows > 0) {
            $row = $UserExist->fetch_assoc();
            $email = $row['u_mail'];
            $password = $row['u_password'];
            $name = $row['u_name'];
            
            $reset_link = $website__.'reset-password?u='.weirdlyEncode($email).'&auth='.$password;
            $subject = 'RiskSafe - Reset Account Password';
            $recipient = $email;
            $sender = $resetPassSender;
            $site = $website__;
            $help = $resetPassHelp;
            $_sentmail = _resetPass($sender, $recipient, $subject, $reset_link, $name, $page_fb, $page_ig, $page_yt, $page_x, $siteMainLocation, $page_wt, $site, $help);
            if ($_sentmail['sent'] == 'true' && $_sentmail['error'] == 'none') {
                $updateUser = "UPDATE users SET reset_pass = 'true' WHERE u_mail = '$email' LIMIT 1";
                $userUpdated = $con->query($updateUser);
                
                $nl_reseted = true;
            }else{
                #error, mail unsuccessful
                array_push($message, "Error 502: Error Sending Reset Link - ".$_sentmail['error']);
            }
        }else{
            array_push($message, "Error 402: Email Provided Does Not Exist!!");
        }
        
    }

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport">
  <title>Reset Password | <?php echo APP_TITLE; ?></title>
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
        <div class="main-content" style='margin-top:30px;'>
            <section class="section">
            <div class="section-body">
                <div class="row firoiz">
                    <div class="col-lg-8 col-12 fnhsgr8">       
                        <?php include 'layout/alert.php'; ?>
                        <div class="card" style="padding:10px;">
                            <div class="card-header custom">
                                <h3 class="panel-title fu49zk card-header-h">RiskSafe - Reset Account Password</h3>
                            </div>
                            
                            <?php if($toReset == true){ #resend otp?>
                            
                            <?php if($a_reseted == true){ ?>
                            <div class="card-body">
                                <div style='text-align:center;'>
                                    <div style='margin-bottom:10px !important;'>Password Reset Successfully!!</div>
                                    Redirecting To Login in <span id="count">5</span>
                                    <div style='margin:10px 0px;'>
                                        <button class='btn btn-primary btn-icon icon-left'><i class='fas fa-arrow-left'></i> Back To Login</button>
                                    </div>
                                </div>
                            </div>
                            <script>
                                function countdown() {
                                    var i = document.getElementById('count');
                                    if (parseInt(i.innerHTML)<=0) {
                                        location.href = '/login';
                                    }
                                    if (parseInt(i.innerHTML)!=0) {
                                        i.innerHTML = parseInt(i.innerHTML)-1;
                                    }
                                }
                                setInterval(function(){ countdown(); },1000);
                            </script>
                            <?php }else{ ?>
                            <div class="card-body"><form role="form" method="post">
                                    <fieldset>
                                        <div class="row">
                                        <div class="form-group col-lg-6 col-12">
                                            <div class="d-block">
                                            <label for="password" class="control-label">Password</label>
                                            </div>
                                            <div class="input-group">
                                                <input type="password" class="form-control" placeholder="New Password..." name="new_password" type="password" id="_pass" required>
                                                <div class="input-group-append">
                                                    <div class="input-group-text fyeviu" style="cursor:pointer;">
                                                        <i class="fa fa-eye"></i>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group col-lg-6 col-12">
                                            <div class="d-block">
                                            <label for="password" class="control-label">Password</label>
                                            </div>
                                            <div class="input-group">
                                                <input type="password" class="form-control" placeholder="Confirm Password..." name="confirm_new_password" type="password" id="__pass" required>
                                            </div>
                                        </div>
                                        </div>
                                        <div style="text-align: center;display:flex">
                                            <button type="submit" class="btn btn-md btn-primary btn_signup" style="width: 100%;" name="reset_auth">Reset Account Password</button>
                                        </div>                              
                                    </fieldset>
                            </form></div>
                            <?php } ?>
                            
                            <?php }else{ ?>
                            
                            <?php if($signedIn == true){ #user signed in?>
                            
                            <?php if($reseted == true){ ?>
                            <div class="card-body">
                                <div style='text-align:center;'>
                                    <div style='margin-bottom:10px !important;'>Password Reset Successfully!!</div>
                                    Redirecting To Login in <span id="count">5</span>
                                    <div style='margin:10px 0px;'>
                                        <button class='btn btn-primary btn-icon icon-left'><i class='fas fa-arrow-left'></i> Back To Login</button>
                                    </div>
                                </div>
                            </div>
                            <script>
                                function countdown() {
                                    var i = document.getElementById('count');
                                    if (parseInt(i.innerHTML)<=0) {
                                        location.href = '/login';
                                    }
                                    if (parseInt(i.innerHTML)!=0) {
                                        i.innerHTML = parseInt(i.innerHTML)-1;
                                    }
                                }
                                setInterval(function(){ countdown(); },1000);
                            </script>
                            <?php }else{ ?>
                            <div class="card-body"><form role="form" method="post">
                                    <fieldset>
                                        <div class="form-group">
                                            <div class="d-block">
                                            <label for="password" class="control-label">Old Password:</label>
                                            </div>
                                            <div class="input-group">
                                                <input type="password" class="form-control" placeholder="New Password..." name="old_password" type="password" required>
                                            </div>
                                        </div>
                                        <div class="row">
                                        <div class="form-group col-lg-6 col-12">
                                            <div class="d-block">
                                            <label for="password" class="control-label">New Password:</label>
                                            </div>
                                            <div class="input-group">
                                                <input type="password" class="form-control" placeholder="New Password..." name="new_password" type="password" id="_pass" required>
                                                <div class="input-group-append">
                                                    <div class="input-group-text fyeviu" style="cursor:pointer;">
                                                        <i class="fa fa-eye"></i>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group col-lg-6 col-12">
                                            <div class="d-block">
                                            <label for="password" class="control-label">Confirm Password:</label>
                                            </div>
                                            <div class="input-group">
                                                <input type="password" class="form-control" placeholder="Confirm Password..." name="confirm_new_password" type="password" id="__pass" required>
                                            </div>
                                        </div>
                                        </div>
                                        <div style="text-align: center;display:flex">
                                            <button type="submit" class="btn btn-md btn-primary btn_signup" style="width: 100%;" name="reset_l">Reset Account Password</button>
                                        </div>                              
                                    </fieldset>
                            </form></div>
                            <?php } ?>
                            
                            <?php }else{ ?>
                            <?php if($nl_reseted == true){ ?>
                            <div class="card-body">
                                <div style='text-align:center;'>
                                    <div style='margin-bottom:10px !important;'>Password Reset Successfully!!</div>
                                    Redirecting To Login in <span id="count">5</span>
                                    <div style='margin:10px 0px;'>
                                        <button class='btn btn-primary btn-icon icon-left'><i class='fas fa-arrow-left'></i> Back To Login</button>
                                    </div>
                                </div>
                            </div>
                            <script>
                                function countdown() {
                                    var i = document.getElementById('count');
                                    if (parseInt(i.innerHTML)<=0) {
                                        location.href = '/login';
                                    }
                                    if (parseInt(i.innerHTML)!=0) {
                                        i.innerHTML = parseInt(i.innerHTML)-1;
                                    }
                                }
                                setInterval(function(){ countdown(); },1000);
                            </script>
                            <?php }else{ ?>
                            <div class="card-body"><form role="form" method="post">
                                    <fieldset>
                                            <div class="form-group">
                                                <div class="d-block">
                                                <label for="e" class="control-label">Old Password:</label>
                                                </div>
                                                <div class="input-group">
                                                    <input type="email" class="form-control" placeholder="Your Registered RiskSafe Email Address..." name="email" type="password" id='e' required>
                                                </div>
                                            </div>
                                        <div class="form-group">
                                            <div style="text-align: center;display:flex">
                                                <button type="submit" class="btn btn-md btn-primary btn_signup" style="width: 100%;" name="reset_nl">Send Reset Link</button>
                                            </div>                              
                                        </div>
                                    </fieldset>
                            </form></div>
                            <?php } ?>
                            
                            <?php } ?>
                            
                            <?php } ?>
                        </div>             
                    </div>
                </div>
            </div>
            </section>
        </div>
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
    </style>
</body>
</html>

