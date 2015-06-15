<?php 

/**
 * @project Bridge shoppingcart
 * author: Soumya 
 * Date:04/02/2015
 * Ajax request to save the New product details
 */

if(isset($_POST))
{
   
$data['name']         = $_POST['product-name'];
$data['description']  = $_POST['neweditor'];
$data['cat_id']       = $_POST['product-cat'];
$data['price']        = $_POST['product-price'];
$data['validity']     = $_POST['product-validity'];
$data['status']       = $_POST['product-status'];

}

include_once 'controller/product-controller.php';
$product = new ProductController();

if(isset($_POST))
{

/**Save uploaded image path */

if(isset($_FILES['product-image'])){
        
        
foreach($_FILES['product-image']['tmp_name'] as $key => $tmp_name ){
if(!empty($_FILES['product-image']['tmp_name'][$key]))
{
$unique_key=rand();
$file_name = $unique_key.'_'.$_FILES['product-image']['name'][$key];
$file_size =$_FILES['product-image']['size'][$key];
$file_tmp =$_FILES['product-image']['tmp_name'][$key];
$file_type=$_FILES['product-image']['type'][$key];  

$desired_dir="uploads";

if(is_dir($desired_dir)==false){
    mkdir("$desired_dir", 0700);        // Create directory if it does not exist
}
if(is_dir("$desired_dir/".$file_name)==false){
    
   if(move_uploaded_file($file_tmp,"$desired_dir/".$file_name))
    	$image_path_array[] = $file_name;

}

}
}
    
}


/**End of Save uploaded image path */

/***Save uploaded product file**/

if(isset($_FILES['product-upload'])){

$unique_key=rand();
if(!empty($_FILES['product-upload']['name']))
{
$file_name = $unique_key.'_'.$_FILES['product-upload']['name'];
$file_size =$_FILES['product-upload']['size'];
$file_tmp =$_FILES['product-upload']['tmp_name'];
$file_type=$_FILES['product-upload']['type'];  

$desired_dir="uploads";

if(is_dir($desired_dir)==false){
mkdir("$desired_dir", 0700);        // Create directory if it does not exist
}
if(is_dir("$desired_dir/".$file_name)==false){

if(move_uploaded_file($file_tmp,"$desired_dir/".$file_name))
$download_link = $file_name;

}
}
else
{
$download_link = $_POST['hid-product-upload']; 
}

}

$data['download_link'] = $download_link; 



if(isset($image_path_array))
{

$default_image_index = count($_FILES['product-image']['tmp_name'])-1;
$data['image_path'] = $image_path_array[$default_image_index];
}
else
{
$data['image_path'] = '';
}

if(empty($data['image_path']))
{
$data_input['name']         = $_POST['product-name'];
$data_input['description']  = $_POST['product-desc'];
$data_input['cat_id']       = $_POST['product-cat'];
$data_input['price']        = $_POST['product-price'];
$data_input['validity']     = $_POST['product-validity'];
$data_input['status']       = $_POST['product-status'];
$data_input['download_link'] = $download_link;   
}
else
{
	$data_input = $data;
}

/**check if its an update**/

if(isset($_POST['product_id']))
$data_input['id'] = $_POST['product_id'];

/**End of check if its an update**/

$result = $product->insert($data_input);


if($result>0)
	$pr_id = $result;
else
	$pr_id = $data_input['id'];

if(isset($pr_id))
{
if(isset($image_path_array))
{
foreach($image_path_array as $img)
{
	$prod_image_array = array('product_id'=>$pr_id, 'image_path'=>$img);
	$product->image_insert($prod_image_array);
}
}

	
}

include_once 'controller/application-controller.php';
$application= new AppController();
$application->redirect("index.php?page=products&msg=1");
}
else
{
echo "ERROR"; exit;        
}

?>