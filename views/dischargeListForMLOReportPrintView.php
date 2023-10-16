<style type="text/css" media="print">
	@page {
		size: auto;   /* auto is the initial value */
		margin: 0;  /* this affects the margin in the printer settings */
	}
</style>			
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
							<table class="table table-bordered table-responsive table-hover table-striped mb-none">
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
											<td align="center"><?php echo $getList[$i]['id']?></td>
											<td align="center"><?php echo $getList[$i]['size']?></td>
											<td align="center"><?php echo $getList[$i]['height']?></td>
											<td align="center"><?php echo $getList[$i]['freight_kind']?></td>
											<td align="center"><?php echo $getList[$i]['time_in']?></td>
											<td align="center"> <?php echo $getList[$i]['time_out']?></td>
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
<script>
	window.print();
</script>
