<?php

    require 'layout/db.php';
    require 'layout/config.php';
    require 'layout/variablesandfunctions.php';     
    require 'layout/mail.php';     

    if (isset($_SESSION["loggedIn"]) == true || isset($_SESSION["loggedIn"]) === true) {
        $signedIn = true;
    } else {}
    $signedIn = false;
    $message = [];
    $nameChecker = false;
    $emailChecker = false;
	$passwordChecker = false;
	$companyChecker = false;
    // array_push($message, "Error 402: Incomplete Parameters!!");a:0:{}

    if (isset($_POST['create-account'])) {

        $email = sanitizePlus($_POST["email"]);
        $password = sanitizePlus($_POST["password"]);
        $name = sanitizePlus($_POST["name"]);
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
            $CheckIfUserExist = "SELECT * FROM users WHERE u_mail = '$email' LIMIT 1";
            $UserExist = $con->query($CheckIfUserExist);
            if ($UserExist->num_rows > 0) {
                array_push($message, "Error 402: Email Already Exists!!");
            }else{
                $otp = secure_random_string(20);
                $company_id = secure_random_string(10);
                $company_id = strtoupper($company_id);
                $confirmation_link = 'https:risksafe.co/auth?e='.weirdlyEncode($email).'&auth='.$otp;
                #mail
                    // $mail = new PHPMailer();
                    // //Enable SMTP debugging
                    // $mail->SMTPDebug = 0;
                    
                    // $mail->isSMTP();
                    // $mail->Host = 'smtp.gmail.com';
                    // $mail->SMTPAuth = true;
                    // $mail->Username = 'dev3.bdpl@gmail.com';
                    // $mail->Password = 'binarydata000';
                    // $mail->SMTPSecure = 'tls';
                    // $mail->Port = 587;
                    // $mail->setFrom('dev3.bdpl@gmail.com');
                    // $mail->addAddress($email);
        
                    // $mail->isHTML(true);
                    // $mail->Subject = 'RiskSafe - Confirm Account One-Time Password (OTP)';
                    // $mail->Body = 'Your One-Time Confirmation Link is:' . $confirmation_link;
        
        
                    // if ($mail->send()) {
                    //     #add user
                    //     $createNewUser = "INSERT INTO users (`iduser`, `superuserid`, `u_mail`, `u_password`, `u_name`, `u_phone`, `u_location`, `c_company`, `c_address`, `c_city`, `c_state`, `c_postcode`, `c_country`, `u_complete`, `u_otp`, `u_datetime`, `u_expire`, `role`,`user_loginstatus`, `u_id`, `company_users`, `company_id`)
                    //       VALUES (NULL, 0, '$email', '$password', '$name', '', '', '$company', '', '', '', '', '', '0', '$otp', '$datetime', '$expire', 'client', 0, '$u_id', 'a:0:{}', `$company_id`)";
                    //     $userCreated = $con->query($createNewUser);
                    //     if ($userCreated) {
                    //         $createContext = "INSERT INTO `as_context`(`idcontext`, `cx_user`, `cx_objectives`, `cx_processes`, `cx_products`, `cx_projects`, `cx_systems`, `cx_relation`, `cx_internallosses`, `cx_externallosses`, `cx_competitors`, `cx_environment`, `cx_regulatory`) VALUES (Null,'$u_id','','','','','','','','','','','')";
                    //         $contextCreated = $con->query($createContext);
                    //         if ($contextCreated) {}else{
                    //             array_push($message, "Error 502: Server Error!! Contact Our Support Team For More Info.");
                    //         }
                    //         array_push($message, 'Account Details Registered Successfully, Login To "'.$email.'" To Authenticate The Account!!');
                    //     }else{
                    //         array_push($message, "Error 502: Server Error!! Contact Our Support Team For More Info.");
                    //     }
                    // } else {
                    //     array_push($message, "Error 502: Error Sending OTP!! ".$mail->ErrorInfo);
                    // };
                #mail
                $mailSubject = 'RiskSafe - Confirm Account One-Time Password (OTP)';
                $mailRecipient = $email;
                $mailSender = $signUpSender;
                $mailBody = 'Your One-Time Confirmation Link is:' . $confirmation_link;
                $mail = mailUser($mailSubject, $mailBody, $mailRecipient, $mailSender);
                if ($mail['sent'] == true) {
                    #add user
                    $createNewUser = "INSERT INTO users (`iduser`, `superuserid`, `u_mail`, `u_password`, `u_name`, `u_phone`, `u_location`, `c_company`, `c_address`, `c_city`, `c_state`, `c_postcode`, `c_country`, `u_complete`, `u_otp`, `u_datetime`, `u_expire`, `role`,`user_loginstatus`, `u_id`, `company_users`, `company_id`)
                      VALUES (NULL, 0, '$email', '$password', '$name', '', '', '$company', '', '', '', '', '', '0', '$otp', '$datetime', '$expire', 'client', 0, '$u_id', 'a:0:{}', `$company_id`)";
                    $userCreated = $con->query($createNewUser);
                    if ($userCreated) {
                        $createContext = "INSERT INTO `as_context`(`idcontext`, `cx_user`, `cx_objectives`, `cx_processes`, `cx_products`, `cx_projects`, `cx_systems`, `cx_relation`, `cx_internallosses`, `cx_externallosses`, `cx_competitors`, `cx_environment`, `cx_regulatory`) VALUES (Null,'$u_id','','','','','','','','','','','')";
                        $contextCreated = $con->query($createContext);
                        if ($contextCreated) {}else{
                            array_push($message, "Error 502: Server Error!! Contact Our Support Team For More Info.");
                        }
                        array_push($message, 'Account Details Registered Successfully, Login To "'.$email.'" To Authenticate The Account!!');
                    }else{
                        array_push($message, "Error 502: Server Error!! Contact Our Support Team For More Info.");
                    }
                } else {
                    array_push($message, "Error 502: Error Sending OTP!! ".$mail['error']);
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
                <div class="row firoiz">
                    <div class="col-lg-8 col-12 fnhsgr8">       
                        <?php include 'layout/alert.php'; ?>
                        <div class="card" style="padding:10px;">
                            <div class="card-header custom">
                                <h3 class="panel-title fu49zk card-header-h">RiskSafe - Create Account</h3>
                            </div>
                            <div class="card-body">
                                <form role="form" method="post">
                                    <fieldset>
                                        <div class="form-group">
                                            <label>*Email Address:</label>
                                            <input class="form-control" placeholder="Valid E-mail Address..." name="email" id="username" type="email" required >
                                        </div>
                                        <div class="row">
                                        <div class="form-group col-lg-6 col-12">
                                            <label>Full Name:</label>
                                            <input class="form-control" placeholder="Your Full Name..." name="fullname" type="text" required>
                                        </div>
                                        <div class="form-group col-lg-6 col-12">
                                            <div class="d-block">
                                            <label for="password" class="control-label">Password</label>
                                            </div>
                                            <div class="input-group">
                                                <input type="password" class="form-control" placeholder="Your RiskSafe Password..." name="password" type="password" id="password" required>
                                                <div class="input-group-append">
                                                    <div class="input-group-text fyeviu" style="cursor:pointer;">
                                                        <i class="fa fa-eye"></i>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        </div>
                                        <div class="form-group">
                                            <label>Company :</label>
                                            <input class="form-control" placeholder="Company..." name="company" type="text">
                                        </div>
                                        <div style="text-align: center;display:flex">
                                            <button type="submit" class="btn btn-md btn-primary btn_signup" style="width: 100%;" name="create-account">Create Your Account</button>
                                        </div>                              
                                    </fieldset>
                                </form>
                            </div>
                            <div class="card-footer custom" style="text-align: center;">
                               Already Have An Account? <a href="login.php" class="bb">Sign In</a>
                            </div>
                        </div>             
                    </div>
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
    </style>
</body>
</html>

