<?php
	include 'layout/config.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport">
  <title>About Us | <?php echo APP_TITLE; ?></title>
  <?php require 'layout/general_css.php' ?>
  <link rel="stylesheet" href="assets/css/index.custom.css">
  <link rel="stylesheet" href="assets/css/main.css">
</head>

<body>
    <div class="loader"></div>
    <div id="app">
        <div class="main-wrapper main-wrapper-1">
        <div class="navbar-bg"></div>
        <?php require 'layout/header_main.php' ?>
        <!-- Main Content -->
        <div class="main-content">
            <section class="header-section">
				<div class="">
					<div class="intro-breadcrumbs custom">
                        <a href="/" class="links">Home</a>
                        <a href="">About Us</a>
                    </div>
					<h1 class="h">About RiskSafe</h1>
					<div class="header-text">
						<div>With more simplified system design, <span class="risksafe">RiskSafe</span> was built by a team is made up of Risk Managers with experience in banking, safety, startups and small business.</div>
					</div>
				</div>
			</section>
            <div class="card" style="margin-top: 25px !important;">
                <div class="card-body">
                    <div class="row custom-row">
                        <div class="col-lg-6 col-12">
                            <img src="assets/img/risk.jpg" class="section-text-image r" alt="About RiskSafe">
                        </div>
                        <div class="col-lg-6 col-12 section-text">
                            <h2 class="section-text-heading" style="text-align: left !important;">Meet Our Team</h2>
                            <p class="section-p">
                                Text Here
                            </p>
                        </div>
                    </div>
                </div>
            </div>
            <section class="section" style="margin-top: -25px !important;">
            <div class="section-body card">
                <div class="card-header"> 
                    <h2 class="section-text-heading">What We Aim To Achieve</h2> 
                </div>
                <div class="card-body"> 
                    <p class="section-p">Why do so many startups, small businesses fail around the world? Poor Risk Management.  It could due to:</p>
                    <ul class="section-ul">
                        <li>Lack of understanding of regulatory requirements such as AML</li>
                        <li>Lack of Fraud risk mitigation</li>
                        <li>Non adherence to Health & Safety requirements</li>
                        <li>In adequate management of Strategic risks</li>
                    </ul>
                    <p class="section-p -p">Entrepreneurs by nature are risk takers.  But the most successful risk takers protected their bottom line risk above all else. The freedom of being your own boss comes with added responsibilities like; ‘How do I protect myself when things go wrong in business?’</p>
                    <p class="section-p -p">RiskSafe helps entrepreneurs understand their business risks and help them move forward with confidence.</p> 
                    <p class="section-p -p"><strong>Forget the paper forms - our tools will help you understand which risks to manage so you can focus on doing what you love.</strong></p>  
                    <p class="section-p -p">Business growth requires a solid foundation and a solid foundation is built on a powerful risk-management plan.</p> 
                    
                    <p class="bold">
                        Get in touch: <a href="mailto:jay@RiskSafe.co" class="bb">jay@RiskSafe.co</a>
                    </p>

                </div>
                <div class="card-footer"> 
                </div>                
            </div>
            </section>
        </div>
        <?php require 'layout/footer.php' ?>
        </footer>
        </div>
    </div>
    <?php require 'layout/general_js.php' ?>
</body>
<style>
    .section-body{
        padding: 20px;
    }
    .section-ul{
        margin-left: 0px !important;
        padding-left: 20px !important;
        color: inherit !important;
    }
    .section-text-heading{
        width:100%;
        text-align: center;
    }
    p.section-p.-p{
        margin-bottom: 25px !important;
    }
    p.section-p.-p strong{
        font-weight: bold !important;
    }
    .main-footer{
        margin-top: -18px;
    }
</style>
</html>
