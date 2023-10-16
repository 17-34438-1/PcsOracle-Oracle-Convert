<style>
    th, td{
        padding:5px;
    }
</style>

<section role="main" class="content-body">
	<header class="page-header">
		<h2><?php echo $title;?></h2>
	</header>

	<!-- start: page -->
    <div class="content">
    <div class="content_resize">
      <div class="mainbar">
        <div class="article">
			<div class="row">
				<div class="col-lg-12">						
					<section class="panel">
						<div class="panel-body">
						<form class="form-horizontal form-bordered" method="POST" action="<?php echo base_url().'index.php/IgmViewController/shedDeliveryOrderInfoData'; ?>" id="myform" name="myform" onsubmit="return validate()">
							<div class="form-group">
								<label class="col-md-3 control-label">&nbsp;</label>
								<div class="col-md-6">		
									<div class="input-group mb-md">
										<span class="input-group-addon span_width">Rotation No <span class="required">*</span></span>
										<input type="text" name="rotNo" id="rotNo" class="form-control" placeholder="Rotation No">
									</div>
									<div class="input-group mb-md">
										<span class="input-group-addon span_width">BL No <span class="required">*</span></span>
										<input type="text" name="blno" id="blno" class="form-control" placeholder="BL No">
									</div>												
								</div>
																				
								<div class="row">
									<div class="col-sm-12 text-center">
										<!--button type="button" class="mb-xs mt-xs mr-xs btn btn-primary">Primary</button-->
										<button type="submit" name="submit_login" class="mb-xs mt-xs mr-xs btn btn-success">Search</button>
									</div>													
								</div>
								<div class="row">
									<div class="col-sm-12 text-center">
                                        <p align="center">
                                            <?php echo $msg;?>
                                        </p>
									</div>
								</div>
							</div>	
						</form>

                        <hr/>

                        <?php if($frmType=="search" or $frmType=="inserted" or $frmType=="deleted") {  
                            if($resltBE=="") { echo "<p align='center'>".$msgBLsearch."</p>"; } else { ?>
                            <form method="POST" enctype="multipart/form-data" action="<?php echo site_url("IgmViewController/shedDeliveryOrderInfoEntry") ?>">
							<div class="table-responsive">
                            <table border="1" style="border-collapse:collapse;margin-bottom:20px;" align="center" width="100%">
                                    <tr>
                                        <th colspan="4" valign="center" align="center" style="font-size:20px;"><b>DELIVERY ORDER</b></th>
                                        <td colspan="2" valign="center"><strong>BL No:</strong> <?php echo $doInfo[0]['BL_No'];?></td>
                                    </tr>
                                    <tr>
                                        <td rowspan="5" colspan="3" valign="top">			
                                            <strong>Notify Party(Complete Name & Address)</strong>
                                            <p>
                                                <?php echo $doInfo[0]['Notify_name'];?><br/>
                                                <?php echo $doInfo[0]['Notify_address'];?>
                                            </p>
                                        </td>
                                        <td>
                                            <b>Vessel</b><br/><?php echo $doInfo[0]['Vessel_Name'];?>
                                        </td>
                                        <td>
                                            <b>Voyage No</b><br/><?php echo $doInfo[0]['Voy_No'];?>
                                        </td>
                                        <td>
                                            <b>Print Date</b><br/><?php echo date("Y-m-d H:i:s"); ?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <b>Place of Receipt</b><br/>CHATTOGRAM PORT AUTHORITY
                                        </td>
                                        <td colspan="2" rowspan="4" valign="top">
                                            <strong>Other Numbering Identification</strong><br/>
                                            <p>
                                                <font style="margin-left:2px;margin-right:4px;color:#0865fc;"><b>Reg No:</b></font> <?php echo $doInfo[0]['Import_Rotation_No'];?><br/>
                                            </p>
                                            <p>
                                                <font style="margin-left:2px;margin-right:4px;color:#0865fc;"><b>BE No:</b></font> <?php echo $doInfo[0]['Bill_of_Entry_No'];?><br/>
                                            </p>
                                            <p>
                                                <font style="margin-left:4px;margin-right:4px;color:#0865fc;"><b>Date:</b></font> <?php echo $doInfo[0]['Submission_Date'];?><br/>
                                            </p>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <b>Port of Loading</b><br/>
                                            <?php echo $doInfo[0]['port_of_origin']." ".$doInfo[0]['Port_of_Shipment'];?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <b>Port of Discharge</b><br/>
                                            CHATTOGRAM,BANGLADESH, Bangladesh
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <b>Place of Delivery</b><br/>
                                            &nbsp;
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="3" valign="top">
                                            <strong>Consignee(Complete Name & Address)</strong>
                                            <p>
                                                <?php echo $doInfo[0]['Consignee_name'];?><br>
                                                <?php echo $doInfo[0]['Consignee_address'];?>
                                            </p>
                                        </td>
                                        <td colspan="3" valign="top">
                                            <strong>Shipper/Exporter(Complete Name & Address)</strong>
                                            <p>
                                                        
                                            </p>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="4" valign="top">
                                            <strong>Kind of Packages,Description of Goods ,Marks and Numbers, Container No/Seal No.</strong>
                                            <p>
                                                <?php echo $doInfo[0]['Description_of_Goods'];?><br/>
                                            </p>
                                            <p>
                                                <?php echo $doInfo[0]['Pack_Description'];?><br/>
                                            </p>
                                            <p>
                                                <?php echo $doInfo[0]['Pack_Marks_Number'];?><br/>
                                            </p>
                                        </td>
                                        <td valign="top" align="center">
                                            <b>Gross Weight</b>
                                            <!--p>
                                                <?php echo $doInfo[0]['weight'].$doInfo[0]['weight_unit'];?>
                                            </p-->
                                            
                                            <p>
                                                <nobr><input type="text" name="deliveredWeight" id="deliveredWeight" value="<?php echo $doInfo[0]['weight'];?>" 
                                                    style="text-align:center; border:1px solid blue;width:100px;" autocomplete="off" <?php if($edit=="edit"){echo "readonly";} ?> required>
                                                <input type="text" value="<?php echo $doInfo[0]['weight_unit'];?>" 
                                                    style="text-align:center; border:1px solid blue;width:50px;" autocomplete="off" <?php if($edit=="edit"){echo "readonly";} ?> required></nobr>
                                            </p>
                                        </td>
                                        <td valign="top" align="center">
                                            <b>Measurement</b>
                                            <p>
                                                <input type="text" name="measurement" id="measurement" 
                                                    style="text-align:center; border:1px solid blue;width:100px;" autocomplete="off" 
                                                        value="<?php if($edit == "edit"){echo $shedInfoById[0]['measurement'];} ?>" required>
                                            </p>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="3" valign="top" align="left">
                                            <p>
                                                <b>Delivered to CNF Agent: </b><!--onblur="getcnf(this.value)"-->
                                                <input type="text" name="cnflic" id="cnflic"
                                                    style="text-align:center; border:0px solid blue;width:100px;" 
                                                        value="<?php if($edit == "edit"){echo $shedInfoById[0]['cnf_lic_no'];} else { echo $cnfLic;} ?>" 
                                                            readonly><br><br>
                                                <b>CNF Name:  </b>
                                                <input type="text" name="cnfname" id="cnfname" value="<?php echo $cnf_name[0]['name'];?>"
                                                    style="text-align:center; border:0px solid blue;width:250px;font-weight:bold;" readonly>
                                            </p>
                                        </td>									
                                        <td align="center">
                                            <b>Valid upto: </b>
                                            <input type="date" name="valid_upto" id="valid_upto" style="width:150px;" 
                                                value="<?php if($edit == "edit"){echo $shedInfoById[0]['valid_upto_dt'];} ?>" required>
                                        </td>
                                        <td colspan="2" align="center">
                                            <input type="file" name="dofile" id="dofile" style="width:200px;" 
                                                <?php if($edit=="edit") { echo " ";} else { echo "required";}?>>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="6">
                                            <table border="1" style="border-collapse:collapse;" align="center" width="100%">
                                                <tr>
                                                    <th>Container No</th>
                                                    <th>Seal No</th>
                                                    <th>Size/Type/Height</th>
                                                    <th>Weight</th>
                                                    <th>Pack Number</th>
                                                </tr>
                                                <?php for($i=0;$i<count($contList);$i++) { ?>
                                                <tr align="center">
                                                    <td><?php echo $contList[$i]['cont_number'];?></td>
                                                    <td><?php echo $contList[$i]['cont_seal_number'];?></td>												
                                                    <td>
                                                        <?php echo $contList[$i]['cont_size']."/".$contList[$i]['cont_type']."/".$contList[$i]['cont_height'];?>
                                                    </td>
                                                    <td><?php echo $contList[$i]['cont_weight'];?></td>
                                                    <td><?php echo $contList[$i]['Pack_Number'];?></td>
                                                </tr>
                                                <?php } ?>
                                            </table>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="6" align="center">
                                            <p>
                                                <input type="hidden" name="igm_dtl" id="igm_dtl" value="<?php echo $doInfo[0]['dtl_id'];?>">
                                                <input type="hidden" name="blno" id="blno" value="<?php echo $doInfo[0]['BL_No'];?>">
                                                <input type="hidden" name="rotno" id="rotno" value="<?php echo $doInfo[0]['Import_Rotation_No'];?>">
                                                <input type="hidden" name="beno" id="beno" value="<?php echo $doInfo[0]['Bill_of_Entry_No'];?>">
                                                <input type="hidden" name="grossQty" id="grossQty" value="<?php echo $doInfo[0]['weight'];?>">
                                                <!-- <input type="hidden" name="CODE" id="CODE" value="<?php echo $CODE;?>"> -->
                                                <!-- <input type="hidden" name="type_of_Igm" id="type_of_Igm" value="<?php echo $type_of_Igm;?>"> -->
                                                <input type="hidden" name="type_of_bl" id="type_of_bl" value="<?php echo $type_of_bl;?>">
                                                <?php if($edit == "edit") { ?>
                                                    <input type="hidden" name="editId" id="editId" value="<?php echo $editId;?>">
                                                    <input type="hidden" name="update" id="update" value="update">
                                                    <button type="submit" class="btn btn-primary" style="margin-top:10px;">Update</button>
                                                <?php }else { ?>
                                                    <button type="submit" class="btn btn-primary" style="margin-top:10px;">Submit</button>
                                                <?php } ?>
                                            </p>
                                        </td>
                                    </tr>
                                </table>
                                </div>
                            </form>				
                        <?php } 
							} 
						?>
                        </div>
                    </section>
                </div>
            </div>
        </div>		 
        </div>
    </div>		 
</div>
</section>