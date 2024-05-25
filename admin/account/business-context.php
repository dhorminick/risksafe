<?php
    session_start();
    $file_dir = '../../';
    if (isset($_SESSION["loggedIn"]) == true || isset($_SESSION["loggedIn"]) === true) {
        $signedIn = true;
    } else {
        header('Location: '.$file_dir.'auth/sign-in?r=/account/business-context');
        exit();
    }
    $message = [];
    include '../../layout/db.php';
    include '../../layout/admin__config.php';
    
    if (isset($_POST['save-context'])) {
       $context_objectives = sanitizePlus($_POST["objectives"]); 
       $context_processes = sanitizePlus($_POST["processes"]); 
       $context_products = sanitizePlus($_POST["products"]); 
       $context_projects = sanitizePlus($_POST["projects"]); 
       $context_systems = sanitizePlus($_POST["systems"]); 
       $context_relationships = sanitizePlus($_POST["relationships"]); 
       $context_internallosses = sanitizePlus($_POST["internallosses"]); 
       $context_externallosses = sanitizePlus($_POST["externallosses"]); 
       $context_competitors = sanitizePlus($_POST["competitors"]); 
       $context_environment = sanitizePlus($_POST["environment"]); 
       $context_regulatory = sanitizePlus($_POST["regulatory"]);

       $UpdateContext = "UPDATE as_context SET cx_objectives= '$context_objectives', cx_processes= '$context_processes', cx_products= '$context_products', cx_projects= '$context_projects', cx_systems= '$context_systems', cx_relation= '$context_relationships', cx_internallosses= '$context_internallosses', cx_externallosses= '$context_externallosses', cx_competitors= '$context_competitors', cx_environment= '$context_environment',cx_regulatory= '$context_regulatory' WHERE cx_user = '$userId'";
       $ContextUpdated = $con->query($UpdateContext);
       if ($ContextUpdated) {
           array_push($message, 'Context Updated Successfully!!');
       }else{
           array_push($message, 'Error 502: Context Error!!');
       }
    }

    $ConfirmUserExist = "SELECT * FROM as_context WHERE cx_user = '$userId'";
    $ConfirmedUser = $con->query($ConfirmUserExist);
    if ($ConfirmedUser->num_rows > 0) {
       $row = $ConfirmedUser->fetch_assoc();
       $filtered = array_map('htmlspecialchars', array_map('stripslashes', $row));
       $datainfo = $filtered;
    }else{
       $datainfo = [];
       echo 'Error';
       exit();
    }

    

     
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport">
  <title>RiskSafe Manual | <?php echo $siteEndTitle; ?></title>
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
        <!-- Main Content -->
        <div class="main-content">
            <section class="section">
            <div class="section-body">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-header-h">RiskSafe Risk Assessment Manual</h3>
                    </div>
                    <div class="card-body custom-p">
                        <?php include '../../layout/alert.php'; ?>
                        <form method="post">
                            <div class="form-group">
                              <label>Business Strategies and Objectives</label>
                              <textarea name="objectives" class="form-control" placeholder="Enter business strategies and objectives..."><?php echo isset($datainfo["cx_objectives"]) ? $datainfo["cx_objectives"] : ''; ?></textarea>
                            </div>
                            <div class="form-group">
                              <label>Critical Business Processes</label>
                              <textarea name="processes" class="form-control" placeholder="Enter critical processes..."><?php echo isset($datainfo["cx_processes"]) ? $datainfo["cx_processes"]:'';?></textarea>
                            </div>
                                               <div class="form-group">
                              <label>Products &amp; Services</label>
                              <textarea name="products" class="form-control" placeholder="Enter products & services..."><?php echo isset($datainfo["cx_products"])? $datainfo["cx_products"]:'' ;?></textarea>            
                            </div>
                                               <div class="form-group">
                              <label>Projects &amp; Initiatives</label>
                              <textarea name="projects" class="form-control" placeholder="Enter projecta & initiatives..."><?php echo isset($datainfo["cx_projects"]) ? $datainfo["cx_projects"]:'';?></textarea>            
                            </div>
                                               <div class="form-group">
                              <label>Key / Critical Technology Systems</label>
                              <textarea name="systems" class="form-control" placeholder="Enter key systems..."><?php echo isset($datainfo["cx_systems"])? $datainfo["cx_systems"]:'';?></textarea>             
                            </div>
                                               <div class="form-group">
                              <label>Key Relationships (Internal &amp; 3rd Party Outsourcing)</label>
                              <textarea name="relationships" class="form-control" placeholder="Enter key relationships..."><?php echo isset($datainfo["cx_relation"]) ? $datainfo["cx_relation"]:'';?></textarea>            
                            </div>
                                               <div class="form-group">
                              <label>Previous Internal Losses</label>
                              <textarea name="internallosses" class="form-control" placeholder="Enter previous internal losses..."><?php echo isset($datainfo["cx_internallosses"]) ? $datainfo["cx_internallosses"] : '';?></textarea>            
                            </div>
                                               <div class="form-group">
                              <label>Relevant External Loss Events</label>
                              <textarea name="externallosses" class="form-control" placeholder="Enter previous external losses..."><?php echo isset($datainfo["cx_externallosses"]) ? $datainfo["cx_externallosses"]:'';?></textarea>            
                            </div>
                                               <div class="form-group">
                              <label>Competitors</label>
                              <textarea name="competitors" class="form-control" placeholder="Enter competitors..."><?php echo isset($datainfo["cx_competitors"]) ? $datainfo["cx_competitors"]:'';?></textarea>
                            </div>
                                               <div class="form-group">
                              <label>Changes in External Environment</label>
                              <textarea name="environment" class="form-control" placeholder="Enter changes in the environment..."><?php echo isset($datainfo["cx_environment"]) ? $datainfo["cx_environment"]: '';?></textarea>
                            </div>
                                               <div class="form-group">
                              <label>Regulatory Environment</label>
                              <textarea name="regulatory" class="form-control" placeholder="Enter regulatory environment..."><?php echo isset($datainfo["cx_regulatory"])? $datainfo["cx_regulatory"]:'';?></textarea>            
                            </div>
                            <div class="form-group">
                              <button type="submit" class="btn btn-md btn-primary" name="save-context">Save Business Context</button>
                            </div>
                          </form>
      		
                    </div>
                </div>
            </div>
            </section>
        </div>
        <?php require '../../layout/footer.php' ?>
        </footer>
        </div>
    </div>
    <?php require '../../layout/general_js.php' ?>
</body>
</html>
<style>
    textarea{
        min-height: 100px;
        resize: vertical;
    }
    .custom-p p{
        margin-bottom: 5px !important;
    }
    .card {
        padding: 10px 0;
    }
</style>