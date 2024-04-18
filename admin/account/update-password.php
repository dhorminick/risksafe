<?php
    session_start();
    $file_dir = '../../';
    if (isset($_SESSION["loggedIn"]) == true || isset($_SESSION["loggedIn"]) === true) {
        $signedIn = true;
    } else {
        header('Location: .'.$file_dir.'login?r=/account/profile');
        exit();
    }
    $message = [];
    include '../../layout/db.php';
    include '../../layout/admin_config.php';

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

                if ($db_usercomplete == 'true') {
                    $_SESSION["loggedIn"] = true;
                    $_SESSION["userId"] = $db_userid;
                    $_SESSION["userDatabaseId"] = $db_databaseid;
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
                        // header('Location: '.$prevUrl);
                    }else{
                        header('refresh:3;url= admin/index.php');
                        // header('Location: admin/index.php');
                    }  
                } else {
                    array_push($message, 'Error 402: Account Not Authenticated, Log In To "'.$db_useremail.'" To Activate Your RiskSafe Account!!');
                }
            }else{
                array_push($message, "Error 402: Invalid Credentials!!");
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
  <?php require '../../layout/general_css.php' ?>
  <link rel="stylesheet" href="<?php echo $file_dir; ?>assets/css/footer.custom.css">
</head>
<div id="totalHeight" style="position: absolute;width:100%;height:100%;"></div>
<body>
    <div class="loader"></div>
    <div id="app">
        <div class="main-wrapper main-wrapper-1">
        <div class="navbar-bg"></div>
        <?php require '../../layout/header.php' ?>
        <?php require '../../layout/sidebar_admin.php' ?>
        <?php #require 'layout/sidebar.php' ?>
        <!-- Main Content -->
        <div class="main-content">
            <section class="section">
            <div class="section-body">
                <div class="row firoiz">
                    <div class="card">
                        <div class="card-header custom">
                            <h3 class="panel-title card-header-h fu49zk">RiskSafe - Reset Password</h3>
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
                                            <a href="recover.php" class="bb">
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
                    </div>   
                </div>
            </div>
            </section>
        </div>
        <?php require '../../layout/user_footer.php' ?>
        </footer>
        </div>
    </div>
    <?php require '../../layout/general_js.php' ?>
    <script src="../../assets/js/auth.js"></script>
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

