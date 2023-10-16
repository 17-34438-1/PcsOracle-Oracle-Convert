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
						<div class="panel-body">
							<form class="form-horizontal form-bordered" id="myform" method="POST" action="<?php echo site_url('report/gangAssignToVesselInsert') ?>">
								<div class="form-group">
									<label class="col-md-3 control-label">&nbsp;</label>
									<div class="col-md-6">
										<div class="input-group mb-md">
											<?php
												echo $msg;
											?>
										</div>

										<div class="input-group mb-md">
											<span class="input-group-addon span_width">GANG ID</span>
											<select id="gangId" name="gangId" class="form-control" required >
												<option value="">--Select--</option>
												<?php 
												for($i=0; $i<count($gangInfo); $i++)
												{  ?> 
												<option value="<?php echo $gangInfo[$i]['GangID']; ?>" ><?php echo $gangInfo[$i]['GangID']; ?></option>
												<?php } ?>

											
											</select>
										</div>

										<div class="input-group mb-md">
											<span class="input-group-addon span_width">VESSEL ID</span>
											<select  id="vesselId" name="vesselId" class="form-control" required >
												<option value="">--Select--</option>
												<?php 
												for($i=0; $i<count($vesselInfo); $i++)
												{  ?> 
												<option value="<?php echo $vesselInfo[$i]['id']; ?>" ><?php echo $vesselInfo[$i]['name']; ?></option>
												<?php } ?>
											</select>
										</div>


									</div>									
									<div class="row">
										<div class="col-sm-12 text-center">
											<button type="submit" name="save" id="submit" class="mb-xs mt-xs mr-xs btn btn-success">Save</button>
										</div>													
									</div>
									<div class="row">
										<div class="col-sm-12 text-center">
											
										</div>
									</div>
								</div>	
							</form>
                            
                            <hr/>
														
							<table class="table table-bordered table-hover table-striped mb-none" id="datatable-default">
								<thead>
									<tr>
										<th class="text-center">SL.</th>
										<th class="text-center">Vessel Name</th>
										<th class="text-center">Assigned Gang</th>	
										<th class="text-center">Total Labour</th>	
									</tr>
								</thead>
								<tbody>
									<?php //$j = $start; 
										for($i=0;$i<count($gangAssign);$i++)
										{ 
												//$j++;
												
											?>
										<tr class="gridLight" align="center">
											<td><?php echo $i+1; ?></td>
											<td><?php
												include("mydbPConnectionn4.php");
												$vslId = $gangAssign[$i]['VesselDetailsID']; 
												$query = "SELECT sparcsn4.vsl_vessel_visit_details.vvd_gkey, argo_carrier_visit.id, sparcsn4.vsl_vessels.name,sparcsn4.vsl_vessel_visit_details.ib_vyg,
												IFNULL(sparcsn4.vsl_vessel_visit_details.flex_string02,sparcsn4.vsl_vessel_visit_details.flex_string03) AS berthop
												FROM sparcsn4.argo_carrier_visit
												INNER JOIN sparcsn4.argo_visit_details ON sparcsn4.argo_visit_details.gkey=sparcsn4.argo_carrier_visit.cvcvd_gkey
												INNER JOIN sparcsn4.vsl_vessel_visit_details ON sparcsn4.vsl_vessel_visit_details.vvd_gkey=sparcsn4.argo_visit_details.gkey
												INNER JOIN sparcsn4.vsl_vessels ON sparcsn4.vsl_vessels.gkey=sparcsn4.vsl_vessel_visit_details.vessel_gkey
												WHERE argo_carrier_visit.id='$vslId' AND sparcsn4.argo_carrier_visit.phase IN ('20INBOUND','30ARRIVED','40WORKING','50COMPLETE','60DEPARTED')
												ORDER BY sparcsn4.argo_carrier_visit.phase";
												$vslName=mysqli_fetch_object(mysqli_query($con_sparcsn4,$query));
												if($vslName != null){
													echo $vslName->name;
												}else{
													echo "";
												}

											?></td>
											<td><?php echo $gangId=$gangAssign[$i]['GangID']; ?></td>
											<td><?php 
												include("mydbPConnectionctmsmis.php");
												$query = "SELECT RequiredLabor FROM ctmsmis.lasGangInfo WHERE GangID = '$gangId'";
												$labour=mysqli_fetch_object(mysqli_query($con_ctmsmis,$query));
												if($labour != null){
													echo $labour->RequiredLabor;
												}else{
													echo "";
												}

											?></td>					
										</tr>
										<?php }?>
										
								</tbody>
							</table>
							
							
						</div>
					</section>
			
				</div>
			</div>	
		<!-- end: page -->
	</section>
	</div>