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
    $error = false;
    
    if(isset($_POST['importSubmit'])){
        $industry = sanitizePlus($_POST["industry"]); #required
        $title = sanitizePlus($_POST["title"]); #required
        $desc = sanitizePlus($_POST["desc"]); #required
        
        if($industry == 'null' || !isset($_POST["title"]) || !isset($_POST["desc"])){
            $error = true;
        }

        if (isset($_POST["custom-treatment"]) && $_POST["custom-treatment"] != null) {
            $control = $_POST["custom-treatment"];
            $n = [];
            foreach($control as $c){
                $rrr_id = secure_random_string(10);
                $newArr_2 = array(
                    'id' => $rrr_id,
                    'text' => $c
                );
                
                array_push($n, $newArr_2);
            }
            $control = serialize($n);
        }else{
            $error = true;
        }
        
        if($error == false){
            
            $ri_id = secure_random_string(10);
            $InsertRisk = "INSERT INTO as_newrisk (title, description, control, risk_id, industry) VALUES ('$title', '$desc', '$control', '$ri_id', '$industry')";
            $RiskInserted = $con->query($InsertRisk);  
            if ($RiskInserted) {
                array_push($message, 'Risk Uploaded Successfully!!');
            }else{
                array_push($message, 'Error 502: Unable To Upload Risk!!');
            }
            
        }
        
    }

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport">
  <title>Upload New Risk | <?php echo $siteEndTitle; ?></title>
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
                            <a href='assessment-details?id=' class="btn btn-primary btn-icon icon-left header-a">New Sub Risk <i class="fas fa-arrow-right"></i></a>
                        </div>
                        <div class='card-body'>
                            <form action="" method="post">
                                <div class="form-group">
                                    <label class="help-label">
                                        Select Industry:
                                    </label>
                                    <select name="industry" class="form-control" required>
                                        <option value="null" selected>None Selected</option>
                                        <?php
                                            $CheckIfAssessmentExist = "SELECT * FROM as_newrisk_industry";
                                            $AssessmentExist = $con->query($CheckIfAssessmentExist);
                                            if ($AssessmentExist->num_rows > 0) {	
                                                while($industry = $AssessmentExist->fetch_assoc()){
                                        ?>
                                        <option value='<?php echo $industry['industry_id']; ?>'><?php echo ucwords($industry['title']); ?></option>
                                        <?php }} ?>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label>Title:</label>
                                    <input type='text' name="title" class="form-control" />
                                </div>
                                <div class="form-group">
                                    <label>Description:</label>
                                    <textarea name="desc" class="form-control"></textarea>
                                </div>
                                <div class="form-group">
                                    <label class="help-label">
                                        Controls:
                                    </label>
                                    <div class="add-customs">
                                        <input type="text" class="form-control" placeholder="Control Description..." name='custom-treatment[]'>
                                        <button type="button" class="btn btn-sm btn-primary" id="btn-append-custom-treatment">+ Add</button>
                                    </div>
                                    <div id='add-customs-treatment'></div>
                                </div>
                                <div class="form-group text-right" style='margin-top:30px !important;'>
                                    <button type="submit" class="btn btn-primary btn-lg btn-icon icon-left" name="importSubmit"><i class="fa fa-plus"></i> Upload Risk</button>
                                </div>
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
    </script>
</body>
</html>