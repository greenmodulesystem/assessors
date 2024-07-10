<?php function login_header()
{
    $ci = &get_instance();
    // if (holy_macaroni() != mac_n_cheese() && holy_macaroni() != macaroni_soup()){ access_forbidden();exit(error_text());}
    defined('BASEPATH') or exit('No direct script access allowed');
?>
    <!DOCTYPE html>
    <html>

    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title><?php echo $ci->config->item('system_tab_name') ?> - Log in</title>
        <link rel="icon" href="<?php echo base_url() ?>assets/img/Logo_3.png">
        <!-- Tell the browser to be responsive to screen width -->
        <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
        <!-- Bootstrap 3.3.7 -->
        <link rel="stylesheet" href="<?php echo base_url() ?>assets/theme/bower_components/bootstrap/dist/css/bootstrap.min.css">
        <!-- Font Awesome -->
        <link rel="stylesheet" href="<?php echo base_url() ?>assets/theme/bower_components/font-awesome/css/font-awesome.min.css">
        <!-- Ionicons -->
        <link rel="stylesheet" href="<?php echo base_url() ?>assets/theme/bower_components/Ionicons/css/ionicons.min.css">
        <!-- Theme style -->
        <link rel="stylesheet" href="<?php echo base_url() ?>assets/theme/dist/css/AdminLTE.min.css">
        <!-- iCheck -->
        <link rel="stylesheet" href="<?php echo base_url() ?>assets/theme/plugins/iCheck/square/blue.css">
        <!-- jQuery -->
        <script language="javascript" src="<?php echo base_url() ?>assets/jquery-3.2.1.js"></script>
        <!-- Theme style -->
        <link rel="stylesheet" href="<?php echo base_url() ?>assets/theme/dist/css/AdminLTE.min.css">
        <!-- AdminLTE Skins. Choose a skin from the css/skins folder instead of downloading all of them to reduce the load. -->
        <link rel="stylesheet" href="<?php echo base_url() ?>assets/theme/dist/css/skins/_all-skins.min.css">
        <!-- Pace style -->
        <link rel="stylesheet" href="<?php echo base_url() ?>/assets/theme/plugins/pace/pace.min.css">
        <!-- Google Font -->
        <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
        <style type="text/css">
            body {
                overflow-y: hidden;
            }

            img.center {
                content:url("<?php echo base_url() ?>assets/img/Sagay_Logo.png");
            position: absolute;
            left: 1vw;
            top: 0vh;
            height: 115vh;
            opacity: .3;
                pointer-events: none;
            }

            .blis {
                position: absolute;
                left: 4.3vw;
                font-family: "Century Gothic";
                pointer-events: none;
            }

            #blis1 {
                top: 40vh;
                font-size: 10vh;
                font-weight: bold;
            }

            #blis2 {
                top: 49vh;
                font-size: 9vh;
            }
        </style>

        <head>

        <body class="hold-transition skin-green-light layout-top-nav">
            <div class="wrapper">

                <header class="main-header">
                    <nav class="navbar navbar-static-top">
                        <span class="logo" style="width:30vw;text-align:left">
                            <i class="fa fa-edit"></i>&ensp;<?php echo $ci->config->item('department_long') ?>
                        </span>
                    </nav>
                </header>

                <div class="content-wrapper">
                    <div class="container">
                        <img class="center">
                        <button data-target="#F11_modal" data-toggle="modal" data-keyboard="false" data-backdrop="static" style="display:none" id="Open_F11"></button>
                        <p class="blis" id="blis1">BUSINESS LICENSING</p>
                        <p class="blis" id="blis2">INFORMATION SYSTEM</p>
                        <div id="F11_modal" class="modal fade">
                            <div class="modal-dialog modal-sm">
                                <div class="modal-content">
                                    <div class="modal-body text-center">
                                        Please press "F11" to proceed.
                                    </div>
                                    <div class="modal-footer" style="display:none">
                                        <button data-dismiss="modal" id="Close_F11"></button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php
                }

                function permission_panel()
                {
                    ?>
                        <script language="javascript">
                            $(document).ready(function() {
                                $('#Username').focus();
                            });
                        </script>
                        <style type="text/css">
                            #center-panel {
                                float: right;
                                width: 40%;
                            }
                        </style>
                    </div>

                    <div id="user_modal" class="modal fade" tabindex="-1">

                        <div class="modal-body">
                            <div id="right-panel">
                                <div class="login-box">
                                    <div class="login-box-body">
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
                                        <h4 class="modal-title">
                                            <i class="fa fa-exclamation-circle"></i> User Permission Required
                                        </h4>
                                        <br>
                                        <div class="form-group has-feedback">
                                            <input type="email" value="" class="form-control input-sm verification-form" placeholder="Username" id="Username" data-field="Username" autofocus>
                                            <span class="glyphicon glyphicon-user form-control-feedback"></span>
                                        </div>
                                        <div class="form-group has-feedback">
                                            <input type="password" value="" class="form-control input-sm verification-form" placeholder="Password" id="Password" data-field="Password">
                                            <span class="glyphicon glyphicon-lock form-control-feedback"></span>
                                        </div>
                                        <div class="row">
                                            <div class="col-xs-4">
                                                <button type="submit" id="submit" class="btn btn-primary btn-block btn-flat">Confirm</button>
                                            </div>
                                            <div class="col-md-8">
                                                <div id="message"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>


                    </div>
                </div>
                <script src="<?php echo base_url() ?>assets/scripts/permission/permission.js"></script>

            <?php
                }

                function login_footer()
                {
            ?>

                <!-- jQuery 3 -->
                <script src="<?php echo base_url() ?>assets/theme/bower_components/jquery/dist/jquery.min.js"></script>
                <!-- Bootstrap 3.3.7 -->
                <script src="<?php echo base_url() ?>assets/theme/bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
                <!-- iCheck -->
                <script src="<?php echo base_url() ?>assets/theme/plugins/iCheck/icheck.min.js"></script>
                <script language="javascript">
                    $(function() {
                        $('input').iCheck({
                            checkboxClass: 'icheckbox_square-blue',
                            radioClass: 'iradio_square-blue',
                            increaseArea: '20%' /* optional */
                        });
                    });

                    // $(document).ready(function(){
                    //     $("#Open_F11").click();
                    // });

                    $(window).on('keydown', function(event) {
                        if (event.keyCode == 123) {
                            return false;
                        } else if (event.ctrlKey && event.shiftKey && event.keyCode == 73) {
                            return false; //Prevent from ctrl+shift+i
                        } else if (event.ctrlKey && event.keyCode == 73) {
                            return false; //Prevent from ctrl+shift+i
                        } else if (event.ctrlKey && event.keyCode == 85) {
                            return false; //Prevent from ctrl+u
                        }

                        // else if(event.keyCode==122) {
                        //     $("#Close_F11").click();
                        //     if (window.screenTop && window.screenY) {
                        //         event.preventDefault();
                        //         return false;
                        //     } else {
                        //         alert("WOW");
                        //     }
                        // } 
                    });

                    $(document).on("contextmenu", function(e) {
                        e.preventDefault();
                    });
                </script>

        </body>

    </html>
<?php
                }
                // start here
                function retype_header()
                {

                    $ci = &get_instance();
                    if (empty($_SESSION['User_details_retype_password']) && empty($_SESSION['User_modules_retype_password'])) {
                        redirect(base_url());
                        exit();
                    }

                    $user =  $_SESSION['User_details_retype_password'];
                    $modules = $_SESSION['User_modules_retype_password'];
                    $permission = false;
                    foreach ($modules as $key => $value) {
                        if (!(bool)$value->Restrict_access && $value->Module_details->Module_name == $ci->config->item('department_short')) {
                            $permission = true;
                        }
                    }
                    if (!$permission) {
                        redirect(base_url());
                        exit();
                    }
?>

    <?php
                    defined('BASEPATH') or exit('No direct script access allowed');
                    $ci = &get_instance();
    ?>

    <!DOCTYPE html>
    <html>

    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title><?php echo $ci->config->item('system_tab_name') ?> - Log in</title>
        <link rel="icon" href="<?php echo base_url() ?>assets/img/Sagay_Logo.png">
        <!-- Tell the browser to be responsive to screen width -->
        <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
        <!-- Bootstrap 3.3.7 -->
        <link rel="stylesheet" href="<?php echo base_url() ?>assets/theme/bower_components/bootstrap/dist/css/bootstrap.min.css">
        <!-- Font Awesome -->
        <link rel="stylesheet" href="<?php echo base_url() ?>assets/theme/bower_components/font-awesome/css/font-awesome.min.css">
        <!-- Ionicons -->
        <link rel="stylesheet" href="<?php echo base_url() ?>assets/theme/bower_components/Ionicons/css/ionicons.min.css">
        <!-- Theme style -->
        <link rel="stylesheet" href="<?php echo base_url() ?>assets/theme/dist/css/AdminLTE.min.css">
        <!-- iCheck -->
        <link rel="stylesheet" href="<?php echo base_url() ?>assets/theme/plugins/iCheck/square/blue.css">
        <!-- jQuery -->
        <script language="javascript" src="<?php echo base_url() ?>assets/jquery-3.2.1.js"></script>
        <!-- Theme style -->
        <link rel="stylesheet" href="<?php echo base_url() ?>assets/theme/dist/css/AdminLTE.min.css">
        <!-- AdminLTE Skins. Choose a skin from the css/skins folder instead of downloading all of them to reduce the load. -->
        <link rel="stylesheet" href="<?php echo base_url() ?>assets/theme/dist/css/skins/_all-skins.min.css">
        <!-- Pace style -->
        <link rel="stylesheet" href="<?php echo base_url() ?>/assets/theme/plugins/pace/pace.min.css">

        <!-- Google Font -->
        <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
        <style type="text/css">
            body {
                overflow-y: hidden;
            }
        </style>

        <head>

        <body class="hold-transition skin-green-light layout-top-nav">
            <div class="wrapper">

                <header class="main-header">
                    <nav class="navbar navbar-static-top">
                        <span class="logo" style="width:30vw;text-align:left">
                            <i class="fa fa-edit"></i>&ensp;<?php echo $ci->config->item('department_long'); ?>
                        </span>
                    </nav>
                </header>

                <div class="content-wrapper">
                    <div class="container">

                    <?php
                }

                function retype_footer()
                {
                    ?>
                    </div>
                </div>

            </div>
            <!-- PACE -->
            <script src="<?php echo base_url() ?>assets/theme/bower_components/pace/pace.min.js"></script>
            <!-- jQuery 3 -->
            <script src="<?php echo base_url() ?>assets/theme/bower_components/jquery/dist/jquery.min.js"></script>
            <!-- Bootstrap 3.3.7 -->
            <script src="<?php echo base_url() ?>assets/theme/bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
            <!-- iCheck -->
            <script src="<?php echo base_url() ?>assets/theme/plugins/iCheck/icheck.min.js"></script>

            <script>
                $(function() {
                    $('input').iCheck();

                    // handle inputs only inside $('.block')
                    $('.block input').iCheck();

                    // handle only checkboxes inside $('.test')
                    $('.test input').iCheck({
                        handle: 'checkbox'
                    });

                    // handle .vote class elements (will search inside the element, if it's not an input)
                    $('.vote').iCheck();

                    // you can also change options after inputs are customized
                    $('input.some').iCheck({
                        // different options
                    });

                    $('input').iCheck({
                        checkboxClass: 'icheckbox_square-blue',
                        radioClass: 'iradio_square-blue',
                        increaseArea: '20%' /* optional */
                    });



                });
            </script>

        </body>

    </html>
<?php
                }
                //end here
                function main_header()
                {
                    $ci = &get_instance();
                    if (empty($_SESSION['User_details']) && empty($_SESSION['User_modules'])) {
                        redirect(base_url());
                        exit();
                    }

                    $user =  $_SESSION['User_details'];
                    $modules = $_SESSION['User_modules'];
                    $permission = false;
                    foreach ($modules as $key => $value) {
                        if (!(bool)$value->Restrict_access && $value->Module_details->Module_name == $ci->config->item('department_short')) {
                            $permission = true;
                        }
                    }
                    if (!$permission) {
                        redirect(base_url());
                        exit();
                    }

                    defined('BASEPATH') or exit('No direct script access allowed');
?>
    <!DOCTYPE html>
    <html>

    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title>Business Licensing Information System</title>
        <link rel="icon" href="<?php echo base_url() ?>assets/img/Logo_3.png">
        <!-- Tell the browser to be responsive to screen width -->
        <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
        <!-- Bootstrap 3.3.7 -->
        <link rel="stylesheet" href="<?php echo base_url() ?>assets/theme/bower_components/bootstrap/dist/css/bootstrap.min.css">
        <!-- Font Awesome -->
        <link rel="stylesheet" href="<?php echo base_url() ?>assets/theme/bower_components/font-awesome/css/font-awesome.min.css">
        <!-- Ionicons -->
        <link rel="stylesheet" href="<?php echo base_url() ?>assets/theme/bower_components/Ionicons/css/ionicons.min.css">
        <!-- iCheck -->
        <link rel="stylesheet" href="<?php echo base_url() ?>assets/theme/plugins/iCheck/square/blue.css">
        <!-- Select2 -->
        <link rel="stylesheet" href="<?php echo base_url() ?>assets/theme/bower_components/select2/dist/css/select2.min.css">
        <!-- Theme style -->
        <link rel="stylesheet" href="<?php echo base_url() ?>assets/theme/dist/css/AdminLTE.min.css">
        <!-- AdminLTE Skins. Choose a skin from the css/skins folder instead of downloading all of them to reduce the load. -->
        <link rel="stylesheet" href="<?php echo base_url() ?>assets/theme/dist/css/skins/_all-skins.min.css">
        <!-- Datepicker -->
        <link rel="stylesheet" href="<?php echo base_url() ?>assets/theme/bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css">
        <!-- Custom CSS -->
        <link rel="stylesheet" type="text/css" href="<?php echo base_url() ?>assets/css/noPostBack.css">
        <script>
            var baseUrl = '<?php echo base_url() ?>';
        </script>

        <head>

        <body class="hold-transition skin-green-light fixed sidebar-mini">
            <div class="wrapper">
                <header class="main-header">

                    <!-- Logo -->
                    <a href="#" class="logo">
                        <!-- mini logo for sidebar mini 50x50 pixels -->
                        <span class="logo-mini"><?php echo $ci->config->item('system_name_short') ?></span>
                        <!-- logo for regular state and mobile devices -->
                        <h4><?php echo $ci->config->item('system_name') ?></h4>
                    </a>

                    <!-- Header Navbar: style can be found in header.less -->
                    <nav class="navbar navbar-static-top">
                        <!-- Sidebar toggle button-->
                        <!-- <a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button">
                <span class="sr-only">Toggle navigation</span>
            </a> -->
                        <span class="logo" style="width:30vw;text-align:left">
                            <i class="fa fa-edit"></i>&ensp;<?php echo $ci->config->item('department_long') ?>
                        </span>
                        <!-- Navbar Right Menu -->
                        <div class="navbar-custom-menu">
                            <ul class="nav navbar-nav">
                                <!-- Messages: style can be found in dropdown.less-->
                                <li class="dropdown user user-menu">
                                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                        <span class="hidden-xs"><?= ucwords($user->First_name) . " " .
                                                                    ucwords($user->Last_name); ?></span>
                                    </a>
                                    <ul class="dropdown-menu">
                                        <li class="user-header">
                                            <img src="<?php echo base_url() ?>uploads/images/no-image.gif" class="img-circle" alt="User Image">
                                            <p>
                                                <?php if ($user->Middle_name == '') {
                                                    echo ucwords($user->First_name) . " " . ucwords($user->Last_name);
                                                } else {
                                                    echo ucwords($user->First_name) . " " . ucwords($user->Middle_name)[0] .
                                                        ". " . ucwords($user->Last_name);
                                                } ?>
                                                <small><?= $user->Position; ?></small>
                                                <small><?= $ci->config->item('department_long'); ?></small>
                                            </p>
                                        </li>
                                        <!-- Menu Footer-->
                                        <li class="user-footer">
                                            <div class="pull-right">
                                                <a href="<?php echo base_url() ?>" id="sign-out" class="btn btn-default btn-flat">Sign out</a>
                                            </div>
                                        </li>
                                    </ul>
                                </li>
                            </ul>
                        </div>

                    </nav>
                </header>
            <?php
                }

                function main_footer()
                {
                    $ci = &get_instance();

            ?>
                <!-- <script language="javascript">
        $('#sign-out').on('click', function(){
            window.location = "<?php echo base_url() ?>";
        });
    </script> -->

                <footer class="main-footer">
                    <div class="pull-right hidden-xs">
                        <b>Version</b> <?= $ci->config->item('version') ?>
                    </div>
                    <strong>Copyright &copy; 2018 <a href="#">Business Licensing Information System</a>.</strong> All rights reserved.
                </footer>
                <!-- jQuery 3 -->
                <script src="<?php echo base_url() ?>assets/theme/bower_components/jquery/dist/jquery.min.js"></script>
                <!-- Bootstrap 3.3.7 -->
                <script src="<?php echo base_url() ?>assets/theme/bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
                <!-- Select2 -->
                <script src="<?php echo base_url() ?>assets/theme/bower_components/select2/dist/js/select2.full.min.js"></script>
                <!-- FastClick -->
                <script src="<?php echo base_url() ?>assets/theme/bower_components/fastclick/lib/fastclick.js"></script>
                <!-- AdminLTE App -->
                <script src="<?php echo base_url() ?>assets/theme/dist/js/adminlte.min.js"></script>
                <!-- Sparkline -->
                <script src="<?php echo base_url() ?>assets/theme/bower_components/jquery-sparkline/dist/jquery.sparkline.min.js"></script>
                <!-- jvectormap  -->
                <script src="<?php echo base_url() ?>assets/theme/plugins/jvectormap/jquery-jvectormap-1.2.2.min.js"></script>
                <script src="<?php echo base_url() ?>assets/theme/plugins/jvectormap/jquery-jvectormap-world-mill-en.js"></script>
                <!-- jQuery -->
                <script language="javascript" src="<?php echo base_url() ?>assets/jquery-3.2.1.js"></script>
                <!-- SlimScroll -->
                <script src="<?php echo base_url() ?>assets/theme/bower_components/jquery-slimscroll/jquery.slimscroll.min.js"></script>
                <!-- ChartJS -->
                <script src="<?php echo base_url() ?>assets/theme/bower_components/chart.js/Chart.js"></script>
                <!-- Custom JS -->
                <script language="javascript" src="<?php echo base_url() ?>assets/scripts/jquery-3.2.1.js"></script>
                <script language="javascript" src="<?php echo base_url() ?>assets/scripts/noPostBack.js"></script>
                <script language="javascript" src="<?php echo base_url() ?>assets/theme/plugins/iCheck/icheck.min.js"></script>
                <script language="javascript" src="<?php echo base_url() ?>assets/printThis.js"></script>

                <!-- Socket.IO -->

                <script src="<?php echo $ci->config->item('socket_url') ?>node_modules/socket.io/node_modules/socket.io-client/socket.io.js"></script>
                <!-- Socket.IO Data-->
                <script src="<?php echo base_url() ?>assets/scripts/socket/index.js"></script>
        </body>

    </html>
<?php
                    permission_panel();
                }

                function sidebar($module, $submenu = '')
                {
                    $ci = &get_instance();

?>
    <!-- Left side column. contains the logo and sidebar -->
    <aside class="main-sidebar">
        <!-- sidebar: style can be found in sidebar.less -->
        <section class="sidebar">
            <!-- sidebar menu: : style can be found in sidebar.less -->
            <ul class="sidebar-menu" data-widget="tree">
                <li class="header">MAIN NAVIGATION</li>
                <li class="treeview <?php echo ($module == 'applicant') || ($module == 'individual') ||
                                        ($module == 'reports') || ($module == 'listings') ? 'active' : ''; ?>">
                    <a href="#">
                        <i class="fa fa-edit"></i>
                        <span>Assessors</span>
                        <span class="pull-right-container">
                            <i class="fa fa-angle-left pull-right"></i>
                        </span>
                    </a>
                    <ul class="treeview-menu">
                        <li class="<?php echo ($module == 'applicant') ? 'active' : ''; ?>">
                            <a href="<?php echo base_url() ?>treasurers/applicant_search">
                                <i class="fa fa-building-o"></i>
                                <span>Businesses</span>
                            </a>
                        </li>
                        <li class="treeview <?php echo ($module == 'reports') ? 'active' : ''; ?>">
                            <a href="<?php echo base_url() ?>treasurers/applicant_search">
                                <i class="fa fa-book"></i>
                                <span>Applications</span>
                                <span class="pull-right-container">
                                    <i class="fa fa-angle-left pull-right"></i>
                            </a>
                            <ul class="treeview-menu">
                                <li class="<?php echo ($submenu == 'waiting_for_billing') &&
                                                ($module == 'reports') ? 'active' : ''; ?>">
                                    <a href="<?php echo base_url() ?>treasurers/reports/waiting_for_billing">
                                        <i class="fa fa-file-o"></i>
                                        <span>Waiting for Billing</span>
                                    </a>
                                </li>
                                <li class="<?php echo ($submenu == 'waiting_for_approval') &&
                                                ($module == 'reports') ? 'active' : ''; ?>">
                                    <a href="<?php echo base_url() ?>treasurers/reports/waiting_for_approval">
                                        <i class="fa fa-file-text-o"></i>
                                        <span>Waiting for Approval</span>
                                    </a>
                                </li>
                                <li class="<?php echo ($submenu == 'approved') && ($module == 'reports') ?
                                                'active' : ''; ?>">
                                    <a href="<?php echo base_url() ?>treasurers/reports/approved">
                                        <i class="fa fa-thumbs-o-up"></i>
                                        <span>Approved</span>
                                    </a>
                                </li>
                                <li class="<?php echo ($submenu == 'cancelled') && ($module == 'reports') ?
                                                'active' : ''; ?>">
                                    <a href="<?php echo base_url() ?>treasurers/reports/cancelled">
                                        <i class="fa fa-thumbs-o-down"></i>
                                        <span>Cancelled</span>
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <li class="treeview <?php echo ($module == 'listings') ? 'active' : ''; ?>">
                            <a href="<?php echo base_url() ?>treasurers/applicant_search">
                                <i class="fa fa-list-alt"></i>
                                <span>Listings</span>
                                <span class="pull-right-container">
                                    <i class="fa fa-angle-left pull-right"></i>
                            </a>
                            <ul class="treeview-menu">
                                <!-- <li class="<?php echo ($submenu == 'masterlist') && ($module == 'listings') ? 'active' : ''; ?>">
                                    <a href="#">
                                        <i class="fa fa-circle-o"></i>
                                        <span>Masterlist of Business</span>
                                    </a>
                                </li> -->
                                <li class="<?php echo ($submenu == 'lines') && ($module == 'listings') ? 'active' : ''; ?>">
                                    <a href="<?php echo base_url() ?>treasurers/line_of_business">
                                        <i class="fa fa-circle-o"></i>
                                        <span>Line of Business</span>
                                    </a>
                                </li>
                                <!-- <li class="<?php echo ($submenu == 'delinquency') && ($module == 'listings') ? 'active' : ''; ?>">
                                    <a href="#">
                                        <i class="fa fa-circle-o"></i>
                                        <span>Business Delinquency</span>
                                    </a>
                                </li> -->
                                <li class="<?php echo ($submenu == 'types') && ($module == 'listings') ? 'active' : ''; ?>">
                                    <a href="<?php echo base_url() ?>treasurers/types_of_business">
                                        <i class="fa fa-circle-o"></i>
                                        <span>Types of Business</span>
                                    </a>
                                </li>
                                <li class="<?php echo ($submenu == 'owner') && ($module == 'listings') ? 'active' : ''; ?>">
                                    <a href="<?php echo base_url() ?>treasurers/type_of_ownership">
                                        <i class="fa fa-circle-o"></i>
                                        <span>Type of Ownership</span>
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <li class="<?php echo ($module == 'mp_fees') ? 'active' : ''; ?>">
                            <a href="<?php echo base_url() ?>management">
                                <i class="fa fa-list"></i>
                                <span>Mayors Permit Fees</span>
                            </a>
                        </li>
                        <li class="<?php echo ($module == 'tax_table') ? 'active' : ''; ?>">
                            <a href="<?php echo base_url() ?>tax_table">
                                <i class="fa fa-table"></i>
                                <span>Tax Table Management</span>
                            </a>
                        </li>
                        <li class="<?php echo ($module == 'due_date') ? 'active' : ''; ?>">
                            <a href="<?php echo base_url() ?>due_date">
                                <i class="fa fa-calendar-o"></i>
                                <span>Due Date Management</span>
                            </a>
                        </li>
                    </ul>
                </li>
                <hr>
                <style>
                    .header-queue {
                        height: 100px !important;
                    }
                </style>

                <li class="active">
                    <a>
                        <div class="row form-inline" style="margin:0 auto">
                            <label>Window&ensp;</label>
                            <select id="assessors-window" class="form-control input-sm">
                                <?php for ($i = 0; $i < 10; $i++) : ?>
                                    <option value="<?= $i + 1 ?>"><?= $i + 1 ?></option>
                                <?php endfor; ?>
                            </select>
                        </div>
                    </a>
                    <a>
                        <div id="">
                            <button style="display:none;" class="btn btn-danger flat btn-sm queue-status" data-queue-status="0"><i class="fa fa-circle"></i> OFFLINE</button>
                            <!-- <button style="display:none;" class="btn btn-success flat btn-sm queue-status" data-queue-status="1"><i class="fa fa-circle"></i> OFFLINE</button> -->
                            <button style="display:none;" class="btn btn-success flat btn-sm queue-status" data-queue-status="1" data-toggle="modal" data-target="#user_modal"><i class="fa fa-wifi"></i> ONLINE</button>
                        </div>
                    </a>
                    <a>
                        <div style="margin-top:-10px; text-align:left">
                            <p style=" ">SERVING :</p>
                            <p style="color:red; font-size:30pt; margin-top: -5px;" id="now-serving"></p>
                            <p id="business-name-q" data-id=""></p>
                        </div>
                    </a>
                    <div class="queue-menu" style="display:none;">
                        <div class="col-sm-6">
                            <button data-menu="pass" class="btn flat btn-default btn-queue-menu">PASS</button>
                        </div>
                        <div class="col-sm-6">
                            <button data-menu="next" class="btn flat btn-primary btn-queue-menu" id="Next">NEXT</button>
                        </div>
                    </div>
                </li>
            </ul>
            <script>
                var current_user = <?= $_SESSION['User_details']->ID ?>;
            </script>
        </section>
        <!-- /.sidebar -->
        <script>
            var now_serving = false;
        </script>

        <script language="javascript" src="<?php echo base_url() ?>assets/scripts/jquery-3.2.1.js"></script>
        <script language="javascript" src="<?php echo base_url() ?>assets/scripts/queueing/index.js"></script>
    </aside>
<?php

                }
