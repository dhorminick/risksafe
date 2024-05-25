<?php
    session_start();
    $file_dir = '../../';
    if (isset($_SESSION["loggedIn"]) == true || isset($_SESSION["loggedIn"]) === true) {
        $signedIn = true;
    } else {
        header('Location: '.$file_dir.'auth/sign-in?r=/account/users');
        exit();
    }
    $message = [];
    $expired = false;
    $payment_countdown = false;
    include '../../layout/db.php';
    include '../../layout/admin__config.php';
    
    #$database_id = $_SESSION["userDatabaseId"];
    $CheckIfUserExist = "SELECT * FROM users WHERE company_id = '$company_id' LIMIT 1";
    $UserExist = $con->query($CheckIfUserExist);
    if ($UserExist->num_rows > 0) {
        $datainfo = $UserExist->fetch_assoc();
        $company_users_main = $datainfo['company_users'];

        $company_users_main = unserialize($company_users_main);
        $companycount_main = count($company_users_main);
        
    }else{
        echo 'Error 01!';
        exit();
    }

    $d_phone = '';
    $d_name = '';
    $d_location = '';
    $d_email = '';
    $iderror = false;

    if(isset($_GET['edit']) && $_GET['edit'] !== '' && $_GET['edit'] == 'user'){
        $id = sanitizePlus($_GET['id']);
        $id = strtolower($id);
        #confirm user
        $ConfirmUserExist = "SELECT * FROM users WHERE company_id = '$company_id' AND u_id = '$userId'";
        $ConfirmedUser = $con->query($ConfirmUserExist);
        if ($ConfirmedUser->num_rows > 0) {
            $found = false;
            $row = $ConfirmedUser->fetch_assoc();
            $company_users = $row['company_users'];

            $company_users = unserialize($company_users);
            $companycount = count($company_users);

            $isInArray = in_array_custom($id, $company_users) ? 'found' : 'notfound';
            if($isInArray === 'found'){
                for ($rowArray = 0; $rowArray < $companycount; $rowArray++) {
                    if($company_users[$rowArray]['id'] == $id){
                        $found = true;
                        $rowNumber = $rowArray;
                    }
                }
                if($found && $found == true){
                    $d_phone = $company_users[$rowNumber]['phone'];
                    $d_name = $company_users[$rowNumber]['fullname'];
                    $d_location = $company_users[$rowNumber]['location'];
                    $d_email = $company_users[$rowNumber]['email'];
                    $d_role = $company_users[$rowNumber]['role'];
                }else{
                    // array_push($message, 'Error 402: User Not Found!!');
                    $iderror = true;
                    $error_message = 'Error 402: User Not Found!!'; 
                }          
            }else{
                // array_push($message, "Error 402: User Doesn't Exist!");
                $iderror = true;
                $error_message = "Error 402: User Doesn't Exist!!"; 
            }
        }else{
            echo 'Error 08!';
            exit();
        }

        #only edit details if still under the get request
        if (isset($_POST['update-user'])) {
            $update_name = sanitizePlus($_POST["name"]); 
            $update_phone = sanitizePlus($_POST["phone"]); 
            $update_location = sanitizePlus($_POST["location"]);
            $update_role = sanitizePlus($_POST["role"]);

            $company_users[$rowNumber]['phone'] = $update_phone;
            $company_users[$rowNumber]['fullname'] = $update_name;
            $company_users[$rowNumber]['location'] = $update_location;
            $company_users[$rowNumber]['role'] = $update_role;
    
            $company_users = serialize($company_users);
    
            #update user
            $UpdateUser = "UPDATE users SET company_users = '$company_users' WHERE company_id = '$company_id' AND u_id = '$userId'";
            $UserUpdated = $con->query($UpdateUser);  
            if ($UserUpdated) {
                array_push($message, 'User Details Updated Successfully!!');
                header('refresh:2;url= users');
            } else {
                array_push($message, 'Error 502: Error 01!!');
            }
        }
    }

    if(isset($_GET['add']) && $_GET['add'] !== '' && $_GET['add'] == 'user'){
        #only edit details if still under the get request
        if (isset($_POST['add-user'])) {
            $add_id = secure_random_string(15);
            $add_email = sanitizePlus($_POST['add-email']);
            $add_role = sanitizePlus($_POST['add-role']);
            $add_password = sanitizePlus($_POST['add-password']);
            $add_password = weirdlyEncode($add_password);
            $add_otp = secure_random_string(40);
            #since its added by the superadmin, confirmed email address = true
            $add_confirmed  = 'true';
            $add_phone = sanitizePlus($_POST['add-phone']);
            $add_fullname = sanitizePlus($_POST['add-name']);
            $add_location = sanitizePlus($_POST['add-location']);

            $ConfirmUserExist_Add = "SELECT * FROM users WHERE company_id = '$company_id' AND u_id = '$userId'";
            $ConfirmedUser_Main = $con->query($ConfirmUserExist_Add);
            if ($ConfirmedUser_Main->num_rows > 0) {
                $found = false;
                $row = $ConfirmedUser_Main->fetch_assoc();
                $company_users = $row['company_users'];

                $company_users = unserialize($company_users);
                $companycount = count($company_users);

                #incase more payment plans gets created
                switch ($user_payment_plan) {
                    case 'basic':
                        $maxusers = 20;
                        $planNam = 'Basic';
                        break;
                    
                    default:
                        $maxusers = 20;
                        $planNam = 'Basic';
                        break;
                }

                if ($companycount < $maxusers) {
                    $isInArray = in_array_custom($add_email, $company_users) ? 'found' : 'notfound';
                    if($isInArray === 'found'){
                        for ($rowArray = 0; $rowArray < $companycount; $rowArray++) {
                            if($arrayAll[$rowArray]['id'] == $id){
                                $found = true;
                                $rowNumber = $rowArray;
                            }
                        }
                        if($found && $found == true){
                            array_push($message, 'Error 402: User Email Address Already Registered!!');
                        }else{
                        }          
                    }else{
                        $userDetails = array(array(
                            'id' => $add_id,
                            'email' => $add_email,
                            'password' => $add_password,
                            'otp' => $add_otp,
                            'confirmed' => $add_confirmed,
                            'phone' => $add_phone,
                            'fullname' => $add_fullname,
                            'role' => $add_role,
                            'location' => $add_location
                        ));
                        
                        $company_new_users = array_merge($company_users, $userDetails);
                        $company_new_users = serialize($company_new_users);
                        #update user
                        $UpdateUser = "UPDATE users SET company_users = '$company_new_users' WHERE company_id = '$company_id' AND u_id = '$userId'";
                        $UserUpdated = $con->query($UpdateUser);  
                        if ($UserUpdated) {
                            array_push($message, 'User Created Successfully!!');
                            header('refresh:2;url= users');
                        } else {
                            array_push($message, 'Error 502: Error 02!!');
                        }
                    }
                } else {
                    array_push($message, "Error 402: You Have Exceeded The Maximum Amount Of User For Your RiskSafe Payment Plan - ".$planName.", Upgrade To Create More Users!!");
                }
                
            }else{
                echo 'Error 08!';
                exit();
            }
        }
    }

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport">
  <title>Company Registered Users | <?php echo $siteEndTitle; ?></title>
  <?php require '../../layout/general_css.php' ?>
  <link rel="stylesheet" href="<?php echo $file_dir; ?>assets/css/admin.custom.css">
  <link rel="stylesheet" href="<?php echo $file_dir; ?>assets/bundles/prism/prism.css">
  <link rel="stylesheet" href="<?php echo $file_dir; ?>assets/bundles/izitoast/css/iziToast.min.css">
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
                <?php if(isset($_GET['edit']) && $_GET['edit'] !== '' && $_GET['edit'] == 'user'){ ?>
                <div class="card">
                    <div class="card-header edit">
                        <h3 class="card-header-h">Edit User Details</h3>
                    </div>
                    <div class="card-body edit">
                        <?php include '../../layout/alert.php'; ?>
                        <?php if($iderror == true){ ?>
                        <div style="width:100%;text-align:center;margin:20px 0px;">
                            <div class="errormessage" style="font-size: 16px;font-weight:bold;"><?php echo $error_message; ?></div>
                        </div>
                        <?php }else{ ?>
                        <form method="POST">
                            <div class="form-group">
                                <label class="control-label">Email :</label>
                                <div class="input-group">
                                    <div class="btn input-group-prepend">
                                        <i class="fas fa-envelope"></i>
                                    </div>
                                    <input class="form-control" value="<?php echo $d_email; ?>" disabled>
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
                                        <input name="name" type="text" class="form-control" placeholder="Enter your full name..." value="<?php echo $d_name; ?>">
                                    </div>
                                </div>
                                <div class="form-group col-lg-5 col-12 n-">
                                    <label>Phone Number</label>
                                    <div class="input-group">
                                        <div class="btn input-group-prepend">
                                            <i class="fas fa-phone"></i>
                                        </div>
                                        <input name="phone" type="text" class="form-control" placeholder="Enter your phone number..."  value="<?php echo $d_phone; ?>">
                                    </div>
                                </div>
                            </div>
                            <div class='row section-row'>
                                <div class="form-group col-lg-8 col-12 pp0">
                                    <label>Location</label>
                                    <div class="input-group">
                                        <div class="btn input-group-prepend">
                                            <i class="fas fa-map-marker-alt"></i>
                                        </div>
                                        <input name="location" type="text" class="form-control" placeholder="Enter your location..."  value="<?php echo $d_location; ?>">
                                    </div>
                                </div>
                                <div class="form-group col-lg-4 col-12 n-">
                                    <label>Role</label>
                                    <div class="input-group">
                                        <div class="btn input-group-prepend">
                                            <i class="fas fa-users-cog"></i>
                                        </div>
                                        <select class="form-control" name='role' required>
                                            <option value='user' <?php if($d_role == 'user'){echo 'selected'; } ?>>Basic User</option>
                                            <option value='read-only' <?php if($d_role == 'read-only'){echo 'selected'; } ?>>Read Only User</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group text-right">
                                <button type="submit" class='btn btn-primary btn-icon icon-right edit' name="update-user">Update User Details <i class="fas fa-forward"></i></button>
                            </div>
                        </form>
                        <?php } ?>
                    </div>
                </div>
                <?php }else{ ?>
                <?php if(isset($_GET['add']) && $_GET['add'] !== '' && $_GET['add'] == 'user'){ ?>
                <div class="card">
                    <div class="card-header edit">
                        <h3 class="card-header-h">Create User <span class="text-small-custom">( <?php echo $companycount_main + 1; ?> / <?php echo $usermaxusers;?>)</span></h3>
                    </div>
                    <div class="card-body edit">
                        <?php include '../../layout/alert.php'; ?>
                        <div class="note"><strong>NOTE:</strong> Your RiskSafe Payment Plan - <?php echo $userplanName;?> - Only Supports - <?php echo $usermaxusers;?> - Maximum Users.</div>
                        <form method="POST">
                            <div class='row section-row'>
                                <div class="form-group col-lg-6 col-12 pp0">
                                    <label class="control-label">Email :</label>
                                    <div class="input-group">
                                        <div class="btn input-group-prepend">
                                            <i class="fas fa-envelope"></i>
                                        </div>
                                        <input class="form-control" type="email" name="add-email" placeholder="Enter User Email Address..." autocomplete="new-email" value='' required>
                                        <div class="btn btn-primary input-group-append">
                                            <i class="fas fa-question"></i>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group col-lg-6 col-12 n-">
                                    <label>Password:</label>
                                    <div class="input-group">
                                        <div class="btn input-group-prepend">
                                            <i class="fas fa-lock"></i>
                                        </div>
                                        <input name="add-password" type="password" id="password" class="form-control" placeholder="Enter User Password..." autocomplete="new-password" value='' required>
                                        <div class="input-group-append">
                                            <div class="input-group-text fyeviu" style="cursor:pointer;">
                                                <i class="fa fa-eye"></i>
                                            </div>
                                        </div>
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
                                        <input name="add-name" type="text" class="form-control" placeholder="Enter User Full Name..." required>
                                    </div>
                                </div>
                                <div class="form-group col-lg-5 col-12 n-">
                                    <label>Phone Number</label>
                                    <div class="input-group">
                                        <div class="btn input-group-prepend">
                                            <i class="fas fa-phone"></i>
                                        </div>
                                        <input name="add-phone" type="text" class="form-control" placeholder="Enter User Phone Number..." required>
                                    </div>
                                </div>
                            </div>
                            <div class='row section-row'>
                                <div class="form-group col-lg-8 col-12 pp0">
                                    <label>Location</label>
                                    <div class="input-group">
                                        <div class="btn input-group-prepend">
                                            <i class="fas fa-map-marker-alt"></i>
                                        </div>
                                        <input name="add-location" type="text" class="form-control" placeholder="Enter User location...">
                                    </div>
                                </div>
                                <div class="form-group col-lg-4 col-12 n-">
                                    <label>Role</label>
                                    <div class="input-group">
                                        <div class="btn input-group-prepend">
                                            <i class="fas fa-users-cog"></i>
                                        </div>
                                        <select class="form-control" name='add-role' required>
                                            <option value='user'>Basic User</option>
                                            <option value='read-only'>Read Only User</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group text-right">
                                <button type="submit" class='btn btn-primary btn-icon icon-right edit' name="add-user">Create User <i class="fas fa-forward"></i></button>
                            </div>
                        </form>
                    </div>
                </div>
                <?php }else{ ?>
                <div class="card">
                    <div class="card-body">
                        <?php include '../../layout/alert.php'; ?>
                        <div class="card-header">
                            <h3 class="d-inline center-sm">Company Registered Users</h3>
                            <?php if($role == 'admin') { ?>
                            <div class="header-a hide-sm"><a href="?add=user" class="btn btn-primary btn-icon icon-left"><i class="fas fa-plus-circle"></i> Add User</a></div>
                            <?php }else{ ?>
                            <div class="header-a hide-sm"><button class="btn btn-primary btn-icon icon-left notAllowed" data-toggle="modal" data-target="#notAllowed"><i class="fas fa-plus-circle"></i> Add User</button></div>
                            <?php } ?>
                        </div>
                        <div class="card-body" id="users">
                            <?php 
                                $GetPrevPayment = "SELECT * FROM users WHERE company_id = '$company_id' LIMIT 10";
                                $PrevPayment = $con->query($GetPrevPayment);
                                if ($PrevPayment->num_rows > 0) {
                                    $p_row = $PrevPayment->fetch_assoc();
                                    $detailss = $p_row['company_users'];
                                    $details = unserialize($detailss);
                            ?>
                            <table class="payment-data">
                                <tr>
                                    <th style="width: 5%;">S/N</th>
                                    <th>Email</th>
                                    <th>Full Name</th>
                                    <th>Role</th>
                                    <th style="width: 20%;">Action</th>
                                </tr>
                            <?php if ($detailss === 'a:0:{}') { ?> 
                            </table> <div class="empty-table">No User Registered Yet!!</div> 
                            <?php }else{ ?>
                            <?php $o = 0; foreach ($details as $datta){$o++; ?>
                                <tr>
                                    <td><?php echo $o; ?></td>
                                    <td><?php echo $datta['email']; ?></td>
                                    <td><?php echo ucwords($datta['fullname']); ?></td>
                                    <td><?php echo ucwords($datta['role']); ?></td>
                                    <td>
                                        <a class="btn btn-primary btn-icon" href="?edit=user&id=<?php echo $datta['id']; ?>"><i class="fas fa-pen"></i></a>
                                        <button data-toggle="modal" data-target="#deleteUser" class="btn btn-primary btn-icon" user='<?php echo strtoupper($datta['id']); ?>' email='<?php echo $datta['email']; ?>' id="delete-users"><i class="fas fa-trash"></i></button>
                                    </td>
                                </tr>
                            <?php } ?>
                            </table> <?php } ?>
                            <?php }else{ ?>
                            <div class="empty-table">No User Registered Yet!!</div>
                            <?php } ?>
                            <?php if($role == 'admin') { ?>
                            <div class="pay-td show-sm"><a href="?add=user" class="btn btn-primary btn-icon icon-left"><i class="fas fa-plus-circle"></i> Add User</a></div>
                            <?php }else{ ?>
                            <div class="pay-td show-sm"><button class="btn btn-primary btn-icon icon-left notAllowed" data-toggle="modal" data-target="#notAllowed"><i class="fas fa-plus-circle"></i> Add User</button></div>
                            <?php } ?>
                            
                        </div>  
                    </div>
                </div>
                <?php } ?>
                <?php } ?>
            </div>
            </section>
        </div>
        <!-- basic modal -->
        <div class="modal fade" id="notAllowed" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
          aria-hidden="true">
          <div class="modal-dialog" role="document">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Access Denied!!</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
              <div class="modal-body">
                Only Admins Are Allowed To Create New Users.
              </div>
            </div>
          </div>
        </div>

        <!-- basic modal -->
        <div class="modal fade" id="deleteUser" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
          aria-hidden="true">
          <div class="modal-dialog" role="document">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Confirm Delete:</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
              <div class="modal-body" style="text-align:center;">
                Are You Sure You Want To Delete User:
                <p id="userEmail" style="width: 100%;text-align:center;"></p>
                <form id="del">
                    <input type="hidden" name="email" id="formEmail" value="">
                    <input type="hidden" name="id" id="formId" value="">
                    <button class="btn btn-primary btn-icon icon-left btn-delete-user"><i class="fas fa-trash"></i> Delete User</button>
                </form>
              </div>
            </div>
          </div>
        </div>
        <?php require '../../layout/footer.php' ?>
        </footer>
        <div class="res"></div>
        </div>
    </div>
    <?php require '../../layout/general_js.php' ?>
    <script src="<?php echo $file_dir; ?>assets/bundles/prism/prism.js"></script>
    <script src="<?php echo $file_dir; ?>assets/bundles/izitoast/js/iziToast.min.js"></script>
    <script>
        $(".fyeviu").on("click", function () {
            var x = document.getElementById("password");
            if (x.type === "password") {
                $(".fyeviu i").attr("class", "fa fa-eye-slash");
                x.type = "text";
            } else {
                $(".fyeviu i").attr("class", "fa fa-eye");
                x.type = "password";
            }
        });
    </script>
</body>
</html>
<style>
    .main-footer {
        margin-top: 25px !important;
    }
    .empty-table{
        margin: 20px 0px !important;
    }
    .pay-td a{
        width: 100%;
    }
    .btn-delete-user{
        margin-top: 10px;
        width: 100%;
    }
    .text-small-custom{
        font-size: 15px !important;
    }
    .note{
        border-left: 7px solid var(--custom-primary);
        background-color: var(--card-border);
        color: black;
        padding: 10px;
        margin: 0px 0px 20px 0px;
        border-radius: 0px 5px 5px 0px;
    }
</style>