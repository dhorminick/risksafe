<?php
    session_start();
    $file_dir = '../';

    if (isset($_SESSION["AdminloggedIn"]) == true || isset($_SESSION["AdminloggedIn"]) === true) {
        $signedIn = true;
    } else {
        header('Location: login');
        exit();
    }
  
    $message = [];
    $company_id = '';
    $accnt_dir = null;
    include $file_dir.'layout/db.php';
    include 'ajax/admin.php';
    include $file_dir.'layout/superadmin_config.php';

    $db = $con;

    if(isset($_POST['importSubmit'])){
        $task = sanitizePlus($_POST["task"]); #required
        $requirement = intval(sanitizePlus($_POST["requirement"])); #required
        $officer = intval(sanitizePlus($_POST["officer"])); #required
        $frequency = intval(sanitizePlus($_POST["frequency"])); 
        $reference = intval(sanitizePlus($_POST["reference"]));
        $evidence = intval(sanitizePlus($_POST["evidence"]));
        $control = intval(sanitizePlus($_POST["control"]));
        $treatment = intval(sanitizePlus($_POST["treatment"]));
        #optional
        $o_frequency = sanitizePlus($_POST["frequency"]); 
        $o_reference = sanitizePlus($_POST["reference"]);
        $o_evidence = sanitizePlus($_POST["evidence"]);
        
        $company = sanitizePlus($_POST["company"]); #required        
        
        // Allowed mime types
        $csvMimes = array('text/x-comma-separated-values', 'text/comma-separated-values', 'application/octet-stream', 'application/vnd.ms-excel', 'application/x-csv', 'text/x-csv', 'text/csv', 'application/csv', 'application/excel', 'application/vnd.msexcel', 'text/plain');
        

        if ($company != '' || $company != null) {
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
                        
                        $s_task = sanitizePlus($line[intval($task)]);
                        $s_requirement = sanitizePlus($line[intval($requirement)]);
                        $s_officer = sanitizePlus($line[intval($officer)]);

                        #controls and treatments
                        $action_type = 'controls_and_treatments';
                        $s_action = 'null';
                            
                        $s_control = sanitizePlus($line[intval($control)]);
                        $s_treatment = sanitizePlus($line[intval($treatment)]);
                            
                        #validate evidence
                        if($o_evidence == '' || strtolower($o_evidence) == 'null' || !$o_evidence){
                            $s_evidence = 'null';
                        }else{
                            $s_evidence = sanitizePlus($line[intval($evidence)]);
                        }

                        #validate reference & legislation
                        if($o_reference == '' || strtolower($o_reference) == 'null' || !$o_reference){
                            $s_reference = 'null';
                        }else{
                            $s_reference = sanitizePlus($line[intval($reference)]);
                        }

                        #validate frequency
                        if($o_frequency == '' || strtolower($o_frequency) == 'null' || !$o_frequency){
                            $s_frequency = 'Un-Assessed';
                        }else{
                            $s_frequency = sanitizePlus($line[intval($frequency)]);
                        }
                        
                        if($db->query("INSERT INTO as_compliancestandard (c_id, compli_id, com_user_id, com_officer, frequency, com_legislation, com_documentation, type, action_type, action, imported_controls, imported_treatments, com_training, com_compliancestandard, co_status) 
                            VALUES ('$company', '$compli_id', 'Admin', '".$s_officer."', '".$s_frequency."', '".$s_reference."', '".$s_evidence."', 'imported', '".$action_type."', '".$s_action."', '".$s_control."', '".$s_treatment."', '".$s_requirement."', '".$s_task."', 'Un-Assessed')")){
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
            array_push($message, 'Error 402: Missing Data (Company ID)!!');
        }
        
    }

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport">
  <title>Import Data | <?php echo $siteEndTitle; ?></title>
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
                        <?php require $file_dir.'layout/alert.php' ?>
                        <form action="" method="post" enctype="multipart/form-data">
                        <div class="card-body row custom-row">
                            <!-- data arrangements -->
                            <div class="form-group col-12">
                            <fieldset class="row">
                                <legend>Compliance CSV Data Row Arrangement</legend>
                                <div class="form-group col-lg-3 col-12">
                                    <label>Task:</label>
                                    <input type="text" name="task" class="form-control" required>
                                </div>
                                <div class="form-group col-lg-3 col-12">
                                    <label>Requirements:</label>
                                    <input type="text" name="requirement" class="form-control" required>
                                </div>
                                <div class="form-group col-lg-3 col-12">
                                    <label>Officers:</label>
                                    <input type="text" name="officer" class="form-control" required>
                                </div>
                                <div class="form-group col-lg-3 col-12">
                                    <label>Frequency:</label>
                                    <input type="text" name="frequency" class="form-control" required>
                                </div>
                                <div class="form-group col-lg-3 col-12">
                                    <label>Reference / Legislation:</label>
                                    <input type="text" name="reference" class="form-control" required>
                                </div>
                                <div class="form-group col-lg-3 col-12">
                                    <label>Evidence:</label>
                                    <input type="text" name="evidence" class="form-control" required>
                                </div>
                                <div class="form-group col-lg-3 col-12"><label>Controls:</label><input type="text" name="control" class="form-control" required></div>
                                <div class="form-group col-lg-3 col-12"><label>Treatments:</label><input type="text" name="treatment" class="form-control" required></div>
                                
                            </fieldset>
                            </div>
                            <!-- CSV file upload form -->
                            <div class="form-group col-lg-4 col-12">
                                <label>Company ID:</label>
                                <input type="text" name="company" id='company_id' class="form-control" placeholder="eg: F4JSJRMAO0" required>
                            </div>
                            <div class="form-group col-12 col-lg-8">
                                <label>CSV File: </label>
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
                            <div class="form-group col-12">
                                <label>Company Name:</label>
                                <div id="company_name">Enter A Valid Company ID!!</div>
                            </div>
                            <!-- <input type="file" name="file" /> -->
                            
                        </div>
                        <div class="card-footer">
                            <button type="submit" class="btn-import btn btn-primary btn-lg btn-icon icon-left" name="importSubmit"><i class="fa fa-plus"></i> Import CSV</button>
                        </div>
                        </form>
                        <form id="validate_form"></form>
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
        $("#validate_form").submit(function (e) {
            e.preventDefault();

            var formValues = $(this).serialize();

            $.post("ajax/validate.company", {
                validateCompany: formValues,
            }).done(function (data) {
                setTimeout(function () {
                    $("#company_name").html(data);
                }, 1000);
            });
        });

        // $(".refInput").change(function () {
        //     // alert($(this).attr('value'));
        //     if ($(this).attr('value') === '1') {
        //        $(".ref_result").html('<div class="form-group col-lg-4 col-12"><label>Reference:</label><input type="text" name="reference" class="form-control" required></div>');
        //     } else if ($(this).attr('value') === '2') {
        //         $(".ref_result").html('<div class="form-group col-lg-4 col-12"><label>Evidence:</label><input type="text" name="evidence" class="form-control" required></div>');
        //     } else {
        //         $(".ref_result").html('Error!!');
        //     }
        // });

        $(".actInput").change(function () {
            // alert($(this).attr('value'));
            if ($(this).attr('value') === '1') {
               $(".action_result").html('<div class="form-group col-lg-4 col-12"><label>Actions:</label><input type="text" name="action" class="form-control" required></div>');
            } else if ($(this).attr('value') === '2') {
                $(".action_result").html('<div class="form-group col-lg-4 col-12"><label>Controls:</label><input type="text" name="control" class="form-control" required></div><div class="form-group col-lg-4 col-12"><label>Treatments:</label><input type="text" name="treatment" class="form-control" required></div>');
            } else {
                $(".action_result").html('Error!!');
            }
        });

        $("#company_id").keyup(function () {
            if ($(this).val().length === 10) {
                var cId = $(this).val();
                var inputID = '<input type="text" value="' + cId + '" name="c_id">';
                $("#validate_form").append(inputID);
                $("#validate_form").submit();
                $("#validate_form").html("");
            } else {
                $("#company_name").html('Validating Company ID...');
            }

            if ($(this).val().length === 0) {
                $("#company_name").html("Enter A Valid Company ID!!");
            }
        });
        
        $("#company_id").change(function () {
            if ($(this).val().length === 10) {
                var cId = $(this).val();
                var inputID = '<input type="text" value="' + cId + '" name="c_id">';
                $("#validate_form").append(inputID);
                $("#validate_form").submit();
                $("#validate_form").html("");
            } else {
                $("#company_name").html('Validating Company ID...');
            }

            if ($(this).val().length === 0) {
                $("#company_name").html("Enter A Valid Company ID!!");
            }
        });
    </script>
</body>
</html>