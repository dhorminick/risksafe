<?php
    session_start();
    $file_dir = '../../';

    if (isset($_SESSION["loggedIn"]) == true || isset($_SESSION["loggedIn"]) === true) {
        $signedIn = true;
    } else {
        header('Location: '.$file_dir.'login?r=/monitoring/edit-criteria');
        exit();
    }
  
    $message = [];
    include '../../layout/db.php';
    include '../ajax/audits.php';
    include '../../layout/admin_config.php';


    if (isset($_GET['id']) && isset($_GET['id']) !== "") {
        $toDisplay = true;   
        $id = sanitizePlus($_GET['id']);
        $CheckIfAuditExist = "SELECT * FROM as_auditcriteria WHERE cri_id = '$id' AND c_id = '$company_id'";
        $AuditExist = $con->query($CheckIfAuditExist);
        if ($AuditExist->num_rows > 0) {	
            $aud_exist = true;
			$info = $AuditExist->fetch_assoc();

            if(isset($_POST["update-criteria"])){
                $question = sanitizePlus($_POST['question']);
                $procedure = sanitizePlus($_POST['procedure']);
                $expected = sanitizePlus($_POST['expected']);
                $outcome = sanitizePlus($_POST['outcome']);
                $notes = sanitizePlus($_POST['notes']);

                if (!$notes || $notes == null || $notes == '' || !$outcome || $outcome == null || $outcome == '' || !$expected || $expected == null || $expected == '' || !$question || $question == null || $question == '' || !$procedure || $procedure == null || $procedure == '') {
                    array_push($message, 'Error 402: Incomplete Data Parameters!!');
                } else {
                    # code...
                    $query = "UPDATE as_auditcriteria SET cri_question = '$question', cri_procedure = '$procedure', cri_expected = '$expected', cri_outcome = '$outcome', cri_notes = '$notes' WHERE cri_id = '$id' AND c_id = '$company_id'";
                    if ($con->query($query)) {
                        array_push($message, 'Control Effectiveness Created Successfully!!');
                    } else {
                        array_push($message, 'Error 502: Error Creating Data!!');
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
  <title>Update Audit Criteria Question | <?php echo $siteEndTitle; ?></title>
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
        <!-- Main
         Content -->
        <div class="main-content">
            <section class="section">
            <div class="section-body">
                <?php if($toDisplay == true){ ?>
                <?php if ($aud_exist == true) { ?>
                <div class="card">
                  <div class="card-body">
                    <?php require '../../layout/alert.php' ?>
                    <form method="post">
                        <div class="card-header">
                            <h3 class="d-inline">Edit Audit Criteria</h3>
                          <a href='audits' class="btn btn-primary btn-icon icon-left header-a"><i class="fas fa-arrow-left"></i> Back</a>
                        </div>
                        <div class="card-body">
                            <div class="form-group">
                                <label>Test Question</label>
                                <input name="question" value='<?php echo $info['cri_question']; ?>' type="text" maxlength="255" class="form-control" placeholder="Enter question..." required>
                            </div>
                            <div class="form-group">
                                <label>Test Procedure</label>
                                <textarea rows="4" name="procedure" maxlength="255" class="form-control" placeholder="Enter test procedure..." required><?php echo $info['cri_procedure']; ?></textarea>
                            </div>
                            <div class="form-group">
                                <label>Expected Outcome</label>
                                <input name="expected" value='<?php echo $info['cri_expected']; ?>' type="text" maxlength="255" class="form-control" placeholder="Enter expected outcome..." required>
                            </div>
                            <div class="form-group">
                                <label>Outcome</label>
                                <select name="outcome" class="form-control">
                                    <option value="0" <?php if($info['cri_outcome'] == 0) echo 'selected'; ?>>N/A</option>
                                    <option value="1" <?php if($info['cri_outcome'] == 1) echo 'selected'; ?>>Pass</option>
                                    <option value="2" <?php if($info['cri_outcome'] == 2) echo 'selected'; ?>>Fail</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label>Notes</label>
                                <textarea rows="4" name="notes" class="form-control"><?php echo $info['cri_notes']; ?></textarea>
                            </div>
                            <div class="form-group">
                                <button type="submit" class="btn btn-primary btn-icon icon-left" name="update-criteria"><i class="fas fa-check-circle"></i> Update Criteria Question</button>
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
                                 Audit Of Control Doesn't Exist!!,
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
        <?php require '../../layout/footer.php' ?>
        </footer>
        </div>
    </div>
    <?php require '../../layout/general_js.php' ?>
    <style>
        textarea{
            min-height: 120px !important;
        }
        .card{
          padding: 10px;
        }
    </style>
</body>
</html>