<div class="row" id="login-banner">
    <div class="col-lg-4"></div>
    <div class="col-lg-4">
        <div class="alert alert-success alert-dismissable">
            <h3>
                <b>Change Password</b>
            </h3>
        </div>
    </div>
    <div class="col-lg-4"></div>
</div>

<div class="row">
<div class="row" id="login-error-banner">
    <div class="col-lg-4"></div>
    <div class="col-lg-4">
        
        <div class="alert">  
			
     <?php if(isset($_GET['msg']) && $_GET['msg']==1) {
			  
			  echo 'Password changed successfully.';
			}
			else if(isset($_GET['msg']) && ($_GET['msg']==3 || $_GET['msg']==2))
			{
				echo 'Old password that you entered is wrong.';
			}  
                        else if(isset($_GET['msg']) && $_GET['msg']==4)
                        {
                            echo "Invalid token. Please Reset the password again to get New password";
                        }
			?>
		</div>
        
        <div class="alert alert-danger alert-dismissable" id="error_msg" style="display:none;">
          
        </div>
    </div>
    <div class="col-lg-4"></div>
</div>
    <div class="col-lg-4"></div>
    <div class="col-lg-4">
        <form role="form" name="frmforgotpassword" id="frmforgotpassword" action="index.php?page=change-password&email-token=<?php $_GET['email-token']; ?>" onSubmit="return resetFormvalidate();" method="post" enctype="multipart/form-data">
			<div class="panel panel-info">
                             <div class="panel-heading">
					
					
					 <div class="row">
							<div class="col-xs-4">
                            <div class="form-group">
                                <label>Old Password</label>
                            </div>
                        </div>
                        
                        <div class="col-xs-7">
                            <div class="form-group">
                                <input autofocus class="form-control" type='password' placeholder="Enter Old Password" name="old-pwd" id="old-pwd" value="">
                            </div>
                        </div>
                        
		</div>
                    <div class="row">
							<div class="col-xs-4">
                            <div class="form-group">
                                <label>New Password</label>
                            </div>
                        </div>
                        
                        <div class="col-xs-7">
                            <div class="form-group">
                                <input autofocus class="form-control" type='password' placeholder="Enter Password" name="new-pwd" id="new-pwd" value="">
                            </div>
                        </div>
                        
                       
                                               
                        
                        <div class="col-xs-4">
                            <div class="form-group">
                                <label>Confirm Password</label>
                            </div>
                        </div>
                        <div class="col-xs-7">
                            <div class="form-group">
                                <input type="password" id="cnf-pwd" class="form-control" placeholder="Re-enter password" name="cnf-pwd" value="">
                            </div>
                        </div>
                        </div>
                        
                        <div class="panel-footer announcement-bottom">
	                <div class="row">
                  
                        <div class="col-xs-8 text-left">
                            <input type='hidden' id='email-token' name='email-token' value="<?php echo $_GET['email-token']; ?>">
                            <input type="submit" class="btn btn-info" name="btnresetpawwsordSubmit" value="Change Password">
                               
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
	
	 var old_pwd = $('#old-pwd').val();
	 var n_pwd = $('#new-pwd').val();
	 var cnf_pwd = $('#cnf-pwd').val();
	
	if(!requireValidation(old_pwd))
	{
		$('#error_msg').show();
		$('#error_msg').html('<p>Old Password cannot be empty<p>');
		return false;
	}
	
	if(!requireValidation(n_pwd))
	{
		$('#error_msg').show();
		$('#error_msg').html('<p>New Password cannot be empty<p>');
		return false;
	}
	
	if(!requireValidation(cnf_pwd))
	{
		$('#error_msg').show();
		$('#error_msg').html('<p>Confirm Password cannot be empty<p>');
		return false;
	}
	
	
	else if(!password_match(n_pwd, cnf_pwd))
	{
		$('#error_msg').show();
		$('#error_msg').html('<p>Password does not match</p>');
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


function password_match(id, cnfid)
{
if(id==cnfid)
return true;
else
return false;
}

 
 </script>
 
 
 
 
