<script>
	function confirmRejection(){
		if(confirm("Do you want to reject ?"))
		  {
			  return true;
		  }
		  else
		  {
			  return false;
		  }
	}
	function confirmDlt(){
		if(confirm("Do you want to delete ?"))
		  {
			  return true;
		  }
		  else
		  {
			  return false;
		  }
	}
	function getSearchInfo()
	{	
		var rot_no = document.getElementById("rot_no").value;
		var bl_no = document.getElementById("bl_no").value;
		document.getElementById("contType").innerHTML = "";
		if(rot_no=="")
		{
			alert("Please select reg number");
			return false;
		}
		else if(bl_no=="")
		{
			alert("Please select BL number");
			return false;
		}
		else
		{
			
			
			if (window.XMLHttpRequest) 
			{
				xmlhttp=new XMLHttpRequest();
			} 
			else 
			{  
				xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
			}
			

			xmlhttp.onreadystatechange=function(){		 
			if (xmlhttp.readyState==4 && xmlhttp.status==200) 
			{							
				var val = xmlhttp.responseText;
				var jsonData = JSON.parse(val);				
				
				var bltypedata = "";
				// alert(jsonData.mloName);
				// alert(jsonData.shippingAgentName);
				// alert(jsonData.ffName);
				// alert(jsonData.msgFlag);
				if(jsonData.msgFlag==0)
				{
					alert("Wrong Combination of Reg and BL");
					document.getElementById("bltype").innerHTML = "";
					document.getElementById("igmtype").innerHTML = "";
					document.getElementById("saname").innerHTML = "";
					document.getElementById("mloname").innerHTML = "";
					document.getElementById("frfname").innerHTML = "";
					document.getElementById("contType").innerHTML = "";
					//document.getElementById("btnApply").style.visibility = "hidden";
					//document.getElementById("applyBtn").style.display = "none";
					return false;
				}
				else
				{
					if(jsonData.blType_BB=="HB")
					{
						//document.getElementById("bltype").innerHTML = jsonData.blType_BB;
						document.getElementById("bltype").innerHTML = "House BL";
						document.getElementById("contType").innerHTML = jsonData.cont_status;
					}
					else if(jsonData.blType_BB=="MB")
					{
						//document.getElementById("bltype").innerHTML = jsonData.blType_BB;
						document.getElementById("bltype").innerHTML = "Master BL";
					}
					if(jsonData.type_of_igm=="GM")
					{
						document.getElementById("igmtype").innerHTML = "General Manifest";
					}
					else
					{
						document.getElementById("igmtype").innerHTML = "Break Bulk";
						document.getElementById("contType").innerHTML = "";
					}
					
					document.getElementById("saname").innerHTML = jsonData.shippingAgentName;
					document.getElementById("mloname").innerHTML = jsonData.mloName;
					document.getElementById("frfname").innerHTML = jsonData.ffName;
					
					//document.getElementById("btnApply").style.visibility = "visible"; 
					//document.getElementById("applyBtn").style.display = "block";
				}			
			}
		};
			
			var url = "<?php echo site_url('AjaxController/getIGMInfo')?>?rot_no="+rot_no+"&bl_no="+bl_no;		
			xmlhttp.open("GET",url,false);
			xmlhttp.send();
		}
		
	}

	function validate(){
		var rot_no = document.getElementById("rot_no").value;
		var bl_no = document.getElementById("bl_no").value;		
		if(rot_no=="")
		{
			alert("Please select Reg number");
			return false;
		}
		else if(bl_no=="")
		{
			alert("Please select BL number");
			return false;
		}
		else
		{
			return true;
		}
	}
	
	function cnfrmClearance() 
	{
	  if(confirm("Do you want to forward ?"))
	  {
		  return true;
	  }
	  else
	  {
		  return false;
	  }
	}
	
	function cnfrmCpaApproval(ival)
	{
		//alert(ival);
		if(confirm("Do you want to Approve ?"))
		{
		  var uploadId = document.getElementById("uploadIdtoApprove"+ival).value;
		  var edoId = document.getElementById("edoIdtoApprove"+ival).value;
		  //alert(uploadId);
			if (window.XMLHttpRequest) 
			{
				xmlhttp=new XMLHttpRequest();
			} 
			else 
			{  
				xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
			}
			

			xmlhttp.onreadystatechange=function(){		 
				if (xmlhttp.readyState==4 && xmlhttp.status==200) 
				{							
					var val = xmlhttp.responseText;
					var jsonData = JSON.parse(val);				
					
					if(jsonData.strUpdateStat==true)
					{
						alert("Updated Successfully!");
						document.getElementById("btnChecked"+ival).style.display = "block"; 
						document.getElementById("btnUnchecked"+ival).style.display = "none";
						document.getElementById("btnChecked"+ival).style.fontWeight = "bold";
					}
					else
					{
						alert("Updated Failed!");
						document.getElementById("btnChecked"+ival).style.display = "none"; 
						document.getElementById("btnUnchecked"+ival).style.display = "block"; 
					}			
				}
			};
				
			var url = "<?php echo site_url('AjaxController/changeChkState')?>?uploadId="+uploadId+"&edoId="+edoId;		
			xmlhttp.open("GET",url,false);
			xmlhttp.send();
	  	}
	  else
	  {
		  return false;
	  }
	}
	
	function setValueOnModal(edoid){
		document.getElementById("eid").value=edoid;
	}
	function setValueOnValidityExtensionforHBLAndFCL(edoid){
		document.getElementById("edoForFCLAndHBL").value=edoid;
	}
	function setValueOnExpansionRequest(edoid){
		document.getElementById("extEDOId").value=edoid;
	}
	function setValueOnClearance(edoid){
		document.getElementById("clearanceEDOId").value=edoid;
	}
	function setInfoOnModal(et,ft,ut){
		document.getElementById("applicationTime").innerHTML=et;
		document.getElementById("forwardingTime").innerHTML=ft;
		document.getElementById("uploadingTime").innerHTML=ut;
	}
</script>

<section role="main" class="content-body">
	<header class="page-header">
		<h2><?php  echo $title; ?></h2>
	</header>
          
