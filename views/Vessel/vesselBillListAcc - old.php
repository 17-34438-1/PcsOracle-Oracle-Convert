<script>
	function confirmBillDelete()
	{
		if(confirm("Do you want to delete this bill?"))
		{
			return true;
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

	<!-- start: page -->
	<div class="row">
		<div class="col-lg-12">						
			<section class="panel">
				
					
						<p align="center"><?php echo $msg;?></p>
												
				
				<div class="panel-body">
					
				<?php
					$section= $this->session->userdata('section');
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
								<th class="text-center">Action</th>	
								<th class="text-center">Action</th>	
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
										<td align="center"><?php echo $rslt_bill_list[$i]['bill_name']; ?></td>
										<td align="center"> <?php echo $rslt_bill_list[$i]['ata']; ?> </td>
										<td align="center"> <?php echo $rslt_bill_list[$i]['atd']; ?> </td>
										<td align="center"> <?php echo $rslt_bill_list[$i]['agent_code']; ?> </td>
										<td align="center"> <?php echo $rslt_bill_list[$i]['flag']; ?> </td>
										<td align="center"> <?php echo $rslt_bill_list[$i]['creator']; ?> </td>
										<td>
											<a href="<?php echo site_url('Report/departureReportOfVessel/R/'.str_replace("/","_",$rslt_bill_list[$i]['rotation']))?>" target="_blank" method="POST">
												<span class="btn btn-warning" >Pilot Paper</span>
											</a>
										</td>
										<td align="center"> 
											<a href="<?php echo site_url('VesselBill/viewVesselBillAcc/'.$rslt_bill_list[$i]['draftNumber'].'/'.$rslt_bill_list[$i]['bill_type']) ?>" target="_blank" class="mb-xs mt-xs mr-xs btn btn-primary">
												View
											</a> 
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
											<form action="<?php echo site_url('VesselBill/vslBillDelete/'); ?>" method="post" onsubmit="return confirmBillDelete()" >
												<input type="hidden" name="draftForDelete" id="draftForDelete" value="<?php echo $rslt_bill_list[$i]['draftNumber']; ?>" />
												<input type="submit" name="btnVslBillDelete" id="btnVslBillDelete" class="mb-xs mt-xs mr-xs btn btn-danger" value="Delete" />
											</form>
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