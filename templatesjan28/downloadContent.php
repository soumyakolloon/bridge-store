<?php
/**
 * @project Bridge shoppingcart
 * Payment response template
 *
 */
?> 
<div class="col-lg-12">
    <div class="panel panel-primary">
        <div class="panel-heading">
            <h3 class="panel-title">File downloading: </h3>
        </div>
        <div class="panel-body">
            <div class="row">
                <div class="col-lg-8" style="display: inline">
                    <div class="row">
                        <p>File download page</p>
                    </div>
                    <div id="token" style="display:none"><?php echo $token;?></div>
                    <div id="message">The download will start automatically in <span id="seconds">3</span>s. If the download not starts,
                        <a href="<?php echo 'download.php?token='. $token;?>">click here</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">

    $(document).ready(function() {
        location.href = "download.php?token="+$('#token').html();
        /*var a = setInterval(function() {            
            var c = $('#seconds').text() - 1;            
            $('#seconds').text(c);
        }, 1000);*/

        /*setTimeout(function() {
            $('#message').html('');
            location.href = "download.php";
            //clearInterval(a);
        }, 3000);  */
    });
</script>