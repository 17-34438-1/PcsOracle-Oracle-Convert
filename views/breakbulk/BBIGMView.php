<script src="<?php echo ASSETS_PATH; ?>vendor/jquery/jquery.js"></script>
<script>
var tableRowIndex = 0;
var value = "";
var rot="";
$(document).ready(function() {
            var table = $('#datatable-default').DataTable();
            $('#datatable-default tbody').on('click', 'tr', function() {
                
                console.log(table.row(this).data());
                const myArray = table.row(this).data();
                rot=myArray[11];
                document.getElementById("rotation").value = myArray[11];
                document.getElementById("imp_voyage").value = myArray[19];
                document.getElementById("exp_voyage").value=myArray[11];
                document.getElementById("vslName").value = myArray[18];
                
            });
     
       
      $('#vesselDeclarationModel').on('show.bs.modal', function(event) {
        console.log("Clicked model data==" +rot);
        $.ajax({
            url: "<?php echo site_url(); ?>/breakbulk/BBIGMController/GETVessselInfo",
            type: "POST",
            cache: false,
            data: {
                rotation: rot
            },
            success: function(data) {
               var response = JSON.parse(data);
               console.log("Vessel Data");
               console.log(response);
               if(response.length>0){
                document.getElementById("grt").value=response[0]['GROSS_REGISTERED_TON'];
                document.getElementById("nrt").value=response[0]['NET_REGISTERED_TON'];
                document.getElementById("imo_no").value=response[0]['LLOYDS_ID'];
                document.getElementById("loa").value=response[0]['LOA_CM'];
                document.getElementById("flag").value=response[0]['CNTRY_NAME'];
                document.getElementById("call_sign").value=response[0]['RADIO_CALL_SIGN'];
                document.getElementById("beam").value=response[0]['BEAM_CM'];
               }
            }
        });
    });
});
</script>

<div class="modal fade bd-example-modal-lg" tabindex="-1" role="dialog" aria-hidden="true" id="vesselDeclarationModel">
    <div class="modal-dialog modal-lg">
        <div class="modal-content" style="background-color:#D3D3D3;">
            <!-- Modal Header -->
            <div class="modal-header">
                <center>
                    <h4 class="modal-title"><b>VESSEL DECLARATION</b></h4>
                </center>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <form class="form-horizontal form-bordered" method="POST"
                    action="<?php echo site_url('breakbulk/BBIGMController/VesselDeclaratonUpload');?>"
                    id="myform" name="myform" enctype="multipart/form-data">
                    <div class="form-group">
                        <!-- <label class="col-md-6 control-label">&nbsp;</label> -->
                        <div class="row">
                            <div class="col-md-2"></div>
                            <div class="col-md-4">
                                <span><b>Rotation</b> <span class="required">* </span>:</span>
                                <input type="text" name="rotation" id="rotation" class="form-control" value="" required>
                            </div>
                            <div class="col-md-4">
                                <span><b>IMP Voyage</b> <span class="required">* </span>:</span>
                                <input type="text" name="imp_voyage" id="imp_voyage" class="form-control" value=""
                                    required>
                            </div>
                            <div class="col-md-2"></div>
                        </div>
                        <div class="row">
                            <div class="col-md-2"></div>
                            <div class="col-md-4">
                                <span><b>EXP Voyage</b> <span class="required">* </span>:</span>
                                <input type="text" name="exp_voyage" id="exp_voyage" class="form-control" value=""
                                    required>
                            </div>
                            <div class="col-md-4">
                                <span><b>Vessel Name</b><span class="required">* </span>:</span>
                                <input type="text" name="vslName" id="vslName" class="form-control" value="" required>
                            </div>
                            <div class="col-md-2"></div>
                        </div>

                        <div class="row">
                            <div class="col-md-2"></div>
                            <div class="col-md-4">
                                <span><b>GRT</b><span class="required">* </span>:</span>
                                <input type="text" name="grt" id="grt" class="form-control" value="" required>
                            </div>
                            <div class="col-md-4">
                                <span><b>NRT</b><span class="required">* </span>:</span>
                                <input type="text" name="nrt" id="nrt" class="form-control" value="" required>
                            </div>
                            <div class="col-md-2"></div>
                        </div>
                        <div class="row">
                            <div class="col-md-2"></div>
                            <div class="col-md-4">
                                <span><b>IMO No.</b><span class="required">* </span>:</span>
                                <input type="text" name="imo_no" id="imo_no" class="form-control" value="" required>
                            </div>
                            <div class="col-md-4">
                                <span><b>LOA</b><span class="required">* </span>:</span>
                                <input type="text" name="loa" id="loa" class="form-control" value="" required>
                            </div>
                            <div class="col-md-2"></div>
                        </div>
                        <div class="row">
                            <div class="col-md-2"></div>
                            <div class="col-md-4">
                                <span><b>Flag</b><span class="required">* </span>:</span>
                                <input type="text" name="flag" id="flag" class="form-control" value="" required>
                            </div>
                            <div class="col-md-4">
                                <span><b>Call Sign</b><span class="required">* </span>:</span>
                                <input type="text" name="call_sign" id="call_sign" class="form-control" value=""
                                    >
                            </div>
                            <div class="col-md-2"></div>
                        </div>
                        <div class="row">
                            <div class="col-md-2"></div>

                            <div class="col-md-4">
                                <span><b>Beam</b><span class="required">* </span>:</span>
                                <input type="text" name="beam" id="beam" class="form-control" value="" >
                            </div>
                            <div class="col-md-4">
                                <span><b>Upload File</b><span class="required">* </span>:</span>
                                <input type="file" name="vesselDec" id="vesselDec" class="form-control" value=""
                                    required>
                            </div>
                            <div class="col-md-2"></div>
                        </div>
                        <div class="row">
                            <div class="col-sm-12 text-center">
                                <!--button type="button" class="mb-xs mt-xs mr-xs btn btn-primary">Primary</button-->
                                <button type="submit" name="submit_login"
                                    class="mb-xs mt-xs mr-xs btn btn-success">SAVE</button>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-12 text-center">
                                <?php echo $msg;?>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <!-- Modal footer -->
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>


