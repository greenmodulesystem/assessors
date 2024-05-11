<?php 
main_header();
sidebar('listings', 'lines');

?>

<div class="content-wrapper">
    <section class="content-header">
        </br>
        <ol class="breadcrumb">
            <li><i class="fa fa-edit"></i> Assessors</li>
            <li>Business Listings</li>
            <li class="active">Line of Business</li>
        </ol>
    </section>
    <section class="content">
        <?php boxes()?>
        <div class="box">
            <div class="box-header with-border">
                <div class="box-title">
                    <h3 class="box-title"><i class="fa fa-list-alt"></i> Line of Business Listing</h3>
                </div>
                <div class="box-tools">
                    <div class="row form-inline" style="padding-right:10px">
                        <select style="width:170px;" class="js-example-basic-single" id="Class">
                            <option disabled selected value="">Filter Classification</option>
                            <option value="">All</option>
                            <?php foreach($classes as $class):?>
                                <option value="<?=$class->Description?>">
                                    <?=strtoupper($class->Description)?>
                                </option>
                            <?php endforeach;?>
                        </select>&emsp;
                        <select style="width:150px;" class="js-example-basic-single" id="Barangay">
                            <option disabled selected value="">Filter Barangay</option>
                            <option value="">All</option>
                            <?php foreach($barangays as $barangay):?>
                                <option value="<?=$barangay->Barangay?>">
                                    <?=str_replace('Barangay','',$barangay->Barangay)?>
                                </option>
                            <?php endforeach;?>
                        </select>&emsp;
                        <label>Date Range :&ensp;</label>
                        <input type="text" id="From" value='' class="form-control input-sm" placeholder="Min date">&ensp;
                        <input type="text" id="To" value='' class="form-control input-sm" placeholder="Max date">
                        <button id="Filter" class="btn btn-default btn-sm flat"><i class="fa fa-filter"></i>&ensp;Filter</button>
                    </div>
                </div>
            </div>
            <div class="box-body">
                <table class="table table-striped table-bordered" cellspacing="0" width="100%">
                    <thead>
                        <tr>
                            <th>Permit No.</th>
                            <th>Trade Name</th>
                            <th>Owner Name</th>
                            <th rowspan="2" style="vertical-align:top">Barangay</th>
                            <th>Classification</th>
                            <th rowspan="2">Capital</th>
                            <th rowspan="2">Gross</th>
                        </tr>
                        <tr>
                            <th>&emsp;App Type</th>
                            <th>&emsp;Business Address</th>
                            <th>&emsp;Owner Address</th>
                            <th>&emsp;Line of Business</th>
                        </tr>
                    </thead>
                    <tbody id="grid">
                    </tbody>
                </table>
            </div>
        </div>
    </section>
</div>

<?php main_footer();?>

<script language="javascript" src="<?php echo base_url()?>assets/scripts/noPostBack.js"></script>
<script src="<?php echo base_url() ?>assets/theme/bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js"></script>
<script src="<?php echo base_url() ?>assets/theme/bower_components/select2/dist/js/select2.full.min.js"></script>
<script language="javascript">
    var baseUrl = '<?php echo base_url()?>';

    $(document).ready(function(){
        $('.js-example-basic-single').select2();
        counter();
        loadGrid();
        socket.emit('assessor', {
            assessor: 0,
        });
    });
    
    var loadGrid = function(){
        $(document).gmLoadPage({
            url     :   baseUrl+"treasurers/listings_line_default/",
            load_on :   "#grid"
        });
    }

    $('#Filter').gmFilter({
        url     :   baseUrl+"treasurers/listings_line_filter",
        brgy    :   "#Barangay",
        class   :   "#Class",
        from    :   "#From",
        to      :   "#To",
        load_on :   "#grid"
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
    
    $('#From').datepicker({
        autoclose: true,
        todayHighlight: true,
        format: 'MM d, yyyy',
    })
    
    $('#To').datepicker({
        autoclose: true,
        todayHighlight: true,
        format: 'MM d, yyyy',
    })

    socket.on('assessor', function(data){
        counter();
        loadGrid();
    });
    
</script>
