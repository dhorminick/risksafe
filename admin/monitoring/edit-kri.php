<?php
    session_start();
    $file_dir = '../../';

    if (isset($_SESSION["loggedIn"]) == true || isset($_SESSION["loggedIn"]) === true) {
        $signedIn = true;
    } else {
        header('Location: '.$file_dir.'auth/sign-in?r=/monitoring/kri');
        exit();
    }
  
    $message = [];
    include $file_dir.'layout/db.php';
    include $file_dir.'layout/admin__config.php';

    
    if(isset($_POST["update-kri"]) && isset($_POST['c__id'])){
        $id = sanitizePlus($_POST["c__id"]);
                
                $indicator = sanitizePlus($_POST['indicator']);
        $description = sanitizePlus($_POST['description']);
        $category = sanitizePlus($_POST['category']);
        $owner = sanitizePlus($_POST['owner']);
        $status = sanitizePlus($_POST['status']);
        $frequency = sanitizePlus($_POST['frequency']);
        $threshold = sanitizePlus($_POST['threshold']);
        $current = sanitizePlus($_POST['current']);
        $target = sanitizePlus($_POST['target']);
        $start = sanitizePlus($_POST['start']);
        $trend = sanitizePlus($_POST['trend']);
        $priority = sanitizePlus($_POST['priority']);
        
                 # code...
                    $query = "UPDATE kri SET indicator = '$indicator', description = '$description', category = '$category', owner = '$owner', status = '$status', frequency = '$frequency', threshold = '$threshold', current = '$current', target = '$target', start = '$start', trend = '$trend', priority = '$priority' WHERE k_id = '$id' AND c_id = '$company_id'";
                    if ($con->query($query)) {
                        array_push($message, 'Risk Indicator Updated Successfully!!');
                    } else {
                        array_push($message, 'Error 502: Error Updating KRI Details!!');
                    }
            }
            
    if (isset($_GET['id']) && isset($_GET['id']) !== "") {
        $toDisplay = true;   
        $id = sanitizePlus($_GET['id']);
        $CheckIfAuditExist = "SELECT * FROM kri WHERE k_id = '$id' AND c_id = '$company_id'";
        $AuditExist = $con->query($CheckIfAuditExist);
        if ($AuditExist->num_rows > 0) {	
            $aud_exist = true;
            
            $info = $AuditExist->fetch_assoc();
        
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
  <meta
   content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport">
  <title>Update Key Risk Indicator | <?php echo $siteEndTitle; ?></title>
  <?php require $file_dir.'layout/general_css.php' ?>
  <link rel="stylesheet" href="<?php echo $file_dir; ?>assets/css/footer.custom.css">
  <link rel="stylesheet" href="<?php echo $file_dir; ?>assets/css/admin.custom.css">
</head>

<body>
    <div class="loader"></div>
    <div id="app">
        <div class="main-wrapper main-wrapper-1">
        <div class="navbar-bg"></div>
        <?php require $file_dir.'layout/header.php' ?>
        <?php require $file_dir.'layout/sidebar_admin.php' ?>
        <!-- Main
         Content -->
        <div class="main-content">
            <section class="section">
            <div class="section-body">
                <?php if($toDisplay == true){ ?>
                <?php if ($aud_exist == true) { ?>
                <div>
                  <div class='card'>
                    <?php require $file_dir.'layout/alert.php' ?>
                    <form method="post" action=''>
                        <div class="card-body">
                            <?php require $file_dir.'layout/alert.php' ?>
                            <div class="card-header">
                                <h3 class="d-inline">Update KRI Details</h3>
                                <a class="btn btn-primary btn-icon icon-left header-a" href="treatments"><i class="fas fa-arrow-left"></i> Back</a>
                            </div>
                            <div class="card-body">
                                <input type='hidden' name='c__id' value='<?php echo $info['k_id']; ?>' />
                                <div class="form-group">
                                    <label>Indicator Name:</label>
                                    <input name="indicator" value='<?php echo $info['indicator']; ?>' type="text" class="form-control" placeholder="KRI" required>
                                </div>
                                <div class="form-group">
                                    <label>Description:</label>
                                    <textarea name="description" rows="4" class="form-control" placeholder="KRI Description" required><?php echo $info['description']; ?></textarea>
                                </div>
                                <div class="form-group">
                                    <label>Category:</label>
                                    <input name="category" type="text" value='<?php echo $info['category']; ?>' class="form-control" placeholder="KRI Category" required>
                                </div>
                                
                                
                                <div class="row custom-row">
                                <div class="form-group col-12 col-lg-4">
                                    <label>Owner:</label>
                                    <input name="owner" type="text" class="form-control" value='<?php echo $info['owner']; ?>' placeholder="Owner" required>
                                </div>
                                
                                <div class="form-group col-lg-4 col-12">
                                    <label>Status :</label>
                                    <select name="status" class="form-control" required>
                                        <?php echo _listStatus($info['status']); ?>    
                                    </select> 
                                </div>
                                <div class="form-group col-lg-4 col-12">
                                    <label>Frequency of Monitoring:</label>
                                    <select name="frequency" class="form-control" required>
                                        <?php echo _listFrequencies($info['frequency']); ?>    
                                    </select> 
                                </div>
                                </div>
                                
                                <div class="row custom-row">
                                <div class="form-group col-lg-4 col-12">
                                    <label>Threshold:</label>
                                    <div class='flexbox'>
                                        <input name="threshold" type="number" value='<?php echo $info['threshold']; ?>' class="form-control" min="0" max="100" placeholder="0" required>
                                        <div class='btn'>%</div>
                                    </div>
                                </div>
                                <div class="form-group col-lg-4 col-12">
                                    <label>Current Value:</label>
                                    <div class='flexbox'>
                                        <input name="current" type="number" value='<?php echo $info['current']; ?>' class="form-control" min="0" max="100" placeholder="0" required>
                                        <div class='btn'>%</div>
                                    </div>
                                </div>
                                <div class="form-group col-lg-4 col-12">
                                    <label>Target Value:</label>
                                    <div class='flexbox'>
                                        <input name="target" type="number" value='<?php echo $info['target']; ?>' class="form-control" min="0" max="100" placeholder="0" required>
                                        <div class='btn'>%</div>
                                    </div>
                                </div>
                                </div>
                                
                                <div class="row custom-row">
                                <div class="form-group col-lg-4 col-12">
                                    <label>Date Captured:</label>
                                    <input name="start" type="text"  value='<?php echo $info['start']; ?>' class="form-control datepicker" required style="cursor:pointer;">
                
                                </div>
                                <div class="form-group col-lg-4 col-12">
                                    <label>Trend:</label>
                                    <select name="trend" class="form-control" required>
                                        <?php echo _listTrend($info['trend']); ?>    
                                    </select> 
                                </div>
                                <div class="form-group col-lg-4 col-12">
                                    <label>Priority:</label>
                                    <select name="priority" class="form-control" required>
                                        <?php echo _listPriority($info['priority']); ?>    
                                    </select>                                
                                </div>
                                </div>

                                
                            </div>
                            <div class="card-body">
                                <div class="form-group text-right">
									<button class="btn btn-md btn-primary btn-icon icol-left" name="update-kri"><i class='fas fa-check'></i> Update KRI</button>
									<button type="button" class="btn btn-md btn-warning" id="btn_cancel">Cancel</button>
								</div>
                            </div>
                        </div>
                        <div class="card-footer"></div>
                    </form>
                  </div>
                </div>
                <style>.main-footer{margin-top:0px;}</style>
                <?php }else{ ?>
                <div class="card">
                    <div class="card-body" style="display:flex;justify-content:center;align-items:center;min-height:500px;">
                        <div style="width:100%;min-height:400px;display:flex;justify-content:center;align-items:center;">
                            <div style="text-align: center;"> 
                                 <h3>Empty Data!!</h3>
                                    Risk Indicator Does not Exist!!
                                 <p><a href="/help#data-error" class="btn btn-primary btn-icon icon-left mt-2"><i class="fas fa-arrow-left"></i> Help</a></p>
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
                                 <p><a href="kri" class="btn btn-primary btn-icon icon-left mt-2"><i class="fas fa-arrow-left"></i> Back</a></p>
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
        .card{
          padding: 10px;
        }
        .flexbox{
            display:flex;
        }
    </style>
</body>
</html>