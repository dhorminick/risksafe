<?php
	include 'layout/config.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport">
  <title>Our Pricing Plans | <?php echo APP_TITLE; ?></title>
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
			<div class="intro-header custom pricing">
                <div class="intro-message">
                    <h2>Our Pricing Plans</h2>
                    <div class="intro-breadcrumbs">
                        <a href="/" class="links">Home</a>
                        <a>Pricing</a>
                    </div>
                </div>
            </div>
            <section class="section">
            <div class="section-body">
				<div class="card">
					<div class="card-body">
						<div class="card-header">
							<h2 class="section-heading center" style="width:100%;text-align: center;">Flexible and Affordable Pricing</h2>
						</div>
						<div class="card-body custom-p" style="text-align: center;">
							<p>Easy to Use Product that gives you Peace of Mind at Affordable Pricing.<p>
							
							<p>We’ve made super affordable Risk Management tools that traditionally cost thousands of dollars for large enterprises.  We are extremely passionate about helping those that need to manage their risks the most to thrive and survive; Small to Medium Businesses, Fintechs, Community organisations.</p>
							
							<p>Simple pricing, $49 a month to conduct risk assessments whenever you like and monitor controls and treatment plans.</p>
						</div>
						
						<div class="card-body"style="display: flex;align-items:center;justify-content:center;">
							<div class="row" style="width: 100%;display: flex;align-items:center;justify-content:center;">
								<div class="col-12 col-lg-8">
									<div class="pricing pricing-highlight">
									<div class="pricing-title">
										Full Package
									</div>
									<div class="pricing-padding">
										<div class="pricing-price">
										<div>$49</div>
										<div>per month</div>
										</div>
										<div class="pricing-details">
										<div class="pricing-item">
											<div class="pricing-item-icon"><i class="fas fa-check"></i></div>
											<div class="pricing-item-label">20 Managed Users</div>
										</div>
										<div class="pricing-item">
											<div class="pricing-item-icon"><i class="fas fa-check"></i></div>
											<div class="pricing-item-label">Unlimited Risk Assessments</div>
										</div>
										<div class="pricing-item">
											<div class="pricing-item-icon"><i class="fas fa-check"></i></div>
											<div class="pricing-item-label">Unlimited Audits</div>
										</div>
										<div class="pricing-item">
											<div class="pricing-item-icon"><i class="fas fa-check"></i></div>
											<div class="pricing-item-label">Unlimited Compliance Standard</div>
										</div>
										<div class="pricing-item">
											<div class="pricing-item-icon"><i class="fas fa-check"></i></div>
											<div class="pricing-item-label">Unlimited Incidents</div>
										</div>
										<div class="pricing-item">
											<div class="pricing-item-icon"><i class="fas fa-check"></i></div>
											<div class="pricing-item-label">Unlimited Business Continuity Plan</div>
										</div>
										<div class="pricing-item">
											<div class="pricing-item-icon"><i class="fas fa-check"></i></div>
											<div class="pricing-item-label">Unlimited Access To Reports</div>
										</div>
										</div>
									</div>
									<div class="pricing-cta">
										<a href="/register.php"><span class="show-sm">Start Free Trial</span><span class="hide-sm">Start Free 14 days Trial Today</span> <i class="fas fa-arrow-right"></i></a>
									</div>
									</div>
								</div>
							</div>
						</div>
		
						<hr style="border-color:#f9f9f9;"> 
		
						<div class="card-body">
							<h3 style="width: 100%;text-align:center;margin-bottom:20px;">Our Plan Includes:</h3>
							<div class="row">
								<div class="col-md-6 col-lg-4 col-12">
									<ul class="lead">
										<li>Create a Simple Business Risk Assessment</li>
										<li>Setup your business context to understand Key Risks </li>
										<li>Simple Drop Down selections of Risk categories </li>					
										<li>Specify details of your relevant risks</li>
										<li>Risk Dashboard </li>
									</ul>	
								</div>
								<div class="col-md-6 col-lg-4 col-12">
									<ul class="lead">	
										<li>Compliance standard</li>
										<li>Risk Rating based on Consequence and Likelihood</li>
										<li>List your current Controls or identify gaps</li>					
										<li>Conduct Audit of Controls </li>					
										<li>Test Effectiveness of Controls </li>
									</ul>	
								</div>
								<div class="col-md-6 col-lg-4 col-12">
									<ul class="lead">		
										<li>Policy </li>	
										<li>Create Incidents </li>		
										<li>Export Reports in PDF and Excel </li>
										<li>Set up Treatment Plans and assign an Owner and Due Date</li>
										<li>Monthly or annual credit card payment</li>
									</ul>	
								</div>
							</div>	
							<div style="text-align: center;font-weight:400;">
								<strong>Managed Service</strong> – Need some help to create your registers, controls and standards?  One of our Risk Professionals can support you from Demo to go live.
							</div>
						</div>
					</div>
				</div>
			</div>
			</section>
        </div>   
        </div>
        <?php require 'layout/footer.php' ?>
        </footer>
        </div>
    </div>
    <?php require 'layout/general_js.php' ?>
	<style>
		.main-footer {
			margin-top: -17px !important;
		}
	</style>
</body>
</html>
