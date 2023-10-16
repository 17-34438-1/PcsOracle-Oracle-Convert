<script>
	function chkConfirm()
	{
		<?php if($frmType=="new") { ?>
			if (confirm("Do you want to save ?") == true)
				{
					return true ;
				}
			else
				{
					return false;
				}
		<?php } else if($frmType=="edit") { ?>
			if (confirm("Do you want to update ?") == true)
				{
					return true ;
				}
			else
				{
					return false;
				}
		<?php } ?>
	}

	function loadfile(event)
	{
		var output = document.getElementById('previewimg');
		output.src = URL.createObjectURL(event.target.files[0]);
	}
	
	function getOrgType()
	{
		var orgTypeId = document.getElementById('org_type').value;
		
		if(orgTypeId==4)
		{
			document.getElementById('agentCodeDiv').style.display="block";
		}
		else
		{
			document.getElementById('agentCodeDiv').style.display="none";
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
							<form class="form-horizontal form-bordered" method="POST" id="myform" name="myform" enctype="multipart/form-data" 
								onsubmit="return chkConfirm();" action="<?php echo site_url("Login/orgProfileEntry");?>">
								<div class="form-group">
								<input type="hidden" name="frmType" id="frmType" value="<?php echo $frmType;?>">
								<input type="hidden" name="orgprofileid" id="orgprofileid" 
									value="<?php if($frmType=="edit") { echo $proId; } else {echo "";}?>">
									<div class="col-md-6">
										<div class="input-group mb-md">
											<span class="input-group-addon span_width">
												Organization Type <span class="required">*</span>
											</span>
											<select class="form-control" name="org_type" id="org_type" onchange="getOrgType()" required>
												<option value="">--Select--</option>
												<?php for($i=0;$i<count($orgTypeList);$i++){ ?>
													<option value="<?php echo $orgTypeList[$i]["id"];?>" 
														<?php if($frmType=="edit" and $orgTypeList[$i]["id"]==$proDtlsById[0]["Org_Type_id"]) { ?> selected <?php } ?>>
														<?php echo $orgTypeList[$i]["Org_Type"];?>
													</option>
												<?php } ?>
											</select>
										</div>										
										<div class="input-group mb-md">
											<span class="input-group-addon span_width">
												AIN No <span class="required">*</span>
											</span>
											<input type="text" name="ain_no" id="ain_no" class="form-control" 
												placeholder="AIN Number" 
												value="<?php if($frmType=="edit") echo $proDtlsById[0]["AIN_No_New"]; else echo "";?>"
												<?php if($frmType=="edit") echo "readonly"; ?>
												required>
										</div>
										<div class="input-group mb-md">
											<span class="input-group-addon span_width">
												License No <span class="required">*</span>
											</span>
											<input type="text" name="lic_no" id="lic_no" class="form-control" placeholder="License No" 
												value="<?php if($frmType=="edit") echo $proDtlsById[0]["License_No"]; else echo "";?>"
												<?php if($frmType=="edit" and $login_id!="dskhorshed") echo "readonly"; ?>
												required>
										</div>
										<div class="input-group mb-md">
											<span class="input-group-addon span_width">
												License Validity Date <span class="required">*</span>
											</span>
											<input type="date" name="lic_validity_date" id="lic_validity_date" class="form-control" 
												placeholder="License Valididty Date" 
												value="<?php if($frmType=="edit") echo $proDtlsById[0]["Licence_Validity_Date"]; else echo "";?>" 
												<?php if($frmType=="edit" and $login_id!="dskhorshed" and $login_id!="dsshohid") echo "readonly"; ?>
												required>
												
												
										</div>
										<div class="input-group mb-md">
											<span class="input-group-addon span_width">
												Land Phone Number 
											</span>
											<input type="text" name="land_phone_no" id="land_phone_no" class="form-control" 
												placeholder="Land Phone Number" 
												value="<?php if($frmType=="edit") echo $proDtlsById[0]["Telephone_No_Land"]; else echo "";?>"
												>
										</div>
										
										<div class="input-group mb-md">
											<span class="input-group-addon span_width">
												Cell Phone No 1 <span class="required">*</span>
											</span>
											<input type="text" name="cell_phone_one" id="cell_phone_one" class="form-control" 
												placeholder="Cell Phone No 1" 
												value="<?php if($frmType=="edit") echo $proDtlsById[0]["Cell_No_1"]; else echo "";?>"
												required>
										</div>
										<div class="input-group mb-md">
											<span class="input-group-addon span_width">
												URL
											</span>
											<input type="text" name="url" id="url" class="form-control" 
												placeholder="URL" 
												value="<?php if($frmType=="edit") echo $proDtlsById[0]["URL"]; else echo "";?>">
										</div>
										
										<?php $orgTypeId = $this->session->userdata('org_Type_id'); ?>
										<div id="agentCodeDiv" <?php if($frmType=="edit" and ($orgTypeId==1 or $orgTypeId==4 or $orgTypeId==28)){ ?>style="display:block"<?php }else{ ?>style="display:none"<?php } ?>>
											<div class="input-group mb-md">
												<span class="input-group-addon span_width">
													Agent Code
												</span>
												<input type="text" name="agentCode" id="agentCode" class="form-control" placeholder="Agent Code" 
												value="<?php if($frmType=="edit") echo $proDtlsById[0]["Agent_Code"]; else echo "";?>"
												<?php if($frmType=="edit" and $orgTypeId==4) echo "readonly"; ?>
												>
											</div>
										</div>
										
										<?php if($orgTypeId == "28") { ?>
										<div id="baffaMembershipDiv" 
											<?php if($frmType=="edit" and $proDtlsById[0]["Org_Type_id"]=="4"){ ?> 
												style="display:block"
											<?php } else { ?>
												style="display:none"
											<?php } ?>
											>
											<div class="input-group mb-md">
												<div class="row">
													<div class="col-md-6" style="display:inline-block;" align="center">
														<font class="span_width" style="display:inline-block;font-weight:bold;" 
																align="center"> 
															Is Baffa Member ?
														</font>
													</div>
													<div class="col-md-3" style="display:inline-block;" align="right">
														<div class="form-check form-check-inline" style="display:inline-block;">
															<input class="form-check-input" type="radio" name="baffa_member" 
																id="baffa_member" value="1"
																<?php if($frmType=="edit" and $proDtlsById[0]["baffa_member"]=="1") echo "checked";?>
																
															/>
															<label class="form-check-label" for="inlineRadio1">YES</label>
														</div>
													</div>
													<div class="col-md-3" style="display:inline-block;" align="right">
														<div class="form-check form-check-inline" style="display:inline-block;">
															<input class="form-check-input" type="radio" name="baffa_member" 
																id="baffa_member" value="0"
																<?php if($frmType=="edit" and $proDtlsById[0]["baffa_member"]=="0") echo "checked";?>
															/>
															<label class="form-check-label" for="inlineRadio1">NO</label>
														</div>
													</div>
												</div>
											</div>											
										</div>
										<?php } ?>
										
										<div id="signDiv" style="display:none">
											<div class="input-group mb-md">
												<span class="input-group-addon span_width">Signature</span>
												<input type="file" name="signature" id="signature" class="form-control" >
											</div>
											<!--div class="input-group mb-md">
												<img src="<?php if($frmType=="edit"){?>
												<?php 
													if($proDtlsById[0]["logo"] != "" || $proDtlsById[0]["logo"] != null){
														echo ASSETS_PATH ?>organizationLogo/<?php echo $proDtlsById[0]["logo"];
													}else{
														echo ASSETS_PATH."organizationLogo/frame.png";
													}
												?>
												<?php }else{?><?php echo ASSETS_PATH;?>organizationLogo/frame.png<?php }?>" id="previewimg" width="180px" height="150px" alt="No photo selected" style="margin-left:50%;"/>
											</div-->
										</div>
										
									
									</div>
									<div class="col-md-6">
										<div class="input-group mb-md">
											<span class="input-group-addon span_width">
												Organization Name <span class="required">*</span>
											</span>
											<input type="text" name="org_name" id="org_name" class="form-control" 
												placeholder="Organization Name" 
												value="<?php if($frmType=="edit") echo $proDtlsById[0]["Organization_Name"]; else echo "";?>"
												required>
										</div>										
									
										<div class="input-group mb-md">
											<span class="input-group-addon span_width">
												Address 1<span class="required">*</span>
											</span>
											<textarea name="address_one" id="address_one" rows="2" class="form-control" 
												placeholder="Address..." required><?php if($frmType=="edit") echo $proDtlsById[0]["Address_1"]; else echo "";?></textarea>
										</div>
										<div class="input-group mb-md">
											<span class="input-group-addon span_width">
												Address 2
											</span>
											<textarea name="address_two" id="address_two" rows="1" class="form-control" 
												placeholder="Address..."><?php if($frmType=="edit") echo $proDtlsById[0]["Address_2"]; else echo "";?></textarea>
										</div>
										
										<div class="input-group mb-md">
											<span class="input-group-addon span_width">
												Cell Phone No 2
											</span>
											<input type="text" name="cell_phone_two" id="cell_phone_two" class="form-control" 
												placeholder="Cell Phone No 2" 
												value="<?php if($frmType=="edit") echo $proDtlsById[0]["Cell_No_2"]; else echo "";?>">
										</div>
										<div class="input-group mb-md">
											<span class="input-group-addon span_width">
												FAX
											</span>
											<input type="text" name="fax" id="fax" class="form-control" 
												placeholder="FAX" 
												value="<?php if($frmType=="edit") echo $proDtlsById[0]["Fax_No"]; else echo "";?>">
										</div>
										<div class="input-group mb-md">
											<span class="input-group-addon span_width">
												Email
											</span>
											<input type="text" name="email_address" id="email_address" class="form-control" 
												placeholder="Email Address" 
												value="<?php if($frmType=="edit") echo $proDtlsById[0]["email"]; else echo "";?>">
										</div>

										<div class="input-group mb-md">
                                            <span class="input-group-addon span_width">Logo </span>
                                            <input type="file" name="logo" id="logo" class="form-control" onchange="loadfile(event)">					
                                        </div>
										<div class="input-group mb-md">
											<img src="<?php if($frmType=="edit"){?>
											<?php 
												if($proDtlsById[0]["logo"] != "" || $proDtlsById[0]["logo"] != null){
													echo ASSETS_PATH ?>organizationLogo/<?php echo $proDtlsById[0]["logo"];
												}else{
													echo ASSETS_PATH."organizationLogo/frame.png";
												}
											?>
											<?php }else{?><?php echo ASSETS_PATH;?>organizationLogo/frame.png<?php }?>" id="previewimg" width="180px" height="150px" alt="No photo selected" style="margin-left:50%;"/>
										</div>

									
									</div>
									<div class="row">
										<div class="col-sm-12 text-center">
											<button type="submit" name="submit_login" id="submit" class="mb-xs mt-xs mr-xs btn btn-success"><?php if($frmType=="edit") { ?>Update<?php } else { ?> Save <?php } ?>
											</button>
										</div>													
									</div>
									<div class="row">
										<div class="col-sm-12 text-center">
											
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