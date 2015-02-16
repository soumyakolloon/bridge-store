<?php

/**
 * @project Bridge shoppingcart
 * Main Index page
 * modified: 16/02/2015 
 */
include('config.php');
include_once 'controller/user-controller.php';
include_once 'common/common-function.php';
include_once 'PHPMailer/PHPMailerAutoload.php';

php_session_start();
error_reporting(E_ERROR);
error_reporting(E_ALL);
error_reporting(E_NOTICE);
//error_reporting(0);
if (!isset($_SESSION ['user_id'])) {
    $_SESSION ['user_id'] = '';
}

$user = new UserController ();
$application = new AppController ();

// Login Submit Handler
if ((isset($_POST) ) && (isset($_POST ['btnLoginSubmit']) )) {
	
    $user->user_login(bridge_trim_deep($_POST));
	
    // If admin logged in
    if ($application->is_logged_in(1, false)) {
        $application->redirect("index.php?page=index");
    }
    else {
		print_r($_POST);
		if(isset($_POST['token']))
		{
			$token = $_GET['token'];
        include_once 'controller/product-controller.php';
        
        $product = new ProductController();
        $is_valid_token = $product->is_valid_download_token($token);
        if ($is_valid_token) {
          
            
            $product_info = $product->get(array('token' => $_GET['token']));
            $filename = $product_info[0]['id'] . '_' . $product_info[0]['download_link'];
            $path = $config['uploads_folder'];
            // Download file                              
            //$product->download_file($path, $filename);
            
           
            include("templates/downloadContent.php");
			
            
            //printf("<script>location.href='download.php?token=$token'</script>");
        }
        else {
			include_once('layout/header.php');
            echo 'Invalid Token';
            include_once('layout/footer.php');
        }
		}
		exit;
        /*if (isset($_SESSION['page']) && $_SESSION['page'] != '') {
            $url_string = 'index.php?page=' . $_SESSION['page'];
            if (isset($_SESSION['url_params']) && !empty($_SESSION['url_params'])) {
                foreach ($_SESSION['url_params'] as $key => $value) {
                    $url_string .= '&' . $key . '=' . $value;
                }
            }

            $application->redirect($url_string);
        }
        else {
            $application->redirect("index.php?page=index");
        }*/
    }
}

// Include the header layout
include_once 'layout/header.php';

$current_file_name = basename($_SERVER ['REQUEST_URI'], ".php");
$current_file_name = '';

if (basename($_SERVER ['REQUEST_URI'], ".php") == 'index') {
    $current_file_name = 'index';
}
else {
    if (isset($_GET ['page'])) {
        $current_file_name = $_GET ['page'];
    }
    else {
        $current_file_name = 'index';
    }
}

selectMenuItem($current_file_name);

