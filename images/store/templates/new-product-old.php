<?php
/**
 * @project Bridge shoppingcart
 * Add new product
 */



$wysiwyg_root = './wysiwyg/'; 

include 'wysiwyg/php/init.php'; 
                            

$imgError = '';
$prodError = '';
if(!empty($_GET['imgErr'])){
    
    $imgError = 'Image Upload: '. display_error($_GET['imgErr']); 
}
if(!empty($_GET['prodErr'])){
    
    $prodError = 'Product Upload: '. display_error($_GET['prodErr']);
}

function display_error($error){
    
    switch ($error)
    {
        case 1: // UPLOAD_ERR_INI_SIZE
            $return = 'file size exceeds limit';
            break;
        case 2: // UPLOAD_ERR_FORM_SIZE
            $return = 'file size exceeds form limit';
            break;
        case 3: // UPLOAD_ERR_PARTIAL
            $return = 'partial file';
            break;
        case 4: // UPLOAD_ERR_NO_FILE
            $return = 'no file selected';
            break;
        case 6: // UPLOAD_ERR_NO_TMP_DIR
            $return = 'no temp directory';
            break;
        case 7: // UPLOAD_ERR_CANT_WRITE
            $return = 'unable to write file';
            break;
        case 8: // UPLOAD_ERR_EXTENSION
            $return = 'Invalid file extension';
            break;
        case 9: // UPLOAD_ERR_EXTENSION
            $return = 'Invalid file dimensions';
            break;
        case 10: // UPLOAD_ERR_EXTENSION
            $return = 'Invalid file name';
            break;
        case 11: // UPLOAD_ERR_EXTENSION
            $return = 'Invalid file destination';
            break;
        default : 
            $return = 'no file selected';
            break;
    }
    
    return $return;
}
?>



<div class="row">
    <div class="col-lg-12">
        <h1>Product <small></small></h1>
        <ol class="breadcrumb">
            <li class="active"><i class="fa fa-dashboard"></i> Manage product</li>
        </ol>
        <?php if(!empty($imgError) || !empty($prodError)){ ?>
        <div class="has-warning">
            <?php if(!empty($imgError)){ ?>
            <span class="control-label input-group-addon"><?php echo $imgError; ?></span><br>
            <?php }
            if(!empty($prodError)){ ?>
            <span class="control-label input-group-addon"><?php echo $prodError; ?></span><br>
            <?php } ?>

        </div>
        <?php } ?>
        
        <div class="table-responsive" id="product-list">
            <div class="col-lg-6">
               <!-- role="form"  post-handler.php?action=PRODUCT&cat_id=<?php //echo $_GET['cat_id']; ?> enctype="multipart/form-data" -->
                <form id="new-product-form" name="new_product_form" action="post-handler.php?action=PRODUCT&cat_id=<?php //echo $_GET['cat_id']; ?>" method="POST" enctype="multipart/form-data">                    <?php
                    if (isset($product_info))
                    {
                        ?>
                        <div class="form-group">
                             <label for="product-name">Product Name</label>
                            <input type="hidden" name="product-id" value="<?php echo $product_info['id']; ?>" />

                            <input class="form-control" id="product-name" name="product-name" value="<?php echo $product_info['name']; ?>">
                        </div>
                        <div class="form-group">
                            <label>Description</label>
                            <!-- <textarea class="form-control" rows="3" name="product-desc"></textarea> -->
                             <?php echo wysiwyg('wysiwyg_id', 'product-desc', $product_info['description']); ?>
                        </div>
                        <div class="form-group">
                            <label>Price</label>
                            <input class="form-control" id="product-price" name="product-price" placeholder="Enter product price" value="<?php echo $product_info['price']; ?>"/>
                        </div>
                        <div class="form-group">
                            <label>Category</label>
                            <select class="form-control" name="product-cat"  id="product-cat">
                                <?php
                                foreach ($categories as $cat)
                                {
                                    if ($product_info['cat_id'] == $cat['id'])
                                    {
                                        $categoryStatus = $cat['status'];
                                        ?>
                                        <option value="<?php echo $cat['id']; ?>" selected="selected"><?php echo $cat['name']; ?></option>
                                        <?php
                                    }
                                    else
                                    {
                                        ?>
                                        <option value="<?php echo $cat['id']; ?>"><?php echo $cat['name']; ?></option>
            <?php
        }
    }
    ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Download Link Validity</label>
                            <input class="form-control" id="product-validity" name="product-validity" placeholder="" value="<?php echo $product_info['validity']; ?>"/>
                        </div>

                        <!--                        <div class="form-group">
                                                    <label>Download Link</label>
                                                    <input class="form-control" id="product-link" name="product-link" placeholder="Enter product link" value="<?php echo $product_info['download_link']; ?>"/>
                                                </div>-->


                        <div class="form-group">                            
                            <label>Upload Product</label>
                            <input type="file" id="product-upload" name="product-upload" placeholder="Upload product" />
                          
                            <label>Current Product: </label>
                            <span>
                                <a href="<?php echo 'uploads/' . $product_info['id'] . '_' . $product_info['download_link']; ?>"><?php echo $product_info['download_link']; ?></a>
                            </span>
                            <input type="hidden" name="hid-product-upload" value="<?php echo $product_info['download_link']; ?>">

                        </div>

                        <div class="form-group">
                            <img src="uploads/<?php echo $product_info['id'] . '_' .$product_info['image_path']; ?>" width="100" height="100" />
                            <input type="hidden" name="hid-product-image" value="<?php echo $product_info['image_path']; ?>">
                            <label>Image</label>
                            <input type="file" id="product-image" name="product-image[]" multiple>
                        </div>

                             <?php
                                }
                                else
                                {
                            ?>
                        <div class="form-group">
                            <label for="product-name">Product Name</label>
                            <input class="form-control" id="product-name" name="product-name" />
                        </div>
                        <div class="form-group">
                            <label>Description </label>
                            <!--<textarea class="form-control" rows="3" id="product-desc" name="product-desc"></textarea>-->
                            
                            <?php echo wysiwyg('wysiwyg_id', 'product-desc', 'Descrption'); ?>
                        </div>
                        <div class="form-group">
                            <label>Price</label>
                            <input class="form-control" id="product-price" name="product-price" placeholder="Enter product price" />
                        </div>
                        <div class="form-group">
                            <label>Category</label>
                            <select class="form-control" name="product-cat" id="product-cat">
                            <?php
                            foreach ($categories as $cat)
                            {
                                ?>
                                    <option value="<?php echo $cat['id']; ?>"><?php echo $cat['name']; ?></option>
                                <?php
                            }
                            ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Download Link Validity</label>
                            <input class="form-control" id="product-validity" name="product-validity" placeholder="" />
                        </div>
                        <!--                        <div class="form-group">
                                                    <label>Download Link</label>
                                                    <input class="form-control" id="product-link" name="product-link" placeholder="Enter product link" />
                                                </div>-->
                        <div class="form-group">
                            <label>Upload Product</label>
                            <input type="file" id="product-upload" name="product-upload" placeholder="Upload product" />
                             <label id="upload_error" ></label>
                        </div>

                        <div class="form-group">
                            <label>Image</label>
                            <input type="file" id="product-image" name="product-image[]" multiple>
                        </div>
                          <label id="img_upload_error" ></label>

    <?php
}
?>
                        
                        <div class="form-group"><label> Allowed Upload File Types </label><br>
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
                    <input type="hidden" name="category-status" id="category-status" value="<?php echo $product_info['categoryStatus']; ?>" >      
                    <button class="btn btn-primary" id="new-product-submit">Submit</button>
                    <button type="reset" class="btn btn-default" id="new-product-reset">Reset</button>

                </form>
            </div>
        </div>

    </div>
