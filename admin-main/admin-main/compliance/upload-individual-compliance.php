<?php
    session_start();
    $file_dir = '../../';
    $inpage_dir = '../';

    if (isset($_SESSION["AdminloggedIn"]) == true || isset($_SESSION["AdminloggedIn"]) === true) {
        $signedIn = true;
    } else {
        header('Location: login');
        exit();
    }
  
    $message = [];
    $accnt_dir = null;
    include $file_dir.'layout/db.php';
    include $inpage_dir.'ajax/admin.php';
    include $file_dir.'layout/superadmin_config.php';

    $db = $con;
    
    $allowedFrequencies = array(
        "daily" => 1, 
        "weekly" => 2, 
        "monthly" => 4, 
        "quaterly" => 5,
        "annually" => 6,
        "half yearly" => 8,
    );
    
    if(isset($_POST['upload'])){
        
        
            $c_id = sanitizePlus($_POST['c_id']);
            
            $section = sanitizePlus($_POST["section"]);
            $task = sanitizePlus($_POST["task"]); #required
            $legislation = sanitizePlus($_POST["legislation"]);
            $requirement = sanitizePlus($_POST["requirement"]); #required
            $frequency = sanitizePlus($_POST["frequency"]);
            $officers = sanitizePlus($_POST["officers"]);
            $note = sanitizePlus($_POST["note"]);
            
            if ($c_id != '' || $c_id != null) {
                if($_GET['id'] && $c_id === $_GET['id']){
                    
                    $compli_id = secure_random_string(10);
                    
                    #effectiveness
                    $effectiveness = 'Unassessed';
                    
                    #frequency
                    if (array_key_exists(strtolower($frequency), $allowedFrequencies)){
                        $freq = $allowedFrequencies[strtolower($frequency)];
                    }else{
                        $freq = 7; #as required 
                    }
                    // $section to $task
                    if($db->query("INSERT INTO as_compliancedata (module, compliance_id, section, requirement, obligation, controls, status, reference, officers, frequency, effectiveness) 
                                VALUES ('$c_id', '$compli_id', '$task', '$requirement', '$task', null, '$freq', '$legislation', '$officers', '$freq', '$effectiveness')")){
                        array_push($message, 'Compliance Data Imported Successfully!!');
                    }else{
                        array_push($message, 'Error Importing Compliance Data!!');
                    }
                    
                }
            }

        
    }
    
    
    if (isset($_GET['id']) && isset($_GET['id']) !== "") {
        $id = strtolower(sanitizePlus($_GET['id']));
        
        $CheckIfAssessmentExist = "SELECT * FROM as_compliance WHERE compliance_id = '$id'";
        $AssessmentExist = $con->query($CheckIfAssessmentExist);
        if ($AssessmentExist->num_rows > 0) {
         $data = $AssessmentExist->fetch_assoc();
        }else{
            echo '<h1>ID Does Not Exist</h1>';
            exit();
        }
        
    }else{
        echo '<h1>Missing ID Param!!</h1>';
        exit();
    }

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport">
  <title>Upload Individual Compliance Data | <?php echo $siteEndTitle; ?></title>
  <?php require $file_dir.'layout/general_css.php' ?>
  <link rel="stylesheet" href="<?php echo $file_dir; ?>assets/css/admin.custom.css">
</head>

<body>
    <div class="loader"></div>
    <div id="app">
        <div class="main-wrapper main-wrapper-1">
        <div class="navbar-bg"></div>
        <?php require $file_dir.'layout/admin_header.php' ?>
        <?php require $file_dir.'layout/sidebar_admin_admin.php' ?>
        <!-- Main Content -->
        <div class="main-content">
            <section class="section">
                <div class="section-body">
                    <div class="card" style="padding: 10px;">
                        <div class="card-header" style="display:flex;justify-content:space-between;">
                            <div class="d-inline"></div>
                            <a href='/' class="btn btn-primary btn-icon icon-left"><i class="fa fa-arrow-left"></i> Back</a>
                        </div>
                        <?php require $file_dir.'layout/alert.php' ?>
                        <form action="" method="post">
                        <div class="card-body row custom-row">
                            <!-- data arrangements -->
                            <div class="form-group col-12">
                            <div class="row">
                                <div class='legend col-12'>Upload Compliance for "<?php echo ucwords($data['title']); ?>"</div>
                                <input type="hidden" name='c_id' value="<?php echo $data['compliance_id']; ?>" />
                                <div class="form-group col-12">
                                    <label>Section:</label>
                                    <input type="text" name="section" class="form-control">
                                </div>
                                <div class="form-group col-12">
                                    <label>Task or Obligation:</label>
                                    <textarea name="task" class="form-control" required></textarea>
                                </div>
                                <div class="form-group col-12">
                                    <label>Legislation:</label>
                                    <input type="text" name="legislation" class="form-control" required>
                                </div>
                                <div class="form-group col-12">
                                    <label>Requirements:</label>
                                    <textarea name="requirement" class="form-control" required></textarea>
                                </div>
                                <div class="form-group col-lg-3 col-12">
                                    <label>Frequency:</label>
                                    <input type="text" name="frequency" class="form-control" required>
                                </div>
                                <div class="form-group col-lg-9 col-12">
                                    <label>Officers:</label>
                                    <input type="text" name="officers" class="form-control" required>
                                </div>
                                <div class="form-group col-12">
                                    <label>Control Requirements (Note):</label>
                                    <textarea name="note" class="form-control" required></textarea>
                                </div>
                                
                            </div>
                            </div>
                            
                        </div>
                        <div class="card-footer">
                            <button type="submit" class="btn-import btn btn-primary btn-lg btn-icon icon-left" name="upload"><i class="fa fa-plus"></i> Upload Compliance</button>
                        </div>
                        </form>
                    </div>
                </div>
            </section>
        </div>
        <?php require $file_dir.'layout/footer.php' ?>
        </footer>
        </div>
    </div>
    <?php require $file_dir.'layout/general_js.php' ?>
    <script src="<?php echo $file_dir; ?>assets/js/admin.custom.js"></script>
    <style>
        .btn-import{
            display: flex;
            align-items:center;
            justify-content:center;
        }
        .btn-import i{
            margin-right: 5px;
        }
        .legend{
            font-size: 16px !important;
            font-weight:bold;
            margin-bottom:30px;
        }
        
    </style>
    <script>
        
    </script>
</body>
</html>