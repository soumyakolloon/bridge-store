<?php

/**
 * @project Bridge shoppingcart
 * Manage database queries and methods
 */
include_once 'config.php';
include_once 'base-controller.php';

class DataBaseController extends BaseController
{

    protected $db_host;
    protected $db_user;
    protected $db_pass;
    protected $db_name;
    protected $db_table_prefix;

    /**
     * Constructor
     */
    function __construct()
    {
//        $config                = get_db_config();
//        $this->db_host         = $config['host'];
//        $this->db_user         = $config['user'];
//        $this->db_pass         = $config['password'];
//        $this->db_name         = $config['name'];
//        $this->db_table_prefix = $config['table_prefix'];
//        $this->db_type         = '';
//
        parent::__construct();
        $this->db_connection($this->db_type);     
    }

    /**
     * Create the DB conenction
     */
    function db_connection($mode = 'mysql')
    {

//        parent::queryText('Select * from bs_users ');
//
//        print_r('Query: '.$this->query . '<br>');
//        print_r('Exec: '.$this->sqlResult . '<br>');
//        echo '<br> Assoc: ';
//        print_r($this->sqlAssoc);
//        echo 'Row: ';
//        print_r($this->sqlRow);
//        echo '<br> Num: ';
//        print_r($this->rowCount);
//        print_r($this->sqlAffected);
//        echo '<br> Specific: ';
//        print_r($this->sqlGetField);
//        echo '<br> Escape string: ';
//        print_r(parent::sqlString('abcs'));
//        die('here');

    }

    /**
     * Validate the user credentials
     * @param string $username
     * @param string $password
     */
    function user_login($username, $password)
    {

        $query = "SELECT u.*
        		  FROM " . $this->db_table_prefix . "users u
        		  JOIN " . $this->db_table_prefix . "roles r ON u.role_id = r.id
        		  WHERE username='" . $username . "' AND password='" . md5($password) . "' 
                  AND u.status = '1' LIMIT 0,1";
        
        $result = $this->commonDatabaseAction($query); 
        $user_details = array();        
        $user_details = $this->sqlAssoc;
        
//        if (mysql_num_rows($result) > 0 && !empty($user_details))
        if ($this->rowCount > 0 && !empty($user_details))
        {   
            $_SESSION ['user_id']         = $user_details[0]['id'];
            $_SESSION ['user_first_name'] = $user_details[0]['firstname'];

            if ($user_details[0]['lastname'] !== '')
            {
                $_SESSION ['user_last_name'] = $user_details[0]['lastname'];
            }
            else
            {
                $_SESSION ['user_last_name'] = '';
            }

            $_SESSION ['user_role'] = $user_details[0]['role_id'];
        }
        else
        {
            $_SESSION ['user_login_error'] = 1;
        }
    }

    /**
     * Get all categories
     * @return array
     */
    public function category_get_all( $data = null )
    {
        $query = "SELECT c.*, count( p.id ) AS no_of_products 
                   FROM " . $this->db_table_prefix . "categories c "
                . "LEFT JOIN " . $this->db_table_prefix . "products p "
                . "ON c.id = p.cat_id AND p.status = '1' ";
        
        $condition = '';
        if(!empty($data)){
            
            foreach ($data as $key => $value) 
            {
                $condition .= $key ." = '". $value ."' AND ";
            }
            if(!empty($condition)){
                $pos = strrpos($condition, " AND ");
                $condition = substr($condition,0,$pos);
                $query = $query. " WHERE ". $condition;
            }
        }
        $query = $query. " group by c.id ";

        $result = $this->commonDatabaseAction($query);
//        if (mysql_num_rows($result) > 0)
        if ($this->rowCount > 0)
        {
            //return $result;
            return $this->resultArray($result);
        }
        else
        {
            return array();
        }
    }

    /**
     * Insert category
     * @param array $data
     * @return boolean
     */
    public function category_insert($data)
    {
        if (isset($data['id']))
        {
            $query = "UPDATE
            		 " . $this->db_table_prefix . "categories
            		 SET name = '" . $data['name'] . "', status = " . $data['status'] . "
            		 WHERE id = " . $data['id'];
        }
        else
        {
            $query = "INSERT INTO
            		 " . $this->db_table_prefix . "categories(id, name, status)
            		 VALUES(null, '" . $data['name'] . "'," . $data['status'] . ")";
        }
        $result = $this->commonDatabaseAction($query);

//        if (@mysql_affected_rows($result) > 0)
        if ($this->sqlAffected > 0)
        {
            return TRUE;
        }
        else
        {
            return FALSE;
        }
    }

