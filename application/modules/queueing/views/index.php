<?php department_header(); 

// var_dump($this->session->userdata('applicant'));
$ci = & get_instance();

?>

<?php sidebar('queueing', '',''); ?>
<script language="javascript" src="<?php echo base_url()?>assets/js/departmentmenu/history_generator.js"></script>
<script language="javascript" src="<?php echo base_url()?>assets/theme/plugins/nestedTables/dist/nested.tables.min.js   "></script>

  <div class="content-wrapper">
       
        <section class="content-header">
        
        <!-- <ol class="breadcrumb">
            <li><a href="<?php  echo base_url() ?>department"><i class="fa fa-department-extinguisher"></i>City Health Office</a></li>
            <li class="active">New</li>
        </ol>
         -->
        <br>
        </section>
        <section class="content">
    
        <div class="box">
                        <div class="box-header with-border">
                            <h3 class="box-title"><i class="fa fa-circle-o"></i> Queueing</h3>
                            <div class="box-tools"> 
                            </div>
                        </div>
                        
                        <div class="box-body">
                            <table class="table table-hover">
                                <tr>
                                    <th></th>
                                    <th width="90%"> Business Name</th>
                                </tr>
                                <tr>
                                    <tbody id="queue">
                                        
                                    </tbody>
                                </tr>
                            </table>
                        </div>

                        <div class="box-footer">
                          
                        </div>
                    </div>
        </section>
    </div>
   
<?php main_footer(); ?>
</div>

<script language="javascript" src="<?php echo base_url()?>assets/js/noPostBack.js"></script>
<link rel="stylesheet" href="<?php echo base_url()?>assets/theme/plugins/DataTables/Buttons-1.5.1/css/buttons.dataTables.min.css">
<link rel="stylesheet" href="<?php echo base_url()?>assets/theme/plugins/DataTables/DataTables-1.10.16/css/jquery.dataTables.min.css">
<!-- <script language="javascript" src="<?php echo base_url()?>assets/js/departmentmenu/application_profile_result.js"></script> -->

<script language="javascript" src="<?php echo base_url() ?>assets/theme/plugins/printThis/printThis.js"></script>
<script language="javascript" src="<?php echo base_url()?>assets/theme/plugins/DataTables/DataTables-1.10.16/js/jquery.dataTables.min.js"></script>
<!-- print -->
<script language="javascript" src="<?php echo base_url()?>assets/theme/plugins/DataTables/Buttons-1.5.1/js/dataTables.buttons.min.js"></script>
<script language="javascript" src="<?php echo base_url()?>assets/theme/plugins/DataTables/Buttons-1.5.1/js/buttons.print.min.js"></script>
<script>

    function load_queue(data){
        data = data.result;
        $.each(data.onprocess,function(key,value){
                
            var html = '<tr>'+
                            '<td> <button data-id="'+value.ID+'" class="btn btn-sm btn-warning flat call"><i class="fa fa-bell"></i></button></td>'+
                            '<td>'+value.Business_name+'</td>'+
                        '</tr>';
            $('#queue').append(html);
        });
    }
    $(document).gmPostHandler({
        url: "department/service/department-service/reports/",
        function_call: true,
        function: load_queue,
        parameter: true,
    });

    $(document).on('click','.call',function(){
        $(document).gmPostHandler({
            url: "queueing/service/queueing-service/call_attention",
            data:{
                application: $(this).data('id'),
            },
            // function_call: true,
            // function: informdepartment,
            // parameter: true,
        });
    });
</script>