<div class="row">
	<div class="col-lg-6">						
		<div class="panel-body">
			<div class="container">
				<div class="modal fade" id="myModal">
					<div class="modal-dialog modal-block-sm">
						<div class="modal-content">
							<!-- Modal Header -->
							<div class="modal-header">
								<h4 class="modal-title">Reject DO</h4>
								<button type="button" class="close" data-dismiss="modal">&times;</button>
							</div>

							<div class="modal-body">
								<form class="form-horizontal form-bordered" method="POST" 
									action="<?php echo site_url('EDOController/updateEDORejectStatus')?>" onsubmit="return confirmRejection();">
									<input type="hidden" name="eid" id="eid" value="">
									<div class="form-group">
										<label class="col-md-2 control-label">&nbsp;</label>
										<div class="col-md-8">
											<div class="input-group mb-md">
												<span class="input-group-addon span_width">Remarks</span>
												<textarea name="rejection_remarks" id="rejection_remarks" rows="3" class="form-control"></textarea>
											</div>
										</div>
										<div class="row">
											<div class="col-sm-12 text-center">
												<button type="submit" class="mb-xs mt-xs mr-xs btn btn-success">Save</button>
											</div>
										</div>
										<div class="row">
											<div class="col-sm-12 text-center">
												
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
			</div>
			<div class="container">
				<div class="modal fade" id="myModalValidity">
					<div class="modal-dialog modal-block-sm">
						<div class="modal-content">
							<!-- Modal Header -->
							<div class="modal-header">
								<h4 class="modal-title">Apply for Validity Date Extension</h4>
								<button type="button" class="close" data-dismiss="modal">&times;</button>
							</div>

							<div class="modal-body">
								<form class="form-horizontal form-bordered" method="POST" action="<?php echo site_url('EDOController/applyForValidityExtension')?>">
									<input type="hidden" name="extEDOId" id="extEDOId" value="">
									<div class="form-group">
										<label class="col-md-2 control-label">&nbsp;</label>
										<div class="col-md-8">
											<div class="input-group mb-md">
												<span class="input-group-addon span_width">Date</span>
												<input type="date" class="form-control" name="requested_date" id="requested_date">
											</div>
										</div>
										<div class="row">
											<div class="col-sm-12 text-center">
												<button type="submit" class="mb-xs mt-xs mr-xs btn btn-success">Apply</button>
											</div>
										</div>
										<div class="row">
											<div class="col-sm-12 text-center">
												
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
			</div>
			<div class="container">
				<div class="modal fade" id="myModalforEdoForFCLAndHBL">
					<div class="modal-dialog modal-block-sm">
						<div class="modal-content">
							<!-- Modal Header -->
							<div class="modal-header">
								<h4 class="modal-title">Apply for Validity Date Extensions</h4>
								<button type="button" class="close" data-dismiss="modal">&times;</button>
							</div>

							<div class="modal-body">
								<form class="form-horizontal form-bordered" method="POST" action="<?php echo site_url('EDOController/applyForValidityExtensionForFCLandHBL')?>">
									<input type="hidden" name="edoForFCLAndHBL" id="edoForFCLAndHBL" value="">
									<div class="form-group">
										<label class="col-md-2 control-label">&nbsp;</label>
										<div class="col-md-8">
											<div class="input-group mb-md">
												<span class="input-group-addon span_width">Date</span>
												<input type="date" class="form-control" name="requested_date" id="requested_date">
											</div>
										</div>
										<div class="row">
											<div class="col-sm-12 text-center">
												<button type="submit" class="mb-xs mt-xs mr-xs btn btn-success">Apply</button>
											</div>
										</div>
										<div class="row">
											<div class="col-sm-12 text-center">
												
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
			</div>
			<div class="container">
				<div class="modal fade" id="modalDtTime">
					<div class="modal-dialog modal-block-sm">
						<div class="modal-content">
							<!-- Modal Header -->
							<div class="modal-header">
								<h4 class="modal-title">Information</h4>
								<button type="button" class="close" data-dismiss="modal">&times;</button>
							</div>
							<div class="modal-body">
								<div class="form-group">
									<div class="row">
										<div class="col-md-6 text-right">
											<label class="control-label">Application Date :</label>
										</div>
										<div class="col-md-6 text-left">
											<label class="control-label text-bold" id="applicationTime"> </label>
										</div>
									</div>
									<div class="row">
										<div class="col-md-6 text-right">
											<label class="control-label">Forwarding Date :</label>
										</div>
										<div class="col-md-6 text-left">
											<label class="control-label text-bold" id="forwardingTime"> </label>
										</div>
									</div>
									<div class="row">
										<div class="col-md-6 text-right">
											<label class="control-label">Uploading Date :</label>
										</div>
										<div class="col-md-6 text-left">
											<label class="control-label text-bold" id="uploadingTime"> </label>
										</div>
									</div>
								</div>
							</div>
							<!-- Modal footer -->
							<div class="modal-footer">
								<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="container">
				<div class="modal fade" id="myModalClearance">
					<div class="modal-dialog modal-block-sm">
						<div class="modal-content">
							<!-- Modal Header -->
							<div class="modal-header">
								<h4 class="modal-title">Clearance to FF</h4>
								<button type="button" class="close" data-dismiss="modal">&times;</button>
							</div>
							<div class="modal-body">
								<form class="form-horizontal form-bordered" method="POST" action="<?php echo site_url('EDOController/updateStatforEDO')?>">
									<input type="hidden" name="clearanceEDOId" id="clearanceEDOId" value="">
									<div class="form-group">
										<label class="col-md-2 control-label">&nbsp;</label>
										<div class="col-md-8">
											<div class="input-group mb-md">
												<span class="input-group-addon span_width">Valid upto date</span>
												<input type="date" class="form-control" name="valid_upto_date" id="valid_upto_date" required>
											</div>
										</div>
										<div class="row">
											<div class="col-sm-12 text-center">
												<button type="submit" class="mb-xs mt-xs mr-xs btn btn-success">Apply</button>
											</div>
										</div>
										<div class="row">
											<div class="col-sm-12 text-center">
												
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
			</div>
			<form class="form-horizontal form-bordered" method="POST" 
				action="<?php echo site_url("EDOController/applicationForEDObyrotationBLentry") ?>" onsubmit="return validate();">
				<div class="form-group">
					<!--label class="col-md-3 control-label">&nbsp;</label-->
					<div class="col-md-12">		
						<div class="input-group mb-md">
							<span class="input-group-addon span_width">Reg No <span class="required">*</span></span>
							<input type="text" name="rot_no" id="rot_no" class="form-control login_input_text" autofocus= "autofocus" tabindex="1" placeholder="Reg No" required>
						</div>
						<div class="input-group mb-md">
							<span class="input-group-addon span_width">BL No <span class="required">*</span></span>
							<input type="text" name="bl_no" id="bl_no" class="form-control login_input_text" tabindex="2" placeholder="BL No" onblur="return getSearchInfo();" required>
						</div>
					</div>									
					<div class="row" id="applyBtn">
						<div class="col-sm-12 text-center">
							<button type="submit" name="btnApply" class="mb-xs mt-xs mr-xs btn btn-primary">
								Apply
							</button>
						</div>													
					</div>
					<div class="row">
						<div class="col-sm-12 text-center">
							<?php echo $msg; ?>
						</div>													
					</div>						
				</div>
			</form>
		</div>
	</div>
	<div class="col-lg-6">						
		<div class="panel-body">
			<div class="row">
				<div class="col-sm-12 col-md-12 text-center">
					<h4 class="h4 mt-none mb-sm text-dark"><b><u>IGM Information</u></b></h4>
				</div>
				<div class="col-sm-12 col-md-12">
					<h6 class="h6 mt-none mb-sm">
						<div class="row">
							<div class="col-md-4 text-right">IGM Type</div>
							<div class="col-md-1">:</div>
							<div class="col-md-7 text-left"><label id="igmtype"></label></div>
						</div>
					</h6>
					<h6 class="h6 mt-none mb-sm">
						<div class="row">
							<div class="col-md-4 text-right">BL Type</div> 
							<div class="col-md-1">:</div>
							<div class="col-md-6 text-left"><label id="bltype"></label></div>
						</div>
					</h6>
					<h6 class="h6 mt-none mb-sm">
						<div class="row">
							<div class="col-md-4 text-right">Shipping Agent</div> 
							<div class="col-md-1">:</div>
							<div class="col-md-7 text-left"><label id="saname"></label></div>
						</div>
					</h6>
					<h6 class="h6 mt-none mb-sm">
						<div class="row">
							<div class="col-md-4 text-right">MLO</div> 
							<div class="col-md-1">:</div>
							<div class="col-md-7 text-left"><label id="mloname"></label></div>
						</div>
					</h6>
					<h6 class="h6 mt-none mb-sm">
						<div class="row">
							<div class="col-md-4 text-right">Freight Forwarder</div> 
							<div class="col-md-1">:</div>
							<div class="col-md-7 text-left"><label id="frfname"></label></div>
						</div>
					</h6>
					<h6 class="h6 mt-none mb-sm">
						<div class="row">
							<div class="col-md-4 text-right">Container Type</div> 
							<div class="col-md-1">:</div>
							<div class="col-md-7 text-left"><label id="contType"></label></div>
						</div>
					</h6>
				</div>
			</div>
		</div>
	</div>
