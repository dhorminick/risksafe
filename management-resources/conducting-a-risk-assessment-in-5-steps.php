<?php require '../layout/config.php';$file_dir = '../'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport">
  <title>Conducting a Risk Assessment in 5 Steps | <?php echo APP_TITLE; ?></title>
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
					<div class="card-header">
						<h3 class="card-header-h">Conducting a Risk Assessment in "5" Steps</h3>
					</div>
                    <div class="card-body custom-p">
						<div class="card-body">
							<ul style="list-style: none;padding:0;" class="li-list">
								<li><a class='smooth-a' href="#step-1">Step 1: Define the risk context</a></li>
								<li><a class='smooth-a' href="#step-2">Step 2: Risk identification</a></li>
								<li><a class='smooth-a' href="#step-3">Step 3: Risk assessment</a></li>
								<li><a class='smooth-a' href="#step-4">Step 4: Control strategies</a></li>
								<li><a class='smooth-a' href="#step-5">Step 5: Monitoring and review</a></li>
							</ul>
						</div>

						<div class="card-body custom-p" id="step-1">
							<h3 class="sub-header">Step 1: Define the risk context</h3>
							<p><b>Identify where or under what circumstances the risk occurs.</b></p> 
							<p>For example:a particular work site, department, work section, retail shop branch or after severe weather.</p>
						</div>

						<div class="card-body custom-p" id="step-2">
							<h3 class="sub-header">Step 2: Risk identification</h3>
							<p><b>Identify risks that are likely to affect the achievement of your business goals.</b></p>
							<p>Describe the risk. What can happen? Consider how and why it can happen. What happens if the risk eventuates?</p>
							<p>Systematically analyse your systems and processes to identify critical points.</p>
							<p>Conduct a review of your records and reports to identify things that have gone wrong in the past.</p>
							<p>Brainstorm with your employees or co-workers.</p>
							<p>Identify what your business does to control this risk and rate the effectiveness of these.</p>
						</div>

						<div class="card-body custom-p" id="step-3">
							<h3 class="sub-header">Step 3: Risk assessment</h3>
							<p><b>Analyse the likelihood and consequences of each identified risk.</b></p>
							<p>What is the likelihood of the risk occurring? What is the consequence of the risk event?</p>
							<p>Select the description that best describes the likelihood and consequenceof the risk occurring (with existing control measures in place).</p>
							<p>Risk Rating = Consequence Rating x Likelihood Rating</p>
							<p>On the risk analysis matrix find the intersection of the likelihood and consequence ratings selected for the risk.</p>
						</div>

						<div class="card-body custom-p" id="step-4">
							<h3 class="sub-header">Step 4: Control strategies</h3>
							<p>Develop cost effective options for treating each risk.</p> 
							<p>Determine the best treatment option from the methods below.</p>
							<ul style="list-style: none;">
								<li>1. Avoid (Discontinue risky activity)</li>
								<li>2. Accept (Retain by informed decision)</li>
								<li>3. Remove (Remove risky activity</li>
								<li>4. Take on Risk to increase opportunity</li>
								<li>5. Change Likelihood</li>
								<li>6. Change Consequence</li>
								<li>7. Share risk with 3rd party (Insurance or Joint Venture)</li>
							</ul>
						</div>

						<div class="card-body custom-p" id="step-5">						
							<h3 class="sub-header">Step 5: Monitoring and review</h3>
							<p>Risk management is an ongoing process. Even if the existing control measures are adequate you need to regularly review whether anything has changed which may impact on the risk issues you have identified.</p>
							<p>Once the proposed controls are completed reassess the risk by conducting regularrisk reviews, control checksand audits.</p>  
							<p>Stay ahead of the competition and thrive in the marketplace!</p>
						</div>
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
            margin-top: -17px !important;
        }
		h3.sub-header{
			font-size: 18px !important;
			margin-bottom: 10px !important;
			font-weight: bold;
		}
		.li-list li{
			margin-bottom: 10px !important;
		}
		.smooth-a{
			font-size: 18px !important;
			font-weight: bold;
			border-bottom: 1px solid var(--custom-primary);
			color: var(--custom-primary);
		}
    </style>
	<script>
		$(document).ready(function(){
		// Add smooth scrolling to all links
		var headerHeight = $(".main-navbar").height();
		$(".smooth-a").on('click', function(event) {
			// Make sure this.hash has a value before overriding default behavior
			if (this.hash !== "") {
			// Prevent default anchor click behavior
			event.preventDefault();

			// Store hash
			var hash = this.hash;
			var scrollH = $(hash).offset().top - 60;
			// Using jQuery's animate() method to add smooth page scroll
			// The optional number (800) specifies the number of milliseconds it takes to scroll to the specified area
			$('html').animate({
				scrollTop: scrollH
			}, 800, function(){
				// Add hash (#) to URL when done scrolling (default click behavior)
				window.location.hash = hash;
			});

			} // End if
		});
		
		});
	</script>
</body>
</html>