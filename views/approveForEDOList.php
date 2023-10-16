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
									<form class="form-horizontal form-bordered" method="POST" action="<?php echo site_url('EDOController/updateEDORejectStatus')?>">
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
				<div class="panel-body">
					<div align="center"><b><?php echo $msg;?></b></div>
				</div>
								<!---search form for CPA role -->
		<?php if($org_Type_id==5 or $org_Type_id==62 or $org_Type_id==80 or $org_Type_id==28 or $org_Type_id==84) { ?>
		
		 <form action="<?php echo site_url('EDOController/approveEDOapplication');?>" method="POST" >
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
						<input type="text" class="form-control" id="searchInputValue" name="searchInput" required  autofocus
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
							<th>#Sl</th>
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
							<?php if($org_Type_id==10 or $org_Type_id==57 or $org_Type_id==2 or $org_Type_id==5 or $org_Type_id==62 or $org_Type_id==80 or  $org_Type_id==28 or $org_Type_id==84) { ?>
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
							<?php if($org_Type_id==5 or $org_Type_id==62 or $org_Type_id==80 or $org_Type_id==28 or $org_Type_id==84){ ?>
							<th>Action</th>
							<?php } ?>
						</tr>
					</thead>
					<?php
						$Submitee_Org_Id = "";
						$query = "SELECT users.org_id FROM users WHERE users.login_id='$login_id'";
						$resultQuery = mysqli_query($con_cchaportdb,$query);
						while($res = mysqli_fetch_object($resultQuery)){
							$Submitee_Org_Id = $res->org_id;
							
						}
						
						if($org_Type_id==73) //freight forwarder association
						{
							$edoQuery = "SELECT * FROM edo_application_by_cf 
										where bl_type='HB' AND igm_type='GM' AND ff_stat='1' ORDER BY id DESC";
						}						
						else if($org_Type_id==57 or $org_Type_id==10) //shipping_agent
						{
							$edoQuery = "SELECT * FROM edo_application_by_cf 
										where igm_type='BB' and sh_agent_org_id='$Submitee_Org_Id' ORDER BY id DESC";
						}
						else if($org_Type_id==4) //freight forwarder
						{
							if($flag=="pending")
							{
								$edoQuery = "SELECT * FROM edo_application_by_cf 
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
										AND (cont_status = 'FCL' OR cont_status = 'FCL/PART' OR bl_type = 'MB')
										ORDER BY id DESC";
						}
						else if($org_Type_id==5 or $org_Type_id==62 or $org_Type_id==80 or $org_Type_id==28 or $org_Type_id==84) 
						{
							//CPA or ONESTOP or Admin or Monitor
						/* 	$edoQuery = "SELECT * FROM edo_application_by_cf                
										WHERE IF(bl_type='HB' AND igm_type='GM', ff_assoc_st, do_upload_st) ='1'
										ORDER BY id DESC LIMIT 500"; */
										
										
							if($cpa_search==0)
							{
							$edoQuery = "SELECT * FROM edo_application_by_cf 
										WHERE IF(bl_type='HB' AND igm_type='GM', ff_assoc_st, do_upload_st) ='1'
										ORDER BY id DESC LIMIT 1000";
							}
							else
							{
								if($searchBy=='bl')
								{
									$edoQuery = "SELECT * FROM edo_application_by_cf 
									WHERE IF(bl_type='HB' AND igm_type='GM', ff_assoc_st, do_upload_st) ='1'
									AND bl='$searchInput'";
								}
								else if($searchBy=='rotation')
								{
									$edoQuery = "SELECT * FROM edo_application_by_cf 
									WHERE IF(bl_type='HB' AND igm_type='GM', ff_assoc_st, do_upload_st) ='1'
									AND rotation='$searchInput'";
								}
								else if($searchBy=='be_no')
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
									WHERE IF(bl_type='HB' AND igm_type='GM', ff_assoc_st, do_upload_st) ='1' AND  rotation='$imp_rot' AND bl='$bl_no'";
								}
							}			
						}
						else  //cnf=2
						{
							$edoQuery = "SELECT * FROM edo_application_by_cf WHERE sumitted_by='$login_id' ORDER BY id DESC";
						}
							// echo $edoQuery;return;
						$edoResult = mysqli_query($con_cchaportdb,$edoQuery);
						
						
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
						$applied_valid_dt = "";
						$cont_status = "";
						$mbl_of_hbl = "";
						$approve_at="";
						$cnf_vldty_appr_st="";
						$i=0;
						while($row = mysqli_fetch_object($edoResult)){
							$uploadId = "";
							
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
							$applied_valid_dt = $row->applied_valid_dt;
							$cont_status = $row->cont_status;
							$mbl_of_hbl = $row->mbl_of_hbl;
							$cnf_vldty_appr_st = $row->cnf_vldty_appr_st;
							$i++;
							
							$sql_exit_no = "SELECT manif_num,sad_item.sum_declare,sad_info.place_dec
										FROM sad_info
										INNER JOIN sad_item ON sad_info.id=sad_item.sad_id
										WHERE sad_info.manif_num LIKE CONCAT('%',REPLACE('$imp_rot','/',' '),'%') AND sad_item.sum_declare='$bl'";
							$rslt_exit_no = mysqli_query($con_cchaportdb,$sql_exit_no);
							while($row_exit_no = mysqli_fetch_object($rslt_exit_no))
							{
								$exit_no=$row_exit_no->place_dec;
							}
							
							// --- 2022-02-17
							$arr = explode("/",$imp_rot);
							
							$manif_num_2 = "";
							
							if(strlen($arr[1])==1)
								$manif_num_2 = $arr[0]." 000".$arr[1];
							else if(strlen($arr[1])==2)
								$manif_num_2 = $arr[0]." 00".$arr[1];
							else if(strlen($arr[1])==3)
								$manif_num_2 = $arr[0]." 0".$arr[1];
							else if(strlen($arr[1])==4)
								$manif_num_2 = $arr[0]." ".$arr[1];							

							$manif_num=str_replace("/"," ",$imp_rot);
							
							if($exit_no=="")
							{
								$sql_exit_no = "SELECT manif_num,sad_item.sum_declare,sad_info.place_dec
										FROM sad_info
										INNER JOIN sad_item ON sad_info.id=sad_item.sad_id
										WHERE (manif_num LIKE '%$manif_num%' OR manif_num LIKE '%$manif_num_2%') AND sad_item.sum_declare='$bl'";
										
								$rslt_exit_no = mysqli_query($con_cchaportdb,$sql_exit_no);
								while($row_exit_no = mysqli_fetch_object($rslt_exit_no))
								{
									$exit_no=$row_exit_no->place_dec;
								}
							}
							// --- 2022-02-17
							
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
					<?php  if($checkSt==1) { ?>
					<tr>
						<td><?php echo $i; ?></td>
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
									echo "<b>Date : </b>".$be_date;
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
						<?php if($org_Type_id==10 or $org_Type_id==57 or $org_Type_id==2 or $org_Type_id==5 or $org_Type_id==62 or $org_Type_id==80 			or $org_Type_id==28 or $org_Type_id==84) { ?>
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
								} else if($org_Type_id==57 or $org_Type_id==10 or $org_Type_id==2 or $org_Type_id==4 or $org_Type_id==1) {
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
									
									else if(($validityDt != $applied_valid_dt) and ($applied_valid_dt != NULL)) { 
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
							<td>
								<!--Freight Forwarder-->
							<?php if($igm_type=="GM" and $bl_type=="HB") { ?>
							
								<?php if($ff_stat==0){ ?>
											<input class="btn btn-xs btn-danger" type="button" value="NOT FORWARDED"/>
									<?php	} else if($ff_stat==1) { ?>
												<?php if($do_upload_st==0 and $rejection_st==0) {?>
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
								<input type="button" style="padding:1px;" value="EXTEND VALIDITY" class="btn btn-xs btn-success" disabled readonly />
								<?php } ?>
								<?php } else { ?>
								<input type="button" style="padding:1px;" value="EXTEND VALIDITY" class="btn btn-xs btn-success" disabled readonly />
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
					<?php } ?>

				</table>				
			</div>
		</section>
	</div>
</div>
</section>
</div>
<script>
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
	
	function changeSearchOptions(searchBy){
		document.getElementById("searchInputValue").focus();
		if(searchBy=="be_no")
		{
			document.getElementById("searched_be_dt").disabled = false;
		}
		else
		{
			document.getElementById("searched_be_dt").disabled = true;
		}
	}
</script>
<?php mysqli_close($con_cchaportdb); ?>