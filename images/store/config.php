<?php

/*
 * Bridge Shopping Cart
  Db and other config settings
 */

class Configuration
{
    public  $config = array();
    
    /**
     * Constructor
     */
    function __construct()
    {
        $this->config = array(
                            'base_path'       => 'bridge-india.in/store/',
                            'uploads_folder'  => 'uploads',
                            'allowed_types'   => 'jpg|gif|png|zip|gz|txt|pdf|docx',
                            'zip_types'       => 'jpg|gif|png',
                        );
    }
            
    function get_db_config()
    {

        return array(
            'host'         => 'localhost',
            'user'         => 'bridgeoutnl_pstr',
            'password'     => 'Z56codex',
            'name'         => 'bridgeoutnl_productstore',
            'table_prefix' => 'bs_'
        );
    }

    function get_base_url(){
        
        $protocol_array    = explode('/', $_SERVER['SERVER_PROTOCOL']);
        $protocol          = strtolower($protocol_array[0]) . '://';
        return $protocol .  $this->config['base_path'];
    }
}

$obj = new Configuration();  
$config = $obj->config;

function get_db_config(){
    global $obj;
    return $get_db_config = $obj->get_db_config();
}
function get_base_url(){
    global $obj;
    return $get_db_config = $obj->get_base_url();
}

?>

