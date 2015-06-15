<script>
    $(function() {
        $("#login-error-banner").hide();
<?php
if (isset($_SESSION ['user_login_error']) && $_SESSION ['user_login_error'] == 1)
{
    ?>
            $("#login-banner").hide();
            $("#login-error-banner").show();
            $("#login-error-banner").effect("shake");
            setTimeout(function() {
                $("#login-error-banner").hide('blind', {}, 500);
                $("#login-banner").show('blind', {}, 500)
            }, 2500);
    <?php
    $_SESSION ['user_login_error'] = 0;
}
?>
    });
</script>

<div class="row" id="login-banner">
    <div class="col-lg-4"></div>
    <div class="col-lg-4">
        <div class="alert alert-success alert-dismissable">
            <h3>
                <b>Login</b>
            </h3>
        </div>
    </div>
    <div class="col-lg-4"></div>
</div>
<div class="row" id="login-error-banner">
    <div class="col-lg-4"></div>
    <div class="col-lg-4">
        <div class="alert alert-danger alert-dismissable">
            <h3>
                <b>Login :</b> Error occurred !!!
            </h3>
        </div>
    </div>
    <div class="col-lg-4"></div>
</div>
<!-- /.row -->

<div class="row">

    <div class="col-lg-4"></div>

    <div class="col-lg-4">
        <form role="form" name="frmLogin" id="frmLogin" action="index.php" method="post">
            <div class="panel panel-info">
                <div class="panel-heading">
                    <div class="row">

                        <div class="col-xs-12">
                            <div class="form-group"></div>
                        </div>
                        <div class="col-xs-4">
                            <div class="form-group">
                                <label>Username</label>
                            </div>
                        </div>
                        <div class="col-xs-7">
                            <div class="form-group">
                                <input autofocus required="required" class="form-control" placeholder="Enter Username" name="username" value="<?php if (isset($_POST['username']) && $_POST['username'] != '')
{
    echo $_POST['username'];
} ?>">
                            </div>
                        </div>

                        <div class="col-xs-4">
                            <div class="form-group">
                                <label>Password</label>
                            </div>
                        </div>
                        <div class="col-xs-7">
                            <div class="form-group">
                                <input required="required" type="password" class="form-control" placeholder="Enter Password" name="password" value="<?php if (isset($_POST['password']) && $_POST['password'] != '')
{
    echo $_POST['password'];
} ?>">
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
                        <div class="col-xs-8 text-left">
                            <button type="submit" class="btn btn-info" name="btnLoginSubmit">Sign In</button>
                            &nbsp;
                            <button type="button" class="btn btn-info" id="btnSignup">Sign Up</button>
                        </div>
                    </div>
                </div>
            </div>
            <input type="hidden" name="hiddenRedirect" value="<?php if (isset($_GET['page']) && $_GET['page'] != '')
{
    echo $_GET['page'];
} ?>" >
        </form>
    </div>
</div>
<script>

$(document).ready(function(){
    $('#btnSignup').on('click',function(){
        window.location.href = "index.php?page=registration";
    });
})
</script>

