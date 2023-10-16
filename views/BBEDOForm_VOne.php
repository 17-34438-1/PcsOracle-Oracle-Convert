<script>
	function chkConfirm()
	{
		if (confirm("Do you want to save ?") == true)
			{
				return true ;
			}
		else
			{
				return false;
			}		
	}
</script>
<section role="main" class="content-body">
		<header class="page-header">
			<h2><?php echo $title;?></h2>
		
			<div class="right-wrapper pull-right">
				
			</div>
		</header>

		<!-- start: page -->
			<div class="row">
				<div class="col-lg-12">						
					<section class="panel">
						
						<div class="panel-body">
							<div class="row">
								<div class="col-sm-12 text-center">
									<?php echo $msg;?>
								</div>
							</div>
							<form class="form-horizontal form-bordered" method="POST" enctype="multipart/form-data" 
								onsubmit="return chkConfirm();" action="<?php echo site_url("EDOController/bbEDOUpload");?>">
								<div class="form-group">								
									<div class="col-md-4">
										<div class="input-group mb-md">
											<span class="input-group-addon span_width">Rotation No <span class="required">*</span></span>
											<input type="text" name="rotNo" id="rotNo" class="form-control" placeholder="Rotation No" 
												value="<?php echo $rotNo; ?>" readonly required>
										</div>	
										<div class="input-group mb-md">
											<span class="input-group-addon span_width">BE No<span class="required">*</span></span>
											<input type="text" name="beNo" id="beNo" class="form-control" placeholder="BE No" 
											value="<?php if($edit=="edit" or $edit=="extendValidity"){echo $Bill_of_Entry_No_Val;}else {echo $beNo;}?>" >
										</div>
									</div>
									<div class="col-md-4">
										<div class="input-group mb-md">
											<span class="input-group-addon span_width">BL No</span>
											<input type="text" name="blno" id="blno" class="form-control" placeholder="BL No" 
												value="<?php echo $blno; ?>" readonly required>
										</div>	
										<div class="input-group mb-md">
											<span class="input-group-addon span_width">BE Date </span>
											<input type="date" name="beDate" id="beDate" class="form-control" placeholder="BE Date" 
												value="<?php if($edit=="edit" or $edit=="extendValidity"){echo $BE_Dt_Val;} else {echo $beDate;}?>">
										</div>
									</div>
									<div class="col-md-4">
										<div class="input-group mb-md">
											<span class="input-group-addon span_width">Line No</span>
											<input type="text" name="line_no" id="line_no" class="form-control" placeholder="Line No"
												value="<?php if($edit=="edit" or $edit=="extendValidity"){ echo $line_no;}?>">
										</div>	
										<div class="input-group mb-md">
											<span class="input-group-addon span_width">Submission Date <span class="required">*</span></span>
											<input type="text" name="Submission_Date" id="Submission_Date" class="form-control" placeholder="Submission Date" 
												value="<?php echo $Submission_Date; ?>" readonly>
										</div>
									</div>
									<div class="col-md-8">										
										<div class="input-group mb-md">
											<span class="input-group-addon span_width">C&F Name <span class="required">*</span></span>
											<input type="text" name="cnfName" id="cnfName" class="form-control" placeholder="C&F No" 
												value="<?php echo $cnfName; ?>" readonly>
										</div>
									</div>
									<div class="col-md-4">										
										<div class="input-group mb-md">
											<span class="input-group-addon span_width">License No <span class="required">*</span></span>
											<input type="text" name="cnfLicenseNo" id="cnfLicenseNo" class="form-control" placeholder="C&F License No" 
												value="<?php echo $cnfLicenseNo; ?>" readonly>
										</div>
									</div>
									<div class="col-md-4">
										<div class="input-group mb-md">
											<span class="input-group-addon span_width">Receipt No</span>
											<input type="text" name="receipt_no" id="receipt_no" class="form-control" placeholder="Receipt No"
												value="<?php if($edit=="edit" or $edit=="extendValidity"){ echo $receipt_no;}?>">
										</div>
									</div>
									<div class="col-md-4">	
										<div class="input-group mb-md">
											<span class="input-group-addon span_width">Receipt Date</span>
											<input type="date" name="receipt_date" id="receipt_date" class="form-control" placeholder="Receipt Date" 
												value="<?php if($edit=="edit" or $edit=="extendValidity"){ echo $receipt_date;}?>">
										</div>
									</div>
									<div class="col-md-4">	
										<div class="input-group mb-md">
											<span class="input-group-addon span_width">From Port <span class="required">*</span></span>
											<input type="text" name="from_port" id="from_port" class="form-control" placeholder="Port of Origin" 
											value="<?php echo $port_of_origin." ".$Port_of_Shipment;?>" readonly>
										</div>
									</div>
									<div class="col-md-4">										
										<div class="input-group mb-md">
											<span class="input-group-addon span_width">Vessel <span class="required">*</span></span>
											<input type="text" name="Vessel_Name" id="Vessel_Name" class="form-control" placeholder="Vessel Name" 
												value="<?php echo $Vessel_Name; ?>" readonly>
										</div>
									</div>
									<div class="col-md-4">										
										<div class="input-group mb-md">
											<span class="input-group-addon span_width">Voyage No <span class="required">*</span></span>
											<input type="text" name="Voy_No" id="Voy_No" class="form-control" placeholder="C&F License No" 
												value="<?php echo $Voy_No; ?>" readonly>
										</div>
									</div>
									<div class="col-md-4">										
										<div class="input-group mb-md">
											<span class="input-group-addon span_width">Remarks</span>
											<input type="text" name="remarks" id="remarks" class="form-control" placeholder="Remarks"
												value="<?php if($edit=="edit" or $edit=="extendValidity"){ echo $remarks;}?>">
										</div>
									</div>
									<div class="col-md-12 table-responsive">
										<div class="row">
											<div class="col-sm-12 text-center">
												<table border="1" class="table table-responsive table-bordered" style="border-collapse:collapse;margin-bottom:20px;margin-top:20px;" align="center" width="100%">
													<tr>
														<th valign="top" class="text-center">MARKS AND NUMBER </th>
														<th valign="top" class="text-center">QUANTITY </th>
														<th valign="top" class="text-center"><nobr>KIND OF PACKAGES</nobr></th>
														<th valign="top" class="text-center">DESCRIPTION OF GOODS</th>
														<th valign="top" class="text-center">GROSS WEIGHT</th>
													</tr>
													<tr>
														<td align="center"><?php echo substr($Pack_Marks_Number,0,100);?></td>
														<td align="center"><?php echo $igm_pack_number;?></td>
														<td align="center"><?php echo $Pack_Description;?></td>
														<td valign="top" align="center"><?php echo substr($Description_of_Goods,0,100);?></td>
														<td align="center"><?php echo $weight." ".$weight_unit;?></td>
													</tr>
												</table>
											</div>
										</div>
									</div>
									<div class="row">
										<div class="col-sm-12 text-center">
										
											<input type="hidden" name="igm_dtl" id="igm_dtl" value="<?php echo $dtl_id;?>">
											<input type="hidden" name="type_of_bl" id="type_of_bl" value="<?php echo $type_of_bl;?>">
											<input type="hidden" name="igm_type" id="igm_type" value="<?php echo $igm_type;?>">
											<input type="hidden" name="grossQty" id="grossQty" value="<?php echo $weight;?>">
											<input type="hidden" name="edo_id" id="edo_id" value="<?php echo $edo_id;?>">
											
											<?php if($edit=="edit") { ?>
											<input type="hidden" name="editId" id="editId" value="<?php echo $editId;?>">
											<input type="hidden" name="update" id="update" value="update">
											<?php } ?>
											
											<button type="submit" name="submit_login" id="submit" class="mb-xs mt-xs mr-xs btn btn-primary">
												<?php if($edit=="edit") { ?>UPDATE<?php } else { ?> SAVE <?php } ?>
											</button>
										</div>													
									</div>
								</div>	
							</form>
						</div>
					</section>
			
				</div>
			</div>	
		<!-- end: page -->
	</section>
	</div>