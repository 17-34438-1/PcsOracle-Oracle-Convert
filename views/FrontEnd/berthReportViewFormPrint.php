		

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
											</div>
										</div>
									</div>
									<div class="row">
										<div class="col-sm-12 mt-md">
											<h5 align="center" class="h4 mt-none mb-sm text-dark text-bold">Berthing Report From : <?php echo $fromdate ;?> To: <?php echo $todate ;?></h5>
											
										</div>
									</div>
								</header>							
							
								<div class="panel-body">
									<table class="table table-responsive table-bordered table-striped mb-none">
										<thead>
											<tr class="gridDark">
												<th align="center">Sl</th>
												<th align="center">Vessel ID</th>
												<th align="center">Vessel Name</th>
												<th align="center">Length</th>
												<th align="center">Draugh</th>
												<th align="center">Local Agent</th>
												<th align="center">L.Call Port</th>
												<th align="center">L.Op</th>
												<th align="center">Flag</th>
												<th align="center">Berthing Date(Est)</th>
												<th align="center">Berthing Date (Act)</th>
												<th align="center">Jetty No</th>
											</tr>
										</thead>
										<tbody>
											<?php for($i=0; $i<count($resultList); $i++) { ?>
											<tr class="gradeX">
												<td align="center"><?php echo $i+1; ?></td>
												<td align="center"><?php echo $resultList[$i]['VesselID']; ?></td>
												<td align="center"><?php echo $resultList[$i]['VesselName']; ?></td>
												<td align="center"><?php echo $resultList[$i]['LENGTH']; ?></td>
												<td align="center"><?php echo $resultList[$i]['Draft']; ?></td>
												<td align="center"><?php echo $resultList[$i]['LoadPortCall']; ?></td>
												<td align="center"><?php echo $resultList[$i]['LocalAgent']; ?></td>
												<td align="center"><?php echo $resultList[$i]['LineOperator']; ?></td>
												<td align="center"><?php echo $resultList[$i]['Flag']; ?></td>
												<td align="center"><?php echo $resultList[$i]['estBerthDate']; ?></td>
												<td align="center"><?php echo $resultList[$i]['BerthDate']; ?></td>
												<td align="center"><?php echo $resultList[$i]['JettyNo']; ?></td>
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