if ($current_file_name == 'index') {

	
    include_once 'controller/product-controller.php';
    // Get products

    $product = new ProductController();
    //$products = $product->get(array('status' => 1));
    // Get categories
    include_once 'controller/category-controller.php';
    $category = new CategoryController();
    $categories = $category->get( '', array('c.status' => 1) );
    $products = $product->get(array('status' => 1));
    
    if ($application->is_logged_in(1, false)) {
       
        $home_page = true;
        //include_once 'templates/admin-dashboard.php';
        
        include_once 'templates/home.php';
        
        
    }
    else {
        if ($application->is_logged_in(0, false)) {
            
            //get purchased product ids
            
            
            $purchase_prod_arr = array();
            
            $purchased_products_array = $product->get_purchased_products($_SESSION['user_id']);
            if($purchased_products_array!=false)
             $purchase_prod_arr = $purchased_products_array; 
            
            $key = 'id';
            $purchased_products = array_map(function($item) use ($key) {
                return $item[$key];
            }, $purchase_prod_arr);
            
            if(!empty($_SESSION ['user_registration_success'])){
                
                $msg = $_SESSION ['user_registration_success'];
                $_SESSION ['user_registration_success'] = '';
            }
        }
        
        $home_page = true;
        include_once 'templates/home.php';
    }
}
// Login page
else if ($current_file_name == 'login') {
    if (isset($_SESSION ['user_id']) && ( $_SESSION ['user_id'] == '' )) {
		        
        $_SESSION['page'] = $_GET['page'];
        include_once 'templates/login.php';
        
        
    }
    else {
        $application->redirect("index.php");
    }
}
// Registartion
else if ($current_file_name == 'registration') {
    if (isset($_SESSION ['user_id']) && ( $_SESSION ['user_id'] == '' )) {
        include_once 'templates/registration.php';
    }
    else {
        if ($_SESSION ['user_role'] == 1) {
            $application->redirect('index.php?page=dashboard');
        }
        else {
            include_once 'templates/home.php';
        }
    }
}
// Buy It Now
else if ($current_file_name == 'buyitnow') {

    if (!$application->is_logged_in(0, false)) {
        if (isset($_GET['product_id']) && $_GET['page'] == 'buyitnow') {
            $_SESSION ['buy_it_now_product_id'] = $_GET['product_id'];
            //include_once 'templates/login.php';
            $_SESSION['page'] = $_GET['page'];
            $_SESSION['url_params'] = array('product_id' => $_GET['product_id']);
        }
        $application->redirect("index.php?page=login");
    }
    else {
        if (!$application->is_admin()) {
            // Get the selected product detail
            $product_id = (isset($_GET['product_id'])) ? $_GET['product_id'] : '';
            if ($product_id != '') {
                include_once 'controller/product-controller.php';
                $product = new ProductController();
                $productInfo = $product->get(array('id' => $product_id));
            }

            include_once 'templates/buy-it-now.php';
        }
        else {
            $application->redirect('index.php?page=index');
        }
    }
}
// Add To Cart
    else if ($current_file_name == 'addtocart') {
   
    include_once 'controller/product-controller.php';
    $product = new ProductController();
   
        
        
                if (isset($_GET['product_id']) && $_GET['page'] == 'addtocart') {

                        if(isset($_COOKIE['id']))
                        {    
                        $productIdArray = unserialize($_COOKIE["id"]);
                        if(!in_array($_GET['product_id'], $productIdArray))
                        array_push($productIdArray, $_GET['product_id']);
                         }
                         else
                         {
                            $productIdArray = array($_GET['product_id']);
                         }  
                            
                             
                        
                }
               else
                {
                        if(isset($_COOKIE['id']))
                          
                        $productIdArray = unserialize($_COOKIE["id"]);



                }
              
                  for($pr=0; $pr<count($productIdArray); $pr++)
                    {
					
                    $prd[$pr] = $product->get(array('id' => $productIdArray[$pr]));            
					//	print_r(empty($produ[$pr]));
					if(!empty($prd[$pr]))
					$produ[$pr] = $prd[$pr];
                    } 

					$produ = array_values($produ);

                    if(empty($_SESSION['user_id']))
                    {
                        $products = $produ;
                     
                     }
        // else
        if(!empty($_SESSION['user_id']))
        {
           
             if (isset($_GET['product_id']) && $_GET['page'] == 'addtocart') {

                $cart = $product->insert(array(
                'product_id' => $_GET['product_id'],
                'user_id' => $_SESSION ['user_id'],
                'date_time' => date("Y-m-d h:i:s")
                    ), 'addtocart');

             }
            

            $prod[] = $product->get(array(
            'cart' => true,
           'user_id' => $_SESSION ['user_id'],
            ));


            for($r=0;$r<count($prod[0]); $r++)
            {
                $prodArr[$r][0] = $prod[0][$r]; 
            }
            // echo '<pre>';
            // print_r($prodArr);  exit;




            if(!empty($produ) && !empty($prodArr))
           $products = array_merge($prodArr, $produ);


            else if(!empty($prodArr))
            $products = $prodArr;
            else if(!empty($produ))
            $products = $produ;

          /**Avoid duplicate entries from array*/
          
          $tmarray = $products;
          $insertArray=array();
         
          for($i=0; $i<count($products); $i++)
          {
             $c=0;

            for($j=0; $j<count($tmarray); $j++)
            {
                if($products[$i][0]['id']==$tmarray[$j][0]['id'])
                    $c++;
                if($c>1)
                    unset($tmarray[$i]);
            }

          }  

          $products = $tmarray; 
        }

       
        include_once 'templates/addtocart.php';

      
}
/* Check out */
else if ($current_file_name == 'checkout') {
    if (!$application->is_logged_in(0, false)) {
        if (isset($_GET['product_id']) && $_GET['page'] == 'checkout') {
            $_SESSION['page'] = $_GET['page'];
            $_SESSION['url_params'] = array('product_id' => $_GET['product_id']);
        }
        $application->redirect("index.php?page=login");
    }
    else {
        include_once 'controller/product-controller.php';
        /* Fetch products details */

        $product = new ProductController();
        $productDetails = $product->get(array('id' => $_GET['product_id']));

        include_once 'templates/checkout.php';
        //$application->redirect("paypal/paypal.php?action=process");
    }
}
// Dashboard
else if ($current_file_name == 'dashboard') {
    $application->is_logged_in(1);
    include_once 'templates/admin-dashboard.php';
}
// Categories List
else if ($current_file_name == 'categories') {
    $application->is_logged_in(1);

    include_once 'controller/category-controller.php';
    include_once 'controller/paginate-controller.php';
    include_once 'controller/product-controller.php';

    $paginator = new PaginateController ();
    $category = new CategoryController ();
    $product = new ProductController();
    $categories = $category->get();

    foreach($categories as $cat)
    {
        $products = $product->get(array('cat_id'=>$cat['id']));
        if(count($products)==0)
        {
            $empty_prod_cat[] = $cat['id'];
        }
    }

 


    include_once 'templates/categories.php';
}
// New category
else if ($current_file_name == 'new-category') {
    $application->is_logged_in(1);

    include_once 'templates/new-category.php';
}
// Edit category
else if ($current_file_name == 'edit-category') {
    $application->is_logged_in(1);

    $categoryId = $_GET ['id'];

    include_once 'controller/category-controller.php';
    $category = new CategoryController ();
    $category_info = $category->get($categoryId);

    include_once 'templates/new-category.php';
}
// Delete category
else if ($current_file_name == 'delete-category') {
    $application->is_logged_in(1);

    include_once 'controller/category-controller.php';
    $category = new CategoryController ();
    $id = $_GET ['id'];
    $category->delete($id);
    $category->redirect('index.php?page=categories');
}
// Products listing
else if ($current_file_name == 'products') {
    $application->is_logged_in(1);

    // include_once 'products.php';
    include_once 'controller/product-controller.php';
    include_once 'controller/paginate-controller.php';
    include_once 'controller/category-controller.php';
    
    $category = new CategoryController ();
    $categories = $category->get();

    $paginator = new PaginateController ();
    $product = new ProductController ();
    $filters = (!empty($_GET)) ? $_GET : array();
    
    $products = $product->get(array_filter($filters));
    
    include_once 'templates/products.php';
}
//product detail page

