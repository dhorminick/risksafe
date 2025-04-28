<?php
    session_start();
    $file_dir = '../../';
    if (isset($_SESSION["loggedIn"]) == true || isset($_SESSION["loggedIn"]) === true) {
        $signedIn = true;
    } else {
        header('Location: '.$file_dir.'login?r=/assessments/new-assessment');
        exit();
    }
    $message = [];
    include '../../layout/db.php';
    include '../../layout/admin__config.php';
    
    function getIndustries($id, $con){
        if($id == ''){
           $selected = 'null'; 
        }else{
            $selected = $id;
        }
            
    		$query="SELECT * FROM updated_module ORDER BY id";
    		$result=$con->query($query);
    		if ($result->num_rows > 0) {	
    			$response='<select name="industry" class="form-control" required>';
    			$response.='<option value="0">-- Select Industry --</option>';
    			while ($row=$result->fetch_assoc()) {
    				$response.='<option value="' . $row["module_id"] . '"';
    				if ($row["module_id"]==$selected) $response.=' selected';
    				$response.='>' . ucwords($row["name"]) . '</option>';
    			}
    			$response.="</select>";
    		}else{
    			$response = 'Error!!';
    		}
    		
        

		return $response;
    }
    
    $risk__industry = $_SESSION['risk_industry']; 

    if(isset($_POST["save"])){
        $type = sanitizePlus($_POST["industry"]);
        
        if($type == '0' || $type == 0){
            array_push($message, 'Please Select A Valid Industry!!');
        }else{

        $query = "UPDATE users SET risk_industry = '$type' WHERE company_id = '$company_id'";
        $assessmentCreated = $con->query($query);
        if ($assessmentCreated) {
            $_SESSION['risk_industry'] = $type;
            $risk__industry = $type;
            array_push($message, 'Industry Updated Successfully!!');
        }else{
            array_push($message, 'Error 502: Error!!');
        }	
        
        }
    }
    
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport">
  <title>Update Industry | <?php echo $siteEndTitle; ?></title>
  <?php require '../../layout/general_css.php' ?>
  <link rel="stylesheet" href="<?php echo $file_dir; ?>assets/css/footer.custom.css">
  <link rel="stylesheet" href="<?php echo $file_dir; ?>assets/bundles/bootstrap-daterangepicker/daterangepicker.css">
</head>

<body>
    <div class="loader"></div>
    <div id="app">
        <div class="main-wrapper main-wrapper-1">
        <div class="navbar-bg"></div>
        <?php require '../../layout/header.php' ?>
        <?php require '../../layout/sidebar_admin.php' ?>
        <!-- Main Content -->
        <div class="main-content">
            <section class="section">
            <div class="section-body">
                <div class="card">
                    <form method="post">
                        <div class="card-header"></div>
                        <div class="card-body">
                            <?php require '../../layout/alert.php' ?>
                            <div class="card-header">
                                <h3 class="d-inline">Select Company Industry:</h3>
                                <a class="btn btn-primary btn-icon icon-left header-a" href="new-assessment"><i class="fas fa-plus"></i> Back To Assessments</a>
                            </div>
                            <div class="card-body">
                                <div class="form-group">
									<label>Available Industries: </label>
									<?php echo getIndustries($risk__industry, $con); ?>

								</div>
                                <div class="form-group">
									<button type="submit" class="btn btn-md btn-primary" name="save">Save Industry</button>
								</div>
                            </div>
                        </div>
                        <div class="card-footer"></div>
                    </form>
                </div>
            </div>
            </section>
        </div>
        <?php require '../../layout/footer.php' ?>
        </footer>
        </div>
    </div>
    <?php require '../../layout/general_js.php' ?>
    <script src="<?php echo $file_dir; ?>assets/bundles/bootstrap-daterangepicker/daterangepicker.js"></script>
    <style>
        textarea{
            min-height: 120px !important;
        }
    </style>
    <script>$("#date").datepicker();</script>
</body>
</html>