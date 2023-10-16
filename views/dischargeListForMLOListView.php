		
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
										<h5 align="center" class="h5 mt-none mb-sm text-dark text-bold">Discharge List</h5>
										<h5 align="center" class="h5 mt-none mb-sm text-dark text-bold">
											<?php echo " From: ".$fromDate." "." To: ".$toDate;?>
										</h5>
									</div>
								</div>
							</div>
						</header>
						<div class="panel-body">
							<table class="table table-bordered table-responsive table-hover table-striped mb-none" id="datatable-default">
								<thead>
									<tr class="gridDark">
										<th class="text-center">Sl.NO</th>
										<th class="text-center">CONTAINER</th>									
										<th class="text-center">CONT. SIZE</th>									
										<th class="text-center">CONT. HEIGHT</th>									
										<th class="text-center">STATUS</th>									
										<th class="text-center">DISCHARGE TIME</th>									
										<th class="text-center">DELIVERY TIME</th>										
									</tr>
								</thead>
								<tbody>
									<?php for($i=0;$i<count($getList);$i++) { ?>
										<tr class="gradeX">
											<td align="center"> <?php echo $i+1;?> </td>
											<td align="center"><?php echo $getList[$i]['ID']?></td>
											<td align="center"><?php echo $getList[$i]['SIZ']?></td>
											<td align="center"><?php echo $getList[$i]['HEIGHT']?></td>
											<td align="center"><?php echo $getList[$i]['FREIGHT_KIND']?></td>
											<td align="center">
												<?php echo $getList[$i]['TIME_IN']?>
											</td>
											<td align="center"> 
												<?php echo $getList[$i]['TIME_OUT']?>
											</td>
										</tr>
									<?php } ?>
								</tbody>
							</table>
						</div>
					</div>
					<div class="text-right mr-lg">
						<form class="form-horizontal form-bordered" method="POST" action="<?php echo site_url('Report/dischargeListForMLOReportPrint') ?>" target="_blank">
								
							<input type="hidden" name="fromDate" id="fromDate" value="<?php echo $fromDate?>" />
							<input type="hidden" name="toDate" id="toDate" value="<?php echo $toDate?>" />
							<input type="hidden" name="type" id="type" value="<?php echo $type?>" />
							
							<button type="submit" class="mb-xs mt-xs mr-xs btn btn-success"><i class="fa fa-print"></i> Print</button>
						</form>
					</div>
				</div>
			</section>
			<!-- end: page -->
	</div>

