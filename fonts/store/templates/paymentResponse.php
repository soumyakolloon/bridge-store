<?php
/**
 * @project Bridge shoppingcart
 * Payment response template
 *
 */

?> 
<div class="row">
<div class="col-lg-12">
    <div class="panel panel-primary">
        <div class="panel-heading">
            <h3 class="panel-title">Payment: <?php
                if ($payment_status == 'error')
                {
                    echo 'Error Occured';
                }
                else if ($payment_status == 'cancel')
                {
                    echo 'Cancelled';
                }
                else
                {
                    echo 'Success';
                }
                ?>  </h3>
        </div>
        <div class="panel-body">
            <div class="row">
                <div class="col-lg-8" style="display: inline">
                    <div class="row">
                            <?php echo $message; ?>
                        <p>
                        <?php
                        echo (isset($_SESSION['payment_error_detail']) && $payment_status == 'error') ? urldecode($_SESSION['payment_error_detail']) : '';
                        ?>
                        </p>
                        <p><button type="submit" name="submitbutt" class="btn btn-primary" onclick="javascript:window.location.href='<?php echo get_base_url(); ?>'">Continue Shopping</button>
                        <button type="submit" name="submitbutt" class="btn btn-primary" onclick="javascript:window.location.href='<?php echo get_base_url(); ?>index.php?page=payment_history'">View Purchase History</button></p>
                        </p>
                    </div>               
                </div>
            </div>
        </div>
    </div>
</div>
    
</div>    
<!--</div>-->
</div>