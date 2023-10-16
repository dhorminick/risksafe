<?php include 'layout/config.php'; ?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport">
  <title>Help Centre | <?php echo APP_TITLE; ?></title>
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
            <div class="intro-header custom help">
                <div class="intro-message">
                    <h2>Help Desk</h2>
                    <div class="intro-breadcrumbs">
                        <a href="/" class="links">Home</a>
                        <a>Help</a>
                    </div>
                </div>
            </div>
            <section class="section">
            <div class="section-body">
                <div class="card">
                    <div class="card-header">
                        <h3 style="width: 100%;text-align:center;">Help Centre - Risk Safe</h3>
                    </div>
                    <div class="card-body custom-p">                            
                        <p><b>How does RiskSafe work?</b></p>
                        <p>Great question! RiskSafe is a platform for tracking work and paying 1099 workers. Work can be entered directly through the app and user interface (UI) or via an integration with your company's other sources of work data.</p> 
                        <p>Work Types are the large tiles across the top that make up your company's incentive structure or ways to get paid. These can be things like Hours, Activities, Expenses, or anything else that you might get paid for. They can be unit-based, time-based, mileage-based, and monetary-based.</p> 
                        <p class="end-p">Any way you pay workers or get paid can be accommodated. Need custom Work Types? Contact us about it!</p> 
        
                        <p class="start-p"><b>How do I signup and conduct a risk assessment?</b></p>
                        <p class="end-p">Simply click on <a class="bb" href="register.php">‘Try RiskSafe’</a>, and in your general information when prompted and select the correct risk assessment that’s relevant to you (e.g. business risk assessment, heath &amp; safety risk assessment, etc.)</p>
        
                        <p class="start-p"><b>Why do I need to make a payment?</b></p>
                        <p class="end-p">We are providing risk management tools that generally cost thousands of dollars for large organisations through licensing and software setup etc.  We’re different in a way that we provide Saas solutions to you so that small to medium businesses can use the same type of software as large organisations (we believe SMEs need risk management the most).  To keep our business running and to keep improving our product and customer service, we need to charge a small fee.  We hope you understand.</p>  
        
                        <p class="start-p"><b>Can I export the files and risk assessments?</b></p>
                        <p class="end-p">Yes once you complete the risk assessment by using the drop down boxes of types of risks and assessing your own circumstances, you are able to export to CSV, Word or Excel files.</p>  
        
                        <p class="start-p"><b>How do I conduct an audit of my controls?</b></p>
                        <p class="end-p">We’ve made it self explanatory for you to conduct an audit of your controls.  You would have identified your key controls during the risk assessment phase (steps you have in place to prevent the risk from occurring).  You simply select the control you want to run an audit on, include the expected outcomes if the control is to run perfectly, test against these expectations and include your responses to each.  At the end, determine if your control is effective or if it needs further treatment.</p>  
        
                        <p class="start-p"><b>Can I speak to an expert if I need help?</b></p>
                        <p class="end-p">Yes absolutely, RiskSafe was built by Risk Managers and Accountants from Large Global Banks and Big for Consulting firms who realised Risk management is needed most by small to medium businesses.  Drop us a line or give us a call and we’d love to go through your business risks with you.</p>  
        
                        <p class="start-p"><b>Can more than one person use RiskSafe in my company?</b></p>
                        <p class="end-p">At this stage we accounts can only be created per user.   As we continue to build on RiskSafe, we plan on rolling out an enterprise solution where many users can log in under one account in the near future.</p>
        
                        <p class="start-p"><b>Is there a discount for non profits?</b></p>
                        <p class="end-p">Yes we do! If you’re organisation is a nonprofit, please <a class="bb" href="mailto:nonprofit@risksafe.com">contact our sales team</a> to find out more about our discount.</p>
        
                        <p class="start-p"><b>Do you offer a free trial?</b></p>
                        <p class="end-p">It’s free to set up an account and create a risk assessment. But to export the assessments, you’ll need to make the suggested payment.</p>
        
                        <p class="start-p"><b>What payment types and currencies are accepted?</b></p>
                        <p class="end-p">We accept most credit cards and the prices above are in AUD however we can work with you to support additional currencies such as EUR, GBP, and US dollars.</p>
        
                        <p class="start-p"><b>I have more questions. Who do I ask?</b></p>
                        <p class="end-p">Write us at <a class="bb" href="mailto:support@risksafe.com">support@risksafe.com</a> or call us at <a href="tel:++61397953170" class="bb">+61 3 9795 3170</a> internationally. We’d be happy to chat with you.</p>
        
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
    <style>
		.thumbnail {
			height: 140px;
			padding: 10px;
		}
        .end-p{
            margin-bottom: 15px !important;
        }
        .start-p{
            margin-top: 20px !important;
        }
        .main-footer {
			margin-top: -17px !important;
		}
	</style>
</body>
</html>