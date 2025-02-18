<?php
    session_start();
    $file_dir = '../../';

    if (isset($_SESSION["loggedIn"]) == true || isset($_SESSION["loggedIn"]) === true) {
        $signedIn = true;
    } else {
        header('Location: '.$file_dir.'login?r=/customs/treatments');
        exit();
    }
  
    $message = [];
    include $file_dir.'layout/db.php';
    include $file_dir.'layout/admin__config.php';
    include '../ajax/customs.php';
    
    if(isset($_POST["update-risk"]) && isset($_POST['c__id'])){
                
        $id = sanitizePlus($_POST["c__id"]);
                
        $title = sanitizePlus($_POST["title"]);
        $sub = sanitizePlus($_POST["sub"]);
        $description = sanitizePlus($_POST["description"]);
        $owner = sanitizePlus($_POST["owner"]);
        $industry = sanitizePlus($_POST["industry"]);
                
        $query = "UPDATE as_customrisks SET title = '$title', description = '$description', sub = '$sub', owner = '$owner', industry = '$industry' WHERE risk_id = '$id' AND c_id = '$company_id'";
        $customCreated = $con->query($query);
        if ($customCreated) {
            array_push($message, 'Risk Details Updated Successfully!!');
        }else{
            array_push($message, 'Error 502: Error Updating Risk!!');
        }
    }

    if (isset($_GET['id']) && isset($_GET['id']) !== "") {
        $toDisplay = true;   
        $id = sanitizePlus($_GET['id']);
        $CheckIfCustomExist = "SELECT * FROM as_customrisks WHERE risk_id = '$id' AND c_id = '$company_id'";
        $CustomExist = $con->query($CheckIfCustomExist);
        if ($CustomExist->num_rows > 0) {	
            $aud_exist = true;
            $info = $CustomExist->fetch_assoc();
        }else{
          $aud_exist = false;
        }
    } else {
        $toDisplay = false;
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport">
  <title>Edit Custom Risks | <?php echo $siteEndTitle; ?></title>
  <?php require $file_dir.'layout/general_css.php' ?>
  <link rel="stylesheet" href="<?php echo $file_dir; ?>assets/css/admin.custom.css">
</head>

<body>
    <div class="loader"></div>
    <div id="app">
        <div class="main-wrapper main-wrapper-1">
        <div class="navbar-bg"></div>
        <?php require $file_dir.'layout/header.php' ?>
        <?php require $file_dir.'layout/sidebar_admin.php' ?>
        <!-- Main Content -->
        <div class="main-content">
            <section class="section">
            <div class="section-body">
              <?php if($toDisplay == true){ ?>
              <?php if ($aud_exist == true) { ?>
                <div class="card" style='padding:10px;'>
                    <form role="form" method="post">
                        <div class="card-bodyy">
                            <?php require $file_dir.'layout/alert.php' ?>
                            <div class="card-header">
                                <h3 class="subtitle">Risk Details</h3>
                                <a class="btn btn-primary btn-icon icon-left header-a" href="risks?id=<?php echo $info['risk_id']; ?>"><i class="fas fa-arrow-left"></i> Back</a>
                            </div>
                            <div class="card-body">
                                <div class="row custom-row">
                                    <div class="form-group col-12 col-lg-8">
                                        <label>Risk:</label>
                                        <input name="title" value="<?php echo $info["title"]; ?>" type="text" class="form-control" placeholder="Enter Risk..." required>
                                    </div>
                                
                                    <div class="form-group col-12 col-lg-4">
                                        <label>Industry Type:</label>
                                        <?php echo getIndustries($info['industry'], $con); ?>
                                    </div>
                                </div>
                                
                                <div class="form-group">
                                    <label>Risk Sub Category:</label>
                                    <input name="sub" value="<?php echo $info["sub"]; ?>" type="text" class="form-control" placeholder="Enter Risk Sub Category..." required>
                                </div>
                                

                                <div class="form-group">
                                    <label>Risk Description:</label>
                                    <textarea name="description" class="form-control" style='white-space:pre-wrap;' placeholder="Enter Risk Description">
                                        <?php echo $info["description"]; ?>
                                    </textarea>
                                </div>
                                
                                <div class="row custom-row">
                                    <!-- <div class="form-group col-12 col-lg-8">
                                        <label>Control Actions:</label>
                                    </div> -->
                                
                                    <div class="form-group col-12">
                                        <label>Risk Owner:</label>
                                        <input name="owner" value="<?php echo $info["owner"]; ?>" type="text" class="form-control" placeholder="Enter Risk Owner..." required>
                                    </div>
                                </div>
                                
                                <input type='hidden' name='c__id' value='<?php echo $info['risk_id']; ?>' />
                            </div>
                            <div class="card-body" style="margin-top: -35px !important;">
                                <div class="form-group text-right">
                                    <button type="submit" class="btn btn-md btn-primary" name="update-risk">Update Risk Details</button>
                                    <button type="reset" class="btn btn-md btn-warning" id="btn_cancel">Cancel</button>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer"></div>
                    </form>
                </div>
                <style>.main-footer{margin-top:0px;}</style>
                <?php }else{ ?>
                <div class="card">
                    <div class="card-body" style="display:flex;justify-content:center;align-items:center;min-height:500px;">
                        <div style="width:100%;min-height:400px;display:flex;justify-content:center;align-items:center;">
                            <div style="text-align: center;"> 
                                 <h3>Empty Data!!</h3>
                                 Custom Treatment Doesn't Exist!!,
                                 <p><a href="new-risk" class="btn btn-primary btn-icon icon-left mt-2"><i class="fas fa-plus"></i> Create New Custom Risk</a></p>
                             </div>
                         </div>
                    </div>
                </div>
                <style>.main-footer{margin-top:0px;}</style>
                <?php } ?>
                <?php }else{ ?>
                <div class="card">
                    <div class="card-body" style="display:flex;justify-content:center;align-items:center;min-height:500px;">
                        <div style="width:100%;min-height:400px;display:flex;justify-content:center;align-items:center;">
                            <div style="text-align: center;"> 
                                 <h3>Empty Data!!</h3>
                                 Missing Parameters,
                                 <p><a href="risks" class="btn btn-primary btn-icon icon-left mt-2"><i class="fas fa-arrow-left"></i> Back</a></p>
                             </div>
                         </div>
                    </div>
                </div>
                <style>.main-footer{margin-top:0px;}</style>
                <?php } ?>
            </div>
            </section>
        </div>
        <?php require $file_dir.'layout/footer.php' ?>
        </footer>
        </div>
    </div>
    <?php require $file_dir.'layout/general_js.php' ?>
    <style>
        textarea{
            min-height: 120px !important;
        }
    </style>
</body>
</html>