<?php
/**
 * @project Bridge shoppingcart
 * User payment history page template
 */


//if(isset($_GET['status']) && $_GET['status']=='success')
//{
 // 
 
 
//}

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
                        
                        <tr class="prod_details" id="<?php echo $payment['purchase_id']; ?>" style="display: none;">
                            <td colspan="6">
                                <div>
                                    <table class="table table-bordered table-hover table-striped tablesorter">
                                        <!--<thead>-->
                                            <tr>                                     
                                                <th>Image</th>
                                                <th>Name </th>
                                                <th>Description </th>
                                                <th>Price </th>
                                                 <?php //if($payment['payment_status']=='Completed')  { ?>  
                                               <!-- <th>Expiry Date for Download</th>-->
                                                
                                                 <?php //} ?>
                                                <th></th>                                                
                                            </tr>
                                        <!--</thead>-->
                                        <!--<tbody>-->
                                            
                                        
                        <?php
                        
                                                
                        foreach ($payment['products'] as $row)
                        {
                           
                            
                            ?>
                                    <tr>
                                        <td style="width: 1%"><img src="uploads/<?php echo $row['image_path']; ?>" width="100" height="100" /></td>
                                        <td style="width: 15%">
                                        <a href="./index.php?page=product-detail&product_id=<?php echo $row['id']; ?>">
                                            <?php echo $row['name']; ?>
                                        </a>
                                        </td>
                                        <td style="width: 40%"><?php echo $row['description']; ?></td>
                                        <td style="width: 19%"><?php echo '$ '. $row['price']; ?></td>
                                      <?php if($row['payment_status']=='Completed') { ?>  
                                        <!--<td style="width: 15%"><?php //echo (!empty($row['expires_on'])) ? date("d M Y H:i A", strtotime($row['expires_on'])) : ''; ?></td>-->
                                        <td style="width: 10%">
                                            <?php
                                            //if( !empty($row['expires_on']) && $row['expires_on'] > date("Y-m-d h:i:s")){
                                            ?>
                                            <input type="button" name="download_prod" class="btn btn-primary" value="Download" onclick="javascript:window.location.href='<?php echo get_base_url(). 'index.php?page=downloader&token=' . $payment['token'] . '||' . $row['id'] ?>'">
                                            <?php// } ?>
                                        </td>
                                      <?php }
                                        else {
                                       ?>
                                <td style="width: 10%">
                                    <div>
                                        <a href="./index.php?page=buyitnow&product_id=<?php echo $row['id']; ?>"><button type="button" class="btn btn-primary">Buy It Now</button></a>
                                        <!-- <a href="./index.php?page=addtocart&product_id=<?php echo $row['id']; ?>"><button type="button" class="btn btn-primary">Add to cart</button></a> -->
                                    </div>
                                </td>
                                       <?php } ?>
                                    </tr>
                                
                                    <?php
                                }
                                ?>
                                    <!--</tbody>-->
                                    </table>
                                    </div>
                            </td>
                        </tr>
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
            $('#'+id).css({'display':'table-row'});
        }
        else{
            $('#'+id).css({'display':'none'});
        }
    }
</script>
