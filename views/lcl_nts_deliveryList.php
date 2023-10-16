<section role="main" class="content-body">
	<header class="page-header">
		<h2><?php echo $title;?></h2>
	</header>
 <div class="content">
    <div class="content_resize">
      <div class="mainbar">
        <div class="article">
		
			<div class="row">
				<div class="col-lg-12">	

				<section class="panel">
						<div class="panel-body">
						<form class="form-horizontal form-bordered" method="POST" action="<?php echo site_url("Report/lcldeliverySearch"); ?>" id="myform" name="myform" onsubmit="return validate()">
							<div class="col-md-12 text-center">
								<div class="col-md-5">	
									<div class="input-group mb-md">
										<span class="input-group-addon span_width">Date <span class="required">*</span></span>
										<input type="date" name="date" id="date" class="form-control" value="<?php echo date("Y-m-d"); ?>">
									</div>
								</div>

								<div class="col-md-4">
									<div class="input-group mb-md">
										<span class="input-group-addon span_width">Yard <span class="required">*</span></span>
										<select name="yard" id="yard" class="form-control">
											<option value="">-- Select --</option>
											<?php
												for($i=0;$i<count($yardList);$i++){
											?>
												<option value="<?php echo $yardList[$i]['shed_yard']?>"><?php echo $yardList[$i]['shed_yard']?></option>
											<?php
												}
											?>
										</select>
									</div>
								</div>

								<div class="col-md-3 text-center">
									<button type="submit" name="submit_login" class="mb-xs mt-xs mr-xs btn btn-success">Search</button>
								</div>
							</div>
						</form>
						</div>
					</section>

					<section class="panel">
                        <?php
                            if($msg != ""){
                        ?>
                            <div class="panel-body">
                                <div class="col-md-12 text-center">		
                                    <?php echo $msg; ?>
                                </div>
                            </div>
                        <?php
                            }
                        ?>

						<div class="panel-body table-responsive">
							<table  class="table table-bordered table-responsive table-hover table-striped mb-none" id="datatable-default">
								<thead>
                                    <tr class="gridDark" align="center">
										<!--td><b>View Appraisement</b></td-->
										<!-- <td ><b>Tally Sheet No</b></td> -->
										<td ><b>Container No</b></td>	
										<td ><b>Bl No</b></td>						
										<td ><b>Rotation</b></td>
										<td ><b>Verify No</b></td>
										<td ><b>Rcv Pkg</b></td>
                                        <td ><b>Unit</b></td>
                                        <td ><b>CP No</b></td>
                                        <td ><b>CP Date</b></td>
                                        <td ><b>B/E No</b></td>
                                        <td ><b>B/E Date</b></td>
										<td ><b>Location</b></td>
                                        <td ><b>Report</b></td>
                                        <td ><b>Tally Stat</b></td>
										<td align="center"><b>Status</b></td>
										<!--td ><b>Fault Pack</b></td>
										<td ><b>Loc Fast</b></td-->
										<!--td ><b>Position</b></td-->
										<!--td ><b>Unstuffing Date</b></td-->
										<!-- <td ><b>CP No</b></td> -->
										<!-- <td ><b>Tally Confirm</b></td> -->
									</tr>
                                </thead>
									<?php 
										include("mydbPConnection.php");
										$rot = "";
										$cont = "";
										$wr_date = "";
										$shed_yard = "";
										$shed_loc = "";
										$loc_first = "";
										$flt_pack = "";
										// $cp_no = "";
										$rcv_pack = "";
										$weight_unit = "";
										$import_rotation = "";
										$BL_No = "";
										$be_no = "";
										$be_dt = "";
										$igm_id = "";
										$verify_no = "";
										$cp_no = "";
										$cp_date = "";
										$cnf_lic_no = "";
										$assignmentDate = "";
										$igm_type = "";
										
										for($i=0;$i<count($rtnContainerList);$i++)
										{
											$rot = $rtnContainerList[$i]['imp_rot_no'];
											$cont = $rtnContainerList[$i]['cont_number'];
											//$wr_date = $rtnContainerList[$i]['imp_rot_no'];
										//	$shed_yard = $rtnContainerList[$i]['shed_yard'];
										//	$shed_loc = $rtnContainerList[$i]['shed_loc'];
										//	$loc_first = $rtnContainerList[$i]['loc_first'];
										//	$flt_pack = $rtnContainerList[$i]['flt_pack'];
											// $cp_no = $rtnContainerList[$i]['cp_no'];
											$rcv_pack = $rtnContainerList[$i]['Pack_Description'];
										//	$weight_unit = $rtnContainerList[$i]['weight_unit'];
										//	$import_rotation = $rtnContainerList[$i]['import_rotation'];
											$BL_No = $rtnContainerList[$i]['bl_no'];
											$be_no = $rtnContainerList[$i]['be_no'];
											$be_dt = $rtnContainerList[$i]['be_dt'];
											$Pack_Number = $rtnContainerList[$i]['Pack_Number'];
											$Pack_unit = $rtnContainerList[$i]['Pack_Description'];

											$verify_no = $rtnContainerList[$i]['verify_no'];
											$cp_no = $rtnContainerList[$i]['cp_no'];
											$cp_date = $rtnContainerList[$i]['cp_date'];
											$igm_type = $rtnContainerList[$i]['igm_type'];
											$igm_id = $rtnContainerList[$i]['igm_id'];
											/* $cnf_lic_no = $rtnContainerList[$i]['cnf_lic_no'];
											$assignmentDate = $rtnContainerList[$i]['assignmentDate']; */
											
											$check_tally_sql = "";
											$check_tally_loc = "";

											if($igm_type=='sup_dtl')
											{
												$check_tally_sql="SELECT * FROM shed_tally_info WHERE cont_number='$cont' AND import_rotation='$rot' AND igm_sup_detail_id='$igm_id'";
												$check_tally_loc="SELECT shed_loc FROM shed_tally_info WHERE cont_number='$cont' AND import_rotation='$rot' AND igm_sup_detail_id='$igm_id'";

											}
											else
											{
												$check_tally_sql="SELECT * FROM shed_tally_info WHERE cont_number='$cont' AND import_rotation='$rot' AND igm_detail_id='$igm_id'";	
												$check_tally_loc="SELECT shed_loc FROM shed_tally_info WHERE cont_number='$cont' AND import_rotation='$rot' AND igm_sup_detail_id='$igm_id'";											
											}

											$check_tally_loc=mysqli_query($con_cchaportdb,$check_tally_loc);
											$loc = "";
											while($locRow=mysqli_fetch_object($check_tally_loc)){
												$loc = $locRow->shed_loc;
											}

											$check_tally=mysqli_query($con_cchaportdb,$check_tally_sql);
											$rowcount=mysqli_num_rows($check_tally);
											if($rowcount>0)
											{
												$st="<font color=green size=1>Tally entry completed.</font>";
											}
											else
											{
												$st="<font color=red size=1>Tally yet not completed.</font>";
											}

											$result = $this->bm->chkBlockedContainerforTruckEntry($cont,$rot,$BL_No);

											$custom_block_status = "";
											for($ij = 0; $ij<count($result);$ij++){
												$custom_block_status = $result[$ij]['custom_block_st'];
											}
											// $custom_block_status = "DO_NOT_RELEASE";
									?>
									<tr class="gridLight" align="center">
									
										<!--td align="center"> 
											<form action="<?php echo site_url('Report/appraisementCertifyList/'.str_replace("/","_",$rtnContainerList[$i]['BL_NO']).'/'.str_replace("/","_",$rtnContainerList[$i]['rotation']))?>" target="_blank" method="POST">						
												<input type="submit" value="View"  class="login_button" style="width:100%;">							
											</form> 
										</td--> 
										<!-- <td style="color:red"><?php echo $rtnContainerList[$i]['tally_sheet_number'];?></td> -->
										<td><?php echo $cont;?></td>
										<td><?php echo $BL_No;?></td>
										<td><?php echo $rot;?></td>
										<td><?php echo $verify_no;?></td>
										<td><?php echo $Pack_Number;?></td>
										<td><?php echo $Pack_unit;?></td>
										<td><?php echo $cp_no;?></td>
										<td><?php echo $cp_date;?></td>
										<td><?php echo $be_no;?></td>
										<td><?php echo $be_dt;?></td>
										<td><?php echo $loc;?></td>
										<td>
											<form name="tallyreport" id="tallyreport" action="<?php echo site_url("LCL/cnfTruckEntryLCL");?>" method="post">
                                                <input type="hidden" name="cont_status" id="cont_status" value="LCL" />
									            <input type="hidden" name="search" id="search" value="search" />
												<input type="hidden" name="igmType" id="igmType" value="<?php echo $igm_type; ?>" />
												<input type="hidden" name="igm_id" id="igm_id" value="<?php echo $igm_id; ?>" />
												<input type="hidden" name="rotNo" id="rotNo" value="<?php echo $rot;?>">
												<input type="hidden" name="blNo" id="blNo" value="<?php echo $BL_No;?>">
												<input type="hidden" name="cp_no" id="cp_no" value="<?php echo $cp_no;?>">
												<input type="hidden" name="verify_no" id="verify_no" value="<?php echo $verify_no;?>">
												<input type="hidden" name="cnf_lic_no" id="cnf_lic_no" value="<?php echo $cnf_lic_no;?>">
												<input type="hidden" name="assignmentDate" id="assignmentDate" value="<?php echo date('Y-m-d'); ?>">
												<button type="submit" name="report" value="Search" class="login_button btn btn-primary btn-xs" <?php if($rowcount==0){ echo "disabled"; } ?> <?php if($custom_block_status=="DO_NOT_RELEASE"){echo "disabled";}?>><nobr>Truck Entry</nobr></button>
											</form>
										</td>
										<td><?php echo $st; ?></td>
										<td>
											<?php 	
												//$custom_remarks=$rslt_assignmentList[$i]['custom_remarks'];
												if($custom_block_status=="DO_NOT_RELEASE")
												{ 
											?> 
													<input style="width:70px" type="submit" class="btn btn-xs btn-danger" value="Blocked"  /> 
											<?php 
												} 
												else 
												{
												?> 
													<input style="width:70px" type="submit" class="btn btn-xs btn-success" value="Open"  /> 
												<?php 
												} 
												?>
										</td>
									</tr>
									<?php } ?>
								</table>
						</div>
					</section>
				</div>
			</div>

		 <!--</div>-->
		 </div>
		 

        </div>
       
       <!-- <p class="pages"><small>Page 1 of 2</small> <span>1</span> <a href="#">2</a> <a href="#">&raquo;</a></p>-->
      </div>
    </div>
	
  </div>
</section>