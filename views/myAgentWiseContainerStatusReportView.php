		
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
										<h5 align="center" class="h5 mt-none mb-sm text-dark text-bold">
											AGENT WISE PRE ADVICE CONTAINER LIST
										</h5>
									</div>
								</div>
							</div>
						</header>
						<div class="panel-body">
							<table class="table table-bordered table-responsive table-hover table-striped mb-none"  id="datatable-default">
								<thead>
									<tr class="gridDark">
										<th class="text-center">SlNo</th>
										<th class="text-center">Container No</th>									
										<th class="text-center">Vessel Name</th>									
										<th class="text-center">Rotation</th>									
										<th class="text-center">Size</th>									
										<th class="text-center">Height</th>									
										<th class="text-center">MLO</th>									
										<th class="text-center">Status</th>									
										<th class="text-center">State</th>									
										<th class="text-center">Category</th>										
									</tr>
								</thead>
								<tbody>
									<?php 
										include('FrontEnd/dbConection.php');
										include("dbOracleConnection.php");	
										$i=0;
										$j=0;
										if($serch_by=="rot")
										{
											// $querystr="select * from (
											// select cont_id,rotation,cont_status,cont_mlo,cont_size,cont_height,agent,
											// (select sparcsn4.vsl_vessels.name from sparcsn4.vsl_vessel_visit_details
											// inner join sparcsn4.vsl_vessels on sparcsn4.vsl_vessels.gkey=sparcsn4.vsl_vessel_visit_details.vessel_gkey
											// where sparcsn4.vsl_vessel_visit_details.vvd_gkey=mis_exp_unit_preadv_req.vvd_gkey) as vsl_name
											// from ctmsmis.mis_exp_unit_preadv_req where agent='$login_id' and rotation='$serch_value'
											//  )as tmp order by cont_id";


											$querystr="SELECT * FROM (
												SELECT cont_id,rotation,cont_status,cont_mlo,cont_size,cont_height,agent,vvd_gkey
												
												FROM ctmsmis.mis_exp_unit_preadv_req WHERE agent='$agentCode' AND rotation='$serch_value'
												)AS tmp ORDER BY cont_id";



										}
										
										else if($serch_by=="cont")
										{
											// $querystr="select * from (
											// select cont_id,rotation,cont_status,cont_mlo,cont_size,cont_height,agent,
											// (select sparcsn4.vsl_vessels.name from sparcsn4.vsl_vessel_visit_details
											// inner join sparcsn4.vsl_vessels on sparcsn4.vsl_vessels.gkey=sparcsn4.vsl_vessel_visit_details.vessel_gkey
											// where sparcsn4.vsl_vessel_visit_details.vvd_gkey=mis_exp_unit_preadv_req.vvd_gkey) as vsl_name
											// from ctmsmis.mis_exp_unit_preadv_req where agent='$login_id' and cont_id='$serch_value'  
											//  )as tmp";


											$querystr="SELECT * FROM (
												SELECT cont_id,rotation,cont_status,cont_mlo,cont_size,cont_height,agent,vvd_gkey
												
												FROM ctmsmis.mis_exp_unit_preadv_req WHERE agent='$login_id' AND rotation='$serch_value'
												)AS tmp ORDER BY cont_id";
										}
										else if($serch_by=="mlo")
										{


											
											// $querystr="select * from (
											// select cont_id,rotation,cont_status,cont_mlo,cont_size,cont_height,agent,
											// (select sparcsn4.vsl_vessels.name from sparcsn4.vsl_vessel_visit_details
											// inner join sparcsn4.vsl_vessels on sparcsn4.vsl_vessels.gkey=sparcsn4.vsl_vessel_visit_details.vessel_gkey
											// where sparcsn4.vsl_vessel_visit_details.vvd_gkey=mis_exp_unit_preadv_req.vvd_gkey) as vsl_name
											// from ctmsmis.mis_exp_unit_preadv_req where agent='$login_id' and cont_mlo='$serch_value' 
											//  )as tmp";


											$querystr="SELECT * FROM (
												SELECT cont_id,rotation,cont_status,cont_mlo,cont_size,cont_height,agent,vvd_gkey
												
												FROM ctmsmis.mis_exp_unit_preadv_req WHERE agent='$login_id' AND rotation='$serch_value'
												)AS tmp ORDER BY cont_id";

										}
										else if($serch_by=="pod")
										{
											// $querystr="select * from (
											// select cont_id,rotation,cont_status,cont_mlo,cont_size,cont_height,agent,
											// (select sparcsn4.vsl_vessels.name from sparcsn4.vsl_vessel_visit_details
											// inner join sparcsn4.vsl_vessels on sparcsn4.vsl_vessels.gkey=sparcsn4.vsl_vessel_visit_details.vessel_gkey
											// where sparcsn4.vsl_vessel_visit_details.vvd_gkey=mis_exp_unit_preadv_req.vvd_gkey) as vsl_name
											// from ctmsmis.mis_exp_unit_preadv_req where agent='$login_id' and pod='$serch_combo')as tmp ";


											$querystr="SELECT * FROM (
												SELECT cont_id,rotation,cont_status,cont_mlo,cont_size,cont_height,agent,vvd_gkey
												
												FROM ctmsmis.mis_exp_unit_preadv_req WHERE agent='$login_id' AND rotation='$serch_combo'
												)AS tmp ORDER BY cont_id";

										}
										else
										{
											// $querystr="select * from (
											// select cont_id,rotation,cont_status,cont_mlo,cont_size,cont_height,agent,
											// (select sparcsn4.vsl_vessels.name from sparcsn4.vsl_vessel_visit_details
											// inner join sparcsn4.vsl_vessels on sparcsn4.vsl_vessels.gkey=sparcsn4.vsl_vessel_visit_details.vessel_gkey
											// where sparcsn4.vsl_vessel_visit_details.vvd_gkey=mis_exp_unit_preadv_req.vvd_gkey) as vsl_name
											// from ctmsmis.mis_exp_unit_preadv_req where agent='$login_id' and transOp='$serch_combo')as tmp";

											$querystr="SELECT * FROM (
												SELECT cont_id,rotation,cont_status,cont_mlo,cont_size,cont_height,agent,vvd_gkey
												FROM ctmsmis.mis_exp_unit_preadv_req WHERE agent='$login_id' AND rotation='$serch_combo'
												)AS tmp ORDER BY cont_id";


										}
										
										
										$query=mysqli_query($con_sparcsn4,$querystr);



									

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




										$strTrans = "select inv_unit_fcy_visit.transit_state,inv_unit.category 
										from inv_unit 
										inner inv_unit_fcy_visit on inv_unit_fcy_visit.unit_gkey=inv_unit.gkey
										where inv_unit.id='$row->cont_id' order by inv_unit_fcy_visit.gkey";
										$resTrans=oci_parse($con_sparcsn4_oracle,$strTrans);
										oci_execute($resTrans);
										$Trans="";
										$cat="";
										while(($rowTrans = oci_fetch_object($resTrans)) !=false)
										{
											$Trans=$rowTrans-> TRANSIT_STATE;
											$cat=$rowTrans->CATEGORY;
										}




									?>
									<tr class="gradeX">
										<td align="center"> <?php echo $i;?> </td>
										<td align="center"> <?php if($row->cont_id) echo $row->cont_id; else echo "&nbsp;";?> </td>
										<td align="center"> <?php echo $VesselName;?> </td>
										<td align="center"> <?php if($row->rotation) echo $row->rotation; else echo "&nbsp;";?> </td>
										<td align="center"> <?php if($row->cont_size) echo $row->cont_size; else echo "&nbsp;";?> </td>
										<td align="center"> <?php if($row->cont_height) echo $row->cont_height; else echo "&nbsp;";?> </td>
										<td align="center"> <?php if($row->cont_mlo) echo $row->cont_mlo; else echo "&nbsp;";?> </td>
										<td align="center"> <?php if($row->cont_status) echo $row->cont_status; else echo "&nbsp;";?> </td>
										<td align="center"> <?php $transs =$Trans; echo $str2 = substr($transs, 4);?> </td>
										<td align="center"> <?php if($cat) echo $cat; else echo "&nbsp;";?> </td>
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

