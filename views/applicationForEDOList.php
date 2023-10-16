<section role="main" class="content-body">
	<header class="page-header">
		<h2><?php  echo $title; ?></h2>
	</header>
          
<div class="row">
	<div class="col-lg-12">						
		<?php
			include("mydbPConnection.php");
			$login_id = $this->session->userdata("login_id");
			$org_Type_id = $this->session->userdata("org_Type_id");
			$org_id = $this->session->userdata("org_id");	
			// echo $flag;
		?>

		<section class="panel">
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
										<input type="hidden" name="flag" id="flag" value="<?php echo $flag; ?>">
										<input type="hidden" name="cpa_search" id="cpa_search" value="<?php echo $cpa_search; ?>">
										<input type="hidden" name="searchBy" id="searchBy" value="<?php if($cpa_search==1) echo $searchBy; ?>">
										<input type="hidden" name="searchInput" id="searchInput" value="<?php if($cpa_search==1) echo $searchInput; ?>">
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
					<div class="modal fade" id="withdrawalModal">
						<div class="modal-dialog modal-block-sm">
							<div class="modal-content">
								<!-- Modal Header -->
								<div class="modal-header">
									<h4 class="modal-title">Withdraw Rejection</h4>
									<button type="button" class="close" data-dismiss="modal">&times;</button>
								</div>

								<div class="modal-body">
									<form class="form-horizontal form-bordered" method="POST" 
										action="<?php echo site_url('EDOController/withdrawRejectionStatus')?>" onsubmit="return confirmRejectionWithdrawal();">
										<input type="hidden" name="withdrawal_edo_id" id="withdrawal_edo_id" value="">
										<input type="hidden" name="flag" id="flag" value="<?php echo $flag; ?>">
										<input type="hidden" name="cpa_search" id="cpa_search" value="<?php echo $cpa_search; ?>">
										<input type="hidden" name="searchBy" id="searchBy" value="<?php if($cpa_search==1) echo $searchBy; ?>">
										<input type="hidden" name="searchInput" id="searchInput" value="<?php if($cpa_search==1) echo $searchInput; ?>">
										<div class="form-group">
											<label class="col-md-2 control-label">&nbsp;</label>
											<div class="col-md-8">
												<div class="input-group mb-md">
													<span class="input-group-addon span_width">Remarks</span>
													<textarea name="withdrawal_remarks" id="withdrawal_remarks" rows="3" class="form-control"></textarea>
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
					<div class="modal fade" id="myModalApproveValidityExtension">
						<div class="modal-dialog modal-block-sm">
							<div class="modal-content">
								<!-- Modal Header -->
								<div class="modal-header">
									<h4 class="modal-title">Approve Validity Extension</h4>
									<button type="button" class="close" data-dismiss="modal">&times;</button>
								</div>
								<div class="modal-body">
									<form class="form-horizontal form-bordered" method="POST" 
										action="<?php echo site_url('EDOController/approveValidityExtensionForFCLandHBL')?>">
										<input type="hidden" name="edoForApproveValidityExtension" id="edoForApproveValidityExtension" value="">
										<div class="form-group">
											<label class="col-md-2 control-label">&nbsp;</label>
											<div class="col-md-8">
												<div class="input-group mb-md">
													<span class="input-group-addon span_width">Valid upto date</span>
													<input type="date" class="form-control" 
														name="valid_upto_date_for_approval" id="valid_upto_date_for_approval" required>
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
					<div class="modal fade" id="myModalClearance">
						<div class="modal-dialog modal-block-sm">
							<div class="modal-content">
								<!-- Modal Header -->
								<div class="modal-header">
									<h4 class="modal-title">Clearance to FF</h4>
									<button type="button" class="close" data-dismiss="modal">&times;</button>
								</div>
								<div class="modal-body">
									<form class="form-horizontal form-bordered" method="POST" 
										action="<?php echo site_url('EDOController/updateStatforEDO')?>">
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
				<div class="container">
					<div class="modal fade" id="myModalHBValidityExtendByMlo">
						<div class="modal-dialog modal-block-sm">
							<div class="modal-content">
								<!-- Modal Header -->
								<div class="modal-header">
									<h4 class="modal-title">Validity Extention</h4>
									<button type="button" class="close" data-dismiss="modal">&times;</button>
								</div>
								<div class="modal-body">
									<form class="form-horizontal form-bordered" method="POST" 
										action="<?php echo site_url('EDOController/updateVaildityDateByMloForHB')?>">
										<input type="hidden" name="validityEdoId" id="validityEdoId" value="">
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
				<div class="panel-body">
					<div align="center"><b><?php echo $msg;?></b></div>
				</div>
				
				<!---search form for CPA role -->
		<?php if($org_Type_id==5 or $org_Type_id==62 or $org_Type_id==80 or $org_Type_id==28 or $org_Type_id==84 or $org_Type_id==1 or $org_Type_id==4 or $org_Type_id==2 or $org_Type_id==57 or $org_Type_id==93 or $org_Type_id==10 ) { ?>
		<form action="<?php echo site_url('EDOController/searchEDOapplication');?>" method="POST" >
			<div class="row">
				<div class="col-md-3">
					<div class="input-group mb-md">
						<span class="input-group-addon span_width">Search By</span>
						<select name="search_by" id="search_by" class="form-control" onchange="changeSearchOptions(this.value);">
							<option value="bl" label="BL" <?php if ($cpa_search==1 && $searchBy=="bl"){echo "selected";}?>>BL</option>
							<option value="rotation" label="Rotation" <?php if ($cpa_search==1 && $searchBy=="rotation"){echo "selected";}?>>Rotation</option>
							<option value="be_no" label="B/E" <?php if ($cpa_search==1 && $searchBy=="be_no"){echo "selected";}?>>B/E</option>
							<?php if($org_Type_id==5 or $org_Type_id==62 or $org_Type_id==80 or $org_Type_id==28 or $org_Type_id==84 ) { ?>
							<!--option value="exit_no" label="Exit No" <?php if ($cpa_search==1 && $searchBy=="exit_no"){echo "selected";}?>>Exit No</option-->
							<?php } ?>
						</select>
					</div>
				</div>				
				<div class="col-md-4">
					<div class="input-group mb-md">
						<span class="input-group-addon span_width">Search Value</span>
						<input type="text" class="form-control" id="searchInputVal" name="searchInput" required
						<?php //if($org_Type_id==5 or $org_Type_id==62 or $org_Type_id==80 or $org_Type_id==28 or $org_Type_id==84 ) { ?>
						value="<?php if(($flag == "all" || $flag=="pending" || $flag=="search") && ($cpa_search ==1)){echo $searchInput;}else {echo "";} ?>" 
						<?php //} ?>
						/>
					</div>
				</div>	
				<div class="col-md-4"> 
					<div class="input-group mb-md">
						<span class="input-group-addon span_width">B/E Date</span>
						<input type="date" class="form-control" id="searched_be_dt" name="searched_be_dt" 
							<?php if($cpa_search==1 && $searchBy=="be_no") echo ""; else echo "disabled"; ?>
							value="<?php if($cpa_search==1 and $searched_be_dt!=NULL) {echo $searched_be_dt;}?>">
					</div>
				</div>
				<div class="col-md-1">
					<button type="submit" value="Search" class="btn btn-primary">Search</button>
				</div>
			</div>
		</form>
		 
		 <hr/>
		<?php } ?>	 
				<!---search form for CPA role -->
				
				<table class="table table-bordered table-hover table-striped mb-none" id="datatable-default">
					<thead>
						<tr>
							<!-- <th>#Sl</th> -->
							<th>EDO No</th>
							<th class="text-center">Reg No & BL</th>
							<th class="text-center">BE NO</th>
							<th class="text-center">Type</th>
							<?php if($org_Type_id==5 or $org_Type_id==62 or $org_Type_id==80 or $org_Type_id==28 or $org_Type_id==84) { ?>
							<th class="text-center">Exit No</th>
							<?php } ?>
							<?php if($org_Type_id!=2) { ?>
							<th class="text-center">C&F </th>
							<?php } ?>
							<?php if($org_Type_id==1 or $org_Type_id==4 or $org_Type_id==2 or $org_Type_id==5 or $org_Type_id==73 or $org_Type_id==62 or $org_Type_id==80) { ?>
							<th class="text-center">MLO</th>							
							<th class="text-center">F.F</th>
							<?php } ?>
							<?php if($org_Type_id==10 or $org_Type_id==57 or $org_Type_id==93 or $org_Type_id==2 or $org_Type_id==5 or $org_Type_id==62 or $org_Type_id==80 
									or $org_Type_id==28 or $org_Type_id==84) { ?>
							<th class="text-center">Agent</th>
							<?php } ?>
							
							
							<th class="text-center">State</th>
							
							<?php if($org_Type_id==73){ ?>
							<th>Action</th>
							<?php } ?>
							<?php if($org_Type_id==1 or $org_Type_id==2){ ?>
							<th>Action</th>
							<?php } ?>
							<?php if($org_Type_id==5 or $org_Type_id==62 or $org_Type_id==80 or $org_Type_id==28 or $org_Type_id==84){ ?>
							<th>Action</th>
							<?php } ?>
							
							<th class="text-center">Date & Time</th>
							<th>Remarks</th>
							
							
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
							$edoQuery = "SELECT * FROM edo_application_by_cf 
										where bl_type='HB' AND igm_type='GM' AND ff_stat='1' ORDER BY id DESC LIMIT 50";
						}						
						else if($org_Type_id==57 or $org_Type_id==93 or $org_Type_id==10) //shipping_agent
						{
							if($flag=="search")
							{
								if($searchBy=='be_no')
								{
									if($searched_be_dt==NULL)
									{
										$doUploadId = "SELECT  imp_rot, bl_no FROM shed_mlo_do_info WHERE be_no='$searchInput' ORDER BY id DESC LIMIT 1";
										$resDoUploadId = mysqli_query($con_cchaportdb,$doUploadId);
									}
									else
									{
										$doUploadId = "SELECT  imp_rot, bl_no FROM shed_mlo_do_info 
											WHERE be_no='$searchInput' AND be_date='$searched_be_dt' ORDER BY id DESC LIMIT 1";
										$resDoUploadId = mysqli_query($con_cchaportdb,$doUploadId);
									}
																		
									$imp_rot="";
									$bl_no="";
						
									while($rowDoUploadId = mysqli_fetch_object($resDoUploadId)){
										$imp_rot = $rowDoUploadId->imp_rot;
										$bl_no = $rowDoUploadId->bl_no;
									}
												
									$edoQuery = "SELECT * FROM edo_application_by_cf 
									WHERE rotation='$imp_rot' AND bl='$bl_no' AND sh_agent_org_id='$org_id' ORDER BY id DESC LIMIT 50";
									
									
								} 								
								else 
								{
									$edoQuery = "SELECT * FROM edo_application_by_cf 
									where igm_type='BB' AND sh_agent_org_id='$org_id' AND $searchBy='$searchInput'
									ORDER BY id DESC LIMIT 50";									
								}
							} else {
								$edoQuery = "SELECT * FROM edo_application_by_cf 
										where igm_type='BB' and sh_agent_org_id='$Submitee_Org_Id' ORDER BY id DESC LIMIT 50";
							}							
						}
						else if($org_Type_id==4) //freight forwarder
						{
							// if($flag=="pending")
							// {
							// 	$edoQuery = "SELECT * FROM edo_application_by_cf 
							// 	WHERE igm_type='GM' AND bl_type='HB' AND ff_org_id='$org_id'
							// 	AND do_upload_st='0' AND rejection_st='0'
							// 	ORDER BY id DESC";
							// }
							// else
							// {
							// 	$edoQuery = "SELECT * FROM edo_application_by_cf 
							// 	where igm_type='GM' AND ff_org_id='$org_id' ORDER BY id DESC";
							// }
							
							if($flag=="pending")
							{
								$edoQuery = "SELECT * FROM edo_application_by_cf 
								WHERE igm_type='GM' AND bl_type='HB' AND ff_org_id='$org_id'
								AND (do_upload_st='0' OR vldty_appr_by_mlo_st=1)
								ORDER BY id DESC";
								//AND rejection_st='0'
							}
							else if($flag=="search")
							{
								if($searchBy=='be_no')
								{
									if($searched_be_dt==NULL)
									{
										$doUploadId = "SELECT  imp_rot, bl_no FROM shed_mlo_do_info WHERE be_no='$searchInput' ORDER BY id DESC LIMIT 1";
										$resDoUploadId = mysqli_query($con_cchaportdb,$doUploadId);
									}
									else
									{
										$doUploadId = "SELECT  imp_rot, bl_no FROM shed_mlo_do_info 
											WHERE be_no='$searchInput' AND be_date='$searched_be_dt' ORDER BY id DESC LIMIT 1";
										$resDoUploadId = mysqli_query($con_cchaportdb,$doUploadId);
									}
									
									// $doUploadId = "SELECT  imp_rot, bl_no FROM shed_mlo_do_info WHERE be_no='$searchInput' ORDER BY id DESC LIMIT 1";
									// $resDoUploadId = mysqli_query($con_cchaportdb,$doUploadId);
									
									$imp_rot="";
									$bl_no="";
						
									while($rowDoUploadId = mysqli_fetch_object($resDoUploadId)){
										$imp_rot = $rowDoUploadId->imp_rot;
										$bl_no = $rowDoUploadId->bl_no;
									}
												
									$edoQuery = "SELECT * FROM edo_application_by_cf 
									WHERE IF(bl_type='HB' AND igm_type='GM', ff_assoc_st, do_upload_st) ='1' AND rotation='$imp_rot' AND bl='$bl_no' ORDER BY id DESC LIMIT 50";
									
									// $edoQuery = "SELECT * FROM edo_application_by_cf 
									// WHERE IF(bl_type='HB' AND igm_type='GM', ff_assoc_st, do_upload_st) ='1' AND rotation='$imp_rot' AND bl='$bl_no' AND check_st='0'";
								}
								else
								{
									$edoQuery = "SELECT * FROM edo_application_by_cf 
									where igm_type='GM' AND ff_org_id='$org_id' AND $searchBy='$searchInput' or (mbl_of_hbl='$searchInput')
									 ORDER BY id DESC LIMIT 50";
									
									// $edoQuery = "SELECT * FROM edo_application_by_cf 
									// where igm_type='GM' AND check_st='0' AND ff_org_id='$org_id' AND $searchBy='$searchInput' or (mbl_of_hbl='$searchInput')";
								}
							}
							else
							{
								$edoQuery = "SELECT * FROM edo_application_by_cf 
								where igm_type='GM' AND ff_org_id='$org_id' AND (do_upload_st='1' AND vldty_appr_by_mlo_st=0) ORDER BY id DESC LIMIT 50";
							}
						}
						else if($org_Type_id==1) //MLO
						{
							// $edoQuery = "SELECT * FROM edo_application_by_cf 
							// 			WHERE igm_type='GM' AND mlo='$Submitee_Org_Id' 
							// 			-- AND (cont_status = 'FCL' OR cont_status = 'FCL/PART' OR cont_status = 'LCL' OR bl_type = 'MB')
							// 			ORDER BY id DESC";
							//echo $flag;
							if($flag=="pending")
							{
								$edoQuery = "SELECT * FROM edo_application_by_cf 
								WHERE igm_type='GM' AND mlo='$Submitee_Org_Id' AND (do_upload_st=0 OR cnf_vldty_appr_st=1)
								ORDER BY id DESC LIMIT 100";
							}
							else if($flag=="search")
							{
								if($searchBy=='be_no')
								{		
									if($searched_be_dt==NULL)
									{
										$doUploadId = "SELECT  imp_rot, bl_no FROM shed_mlo_do_info WHERE be_no='$searchInput' ORDER BY id DESC LIMIT 1";
										$resDoUploadId = mysqli_query($con_cchaportdb,$doUploadId);
									}
									else
									{
										$doUploadId = "SELECT  imp_rot, bl_no FROM shed_mlo_do_info WHERE be_no='$searchInput' AND be_date='$searched_be_dt' ORDER BY id DESC LIMIT 1";
										$resDoUploadId = mysqli_query($con_cchaportdb,$doUploadId);
									}
									
									// $doUploadId = "SELECT  imp_rot, bl_no FROM shed_mlo_do_info WHERE be_no='$searchInput' ORDER BY id DESC LIMIT 1";
									// $resDoUploadId = mysqli_query($con_cchaportdb,$doUploadId);
									
									$imp_rot="";
									$bl_no="";
						
									while($rowDoUploadId = mysqli_fetch_object($resDoUploadId)){
										$imp_rot = $rowDoUploadId->imp_rot;
										$bl_no = $rowDoUploadId->bl_no;
									}												
									
									$edoQuery = "SELECT * FROM edo_application_by_cf 
									WHERE IF(bl_type='HB' AND igm_type='GM', ff_assoc_st, do_upload_st) ='1' AND  rotation='$imp_rot' AND bl='$bl_no'";
								}
								else
								{
									$edoQuery = "SELECT * FROM edo_application_by_cf 
									WHERE igm_type='GM' AND mlo='$Submitee_Org_Id' AND $searchBy='$searchInput' or (mbl_of_hbl='$searchInput')";
								}
							}
							else
							{
								$edoQuery = "SELECT * FROM edo_application_by_cf 
								WHERE igm_type='GM' AND mlo='$Submitee_Org_Id' AND do_upload_st=1 AND cnf_vldty_appr_st=0
								ORDER BY id DESC LIMIT 100";
							}
						}
						else if($org_Type_id==5 or $org_Type_id==62 or $org_Type_id==80 or $org_Type_id==28 or $org_Type_id==84) //CPA or ONESTOP
						{
							// if($cpa_search==0)
							// {
							// $edoQuery = "SELECT * FROM edo_application_by_cf 
							// 			WHERE IF(bl_type='HB' AND igm_type='GM', ff_assoc_st, do_upload_st) ='1'
							// 			ORDER BY id DESC LIMIT 500";
							// }
							// else
							// {
							// 	    if($searchBy=='bl')
							// 		{
							// 			$edoQuery = "SELECT * FROM edo_application_by_cf 
							// 			WHERE IF(bl_type='HB' AND igm_type='GM', ff_assoc_st, do_upload_st) ='1'
							// 			AND bl='$searchInput'";
							// 		}
							// 		else if($searchBy=='rotation')
							// 		{
							// 			$edoQuery = "SELECT * FROM edo_application_by_cf 
							// 			WHERE IF(bl_type='HB' AND igm_type='GM', ff_assoc_st, do_upload_st) ='1'
							// 			AND rotation='$searchInput'";
							// 		}
							// 		else if($searchBy=='be')
							// 		{
							// 			$doUploadId = "SELECT  imp_rot, bl_no FROM shed_mlo_do_info WHERE be_no='$searchInput' ORDER BY id DESC LIMIT 1";
							// 			$resDoUploadId = mysqli_query($con_cchaportdb,$doUploadId);
										
							// 			$imp_rot="";
							// 			$bl_no="";
							
							// 			while($rowDoUploadId = mysqli_fetch_object($resDoUploadId)){
							// 				$imp_rot = $rowDoUploadId->imp_rot;
							// 				$bl_no = $rowDoUploadId->bl_no;
							// 			}
													
							// 			$edoQuery = "SELECT * FROM edo_application_by_cf 
							// 			WHERE IF(bl_type='HB' AND igm_type='GM', ff_assoc_st, do_upload_st) ='1' AND  rotation='$imp_rot' AND bl='$bl_no'";
							// 		}
							// }

							if($cpa_search==0)
							{
							$edoQuery = "SELECT * FROM edo_application_by_cf 
										WHERE IF(bl_type='HB' AND igm_type='GM', ff_assoc_st, do_upload_st) ='1'
										ORDER BY id DESC LIMIT 100";
							}
							else
							{
								if($searchBy=='be_no')
								{
									$doUploadId = "SELECT  imp_rot, bl_no FROM shed_mlo_do_info WHERE be_no='$searchInput' ORDER BY id DESC LIMIT 1";
									
									$resDoUploadId = mysqli_query($con_cchaportdb,$doUploadId);
									
									$imp_rot="";
									$bl_no="";
						
									while($rowDoUploadId = mysqli_fetch_object($resDoUploadId)){
										$imp_rot = $rowDoUploadId->imp_rot;
										$bl_no = $rowDoUploadId->bl_no;
									}
												
									$edoQuery = "SELECT * FROM edo_application_by_cf WHERE IF(bl_type='HB' AND igm_type='GM', ff_assoc_st, do_upload_st) ='1' AND  rotation='$imp_rot' AND bl='$bl_no'";
									
									// $edoQuery = "SELECT * FROM edo_application_by_cf WHERE IF(bl_type='HB' AND igm_type='GM', ff_assoc_st, do_upload_st) ='1' AND  rotation='$imp_rot' AND bl='$bl_no' AND check_st='0'";
								}
								else if($searchBy=='exit_no')
								{
									$edoQuery = "SELECT * FROM edo_application_by_cf 
										INNER JOIN sad_item ON sad_item.sum_declare=edo_application_by_cf.bl
										INNER JOIN sad_info ON sad_info.id=sad_item.sad_id
										WHERE (IF(bl_type='HB' AND igm_type='GM', ff_assoc_st, do_upload_st) ='1') AND (sad_info.place_dec='$searchInput')
										ORDER BY edo_application_by_cf.id DESC LIMIT 1";
									
									// $edoQuery = "SELECT * FROM edo_application_by_cf 
										// INNER JOIN sad_item ON sad_item.sum_declare=edo_application_by_cf.bl
										// INNER JOIN sad_info ON sad_info.id=sad_item.sad_id
										// WHERE (IF(bl_type='HB' AND igm_type='GM', ff_assoc_st, do_upload_st) ='1') AND check_st='0' AND (sad_info.place_dec='$searchInput')
										// ORDER BY edo_application_by_cf.id DESC LIMIT 1";
											
								}
								else
								{
									$edoQuery = "SELECT * FROM edo_application_by_cf 
									WHERE IF(bl_type='HB' AND igm_type='GM', ff_assoc_st, do_upload_st) ='1'
									AND $searchBy='$searchInput' or (mbl_of_hbl='$searchInput')";
									
									// $edoQuery = "SELECT * FROM edo_application_by_cf 
									// WHERE IF(bl_type='HB' AND igm_type='GM', ff_assoc_st, do_upload_st) ='1' AND check_st='0'
									// AND $searchBy='$searchInput' or (mbl_of_hbl='$searchInput')";
								}
							}
						}
						else  //cnf=2
						{
							$edoQuery = "";
							if($flag=="search"){
								if($searchBy=="bl"){
									$edoQuery = "SELECT * FROM edo_application_by_cf 
												WHERE bl='$searchInput' AND sumitted_by='$login_id' ORDER BY id DESC LIMIT 50";
								} else if($searchBy=="rotation"){
									$edoQuery = "SELECT * FROM edo_application_by_cf 
												WHERE rotation='$searchInput' AND sumitted_by='$login_id' ORDER BY id DESC LIMIT 50";
								} else if($searchBy=="be_no"){
									$doUploadId = "";
									if($searched_be_dt==NULL)
									{
										$doUploadId = "SELECT  imp_rot, bl_no FROM shed_mlo_do_info 
													WHERE be_no='$searchInput' ORDER BY id DESC LIMIT 1";
									}
									else
									{
										$doUploadId = "SELECT  imp_rot, bl_no FROM shed_mlo_do_info 
													WHERE be_no='$searchInput' AND be_date='$searched_be_dt' ORDER BY id DESC LIMIT 1";
									}
																		
									$resDoUploadId = mysqli_query($con_cchaportdb,$doUploadId);
									
									$imp_rot="";
									$bl_no="";
						
									while($rowDoUploadId = mysqli_fetch_object($resDoUploadId)){
										$imp_rot = $rowDoUploadId->imp_rot;
										$bl_no = $rowDoUploadId->bl_no;
									}
												
									$edoQuery = "SELECT * FROM edo_application_by_cf WHERE IF(bl_type='HB' AND igm_type='GM', ff_assoc_st, do_upload_st) ='1' AND  rotation='$imp_rot' AND bl='$bl_no'";
								}
							} else {
								$edoQuery = "SELECT * FROM edo_application_by_cf WHERE sumitted_by='$login_id' ORDER BY id DESC LIMIT 50";
							}
							
							
						}
						// echo $flag;
						// echo "<br>";
						// echo $edoQuery;
						// return;
						//echo $edoQuery;
						$edoResult = mysqli_query($con_cchaportdb,$edoQuery);
						// $row = mysqli_fetch_object($edoResult);
						// var_dump($row);
						
						$edo_id = "";
						$imp_rot = "";
						$bl = "";
						$exit_no = "";
						//$be_no = "";
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
						$checkSt = 0;
						$sumitted_by = "";
						$entry_time = "";
						$ff_clearance_time = "";
						$rejection_st = "";
						$rejection_time = "";
						$rejection_remarks = "";
						
						$rejected_by_org = "";
						$rejected_by_user = "";
						$withdrawn_by_org = "";
						$withdrawn_by_user = "";
						
						$applied_valid_dt = "";
						$cont_status = "";
						$mbl_of_hbl = "";
						$approve_at="";
						$cnf_vldty_appr_st="";
						$valid_upto_dt_by_mlo="";
						$vldty_appr_by_mlo_st="";
						
						$applied_validity_extension_at="";
						$approved_validity_extension_at="";
						
						
						$i=0;
						while($row = mysqli_fetch_object($edoResult)){
							$uploadId = "";
							$exit_no = "";
							
							$edo_id = $row->id;
							$imp_rot = $row->rotation;
							$bl = $row->bl;
							//$be_no = $row->be_no;
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
							
							$rejected_by_org = $row->rejected_by_org;							
							$rejected_by_user = $row->rejected_by_user;							
							$withdrawn_by_org = $row->withdrawn_by_org;							
							$withdrawn_by_user = $row->withdrawn_by_user;							
							
							$applied_valid_dt = $row->applied_valid_dt;
							$cont_status = $row->cont_status;
							$mbl_of_hbl = $row->mbl_of_hbl;
							$cnf_vldty_appr_st = $row->cnf_vldty_appr_st;
							$vldty_appr_by_mlo_st = $row->vldty_appr_by_mlo_st;
							$valid_upto_dt_by_mlo = $row->valid_upto_dt_by_mlo;
							
							$applied_validity_extension_at = $row->applied_validity_extension_at;
							$approved_validity_extension_at = $row->validity_approved_by_mlo_at;
							
							// 2022-04-13 - Intakhab
							/*
							$sql_exit_no = "SELECT manif_num,sad_item.sum_declare,sad_info.place_dec
										FROM sad_info
										INNER JOIN sad_item ON sad_info.id=sad_item.sad_id
										WHERE sad_info.manif_num LIKE CONCAT('%',REPLACE('$imp_rot','/',' '),'%') AND sad_item.sum_declare='$bl'";
							*/			
							
							// $sql_exit_no = "SELECT manif_num,sad_item.sum_declare,sad_info.place_dec
										// FROM sad_info
										// INNER JOIN sad_item ON sad_info.id=sad_item.sad_id
										// WHERE REPLACE(sad_info.manif_num,' ','')= REPLACE('$imp_rot','/','') AND sad_item.sum_declare='$bl';";
										
							$sql_exit_no = "SELECT manif_num,sad_item.sum_declare,sad_info.place_dec
										FROM sad_info
										INNER JOIN sad_item ON sad_info.id=sad_item.sad_id
										WHERE REPLACE(sad_info.manif_num,' ','') LIKE CONCAT('%',REPLACE('$imp_rot','/',''),'%')
										AND sad_item.sum_declare='$bl'";
							// echo $sql_exit_no;
							
							$rslt_exit_no = mysqli_query($con_cchaportdb,$sql_exit_no);
							while($row_exit_no = mysqli_fetch_object($rslt_exit_no))
							{
								$exit_no=trim($row_exit_no->place_dec);
							}
							
							
							// check for Bill of Entry - start
							//---
							
						//	/*
							$arr = explode("/",$imp_rot);
							// print_r($arr);
							$manif_num_2 = "";
							// 2022-04-13 - Intakhab
							/*
							if(strlen($arr[1])==1)
								$manif_num_2 = $arr[0]." 000".$arr[1];
							else if(strlen($arr[1])==2)
								$manif_num_2 = $arr[0]." 00".$arr[1];
							else if(strlen($arr[1])==3)
								$manif_num_2 = $arr[0]." 0".$arr[1];
							else if(strlen($arr[1])==4)
								$manif_num_2 = $arr[0]." ".$arr[1];
							*/
							if(strlen($arr[1])==1)
								$manif_num_2 = $arr[0]."000".$arr[1];
							else if(strlen($arr[1])==2)
								$manif_num_2 = $arr[0]."00".$arr[1];
							else if(strlen($arr[1])==3)
								$manif_num_2 = $arr[0]."0".$arr[1];
							else if(strlen($arr[1])==4)
								$manif_num_2 = $arr[0]."".$arr[1];
							// echo "<br>";
							// echo $manif_num_2;
							//---
							
							

							// $manif_num=str_replace("/"," ",$imp_rot);	// 2022-04-13 - Intakhab

							$manif_num=str_replace("/","",$imp_rot);
							
							if($exit_no=="")
							{
								// 2022-04-13 - Intakhab
								/*
								$sql_exit_no = "SELECT manif_num,sad_item.sum_declare,sad_info.place_dec
										FROM sad_info
										INNER JOIN sad_item ON sad_info.id=sad_item.sad_id
										WHERE (manif_num LIKE '%$manif_num%' OR manif_num LIKE '%$manif_num_2%') AND sad_item.sum_declare='$bl'";
								*/
								$sql_exit_no = "SELECT manif_num,sad_item.sum_declare,sad_info.place_dec
								FROM sad_info
								INNER JOIN sad_item ON sad_info.id=sad_item.sad_id
								WHERE (REPLACE(manif_num,' ','') LIKE '%$manif_num%' OR REPLACE(manif_num,' ','') LIKE '%$manif_num_2%') AND sad_item.sum_declare='$bl'";
										// echo $sql_exit_no;
								$rslt_exit_no = mysqli_query($con_cchaportdb,$sql_exit_no);
								while($row_exit_no = mysqli_fetch_object($rslt_exit_no))
								{
									$exit_no=trim($row_exit_no->place_dec);
								}
							}
							// echo "<br>";
							// echo $manif_num;
							
							// 2022-04-13 - Intakhab
							/*
							$sql_chkBE = "SELECT COUNT(*) AS cnt
										FROM sad_info
										INNER JOIN sad_item ON sad_item.sad_id=sad_info.id
										WHERE (manif_num LIKE '%$manif_num%' OR manif_num LIKE '%$manif_num_2%') AND sum_declare='$bl'";
							*/
							$chkBE = 0;
							$sql_chkBE = "SELECT COUNT(*) AS cnt
										FROM sad_info
										INNER JOIN sad_item ON sad_item.sad_id=sad_info.id
										WHERE (REPLACE(manif_num,' ','') LIKE '%$manif_num%' OR REPLACE(manif_num,' ','') LIKE '%$manif_num_2%') AND sad_item.sum_declare='$bl'";
										
							// echo "<br>";
							// echo $sql_chkBE;
							
							$rslt_chkBE = mysqli_query($con_cchaportdb,$sql_chkBE);
														
							while($row = mysqli_fetch_object($rslt_chkBE))
							{
								$chkBE=$row->cnt;
							}
							// echo "<br>";
							// echo $chkBE;
							
							/* 
								if no data with manif_num and sum_declare, get be_no and be_date from shed_mlo_do_info
								with edo_id and check in sad_info again
							*/
							if($chkBE==0)
							{
								// 2022-04-13 - Intakhab
								// $sql_beNoDt = "SELECT edo_id,shed_mlo_do_info.be_no,shed_mlo_do_info.be_date,rotation,bl
								// FROM edo_application_by_cf
								// INNER JOIN shed_mlo_do_info ON shed_mlo_do_info.edo_id=edo_application_by_cf.id
								// WHERE shed_mlo_do_info.edo_id='$edo_id'";

								$sql_beNoDt = "SELECT edo_id,shed_mlo_do_info.be_no,shed_mlo_do_info.be_date,imp_rot AS rotation,bl_no AS bl
								FROM shed_mlo_do_info
								WHERE shed_mlo_do_info.edo_id='$edo_id'";
								
								$rslt_beNoDt = mysqli_query($con_cchaportdb,$sql_beNoDt);
								
								while($row_beNoDt = mysqli_fetch_object($rslt_beNoDt))
								{
									$beNoChk=$row_beNoDt->be_no;
									$beDtChk=$row_beNoDt->be_date;
									
									$sql_chkBE = "SELECT COUNT(*) AS cnt
												FROM sad_info
												WHERE reg_no='$beNoChk' AND reg_date='$beDtChk'";
												
									$rslt_chkBE = mysqli_query($con_cchaportdb,$sql_chkBE);
																
									while($row_chkBE = mysqli_fetch_object($rslt_chkBE))
									{
										$chkBE=$row_chkBE->cnt;
									}									
								}
							}
						//	*/
							
							// old code
							
							/* 
							$manif_num=str_replace("/"," ",$imp_rot);
									
							$sql_chkBE = "SELECT COUNT(*) AS cnt
										FROM sad_info
										INNER JOIN sad_item ON sad_item.sad_id=sad_info.id
										WHERE manif_num LIKE '%$manif_num%' AND sum_declare='$bl'";
							$rslt_chkBE = mysqli_query($con_cchaportdb,$sql_chkBE);
							
							$chkBE = 0;
							while($row = mysqli_fetch_object($rslt_chkBE))
							{
								$chkBE=$row->cnt;
							} 
							*/
							
							// check for Bill of Entry - end
							
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
									$queryContStatus = "select igm_supplimentary_detail.master_BL_No,
												igm_sup_detail_container.cont_status
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
							
							
							$place_of_unloading="";
							$queryPlaceOfUnloading="";	
							
							$queryChkInSupplementary = "SELECT * FROM igm_supplimentary_detail WHERE igm_supplimentary_detail.BL_No='$bl'";
							$resChkInSupplementary = mysqli_query($con_cchaportdb,$queryChkInSupplementary);
							if(mysqli_num_rows($resChkInSupplementary) > 0){
								$queryPlaceOfUnloading = "SELECT igm_details.place_of_unloading FROM igm_supplimentary_detail 
														INNER JOIN igm_details ON igm_supplimentary_detail.igm_detail_id=igm_details.id
														WHERE igm_supplimentary_detail.BL_No='$bl'";
							} else {
								$queryPlaceOfUnloading = "SELECT place_of_unloading FROM igm_details WHERE BL_No='$bl'";
							}
							$resPlaceOfUnloading = mysqli_query($con_cchaportdb,$queryPlaceOfUnloading);
							while($rowPlaceOfUnloading = mysqli_fetch_object($resPlaceOfUnloading)){
									$place_of_unloading = $rowPlaceOfUnloading->place_of_unloading;
								}
						
							if($ff_clearance_time=="0000-00-00 00:00:00")
							{
								$ff_clearance_time = "";
							}
							$doUploadId = "SELECT * FROM shed_mlo_do_info WHERE imp_rot='$imp_rot' AND bl_no='$bl' 
										ORDER BY id DESC LIMIT 1";
							$resDoUploadId = mysqli_query($con_cchaportdb,$doUploadId);
							
							$uploadId="";
							$uploadedAt="";
							$validityDt="";
							$checkSt="";
							$approve_at="";
							$be_no = "";
							$be_date = "";
							$edo_mlo = "";
							$edo_sl = "";
							$edo_year = "";
							$edo_number = "";
							while($rowDoUploadId = mysqli_fetch_object($resDoUploadId)){
								$uploadId = $rowDoUploadId->id;
								$uploadedAt = $rowDoUploadId->upload_time;
								$validityDt = $rowDoUploadId->valid_upto_dt;
								$checkSt = $rowDoUploadId->check_st;
								$approve_at = $rowDoUploadId->cpa_check_time;
								$be_no = $rowDoUploadId->be_no;
								$be_date = $rowDoUploadId->be_date;
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
					<?php  
						if($org_Type_id == 80){
							if($checkSt == 0 || $flag == 'search') // added $flag condition at 24 jan 2022
							{
					?>
					<tr>
						<!-- <td><?php //echo $i; ?></td> -->
						<td><?php echo $edo_number; //$uploadId; ?></td>
						<td>
							<?php 
								echo "Reg-".$imp_rot; 
								echo "<br>";
								echo "<nobr>"." BL-".$bl."</nobr>"; 
								echo "<br>";
								echo "<nobr>"." MBL-".$mbl_of_hbl."</nobr>";
								echo "<br>";
								echo "<nobr>"."Container Type-".$cont_status."</nobr>";
							?>
						</td>
						<td>
							<?php 
								echo $be_no; 
								echo "<br>";
								if($be_date != "") {
									echo "<b>Date : </b>".date("d/m/Y", strtotime($be_date));
								}
								
							?>
						</td>
						<td><?php echo $bl_type_txt."<br>".$igm_type; ?></td>
						
						<?php if($org_Type_id==5 or $org_Type_id==62 or $org_Type_id==80 or $org_Type_id==28 or $org_Type_id==84) { ?>
						<td><?php echo $exit_no; ?></td>
						<?php } ?>
						
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
						<?php if($org_Type_id==1 or $org_Type_id==28 or $org_Type_id==4 or $org_Type_id==2 or $org_Type_id==5 
									or $org_Type_id==73 or $org_Type_id==62 or $org_Type_id==80) { ?>
						<td>
							
							<?php
								$mloName = "";
								$mloAIN = "";
								$mlocode = "";
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
								
								if($bl_type=="MB")
								{
									$strIGM = "SELECT igm_details.mlocode FROM igm_details WHERE igm_details.Import_Rotation_No='$imp_rot' AND igm_details.BL_No='$bl'";
									$resIGM = mysqli_query($con_cchaportdb,$strIGM);
									while($rowIGM = mysqli_fetch_object($resIGM)){
										$mlocode = $rowIGM->mlocode;
									}
									echo "<br><b>MLO Code : </b>".$mlocode;
								}
								else
								{
									$strIGM = "SELECT igm_details.mlocode FROM igm_details WHERE igm_details.Import_Rotation_No='$imp_rot' AND igm_details.BL_No='$mbl_of_hbl'";
									$resIGM = mysqli_query($con_cchaportdb,$strIGM);
									while($rowIGM = mysqli_fetch_object($resIGM)){
										$mlocode = $rowIGM->mlocode;
									}
									echo "<br><b>MLO Code : </b>".$mlocode;
								}
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
						<?php if($org_Type_id==10 or $org_Type_id==57 or $org_Type_id==93 or $org_Type_id==2 or $org_Type_id==5 or $org_Type_id==62 or $org_Type_id==80 
								or $org_Type_id==28 or $org_Type_id==84) { ?>
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
						
						<?php
						if ($org_Type_id==73) { ?>
						<td>
							<!--Freight Forwarder Association-->
							<?php if($do_upload_st==0 and $rejection_st==0) 
									{ 
							?>
								<input class="btn btn-xs btn-primary" type="button" value="FORWARDED"/>
							<?php 
									} 
									else if($do_upload_st==1) 
									{ 									
									
										
							?>
								<form action="<?php echo site_url('EDOController/eDOPDF')?>" target="_blank" method="POST">
									<input type="hidden" name="shedMloDo" id="shedMloDo" value="<?php echo $uploadId; ?>"/>
									<input type="hidden" name="rotNo" id="rotNo" value="<?php echo $imp_rot; ?>"/>
									<input type="hidden" name="blno" id="blno" value="<?php echo $bl; ?>"/>
									<input type="hidden" name="bl_type" id="bl_type" value="<?php echo $bl_type; ?>"/>
									<input type="hidden" name="sumitted_by" id="sumitted_by" value="<?php echo $sumitted_by; ?>"/>
									<input class="btn btn-xs btn-primary" type="submit" value="VIEW DO"/>
								</form>
							<?php 	} 
									else if($rejection_st==1) { ?>
								<input class="btn btn-xs btn-primary" type="button" value="Rejected"/>
							<?php } ?>
						</td>
						<td>
							<?php if($rejection_st==0) { ?>
								<?php if($ff_assoc_st==1){ ?>
									<input class="btn btn-xs btn-success" type="button" value="approved"/>
								<?php }else if($ff_assoc_st==0){ ?>
									<!--form action="<?php echo site_url('EDOController/ffAssocStateChange')?>" method="POST">
										<input type="hidden" name="edoId" id="edoId" value="<?php echo $edo_id; ?>"/>
										<input type="hidden" name="sumitted_by" id="sumitted_by" value="<?php echo $sumitted_by; ?>"/>
										<input type="hidden" name="cnfLic" id="cnfLic" value="<?php echo $applicantLic; ?>"/>
										<input class="btn btn-xs btn-primary" type="button" value="Approve"/>
									</form-->
								<?php } ?>
							<?php } else { ?>
								<input class="btn btn-xs btn-danger" type="button" value="Rejected"/>
							<?php } ?>
						</td>						
						<?php } else if($org_Type_id==57 or $org_Type_id==93 or $org_Type_id==10) { ?>
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
								<?php if($rejection_st==0) { ?>
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
								<?php } else { ?>
									<input type="button" style="padding:1px;" value="View Do" class="btn btn-xs btn-danger" 
										onclick="showRejected();"	/>
								<?php } ?>
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
								<input class="btn btn-xs btn-danger" type="button" value="REJECTED"/>
							<?php } ?>
						</td>
						<?php }  else if ($org_Type_id==2){ ?>
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
												<input class="btn btn-xs btn-primary" type="submit" value="VIEW DO"/>	<!-- 24 Jan 22 -->
												<!--input class="btn btn-xs btn-primary" type="submit" value="VIEW DO" <?php if($chkBE==0){ ?>disabled<?php } ?>/-->
											</form>
										<?php } else if($rejection_st==1) { ?>
											<input class="btn btn-xs btn-danger" type="button" value="REJECTED"/>
										<?php } ?>
									<?php } else { ?>
										<?php if($cont_status=="FCL" or $cont_status=="FCL/PART" or $cont_status=="ETY") { ?>
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
								<?php if(($igm_type=="GM") and ($bl_type=="HB") and ($cont_status=="FCL" or $cont_status=="FCL/PART" or $cont_status=="ETY")) { ?>
									<?php if($ff_stat==0) { ?>
										<form method="POST" action="<?php echo site_url("EDOController/deleteEDOApplication") ?>" onsubmit="return confirmDlt();">
											<input type="hidden" name="edo_id" id="edo_id" value="<?php echo $edo_id; ?>"/>
											<input type="submit" style="padding:1px;" class="btn btn-xs btn-danger" value="DELETE DO"/>
										</form>
									<?php } else { ?>
										<!--input type="button" style="padding:1px;" value="DELETE DO" class="btn btn-xs btn-danger" disabled readonly /-->
										<!--validity extension-->
										<input type="button" style="padding:1px;" onclick="setValueOnValidityExtensionforHBLAndFCL(<?php echo $edo_id;?>);"
											value="Extend Validity" class="btn btn-xs btn-success" data-toggle="modal" 
											data-target="#myModalforEdoForFCLAndHBL"/>
									<?php } ?>
								<?php } else { ?>
									<?php if($do_upload_st==1) { ?>
										<input type="button" style="padding:1px;" onclick="setValueOnExpansionRequest(<?php echo $edo_id;?>);"
											value="Extend Validity" class="btn btn-xs btn-success" data-toggle="modal" data-target="#myModalValidity"/>
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
										<?php if($cont_status=="FCL" or $cont_status=="FCL/PART" or $cont_status=="ETY") { ?>
											<?php //if($today <= $valid_upto_dt_by_mlo) { ?>	
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
											<?php //} else { 
												//echo "<b>Validity date set by MLO (".$valid_upto_dt_by_mlo.") has expired.</b>";
											//} ?>												
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
									<?php } else if($do_upload_st==1) {?>
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
											<?php if($rejection_st==0) { ?>
												<?php if($checkSt==0) { ?>
													<p>
														<input type="button" style="padding:1px;" onclick="setValueOnModal(<?php echo $edo_id;?>);"
														value="Reject" class="btn btn-xs btn-danger" data-toggle="modal" data-target="#myModal"/>
													</p>
												<?php } ?>
											<?php } ?>
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
											<?php if($cont_status=="LCL") { ?>
											<form method="POST" action="<?php echo site_url("EDOController/updateStatforEDO") ?>" 
												onclick="return cnfrmClearance()">
												<input type="hidden" name="clearanceEDOId" id="clearanceEDOId" value="<?php echo $edo_id; ?>"/>
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
													<input type="submit" style="padding:1px;" class="btn btn-xs btn-primary" value="UPLOAD DO"/>
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
											<?php if($rejection_st==0) { ?>
												<?php if($checkSt==0) { ?>
													<p>
														<input type="button" style="padding:1px;" onclick="setValueOnModal(<?php echo $edo_id;?>);"
														value="Reject" class="btn btn-xs btn-danger" data-toggle="modal" data-target="#myModal"/>
													</p>
												<?php } ?>
											<?php } ?>
											
										<?php } else if($rejection_st==1) { ?>
											<input class="btn btn-xs btn-primary" type="button" value="REJECTED"/>
										<?php } ?>
							<?php } ?>
							</td>
							<td>
								<?php if($igm_type=="GM" and $bl_type=="HB") { ?>
								<?php if($do_upload_st==1 and ($cont_status=="FCL" or $cont_status=="FCL/PART" or $cont_status=="ETY")) { ?>  
								
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
							<?php } else if ($org_Type_id==5 or $org_Type_id==62 or $org_Type_id==80 or $org_Type_id==28 or $org_Type_id==84) { ?>
								<!--CPA-->
								<?php if($ff_assoc_st==1 and $do_upload_st==0) { ?>
								<td>		
									<input class="btn btn-xs btn-success" type="submit" value="NOT UPLOADED"/>																		
								</td>
								<?php } else { ?>
								<td>
									<?php if($rejection_st==0) { ?>
										<form action="<?php echo site_url('EDOController/eDOPDF')?>" target="_blank" method="POST">
											<input type="hidden" name="shedMloDo" id="shedMloDo" value="<?php echo $uploadId; ?>"/>
											<input type="hidden" name="rotNo" id="rotNo" value="<?php echo $imp_rot; ?>"/>
											<input type="hidden" name="blno" id="blno" value="<?php echo $bl; ?>"/>
											<input type="hidden" name="bl_type" id="bl_type" value="<?php echo $bl_type; ?>"/>
											<input type="hidden" name="sumitted_by" id="sumitted_by" value="<?php echo $sumitted_by; ?>"/>
											<input class="btn btn-xs btn-primary" type="submit" value="VIEW DO"/>	<!-- 24 Jan 22 -->
											<!--input class="btn btn-xs btn-primary" type="submit" value="VIEW DO" <?php if($org_Type_id==80 and $chkBE==0){ ?>disabled<?php } ?>/-->
										</form>
									<?php } else { ?>
										<input type="button" style="padding:1px;" value="View Do" class="btn btn-xs btn-danger" 
											onclick="showRejected();"	/>
									<?php } ?>
									<?php
									if($org_Type_id==80 and $chkBE==0)
									// if($org_Type_id==80 and ($exit_no=="" or $exit_no==null))
									{
									?>
										<p style="color:red"><b>Please upload B/E</b></p>
									<?php
									}
									?>
								</td>
								<?php }  ?>
								<td>
									<p>
										<?php if($rejection_st=="0") { ?>
										<p id="<?php echo "btnUnchecked".$i?>" <?php if($checkSt==0) { ?> style="display:block;" <?php } else { ?> style="display:none;" <?php } ?>>
											<?php if($checkSt==0) { ?>
											<form action="<?php echo site_url('EDOController/changeChkState')?>" 
												onsubmit="return confirmApproval();" method="POST">
												<input type="hidden" name="uploadIdtoApprove" id="uploadIdtoApprove" value="<?php echo $uploadId; ?>"/>
												<input type="hidden" name="edoIdtoApprove" id="edoIdtoApprove" value="<?php echo $edo_id; ?>"/>
												<input type="hidden" name="bl" id="bl" value="<?php echo $bl; ?>"/>
												<!--input class="btn btn-xs btn-success" type="submit" value="Approve"/-->	<!-- 01 Feb 22 -->
											<input class="btn btn-xs btn-success" type="submit" value="Approve" 
												<?php if($place_of_unloading=="BDROP"){ ?>
													
												<?php } else if($chkBE==0){ ?>
													disabled
												<?php } else { ?> 
												
												<?php } ?> 
											/>
											<!--input class="btn btn-xs btn-success" type="submit" value="Approve" <?php if(($exit_no=="" or $exit_no==null)){ ?>disabled<?php } ?> /-->
											</form>
											<?php } ?>
										
											<!--input type="hidden" name="uploadIdtoApprove" id="<?php echo "uploadIdtoApprove".$i?>" 
												value="<?php echo $uploadId; ?>"/>
											<input type="hidden" name="edoIdtoApprove" id="<?php echo "edoIdtoApprove".$i?>" value="<?php echo $edo_id; ?>"/>
											<input type="submit" style="padding:1px;" class="btn btn-xs btn-success" value="Approve" onclick="return cnfrmCpaApproval(<?php echo $i;?>)"/-->	<!-- 24 Jan 22 -->
											<!--input type="submit" style="padding:1px;" class="btn btn-xs btn-success" value="Approve" onclick="return cnfrmCpaApproval(<?php echo $i;?>)" <?php if($chkBE==0){ ?>disabled<?php } ?>/-->
										</p>
										<?php } else { ?>
										<p class="text-center">
											<input class="btn btn-xs btn-primary" type="button" value="Approve" disabled/><br/>
											Status : Rejected
										<p>
										<?php } ?>
										<!--if($org_Type_id==80 and ($exit_no=="" or $exit_no==null)) place_of_unloading-->
										<?php if($org_Type_id==80 and $chkBE==0) { ?>
											<?php if($place_of_unloading!="BDROP"){ ?>
												<p style="color:red"><b>Please upload B/E <?php //echo $place_of_unloading;?></b></p>
											<?php } ?>
										<?php } ?>
										
										<p id="<?php echo "btnChecked".$i?>" 
											<?php if($checkSt==0) { ?> 
												style="display:none;" 
											<?php } else { ?> 
												style="display:block;font-weight:bold;" 
											<?php } ?>>
											<!--input type="button" style="padding:1px;" class="btn btn-xs btn-success" value="Checked"/-->
											Approved 
										</p>
									</p>
								</td>
								<?php } ?>
								<td>
									<?php 
										echo "<nobr>"."Application- "."<b>".$entry_time."</b>"."</nobr>";
										echo "<br>";
										echo "<nobr>"."Forwarding- "."<b>".$ff_clearance_time."</b>"."</nobr>";
										echo "<br>";
										echo "<nobr>"."Issuing- "."<b>".$uploadedAt."</b>"."</nobr>";
										echo "<br>";
										echo "<nobr>"."CPA Approved at- "."<b>".$approve_at."</b>"."</nobr>";
									?>
								</td>
								<td>
									<?php 
										if ($org_Type_id==73 or $org_Type_id==5 or $org_Type_id==62 or $org_Type_id==80 or $org_Type_id==28 or $org_Type_id==84) {
											//--Freight Forwarder Association/CPA/ONESTOP
											if($rejection_st==1) { 
												echo $rejection_remarks."<br>"."<b>Time-</b>".$rejection_time;
											}
										} else if($org_Type_id==57 or $org_Type_id==93 or $org_Type_id==10 or $org_Type_id==2 or $org_Type_id==4 or $org_Type_id==1) {
											//--Shipping Agent/C&F/MLO/FF
											if($rejection_st==1) { 
												echo $rejection_remarks."<br>"."<b>Time-</b>".$rejection_time;
											} else if($bl_type=="MB") {
												
												if($cnf_vldty_appr_st=='0' and $vldty_appr_by_mlo_st=='0') {
													echo "";
												} else if($cnf_vldty_appr_st=='1' and $vldty_appr_by_mlo_st=='0') {
													if(($validityDt != $applied_valid_dt) and ($applied_valid_dt != NULL) and ($applied_valid_dt != "0000-00-00") 
															and ($cnf_vldty_appr_st=='1')) { 
														echo "applied validity up to date-"."<b>".$applied_valid_dt."</b>"; 
													}
												} else if($cnf_vldty_appr_st=='0' and $vldty_appr_by_mlo_st=='1') {
													echo "Validity Extension Approved";
												} else if($cnf_vldty_appr_st=='1' and $vldty_appr_by_mlo_st=='1') {
													echo "applied validity up to date-"."<b>".$applied_valid_dt."</b>"; 
												}
												
											}
											
											else if(($validityDt != $applied_valid_dt) and ($applied_valid_dt != NULL) and ($applied_valid_dt != "0000-00-00") 
													and ($cnf_vldty_appr_st=='1')) { 
												echo "applied validity up to date-"."<b>".$applied_valid_dt."</b>"; 
											}
										}
									 ?>
									 <input type="button" style="padding:1px;" value="INFO" class="btn btn-xs btn-primary"
										onclick="setInfoOnModal('<?php echo $entry_time;?>','<?php echo $ff_clearance_time;?>','<?php echo $uploadedAt;?>');"
										  data-toggle="modal" data-target="#modalDtTime" />
								</td>
						</tr>
						<?php
									} 
								} else {
						?>

					<tr>
						<!-- <td><?php //echo $i; ?></td> -->
						<td><?php echo $edo_number; //$uploadId; ?></td>
						<td>
							<?php 
								echo "Reg-".$imp_rot; 
								echo "<br>";
								echo "<nobr>"." BL-".$bl."</nobr>"; 
								echo "<br>";
								echo "<nobr>"." MBL-".$mbl_of_hbl."</nobr>";
								echo "<br>";
								echo "<nobr>"."Container Type-".$cont_status."</nobr>";
							?>
						</td>
						<td>
							<?php 
								echo $be_no; 
								echo "<br>";
								if($be_date != "") {
									echo "<b>Date : </b>".date("d/m/Y", strtotime($be_date));
								}
								
							?>
						</td>
						<td><?php echo $bl_type_txt."<br>".$igm_type; ?></td>
						
						<?php if($org_Type_id==5 or $org_Type_id==62 or $org_Type_id==80 or $org_Type_id==28 or $org_Type_id==84) { ?>
							<td><?php echo $exit_no; ?></td>
						<?php } ?>
						
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
						<?php if($org_Type_id==1 or $org_Type_id==4 or $org_Type_id==2 or $org_Type_id==5 or $org_Type_id==73 or $org_Type_id==62 or $org_Type_id==80) { ?>
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
						<?php if($org_Type_id==10 or $org_Type_id==57 or $org_Type_id==93 or $org_Type_id==2 or $org_Type_id==5 or $org_Type_id==62 or $org_Type_id==80 or $org_Type_id==28 or $org_Type_id==84) { ?>
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
								<input class="btn btn-xs btn-primary" type="button" value="Rejected"/>
							<?php } ?>
						</td>
						<td>
							<?php if($rejection_st==0) { ?>
								<?php if($ff_assoc_st==1){ ?>
									<input class="btn btn-xs btn-success" type="button" value="approved"/>
								<?php }else if($ff_assoc_st==0){ ?>
									<!--form action="<?php echo site_url('EDOController/ffAssocStateChange')?>" method="POST">
										<input type="hidden" name="edoId" id="edoId" value="<?php echo $edo_id; ?>"/>
										<input type="hidden" name="sumitted_by" id="sumitted_by" value="<?php echo $sumitted_by; ?>"/>
										<input type="hidden" name="cnfLic" id="cnfLic" value="<?php echo $applicantLic; ?>"/>
										<input class="btn btn-xs btn-primary" type="button" value="Approve"/>
									</form-->
								<?php } ?>
							<?php } else { ?>
								<input class="btn btn-xs btn-danger" type="button" value="Rejected"/>
							<?php } ?>
						</td>						
						<?php } else if($org_Type_id==57 or $org_Type_id==93 or $org_Type_id==10) { ?>
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
								<?php if($rejection_st==0) { ?>
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
									<p>
										<input type="button" style="padding:1px;" onclick="setValueOnModal(<?php echo $edo_id;?>);"
											value="Reject" class="btn btn-xs btn-danger" data-toggle="modal" data-target="#myModal"/>
									</p>									
								<?php } else { ?>
									<p>
										<input class="btn btn-xs btn-danger" type="button" value="VIEW DO" disabled/>
									</p>
									<p>
										<input class="btn btn-xs btn-danger" type="button" value="REJECTED"/>
									</p>
									<?php if($rejected_by_org == $org_id) { ?>
										<p>
											<input type="button" style="padding:1px;" onclick="setValueOnWithdrawalModal(<?php echo $edo_id;?>);"
											value="WITHDRAW REJECTION" class="btn btn-xs btn-success" data-toggle="modal" data-target="#withdrawalModal"/>
										</p>
									<?php } ?>
									
								<?php } ?>
							<?php } else if($rejection_st==1) { ?>
								<input class="btn btn-xs btn-danger" type="button" value="REJECTED"/>
							<?php } ?>
						</td>
						<?php }  else if ($org_Type_id==2){ ?>
							<td>
								<!--C&F-->
								<?php if($igm_type=="GM" and $bl_type=="HB") { ?>
									<?php if($ff_stat==0) { ?>
										<input class="btn btn-xs btn-danger" type="button" value="NOT FORWARDED"/>
									<?php } else if($ff_stat==1 and ($do_upload_st==1 or $rejection_st==1)) { ?>
										<?php if($do_upload_st==1) { ?>
											<?php if($rejection_st==0) { ?>
												<form action="<?php echo site_url('EDOController/eDOPDF')?>" target="_blank" method="POST">
													<input type="hidden" name="shedMloDo" id="shedMloDo" value="<?php echo $uploadId; ?>"/>
													<input type="hidden" name="rotNo" id="rotNo" value="<?php echo $imp_rot; ?>"/>
													<input type="hidden" name="blno" id="blno" value="<?php echo $bl; ?>"/>
													<input type="hidden" name="bl_type" id="bl_type" value="<?php echo $bl_type; ?>"/>
													<input type="hidden" name="sumitted_by" id="sumitted_by" value="<?php echo $sumitted_by; ?>"/>
													<input class="btn btn-xs btn-primary" type="submit" value="VIEW DO"/>
												</form>
											<?php } else { ?>
												<input type="button" style="padding:1px;" value="View Do" class="btn btn-xs btn-danger" 
													onclick="showRejected();"	/>
											<?php } ?>
										<?php } else if($rejection_st==1) { ?>
											<input class="btn btn-xs btn-danger" type="button" value="REJECTED"/>
										<?php } ?>
									<?php } else { ?>
										<?php if($cont_status=="FCL" or $cont_status=="FCL/PART" or $cont_status=="ETY") { ?>
											<input class="btn btn-xs btn-success" type="button" value="FORWARDED"/>
										<?php } else { ?>
											<input class="btn btn-xs btn-success" type="button" value="NOT UPLOADED"/>
									<?php } } } else if($igm_type=="GM" and $bl_type=="MB") { ?>
										<?php if($do_upload_st==1 or $rejection_st==1) { ?>
											<?php if($do_upload_st==1) { ?>
												<?php if($rejection_st==0) { ?>
													<form action="<?php echo site_url('EDOController/eDOPDF')?>" target="_blank" method="POST">
														<input type="hidden" name="shedMloDo" id="shedMloDo" value="<?php echo $uploadId; ?>"/>
														<input type="hidden" name="rotNo" id="rotNo" value="<?php echo $imp_rot; ?>"/>
														<input type="hidden" name="blno" id="blno" value="<?php echo $bl; ?>"/>
														<input type="hidden" name="bl_type" id="bl_type" value="<?php echo $bl_type; ?>"/>
														<input type="hidden" name="sumitted_by" id="sumitted_by" value="<?php echo $sumitted_by; ?>"/>
														<input class="btn btn-xs btn-primary" type="submit" value="VIEW DO"/>
													</form>
												<?php } else { ?>
													<input type="button" style="padding:1px;" value="View Do" class="btn btn-xs btn-danger" 
														onclick="showRejected();"	/>
												<?php } ?>
										<?php } else if($rejection_st==1) { ?>
											<input class="btn btn-xs btn-danger" type="button" value="REJECTED"/>
										<?php } ?>
										<?php } else { ?>
											<input class="btn btn-xs btn-success" type="button" value="NOT UPLOADED"/>
										<?php } ?>									
								<?php } else if($igm_type=="BB") { ?>
									<?php if($do_upload_st==1 or $rejection_st==1) { ?>
										<?php if($do_upload_st==1) { ?>
											<?php if($rejection_st==0) { ?>
											<form action="<?php echo site_url('EDOController/eDOPDF')?>" target="_blank" method="POST">
												<input type="hidden" name="shedMloDo" id="shedMloDo" value="<?php echo $uploadId; ?>"/>
												<input type="hidden" name="rotNo" id="rotNo" value="<?php echo $imp_rot; ?>"/>
												<input type="hidden" name="blno" id="blno" value="<?php echo $bl; ?>"/>
												<input type="hidden" name="bl_type" id="bl_type" value="<?php echo $bl_type; ?>"/>
												<input type="hidden" name="sumitted_by" id="sumitted_by" value="<?php echo $sumitted_by; ?>"/>
												<input class="btn btn-xs btn-primary" type="submit" value="VIEW DO"/>
											</form>
											<?php } else { ?>
												<input type="button" style="padding:1px;" value="View Do" class="btn btn-xs btn-danger" 
													onclick="showRejected();"	/>
											<?php } ?>
									<?php } else if($rejection_st==1) { ?>
										<input class="btn btn-xs btn-danger" type="button" value="REJECTED"/>
									<?php } ?>
									<?php } else { ?>
										<input class="btn btn-xs btn-success" type="button" value="NOT UPLOADED"/>
									<?php } ?>									
								<?php  } ?>
							</td>
							<td>
								<?php if(($igm_type=="GM") and ($bl_type=="HB")) { ?>
									<?php if($cont_status=="FCL" or $cont_status=="FCL/PART" or $cont_status=="ETY") { ?>
										<?php if($ff_stat==0) { ?>
											<form method="POST" action="<?php echo site_url("EDOController/deleteEDOApplication") ?>" 
												onsubmit="return confirmDlt();">
												<input type="hidden" name="edo_id" id="edo_id" value="<?php echo $edo_id; ?>"/>
												<input type="submit" style="padding:1px;" class="btn btn-xs btn-danger" value="DELETE DO"/>
											</form>
										<?php } else { ?>
										
											<!--input type="button" style="padding:1px;" 
												onclick="setValueOnValidityExtensionforHBLAndFCL(<?php echo $edo_id;?>);"
												value="EXTEND VALIDITY" class="btn btn-xs btn-success" data-toggle="modal" 
												data-target="#myModalforEdoForFCLAndHBL"/-->	
												
											<?php if($rejection_st==0) { ?>										
												<br>
												<form method="POST" action="<?php echo site_url("EDOController/validityExtensionApplicationFormForHBL") ?>" >
													<input type="hidden" name="edo_id" id="edo_id" value="<?php echo $edo_id; ?>"/>
													<input type="hidden" name="do_upload_st" id="do_upload_st" value="<?php echo $do_upload_st; ?>"/>
													<input type="hidden" name="bl_type" id="bl_type" value="<?php echo $bl_type; ?>"/>
													<input type="hidden" name="imp_rot" id="imp_rot" value="<?php echo $imp_rot; ?>"/>
													<input type="hidden" name="blNo" id="blNo" value="<?php echo $bl; ?>"/>
													<input type="hidden" name="uploadId" id="uploadId" value="<?php echo $uploadId; ?>"/>
													<input type="submit" style="padding:1px;" class="btn btn-xs btn-primary" value="EXTEND VALIDITY"/>
												</form>									   
											<?php } else { ?>
												<input type="button" style="padding:1px;" value="REJECTED" class="btn btn-xs btn-danger" disabled readonly />
											<?php } ?>
												
										<?php } ?>
									<?php } else { ?>
											
										<?php if($rejection_st==1) { ?>
											<input type="button" style="padding:1px;" value="REJECTED" class="btn btn-xs btn-danger" disabled readonly />
										<?php } else { ?>
											<?php if($ff_stat==0) { ?>
												<form method="POST" action="<?php echo site_url("EDOController/deleteEDOApplication") ?>" 
														onsubmit="return confirmDlt();">
													<input type="hidden" name="edo_id" id="edo_id" value="<?php echo $edo_id; ?>"/>
													<input type="submit" style="padding:1px;" class="btn btn-xs btn-danger" value="DELETE DO"/>
												</form>											
											<?php } else { ?>
												<!--input type="button" style="padding:1px;" onclick="setValueOnExpansionRequest(<?php echo $edo_id;?>);"
												value="EXTEND VALIDITY" class="btn btn-xs btn-success" data-toggle="modal" data-target="#myModalValidity"/-->
											<?php }  ?>										
										<?php } ?>
										
									<?php } ?>
									
								<?php } else { ?>
								
									<?php if($rejection_st==1) { ?>
										<input type="button" style="padding:1px;" value="REJECTED" class="btn btn-xs btn-danger" disabled readonly />
									<?php } else { ?>
										<?php if($do_upload_st==0) { ?>
											<form method="POST" action="<?php echo site_url("EDOController/deleteEDOApplication") ?>" 
													onsubmit="return confirmDlt();">
												<input type="hidden" name="edo_id" id="edo_id" value="<?php echo $edo_id; ?>"/>
												<input type="submit" style="padding:1px;" class="btn btn-xs btn-danger" value="DELETE DO"/>
											</form>
											<br/><br/>
										<?php } ?>
											<form method="POST" 
												action="<?php echo site_url("EDOController/validityExtensionApplicationFormForMBL") ?>" >
												<input type="hidden" name="edo_id" id="edo_id" value="<?php echo $edo_id; ?>"/>
												<input type="hidden" name="do_upload_st" id="do_upload_st" value="<?php echo $do_upload_st; ?>"/>
												<input type="hidden" name="bl_type" id="bl_type" value="<?php echo $bl_type; ?>"/>
												<input type="hidden" name="imp_rot" id="imp_rot" value="<?php echo $imp_rot; ?>"/>
												<input type="hidden" name="blNo" id="blNo" value="<?php echo $bl; ?>"/>
												<input type="hidden" name="uploadId" id="uploadId" value="<?php echo $uploadId; ?>"/>
												<input type="submit" style="padding:1px;" class="btn btn-xs btn-primary" value="EXTEND VALIDITY"/>
											</form>
									<?php } ?>
								<?php } ?>
							</td>
						<?php }  else if ($org_Type_id==4) { ?>
							<td align="center">
								<!--Freight Forwarder-->
							<?php if($igm_type=="GM" and $bl_type=="HB") { ?>
								<!--11 April-->
								<?php if($ff_stat==1) { ?>
									<?php if($checkSt==0) { ?>
										<?php if($rejection_st==0) { ?>
											<p>
												<input type="button" style="padding:2px;" onclick="setValueOnModal(<?php echo $edo_id;?>);"
												value="REJECT" class="btn btn-xs btn-danger" data-toggle="modal" data-target="#myModal"/>
											</p>
										<?php } else { ?>
											<?php if($rejected_by_org == $org_id) { ?>
												<p>
													<input type="button" style="padding:1px;" onclick="setValueOnWithdrawalModal(<?php echo $edo_id;?>);"
													value="WITHDRAW REJECTION" class="btn btn-xs btn-success" data-toggle="modal" data-target="#withdrawalModal"/>
												</p>
											<?php } else { ?>
												<p><input class="btn btn-xs btn-primary" type="button" value="REJECTED"/></p>
											<?php } ?>
										<?php } ?>
									<?php } ?>
								<?php } ?>
								<!--11 April-->
							
								<?php if($ff_stat==0){ ?>
											<input class="btn btn-xs btn-danger" type="button" value="NOT FORWARDED"/>
									<?php } else if($ff_stat==1) { ?>
											<?php if($do_upload_st==0 and $rejection_st==0) { ?>												
										<?php if($cont_status=="FCL" or $cont_status=="FCL/PART" or $cont_status=="ETY") { ?>
											<?php //if($today <= $valid_upto_dt_by_mlo) { ?>	
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
												<!--input type="button" style="padding:1px;" onclick="setValueOnModal(<?php echo $edo_id;?>);"
													value="Reject" class="btn btn-xs btn-danger" data-toggle="modal" data-target="#myModal"/-->
											<?php //} else { 
												//echo "<b>Validity date set by MLO (".$valid_upto_dt_by_mlo.") has expired.</b>";
											//} ?>
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
													<!--input type="button" style="padding:1px;" onclick="setValueOnModal(<?php echo $edo_id;?>);"
														value="Reject" class="btn btn-xs btn-danger" data-toggle="modal" data-target="#myModal"/-->
												
													
										<?php } ?>
									<?php	} else if($do_upload_st==1) { ?>
											<?php if($rejection_st==0) { ?>
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
											<?php } else { ?>
												<input type="button" style="padding:1px;" value="View Do" class="btn btn-xs btn-danger" 
													onclick="showRejected();"	/>
											<?php } ?>
											
											<?php if($cont_status=="FCL" or $cont_status=="FCL/PART" or $cont_status=="ETY") { ?>
												<?php if($vldty_appr_by_mlo_st == 1 ) { ?>
													<p>
														<form method="POST" action="<?php echo site_url("EDOController/shedDeOInfoData") ?>" >
															<input type="hidden" name="extendValidityFlag" value="extendValidityFlag"/>
															<input type="hidden" name="uploadId" value="<?php echo $uploadId; ?>"/>
															<input type="hidden" name="imp_rot" value="<?php echo $imp_rot; ?>"/>
															<input type="hidden" name="blNo" value="<?php echo $bl; ?>"/>
															<input type="hidden" name="edo_id" id="edo_id" value="<?php echo $edo_id; ?>"/>
															<input type="hidden" name="bl_type" id="bl_type" value="<?php echo $bl_type; ?>"/>
															<input type="hidden" name="igm_type" id="igm_type" value="<?php echo $igm_type; ?>"/>
															<input type="hidden" name="sumitted_by" id="sumitted_by" value="<?php echo $sumitted_by; ?>"/>
															<input type="submit" style="padding:1px;" class="btn btn-xs btn-success" value="EXTEND VALIDITY"/>
														</form>
													</p>
												<?php } else { ?>
													<?php //if($checkSt==0) { ?>
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
													<?php //} ?>
												<?php } ?>
											<?php } else { ?>
													<?php //if($checkSt==0) { ?>
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
													<?php //} ?>
												<?php } ?>
											
											
											<?php if($rejection_st==0) { ?>
												<?php if($checkSt==0) { ?>
													<p>
														<!--input type="button" style="padding:1px;" onclick="setValueOnModal(<?php echo $edo_id;?>);"
														value="Reject" class="btn btn-xs btn-danger" data-toggle="modal" data-target="#myModal"/-->
													</p>
												<?php } ?>
											<?php } ?>
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
											<?php if($rejection_st==0) { ?>											
												<?php if($cont_status=="LCL") { ?>
												<form method="POST" action="<?php echo site_url("EDOController/updateStatforEDO") ?>" 
													onclick="return cnfrmClearance()">
													<input type="hidden" name="clearanceEDOId" id="clearanceEDOId" value="<?php echo $edo_id; ?>"/>
													<input type="submit" style="padding:1px;" class="btn btn-xs btn-primary" 
														value="Clearance to FF"/>
												</form>
												<?php } else { ?>
												<input type="button" style="padding:1px;" onclick="setValueOnClearance(<?php echo $edo_id;?>);"
													value="Clearance to FF" class="btn btn-xs btn-success" data-toggle="modal" data-target="#myModalClearance"/>
												<?php } ?>
											<?php } ?>
											
										<?php } else if($ff_stat==1 and ($do_upload_st==1 or $rejection_st==1)) { ?>
											<?php if($do_upload_st==1) { ?>
												<?php if($rejection_st==0) { ?>
													<form action="<?php echo site_url('EDOController/eDOPDF')?>" target="_blank" method="POST">
														<input type="hidden" name="shedMloDo" id="shedMloDo" value="<?php echo $uploadId; ?>"/>
														<input type="hidden" name="rotNo" id="rotNo" value="<?php echo $imp_rot; ?>"/>
														<input type="hidden" name="blno" id="blno" value="<?php echo $bl; ?>"/>
														<input type="hidden" name="bl_type" id="bl_type" value="<?php echo $bl_type; ?>"/>
														<input type="hidden" name="sumitted_by" id="sumitted_by" value="<?php echo $sumitted_by; ?>"/>
														<input class="btn btn-xs btn-primary" type="submit" value="VIEW DO"/>
													</form>
												<?php } else { ?>
													<input type="button" style="padding:1px;" value="View Do" class="btn btn-xs btn-danger" 
														onclick="showRejected();"	/>
												<?php } ?>
												
											<?php } else if($rejection_st==1) { ?>
												<input class="btn btn-xs btn-danger" type="button" value="REJECTED"/>
											<?php } ?>
										<?php } else { ?>
											
											<p>
												<input type="button" style="padding:1px;" class="btn btn-xs btn-success" value="FORWARDED"/>
											</p>											
											<?php if($do_upload_st==0) {?>
												<?php if($cont_status=="FCL" or $cont_status=="FCL/PART" or $cont_status=="ETY") {?>
													<p>
														<input type="button" style="padding:1px;" onclick="setHBValidityExtendByMlo(<?php echo $edo_id;?>);"
														value="EDIT VALIDITY" class="btn btn-xs btn-primary" data-toggle="modal" data-target="#myModalHBValidityExtendByMlo"/>
												    </p>
												<?php } ?>
											   <?php } ?> 
											
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
													<input type="submit" style="padding:1px;" class="btn btn-xs btn-primary" value="UPLOAD DO"/>
												</form>
											</p>
											<p>
												<input type="button" style="padding:1px;" onclick="setValueOnModal(<?php echo $edo_id;?>);"
													value="Reject" class="btn btn-xs btn-danger" data-toggle="modal" data-target="#myModal"/>
											</p>
										<?php } else if($do_upload_st==1) { ?>
											<?php if($rejection_st==0) { ?>
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
											<?php } else { ?>
												<input type="button" style="padding:1px;" value="View Do" class="btn btn-xs btn-danger" 
													onclick="showRejected();"	/>
											<?php } ?>
											<!--$cnf_vldty_appr_st == 0 and-->
											<?php if($do_upload_st==1){ ?> 
												<?php if($checkSt==0 or $cnf_vldty_appr_st == 1) { ?>
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
												<?php } ?>	
												<?php if($rejection_st==0) { ?>
												<?php if($checkSt==0) { ?>
													<p>
														<input type="button" style="padding:1px;" onclick="setValueOnModal(<?php echo $edo_id;?>);"
														value="Reject" class="btn btn-xs btn-danger" data-toggle="modal" data-target="#myModal"/>
													</p>
												<?php } ?>
											<?php } ?>
											<?php } ?>
										<?php } else if($rejection_st==1) { ?>
											<input class="btn btn-xs btn-primary" type="button" value="REJECTED"/>
										<?php } ?>
							<?php } ?>
							</td>
							<td>
								<!--?php 
									echo $igm_type."<br/>";
									echo $bl_type."<br/>";
									echo $do_upload_st."<br/>";
									echo $cont_status."<br/>";
									echo $cnf_vldty_appr_st."<br/>";
								?-->
								<?php if($igm_type=="GM" and $bl_type=="HB") { ?>
									<!--10 April-->
									<?php if($checkSt==0) { ?>
										<?php if($rejection_st==0) { ?>
											<p>
												<input type="button" style="padding:2px;" onclick="setValueOnModal(<?php echo $edo_id;?>);"
												value="REJECT" class="btn btn-xs btn-danger" data-toggle="modal" data-target="#myModal"/>
											</p>
										<?php } else { ?>
											<?php if($rejected_by_org == $org_id) { ?>
												<p>
													<input type="button" style="padding:1px;" onclick="setValueOnWithdrawalModal(<?php echo $edo_id;?>);"
													value="WITHDRAW REJECTION" class="btn btn-xs btn-success" data-toggle="modal" data-target="#withdrawalModal"/>
												</p>
											<?php } else { ?>
												<p><input class="btn btn-xs btn-primary" type="button" value="REJECTED"/></p>
											<?php } ?>
										<?php } ?>
									<?php } ?>
									<!--10 April-->
									<?php if($cont_status=="FCL" or $cont_status=="FCL/PART" or $cont_status=="ETY") { ?>  
										<?php if($ff_stat==1) { ?>
									
											<?php if($cnf_vldty_appr_st == 1) { ?>
												<form method="POST" action="<?php echo site_url("EDOController/validityExtensionApplicationFormForFFApproveValidity") ?>" >
													<input type="hidden" name="edo_id" id="edo_id" value="<?php echo $edo_id; ?>"/>
													<input type="hidden" name="do_upload_st" id="do_upload_st" value="<?php echo $do_upload_st; ?>"/>
													<input type="hidden" name="bl_type" id="bl_type" value="<?php echo $bl_type; ?>"/>
													<input type="hidden" name="imp_rot" id="imp_rot" value="<?php echo $imp_rot; ?>"/>
													<input type="hidden" name="blNo" id="blNo" value="<?php echo $bl; ?>"/>
													<input type="hidden" name="cont_status" id="cont_status" value="<?php echo $cont_status; ?>"/>
													<input type="hidden" name="uploadId" id="uploadId" value="<?php echo $uploadId; ?>"/>
													<input type="submit" style="padding:1px;" class="btn btn-xs btn-primary" value="APPROVE VALIDITY"/>										
												</form>
											<?php } ?>
										
										<?php } ?>
									<?php } else { ?>
										
									<?php } ?>
								<?php } else { ?>
									
									<?php if(($cnf_vldty_appr_st == 1) and $do_upload_st==1) { ?>
										<form method="POST" action="<?php echo site_url("EDOController/shedDeOInfoData") ?>" >
										    <input type="hidden" name="extendValidityFlag" value="extendValidityFlag"/>
											<input type="hidden" name="uploadId" value="<?php echo $uploadId; ?>"/>
											<input type="hidden" name="imp_rot" value="<?php echo $imp_rot; ?>"/>
											<input type="hidden" name="blNo" value="<?php echo $bl; ?>"/>
											<input type="hidden" name="edo_id" id="edo_id" value="<?php echo $edo_id; ?>"/>
											<input type="hidden" name="bl_type" id="bl_type" value="<?php echo $bl_type; ?>"/>
											<input type="hidden" name="igm_type" id="igm_type" value="<?php echo $igm_type; ?>"/>
											<input type="hidden" name="sumitted_by" id="sumitted_by" value="<?php echo $sumitted_by; ?>"/>
									        <input type="submit" style="padding:1px;" class="btn btn-xs btn-success" value="EXTEND VALIDITY"/>
										</form>
									<?php } ?>
									
									<?php if($checkSt==0 and $rejection_st==1) { ?>
										<?php if($rejected_by_org == $org_id) { ?>
											<p>
												<input type="button" style="padding:1px;" onclick="setValueOnWithdrawalModal(<?php echo $edo_id;?>);"
												value="WITHDRAW REJECTION" class="btn btn-xs btn-success" data-toggle="modal" data-target="#withdrawalModal"/>
											</p>
										<?php } else { ?>
											<p><input class="btn btn-xs btn-primary" type="button" value="REJECTED"/></p>
										<?php } ?>
									<?php } ?>
									
								<?php } ?>
							</td>
							<?php } else if ($org_Type_id==5 or $org_Type_id==62 or $org_Type_id==80 or $org_Type_id==28 or $org_Type_id==84) { ?>
								<!--CPA-->
								<?php if($ff_assoc_st==1 and $do_upload_st==0) { ?>
								<td>		
									<input class="btn btn-xs btn-success" type="submit" value="NOT UPLOADED"/>																		
								</td>
								<?php } else { ?>
								<td>
									<?php if($rejection_st==0) { ?>
										<form action="<?php echo site_url('EDOController/eDOPDF')?>" target="_blank" method="POST">
											<input type="hidden" name="shedMloDo" id="shedMloDo" value="<?php echo $uploadId; ?>"/>
											<input type="hidden" name="rotNo" id="rotNo" value="<?php echo $imp_rot; ?>"/>
											<input type="hidden" name="blno" id="blno" value="<?php echo $bl; ?>"/>
											<input type="hidden" name="bl_type" id="bl_type" value="<?php echo $bl_type; ?>"/>
											<input type="hidden" name="sumitted_by" id="sumitted_by" value="<?php echo $sumitted_by; ?>"/>
											<input class="btn btn-xs btn-primary" type="submit" value="VIEW DO"/>
										</form>
									<?php } else { ?>
										<input type="button" style="padding:1px;" value="View Do" class="btn btn-xs btn-danger" onclick="showRejected();"	/>
									<?php } ?>
								</td>
								<?php } ?>
								<td>
									<p>
										<?php if($rejection_st=="0" ) { ?>
											<?php if($do_upload_st=="1" ) { ?>										
												<p id="<?php echo "btnUnchecked".$i?>" <?php if($checkSt==0) { ?> style="display:block;" <?php } else { ?> style="display:none;" <?php } ?>>
													<input type="hidden" name="uploadIdtoApprove" id="<?php echo "uploadIdtoApprove".$i?>" 
														value="<?php echo $uploadId; ?>"/>
													<input type="hidden" name="edoIdtoApprove" id="<?php echo "edoIdtoApprove".$i?>" value="<?php echo $edo_id; ?>"/>
													<input type="submit" style="padding:1px;" class="btn btn-xs btn-success" value="Approve" 
														onclick="return cnfrmCpaApproval(<?php echo $i;?>)"/>										
												</p>										
											<?php } ?>
										<?php } else { ?>
										<p class="text-center">
											<input class="btn btn-xs btn-primary" type="button" value="Approve" disabled/><br/>
											Status : Rejected
										<p>
										<?php } ?>
										
										
										<p id="<?php echo "btnChecked".$i?>" <?php if($checkSt==0) { ?> style="display:none;" <?php } else { ?> style="display:block;font-weight:bold;" <?php } ?>>
											<!--input type="button" style="padding:1px;" class="btn btn-xs btn-success" value="Checked"/-->
											Approved
										</p>
									</p>
								</td>
								<?php } ?>
								
								<td>
									<?php 
										echo "<nobr>"."Application- "."<b>".$entry_time."</b>"."</nobr>";
										echo "<br>";
										echo "<nobr>"."Forwarding- "."<b>".$ff_clearance_time."</b>"."</nobr>";
										echo "<br>";
										echo "<nobr>"."Issuing- "."<b>".$uploadedAt."</b>"."</nobr>";
										echo "<br>";
										echo "<nobr>"."CPA Approved at- "."<b>".$approve_at."</b>"."</nobr>";
										echo "<br>";
										if($cnf_vldty_appr_st=="1")
										{
											echo "<nobr>"."Applied Validity Extension at- "."<b>".$applied_validity_extension_at."</b>"."</nobr>";
											echo "<br>";
										}
										else
										{
											echo "<nobr>"."Applied Validity Extension at- "."</nobr>";
											echo "<br>";
										}
										echo "<nobr>"."Approved Validity Extension at- "."<b>".$approved_validity_extension_at."</b>"."</nobr>";
									?>
								</td>
								<td>
									<?php 
										if ($org_Type_id==73 or $org_Type_id==5 or $org_Type_id==62 or $org_Type_id==80 or $org_Type_id==28 or $org_Type_id==84) {
											//--Freight Forwarder Association/CPA/ONESTOP
											if($rejection_st==1) { 
												echo $rejection_remarks."<br>"."<b>Time-</b>".$rejection_time;
											}
										} else if($org_Type_id==57 or $org_Type_id==93 or $org_Type_id==10 or $org_Type_id==2 or $org_Type_id==4 or $org_Type_id==1) {
											//--Shipping Agent/C&F/MLO/FF
											if($rejection_st==1) { 
												echo $rejection_remarks."<br>"."<b>Time-</b>".$rejection_time;
											} else if($bl_type=="MB") {
												
												if($cnf_vldty_appr_st=='0' and $vldty_appr_by_mlo_st=='0') {
													echo "";
												} else if($cnf_vldty_appr_st=='1' and $vldty_appr_by_mlo_st=='0') {
													if(($validityDt != $applied_valid_dt) and ($applied_valid_dt != NULL) and ($applied_valid_dt != "0000-00-00") 
															and ($cnf_vldty_appr_st=='1')) { 
														echo "applied validity up to date-"."<b>".$applied_valid_dt."</b>"; 
													}
												} else if($cnf_vldty_appr_st=='0' and $vldty_appr_by_mlo_st=='1') {
													echo "Validity Extension Approved";
												} else if($cnf_vldty_appr_st=='1' and $vldty_appr_by_mlo_st=='1') {
													echo "applied validity up to date-"."<b>".$applied_valid_dt."</b>"; 
												}
												
											}
											
											else if(($validityDt != $applied_valid_dt) and ($applied_valid_dt != NULL) and ($applied_valid_dt != "0000-00-00") 
													and ($cnf_vldty_appr_st=='1')) { 
												echo "applied validity up to date-"."<b>".$applied_valid_dt."</b>"; 
											}
										}
									 ?>
									 <input type="button" style="padding:1px;" value="INFO" class="btn btn-xs btn-primary"
										onclick="setInfoOnModal('<?php echo $entry_time;?>','<?php echo $ff_clearance_time;?>','<?php echo $uploadedAt;?>');"
										  data-toggle="modal" data-target="#modalDtTime" />
								</td>
						</tr>

						<?php
								}
							}
						?>

				</table>				
			</div>
		</section>
	</div>
</div>
</section>
</div>
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
	
	function confirmRejectionWithdrawal(){
		if(confirm("Do you want to withdraw rejection ?"))
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
	
	function confirmApproval() {
		if(confirm("Do you want to Approve ?"))
		{
			return true;
		}
		else
		{
			return false;
		}
	}
	
	function cnfrmClearance() {
		if(confirm("Do you want to forward ?"))
		{
			return true;
		}
		else
		{
			return false;
		}
	}
	
	function cnfrmCpaApproval() {
		if(confirm("Do you want to Approve ?"))
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
	function setValueOnWithdrawalModal(edoid){
		document.getElementById("withdrawal_edo_id").value=edoid;
	}
	function setValueOnValidityExtensionforHBLAndFCL(edoid){
		document.getElementById("edoForFCLAndHBL").value=edoid;
	}
	function setValueOnExpansionRequest(edoid){
		document.getElementById("extEDOId").value=edoid;
	}
	function setHBValidityExtendByMlo(edoid){
		document.getElementById("validityEdoId").value=edoid;
	}
	function setValueOnClearance(edoid){
		document.getElementById("clearanceEDOId").value=edoid;
	}
	function setValueOnApproval(edoid,applied_valid_dt){
		document.getElementById("edoForApproveValidityExtension").value=edoid;
		document.getElementById("valid_upto_date_for_approval").value=applied_valid_dt;
	}
	function setInfoOnModal(et,ft,ut){
		document.getElementById("applicationTime").innerHTML=et;
		document.getElementById("forwardingTime").innerHTML=ft;
		document.getElementById("uploadingTime").innerHTML=ut;
	}
	
	function changeSearchOptions(searchBy){
		document.getElementById("searchInputVal").focus();
		if(searchBy=="be_no")
		{
			document.getElementById("searched_be_dt").disabled = false;
		}
		else
		{
			document.getElementById("searched_be_dt").disabled = true;
			
		}
	}
	
	function showRejected(){
		alert("This DO is rejected !");
	}
	
</script>
<?php mysqli_close($con_cchaportdb); ?>