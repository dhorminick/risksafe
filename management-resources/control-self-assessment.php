<?php require '../layout/config.php';$file_dir = '../'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport">
  <title>Control Self Assessment | <?php echo APP_TITLE; ?></title>
  <?php require '../layout/general_css.php' ?>
  <link rel="stylesheet" href="../assets/css/index.custom.css">
  <link rel="stylesheet" href="../assets/css/main.css">
</head>

<body>
    <div class="loader"></div>
    <div id="app">
        <div class="main-wrapper main-wrapper-1">
        <div class="navbar-bg"></div>
        <?php require '../layout/header_main.php' ?>
        <!-- Main Content -->
        <div class="main-content">
            <section class="section">
            <div class="section-body">
                <div class="card" style="padding: 20px 0px 20px 0px;">
                    <div class="card-header"><h3 class="card-header-h">Control Self Assessment</h3></div>
                    <div class="card-body custom-p">
                        <p>Control Self Assessment (CSA) is a management tool designed to assist work teams to be more effective in achieving their objectives and managing their related risks.</p>
                        <p>CSA is a highly interactive and collaborative process that is designed to focus on processes and issues that are important to a business or organisation.</p>  
                        <p>The CSA should include the people actually doing the work - not just those managing a process.</p> 
                        <p>Control measures that have been implemented must be reviewed, and if necessary, revised to make sure they work as planned.</p>
                        <p>There are certain situations where you must review your control measures, including:</p>
                        
                        <ul style="font-weight: 400;">
                            <li>When the control measure is not effective in controlling the risk e.g. when an incident occurs</li>
                            <li>Before a change at the workplace that is likely to give rise to a new or different health and safety risk that the control measure may not effectively control</li>
                            <li>If a new hazard or risk is identified</li>
                            <li>If the results of consultation indicate that a review is necessary</li>
                            <li>If a Health and Safety Representative requests a review</li>
                        </ul>
                    </div>
                </div>
            </div>
            </section>
        </div>
        <?php require '../layout/footer.php' ?>
        </footer>
        </div>
    </div>
    <?php require '../layout/general_js.php' ?>
    <style>
        .main-footer{
            margin-top: -75px !important;
        }
        @media (max-width: 767px) {
            .main-footer{
                margin-top: -17px !important;
            }
        }
    </style>
</body>
</html>