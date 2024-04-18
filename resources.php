<?php
	include 'layout/config.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport">
  <title>Our Resources | <?php echo APP_TITLE; ?></title>
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
            <div class="intro-header custom resources" style="display: none;">
                <div class="intro-message">
                    <h2>Our Resources</h2>
                    <div class="intro-breadcrumbs">
                        <a href="/" class="links">Home</a>
                        <a href="">Our Resources</a>
                    </div>
                </div>
            </div>
			<section class="header-section">
				<div class="">
					<div class="intro-breadcrumbs custom">
                        <a href="/" class="bb">Home</a>
                        <a href="">Our Resources</a>
                    </div>
					<h1 class="h">RiskSafe Resources</h1>
					<div class="header-text">
						<div>Free resources in all shapes and kinds made by our experts at <span class="risksafe">RiskSafe</span> on Risk Assessments, Management, Treatments and Maintenance for different possible outcomes and scenerios.</div>
					</div>
				</div>
			</section>
            <section class="section">
            <div class="section-body">
				<div class="card" style="display:none;">
					<div class="card-header"> 
						<h2 class="section-text-heading">Risk Management</h2> 
					</div>
					<div class="card-body row"> 
						<div class="col-lg-7">
						<p>When you remove risk, you increase the value of your business.  Success comes to those who quickly identify and eliminate risks in the right order.</p>
						<p>The key question is “What’s the most important uncertainty?” and the answer should be targeted early.</p>
						<p>Risk management is all about identifying and mitigating the uncertainties — especially the company killers.</p>
						<p>Insurance is just one element of Risk Management strategies - policy that protects specific assets, risks, or contingencies.</p>  
						<p>Completing a risk assessment will help you eliminate costly problems and may also help reduce insurance claims and premiums.</p> 
						</div>
						<div class="col-lg-5 hide-sm"><img src="assets/img/banner-bg-old.jpg"  class="section-text-image" alt="image" /></div> 
					</div>
					<div class="card-footer"> 
					</div>  
				</div>   
				<div class="card" style="display:none;">
					<div class="card-header"> 
						<h2 class="section-text-heading">Risk Management – Overview in 5 Steps</h2> 
					</div>
					<div class="card-body light-text"> 
						1. Define Business Context
							<ul>
								<li>What environment do you operate in</li>
							</ul>

						2. Identify the Risks & Hazards
							<ul>
								<li>Find out what could go wrong or cause harm in your business</li>
							</ul>
						3. Assess risks
							<ul>
								<li>Understand the nature of the consequence that could becaused by the Risk or Hazard and the likelihood of it happening</li>
							</ul>
						4. Control risks
							<ul>
								<li>Implement the most effective control measure thatis realistic</li>
							</ul>
						5. Review the control measures
							<ul>
								<li>Ensure controls are working as planned</li>
							</ul> 
						<hr>
						<p class="bold">When should you do risk assessments?</p>
						<ul>
							<li>As soon as possible, if a risk assessment has not yetbeen completed for your work tasks / activities</li>
							<li>Whenever any new work is planned</li>
							<li>Whenever a significant change occurs</li>
							<li>After an incident or an accident</li>
						</ul>
					</div>
					<div class="card-footer"> 
					</div>  
				</div>   
				<div class="card r">
					<div class="card-body">
						<div class="row custom-row resources">
							<div class="col-lg-4 col-12">
								<div class="resource-image" style="--bg-image: url('../images/What-is-a-Four-Factor-Breach-Risk-Assessment.png')"></div>
								<div class="resource-text">
									<h3 class="resource-header">Conducting a Risk Assessment</h3>
									<p class="resource-desc">
										<?php 
											$string = "Describe the risk. What can happen? Consider how and why it can happen. What happens if the risk eventuates? Systematically analyse your systems and processes to identify critical points. Conduct a review of your records and reports to identify things that have gone wrong in the past.";
											echo substr($string,0,200).'...'; 
										?>
									</p>
									<div class="r-card-footer"><a href="/management-resources/control-self-assessment.php" class="bb">Read More</a></div>
								</div>
							</div>
							<div class="col-lg-4 col-12">
								<div class="resource-image" style="--bg-image: url('../images/Types-of-Risk-in-Insurance.webp')"></div>
								<div class="resource-text">
									<h3 class="resource-header">Common Risk Types</h3>
									<p class="resource-desc">
										<?php 
											$string = "The level and type of risk that you need to consider will vary with the type of business you operate. However, there are some common categories which you can use to guide your thinking and the development of your risk management plan.";
											echo substr($string,0,200).'...'; 
										?>
									</p>
									<div class="r-card-footer"><a href="management-resources/common-risk-types.php" class="bb">Read More</a></div>
								</div>
							</div>
							<div class="col-lg-4 col-12">
								<div class="resource-image" style="--bg-image: url('../images/control-self-assessments-blog-20161123-k12532.jpg')"></div>
								<div class="resource-text">
									<h3 class="resource-header">Control Self Assessment (CSA)</h3>
									<p class="resource-desc">
										<?php 
											$string = "Control Self Assessment (CSA) is a management tool designed to assist work teams to be more effective in achieving their objectives and managing their related risks. CSA is a highly interactive and collaborative process that is designed to focus on processes and issues that are important to a business or organisation. The CSA should include the people actually doing the work - not just those managing a process.";
											echo substr($string,0,200).'...'; 
										?>
									</p>
									<div class="r-card-footer"><a href="management-resources/control-self-assessment.php" class="bb">Read More</a></div>
								</div>
							</div>
							<div class="col-lg-4 col-12">
								<div class="resource-image" style="--bg-image: url('../images/Scytale_Blog-images-03-3-1-768x511.jpg.webp')"></div>
								<div class="resource-text">
									<h3 class="resource-header">How can a Controls Assessment help your business?</h3>
									<p class="resource-desc">
										<?php 
											$string = "How can a Controls Assessment help your business? Stay ahead of the competition, Comply with regulatory or licensing requirements, Deal with new technologies, Meet quality system requirements in the supply chain, Improve safety, Boost productivity and profitability, Keep customers, Allow for expansion, Reduce employee turnover, Increase efficiency, Decrease the need for supervision.";
											echo substr($string,0,200).'...'; 
										?>
									</p>
									<div class="r-card-footer"><a href="management-resources/controls-assessment-for-business.php" class="bb">Read More</a></div>
								</div>
							</div>
							<div class="col-lg-4 col-12">
								<div class="resource-image" style="--bg-image: url('../images/examples-of-health-and-safety-risk-assessmennts-464007914.jpg')"></div>
								<div class="resource-text">
									<h3 class="resource-header">Workplace Health &amp; Safety Risk Assessments</h3>
									<p class="resource-desc">
										<?php 
											$string = "Every business owner should manage health & safety of your business and control risks. Health and safety risk assessments should be carried out with your key staff who knows the environment you operate in. Risks are triggered when any changes affect your work activities like";
											echo substr($string,0,200).'...'; 
										?>
									</p>
									<div class="r-card-footer"><a href="management-resources/workplace-health-and-safety-risk-assessment.php" class="bb">Read More</a></div>
								</div>
							</div>
							<div class="col-lg-4 col-12">
								<div class="resource-image" style="--bg-image: url('../images/what-insurance-does-a-community-group-need.jpg')"></div>
								<div class="resource-text">
									<h3 class="resource-header">Risk Management for Community &amp; Charity Groups</h3>
									<p class="resource-desc">
										<?php 
											$string = "The responsibility for the management and control of a charity rests with the committee or trustee body and therefore their involvement in the key aspects of the risk management process is essential. In all but the smallest charities, the trustees are likely to delegate elements of the risk management process to staff or professional advisers.";
											echo substr($string,0,200).'...'; 
										?>
									</p>
									<div class="r-card-footer"><a href="management-resources/risk-management-for-community-and-charity-groups.php" class="bb">Read More</a></div>
								</div>
							</div>
							<div class="col-lg-4 col-12">
								<div class="resource-image" style="--bg-image: url('../images/7915_bigstock_Businessman_Collects_Wooden_Pu_302963389.jpg')"></div>
								<div class="resource-text">
									<h3 class="resource-header">Insurable Risks</h3>
									<p class="resource-desc">
										<?php 
											$string = "Insurance is a small part of the whole Risk Management process. It allows your organisation to receive financial compensation in the case of loss through the operation of your organisation. However, it does not prevent an incident occurring. Insurance should be employed as a “safety net”in case of an incident.";
											echo substr($string,0,200).'...'; 
										?>
									</p>
									<div class="r-card-footer"><a href="management-resources/insurable-risks.php" class="bb">Read More</a></div>
								</div>
							</div>
							<div class="col-lg-4 col-12">
								<div class="resource-image" style="--bg-image: url('../images/shutterstock_14082997881.jpg')"></div>
								<div class="resource-text">
									<h3 class="resource-header">How to Reduce Your Insurance Premiums</h3>
									<p class="resource-desc">
										<?php 
											$string = "Many of the risks that could edge your business into a higher premium bracket can be minimised, reassuring your insurance company about your reduced level of risk. As work environments become safer, the number of workers’ compensation claims continues to decline. By performing regular safety checks, reinforcement of safe working behaviour of employees, eliminating hazards that cause injuries can reduce workers compensation premiums.";
											echo substr($string,0,200).'...'; 
										?>
									</p>
									<div class="r-card-footer"><a href="management-resources/reduce-your-insurance-premiums.php" class="bb">Read More</a></div>
								</div>
							</div>

						</div>
					</div>      
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
	.card.r .header-text{
		font-weight: 400;
		font-size: 16px;
		text-align: center !important;
		width: 100%;
	}
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
        margin-top: -17px;
    }
	.thumbnail {
		height: 140px;
		padding: 10px;
	}
	.r-card-header {
	font-weight: bolder;
	background-color: var(--color6777ef);
	padding: 10px 0;
	border-radius: 5px;
	color: white;
	}
	.r-card-body {
	margin: 15px 0px;
	font-size: 16px;
	font-weight: 100;
	/* padding: 10px; */
	}
	.thumbnail-custom {
	/* height: 140px; */
	padding: 15px 10px;
	}

	.thumbnail-custom {
	display: block;
	margin-bottom: 20px;
	border: 1px solid #ddd;
	border-radius: 4px;
	-webkit-transition: border 0.2s ease-in-out;
	-o-transition: border 0.2s ease-in-out;
	transition: border 0.2s ease-in-out;
	}
	.row.resources .col-lg-4.col-12{
		text-align: center;
	}
	.light-text{
		font-weight: 100;
	}
	p.bold{
	font-weight: bold !important;
	}
	.card{
		padding: 0px 0px;
		margin: 5px -30px 10px -30px;
	}
</style>
</html>
