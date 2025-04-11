<?php
    session_start();
    $file_dir = '../';

    if (isset($_SESSION["AdminloggedIn"]) == true || isset($_SESSION["AdminloggedIn"]) === true) {
        $signedIn = true;
    } else {
        header('Location: login');
        exit();
    }
    
    require $file_dir.'vendor/autoload.php';
    use PhpOffice\PhpSpreadsheet\Spreadsheet;
    use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

    $message = [];
    include $file_dir.'layout/db.php';
    include 'ajax/admin.php';
    include $file_dir.'layout/superadmin_config.php';

    $db = $con;

    if(isset($_POST['importSubmit'])){
        $task = sanitizePlus($_POST["task"]); #required
        $requirement = sanitizePlus($_POST["requirement"]); #required
        $officer = sanitizePlus($_POST["officer"]); #required
        $frequency = sanitizePlus($_POST["frequency"]); 
        $reference = sanitizePlus($_POST["reference"]);
        $evidence = sanitizePlus($_POST["evidence"]);
        $act = sanitizePlus($_POST["act"]);
        
        $company = sanitizePlus($_POST["company"]); #required        
        
        if ($company != '' || $company != null) {
            $fileName = $_FILES['import_file']['name'];
            $file_ext = pathinfo($fileName, PATHINFO_EXTENSION);
        
            $allowed_ext = ['xls','csv','xlsx'];
        
            if(in_array($file_ext, $allowed_ext)) {
                $inputFileNamePath = $_FILES['import_file']['tmp_name'];
                $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($inputFileNamePath);
                $data = $spreadsheet->getActiveSheet()->toArray();
        
                $count = "0";
                foreach($data as $row) {
                    if($count > 0) {
                        #while(($line = fgetcsv($csvFile)) !== FALSE){
                            #compliance id
                            $compli_id = secure_random_string(10);
                            // Get row data
                            $task = $line[$task];
                            $requirement = $line[$requirement];
                            $officer = $line[$officer];
    
                            #validate if action_type is compliance actions or compliance controls and treatments
                            if ($act == '1') {
                                #actions
                                $action_type = 'actions';
                                $action = sanitizePlus($_POST["action"]);
                                $action = $line[$action];
                                $control = 'a:1:{i:0;s:0:"";}';
                                $treatment = 'a:1:{i:0;s:0:"";}';
                            } else if ($act == '2') {
                                #controls and treatments
                                $action_type = 'controls_and_treatments';
                                $action = 'null';
                                $control = sanitizePlus($_POST["control"]);
                                $treatment = sanitizePlus($_POST["treatment"]);
                                $control = $line[$control];
                                $treatment = $line[$treatment];
                            }else{
                                #error
                                $action_type = 'actions';
                                $action = 'Error!!';
                                $control = 'a:1:{i:0;s:0:"";}';
                                $treatment = 'a:1:{i:0;s:0:"";}';
                            }
    
                            #validate evidence
                            if($evidence == '' || strtolower($evidence) == 'null' || !$evidence){
                                $evidence = 'null';
                            }else{
                                $evidence = $line[$evidence];
                            }
    
                            #validate reference & legislation
                            if($reference == '' || strtolower($reference) == 'null' || !$reference){
                                $reference = 'null';
                            }else{
                                $reference = $line[$reference];
                            }
    
                            #validate frequency
                            if($frequency == '' || strtolower($frequency) == 'null' || !$frequency){
                                $frequency = 'Un-Assessed';
                            }else{
                                $frequency = $line[$frequency];
                            }
    
                            #add line breaks on each new line
                            $task = nl2br($task, true);
                            $requirement = nl2br($requirement, true);
    
                            if($db->query("INSERT INTO as_compliancestandard (c_id, compli_id, com_user_id, com_officer, frequency, com_legislation, com_documentation, type, action_type, action, existing_ct, existing_tr, com_training, com_compliancestandard, co_status) VALUES ('$company', '$compli_id', '$com_user_id', '$officer', '$frequency', '$reference', '$evidence', 'imported', '$action_type', '$action', '$control', '$treatment', '$requirement', '$task', 'Un-Assessed')")){
                                #works
                                array_push($message, 'Compliance Data Imported Successfully!!');
                            }else{
                                #error
                                array_push($message, 'Error Importing Compliance Data!!' . $db->error);
                            }
                        #}
                    } else {
                        $count = "1";
                    }
                }
            } else {
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
  <title>Import Data (Excel) | <?php echo $siteEndTitle; ?></title>
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
                                <!-- <div class="form-group col-lg-3 col-12">
                                    <div class="custom-switches-stacked mt-2">
                                        <label class="custom-switch">
                                        <input type="radio" name="ref" value="1" class="custom-switch-input refInput" checked>
                                        <span class="custom-switch-indicator"></span>
                                        <span class="custom-switch-description">Reference</span>
                                        </label>
                                        <label class="custom-switch">
                                        <input type="radio" name="ref" value="2" class="custom-switch-input refInput">
                                        <span class="custom-switch-indicator"></span>
                                        <span class="custom-switch-description">Evidence</span>
                                        </label>
                                    </div>
                                </div>
                                <div class="ref_result row custom-row col-lg-9 col-12">
                                    <div class="form-group col-lg-4 col-12">
                                        <label>Reference:</label>
                                        <input type="text" name="reference" class="form-control" required>
                                    </div>
                                </div> -->
                                <hr class="col-9">
                                <div class="form-group col-lg-3 col-12">
                                    <div class="custom-switches-stacked mt-2">
                                        <label class="custom-switch">
                                        <input type="radio" name="act" value="1" class="custom-switch-input actInput" checked>
                                        <span class="custom-switch-indicator"></span>
                                        <span class="custom-switch-description">Actions</span>
                                        </label>
                                        <label class="custom-switch">
                                        <input type="radio" name="act" value="2" class="custom-switch-input actInput">
                                        <span class="custom-switch-indicator"></span>
                                        <span class="custom-switch-description">Controls / Treatments</span>
                                        </label>
                                    </div>
                                </div>
                                <div class="action_result row custom-row col-lg-9 col-12">
                                    <div class="form-group col-lg-4 col-12">
                                        <label>Actions:</label>
                                        <input type="text" name="action" class="form-control" required>
                                    </div>
                                </div>
                                
                            </fieldset>
                            </div>
                            <!-- CSV file upload form -->
                            <div class="form-group col-lg-4 col-12">
                                <label>Company ID:</label>
                                <input type="text" name="company" id='company_id' class="form-control" placeholder="eg: F4JSJRMAO0" required>
                            </div>
                            <div class="form-group col-12 col-lg-8">
                                <label>Excel File: </label>
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
                                <input id="file_main" name="import_file" type="file" required>
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
    </script>
</body>
</html>