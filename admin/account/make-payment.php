<?php
    session_start();
    $file_dir = '../../';
    if (isset($_SESSION["loggedIn"]) == true || isset($_SESSION["loggedIn"]) === true) {
        $signedIn = true;
    } else {
        header('Location: '.$file_dir.'login?r=/account/payment');
        exit();
    }
    $message = [];
    
    include $file_dir.'layout/db.php';
    include $file_dir.'layout/admin_config.php';
    require_once $file_dir.'layout/stripe_config.php'; 
    
    
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport">
  <title>Make Payments | <?php echo $siteEndTitle; ?></title>
  <?php require '../../layout/general_css.php' ?>
  <link rel="stylesheet" href="<?php echo $file_dir; ?>assets/css/admin.custom.css">
</head>

<body>
    <div class="loader"></div>
    <div id="app">
        <div class="main-wrapper main-wrapper-1">
        <div class="navbar-bg"></div>
        <?php require '../../layout/header.php' ?>
        <?php require '../../layout/sidebar_admin.php' ?>
        <!-- Main Content -->
        <div class="main-content">
            <section class="section">
            <div class="section-body">
                <div class="card">
                    <?php include '../../layout/alert.php'; ?>
                    <div class="card-body">
                        <div class="card-header">
                            <h3 class="d-inline">Initiate Payment</h3>
                            <div class="header-a hide-sm"><a href='payments' class="btn btn-primary btn-icon icon-left"><i class="fas fa-arrow-left"></i> Back</a></div>
                        </div>
                        <div class="card-body">
                            <h3 class="panel-title">Charge <?php echo '$'.$itemPrice; ?> with Stripe</h3>
        
                            <!-- Product Info -->
                            <p><b>Item Name:</b> <?php echo $itemName; ?></p>
                            <p><b>Price:</b> <?php echo '$'.$itemPrice.' '.$currency; ?></p>
                            
                            <div id="paymentResponse" class="hidden"></div>
                            <!-- Display a payment form -->
                            <form id="paymentFrm" class="hidden">
                                <div class="form-group">
                                    <label class="help-label">Name:</label>
                                    <input type="text" id="name" class="form-control" placeholder="Payee Name..." required="" autofocus="">
                                </div>
                                <div class="form-group">
                                    <label class="help-label">Email</label>
                                    <input type="email" id="email" class="form-control" placeholder="Payee Email Address.." required="">
                                </div>
                                
                                <div id="paymentElement">
                                    <!--Stripe.js injects the Payment Element-->
                                </div>
                                
                                <!-- Form submit button -->
                                <button id="submitBtn" class="btn btn-success">
                                    <div class="spinner hidden" id="spinner"></div>
                                    <span id="buttonText">Pay Now</span>
                                </button>
                            </form>
                            
                            <!-- Display processing notification -->
                            <div id="frmProcess" class="hidden">
                                <span class="ring"></span> Processing...
                            </div>
                            
                            <!-- Display re-initiate button -->
                            <div id="payReinit" class="hidden">
                                <button class="btn btn-primary" onClick="window.location.href=window.location.href.split('?')[0]"><i class="rload"></i>Re-initiate Payment</button>
                            </div>
                        </div>
                            
                    </div>
                </div>
            </div>
            </section>
        </div>
        
        <?php require '../../layout/footer.php' ?>
        </footer>
        </div>
    </div>
    <?php require '../../layout/general_js.php' ?>
    <script src="https://js.stripe.com/v3/"></script>
    <script src="<?php echo $file_dir; ?>assets/js/checkout.js" STRIPE_PUBLISHABLE_KEY="<?php echo STRIPE_PUBLISHABLE_KEY; ?>" defer></script>
</body>
</html>
<style>
    .main-footer {
        margin-top: -15px;
    }
    .note {
        border-left: 7px solid var(--custom-primary);
        background-color: var(--card-border);
        color: black;
        padding: 10px;
        margin: 0px 0px 20px 0px;
        border-radius: 0px 5px 5px 0px;
    }
    .price{
        text-align:center;
        margin-bottom:10px;
        font-weight:400;
    }
</style>