    /**
     * Get category by id
     * @param int $id
     */
    public function category_by_id($id)
    {
        $query  = "SELECT *
                   FROM " . $this->db_table_prefix . "categories
        	   WHERE id = $id";
        $result = $this->commonDatabaseAction($query);
//        if (mysql_num_rows($result) > 0)
        if ($this->rowCount > 0)
        {
//            return mysql_fetch_assoc($result);
            return $this->sqlAssoc;
        }
        else
        {
            return array();
        }
    }

    /**
     * Delete a category
     * @param int $id
     */
    public function category_delete($id)
    {
        // update products table
        $update_query = "UPDATE
         " . $this->db_table_prefix . "products
         SET cat_id = '0', status = '0'
         WHERE cat_id = " . $id;
        
        $this->commonDatabaseAction($update_query);
        
        // delete from category table
        $query  = "DELETE
        	   FROM " . $this->db_table_prefix . "categories
        	   WHERE id = $id";
        
        $result = $this->commonDatabaseAction($query);
//        if (@mysql_affected_rows($result) > 0)
        if ($this->sqlAffected > 0)
        {
            return TRUE;
        }
        else
        {
            return FALSE;
        }
    }

    /**
     * Get all products
     * @return array
     */
    public function product_get_all($filters = array())
    {
        $query   = "DESCRIBE " . $this->db_table_prefix . "products";
        $result  = $this->commonDatabaseAction($query);
        $result  = $this->resultArray($result);
//        $columns = array_column($result, 'Field');
        $columns = php_array_column($result, 'Field');
        

        $query = "SELECT *
                   FROM " . $this->db_table_prefix . "products ";
        
        if (!empty($filters))
        {
            $query .= 'WHERE 1';
            foreach ($filters as $key => $value)
            {
                if (in_array($key, $columns))
                {
                    $query .= ' AND ' . $key . ' = ' . $value;
                }
            }
        }
        $query .= " ORDER BY id DESC";
        
        $result = $this->commonDatabaseAction($query);
//        if (mysql_num_rows($result) > 0)
        if ($this->rowCount > 0)
        {
            return $this->resultArray($result);
        }
        else
        {
            return null;
        }
    }

    /**
     * Get the product by Id
     * @param int $id
     */
    public function product_get_by_id($id)
    {
        $query  = "SELECT p.*, c.status as categoryStatus
        	       FROM " . $this->db_table_prefix . "products p
                   LEFT JOIN " . $this->db_table_prefix . "categories c ON p.cat_id = c.id 
        	       WHERE p.id = $id";
        $result = $this->commonDatabaseAction($query);
//        if (mysql_num_rows($result) > 0)
        if ($this->rowCount > 0)
        {
//            return mysql_fetch_assoc($result);
            return $this->sqlAssoc;
        }
        else
        {
            return null;
        }
    }

    /**
     * Get the product by category Id
     * @param int $cat_id
     */
    public function product_get_by_category($data)
    {
        $queryColumn   = "DESCRIBE " . $this->db_table_prefix . "products";
        $resultColumn  = $this->commonDatabaseAction($queryColumn);
        $resultColumn  = $this->resultArray($resultColumn);
        $columns = php_array_column($resultColumn, 'Field');
        
        $query = "SELECT p.*, c.name as catName
        	   FROM " . $this->db_table_prefix . "products p
               JOIN " . $this->db_table_prefix . "categories c
               ON p.cat_id = c.id ";
        
        if (!empty($data))
        {
            $query .= 'WHERE 1';
            foreach ($data as $key => $value)
            {
                $pos            = strpos($key, '.');
                $columnArray    = ($pos) ? explode('.', $key) : '';                
                $columnName     = (!empty($columnArray)) ? $columnArray[1] : $key ;
                
                if (in_array($columnName, $columns))
                {
                    $query .= ' AND ' . $key . ' = ' . $value;
                }
            }
        }
        $query .= " ORDER BY p.id DESC";

        $result = $this->commonDatabaseAction($query);
//        if (mysql_num_rows($result) > 0)
        if ($this->rowCount > 0)
        {
            return $this->resultArray($result);
        }
        else
        {
            return null;
        }
    }

