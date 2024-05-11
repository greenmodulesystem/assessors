<style>
.permit-info{
    border-width: 1px;
    border-color: black;
    border-style: none none ridge none;
}
#content {
  width: 100%; 
} 
#doc{
    left: 25px;
    position: relative;
    padding-top: 10px;
    padding-left: 30px;
    padding-right: 30px;
    padding-bottom: -100px;
    border-width: 5px;
    border-color: black;
    border-style: double double double double ;
}

.officer{
    width:250px;
}
#left, #right { 
  width: 50%; 
  background: white; 
}

/* Create two equal columns that floats next to each other */
.column {
    font-size:12px;
    padding-left:13px;
    padding-right:20px;
    width: 50%;
}

/* Clear floats after the columns */
.row:after {
   
    display: table;
    clear: both;
}

#left  { float:left;  }
#right { float:right; }
</style>
<div id="doc" style="line-height: .4em; width: 15.5cm; height: 12.5cm; display:inline-block; left:in;">
   <img style="width:70px; height: 70px; position:absolute; top: 10px; left: 5%;"src="<?php echo base_url()?>assets/img/logo/cadiz-official-logo-o.png">
   <img style="width:60px; height: 70px; position:absolute; top: 10px; right: 5%;"src="<?php echo base_url()?>assets/img/logo/sentrong-sigla-o.png">
   <div style="text-align: center;">
        <p style="font-size: 12px; font-family: Calibri (Body); ">Republic of the Philippines</p>
        <p style="font-size: 12px; font-family: Calibri (Body); ">City of Cadiz</p>
        <div style="line-height: .8em;">
            <p style="font-size: 12px; font-family: Arial Black;"><b>OFFICE OF THE CITY HEALTH OFFICER</b></p>
            <p style="font-family:'Old English Text MT'; font-size: 30px; transform: scale(1.1, 1);">Sanitary Permit to Operate</p>
        </div>
   </div>
   <br>
   <br>
   <p style="font-size:12px;">Issued to <input style="width:460px;" class="sm permit-info text-center template-detail" name="business-name"></p>
   <p style="font-size:10px;" class="text-center">(Registered Name)</p>
   <p><input style="width:512px;" class="sm permit-info text-center permit-info" name="" data-field="establishment_type"></p>
   <p style="font-size:10px;" class="text-center">Type of Establishment</p>
   <p style="font-size:12px;">Address &nbsp<input style="width:461px;" class="sm permit-info text-center template-detail" name="address"></p>
   <br>
   <p style="font-size:12px;">Sanitary Permit No.<input style="width:100px;" class="sm permit-info text-center template-detail" name="permit-no"> &nbsp ,&nbsp  &nbsp Date Issued  <input style="width:213px;" class="sm permit-info text-center template-detail" name="date-issued"></p>
   <p style="font-size:12px;">Date of Expiration  <input style="width:180px;" class="sm permit-info text-center template-detail" name="date-expiry"></p>
   <div style="line-height:.5em;">
   <p style="font-size:12px; font-weight:bold;"><i>This permit is not transferable and will be revoked for violation of the Sanitary Rules,</i></p>
   <p style="font-size:12px; font-weight:bold;"><i>Laws or Regulation of P.D. 522 & P.D. 856 and Pertinent Local Ordinances.</i></p>
   </div>
   
   <br>
   
    <div class="row">
        <div class="column" style="float:left; ">
                    <p>Inspected by:</p>
                    <p> <input style="width:220px;" class="sm permit-info text-center officer" name=""  data-field="inspected_by"></p>
                    <p class="text-center">SANITARY INSPECTOR III</p>
                    <p>Recommending Approval:</p>
                    <p><input style="width:220px;" class="sm permit-info text-center officer" type="text" name="" value="SUZZETTE A. DELOS SANTOS" data-field="recommend_by"></p>
                    <p style="text-align: center;">SANITARY INSPECTOR V</p>
        </div>
        <div class="column" style="float:right;">
                    <p>Reviewed by: </p>
                    <p><input style="width:240px;" class="sm permit-info text-center officer" type="text" value="JEANNIE VIC P. TARRAZONA" name="" data-field="reviewed_by"></p>
                    <p class="text-center">SANITARY INSPECTOR III</p>
                    <p>Approved by:</p>
                    <p><input style="width:240px;" class="sm permit-info text-center officer" type="text" value="HILDEGARDE B. MADALAG, M.D." name="" data-field="approved_by"></p>
                    <p class="text-center">CITY HEALTH OFFICER</p>
        </div>
    </div>
    <p style="opacity: 0;">Space here</p> 
    <p style="font-size:10px; bottom:1px;" class="text-center"><b>This Sanitary Permit shall be posted in a conspicuous place in the establishment</b></p>
</div>
<script>
$('.template-detail').attr('disabled', 'disabled');
</script>