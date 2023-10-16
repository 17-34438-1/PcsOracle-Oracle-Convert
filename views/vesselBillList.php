		
			<!-- start: page -->
			<section class="panel">
				<div class="panel-body">
					<div class="invoice">
						<header class="clearfix">
							<div class="row">
								<div class="col-sm-12 text-center mt-md mb-md">
									<div class="ib">
										<img src="<?php echo ASSETS_WEB_PATH;?>fimg/cpaLogo.png" alt="Chattogram Port Authority Logo"/><br>
										<h4 class="h4 mt-none mb-sm text-dark text-bold">Chattogram Port Authority</h4>
										<h5 align="center" class="h5 mt-none mb-sm text-dark text-bold">Vessel Bill List</h5>
									</div>
								</div>
							</div>
						</header>
						<div class="panel-body">
							<table class="table table-bordered table-responsive table-hover table-striped mb-none" id="datatable-default">
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
									</tr>
								</thead>
								<tbody>
									<?php 
										$j=$start;
										for($i=0;$i<count($rslt_bill_list);$i++) 
										{ 
											$j++;
									?>
											<tr class="gradeX">
												<td align="center"> <?php echo $j; ?> </td>
												<td align="center"><?php echo $rslt_bill_list[$i]['finalNumber']; ?></td>
												<td align="center"><?php echo $rslt_bill_list[$i]['rotation']; ?></td>
												<td align="center"><?php echo $rslt_bill_list[$i]['vsl_name']; ?></td>
												<td align="center">
													<a href="<?php echo site_url('Report/viewBill/'.$rslt_bill_list[$i]['draftNumber'].'/'.$rslt_bill_list[$i]['cnt_code'].'/'.$rslt_bill_list[$i]['bill_type']) ?>" target="_blank">
														<?php echo $rslt_bill_list[$i]['bill_name']; ?>
													</a>
												</td>
												<td align="center"> <?php echo $rslt_bill_list[$i]['ata']; ?> </td>
												<td align="center"> <?php echo $rslt_bill_list[$i]['atd']; ?> </td>
												<td align="center"> <?php echo $rslt_bill_list[$i]['agent_code']; ?> </td>
												<td align="center"> <?php echo $rslt_bill_list[$i]['flag']; ?> </td>
											</tr>
									<?php } ?>
								</tbody>
							</table>
						</div>
					</div>
				
				</div>
			</section>
			<!-- end: page -->
	</div>