    /**
     * Insert product data
     * @param array $data
     */
    public function product_insert($data)
    {
        if (isset($data['id']))
        {
            $setValues = '';

            foreach ($data as $key => $val)
            {
                if($key == 'name' || $key == 'description')
                    $setValues .= $key . " = '" . parent::sqlString( $val ) . "',";
                else if($key != 'id')
                    $setValues .= $key . " = '" . $val . "',";
            }
            $setValues = rtrim($setValues, ',') . ' ';
            $query     = "UPDATE
                     " . $this->db_table_prefix . "products
            	     SET " . $setValues . "
            	     WHERE id = " . $data['id'];
           
        }
        else
        {
          
            $insertValues = 'null,';
            $insertKeys = 'id,';
            foreach ($data as $key => $val)
            {
                $insertKeys   .= $key .",";
                $insertValues .= "'". $val . "',";
            }
            $insertValues = rtrim($insertValues, ',');
            $insertKeys   = rtrim($insertKeys, ',');
            
            $query        = "INSERT INTO
            		 " . $this->db_table_prefix . "products (". $insertKeys .")
            		 VALUES(" . $insertValues . ")";
        }

        $result = $this->commonDatabaseAction($query);
//        if (@@mysql_affected_rows($result) > 0)
        if ($this->sqlAffected > 0)
        {
            return $this->sqlInsertId();
        }
        else
        {
            return FALSE;
        }
    }

    /**
     * Delete a product
     * @param int $id
     * @return bool
     */
    public function product_delete($id)
    {
        $query  = "DELETE
        		  FROM " . $this->db_table_prefix . "products
        		  WHERE id = $id";
      //  echo $query; die();
        $result = $this->commonDatabaseAction($query);
//        if (@mysql_affected_rows($result) > 0)
        if ($this->sqlAffected > 0)
        {
            return TRUE;
        }
        else
        {
            return FALSE;
        }
    }
    
     /**
     * Delete a user except user with admin previlage
     * @param int $id
     * @return bool
     */
    public function user_delete($id)
    {
        $query  = "DELETE
        		  FROM " . $this->db_table_prefix . "users
        		  WHERE id = $id";
      //  echo $query; die();
        $result = $this->commonDatabaseAction($query);
//        if (@mysql_affected_rows($result) > 0)
        if ($this->sqlAffected > 0)
        {
            return TRUE;
        }
        else
        {
            return FALSE;
        }
    }
    
    
    
    
    /**
     * Empty user cart
     * 
     * @param int $user_id
     * @param int $product_id
     * @return bool
     * 
     * @author Jeny Devassy <jeny.devassy@bridge-india.in>
     * @date 12 Sep 2014
     */
    public function empty_cart($user_id, $product_id = null)
    {
        $condition = (!empty($product_id)) ? "user_id = $user_id AND product_id = $product_id" : "user_id = $user_id";
        
        $query  = "DELETE
        		  FROM " . $this->db_table_prefix . "cart
        		  WHERE ". $condition;
        
        $result = $this->commonDatabaseAction($query);
//        if (@mysql_affected_rows($result) > 0)
        if ($this->sqlAffected > 0)
        {
            return TRUE;
        }
        else
        {
            return FALSE;
        }
    }
    
