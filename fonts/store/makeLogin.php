<?php 
include_once('controller/user-controller.php');
include_once('controller/application-controller.php');

$user = new UserController ();

$app = new AppController();

$_POST['firstname']=$_POST['username'];
$_POST['lastname']='';
$user_details = $user->user_existence_check($_POST['email']);
session_start(); 
if(!empty($user_details))
{
            $_SESSION ['user_id']= $user_details[0]['id'];
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
                
               $respose_json = json_encode($_SESSION);    
                echo $respose_json; exit;
           // echo "1";
      
}
else
{
echo "0";
}




?>
