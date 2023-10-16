<?php

include_once("../config.php");
include_once('../model/users.php');

$msg = null;
if (isset($_REQUEST["login"]) && $_REQUEST["login"]=="false") {
	$msg="E-mail and/or password are incorrect";
} else {
	$user=new users;
	if (!$user->isLogged()) {
		//$msg="Please login using your e-mail and password";	
	} else {
		$msg="You are logged into the system";
		header('Location: main.php');
		exit;
	}
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
<?php include_once("header.php");?>
<style>
    body {
    font-family: "Helvetica Neue",Helvetica,Arial,sans-serif;
    font-size: 14px;
    line-height: 1.42857143;
    color: #333;
    background-color: #fff;
    background: url(http://risksafe.co/img/intro-bg.png);
    background-size: cover;
    background-repeat: no-repeat;
}
    </style>
</head>
<div id="totalHeight"></div>
<body>
    <div class="container" >
        <?php if(null != $msg) { ?>
            <div class="row">
                <div class="col-md-6 col-md-offset-3">
                    <div style="text-align: center" class="alert alert-info" id="msg_login"><?php echo $msg;?></div>
                </div>
		    </div>
    	<?php } ?>
        <div class="row firoiz" style="width: 100%;">
        	
            <div class="col-lg-6 col-12 fnhsgr8">            	
                <div class="login-panel panel panel-default">
                    <div class="panel-heading custom">
                        <h3 class="panel-title fu49zk">RiskSafe - Create Account</h3>
                    </div>
                    <div class="panel-body">
                        <form role="form" method="post" action="../controller/users.php?action=login">
                            <fieldset>
                                <div class="form-group">
                                    <label>Full Name:</label>
                                    <input class="form-control" placeholder="Your Full Name..." name="fullname" type="text" required>
                                </div>
                                <div class="form-group">
                                    <label>Email Address:</label>
                                    <input class="form-control" placeholder="Valid E-mail Address..." name="email" id="username" type="email" required >
                                </div>
                                <div class="form-group">
                                    <label>Password:</label>
                                    <div class="fneip">
                                        <input class="form-control" placeholder="Enter Password..." name="password" id="password" type="password" required>
                                        <button type="button" class="fyeviu"><i class="fa fa-eye"></i></button>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label>Company:</label>
                                    <input class="form-control" placeholder="Company..." name="company" type="text" required>
                                </div>
                                <div style="text-align: center;display:flex">
                                    <button type="submit" class="btn btn-md btn-primary btn_signup" id="btn_login">Create Account</button>
                                </div>                              
                            </fieldset>
                        </form>

                    </div>
                    <div class="panel-footer custom" style="text-align: center;">
						<a href="login.php" class="bb">Already Have An Account? Sign In</a>
                    </div>
                </div>                
            	
            </div>

        </div>
    </div>
    <style>
        .fneip{
            display: flex;
        }
        .fneip input{
            width: 100%;
        }
        .fneip button{
            padding:10px;
        }
        #btn_login{
            width: 60%;
            margin-right: 5px;
        }
        .btn_signup{
            width: 100% !important;
        }
        #a_signup{
            width: 40%;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        #btn_signup{
            background-color: inherit !important;
            outline: none !important;
            border: none !important;
            color: white !important;
        }
        .fu49zk{
            width: 100%;
            text-align: center;
        }
        .firoiz{
            display: flex;
            align-items: center;
            justify-content: center;
            /* border: 1px solid red; */
        }
        #totalHeight{
            width: 100%;
            height: 100%;
            position: absolute;
            /* border: 1px solid red; */
        }
        .fopaei{
            position:absolute;bottom:0px;width:100%
        }
    </style>
<footer class="text-center fopaei">Copyright &copy; <?php echo date("Y");?> RiskSafe. All rights reserved</a></footer>
<!-- script references --> 
<script src="//ajax.googleapis.com/ajax/libs/jquery/2.0.2/jquery.min.js"></script> 
<script src="../js/bootstrap.min.js"></script> 
<script src="../js/scripts.js"></script>
<script src="../../js/jquery.js"></script>
<script>
    var totalHeight = $("#totalHeight").height();
    var totalWidth = $("#totalHeight").width();
    var footerHeight = $(".fopaei").height();

    var calcHeight = totalHeight - footerHeight;
    if (totalWidth > 575) {
        $('.firoiz').height(totalHeight - 60);   
    } else {
        $('.firoiz').height(totalHeight);
        $('.firoiz').width(totalWidth);
        $(".fnhsgr8").css({"margin-right": "-10px", "margin-left": "-10px"});
    }

    $(".fyeviu").on("click", function () {
    var x = document.getElementById("password");
    if (x.type === "password") {
        $(".fyeviu i").attr("class", "fa fa-eye-slash");
        x.type = "text";
    } else {
        $(".fyeviu i").attr("class", "fa fa-eye");
        x.type = "password";
    }
    });
$(document).ready(function(e) {
    
	$("#btn_signup").click(function() {
        
		$(location).attr("href", "../../index.php#sg");
	});
	
});
</script>
</body>
</html>