<?php 
include_once('controller/user-controller.php');
$user = new UserController ();
$userDetails = $user->user_existence_check($_POST['email']);
if($userDetails!='')
	echo $userDetails[0]['id'];
else
	echo "0";

?>
