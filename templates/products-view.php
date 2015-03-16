<?php
/**
 * @project Bridge shoppingcart
 * Home page template
 *
 */
?>

<!--<div class="row">
    <div class="col-lg-12">
        <h1>User Front End <small></small></h1>

        <div class="alert alert-success alert-dismissable">
            This is the user home page
        </div>
    </div>-->

<?php

$purchase_prod = array();

if(isset($purchased_products) && count($purchased_products)){
    
    $purchase_prod = $purchased_products;
    
}
 //print_r($products); exit;
?>

<div class="col-lg-12">
    <div class="panel panel-primary">
        <div class="panel-heading">
            <h3 class="panel-title">Products <?php echo (isset($cat_name) && $cat_name != '') ? '>>' . $cat_name : ''; ?></h3>
        </div>
        <div class="panel-body">
            <div class="row">
                <div class="col-lg-2">
                    <div class="bs-example">                                                  
                            <?php
                            if ((isset($categories) && count($categories) > 0))
                            { ?>
                            <ul class="list-group">  
                                <li class="list-group-item">Categories</li>
                            <?php   foreach ($categories as $category)
                                {
                                    ?>
                                    <li class="list-group-item">                                            
                                        <span class="badge"><?php echo $category['no_of_products']; ?></span>
                                        <a href="index.php?page=products-view&cat_id=<?php echo $category['id']; ?>"><?php echo $category['name']; ?></a>                                            
                                    </li>
                                    <?php
                                } ?>
                                </ul>
                            <?php }
                            else { ?>
                            <p>No products added yet!</p>
                            <?php }
                            ?>                        
                    </div>
                </div>

                <div class="col-lg-8" style="display: inline">
                    <div class="row">
                        <?php                        
						//print_r($products); exit;
                        if (isset($products) && count($products) > 0)
                        {
                            foreach ($products as $product)
                            {         
                                $class = '';
                                if(in_array($product['id'], $purchase_prod))
                                        $class = 'glyphicon glyphicon-ok-circle';
                                ?>
                                <div class="col-lg-6">
                                    <div class="panel panel-success">
                                        <div class="panel-heading">
                                          <a href="./index.php?page=product-detail&product_id=<?php echo $product['id']; ?>">
                                            <h3 class="panel-title"><?php echo $product['name']; ?>
                                            <span class="<?php echo $class; ?>" style="float: right;margin-top: 0;"></span>
                                            </h3>
                                        </a>
                                        </div>
                                        <div class="panel-body">
                                            <div class="row">
                                                <div class="col-lg-4">
                                                  <a href="./index.php?page=product-detail&product_id=<?php echo $product['id']; ?>">  <img src="uploads/<?php echo $product['image_path']; ?>" width="100" height="100" /></a>
                                                </div>                                                    
                                                <div class="col-lg-8">
                                                    <div class="prod-desc"> 
												  <?php
													
														$prod_desc = strip_tags($product['description']);
                                                                                                            
                                                         echo $desc = (strlen($prod_desc) > 50) ? substr($prod_desc, 0, 50). '....' : $prod_desc;
                                                       ?>
                                                    </div>
                                                    <p><strong><?php echo '$ '. $product['price']; ?></strong></p>
                                                    <div>
                                                        <a href="./index.php?page=buyitnow&product_id=<?php echo $product['id']; ?>"><button type="button" class="btn btn-primary">Buy It Now</button></a>
                                                        <a href="./index.php?page=addtocart&product_id=<?php echo $product['id']; ?>"><button type="button" class="btn btn-primary">Add to cart</button></a>
                                                    </div>
                                                </div>
                                            </div>                    
                                        </div>
                                    </div>
                                </div>

                                <?php
                            }
                        }
                        else{
                            ?>
                        <p>No products added yet!</p>
                        <?php
                        }
                        ?>
                    </div>               
                </div>
            </div>
        </div>
    </div>
</div>
<!--</div>-->
</div>
