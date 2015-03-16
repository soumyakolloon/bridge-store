<?php

include('config.php');
include_once 'PHPMailer/PHPMailerAutoload.php';
include_once 'common/common-function.php';
include_once("paypal/paypal-config.php");
include_once("paypal/paypal.class.php");
include_once('controller/application-controller.php');
include_once('controller/database-controller.php');
include_once('controller/product-controller.php');
include_once('controller/user-controller.php');

$paypalmode = ($PayPalMode == 'sandbox') ? '.sandbox' : '';

$db     = new DataBaseController();
$mysqli = $db->db_connect('mysqli');
get_db_config();



$app = new AppController();

if ($_POST) //Post Data received from product list page.
{

    $buyer_id         = $_SESSION["user_id"];   
    $Item_total_price = 0;
    
    if(!empty($_POST['products']) && is_array($_POST['products'])){

        foreach ($_POST['products'] as $product) 
        {
            $order['itemnumber']     = $product["itemnumber"]; //Item Number
            $order['itemname']       = $product["itemname"]; // Item Quantity
            $order['itemdesc']       = $product["itemdesc"]; // Item Quantity
            $order['itemQty']        = $product["itemQty"]; // Item Quantity
            $order['itemprice']      = base64_decode($product["itemprice"]); //Item Price
            $item[]                  = $order;
            $Item_total_price        = ($order['itemprice'] * $order["itemQty"]) + $Item_total_price; //(Item Price x Quantity = Total) Get total amount of product;
        }
    }
    else{

        $order['itemnumber']         = $_POST["itemnumber"]; //Item Number
        $order['itemname']           = $_POST["itemname"]; // Item Quantity
        $order['itemdesc']           = $_POST["itemdesc"]; // Item Quantity
        $order['itemQty']            = $_POST["itemQty"]; // Item Quantity
        $order['itemprice']          = base64_decode($_POST["itemprice"]); //Item Price
        $item[]                      = $order;
        $Item_total_price            = ($order["itemprice"] * $order["itemQty"]); //(Item Price x Quantity = Total) Get total amount of product;

    }
    
    //Other important variables like tax, shipping cost
    $total_tax_amount = 0.00;  //Sum of tax for all items in this order.
    $handaling_cost   = 0.00;  //Handling cost for this order.
    $insurance_cost   = 0.00;  //shipping insurance cost for this order.
    $shippin_discount = 0.00; //Shipping discount for this order. Specify this as negative number.
    $shippin_cost     = 0.00; //Although you may change the value later, try to pass in a shipping amount that is reasonably accurate.
    //Grand total including all tax, insurance, shipping cost and discount
    $grand_total      = ($Item_total_price + $total_tax_amount + $handaling_cost + $insurance_cost + $shippin_cost + $shippin_discount);

    //Delete pending/cancelled purchases of user
    $cartQuery = "SELECT * FROM bs_purchases 
        WHERE user_id = '".$buyer_id."' AND payment_status = 'Initiated' ";
    
    $cartResult = $mysqli->query($cartQuery);
    if( $cartResult->num_rows > 0){
        
        while ($pending_purchase = mysqli_fetch_object($cartResult)) {
            
            $prodDelete  = "DELETE FROM bs_purchase_products
              WHERE purchase_id = '".$pending_purchase->id."'";
            $mysqli->query($prodDelete);
            
            $pendingDelete  = "DELETE FROM bs_purchases
              WHERE id = '".$pending_purchase->id."'";
            $mysqli->query($pendingDelete);
        }

    }
    
    // Save the initial purchse data to db
    $query = "INSERT INTO bs_purchases
      (user_id, date_time, transaction_id, total_price, payment_status)
      VALUES ($buyer_id, NOW(), '', $grand_total, 'Initiated')";

    $insert_row = $mysqli->query($query);

    if ($insert_row)
    {
        $purchase_id = $mysqli->insert_id;
        
        $download_token   = md5(mt_rand());
            
        $query_token  = "INSERT INTO bs_downloads(token,purchase_id) VALUES('$download_token', $purchase_id)";
         $insert_row = $mysqli->query($query_token);
        // Insert into purchase products table
        foreach ($item as $products) {
              
           //get prduct validity info based on product id
            $product = new ProductController();
            $product_information = $product->get(array('id' => $products['itemnumber']));
            $product_info      = $product_information[0];
            $expires_on       = time() + $product_info['validity'] * 3600;
            $expire_timestamp = date('Y-m-d h:i:s', $expires_on);
                    
            $item_number = $products['itemnumber']; // item number
            
            $query = "INSERT INTO bs_purchase_products(purchase_id, product_id, expires_on) VALUES ($purchase_id, $item_number, '$expire_timestamp')";
            $insert_row = $mysqli->query($query);
            
            
        }
        
    }
    else
    {
        die('Error : (' . $mysqli->errno . ') ' . $mysqli->error);
    }

    $PayPalReturnURL .= '?purchase_id=' . $purchase_id;

    //Parameters for SetExpressCheckout, which will be sent to PayPal
    $param = '';
    for ($i=0; $i < count($item); $i++) 
    {
        $param .=   '&L_PAYMENTREQUEST_0_NAME'. $i      .'=' . ($item[$i]['itemname']) . 
                    '&L_PAYMENTREQUEST_0_NUMBER'. $i    .'=' . ($item[$i]['itemnumber']) .
                    '&L_PAYMENTREQUEST_0_DESC'. $i      .'=' . (substr($item[$i]['itemdesc'], 0, 100)) .
                    '&L_PAYMENTREQUEST_0_AMT'. $i       .'=' . ($item[$i]['itemprice']) .
                    '&L_PAYMENTREQUEST_0_QTY'. $i       .'=' . ($item[$i]['itemQty']) ;
    }
    $padata = '&METHOD=SetExpressCheckout' .
            '&RETURNURL=' . ($PayPalReturnURL ) .
            '&CANCELURL=' . ($PayPalCancelURL) .
            '&PAYMENTREQUEST_0_PAYMENTACTION=' . ("SALE") . $param .
            
            /*
              //Override the buyer's shipping address stored on PayPal, The buyer cannot edit the overridden address.
              '&ADDROVERRIDE=1'.
              '&PAYMENTREQUEST_0_SHIPTONAME=J Smith'.
              '&PAYMENTREQUEST_0_SHIPTOSTREET=1 Main St'.
              '&PAYMENTREQUEST_0_SHIPTOCITY=San Jose'.
              '&PAYMENTREQUEST_0_SHIPTOSTATE=CA'.
              '&PAYMENTREQUEST_0_SHIPTOCOUNTRYCODE=US'.
              '&PAYMENTREQUEST_0_SHIPTOZIP=95131'.
              '&PAYMENTREQUEST_0_SHIPTOPHONENUM=408-967-4444'.
             */

            '&NOSHIPPING=0' . //set 1 to hide buyer's shipping address, in-case products that does not require shipping

            '&PAYMENTREQUEST_0_ITEMAMT=' . ($Item_total_price) .
            '&PAYMENTREQUEST_0_TAXAMT=' . ($total_tax_amount) .
            '&PAYMENTREQUEST_0_SHIPPINGAMT=' . ($shippin_cost) .
            '&PAYMENTREQUEST_0_HANDLINGAMT=' . ($handaling_cost) .
            '&PAYMENTREQUEST_0_SHIPDISCAMT=' . ($shippin_discount) .
            '&PAYMENTREQUEST_0_INSURANCEAMT=' . ($insurance_cost) .
            '&PAYMENTREQUEST_0_AMT=' . ($grand_total) .
            '&PAYMENTREQUEST_0_CURRENCYCODE=' . ($PayPalCurrencyCode) .
            '&LOCALECODE=GB' . //PayPal pages to match the language on your website.            
            '&CARTBORDERCOLOR=FFFFFF' . //border color of cart
            '&ALLOWNOTE=1';

    ############# set session variable for "DoExpressCheckoutPayment" #######
    $_SESSION['Item']            = $item; // Items
    $_SESSION['ItemTotalPrice']  = $Item_total_price; //(Item Price x Quantity = Total) Get total amount of product;
    $_SESSION['TotalTaxAmount']  = $total_tax_amount;  //Sum of tax for all items in this order.
    $_SESSION['HandalingCost']   = $handaling_cost;  //Handling cost for this order.
    $_SESSION['InsuranceCost']   = $insurance_cost;  //shipping insurance cost for this order.
    $_SESSION['ShippinDiscount'] = $shippin_discount; //Shipping discount for this order. Specify this as negative number.
    $_SESSION['ShippinCost']     = $shippin_cost; //Although you may change the value later, try to pass in a shipping amount that is reasonably accurate.
    $_SESSION['GrandTotal']      = $grand_total;


    //We need to execute the "SetExpressCheckOut" method to obtain paypal token
    $paypal               = new MyPayPal();
    $httpParsedResponseAr = $paypal->PPHttpPost('SetExpressCheckout', $padata, $PayPalApiUsername, $PayPalApiPassword, $PayPalApiSignature, $PayPalMode);

    //Respond according to message we receive from Paypal
    if ("SUCCESS" == strtoupper($httpParsedResponseAr["ACK"]) || "SUCCESSWITHWARNING" == strtoupper($httpParsedResponseAr["ACK"]))
    {
        //Redirect user to PayPal store with Token received.
        $paypalurl = 'https://www' . $paypalmode . '.paypal.com/cgi-bin/webscr?cmd=_express-checkout&token=' . $httpParsedResponseAr["TOKEN"] . '';
        header('Location: ' . $paypalurl);
    }
    else
    {
        echo 'eeee';
        $_SESSION['payment_error_detail'] = $httpParsedResponseAr["L_LONGMESSAGE0"];
        $app->redirect('index.php?page=paymentResponse&status=error');
    }
}

