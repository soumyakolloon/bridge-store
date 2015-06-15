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

<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-primary">
            <div class="panel-heading">
                <h3 class="panel-title" style="width:40%">Shopping Cart</h3>
                <h3 class="panel-title" style="float:right; width: 24%; margin-top: -18px !important;">Price</h3>
            </div>
            <div class="panel-body">
                <?php                        
               
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
                                                            <img src="uploads/<?php echo $product['image_path']; ?>" width="100" height="100" />
                                                        </div>                                                    
                                                        <div class="col-lg-7">
                                                            <div>
                                                                <strong> <?php echo $product['name']; ?></strong>
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
                                                            <a class="glyphicon glyphicon-remove-circle" href="./index.php?page=delete-product&id=<?php echo $product['id']; ?>&section=cart"></a>
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
                                    <style>
                                    .error_msg{
                                        color:red;
                                        font-weight:normal;
                                        font-size:10px;
                                    }

                                    div.mask {
                                        position:           absolute;
                                        z-index:            1;
                                        top:                0;
                                        left:               0;
                                        width:              100%;
                                        height:             100%;
                                        background-color:   rgba(0,0,0,0.15);
                                    }



                                    </style>
                                        
                                        <form enctype="multipart/form-data" method="POST">
                                            
                                        <div id="ajax-loader" class='mask' style="display:none; float: right; margin: 136px 256px 61px 52px;"><img src="images/bx_loader.gif"></div>
                                        <div class="form-group" id="name_field">
                                        <label>Your Name:</label>
                                        <input class="form-control" id="customer_name" name="customer_name" placeholder="" onblur="fieldvalidate();" />
                                        <label id="customer_name_error" class='error_msg'></label>
                                        </div>

                                        <div class="form-group" id="email_field">
                                        <label>Email:</label>
                                        <input class="form-control" id="customer_email" name="customer_email" placeholder="" onblur="fieldvalidate();" />
                                        <label id="customer_email_error" class='error_msg'></label>
                                        </div>

                                        <div class="form-group" id="password_feild" style="display:none;">
                                        <label>Choose Password:</label>
                                        <input type="password" class="form-control" id="password_set" name="password_set" placeholder="" onblur="fieldvalidate();"/>
                                        <label id="password_set_error" class='error_msg'></label>
                                        </div>

                                       <input type='button' class="btn btn-primary" id="primary_click" value="Submit" onClick="javascript:fieldvalidate();"  >  
                                       
                                       <button type="reset" class="btn btn-default" id="new-product-reset">Reset</button>
                                       <a id="close-btn" href="#">Close</a>
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
    // $('#popup-wrapper').modalPopLite({ 
    // openButton: '#clicker', closeButton: '#close-btn' }); 
    // }); 
    return false; 
    }
}


function fieldvalidate()
{
    var flag=true;
    var flag1=true;
    var flag2=true;
    var flag3=true;

    var customer_name = $('#customer_name').val();
    var customer_email = $('#customer_email').val();

 if(!requireValidation(customer_name))
{
$('#customer_name_error').text("Name field cannot be empty");

flag1=false;
}
else
{
$('#customer_name_error').text("");

flag1=true;
}
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
$('#customer_email_error').text("Please Enter a valid email");

flag3=false;
}
else
{
$('#customer_email').text("");

flag3=true;
}

if(flag1==true && flag2==true && flag3==true)
    flag=true
else
    flag=false;


//return flag;

if(flag==true)
{

var d={};
d['email'] = customer_email;
d['username'] = customer_name;

$.ajax({
url: "verifylogin.php",
type: 'POST',
data: d,
success: function (returndata) {
if(returndata==0)
{
    
$('#name_field').hide();    
$('#email_field').hide();
$('#password_feild').show();

var pwd_feild = $('#password_set').val();

if(!requireValidation(pwd_feild))
{
$('#password_set_error').text("Please enter a password");
}
else
{
$('#ajax-loader').show();
$('#password_set_error').text("");
d['password'] = pwd_feild;


$.ajax({
url: "saveLoginInfo.php",
type: 'POST',
data: d,
success: function (response) {

if(response!=0)
{
    $('#ajax-loader').hide();

    $( "#close-btn" ).trigger( "click" );
   window.location.href="index.php?page=addtocart";
}

}
});



}



}
else
{
$('#ajax-loader').show();

$.ajax({
url: "makeLogin.php",
type: 'POST',
data: d,
success: function (resp) {
//$.parseJSON(resp);

console.info(resp.user_id);
if(resp!=0)
{
   $('#ajax-loader').hide();
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