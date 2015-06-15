<?php 
if(empty($_SESSION['user_id']))
{
     
if (isset($_GET['product_id']) && $_GET['page'] == 'addtocart') {
   $data = unserialize($_COOKIE['id']);
    
    //print_r($data);
    if(empty($data))
        $data=array($_GET['product_id']);

    $product_id=$data;

    if(!in_array($_GET['product_id'], $product_id)) {

  array_push($product_id, $_GET['product_id']);

}
    
 setcookie('id', serialize($product_id));
}
}

// if (isset($_GET['product_id']) && $_GET['page'] == 'delete-product') {
//    $data = unserialize($_COOKIE['id']);
    
//     //print_r($data);
//     if(empty($data))
//         $data=array($_GET['product_id']);

//     $product_id=$data;

//     if(!in_array($_GET['product_id'], $product_id)) {

//   unset($product_id[$_GET['product_id']]);

// }
    
//  setcookie('id', serialize($product_id));
// }


?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="description" content="">
<meta name="author" content="">

<title>Bridge-Store-Dashboard</title>

<!-- Bootstrap core CSS -->
<link href="css/bootstrap.css" rel="stylesheet">

<!-- Add custom CSS here -->
<link rel="stylesheet" href="css/sb-admin.css">
<link rel="stylesheet" href="font-awesome/css/font-awesome.min.css">
<link rel="stylesheet" href="css/normalize.css">
<link rel="stylesheet" href="css/style.css">
<link rel="stylesheet" href="css/custom.css">
<link rel="stylesheet" href="css/morris-0.4.3.min.css">
<link rel="stylesheet" href="css/jquery-ui.css">
<link href="css/modalPopLite.css" rel="stylesheet" type="text/css" /> 

<!--<script type="text/javascript" src="js/jquery-1.10.2.js"></script>-->
<script src="//ajax.googleapis.com/ajax/libs/jquery/1.8.2/jquery.min.js"></script>
<script type="text/javascript"  src="js/bootstrap.js"></script>
<script type="text/javascript" src="js/jquery-ui.js"></script>
<script type="text/javascript" src="js/admin.js"></script>

<script type="text/javascript" src="js/jquery-validate.js"></script>

<script type="text/javascript" src="js/modalPopLite.min.js"></script> 
<!-- bxSlider CSS file -->
<link href="css/jquery.bxslider.css" rel="stylesheet" />
<link rel="stylesheet" href="css/colorbox.css" />

<script src="js/jquery.colorbox.js"></script>
<!-- bxSlider Javascript file -->
<script src="js/jquery.bxslider.min.js"></script>



<!-- jquery image slider ends here -->
</head>
<body>
<script>
$(function() {

var pull = $('#pull');
menu = $('nav ul');
menuHeight = menu.height();

$('#pull').on('click', function(e) {
e.preventDefault();
menu.slideToggle();
});

$(window).resize(function() {
var w = $(window).width();
if (w > 320 && menu.is(':hidden')) {
menu.removeAttr('style');
}
});

});

</script>



<div id="page-wrapper">
<div class="row">
<div class="col-lg-12">
<div class="panel panel-info">
<div class="panel-heading">
<div class="row">
<div class="col-xs-10">
<i class="fa fa-3x">Bridge Store</i>
</div>

</div>
</div>
<div class="panel-footer announcement-bottom">
<div class="row ">
<nav class="clearfix">
<?php

if (isset($_SESSION ['user_id']) && ($_SESSION ['user_id'] != '' ))
{

?>
<ul class="clearfix col-lg-12">
<li><a href="./index.php">Home</a></li>
<?php
if (isset($_SESSION['user_role']) && ( $_SESSION['user_role'] == 1 ))
{
?>
<li><a href="./index.php?page=categories">Categories</a></li>
<li><a href="./index.php?page=products">Products</a></li>
<li><a href="./index.php?page=purchases">Purchases</a></li>
<li><a href="./index.php?page=customers">Customers</a></li>
<?php
}
else
{

?>
<li><a href="./index.php?page=payment_history">Payment History</a></li>
<li><a href="./index.php?page=addtocart">Cart</a></li>

<?php
}
?>
<li id="user-link" style="float:right;"><a href="./logout.php">Sign Out</a></li>
<li style="float:right;">
<?php
echo ucwords($_SESSION['user_first_name'] . ' ' . $_SESSION['user_last_name']);
?>
</li>
</ul>
<?php
}
else
{
?>
<ul class="clearfix col-lg-12">
<?php
if (isset($_GET['page']) && ( $_GET['page'] == 'login' || $_GET['page'] == 'registration' ))
{
$current_file_name = $_GET ['page'];
selectMenuItem($current_file_name);
?>
<!-- <li><a href="./index.php">Home</a></li>
<li><a href="./index.php?page=addtocart">Cart</a></li> -->
<?php
}
?>
<li><a href="./index.php">Home</a></li>
<li><a href="./index.php?page=addtocart">Cart</a></li>
<li id="user-link" style="float:right;"><a href="./index.php?page=login">Sign In</a></li>
<li id="user-link" style="float:right;"><a href="./index.php?page=registration">Sign Up</a></li>
</ul>
<?php
}
?>

<span id="pull">&nbsp;</span>
</nav>
</div>
</div>

</div>
</div>
</div>

