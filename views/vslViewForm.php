<section role="main" class="content-body">
	<header class="page-header">
		<h2><?php echo $title;?></h2>
	
		<div class="right-wrapper pull-right">
			
		</div>
	</header>
	<?php
		include('dbConection.php');
		$str = "select * from (
		select sparcsn4.vsl_vessel_visit_details.vvd_gkey,concat(sparcsn4.vsl_vessels.name,'-',sparcsn4.vsl_vessel_visit_details.ib_vyg) as vsl
				from sparcsn4.vsl_vessel_visit_details
				inner join sparcsn4.argo_carrier_visit on sparcsn4.argo_carrier_visit.cvcvd_gkey=sparcsn4.vsl_vessel_visit_details.vvd_gkey
				inner join sparcsn4.vsl_vessels on sparcsn4.vsl_vessels.gkey=sparcsn4.vsl_vessel_visit_details.vessel_gkey
				where sparcsn4.argo_carrier_visit.phase not in('80CANCELED','70CLOSED') and sparcsn4.vsl_vessels.name not like '%PANGAON%' 
		union
		select sparcsn4.vsl_vessel_visit_details.vvd_gkey,concat(sparcsn4.vsl_vessels.name,'-',sparcsn4.vsl_vessel_visit_details.ib_vyg) as vsl
				from sparcsn4.vsl_vessel_visit_details
				inner join sparcsn4.argo_carrier_visit on sparcsn4.argo_carrier_visit.cvcvd_gkey=sparcsn4.vsl_vessel_visit_details.vvd_gkey
				inner join sparcsn4.vsl_vessels on sparcsn4.vsl_vessels.gkey=sparcsn4.vsl_vessel_visit_details.vessel_gkey
				where sparcsn4.argo_carrier_visit.atd > DATE_ADD(now(),interval -2 day)
		) as tbl order by vvd_gkey desc";
		$query = mysqli_query($con_sparcsn4,$str);
	?>
	<!-- start: page -->
		<div class="row">
			<div class="col-lg-12">						
				<section class="panel">
					<header class="panel-heading">
						<h2 class="panel-title" align="right">
							<!--a href="<?php echo site_url('ControllerName/FunctionName') ?>">
								<button style="margin-left: 35%" class="btn btn-primary btn-sm">
									<i class="fa fa-list"></i>
								</button>
							</a-->
						</h2>								
					</header>
					<div class="panel-body">
						<form class="form-horizontal form-bordered" id="myform" method="POST" action="<?php echo site_url('uploadExcel/bayViewPerformed') ?>"
						target="_blank">
						
							<div class="form-group">
								<label class="col-md-3 control-label">&nbsp;</label>
								<div class="col-md-6">
									<div class="input-group mb-md">
										<span class="input-group-addon span_width">Rotation :</span>
										<input type="text" name="vsl_rotation" id="vsl_rotation" class="form-control" required>
									</div>
								</div>
																				
								<div class="row">
									<div class="col-sm-12 text-center">
										<button type="submit" class="mb-xs mt-xs mr-xs btn btn-success">View</button>
									</div>													
								</div>
								<div class="row">
									<div class="col-sm-12 text-center">
										
									</div>
								</div>
							</div>	
						</form>
					</div>
				</section>
		
			</div>
		</div>	
	<!-- end: page -->
</section>
</div>