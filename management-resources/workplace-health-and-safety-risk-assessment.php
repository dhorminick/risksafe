<?php require '../layout/config.php';$file_dir = '../'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport">
  <title>Workplace Health &amp; Safety Risk Assessment | <?php echo APP_TITLE; ?></title>
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
                <div class="card" style="padding: 20px 0px 20px 0px;font-weight:400;">
					<div class="card-header"><h3 class="card-header-h">Workplace Health &amp; Safety Risk Assessment</h3></div>
                    <div class="card-body custom-p">
						<p>Every business owner should manage health &amp; safety of your business and control risks. Health and safety risk assessments should be carried out with your key staff who knows the environment you operate in. Risks are triggered when any changes affect your work activities like;</p>
						<ul>
							<li>Starting a new business, a project or planning an event</li>
							<li>Changing work practices, procedures, environment or undertaking any high risk work</li>
							<li>Purchasing new or used equipment or using new substances</li>
							<li>Planning to improve productivity or reduce costs</li>
							<li>New hazards have been identified</li>
							<li>Responding to workplace incidents</li>
							<li>Responding to concerns raised by workers, health and safety representatives or others at the workplace</li>
							<li>Sending a student or apprentice on placement</li>
						</ul> 
						
						<p>For any of the above, as the owner you should:</p>
						
						<ul>
							<li>Identifying foreseeable hazards and the risks associated</li>
							<li>Assessing the risks - determining the consequence and likelihood of the risk occurring</li>
							<li>Controlling the risk – implementing control measures to eliminate or reduce the risk</li>
							<li>Monitor and review the above process</li>
						</ul> 
						
						<p>Please note that legal obligations of employers vary according to circumstances. You may wish to seek independent legal advice on what is applicable to your situation.</p>


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