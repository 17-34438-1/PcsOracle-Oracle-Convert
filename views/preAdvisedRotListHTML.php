<section role="main" class="content-body">
	<header class="page-header">
		<h2><?php echo $title;?></h2>
	</header>

	<!-- start: page -->
		<div class="row">
			<div class="col-lg-12">
				<section class="panel">
					<div class="panel-body">
						<div class="invoice">
							<header class="clearfix">
								<div class="row">
									<div class="col-sm-12 text-center mt-md mb-md">
										<div class="ib">
											<img src="<?php echo ASSETS_WEB_PATH;?>fimg/cpaLogo.png" alt="Chattogram Port Authority Logo"/><br>
											<h4 class="h4 mt-none mb-sm text-dark text-bold">Chattogram Port Authority</h4>
											<h5 align="center" class="h5 mt-none mb-sm text-dark text-bold">TODAY'S PRE-ADVISED ROTATION LIST</h5>
										</div>
									</div>
								</div>
							</header>
							<div class="panel-body">
								<?php include("FrontEnd/dbConection.php");
								include("dbOracleConnection.php");
									if($login_id=="sazam" or $login_id=="tipai" or $login_id=="popy" or $login_id=="Shepu" or $login_id=="shopna" or 
									$login_id=="cparaselrana2018" or $login_id=="cpakamruzzaman2018"  or $login_id=="cpakamar2018" or $login_id=="cpaziauddin" or $login_id=="cpakamruzzaman2018"  or $login_id=="cpakamar2018" or $login_id=="admin" or $login_id=="cpaabsaruddin2018" or $login_id=="cpatipu2018") 
										{

											// $str = "select distinct ctmsmis.mis_exp_unit_preadv_req.vvd_gkey,rotation,sparcsn4.vsl_vessels.name as vsl_name,Y.id as agent from ctmsmis.mis_exp_unit_preadv_req 
											// inner join sparcsn4.vsl_vessel_visit_details vsldtl on vsldtl.vvd_gkey=ctmsmis.mis_exp_unit_preadv_req.vvd_gkey
											// inner join sparcsn4.vsl_vessels on sparcsn4.vsl_vessels.gkey=vsldtl.vessel_gkey
											// inner join  ( sparcsn4.ref_bizunit_scoped r  
											// left join ( sparcsn4.ref_agent_representation X  
											// left join sparcsn4.ref_bizunit_scoped Y ON X.agent_gkey=Y.gkey )               
											// ON r.gkey=X.bzu_gkey)  ON r.gkey = vsldtl.bizu_gkey
											// where preAddStat=1 and date(last_update)=date(now())";
											
											// $str = "SELECT DISTINCT ctmsmis.mis_exp_unit_preadv_req.vvd_gkey,rotation,sparcsn4.vsl_vessels.name AS vsl_name,Y.id AS agent
											// FROM ctmsmis.mis_exp_unit_preadv_req 
											// INNER JOIN sparcsn4.vsl_vessel_visit_details vsldtl ON vsldtl.vvd_gkey=ctmsmis.mis_exp_unit_preadv_req.vvd_gkey
											// INNER JOIN sparcsn4.vsl_vessels ON sparcsn4.vsl_vessels.gkey=vsldtl.vessel_gkey
											// INNER JOIN  ( sparcsn4.ref_bizunit_scoped r  
											// LEFT JOIN ( sparcsn4.ref_agent_representation X  
											// LEFT JOIN sparcsn4.ref_bizunit_scoped Y ON X.agent_gkey=Y.gkey )               
											// ON r.gkey=X.bzu_gkey)  ON r.gkey = vsldtl.bizu_gkey
											// WHERE preAddStat=1 AND last_update BETWEEN CONCAT(DATE(NOW()),' 00:00:00') AND CONCAT(DATE(NOW()),' 23:59:59')";
										
										
										$str = "SELECT DISTINCT ctmsmis.mis_exp_unit_preadv_req.vvd_gkey,rotation
										FROM ctmsmis.mis_exp_unit_preadv_req 
										WHERE preAddStat=1 AND last_update BETWEEN CONCAT(DATE(NOW()),' 00:00:00') AND CONCAT(DATE(NOW()),' 23:59:59')";



										}
									else
										{
											// $str = "select distinct ctmsmis.mis_exp_unit_preadv_req.vvd_gkey,rotation,sparcsn4.vsl_vessels.name as vsl_name,Y.id as agent from ctmsmis.mis_exp_unit_preadv_req 
											// inner join sparcsn4.vsl_vessel_visit_details vsldtl on vsldtl.vvd_gkey=ctmsmis.mis_exp_unit_preadv_req.vvd_gkey
											// inner join sparcsn4.vsl_vessels on sparcsn4.vsl_vessels.gkey=vsldtl.vessel_gkey
											// inner join  ( sparcsn4.ref_bizunit_scoped r  
											// left join ( sparcsn4.ref_agent_representation X  
											// left join sparcsn4.ref_bizunit_scoped Y ON X.agent_gkey=Y.gkey )               
											// ON r.gkey=X.bzu_gkey)  ON r.gkey = vsldtl.bizu_gkey
											// where preAddStat=1 and date(last_update)=date(now()) and agent='$login_id'";
											
											$str = "
											SELECT DISTINCT ctmsmis.mis_exp_unit_preadv_req.vvd_gkey,rotation
											FROM ctmsmis.mis_exp_unit_preadv_req 
											WHERE preAddStat=1 AND last_update BETWEEN CONCAT(DATE(NOW()),' 00:00:00') AND CONCAT(DATE(NOW()),' 23:59:59') AND agent='$login_id'";
										
										
										}


									
									$result = mysqli_query($con_sparcsn4,$str);
									$vvd_gkey="";
									while($rowTrans = mysqli_fetch_object($result))
									{
										$vvd_gkey=$rowTrans->vvd_gkey;
										$str = "
										SELECT 
										vsl_vessels.name AS vsl_name
										FROM vsl_vessel_visit_details
										INNER JOIN vsl_vessels ON vsl_vessels.gkey=vsl_vessel_visit_details.vessel_gkey
										WHERE vsl_vessel_visit_details.vvd_gkey='$vvd_gkey'
									";
									$result=oci_parse($con_sparcsn4_oracle,$str);
									$row=oci_execute($result);

									}


								?>
								<table class="table table-bordered table-responsive table-hover table-striped mb-none" id="datatable-default">
									<thead>
										<tr class="gridDark">
											<th class="text-center">Rotation</th>
											<th class="text-center">Vessel Name</th>									
											<th class="text-center">Agent</th>									
											<th class="text-center">Total Container</th>									
											<th class="text-center">To be Converted</th>									
											<th class="text-center">Will not Convert</th>									
											<th class="text-center">Action</th>										
										</tr>
									</thead>
									<tbody>
										<?php 
											while($row = mysqli_fetch_object($result))
											{ 
												// $strCont = "select cont_id from ctmsmis.mis_exp_unit_preadv_req 
															// where vvd_gkey='$row->vvd_gkey' and preAddStat=1 and date(last_update)=date(now())";
		
												$strCont = "select cont_id from ctmsmis.mis_exp_unit_preadv_req 
															where vvd_gkey='$row->vvd_gkey' and preAddStat=1 and 
															last_update BETWEEN CONCAT(DATE(NOW()),' 00:00:00') AND CONCAT(DATE(NOW()),' 23:59:59')";
												$resultCont = mysqli_query($con_sparcsn4,$strCont);
												$totCont = 0;
												$convertCont = 0;
												$noConvertCont = 0;
												
												while($rowCont = mysqli_fetch_object($resultCont))
												{
													$totCont = $totCont+1;


													// $strTrans = "select sparcsn4.inv_unit_fcy_visit.transit_state,sparcsn4.inv_unit.category 
													// from sparcsn4.inv_unit 
													// inner join sparcsn4.inv_unit_fcy_visit on sparcsn4.inv_unit_fcy_visit.unit_gkey=sparcsn4.inv_unit.gkey
													// where sparcsn4.inv_unit.id='$rowCont->cont_id' order by sparcsn4.inv_unit_fcy_visit.gkey";
												
												
													$strTrans = "   select inv_unit_fcy_visit.transit_state,inv_unit.category 
													from inv_unit 
													inner join inv_unit_fcy_visit on inv_unit_fcy_visit.unit_gkey=inv_unit.gkey
													where inv_unit.id='$rowCont->cont_id' order by inv_unit_fcy_visit.gkey";

													$resTrans=oci_parse($con_sparcsn4_oracle,$strTrans);
													$row=oci_execute($resTrans);


													// $resTrans = mysqli_query($con_sparcsn4,$strTrans);
													
													$Trans="";
													$cat="";
													
													while($rowTrans = mysqli_fetch_object($resTrans))
														{
															$Trans=$rowTrans->transit_state;
															$cat=$rowTrans->category;
														}
													if($Trans=="S40_YARD" or $Trans=="S50_ECOUT")
														$noConvertCont = $noConvertCont+1;
													else if($cat=="EXPRT" and ($Trans=="S60_LOADED" or $Trans=="S70_DEPARTED" or $Trans=="S99_RETIRED"))
														$noConvertCont = $noConvertCont+1;
													else if(($cat=="IMPRT" or $cat=="STRGE") and $Trans=="S20_INBOUND")
														$noConvertCont = $noConvertCont+1;
												}
												$convertCont = $totCont-$noConvertCont;
										?>
												<tr class="gradeX">
													<td align="center"> <?php echo $row->rotation; ?> </td>
													<td align="center"><?php echo $row->vsl_name; ?></td>
													<td align="center"><?php echo $row->agent; ?></td>
													<td align="center">
														<a href="<?php echo site_url('uploadExcel/showDetailPrevCont/'.$row->vvd_gkey.'/all'); ?>" target="_blank"><?php echo $totCont; ?></a>
													</td>
													<td align="center">
														<a href="<?php echo site_url('uploadExcel/showConverted/'.$row->vvd_gkey); ?>" target="_blank"><?php echo $convertCont; ?></a>
													</td>
													<td align="center"> 
														<a href="<?php echo site_url('uploadExcel/showNoConverted/'.$row->vvd_gkey); ?>" target="_blank"><?php echo $noConvertCont; ?></a>
													</td>
													<?php if($login_id=="sazam" or $login_id=="tipai" or $login_id=="popy" or $login_id=="Shepu" or $login_id=="shopna" or $login_id=="cparaselrana2018" or $login_id=="cpakamruzzaman2018"  or $login_id=="cpakamar2018" or $login_id=="cpaziauddin" or $login_id=="cpakamruzzaman2018"  or $login_id=="cpakamar2018" or $login_id=="admin" or $login_id=="cpaabsaruddin2018" or $login_id=="cpatipu2018"){?>
														<td align="center"> 
															<a href="<?php echo site_url('uploadExcel/updateSNXStatus/'.$row->vvd_gkey); ?>" class="login_button" style="text-decoration: none;" onclick="return myconfirm();">Done SNX</a>
														</td>
													<?php }else{?>
														<td align="center"> CPA got notification </td>
													<?php }?>
													<!--<td align="center"> <?php echo $rslt_bill_list[$i]['flag']; ?> </td>-->
												</tr>
										<?php } ?>
									</tbody>
								</table>
							</div>
						</div>
						<div class="row">
							<div class="col-sm-12 mt-md">
								<?php if($mystatus==2){ echo $body; } ?>
							</div>
						</div>
					</div>
				</section>
			</div>
		</div>
</section>

</div>

<script>
	function myconfirm()
		{
			if(confirm("Do you want done this SNX?"))
			{
				return true;
			}
			else
			{
				return false;
			}
		}
</script>
