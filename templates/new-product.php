<?php
/**
* @project Bridge shoppingcart
* Add new product
* Author:soumya
* Date: 03/02/2015
*
*/



$wysiwyg_root = './wysiwyg/'; 

include 'wysiwyg/php/init.php'; 

?>
<!-- style for error messages -->
<style>
.error_msg {
color:red;
font-weight:normal;
font-size:12px;
font-style: italic;
}
</style>


<script type="text/javascript" src="ckeditor/ckeditor.js"></script>




<div class="row">
<div class="col-lg-12">
<h1>Product <small></small></h1>
<ol class="breadcrumb">
<li class="active"><i class="fa fa-dashboard"></i> Manage product</li>
</ol>


<div class="table-responsive" id="product-list">
<div class="col-lg-6">

<form id="new-product-form" name="new_product_form" action="productManagement.php" method="POST" onsubmit="return productFromValid();" enctype="multipart/form-data" >   


<div class="form-group">
<label for="product-name">Product Name</label>
<input class="form-control" id="product-name" name="product-name" onblur="productFromValid();" <?php if(isset($product_info)) { echo "value=".$product_info['name']; } ?> />
<label id="product-name-error" class='error_msg'></label>
</div>
<div class="form-group">
<label>Description </label>

<textarea id="neweditor" name="neweditor" >
<?php 
if(isset($product_info)) { $desc = $product_info['description']; } ?>
<?php
if(isset($desc))
echo $desc; 
else
echo ''; 
?>
</textarea>


<!--<textarea class="form-control" rows="3" id="product-desc" name="product-desc"></textarea>-->

<label id="product-desc-error" class='error_msg'></label>

</div>
<div class="form-group">
<label>Price</label>
<input class="form-control" id="product-price" name="product-price" placeholder="Enter product price" <?php if(isset($product_info)) { echo "value=".$product_info['price']; } ?>  onblur="productFromValid();"/>
<label id="product-price-error" class='error_msg'></label>
</div>
<div class="form-group">
<label>Category</label>

<select class="form-control" name="product-cat" id="product-cat">
<?php
foreach ($categories as $cat)
{
    //if(isset($_GET['cat_id']) && ($_GET['cat_id']!=0))
    //{
        if($cat['id']==$_GET['cat_id']) {
?>
<option value="<?php echo $cat['id']; ?>" selected="selected"><?php echo $cat['name']; ?></option>
<?php } else {  ?>
<option value="<?php echo $cat['id']; ?>"><?php echo $cat['name']; ?></option>
<?php } } ?>
</select>

</div>
<div class="form-group">
<label>Download Link Validity</label>
<input class="form-control" id="product-validity" name="product-validity" placeholder="" <?php if(isset($product_info)) { echo "value=".$product_info['validity']; } ?>  onblur="productFromValid();"/>
<label id="product-validity-error" class='error_msg'></label>
</div>

<div class="form-group">
<label>Upload Product</label>
<input type="file" id="product-upload" name="product-upload" placeholder="Upload product" onblur="productFromValid();"/>
<label id="product-upload_error" class='error_msg'></label>
<?php if(isset($product_info)) { ?>
<span>
<a href="<?php echo 'uploads/'. $product_info['download_link']; ?>"><?php echo $product_info['download_link']; ?></a>
</span>
<?php } ?>
<input type="hidden" name="hid-product-upload" value="<?php echo $product_info['download_link']; ?>">
</div>

<div class="form-group">

<?php if(isset($product_images)) { ?>

<div id="container">
<div class="mask" style="display:none;"><img id="ajax-loader" src="images/bx_loader.gif"></div>
 
<?php 

foreach($product_images as $prod_img)
{
?>

<div class="image no-borderClass" id="<?php echo $prod_img['id']; ?>" name="<?php echo $prod_img['image_path']; ?>" onClick="selectImage(<?php echo $prod_img['id']; ?>);">
<!-- <a href="#" class="delete"></a> -->
<img src="uploads/<?php echo $prod_img['image_path']; ?>" width="100" height="100" alt="Please click on Image to select" title="Please click on Image to select" />
<input type="hidden" name="hid-product-image" value="<?php print_r($product_images); ?>"> 
</div> 

<?php  } ?>
<div style="margin-bottom: 15px;"><input type="button" name="delete-product-image" class="btn-primary" value="Delete Images" id="delete-product-image" style="display:none;"> </div>

</div>

<?php } ?>
<div>
<label>Upload Images</label>
<input type="file" id="product-image" name="product-image[]" multiple="multiple" onblur="productFromValid();" >

