<?php

/**
 * @project Bridge shoppingcart
 * Manage User actions
 */
include_once 'controller/application-controller.php';

class UserController extends AppController
{

    /**
     * Constructor
     */
    function __construct()
    {
        parent::__construct();
    }

    /**
     * Manage login submit
     * @param array $post
     */
    function user_login($post)
    {
        $username = addslashes($post['username']);
        $password = addslashes($post['password']);

        if ($username != '' && $password != '')
        {
            $this->database->user_login($username, $password);
        }
        else
        {
            $_SESSION ['user_login_error'] = 1;
        }
    }

    /**
     * Manage user registration submit
     * @param array $post
     */
    function user_registration($post)
    {

        $first_name = addslashes($post['firstname']);
        $last_name  = addslashes($post['lastname']);
        $email      = addslashes($post['email']);
        $username   = addslashes($post['username']);
        $password   = addslashes($post['password']);        
        $result     = $this->database->user_registration($first_name, $last_name, $email, $username, $password);        
        return $result;
    }


    function force__user_registration($data)
    {
        $username=$data['username'];
      //  print_r($_POST); exit
        $first_name = $data['username'];
        $last_name='';
        $email = $data['email'];
        $password=$data['password'];

         
          $result =  $this->database->force_user_registration($first_name, $last_name, $email, $username, $password); 
          return $result;

    }
    
    /**
     * Get the uset info based on the filters
     * $filters array conditional params
     * @return array
     */
    function get($filters = array())
    {
        if (isset($filters['id']))
        {
            $result = $this->database->user_get_by_id($filters['id']);
        }        
        else
        {
            $result = $this->database->user_get_all();
        }
        return $result;
    }
    
    /**
     * Insert / updaet the user data
     * $filters array conditional params
     * @return array
     */
    function user_update($data)
    {
        if (!empty($data))
        {
            $result = $this->database->update_user($data);
        }        
        else
        {
            $result = '';
        }
        return $result;
    }
    
    /**Remove a user except admin**/
       
    public function user_delete($id)
    {
     
       
        $result = $this->database->user_delete($id);
        return $result;
    }


    /**Get user existence**/

    public function user_existence_check($email)
    {
        $result = $this->database->user_get_by_email($email) ;
        return $result;
    }

	/**Generate a random string*/

function rand_string( $length ) {

$chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
return substr(str_shuffle($chars),0,$length);

}


/**
	 * Update password in database
	 * @author Soumya Kolloon
	 * * */
	 
	 public function updatePassword($filters)
	 {
		
		//print_r($filters); exit;
		if(isset($filters['username']) && $filters['username']!='')
        {

        $result = $this->database->password_update_by_name($filters['username'], $filters['rand_password']);
            
        }
        else if(isset($filters['email']) && $filters['email']!='')
        {
		 $result = $this->database->password_update_by_email($filters['email'], $filters['rand_password']);
		}
		
		return $result; 
		 
	 }

/**
	 * Update password in database
	 * @author Soumya Kolloon
	 * * */
	 
	 public function getUserDetailsByemail($email)
	 {
			

        $result = $this->database->user_get_by_useremail($email);
                  	
		return $result; 
		 
	 }


}