<section section role="main" class="content-body">
    <header class="page-header">
        <h2><?php echo $title;?></h2>
    </header>


    <div class="row">
        <div class="col-lg-12">
            <?php
$UserId=$this->session->userdata('Control_Panel');
?>
            <section class="panel">
                <div class="panel-body">
                    <div>
                        <?php
				echo form_open(base_url().'index.php/breakbulk/BBIGMController/myListSearchBB',@$attributes);
				$Stylepadding = 'style="padding: 12px 20px;"';
				if(!empty($error_message))
				{
					$Stylepadding = 'style="padding:25px 20px;"';
				}	
				if(isset($captcha_image)){
					$Stylepadding = 'style="padding:62px 20px 93px;"';
				}
			?>
                        <b>Search</b>
                        <?php 
				$location_options = array(
					'VName' =>'Vessel Name',
					'Voy' =>'Voyage No',
					'Import' =>'Import Rot',
					'Export' =>'Export Rot',
					'port' =>'Port of Shipment',
					'All' =>'All',
				);
				echo form_dropdown('SearchCriteria', $location_options, $this->input->post('SearchCriteria'));
			?>
                        <?php 
				$attribute = array('name'=>'Searchdata','id'=>'SearchID','class'=>'login_input_text','required' => 'required');
				echo form_input($attribute,set_value('Searchdata'));
				//'onblur'=> "alert();"
			?>
                        <?php $arrt = array('name'=>'SearchD','id'=>'submit','value'=>'Go','class'=>'login_button'); echo form_submit($arrt);?>
                        <input type="hidden" name="type" value="<?php echo $type; ?>">
                        <?php form_close();?>
                    </div>
                    <hr>

                    <table class="table table-bordered table-hover table-striped mb-none" id="datatable-default">
                        <thead>
                            <td style="display:none;"></td>
                            <td style="display:none;"></td>
                            <td style="display:none;"></td>
                            <td style="display:none;"></td>
                            <td style="display:none;"></td>
                            <td style="display:none;"></td>
                            <td style="display:none;"></td>
                            <td style="display:none;"></td>
                            <th>Agent Type</th>
                            <th>Shipping Agent</th>
                            <th>License Number</th>
                            <th>Import Rotation No</th>
                            <th>Export Rotation No</th>
                            <th>Sailed Year</th>
                            <th>Sailed Date</th>
                            <th>Expected Date Of Arrival</th>
                            <th>Actual Berth Date</th>
                            <th>Final Entry Date</th>
                            <th>Vessel Name</th>
                            <th>Voy No</th>
                            <th>Net Tonnage</th>
                            <th>Name of Master</th>
                            <th>Port of Depart</th>
                            <th>Port of Destination</th>
                            <th>Submission Date</th>
                            <th>Action (Import)</th>
                            </tr>
                        </thead>
                        <?php
                        if(@$igmMasterList) {
                            $len=count($igmMasterList);
    
                        for($i=0;$i<$len;$i++){
                        // $vvdGkey = $rowVvd->VVD_GKEY;
                        $id=$igmMasterList[$i]['id'];
                        $vvdGkey = 9513295;			 	
	    	?>
                        <tr>
                            <td style="display:none;"> <?php echo $igmMasterList[$i]['VoyNoExp']; ?></td>
                            <td style="display:none;"> <?php echo $igmMasterList[$i]['grt']; ?></td>
                            <td style="display:none;"> <?php echo $igmMasterList[$i]['nrt']; ?></td>
                            <td style="display:none;"> <?php echo $igmMasterList[$i]['imo']; ?></td>
                            <td style="display:none;"> <?php echo $igmMasterList[$i]['loa_cm']; ?></td>
                            <td style="display:none;"> <?php echo $igmMasterList[$i]['flag']; ?></td>
                            <td style="display:none;"> <?php echo $igmMasterList[$i]['radio_call_sign']; ?></td>
                            <td style="display:none;"> <?php echo $igmMasterList[$i]['beam_cm']; ?></td>
                            <td><?php echo $igmMasterList[$i]['Submitee_Org_Type']; ?></td>
                            <td style="vertical-align: middle;  vertical-align: middle;">
                                <?php print($igmMasterList[$i]['org_name']); ?></td>
                            <td><?php print($igmMasterList[$i]['S_Org_License_Number']); ?></td>
                            <td><?php print($igmMasterList[$i]['Import_Rotation_No']); ?></td>
                            <td><?php print($igmMasterList[$i]['Export_Rotation_No']); ?></td>
                            <td><?php print($igmMasterList[$i]['Sailed_Year']); ?></td>
                            <td><?php print($igmMasterList[$i]['Sailed_Date']); ?></td>
                            <td><?php print($igmMasterList[$i]['ETA_Date']); ?></td>
                            <td><?php print($igmMasterList[$i]['Actual_Berth']); ?></td>
                            <td><?php print($igmMasterList[$i]['file_clearence_date']); ?></td>
                            <td><?php print($igmMasterList[$i]['Vessel_Name']); ?></td>
                            <td><?php print($igmMasterList[$i]['Voy_No']); ?></td>
                            <td><?php print($igmMasterList[$i]['Net_Tonnage']); ?></td>
                            <td><?php print($igmMasterList[$i]['Name_of_Master']); ?></td>
                            <td><?php print($igmMasterList[$i]['Port_of_Shipment']); ?></td>
                            <td><?php print($igmMasterList[$i]['Port_of_Destination']); ?></td>
                            <td><?php print($igmMasterList[$i]['Submission_Date']); ?></td>
                            <td>
                                <?php $nmrwVvd=1;
					                if($nmrwVvd>0){
					            ?>
                                <a href="<?php echo site_url('uploadExcel/impBayViewPerformed') ?>?vvdGkey=<?php echo $vvdGkey;?>"
                                    target="_BLANK" class="blink_me">Import Vessel Layout</a>
                                <hr>
                                <?php } ?>
                                <a href="<?php echo site_url("breakbulk/BBIGMController/BBIGMSubDetails/$id/$type") ?>"
                                    target="_BLANK">View IGM Sub Detail</a>
                                <hr>

                                <?php
					                if($UserId==93){
					            ?>
                                    <a data-target="#vesselDeclarationModel" data-toggle="modal" href="#vesselDeclarationModel">Upload Vessel Declaration</a>
                                    <?php } ?>
                                <?php } }?>


                            </td>
                        </tr>
                    </table>
                </div>
            </section>
        </div>
    </div>

</section>