//Paypal redirects back to this page using ReturnURL, We should receive TOKEN and Payer ID
if (isset($_GET["token"]) && isset($_GET["PayerID"]))
{
    //we will be using these two variables to execute the "DoExpressCheckoutPayment"
    //Note: we haven't received any payment yet.

    $token    = $_GET["token"];
    $payer_id = $_GET["PayerID"];

    //get session variables

    $item              = $_SESSION['Item']; // Item Quantity
    $Item_total_price  = $_SESSION['ItemTotalPrice']; //(Item Price x Quantity = Total) Get total amount of product;
    $total_tax_amount  = $_SESSION['TotalTaxAmount'];  //Sum of tax for all items in this order.
    $handaling_cost   = $_SESSION['HandalingCost'];  //Handling cost for this order.
    $insurance_cost   = $_SESSION['InsuranceCost'];  //shipping insurance cost for this order.
    $shippin_discount = $_SESSION['ShippinDiscount']; //Shipping discount for this order. Specify this as negative number.
    $shippin_cost     = $_SESSION['ShippinCost']; //Although you may change the value later, try to pass in a shipping amount that is reasonably accurate.
    $grand_total      = $_SESSION['GrandTotal'];
    
    $param = '';
    for ($i=0; $i < count($item); $i++) 
    {
        $param .=   '&L_PAYMENTREQUEST_0_NAME'. $i      .'=' . ($item[$i]['itemname']) . 
                    '&L_PAYMENTREQUEST_0_NUMBER'. $i    .'=' . ($item[$i]['itemnumber']) .
                    '&L_PAYMENTREQUEST_0_DESC'. $i      .'=' . (substr($item[$i]['itemdesc'], 0, 100)).
                    '&L_PAYMENTREQUEST_0_AMT'. $i       .'=' . ($item[$i]['itemprice']) .
                    '&L_PAYMENTREQUEST_0_QTY'. $i       .'=' . ($item[$i]['itemQty']) ;
    }

    $padata = '&PAYMENTREQUEST_0_NOTIFYURL='. get_base_url() .'paypal/ipn_paypal.php' .
            '&TOKEN=' . ($token) .
            '&PAYERID=' . ($payer_id) .
            '&PAYMENTREQUEST_0_PAYMENTACTION=' . ("SALE") . $param .
            //set item info here, otherwise we won't see product details later
            '&PAYMENTREQUEST_0_ITEMAMT=' . ($Item_total_price) .
            '&PAYMENTREQUEST_0_TAXAMT=' . ($total_tax_amount) .
            '&PAYMENTREQUEST_0_SHIPPINGAMT=' . ($shippin_cost) .
            '&PAYMENTREQUEST_0_HANDLINGAMT=' . ($handaling_cost) .
            '&PAYMENTREQUEST_0_SHIPDISCAMT=' . ($shippin_discount) .
            '&PAYMENTREQUEST_0_INSURANCEAMT=' . ($insurance_cost) .
            '&PAYMENTREQUEST_0_AMT=' . ($grand_total) .
            '&PAYMENTREQUEST_0_CURRENCYCODE=' . ($PayPalCurrencyCode);
   
    // Execute the "DoExpressCheckoutPayment" at to Receive payment from user.
    $paypal               = new MyPayPal();
    $httpParsedResponseAr = $paypal->PPHttpPost('DoExpressCheckoutPayment', $padata, $PayPalApiUsername, $PayPalApiPassword, $PayPalApiSignature, $PayPalMode);
    //Check if everything went ok..
    if ("SUCCESS" == strtoupper($httpParsedResponseAr["ACK"]) || "SUCCESSWITHWARNING" == strtoupper($httpParsedResponseAr["ACK"]))
    {
        /*
          //Sometimes Payment are kept pending even when transaction is complete.
          //hence we need to notify user about it and ask him manually approve the transiction
         */
        $payment_status = $httpParsedResponseAr["PAYMENTINFO_0_PAYMENTSTATUS"];
        if ('Completed' == $payment_status)
        {
            $_SESSION['payment_message'] = '<div style="color:green">Payment Received! Your product will be sent to you very soon!</div>';
        }
        elseif ('Pending' == $payment_status)
        {
            $_SESSION['payment_message'] = '<div style="color:red">Transaction Complete, but payment is still pending! ' .
                    'You need to manually authorize this payment in your <a target="_new" href="http://www.paypal.com">Paypal Account</a></div>';
        }

        $padata               = '&TOKEN=' . ($token);
        $paypal               = new MyPayPal();
        $httpParsedResponseAr = $paypal->PPHttpPost('GetExpressCheckoutDetails', $padata, $PayPalApiUsername, $PayPalApiPassword, $PayPalApiSignature, $PayPalMode);

        if ("SUCCESS" == strtoupper($httpParsedResponseAr["ACK"]) || "SUCCESSWITHWARNING" == strtoupper($httpParsedResponseAr["ACK"]))
        {
            $buyerName      = $httpParsedResponseAr["FIRSTNAME"] . ' ' . $httpParsedResponseAr["LASTNAME"];
            $buyerEmail     = urldecode($httpParsedResponseAr["EMAIL"]);
            $purchase_id    = (isset($_GET['purchase_id'])) ? $_GET['purchase_id'] : null;
            $transaction_id = $httpParsedResponseAr['PAYMENTREQUESTINFO_0_TRANSACTIONID'];
//            $product_id     = $httpParsedResponseAr["L_PAYMENTREQUEST_0_NUMBER0"];
            
            $item              = $_SESSION['Item']; // Item Quantity
            for ($i=0; $i < count($item); $i++) 
            {
                $product_ids[]     = $httpParsedResponseAr["L_PAYMENTREQUEST_0_NUMBER". $i];                
            }

            if ($purchase_id)
            {
                // Save purchase data
                $query = "UPDATE bs_purchases SET transaction_id = '$transaction_id', payment_status = '$payment_status', transaction_date = NOW() 
                  WHERE id = $purchase_id";

                $insert_row = $mysqli->query($query);

                if ($insert_row)
                {
                    $emails    = array(array(
                            'email' => $buyerEmail,
                            'name'  => $buyerName
                        )
                    );
                    $user      = new UserController();
                    $user_info_arr = $user->get(array('id' => $_SESSION['user_id']));
                    $user_info = $user_info_arr[0];
                    if ($user_info!=null)
                    {
                        $user_email = (isset($user_info['email'])) ? $user_info['email'] : '';
                        $user_name  = (isset($user_info['firstname'])) ? $user_info['firstname'] : $user_info['username'];
                        $emails[]   = array(
                            'email' => $user_email,
                            'name'  => $user_name
                        );
                    }
                    // Send an email to buyer with the download link                                        
                    $product = new ProductController();
                    
                                   
                    $product->generate_download_link($purchase_id, $emails, $product_ids, $config['base_path']);
                }
                else
                {
                    die('Error : (' . $mysqli->errno . ') ' . $mysqli->error);
                }
            }

            $req  = 'cmd=_notify-validate';
            $test = array();
            foreach ($httpParsedResponseAr as $key => $value)
            {
                $value      = (stripslashes($value));
                $value      = preg_replace('/(.*[^%^0^D])(%0A)(.*)/i', '${1}%0D%0A${3}', $value); // IPN fix
                $test[$key] = urldecode($value);
            }

            /**Empty the cart in the case successful payment*/

            // for ($i=0; $i < count($item); $i++) 
            // {
            //     $product_ids[$i]     = $httpParsedResponseAr["L_PAYMENTREQUEST_0_NUMBER". $i];                
            // } 

            
            

            $app->redirect('index.php?page=payment_history&status=success');
        }
        else
        {
           
            $_SESSION['payment_error_detail'] = urldecode($httpParsedResponseAr["L_LONGMESSAGE0"]);
            $app->redirect('index.php?page=payment_history&status=error');
        }
    }
    else
    {
        
        $_SESSION['payment_error_detail'] = urldecode($httpParsedResponseAr["L_LONGMESSAGE0"]);
        $app->redirect('index.php?page=payment_history&status=error');
    }
}
?>
