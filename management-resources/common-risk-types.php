<?php require '../layout/config.php'; $file_dir = '../';?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport">
  <title>Common Risk Types | <?php echo APP_TITLE; ?></title>
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
        <div class="main-content" style="min-height: 0px !important;">
            <section class="section">
            <div class="section-body">
                <div class="card" style="padding:20px;">
                    <div class="card-header">
                        <h3 class='card-header-h'>Common Risk Types</h3>
                    </div>
                    <div class="card-body custom-p">                    
                        <p>The level and type of risk that you need to consider will vary with the type of business you operate. However, there are some common categories which you can use to guide your thinking and the development of your risk management plan. </p>
                        
                        <p>The following lists the official Basel II defined 7 risk event types with examples for each category:</p>
                        <ul style="font-weight: 400;">
                            <li><strong>Internal Fraud</strong> - misappropriation of assets, tax evasion, intentional mismarking of positions, bribery</li>
                            <li><strong>External Fraud</strong> - theft of information, hacking damage, third-party theft and forgery</li>
                            <li><strong>Employment Practices and Workplace Safety</strong> - discrimination, workers compensation, employee health and safety</li>
                            <li><strong>Clients, Products, and Business Practice</strong> - market manipulation, antitrust, improper trade, product defects, fiduciary breaches, account churning</li>
                            <li><strong>Damage to Physical Assets</strong> - natural disasters, terrorism, vandalism</li>                	
                            <li><strong>Business Disruption and Systems Failures</strong> - utility disruptions, software failures, hardware failures</li>
                            <li><strong>Execution, Delivery, and Process Management</strong> - data entry errors, accounting errors, failed mandatory reporting, negligent loss of client assets</li>
                        </ul>
                        <p>Operational risk management is the oversight of loss resulting from inadequate or failed internal processes; systems; people; or external events.</p>

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
            margin-top: -57px !important;
        }
        @media (max-width: 767px) {
            .main-footer{
                margin-top: -17px !important;
            }
        }
    </style>
</body>
</html>
