<script>
    function validate()
    {
        if (confirm("Are you sure!! Delete this record?") == true) {
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
					
			
					</header>

			
							<div class="panel-body">
							
								<table class="table table-bordered table-hover table-striped mb-none" id="datatable-default">
									<thead>
										<tr>
											<th class="text-center">Sl</th>
											<th class="text-center">Reg No</th>
											<th class="text-center">BL No</th>
											<th class="text-center">Assigned Unit</th>
											<th class="text-center">Container Status</th>
											<th class="text-center">Agent Name</th>
											<th class="text-center">PRO State</th>
											<th class="text-center">State Desc.</th>
											<?php
												if($org_Type_id == 62){
											?>
											
											<th class="text-center">Action</th>
											<?php
												}
											?>

											<th class="text-center">R/O</th>
											
											<?php
												if($org_Type_id == 2){
											?>
											<th class="text-center">Draft Bill</th>	
											<th class="text-center">Delete</th>	
											<?php
												}
											?>
										</tr>
									</thead>
									<tbody>
										<?php for($i=0;$i<count($rslt_sqlRelease);$i++)
										{
											$id = $rslt_sqlRelease[$i]['id'];
											$rotation = $rslt_sqlRelease[$i]['imp_rot'];
											$bl = $rslt_sqlRelease[$i]['bl_no'];
											$unit = $rslt_sqlRelease[$i]['unit_no'];
											$unit_assign_at = $rslt_sqlRelease[$i]['unit_assign_at'];
											$ro_type = $rslt_sqlRelease[$i]['ro_type'];
											$appraise_st = $rslt_sqlRelease[$i]['appraise_st'];
											$appraise_at = $rslt_sqlRelease[$i]['appraise_at'];

											// Certify check
											$chkcertified = 0;
											$certify_at = null;

											$chkcertify = "SELECT COUNT(certify_info_fcl.id) AS rtnValue,certify_info_fcl.last_update
											FROM certify_info_fcl 
											INNER JOIN igm_details ON certify_info_fcl.igm_detail_id=igm_details.id
											WHERE igm_details.Import_Rotation_No='$rotation' AND igm_details.BL_No='$bl'";
											$chkcertifiedRslt = $this->bm->dataSelectDb1($chkcertify);
											if(count($chkcertifiedRslt)>0)
											{
												$chkcertified = $chkcertifiedRslt[0]['rtnValue'];
												$certify_at = $chkcertifiedRslt[0]['last_update'];
											}

											if($chkcertified == 0){
												$chkcertify = "SELECT COUNT(certify_info_fcl.id) AS rtnValue,certify_info_fcl.last_update
												FROM certify_info_fcl 
												INNER JOIN igm_supplimentary_detail ON certify_info_fcl.igm_sup_detail_id=igm_supplimentary_detail.id
												WHERE igm_supplimentary_detail.Import_Rotation_No='$rotation' AND igm_supplimentary_detail.BL_No='$bl'";
												$chkcertifiedRslt = $this->bm->dataSelectDb1($chkcertify);
												if(count($chkcertifiedRslt)>0)
												{
													$chkcertified = $chkcertifiedRslt[0]['rtnValue'];
													$certify_at = $chkcertifiedRslt[0]['last_update'];
												}
											}

											if($chkcertified == 0){
												$chkcertify = "SELECT COUNT(verify_other_data.id) AS rtnValue,verify_other_data.last_update
												FROM verify_other_data 
												INNER JOIN shed_tally_info ON verify_other_data.shed_tally_id=shed_tally_info.id
												INNER JOIN igm_supplimentary_detail ON shed_tally_info.igm_sup_detail_id=igm_supplimentary_detail.id
												WHERE igm_supplimentary_detail.Import_Rotation_No='$rotation' AND igm_supplimentary_detail.BL_No='$bl'";
												$chkcertifiedRslt = $this->bm->dataSelectDb1($chkcertify);
												if(count($chkcertifiedRslt)>0)
												{
													$chkcertified = $chkcertifiedRslt[0]['rtnValue'];
													$certify_at = $chkcertifiedRslt[0]['last_update'];
												}
											}

											// Verify check
											$chkverified = 0;
											$verify_at = null;

											$chkverify = "SELECT COUNT(verify_number) AS rtnValue,verify_time
											FROM igm_details 
											LEFT JOIN verify_info_fcl ON verify_info_fcl.igm_detail_id=igm_details.id
											WHERE igm_details.Import_Rotation_No = '$rotation' AND igm_details.BL_No = '$bl'";
											$chkverifiedRslt = $this->bm->dataSelectDb1($chkverify);
											if(count($chkverifiedRslt)>0)
											{
												$chkverified = $chkverifiedRslt[0]['rtnValue'];
												$verify_at = $chkverifiedRslt[0]['verify_time'];
											}

											if($chkverified == 0){
												$chkverify = "SELECT COUNT(verify_number) AS rtnValue,verify_time
												FROM igm_supplimentary_detail
												INNER JOIN igm_details ON igm_details.id=igm_supplimentary_detail.igm_detail_id
												LEFT JOIN shed_tally_info ON shed_tally_info.igm_detail_id = igm_details.id
												WHERE igm_supplimentary_detail.Import_Rotation_No='$rotation' AND igm_supplimentary_detail.BL_No='$bl'";
												$chkverifiedRslt = $this->bm->dataSelectDb1($chkverify);
												if(count($chkverifiedRslt)>0)
												{
													$chkverified = $chkverifiedRslt[0]['rtnValue'];
													$verify_at = $chkverifiedRslt[0]['verify_time'];
												}
											}

											$verifyQuery = "SELECT verify_number FROM verify_info_fcl WHERE verify_info_fcl.rotation = '$rotation' AND verify_info_fcl.bl_no = '$bl'";
											$verifyRslt = $this->bm->dataSelectDb1($verifyQuery);
											$verify_no = "";
											if(count($verifyRslt)>0)
											{
												$verify_no = $verifyRslt[0]['verify_number'];
											}

											$billGenerated = 0;
											$generated_at = null;

											if(strlen($verify_no)>0)
											{
												$billChkQry="SELECT Count(verify_no) as rtnValue,entry_dt from shed_bill_master where verify_no='$verify_no'";
												$billChkRslt = $this->bm->dataSelectDb1($billChkQry);;
												
												if(count($billChkRslt)>0)
												{
													$billGenerated = $billChkRslt[0]['rtnValue'];
													$generated_at = $billChkRslt[0]['entry_dt'];
												}
											}
										?>
											<tr class="gradeX">
												<td align="center"> <?php echo $i+1;?> </td>
												<td align="center"> <?php echo $rotation; ?> </td>
												<td align="center"> <?php echo $bl; ?> </td>
												<td align="center"> <?php echo $unit; ?> </td>
												<td align="center"> <?php echo $rslt_sqlRelease[$i]['contStatus']; ?> </td>
												<td align="center"> <?php echo $rslt_sqlRelease[$i]['agent_name']; ?> </td>

												<td class="text-center" width="12%">
													<?php
														if($billGenerated != 0)
														{
															echo "Bill Generation Done.";
														}
														else if($chkverified != 0)
														{
															echo "Verify Done.";
														}
														else if($appraise_st == 1)
														{
															echo "Appraised.";
														}
														else if(strlen(trim($unit))>0)
														{
															echo "Unit Assigned.";
														}
														else if($chkcertified != 0)
														{
															echo "Certify Done.";
														}
														else
														{
															echo "Pending at Certify";
														}
													?>
												</td>
												<td class="text-left" width="20%">
													<?php
														echo "<b>Submitted at: </b>".$rslt_sqlRelease[$i]['entry_at']."<br/>";
														if($chkcertified != 0){
															echo "<b>Certify at: </b>".$certify_at."<br/>";
														}

														if(strlen(trim($unit))>0){
															echo "<b>Unit Assign at: </b>".$unit_assign_at."<br/>";
														}

														if($appraise_st == 1){
															echo "<b>Appraised at: </b>".$appraise_at."<br/>";
														}

														if($chkverified != 0){
															echo "<b>Verify at: </b>".$verify_at."<br/>";
														}

														if($billGenerated != 0){
															echo "<b>Bill Generated at: </b>".$generated_at."<br/>";
														}
													?>
												</td>

												<?php
													if($org_Type_id == 62){
												?>
													
												<td>
													<?php
														if($chkcertified == 0){
													?>
														<form action="<?php echo site_url('ReleaseOrderController/lclAssignmentCertifyList');?>" method="POST">
															<input type="hidden" name="ddl_imp_rot_no" id="ddl_imp_rot_no" value="<?php echo $rotation; ?>">
															<input type="hidden" name="ddl_bl_no" id="ddl_bl_no" value="<?php echo $bl; ?>">				
															<input type="submit" value="Certify"  class="mb-xs mt-xs mr-xs btn btn-primary text-center">	
														</form>
													
													<?php
														} else if(strlen(trim($unit))==0){
													?>	
														<form action="<?php echo site_url('ReleaseOrderController/unitSetUpdate');?>" method="POST">
															<input type="hidden" name="ddl_imp_rot_no" id="ddl_imp_rot_no" value="<?php echo $rotation; ?>">
															<input type="submit" value="Unit Assign"  class="mb-xs mt-xs mr-xs btn btn-primary">	
														</form>
													<?php	
														}
														else if($ro_type!="Delivery" && $appraise_st == 0)
														{
														?>	
															<form action="<?php echo site_url('ReleaseOrderController/appraisementCertifyList');?>" method="POST">
																<input type="hidden" name="ddl_imp_rot_no" id="ddl_imp_rot_no" value="<?php echo $rotation; ?>">

																<input type="hidden" name="ddl_bl_no" id="ddl_bl_no" value="<?php echo $bl;?>">

																<input type="hidden" name="type" id="type" value="RO">				
																<input type="hidden" name="id" id="id" value="<?php echo $id;?>">				
																<input type="submit" value="Appraise"  class="mb-xs mt-xs mr-xs btn btn-primary">	
															</form>
														<?php
														}
														else if($chkverified == 0){
													?>
														<form action="<?php echo site_url('ReleaseOrderController/deliveryEntryFormByWHClerk');?>" method="POST">
															<input type="hidden" name="ddl_imp_rot_no" id="ddl_imp_rot_no" value="<?php echo $rotation; ?>">
															<input type="hidden" name="ddl_bl_no" id="ddl_bl_no" value="<?php echo $bl; ?>">
															<input type="submit" value="Verify"  class="mb-xs mt-xs mr-xs btn btn-primary">	
														</form>
													<?php
														} else if($billGenerated == 0){
													?>
														<form action="<?php echo site_url('ShedBillController/billGenerationForm');?>" method="POST">
															<input type="hidden" name="verify_number" id="verify_number" value="<?php echo $verify_no; ?>">
															<input type="submit" value="Bill Generation"  class="mb-xs mt-xs mr-xs btn btn-primary">	
														</form>
													<?php
														} else {
													?>
														<input type="button" value="Bill Generated"  class="mb-xs mt-xs mr-xs btn btn-primary" disabled>
													<?php
														}
													?>
												</td>

												<?php
													}
												?>

												<td class="text-center">
														<form action="<?php echo site_url('ReleaseOrderController/releaseOrderViewTos')?>" target="_blank" method="POST">		
															<input type="hidden" name="imp_rot" id="imp_rot" value="<?php echo $rotation; ?>" />
															<input type="hidden" name="bl_no" id="bl_no" value="<?php echo $bl; ?>" />
															<input type="submit" value="ViewRO"  class="mb-xs mt-xs mr-sm btn btn-xs btn-success">
														</form>
													
													<?php
														if($org_Type_id == 62){
													?>
														<!-- <form action="<?php //echo site_url('ReleaseOrderController/releaseOrderView')?>" target="_blank" method="POST">		
															<input type="hidden" name="imp_rot" id="imp_rot" value="<?php //echo $rotation; ?>" />
															<input type="hidden" name="bl_no" id="bl_no" value="<?php //echo $bl; ?>" />
															<input type="submit" value="ViewRO"  class="mb-xs mt-xs mr-xs btn btn-sm btn-success">
														</form> -->
													<?php
														}
													?>

												</td>
												


												<?php
													if($org_Type_id == 2){
												?>
												<td align="center">
										
													<form action="<?php echo site_url('ReleaseOrderController/shedBillDraftDetail');?>" method="POST" target="_blank">
														<input type="hidden" name="imp_rot" id="imp_rot" value="<?php echo $rotation; ?>" />
														<input type="hidden" name="bl_no" id="bl_no" value="<?php echo $bl; ?>" />						
														<input type="submit" value="DraftBill" name="DraftBill" class="mb-xs mt-xs mr-xs btn btn-primary btn-sm">
													</form>
												</td>
												
												<td align="center">
													<?php
														if($chkverified == 0 && $chkcertified == 0){
													?>
														<form action="<?php echo site_url('ReleaseOrderController/roDelete');?>" method="POST" onsubmit="return validate();">
															<input type="hidden" id="eid" name="eid" value="<?php echo $rslt_sqlRelease[$i]['id'];?>">							
															<input type="submit" value="Delete" name="delete" class="mb-xs mt-xs mr-xs btn btn-danger btn-sm" >
														</form>
													<?php
														}
														else
														{
													?>
														<input type="button" value="Delete" name="delete" class="mb-xs mt-xs mr-xs btn btn-danger btn-sm" disabled>
													<?php
														}
													?>
												</td>
												<?php
													}
												?>
											</tr>
										<?php } ?>
									</tbody>
								</table>
							</div>
						</section>
					<!-- end: page -->
				</section>
			</div>