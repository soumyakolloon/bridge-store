<?php print_r($user_details); exit; ?>
<div>
    <p>Hi, <?php echo $username; ?></p>
    <p>Thank you for buying the product. Please login and download the purchsed product. Your Username: </br/> <?php echo $fullname; ?> </p>
    <p>You can download product by clicking on below link</p>
    <a href="<?php echo 'http://'.$download_link; ?>"> <?php echo $download_link; ?></a>
    </p>
    <p>Best Regards,<br/>Bridge Team.</p>
    
</div>

