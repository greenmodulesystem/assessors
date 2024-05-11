<?php
main_header();
sidebar('due_date');https://panel.apexminecrafthosting.com/server/1175104
$monthArr = range(1, 12);

var_dump($monthArr);
?>

<div class="content-wrapper">
    <section class="content-header">
        </br>
        <ol class="breadcrumb">
            <li><i class="fa fa-edit"></i> Assessors</li>
            <li>Businesses</li>
            <li>Assessment</li>
            <li class="active">Due Date</li>
        </ol>
    </section>
    <section class="content">
        <div class="row">
            <div class="box" style="width: 75%; margin:0.5rem auto;">
                <div class="box-header text-center" style="padding-top: 25px;">
                    <h3 class="box-title">DUE DATE MANAGEMENT</h3>
                    <!-- <div class="box-tools"> -->
                    <!-- <div class="input-group input-group-sm" style="width: 350px;  padding-top: 5px; margin-bottom: 5px">
                            <input type="text" name="table_search" id="search" class="form-control pull-right input-sm" placeholder="Search Mayors Permit Fee">
                            <div class="input-group-btn">
                                <button type="button" style="width: 50px;" id="btnSearch" class="btn btn-success input-sm"><i class="fa fa-search"></i></button>
                            </div>
                        </div> -->
                    <!-- </div> -->
                </div>
                <div class="box-body" style="padding: 50px;">
                    <div class="row">
                        <?php
                        foreach (@$due_date as $dd) {
                        ?>
                            <div class="col-md-6 text-center" style="height:200px; padding: 10px; border: 2px solid black;">
                                <?php
                                switch ($dd->Qtr) {
                                    case '1';
                                        echo '<h4>1ST QUARTER / 1ST PAYMENT / 1ST SEMI ANNUAL PAYMENT </h4>';
                                        break;
                                    case '2';
                                        echo '<h4>2ND QUARTER PAYMENT </h4>';
                                        break;
                                    case '3';
                                        echo '<h4>3RD QUARTER / 2ND SEMI ANNUAL PAYMENT </h4>';
                                        break;
                                    case '4';
                                        echo '<h4>4TH QUARTER PAYMENT </h4>';
                                        break;
                                }

                                // $year = date("Y");
                                // $month = strlen($dd->Mm)==1 ? '0'.$dd->Mm : $dd->Mm ;
                                // $days_in_selected_month_yr = date('t', mktime(0, 0, 0, $month, 1, $year));
                                $daysArray = range(1, 31);
                                ?>

                                <div class="row" style="padding:10px">
                                    <div class="col-md-5 text-center">
                                        <div class="form-group">
                                            <label for="exampleInputEmail1">MONTH</label>
                                            <select class="form-control monthval">
                                                <?php
                                                foreach (@$monthArr as $month) {
                                                    $m = strlen($month) == 1 ? '0' . $month : $month; ?>
                                                    <option value="<?= $m ?>" <?=$dd->Mm==$m ? 'selected' : ''?>><?= $m ?></option>
                                                <?php
                                                }
                                                ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-5 text-center">
                                        <div class="form-group">
                                            <label for="exampleInputEmail1">DAY</label>
                                            <select class="form-control dayval">
                                                <?php
                                                foreach (@$daysArray as $day) {
                                                    $d = strlen($day) == 1 ? '0' . $day : $day; ?>
                                                    <option value="<?= $d ?>" <?=$dd->Dd==$day ? 'selected' : ''?> ><?= $d ?></option>
                                                <?php
                                                }
                                                ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-2 text-center" style="padding-top: 25px;">
                                        <button type="button" class="btn btn-primary btn-lgw savedue" data-id='<?=@$dd->ID ?>'>SAVE</button>
                                    </div>
                                </div>
                            </div>
                        <?php
                        }
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<?php main_footer(); ?>

<script language="javascript" src="<?php echo base_url() ?>assets/mgmt/due_date.js"></script>
<script language="javascript" src="<?php echo base_url() ?>assets/scripts/noPostBack.js"></script>
<script language="javascript">
    var baseUrl = '<?php echo base_url() ?>';
    // $(function() {
    //     $('.select2').select2()
    // })
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