    /**
     * Insert product to cart
     * @param array $data
     * 
     * @author Jeny Devassy <jeny.devassy@bridge-india.in>
     * @date 11 Sep 2014
     */
    public function add_to_cart($data)
    {
        if(empty($data['product_id']))
            return FALSE;
        
        
        $cartQuery = "SELECT * FROM " . $this->db_table_prefix . "cart WHERE 
            product_id = '".$data['product_id']."' AND user_id = '".$data['user_id']."' ";
        
        $cartResult = $this->commonDatabaseAction($cartQuery);
        
              
//        if( mysql_num_rows($cartResult) > 0){
        if( $this->rowCount > 0){
            
            $query = "UPDATE " . $this->db_table_prefix . "cart 
                SET date_time = '" . date("Y-m-d h:i:s") . "' WHERE 
                product_id = '".$data['product_id']."' AND user_id = '".$data['user_id']."' ";
            
        }
        else{

            $insertValues = 'NULL,';
            foreach ($data as $key => $val)
            {
                $insertValues .= "'". $val ."',";
            }
            $insertValues = rtrim($insertValues, ',');
            
            
            $query  = "INSERT INTO " . $this->db_table_prefix . "cart
                     VALUES (".$insertValues.")";

        }
        
        //echo $query; exit;
        $result = $this->commonDatabaseAction($query);
        
//        if (@mysql_affected_rows($result) > 0)
        if ($this->sqlAffected > 0)
        {
            return TRUE;
        }
        else
        {
            return FALSE;
        }
    }
    
    
    /**
     * Get the user cart products
     * @param int $user_id
     * 
     * @author Jeny Devassy <jeny.devassy@bridge-india.in>
     * @date 11 Sep 2014
     */
    public function get_cart_products($user_id)
    {
       
        $query = "SELECT p.*
        	   FROM " . $this->db_table_prefix . "cart c
               JOIN " . $this->db_table_prefix . "products p
               ON p.id = c.product_id
        	   WHERE c.user_id = $user_id";

        $result = $this->commonDatabaseAction($query);
        
//        if (mysql_num_rows($result) > 0)
        if ($this->rowCount > 0)
        {
            return $this->resultArray($result);
        }
        else
        {
            return null;
        }
    }

    /**
     * Creates an array result for a DB query
     * @param object $result mysql result object
     * @return array
     */
    public function resultArray($recordset)
    {
                
//        $result = array();
//        while ($row    = mysql_fetch_assoc($recordset))
//        {
//            $result[] = $row; // Inside while loop
//        }
//        return $result;
        $result = $this->sqlAssoc;
        return $result;
    }

    /**
     * Common method for Db query execution
     * @param string $query
     * @return array
     */
    function commonDatabaseAction($query, $params = null)
    {
      
        parent::queryText($query, $params);
       
        return $this->sqlResult;
//        
//        $result = mysql_query($query);
//
//        if (!mysql_error())
//        {
//            return $result;
//        }
//        else
//        {
//            //echo mysql_error();
//            return false;
//        }
    }

    /**
     * This function used to return a field value FROM database.
     * @parameter 1.query, 2.status
     *
     */
    function singlevalue($sql, $stat = FALSE)
    {

        $result = $this->commonDatabaseAction($sql, 0);

//        if (mysql_num_rows($result))
        if ($this->rowCount)
        {
//            $returnResult = mysql_result($result, 0);
            $returnResult = $this->sqlResult;
        }
        else
        {
            if ($stat == true)
            {
                $returnResult = "";
            }
            else
            {
                $returnResult = 0;
            }
        }
        return $returnResult;
    }

    /**
     * user register process
     * @param type $first_name
     * @param type $last_name
     * @param type $email
     * @param type $username
     * @param type $password
     * @return boolean
     */
    public function user_registration($first_name, $last_name, $email, $username, $password)
    {

        $checking_query = "SELECT *
						   FROM " . $this->db_table_prefix . "users
					       WHERE username='" . $username . "' AND role_id='2'
  		     			   LIMIT 0,1";
        
        if ($this->singlevalue($checking_query) == 0)
        {            
            $query = "INSERT INTO
	            	  " . $this->db_table_prefix . "users(username, password, firstname, lastname, email, role_id)
	            	  VALUES('" . $username . "','" . md5($password) . "','" . $first_name . "','" . $last_name . "','" . $email . "', 2 )";

            $this->commonDatabaseAction($query);
            
            $this->user_login($username, $password);
            
            return true;
        }
        else
        {
            return false;
        }
    }

    /**
     * Save download info with token and purchase id
     * @param type $purchase_id
     * @param type $download_token
     * @param type $expires_on
     * @return boolean
     */
    public function save_downlad_token($purchase_id, $download_token)
    {
        $query  = "INSERT INTO " . $this->db_table_prefix . "downloads(token,purchase_id) VALUES('$download_token', $purchase_id)";
        $result = $this->commonDatabaseAction($query);
        if ($result)
        {
            return true;
        }
        else
        {
            return false;
        }
    }

