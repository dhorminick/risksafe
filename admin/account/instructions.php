<?php
    session_start();
    $file_dir = '../../';
    if (isset($_SESSION["loggedIn"]) == true || isset($_SESSION["loggedIn"]) === true) {
        $signedIn = true;
    } else {
        header('Location: .'.$file_dir.'auth/sign-in?r=/account/instructions');
        exit();
    }
    $message = [];
    include '../../layout/db.php';
    include '../../layout/admin__config.php';
    
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
                <div class="card" style="padding: 10px;">
                    <?php include '../../layout/alert.php'; ?>
                    <div class="card-header">
                        <h3 class="card-header-h">RiskSafe Risk Assessment Manual</h3>
                    </div>
                    <div class="card-body custom-p">
                        <h3 class="manual-header">Assessment Details</h3>
                        <p>Complete details of your Organisation, Team/Department, Assessor and Date. </p>
                        <p>Note 1: Determine the next review date. Normally the next review date is up to one year ahead. The Risk assessmeny is also reviewed:<br/> 
                            (1) When there are changes in work processes/activities<br/>
                            (2) After any accidents/incidents</p>
    
    
                        <h3 class="manual-header">Risk Identification</h3>
                        <p>Identify risks or hazards with your organisation or a particular team, business unit or a process.  Carefully go through each of the Risk categories to understand if any are valid risks for your organisation (e.g. Legal, Health & safety).</p>  
                        <p>You can use the risks by going through each of the risks categories.  There is also free field text for you to enter specific information that relates to your risk.  </p>
                        
                        
                        <h3 class="manual-header">Risk Evaluation</h3>
                        <p>Likelihood of occurrence of a risk or an accident is defined as the probability that the risk event will happen. Choose the likelihood from a value between 1 and 5. Consider the records of such events happening in the past when deciding on the likelihood.</p>
                                       
                        <p>Consequence is the degree or extent of risk or harm caused by the hazards. Choose the most likely severity from a value between 1 and 5, rather than the most extreme.<br/>                
                        Refer to the Risk Matrix tab for the likelihood and consequence table.</p>
    
                        <p>Risk Rating is calculated using the combination of Likelihood and Consequence.<br/>                                                                                                      
                        Risk rating = Likelihood X Consequence</p>
    
    
                        <h3 class="manual-header">Control Actions</h3>
                        <p>Indicate risk control measures that are already in place to eliminate or minimise risks.<br/>
                        Note, where there are good controls in place, the consequence or likelihood or a risk can be reduced, bringing down the 'inherent' risk rating to a 'residual' risk rating.</p>	
    
    
                        <h3 class="manual-header">Treatment Plans</h3>
                        <p>Where there are ineffective controls or gaps in processes identified, you can create treatment plans to minimise risk exposure.</p>  
                        <p>You can also assign an owner to complete these treatments and due dates. </p>
                        
                        <h3 class="manual-header">Audit of Controls</h3>
                        <p>You can test each of the controls you have listed as a control to minimise your risk.  In order to do this, create a list of expected outcomes (how should the control be functioning ideally).</p>  
                        <p>The assessor should then test against each of these expected outcomes by either observation, interview, re-performance.  Once you are comfortable, assess whether the control is effective or ineffective.</p>   
                        <p>If the control is ineffective, consider creating a treament plan.  Also identify when the next test should occur.  </p>
    
                        <h3 class="manual-header">Dashboard</h3>
                        <p>The dashboard should will show an overview of number of risks, controls and treatments that have been identified.</p>  
                        <p>You can export reports through the dashboard.  </p>
                        
                        <h3 class="manual-header">Treatments</h3>
                        <p>List out all of the treaments that are currently open and provide an update on the status.  </p>
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
    .manual-header{
        font-size: 17px !important;
        margin-top: 20px !important;
        color: var(--custom-primary);
    }
    .card-header-h{
        color: var(--custom-primary);
    }
    .custom-p p{
        margin-bottom: 5px !important;
    }
</style>