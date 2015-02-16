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

 	$data['imagekey']=$_POST['imagekey'];
    $data['image_path'] = $_POST['image_path'];
    $data['product_id']= $_POST['product_id'];

    $delete_status = $product->prod_image_remove($data);

    echo $delete_status;

	// prod_image_remove
}

?>