<?php
include_once("../controller/auth.php");
include_once("../config.php");
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <?php include_once("header.php"); ?>
    <style>
        .container {
            width: 400px;
            margin: 90px auto;
            padding: 20px;
            background-color: #f7f7f7;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        h1 {
            text-align: start;
            margin-bottom: 30px;
        }

        body {
            background-image: url(http://risksafe.co/img/intro-bg-old3.jpg);
            background-repeat: no-repeat;
            background-position: center;
            background-size: cover;
        }

        .payment-form {
            margin-top: 30px;
        }

        .form-row {
            margin-bottom: 20px;
        }

        label {
            font-weight: bold;
            display: inline-block;
            margin-bottom: 5px;
        }


        input[type="number"],
        input[type="text"] {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        #card-element {
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        #card-errors {
            color: #dc3545;
            margin-top: 10px;
            font-size: 14px;
        }

        .btn {
            display: block;
            width: 50%;
            background-color: #FF642D;
            color: #fff;
            padding: 10px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            outline: none;
            font-size: 16px;
            text-align: center;
            transition: background-color 0.3s ease;
        }

        .btn:hover {
            background-color: #cc5024;
            color: white;

        }

        .btn:focus {
            outline: none;
            box-shadow: none;
            / Remove the box shadow property /
        }

        .error {
            color: #dc3545;
            font-size: 14px;
            margin-top: 5px;
        }

        .seprate {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
        }

        .btn-box {
            display: flex;
            align-items: center;
            justify-content: end;
        }
    </style>
</head>

<body>

    <div id="top-nav" class="navbar navbar-inverse navbar-static-top" style="background-color:#09F;border:0;">
        <div class="container-fluid">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse"> <span
                        class="icon-bar"></span> <span class="icon-bar"></span> <span class="icon-bar"></span> </button>
                <a class="navbar-brand" href="main.php" style="font-weight:900;color:#fff;">
                    <?php echo APP_TITLE; ?>
                </a>
            </div>
            <div class="navbar-collapse collapse">
                <ul class="nav navbar-nav navbar-right">
                    <li class="dropdown"> <a class="dropdown-toggle" role="button" data-toggle="dropdown" href="#"
                            style="font-weight:900;color:#fff;"><i class="glyphicon glyphicon-user"></i> Account <span
                                class="caret"></span></a>
                        <ul id="g-account-menu" class="dropdown-menu" role="menu">
                            <?php include_once("menu_top.php"); ?>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
        <!-- /container -->
    </div>
    <div class="container">
        <!-- <h1>Pro 14 days free trial</h1> -->
        <h1>Payment Information</h1>
        <div class="payment-form">
            <form action="submit.php" method="post" id="stripe-form">
                <div class="form-row">
                    <label for="card-element">Credit or Debit Card</label>
                    <div id="card-element"></div>
                    <div id="card-errors" role="alert"></div>
                </div>
                <div class="form-row">
                    <label for="name">Card Holder</label>
                    <input type="text" id="name" name="name" placeholder="Card Holder" required>
                </div>
                <div class="form-row">
                    <label for="abn">ABN (Australian Business Number)</label>
                    <input type="text" id="abn" name="abn" placeholder="ABN" required>
                    <div class="error" id="abn-error"></div>
                </div>

                <div class="form-row">
                    <label for="abn"> Amount </label>
                    <input type="text" id="amount" name="amount" placeholder="amount"  value="49" readonly required >
                    <div class="error" id="abn-error"></div>
                </div>
                <div class="seprate">
                    <div>
                        <label>Today's charge:&nbsp;</label>
                        <span>
                            <label>$0.00(inc.Tax)</label>
                            <i>i</i>
                        </span>
                    </div>
                    <div class="btn-box">
                        <button type="button" class="btn" id="submit-button">Try it now</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <?php include_once("footer.php"); ?>
</body>
<script src="https://js.stripe.com/v3/"></script>
<script>
 //   pk_test_FQu4ActGupRmMrkmBpwU26js
 //pk_live_51JqokKHFz38HMyMvoV3SIRNXrueIb1X2VNjtdLq5PMpsKbmYJmRaLfsomj9vZzPWN3gqzB3wXRe0s9qunu9LzPwE00vOyTizvi
    var stripe = Stripe('pk_test_FQu4ActGupRmMrkmBpwU26js');
    var elements = stripe.elements();
    var cardElement = elements.create('card', {
        hidePostalCode: true
    });
    function validateABN() {
        var abnInput = document.getElementById('abn');
        var abn = abnInput.value.replace(/\s/g, '');

        if (abn.length !== 11) {
            // ABN should be exactly 11 characters long
            displayABNError('Invalid ABN. ABN should be 11 digits.');
            return false;
        }

        var nums = Array.from(abn, Number);
        nums[0] -= 1;

        var weights = [10, 1, 3, 5, 7, 9, 11, 13, 15, 17, 19];

        nums = nums.map(function (num, pos) {
            var weight = weights[pos];
            return num * weight;
        });

        if (nums.reduce(function (sum, num) {
            return sum + num;
        }, 0) % 89 !== 0) {
            // ABN validation failed
            displayABNError('Invalid ABN. Please enter a valid ABN.');
            return false;
        }

        // ABN is valid
        clearABNError();
        return true;
    }

    function displayABNError(message) {
        var errorElement = document.getElementById('abn-error');
        errorElement.textContent = message;
    }

    function clearABNError() {
        var errorElement = document.getElementById('abn-error');
        errorElement.textContent = '';
    }

    cardElement.mount('#card-element');

    var submitButton = document.getElementById('submit-button');
    submitButton.addEventListener('click', function (event) {
        event.preventDefault();
        if (validateABN()) {
            stripe.createToken(cardElement).then(function (result) {
                if (result.error) {
                    var errorElement = document.getElementById('card-errors');
                    errorElement.textContent = result.error.message;
                } else {
                    stripeTokenHandler(result.token);
                }
            });
        }
    });

    function stripeTokenHandler(token) {
        var form = document.getElementById('stripe-form');
        var tokenInput = document.createElement('input');
        tokenInput.type = 'hidden';
        tokenInput.name = 'stripeToken';
        tokenInput.value = token.id;
        form.appendChild(tokenInput);
        form.submit();
    }
</script>