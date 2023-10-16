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
                                        <td ><b>Unit</b></td>
										<td ><b>Rcv Pkg</b></td>
										<td ><b>Fault Pack</b></td>
										<td ><b>Loc Fast</b></td>
										<td ><b>Position</b></td>
										<td ><b>Yard/Shed</b></td>
										<td ><b>Unstuffing Date</b></td>
										<!-- <td ><b>CP No</b></td> -->
										<td ><b>Report</b></td>
										<td ><b>Status</b></td>
										<!-- <td ><b>Tally Confirm</b></td> -->
									</tr>
                                </thead>
									<?php 
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
										$cont_number = "";
										$igm_sup_detail_id = "";
										$verify_no = "";
										$cp_no = "";
										$cnf_lic_no = "";
										$assignmentDate = "";
										
										for($i=0;$i<count($rtnContainerList);$i++)
										{
											$rot = $rtnContainerList[$i]['import_rotation'];
											$cont = $rtnContainerList[$i]['cont_number'];
											$wr_date = $rtnContainerList[$i]['wr_date'];
											$shed_yard = $rtnContainerList[$i]['shed_yard'];
											$shed_loc = $rtnContainerList[$i]['shed_loc'];
											$loc_first = $rtnContainerList[$i]['loc_first'];
											$flt_pack = $rtnContainerList[$i]['flt_pack'];
											// $cp_no = $rtnContainerList[$i]['cp_no'];
											$rcv_pack = $rtnContainerList[$i]['rcv_pack'];
											$weight_unit = $rtnContainerList[$i]['weight_unit'];
											$import_rotation = $rtnContainerList[$i]['import_rotation'];
											$BL_No = $rtnContainerList[$i]['BL_No'];
											$cont_number = $rtnContainerList[$i]['cont_number'];
											$igm_sup_detail_id = $rtnContainerList[$i]['igm_sup_detail_id'];
											$verify_no = $rtnContainerList[$i]['verify_number'];
											$cp_no = $rtnContainerList[$i]['cp_no'];
											$cnf_lic_no = $rtnContainerList[$i]['cnf_lic_no'];
											$assignmentDate = $rtnContainerList[$i]['assignmentDate'];
									?>
									<tr class="gridLight" align="center">
									
										<!--td align="center"> 
											<form action="<?php echo site_url('Report/appraisementCertifyList/'.str_replace("/","_",$rtnContainerList[$i]['BL_NO']).'/'.str_replace("/","_",$rtnContainerList[$i]['rotation']))?>" target="_blank" method="POST">						
												<input type="submit" value="View"  class="login_button" style="width:100%;">							
											</form> 
										</td--> 
										<!-- <td style="color:red"><?php echo $rtnContainerList[$i]['tally_sheet_number'];?></td> -->
										<td><?php echo $cont_number;?></td>
										<td><?php echo $BL_No;?></td>
										<td><?php echo $import_rotation;?></td>
                                        <td><?php echo $weight_unit;?></td>
										<td><?php echo $rcv_pack;?></td>
										<td><?php echo $flt_pack;?></td>
										<td><?php echo $loc_first;?></td>
										<td><?php echo $shed_loc;?></td>
										<td><?php echo $shed_yard;?></td>
										<td><?php echo $wr_date;?></td>
										<!-- <td><?php echo $cp_no;?></td> -->
                                        <?php
											$result = $this->bm->chkBlockedContainerforTruckEntry($cont_number,$import_rotation,$BL_No);

											$custom_block_status = "";
											for($ij = 0; $ij<count($result);$ij++){
												$custom_block_status = $result[$ij]['custom_block_st'];
											}

                                            if($org_Type_id == 59 || $org_Type_id == 62){
												include("mydbPConnection.php");
												$bill_no = "";
												$cp_no = "";
												$sqlBillNo="SELECT shed_bill_master.bill_no
														FROM shed_bill_master 
														INNER JOIN bank_bill_recv ON shed_bill_master.bill_no=bank_bill_recv.bill_no 
														WHERE shed_bill_master.import_rotation='$import_rotation' AND shed_bill_master.bl_no='$BL_No'
														GROUP BY shed_bill_master.bill_no";
												$strBillNo=mysqli_query($con_cchaportdb,$sqlBillNo);
												while($rowBillNo = mysqli_fetch_object($strBillNo)){
													$bill_no = $rowBillNo->bill_no;

												}
												
												$sqlcpno="SELECT gkey,bill_no,cp_no,RIGHT(cp_year,2) AS cp_year,cp_bank_code,cp_unit 
															FROM bank_bill_recv WHERE bill_no='$bill_no'";
												$rek = mysqli_query($con_cchaportdb,$sqlcpno);
												if($rek->num_rows > 0)
												{
													$rtncpno = mysqli_fetch_object($rek);

													$cpbankcode=$rtncpno->cp_bank_code;
													$cpno=$rtncpno->cp_no;
													$cpyear=$rtncpno->cp_year;
													$cpunit=$rtncpno->cp_unit;
													$num_length = strlen($cpno);
													$num_length = strlen($cpno);
													
													if($num_length == 4)
													{
														$newcpno=$cpno;
													}
													else if($num_length == 3)
													{
														$newcpno="0"."$cpno";
													}
													else if($num_length == 2)
													{
														$newcpno="00"."$cpno";
													}
													else if($num_length == 1)
													{
														$newcpno="000"."$cpno";
													}
													if($cpbankcode!=""&&$cpno!="")
													{
														$cp_no=$cpbankcode.$cpunit."/".$cpyear."-"."$newcpno";
														$cp=$cp_no;
													}
												}
												else
												{
													$cp_no = ""; 
												}
												mysqli_close($con_cchaportdb);
                                        ?>
										<td>
											<form name="tallyreport" id="tallyreport" action="<?php echo site_url("Report/viewDeliveryInfo");?>" method="post">
												<input type="hidden" name="rotation" id="rotation" value="<?php echo $import_rotation;?>">
												<input type="hidden" name="blNo" id="blNo" value="<?php echo $BL_No;?>">
												<input type="hidden" name="cp_no" id="cp_no" value="<?php echo $cp_no;?>">
												<input type="hidden" name="verify_no" id="verify_no" value="<?php echo $verify_no;?>">
												<input type="hidden" name="cnf_lic_no" id="cnf_lic_no" value="<?php echo $cnf_lic_no;?>">
												<input type="hidden" name="assignmentDate" id="assignmentDate" value="<?php echo $assignmentDate;?>">
                                                <input type="hidden" name="igm_sup_dtl_id" id="igm_sup_dtl_id" value="<?php echo $igm_sup_detail_id;?>">
												<button type="submit" name="report" class="login_button btn btn-primary" ><nobr>Delivery Assignment Entry</nobr></button>
											</form>
										</td>
                                        <?php
                                            }else if($org_Type_id == 2){
                                        ?>
                                        <td>
                                            <form name="tallyreport" id="tallyreport" action="<?php echo site_url("ShedBillController/cnfTruckEntryLCL");?>" method="post">
                                                <input type="hidden" name="cont_status" id="cont_status" value="LCL" />
									            <input type="hidden" name="search" id="search" value="search" />
												<input type="hidden" name="rotNo" id="rotNo" value="<?php echo $import_rotation;?>">
												<input type="hidden" name="blNo" id="blNo" value="<?php echo $BL_No;?>">
												<input type="hidden" name="cp_no" id="cp_no" value="<?php echo $cp_no;?>">
												<input type="hidden" name="verify_no" id="verify_no" value="<?php echo $verify_no;?>">
												<input type="hidden" name="cnf_lic_no" id="cnf_lic_no" value="<?php echo $cnf_lic_no;?>">
												<input type="hidden" name="assignmentDate" id="assignmentDate" value="<?php echo $assignmentDate;?>">
												<button type="submit" name="report" value="Search" class="login_button btn btn-primary" <?php if($custom_block_status == "DO_NOT_RELEASE"){echo "disabled";}?>><nobr>Truck Entry</nobr></button>
											</form>
                                        </td>
                                        <?php
                                            }
                                        ?>

										<td>
										<?php
											if($custom_block_status=="DO_NOT_RELEASE"){ 
										?> 
											<input style="width:70px" type="submit" class="btn btn-xs btn-danger" value="Blocked"  /> 
										<?php 
											} else {
										?> 
											<input style="width:70px" type="submit" class="btn btn-xs btn-success" value="Open"  /> 
										<?php 
											} 
										?>
										</td>
										
									</tr>
									<?php }?>
								</table>
						</div>
					</section>
				</div>
			</div>

		 </div>
		 

        </div>
       
      </div>
    </div>
	
  </div>
</section>