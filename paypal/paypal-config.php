<?php

$base_url = get_base_url();

//start session in all pages
php_session_start();
//PHP >= 5.4.0
//if(session_id() == '') { session_start(); } //uncomment this line if PHP < 5.4.0 and comment out line above

$PayPalMode         = 'sandbox'; // sandbox or live
$PayPalApiUsername  = 'sobin87-facilitator_api1.gmail.com'; //PayPal API Username
$PayPalApiPassword  = '1406782475'; //Paypal API password
$PayPalApiSignature = 'An5ns1Kso7MWUdW4ErQKJJJ4qi4-AO6y-681EaMbmEcM5aJnJMbVssg1'; //Paypal API Signature
$PayPalCurrencyCode = 'USD'; //Paypal Currency Code
$PayPalReturnURL    = $base_url . 'process.php'; //Point to process.php page
//$PayPalCancelURL    = $base_url . "paypal/cancel_url.php"; //'http://localhost/bridge-store/paypal/cancel_url.php'; //Cancel URL if user clicks cancel
$PayPalCancelURL    = $base_url . "index.php?page=paymentCancel"; //paymentResponse //'http://localhost/bridge-store/paypal/cancel_url.php'; //Cancel URL if user clicks cancel
?>