<?php

/**
 * @project Bridge shoppingcart
 * Manage database mode
 */
include_once 'config.php';

class BaseController
{
    
    public $con;
    public $db_type;
    public $query;
    public $sqlResult;
    public $sqlRow;
    public $sqlAssoc;
    public $rowCount;
    public $sqlAffected;
    public $param;
    
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
        $this->db_type         = ''; 
        $this->con             =   $this->db_connect($this->db_type);
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
                return mysql_select_db($this->db_name);
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
    
    public function queryText($query, $param = null)
    {
        $this->query = $query;
        $this->param = $param;
        
        if($this->db_type == 'mysql'){
            
            return $this->mysqlfunctions();
        }
        else{
            
            return $this->defaultfunctions();
        }
        return $this->query;
    }


    /**
     * Define mysql method
     */
    function mysqlfunctions(){
        
        $result = mysql_query($this->query);
        if (!mysql_error())
        {
            $this->sqlResult = $result;
        }
        else
        {
//            echo mysql_error();
            $this->sqlResult =  'false';
        }
        
        $this->rowCount     = mysql_num_rows($this->sqlResult);
        $this->sqlAssoc     = $this->fecthResultArray();
//        $this->sqlRow       = mysql_fetch_row($this->sqlResult);
        $this->sqlAffected  = mysql_affected_rows($this->sqlResult);
        $this->sqlGetField  = mysql_result($this->sqlResult, $this->param);
    
    }
    
    /**
     * Define mysql method
     */
    function defaultfunctions(){
        
        $result =  $this->con->query($this->query);
        if (!mysql_error())
        {
            $this->sqlResult = $result;
        }
        else
        {
//            echo mysql_error();
            $this->sqlResult =  'false';
        }
        
        $this->rowCount     = $this->sqlResult->num_rows;
        $this->sqlAssoc     = $this->fecthDefaultResultArray();
//        $this->sqlRow       = mysql_fetch_row($this->sqlResult);
        $this->sqlAffected  = $this->con->affected_rows;
        $this->sqlGetField  = mysqli_data_seek($this->sqlResult, $this->param);

    }
    
    /*
     * Sql format string
     */
    public function sqlString($string){
        
        if($this->db_type == 'mysql'){
            return mysql_real_escape_string($string);
        }
        else{
            return $this->con->real_escape_string($string);
        }
    }

    /*
     * Sql last insert id int
     */
    public function sqlInsertId(){
        
        if($this->db_type == 'mysql'){
            return mysql_insert_id();
        }
        else{
            return mysqli_insert_id($this->con);
        }
    }
    
    function fecthResultArray(){
        $result = array();
        while ($row    = mysql_fetch_assoc($this->sqlResult))
        {
            $result[] = $row; // Inside while loop
        }
        return $result;
    }

    function fecthDefaultResultArray(){
        $result = array();
        while ($row    = mysqli_fetch_object($this->sqlResult))
        {
            $result[] = $row; // Inside while loop
        }
        return json_decode(json_encode($result), true);
    }
}
?>
