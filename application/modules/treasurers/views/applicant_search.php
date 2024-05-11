<?php 
main_header();
sidebar('applicant'); 
?>

<div class="content-wrapper">
    <section class="content-header">
        </br>
        <ol class="breadcrumb">
            <li><i class="fa fa-edit"></i> Assessors</li>
            <li class="active">Businesses</li>
        </ol>
    </section>
    <section class="content">
        <?php boxes()?>
        <div class="box">
            <div class="box-header with-border">
                <div class="box-title">
                    <h3 class="box-title"><i class="fa fa-search"></i> Search Business</h3>
                </div>
                <div class="box-tools">
                    <div class="input-group input-group-sm" style="width: 350px;">
                        <input type="text" name="table_search" id="search" class="form-control pull-right" 
                        placeholder="Search business name or business owner...">
                        <div class="input-group-btn">
                            <button type="button" id="btnSearch" class="btn btn-default"><i class="fa fa-search"></i></button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="box-body">
                <table class="table table-striped table-bordered" cellspacing="0" width="100%">
                    <thead>
                        <tr>
                            <th>Business Owner</th>
                            <th>Business Name</th>
                            <th>Latest Application Date</th>
                            <th class="text-center" style="width:10%;">Option</th>
                        </tr>
                    </thead>
                    <tbody id="results">
                    </tbody>
                </table>
            </div>
        </div>
    </section>
</div>

<?php main_footer();?>

<script language="javascript" src="<?php echo base_url()?>assets/scripts/noPostBack.js"></script>

<script language="javascript">
    $(document).ready(function(){
        $('#btnSearch').click();
        counter();
        socket.emit('assessor', {
            assessor: 0,
        });
    });
	
    $(document).on('keypress',function(e) {
        if(e.which == 13) {
            $('#btnSearch').click();
        }
    });

    $('#btnSearch').gmSearch({
        url     :   baseUrl+"treasurers/applicant_search_results",
        search  :   "#search",
        load_on :   "#results"
    });
    
    var counter = function(){
        $.ajax({
            type: "GET",
            url: baseUrl + "treasurers/counter",
        }).done(function(data) {
            data = JSON.parse(data);
            $('#Billing_count').text(data['Billing']);
            $('#Approval_count').text(data['Approval']);
            $('#Approved_count').text(data['Approved']);
            $('#Cancelled_count').text(data['Cancelled']);
        });
    }

    socket.on('assessor', function(data) {
        $('#btnSearch').click();
        counter();
    });
</script>
