<?php login_header(); ?>

<script language="javascript">
    $(document).ready(function(){
        $('#Username').focus();
    });
</script>
<style type="text/css">
    #right-panel {
        float               :   right;
        width               :   40%;
    }
</style>

<div id="right-panel">
    <div class="login-box">
        <div class="login-box-body">
            <p class="login-box-msg">Sign in to start</p>
            <div class="form-group has-feedback">
                <input type="text" class="user-input form-control" placeholder="Username" id="Username">
                <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
            </div>
            <div class="form-group has-feedback">
                <input type="password" class="user-input form-control" placeholder="Password" id="Password">
                <span class="glyphicon glyphicon-lock form-control-feedback"></span>
                <label id="Error" class="text-center" hidden></label>
            </div>
            <div class="row">
                <div class="col-xs-4">
                    <button type="submit" id="submit" class="btn btn-primary btn-block btn-flat">Sign In</button>
                </div>
            </div>
        </div>
    </div>
</div>

<script language="javascript">
    $('#submit').on('click', function(){
        $.ajax({
            url			:	"<?php echo base_url() ?>login/service/authenticate/sign_in",
            type 		:	"POST",
            data 		:	
                    {
                        Username	:	$("#Username").val(),
                        Password    :   $("#Password").val()
                    }
        }).always(function (e) {
            if (e != "") {
                var a = jQuery.parseJSON( e );
                if (a.has_error == true) {
                    $.each($('.user-input'), function() {
                        $(this).parent('div').addClass('has-error');
                    });
                    $('#Error').removeAttr('hidden');
                    $('#Error').html('</br>' + a.error_message);
                }
                else if(a.password_verified == true){
                    window.location = "<?php echo base_url() ?>login/retype_password";
                }
                else {
                    window.location = "<?php echo base_url() ?>treasurers/applicant_search";
                }
   
            }
        });
    });
</script>

<?php login_footer(); ?>