else if ($current_file_name == 'product-detail') {
    
    //get product details
     include_once 'controller/product-controller.php';
    
     $product = new ProductController();
     $product_id = $_GET['product_id'];
  

    $productArray = $product->get(array('id'=>  $product_id));

    $prod_images = $product->get_prod_inages($product_id);

		//echo '<pre>';
     //print_r($productArray); exit;

    include_once 'templates/product-detail.php';


}



// New product
else if ($current_file_name == 'new-product') {
    $application->is_logged_in(1);

    include_once 'controller/category-controller.php';
    $category = new CategoryController ();
    $categories = $category->get();

    include_once 'templates/new-product.php';
}
// Edit product
else if ($current_file_name == 'edit-product') {
    $application->is_logged_in(1);

    $product_id = $_GET ['id'];
    include_once 'controller/product-controller.php';
    $product = new ProductController ();
    $productArray = $product->get(array('id' => $product_id));
    $product_info = $productArray[0];
    $product_images = $product->get_prod_inages($product_id);
    // echo '<pre>';
    // print_r($product_info);
    // die();

    include_once 'controller/category-controller.php';
    $category = new CategoryController ();
    $categories = $category->get();

    include_once 'templates/new-product.php';
}
// Delete product
else if ($current_file_name == 'delete-product') {
    
    
    // if(empty($_GET['section']))
       
    //     $application->is_logged_in(1);
    //     else

    //     $application->is_logged_in(0);

    include_once 'controller/product-controller.php';
    $product = new ProductController ();
    $id = $_GET ['id'];

    if (isset($_GET['section']) && $_GET['section'] == 'cart' && !empty($_GET ['id'])) {

        if(isset($_SESSION['user_id']) && !empty($_SESSION['user_id']))
        {
        $response =  $product->empty_cart($_SESSION ['user_id'], $id);
        
        // if(empty($response)) 
        // {
        
        //  $cookiedata = unserialize($_COOKIE['id']);
        //  for($e=0; $e<count($cookiedata); $e++)
        //  {
        //    if($cookiedata[$e]==$_GET['id'])
        //      unset($cookiedata[$e]);

        //  }
        
        // $cartvalues= serialize($cookiedata);

        // }
        }
        
       //  if(!empty($cartvalues))
       //  $product->redirect('index.php?page=addtocart&cart-val='.$cartvalues);
       // else
        $product->redirect('index.php?page=addtocart');

    }
    else {
        
        $productInfo = $product->get(array('id' => $id));
        
        $prod_images = $product->get_prod_inages($id);

        if($product->delete($id))
        {
            //echo '<pre>';
            //print_r($productInfo); exit;
            
            @unlink ("uploads/".$productInfo[0]['download_link']);
           
            foreach($prod_images as $img)
            {
                @unlink("uploads/".$img['image_path']);
            }

       }

        $product->redirect('index.php?page=products&cat_id='.$_GET['cat_id']);
    }
}

