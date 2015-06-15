<?php
/**
 * @project Bridge shoppingcart
 * Home page template
 *
 */
?>

<div class="row">
    <?php if (isset($home_page) && $home_page)
    { ?>
        <div class="col-lg-12">
            <h1>User Front End <small></small></h1>

            <?php if(!empty($msg)){ ?>
            <div class="alert alert-success alert-dismissable">
                <?php echo $msg; ?>
            </div>
            <?php } ?>
            <div class="alert alert-success alert-dismissable">
                This is the user home page
            </div>
        </div>
    <?php } ?>

<?php include_once 'products-view.php'; ?>
</div>