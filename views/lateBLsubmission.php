<script>
		function selectAllRot(state)
		{
			var totAllMeasurement = 0;
			var subTotMeasurement = 0;
			var totalRot = document.getElementById('rotCount').value;
			if(state.checked == true)
			{
				//If "All" is not checked;
				for(var p=0;p<totalRot;p++)
				{
					//document.getElementById("rotchk"+p).checked = true;
					document.getElementById("idchk"+p).checked = true;
					//subTotMeasurement = parseFloat(document.getElementById("rotchk"+p).value);
					//totAllMeasurement = parseFloat(totAllMeasurement)+parseFloat(subTotMeasurement);
				}
				// Following line is commented because from now on measurement will not change by clicking on chkbox.....
				// document.getElementById("measurement").value=totAllMeasurement;
			}
			else
			{
				//If "All" is not checked;
				for(var p=0;p<totalRot;p++)
				{
					//document.getElementById("rotchk"+p).checked = false;						
					document.getElementById("idchk"+p).checked = false;						
				}
				// Following line is commented because from now on measurement will not change by clicking on chkbox.....
				// document.getElementById("measurement").value = 0;
			}
		}
</script>

<section role="main" class="content-body">
	<header class="page-header">
		<h2><?php echo $title;?></h2>
	</header>

	<!-- start: page -->
		<div class="row">
			<div class="col-lg-12">						
				<section class="panel">
					<div class="panel-body">
						<form class="form-horizontal form-bordered" method="POST" action="<?php echo site_url('IgmViewController/lateBLsubmissionPerform'); ?>"  id="myform" name="myform">
							<div class="form-group">
								<label class="col-md-3 control-label">&nbsp;</label>
								<div class="col-md-6">		
									<div class="input-group mb-md">
										<span class="input-group-addon span_width">Rotation <span class="required">*</span></span>
										<input type="text" name="rotation" id="rotation" class="form-control" value="">
									</div>
									<div class="input-group mb-md">
										<span class="input-group-addon span_width">Master BL No<span class="required">*</span></span>
										<input type="text" name="BL_No" id="BL_No" class="form-control" value="">
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
										<?php echo $msg;?>
									</div>
								</div>
							</div>	
						</form>
					</div>
				</section>
			</div>
		</div>	
	<!-- end: page -->
	

	<?php if($flag==1) { ?>
	<div class="row">
	<?php include("mydbPConnection.php"); ?>
	<div class="col-lg-12">					
		<div class="panel-body">
			<section class="panel">
			<form class="form-horizontal form-bordered" method="POST" action="<?php echo base_url().'index.php/IgmViewController/lateBLsubmissionAction'; ?>"  id="myform" name="myform" onsubmit="return(validate());">
				      <input type="hidden" id="igm_detail_id" name="igm_detail_id" value="<?php if(@$editFlag==1) echo $igm_data[0]['id']; else "";?>">
				      <input type="hidden" id="igm_master_id" name="igm_master_id" value="<?php if(@$editFlag==1) echo $igm_data[0]['IGM_id']; else "";?>">
				<div class="form-group">
					<div class="col-md-6">
						<div class="input-group mb-md">
							<span class="input-group-addon span_width">Master Line No <span class="required">*</span></span>
							<input type="text" name="master_Line_No" id="master_Line_No" class="form-control" autofocus  tabindex="2" value="<?php if(@$editFlag==1) echo $igm_data[0]['Line_No']; else "";?>" placeholder="Container No">
						</div>
					</div>
					
					<div class="col-md-6">
						<div class="input-group mb-md">
							<span class="input-group-addon span_width">Line No <span class="required">*</span></span>
							<input type="text" name="Line_No" id="Line_No" class="form-control" value="<?php if(@$editFlag==1) echo $igm_data[0]['Line_No']; else "";?>" placeholder="Line No" >
						</div>
					</div>

					<div class="col-md-6">
						<div class="input-group mb-md">
							<span class="input-group-addon span_width">Rotation <span class="required">*</span></span>
							<input type="text" name="Import_Rotation_No" id="Import_Rotation_No" class="form-control" value="<?php if(@$editFlag==1) echo $igm_data[0]['Import_Rotation_No']; else "";?>" placeholder="Container height" >
						</div>
					</div>
					<div class="col-md-6">
						<div class="input-group mb-md">
							<span class="input-group-addon span_width">Master BL No <span class="required">*</span></span>
							<input type="text" name="master_BL_No" id="master_BL_No" class="form-control" value="<?php if(@$editFlag==1) echo $igm_data[0]['BL_No']; else "";?>" placeholder="Container height" >
						</div>
					</div>
					<div class="col-md-6">
						<div class="input-group mb-md">
							<span class="input-group-addon span_width">BL No <span class="required">*</span></span>
							<input type="text" name="BL_No" id="BL_No" class="form-control" value="" placeholder="BL No" >
						</div>
					</div>

					<div class="col-md-6">
						<div class="input-group mb-md">
							<span class="input-group-addon span_width">Pack Number <span class="required">*</span></span>
							<input type="text" name="Pack_Number" id="Pack_Number" class="form-control" value="<?php if(@$editFlag==1) echo $igm_data[0]['Pack_Number']; else "";?>" placeholder="Vessel Name" >
						</div>
					</div>

					<div class="col-md-12">
						<div class="input-group mb-md">
							<span class="input-group-addon span_width">Pack Description <span class="required">*</span></span>
							<textarea class="form-control"  name="Pack_Description" id="Pack_Description" style="height:50px;"> <?php if(@$editFlag==1) echo $igm_data[0]['Pack_Description']; else "";?></textarea>
							<!--input type="text" name="Pack_Description" id="Pack_Description" class="form-control" value="<?php if(@$editFlag==1) echo $igm_data[0]['Pack_Description']; else "";?>" placeholder="Rotation" -->
						</div>
					</div>	
					<div class="col-md-12">
						<div class="input-group mb-md">
							<span class="input-group-addon span_width">Marks Number <span class="required">*</span></span>
							<textarea class="form-control"  name="Pack_Marks_Number" id="Pack_Marks_Number" style="height:50px;"> <?php if(@$editFlag==1) echo $igm_data[0]['Pack_Marks_Number']; else "";?></textarea>
							<!--input type="text" name="Pack_Marks_Number" id="Pack_Marks_Number" class="form-control" value="<?php if(@$editFlag==1) echo $igm_data[0]['Pack_Marks_Number']; else "";?>" placeholder="Marks Number" -->
						</div>
					</div>
					<div class="col-md-12">
						<div class="input-group mb-md">
							<span class="input-group-addon span_width">Desc of Goods <span class="required">*</span></span>
							<textarea class="form-control" name="Description_of_Goods" id="Description_of_Goods" style="height:50px;"> <?php if(@$editFlag==1) echo $igm_data[0]['Description_of_Goods']; else "";?></textarea>
							<!--input type="text" name="Description_of_Goods" id="Description_of_Goods" class="form-control" value="<?php if(@$editFlag==1) echo $igm_data[0]['Description_of_Goods']; else "";?>" placeholder="Desc of Goods" -->
						</div>
					</div>

					<!--div class="col-md-6">
						<div class="input-group mb-md">
							<span class="input-group-addon span_width">Desc of Goods <span class="required">*</span></span>
							<input type="text" name="Description_of_Goods" id="Description_of_Goods" class="form-control" value="<?php if(@$editFlag==1) echo $igm_data[0]['Description_of_Goods']; else "";?>" placeholder="Desc of Goods" >
						</div>
					</div-->
					<div class="col-md-6">
						<div class="input-group mb-md">
							<span class="input-group-addon span_width">Weight <span class="required">*</span></span>
							<input type="text" name="weight" id="weight" class="form-control" value="<?php if(@$editFlag==1) echo $igm_data[0]['weight']; else "";?>" placeholder="Weight" >
						</div>
					</div>
					<div class="col-md-6">
						<div class="input-group mb-md">
							<span class="input-group-addon span_width">BE No <span class="required">*</span></span>
							<input type="text" name="Bill_of_Entry_No" id="Bill_of_Entry_No" class="form-control" value="<?php if(@$editFlag==1) echo $igm_data[0]['Bill_of_Entry_No']; else "";?>" placeholder="BE No" >
						</div>
					</div>
					<div class="col-md-6">
						<div class="input-group mb-md">
							<span class="input-group-addon span_width">BE Date <span class="required">*</span></span>
							<input type="text" name="Bill_of_Entry_Date" id="Bill_of_Entry_Date" class="form-control" value="<?php if(@$editFlag==1) echo $igm_data[0]['Bill_of_Entry_Date']; else "";?>" placeholder="BE Date" >
						</div>
					</div>
					<div class="col-md-6">
						<div class="input-group mb-md">
							<span class="input-group-addon span_width">Remarks <span class="required">*</span></span>
							<input type="text" name="Remarks" id="Remarks" class="form-control" value="<?php if(@$editFlag==1) echo $igm_data[0]['Remarks']; else "";?>" placeholder="Remarks" >
						</div>
					</div><div class="col-md-12">
						<div class="input-group mb-md">
							<span class="input-group-addon span_width">Consignee Desc. <span class="required">*</span></span>
							<textarea class="form-control" name="ConsigneeDesc" id="ConsigneeDesc" style="height:50px;"> <?php if(@$editFlag==1) echo $igm_data[0]['ConsigneeDesc']; else "";?></textarea>
							<!--input type="text" name="ConsigneeDesc" id="ConsigneeDesc" class="form-control" value="<?php if(@$editFlag==1) echo $igm_data[0]['ConsigneeDesc']; else "";?>" placeholder="Consignee Desc" -->
						</div>
					</div>
					<div class="col-md-12">
						<div class="input-group mb-md">
							<span class="input-group-addon span_width">Notify Desc. <span class="required">*</span></span>
							<textarea class="form-control" name="NotifyDesc" id="NotifyDesc" style="height:50px;"> <?php if(@$editFlag==1) echo $igm_data[0]['NotifyDesc']; else "";?></textarea>
							<!--input type="text" name="NotifyDesc" id="NotifyDesc" class="form-control" value="<?php if(@$editFlag==1) echo $igm_data[0]['NotifyDesc']; else "";?>" placeholder="Notify Desc" -->
						</div>
					</div>
					<div class="col-md-6">
						<div class="input-group mb-md">
							<span class="input-group-addon span_width">FF AIN <span class="required">*</span></span>
							<input type="text" name="Submitee_Id" id="Submitee_Id" class="form-control" value="" placeholder="Submitee Id" >
						</div>
					</div>	
					<!--div class="col-md-6">
						<div class="input-group mb-md">
							<span class="input-group-addon span_width">Submitee OrgId <span class="required">*</span></span>
							<input type="text" name="Submitee_Org_Id" id="Submitee_Org_Id" class="form-control" value="<?php if(@$editFlag==1) echo $igm_data[0]['Submitee_Org_Id']; else "";?>" placeholder="Submitee Id" >
						</div>
					</div-->
					<div class="col-md-6">
						<div class="input-group mb-md">
							<span class="input-group-addon span_width">Submission Date <span class="required">*</span></span>
							<input type="date" name="Submission_Date" id="Submission_Date" class="form-control" value="<?php if(@$editFlag==1) echo $igm_data[0]['Submission_Date']; else "";?>" placeholder="Submission_Date" >
						</div>
					</div>
					<!--div class="col-md-6">
						<div class="input-group mb-md">
							<span class="input-group-addon span_width">Type of IGM <span class="required">*</span></span>
							<input type="text" name="type_of_igm" id="type_of_igm" class="form-control" value="<?php if(@$editFlag==1) echo $igm_data[0]['type_of_igm']; else "";?>" placeholder="Type of IGM " >
						</div>
					</div--->

					<div class="col-md-6">
						<div class="input-group mb-md">
							<span class="input-group-addon span_width">Weight Unit <span class="required">*</span></span>
							<input type="text" name="weight_unit" id="weight_unit" class="form-control" value="<?php if(@$editFlag==1) echo $igm_data[0]['weight_unit']; else "";?>" placeholder="Weight Unit" >
						</div>
					</div>
					<!--div class="col-md-6">
						<div class="input-group mb-md">
							<span class="input-group-addon span_width">AFR <span class="required">*</span></span>
							<input type="text" name="AFR" id="AFR" class="form-control" value="<?php if(@$editFlag==1) echo $igm_data[0]['AFR']; else "";?>" placeholder="AFR" >
						</div>
					</div>
					<div class="col-md-6">
						<div class="input-group mb-md">
							<span class="input-group-addon span_width">Delv Block Stat <span class="required">*</span></span>
							<input type="text" name="delivery_block_stat" id="delivery_block_stat" class="form-control" value="<?php if(@$editFlag==1) echo $igm_data[0]['delivery_block_stat']; else "";?>" placeholder="Delivery Block Stat" >
						</div>
					</div>
					<div class="col-md-6">
						<div class="input-group mb-md">
							<span class="input-group-addon span_width">INT block <span class="required">*</span></span>
							<input type="text" name="int_block" id="int_block" class="form-control" value="<?php if(@$editFlag==1) echo $igm_data[0]['int_block']; else "";?>" placeholder="INT block" >
						</div>
					</div>
					<div class="col-md-6">
						<div class="input-group mb-md">
							<span class="input-group-addon span_width">Amendt Apprv. <span class="required">*</span></span>
							<input type="text" name="amendment_appoved" id="amendment_appoved" class="form-control" value="<?php if(@$editFlag==1) echo $igm_data[0]['amendment_appoved']; else "";?>" placeholder="Amendment Approve" >
						</div>
					</div>
					<div class="col-md-6">
						<div class="input-group mb-md">
							<span class="input-group-addon span_width">PF status<span class="required">*</span></span>
							<input type="text" name="PFstatus" id="PFstatus" class="form-control" value="<?php if(@$editFlag==1) echo $igm_data[0]['PFstatus']; else "";?>" placeholder="PF Status" >
						</div>
					</div>
					<div class="col-md-6">
						<div class="input-group mb-md">
							<span class="input-group-addon span_width">PF status date <span class="required">*</span></span>
							<input type="date" name="PFstatusdt" id="PFstatusdt" class="form-control" value="<?php if(@$editFlag==1) echo $igm_data[0]['PFstatusdt']; else "";?>" placeholder="PF Status date" >
						</div>
					</div-->
					<div class="col-md-6">
						<div class="input-group mb-md">
							<span class="input-group-addon span_width">Exporter name <span class="required">*</span></span>
							<input type="text" name="Exporter_name" id="Exporter_name" class="form-control" value="<?php if(@$editFlag==1) echo $igm_data[0]['Exporter_name']; else "";?>" placeholder="Exporter name" >
						</div>
					</div>
					<div class="col-md-12">
						<div class="input-group mb-md">
							<span class="input-group-addon span_width">Exporter Addr <span class="required">*</span></span>
							<textarea class="form-control" name="Exporter_address" id="Exporter_address" style="height:50px;"> <?php if(@$editFlag==1) echo $igm_data[0]['Exporter_address']; else "";?></textarea>
							<!--input type="text" name="Exporter_address" id="Exporter_address" class="form-control" value="<?php if(@$editFlag==1) echo $igm_data[0]['Exporter_address']; else "";?>" placeholder="Exporter Address" -->
						</div>
					</div>
					<div class="col-md-6">
						<div class="input-group mb-md">
							<span class="input-group-addon span_width">Notify Code <span class="required">*</span></span>
							<input type="text" name="Notify_code" id="Notify_code" class="form-control" value="<?php if(@$editFlag==1) echo $igm_data[0]['Notify_code']; else "";?>" placeholder="Notify Code" >
						</div>
					</div>
					<div class="col-md-6">
						<div class="input-group mb-md">
							<span class="input-group-addon span_width">Notify Name <span class="required">*</span></span>							
							<input type="text" name="Notify_name" id="Notify_name" class="form-control" value="<?php if(@$editFlag==1) echo $igm_data[0]['Notify_name']; else "";?>" placeholder="Notify Name" >
						</div>
					</div>
					<div class="col-md-12">
						<div class="input-group mb-md">
							<span class="input-group-addon span_width">Notify Addr <span class="required">*</span></span>
							<textarea class="form-control" name="Notify_address" id="Notify_address" style="height:50px;"> <?php if(@$editFlag==1) echo $igm_data[0]['Notify_address']; else "";?></textarea>
							<!--input type="text" name="Notify_address" id="Notify_address" class="form-control" value="<?php if(@$editFlag==1) echo $igm_data[0]['Notify_address']; else "";?>" placeholder="Notify Address" -->
						</div>
					</div>

					<div class="col-md-6">
						<div class="input-group mb-md">
							<span class="input-group-addon span_width">Consignee Code <span class="required">*</span></span>
							<input type="text" name="Consignee_code" id="Consignee_code" class="form-control" value="<?php if(@$editFlag==1) echo $igm_data[0]['Consignee_code']; else "";?>" placeholder="Consignee Code" >
						</div>
					</div>
					
					<div class="col-md-6">
						<div class="input-group mb-md">
							<span class="input-group-addon span_width">Consignee Name <span class="required">*</span></span>
							<input type="text" name="Consignee_name" id="Consignee_name" class="form-control" value="<?php if(@$editFlag==1) echo $igm_data[0]['Consignee_name']; else "";?>" placeholder="Consignee Name" >
						</div>
					</div>
					<div class="col-md-12">
						<div class="input-group mb-md">
							<span class="input-group-addon span_width">Consignee Addr <span class="required">*</span></span>
							<textarea class="form-control" name="Consignee_address" id="Consignee_address" style="height:50px;"> <?php if(@$editFlag==1) echo $igm_data[0]['Consignee_address']; else "";?></textarea>
							<!--input type="text" name="Consignee_address" id="Consignee_address" class="form-control" value="<?php if(@$editFlag==1) echo $igm_data[0]['Consignee_address']; else "";?>" placeholder="Consignee Address" -->
						</div>
					</div>
					<div class="col-md-6">
						<div class="input-group mb-md">
							<span class="input-group-addon span_width">Volume c.meters <span class="required">*</span></span>
							<input type="text" name="Volume_in_cubic_meters" id="Volume_in_cubic_meters" class="form-control" value="<?php if(@$editFlag==1) echo $igm_data[0]['Volume_in_cubic_meters']; else "";?>" placeholder="Volume c.meters" >
						</div>
					</div>
					<div class="col-md-6">
						<div class="input-group mb-md">
							<span class="input-group-addon span_width">DG status <span class="required">*</span></span>
							<input type="text" name="DG_status" id="DG_status" class="form-control" value="<?php if(@$editFlag==1) echo $igm_data[0]['DG_status']; else "";?>" placeholder="DG status" >
						</div>
					</div>
					<div class="col-md-6">
						<div class="input-group mb-md">
							<span class="input-group-addon span_width">Port of Origin <span class="required">*</span></span>
							<input type="text" name="port_of_origin" id="port_of_origin" class="form-control" value="<?php if(@$editFlag==1) echo $igm_data[0]['port_of_origin']; else "";?>" placeholder="Port of Origin" >
						</div>
					</div>	
					
					<div class="row">
						<div class="col-sm-12 text-center">
							<button type="submit" name="submit_login" id="submit" class="mb-xs mt-xs mr-xs btn btn-success"><?php if(@$frmType=="edit") { ?>Update<?php } else { ?> Save <?php } ?>
							</button>
						</div>													
					</div>
					
				</div>	
			</form>
		</section>
	</div>
	</div>
</div>
	<?php } ?>

</section>
</div>