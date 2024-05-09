<?php
    session_start();
    
    if (isset($_SESSION["loggedIn"]) == true || isset($_SESSION["loggedIn"]) === true) {
        $signedIn = true;
    } else {
        $signedIn = false;
    }
    $message = [];
    
    $file_dir = '../';
    
    include $file_dir.'layout/db.php';
    require $file_dir.'layout/variablesandfunctions.php';
    include $file_dir.'layout/mail.php';
    
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
            $row = $UserExist->fetch_assoc();
            $em = $row['u_mail'];
        }else{
            $toReset = false;
            #error, user doesn't exist
            array_push($message, "Error 402: Authentication Token Doesn't Exist!!");
        }
    }else{
        $authe = false;
    }
    
    if (isset($_POST['reset-password'])) {
        $n_p = sanitizePlus($_POST['n_pass']);
        $c_n_p = sanitizePlus($_POST['c_n_pass']);
        $__c__ = sanitizePlus($_POST["__c__"]);
        $__em__ = sanitizePlus($_POST[" __em__"]);
        
        if($__c__ == '' || $__c__ == null){      
            if($n_p == $c_n_p && $n_p !== ''){
                #select all mails
                $CheckIfUserExist = "SELECT * FROM users WHERE crc32(md5(u_mail)) = '$__em__' AND reset_pass = 'true' LIMIT 1";
                $UserExist = $con->query($CheckIfUserExist);
                if ($UserExist->num_rows > 0) {
                    $row = $UserExist->fetch_assoc();
                    $e_mail = $row['u_mail'];
                    $p_word = $row['u_password'];
                    $n_p = weirdlyEncode($n_p);
                    $updateUser = "UPDATE users SET u_password = '$n_p', reset_pass = 'false' WHERE u_mail = '$e_mail' AND u_password = '$p_word' AND reset_pass = 'true' LIMIT 1";
                    $userUpdated = $con->query($updateUser);
                    if($userUpdated){
                        session_unset();
                        session_destroy();
                        header('Location: sign-in');
                        exit();
                    }else{
                        array_push($message, "Error 502: Error Reseting Passwords!!");
                    }
                }
            }else{
                array_push($message, "Error 402: New Passwords Don't Match!!");
            }
        }
    }
    if (isset($_POST['reset_l'])) {
        $session_email = $_SESSION["userMail"];
        $o_p = sanitizePlus($_POST['o_pass']);
        $n_p = sanitizePlus($_POST['n_pass']);
        $c_n_p = sanitizePlus($_POST['c_n_pass']);
        
        $__c__ = sanitizePlus($_POST["__c__"]);
        
        $o_p = weirdlyEncode($o_p);
        
        if($__c__ == '' || $__c__ == null){
            if($n_p == $c_n_p && $n_p !== ''){
                #select all mails
                $CheckIfUserExist = "SELECT * FROM users WHERE u_password = '$o_p' AND u_mail = '$session_email' LIMIT 1";
                $UserExist = $con->query($CheckIfUserExist);
                if ($UserExist->num_rows > 0) {
                    $row = $UserExist->fetch_assoc();
                    $activated = $row['u_complete'];
        
                    if ($activated == 'true') {
                        $n_p = weirdlyEncode($n_p);
                        $updateUser = "UPDATE users SET u_password = '$n_p' WHERE u_password = '$o_p' AND u_mail = '$session_email' AND u_complete = 'true' AND reset_pass = 'true' LIMIT 1";
                        $userUpdated = $con->query($updateUser);
                        if($userUpdated){
                            session_unset();
                            session_destroy();
                            header('Location: sign-in');
                            exit();
                        }else{
                            array_push($message, "Error 502: Error Reseting Passwords!!");
                        }
                    } else {
                        array_push($message, "Account Error: Email Address Needs To Be Verified Before Password Reset!!");
                    }
                }else{
                    array_push($message, "Error 402: Password Incorrect!!");
                }
            }else{
                array_push($message, "Error 402: New Passwords Don't Match!!");
            }
        }
        
    }
    if (isset($_POST['reset_nl'])) {
        $email = sanitizePlus($_POST['email']);
        $__c__ = sanitizePlus($_POST["__c__"]);
        
        if($__c__ == '' || $__c__ == null){
            #select all mails
            $CheckIfUserExist = "SELECT * FROM users WHERE u_mail = '$email'";
            $UserExist = $con->query($CheckIfUserExist);
            if ($UserExist->num_rows > 0) {
                $row = $UserExist->fetch_assoc();
                $email = $row['u_mail'];
                $password = $row['u_password'];
                $name = $row['u_name'];
                
                $reset_link = $website__.'auth/reset-password?u='.weirdlyEncode($email).'&auth='.$password;
                $subject = 'RiskSafe - Reset Account Password';
                $recipient = $email;
                $sender = $resetPassSender;
                $site = $website__;
                $help = $resetPassHelp;
                
                $_sentmail = _resetPass($sender, $recipient, $subject, $reset_link, $name, $site, $help);
                if ($_sentmail['sent'] == 'true' && $_sentmail['error'] == 'none') {
                    $updateUser = "UPDATE users SET reset_pass = 'true' WHERE u_mail = '$email' LIMIT 1";
                    $userUpdated = $con->query($updateUser);
                    
                    if($userUpdated){
                        array_push($message, "Reset Link Sent To '".strtolower($email)."' Successfully!!");
                    }else{
                        array_push($message, "Error 502: Error!!");
                    }
                    
                }else{
                    #error, mail unsuccessful
                    array_push($message, "Error 502: Error Sending Reset Link - ".$_sentmail['error']);
                }
            }else{
                array_push($message, "Error 402: Email Does Not Exist!!");
            }
        }
        
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport">
  <title>Reset Password | <?php echo $siteEndTitle; ?></title>
  <?php require $file_dir.'layout/general_css.php' ?>
  <link href='style.css' rel='stylesheet' />
</head>

<body>
    <div class="loader"></div>
    <div id="app">
        <div class="main-wrapper main-wrapper-1">
        <!-- Main Content -->
        <div class="navbar-bg"></div>
        <?php include $file_dir.'layout/header_auth.php'; ?>
                
        <div class="main-content custom">
            <div class="section-body row custom">
                <div class='col-12 col-lg-8' style='margin-bottom:10px;'><?php include $file_dir.'layout/alert.php'; ?></div>
                <div class="col-lg-8 col-12 fnhsgr8">       	
                    <div class="login-panel card panel-default">
                        <div class="card-header custom">
                            <h4 class="card-header-h fu49zk">Reset Password:</h4>
                        </div>
                        <div class="card-body text-left" style='text-align:left;'>
                            <div class="empty-state" style='padding:0px 0px 20px 0px;text-align:left !important;font-weight:400;'>
                                <?php if($authe == true){ #if get auth ?>
                                
                                <?php if($toReset == true){ #if get auth ?>
                                <form method="post" class='row' style='width:100%;'>
                                    <div class='mb-20 col-12 col-lg-6'>
                                        <label for="_m_">New Password:</label>
                                        <input type="password" class="form-control" id="_m_" name="n_pass" />
                                    </div>
                                    <div class='col-12 col-lg-6'>
                                        <label for="_n_">Confirm New Password:</label>
                                        <input type="password" class="form-control" id="_n_" name="c_n_pass" />
                                    </div>
                                    <div class='c mb-20 col-12'>
                                        <input type="checkbox" id="_cc_" />
                                        <span>Show Passwords</span>
                                    </div>
                                    <input type="hidden" name="__c__">
                                    <input type="hidden" name="__em__" value='<?php echo crc32(md5($em)); ?>'>
                                    
                                    <div class='text-right mt-10 col-12' style='width:100% !important;'>
                                        <button type='submit' name='reset-password' class="btn btn-primary btn-lg mt-4 btn-icon icon-right">Reset Account Password</button>
                                    </div>
                                </form>
                                <?php }else{ ?>
                                <div class="empty-state-icon" style='display:flex;justify-content:center;align-items:center;'>
                                    <i class="fas fa-question"></i>
                                </div>
                                <h2>Error 402: Authentication Error!!</h2>
                                <a href="/" class="btn btn-primary mt-4 btn-icon icon-left"><i class='fas fa-arrow-left'></i> Go Back Home</a>
                                <?php } ?>
                                
                                <?php }else{ ?>
                                
                                <?php if($signedIn == true){ #if signed in ?>
                                <form method="post" class='row' style='width:100%;'>
                                    <div class='mb-20 col-12'>
                                        <label for="_o_">Old Password:</label>
                                        <input type="password" class="form-control" id="_o_" name="o_pass" required />
                                    </div>
                                    <div class='mb-20 col-12 col-lg-6'>
                                        <label for="_m_">New Password:</label>
                                        <input type="password" class="form-control" id="_m_" name="n_pass" required />
                                    </div>
                                    <div class='col-12 col-lg-6'>
                                        <label for="_n_">Confirm New Password:</label>
                                        <input type="password" class="form-control" id="_n_" name="c_n_pass" required />
                                    </div>
                                    <div class='c mb-20 col-12'>
                                        <input type="checkbox" id="_cc_" />
                                        <span>Show Passwords</span>
                                    </div>
                                    <input type="hidden" name="__c__">
                                    <div class='text-right mt-10 col-12' style='width:100% !important;'>
                                        <button type='submit' name='reset_l' class="btn btn-primary btn-lg mt-4 btn-icon icon-right">Reset Account Password</button>
                                    </div>
                                </form>
                                <?php }else{ ?>
                                <form method="post" style='width:100%;'>
                                    <div class='mb-20'>
                                        <label for="_m_">Registered Email Address:</label>
                                        <input type="email" class="form-control" id="_m_" name="email" required />
                                    </div>
                                    <input type="hidden" name="__c__">
                                    <div class='text-right mt-10' style='width:100% !important;'>
                                        <button type='submit' name='reset_nl' class="btn btn-primary btn-lg mt-4 btn-icon icon-right">Send Reset Link</button>
                                    </div>
                                </form>
                                <?php } ?>
                                
                                <?php } ?>
                                
                            </div>
                        </div>
                    </div>                
                </div>
                
            </div>
        </div>
        
        </div>
    </div>
    <?php require $file_dir.'layout/general_js.php' ?>
</body>
</html>
<style>
    .c {
        display:flex;
    }
    #_cc_{
        margin-right:5px;
    }
</style>
<script>
    var x = document.getElementById("_n_");
    var y = document.getElementById("_m_");
    var o = document.getElementById("_o_");
    
    const someCheckbox = document.getElementById('_cc_');

    someCheckbox.addEventListener('change', e => {
      if(e.target.checked === true) {
        x.type = "text";
        y.type = "text";
        o.type = "text";
      }
      
      if(e.target.checked === false) {
        x.type = "password";
        y.type = "password";
        o.type = "password";
      }
    });
</script>