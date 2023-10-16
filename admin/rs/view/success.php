<?php
include_once("../controller/auth.php");
include_once("../config.php");
?>
<!DOCTYPE html>
<html>
<head>
  <?php include_once("header.php"); ?>
    <title>Payment Success</title>
    <style>
        .tick {
            color: blue;
            font-size: 24px;
        }
        .main {
  display: flex;
  justify-content: center;
  align-items: flex-start;
  margin-top: 2rem;
  width: 100%;
  height: 90vh;
 
  background-repeat: no-repeat;
  background-position: center;
  background-size: cover;
}
.btn:focus {
    outline: none;
    box-shadow: none; /* Remove the box shadow property */
  }

body{
    background-image: url(http://risksafe.co/img/ipad.png);
}
        .container{
            width: 50%;
            margin: 3rem;
            border-radius: 3rem;
    padding: 1rem 2rem;
            background-color: #DED299;
        }
        .combined-box{
            display: flex;
    justify-content: center;
    flex-direction: column;
    align-items: center;
    gap:0.5rem;
        }
         /* CSS styles for the button */
  .btn {
    /* Button background color */
    width: 7rem;
    height: auto;
    background-color: #FF642D;
    /* Button text color */
    color: #fff;
    /* Button padding */
    padding: 10px 20px;
    /* Button border */
    border: none;
    /* Button cursor style */
    cursor: pointer;
    /* Button border radius */
    border-radius: 4px;
    font-size: 16px;
  }
  .tick1 {
            color: blue;
            font-size: 30px;
            animation-name: tickAnimation;
            animation-duration: 1s;
            animation-fill-mode: both;
        }

        @keyframes tickAnimation {
            0% {
                opacity: 0;
                transform: scale(0.2);
            }
            100% {
                opacity: 1;
                transform: scale(1);
            }
        }

  /* Additional styles for the button */
  .btn:hover {
    /* Button background color on hover */
    background-color: #cc5024;
  }

  .btn:focus {
    /* Button outline on focus */
    outline: none;
    /* Add box shadow to create a focus effect */
    box-shadow: 0 0 0 2px #cc5024;
  }
  h1{
    font: Arial, Helvetica, sans-serif;
  }
    </style>
</head>
<body >
<div id="top-nav" class="navbar navbar-inverse navbar-static-top" style="background-color:#09F;border:0;">
    <div class="container-fluid">
      <div class="navbar-header">
        <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse"> <span class="icon-bar"></span> <span class="icon-bar"></span> <span class="icon-bar"></span> </button>
        <a class="navbar-brand" href="main.php" style="font-weight:900;color:#fff;"><?php echo APP_TITLE; ?></a>
      </div>
      <div class="navbar-collapse collapse">
        <ul class="nav navbar-nav navbar-right">
          <li class="dropdown"> <a class="dropdown-toggle" role="button" data-toggle="dropdown" href="#" style="font-weight:900;color:#fff;"><i class="glyphicon glyphicon-user"></i> Account <span class="caret"></span></a>
            <ul id="g-account-menu" class="dropdown-menu" role="menu">
              <?php include_once("menu_top.php"); ?>
            </ul>
          </li>
        </ul>
      </div>
    </div>
    <!-- /container -->
  </div>
  <div class="container-fluid">
    <div class="row">
      <div class="col-lg-3 col-sm-12">
        <!-- Left column -->
  
        <!-- /col-3 -->
      </div>
    <div class="main">
    <div class="container">
        <div class="combined-box" id="para-add">
        <h1>Payment Successful! <span class="tick1">&#9989;</span></h1>
        <span class="tick1">&#128578;</span>
                <h1>Thanks</h1>
    <button type="button" class="btn" id="next-button">Next</button>   
        </div>
    
    </div>
   
    </div>

</div>
</div>
   
    <script>
        // Retrieve the customer name from the URL parameter
        const urlParams = new URLSearchParams(window.location.search);
        const customerName = urlParams.get('customer_name');

        // Display the customer name with a blue tick symbol
        const parentElement = document.getElementById('para-add');
const paymentMessage = document.createElement('p');
paymentMessage.innerText = `Payment successful for: ${customerName}`;
const blueTick = document.createElement('span');
blueTick.classList.add('tick');
blueTick.innerText = '\u2714';// Unicode for blue tick symbol
paymentMessage.appendChild(blueTick);
parentElement.appendChild(paymentMessage);

    </script>
    <script>
    // Stripe setup code
    
    var nextButton = document.getElementById('next-button');
    nextButton.addEventListener('click', function(event) {
        event.preventDefault();
        window.location.href = 'main.php'; // Redirect to main.php
    });
</script>
<?php include_once("footer.php"); ?>
</body>
</html>
