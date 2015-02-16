<?php 

/**
 * @project Bridge shoppingcart
 * author: Soumya 
 * Date:04/02/2015
 * Ajax request to remove the selected image
 */

include_once 'controller/product-controller.php';
$product = new ProductController();

if(isset($_POST))
{
	// print_r($_POST);
	// exit;

 	$id=$_POST['id'];
    

    $status = $product->imageExitence($id);

    echo $status;

	// prod_image_remove
}

?>