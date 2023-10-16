<?php

include_once("../controller/auth.php");
include_once("../config.php");

?>

<!DOCTYPE html>
<html lang="en">
<head>
<?php include_once("header.php");?>
</head>
<body>
<!-- header -->
<div id="top-nav" class="navbar navbar-inverse navbar-static-top" style="background-color:#09F;border:0;">
  <div class="container-fluid">
    <div class="navbar-header">
      <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse"> <span class="icon-bar"></span> <span class="icon-bar"></span> <span class="icon-bar"></span> </button>
      <a class="navbar-brand" href="main.php" style="font-weight:900;color:#fff;"><?php echo APP_TITLE;?></a> </div>
    <div class="navbar-collapse collapse">
      <ul class="nav navbar-nav navbar-right">
        <li class="dropdown"> <a class="dropdown-toggle" role="button" data-toggle="dropdown" href="#" style="font-weight:900;color:#fff;"><i class="glyphicon glyphicon-user"></i> Account <span class="caret"></span></a>
          <ul id="g-account-menu" class="dropdown-menu" role="menu">
			<?php include_once("menu_top.php");?>
          </ul>
        </li>
      </ul>
    </div>
  </div>
  <!-- /container --> 
</div>
<!-- /Header --> 

		<!-- Main -->
		<div class="container-fluid">
			<div class="row">
				<div class="col-lg-3 col-sm-12">
					<!-- Left column -->
					<?php include_once("menu.php"); ?>
					<!-- /col-3 -->
				</div>
				<div class="col-lg-9 col-md-12">
					<h1 class="page-header">Instructions for using the Risk Assessment</h1>

					<h3>Assessment Details</h3>
					<p>Complete details of your Organisation, Team/Department, Assessor and Date. </p>
					<p>Note 1: Determine the next review date. Normally the next review date is up to one year ahead. The Risk assessmeny is also reviewed:<br/> 
						(1) When there are changes in work processes/activities<br/>
						(2) After any accidents/incidents</p>


					<h3>Risk Identification</h3>
					<p>Identify risks or hazards with your organisation or a particular team, business unit or a process.  Carefully go through each of the Risk categories to understand if any are valid risks for your organisation (e.g. Legal, Health & safety).</p>  
					<p>You can use the risks by going through each of the risks categories.  There is also free field text for you to enter specific information that relates to your risk.  </p>
					
					
					<h3>Risk Evaluation</h3>
					<p>Likelihood of occurrence of a risk or an accident is defined as the probability that the risk event will happen. Choose the likelihood from a value between 1 and 5. Consider the records of such events happening in the past when deciding on the likelihood.</p>
                                   
					<p>Consequence is the degree or extent of risk or harm caused by the hazards. Choose the most likely severity from a value between 1 and 5, rather than the most extreme.<br/>                
					Refer to the Risk Matrix tab for the likelihood and consequence table.</p>

					<p>Risk Rating is calculated using the combination of Likelihood and Consequence.<br/>                                                                                                      
					Risk rating = Likelihood X Consequence</p>


					<h3>Control Actions</h3>
					<p>Indicate risk control measures that are already in place to eliminate or minimise risks.<br/>
					Note, where there are good controls in place, the consequence or likelihood or a risk can be reduced, bringing down the 'inherent' risk rating to a 'residual' risk rating.</p>	


					<h3>Treatment Plans</h3>
					<p>Where there are ineffective controls or gaps in processes identified, you can create treatment plans to minimise risk exposure.</p>  
					<p>You can also assign an owner to complete these treatments and due dates. </p>
					
					<h3>Audit of Controls</h3>
					<p>You can test each of the controls you have listed as a control to minimise your risk.  In order to do this, create a list of expected outcomes (how should the control be functioning ideally).</p>  
					<p>The assessor should then test against each of these expected outcomes by either observation, interview, re-performance.  Once you are comfortable, assess whether the control is effective or ineffective.</p>   
					<p>If the control is ineffective, consider creating a treament plan.  Also identify when the next test should occur.  </p>

					<h3>Dashboard</h3>
					<p>The dashboard should will show an overview of number of risks, controls and treatments that have been identified.</p>  
					<p>You can export reports through the dashboard.  </p>
					
					<h3>Treatments</h3>
					<p>List out all of the treaments that are currently open and provide an update on the status.  </p>
				</div>
			</div>
			<!--/col-span-9-->
		</div>
 


<!-- /Main -->

<?php include_once("footer.php");?>

</body>
</html>