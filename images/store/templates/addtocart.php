<?php
/**
 * @project Bridge shoppingcart
 * Cart page template
 *
 */
?>

<script>
// Check browser support
// var products = <?php print_r(json_encode($products)); ?>;
// console.info(products);
// if (typeof(Storage) != "undefined") {
//     // Store
//     $(products).each(function(index, value){

//         localStorage.setItem("product-name"+index, value.name);
//         localStorage.setItem("product-price"+index, value.price);
//         localStorage.setItem("product-desc"+index, value.description);
//         localStorage.setItem("product-img"+index, value.image_path);

//     });
    
    
//     // Retrieve
//     // document.getElementById("result").innerHTML = localStorage.getItem("lastname");
// } else {
//     // document.getElementById("result").innerHTML = "Sorry, your browser does not support Web Storage...";
// }
</script>
<div class="mask" style="display:none;"><img id="ajax-loader" src="images/bx_loader.gif"></div>
<div class="row">
    <div class="col-lg-12">
	<h1>Shopping Cart</h2>
        <?php
        if($_SESSION['warning']!=''){
        ?>
          <div class="alert alert-warning alert-dismissable" id="warning">
              <?php echo $_SESSION['warning'];
              $_SESSION['warning']='';
              ?>
          </div>
     <?php
        }
        ?>
        <div class="panel panel-primary">
            <div class="panel-heading">
                <h3 class="panel-title" style="width:40%">Products</h3>
                <h3 class="panel-title" style="float:right; width: 24%; margin-top: -18px !important;">Price</h3>
            </div>
            <div class="panel-body">
				
                <?php                        
              // print_r($products); exit;
                if (isset($products) && count($products) > 0)
                {
                ?>
                    <form method="post" action="./process.php" onsubmit="return checkLogin();">
                        <div class="row">
                            <div class="col-lg-12" style="display: inline">
                                <div class="row">
                                    <?php                        
                                    $price = 0;$i=0;
                                    foreach ($products as $prod)
                                    {
                                      
                                        $product = $prod[0];
                                        $price = $price + $product['price'];
                                        ?>
                                        <div class="col-lg-12">
                                            <div class="panel panel-success">
                                                <div class="panel-body">
                                                    <div class="row">
                                                        <div class="col-lg-2">
                                                            <a href="./index.php?page=product-detail&product_id=<?php echo $product['id']; ?>"><img src="uploads/<?php echo $product['image_path']; ?>" width="100" height="100" /></a>
                                                        </div>                                                    
                                                        <div class="col-lg-7">
                                                            <div>
                                                                <strong> <a href="./index.php?page=product-detail&product_id=<?php echo $product['id']; ?>"><?php echo $product['name']; ?></a></strong>
                                                            </div>
                                                            <div>
                                                                <?php echo $product['description']; ?>
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-2">
                                                            <p><?php echo '$ '. $product['price']; ?></p>
                                                        </div>
                                                        <div>
                                                            <!-- <a class="glyphicon glyphicon-remove-circle" href="./index.php?page=delete-product&id=<?php echo $product['id']; ?>&section=cart"></a> -->
                                                            <a class="glyphicon glyphicon-remove-circle" style="cursor:pointer" onClick="reset_cart(<?php echo $product['id']; ?>);"></a>
                                                        </div>
                                                    </div>                    
                                                </div>
                                            </div>
                                        </div>
                                        <input type="hidden" name="products[<?php echo $i; ?>][itemname]" value="<?php echo $product['name']; ?>" />
                                        <input type="hidden" name="products[<?php echo $i; ?>][itemnumber]" value="<?php echo $product['id']; ?>" />
                                        <input type="hidden" name="products[<?php echo $i; ?>][itemdesc]" value="<?php echo $product['description']; ?>" />
                                        <input type="hidden" name="products[<?php echo $i; ?>][itemprice]" value="<?php echo base64_encode($product['price']); ?>" />
                                        <input type="hidden" name="products[<?php echo $i; ?>][itemQty]" value="1" />

                                        <?php
                                        $i++;
                                    }
                                    ?>
                                </div>               
                            </div>
                        </div>
                        <div class="panel-title" style="float:right; width: 28%;">
                            <p>
                                <strong>Total &nbsp;&nbsp;  <?php echo '$ '. $price; ?></strong>&nbsp;
                                <span style="float: right; margin-top: -5px;">
                                <button  type="submit" name="submitbutt" class="btn btn-primary" id="clicker" >Proceed</button>
                                

                                <button type="button" name="shop" class="btn btn-group" onclick="javascript:window.location.href='<?php echo get_base_url(); ?>'">Continue Shopping</button>
                                </span>
                            </p>
                        </div>
                    </form>

                    <!-- Added primary data submission form code by @soumya 06/02/2015-->
                    <div id="popup-wrapper" style="background-color: #ccc; display:none; width:500px; height:300px; padding:10px">
                                    
                                        
                                        <form enctype="multipart/form-data" method="POST">
                                        <div class="mask" style="display:none; width:500px; height:300px; left:0px; top:0px;">
											<img id="ajax-loader" style="margin-left:250px; margin-top:150px;" src="images/bx_loader.gif"></div>
											
                                         <a id="close-btn" href="#"><img src="img/close.png"></a>
                                         
                                         <div class="form-group" id="email_field">
                                        <label>Email:</label>
                                        <input class="form-control" id="customer_email" name="customer_email" placeholder=""  />
                                        <label id="customer_email_error" class='error_msg'></label>
                                        </div>
										
										
										<div id="new_reg_section" style="display:none;">
										
										<div class="form-group" id="name_field">
                                        <label>Your Name:</label>
                                        <input class="form-control" id="customer_name" name="customer_name" placeholder="" />
                                        <label id="customer_name_error" class='error_msg'></label>
                                        </div>
										
                                        <div class="form-group" id="password_feild" >
										<label>Choose Password:</label>
                                        <input type="password" class="form-control" id="password_set" name="password_set" />
                                        <label id="password_set_error" class='error_msg'></label>
                                        </div>
                                        
										</div>
										
										
										


                                        <input type='button' class="btn btn-primary" id="primary_click" value="Submit" onClick="javascript:fieldvalidate();"  >  
                                        <!--  -->

                                        <button type="reset" class="btn btn-default" id="new-product-reset">Reset</button>
                                       
                                    <form>
                                    

                                </div>
                                

                <?php
                }else{
                    echo 'Your cart is empty !';
                    ?>
                <p>
                <button type="button" name="shop" class="btn btn-primary" onclick="javascript:window.location.href='<?php echo get_base_url(); ?>'">Continue Shopping</button>
                </p>
                <?php
                }
                ?>
            </div>
        </div>
    </div>
    <!--</div>-->
    </div>
