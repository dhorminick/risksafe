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
    $hasSubs = false;
    include $file_dir.'layout/db.php';
    include 'ajax/admin.php';
    include $file_dir.'layout/superadmin_config.php';

    $db = $con;
    $error = false;
    
    if(isset($_POST['importSubmit']) && isset($_POST["ri_id"])){
        $risk_id = sanitizePlus($_POST["ri_id"]);
        $n = [];
        
        if (isset($_POST["custom-treatment"]) && $_POST["custom-treatment"] != null) {
            $control = $_POST["custom-treatment"];
        
            foreach($control as $c){
                $r_id = secure_random_string(10);
                $newArr_2 = array(
                    'id' => $r_id,
                    'text' => $c
                );
                
                array_push($n, $newArr_2);
            }
            
            $n = serialize($n);
        }else{
            $error = true;
        }
        
        if($error == false){
            
            $InsertRisk = "UPDATE as_newrisk_sub SET title = '$n' WHERE risk_id = '$risk_id'";
            $RiskInserted = $con->query($InsertRisk);  
            if ($RiskInserted) {
                array_push($message, 'Risk Updated Successfully!!');
                header('Location: risks?id='.$id);
            }else{
                array_push($message, 'Error 502: Unable To Update Risk!!');
            }
            
        }else{
            array_push($message, 'Error!!');
        }
        
    }
    
    if (isset($_GET['id']) && isset($_GET['id']) !== "") {
        $id = strtolower(sanitizePlus($_GET['id']));
        
        $CheckIfAssessmentExist = "SELECT * FROM as_newrisk WHERE risk_id = '$id'";
        $AssessmentExist = $con->query($CheckIfAssessmentExist);
        if ($AssessmentExist->num_rows > 0) {
         $info = $AssessmentExist->fetch_assoc();
        }else{
            echo '<h1>ID Does Not Exist</h1>';
            exit();
        }
        
    }else{
        echo '<h1>Missing ID Param!!</h1>';exit();
    }

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport">
  <title>Update New Sub Risk | <?php echo $siteEndTitle; ?></title>
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
                        <div class="card-header">
                            <h4 class="d-inline">Upload New Risk</h4>
                            <a href='risks?id=<?php echo $info['industry']; ?>' class="btn btn-primary btn-icon icon-left header-a">Back <i class="fas fa-arrow-right"></i></a>
                        </div>
                        <div class='card-body'>
                            <form action="" method="post">
                                <div class="form-group">
                                    <label>Risk:</label>
                                    <input type='text' value='<?php echo ucwords($info['title']); ?>' class="form-control" readonly />
                                </div>
                                <input type='hidden' name="ri_id" value='<?php echo $id; ?>' />
                                <div class='row custom'>
                                <?php
                                    $CheckIfAssessmentsExist = "SELECT * FROM as_newrisk_sub WHERE risk_id = '$id'";
                                    $AssessmentsExist = $con->query($CheckIfAssessmentsExist);
                                    if ($AssessmentsExist->num_rows > 0) {
                                        $hasSubs = true;
                                     $data = $AssessmentsExist->fetch_assoc();
                                     $subrisks = unserialize($data['title']);
                                     $i=0;
                                     foreach($subrisks as $value){ $i++;
                                ?>
                                
                                <?php if($i==1){ ?>
                                <div class="form-group col-12">
                                    <label class="help-label">
                                        Sub Risks:
                                    </label>
                                    <div class="add-customs">
                                        <input type="text" value='<?php echo $value; ?>' class="form-control" placeholder="Control Description..." name='custom-treatment[]'>
                                        <button type="button" class="btn btn-sm btn-primary" id="btn-append-custom-treatment">+ Add</button>
                                    </div>
                                    <div id='add-customs-treatment'></div>
                                </div>
                                <?php }else{ ?>
                                    <div style="display:flex;justify-content:center;align-items:center;" class='col-12'>
                                        <input type="text" value='<?php echo $value; ?>' class="form-control" placeholder="Enter Custom Treatment Description..." style="margin-top:5px;" name="custom-treatment[]"  required/>
                                        <buttton class="btn btn-sm btn-primary remove_button_t" data-toggle="tooltip" title="Remove Field" data-placement="left" type="button" style="margin-left:5px;display:flex;justify-content:center;align-items:center;font-size:20px;padding:12px 10px;"><i class="fas fa-minus"></i></buttton>
                                    </div>
                                <?php } ?>
                                
                                <?php }}else{ ?>
                                <div class='col-12' style='padding:20px 10px;'>
                                <h4>No Sub Risk Created Yet!!</h4>
                                <a href='upload-sub-risk?id=<?php echo $id; ?>' class='btn btn-primary btn-icon icon-left'><i class='fa fa-plus'></i> Add Sub Risk</a>
                                </div>
                                <? } ?>
                                </div>
                                
                                <?php if($hasSubs){ ?>
                                <div class="form-group text-right" style='margin-top:30px !important;'>
                                    <button type="submit" class="btn btn-primary btn-lg btn-icon icon-left" name="importSubmit"><i class="fa fa-plus"></i> Update Sub Risk</button>
                                </div>
                                <?php } ?>
                            </form>
                        </div>
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
    </script>
</body>
</html>