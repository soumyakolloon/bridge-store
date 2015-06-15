<?php
/**
 * Paypal ipn listner - reponse handler
 * 
 * @author Jeny Devassy <jeny.devassy@beidge-india.in>
 * @date 30 Sep 2014
 */

include_once('../config.php');
include_once '../common/common-function.php';
include_once('../controller/application-controller.php');
include_once('../controller/database-controller.php');
include_once('paypal-config.php');

// Db Connection setup
$db     = new DataBaseController();    
$mysqli = $db->db_connect('mysqli');

//Change these with your information
$paypalmode = ( !empty($PayPalMode) ) ? $PayPalMode : 'sandbox' ; //Sandbox for testing or empty ''

// CONFIG: Enable debug mode. This means we'll log requests into 'ipn.log' in the same directory.
define("DEBUG", 1);
define("LOG_FILE", "../uploads/ipn.log");


if($_POST)
{
    $paypalmode = ($paypalmode=='sandbox') ? '.sandbox' : '';
    
    $req = 'cmd=' . urlencode('_notify-validate'). '&rm=2';
    
    foreach ($_POST as $key => $value) {
        
        $value = urlencode(stripslashes($value));
        $req .= "&$key=$value";
    }
    
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, 'https://www'.$paypalmode.'.paypal.com/cgi-bin/webscr');
    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $req);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 1);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Host: www'.$paypalmode.'.paypal.com'));
    $res = curl_exec($ch);
    
    
    if (curl_errno($ch) != 0) // cURL error
    {        
        if(DEBUG == true) {	
        
            error_log(date('[Y-m-d H:i e] '). "Can't connect to PayPal to validate IPN message: " . curl_error($ch) . PHP_EOL, 3, LOG_FILE);
        }
        curl_close($ch);
        exit;
        
    }
    else {
        
        // Log the entire HTTP response if debug is switched on.
        if(DEBUG == true) {
            
            error_log(date('[Y-m-d H:i e] '). "HTTP request of validation request:". curl_getinfo($ch, CURLINFO_HEADER_OUT) ." for IPN payload: $req" . PHP_EOL, 3, LOG_FILE);
        }
        
        curl_close($ch);
    }
    
    if (strcmp ($res, "VERIFIED") == 0 )
    {

        $transaction_id = $_POST['txn_id'];
        $payerid = $_POST['payer_id'];
        $firstname = $_POST['first_name'];
        $lastname = $_POST['last_name'];
        $payeremail = $_POST['payer_email'];
        $paymentdate = $_POST['payment_date'];
        $paymentstatus = $_POST['payment_status'];
        $mdate= date('Y-m-d h:i:s',strtotime($paymentdate));
        $otherstuff = json_encode($_POST);

        // update IPN record in purchases table
        $query = "UPDATE bs_purchases SET payment_status = '$paymentstatus', transaction_date = '$mdate', ipn_status = 'Confirmed'
                  WHERE transaction_id = '$transaction_id' ";

        $mysqli->query($query);

        if(DEBUG == true) {
        
            error_log(date('[Y-m-d H:i e] '). "Verified IPN: $req ". PHP_EOL, 3, LOG_FILE);
        }
        
    } 
    else if (strcmp ($res, "INVALID") == 0) {
        // log for manual investigation
        // Add business logic here which deals with invalid IPN messages
        if(DEBUG == true) {

            error_log(date('[Y-m-d H:i e] '). "Invalid IPN: $req" . PHP_EOL, 3, LOG_FILE);
        }
    }
    else{
        
        error_log(date('[Y-m-d H:i e] '). "Error IPN: IPN response error" . PHP_EOL, 3, LOG_FILE);
    }
}
?>