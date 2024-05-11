<?php main_header(); ?>
<?php sidebar('user', 'grid'); ?>

<script language="javascript">
    $(document).gmLoadPage({
        url     :   "<?php echo base_url() ?>users/grid",
        load_on :   "#grid"
    });
</script>

    <div class="content-wrapper">
        <section class="content-header">
        <h1>&nbsp;</h1>
        <ol class="breadcrumb">
            <li><a href="<?php  echo base_url() ?>dashboard"><i class="fa fa-dashboard"></i> Dashboard</a></li>
            <li class="active"><i class="fa fa-search"></i> User Account Search</li>
        </ol>
        </section>
        <section class="content">
            
            <div class="box">
                <div class="box-header">
                    <h3 class="box-title"><i class="fa fa-search"></i> User Account Search</h3>

                    <div class="box-tools">
                        <div class="input-group input-group-sm" style="width: 350px;  padding-top: 5px; margin-bottom: 5px">
                            <input type="text" name="table_search" id="search" class="form-control pull-right input-sm" placeholder="Search...">
                            <div class="input-group-btn">
                                <button type="button" id="btnSearch" class="btn btn-default input-sm"><i class="fa fa-search"></i></button>
                            </div>
                        </div>
                    </div>
                
                </div>
                <div class="box-body">

                    <div class="box-body table-responsive no-padding">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <td width="5%"></td>
                                    <td width="35%">Username</td>
                                    <td width="60%">Fullname</td>
                                </tr>
                            </thead>
                        </table>
                    </div>

                    <div id="grid">
                        <div class="progress progress-xs">
                            <div class="progress-bar progress-bar-warning progress-bar-striped" role="progressbar" aria-valuenow="60" aria-valuemin="0" aria-valuemax="100" style="width: 100%"></div>
                        </div>
                    </div>

                </div>
            </div>

        </section>
    </div>

    <script language="javascript">
        $('#btnSearch').gmSearch({
            url     :   "<?php echo base_url() ?>users/grid",
            search  :   "#search",
            load_on :   "#grid"
        });
    </script>

<?php main_footer(); ?>