<?php main_header(); ?>
<?php error_reporting(0); ?>
<?php sidebar('user', 'form'); ?>
<?php $user_account = (@$model->ID == 0) ? "<i class='fa fa-file-o'></i> New User Account" : "<i class='fa fa-user'></i> User Account"; ?>

    <div class="content-wrapper">
        <section class="content-header">
            <h1>&nbsp;</h1>
            <ol class="breadcrumb">
                <li><a href="<?php  echo base_url() ?>dashboard"><i class="fa fa-dashboard"></i> Dashboard</a></li>
                <li class="active"><?php echo $user_account ?></li>
            </ol>
        </section>
        <section class="content">
            
            <div class="row">
                <div class="col-md-12">
                    <div class="box">
                        <div class="box-header with-border">
                            <h3 class="box-title"><?php echo $user_account ?></h3>
                        </div>
                        <div class="box-body">

                            <div class="row form-group">
                                <div class="col-md-3">
                                    <label>Position</label>
                                    <select id="Position_ID" class="input-field form-control input-sm" data-field="Position_ID">
                                        <?php foreach($position as $item){ ?>
                                            <option <?php echo @select_active($model->Position_ID, $item->ID) ?> value="<?php echo $item->ID ?>"><?php echo $item->Position ?></option>
                                        <?php }?>
                                    </select>
                                </div>
                            </div>
                            <div class="row form-group">
                                <div class="col-md-3">
                                    <label>First Name</label>
                                    <input placeholder="First Name" value="<?php echo $model->First_name ?>" type="text" value="" id="First_name" class="input-field form-control input-sm" data-field="First_name" />
                                </div>
                                <div class="col-md-3">
                                    <label>Last Name</label>
                                    <input placeholder="Last Name" value="<?php echo $model->Last_name ?>" type="text" value="" id="Last_name" class="input-field form-control input-sm" data-field="Last_name" />
                                </div>
                                <div class="col-md-4">
                                    <label>Email Address</label>
                                    <input placeholder="Email Address" <?php echo disable_field($model->ID) ?> value="<?php echo $model->Username ?>" type="text" value="" id="Username" class="input-field form-control input-sm" data-field="Username" />
                                </div>
                            </div>
                        </div>

                        <div class="box-footer">
                            <?php if (!empty($model->ID)) { ?>
                                <input type="hidden" class="input-field" id="ID" data-field="ID" value="<?php echo $model->ID ?>" />
                                <button class="btn btn-primary btn-sm" id="update">
                                    <i class="fa fa-plus-square"></i>
                                    Save</button>
                            <?php } else { ?>
                                <button class="btn btn-primary btn-sm" id="submit">
                                    <i class="fa fa-plus-square"></i>
                                    Save</button>
                            <?php } ?>
                            <button class="btn btn-warning btn-sm new-user"><i class="fa fa-file"></i> New</button>
                            <a href="<?php echo base_url() ?>users" class="btn btn-default btn-sm">
                                <i class="fa fa-close"></i> Close</a>
                            <?php if (!empty($model->ID)) { ?>
                                <button id="reset-password" class="btn btn-danger btn-sm">
                                    <i class="fa fa-lock"></i> Reset Password</button>
                            <?php } ?>
                        </div>

                    </div>
                </div>
            </div>
            
            <?php if (!empty($model->ID)) { ?>
            <div class="row">
                <div class="col-md-12">
                    <div class="box">
                        <div class="box-header with-border">
                            <h3 class="box-title"><i class='fa fa-tasks'></i> System Modules</h3>
                        </div>
                        <div class="box-body">
                            
                            <div class="row form-group">
                                <div class="col-md-12">
                                    
                                    <table class="table table-striped">
                                        <tbody>
                                            <?php foreach($modules as $item): ?>
                                                <tr><td width="100%" colspan="4"><?php echo $item->Module_name ?></td></tr>
                                                <tr>
                                                    <td width="20%">
                                                        <input 
                                                        <?php echo user_module($user_modules, 
                                                            $item->ID,
                                                            'Restrict_access') ?>
                                                            name="permission-<?php echo $item->ID ?>" id="restrict-access-<?php echo $item->ID ?>" data-id="<?php echo $item->ID ?>" type="radio" class="permission" value="<?php echo $item->ID ?>">
                                                        Restrict Access
                                                    </td>
                                                    <td width="20%">
                                                        <input 
                                                        <?php echo user_module($user_modules, 
                                                            $item->ID,
                                                            'Full_control') ?>
                                                            name="permission-<?php echo $item->ID ?>" id="full-control-<?php echo $item->ID ?>" data-id="<?php echo $item->ID ?>"  type="radio" class="permission" value="<?php echo $item->ID ?>">
                                                        Full Control
                                                    </td>
                                                    <td width="20%">
                                                        <input 
                                                        <?php echo user_module($user_modules, 
                                                            $item->ID,
                                                            'Read_only') ?>
                                                            name="permission-<?php echo $item->ID ?>" id="read-only-<?php echo $item->ID ?>" data-id="<?php echo $item->ID ?>"  type="radio" class="permission" value="<?php echo $item->ID ?>">
                                                        Read Only
                                                    </td>
                                                    <td width="20%">
                                                        <input
                                                        <?php echo user_module($user_modules, 
                                                            $item->ID,
                                                            'Write_only') ?> 
                                                            name="permission-<?php echo $item->ID ?>" id="write-only-<?php echo $item->ID ?>" data-id="<?php echo $item->ID ?>"  type="radio" class="permission" value="<?php echo $item->ID ?>">
                                                        Write Only
                                                    </td>                    
                                                </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>

                                </div>
                            </div>
                           
                        </div>

                        <div class="box-footer">
                            <button class="btn btn-primary btn-sm" id="save-modules">
                                    <i class="fa fa-plus-square"></i>
                                    Save</button>
                            <button class="btn btn-warning btn-sm new-user"><i class="fa fa-file"></i> New</button>
                            <a href="<?php echo base_url() ?>users" class="btn btn-default btn-sm">
                                <i class="fa fa-close"></i> Close</a>
                        </div>

                    </div>
                </div>
            </div>
            <?php } ?>

        </section>
    </div>
    
    <script language="javascript">
        $('.new-user').on('click', function(){
            window.location = "<?php echo base_url() ?>users/new-user";
        });

        $('#submit').gmSavePost({
            url         :   "<?php echo base_url() ?>users/service/users-service/save",
            field       :   "field",
            selector    :   ".input-field",
            redirect    :   "<?php echo base_url() ?>users/update-user",
            return_id   :   true,
            alert       :   true,
        });

        $('#update').gmSavePost({
            url         :   "<?php echo base_url() ?>users/service/users-service/save",
            field       :   "field",
            selector    :   ".input-field",
            alert       :   true,
        });

        $('#save-modules').on('click', function(){
            var module_ID = [];
            var restrict_access = [];
            var full_control = [];
            var read_only = [];
            var write_only = []

            $('.permission:checked').each(function(){
                var id = $(this).val();
                
                $(this).each(function(){
                    module_ID.push(id);
                });

                $('#restrict-access-' + id + ':checked').each(function(){
                    restrict_access.push(id);
                });

                $('#full-control-' + id + ':checked').each(function(){
                    full_control.push(id);
                });

                $('#read-only-' + id + ':checked').each(function(){
                    read_only.push(id);
                });

                $('#write-only-' + id + ':checked').each(function(){
                    write_only.push(id);
                });
            });       

            $.ajax({
				url			:	"<?php echo base_url() ?>users/service/users-service/save_module",
                type 		:	"POST",
                dataType    :   "JSON",
				data 		:	{
                    Module_ID           :   module_ID,
                    Restrict_access     :   restrict_access,
                    Full_control        :   full_control,
                    Read_only           :   read_only,
                    Write_only          :   write_only,         
                    User_ID             :   $('#ID').val()
				}
			}).always(function (e) {
				//todo
			});
        });

        $('#reset-password').on('click', function(){
            $.ajax({
				url			:	"<?php echo base_url() ?>users/service/users-service/reset_password",
                type 		:	"POST",
                dataType    :   "JSON",
				data 		:	{
                        ID	         :  $('#ID').val()
					}
			}).always(function (e) {
				//todo
			});
        });
    </script>

<?php main_footer(); ?>