</div>
<div class="row">
	<?php
		include("mydbPConnection.php");
		$login_id = $this->session->userdata("login_id");
		$org_Type_id = $this->session->userdata("org_Type_id");
		$org_id = $this->session->userdata("org_id");
	?>
	<div class="col-lg-12">						
		<div class="panel-body">
			<section class="panel">
				<table class="table table-bordered table-hover table-striped mb-none" id="datatable-default">
					<thead>
						<tr>
							<th>#Sl</th>
							<th>EDO No</th>
							<th class="text-center">Reg No & BL</th>
							<th class="text-center">Type</th>
							<?php if($org_Type_id!=2) { ?>
							<th class="text-center">C&F </th>
							<?php } ?>
							<?php if($org_Type_id==1 or $org_Type_id==4 or $org_Type_id==2 or $org_Type_id==5 or $org_Type_id==73) { ?>
							<th class="text-center">MLO</th>							
							<th class="text-center">F.F</th>
							<?php } ?>
							<?php if($org_Type_id==10 or $org_Type_id==57 or $org_Type_id==2 or $org_Type_id==5) { ?>
							<th class="text-center">Agent</th>
							<?php } ?>
							<th class="text-center">Date & Time</th>
							<th>Remarks</th>
							<th class="text-center">State</th>
							<?php if($org_Type_id==73){ ?>
							<th>Action</th>
							<?php } ?>
							<?php if($org_Type_id==1 or $org_Type_id==2){ ?>
							<th>Action</th>
							<?php } ?>
							<?php if($org_Type_id==5){ ?>
							<th>Action</th>
							<?php } ?>
						</tr>
					</thead>
					<?php
						$today= date("Y-m-d", strtotime(date('Y-m-d')));
						
						$Submitee_Org_Id = "";
						$query = "SELECT users.org_id FROM users WHERE users.login_id='$login_id'";
						$resultQuery = mysqli_query($con_cchaportdb,$query);
						while($res = mysqli_fetch_object($resultQuery)){
							$Submitee_Org_Id = $res->org_id;
						}
						
						if($org_Type_id==73) //freight forwarder association
						{
							$edoQuery = "SELECT *
							FROM edo_application_by_cf where bl_type='HB' AND igm_type='GM' AND ff_stat='1' ORDER BY id DESC";
						}						
						else if($org_Type_id==57 or $org_Type_id==10) //shipping_agent
						{
							$edoQuery = "SELECT *
							FROM edo_application_by_cf where igm_type='BB' and sh_agent_org_id='$Submitee_Org_Id' ORDER BY id DESC";
						}
						else if($org_Type_id==4) //freight forwarder
						{
							if($flag=="pending")
							{
								$edoQuery = "SELECT * 
								FROM edo_application_by_cf 
								WHERE igm_type='GM' AND bl_type='HB' AND ff_org_id='$org_id' 
								AND do_upload_st='0' AND rejection_st='0'
								ORDER BY id DESC";
							}
							else
							{
								$edoQuery = "SELECT * FROM edo_application_by_cf 
									where igm_type='GM' AND ff_org_id='$org_id' ORDER BY id DESC";
							}
						
						}
						else if($org_Type_id==1) //MLO
						{
							$edoQuery = "SELECT * FROM edo_application_by_cf 
										WHERE igm_type='GM' AND mlo='$Submitee_Org_Id' 
										AND (cont_status = 'FCL' OR bl_type = 'MB')
										ORDER BY id DESC";
						}
						else if($org_Type_id==5) //CPA
						{
							$edoQuery = "SELECT * FROM edo_application_by_cf 
										WHERE IF(bl_type='HB' AND igm_type='GM', ff_assoc_st, do_upload_st) ='1'
										ORDER BY id DESC";
						}
						else  //cnf=2
						{
							$edoQuery = "SELECT * FROM edo_application_by_cf 
										WHERE sumitted_by='$login_id' ORDER BY id DESC";
						}
												
						$edoResult = mysqli_query($con_cchaportdb,$edoQuery);
						
						$edo_id = "";
						$imp_rot = "";
						$bl = "";
						$bl_type = "";
						$bl_type_txt = "";
						$igm_type = "";
						$frightFrwder = "";
						$shipping_agent = "";
						$ff_stat = "";
						$ff_assoc_st = "";
						$do_upload_st = "";
						
						$uploadedAt = "";
						$validityDt = "";
						$checkSt = "";
						$sumitted_by = "";
						$entry_time = "";
						$ff_clearance_time = "";
						$rejection_st = "";
						$rejection_time = "";
						$rejection_remarks = "";
						$applied_valid_dt = "";
						$cont_status = "";
						$mbl_of_hbl = "";
						$valid_upto_dt_by_mlo = "";

						$i=0;
						while($row = mysqli_fetch_object($edoResult)){
							$uploadId = "";
							
							$edo_id = $row->id;
							$imp_rot = $row->rotation;
							$bl = $row->bl;
							$bl_type = $row->bl_type;
							$igm_type = $row->igm_type;
							$mlo = $row->mlo;
							$frightFrwder = $row->ff_org_id;
							$shipping_agent = $row->sh_agent_org_id;
							$ff_stat = $row->ff_stat;
							$ff_assoc_st = $row->ff_assoc_st;
							$do_upload_st = $row->do_upload_st;
							$sumitted_by = $row->sumitted_by;
							$entry_time = $row->entry_time;
							$ff_clearance_time = $row->ff_clearance_time;
							$rejection_st = $row->rejection_st;
							$rejection_time = $row->rejection_time;
							$rejection_remarks = $row->rejection_remarks;
							$applied_valid_dt = $row->applied_valid_dt;
							$cont_status = $row->cont_status;
							$mbl_of_hbl = $row->mbl_of_hbl;
							$valid_upto_dt_by_mlo = $row->valid_upto_dt_by_mlo;
							$i++;
							
							if($cont_status == " " or $cont_status == "" or $cont_status == null){
								if($bl_type=="HB") {
									$queryContStatus = "SELECT igm_detail_container.cont_status
												FROM igm_detail_container
												INNER JOIN igm_details ON igm_detail_container.igm_detail_id=igm_details.id
												WHERE igm_details.Import_Rotation_No='$imp_rot' AND igm_details.BL_No='$bl'";
									$resContStatus = mysqli_query($con_cchaportdb,$queryContStatus);
									while($rowContStatus = mysqli_fetch_object($resContStatus)){
										$cont_status = $rowContStatus->cont_status;
									}
								} else if($bl_type=="MB"){
									$queryContStatus = "select igm_supplimentary_detail.master_BL_No,igm_sup_detail_container.cont_status
												from igm_supplimentary_detail 
												INNER JOIN igm_sup_detail_container ON igm_sup_detail_container.igm_sup_detail_id=igm_supplimentary_detail.id
												INNER JOIN igm_details ON igm_supplimentary_detail.igm_detail_id=igm_details.id
												where igm_supplimentary_detail.Import_Rotation_No='$imp_rot' and igm_supplimentary_detail.BL_No='$bl'";
									$resContStatus = mysqli_query($con_cchaportdb,$queryContStatus);
									while($rowContStatus = mysqli_fetch_object($resContStatus)){
										$cont_status = $rowContStatus->cont_status;
									}
								} else {
									$cont_status = $cont_status;
								}
							}
							
							if($bl_type=="MB")
							{
								$bl_type_txt="MASTER";
							}
							else
							{
								$bl_type_txt="HOUSE";
							}
							if($ff_clearance_time=="0000-00-00 00:00:00")
							{
								$ff_clearance_time = "";
							}
							$doUploadId = "SELECT * FROM shed_mlo_do_info WHERE imp_rot='$imp_rot' AND bl_no='$bl' 
										ORDER BY id DESC LIMIT 1";
							$resDoUploadId = mysqli_query($con_cchaportdb,$doUploadId);
							
							$edo_mlo = "";
							$edo_sl = "";
							$edo_year = "";
							$edo_number = "";
							
							while($rowDoUploadId = mysqli_fetch_object($resDoUploadId)){
								$uploadId = $rowDoUploadId->id;
								$uploadedAt = $rowDoUploadId->upload_time;
								$validityDt = $rowDoUploadId->valid_upto_dt;
								$checkSt = $rowDoUploadId->check_st;
								$edo_mlo = $rowDoUploadId->edo_mlo;
								$edo_sl = str_pad($rowDoUploadId->edo_sl, 6, "0", STR_PAD_LEFT);
								$edo_year = $rowDoUploadId->edo_year;
							}
							$edo_uploaded_at = date( "Y-m-d", strtotime($uploadedAt));
							if($edo_uploaded_at < "2021-12-02") {
								$edo_number = $uploadId;
							} else {
								$edo_number = $edo_mlo.$edo_sl.$edo_year;
							}
					?>
					
					<tr>
						<td><?php echo $i; ?></td>
						<td><?php echo $edo_number; //$uploadId; ?></td>
						<td>
							<?php 
								echo "Rot-".$imp_rot; 
								echo "<br>";
								echo "<nobr>"." Bl-".$bl."</nobr>";
								echo "<br>";
								echo "<nobr>"." MBL-".$mbl_of_hbl."</nobr>";
								echo "<br>";
								echo "<nobr>"."Container Type-".$cont_status."</nobr>";
							?>
						</td>
						<td><?php echo $bl_type_txt."<br>".$igm_type; ?></td>
						<?php if($org_Type_id!=2) { ?>
						<td>
							<?php 
								$appliedById = "";
								$applicantName = "";
								$applicantLic = "";
								$applicantAIN = "";
								
								$applicantDtls = "SELECT * FROM users WHERE login_id='$sumitted_by'";
								$resApplicantDtls = mysqli_query($con_cchaportdb,$applicantDtls);
								while($rowApplicantDtls = mysqli_fetch_object($resApplicantDtls)){
									$appliedById = $rowApplicantDtls->org_id;
								}
								
								$queryApplicantName = "SELECT Organization_Name,IFNULL(AIN_No_New,AIN_No) AS ain,License_No FROM organization_profiles WHERE id='$appliedById'";
								$resApplicantName = mysqli_query($con_cchaportdb,$queryApplicantName);
								while($rowApplicantNames = mysqli_fetch_object($resApplicantName)){
									$applicantName = $rowApplicantNames->Organization_Name;
									$applicantLic = $rowApplicantNames->License_No;
									$applicantAIN = " (".$rowApplicantNames->ain.")";
								}
								
								echo $applicantName.$applicantAIN; 
							?>
						</td>
						<?php } ?>
						<!--td><?php echo date("d-m-Y", strtotime($entry_time)); ?></td-->
						<?php if($org_Type_id==1 or $org_Type_id==4 or $org_Type_id==2 or $org_Type_id==5 or $org_Type_id==73) { ?>
						<td>
							<?php
								$mloName = "";
								$mloAIN = "";
								if($mlo!="")
								{
									$mloDtls = "SELECT Organization_Name,IFNULL(AIN_No_New,AIN_No) AS ain FROM organization_profiles WHERE id='$mlo'";
									$resMloDtls = mysqli_query($con_cchaportdb,$mloDtls);
									while($rowMloDtls = mysqli_fetch_object($resMloDtls)){
										$mloName = $rowMloDtls->Organization_Name;
										$mloAIN = " (".$rowMloDtls->ain.")";
									}
								}								
								echo $mloName.$mloAIN;
							?>
						</td>
						<td>
							<?php 
								$ffName = "";
								$ffAIN = "";
								if($frightFrwder!="")
								{
									$ffDtls = "SELECT Organization_Name,IFNULL(AIN_No_New,AIN_No) AS ain FROM organization_profiles WHERE id='$frightFrwder'";
									$resFFDtls = mysqli_query($con_cchaportdb,$ffDtls);
									while($rowFFDtls = mysqli_fetch_object($resFFDtls)){
										$ffName = $rowFFDtls->Organization_Name;
										$ffAIN = " (".$rowFFDtls->ain.")";
									}
								}								
								echo $ffName.$ffAIN;
							?>
						</td>
						<?php } ?>
						<?php if($org_Type_id==10 or $org_Type_id==57 or $org_Type_id==2 or $org_Type_id==5) { ?>
						<td>
							<?php 
								$shippingAgentName = "";
								$shippingAgentAIN = "";
								if($shipping_agent!="")
								{
									$saDtls = "SELECT Organization_Name,IFNULL(AIN_No_New,AIN_No) AS ain FROM organization_profiles WHERE id='$shipping_agent'";
									$resSHDtls = mysqli_query($con_cchaportdb,$saDtls);
									while($rowSHDtls = mysqli_fetch_object($resSHDtls)){
										$shippingAgentName = $rowSHDtls->Organization_Name;
										$shippingAgentAIN = " (".$rowSHDtls->ain.")";
									}
								}								
								echo $shippingAgentName.$shippingAgentAIN;
							?>
						</td>
						<?php } ?>
						<td>
							<?php 
								echo "<nobr>"."Application- "."<b>".$entry_time."</b>"."</nobr>";
								echo "<br>";
								echo "<nobr>"."Forwarding- "."<b>".$ff_clearance_time."</b>"."</nobr>";
								echo "<br>";
								echo "<nobr>"."Uploading- "."<b>".$uploadedAt."</b>"."</nobr>";
							?>
						</td>
						<td>
							<?php 
								if ($org_Type_id==73 or $org_Type_id==5) {
									//--Freight Forwarder Association/CPA
									if($rejection_st==1) { 
										echo $rejection_remarks."<br>"."<b>Time-</b>".$rejection_time;
									}
								} else if($org_Type_id==57 or $org_Type_id==10 or $org_Type_id==2 or $org_Type_id==4 or $org_Type_id==1) {
									//--Shipping Agent/C&F/MLO/FF
									if($rejection_st==1) { 
										echo $rejection_remarks."<br>"."<b>Time-</b>".$rejection_time;
									} else if(($validityDt != $applied_valid_dt) and ($applied_valid_dt != NULL)) { 
										echo "applied validity up to date-"."<b>".$applied_valid_dt."</b>"; 
									}
								}
							 ?>
							 <input type="button" style="padding:1px;" value="INFO" class="btn btn-xs btn-primary"
								onclick="setInfoOnModal('<?php echo $entry_time;?>','<?php echo $ff_clearance_time;?>','<?php echo $uploadedAt;?>');"
								  data-toggle="modal" data-target="#modalDtTime" />
						</td>
						<?php
						if ($org_Type_id==73) { ?>
						<td>
							<!--Freight Forwarder Association-->
							<?php if($do_upload_st==0 and $rejection_st==0) { ?>
								<input class="btn btn-xs btn-primary" type="button" value="FORWARDED"/>
							<?php } else if($do_upload_st==1) { ?>
								<form action="<?php echo site_url('EDOController/eDOPDF')?>" target="_blank" method="POST">
									<input type="hidden" name="shedMloDo" id="shedMloDo" value="<?php echo $uploadId; ?>"/>
									<input type="hidden" name="rotNo" id="rotNo" value="<?php echo $imp_rot; ?>"/>
									<input type="hidden" name="blno" id="blno" value="<?php echo $bl; ?>"/>
									<input type="hidden" name="bl_type" id="bl_type" value="<?php echo $bl_type; ?>"/>
									<input type="hidden" name="sumitted_by" id="sumitted_by" value="<?php echo $sumitted_by; ?>"/>
									<input class="btn btn-xs btn-primary" type="submit" value="VIEW DO"/>
								</form>
							<?php } else if($rejection_st==1) { ?>
								<input class="btn btn-xs btn-primary" type="button" value="REJECTED"/>
							<?php } ?>
						</td>
						<td>
							<?php if($rejection_st==0) { ?>
								<?php if($ff_assoc_st==1){ ?>
									<input class="btn btn-xs btn-success" type="button" value="APPROVED"/>
								<?php }else if($ff_assoc_st==0){ ?>
									<!--form action="<?php echo site_url('EDOController/ffAssocStateChange')?>" method="POST">
										<input type="hidden" name="edoId" id="edoId" value="<?php echo $edo_id; ?>"/>
										<input type="hidden" name="sumitted_by" id="sumitted_by" value="<?php echo $sumitted_by; ?>"/>
										<input type="hidden" name="cnfLic" id="cnfLic" value="<?php echo $applicantLic; ?>"/>
										<input class="btn btn-xs btn-primary" type="submit" value="Approve"/>
									</form-->
								<?php } ?>
							<?php } else { ?>
								<input class="btn btn-xs btn-danger" type="button" value="REJECTED"/>
							<?php } ?>
						</td>						
						<?php } else if($org_Type_id==57 or $org_Type_id==10) { ?>
						<td align="center">
							<!--Shipping Agent-->
							<?php if($do_upload_st==0 and $rejection_st==0) { ?>
								<p>
									<form method="POST" action="<?php echo site_url("EDOController/shedDeOInfoData") ?>" >
										<input type="hidden" name="edo_id" id="edo_id" value="<?php echo $edo_id; ?>"/>
										<input type="hidden" name="imp_rot" id="imp_rot" value="<?php echo $imp_rot; ?>"/>
										<input type="hidden" name="blNo" id="blNo" value="<?php echo $bl; ?>"/>
										<input type="hidden" name="bl_type" id="bl_type" value="<?php echo $bl_type; ?>"/>
										<input type="hidden" name="igm_type" id="igm_type" value="<?php echo $igm_type; ?>"/>
										<input type="hidden" name="sumitted_by" id="sumitted_by" value="<?php echo $sumitted_by; ?>"/>
										<input type="submit" style="padding:1px;" class="btn btn-xs btn-primary" value="Upload DO"/>
									</form>
								</p>
								<p>
									<input type="button" style="padding:1px;" onclick="setValueOnModal(<?php echo $edo_id;?>);"
										value="Reject" class="btn btn-xs btn-danger" data-toggle="modal" data-target="#myModal"/>
								</p>
							<?php } else if($do_upload_st==1) { ?>
								<p>
									<form action="<?php echo site_url('EDOController/eDOPDF')?>" target="_blank" method="POST">
										<input type="hidden" name="shedMloDo" id="shedMloDo" value="<?php echo $uploadId; ?>"/>
										<input type="hidden" name="rotNo" id="rotNo" value="<?php echo $imp_rot; ?>"/>
										<input type="hidden" name="blno" id="blno" value="<?php echo $bl; ?>"/>
										<input type="hidden" name="bl_type" id="bl_type" value="<?php echo $bl_type; ?>"/>
										<input type="hidden" name="sumitted_by" id="sumitted_by" value="<?php echo $sumitted_by; ?>"/>
										<input class="btn btn-xs btn-primary" type="submit" value="VIEW DO"/>
									</form>
								</p>
								<p>
									<form action="<?php echo site_url('EDOController/shedDeOInfoData')?>" method="POST">
										<input type="hidden" name="editFlag" value="editFlag"/>
										<input type="hidden" name="uploadId" value="<?php echo $uploadId; ?>"/>
										<input type="hidden" name="imp_rot" value="<?php echo $imp_rot; ?>"/>
										<input type="hidden" name="blNo" value="<?php echo $bl; ?>"/>
										<input type="hidden" name="edo_id" id="edo_id" value="<?php echo $edo_id; ?>"/>
										<input type="hidden" name="bl_type" id="bl_type" value="<?php echo $bl_type; ?>"/>
										<input type="hidden" name="igm_type" id="igm_type" value="<?php echo $igm_type; ?>"/>
										<input type="hidden" name="sumitted_by" id="sumitted_by" value="<?php echo $sumitted_by; ?>"/>
										<input class="btn btn-xs btn-primary" type="submit" value="Edit"/>
									</form>
								</p>
							<?php } else if($rejection_st==1) { ?>
								<input class="btn btn-xs btn-danger" type="button" value="Rejected"/>
							<?php } ?>
						</td>
						<?php } else if ($org_Type_id==2){ ?>
							<td>
								<!--C&F-->
								<?php if($igm_type=="GM" and $bl_type=="HB") { ?>
									<?php if($ff_stat==0) { ?>
										<input class="btn btn-xs btn-danger" type="button" value="NOT FORWARDED"/>
									<?php } else if($ff_stat==1 and ($do_upload_st==1 or $rejection_st==1)) { ?>
										<?php if($do_upload_st==1) { ?>
											<form action="<?php echo site_url('EDOController/eDOPDF')?>" target="_blank" method="POST">
												<input type="hidden" name="shedMloDo" id="shedMloDo" value="<?php echo $uploadId; ?>"/>
												<input type="hidden" name="rotNo" id="rotNo" value="<?php echo $imp_rot; ?>"/>
												<input type="hidden" name="blno" id="blno" value="<?php echo $bl; ?>"/>
												<input type="hidden" name="bl_type" id="bl_type" value="<?php echo $bl_type; ?>"/>
												<input type="hidden" name="sumitted_by" id="sumitted_by" value="<?php echo $sumitted_by; ?>"/>
												<input class="btn btn-xs btn-primary" type="submit" value="VIEW DO"/>
											</form>
										<?php } else if($rejection_st==1) { ?>
											<input class="btn btn-xs btn-danger" type="button" value="REJECTED"/>
										<?php } ?>
									<?php } else { ?>
										<?php if($cont_status=="FCL") { ?>
											<input class="btn btn-xs btn-success" type="button" value="FORWARDED"/>
										<?php } else { ?>
											<input class="btn btn-xs btn-success" type="button" value="NOT UPLOADED"/>
									<?php } } } else if($igm_type=="GM" and $bl_type=="MB") { ?>
										<?php if($do_upload_st==1 or $rejection_st==1) { ?>
											<?php if($do_upload_st==1) { ?>
												<form action="<?php echo site_url('EDOController/eDOPDF')?>" target="_blank" method="POST">
													<input type="hidden" name="shedMloDo" id="shedMloDo" value="<?php echo $uploadId; ?>"/>
													<input type="hidden" name="rotNo" id="rotNo" value="<?php echo $imp_rot; ?>"/>
													<input type="hidden" name="blno" id="blno" value="<?php echo $bl; ?>"/>
													<input type="hidden" name="bl_type" id="bl_type" value="<?php echo $bl_type; ?>"/>
													<input type="hidden" name="sumitted_by" id="sumitted_by" value="<?php echo $sumitted_by; ?>"/>
													<input class="btn btn-xs btn-primary" type="submit" value="VIEW DO"/>
												</form>
										<?php } else if($rejection_st==1) { ?>
											<input class="btn btn-xs btn-danger" type="button" value="REJECTED"/>
										<?php } ?>
										<?php } else { ?>
											<input class="btn btn-xs btn-success" type="button" value="NOT UPLOADED"/>
										<?php } ?>									
								<?php } else if($igm_type=="BB") { ?>
									<?php if($do_upload_st==1 or $rejection_st==1) { ?>
										<?php if($do_upload_st==1) { ?>
											<form action="<?php echo site_url('EDOController/eDOPDF')?>" target="_blank" method="POST">
												<input type="hidden" name="shedMloDo" id="shedMloDo" value="<?php echo $uploadId; ?>"/>
												<input type="hidden" name="rotNo" id="rotNo" value="<?php echo $imp_rot; ?>"/>
												<input type="hidden" name="blno" id="blno" value="<?php echo $bl; ?>"/>
												<input type="hidden" name="bl_type" id="bl_type" value="<?php echo $bl_type; ?>"/>
												<input type="hidden" name="sumitted_by" id="sumitted_by" value="<?php echo $sumitted_by; ?>"/>
												<input class="btn btn-xs btn-primary" type="submit" value="VIEW DO"/>
											</form>
									<?php } else if($rejection_st==1) { ?>
										<input class="btn btn-xs btn-danger" type="button" value="REJECTED"/>
									<?php } ?>
									<?php } else { ?>
										<input class="btn btn-xs btn-success" type="button" value="NOT UPLOADED"/>
									<?php } ?>									
								<?php  } ?>
							</td>
							<td>
								<?php if(($igm_type=="GM") and ($bl_type=="HB") and ($cont_status=="FCL")) { ?>
									<?php if($ff_stat==0) { ?>
										<form method="POST" action="<?php echo site_url("EDOController/deleteEDOApplication") ?>" onsubmit="return confirmDlt();">
											<input type="hidden" name="edo_id" id="edo_id" value="<?php echo $edo_id; ?>"/>
											<input type="submit" style="padding:1px;" class="btn btn-xs btn-danger" value="DELETE DO"/>
										</form>
									<?php } else { ?>
										<!--input type="button" style="padding:1px;" value="DELETE DO" class="btn btn-xs btn-danger" disabled readonly /-->
										<!--validity extension-->
										<!--input type="button" style="padding:1px;" onclick="setValueOnValidityExtensionforHBLAndFCL(<?php echo $edo_id;?>);"
											value="Extend Validity" class="btn btn-xs btn-success" data-toggle="modal" 
											data-target="#myModalforEdoForFCLAndHBL"/-->
									<?php } ?>
								<?php } else { ?>
									<?php if($do_upload_st==1) { ?>
										<!--input type="button" style="padding:1px;" onclick="setValueOnExpansionRequest(<?php echo $edo_id;?>);"
											value="Extend Validity" class="btn btn-xs btn-success" data-toggle="modal" data-target="#myModalValidity"/-->
									<?php } else if($rejection_st==1) { ?>
										<input type="button" style="padding:1px;" value="DELETE DO" class="btn btn-xs btn-danger" disabled readonly />
									<?php } else { ?>
										<form method="POST" action="<?php echo site_url("EDOController/deleteEDOApplication") ?>" onsubmit="return confirmDlt();">
											<input type="hidden" name="edo_id" id="edo_id" value="<?php echo $edo_id; ?>"/>
											<input type="submit" style="padding:1px;" class="btn btn-xs btn-danger" value="DELETE DO"/>
										</form>
									<?php } ?>
								<?php } ?>
							</td>
						<?php }  else if ($org_Type_id==4) { ?>
							<td align="center">
								<!--Freight Forwarder-->
							<?php if($igm_type=="GM" and $bl_type=="HB") { ?>
							
								<?php if($ff_stat==0){ ?>
											<input class="btn btn-xs btn-danger" type="button" value="NOT FORWARDED"/>
									<?php	} else if($ff_stat==1) { ?>
										<?php if($do_upload_st==0 and $rejection_st==0) { ?>
										<?php if($cont_status=="FCL") { ?>		
											<?php if($today <= $valid_upto_dt_by_mlo) { ?>	
												<form method="POST" action="<?php echo site_url("EDOController/shedDeOInfoData") ?>" >
													<input type="hidden" name="edo_id" id="edo_id" value="<?php echo $edo_id; ?>"/>
													<input type="hidden" name="imp_rot" id="imp_rot" value="<?php echo $imp_rot; ?>"/>
													<input type="hidden" name="blNo" id="blNo" value="<?php echo $bl; ?>"/>
													<input type="hidden" name="bl_type" id="bl_type" value="<?php echo $bl_type; ?>"/>
													<input type="hidden" name="igm_type" id="igm_type" value="<?php echo $igm_type; ?>"/>
													<input type="hidden" name="sumitted_by" id="sumitted_by" value="<?php echo $sumitted_by; ?>"/>
													<input type="submit" style="padding:1px;" class="btn btn-xs btn-primary" value="UPLOAD DO"/>
												</form>
												<br>												
												<input type="button" style="padding:1px;" onclick="setValueOnModal(<?php echo $edo_id;?>);"
													value="Reject" class="btn btn-xs btn-danger" data-toggle="modal" data-target="#myModal"/>
											<?php } else { 
												echo "<b>Validity date set by MLO (".$valid_upto_dt_by_mlo.") has expired.</b>";
											} ?>												
										<?php } else { ?>
												<form method="POST" action="<?php echo site_url("EDOController/shedDeOInfoData") ?>" >
													<input type="hidden" name="edo_id" id="edo_id" value="<?php echo $edo_id; ?>"/>
													<input type="hidden" name="imp_rot" id="imp_rot" value="<?php echo $imp_rot; ?>"/>
													<input type="hidden" name="blNo" id="blNo" value="<?php echo $bl; ?>"/>
													<input type="hidden" name="bl_type" id="bl_type" value="<?php echo $bl_type; ?>"/>
													<input type="hidden" name="igm_type" id="igm_type" value="<?php echo $igm_type; ?>"/>
													<input type="hidden" name="sumitted_by" id="sumitted_by" value="<?php echo $sumitted_by; ?>"/>
													<input type="submit" style="padding:1px;" class="btn btn-xs btn-primary" value="UPLOAD DO"/>
												</form>
												<br>												
												<input type="button" style="padding:1px;" onclick="setValueOnModal(<?php echo $edo_id;?>);"
													value="Reject" class="btn btn-xs btn-danger" data-toggle="modal" data-target="#myModal"/>
										<?php } ?>												
											
									<?php	} else if($do_upload_st==1) {?>
											<p>
												<form action="<?php echo site_url('EDOController/eDOPDF')?>" target="_blank" method="POST">
													<input type="hidden" name="shedMloDo" id="shedMloDo" value="<?php echo $uploadId; ?>"/>
													<input type="hidden" name="rotNo" id="rotNo" value="<?php echo $imp_rot; ?>"/>
													<input type="hidden" name="blno" id="blno" value="<?php echo $bl; ?>"/>
													<input type="hidden" name="bl_type" id="bl_type" value="<?php echo $bl_type; ?>"/>
													<input type="hidden" name="sumitted_by" id="sumitted_by" value="<?php echo $sumitted_by; ?>"/>
													<input class="btn btn-xs btn-primary" type="submit" value="VIEW DO"/>
												</form>
											</p>
											<p>
												<form action="<?php echo site_url('EDOController/shedDeOInfoData')?>" method="POST">
													<input type="hidden" name="editFlag" value="editFlag"/>
													<input type="hidden" name="uploadId" value="<?php echo $uploadId; ?>"/>
													<input type="hidden" name="imp_rot" value="<?php echo $imp_rot; ?>"/>
													<input type="hidden" name="blNo" value="<?php echo $bl; ?>"/>
													<input type="hidden" name="edo_id" id="edo_id" value="<?php echo $edo_id; ?>"/>
													<input type="hidden" name="bl_type" id="bl_type" value="<?php echo $bl_type; ?>"/>
													<input type="hidden" name="igm_type" id="igm_type" value="<?php echo $igm_type; ?>"/>
													<input type="hidden" name="sumitted_by" id="sumitted_by" value="<?php echo $sumitted_by; ?>"/>
													<input class="btn btn-xs btn-primary" type="submit" value="Edit"/>
												</form>
											</p>
										<?php } else if($rejection_st==1) {?>
											<input class="btn btn-xs btn-danger" type="button" value="REJECTED"/>
									<?php } } ?>	
							<?php  } ?>
							</td>
							<?php } else if ($org_Type_id==1) { ?>
								<td>
									<!--MLO-->
									<?php if($igm_type=="GM" and $bl_type=="HB") { ?>
										<?php if($ff_stat==0) { ?>
											<?php if($mbl_of_hbl=="LCL") { ?>
											<form method="POST" action="<?php echo site_url("EDOController/updateStatforEDO") ?>" 
												onclick="return cnfrmClearance()">
												<input type="hidden" name="edo_id" id="edo_id" value="<?php echo $edo_id; ?>"/>
												<input type="submit" style="padding:1px;" class="btn btn-xs btn-primary" 
													value="Clearance to FF"/>
											</form>
											<?php } else { ?>
											<input type="button" style="padding:1px;" onclick="setValueOnClearance(<?php echo $edo_id;?>);"
												value="Clearance to FF" class="btn btn-xs btn-success" data-toggle="modal" data-target="#myModalClearance"/>
											<?php } ?>
										<?php } else if($ff_stat==1 and ($do_upload_st==1 or $rejection_st==1)) { ?>
											<?php if($do_upload_st==1) { ?>
												<form action="<?php echo site_url('EDOController/eDOPDF')?>" target="_blank" method="POST">
													<input type="hidden" name="shedMloDo" id="shedMloDo" value="<?php echo $uploadId; ?>"/>
													<input type="hidden" name="rotNo" id="rotNo" value="<?php echo $imp_rot; ?>"/>
													<input type="hidden" name="blno" id="blno" value="<?php echo $bl; ?>"/>
													<input type="hidden" name="bl_type" id="bl_type" value="<?php echo $bl_type; ?>"/>
													<input type="hidden" name="sumitted_by" id="sumitted_by" value="<?php echo $sumitted_by; ?>"/>
													<input class="btn btn-xs btn-primary" type="submit" value="VIEW DO"/>
												</form>
											<?php } else if($rejection_st==1) { ?>
												<input class="btn btn-xs btn-danger" type="button" value="REJECTED"/>
											<?php } ?>
										<?php } else { ?>
											<input type="button" style="padding:1px;" class="btn btn-xs btn-success" value="FORWARDED"/>
										<?php } ?>
									<?php } else if($igm_type=="GM" and $bl_type=="MB") { ?>
										<?php if($do_upload_st==0 and $rejection_st==0) { ?>
											<p>
												<form method="POST" action="<?php echo site_url("EDOController/shedDeOInfoData") ?>" >
													<input type="hidden" name="edo_id" id="edo_id" value="<?php echo $edo_id; ?>"/>
													<input type="hidden" name="imp_rot" id="imp_rot" value="<?php echo $imp_rot; ?>"/>
													<input type="hidden" name="blNo" id="blNo" value="<?php echo $bl; ?>"/>
													<input type="hidden" name="bl_type" id="bl_type" value="<?php echo $bl_type; ?>"/>
													<input type="hidden" name="igm_type" id="igm_type" value="<?php echo $igm_type; ?>"/>
													<input type="hidden" name="sumitted_by" id="sumitted_by" value="<?php echo $sumitted_by; ?>"/>
													<input type="submit" style="padding:1px;" class="btn btn-xs btn-primary" value="Upload DO"/>
												</form>
											</p>
											<p>
												<input type="button" style="padding:1px;" onclick="setValueOnModal(<?php echo $edo_id;?>);"
													value="Reject" class="btn btn-xs btn-danger" data-toggle="modal" data-target="#myModal"/>
											</p>
										<?php } else if($do_upload_st==1) { ?>
											<p>
												<form action="<?php echo site_url('EDOController/eDOPDF')?>" target="_blank" method="POST">
													<input type="hidden" name="shedMloDo" id="shedMloDo" value="<?php echo $uploadId; ?>"/>
													<input type="hidden" name="rotNo" id="rotNo" value="<?php echo $imp_rot; ?>"/>
													<input type="hidden" name="blno" id="blno" value="<?php echo $bl; ?>"/>
													<input type="hidden" name="bl_type" id="bl_type" value="<?php echo $bl_type; ?>"/>
													<input type="hidden" name="sumitted_by" id="sumitted_by" value="<?php echo $sumitted_by; ?>"/>
													<input class="btn btn-xs btn-primary" type="submit" value="VIEW DO"/>
												</form>
											</p>
											<p>
												<form action="<?php echo site_url('EDOController/shedDeOInfoData')?>" method="POST">
													<input type="hidden" name="editFlag" value="editFlag"/>
													<input type="hidden" name="uploadId" value="<?php echo $uploadId; ?>"/>
													<input type="hidden" name="imp_rot" value="<?php echo $imp_rot; ?>"/>
													<input type="hidden" name="blNo" value="<?php echo $bl; ?>"/>
													<input type="hidden" name="edo_id" id="edo_id" value="<?php echo $edo_id; ?>"/>
													<input type="hidden" name="bl_type" id="bl_type" value="<?php echo $bl_type; ?>"/>
													<input type="hidden" name="sumitted_by" id="sumitted_by" value="<?php echo $sumitted_by; ?>"/>
													<input class="btn btn-xs btn-primary" type="submit" value="Edit"/>
												</form>
											</p>
										<?php } else if($rejection_st==1) { ?>
											<input class="btn btn-xs btn-primary" type="button" value="REJECTED"/>
										<?php } ?>
							<?php } ?>
							</td>
							<td>
								<?php if($igm_type=="GM" and $bl_type=="HB") { ?>
								<?php if($do_upload_st==1 and $cont_status=="FCL") { ?> 
								
								<?php if($cnf_vldty_appr_st == 1) { ?>
								<form action="<?php echo site_url('EDOController/approveValidityExtensionForFCLandHBL')?>" method="POST">
									<input type="hidden" name="edo_id" id="edo_id" value="<?php echo $edo_id; ?>"/>
									<input type="submit" style="padding:1px;" value="APPROVE VALIDITY" class="btn btn-xs btn-success" />
								</form>
								<?php } ?>
								
								<!--input type="button" style="padding:1px;" onclick="setValueOnExpansionRequest(<?php echo $edo_id;?>);"
									value="EXTEND VALIDITY" class="btn btn-xs btn-success" data-toggle="modal" data-target="#myModalValidity"/-->
									
								<?php } else { ?>
								<!--input type="button" style="padding:1px;" value="EXTEND VALIDITY" class="btn btn-xs btn-success" disabled readonly /-->
								<?php } ?>
								<?php } else { ?>
								<!--input type="button" style="padding:1px;" value="EXTEND VALIDITY" class="btn btn-xs btn-success" disabled readonly /-->
								<?php } ?>
							</td>
							<?php } else if ($org_Type_id==5) { ?>
								<!--CPA-->
								<?php if($ff_assoc_st==1 and $do_upload_st==0) { ?>
								<td>		
									<input class="btn btn-xs btn-success" type="submit" value="NOT UPLOADED"/>																		
								</td>
								<?php } else { ?>
								<td>
									<form action="<?php echo site_url('EDOController/eDOPDF')?>" target="_blank" method="POST">
										<input type="hidden" name="shedMloDo" id="shedMloDo" value="<?php echo $uploadId; ?>"/>
										<input type="hidden" name="rotNo" id="rotNo" value="<?php echo $imp_rot; ?>"/>
										<input type="hidden" name="blno" id="blno" value="<?php echo $bl; ?>"/>
										<input type="hidden" name="bl_type" id="bl_type" value="<?php echo $bl_type; ?>"/>
										<input type="hidden" name="sumitted_by" id="sumitted_by" value="<?php echo $sumitted_by; ?>"/>
										<input class="btn btn-xs btn-primary" type="submit" value="VIEW DO"/>
									</form>
								</td>
								<?php }  ?>
								<td>
									<p>
										<p id="<?php echo "btnUnchecked".$i?>" <?php if($checkSt==0) { ?> style="display:block;" <?php } else { ?> style="display:none;" <?php } ?>>
											<input type="hidden" name="uploadIdtoApprove" id="<?php echo "uploadIdtoApprove".$i?>" 
												value="<?php echo $uploadId; ?>"/>
											<input type="hidden" name="edoIdtoApprove" id="<?php echo "edoIdtoApprove".$i?>" value="<?php echo $edo_id; ?>"/>
											<input type="submit" style="padding:1px;" class="btn btn-xs btn-success" value="Approve" 
												onclick="return cnfrmCpaApproval(<?php echo $i;?>)"/>										
										</p>
										<p id="<?php echo "btnChecked".$i?>" <?php if($checkSt==0) { ?> style="display:none;" <?php } else { ?> style="display:block;font-weight:bold;" <?php } ?>>
											<!--input type="button" style="padding:1px;" class="btn btn-xs btn-success" value="Checked"/-->
											Approved
										</p>
									</p>
								</td>
							<?php } ?>
					</tr>
					
					<?php } ?>
				</table>			
			</section>
		</div>
	</div>
</div>
</section>
</div>
<?php mysqli_close($con_cchaportdb); ?>