    /**
     * Get the user by Id
     * @param int $id
     */
    public function user_get_by_id($id)
    {
        $query  = "SELECT *
        	       FROM " . $this->db_table_prefix . "users
        	       WHERE id = $id";
        $result = $this->commonDatabaseAction($query);
//        if (mysql_num_rows($result) > 0)
        if ($this->rowCount > 0)
        {
//            return mysql_fetch_assoc($result);
            return $this->sqlAssoc;
        }
        else
        {
            return null;
        }
    }

    /**
     * Get all users
     * @return array
     */
    public function user_get_all()
    {
        $query  = "SELECT *
                   FROM " . $this->db_table_prefix . "users";
        $result = $this->commonDatabaseAction($query);
//        if (mysql_num_rows($result) > 0)
        if ($this->rowCount > 0)
        {
            return $this->resultArray($result);
        }
        else
        {
            return null;
        }
    }
    
    
    /**
     * update user data
     * @param array $data
     */
    public function update_user($data)
    {
        if (empty($data['id']))
            return FALSE;
        
        $setValues = '';

        foreach ($data as $key => $val)
        {
            if($key != 'id' && $key != 'submit')
//                $setValues .= $key . ' = ' . mysql_real_escape_string ( $val ) . ',';
                $setValues .= $key . ' = ' . parent::sqlString( $val ) . ',';
        }
        $setValues = rtrim($setValues, ',') . ' ';
        $query     = "UPDATE
                 " . $this->db_table_prefix . "users
                 SET " . $setValues . "
                 WHERE id = " . $data['id'];

        $result = $this->commonDatabaseAction($query);
//        if (@@mysql_affected_rows($result) > 0)
        if ($this->sqlAffected > 0)
        {
            return TRUE;
        }
        else
        {
            return FALSE;
        }
        
    }

    /**
     * Validate download token
     * @param int $token
     */
    public function validate_download_token($token)
    {
        $conditionalArray = explode('||', $token);
        
        $condition = (!empty($conditionalArray[1])) ? ' AND pr.product_id = '. $conditionalArray[1] : '';
        
        $query  = "SELECT d.*
                   FROM " . $this->db_table_prefix . "downloads d
                   LEFT JOIN " . $this->db_table_prefix . "purchase_products pr ON pr.purchase_id = d.purchase_id  
                   WHERE d.token = '$conditionalArray[0]' and pr.expires_on > '".date('Y-m-d h:i:s')."' ". $condition;
        
        //echo $query; exit;
        $result = $this->commonDatabaseAction($query);
//        if (mysql_num_rows($result) > 0)
        if ($this->rowCount > 0)
        {
            return true;
        }
        else
        {
            return false;
        }
    }

    /**
     * Get the product info from the download token
     * @param string $token
     * @return array
     */
    public function get_product_by_download_token($token)
    {
        $conditionalArray = explode('||', $token);
        
        $condition = (!empty($conditionalArray[1])) ? ' AND pr.product_id = '. $conditionalArray[1] : '';
        
        $query  = "SELECT d.purchase_id,p.* FROM " . $this->db_table_prefix . "downloads d JOIN bs_purchase_products pr JOIN bs_products p
                    ON d.purchase_id = pr.purchase_id AND pr.product_id = p.id
                    WHERE d.token = '$conditionalArray[0]' ". $condition;
        $result = $this->commonDatabaseAction($query);
//        if (mysql_num_rows($result) > 0)
        if ($this->rowCount > 0)
        {
            return $this->resultArray($result);
        }
        else
        {
            return null;
        }
    }

