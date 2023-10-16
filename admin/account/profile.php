<?php
    $file_dir = '../../';
    $message = [];
    include '../../layout/db.php';
    include '../../layout/admin_config.php';
    $role = 'admin';
    $company_id = 'ufvbioghsir';

    $CheckIfUserExist = "SELECT * FROM users WHERE u_mail = '$login'";
    $UserExist = $con->query($CheckIfUserExist);
    if ($UserExist->num_rows > 0) {
        $datainfo = $UserExist->fetch_assoc();
    }else{
      $msg = 'no account';
      exit();
    }
    
    if(isset($_POST["update"])){
        $update_email = sanitizePlus($_POST["email"]); 
		$update_password = sanitizePlus($_POST["password"]); 
		$update_name = sanitizePlus($_POST["name"]); 
		$update_phone = sanitizePlus($_POST["phone"]); 
		$update_location = sanitizePlus($_POST["location"]);

        #company
		$update_company = sanitizePlus($_POST["company"]); 
		$update_companyaddress = sanitizePlus($_POST["companyaddress"]); 
		$update_city = sanitizePlus($_POST["city"]); 
		$update_state = sanitizePlus($_POST["state"]); 
		$update_postcode = sanitizePlus($_POST["postcode"]); 
		$update_country = sanitizePlus($_POST["country"]);

        if ($role == 'user') {
            $user = 'test';
            $user_details = array(array(
                'userid' => $user,
                'email' => $email,
                'password' => $password,
                'name' => $name,
                'phone' => $phone,
                'location' => $location              
            ));

            $Update = "SELECT * FROM users WHERE company_id = '$company_id'";
            $UserUpdated = $con->query($Update);
            if ($UserUpdated->num_rows > 0) {
                $row = $UserExist->fetch_assoc();
                $company_users = $row['company_users'];

                $company_users = unserialize($company_users);
            }else{

            }
            
        } else {}
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport">
  <title>Otika - Admin Dashboard Template</title>
  <?php require '../../layout/general_css.php' ?>
  <link rel="stylesheet" href="<?php echo $file_dir; ?>assets/css/footer.custom.css">
</head>

<body>
    <div class="loader"></div>
    <div id="app">
        <div class="main-wrapper main-wrapper-1">
        <div class="navbar-bg"></div>
        <?php require '../../layout/header.php' ?>
        <?php require '../../layout/sidebar_admin.php' ?>
        <!-- Main Content -->
        <div class="main-content">
            <section class="section">
            <div class="section-body">
                <div class="card">
                    <form role="form" class="form" id="form" action="../controller/users.php" method="post">
                        <div class="card-header"></div>
                        <div class="card-body">
                            <div class="card-header"><h3 class="subtitle">User Information</h3></div>
                            <div class="card-body">
                                <div class='row section-row'>
                                    <div class="form-group col-lg-6 col-12 pp0">
                                        <label>E-mail</label>
                                        <input name="email" type="email" maxlength="100" class="form-control" placeholder="Enter e-mail address..."  required value="<?php if(isset($datainfo['u_mail']) && ($datainfo['u_mail']!="")){ echo $datainfo['u_mail'];}else{ echo "";}; ?>">
                                
                                    </div>
                                    <div class="form-group col-lg-6 col-12 n-">
                                        <label>Password</label>
                                        <input name="password" type="password" maxlength="100" class="form-control" placeholder="Enter password..."  required value="<?php echo htmlspecialchars($datainfo["u_password"]);?>">
                            
                                    </div>
                                </div>
                                <div class='row section-row'>
                                    <div class="form-group col-lg-7 col-12 pp0">
                                        <label>Full name</label>
                                        <input name="name" type="text" maxlength="50" class="form-control" placeholder="Enter your full name..."  required value="<?php echo htmlspecialchars($datainfo["u_name"]);?>">
                        
                                    </div>
                                    <div class="form-group col-lg-5 col-12 n-">
                                        <label>Phone Number</label>
                                        <input name="phone" type="text" maxlength="50" class="form-control" placeholder="Enter your phone number..."  value="<?php echo htmlspecialchars($datainfo["u_phone"]);?>">
                                    
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label>Location</label>
                                    <input name="location" type="text" maxlength="50" class="form-control" placeholder="Enter your location..."   value="<?php echo htmlspecialchars($datainfo["u_location"]);?>">
                            
                                </div>
                            </div>
                            <div class="card-body"></div>
                            <div class="card-header"><h3 class="subtitle">Company Information</h3></div>
                            <div class="card-body">
                                <div class="form-group">
                                    <label>Company Name</label>
                                    <input name="company" type="text" maxlength="50" class="form-control" placeholder="Enter company name..." required value="<?php echo htmlspecialchars($datainfo["c_company"]);?>">
                                
                                </div>
                                <div class="form-group">
                                    <label>Company Address</label>
                                    <input name="companyaddress" type="text" maxlength="100" class="form-control" placeholder="Enter company address..."  value="<?php echo htmlspecialchars($datainfo["c_address"]);?>">
                                
                                </div>
                                <div class='row section-row'>
                                    <div class="form-group col-lg-6 col-12 pp0">
                                        <label>Country</label>
                                        <input name="country" type="text" maxlength="50" class="form-control" placeholder="Enter country..." value="<?php echo htmlspecialchars($datainfo["c_country"]);?>">
                                
                                    </div>
                                    <div class="form-group col-lg-6 col-12 n-">
                                        <label>City</label>
                                        <input name="city" type="text" maxlength="50" class="form-control" placeholder="Enter company city..."  value="<?php echo htmlspecialchars($datainfo["c_city"]);?>">
                                    
                                    </div>
                                </div>
                                <div class='row section-row'>
                                    <div class="form-group col-lg-6 col-12 pp0">
                                        <label>State/Provnice</label>
                                        <input name="state" type="text" maxlength="30" class="form-control" placeholder="Enter state/province..."  value="<?php echo htmlspecialchars($datainfo["c_state"]);?>">
                                    
                                    </div>
                                    <div class="form-group col-lg-6 col-12 n-">
                                        <label>Post Code</label>
                                        <input name="postcode" type="text" maxlength="20" class="form-control" placeholder="Enter company post code..."  value="<?php echo htmlspecialchars($datainfo["c_postcode"]);?>">
                                    
                                    </div>
                                </div>
                            </div>
                            <div class="card-body" style="margin-top: -45px !important;">
                                <button type="submit" class="btn btn-md btn-info" name="update">Update information</button>
                                <input name="action" type="hidden" value="profile" />
                                <input name="id" type="hidden" value="<?php echo $datainfo["iduser"];?>" />
                                <input name="email_old" type="hidden" value="<?php echo $datainfo["u_mail"];?>" />
                            </div>
                        </div>
                        <div class="card-footer"></div>
                    </form>
                </div>
            </div>
            </section>
        </div>
        <?php require '../../layout/footer.php' ?>
        </footer>
        </div>
    </div>
    <?php require '../../layout/general_js.php' ?>
</body>
</html>