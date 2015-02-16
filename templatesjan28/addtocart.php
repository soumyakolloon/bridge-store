<?php
/**
 * @project Bridge shoppingcart
 * Cart page template
 *
 */
?>

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
                    <form method="post" action="<?php echo $base_url; ?>process.php">
                        <div class="row">
                            <div class="col-lg-12" style="display: inline">
                                <div class="row">
                                    <?php                        
                                    $price = 0;$i=0;
                                    foreach ($products as $product)
                                    {
                                        $price = $price + $product['price'];
                                        ?>
                                        <div class="col-lg-12">
                                            <div class="panel panel-success">
                                                <div class="panel-body">
                                                    <div class="row">
                                                        <div class="col-lg-2">
                                                            <img src="uploads/<?php echo $product['id'] . '_' . $product['image_path']; ?>" width="100" height="100" />
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
                                <button  type="submit" name="submitbutt" class="btn btn-primary">Proceed</button>
                                <button type="button" name="shop" class="btn btn-group" onclick="javascript:window.location.href='<?php echo get_base_url(); ?>'">Continue Shopping</button>
                                </span>
                            </p>
                        </div>
                    </form>
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