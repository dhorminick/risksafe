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
    
    $loginCounter = false;
    $passwordCounter = false;
    $companyCounter = false;

    if (isset($_POST["sign-in"])) {
        
        if($signedIn == true){
            array_push($message, "User Already Signed In!!");
        }else{
            $login = sanitizePlus($_POST["email"]);
            $password = sanitizePlus($_POST["password"]);
            $__c__ = sanitizePlus($_POST["__c__"]);
        
            if($__c__ == '' || $__c__ == null){
                #verify if params have values
                $loginCounter = errorExists($login, $loginCounter);
                $passwordCounter = errorExists($password, $passwordCounter);
        
                if ($loginCounter == true || $passwordCounter == true) {
                    array_push($message, "Error 402: Incomplete Parameters!!");
                } else {
                    $password = weirdlyEncode($password);
            
                    $CheckIfUserExist = "SELECT * FROM users WHERE u_mail = '$login' AND u_password = '$password' LIMIT 1";
                    $UserExist = $con->query($CheckIfUserExist);
                    if ($UserExist->num_rows > 0) {
                        $row = $UserExist->fetch_assoc();
                        $db_userid = $row['u_id'];
                        $db_databaseid = $row['iduser'];
                        $db_useremail = $row["u_mail"];
                        $db_usercomplete = $row["u_complete"];
                        $db_userphone = $row["u_phone"];
                        $db_userusername = $row["u_name"];
                        $db_userpaymentstatus = $row["payment_status"];
                        $db_userpaymentduration = $row["payment_duration"];
                        $company_mail = $row["u_mail"];
        
                        if ($db_usercomplete == 'true') {
                            
                            $_SESSION["loggedIn"] = true;
                            $_SESSION["userId"] = $db_userid;
                            $_SESSION["userDatabaseId"] = $db_databaseid;
                            $_SESSION["userMail"] = $db_useremail;
                            $_SESSION["userPaymentStatus"] = $db_userpaymentstatus;
                            $_SESSION["u_datetime"]= $row["u_datetime"];
                            $_SESSION["u_expire"]= $row["u_expire"];
                            $_SESSION["role"]= 'admin';
                            $_SESSION["u_name"]= $row["u_name"];
                            $_SESSION["company_id"]= $row["company_id"];
                            $_SESSION["company_name"]= $row["c_company"];
                            $_SESSION['admin_mail'] = $company_mail;
                            $_SESSION['risk_industry'] = $row['risk_industry'];
                
                            array_push($message, "Sign In Succesfully!!");
            
                            if (isset($_GET["r"])) {
                                $prevUrl = $_GET["r"];
                                header('refresh:2;url= /admin'.$prevUrl);
                            }else{
                                header('refresh:2;url= /admin/');
                            }
                            
                        } else {
                            array_push($message, 'Error 402: Account Not Authenticated, Log In To "'.$db_useremail.'" To Activate Your RiskSafe Account!!');
                        }
                    }else{
                        array_push($message, "Error 402: Invalid Credentials!!");
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
  <title>Sign In | <?php echo $siteEndTitle; ?></title>
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
                                <h4>Don't Have An Account?</h4>
                            </div>
                            <div class='__sub_sub'>
                                Navigate uncertainties with confidence! Sign up today and unlock tailored risk assessments that empower you to safeguard your future.
                            </div>
                            <div class='_sub_link'>
                                <a href='sign-up' class='btn btn-primary btn-outline btn-lg btn-large'>Create Account</a>
                            </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-8 col-12 __main__">
                        <div class="_group_main">
                            <div class="__main_text"><h3>Welcome Back!!</h3></div>
                            <div class="__main_caption">Please enter your account details:</div>
                            <div class="__main_body">
                                <form method='post'>
                                    <div class='mb-20'>
                                        <label for="_m_">Email Address:</label>
                                        <input type="text" class="form-control" id="_m_" name="email" />
                                    </div>
                                    <div class='mb-20'>
                                        <div class="d-block">
                                            <label for="_p_">Password:</label>
                                            <label class="float-right">
                                                <a href="reset-password" class="bb">
                                                Forgot Password?
                                                </a>
                                            </label>
                                        </div>
                                        <input type="password" class="form-control" id="_p_" name="password" />
                                        <input type="hidden" name="__c__">
                                    </div>
                                    <div class='mt-10'>
                                        <button class="btn btn-primary btn-lg btn-icon icon-left _sbmt_" name='sign-in'>Sign In To Account</button>
                                    </div>
                                </form>
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