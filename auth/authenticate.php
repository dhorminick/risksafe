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
    
    $authentication = false;
    $user_authenticated = false;
    $auth_complete = false;
    $invalid_params = false;
    $empty = false;
    
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
  <title>Authenticate | <?php echo $siteEndTitle; ?></title>
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
                
                <?php if ($authentication == true){ ?>
                        <?php if($user_authenticated == true){ ?>
                                <div class="col-lg-8 col-12 fhsgr8">      	
                                    <div class="login-panel card panel-default">
                                        <div class="card-header custom">
                                            <h4 class="card-header-h fu49zk">RiskSafe - Authentication</h4>
                                        </div>
                                        <div class="card-body">
                                            <div class="empty-state" data-height="320">
                                              <div class="empty-state-icon"  style='display:flex;justify-content:center;align-items:center;'>
                                                <i class="fas fa-question"></i>
                                              </div>
                                              <h2>Error 402: Authentication Error</h2>
                                              <p class="lead" style='line-height:20px;margin-top:10px;'>
                                                User already authenticated, contact our support team @ <a class="bb" href='mailto:support.risksafe.co'>support.risksafe.co</a> if you have any issue with our application!!
                                              </p>
                                              <a href="/" class="btn btn-primary mt-4 btn-icon icon-left"><i class='fas fa-arrow-left'></i> Go Back Home</a>
                                            </div>
                                        </div>
                                    </div>                
                                    
                                </div>
                        <?php }else{ ?>
                            <?php if($auth_complete == true){ ?>
                                <div class="col-lg-8 col-12 fnhsgr8">       	
                                    <div class="login-panel card panel-default">
                                        <div class="card-header custom">
                                            <h4 class="card-header-h fu49zk">Authentication Successful</h4>
                                        </div>
                                        <div class="card-body text-left" style='text-align:left;'>
                                            <div class="empty-state" style='padding:0px 0px 20px 0px;text-align:left !important;font-weight:400;'>
                                              <div style='width:100% !important;margin-bottom:10px;'>Congratulations! Your Email Address is Verified,</div>
                                              <div style='width:100% !important;'>What's Next?</div>
                                              <p class="lead" style='line-height:20px;text-align:left;'>
                                                <ul>
                                                <li><strong>Explore Your Account:</strong> Log in to your account to start exploring all the features and functionalities available to you. From personalizing your profile to engaging with our services,
                                                there's so much waiting for you.</li>
                                                <li><strong>Stay Updated:</strong> Be the first to know about exciting updates, exclusive offers, and important announcements. Make sure to keep an eye on your inbox for our latest news and insights.</li>
                                                </ul>
                                              </p>
                                              <div class='text-right' style='width:100% !important;'>
                                                <a href="sign-in" class="btn btn-primary btn-lg mt-4 btn-icon icon-right">Sign In To Account <i class='fas fa-arrow-right'></i></a>
                                              </div>
                                            </div>
                                        </div>
                                    </div>                
                                    
                                </div>
                            <?php }else{ ?>
                                <div class="col-lg-8 col-12 fhsgr8">      	
                                    <div class="login-panel card panel-default">
                                        <div class="card-header custom">
                                            <h4 class="card-header-h fu49zk">RiskSafe - Authentication</h4>
                                        </div>
                                        <div class="card-body">
                                            <div class="empty-state" data-height="320">
                                              <div class="empty-state-icon"  style='display:flex;justify-content:center;align-items:center;'>
                                                <i class="fas fa-question"></i>
                                              </div>
                                              <h2>Error 502: Authentication Error</h2>
                                              <p class="lead" style='line-height:20px;'>
                                                Unable to update user status to authenticated, contact our support @ <a class="bb" href='mailto:support.risksafe.co'>support.risksafe.co</a> team for more information!!
                                              </p>
                                              <a href="/" class="btn btn-primary mt-4 btn-icon icon-left"><i class='fas fa-arrow-left'></i> Go Back Home</a>
                                              <a href="help#auth" class="mt-4 bb">Need Help?</a>
                                            </div>
                                            <!--Error 502: Authentication Error, Contact Our Support Team For More Info!!-->
                                        </div>
                                    </div>                
                                    
                                </div>
                            <?php } ?>
                        <?php } ?>
                        
                    <?php }else{ ?>
                                <div class="col-lg-8 col-12 fhsgr8">      	
                                    <div class="login-panel card panel-default">
                                        <div class="card-header custom">
                                            <h4 class="card-header-h fu49zk">RiskSafe - Authentication</h4>
                                        </div>
                                        <div class="card-body">
                                            <div class="empty-state" data-height="320">
                                              <div class="empty-state-icon"  style='display:flex;justify-content:center;align-items:center;'>
                                                <i class="fas fa-question"></i>
                                              </div>
                                              <h2>Error 402: Authentication Error</h2>
                                              <p class="lead" style='line-height:20px;margin-top:10px;'>
                                                Authentication parameters error!!! Contact our support team @ <a class="bb" href='mailto:support.risksafe.co'>support.risksafe.co</a> if you have any issue with our application!!
                                              </p>
                                              <a href="/" class="btn btn-primary mt-4 btn-icon icon-left"><i class='fas fa-arrow-left'></i> Go Back Home</a>
                                            </div>
                                        </div>
                                    </div>                
                                    
                                </div>
                    <?php } ?>
                    
                    <?php if($invalid_params == true){ ?>
                                <div class="col-lg-8 col-12 fhsgr8">      	
                                    <div class="login-panel card panel-default">
                                        <div class="card-header custom">
                                            <h4 class="card-header-h fu49zk">RiskSafe - Authentication</h4>
                                        </div>
                                        <div class="card-body">
                                            <div class="empty-state" data-height="320">
                                              <div class="empty-state-icon"  style='display:flex;justify-content:center;align-items:center;'>
                                                <i class="fas fa-question"></i>
                                              </div>
                                              <h2>Error 402: Invalid Parameters!!</h2>
                                              <a href="help#auth" class="btn btn-primary mt-4 btn-icon icon-right">Help <i class='fas fa-question'></i></a>
                                            </div>
                                        </div>
                                    </div>                
                                    
                                </div>
                    <?php } ?>
            </div>
        </div>
        
        </div>
    </div>
    <?php require $file_dir.'layout/general_js.php' ?>
</body>
</html>