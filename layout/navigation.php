 <!-- Navigation -->
    <nav class="navbar navbar-default navbar-fixed-top topnav" role="navigation">
        <div class="container topnav">
            <!-- Brand and toggle get grouped for better mobile display -->
            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
     
                </button>
                <!-- <div class="logo"> <a href="<?php printPageURL("");?>"> <img src="img/logo.png" alt="logo" width="100px"> <span class="navbar-brand"><?php echo APP_TITLE; ?> </span></a></div> -->
                <div class="logos"> <a href="<?php printPageURL("");?>"><span class="navbar-brand"><?php echo APP_TITLE; ?> </span></a></div>
            </div>
            <!-- Collect the nav links, forms, and other content for toggling -->
            <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
        		<ul class="nav navbar-nav navbar-right">
				    <li><a href="<?php printPageURL("about.php");?>">About</a></li>
				    
				    <!--
				    <li class="dropdown">
			    		<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false" href="#">Resources</a>
			    		<ul class="dropdown-menu pull-right">
			    			<li><a>How to Conduct a Risk Assessment</a></li>
			    			<li><a>Risk Types</a></li>
			    			<li><a>Insurance &amp; Risk Management</a></li>
			    		</ul>
		    		</li>
	    			-->	    	
	    			<li><a href="<?php printPageURL("resources.php");?>">Resources</a></li>
				    <li><a href="<?php printPageURL("pricing.php");?>">Products</a></li>             
				    <li><a href="<?php printPageURL("contact.php");?>">Contact</a></li>
				    <li><a href="<?php printPageURL("#sg");?>">Try RiskSafe</a></li>
				    <li><a href="<?php printPageURL("rs/index.php");?>">Login</a></li>
				</ul>                
            </div>
            <!-- /.navbar-collapse -->
        </div>
        <!-- /.container -->
    </nav>

