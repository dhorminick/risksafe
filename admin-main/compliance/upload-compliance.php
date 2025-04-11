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
    
    function checkIfNull($data){
        if($data == '' || $data == null || !$data){
            return false;
        }else{
            return true;
        }
    }
    
    $allowedFrequencies = array(
                                "daily" => 1, 
                                "weekly" => 2, 
                                "monthly" => 4, 
                                "quaterly" => 5,
                                "annually" => 6,
                                "half yearly" => 8,
                          );
                          
    $allowedEffectiveness = array("effective" => 'Effective', "ineffective" => 'InEffective');

    if(isset($_POST['importSubmit'])){
        
        if(
            checkIfNull($_POST["task"]) == false 
            || checkIfNull($_POST["requirement"]) == false 
            || checkIfNull($_POST["control"]) == false 
            || checkIfNull($_POST["section"]) == false 
            || checkIfNull($_POST["reference"]) == false 
            || checkIfNull($_POST["officers"]) == false 
            || checkIfNull($_POST["frequency"]) == false
            || checkIfNull($_POST["effectiveness"]) == false
        ){
          array_push($message, 'Error: Empty Data Passed!!');

        }else{
            $c_id = sanitizePlus($_POST['c_id']);
            $task = sanitizePlus($_POST["task"]); #required
            $requirement = intval(sanitizePlus($_POST["requirement"])); #required
            $control = intval(sanitizePlus($_POST["control"]));
            $section = intval(sanitizePlus($_POST["section"]));
            
            $reference = intval(sanitizePlus($_POST["reference"]));
            $officers = intval(sanitizePlus($_POST["officers"]));
            $frequency = intval(sanitizePlus($_POST["frequency"]));
            $effectiveness = intval(sanitizePlus($_POST["effectiveness"]));
        
        
        

        // Allowed mime types
        $csvMimes = array('text/x-comma-separated-values', 'text/comma-separated-values', 'application/octet-stream', 'application/vnd.ms-excel', 'application/x-csv', 'text/x-csv', 'text/csv', 'application/csv', 'application/excel', 'application/vnd.msexcel', 'text/plain');
        

        if ($c_id != '' || $c_id != null) {
            // Validate whether selected file is a CSV file
            if(!empty($_FILES['file']['name']) && in_array($_FILES['file']['type'], $csvMimes)){
                
                // If the file is uploaded
                if(is_uploaded_file($_FILES['file']['tmp_name'])){
                    
                    // Open uploaded CSV file with read-only mode
                    $csvFile = fopen($_FILES['file']['tmp_name'], 'r');
                    
                    // Skip the first line
                    fgetcsv($csvFile);
                    
                    
                    // Parse data from CSV file line by line
                    while(($line = fgetcsv($csvFile)) !== FALSE){
                        #compliance id
                        $compli_id = secure_random_string(10);
                        // Get row data
                        
                        $s_section = sanitizePlus($line[intval($section)]);
                        $s_task = sanitizePlus($line[intval($task)]);
                        $s_requirement = sanitizePlus($line[intval($requirement)]);
                        $s_control = $line[intval($control)]; # no sanitization
                        $s_frequency = $line[intval($frequency)];
                        $s_effectiveness = $line[intval($effectiveness)];
                        $s_officers = $line[intval($officers)];
                        $s_reference = $line[intval($reference)];
                        
                        if($s_control !== '' || $s_control !== null){
                            $newControlData = explode("*", strval($s_control)); #explode with *
                            $controls_arr = []; #define the array
                            foreach ($newControlData as $_control){
                                $dataToPush = array(
                                    'id' => secure_random_string(10),
                                    'control' => sanitizePlus($_control)
                                );
                                
                                array_push($controls_arr, $dataToPush); #push to array
                            }
                            
                            $s_control = serialize($controls_arr);
                            
                            // var_dump($controls_arr);
                            // exit();
                        }else{
                            $s_control = null;
                        }
                        
                        
                        if (array_key_exists(strtolower($s_frequency), $allowedFrequencies)){
                            $freq = $allowedFrequencies[strtolower($s_frequency)];
                        }else{
                           $freq = 7; # as required 
                        }
                        
                        if (array_key_exists(strtolower($s_effectiveness), $allowedEffectiveness)){
                            $effect = $allowedEffectiveness[strtolower($s_effectiveness)];
                        }else{
                            $effect = 'Unassessed';
                        }
                        
                        if($db->query("INSERT INTO as_compliancedata (module, compliance_id, section, requirement, obligation, controls, status, reference, officers, frequency, effectiveness) 
                            VALUES ('$c_id', '$compli_id', '".$s_section."', '".$s_requirement."', '".$s_task."', '".$s_control."', '$freq', '".$s_reference."', '".$s_officers."', '$freq', '$effect')")){
                            $dataImported = true;
                        }else{
                            $dataImported = false;
                        }
                        
                        
                    }
                    
                    if($dataImported == true){
                        array_push($message, 'Compliance Data Imported Successfully!!');
                    }else{
                        array_push($message, 'Error Importing Compliance Data!!' . $db->error);
                    }
                    
                    // Close opened CSV file
                    fclose($csvFile);
                    
                }else{
                    #error uploading csv
                    array_push($message, 'Error Uploading CSV Data!!');
                }
            }else{
                #invalid file
                array_push($message, 'Error: Please Upload A Valid CSV File!!');
            }
        } else {
            array_push($message, 'Error 402: Missing Data (Module ID)!!');
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
  <title>Upload Compliance | <?php echo $siteEndTitle; ?></title>
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
                        <form action="" method="post" enctype="multipart/form-data">
                        <div class="card-body row custom-row">
                            <!-- data arrangements -->
                            <div class="form-group col-12">
                            <fieldset class="row">
                                <legend>Upload Compliance for "<?php echo ucwords($data['title']); ?>"</legend>
                                <input type="hidden" name='c_id' value="<?php echo $data['compliance_id']; ?>" />
                                <div class="form-group col-lg-3 col-12">
                                    <label>Section:</label>
                                    <input type="number" name="section" class="form-control">
                                </div>
                                <div class="form-group col-lg-3 col-12">
                                    <label>Task or Obligation:</label>
                                    <input type="number" name="task" class="form-control" required>
                                </div>
                                <div class="form-group col-lg-3 col-12">
                                    <label>Requirements:</label>
                                    <input type="number" name="requirement" class="form-control" required>
                                </div>
                                <div class="form-group col-lg-3 col-12">
                                    <label>Controls:</label>
                                    <input type="number" name="control" class="form-control" required>
                                </div>
                                
                                <div class="form-group col-lg-3 col-12">
                                    <label>Reference:</label>
                                    <input type="number" name="reference" class="form-control" required>
                                </div>
                                <div class="form-group col-lg-3 col-12">
                                    <label>Officers:</label>
                                    <input type="number" name="officers" class="form-control" required>
                                </div>
                                <div class="form-group col-lg-3 col-12">
                                    <label>Frequency:</label>
                                    <input type="number" name="frequency" class="form-control" required>
                                </div>
                                <div class="form-group col-lg-3 col-12">
                                    <label>Effectiveness:</label>
                                    <input type="number" name="effectiveness" class="form-control" required>
                                </div>
                                
                            </fieldset>
                            </div>
                            <!-- CSV file upload form -->
                            <div class="form-group col-12">
                                <label>Compliance File: </label>
                                    <div class="input-group">
                                        <div class='file_name form-control'>Select File:</div>
                                        <div class="input-group-append">
                                            <div class="input-group-text" id='file_opener' style="cursor:pointer;">
                                                <i class="fa fa-plus"></i>
                                            </div>
                                        </div>
                                    </div>
                                <div class="col-12" style='margin-bottom:10px;'>
                                </div>
                                <input id="file_main" name="file" type="file" required>
                            </div>
                        </div>
                        <div class="card-footer">
                            <button type="submit" class="btn-import btn btn-primary btn-lg btn-icon icon-left" name="importSubmit"><i class="fa fa-plus"></i> Upload Compliance</button>
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
            display: flex;align-items:center;justify-content:center;
        }
        .btn-import i{
            margin-right: 5px;
        }
        fieldset {
            min-width: 0;
            padding: 10px;
            margin: 0;
            border: 1px solid #e4e6fc;;
            border-radius: 10px;
        }
        legend{
            border: 1px solid #e4e6fc;;
            padding: 10px;
            border-radius: 10px;
            font-size: 16px !important;
        }
        input.form-control{
            background-color: rgb(240, 240, 240) !important;
            border-width: 3px !important;
        }
    </style>
    <script>
        
    </script>
</body>
</html>