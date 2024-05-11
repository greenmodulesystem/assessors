<?php retype_header(); ?>
<?php
    $user =  $_SESSION['User_details_retype_password'];
    $modules = $_SESSION['User_modules_retype_password'];
?>
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
            <p class="login-box-msg">Please enter your new password:</p>

            <div class="form-group has-feedback">
                <input type="text" class="form-control" value="<?=$user->Username?>"placeholder="Username" disabled>
                <span class="glyphicon glyphicon-user form-control-feedback"></span>
            </div>
            <div class="form-group has-feedback">
                <input type="password" class="user-input form-control" placeholder="Password" id="Password">
                <span class="glyphicon glyphicon-lock form-control-feedback"></span>
            </div>
            <div class="form-group has-feedback">
                <input type="password" class="user-input form-control" placeholder="Retype Password" id="Retype_Password">
                <span class="glyphicon glyphicon-lock form-control-feedback"></span>
                <label id="Error" class="text-left" hidden></label>
            </div>
            <div class="row">
                <div class="col-xs-4">
                    <button type="submit" id="submit" class="btn btn-primary btn-block btn-flat">Submit</button>
                </div>
            </div>
        </div>

    </div>
</div>

<script language="javascript">
    $('#submit').on('click', function(){
        $.ajax({
			url			:	"<?php echo base_url() ?>login/service/authenticate/retype_password",
			type 		:	"POST",
			data 		:	
					{
                        Credential          :   '<?=$user->U_ID;?>',
                        Password	        :	$("#Password").val(),
                        Retype_Password     :   $("#Retype_Password").val()
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
                else {
                    window.location = "<?php echo base_url() ?>treasurers/applicant_search";
                }
            }
		});
    });
</script>

<?php retype_footer(); ?>