// All Purchases list
else if ($current_file_name == 'purchases') {
        
    $application->is_logged_in(1); // only admin allowed
    
    include_once 'controller/product-controller.php';
    include_once 'controller/paginate-controller.php';

    $paginator = new PaginateController ();    
    $product   = new ProductController();
    
    $purchased_products = $product->get_purchased_products('all', 'history'); // get all purchase history

    foreach ($purchased_products as $key => $products) 
    {
        $transactions[$products['purchase_id']]['purchase_id'] = $products['purchase_id'];
        $transactions[$products['purchase_id']]['transaction_id'] = $products['transaction_id'];
        $transactions[$products['purchase_id']]['payment_status'] = $products['payment_status'];
        $transactions[$products['purchase_id']]['total_price'] = $products['total_price'];
        $transactions[$products['purchase_id']]['date_time'] = $products['date_time'];
        $transactions[$products['purchase_id']]['token'] = $products['token'];
        $transactions[$products['purchase_id']]['user'] = $products['user'];
        $transactions[$products['purchase_id']]['email'] = $products['email'];
        $transactions[$products['purchase_id']]['products'][] = $products;
    }
    $transactions = array_values($transactions);
    
    include_once 'purchases.php';
}
// Customers
else if ($current_file_name == 'customers') {
  
    
    if(isset($_GET['validate_msg']) && $_GET['validate_msg']==2)
    {
        echo '<label class="alert alert-success alert-dismissable" id="validate_msg">Admin User cannot be removed</label>';
    }
    if(isset($_GET['validate_msg']) && $_GET['validate_msg']==1)
    {
        echo '<label class="alert alert-success alert-dismissable" id="validate_msg">User removed successfully</label>';
    }
    
    $application->is_logged_in(1);
    
    include_once 'controller/user-controller.php';
    include_once 'controller/paginate-controller.php';

    $paginator = new PaginateController ();
    
    $users = new UserController();
    $allusers = $users->get();
    
    include_once 'customers.php';
}

// products view
else if ($current_file_name == 'products-view') {
    include_once 'controller/product-controller.php';
    // Get products
    $product = new ProductController();
    
    if (isset($_GET['cat_id'])) {
        $products = $product->get( array( 
            'p.cat_id' => $_GET['cat_id'], 
            'p.status' => '1'));
        
        if (count($products) > 0) {
            $cat_name = (isset($products[0]['catName'])) ? $products[0]['catName'] : '';
        }
        else {
            $cat_name = '';
        }
    }
    else {
        $products = $product->get();
    }

    // Get categories
    include_once 'controller/category-controller.php';
    $category = new CategoryController();
    $categories = $category->get( '', array('c.status' => 1) );
    
    include_once 'templates/home.php';
}

// Paypal success
else if ($current_file_name == 'paypal') {
    if (isset($_GET['action']) && $_GET['action'] == 'success') {
        foreach ($_POST as $key => $value) {
            echo "$key: $value<br>";
        }
    }
}

// Paypal Response cancelled before payment
else if ($current_file_name == 'paymentCancel') {
    
    // Get products
    include_once 'controller/product-controller.php';
    $product = new ProductController();
    $product->insert( array( 'user_id' => $_SESSION ['user_id'],
        'status' => 'Cancelled') , 'purchases');
    
    $application->redirect("index.php?page=paymentResponse&status=cancelled");
    
}

// Paypal Response success
else if ($current_file_name == 'paymentResponse') {
   
    // Get products
    include_once 'controller/product-controller.php';
    $product = new ProductController();
    
    if (isset($_GET['status'])) {
        
        $payment_status = $_GET['status'];
        
        if ($payment_status == 'error')
            $message = "The payment not completed because of an error!";
        else if ($payment_status == 'cancelled')
            $message = "The payment cancelled by customer!";
        
        else {
            $message = "Your payment has been successfully completed!";
          
            $product_id=null;
            if(isset($_GET['product_id']))
            $product_id = $_GET['product_id'];
            
            $product->empty_cart($_SESSION ['user_id'], $product_id);
            
        }
    }    
    
   
    $product->delete_purchases($_SESSION ['user_id']);
    
    include_once 'templates/paymentResponse.php';
}

