<?php
/**
* @project Bridge shoppingcart
* Product Detail Page template
*
*/
?>
<!-- add carousal slider and lightbox -->


<div class="row">
<div class="col-lg-12">
<div class="panel panel-primary">
<div class="panel-body">

<div class="col-lg-4">
<!-- <img src="uploads/<?php //echo $product['id'] . '_' . $product['image_path']; ?>" width="100" height="100" /> -->

<ul class="bxslider">
<?php

 foreach($prod_images as $imgdetails){ ?>
<li><a class="group1" href="uploads/<?php echo $imgdetails['image_path']; ?>"><img src="uploads/<?php echo $imgdetails['image_path']; ?>" width="100" height="100"/></a></li>
<?php } ?>
</ul>


</div>


<div class="col-lg-6">
<div class="prod-desc">
<?php echo $desc = $productArray[0]['description'];  ?>
</div>
<p><strong><?php echo '$ '. $productArray[0]['price']; ?></strong></p>
<div>
<a href="./index.php?page=buyitnow&product_id=<?php echo $productArray[0]['id']; ?>"><button type="button" class="btn btn-primary">Buy It Now</button></a>
<a href="./index.php?page=addtocart&product_id=<?php echo $productArray[0]['id']; ?>"><button type="button" class="btn btn-primary">Add to cart</button></a>
</div>
</div>

</div>


</div>

</div> </div>
<script>

$(document).ready(function(){

$('.bxslider').bxSlider({
minSlides: 2,
maxSlides: 2,
slideWidth: 105,
slideMargin: 10,
total:2,

});

$(".group1").colorbox({rel:'group1'});
});

</script>
