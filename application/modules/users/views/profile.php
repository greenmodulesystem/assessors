<?php main_header(); ?>
<?php //error_reporting(0); ?>
<?php sidebar('user'); ?>
<?php
$user = $_SESSION['User_details'];
$user_modules = $_SESSION['User_modules'];
?>

    <div class="content-wrapper">
        <section class="content-header">
            <h1>&nbsp;</h1>
            <ol class="breadcrumb">
                <li><a href="<?php  echo base_url() ?>dashboard"><i class="fa fa-dashboard"></i> Dashboard</a></li>
                <li class="active">User Profile</li>
            </ol>
        </section>
        <section class="content">

            <div class="row">
                <div class="col-md-12">
                    <div class="box">
                        <div class="box-header with-border">
                            <h3 class="box-title"><i class="fa fa-user"></i> User Profile</h3>
                        </div>
                        <div class="box-body">

                            <div class="row form-group">
                                <div class="col-md-3">
                                    <label>Position</label><br />
                                    <?php echo $user->Position; ?>
                                </div>
                            </div>
                            <div class="row form-group">
                                <div class="col-md-3">
                                    <label>First Name</label><br />
                                    <?php echo $user->First_name ?>
                                </div>
                                <div class="col-md-9">
                                    <label>Last Name</label><br />
                                    <?php echo $user->Last_name ?>
                                </div>
                            </div>
                            <div class="row form-group">
                                <div class="col-md-3">
                                <label>Email Address</label><br />
                                    <?php echo $user->Username ?>
                                </div>
                                <div class="col-md-3">
                                    <label>Password</label>
                                    <input type="password" placeholder="Password" id="Password" class="input-field-password form-control input-sm" data-field="Password" />
                                </div>
                            </div>

                        </div>

                        <div class="box-footer">
                            <button id="change-password" class="btn btn-danger btn-sm"><i class="fa fa-lock"></i> Change Password</button>
                            <input type="hidden" id="ID" value="<?php echo $user->ID ?>">
                        </div>

                    </div>
                </div>

            </div>

        </section>
    </div>

    <script language="javascript">
        $('#change-password').on('click', function(){
            $.ajax({
				url			:	"<?php echo base_url() ?>users/service/users-service/change_password",
                type 		:	"POST",
                dataType    :   "JSON",
				data 		:	{
                        ID	            :   $('#ID').val(),
                        password        :   $('#Password').val()
					}
			}).always(function (e) {
				//todo
			});
        });
    </script>

<?php main_footer(); ?>