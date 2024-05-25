<?php
    session_start();
    $file_dir = '../../';

    if (isset($_SESSION["loggedIn"]) == true || isset($_SESSION["loggedIn"]) === true) {
        $signedIn = true;
    } else {
        header('Location: '.$file_dir.'auth/sign-in?r=/monitoring/audits');
        exit();
    }
  
    $message = [];
    include $file_dir.'layout/db.php';
    include '../ajax/audits.php';
    include $file_dir.'layout/admin__config.php';


    if (isset($_GET['id']) && isset($_GET['id']) !== "") {
        $toDisplay = true;   
        $id = sanitizePlus($_GET['id']);
        $CheckIfAuditExist = "SELECT * FROM as_auditcontrols WHERE aud_id = '$id' AND c_id = '$company_id'";
        $AuditExist = $con->query($CheckIfAuditExist);
        if ($AuditExist->num_rows > 0) {	
            $aud_exist = true;
            $aud_id = $id;
			#$info = $AuditExist->fetch_assoc();

            if(isset($_POST["save-criteria"])){
                $cri_id = secure_random_string(10);
                $question = sanitizePlus($_POST['question']);
                $procedure = sanitizePlus($_POST['procedure']);
                $expected = sanitizePlus($_POST['expected']);
                $outcome = sanitizePlus($_POST['outcome']);
                $notes = sanitizePlus($_POST['notes']);

                if (!$notes || $notes == null || $notes == '' || !$outcome || $outcome == null || $outcome == '' || !$expected || $expected == null || $expected == '' || !$question || $question == null || $question == '' || !$procedure || $procedure == null || $procedure == '') {
                    array_push($message, 'Error 402: Incomplete Data Parameters!!');
                } else {
                    # code...
                    $query = "INSERT INTO as_auditcriteria (cri_control, cri_question, cri_procedure, cri_expected, cri_outcome, cri_notes, c_id, cri_id, aud_id) VALUES ('$aud_id', '$question', '$procedure', '$expected', '$outcome', '$notes', '$company_id', '$cri_id', '$aud_id')";
                    $criteriaCreated = $con->query($query);
                    if ($criteriaCreated) {
                        array_push($message, 'Risk Criteria Created Successfully!!');
                    } else {
                        array_push($message, 'Error 502: Error Creating Criteria!!');
                    }
                }		
            }
        
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
  <title>Create New Audit Criteria Question | <?php echo $siteEndTitle; ?></title>
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
        <!-- Main
         Content -->
        <div class="main-content">
            <section class="section">
            <div class="section-body">
                <?php if($toDisplay == true){ ?>
                <?php if ($aud_exist == true) { ?>
                <div class="card">
                  <div class="card-body">
                    <?php require $file_dir.'layout/alert.php' ?>
                    <form method="post">
                        <div class="card-header">
                            <h3 class="d-inline">Create Audit Criteria</h3>
                          <a href='audit-details?id=<?php echo $id; ?>' class="btn btn-primary btn-icon icon-left header-a"><i class="fas fa-arrow-left"></i> Back</a>
                        </div>
                        <div class="card-body">
                            <div class="form-group">
                                <label>Test Question</label>
                                <input name="question" type="text" maxlength="255" class="form-control" placeholder="Enter question..." required>
                            </div>
                            <div class="form-group">
                                <label>Test Procedure</label>
                                <textarea rows="4" name="procedure" maxlength="255" class="form-control" placeholder="Enter test procedure..." required></textarea>
                            </div>
                            <div class="form-group">
                                <label>Expected Outcome</label>
                                <input name="expected" type="text" maxlength="255" class="form-control" placeholder="Enter expected outcome..." required>
                            </div>
                            <div class="form-group">
                                <label>Outcome</label>
                                <select name="outcome" class="form-control">
                                    <option value="0" >N/A</option>
                                    <option value="1" >Pass</option>
                                    <option value="2" >Fail</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label>Notes</label>
                                <textarea rows="4" name="notes" class="form-control"></textarea>
                            </div>
                            <div class="form-group text-right">
                                <button type="submit" class="btn btn-primary btn-icon icon-left" name="save-criteria"><i class="fas fa-check-circle"></i> Save Criteria Question</button>
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
                                 Audit Of Control Doesn't Exist!!
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
                                 <p><a href="audits" class="btn btn-primary btn-icon icon-left mt-2"><i class="fas fa-arrow-left"></i> Back</a></p>
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
    </style>
    <script>
      $(".sub-control").hide();
      $("#control-type").change(function(e) { 
        var riskValue = $("#control-type").val();
        if (riskValue == '0') {
            $(".sub-control").hide();
        } else {
            $("#get_subcontrol").val();
            $("#get_subcontrol").val(riskValue);
            $("#getSubControl").submit();
        }
      });
      $("#getSubControl").submit(function (event) {
        // alert('first first stop!');
        event.preventDefault();
  
        var formValues = $(this).serialize();
  
        $.post("../ajax/audits.php", {
            getSubControl: formValues,
        }).done(function (data) {
            // alert(data);
            $("#sub-control").html(data);
            $(".sub-control").show();
            setTimeout(function () {
                $("#getSubControl input").val('');
            }, 1000);
            
            // alert('second stop!');
        });
      });
      
    </script>
</body>
</html>