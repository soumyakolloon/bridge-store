<?php
/**
 * @project Bridge shoppingcart
 * User payment history page template
 */

?>
<div class="row">
    <div class="col-lg-12">
        <h1>Payment History <small></small></h1>

        <?php 
		
        
        if (isset($transactions) && !empty($transactions))
        { 
            ?>
            <div class="table-responsive" id="category-list">
                <table class="table table-bordered table-hover table-striped tablesorter">
                    <thead>
                        <tr>
                            <th>Transaction ID <i class="fa fa-sort"></i></th>
                            <th>Price <i class="fa fa-sort"></i></th>
                            <th>Date <i class="fa fa-sort"></i></th>
                            <th>Status <i class="fa fa-sort"></i></th>
                            <th></th>        
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        											
                        foreach ($transactions as $payment)
                        {
                        ?>
                        <tr>
                            <td><?php echo $payment['transaction_id']; ?></td>
                            <td><?php echo '$ '. $payment['total_price']; ?></td>
                            <td><?php echo date("d M Y", strtotime($payment['date_time'])); ?></td>
                            <td><?php echo $payment['payment_status']; ?></td>
                            <td>
                                <input type="button" name="view_prod" class="btn btn-primary" value="View Products" onclick="javascript:displayDetails('<?php echo $payment['purchase_id']; ?>')">
                            </td>
                        </tr>
                        
                        <div class="prod_details" id="<?php echo $payment['purchase_id']; ?>" style="display: none;">
                           <h2>Products</h2>
                                <div>
                                   <div class="table table-bordered table-hover table-striped tablesorter">
                                <?php
                                foreach ($payment['products'] as $row)
                                {
                                ?>                            
                                <p>Image: <img src="uploads/<?php echo $row['image_path']; ?>" width="100" height="100" /></p>
                                <p>Name:  <a href="./index.php?page=product-detail&product_id=<?php echo $row['id']; ?>">
                                <?php echo $row['name']; ?>
                                </a></p>
                                <p>Description: <?php echo $row['description']; ?></p>
                                <p>Price: <?php echo '$ '. $row['price']; ?></p>
                                <?php if($row['payment_status']=='Completed') { ?>  
                                <input type="button" name="download_prod" class="btn btn-primary" value="Download" onclick="javascript:window.location.href='<?php echo get_base_url(). 'index.php?page=downloader&token=' . $payment['token'] . '||' . $row['id'] ?>'">
                                <?php }
                                else {
                                ?>

                                <a href="./index.php?page=buyitnow&product_id=<?php echo $row['id']; ?>"><button type="button" class="btn btn-primary">Buy It Now</button></a>
                                <!-- <a href="./index.php?page=addtocart&product_id=<?php echo $row['id']; ?>"><button type="button" class="btn btn-primary">Add to cart</button></a> -->


                                <?php } ?>

                                <?php
                                }
                                ?>

                                    </div>
                                    </div>
                           </div>
                      
                        <?php
                        }
                        ?>
                    </tbody>
                </table>
                
                
                 
            </div>
            <?php
        }else{
            echo '<div class="table-responsive" id="category-list">No Payment Record Found !</div>';
        }
        ?>
        
        
        
                    
        
        
    </div>
</div>
<!-- Page Specific Plugins -->
<script src="js/tablesorter/jquery.tablesorter.js"></script>
<script src="js/tablesorter/tables.js"></script>
<script>
	
	
    function displayDetails(id){
        
        if($('#'+id).css('display') == 'none'){
            $('#'+id).css({'display':'block'});
        }
        else{
            $('#'+id).css({'display':'none'});
        }
    }
    
//    function displayDetails(id){
//        
//        if($('#'+id).css('display') == 'none'){
//           $('#'+id).siblings().hide();
//            $('#'+id).css('display', 'block');
//           
//        }
//        else{
//            $('#'+id).css({'display':'none'});
//            $('#'+id).siblings().show();
//        }
//    }
</script>
