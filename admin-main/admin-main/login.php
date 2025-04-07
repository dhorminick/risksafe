<?php
    session_start();
    
    // session_start();
    if (isset($_SESSION["AdminloggedIn"]) == true || isset($_SESSION["AdminloggedIn"]) === true) {
        $signedIn = true;
        header('Location: /admin-main/');
        exit();
    } else {}
    
    set_include_path(dirname(__FILE__)."/../");
    include 'layout/db.php';
    include 'layout/config.php';
    include 'layout/variablesandfunctions.php'; 
    
    $file_dir = '../';
    
    $message = [];

    $loginCounter = false;
    $passwordCounter = false;

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
    
            $CheckIfUserExist = "SELECT * FROM admin_users WHERE email = '$login' AND password = '$password' LIMIT 1";
            $UserExist = $con->query($CheckIfUserExist);
            if ($UserExist->num_rows > 0) {
                $row = $UserExist->fetch_assoc();
                $admin_id = $row['admin_id'];
                
                array_push($message, "Sign In Succesful. You Will Be Redirected In A Moment...");
                $_SESSION["AdminloggedIn"] = true;
                $_SESSION["admin_id"] = $admin_id;
                header('refresh:3;url= /admin-main/');
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
  <title>Admin Login | <?php echo APP_TITLE; ?></title>
  <?php include 'layout/general_css.php' ?>
  <link rel="stylesheet" href="<?php echo $file_dir; ?>assets/css/index.custom.css">
  <link rel="stylesheet" href="<?php echo $file_dir; ?>assets/css/main.css">
</head>
<div id="totalHeight" style="position: absolute;width:100%;height:100%;"></div>
<body>
    <div class="loader"></div>
    <div id="app">
        <div class="main-wrapper main-wrapper-1">
        <div class="navbar-bg"></div>
        <?php include 'layout/header_main.php' ?>
        <?php #include 'layout/sidebar.php' ?>
        <!-- Main Content -->
        <div class="main-content">
            <section class="section">
            <div class="section-body">
                <div class="row firoiz">
                    <div class="card col-lg-7 col-12" style='min-height:400px;margin-top:50px;'>
                            <?php include 'layout/alert.php' ?>
                            <div class="card-header custom">
                                <h3 class="panel-title card-header-h fu49zk">RiskSafe - Admin Log in</h3>
                            </div>
                            <div class="card-body">
                                <form role="form" method="post">
                                    <fieldset>
                                        <div class="form-group">
                                            <label>Email Address:</label>
                                            <input class="form-control" placeholder="..." name="email" type="email" included >
                                        </div>
                                        <div class="form-group">
                                            <div class="d-block">
                                            <label for="password" class="control-label">Password</label>
                                            </div>
                                            <div class="input-group">
                                                <input type="password" class="form-control" placeholder="..." name="password" type="password" id="password" included>
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
        <?php #include 'layout/user_footer.php' ?>
        </footer>
        </div>
    </div>
    <?php include 'layout/general_js.php' ?>
    <script src="<?php echo $file_dir; ?>assets/js/auth.js"></script>
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

