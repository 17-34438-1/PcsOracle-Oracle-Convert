<script src="<?php echo ASSETS_PATH; ?>vendor/jquery/jquery.js"></script>
<script>
var tableRowIndex = 0;
var value = "";
var igmId = "";
$(document).ready(function() {
    var table = $('#default-datatable').DataTable();
    $('#default-datatable tbody').on('click', 'tr', function() {
        console.log(table.row(this).data());
        const myArray = table.row(this).data();
        igmId = myArray[26];
        document.getElementById("rotation").value = myArray[9];
        document.getElementById("imp_voyage").value = myArray[11];
        document.getElementById("exp_voyage").value = myArray[0];
        document.getElementById("vslName").value = myArray[10];
        document.getElementById("grt").value = myArray[1];
        document.getElementById("nrt").value = myArray[2];
        document.getElementById("loa").value = myArray[4];
        document.getElementById("imo_no").value = myArray[3];
        document.getElementById("flag").value = myArray[5];
        document.getElementById("call_sign").value = myArray[6];
        document.getElementById("beam").value = myArray[7];
    });

    $('#vesselDeclarationModel').on('show.bs.modal', function(event) {
        // console.log("Clicked model data==");
        $.ajax({
            url: "<?php echo site_url(); ?>/breakbulk/BBVesselOperationController/BBVesselDeclarationViewUpdate",
            type: "POST",
            cache: false,
            data: {
                igm_id: igmId
            },
            success: function(dataResult) {
                if (dataResult == 1) {
                    console.log("Sucessfully Updated");
                    //location.reload();					
                } else {
                    console.log("Error not Updated");
                }
            }
        });
    });

    $('#closeModal').on('click', function() {
        //console.log("Model close button clicked");
        location.reload();
    });

});

// function ajaxPost(str, plArrayC){
//     var xmlhttp;
//     if (window.XMLHttpRequest){xmlhttp = new XMLHttpRequest();}
//     else{xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");}
//     xmlhttp.open("POST",str,true);
//     xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
//     xmlhttp.send('Array=' + plArrayC);
// }



