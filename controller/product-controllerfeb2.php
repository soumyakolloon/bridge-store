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
            $result = $this->database->get_cart_products($filters['user_id']);
        }
        else
        {
            $result = $this->database->product_get_all($filters);
        }
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
                include('layout/download_link_mail.php');
                $message       = ob_get_contents();
                ob_end_clean();
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
        if(empty($user_id))
            return false;
        
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

}