<label id="product-image_error" class='error_msg'></label>
</div>
</div>


            
<div class="form-group" ><label> Allowed Upload File Types </label><br>
<?php  
$typesArray = explode('|', $config['allowed_types']); 
echo $types = implode(', ', $typesArray)
?>
</div>

<?php
if (isset($product_info) && $product_info['categoryStatus'] == '0')
{ 
?> 
<div class="form-group" id="prod-status" style="display: none" >
<?php }else{
?>
<div class="form-group" id="prod-status" >
<?php
} ?>
<label>Status</label>
<?php
if (isset($product_info))
{
?>
<label class="radio-inline">
<input name="product-status" id="product-status-enabled" value="1" type="radio" <?php echo ($product_info['status'] == 1) ? 'checked=""' : '';  ?>> Enabled
</label>
<label class="radio-inline">
<input name="product-status" id="product-status-disabled" value="0" type="radio" <?php echo ($product_info['status'] == 0) ? 'checked=""' : '';  ?>> Disabled
</label>
<?php


}
else if (!isset($product_info))
{
?>
<label class="radio-inline">
<input name="product-status" id="product-status-enabled" value="1" type="radio" checked=""> Enabled
</label>
<label class="radio-inline">
<input name="product-status" id="product-status-disabled" value="0" type="radio"> Disabled
</label>
<?php
}
?>
</div>

<?php if(isset($product_info)) { ?>
<input type="hidden" id="page_type" value="1">
<input type="hidden" id="product_id" name="product_id" value="<?php echo $product_info['id']; ?>" />

<?php } else { ?>
<input type="hidden" id="page_type" value="0">
<?php } ?>
<input type="hidden" name="category-status" id="category-status" value="" >      
<input type='submit' class="btn btn-primary" id="new-product-submit" value="Submit" >  
<!--  -->
<button type="reset" class="btn btn-default" id="new-product-reset">Reset</button>

</form>
</div>
</div>

</div>
</div>
    


<script type="text/javascript">
var neditor = CKEDITOR.replace( 'neweditor' );
</script>


<script type="text/javascript">

/**on mouse hover display the close icon above each image and and remove the corresponding div */
$(document).ready(function(){
// console.info($('.image').closest('.image')[0].id).length));
var select_count=0;
var totalcount = $('.image').length;
var totallen = 100*totalcount;
$('.mask').css('width',  totallen+"px");
$('.mask').css('height', $('#container').height()+"px");
$('#ajax-loader').css('margin-left',  totallen/2+"px");
$('#ajax-loader').css('margin-top', '26px');
});

function selectImage(id)
{
//select_count++;
var counter;
//ctrl was held down during the click
if (window.event.ctrlKey)   
{ 

$('#'+id+' img').attr('alt', 'Please click on image to select the image');
$('#'+id+' img').attr('title', 'Press click on image to select the image');
$('#'+id).removeClass('borderClass');  
$('#'+id).addClass('no-borderClass');
/**IF no image is selected hide the delete button**/
counter = $('.borderClass').length;

if(counter==0)
  $('#delete-product-image').hide();

}
else
{


$('#'+id+' img').attr('alt', 'Press cntrl+select to deselect the image');
$('#'+id+' img').attr('title', 'Press cntrl+select to deselect the image');

$('#'+id).addClass('borderClass');
$('#'+id).removeClass('no-borderClass');
$('#delete-product-image').show();
}

}

