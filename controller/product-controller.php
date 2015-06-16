<?php

/**
 * @project Bridge shoppingcart
 * Manage Product actions
 */
include_once 'controller/application-controller.php';

class ProductController extends AppController
{

    public $protocal_array    = '';
    public $host              = '';
    public $protocal          = '';
    public $request_uri_array = '';
    public $request_uri       = '';

    /**
     * Constructor
     */
    function __construct()
    {
        parent::__construct();
    }

    /**
     * Get all products/Specific product
     * @param array $filters
     * @return array
     */
    public function get($filters = array())
    {
        if (isset($filters['id']))
        {

            $result = $this->database->product_get_by_id($filters['id']);
            
        }
        else if (isset($filters['cat_id']) || isset($filters['p.cat_id']))
        {
            $result = $this->database->product_get_by_category($filters);
        }
        else if (isset($filters['token']))
        {
            $result = $this->database->get_product_by_download_token($filters['token']);
        }
        else if (isset($filters['cart']))
        {
            $result = $this->database->get_cart_products();
        }
        else
        {
            $result = $this->database->product_get_all($filters);
        }
        
        return $result;
    }


/**check image is present*/
public function imageExitence($id)
{
    $result = $this->database->product_image_check_id($id);
    return $result;
}

    /**
     * Insert the products data
     * @param array $data
     * @return int
     */
    public function insert($data, $type = null)
    {

        
        if(!empty($type) && $type == "addtocart")
        
            $result = $this->database->add_to_cart($data);
        
        else if(!empty($type) && $type == "purchases")
            $result = $this->database->update_purchase($data);
        else
            $result = $this->database->product_insert($data);
        
        
        
        return $result;
    }

    /** insert product images **/

    public function image_insert($data)
    {
        $product_id=$data['product_id'];

        $image_path = $data['image_path'];
        $result = $this->database->product_image_insert($product_id, $image_path);
        return $result;
    }
    /** Get product images**/
    public function get_prod_inages($id)
    {

        if (isset($id))
        {
            $result = $this->database->product_get_images_id($id);
        }

        return $result;
    
    }
    /***/
    public function prod_image_remove($data)
    {
       $result = $this->database->product_image_remove($data);
        return $result;
    }


    /**
     * Delete the product data
     * @param int $id
     */
    public function delete($id)
    {
        
        
       
        $result = $this->database->product_delete($id);

        return $result;
    }

    /*
     * Genarete and email the download link of the product
     * @param int purchase_id
     * @param array $emails
     * @param int $product_id
     * @return boolean
     */

    public function generate_download_link($purchase_id, $emails, $product_id = null, $base_url = '')
    {
        
        if ($product_id)
        {
            if(is_array($product_id)){
                
                foreach ($product_id as $key => $value) 
                {                    
                    $product_array     = $this->get(array('id' => $value));
                    $product_info      = $product_array[0];
                    date_default_timezone_set('Asia/Kolkata');
                    $expires_on       = time() + $product_info['validity'] * 3600;
                    $expire_timestamp = date('Y-m-d h:i:s A', $expires_on);
                    
                    $this->database->save_product_expiry($purchase_id, $value, "'$expire_timestamp'");
                }
            }
            else{
                
                $product_array     = $this->get(array('id' => $product_id));
                $product_info      = $product_array[0];
                $expires_on       = time() + $product_info['validity'] * 3600;
                $expire_timestamp = date('Y-m-d h:i:s A', $expires_on);

                $this->database->save_product_expiry($purchase_id, $product_id, "'$expire_timestamp'");
            }
            
            // Generate a download token and save to db
            $download_token   = md5(mt_rand());
            
                      
            $this->database->save_downlad_token($purchase_id, $download_token);

            //die(print_r($product_info));
            if (!empty($product_info))
            {

					$mail_info = array(
						'from_email'  => 'sobin87@gmail.com',
						'from_name'   => 'Bridge Team',
						'reply_email' => 'sobin87@gmail.com',
						'reply_name'  => 'Bridge Team',
						'to_email'    => $emails
					);
				
					$subject       = 'Bridgestore: Product download Link';
                $download_link = $base_url . 'index.php?page=downloader&token=' . $download_token;
                ob_start();
                
                /**Get User name and user first and last name*/
            
			include_once 'controller/user-controller.php';
            $user = new UserController();
            $userdetails = $user->get(array('id'=>$_SESSION['user_id']));
            
            $userdet = $userdetails[0];
            if(!empty($userdet))
            {
			$username = $userdet['username'];
			$firstname = $userdet['firstname'];
			$lastname = $userdet['lastname'];
			$user_email = $userdet['email'];
			if($lastname!='')
			$fullname = $firstname. " ". $lastname;
			else
			$fullname = $firstname;
			}
				
                $message       = '<div><p>Hi '.ucfirst($fullname).', </p><p>Thank you for your interest in Bridge Store. Please click on the below link to access your product.</p> <p><a href="http://'.$download_link.'">'.$download_link.'</a></p><p>Your Login Credentials: </p><p>Your registered email/ Username: '.$user_email.' <br/><br/>Your password were sent to the registered email-id when you registered with Bridge Store.</p><p>Best Regards,<br/>Bridge Team.</p></div>'; 
				
                if ($this->send_email($mail_info, $subject, $message))
                {
                   
                    return true;
                }
                else
                {
                    return false;
                }
            }
            else
            {
                return false;
            }
        }
        else
        {
            return false;
        }
    }

    /**
     * Check whether the token is valid
     * @param  string $token
     * @return boolean
     */
    public function is_valid_download_token($token)
    {
        if ($token)
        {
            return $this->database->validate_download_token($token);
        }
        else
        {
            return false;
        }
    }