function getdownload() {
    $(document).ready(function() {
        $.ajax({
            url: "<?php echo site_url(); ?>/breakbulk/BBVesselOperationController/BBVesselDeclarationViewUpdate",
            type: "POST",
            cache: false,
            data: {
                igm_id: igmId
            },
            success: function(dataResult) {
                if (dataResult == 1) {
                    console.log("Sucessfully Updated");
                    //location.reload();					
                } else {
                    console.log("Error not Updated");
                }
            }

        });
    });
}
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
                <form class="form-horizontal form-bordered" method="POST" action="" id="myform" name="myform"
                    enctype="multipart/form-data">
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
                                    required>
                            </div>
                            <div class="col-md-2"></div>
                        </div>
                        <div class="row">
                            <div class="col-md-2"></div>

                            <div class="col-md-4">
                                <span><b>Beam</b><span class="required">* </span>:</span>
                                <input type="text" name="beam" id="beam" class="form-control" value="" required>
                            </div>
                            <div class="col-md-4">
                                <!-- <span><b>Upload File</b><span class="required">* </span>:</span>
                                <input type="file" name="vesselDec" id="vesselDec" class="form-control" value=""
                                    required> -->
                            </div>
                            <div class="col-md-2"></div>
                        </div>
                        <div class="row">
                            <div class="col-sm-12 text-center">
                                <!--button type="button" class="mb-xs mt-xs mr-xs btn btn-primary">Primary</button-->
                                <!-- <button type="submit" name="submit_login"
                                    class="mb-xs mt-xs mr-xs btn btn-success">SAVE</button> -->
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
                <button type="button" class="btn btn-secondary" data-dismiss="modal" id="closeModal">Close</button>
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
                <table class="table table-bordered table-hover table-striped mb-none" id="default-datatable" style="padding:0;">
                    <thead>
                        <tr style="text-align:top;">
                            <td style="display:none;"></td>
                            <td style="display:none;"></td>
                            <td style="display:none;"></td>
                            <td style="display:none;"></td>
                            <td style="display:none;"></td>
                            <td style="display:none;"></td>
                            <td style="display:none;"></td>
                            <td style="display:none;"></td>
                            <th style="display:none;">Agent Type</th>
                            <th style="text-align: center;">Import Rotation No</th>
                            <th style="text-align: center;">Vessel Name</th>
                            <th style="text-align: center;display:top;">Voy No</th>
                            <th style="display:none;">Export Rotation No</th>
                            <th style="text-align: center;">Shipping Agent</th>
                            <th style="display:none;">License Number</th>
                            <th style="display:none;">Sailed Year</th>
                            <th style="display:none;">Sailed Date</th>
                            <th style="text-align: center;">Expected Date Of Arrival</th>
                            <th style="display:none;">Actual Berth Date</th>
                            <th style="display:none;">Final Entry Date</th>
                            <th style="display:none;">Net Tonnage</th>
                            <th style="display:none;">Name of Master</th>
                            <th style="display:none;">Port of Depart</th>
                            <th style="display:none;">Port of Destination</th>
                            <th style="display:none;">Submission Date</th>
                            <th style="text-align: center;">Action</th>
                            <th style="display:none;"></th>
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
                        <td style="display:none;"><?php echo $igmMasterList[$i]['Submitee_Org_Type']; ?></td>
                        <td style="text-align: center;"><?php print($igmMasterList[$i]['Import_Rotation_No']); ?></td>
                        <td style="text-align: center;"><?php print($igmMasterList[$i]['Vessel_Name']); ?></td>
                        <td style="text-align: center;"><?php print($igmMasterList[$i]['Voy_No']); ?></td>
                        <td style="display:none;"><?php print($igmMasterList[$i]['Export_Rotation_No']); ?></td>
                        <td style="text-align: center;">
                            <?php print($igmMasterList[$i]['org_name']); ?></td>
                        <td style="display:none;"><?php print($igmMasterList[$i]['S_Org_License_Number']); ?></td>
                        <td style="display:none;"><?php print($igmMasterList[$i]['Sailed_Year']); ?></td>
                        <td style="display:none;"><?php print($igmMasterList[$i]['Sailed_Date']); ?></td>
                        <td style="text-align: center;"><?php print($igmMasterList[$i]['ETA_Date']); ?></td>
                        <td style="display:none;"><?php print($igmMasterList[$i]['Actual_Berth']); ?></td>
                        <td style="display:none;"><?php print($igmMasterList[$i]['file_clearence_date']); ?></td>
                        <td style="display:none;"><?php print($igmMasterList[$i]['Net_Tonnage']); ?></td>
                        <td style="display:none;"><?php print($igmMasterList[$i]['Name_of_Master']); ?></td>
                        <td style="display:none;"><?php print($igmMasterList[$i]['Port_of_Shipment']); ?></td>
                        <td style="display:none;"><?php print($igmMasterList[$i]['Port_of_Destination']); ?></td>
                        <td style="display:none;"><?php print($igmMasterList[$i]['Submission_Date']); ?></td>

                        <?php
                        if($igmMasterList[$i]['file_status']!=0) {         	
	    	             ?>
                        <td style="text-align: center;">
                            <!-- <button type="submit" title="Viewed">
                                <img src="<?php echo  ASSETS_WEB_PATH?>fimg/view.png" alt="buttonpng" border="0" />
                            </button> -->
                            <center>
                                <a data-target="#vesselDeclarationModel" data-toggle="modal"
                                    href="#vesselDeclarationModel" title="viewed"><img
                                        src="<?php echo  ASSETS_WEB_PATH?>fimg/view.png" alt="buttonpng"
                                        border="0" /></a>&nbsp;
                                <a href="/PcsOracle/resources/VesselDeclaration/<?php echo  $igmMasterList[$i]['file_name_stow']?>"
                                    title="Download" onclick="return getdownload()" download>
                                    <img src="<?php echo  ASSETS_WEB_PATH?>fimg/download.svg" alt="buttonpng"
                                        border="0" />
                                </a>
                            </center>
                        </td>
                        <td style="display:none;"> <?php echo $igmMasterList[$i]['id']; ?></td>
                        <?php
                             }else {         	
	    	             ?>
                        <td style="text-align: center;">
                            <!-- <button type="submit" title="View" data-target="#vesselDeclarationModel" data-toggle="modal" href="#vesselDeclarationModel">
                                <img src="<?php echo  ASSETS_WEB_PATH?>fimg/notViewed.png" alt="buttonpng" border="0" />
                            </button> -->
                            <a data-target="#vesselDeclarationModel" data-toggle="modal" href="#vesselDeclarationModel"
                                title="view"><img src="<?php echo  ASSETS_WEB_PATH?>fimg/notViewed.png" alt="buttonpng"
                                    border="0" /></a>
                            &nbsp;
                            <a id="downloadFile" title="Download"
                                href="/PcsOracle/resources/VesselDeclaration/<?php echo  $igmMasterList[$i]['file_name_stow']?>"
                                download>
                                <img src="<?php echo  ASSETS_WEB_PATH?>fimg/download.svg" alt="buttonpng" border="0" />
                            </a>
                        </td>
                        <td style="display:none;"> <?php echo $igmMasterList[$i]['id']; ?></td>

                        <?php }} }?>


                    </tr>


                </table>
        </div>
</section>
</div>
</div>

</section>