/**Handle delete event*/
$('#delete-product-image').on('click',function(e){
var cnt = $('.borderClass').length;


if(cnt>0)
{

/**Variable declaration**/
var data_input = {};
data_input['product_id'] = $('#product_id').val();


var arrayOfdeslectIds = $.map($(".no-borderClass"), function(n, i){
  return n.id;
});

if(arrayOfdeslectIds.length>=1)
{
var arrayOfslectIds = $.map($(".borderClass"), function(n, i){
  return n.id;
});



$.each(arrayOfslectIds, function(index, value ) {

data_input['imagekey'] = arrayOfslectIds[index];
data_input['image_path'] = $('#'+arrayOfslectIds[index]).attr('name');
$('.mask').show();

/**Remove the entry from databse and folder path**/
$.ajax({
url: "productDeleteAjax.php",
type: 'POST',
data: data_input,
success: function (returndata) {
console.info(returndata);

$('#'+arrayOfslectIds[index]).animate({width:0},200,function(){
    if(returndata==1)
    {    
        $('#'+arrayOfslectIds[index]).remove();
        $('#delete-product-image').hide();
       $('.mask').hide();
 
   
    //location.reload();
   // e.preventDefault();
}

});
},

});


});

  }
  else
{
  $('#product-image_error').text("Please keep atleast one file");
}

}



});




// $('a.delete').on('click',function(e){
// e.preventDefault();
// imageID = $(this).closest('.image')[0].id;
// // alert('Now deleting "'+imageID+'"');
// if(confirm('Are you sure you want to delete the image?')) {

// var imagedivcount=$('.image').length;
// $('#ajax-loader').css('width', $('.image').width()*imagedivcount);
// $('#ajax-loader').css('height', $('.image').height());
// $('#ajax-loader').css('left', '13px');
// $('#ajax-loader img').css('margin-left',  ($('.image').width()*imagedivcount)/2);
// $('#ajax-loader').show();

// /**Variable declaration**/
// var data_input = {};
// data_input['product_id'] = $('#product_id').val();
// data_input['image_path'] = $('#'+imageID).attr('name');
// data_input['imagekey'] = imageID;

// //var countflag=imagedivcount; 
// if(imagedivcount>1)
// {
// /**End of variable declaration**/

// /**Remove the entry from databse and folder path**/
// $.ajax({
// url: "productDeleteAjax.php",
// type: 'POST',
// data: data_input,
// success: function (returndata) {
// console.info(returndata);
// //$(this).closest('.image').fadeTo(300,0,function(){
// // $(this).animate({width:0},200,function(){
//     if(returndata==1)
//     {    
//         $('#'+imageID).remove();
//        $('#ajax-loader').hide();
 
   
//     //location.reload();
//    // e.preventDefault();
// }
// // });
// // });
// },
// });
// }
// else
// {
//     $('#product-image_error').text("Please add more files to remove the existing one");
// }


// // });


// }
// });

// //});


