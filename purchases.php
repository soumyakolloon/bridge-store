<?php
/**
 * @project Bridge shoppingcart
 * Admin payment history page template
 */

$per_page     = 4;
$page_deatils = $paginator->setPagination($per_page, $transactions);
$page         = $page_deatils['page'];
$show_page    = $page_deatils['showPage'];
$total_pages  = $page_deatils['totalPages'];
$start        = $page_deatils['start'];
$end          = $page_deatils['end'];

$total_results = count($transactions);
?>
<div class="row">
    <div class="col-lg-12">
        <h1>Purchase History <small></small></h1>
        
        <div>
            <ul class="pager">
                <?php
                $reload        = $_SERVER['PHP_SELF'] . "?page=purchases&amp;tpages=" . $total_pages;
                if ($total_pages > 1)
                {
                    echo $paginator->paginate($reload, $show_page, $total_pages);
                }
                ?>
            </ul>
        </div>

        <?php 
        
        if ($transactions)
        { 
            ?>
            <div class="table-responsive" id="category-list">
                <table class="table table-bordered table-hover table-striped tablesorter">
                    <thead>
                        <tr>
                            <th>User <i class="fa fa-sort"></i></th>
                            <th>Email <i class="fa fa-sort"></i></th>
                            <th>Transaction ID <i class="fa fa-sort"></i></th>
                            <th>Price <i class="fa fa-sort"></i></th>
                            <th>Date <i class="fa fa-sort"></i></th>
                            <th>Status <i class="fa"></i></th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
//                        foreach ($transactions as $payment)
                        for ($i = $start; $i < $end; $i++)
                        {   
                            if ($i == $total_results)
                            {
                                break;
                            }
                            $payment = $transactions[$i];
                            
                           
                        ?>
                            <tr>
                                <td><?php echo $payment['transaction_user']; ?></td>
                                <td><?php echo $payment['email']; ?></td>
                                <td><?php echo $payment['transaction_id']; ?></td>
                                <td><?php echo '$ '. $payment['total_price']; ?></td>
                                <td><?php echo date("d M Y", strtotime($payment['date_time'])); ?></td>
                                <td><?php echo $payment['payment_status']; ?></td>
                                <td>
                                    <input type="button" name="view_prod" class="btn btn-primary" value="View Products" onclick="javascript:displayDetails('<?php echo $payment['purchase_id']; ?>')">
                                </td>
                            </tr>
                        
                            <tr class="prod_details" id="<?php echo $payment['purchase_id']; ?>" style="display: none;">
                                <td colspan="9">
                                    <div>
                                        <table class="table table-bordered table-hover table-striped tablesorter">
                                        <tr>
                                            <th>Image</th>
                                            <th>Name </th>
                                            <th>Description </th>
                                            <th>Price </th>
                                            
                                            <th>Expiry </th>
                                            <th>Downloads</th>
                                        </tr>

                                        <?php
                                        foreach ($payment['products'] as $row)
                                        {
                                            ?>
                                        <tr>
                                            <td style="width: 1%"><a href="./index.php?page=product-detail&product_id=<?php echo $row['product_id']; ?>"><img src="uploads/<?php echo $row['image_path']; ?>" width="100" height="100" /></a></td>
                                            <td style="width: 15%"><a href="./index.php?page=product-detail&product_id=<?php echo $row['product_id']; ?>"><?php echo $row['name']; ?></a></td>
                                            <td style="width: 40%"><?php echo $row['description']; ?></td>
                                            <td style="width: 19%"><?php echo '$ '. $row['price']; ?></td>
                                            <td style="width: 15%"><?php echo (!empty($row['expires_on'])) ? date("d M Y h:i A", strtotime($row['expires_on'])) : ''; ?></td>
                                            <td style="width: 10%"><?php echo $row['download_count']; ?></td>
                                        </tr>

                                        <?php
                                        }
                                        ?>
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
            echo '<div class="table-responsive" id="category-list">No Purchase Record Found !</div>';
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