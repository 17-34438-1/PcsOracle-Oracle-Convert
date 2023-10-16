<?php include("mydbPConnection.php"); ?>
<section role="main" class="content-body">
	<header class="page-header">
		<h2><?php echo $title;?></h2>	
		<div class="right-wrapper pull-right"></div>
	</header>
	<!-- start: Table -->
	<div class="row">
		<div class="col-lg-12">	
			<section class="panel">
				<div class="panel-body">					
					<table class="table table-bordered mb-none" id="datatable-default">
						<thead>
							<tr>
								<th class="text-center">#RL</th>
								<th class="text-center">Rotation No</th>
								<th class="text-center">Vessel Name</th>
								<th class="text-center">Arrival Date</th>
								<th class="text-center">C/L Date</th>
								<th class="text-center">Action</th>					
								<th class="text-center">Notice</th>					
							</tr>
						</thead>
						<tbody>
							<?php 
								$rot = "";
								$rtnValue = "";
								for($i=0;$i<count($result);$i++){ 
								$rot = $result[$i]['ROT_NO'];
								$sql = "SELECT COUNT(*) AS rtnValue FROM auction_handover WHERE rotation_no='$rot'";
								$rslt=mysqli_query($con_cchaportdb,$sql);
								while($row=mysqli_fetch_object($rslt)){
								$rtnValue = $row->rtnValue;
								}
								if($rtnValue==0) { 
							?>
							<tr>
								<td align="center"><?php echo $i+1;?></td>
								<td align="center"><?php echo $result[$i]['ROT_NO'];?></td>
								<td align="center"><?php echo $result[$i]['V_NAME'];?></td>
								<td align="center"><?php echo $result[$i]['ATA'];?></td>
								<td align="center"><?php echo $result[$i]['CL_DT'];?></td>
								<td align="center">
									<form action="<?php echo site_url("Report/AuctionHandOverReportForm");?>" method="post">
										<input type="hidden" class="form-control" name="rotation" id="rotation" value="<?php echo $rot; ?>">
										<button type="submit" value="Search" name="action" id="action" class="btn btn-primary">Hand Over</button>	
									</form>
								</td>
								<td align="center">
									<form action="<?php echo site_url("Report/AuctionHandNoticeGeneration");?>" method="post" target="_blank">
										<input type="hidden" class="form-control" name="rotation" id="rotation" value="<?php echo $result[$i]['ROT_NO']; ?>">
										<input type="hidden" class="form-control" name="arrival_dt" id="arrival_dt" value="<?php echo $result[$i]['ATA'];?>">
										<input type="hidden" class="form-control" name="cl_dt" id="cl_dt" value="<?php echo $result[$i]['CL_DT'];?>">
										<button type="submit" value="Search" name="action" id="action" class="btn btn-primary"> Notice Generate</button>	
									</form>
								</td>
								
							</tr>
							<?php } } ?>
						</tbody>
					</table>
				</div>
			</section>	
		</div>
	</div>	
	<!-- end: Table -->
</section>
</div>
<?php mysqli_close($con_cchaportdb); ?>
