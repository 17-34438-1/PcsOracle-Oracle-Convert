<section role="main" class="content-body">
	<header class="page-header">
		<h2><?php echo $title;?></h2>
	
		<div class="right-wrapper pull-right">
			
		</div>
	</header>

	<!-- start: page -->
		<?php
		
		
		include('dbConection.php');
		include("dbOracleConnection.php");

		$str = "select distinct vsl_vessel_visit_details.vvd_gkey,(vsl_vessels.name || '-' ||vsl_vessel_visit_details.ib_vyg) AS vsl,argo_carrier_visit.phase
		from vsl_vessel_visit_details
		inner join argo_carrier_visit on argo_carrier_visit.cvcvd_gkey=vsl_vessel_visit_details.vvd_gkey
		inner join vsl_vessels on vsl_vessels.gkey=vsl_vessel_visit_details.vessel_gkey
		where argo_carrier_visit.phase not in('80CANCELED','70CLOSED') order by argo_carrier_visit.phase desc";

		$query = oci_parse($con_sparcsn4_oracle,$str);
		oci_execute($query);
		
		
		?>
		<div class="row">
			<div class="col-lg-12">						
				<section class="panel">
					<header class="panel-heading">
						<h2 class="panel-title" align="right">
							
						</h2>								
					</header>
					<div class="panel-body">
						<form class="form-horizontal form-bordered" id="myform" method="POST" action="<?php echo site_url('report/blankBayView') ?>"
						target="_blank">
						
							<div class="form-group">
								<label class="col-md-3 control-label">&nbsp;</label>
								<div class="col-md-6">
									<div class="input-group mb-md">														
										<span class="input-group-addon span_width">Vessel :</span>
										<select name="vvdGkey" class="form-control" required>
											<option>----Select Vessel----</option>
											<?php while(($row= oci_fetch_object($query)) != false){ ?>
												<option value="<?php echo $row->VVD_GKEY; ?>"><?php echo $row->VSL; ?></option>
											<?php } ?>
										</select>														
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
