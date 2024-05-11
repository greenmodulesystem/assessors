<?php 
main_header();
sidebar('listings', 'owner');

?>

<div class="content-wrapper">
    <section class="content-header">
        </br>
        <ol class="breadcrumb">
            <li><i class="fa fa-edit"></i> Assessors</li>
            <li>Business Listings</li>
            <li class="active">Type of Ownership</li>
        </ol>
    </section>
    <section class="content">
        <?php boxes()?>
        <div class="box">
            <div class="box-header with-border">
                <div class="box-title">
                    <h3 class="box-title"><i class="fa fa-list-alt"></i> Type of Ownership Listing</h3>
                </div>
                <div class="box-tools">
                    <div class="row form-inline" style="padding-right:10px">
                        <select style="width:200px;" class="js-example-basic-single" id="Class">
                            <option disabled selected value="">Filter Type of Ownership</option>
                            <option value="">All</option>
                            <?php foreach($ownership as $owner):?>
                                <option value="<?=$owner->Type?>">
                                    <?=strtoupper($owner->Type)?>
                                </option>
                            <?php endforeach;?>
                        </select>
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
                            <th>Date</th>
                            <th>Permit No.</th>
                            <th>Name</th>
                            <th>Address</th>
                            <th>Tradename</th>
                            <th>Line of Business</th>
                            <th>App Type</th>
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
            url     :   baseUrl+"treasurers/listings_owner_default/",
            load_on :   "#grid"
        });
    }

    $('#Filter').gmFilter({
        url     :   baseUrl+"treasurers/listings_owner_filter",
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
