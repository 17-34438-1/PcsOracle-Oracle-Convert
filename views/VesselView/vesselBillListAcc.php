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
				<header class="panel-heading">
					<h2 class="panel-title text-center" align="right">
						<?php echo $msg;?>
					</h2>								
				</header>
				<div class="panel-body">
				<?php
					$section= $this->session->userdata('section');
				?>
					<hr/>					
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
								<th class="text-center">Action</th>
								<?php
									if($action == "p" && $section == "acc"){
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
										<td align="center"><?php echo $rslt_bill_list[$i]['finalNumber']; ?></td>
										<td align="center"><?php echo $rslt_bill_list[$i]['rotation']; ?></td>
										<td align="center"><?php echo $rslt_bill_list[$i]['vsl_name']; ?></td>
										<td align="center"><?php echo $rslt_bill_list[$i]['bill_name']; ?></td>
										<td align="center"> <?php echo $rslt_bill_list[$i]['ata']; ?> </td>
										<td align="center"> <?php echo $rslt_bill_list[$i]['atd']; ?> </td>
										<td align="center"> <?php echo $rslt_bill_list[$i]['agent_code']; ?> </td>
										<td align="center"> <?php echo $rslt_bill_list[$i]['flag']; ?> </td>
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