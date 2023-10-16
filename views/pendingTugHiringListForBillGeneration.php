<script>
	function validate()
		{
			if (confirm("Do you want to generate bill ?") == true)
				{
					return true ;
				}
			else
				{
					return false;
				}
		}
</script>
<?php include("mydbPConnectionn4.php"); ?>
<?php include("mydbPConnection.php"); ?>
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
				<?php if($msg != null) { ?>
				<header class="panel-heading">
					<h6 class="panel-title" align="center">
						<b><?php echo $msg;?></b>
					</h6>								
				</header>
				<?php } ?>
				<div class="panel-body">
					<div class="row">
							<input type="hidden" id="totalRow" name="totalRow" value="<?php echo count($tugHiringList);?>">
							<div class="col-md-12">
								<div>
									<table class="table table-responsive table-bordered table-striped" id="datatable-default">
										<thead>
											<tr>
												<th class="text-center">SL</th>
												<th class="text-center">Vessel</th>
												<th class="text-center">Location</th>
												<th class="text-center">Rotation</th>
												<th class="text-center">Description</th>
												<th class="text-center">Timing</th>
												<th class="text-center">Hours</th>
												<th class="text-center">Bill Generation</th>
											</tr>
										</thead>
										<tbody>
											<?php 
												$sl = 0;
												for($i=0;$i<count($tugHiringList);$i++){ 
												$sl++;
												
											?>
												<tr class="gradeX">
													<td align="center"><?php echo $i+1;?></td>
													<td align="center"><?php echo $tugHiringList[$i]['vessel_name']?></td>
													<td align="center">
														<?php 
															if($tugHiringList[$i]['location'] == "inside")
																echo "Inside Port";
															else if($tugHiringList[$i]['location'] == "outside")
																echo "Outside Port";
														?>
													</td>
													<td align="center"><?php echo $tugHiringList[$i]['rotation']?></td>
													<td align="center"><?php echo $tugHiringList[$i]['description']?></td>
													<td align="center">
														<?php 
															$id = $tugHiringList[$i]['id'];
															$sql_timing = "SELECT vehicle_name,from_date,from_time,to_date,to_time
																		FROM tug_hire_timing WHERE tug_hire_id='$id'";
															$rslt_timing = mysqli_query($con_cchaportdb,$sql_timing);
															
															while($row_timing = mysqli_fetch_object($rslt_timing))
															{
																echo "<b>".$row_timing->vehicle_name."</b> : ".date( 'm-d-Y', strtotime($row_timing->from_date) )." ".$row_timing->from_time." to ".date( 'm-d-Y', strtotime($row_timing->from_date) )." ".$row_timing->to_time."<br>";
															}
														?>
													</td>
													<td align="center">
														<?php echo $tugHiringList[$i]['total_hours'];?>
													</td>
													<td align="center">
														
														<form action="<?php echo site_url('VesselBill/tugHireBill');?>" method="POST"
															onclick="return validate();">
															<input type="hidden" name="tug_hire_id" id="tug_hire_id" 
																value="<?php echo $tugHiringList[$i]['id'];?>">
															<input type="hidden" name="location" id="location" 
																value="<?php echo $tugHiringList[$i]['location'];?>">
															<button type="submit" class="mb-xs mt-xs mr-xs btn btn-sm btn-primary">
																Generate Bill
															</button>
														</form>
														
														<form action="<?php echo site_url('Vessel/usdtoBdtExchangeRateform');?>" 
																method="POST" >
															<input type="hidden" name="forwarding_id" id="forwarding_id" 
																value="<?php echo $tugHiringList[$i]['id'];?>">
															<!--button type="submit" class="mb-xs mt-xs mr-xs btn btn-sm btn-success">
																Rate Setting
															</button-->
														</form>
														
													</td>
												</tr>
											<?php } ?>
										</tbody>
									</table>
								</div>
							</div>
						</form>
					</div>
				</div>
			</section>
		</div>
	</div>	
	<!-- end: page -->
</section>
</div>
<?php mysqli_close($con_cchaportdb); ?>
<?php mysqli_close($con_sparcsn4); ?>