    /**
     * Get the purchased products of user
     * @param type $user_id
     * @return array
     */
    public function get_user_purchased_products($user_id, $section = null)
    {
        if ($user_id)
        {
            $condition = ($user_id == 'all') ? '1' : "pr.user_id = $user_id ";
            
            $query  = "SELECT p . * , pr.id as purchase_id, pr.transaction_id, pr.date_time, pr.total_price, pr.payment_status, 
                    d.token, pp.expires_on, pp.download_count, CONCAT(u.firstname, ' ', u.lastname) as user, u.email FROM " 
                    . $this->db_table_prefix . "purchase_products pp LEFT JOIN " 
                    . $this->db_table_prefix . "purchases pr ON pp.purchase_id = pr.id LEFT JOIN "
                    . $this->db_table_prefix . "products p ON pp.product_id = p.id LEFT JOIN " 
                    . $this->db_table_prefix . "downloads d ON pr.id = d.purchase_id LEFT JOIN "
                    . $this->db_table_prefix . "users u ON pr.user_id = u.id "
                    . "WHERE ". $condition . " group BY pr.date_time DESC";
          //  echo $query; exit;
            
//            if(!empty($section) && $section == 'history')
//                $query .= " AND pr.payment_status = 'Completed'";
            
            $result = $this->commonDatabaseAction($query);            
//            if (mysql_num_rows($result) > 0)
            if ($this->rowCount > 0)
            {
                return $this->resultArray($result);
            }
            else
            {
               
                return null;
            }
        }
        else
            return null;
    }
    
    
    /**
     * Insert category
     * @param array $data
     * @return boolean
     */
    public function update_downlaod_count($data)
    {
        if (empty($data['purchase_id']) || empty($data['product_id']))
            return FALSE;
       
        $query = "UPDATE
                 " . $this->db_table_prefix . "purchase_products
                 SET download_count = download_count+1 
                 WHERE purchase_id = '" . $data['purchase_id'] . "' AND product_id = '" . $data['product_id'] . "'";
       
        $result = $this->commonDatabaseAction($query);
        
//        if (@mysql_affected_rows($result) > 0)
        if ($this->sqlAffected > 0)
        {
            return TRUE;
        }
        else
        {
            return FALSE;
        }
    }
        
    /**
     * Empty user cart
     * 
     * @param int $user_id
     * @param int $product_id
     * @return bool
     * 
     * @author Jeny Devassy <jeny.devassy@bridge-india.in>
     * @date 12 Sep 2014
     */
    public function delete_customer_purchases($user_id)
    {
        $query  = "SELECT * 
        		  FROM " . $this->db_table_prefix . "purchases
        		  WHERE user_id = $user_id AND payment_status = 'Initiated' ";
        
        $result = $this->commonDatabaseAction($query);
        
        if ($this->rowCount > 0)
        {
            foreach ($this->resultArray($result) as $pendingPurchases) 
            {
                echo $pendingPurchases['id'];
            }
            return ;
        }
        else
        {
            return null;
        }
    }
    
    /** Update product purchase status
     * 
     * @param array $data 
     * 
     * @author Jeny Devassy <jeny.devassy@bridge-india.in>
     * @date 01 Oct 2014
     * 
     */
    public function update_purchase($data){
        
        $query  = "SELECT * 
          FROM " . $this->db_table_prefix . "purchases
          WHERE user_id = '" . $data['user_id'] . "' AND  payment_status = 'Initiated'
          ORDER BY `id` DESC 
          LIMIT 0 , 1 ";
        
        $result = $this->commonDatabaseAction($query);
        $Purchase = $this->resultArray($result);
        
        if ($this->rowCount > 0)
        {
            $query = "UPDATE
                " . $this->db_table_prefix . "purchases
                SET payment_status = '" . $data['status'] . "'
                WHERE id = " . $Purchase[0]['id'];
            
            $result = $this->commonDatabaseAction($query);
            
            if ($this->sqlAffected > 0)
            {
                return TRUE;
            }
            else
            {
                return FALSE;
            }
        }
        else
            return FALSE;
        
    }
    
    /**
     * Update expiry date
     * @param int $purchase_id, $purchase_id
     * @param timestamp $expires_on
     * @return boolean
     * 
     * @author Jeny Devassy <jeny.devassy@bridge-india.in>
     * @date 06 Oct 2014
     */
    public function save_product_expiry($purchase_id, $product_id, $expires_on)
    {
        
        if (empty($purchase_id))
            return FALSE;
        
        $query = "UPDATE
                 " . $this->db_table_prefix . "purchase_products
                 SET expires_on = ". $expires_on ."  
                 WHERE purchase_id = '" . $purchase_id . "' AND 
                       product_id = '" . $product_id . "' ";
        
        $result = $this->commonDatabaseAction($query);

        if ($this->sqlAffected > 0)
        {
            return TRUE;
        }
        else
        {
            return FALSE;
        }
    }

}
