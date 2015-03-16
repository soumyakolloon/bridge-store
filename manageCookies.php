<?php 

/**
 * @project Bridge shoppingcart
 * author: Soumya 
 * Date:04/02/2015
 * Ajax request to save the New product details
 */
session_start();
if(isset($_POST))
{
	$data['product_id'] = $_POST['prod_id'];
	
	if(empty($_SESSION['user_id']))
	{
		$data_cookieArray = unserialize($_COOKIE['id']);
		// print_r($data_cookieArray);
		$cookieArray;  
		for($c=0; $c<count($data_cookieArray); $c++)
		{ 
			if($data_cookieArray[$c]!=$data['product_id'])
				{
				
				
				$cookieArray[$c] = $data_cookieArray[$c];
			}

		}

		unset($_COOKIE['id']);
		
		$cookieArray = array_values($cookieArray);
		// print_r($data_cookieArray);
		
		// echo 'dd';
		if(empty($cookieArray))
			$cookieArray = array();

		setcookie('id',serialize($cookieArray),  time() + 60 * 60, 'index.php?page=addtocart');


		print_r($_COOKIE['id']); exit;

		
	}
	else if(isset($_SESSION['user_id']))
	{
	
		/**Logout mode*/

	include_once 'controller/product-controller.php';
    
    $product = new ProductController ();
    
    $id = $data['product_id'];

	$response =  $product->empty_cart($_SESSION ['user_id'], $id);

	if(empty($response))
	{
		$data_cookieArray = unserialize($_COOKIE['id']);
		// print_r($data_cookieArray);
		$cookieArray;  
		//$index = count($data_cookieArray);
		//$index =0; 
		for($c=0; $c<count($data_cookieArray); $c++)
		{ 
			if($data_cookieArray[$c]!=$data['product_id'])
				{
				
				
				$cookieArray[$c] = $data_cookieArray[$c];
			}

		}
	/*if(!in_array($data['product_id'], $data_cookieArray))
	{
		$cookieArray[$index] = $data['product_id'];
		$index++;
	}*/
		
		unset($_COOKIE['id']);
		$cookieArray = array_values($cookieArray);
		// print_r($data_cookieArray);
		
		// echo 'dd';
		if(empty($cookieArray))
			$cookieArray = array();

		setcookie('id',serialize($cookieArray),  time() + 60 * 60, 'index.php?page=addtocart');


		print_r($_COOKIE['id']); exit;
	}


	}

	

}

?>