// Downloader page
else if ($current_file_name == 'downloader') {
  
	include_once 'controller/user-controller.php';
  
	
    if (isset($_GET['token']) && $_GET['token'] != '') {
        $token = $_GET['token'];
        include_once 'controller/product-controller.php';
        
        $product = new ProductController();
        $is_valid_token = $product->is_valid_download_token($token);
        if ($is_valid_token) {
          
            
            $product_info = $product->get(array('token' => $_GET['token']));
            $filename = $product_info[0]['id'] . '_' . $product_info[0]['download_link'];
            $path = $config['uploads_folder'];
            // Download file                              
            //$product->download_file($path, $filename);
            
           if(!empty($_SESSION['user_id']))
			{
            include("templates/downloadContent.php");
			}
			else
			{
			$user = new UserController();
			$user->redirect('index.php?page=login&fromhost=mail&token='.$_GET['token']);
			}
            
            //printf("<script>location.href='download.php?token=$token'</script>");
        }
        else {
            echo 'Invalid Token';
        }
    }
	
    else {
			
    }
}
// mail test
else if ($current_file_name == 'mailtest') {
    include_once 'controller/product-controller.php';
    $product = new ProductController();
    $product->generate_download_link(3, 'sobin.yohannan@bridge-india.in', 5, $config['base_url']);
}

// user payment history
else if ($current_file_name == 'payment_history') {
    
    $application->is_logged_in(0);
    
    include_once 'controller/product-controller.php';
    
    $product = new ProductController();
    
    $purchased_products = $product->get_purchased_products($_SESSION['user_id'], 'history');
   
    if($purchased_products!=false)
    {
      
    foreach ($purchased_products as $key => $products) 
    {
        $transactions[$products['purchase_id']]['purchase_id'] = $products['purchase_id'];
        $transactions[$products['purchase_id']]['transaction_id'] = $products['transaction_id'];
        $transactions[$products['purchase_id']]['payment_status'] = $products['payment_status'];
        $transactions[$products['purchase_id']]['total_price'] = $products['total_price'];
        $transactions[$products['purchase_id']]['date_time'] = $products['date_time'];
        $transactions[$products['purchase_id']]['token'] = $products['token'];
        $transactions[$products['purchase_id']]['products'][] = $products;
    }
    }
    
   
    include("templates/payment_history.php");
}
//user delete
else if($current_file_name == 'customer_delete')
{
        $id=$_GET['id'];
        
        //check the selected user is admin
        
        $user_arr = $user->get(array('id'=>$id));
        $user_info = $user_arr[0];
        
        if($user_info['role_id']!=1)
        {
        $user->user_delete($id);
              
        $user->redirect('index.php?page=customers&validate_msg=1');
        }
        
        else
        {
             $user->redirect('index.php?page=customers&validate_msg=2');
        }
}

// user download history
else if ($current_file_name == 'download_history') {
    
    $application->is_logged_in(0);
    
    get_purchase_details();
//    include("templates/payment_history.php");
}
// user download history
// else if ($current_file_name == 'verifylogin') {
    
//         include_once('controller/user-controller.php');
//         echo 'fs'; die();
//         $user = new UserController ();
//         $userDetails = $user->user_existence_check($_POST['email']);
//         print_r($userDetails); die();
// }

/**
 * function to get full purchase details
 * 
 * @author Jeny Devassy <jeny.devassy@bridge-india.in>
 * @modified on 16 Sep 2014
 */
//function get_purchase_details(){
//    
//    include_once 'controller/product-controller.php';
//    
//    $product = new ProductController();
//    
//    $purchased_products = $product->get_purchased_products($_SESSION['user_id'], 'history');
//    
//    foreach ($purchased_products as $key => $products) 
//    {
//        $transactions[$products['transaction_id']]['transaction_id'] = $products['transaction_id'];
//        $transactions[$products['transaction_id']]['payment_status'] = $products['payment_status'];
//        $transactions[$products['transaction_id']]['total_price'] = $products['total_price'];
//        $transactions[$products['transaction_id']]['date_time'] = $products['date_time'];
//        $transactions[$products['transaction_id']]['products'][] = $products;
//    }
//    return $transactions;
//    
//}

include_once 'layout/footer.php';

?>
