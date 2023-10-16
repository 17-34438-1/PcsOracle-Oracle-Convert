<script>
	function confirmBillDelete(i)
	{
		var dlt_rot = document.getElementById('dlt_rot'+i).value;
		var dlt_bill_name = document.getElementById('dlt_bill_name'+i).value;
		
		// if(confirm("Renewal Pass : "+renewfeeamt+"*"+duration+" = "+sTotal+"\nVAT : "+vatTotal+"\nTotal : "+totalAmt+"\nDo you want to continue?"))
		
		if(confirm("Rotation : "+dlt_rot+"\nBill Name : "+dlt_bill_name+"\nDo you want to delete ?"))
		{
			return true;
		}
		else			
		{
			return false;
		}
	}

	function getData(sl)
	{
		var draft = document.getElementById('disputeDraft'+sl).value;
		var bill = document.getElementById('disputeBill'+sl).value;
		document.getElementById('billForDispute').value = bill;
		document.getElementById('draftForDispute').value = draft;
	}

	function confirmation(){
		if(confirm("Are you sure?"))
		{
			return true;
		}
		else			
		{
			return false;
		}
		return false;
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
				<p align="center"><?php echo $msg;?></p>

				<?php
					if(!is_null($this->session->flashdata('success'))){
						echo $this->session->flashdata('success');
					}

					if(!is_null($this->session->flashdata('error'))){
						echo $this->session->flashdata('error');
					}
				?>

				<div class="panel-body">
					
				<?php
					$section= $this->session->userdata('section');
					$org_type_id= $this->session->userdata('org_Type_id');
				?>
								
					<table class="table table-bordered table-hover table-striped mb-none" id="datatable-default">
						<thead>
							<tr class="gridDark">
								<th class="text-center">Sl.NO</th>
								<th class="text-center">Bill Number</th>									
								<th class="text-center">Rotation</th>									
								<th class="text-center">Vessel Name</th>									
								<th class="text-center">Bill Name</th>									
								<th class="text-center">Arrival</th>									
								<th class="text-center">Departure</th>										
								<th class="text-center">Agent Code</th>										
								<th class="text-center">Flag</th>
								<th class="text-center">Bill Operator</th>
								<?php
									if($action == "a"){
								?>
								<th class="text-center">Status</th>
								<th class="text-center">Dispute Details</th>
								<?php
									}
								?>
								<th class="text-center">Paper View</th>	
								<th class="text-center">Bill</th>	
								<?php
									if($action == "p" && $section == "acc"){
								?>
								<th class="text-center">Action</th>								
								<?php
									}
								?>
								<?php
									// if($action == "p" and $section == "billop")
									if($section == "billop")		// intakhab; - hanif vai's requirement
									{
								?>
									<th class="text-center">Action</th>	
									<th class="text-center">Action</th>	
								<?php
									}

									if($org_type_id == 81 || $org_type_id == 83){
								?>
									<th class="text-center">Dispute Text</th>	
								<?php
									}

									if($org_type_id == 1 || $org_type_id == 81 || $org_type_id == 83 || ($org_type_id == 82 && $section == "acc")){
								?>
									<th class="text-center">Action</th>
								<?php
									}
								?>
							</tr>
						</thead>
						<tbody>
							<?php 
								for($i=0;$i<count($rslt_bill_list);$i++) 
								{
							?>
									<tr class="gradeX">
										<td align="center"> <?php echo $i+1; ?> </td>
										<td align="center"><?php echo strtoupper($rslt_bill_list[$i]['billNumber']); ?></td>
										<td align="center"><?php echo $rslt_bill_list[$i]['rotation']; ?></td>
										<td align="center"><?php echo $rslt_bill_list[$i]['vsl_name']; ?></td>
										<td align="center" style="font-size:11px"><?php echo $rslt_bill_list[$i]['bill_name']; ?></font></td>
										<td align="center"> <?php echo $rslt_bill_list[$i]['ata']; ?> </td>
										<td align="center"> <?php echo $rslt_bill_list[$i]['atd']; ?> </td>
										<td align="center"> <?php echo $rslt_bill_list[$i]['agent_code']; ?> </td>
										<td align="center"> <?php echo $rslt_bill_list[$i]['flag']; ?> </td>
										<td align="center"> 
											<?php 
												echo $creator = trim($rslt_bill_list[$i]['creator']); 
												$creatorQuery = "SELECT u_name as rtnValue FROM users WHERE login_id = '$creator'";
												echo " -<br/>".$creatorData = $this->bm->dataReturnDb1($creatorQuery);
											?> 
										</td>
										<?php
											if($action == "a"){
										?>
										<td align="center"> 
											<?php 
												if($rslt_bill_list[$i]['disputeraised'] == 1)
												{
													if($rslt_bill_list[$i]['forward_by_st'] == 1)
													{
														echo "<font color='blue'>Forwarded to Regenerate Bill</font>";
													}
													else if($rslt_bill_list[$i]['forward_post_mod_st'] == 1)
													{
														echo "<font color='blue'>Forwarded by Herbour Master</font>";
													}
													else if($rslt_bill_list[$i]['mod_st'] == 1)
													{
														echo "<font color='blue'>Updated by Marine</font>";
													}
													else if($rslt_bill_list[$i]['app_st'] == 1)
													{
														echo "<font color='blue'>Dispute approved by Herbour Master</font>";
													}
													else
													{
														echo "<font color='red'>Dispute raised!</font>";
													}
												}
												else
												{
													echo "<font color='green'>No dispute raised..</font>";
												}
											?> 
										</td>
										<td>
											<nobr>Dispute at : <?= $rslt_bill_list[$i]['dispute_at'];?></nobr><br/>
											<nobr>H.M. approval at : <?= $rslt_bill_list[$i]['app_at'];?></nobr><br/>
											<nobr>Marine modified at : <?= $rslt_bill_list[$i]['mod_at'];?></nobr><br/>
											<nobr>H.M. forward to A/C at : <?= $rslt_bill_list[$i]['forward_post_mod_at'];?></nobr><br/>
											<nobr>A/C forward to Bill Op at : <?= $rslt_bill_list[$i]['forward_by_at'];?></nobr><br/>
											<nobr>Regenerate at : <?= $rslt_bill_list[$i]['bill_regenerate_at'];?></nobr><br/>
										</td>
										<?php
											}
										?>
										<td>
											<a href="<?php echo site_url('Report/departureReportOfVessel/R/'.str_replace("/","_",$rslt_bill_list[$i]['rotation']))?>" target="_blank" method="POST">
												<span class="mb-xs mt-xs mr-xs btn btn-warning" >PilotPaper</span>
											</a>
										</td>
										<td align="center"> 
											<a href="<?php echo site_url('VesselBill/viewVesselBillAcc/'.$rslt_bill_list[$i]['draftNumber'].'/'.$rslt_bill_list[$i]['bill_type']) ?>" target="_blank" class="mb-xs mt-xs mr-xs btn btn-primary">
												View
											</a>
											<?php
												if($org_type_id == 81 || $org_type_id == 83){
													$len = strlen($rslt_bill_list[$i]['dispute_doc']);
													if($len >0){
											?>
												<a href="<?php echo site_url("/../../vslbilldisputedoc/{$rslt_bill_list[$i]['dispute_doc']}"); ?>" target="_blank" class="mb-xs mt-xs mr-xs btn btn-primary">
													View Document
												</a>
											<?php
													}else{
											?>
												<button class="mb-xs mt-xs mr-xs btn btn-primary" disabled>View Document</button>
											<?php
													}
												}
											?>
										</td>
										<?php
											if($action == "p" && $section == "acc"){
										?>
										<td align="center">
											<form class="form-horizontal form-bordered" method="POST" action="<?php echo site_url('VesselBill/vesselBillListApprove'); ?>">
												<input type="hidden" name="draft" value="<?php echo $rslt_bill_list[$i]['draftNumber']; ?>">
												<button class="mb-xs mt-xs mr-xs btn btn-success" <?php if($rslt_bill_list[$i]['acc_apprv_st'] == 1){ echo "disabled";}?>>Approve</button>
											</form> 
										</td>
										<?php
											}
										?>
										<?php
											// if($action == "p" and $section == "billop")
											if($section == "billop")		// intakhab; - hanif vai's requirement
											{
										?>
										<td align="center">
											<?php
												if($action == "a" && $rslt_bill_list[$i]['forward_by_st'] == 1){
											?>
												<form action="<?php echo site_url('VesselBill/vslBillRegenerate/'); ?>" method="post" onsubmit="return confirm('Are you sure?')" >
													<input type="hidden" name="draftForRegenerate" id="draftForRegenerate" value="<?php echo $rslt_bill_list[$i]['draftNumber']; ?>" />
													<input type="hidden" name="regenerateBillType" id="regenerateBillType" value="<?php echo $rslt_bill_list[$i]['bill_type']; ?>" />
													<input type="hidden" name="regenerateRotation" id="regenerateRotation" value="<?php echo $rslt_bill_list[$i]['rotation']; ?>" />
													<input type="submit" name="btnVslBillRegenerate" id="btnVslBillRegenerate" class="mb-xs mt-xs mr-xs btn btn-info" value="Regenerate"/>
												</form>
											<?php 
												}

												if($action == "p"){
													$dlt_rot = $rslt_bill_list[$i]['rotation'];
													$dlt_bill_name = $rslt_bill_list[$i]['bill_name'];
											?>
												
												<form action="<?php echo site_url('VesselBill/vslBillDelete/'); ?>" method="post" 
														onsubmit="return confirmBillDelete(<?php echo $i;?>)" >
													
													<input type="hidden" name="draftForDelete" id="draftForDelete" 
														value="<?php echo $rslt_bill_list[$i]['draftNumber']; ?>" />
														
													<input type="hidden" name="dlt_rot<?php echo $i;?>" id="dlt_rot<?php echo $i;?>" 
														value="<?php echo $dlt_rot; ?>" />
														
													<input type="hidden" name="dlt_bill_name<?php echo $i;?>" 
														id="dlt_bill_name<?php echo $i;?>" value="<?php echo $dlt_bill_name; ?>" />
													
													<input type="submit" name="btnVslBillDelete" id="btnVslBillDelete" 
														class="mb-xs mt-xs mr-xs btn btn-danger" value="Delete" />
												</form>
											<?php
												}
											?>
										</td>
										<td align="center">
											<form action="<?php echo site_url('VesselBill/vslBillEditForm/'); ?>" method="post">
												<input type="hidden" name="billNo" id="billNo" value="<?php echo $rslt_bill_list[$i]['billNumber']; ?>" />
												<input type="hidden" name="draftForEdit" id="draftForEdit" value="<?php echo $rslt_bill_list[$i]['draftNumber']; ?>" />
												<input type="submit" name="btnVslBillEdit" id="btnVslBillEdit" class="mb-xs mt-xs mr-xs btn btn-warning btn-xs" value="Edit Bill No" />
											</form>
										</td>
										<?php
											}
											if($org_type_id == 81 || $org_type_id == 83){
										?>
										
										<td align="center"><font color='red'><?= $rslt_bill_list[$i]['dispute_text'];?></font></td>

										<?php
											}

											if($org_type_id == 1 || $org_type_id == 81 || $org_type_id == 83 || ($org_type_id == 82 && $section == "acc")){
										?>
										<td align="center">
											<input type="hidden" id="disputeBill<?php echo $i; ?>" value="<?php echo $rslt_bill_list[$i]['billNumber']; ?>" />
											<input type="hidden" id="disputeDraft<?php echo $i; ?>" value="<?php echo $rslt_bill_list[$i]['draftNumber']; ?>" />
										<?php
											if($org_type_id == 1){
										?>
											<button type="button" name="btnVslBillDispute" id="btnVslBillDispute" data-toggle="modal" data-target="#disputeModal" class="mb-xs mt-xs mr-xs btn btn-primary" <?php if($rslt_bill_list[$i]['disputeraised'] == 1){ echo "disabled";}?> onclick="getData(this.value)" value="<?php echo $i; ?>">Dispute</button>
										<?php 
											}

											if($rslt_bill_list[$i]['disputeraised'] == 1)
											{
												$billNumber = str_replace("/","_",$rslt_bill_list[$i]['billNumber']);
												if($org_type_id == 81)
												{
													if($rslt_bill_list[$i]['mod_st'] == 0)
													{
										?>
											<a href="<?php echo site_url('VesselBill/vslBillAction/'.'app'.'/'.$rslt_bill_list[$i]['draftNumber'].'/'.$billNumber) ?>" class="mb-xs mt-xs mr-xs btn btn-success" onclick="return confirmation()" <?php if($rslt_bill_list[$i]['app_st'] == 0){echo '';}else{ echo "disabled";}?>>Approve Dispute</a>
										<?php
													}else{
										?>
											<a href="<?php echo site_url('VesselBill/vslBillAction/'.'postMod'.'/'.$rslt_bill_list[$i]['draftNumber'].'/'.$billNumber) ?>" class="mb-xs mt-xs mr-xs btn btn-success" onclick="return confirmation()" <?php if($rslt_bill_list[$i]['forward_post_mod_st'] == 0){echo '';}else{ echo "disabled";}?>>Forward To Accountant</a>
										<?php
													}
												}
									 
												if($org_type_id == 83)
												{
										?>
											<a href="<?php echo site_url('VesselBill/vslBillAction/'.'mod'.'/'.$rslt_bill_list[$i]['draftNumber'].'/'.$billNumber) ?>" class="mb-xs mt-xs mr-xs btn btn-primary" onclick="return confirmation()" <?php if($rslt_bill_list[$i]['app_st'] == 1 && $rslt_bill_list[$i]['mod_st'] == 0){echo '';}else{ echo "disabled";}?>>Modified</a>
										<?php
												}
												if($org_type_id == 82 && $section == "acc")
												{
										?>
											<a href="<?php echo site_url('VesselBill/vslBillAction/'.'forward'.'/'.$rslt_bill_list[$i]['draftNumber'].'/'.$billNumber) ?>" class="mb-xs mt-xs mr-xs btn btn-primary" onclick="return confirmation()" <?php if($rslt_bill_list[$i]['forward_post_mod_st'] == 1 && $rslt_bill_list[$i]['forward_by_st'] == 0){echo '';}else{ echo "disabled";}?>>Forward To Bill Operator</a>
										<?php
												}
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
	
		</div>
	</div>	
	<!-- end: page -->
</section>
</div>


<!-- Modal -->
<form action="<?php echo site_url('VesselBill/vslBillDispute/'); ?>" method="post" enctype="multipart/form-data">
	<div class="modal fade" id="disputeModal" tabindex="-1" role="dialog" aria-labelledby="disputeModalLabel" aria-hidden="true">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="disputeModalLabel">Dispute Bill</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
				<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<input type="hidden" name="billForDispute" id="billForDispute" value="" />
				<input type="hidden" name="draftForDispute" id="draftForDispute" value="" />
				<input type="text" name="note" id="note" class="form-control" />
				<br/>
				<input type="file" name="noteFile" id="noteFile" class="form-control" />
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
				<button type="submit" class="btn btn-primary">Dispute</button>
			</div>
			</div>
		</div>
	</div>
</form>