<?php include("mydbPConnection.php"); ?>
<?php include("dbConection.php"); ?>
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
								<th class="text-center">#Sl</th>
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
								$bl = "";
								$rtnValue = "";
								for($i=0;$i<count($result);$i++){ 
								$rot = $result[$i]['Import_Rotation_No'];
								//$bl = $result[$i]['BL_No'];
								$sql = "SELECT COUNT(*) AS rtnValue FROM auction_handover WHERE rotation_no='$rot'";
								$rslt=mysqli_query($con_cchaportdb,$sql);
								while($row=mysqli_fetch_object($rslt)){
								$rtnValue = $row->rtnValue;
								}
								if($rtnValue==0) {

							 $berth_query = "SELECT DATE(time_discharge_complete) AS cl_date, sparcsn4.ref_bizunit_scoped.id AS agent,
									DATE(sparcsn4.argo_carrier_visit.ata) AS arriv_dt
									FROM sparcsn4.vsl_vessel_visit_details 
									INNER JOIN sparcsn4.argo_carrier_visit ON sparcsn4.vsl_vessel_visit_details.vvd_gkey=sparcsn4.argo_carrier_visit.cvcvd_gkey 
									INNER JOIN sparcsn4.argo_visit_details ON sparcsn4.argo_visit_details.gkey = sparcsn4.vsl_vessel_visit_details.vvd_gkey
									INNER JOIN sparcsn4.ref_bizunit_scoped ON sparcsn4.ref_bizunit_scoped.gkey=sparcsn4.vsl_vessel_visit_details.bizu_gkey
									 WHERE ib_vyg='$rot' LIMIT 1";
											
											$cl_date="";
											$arriv_dt="";
											
											$berth_rslt=mysqli_query($con_sparcsn4,$berth_query);
											$j=0;
											while($berth_row = mysqli_fetch_object($berth_rslt)){
												$j++;
												$cl_date = $berth_row->cl_date;
												$arriv_dt = $berth_row->arriv_dt;
											}
									
							?>
							<tr>
								<td align="center"><?php echo $i+1;?></td>
								<td align="center"><?php echo $result[$i]['Import_Rotation_No'];?></td>
								<td align="center"><?php echo $result[$i]['Vessel_Name'];?></td>
								<td align="center"><?php echo $arriv_dt;?></td>
								<td align="center"><?php echo $cl_date;?></td>
								<td align="center">
									<form action="<?php echo site_url("Auction/AuctionHandOverReportFormLCL");?>" method="post" target="_blank">
										<input type="hidden" class="form-control" name="rotation" id="rotation" value="<?php echo $rot; ?>">
										<!--input type="hidden" class="form-control" name="bl_no" id="bl_no" value="<?php echo $bl; ?>"-->
										<button type="submit" value="Search" name="action" id="action" class="btn btn-primary">Hand Over</button>	
									</form>
								</td>
								<td align="center">
									<form action="<?php echo site_url("Auction/AuctionHandNoticeGenerationLCL");?>" method="post" target="_blank">
										<input type="hidden" class="form-control" name="rotation" id="rotation" value="<?php echo $rot; ?>">
										<input type="hidden" class="form-control" name="cl_dt" id="cl_dt" value="<?php echo $cl_date;?>">
										<input type="hidden" class="form-control" name="arriv_dt" id="arriv_dt" value="<?php echo $arriv_dt;?>">
										<button type="submit" value="Search" name="action" id="action" class="btn btn-primary"> Notice</button>	
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