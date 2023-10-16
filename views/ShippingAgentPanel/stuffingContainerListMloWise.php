
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
									<h5 align="center" class="h4 mt-none mb-sm text-dark text-bold">STUFFING CONTAINER LIST</h5>
								</div>
							</div>
						</header>
						<div class="table-responsive">
							<table class="table table-bordered table-responsive table-hover table-striped mb-none">
								<thead>
									<tr class="gridDark">
										<th style="border:1px solid black;">Sl</th>
										<th style="border:1px solid black;">Container No</th>
										<th style="border:1px solid black;">Seal No</th>
										<th style="border:1px solid black;">ISO</th>
										<th style="border:1px solid black;">Size</th>
										<th style="border:1px solid black;">Height</th>
										<th style="border:1px solid black;">Type</th>
						
										<th style="border:1px solid black;">Stuffing Date</th>
										<th style="border:1px solid black;">Destination Port</th>
										<th style="border:1px solid black;">Commodity</th>
										<th style="border:1px solid black;">Offdock</th>									
									</tr>
								</thead>
								<tbody>
									<?php for($i=0;$i<count($rslt_stuffing_report);$i++) { ?>
									<tr class="gradeX">
										<td align="center" style="border:1px solid black;"><?php echo $i+1; ?></td>
										<td align="center" style="border:1px solid black;"><?php echo $rslt_stuffing_report[$i]['cont_id']; ?></td>
										<td align="center" style="border:1px solid black;"><?php echo $rslt_stuffing_report[$i]['seal_no']; ?></td>
										<td align="center" style="border:1px solid black;"><?php echo $rslt_stuffing_report[$i]['iso_type']; ?></td>
										<td align="center" style="border:1px solid black;"><?php echo $rslt_stuffing_report[$i]['size']; ?></td>
										<td align="center" style="border:1px solid black;"><?php echo $rslt_stuffing_report[$i]['height']; ?></td>
										<td align="center" style="border:1px solid black;"><?php echo $rslt_stuffing_report[$i]['iso_group']; ?></td>
										
										<td align="center" style="border:1px solid black;"><?php echo $rslt_stuffing_report[$i]['stuffing_date']; ?></td>
										<td align="center" style="border:1px solid black;"><?php echo $rslt_stuffing_report[$i]['dest_port']; ?></td>
										<td align="center" style="border:1px solid black;"><?php echo $rslt_stuffing_report[$i]['comodity_code']; ?></td>
										<td align="center" style="border:1px solid black;"><?php echo $rslt_stuffing_report[$i]['name']; ?></td>
									</tr>
									<?php } ?>
									<tr align="center" class="gradeX">
										<td style="border:1px solid black;" colspan="2" align="center">
											<b>20' => <?php echo $size_20;?></b>
										</td>
										<td style="border:1px solid black;" colspan="2" align="center">
											<b>40' => <?php echo $size_40;?></b>
										</td>
										<td style="border:1px solid black;" colspan="7" align="left">
											<b>Teus => <?php echo $t20+$t40;?></b>
										</td>
									</tr>
								</tbody>
							</table>
						</div>
					</div>
					<div class="text-right mr-lg">
						<form class="form-horizontal form-bordered" method="POST" name= "stuffingContainerListPrintPerform" 
						id="stuffingContainerListPrintPerform" action="<?php echo site_url('Report/stuffingContainerListPrintPerform') ?>" target="_blank">
								
							<input type="hidden" name="cont_no" id="cont_no" value="<?php echo $cont_no?>" />
							<input type="hidden" name="offDock_condsubstring" id="offDock_condsubstring" 
								value="<?php echo $offDock_condsubstring?>" />
							<input type="hidden" name="condmlo" id="condmlo" value="<?php echo $condmlo?>" />
							<input type="hidden" name="stuffing_date_mlo" id="stuffing_date_mlo" value="<?php echo $stuffing_date_mlo?>" />
							
							<button type="submit" class="mb-xs mt-xs mr-xs btn btn-success"><i class="fa fa-print"></i> Print</button>
						</form>
					</div>
				</div>
			</section>
		
	</div>

