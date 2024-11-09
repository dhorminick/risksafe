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
    require $file_dir.'layout/mail.php';
    
    $nameChecker = false;
    $emailChecker = false;
	$passwordChecker = false;
	$companyChecker = false;

    if (isset($_POST['sign-up']) && $_POST['__c__'] == '') {
        if($signedIn == true){
            array_push($message, "User Already Signed In!!");
        }else{
            $email = sanitizePlus($_POST["email"]);
            $password = sanitizePlus($_POST["password"]);
            $name = sanitizePlus($_POST["fullname"]);
            $company = sanitizePlus($_POST["company"]);
            $u_id = secure_random_string(20);
            $datetime = date("Y-m-d H:i:s");
    		$expire = date("Y-m-d H:i:s", strtotime("+14 days"));
    
            #verify if params have values
            $nameChecker = errorExists($name, $nameChecker);
            $emailChecker = errorExists($email, $emailChecker);
            $passwordChecker = errorExists($password, $passwordChecker);
    		$companyChecker = errorExists($company, $companyChecker);
    
            if ($nameChecker == true || $emailChecker == true || $passwordChecker == true || $companyChecker == true) {
                array_push($message, "Error 402: Incomplete Parameters!!");
            } else {
                $password = weirdlyEncode($password);
                $company_details = array(
                    'company_name' => $company, 
                    'company_address' => '', 
                    'company_city' => '', 
                    'company_state' => '', 
                    'company_postcode' => '', 
                    'company_country' => '', 
                );
                $company_details = serialize($company_details);
                $user_details = array(
                    'fullname' => $name,
                    'phone' => '',
                    'location' => '', 
                );
                $user_details = serialize($user_details);
                $CheckIfUserExist = "SELECT * FROM users WHERE u_mail = '$email' LIMIT 1";
                $UserExist = $con->query($CheckIfUserExist);
                if ($UserExist->num_rows > 0) {
                    array_push($message, "Error 402: Email Already Exists!!");
                }else{
                    $otp = secure_random_string(20);
                    $company_id = secure_random_string(10);
                    $company_id = strtoupper($company_id);
                    $confirmation_link = $site__.'/auth/authenticate?e='.weirdlyEncode($email).'&auth='.$otp;
                    
                    #mail
                    $mailSubject = 'RiskSafe - Confirm Account One-Time Password (OTP)';
                    $mailRecipient = 'etiketochukwu@gmail.com';
                    $mailSender = $signUpSender;
                    $mail = _reg($mailSender, $mailRecipient, $mailSubject, $confirmation_link, $name, $site__, $signUpHelp);
                    #$mail = _createAcc($mailSender, $mailRecipient, $mailSubject, $confirmation_link, $name, $page_fb, $page_ig, $page_yt, $page_x, $siteMainLocation, $page_wt, $site__, $signUpHelp);
                    // $mail['sent'] = true;
                    if ($mail['sent'] === 'true' && $mail['error'] === 'none') {
                        #add user
                        $createNewUser = "INSERT INTO users (`superuserid`, `u_mail`, `u_password`, `u_name`, `u_phone`, `u_location`, `c_company`, `c_address`, `c_city`, `c_state`, `c_postcode`, `c_country`, `u_complete`, `u_otp`, `u_datetime`, `u_expire`, `role`,`user_loginstatus`, `u_id`, `company_users`, `company_id`, `company_details`, `user_details`, `payment_status`, `payment_duration`)
                          VALUES (0, '$email', '$password', '$name', '', '', '$company', '', '', '', '', '', 'false', '$otp', '$datetime', '$expire', 'admin', 0, '$u_id', 'a:0:{}', '$company_id', '$company_details', '$user_details', 'free', 'trial')";
                        $userCreated = $con->query($createNewUser);
                        if ($userCreated) {
                            #$createContext = "INSERT INTO `as_context`(`cx_user`, `cx_objectives`, `cx_processes`, `cx_products`, `cx_projects`, `cx_systems`, `cx_relation`, `cx_internallosses`, `cx_externallosses`, `cx_competitors`, `cx_environment`, `cx_regulatory`) VALUES ('$u_id','','','','','','','','','','','')";
                            #$contextCreated = $con->query($createContext);
                            
                            array_push($message, 'Account Details Registered Successfully, Login To "'.$email.'" To Authenticate The Account!! ');
                        }else{
                            array_push($message, "Error 502: Server Error!! Contact Our Support Team For More Info.");
                        }
                    } else {
                        array_push($message, "Error 502: Error Sending OTP!! ".$mail['error']);
                    }
                    
                }
            }
        }
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport">
  <title>Create Account | <?php echo $siteEndTitle; ?></title>
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
                <div class='col-12' style='margin-bottom:-10px;'><?php include $file_dir.'layout/alert.php'; ?></div>
                <div class="col-lg-11 col-12 ___main">
                    <div class="col-lg-4 col-12 _side">
                        <div class='_sub_side'>
                            <div>
                            <div>
                                <h4>Already Have An Account?</h4>
                            </div>
                            <div class='__sub_sub'>
                                Navigate uncertainties with confidence! Sign up today and unlock tailored risk assessments that empower you to safeguard your future.
                            </div>
                            <div class='_sub_link'>
                                <a href='sign-in' class='btn btn-primary btn-outline btn-lg btn-large'>Sign In</a>
                            </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-8 col-12 __main__">
                        <div class="_group_main">
                            <div class="__main_text"><h3>Welcome To RiskSAFE!!</h3></div>
                            <div class="__main_caption">Please enter valid details below:</div>
                            <div class="__main_body">
                                <form method='post' class='row'>
                                    <div class='mb-20 col-lg-6 col-12 pr'>
                                        <label for="_m_">Email Address:</label>
                                        <input type="email" class="form-control" id="_m_" name="email" required />
                                    </div>
                                    <div class='mb-20 col-lg-6 col-12 pl'>
                                        <label for="_p_">Password:</label>
                                        <input type="password" class="form-control" id="_p_" name="password" required />
                                    </div>
                                    <div class='mb-20 col-lg-6 col-12 pr'>
                                        <label for="_n_">Full Name:</label>
                                        <input type="text" class="form-control" id="_n_" name="fullname" required />
                                    </div>
                                    <div class='mb-20 col-lg-6 col-12 pl'>
                                        <label for="_id_">Company:</label>
                                        <input type="text" class="form-control" id="_id_" name="company" required />
                                    </div>
                                    
                                    <input type="hidden" name="__c__">
                                    <div class='mt-10 col-12 text-right'>
                                        <button class="btn btn-primary btn-lg btn-icon icon-left _sbmt_" name='sign-up'>Create Account</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- add content here -->
            </div>
        </div>
        
        </div>
    </div>
    <?php require $file_dir.'layout/general_js.php' ?>
</body>
</html>
<style>
   form.row div.col-12{
       padding:0px;
   }
   form.row div.col-12.pl{
       padding-left:10px !important;
   }
   form.row div.col-12.pr{
       padding-left:15px !important;
   }
</style>