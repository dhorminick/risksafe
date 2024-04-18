<?php
    session_start();
    
    // session_start();
    $signedIn = false;
    if (isset($_SESSION["loggedIn"]) == true || isset($_SESSION["loggedIn"]) === true) {
        $signedIn = true;
        header('Location: admin/');
    } else {}
    
    require 'layout/db.php';
    require 'layout/config.php';
    require 'layout/variablesandfunctions.php'; 
    
    $message = [];

    $loginCounter = false;
    $passwordCounter = false;
    $companyCounter = false;

    if (isset($_POST["sign-in"])) {
        $login = sanitizePlus($_POST["email"]);
        $password = sanitizePlus($_POST["password"]);

        #verify if params have values
        $loginCounter = errorExists($login, $loginCounter);
        $passwordCounter = errorExists($password, $passwordCounter);

        if ($loginCounter == true || $passwordCounter == true) {
            array_push($message, "Error 402: Incomplete Parameters!!");
        } else {
            $password = weirdlyEncode($password);
            // $password = md5($password);
    
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
        
                    array_push($message, "Sign In Succesful. You Will Be Redirected In A Moment...");
    
                    // if (isset($_GET["r"])) {
                    //     $prevUrl = $_GET["r"];
                    //     header('refresh:3;url= admin'.$prevUrl);
                    //     // header('Location: '.$prevUrl);
                    // }else{
                    //     header('refresh:3;url= admin/');
                    //     // header('Location: admin/index.php');
                    // }
                    
                    if (isset($_GET["re"])) {
                        $prevUrl = $_GET["re"];
                        header('refresh:3;url= '.$prevUrl);
                        // header('Location: '.$prevUrl);
                    } else{
                        if (isset($_GET["r"])) {
                            $prevUrl = $_GET["r"];
                            header('refresh:3;url= admin'.$prevUrl);
                            // header('Location: '.$prevUrl);
                        }else{
                            header('refresh:3;url= admin/');
                            // header('Location: admin/index.php');
                        }  
                    }
                } else {
                    array_push($message, 'Error 402: Account Not Authenticated, Log In To "'.$db_useremail.'" To Activate Your RiskSafe Account!!');
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
        $loginCounter = errorExists($login, $loginCounter);
        $passwordCounter = errorExists($password, $passwordCounter);
        $companyCounter = errorExists($company, $companyCounter);

        if ($loginCounter == true || $passwordCounter == true || $companyCounter == true) {
            array_push($message, "Error 402: Incomplete Parameters!!");
        } else {
            if ($auth == 'user') {
                $password = weirdlyEncode($password);
                // $password = md5($password);
                $company = strtoupper($company);
                $CheckIfCompanyExist = "SELECT * FROM users WHERE company_id = '$company' LIMIT 1";
                $CompanyExist = $con->query($CheckIfCompanyExist);
                if ($CompanyExist->num_rows > 0) {
                    $row = $CompanyExist->fetch_assoc();
                    
                    $db_useremail = $row["u_mail"];
                    $db_databaseid = $row['iduser'];
                    $db_usercomplete = $row["u_complete"];
                    $db_userphone = $row["u_phone"];
                    $db_userusername = $row["u_name"];
                    $db_userpaymentstatus = $row["payment_status"];
                    $db_userpaymentduration = $row["payment_duration"];
                    $db_users = $row['company_users'];
                    $company_mail = $row['u_mail'];
    
                    $db_users = unserialize($db_users);
                    $db_count = count($db_users);
    
                    
    
                    $isInArray = in_array_custom($login, $db_users) ? 'found' : 'notfound';
                    if($isInArray === 'found'){
                        for ($rowArray = 0; $rowArray < $db_count; $rowArray++) {
                            // echo 'row - '.$row.', email = '.$db_users[$row]['email'].', password = '.$db_users[$row]['password'];
                            if($db_users[$rowArray]['email'] == $login && $db_users[$rowArray]['password'] == $password){
                                $found = true;
                                $rowNumber = $rowArray;
                            }
                        }
    
                        if($found && $found == true){
                            $_SESSION["loggedIn"] = true;
                            $_SESSION["userId"] = $db_users[$rowNumber]['id'];
                            $_SESSION["userDatabaseId"] = $db_databaseid;
                            $_SESSION["userMail"] = $db_users[$rowNumber]['email'];
                            $_SESSION["u_name"] = $db_users[$rowNumber]['fullname'];
                            $_SESSION["userPaymentStatus"] = $db_userpaymentstatus;
                            $_SESSION["u_datetime"]= $row["u_datetime"];
                            $_SESSION["u_expire"]= $row["u_expire"];
                            $_SESSION["role"]= 'user';
                            $_SESSION["company_id"]= $row["company_id"];
                            $_SESSION["company_name"]= $row["c_company"];
                            $_SESSION['admin_mail'] = $company_mail;
                
                            array_push($message, "Sign In Succesful. You Will Be Redirected In A Moment...");
            
                            if (isset($_GET["redirect"])) {
                                $prevUrl = $_GET["redirect"];
                                header('refresh:3;url= '.$prevUrl);
                            }else{
                                header('refresh:3;url= admin/');
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
                    <?php if($signedIn == true){ ?>
                    <div class="col-lg-6 col-12 fnhsgr8" style='margin-top:30px;'>       
                        <div class="card">
                            <div class="card-header custom">
                                <h3 class="panel-title card-header-h fu49zk">RiskSafe - Log in</h3>
                            </div>
                            <div class="card-body">
                                User Already Logged In!!
                            </div>
                            <div class="card-footer custom" style="text-align: center;"><a href="/" class="bb">Home?</a></div>
                        </div>
                    </div>
                    <?php }else{ ?>
                    <div class="col-lg-6 col-12 fnhsgr8" style='margin-top:30px;'>       
                        <?php include 'layout/alert.php'; ?>
                        <?php if (isset($_GET['auth']) && $_GET['auth'] == 'user') { ?>
                        <div class="card">
                            <div class="card-header custom">
                                <h3 class="panel-title card-header-h fu49zk">RiskSafe - Users Log in</h3>
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
                                                <a href="reset-password" class="bb">
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
                                <a href="login" class="bb">Sign In As An Admin</a>
                            </div>
                        </div>  
                        <?php }else{ ?>
                        <div class="card">
                            <div class="card-header custom">
                                <h3 class="panel-title card-header-h fu49zk">RiskSafe - Client Admin Log in</h3>
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
                                                <a href="reset-password" class="bb">
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
                                Don't have an account? <a href="register" class="bb">Create One</a>
                                <div style='margin-top:10px;'><a href="login?auth=user" class="bb">Sign In As A User</a></div>
                            </div>
                        </div>   
                        <?php } ?>          
                    </div>
                    <?php } ?>
                </div>
        	
            </div>
            </section>
        </div>
        <?php #require 'layout/user_footer.php' ?>
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

