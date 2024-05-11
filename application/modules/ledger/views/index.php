<?php
main_header();
sidebar('ledger');

$fee_arr = [];
if (!empty($fees_list)) {
    foreach (@$fees_list as $f) {
        $fee_arr[] = $f->BusLine_ID;
    }
}

?>

<div class="content-wrapper">
    <section class="content-header">
        </br>
        <ol class="breadcrumb">
            <li><i class="fa fa-edit"></i> Assessors</li>
            <li>Businesses</li>
            <li class="active">Assessment</li>
        </ol>
    </section>
    <section class="content">
        <div class="row">
            <div class="box" style="width: 79%; margin:0.5rem auto;">
                <div class="box-header">
                    <h3 class="box-title"><i class="fa fa-person"></i> Applicant List</h3>
                </div>
                <div class="box-body">
                    <div class="row">
                    <!-- <div class="col-md-2"></div> -->
                        <div class="col-md-6 text-right">SEARCH APPLICANT : </div>
                        <div class="col-md-6 pr-4">
                            <input type="text" class="form-control" id="search" placeholder="Enter Applicant Name Here....">
                        </div>
                    </div>
                    <div class="box-body table-responsive no-padding" style="max-height: 500px;">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <td class="text-center" width="50%"><strong>Applicant Name</strong></td>
                                    <td width="50%"></td>
                                </tr>
                            </thead>
                            <tbody id="load-grid">
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<?php main_footer(); ?>

<script language="javascript" src="<?php echo base_url() ?>assets/ledger/ledger.js"></script>
<script language="javascript" src="<?php echo base_url() ?>assets/scripts/noPostBack.js"></script>
<script language="javascript">
    var baseUrl = '<?php echo base_url() ?>';
    $(document).ready(function() {

        // // loadGrid();
        // // counter();
        // socket.emit('assessor', {
        //     assessor: 0,
        // });
        // updatecapp(ID);
        // $('.queue-status').attr('disabled', true);
    });
</script>