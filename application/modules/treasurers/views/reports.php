  <?php
    main_header();
    sidebar('reports', $type);

    $icon = '';
    if ($type == 'waiting_for_billing') {
        $icon = "fa fa-file-o";
    } else if ($type == 'waiting_for_approval') {
        $icon = "fa fa-file-text-o";
    } else if ($type == 'approved') {
        $icon = "fa fa-thumbs-o-up";
    } else if ($type == 'cancelled') {
        $icon = "fa fa-thumbs-o-down";
    }
    ?>

  <div class="content-wrapper">
      <section class="content-header">
          </br>
          <ol class="breadcrumb">
              <li><i class="fa fa-edit"></i> Assessors</li>
              <li>Applications</li>
              <li class="active"><?= ucwords(str_replace('_', ' ', $type)); ?></li>
          </ol>
      </section>
      <section class="content">
          <?php boxes() ?>
          <div class="box mb-4">
              <div class="box-header with-border">
                  <div class="box-title">
                      <h3 class="box-title"><i class="<?= $icon ?>"></i>
                          <?= strtoUpper(str_replace('_', ' ', $type)); ?>
                      </h3>
                  </div>
                  <!-- <div class="box-tools">
                    <div class="input-group input-group-sm" style="width: 350px;">
                        <input type="text" name="table_search" id="search" class="form-control pull-right" 
                        placeholder="Search business name...">
                        <div class="input-group-btn">
                            <button type="button" id="btnSearch" class="btn btn-default"><i class="fa fa-search"></i></button>
                        </div>
                    </div>
                </div> -->
              </div>
              <div class="box-body mb-4">
                  <table class="table table-striped table-bordered" cellspacing="0" width="100%">
                      <thead>
                          <tr>
                              <th>Business Owner</th>
                              <th>Business Name</th>
                              <th>Latest Application Date</th>
                              <th class="text-center" style="width:10%;">Option</th>
                          </tr>
                      </thead>
                      <tbody id="grid">
                      </tbody>
                  </table>
              </div>
          </div>

          <?php if ($type == 'waiting_for_billing' && @$r_listings!=null) { ?>
              <div class="box">
                  <div class="box-header with-border">
                      <div class="box-title">
                          <h3 class="box-title"><i class="fa hourglass"></i>
                              RETIREMENT LISTINGS
                          </h3>
                      </div>
                      <!-- <div class="box-tools">
                    <div class="input-group input-group-sm" style="width: 350px;">
                        <input type="text" name="table_search" id="search" class="form-control pull-right" 
                        placeholder="Search business name...">
                        <div class="input-group-btn">
                            <button type="button" id="btnSearch" class="btn btn-default"><i class="fa fa-search"></i></button>
                        </div>
                    </div>
                </div> -->
                  </div>
                  <div class="box-body mb-4">
                      <table class="table table-striped table-bordered" cellspacing="0" width="100%">
                          <thead>
                              <tr>
                                  <th>Business Owner</th>
                                  <th>Business Name</th>
                                  <th>Latest Application Date</th>
                                  <th class="text-center" style="width:10%;">Option</th>
                              </tr>
                          </thead>
                          <tbody id="retire-grid">
                          </tbody>
                      </table>
                  </div>
              </div>
          <?php } ?>    
      </section>
  </div>

  <?php main_footer(); ?>

  <script language="javascript" src="<?php echo base_url() ?>assets/scripts/noPostBack.js"></script>
  <script language="javascript">
      var baseUrl = '<?php echo base_url() ?>';
      var type = '<?php echo $type ?>';

      $(document).ready(function() {
          counter();
          loadGrid();
          socket.emit('assessor', {
              assessor: 0,
          });
      });

      var loadGrid = function() {
          $(document).gmLoadPage({
              url: baseUrl + "treasurers/reports_grid/" + type,
              load_on: "#grid"
          });
      }

      var counter = function() {
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
          counter();
          loadGrid();
      });
  </script>