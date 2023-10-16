		
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
										<h5 align="center" class="h5 mt-none mb-sm text-dark text-bold">Agent Wise Export Container  Report</h5>
									</div>
								</div>
							</div>
						</header>
						<div class="panel-body">
							<table class="table table-bordered table-responsive table-hover table-striped mb-none" id="datatable-default">
								<thead>
									<tr class="gridDark">
										<th class="text-center">SlNo</th>
										<th class="text-center">Rotation</th>									
										<th class="text-center">Vessel Name</th>									
										<th class="text-center">Phase</th>									
										<th class="text-center">ATA</th>									
										<th class="text-center">ATD</th>									
										<th class="text-center">Total Exp Unit</th>									
										<th class="text-center">Agent Wise PreAdvice Details</th>									
									</tr>
								</thead>
								<tbody>
									<?php 
										include('FrontEnd/mydbPConnectionctms.php');
										include("dbOracleConnection.php");	
										$i=0;
										$j=0;
										

										$querystr="SELECT DISTINCT vvd_gkey,rotation,cont_id,agent
										FROM ctmsmis.mis_exp_unit_preadv_req 
										WHERE agent='$login_id' ORDER BY last_update DESC LIMIT 20	
											";
										$query=mysqli_query($con_ctmsmis,$querystr);

										$vvd_gkey=0;
										while($row=mysqli_fetch_object($query)){
										$i++;
										$vvd_gkey=$row->vvd_gkey;

										$srtVessel="SELECT vsl_vessels.name FROM vsl_vessel_visit_details
										INNER JOIN vsl_vessels ON vsl_vessels.gkey=vsl_vessel_visit_details.vessel_gkey
										WHERE vsl_vessel_visit_details.vvd_gkey='$vvd_gkey'";
										$resVessel=oci_parse($con_sparcsn4_oracle,$srtVessel);
										oci_execute($resVessel);
										$VesselName="";
										while(($resVessel = oci_fetch_object($resVessel)) !=false)
										{
											$VesselName=$resVessel->NAME;
										}


										$srtPhase="SELECT argo_carrier_visit.phase FROM argo_carrier_visit
										WHERE argo_carrier_visit.cvcvd_gkey='$vvd_gkey'";
										$resPhase=oci_parse($con_sparcsn4_oracle,$srtPhase);
										oci_execute($resPhase);
										
										
										$srt_ata="SELECT ata FROM argo_carrier_visit WHERE cvcvd_gkey='$vvd_gkey'";
										$resAta=oci_parse($con_sparcsn4_oracle,$srt_ata);
										oci_execute($resAta);

									
										$srt_atd="SELECT atd FROM argo_carrier_visit WHERE cvcvd_gkey='$vvd_gkey'";
										$resAtd=oci_parse($con_sparcsn4_oracle,$srt_atd);
										oci_execute($resAtd);
										
									



									?>
									<tr class="gradeX">
										<td align="center"> <?php  echo $i;?> </td>
										<td align="center"> <?php if($row->rotation) echo $row->rotation; else echo "&nbsp;";?> </td>
										<td align="center"> <?php if($row_vsl_name->NAME) echo $row_vsl_name->NAME; else echo "&nbsp;";?> </td>
										<td align="center"> <?php echo substr($row_phase->PHASE,2); ?> </td>
										<td align="center"> <?php if($rowAta->ATA) echo $rowAta->ATA; else echo "&nbsp;";?> </td>
										<td align="center"> <?php if($rowAtd->ATD) echo $rowAtd->ATD; else echo "&nbsp;";?> </td>
										<td align="center"> <?php if($row->totexpcont) echo $row->totexpcont; else echo "&nbsp;";?> </td>
										<td align="center"> 
											<a href="<?php echo site_url('report/myPreAdviceContainerDetail/'.$row->rotation.'/');?>" target="_blank">
												<button type="button" class="btn btn-primary btn-sm"><b><i class="fa fa-eye"></i> View</b></button>
											</a> 
										</td>
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

