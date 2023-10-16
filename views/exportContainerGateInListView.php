		
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
										<h5 align="center" class="h5 mt-none mb-sm text-dark text-bold">Export Container Gate In List</h5>
										<h5 align="center" class="h5 mt-none mb-sm text-dark text-bold">Rotation: <?php echo $rotation_no; ?></h5>
									</div>
								</div>
							</div>
						</header>
						<div class="panel-body">
							<table class="table table-bordered table-responsive table-hover table-striped mb-none" id="datatable-default">
								<thead>
									<tr class="gridDark">
										<th class="text-center">Sl.NO</th>
										<th class="text-center">CONTAINER NO</th>									
										<th class="text-center">SIZE</th>									
										<th class="text-center">HEIGHT</th>									
										<th class="text-center">SEAL NO</th>									
										<th class="text-center">MLO</th>									
										<th class="text-center">TYPE</th>									
										<th class="text-center">VESSEL NAME</th>									
										<th class="text-center">TIME IN</th>									
									</tr>
								</thead>
								<tbody>
									<?php 
										include('dbOracleConnection.php');
										
										$chkListQuery= "SELECT count(inv.id) chkNum											 
											FROM inv_unit inv  
											INNER JOIN inv_unit_fcy_visit ON inv_unit_fcy_visit.unit_gkey=inv.gkey
											INNER JOIN argo_carrier_visit ON argo_carrier_visit.gkey=inv_unit_fcy_visit.actual_ob_cv
											INNER JOIN vsl_vessel_visit_details ON vsl_vessel_visit_details.vvd_gkey=argo_carrier_visit.cvcvd_gkey
											INNER JOIN ref_bizunit_scoped g ON vsl_vessel_visit_details.bizu_gkey = g.gkey
											where g.id='$login_id_ship' and vsl_vessel_visit_details.ob_vyg='$rotation_no' 
											order by inv_unit_fcy_visit.time_in desc";
										$rtnChkList=oci_parse($con_sparcsn4_oracle,$chkListQuery);
										oci_execute($rtnChkList,OCI_DEFAULT);
										$rowChkList = oci_fetch_object($rtnChkList);
																					
										if($rowChkList->CHKNUM > 0)
										{
											$expQuery="SELECT inv_unit.id,substr(ref_equip_type.nominal_length,-2) siz,
												substr(ref_equip_type.nominal_height,-2)/10 AS height,inv_unit.seal_nbr1,vsl_vessels.name,
												g.id AS MLO,inv_unit.category,inv_unit.freight_kind,
												vsl_vessel_visit_details.ob_vyg AS rotation,
												inv_unit_fcy_visit.time_in											 
												FROM inv_unit
												LEFT JOIN inv_unit_fcy_visit ON inv_unit_fcy_visit.unit_gkey=inv_unit.gkey
												LEFT JOIN argo_carrier_visit ON argo_carrier_visit.gkey=inv_unit_fcy_visit.actual_ob_cv
												LEFT JOIN vsl_vessel_visit_details ON vsl_vessel_visit_details.vvd_gkey=argo_carrier_visit.cvcvd_gkey
												LEFT JOIN vsl_vessels ON vsl_vessels.gkey=vsl_vessel_visit_details.vessel_gkey
												LEFT JOIN ref_bizunit_scoped g ON inv_unit.line_op = g.gkey
												LEFT JOIN ref_equipment ON inv_unit.eq_gkey=ref_equipment.gkey
												LEFT JOIN ref_equip_type ON ref_equipment.eqtyp_gkey=ref_equip_type.gkey 
												WHERE vsl_vessel_visit_details.ob_vyg='$rotation_no' and 
												inv_unit_fcy_visit.time_in is not null  
												ORDER BY inv_unit.gkey DESC";
											$rtnExpQuery=oci_parse($con_sparcsn4_oracle,$expQuery);
											oci_execute($rtnExpQuery,OCI_DEFAULT);
											$i=0;	
											while (($rowExpQuery = oci_fetch_object($rtnExpQuery)) != false){
											$i++;
									?>
										<tr class="gradeX">
											<td align="center"> <?php  echo $i;?> </td>
											<td align="center"><?php if($rowExpQuery->ID) echo $rowExpQuery->ID; else echo "&nbsp;";?></td>
											<td align="center"><?php if($rowExpQuery->SIZ) echo $rowExpQuery->SIZ; else echo "&nbsp;";?></td>
											<td align="center">
												<?php if($rowExpQuery->HEIGHT) echo $rowExpQuery->HEIGHT; else echo "&nbsp;";?>
											</td>
											<td align="center">
												<?php if($rowExpQuery->SEAL_NBR1) echo $rowExpQuery->SEAL_NBR1; else echo "&nbsp;";?>
											</td>
											<td align="center"><?php if($rowExpQuery->MLO) echo $rowExpQuery->MLO; else echo "&nbsp;";?></td>
											<td align="center">
												<?php if($rowExpQuery->FREIGHT_KIND) echo $rowExpQuery->FREIGHT_KIND; else echo "&nbsp;";?>
											</td>
											<td align="center">
												<?php if($rowExpQuery->NAME) echo $rowExpQuery->NAME; else echo "&nbsp;";?>
											</td>
											<td align="center">
												<?php if($rowExpQuery->TIME_IN) echo $rowExpQuery->TIME_IN; else echo "&nbsp;";?>
											</td>
										</tr>
									<?php } }  ?>
								</tbody>
							</table>
						</div>
					</div>
					<div class="text-center mr-lg">
						<form class="form-horizontal form-bordered" method="POST" 
							action="<?php echo site_url('Report/exportContainerGateInListPrint') ?>" target="_blank">
							
							<input type="hidden" name="rotation_no" id="rotation_no" value="<?php echo $rotation_no?>" />
							<input type="hidden" name="login_id_ship" id="login_id_ship" value="<?php echo $login_id_ship?>" />
							<input type="hidden" name="search_format" id="search_format" value="<?php echo $search_format?>" />							
							<button type="submit" class="mb-xs mt-xs mr-xs btn btn-success"><i class="fa fa-print"></i> Print</button>
						</form>
					</div>
				</div>
			</section>
			<!-- end: page -->
	</div>
<?php oci_close($con_sparcsn4_oracle); ?>