</div>
    
   
    

<script type="text/javascript">
  
    $(document).ready(function() {
        $('#new-product-form').submit(function(e) {
           

            flag = true;
            productName = $('#product-name').val();
            $('#product-name').removeAttr('style');
            $('#product-desc').removeAttr('style');
            $('#product-price').removeAttr('style');
            $('#product-validity').removeAttr('style');

            flag1 = validate('name', 'Product name is required', 'required');
            flag2 = validate('desc', 'Description is required', 'required');
            flag3 = validate('price', 'Price should be a numeric field', 'numeric');
            flag4 = validate('validity', 'Validity should be a numeric field', 'numeric');
           
            flag = flag1 && flag2 && flag3 && flag4;

            if (flag) {
                return;
            } else {
                e.preventDefault();
            }
        });
        
        // /* product status display based on active category */
        $('#product-cat').change(function(){
           
           var catid = $(this).val();
           var arrayCategories = <?php echo json_encode($categories); ?>;
           
            $.each(arrayCategories, function (i, elem) {
               if(elem.id == catid){
                   $('#category-status').val(elem.status);
                   if(elem.status == 1){
                       $('#prod-status').show();
                   }
                   else{
                       $('#prod-status').hide();
                   }
               }
            });
        });

         

    });

    function validate(field, message, filter)
    {
        valid = false;
        if (filter === 'required') {
            valid = ($('#product-' + field).val() != '') ? true : false;
        } else if (filter === 'numeric') {
            valid = $.isNumeric($('#product-' + field).val());
        } else {
            valid = true;
        }

        if (!valid) {
            $('#product-' + field).val('');
            $('#product-' + field).attr('placeholder', message);
            $('#product-' + field).css('border-color', 'red');
            return false;
        } else {
            return true;
        }
    }


</script>