</div>

<script>
$(function () { 
	
	
   
    if(!checkLogin())
    {

  $('#popup-wrapper').show();      
 $('#popup-wrapper').modalPopLite({ 
    openButton: '#clicker', closeButton: '#close-btn' }); 


    }
 }); 

/**Handling the cart product removal**/
function reset_cart(id)
{
	
  $('.mask').css('width', $(window).width());
	$('.mask').css('height', $(window).height());
	$('.mask').css('margin-top', '-140px');
	$('.mask').css('margin-left', '-30px');
	
	$('#ajax-loader').css('margin-left', $(window).width()/2);
	$('#ajax-loader').css('margin-top', $(window).height()/2);
	
	$('.mask').show();
	
    var d={};
    d['prod_id'] = id;

    $.ajax({
    url: "manageCookies.php",
    type: 'POST',
    data: d,
    success: function (respcook) {
              $('.mask').hide();
    window.location.href="index.php?page=addtocart";
       
    }
    
    });


  
}



/**Check the login status**/
function checkLogin()
{
    var user = "<?php echo $_SESSION['user_id']; ?>";

    if(user!='')
    {
    return true;
    }
    else
    {
    
    return false; 
    }
}

/**Login popup validation**/

function fieldvalidate()
{
    var flag=true;
    var flag1=true;
    var flag2=true;
    var flag3=true;
    var flag5=true;

   
    var customer_email = $('#customer_email').val();


 if(!requireValidation(customer_email))
{
$('#customer_email_error').text("Email field cannot be empty");

flag2=false;
}
else
{
$('#customer_email_error').text("");

flag2=true;
}

if(!validateEmail(customer_email))
{
$('#customer_email_error').text("Please enter a valid email");

flag3=false;
}
else
{
$('#customer_email').text("");

flag3=true;
}

if(flag2==true && flag3==true)
    flag=true
else
    flag=false;


//return flag;

if(flag==true)
{
$('.mask').show();
var d={};
d['email'] = customer_email;
//d['username'] = customer_name;

$.ajax({
url: "verifylogin.php",
type: 'POST',
data: d,
success: function (returndata) {
if(returndata==0)
{
    $('.mask').hide();
//$('#name_field').hide();    
$('#email_field').hide();
$('#new_reg_section').show();
var pwd_feild = $('#password_set').val();
 var customer_name = $('#customer_name').val();
 if(!requireValidation(customer_name))
{
$('#customer_name_error').text("Name field cannot be empty");

flag1=false;
//return false;

}
else
{
$('#customer_name_error').text("");

flag1=true;
//return true;

}
if(!requireValidation(pwd_feild))
{
$('#password_set_error').text("Please enter a password");
flag5 = false;
}
else
{
$('#password_set_error').text("");
flag5 = true;	
}


if(flag5==true && flag1==true)
{
$('.mask').show();
$('#password_set_error').text("");
$('#customer_name_error').text("");
d['password'] = pwd_feild;
d['username'] = customer_name;

$.ajax({
url: "saveLoginInfo.php",
type: 'POST',
data: d,
success: function (response) {

if(response!=0)
{
    $('.mask').hide();

    $( "#close-btn" ).trigger( "click" );
   window.location.href="index.php?page=addtocart";
}

}

});



}



}
else
{
$('.mask').show();

$.ajax({
url: "makeLogin.php",
type: 'POST',
data: d,
success: function (resp) {
//$.parseJSON(resp);

console.info(resp.user_id);
if(resp!=0)
{
   $('.mask').hide();
   $( "#close-btn" ).trigger( "click" );
   window.location.href="index.php?page=addtocart";

}
}
});

}



},
});
}



}

function requireValidation(id)
{
if(id=='')
return false;
else
return true;
}

function validateEmail(email) 
{
    var re = /\S+@\S+\.\S+/;
    return re.test(email);
}

</script>
