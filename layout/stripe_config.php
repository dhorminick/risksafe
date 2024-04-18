<?php 
 
// Product Details 
// Minimum amount is $0.50 US 
$itemName = "Demo Product"; 
$itemPrice = 25;  
$currency = "USD";  
 
/* Stripe API configuration 
 * Remember to switch to your live publishable and secret key in production! 
 * See your keys here: https://dashboard.stripe.com/account/apikeys 
 */ 
define('STRIPE_API_KEY', 'pk_live_51JqokKHFz38HMyMvoV3SIRNXrueIb1X2VNjtdLq5PMpsKbmYJmRaLfsomj9vZzPWN3gqzB3wXRe0s9qunu9LzPwE00vOyTizvi'); 
define('STRIPE_PUBLISHABLE_KEY', 'pk_test_51Kg1ZBAznAVlkN5VUZvbwBCO2mUQJ2QdbYphJwJYvFbu6pPeBpzzoHv3wzLKcRzJS58i7IzrVuRnaNTaue3Vbclu002JXO2MRB'); 
  
?>