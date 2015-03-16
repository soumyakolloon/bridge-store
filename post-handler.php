<?php

include_once 'common/common-function.php';
include_once 'controller/base-controller.php';
php_session_start();

$base = new BaseController();
/**
 * @project Bridge shoppingcart
 * Manage submitted form values( POST )
 */
if (isset($_REQUEST['action']))
{
    /* User Registartion */
    if ($_REQUEST['action'] == 'REGISTRATION')
    {
        if (( $_POST['firstname'] ) && ( $_POST['email'] ) && ( $_POST['password'] ) && ( $_POST['confirmpassword'] ))
        {
			
            if (( $_POST['password'] == $_POST['confirmpassword']))
            {
                include_once 'controller/user-controller.php';
                $user = new UserController();

                $result = $user->user_registration(bridge_trim_deep($_POST));

                $_SESSION ['user_registration_error']          = '';
                $_SESSION ['user_registration_password_error'] = '';
                $_SESSION ['user_registration_username_error'] = '';
                $_SESSION ['user_registration_post']           = '';

                if ($result)
                {
                    $_SESSION ['user_registration_success']    = 'Account created successfully';
                    
                    
                    /**Send welcome email***/
                    
                    
                     $emails[]   = array(
                            'email' => $_POST['email'],
                            'name'  => ''
                        );
			
					$mail_info = array(
						'from_email'  => 'team@bridge.in',
						'from_name'   => 'Bridge Team',
						'reply_email' => '',
						'reply_name'  => 'Bridge Team',
						'to_email'    => $emails
					);
				
				$subject = 'Bridgestore Account Created!';
				
				include_once('controller/application-controller.php');
				$appObj = new AppController();
				include_once 'PHPMailer/PHPMailerAutoload.php';
				$web_root = "http://".$_SERVER['HTTP_HOST'].dirname($_SERVER['PHP_SELF'])."/";
				
				$user_email = $_POST['email'];
				$user_pwd = $_POST['password'];
				
		
				//$pwd_change_link = $web_root . 'index.php?page=change-password&email-token='.base64_encode($_POST['email']);		
			
				$message = '<div><p>Dear <strong>Customer</strong>, </p><p>Congratulations! You have successfully created a new account with Bridge Store. Your account details are: </p><p>Registered email ID: '.$user_email.' </p><p>Password: <strong>'.$user_pwd.'</strong></p><p>Start login and enjoy your shopping experience with Bridge Store.</p><p><a href="'.$web_root.'index.php?page=login" >'.$web_root.'index.php?page=login</a></p><p>Best Regards,</br>Bridge Store Team.</p>';
				
                if ($appObj->send_email($mail_info, $subject, $message))
                {
					echo 'Password reset successfully. Please check your email to get new password.';
					
                }
                             
                
                    
                    
                    /*
                     * Registration success
                     * Redirect to login page */
                    $user->redirect("index.php?page=login");
                }
                else
                {

                    $_SESSION ['user_registration_username_error'] = 1;
                    $_SESSION ['user_registration_post']           = $_POST;
                    /*
                     * Registration error (Username already exist)
                     * Redirect to registration page */
                   $user->redirect("index.php?page=registration");
                }
            }
            else
            {
                /*
                 * Registartion error (Password and Confirmpassword are not matched)
                 *   */
                $_SESSION ['user_registration_error']          = 1;
                $_SESSION ['user_registration_password_error'] = 1;
            }
        }
        else
        {
			echo 'inside else';
            /*
             * Registartion error (Mandatory fields are empty)
             *   */
            $_SESSION ['user_registration_error'] = 1;
        }
    }

    /* Category insert */
    if ($_REQUEST['action'] == 'CATEGORY')
    {
        if ($_POST['category-name'])
        {
            include_once 'controller/category-controller.php';
            $category     = new CategoryController();
            $data['name'] = mysql_escape_string($_POST['category-name']);
            if (isset($_POST['category-id']))
            {
                $data['id'] = $_POST['category-id'];
            }
            $data['status'] = $_POST['cat-status'];
            $result         = $category->insert($data);
            
            if(!empty($data['id']) && $data['status'] == '0' ){
                
                include_once 'controller/product-controller.php';
                $product         = new ProductController();
                $productList     = $product->get( array('cat_id' => $data['id']) );
                
                if(!empty($productList)){
                    
                    foreach ($productList as $products) 
                    {
                        $product->insert( array(
                            'id' => $products['id'],
                            'status' => $data['status']
                            ) );
                    }
                }
            }

            /* Redirect to categories page */
            $category->redirect("index.php?page=categories");
        }
    }

    /* Product insert */
    if ($_REQUEST['action'] == 'PRODUCT')
    {
     
      /*  echo '<pre>';
        print_r($_FILES); 
        exit;*/
       
       

        include_once 'controller/product-controller.php';
        include_once 'controller/upload-controller.php';

        $product         = new ProductController();
        $uploadedFile    = '';
        $uploadedProduct = '';
        
        $image_path    = (isset($_FILES['product-image']['name'][0]) && $_FILES['product-image']['name'][0] != null) ? mysql_escape_string($_FILES['product-image']['name'][0])  : '';
        $download_link = (isset($_FILES['product-upload']['name']) && $_FILES['product-upload']['name'] != null) ? mysql_escape_string($_FILES['product-upload']['name'])  : '';
        $product_id    = (isset($_POST['product-id'])) ? $_POST['product-id'] : '';
        
         // if(isset($_FILES['product-upload']) && isset($_FILES['product-upload']['error']))
         //   {
         //    echo "inside";
         //    $imageError='';
         //    $prodError= $_FILES['product-upload']['error'];
         //     $product->redirect("index.php?page=new-product&imgErr=".$imageError."&prodErr=".$prodError);
         // }

        //echo $image_path; exit;
       
        if (isset($_POST['product-id']))
        {


            $data['id'] = $_POST['product-id'];
            $product_id  = $data['id'];



            if ($image_path === '')
            {

                $image_path    = $_POST['hid-product-image'];
                $product_insert['image_path'] = $image_path;
                $uploadedFile = $image_path;
            }

            if ($download_link === '')
            {
                $download_link    = $_POST['hid-product-upload'];
                $product_insert['download_link'] = $download_link;

                $uploadedProduct = $download_link;
            }
        }

        

        $data['name']         = $_POST['product-name'];
        $data['description']  = $_POST['product-desc'];
        $data['cat_id']       = $_POST['product-cat'];
        $data['price']        = (is_numeric($_POST['product-price'])) ? $_POST['product-price'] : 0;
        $data['validity']     = (is_numeric($_POST['product-validity'])) ? $_POST['product-validity'] : 0;
        $data['status']       = ( $_POST['product-status'] == 0 ) ? 0 : $_POST['product-status'];
        $data['image_path']   = $image_path;
        $data['download_link']= $download_link;
        
        
       
        

    // save uploaded image details

       
//         if(isset($_FILES['product-image'])){
//         $errors= array();
//         foreach($_FILES['product-image']['tmp_name'] as $key => $tmp_name ){
//         $file_name = $key.$_FILES['product-image']['name'][$key];
//         $file_size =$_FILES['product-image']['size'][$key];
//         $file_tmp =$_FILES['product-image']['tmp_name'][$key];
//         $file_type=$_FILES['product-image']['type'][$key];  
//         if($file_size > 2097152){
//             $errors[]='File size must be less than 2 MB';
//         }    
      

//         $desired_dir="uploads";

//         if(empty($errors)==true){
//             if(is_dir($desired_dir)==false){
//                 mkdir("$desired_dir", 0700);        // Create directory if it does not exist
//             }
//             if(is_dir("$desired_dir/".$file_name)==false){
//                 move_uploaded_file($file_tmp,"$desired_dir/".$file_name);
//             }else{                                  // rename the file if another one exist
//                 $new_dir="$desired_dir/".$file_name.time();
//                  rename($file_tmp,$new_dir) ;               
//             }
        
//        $data = array('product_id'=>$result, 'image_path'=>$file_name);

//        $product->image_insert($data);

//         }
//         else {
//                 print_r($errors);
//         }
//     }
   
// }



// exit;

        // DB update uploaded files
        $imageError = '';
        $prodError  = '';  
       $result = $product->insert($data); 
       // die($data['image_path']);
        if(!empty($data['image_path']) )
        {


          

          // echo $result; exit;
        $config = array(
            'overwrite'       => true,
            'upload_path'     => 'uploads',
            'allowed_types'   => $config['allowed_types'],
            'filename_prefix' => ($result != null) ? $result . '_' : $product_id . '_'
        );
        

        $upload = new UploadController($config);
         if (isset($_POST['product-id']))
            {
            $prod_id=$_POST['product-id'];
            }
            else
            {
                $prod_id = $result;
            }
            
           foreach($_FILES['product-image']['tmp_name'] as $key => $tmp_name ){
             $file_name = trim($_FILES['product-image']['name'][$key]);
            $file_tmp =$_FILES['product-image']['tmp_name'][$key];


            if ($uploadedFile === '')
            {

            $uploadedFile = $upload->do_upload('product-image');
            }


            if(!empty($uploadedFile) ){
            
            if(is_int($uploadedFile))
            {
                $imageError = $uploadedFile;
                $product->redirect("index.php?page=edit-product&id=".$_POST['product-id']."&imgErr=".$imageError."&prodErr=".$prodError);

            }

            else
            {
                 $file_path = trim($prod_id."_".$_FILES['product-image']['name'][$key]);

                $desired_dir="uploads";
                chmod($file_tmp, 0777);
                 move_uploaded_file($file_tmp,"$desired_dir/".$file_path);

                 $data_for_images = array('product_id'=>$prod_id, 'image_path'=>$file_name);

                 $product->image_insert($data_for_images);
             }
         
        }


             

           }

        }
        else
        {     
            if(empty($data['image_path']))
             $imageError=4;
            if(empty($data['download_link']))
             $prodError=4;

              $product->redirect("index.php?page=edit-product&id=".$_POST['product-id']."&imgErr=".$imageError."&prodErr=".$prodError);
        }

        /* Upload the thumbnail image and set path */
        $config = array(
            'overwrite'       => true,
            'upload_path'     => 'uploads',
            'allowed_types'   => $config['allowed_types'],
            'filename_prefix' => ($result != null) ? $result . '_' : $product_id . '_'
        );
        

        $upload = new UploadController($config);



        


        // Upload product
        if ($uploadedProduct === '')
        {
            $uploadedPrduct = $upload->do_upload('product-upload');
        }
              
        
       // If upload not null prepare for db entry
        
        if( !empty($uploadedPrduct) ){
            
            if(!empty($uploadedPrduct) && is_int($uploadedPrduct))
                {
                $prodError = $uploadedPrduct;
            }
            else
            {
                $product_insert['download_link'] = trim(preg_replace('/'.$config["filename_prefix"].'/', '', $uploadedPrduct, 1), '\'');

                // $desired_dir="uploads";

                // $prod_file_name = $."_".$product_insert['download_link'];
                //  move_uploaded_file($file_tmp,"$desired_dir/".$prod_file_name);
            }
        }

  
        // echo 'dsf<pre>';
        // print_r($product_insert);
        // exit;


        if(!empty($product_insert)){
            // $data['download_link'] = $product_insert['download_link'];
            // $data['image_path'] = $product_insert['image_path'];

           
            $product_insert['id'] = rtrim($config["filename_prefix"], '_') ;
            $product->insert($product_insert);

            $prod_images = $product->get_prod_inages($product_insert['id']);

            $prod_image_latest['image_path'] = $prod_images[0]['image_path'];
            $prod_image_latest['id'] = rtrim($config["filename_prefix"], '_') ;

           // $product->insert($prod_image_latest);
                        
        }

        
        // Redirect to edit page 
        if(!empty($imageError) || !empty($prodError))
            $product->redirect("index.php?page=edit-product&id=".$_POST['product-id']."&imgErr=".$imageError."&prodErr=".$prodError);
        else
            $product->redirect("index.php?page=products&msg=1&cat_id=".$_REQUEST['cat_id']);
    }
    
    

    /* User update */
    if ($_REQUEST['action'] == 'EDIT_USER')
    {
        include_once 'controller/user-controller.php';            
        $users = new UserController();
        
        if ($_POST['id'])
            $var = $users->user_update($_POST);
        
        
        /* Redirect to categories page */
        $users->redirect("index.php?page=customers&pageNo=". $_GET['page']);
    }
}
?>