// /**Form Validation starts here*/
function productFromValid()
{
/**Form feild varibale declaration*/
var flag=true;


var prodName=$('#product-name').val();
var prodDesc=$('#neweditor').val();
var prodPrice=$('#product-price').val();
// var prodCat = $('#product-cat option:selected').val();
var productvalidity=$('#product-validity').val();
var productuploadLength =  document.getElementById("product-upload").files.length;
var productimageLength = document.getElementById("product-image").files.length;

var page_type = $('#page_type').val();


var flag1=true;
var flag2=true;
var flag3=true;
var flag4=true;
var flag5=true;
var flag6=true;
var flag7=true;
var flag8=true;
var flag9=true;
var flag10=true;
var flag11=true;

// if(page_type==0)
// {

// alert((document.getElementById("product-image").files.length));
if(productuploadLength>0)
{
var uploadedprodFile = document.getElementById('product-upload');
var prodFileName = uploadedprodFile.files[0].name;
}



if(!requireValidation(prodName))
{
$('#product-name-error').text("Product Name field cannot be empty");

flag1=false;
}
else
{
$('#product-name-error').text("");

flag1=true;
}


if(!requireValidation(prodDesc))
{
$('#product-desc-error').text("Product description field cannot be empty");

flag2=false;
}
else
{
$('#product-desc-error').text("");

flag2=true;
}

if(!requireValidation(prodPrice))
{

$('#product-price-error').text("Product price field cannot be empty");

flag3=false;
}
else
{
$('#product-price-error').text("");

flag3=true;
}

if(!requireValidation(productvalidity))
{
$('#product-validity-error').text("Product validity field cannot be empty");

flag4=false;
}
else
{
$('#product-validity-error').text("");

flag4=true;
}

if(page_type==0)
{
if(!requireValidationForFiles(productuploadLength))
{
$('#product-upload_error').text("Please upload a file");

flag5=false;
}
else
{
$('#product-upload_error').text("");

flag5=true;
}
}


// productimageLength
if(page_type==0)
{
if(!requireValidationForFiles(productimageLength))
{
$('#product-image_error').text("Please upload atleast one file");

flag6=false;
}
else
{
$('#product-image_error').text("");

flag6=true;
}
}

if(productvalidity)
{
if(!isNumeric(productvalidity))
{
$('#product-validity-error').text("Please enter a numeric value");

flag7=false;
}
else
{
$('#product-validity-error').text("");

flag7=true;
}
}

if(prodPrice)
{
if(!isNumeric(prodPrice))
{
$('#product-price-error').text("Please enter a numeric value");

flag8=false;
}
else
{
$('#product-price-error').text("");

flag8=true;
}
}

if(page_type==0)
{
if(prodFileName)
{
if(!checkfileType(prodFileName))
{
$('#product-upload_error').text("File Type is not supported. Please enter a valid file");

flag9=false;

}
else
{
$('#product-upload_error').text("");

flag9=true;
}
}
}


var proImageName;

if(productimageLength>0)
{
var uploadedImages = document.getElementById('product-image').files;
for(i=0; i<uploadedImages.length; i++)
{
$.each(uploadedImages[i], function(index, value ) {
// console.log(index+"ggg"+value);
proImageName = uploadedImages[i]['name'];
if(!checkImagefileType(proImageName))
{

$('#product-image_error').text("File Type is not supported. Please enter a valid file");
flag10=false;
}
else
{
$('#product-image_error').text("");
flag10=true;
}

});

}

}



if(flag1==true && flag2==true && flag3==true && flag4==true && flag5==true && flag6==true && flag7==true && flag8==true && flag9==true && flag10==true )
flag=true;
else
flag=false;
console.info(flag);
// }

return flag;

}

function formSubmission()
{
var flag= productFromValid();

if(flag==true)
{
//var data={};

var formData = new FormData($('#new-product-form')[0]);
formData['lenght'] = 1; 
console.info(formData);

$.ajax({
url: "productManagement.php",
type: 'POST',
data: formData,
processData: false,
contentType: false,
//cache:false,
//async:false,
success: function (returndata) {
if(returndata!="ERROR")
{
//console.info("Product saved successfully");
window.location.href = 'index.php?page=products&msg=1';
}
else
{
console.info("Error occured while saving the product details");
}
},



});
return false;
// console.info("submit success");


}   

}



function requireValidation(id)
{
if(id=='')
return false;
else
return true;
}

function requireValidationForFiles(id)
{
if(id>=1)
return true;
else
return false;
}

function isNumeric(numVal)
{
if(isNaN(numVal)){
return false;
}else{
//document.write(num1 + " is a number <br/>");
return true;
}

}

function checkfileType(fname)
{

var re = /(\.jpg|\.jpeg|\.bmp|\.gif|\.png|\.txt|\.pdf|\.docx|\zip)$/i;
if(!re.exec(fname))
{
return false;
}
else
return true;
}



function checkImagefileType(fname)
{

var re = /(\.jpg|\.jpeg|\.bmp|\.gif|\.png)$/i;
if(!re.exec(fname))
{
return false;
}
else
return true;
}

</script>
