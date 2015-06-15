<div class="row" id="login-banner">
    <div class="col-lg-4"></div>
    <div class="col-lg-4">
        <div class="alert alert-success alert-dismissable">
            <h3>
                <b>Forgot Password</b>
            </h3>
        </div>
    </div>
    <div class="col-lg-4"></div>
</div>

<div class="row">
<div class="row" id="login-error-banner">
    <div class="col-lg-4"></div>
    <div class="col-lg-4">
        
        <div class="alert">   <?php if(isset($_GET['msg']) && $_GET['msg']==1) {
			  
			  echo 'Password reset successfully. Please check your email to get the new password.';
			}
			else if(isset($_GET['msg']) && $_GET['msg']>1)
			{
				echo 'Email is not registered with Bridge Store.';
			}  
			?></div>
        
        <div class="alert alert-danger alert-dismissable" id="error_msg" style="display:none;">
                     
           
        </div>
    </div>
    <div class="col-lg-4"></div>
</div>
    <div class="col-lg-4"></div>
    <div class="col-lg-4">
        <form role="form" name="frmforgotpassword" id="frmforgotpassword" action="index.php?page=forgot-password" onSubmit="return resetFormvalidate();" method="post" enctype="multipart/form-data">
			<div class="panel panel-info">
                <div class="panel-heading">
					
					
                    <div class="row">
							<!-- <div class="col-xs-4">
                            <div class="form-group">
                                <label>Username</label>
                            </div>
                        </div>
                        
                        <div class="col-xs-7">
                            <div class="form-group">
                                <input autofocus class="form-control" placeholder="Enter Username" name="username" id="username" value="">
                            </div>
                        </div>
                        
                        <div class="col-xs-4" style="float: right;margin-bottom: -12px;width: 500px;left: 101px;">
                            <div class="form-group">
                                <label>OR</label>
                            </div>
                        </div>-->
                        
                                               
                        
                        <div class="col-xs-4">
                            <div class="form-group">
                                <label>Email</label>
                            </div>
                        </div>
                        <div class="col-xs-7">
                            <div class="form-group">
                                <input type="text" id="email" class="form-control" placeholder="Enter Email" name="email" value="">
                            </div>
                        </div>
                        </div>
                        
                        <div class="panel-footer announcement-bottom">
						<div class="row">
                  
                        <div class="col-xs-8 text-left">
                            <input type="submit" class="btn btn-info" name="btnresetpawwsordSubmit" value="Reset Passoword">
                                  
                        </div>
                    </div>
                </div>
				</div>
                        
                        
					</div>
				</div>
			</div>
			
		</form>
			
		</div>
 </div>
 
 <script>
 
 function resetFormvalidate()
 {
	
	 var email = $('#email').val();
	
	if(!requireValidation(email))
	{
		$('#error_msg').show();
		$('#error_msg').html('<p>Please enter email<p>');
		return false;
	}
	else if(requireValidation(email) && !validateEmail(email))
	{
		$('#error_msg').show();
		$('#error_msg').html('<p>Please enter a valid email</p>');
		return false;
	}
	else
	{
		$('#error_msg').hide();
		return true;
	}
	
	 
 }
 
 
function requireValidation(id)
{
if(id=='')
return false;
else
return true;
}

function validateEmail(email) 
{
    var re = /\S+@\S+\.\S+/;
    return re.test(email);
}
 
 </script>
 
 
 
 
