<?php

/**
 * @project Bridge shoppingcart
 * Manage database queries and methods
 */
include_once 'config.php';

class DataBaseController
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
        $config                = get_db_config();
        $this->db_host         = $config['host'];
        $this->db_user         = $config['user'];
        $this->db_pass         = $config['password'];
        $this->db_name         = $config['name'];
        $this->db_table_prefix = $config['table_prefix'];

        $this->db_connect();
    }

    /**
     * Create the DB conenction
     */
    function db_connect($mode = 'mysql')
    {
        if ($mode == 'mysql')
        {
            $link = mysql_connect($this->db_host, $this->db_user, $this->db_pass);

            if (!$link)
            {
                echo 'DataBase Connection Error!!!';
            }
            else
            {
                mysql_select_db($this->db_name);
            }
        }
        else
        {
            $mysqli = new mysqli($this->db_host, $this->db_user, $this->db_pass, $this->db_name);

            //Output any connection error
            if ($mysqli->connect_error)
            {
                echo 'DataBase Connection Error!!!';
            }
            else
            {
                return $mysqli;
            }
        }
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
        		  LIMIT 0,1";
        
        $result = $this->commonDatabaseAction($query); 
        $user_details = array();        
        while($r = mysql_fetch_assoc($result))
        {
            $user_details = $r;
        }        
        if (mysql_num_rows($result) > 0 && !empty($user_details))
        {   
            $_SESSION ['user_id']         = $user_details['id'];
            $_SESSION ['user_first_name'] = $user_details['firstname'];

            if ($result['firstname'] !== '')
            {
                $_SESSION ['user_last_name'] = $user_details['firstname'];
            }
            else
            {
                $_SESSION ['user_last_name'] = '';
            }

            $_SESSION ['user_role'] = $user_details['role_id'];
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
    public function category_get_all()
    {
        $query = "SELECT c.*, count(p.id) as no_of_products
                   FROM " . $this->db_table_prefix . "categories c "
                . "LEFT JOIN " . $this->db_table_prefix . "products p "
                . "ON c.id = p.cat_id group by c.id";

        $result = $this->commonDatabaseAction($query);
        if (mysql_num_rows($result) > 0)
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

        if (@mysql_affected_rows($result) > 0)
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
        if (mysql_num_rows($result) > 0)
        {
            return mysql_fetch_assoc($result);
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
        $query  = "DELETE
        	   FROM " . $this->db_table_prefix . "categories
        	   WHERE id = $id";
        $result = $this->commonDatabaseAction($query);
        if (@mysql_affected_rows($result) > 0)
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
        $query   = "DESCRIBE bs_products";
        $result  = $this->commonDatabaseAction($query);
        $result  = $this->resultArray($result);
        $columns = array_column($result, 'Field');

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

        $result = $this->commonDatabaseAction($query);
        if (mysql_num_rows($result) > 0)
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
        $query  = "SELECT *
        	       FROM " . $this->db_table_prefix . "products
        	       WHERE id = $id";
        $result = $this->commonDatabaseAction($query);
        if (mysql_num_rows($result) > 0)
        {
            return mysql_fetch_assoc($result);
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
    public function product_get_by_category($cat_id)
    {
        $query = "SELECT p.*, c.name as catName
        	   FROM " . $this->db_table_prefix . "products p
               JOIN " . $this->db_table_prefix . "categories c
               ON p.cat_id = c.id
        	   WHERE p.cat_id = $cat_id";

        $result = $this->commonDatabaseAction($query);
        if (mysql_num_rows($result) > 0)
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
                if($key != 'id')
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
            foreach ($data as $key => $val)
            {
                $insertValues .= $val . ',';
            }
            $insertValues = rtrim($insertValues, ',');
            $query        = "INSERT INTO
            		 " . $this->db_table_prefix . "products
            		 VALUES(" . $insertValues . ")";
        }

        $result = $this->commonDatabaseAction($query);
        if (@@mysql_affected_rows($result) > 0)
        {
            return TRUE;
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
        $result = $this->commonDatabaseAction($query);
        if (@mysql_affected_rows($result) > 0)
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
        if (@mysql_affected_rows($result) > 0)
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
        
        if( mysql_num_rows($cartResult) > 0){
            
            $query     = "UPDATE " . $this->db_table_prefix . "cart 
                SET date_time = " . date("Y-m-d h:i:s") . " WHERE 
                product_id = '".$data['product_id']."' AND user_id = '".$data['user_id']."' ";
        }
        else{

            $insertValues = 'null,';
            foreach ($data as $key => $val)
            {
                $insertValues .= "'". $val ."',";
            }
            $insertValues = rtrim($insertValues, ',');
            $query        = "INSERT INTO
                     " . $this->db_table_prefix . "cart
                     VALUES(" . $insertValues . ")";

        }
        $result = $this->commonDatabaseAction($query);
        if (@mysql_affected_rows($result) > 0)
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
        if (mysql_num_rows($result) > 0)
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
        $result = array();
        while ($row    = mysql_fetch_assoc($recordset))
        {
            $result[] = $row; // Inside while loop
        }
        return $result;
    }

    /**
     * Common method for Db query execution
     * @param string $query
     * @return array
     */
    function commonDatabaseAction($query)
    {
        $result = mysql_query($query);

        if (!mysql_error())
        {
            return $result;
        }
        else
        {
            //echo mysql_error();
            return false;
        }
    }

    /**
     * This function used to return a field value FROM database.
     * @parameter 1.query, 2.status
     *
     */
    function singlevalue($sql, $stat = FALSE)
    {

        $result = $this->commonDatabaseAction($sql);

        if (mysql_num_rows($result))
        {
            $returnResult = mysql_result($result, 0);
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
    public function save_downlad_token($purchase_id, $download_token, $expires_on)
    {
        $query  = "INSERT INTO " . $this->db_table_prefix . "downloads(token,purchase_id,expires_on) VALUES('$download_token', $purchase_id, $expires_on)";
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
        if (mysql_num_rows($result) > 0)
        {
            return mysql_fetch_assoc($result);
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
        if (mysql_num_rows($result) > 0)
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
                $setValues .= $key . ' = ' . mysql_real_escape_string ( $val ) . ',';
        }
        $setValues = rtrim($setValues, ',') . ' ';
        $query     = "UPDATE
                 " . $this->db_table_prefix . "users
                 SET " . $setValues . "
                 WHERE id = " . $data['id'];

        $result = $this->commonDatabaseAction($query);
        if (@@mysql_affected_rows($result) > 0)
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
        $query  = "SELECT *
                   FROM " . $this->db_table_prefix . "downloads WHERE token = '$token' and expires_on > NOW()";
        $result = $this->commonDatabaseAction($query);
        if (mysql_num_rows($result) > 0)
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
        $query  = "SELECT d.purchase_id,p.* FROM " . $this->db_table_prefix . "downloads d JOIN bs_purchase_products pr JOIN bs_products p
                    ON d.purchase_id = pr.purchase_id AND pr.product_id = p.id
                    WHERE d.token = '$token'";
        $result = $this->commonDatabaseAction($query);
        if (mysql_num_rows($result) > 0)
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
            
            $query  = "SELECT p . * , pr.transaction_id, pr.date_time, pr.total_price, pr.payment_status, 
                    d.token, d.expires_on, CONCAT(u.firstname, ' ', u.lastname) as user, u.email FROM " 
                    . $this->db_table_prefix . "purchase_products pp LEFT JOIN " 
                    . $this->db_table_prefix . "purchases pr ON pp.purchase_id = pr.id LEFT JOIN "
                    . $this->db_table_prefix . "products p ON pp.product_id = p.id LEFT JOIN " 
                    . $this->db_table_prefix . "downloads d ON pr.id = d.purchase_id LEFT JOIN "
                    . $this->db_table_prefix . "users u ON pr.user_id = u.id "
                    . "WHERE ". $condition ;
            
            if(!empty($section) && $section == 'history')
                $query .= " AND pr.payment_status = 'Completed'";
            
            $result = $this->commonDatabaseAction($query);            
            if (mysql_num_rows($result) > 0)
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
        if (empty($data['token']))
            return FALSE;
        
        $query = "UPDATE
                 " . $this->db_table_prefix . "downloads
                 SET download_count = download_count+1 
                 WHERE token = '" . mysql_real_escape_string($data['token']) . "'";
        
        $result = $this->commonDatabaseAction($query);

        if (@mysql_affected_rows($result) > 0)
        {
            return TRUE;
        }
        else
        {
            return FALSE;
        }
    }

}
