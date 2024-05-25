<?php
    session_start();
    $file_dir = '../../';
    if (isset($_SESSION["loggedIn"]) == true || isset($_SESSION["loggedIn"]) === true) {
        $signedIn = true;
    } else {
        header('Location: '.$file_dir.'auth/sign-in?r=/account/profile');
        exit();
    }
    $message = [];
    include '../../layout/db.php';
    include '../../layout/admin__config.php';
    
    if(isset($_POST["update-user"])){
        $update_name = sanitizePlus($_POST["name"]); 
		$update_phone = sanitizePlus($_POST["phone"]); 
		$update_location = sanitizePlus($_POST["location"]);

        if ($role === 'user') {
            #confirm user
            $ConfirmUserExist = "SELECT * FROM users WHERE company_id = '$company_id' AND u_id = '$userId'";
            $ConfirmedUser = $con->query($ConfirmUserExist);
            if ($ConfirmedUser->num_rows > 0) {
                $found = false;
                $row = $ConfirmedUser->fetch_assoc();
                $company_users = $row['company_users'];

                $company_users = unserialize($company_users);
                $companycount = count($company_users);

                $isInArray = in_array_custom($userMail, $company_users) ? 'found' : 'notfound';
                if($isInArray === 'found'){
                    for ($rowArray = 0; $rowArray < $companycount; $rowArray++) {
                        // echo 'row - '.$row.', email = '.$arrayAll[$row]['email'].', password = '.$arrayAll[$row]['password'];
                        if($arrayAll[$rowArray]['email'] == $email && $arrayAll[$rowArray]['password'] == $password){
                            $found = true;
                            $rowNumber = $rowArray;
                        }
                    }
                    if($found && $found == true){
                        $company_users[$rowNumber]['phone'] = $update_phone;
                        $company_users[$rowNumber]['fullname'] = $update_name;
                        $company_users[$rowNumber]['location'] = $update_location;

                        $company_users = serialize($company_users);

                        #update user
                        $UpdateUser = "UPDATE users SET company_users = '$company_users' WHERE company_id = '$company_id' AND u_id = '$userId'";
                        $UserUpdated = $con->query($UpdateUser);  
                        if ($UserUpdated) {
                            array_push($message, 'User Details Updated Successfully!!');
                            header('refresh:3;url= profile.php');
                        } else {
                            array_push($message, 'Error 502: Error 01!!');
                        }
                        
                    }else{
                        echo 'Error 06!';
                        exit();
                    }          
                }else{
                    echo 'Error 07!';
                    exit();
                }
            }else{
                echo 'Error 08!';
                exit();
            }
        } else if ($role === 'admin'){
            #confirm user
            $ConfirmUserExist = "SELECT * FROM users WHERE company_id = '$company_id' AND u_id = '$userId'";
            $ConfirmedUser = $con->query($ConfirmUserExist);
            if ($ConfirmedUser->num_rows > 0) {
                $user_details = array(
                    'fullname' => $update_name,
                    'phone' => $update_phone,
                    'location' => $update_location, 
                );
                $user_details = serialize($user_details);
                #update user
                $UpdateUser = "UPDATE users SET user_details = '$user_details' WHERE company_id = '$company_id' AND u_id = '$userId'";
                $UserUpdated = $con->query($UpdateUser);  
                if ($UserUpdated) {
                    array_push($message, 'User Details Updated Successfully!!');
                } else {
                    array_push($message, 'Error 502: Error 02!!');
                }
            }else{
                echo 'Error 09!';
                exit();
            }
        }else{}
    }

    if(isset($_POST["update-company"])){
        $update_company = sanitizePlus($_POST["company"]); 
		$update_companyaddress = sanitizePlus($_POST["companyaddress"]); 
		$update_city = sanitizePlus($_POST["city"]); 
		$update_state = sanitizePlus($_POST["state"]); 
		$update_postcode = sanitizePlus($_POST["postcode"]); 
		$update_country = sanitizePlus($_POST["country"]);

        $ConfirmCompany = "SELECT * FROM users WHERE company_id = '$company_id' AND u_id = '$userId'";
        $CompanyConfirmed = $con->query($ConfirmCompany);
        if ($CompanyConfirmed->num_rows > 0) {
            #update company
            $update_company_details = array(
                'company_name' => $update_company, 
                'company_address' => $update_companyaddress, 
                'company_city' => $update_city, 
                'company_state' => $update_state, 
                'company_postcode' => $update_postcode, 
                'company_country' => $update_country, 
            );
            $update_company_details = serialize($update_company_details);

            $UpdateUser = "UPDATE users SET company_details = '$update_company_details' WHERE company_id = '$company_id' AND u_id = '$userId'";
            $UserUpdated_c = $con->query($UpdateUser);  
            if ($UserUpdated_c) {
                array_push($message, 'Company Details Updated Successfully!!');
            } else {
                array_push($message, 'Error 502: Error 02!!');
            }
        }else{
            echo 'Error 10';
            exit();
        }
    }
    
    if ($role == 'admin') {
        $CheckIfUserExist = "SELECT * FROM users WHERE u_mail = '$userMail' AND u_id = '$userId'";
        $UserExist = $con->query($CheckIfUserExist);
        if ($UserExist->num_rows > 0) {
            $datainfo = $UserExist->fetch_assoc();
            $company_details = $datainfo['company_details'];
            $email = $datainfo['u_mail'];
            $password = $datainfo['u_password'];
            
            $details = $datainfo['user_details'];
            $details = unserialize($details);
            $phone = $details['phone'];
            $name = ucwords($details['fullname']);
            $location = ucwords($details['location']);
        }else{
            echo 'Error 01!';
            exit();
        }
    } else if($role == 'user'){
        $CheckIfUserExist = "SELECT * FROM users WHERE company_id = '$company_id'";
        $UserExist = $con->query($CheckIfUserExist);
        if ($UserExist->num_rows > 0) {
            $found = false;
            $row = $UserExist->fetch_assoc();
            $company_details = $row['company_details'];
            $company_users = $row['company_users'];

            $company_users = unserialize($company_users);

            $isInArray = in_array_custom($userMail, $company_users) ? 'found' : 'notfound';
            if($isInArray === 'found'){
                for ($rowArray = 0; $rowArray < 3; $rowArray++) {
                    // echo 'row - '.$row.', email = '.$arrayAll[$row]['email'].', password = '.$arrayAll[$row]['password'];
                    if($arrayAll[$rowArray]['email'] == $email && $arrayAll[$rowArray]['password'] == $password){
                        $found = true;
                        $rowNumber = $rowArray;
                    }
                }
                if($found && $found == true){
                    $email = $company_users[$rowNumber]['email'];
                    $password = $company_users[$rowNumber]['password'];
                    $phone = $company_users[$rowNumber]['phone'];
                    $name = $company_users[$rowNumber]['fullname'];
                    $location = $company_users[$rowNumber]['location'];
                }else{
                    echo 'Error 05!';
                    exit();
                }          
            }else{
                echo 'Error 06!';
                exit();
            }
        }else{
            echo 'Error 02!';
            exit();
        }
    }else{
        echo 'Error 03!';
        exit();
    }
    
    $companyinfo = unserialize($company_details);
    $company_name = ucwords($companyinfo['company_name']);
    $company_address = ucwords($companyinfo['company_address']);
    $company_city = ucwords($companyinfo['company_city']);
    $company_state = ucwords($companyinfo['company_state']);
    $company_postcode = ucwords($companyinfo['company_postcode']);
    $company_country = ucwords($companyinfo['company_country']);
    
    
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport">
  <title><?php if(isset($_GET['edit'])){echo 'Edit Profile';}else{echo 'Profile';} ?> | <?php echo $siteEndTitle; ?></title>
  <?php require '../../layout/general_css.php' ?>
  <link rel="stylesheet" href="<?php echo $file_dir; ?>assets/css/footer.custom.css">
  <link rel="stylesheet" href="<?php echo $file_dir; ?>assets/css/admin.custom.css">
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
                <div class="card edit">
                    <?php if(isset($_GET['edit']) && $_GET['edit'] !== '' && $_GET['edit'] == 'company' || isset($_GET['edit']) && $_GET['edit'] !== '' && $_GET['edit'] == 'user'){ $edit = sanitizePlus($_GET['edit']); if($edit === 'company'){ ?>
                    <div class="card-header ">
                        <h3 class="d-inline">Edit Company Details</h3>
                        <a class="btn btn-primary btn-icon icon-left header-a" href="profile"><i class="fas fa-arrow-left"></i> Back</a>
                    </div>
                    <div class="card-body edit">
                        <?php include '../../layout/alert.php'; ?>
                        <form method="POST">
                            <div class="form-group">
                                <label>Company Name :</label>
                                <div class="input-group">
                                    <div class="btn input-group-prepend">
                                        <i class="fas fa-archive"></i>
                                    </div>
                                    <input name="company" type="text" class="form-control" placeholder="Enter company name..." required value="<?php if($company_name == ''){}else{echo $company_name;} ?>">
                                </div>
                            </div>
                            <div class="form-group">
                                <label>Company Address :</label>
                                <div class="input-group">
                                    <div class="btn input-group-prepend">
                                        <i class="fas fa-map-marker-alt"></i>
                                    </div>
                                    <input name="companyaddress" type="text" class="form-control" placeholder="Enter company address..."  value="<?php if($company_address == ''){}else{echo $company_address;} ?>">
                                </div>
                            </div>
                            <div class='row section-row'>
                                <div class="form-group col-lg-6 col-12 pp0">
                                    <label>Country :</label>
                                    <div class="input-group">
                                        <div class="btn input-group-prepend">
                                            <i class="fas fa-flag"></i>
                                        </div>
                                        <input name="country" type="text" class="form-control" placeholder="Enter country..." value="<?php if($company_country == ''){}else{echo $company_country;} ?>">
                                    </div>
                                </div>
                                <div class="form-group col-lg-6 col-12 n-">
                                    <label>City :</label>
                                    <div class="input-group">
                                        <div class="btn input-group-prepend">
                                            <i class="fas fa-map-marker-alt"></i>
                                        </div>
                                        <input name="city" type="text" class="form-control" placeholder="Enter company city..."  value="<?php if($company_city == ''){}else{echo $company_city;} ?>">
                                    </div>
                                </div>
                            </div>
                            <div class='row section-row'>
                                <div class="form-group col-lg-6 col-12 pp0">
                                    <label>State/Provnice :</label>
                                    <div class="input-group">
                                        <div class="btn input-group-prepend">
                                            <i class="fas fa-map-marker-alt"></i>
                                        </div>
                                        <input name="state" type="text" class="form-control" placeholder="Enter state/province..."  value="<?php if($company_state == ''){}else{echo $company_state;} ?>">
                                    </div>
                                </div>
                                <div class="form-group col-lg-6 col-12 n-">
                                    <label>Post Code :</label>
                                    <div class="input-group">
                                        <div class="btn input-group-prepend">
                                            <i class="fas fa-at"></i>
                                        </div>
                                        <input name="postcode" type="text" class="form-control" placeholder="Enter company post code..."  value="<?php if($company_postcode == ''){}else{echo $company_postcode;} ?>">
                                    </div>
                                </div>
                            </div>
                            <div class="form-group text-right">
                                <button type="submit" class='btn btn-primary btn-icon icon-right edit' name="update-company">Update Company Details <i class="fas fa-forward"></i></button>
                            </div>
                        </form>
                    </div>
                    <?php }else if($edit === 'user'){ ?>
                    <div class="card-header">
                        <h3 class="d-inline">Edit User Details</h3>
                        <a class="btn btn-primary btn-icon icon-left header-a" href="profile"><i class="fas fa-arrow-left"></i> Back</a>
                    </div>
                    <div class="card-body edit">
                        <?php include '../../layout/alert.php'; ?>
                        <form method="POST">
                            <div class="form-group">
                                <label class="control-label">Email :</label>
                                <div class="input-group">
                                    <div class="btn input-group-prepend">
                                        <i class="fas fa-envelope"></i>
                                    </div>
                                    <input class="form-control" value="<?php echo $email; ?>" disabled>
                                    <div class="btn btn-primary input-group-append">
                                        <i class="fas fa-question"></i>
                                    </div>
                                </div>
                            </div>
                            <div class='row section-row'>
                                <div class="form-group col-lg-7 col-12 pp0">
                                    <label>Full name</label>
                                    <div class="input-group">
                                        <div class="btn input-group-prepend">
                                            <i class="fas fa-user-circle"></i>
                                        </div>
                                        <input name="name" type="text" class="form-control" placeholder="Enter your full name..." value="<?php echo $name; ?>">
                                    </div>
                                </div>
                                <div class="form-group col-lg-5 col-12 n-">
                                    <label>Phone Number</label>
                                    <div class="input-group">
                                        <div class="btn input-group-prepend">
                                            <i class="fas fa-phone"></i>
                                        </div>
                                        <input name="phone" type="text" class="form-control" placeholder="Enter your phone number..."  value="<?php echo $phone; ?>">
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label>Location</label>
                                <div class="input-group">
                                    <div class="btn input-group-prepend">
                                        <i class="fas fa-map-marker-alt"></i>
                                    </div>
                                    <input name="location" type="text" class="form-control" placeholder="Enter your location..."  value="<?php echo $location; ?>">
                                </div>
                            </div>
                            <div class="form-group text-right">
                                <button type="submit" class='btn btn-primary btn-icon icon-right edit' name="update-user">Update User Details <i class="fas fa-forward"></i></button>
                            </div>
                        </form>
                    </div>
                    <?php }?>
                    <?php }else{ ?>
                    <div>
                        <div class="card-header"></div>
                        <div class="card-body">
                            <div class="card-header">
                                <h3 class="subtitle d-inline">User Information</h3>
                                <a class="btn btn-primary btn-icon icon-left header-a" href="?edit=user"><i class="fas fa-pen"></i> Update User Details</a>
                            </div>
                            <div class="card-body">
                                <div class="row section-rows customs">
                                    <div class="user-description col-12 col-lg-6">
                                        <label>User Email Address :</label>
                                        <div class="description-text"><?php echo $email; ?></div>
                                    </div>
                                    <div class="user-description col-12 col-lg-6">
                                        <label>User Full Name :</label>
                                        <div class="description-text"><?php if($name == ''){echo '---';}else{echo $name;} ?></div>
                                    </div>
                                    <div class="user-description col-12 col-lg-6">
                                        <label>User Password :</label>
                                        <div class="description-text">*****</div>
                                    </div>
                                    <div class="user-description col-12 col-lg-6">
                                        <label>User Phone Number :</label>
                                        <div class="description-text"><?php if($phone == ''){echo '---';}else{echo $phone;} ?></div>
                                    </div>
                                    <div class="user-description col-12">
                                        <label>User Address :</label>
                                        <div class="description-text"><?php if($location == ''){echo '---';}else{echo $location;} ?></div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body"></div>
                            <div class="card-header">
                                <h3 class="subtitle d-inline">Company Information</h3>
                                <?php if ($role == 'admin') { ?>
                                <a class="btn btn-primary btn-icon icon-left header-a" href="?edit=company"><i class="fas fa-pen"></i> Update Company Details</a>
                                <?php } ?>
                            </div>
                            <div class="card-body">
                                <div class="row section-rows customs">
                                    <div class="user-description col-12 col-lg-8">
                                        <label>Company Name :</label>
                                        <div class="description-text"><?php if($company_name == ''){echo '---';}else{echo $company_name;} ?></div>
                                    </div>
                                    <div class="user-description col-12 col-lg-4">
                                        <label>Company ID :</label>
                                        <div class="description-text"><?php echo $company_id; ?></div>
                                    </div>
                                    <div class="user-description col-12">
                                        <label>Company Address :</label>
                                        <div class="description-text"><?php if($company_address == ''){echo '---';}else{echo $company_address;} ?></div>
                                    </div>
                                    <div class="user-description col-12 col-lg-6">
                                        <label>Company Country :</label>
                                        <div class="description-text"><?php if($company_country == ''){echo '---';}else{echo $company_country;} ?></div>
                                    </div>
                                    <div class="user-description col-12 col-lg-6">
                                        <label>Company State / Province :</label>
                                        <div class="description-text"><?php if($company_state == ''){echo '---';}else{echo $company_state;} ?></div>
                                    </div>
                                    <div class="user-description col-12 col-lg-6">
                                        <label>Company City :</label>
                                        <div class="description-text"><?php if($company_city == ''){echo '---';}else{echo $company_city;} ?></div>
                                    </div>
                                    <div class="user-description col-12 col-lg-6">
                                        <label>Company Post Code :</label>
                                        <div class="description-text"><?php if($company_postcode == ''){echo '---';}else{echo $company_postcode;} ?></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer"></div>
                    </div>
                    <?php } ?>
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
<style>
    <?php if(isset($_GET['edit'])){ echo '.main-footer{margin-top:-17px;}.card.edit{margin-top:10px;}'; }else{} ?>
    .card{
        padding:10px;
    }
</style>