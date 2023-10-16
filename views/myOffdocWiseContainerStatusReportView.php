		<?php if($_POST['options']=='html'){?>
<HTML>

<BODY>

<?php }
else if($_POST['options']=='xl'){
   // $rota=str_replace('/', '-', $rot);

    header("Content-type: application/octet-stream");
    //header("Content-Disposition: attachment; filename=Container-List-$rota-Stripping.xls;");
    header("Content-Disposition: attachment; filename=Container-List-Stripping.xls;");
    header("Content-Type: application/ms-excel");
    header("Pragma: no-cache");
    header("Expires: 0");
}
//$rot=$_REQUEST['rot'];
?>
			<!-- start: page -->
			<section class="panel">
				<div class="panel-body">
					<div class="invoice">
						<header class="clearfix">
							<div class="row">
								<div class="col-sm-12 text-center mt-md mb-md">
									<div class="ib">
										 <?php if($_POST['options']=='html'){ ?>
										<img src="<?php echo ASSETS_WEB_PATH;?>fimg/cpaLogo.png" alt="Chattogram Port Authority Logo"/><br>
										<?php } ?>
										<h4 class="h4 mt-none mb-sm text-dark text-bold">Chattogram Port Authority</h4>
										<h5 align="center" class="h5 mt-none mb-sm text-dark text-bold">
											AGENT WISE PRE ADVICE CONTAINER LIST
										</h5>
									</div>
								</div>
							</div>
						</header>
						<div class="panel-body">
						<?php if($_POST['options']=='html'){ ?>
							<table class="table table-bordered table-responsive table-hover table-striped mb-none">
						<?php } else { ?>
							<table align="center" width="70%" border="1" cellpadding="0" cellspacing="0" style=" border-collapse: collapse;">
						<?php }?>
								<thead>
									<tr class="gridDark">
										<th class="text-center">SlNo</th>
										<th class="text-center">Container No</th>									
										<th class="text-center"></th>									
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
										$offDocid="";
										// $querystr="select * from (
										// select cont_id,rotation,cont_status,cont_mlo,cont_size,cont_height,agent,transOp,
										// (select sparcsn4.vsl_vessels.name from sparcsn4.vsl_vessel_visit_details
										// inner join sparcsn4.vsl_vessels on sparcsn4.vsl_vessels.gkey=sparcsn4.vsl_vessel_visit_details.vessel_gkey
										// where sparcsn4.vsl_vessel_visit_details.vvd_gkey=mis_exp_unit_preadv_req.vvd_gkey) as vsl_name,
										// (select ctmsmis.offdoc.code from ctmsmis.offdoc where ctmsmis.offdoc.id=ctmsmis.mis_exp_unit_preadv_req.transOp) as offDocid,
										// (select ctmsmis.offdoc.name from ctmsmis.offdoc where ctmsmis.offdoc.id=ctmsmis.mis_exp_unit_preadv_req.transOp) as offDocName
										// from ctmsmis.mis_exp_unit_preadv_req where agent='$login_id' and rotation='$rot'  
										//  )as tmp  order by transOp";

										$querystr="
										SELECT * FROM (
										SELECT cont_id,rotation,cont_status,cont_mlo,cont_size,cont_height,agent,transOp,vvd_gkey,
										
										(SELECT ctmsmis.offdoc.code FROM ctmsmis.offdoc WHERE ctmsmis.offdoc.id=ctmsmis.mis_exp_unit_preadv_req.transOp) AS offDocid,
										(SELECT ctmsmis.offdoc.name FROM ctmsmis.offdoc WHERE ctmsmis.offdoc.id=ctmsmis.mis_exp_unit_preadv_req.transOp) AS offDocName
										FROM ctmsmis.mis_exp_unit_preadv_req WHERE agent='$agentCode' AND rotation='$rot' 
										)AS tmp  ORDER BY transOp	";
									
										
										
										//$Trans="";
										//$cat="";
										$vvd_gkey=0;
										$query=mysqli_query($con_sparcsn4,$querystr);
										while($row=mysqli_fetch_object($query)){


										$i++;
										
										$vvd_gkey=$row->vvd_gkey;

										$v_name="SELECT vsl_vessels.name FROM vsl_vessel_visit_details
										INNER JOIN vsl_vessels ON vsl_vessels.gkey=vsl_vessel_visit_details.vessel_gkey
										WHERE vsl_vessel_visit_details.vvd_gkey='$vvd_gkey'";
										$v_name=oci_parse($con_sparcsn4_oracle,$v_name);
										oci_execute($v_name);
										$VesselName="";
										while(($resVessel = oci_fetch_object($v_name)) !=false)
										{
											$VesselName=$resVessel->NAME;
										}




										$strTrans = "select inv_unit_fcy_visit.transit_state,inv_unit.category 
										from inv_unit 
										inner join inv_unit_fcy_visit on inv_unit_fcy_visit.unit_gkey=inv_unit.gkey
										where inv_unit.id='$row->cont_id' order by inv_unit_fcy_visit.gkey";
											//echo $strTrans."<hr>";
										$resTrans = oci_parse($con_sparcsn4_oracle,$strTrans);
										$Trans="";
										$cat="";

										// while(($rowTrans = oci_fetch_object($resTrans)) !=false)
										// {
										// 	$Trans=$rowTrans-> TRANSIT_STATE;
										// 	$cat=$rowTrans->CATEGORY;
										// }

										
										if($offDocid!=$row->offDocid)
										{
											if($j>0){
											?>		
												<tr class="gradeX">
													<td align="center" colspan="2"> <b> Total Container (<?php echo $offDocid; ?>): </b> </td>
													<td align="center" colspan="8"> <b> <?php  echo $j;?> </b> </td>
												</tr>
											<?php } ?>
												<tr class="gradeX">
													<td align="center" colspan="10"> 
														<b><?php  if($row->offDocid) echo "(".$row->offDocid.") ".$row->offDocName; else echo "&nbsp;"; ?></b>
													</td>
												</tr>
											<?php 
											$j=1;
										}
										else 
										{
											$j++;
										}
										$offDocid=$row->offDocid;
											?>
									<tr class="gradeX">
										<td align="center"> <?php echo $j;?> </td>
										<td align="center"> <?php if($row->cont_id) echo $row->cont_id; else echo "&nbsp;";?> </td>
										<td align="center"><?php  echo $VesselName; ?></td>
										<td align="center"><?php if($row->rotation) echo $row->rotation; else echo "&nbsp;";?></td>
										<td align="center"><?php if($row->cont_size) echo $row->cont_size; else echo "&nbsp;";?></td>
										<td align="center"><?php if($row->cont_height) echo $row->cont_height/10; else echo "&nbsp;";?></td>
										<td align="center"><?php if($row->cont_mlo) echo $row->cont_mlo; else echo "&nbsp;";?></td>
										<td align="center"><?php if($row->cont_status) echo $row->cont_status; else echo "&nbsp;";?></td>
										<td align="center"><?php $transs =$Trans; echo $str2 = substr($transs, 4);?></td>
										<td align="center"><?php if($cat) echo $cat; else echo "&nbsp;";?></td>
									</tr>
									<?php } ?>
									<tr class="gradeX">
										<td align="center" colspan="2"> <b> Total Container : </b> </td>
										<td align="center" colspan="8"> <b> <?php  echo $j;?> </b> </td>
									</tr>
									<tr class="gradeX">
										<td align="center" colspan="2"> <b> Grand Total: </b> </td>
										<td align="center" colspan="8"> <b> <?php  echo $i;?> </b> </td>
									</tr>
								</tbody>
							</table>
						</div>
					</div>
				</div>
			</section>
			<!-- end: page -->
	</div>

