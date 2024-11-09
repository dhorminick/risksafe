<?php
    session_start();
    $file_dir = '../../';

    if (isset($_SESSION["loggedIn"]) == true || isset($_SESSION["loggedIn"]) === true) {
        $signedIn = true;
    } else {
        header('Location: '.$file_dir.'login?r=/customs/controls');
        exit();
    }
  
    $message = [];
    include $file_dir.'layout/db.php';
    include $file_dir.'layout/admin__config.php';
    include '../ajax/customs.php';
    
    if(isset($_POST["update-control"]) && isset($_POST['c__id'])){
                
                $id = sanitizePlus($_POST["c__id"]);
                
                $title = sanitizePlus($_POST["title"]);
                $description = htmlentities(trim($_POST["description"]));
                $effectiveness = sanitizePlus($_POST["effectiveness"]);
                $frequency = sanitizePlus($_POST["frequency"]);
                $category = sanitizePlus($_POST["category"]);
                
                $query = "UPDATE as_customcontrols SET title = '$title', description = '$description', effectiveness = '$effectiveness', frequency = '$frequency', category = '$category' WHERE control_id = '$id' AND c_id = '$company_id'";
                $customCreated = $con->query($query);
                if ($customCreated) {
                    array_push($message, 'Control Details Updated Successfully!!');
                }else{
                  array_push($message, 'Error 502: Error Updating Control!!');
                }
            }
            
    if (isset($_GET['id']) && isset($_GET['id']) !== "") {
        $toDisplay = true;   
        $id = sanitizePlus($_GET['id']);
        $CheckIfCustomExist = "SELECT * FROM as_customcontrols WHERE control_id = '$id' AND c_id = '$company_id'";
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
  <title>Edit Custom Controls | <?php echo $siteEndTitle; ?></title>
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
                                <h3 class="subtitle">Control Details</h3>
                                <a class="btn btn-primary btn-icon icon-left header-a" href="controls?id=<?php echo $info['control_id']; ?>"><i class="fas fa-arrow-left"></i> Back</a>
                            </div>
                            <div class="card-body">
                                <div class="form-group">
                                  <label>Control Category</label>
                                  <select name="category" id="control-type" class="form-control" required>
                                    <option value="0">No Category</option>
                                    <?php echo listTypes($info["category"], $con); ?>
                                  </select>
                                </div>
                                <input type='hidden' name='c__id' value='<?php echo $info['control_id']; ?>' />

                                <div class="form-group">
                                    <label>Control Title:</label>
                                    <input name="title" value="<?php echo $info["title"]; ?>" type="text" class="form-control" placeholder="Enter Control Title..." required>
                                </div>

                                <div class="form-group">
                                    <label>Control Description:</label>
                                    <textarea rows='3' class="form-control" name="description" class="form-control"  placeholder="Enter Control Description">
                                        <?php echo html_entity_decode($info["description"]); ?>
                                    </textarea>
                                </div>

                                <div class="row custom-row">
                                    <div class="form-group col-lg-6 col-12">
                                        <label>Effectiveness</label>
                                        <select name="effectiveness" class="form-control" required>
                                            <option value="1" <?php if ($info['effectiveness'] == 1) echo "selected"; ?>>Effective</option>
                                            <option value="2" <?php if ($info['effectiveness'] == 2) echo "selected"; ?>>InEffective</option>
                                            <option value="3" <?php if ($info['effectiveness'] == 3) echo "selected"; ?>>Unassessed</option>
                                        </select>
                                    </div>
                                    <div class="form-group col-lg-6 col-12">
                                        <label>Frequency</label>
                                        <select class="form-control" name="frequency" required>
                                            <option value="1" <?php if ($info['frequency'] == 1) echo "selected"; ?>>Daily Controls</option>
                                            <option value="2" <?php if ($info['frequency'] == 2) echo "selected"; ?>>Weekly Controls</option>
                                            <option value="3" <?php if ($info['frequency'] == 3) echo "selected"; ?>>Fort-Nightly Controls</option>
                                            <option value="4" <?php if ($info['frequency'] == 4) echo "selected"; ?>>Monthly Controls</option>
                                            <option value="5" <?php if ($info['frequency'] == 5) echo "selected"; ?>>Semi-Annually Controls</option>
                                            <option value="6" <?php if ($info['frequency'] == 6) echo "selected"; ?>>Annually Controls</option>
                                            <option value="7" <?php if ($info['frequency'] == 7) echo "selected"; ?>>As Required</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="form-group text-right">
                                    <button type="submit" class="btn btn-md btn-primary" name="update-control">Update Control Details</button>
                                    <button type="button" class="btn btn-md btn-warning" id="btn_cancel">Cancel</button>
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
                                 Custom Control Doesn't Exist!!,
                                 <p><a href="new-control" class="btn btn-primary btn-icon icon-left mt-2"><i class="fas fa-plus"></i> Create New Control</a></p>
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
                                 <p><a href="controls" class="btn btn-primary btn-icon icon-left mt-2"><i class="fas fa-arrow-left"></i> Back</a></p>
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