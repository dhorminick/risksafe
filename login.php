<?php
    require 'layout/db.php';
    require 'layout/config.php';
    require 'layout/variablesandfunctions.php';   

    if (isset($_SESSION["loggedIn"]) == true || isset($_SESSION["loggedIn"]) === true) {
        $signedIn = true;
    } else {}
    $signedIn = false;
    $message = [];

    $loginCounter = false;
    $passwordCounter = false;
    $companyCounter = false;

    if (isset($_POST["sign-in"])) {
        $login = sanitizePlus($_POST["email"]);
        $password = sanitizePlus($_POST["password"]);

        #verify if params have values
        $loginCounter = errorExists($login, $errorCounter);
        $passwordCounter = errorExists($password, $errorCounter);

        if ($loginCounter == true || $passwordCounter == true) {
            array_push($message, "Error 402: Incomplete Parameters!!");
        } else {
            // $password = weirdlyEncode($password);
            $password = md5($password);
    
            $CheckIfUserExist = "SELECT * FROM users WHERE u_mail = '$login' AND u_password = '$password' LIMIT 1";
            $UserExist = $con->query($CheckIfUserExist);
            if ($UserExist->num_rows > 0) {
                $row = $UserExist->fetch_assoc();
                $db_userid = $row['iduser'];
                $db_useremail = $row["u_email"];
                $db_usercomplete = $row["u_complete"];
                $db_userphone = $row["u_phone"];
                $db_userusername = $row["u_name"];
                $db_userpaymentstatus = $row["payment_status"];
                $db_userpaymentduration = $row["payment_duration"];

                $_SESSION["loggedIn"] = true;
                $_SESSION["userId"] = $db_userid;
                $_SESSION["userMail"] = $db_useremail;
                $_SESSION["userPaymentStatus"] = $db_userpaymentstatus;
                $_SESSION["u_datetime"]= $row["u_datetime"];
                $_SESSION["u_expire"]= $row["u_expire"];
                $_SESSION["role"]= 'admin';
                $_SESSION["company_id"]= $row["company_id"];
    
                array_push($message, "Sign In Succesful, Redirecting...");

                if (isset($_GET["redirect"])) {
                    $prevUrl = $_GET["redirect"];
                    header('refresh:3;url= '.$prevUrl);
                }else{
                    header('refresh:3;url= admin/index.php');
                }  
            }else{
                array_push($message, "Error 402: Invalid Credentials!!");
            }
        }
        
    }

    if (isset($_POST["sign-in-user"])) {
        $found = false;
        $auth = sanitizePlus($_POST["auth"]);
        $login = sanitizePlus($_POST["email"]);
        $password = sanitizePlus($_POST["password"]);
        $company = sanitizePlus($_POST["company"]);

        #verify if params have values
        $loginCounter = errorExists($login, $errorCounter);
        $passwordCounter = errorExists($password, $errorCounter);
        $companyCounter = errorExists($company, $errorCounter);

        if ($loginCounter == true || $passwordCounter == true || $companyCounter == true) {
            array_push($message, "Error 402: Incomplete Parameters!!");
        } else {
            if ($auth == 'user') {
                // $password = weirdlyEncode($password);
                $password = md5($password);
                
                $CheckIfCompanyExist = "SELECT * FROM users WHERE company_id = '$company' LIMIT 1";
                $CompanyExist = $con->query($CheckIfCompanyExist);
                if ($CompanyExist->num_rows > 0) {
                    $row = $UserExist->fetch_assoc();
                    $db_useremail = $row["u_email"];
                    $db_usercomplete = $row["u_complete"];
                    $db_userphone = $row["u_phone"];
                    $db_userusername = $row["u_name"];
                    $db_userpaymentstatus = $row["payment_status"];
                    $db_userpaymentduration = $row["payment_duration"];
                    $db_users = $row['company_users'];
    
                    $db_users = unserialize($db_users);
    
                    function in_array_custom($needle, $haystack, $strict = true){
                        foreach ($haystack as $items){
                            if (($strict ? $items === $needle : $items == $needle) || (is_array($items) && in_array_custom($needle, $items, $strict))){
                                return true;
                            }
                        }
    
                        return false;
                    }
    
                    $isInArray = in_array_custom($email, $db_users) ? 'found' : 'notfound';
                    if($isInArray === 'found'){
                        for ($rowArray = 0; $rowArray < 3; $rowArray++) {
                            // echo 'row - '.$row.', email = '.$arrayAll[$row]['email'].', password = '.$arrayAll[$row]['password'];
                            if($arrayAll[$rowArray]['email'] == $email && $arrayAll[$rowArray]['password'] == $password){
                                $found = true;
                                $rowNumber = $rowArray;
                            }
                        }
    
                        if($found && $found == true){
                            $_SESSION["loggedIn"] = true;
                            $_SESSION["userId"] = $db_users[$rowNumber]['id'];
                            $_SESSION["userMail"] = $db_users[$rowNumber]['email'];
                            $_SESSION["userPaymentStatus"] = $db_userpaymentstatus;
                            $_SESSION["u_datetime"]= $row["u_datetime"];
                            $_SESSION["u_expire"]= $row["u_expire"];
                            $_SESSION["role"]= 'user';
                            $_SESSION["company_id"]= $row["company_id"];
                
                            array_push($message, "Sign In Succesful, Redirecting...");
            
                            if (isset($_GET["redirect"])) {
                                $prevUrl = $_GET["redirect"];
                                header('refresh:3;url= '.$prevUrl);
                            }else{
                                header('refresh:3;url= admin/index.php');
                            }  
                        }else{
                            array_push($message, "Error 402: Email And Password Does Not Match!!");
                        }          
                    }else{
                        array_push($message, "Error 402: Account Does Not Exist!!");
                    }
    
                }else{
                    array_push($message, "Error 402: Invalid Credentials - Company ID!!");
                }
            } else {
                array_push($message, "Error 402: Invalid Credentials - Auth!!");
            }
        }  
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport">
  <title><?php if (isset($_GET['auth']) && $_GET['auth'] == 'user') {echo 'User Login';}else{echo 'Login';} ?> | <?php echo APP_TITLE; ?></title>
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
                    <div class="col-lg-6 col-12 fnhsgr8">       
                        <?php include 'layout/alert.php'; ?>
                        <?php if (isset($_GET['auth']) && $_GET['auth'] == 'user') { ?>
                        <div class="card">
                            <div class="card-header custom">
                                <h3 class="panel-title fu49zk">RiskSafe - Users Log in</h3>
                            </div>
                            <div class="card-body">
                                <form role="form" method="post">
                                    <fieldset>
                                        <div class="form-group">
                                            <label>Company Id:</label>
                                            <input class="form-control" placeholder="Your Company Id..." name="company" type="text" required >
                                        </div>
                                        <div class="form-group">
                                            <label>Email Address:</label>
                                            <input class="form-control" placeholder="Your RiskSafe E-mail Address..." name="email" type="email" required >
                                        </div>
                                        <div class="form-group">
                                            <div class="d-block">
                                            <label for="password" class="control-label">Password</label>
                                            <label class="float-right">
                                                <a href="auth-forgot-password.html" class="bb">
                                                Forgot Password?
                                                </a>
                                            </label>
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
                                        <div style="text-align: center;display:flex">
                                            <button type="submit" class="btn btn-md btn-primary" style="width: 100%;" name="sign-in-user">Login</button>
                                            <input type="hidden" name="auth" value="user">
                                        </div>                              
                                    </fieldset>
                                </form>
                            </div>
                            <div class="card-footer custom" style="text-align: center;">
                                Don't have an account? <a href="register.php?auth=user" class="bb">Create One</a>
                            </div>
                        </div>  
                        <?php }else{ ?>
                        <div class="card">
                            <div class="card-header custom">
                                <h3 class="panel-title fu49zk">RiskSafe - Admin Log in</h3>
                            </div>
                            <div class="card-body">
                                <form role="form" method="post">
                                    <fieldset>
                                        <div class="form-group">
                                            <label>Email Address:</label>
                                            <input class="form-control" placeholder="Your RiskSafe E-mail Address..." name="email" type="email" required >
                                        </div>
                                        <div class="form-group">
                                            <div class="d-block">
                                            <label for="password" class="control-label">Password</label>
                                            <label class="float-right">
                                                <a href="auth-forgot-password.html" class="bb">
                                                Forgot Password?
                                                </a>
                                            </label>
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
                                        <div style="text-align: center;display:flex">
                                            <button type="submit" class="btn btn-md btn-primary" style="width: 100%;" name="sign-in">Login</button>
                                        </div>                              
                                    </fieldset>
                                </form>
        
                            </div>
                            <div class="card-footer custom" style="text-align: center;">
                                Don't have an account? <a href="register.php" class="bb">Create One</a>
                            </div>
                        </div>   
                        <?php } ?>          
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