    /**
     * Handle the file download
     * @param string $path
     * @param string $filename
     */
    public function download_file($path = '', $filename = '')
    {
        if ($path != '' && $filename != '')
        {
            $file           = $path . '/' . $filename;
            $finfo          = finfo_open(FILEINFO_MIME_TYPE);
            $file_mime_type = finfo_file($finfo, $file);
            $len            = filesize($file); // Calculate File Size            
            ob_clean();
            ob_start();
            if (headers_sent())
            {
                echo 'HTTP header already sent';
            }
            else if (!is_readable($file))
            {
                header($_SERVER['SERVER_PROTOCOL'] . ' 403 Forbidden');
                echo 'File not readable';
            }
            else
            {
                header("Pragma: public");
                header("Expires: 0");
                header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
                header("Cache-Control: public");
                header("Content-Description: File Transfer");
                header("Content-Type:pplication/octet-stream"); // Send type of file
                $header = "Content-Disposition: attachment; filename=$filename;"; // Send File Name
                header($header);
                header("Content-Transfer-Encoding: binary");
                header("Content-Length: " . $len); // Send File Size            
                readfile($file);
            }
        }
    }
    
    /**
     * Get the purchased products of a user
     * @param int $user_id
     * @return array
     */
    public function get_purchased_products($user_id, $section = null)
    {
        if($user_id)
        {
            $purchased_products = $this->database->get_user_purchased_products($user_id, $section);
            
          
            
            if($purchased_products)
            {
                return $purchased_products;
                
            }
            else
            {
                return false;
            }
        }
        else
        {
            return false;
        }
    }
    
    /**
     * Check whether a product is purchased
     * @param type $product_id
     * @param type $purchased_products
     */
    public function get_purchased_status($product_id, $purchased_products)
    {
        
    }
    
    
    /**
     * Empty cart after shopping
     * @param int $user_id
     * 
     * @author Jeny Devassy <jeny.devassy@bridge-india.in>
     * @date 12 Sep 2014
     */
    public function empty_cart($user_id, $product_id = null)
    {
       
        // if(empty($user_id))
            
        //     return false;
        
        $cart = $this->database->empty_cart($user_id, $product_id);
        
        if($cart)
            return $cart;
        else
            return false;
    }
    
    /**
     * Empty cart after shopping
     * @param int $user_id
     * 
     * @author Jeny Devassy <jeny.devassy@bridge-india.in>
     * @date 12 Sep 2014
     */
    public function delete_purchases($user_id)
    {
        if(empty($user_id))
            return false;
        
        $result = $this->database->delete_customer_purchases($user_id);
        if($result)
            return $result;
        else
            return false;
    }
    
    
    /**
     * Send password reset email
     * @author Soumya Kolloon
     * **/

	public function sendMail($rand_password)
	{
			/**Send email notification for the password*/
			ob_start();
			
			 $emails[]   = array(
                            'email' => $_POST['email'],
                            'name'  => ''
                        );
			
			$mail_info = array(
						'from_email'  => '',
						'from_name'   => 'Bridge Team',
						'reply_email' => '',
						'reply_name'  => 'Bridge Team',
						'to_email'    => $emails
					);
				
				$subject = 'Bridgestore: Password Reset';
				
				include_once 'controller/application-controller.php';
				$appObj = new AppController();
				
				$web_root = "http://".$_SERVER['HTTP_HOST'].dirname($_SERVER['PHP_SELF'])."/";
				
								
				$pwd_change_link = $web_root . 'index.php?page=change-password&email-token='.base64_encode($_POST['email']);		
			
				$message = '<div><p>Hi, </p><p>We received a password reset request and your password has been changed as <strong>'.$rand_password.'</strong>. Click the link below to change the password.<br/><a href="'.$pwd_change_link.'">'.$pwd_change_link.'</a></p><p>Best Regards,<br/>Bridge Team.</p></div>'; 
				
				
                if ($appObj->send_email($mail_info, $subject, $message))
                {
					//echo 'Password reset successfully. Please check your email to get new password.';
					//exit;
                    return true;
                }
                else
                {
					//echo 'errorsss'; exit;
                    return false;
                }
		
		
		
		
		
	}
	


  /**
     * Send password reset email
     * @author Soumya Kolloon
     * **/

	public function sendMail_welcome($data)
	{
		
				ob_start();
				/**Send welcome email***/
                    
                    
                     $emails[]   = array(
                            'email' => $data['email'],
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
				
						
				$web_root = "http://".$_SERVER['HTTP_HOST'].dirname($_SERVER['PHP_SELF'])."/";
				
				$user_email = $data['email'];
				$user_pwd = $data['password'];
							
			include_once 'controller/application-controller.php';
				$appObj = new AppController();
				$message = '<div><p>Dear Customer, </p><p>Congratulations! You have successfully created a new account with Bridge Store. Your account details are: </p><p>Registered email ID: '.$user_email.' </p><p>Password: <strong>'.$user_pwd.'</strong></p><p>Start login and enjoy your shopping experience with Bridge Store.</p><p><a href="'.$web_root.'" ><input type="button" value="Start Buying Products" style="color: #fff;background-color: #5bc0de;border-color: #46b8da;"></a></p>';
				
                if ($appObj->send_email($mail_info, $subject, $message))
                {
					echo 'Password reset successfully. Please check your email to get new password.'; //exit;
					return true;
                }
                else
                {
					return false;
				}
		
		
		
		
		
	}
	
	 /**Get Cart products**/
    public function get_cartItems($user_id)
    {
        $result = $this->database->get_cart_items($user_id);
        if($result)
            return $result;
        else
            return false;

    }


}
