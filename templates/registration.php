<?php
php_session_start();

if (isset($_SESSION ['user_registration_post']) && count($_SESSION ['user_registration_post']) > 0)
{
    $post = $_SESSION ['user_registration_post'];
}
?>
<script>
    $(function() {
        $("#registration-error-banner").hide();
<?php
if (isset($_SESSION ['user_registration_password_error']) && $_SESSION ['user_registration_password_error'] == 1)
{
    ?>
            $("#registration-banner").hide();
            $("#registration-error-banner").show();
            $("#registration-error-banner").html('<div class="col-lg-4"></div><div class="col-lg-4"><div class="alert alert-danger alert-dismissable"><h3><b>Registration :</b> Password not matched !!!</h3></div></div><div class="col-lg-4"></div>');
            $("#registration-error-banner").effect("shake");
            setTimeout(function() {
                $("#registration-error-banner").hide('blind', {}, 500);
                $("#registration-banner").show('blind', {}, 500)
            }, 2500);
    <?php
}
/*if (isset($_SESSION ['user_registration_username_error']) && $_SESSION ['user_registration_username_error'] == 1)
{
    ?>
            $("#registration-banner").hide();
            $("#registration-error-banner").show();
            $("#registration-error-banner").html('<div class="col-lg-4"></div><div class="col-lg-4"><div class="alert alert-danger alert-dismissable"><h3><b>Registration :</b> Username exist !!!</h3></div></div><div class="col-lg-4"></div>');
            $("#registration-error-banner").effect("shake");
            setTimeout(function() {
                $("#registration-error-banner").hide('blind', {}, 500);
                $("#registration-banner").show('blind', {}, 500)
            }, 2500);
    <?php
}*/
if (isset($_SESSION ['user_registration_error']) && $_SESSION ['user_registration_error'] == 1)
{
    ?>
            $("#registration-banner").hide();
            $("#registration-error-banner").show();
            //$("#registration-error-banner").effect("shake");
            // setTimeout(function() {
            //     $("#registration-error-banner").hide('blind', {}, 500);
            //     $("#registration-banner").show('blind', {}, 500)
            // }, 2500);
    <?php
}
?>

        $("#frmRegistration").submit(function() {
            var password = $.trim($('#password').val());
            var confirmpassword = $.trim($('#confirmpassword').val());

            if (password != '' && confirmpassword != '') {
                if (password != confirmpassword) {
                    $("#registration-banner").hide();
                    $("#registration-error-banner").show();
                    $("#registration-error-banner").html('<div class="col-lg-4"></div><div class="col-lg-4"><div class="alert alert-danger alert-dismissable"><h3><b>Registration :</b> Password not matched !!!</h3></div></div><div class="col-lg-4"></div>');
                    // $("#registration-error-banner").effect("shake");
                    // setTimeout(function() {
                    //     $("#registration-error-banner").hide('blind', {}, 500);
                    //     $("#registration-banner").show('blind', {}, 500)
                    // }, 2500);
                    return false;
                } else {
                    $("#frmRegistration").submit();
                }
            }
        });


    });
</script>

<div class="row" id="registration-banner">
    <div class="col-lg-4"></div>
    <div class="col-lg-4">
        <div class="alert alert-success alert-dismissable">
            <h3>
                <b>Registration</b>
            </h3>
        </div>
    </div>
    <div class="col-lg-4"></div>
</div>
<div class="row" id="registration-error-banner">
    <div class="col-lg-4"></div>
    <div class="col-lg-4">
        <div class="alert alert-danger alert-dismissable">
            <h3>
                <b>Registration :</b> Error occurred !!!
            </h3>
        </div>
    </div>
    <div class="col-lg-4"></div>
</div>
<!-- /.row -->

<div class="row">

    <div class="col-lg-4"></div>

    <div class="col-lg-4">
        <form role="form" name="frmRegistration" id="frmRegistration" action="post-handler.php?action=REGISTRATION" method="post">
            <div class="panel panel-info">
                <div class="panel-heading">
                    <div class="row">

                        <div class="col-xs-12">
                            <div class="form-group"></div>
                        </div>
                        <div class="col-xs-4">
                            <div class="form-group">
                                <label>First name</label>
                            </div>
                        </div>
                        <div class="col-xs-7">
                            <div class="form-group">
                                <input autofocus required="required" class="form-control" placeholder="Enter First name" name="firstname" value="<?php if (isset($post['firstname']) && $post['firstname'] != '')
{
    echo $post['firstname'];
} ?>">
                            </div>
                        </div>
                        <div class="col-xs-4">
                            <div class="form-group">
                                <label>Last name</label>
                            </div>
                        </div>
                        <div class="col-xs-7">
                            <div class="form-group">
                                <input class="form-control" placeholder="Enter Last name" name="lastname" value="<?php if (isset($post['lastname']) && $post['lastname'] != '')
{
    echo $post['lastname'];
} ?>">
                            </div>
                        </div>
                        <div class="col-xs-4">
                            <div class="form-group">
                                <label>Email</label>
                            </div>
                        </div>
                        <div class="col-xs-7">
                            <div class="form-group">
                                 <input autofocus required="required"  type="email" class="form-control" placeholder="Enter Email" name="email" value="<?php if (isset($post['email']) && $post['email'] != '')
{
    echo $post['email'];
} ?>">
                            </div>
                        </div>
                       
                       <!-- <div class="col-xs-4">
                            <div class="form-group">
                                <label>Username</label>
                            </div>
                        </div>
                        <div class="col-xs-7">
                            <div class="form-group">
                                <input required="required" class="form-control" placeholder="Enter Username" name="username" value="<?php //if (isset($post['username']) && $post['username'] != '')
//{
  //  echo $post['username'];
//} ?>">
                            </div>
                        </div>-->
                        
                        <div class="col-xs-4">
                            <div class="form-group">
                                <label>Password</label>
                            </div>
                        </div>
                        <div class="col-xs-7">
                            <div class="form-group">
                                <input required="required" type="password" class="form-control" placeholder="Enter Password" name="password" id="password" >
                            </div>
                        </div>
                        <div class="col-xs-4">
                            <div class="form-group">
                                <label>Confirm Password</label>
                            </div>
                        </div>
                        <div class="col-xs-7">
                            <div class="form-group">
                                <input required="required" type="password" class="form-control" placeholder="Enter Confirm password" name="confirmpassword" id="confirmpassword" >
                            </div>
                        </div>
                        <div class="col-xs-12">
                            <div class="form-group"></div>
                        </div>

                    </div>
                </div>

                <div class="panel-footer announcement-bottom">
                    <div class="row">
                        <div class="col-xs-4"></div>
                        <div class="col-xs-1 text-right">
                            <button type="submit" class="btn btn-info" name="btnRegisterSubmit">Register</button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
