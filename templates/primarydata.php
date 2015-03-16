<link href="css/modalPopLite.css" rel="stylesheet" type="text/css" /> 
<script type="text/javascript" src="js/modalPopLite.min.js"></script> 


<script type="text/javascript"> $(function () {    
 $('#popup-wrapper').modalPopLite({ 
    openButton: '#clicker', closeButton: '#close-btn' }); 
 }); 
</script> 

<div id="clicker">Click Me!</div> 
<div id="popup-wrapper" style="background-color: #ccc;">I am a popup box. Content can be anything. 

    <a id="close-btn" href="#">Close</a>

</div>