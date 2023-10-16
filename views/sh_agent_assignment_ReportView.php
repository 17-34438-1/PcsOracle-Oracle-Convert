<?php if($_POST['options']=='html'){?>
<HTML>
	<HEAD>
		<TITLE>ASSIGNMENT REPORT VIEW</TITLE>
		<LINK href="../css/report.css" type=text/css rel=stylesheet>
	    <style type="text/css">
<!--
.style1 {font-size: 12px}
-->
        </style>
</HEAD>
<BODY>

	<?php } 
	else if($_POST['options']=='xl'){
		header("Content-type: application/octet-stream");
		header("Content-Disposition: attachment; filename=ASSIGNMENT_REPORT_VIEW.xls;");
		header("Content-Type: application/ms-excel");
		header("Pragma: no-cache");
		header("Expires: 0");

	}
	?>	
	<?php if($_POST['options']=='html') {?>
	<?php 
	include("FrontEnd/dbConection.php");
	include("dbOracleConnection.php");
	$col = "b.flex_date01";
	$head = "Assignment Date Wise Import Container List";
	
	// previously shp_id='login_id'
	?>
	
	
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
										<h5 align="center" class="h5 mt-none mb-sm text-dark text-bold">YARD WISE PROPOSED EMPTY AND EMPTY CONTAINER REPORT</h5>
										<h5 align="center" class="h5 mt-none mb-sm text-dark text-bold"><?php echo $head; ?></h5>
										<h5 align="center" class="h5 mt-none mb-sm text-dark text-bold"><?php echo $fromdate; ?></h5>
									</div>
								</div>
							</div>
						</header>
						<div class="panel-body">
							<table class="table table-bordered table-responsive table-hover table-striped mb-none">
								<thead>
									<tr class="gridDark">
										<th class="text-center">SlNo</th>
										<th class="text-center">Container No.</th>									
										<th class="text-center">Rotation No.</th>									
										<th class="text-center">Type</th>									
										<th class="text-center">MLO</th>									
										<th class="text-center">Status</th>									
										<th class="text-center">Assignment Type</th>									
										<th class="text-center">C and F</th>									
										<th class="text-center">Weight</th>									
										<th class="text-center">Current Position</th>									
										<th class="text-center">Assignment date</th>									
										<th colspan="2" class="text-center">Delivery/Empty Date</th>									
										<th class="text-center">Stripped By</th>									
										<th class="text-center">Remarks</th>											
									</tr>
								</thead>
								<tbody>
									<?php
									//  AND tmp_oracle_assignment.mfdch_value NOT IN ('CANCEL','OCD','APPCUS','APPOTH','APPREF')
									//  ORDER BY tmp_oracle_assignment.assignmentDate DESC

									// $mainSql="SELECT * FROM ctmsmis.tmp_vcms_assignment 
									// WHERE DATE(tmp_vcms_assignment.assignmentDate)='$fromdate' AND tmp_vcms_assignment.mfdch_value NOT IN ('CANCEL','OCD','APPCUS','APPOTH','APPREF')
									// ORDER BY tmp_vcms_assignment.assignmentDate DESC";
									$mainSql="SELECT * FROM ctmsmis.tmp_oracle_assignment 
									WHERE DATE(tmp_oracle_assignment.assignmentDate)='$fromdate' AND tmp_oracle_assignment.mfdch_value NOT IN ('CANCEL','OCD','APPCUS','APPOTH','APPREF')
									ORDER BY tmp_oracle_assignment.assignmentDate DESC
									";
									$mainQuery=mysqli_query($con_sparcsn4,$mainSql);
									$unit_gkey=0;
									$mfdch_desc="";
									$yard_no = "";
									while($mainRow=mysqli_fetch_object($mainQuery)){
									
									$mfdch_desc=$mainRow->mfdch_desc;
									 $unit_gkey=$mainRow->unit_gkey;

										 $ass_query="SELECT Distinct tmp.* FROM (
											SELECT a.id AS cont_no,a.gkey,
											
											b.flex_string10 AS rot_no,
											b.time_in AS dischargetime,
											b.time_out AS delivery,
											g.id AS mlo,
											k.name as cf,
																								
											a.freight_kind AS statu,
											a.goods_and_ctr_wt_kg AS weight,
											
											ref_equip_type.id AS iso_code,
											b.flex_date01 AS assignmentdate,
											NVL((SELECT SUBSTR(srv_event_field_changes.new_value,7)
											FROM srv_event
											INNER JOIN srv_event_field_changes ON srv_event_field_changes.event_gkey=srv_event.gkey
											WHERE srv_event.applied_to_gkey=a.gkey  AND srv_event.event_type_gkey IN(31363,31143,31447) AND srv_event_field_changes.new_value IS NOT NULL AND srv_event_field_changes.new_value !='' AND srv_event_field_changes.new_value !='Y-CGP-.' AND srv_event.gkey<(SELECT srv_event.gkey FROM srv_event
											INNER JOIN srv_event_field_changes ON srv_event_field_changes.event_gkey=srv_event.gkey
											WHERE srv_event.event_type_gkey=31426 AND srv_event.applied_to_gkey=a.gkey AND metafield_id='unitFlexString01' AND new_value IS NOT NULL ORDER BY srv_event_field_changes.gkey DESC fetch first 1 rows only) ORDER BY srv_event.gkey DESC fetch first 1 rows only),(SELECT substr(srv_event_field_changes.new_value,7)
											FROM srv_event
											INNER JOIN srv_event_field_changes ON srv_event_field_changes.event_gkey=srv_event.gkey
											WHERE srv_event.applied_to_gkey=a.gkey  AND srv_event.event_type_gkey IN(31363,31143,31447) ORDER BY srv_event_field_changes.gkey DESC fetch first 1 rows only)) AS carrentPosition,
											(SELECT creator FROM srv_event WHERE applied_to_gkey=a.gkey AND event_type_gkey=31473 ORDER BY gkey DESC fetch first 1 rows only) as stripped_by,
											(SELECT srv_event.created FROM  srv_event 
											INNER JOIN srv_event_field_changes ON srv_event_field_changes.event_gkey=srv_event.gkey
											WHERE applied_to_gkey=a.gkey AND event_type_gkey=31426 AND srv_event_field_changes.new_value='E' fetch first 1 rows only) AS proEmtyDate,
										   
											
											 (Case when UPPER (a.flex_string15) like  '%STAY%' then '1'
											 
											 else '0'
											 End) as stay ,
											Y.id AS shp_id
											
											
											FROM inv_unit a
											INNER JOIN inv_unit_fcy_visit b ON b.unit_gkey=a.gkey
											INNER JOIN argo_carrier_visit   ON argo_carrier_visit.gkey= b.actual_ib_cv
											INNER JOIN vsl_vessel_visit_details ON argo_carrier_visit.cvcvd_gkey=vsl_vessel_visit_details.vvd_gkey
											INNER JOIN ref_equipment ON ref_equipment.gkey=a.eq_gkey
											INNER JOIN ref_equip_type ON ref_equip_type.gkey=ref_equipment.eqtyp_gkey 
											INNER JOIN  ( ref_bizunit_scoped g        
											LEFT JOIN ( ref_agent_representation X        
											LEFT JOIN ref_bizunit_scoped Y ON X.agent_gkey=Y.gkey )  ON g.gkey=X.bzu_gkey        
											)  ON g.gkey = a.line_op													
											
											INNER JOIN
											inv_goods j ON j.gkey = a.goods
											LEFT JOIN
											ref_bizunit_scoped k ON k.gkey = j.shipper_bzu
											where a.gkey='$unit_gkey'
											)  tmp WHERE  shp_id='SAS' " ;

									 
										
										$query = oci_parse($con_sparcsn4_oracle,$ass_query);
										oci_execute($query);
										$i=0;
										$j=0;
										$j20=0;
										$j40=0;
										$a20 = 0;
										$a40 = 0;
										$b20 = 0;
										$a40 = 0;
										$c20 = 0;
										$a40 = 0;
										$allCont="";
										$yard="";
										$shift="";
										$tot20 = 0;
										$tot40 = 0;
										$stayed=0;
								include("FrontEnd/mydbPConnection.php");
								$carrentPosition="";
								$gkey="";
							
								while(($row=oci_fetch_object($query))!=false){
											
									echo $gkey=$row->GKEY;
									
								 $carrentPosition=$row->CARRENTPOSITION;
								}

									}

									while($mainR=mysqli_fetch_object($mainQ)){									
								
								

										while(($row=oci_fetch_object($query))!=false){
											
											echo $gkey=$row->GKEY;
											
										 $carrentPosition=$row->CARRENTPOSITION;
				

											$lastUpdateStr="SELECT ctmsmis.mis_exp_unit_load_failed.last_update
											FROM ctmsmis.mis_exp_unit_load_failed WHERE ctmsmis.mis_exp_unit_load_failed.gkey='$gkey'";
											$lastUpdateQuery=mysqli_query($con_sparcsn4,$lastUpdateStr);
											$lastUpdate="";
											while($lastUpdateRow=mysqli_fetch_object($lastUpdateQuery)){
												$lastUpdate=$lastUpdateRow->last_update;
											}

											$userIDStr="SELECT ctmsmis.mis_exp_unit_load_failed.user_id
											FROM ctmsmis.mis_exp_unit_load_failed WHERE ctmsmis.mis_exp_unit_load_failed.gkey='$gkey'";
											$userIDStrQuery=mysqli_query($con_sparcsn4,$userIDStr);
											$userId="";
											while($userIdRow=mysqli_fetch_object($userIDStrQuery)){
												$userId=$userIdRow->user_id;
											}

											$yardStr="SELECT ctmsmis.cont_yard('$carrentPosition') AS Yard_No";
											$yardQuery=mysqli_query($con_sparcsn4,$yardStr);
											$yardNo="";
											while($yardRow=mysqli_fetch_object($yardQuery)){
												$yardNo=$yardRow->Yard_No;
											}

											$blockStr="SELECT ctmsmis.cont_block('$carrentPosition','$yardNo') AS Block_No";
											$blockQuery=mysqli_query($con_sparcsn4,$blockStr);
											$blockNo="";
											while($blockRow=mysqli_fetch_object($blockQuery)){
												$blockNo=$blockRow->Block_No;
											}

											if($numRows >0 && $yardNo!=NULL ){




											$i++;
											$stayed=$stayed+$row->STAY;
											
											if($i==$numRows)
												$allCont .=$row->CONT_NO ;
											else
												$allCont .=$row->CONT_NO.", ";
											
											$sqlIsoCode="select cont_iso_type from igm_detail_container where cont_number='$row->CONT_NO'";
											$sqlIsoCode=mysqli_query($con_cchaportdb,$sqlIsoCode);
											$rtnIsoCode=mysqli_fetch_object($sqlIsoCode);
											$iso=$rtnIsoCode->cont_iso_type;
											if(substr($iso,0,1)==2)
												$j20=$j20+1;
											else
												$j40=$j40+1;
											if(substr($iso,0,1)==2)
											{
												if($row->SHIFT=="Shift A")
													$a20 = $a20+1;
												if($row->SHIFT=="Shift B")
													$b20 = $b20+1;
												if($row->SHIFT=="Shift C")
													$c20 = $c20+1;
											}
											else
											{
												if($row->SHIFT=="Shift A")
													$a40 = $a40+1;
												if($row->SHIFT=="Shift B")
													$b40 = $b40+1;
												if($row->SHIFT=="Shift C")
													$c40 = $c40+1;
											}
											if($shift==$row->SHIFT or $i==1)
											{
												if(substr($iso,0,1)==2)
													$tot20 = $tot20+1;
												else 
													$tot40 = $tot40+1;
											}
											if($yard!=$yardNo)
											{
												$yard=$yardNo;
												if($i!=1)
												{
												?>
												<tr>
													<td colspan="15"><b><?php  echo "Total 20'=>".$tot20." & 40'=>".$tot40;?></b></td>
												</tr>
												<?php
													if(substr($iso,0,1)==2)
													{
														$tot20 = 1;
														$tot40 = 0;
													}
													else
													{
														$tot20 = 0;
														$tot40 = 1;
													}
												}
												?>
												<tr>
													<td colspan="15"><b><?php  echo $yardNo;?></b></td>
												</tr>
												<?php
												$i=1;
												}?>
												<?php
												if($shift!=$row->SHIFT)
													{	
														$shift=$row->SHIFT;		
														if($i!=1)
														{
															if(substr($iso,0,1)==2)
															{
																$tot20 = $tot20;
															}
															else
															{
																$tot40 = $tot40;
															}
														?>
														<tr>
															<td colspan="15"><b><?php  echo "Total 20'=>".$tot20." & 40'=>".$tot40;?></b></td>
														</tr>
														<?php
															if(substr($iso,0,1)==2)
															{
																$tot20 = 1;
																$tot40 = 0;
															}
															else
															{
																$tot20 = 0;
																$tot40 = 1;
															}
														}
														?>
														<tr>
															<td colspan="15"><b><?php  echo $row->SHIFT;?></b></td>
														</tr>	
														<?php	
														$i=1;
													}
													$shift=$row->SHIFT;	
													?>
									<tr class="gradeX">
										<td align="center"> <?php  echo $i;?> </td>
										<td align="center"><?php if($row->CONT_NO) echo $row->CONT_NO; else echo "&nbsp;";?></td>
										<td align="center"><?php if($row->ROT_NO ) echo $row->ROT_NO; else echo "&nbsp;";?></td>
										<td align="center"><?php if($iso) echo $iso; else echo "&nbsp;";?></td>
										<td align="center"><?php if($row->MLO) echo $row->MLO; else echo "&nbsp;";?></td>
										<td align="center"><?php if($row->STATU) echo $row->STATU; else echo "&nbsp;";?></td>
										<td align="center"><?php if($mainRow->mfdch_desc) echo $row->mfdch_desc; else echo "&nbsp;";?></td>
										<td align="center"><?php if($row->CF ) echo $row->CF; else echo "&nbsp;";?></td>
										<td align="center"><?php if($row->WEIGHT) echo $row->WEIGHT; else echo "&nbsp;";?></td>
										<td align="center"><?php if($row->CARRENTPOSITION) echo $row->CARRENTPOSITION; else echo "&nbsp;";?></td>
										<td align="center"><?php if($mainRow->assignmentdate) echo $mainRow->assignmentdate; else echo "&nbsp;";?></td>
										<td colspan="2" align="center"><?php if($row->DELIVERY) echo $row->DELIVERY; else echo "&nbsp;";?></td>
										<td align="center"><?php if($row->STRIPPED_BY) echo $row->STRIPPED_BY; else echo "&nbsp;";?></td>
										<td align="center"><?php if (($lastUpdate!="") or  ($lastUpdate!=null)) echo "Tried to Exp. Load:".$lastUpdate." by ".$userId; else echo "&nbsp;";?></td>
									</tr>
									<?php } }
								
								
								} ?>
									<tr class="gradeX">
										<td colspan="15" align="center"> <?php  echo "Total 20'=>".$tot20." & 40'=>".$tot40;?> </td>
									</tr>
									<?php if($yard_no=="GCB") { ?>
									<tr class="gradeX">
										<td colspan="15" align="center"> <?php echo $allCont;?> </td>
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
	
	<?php } else { ?>
		<?php 
	include("FrontEnd/dbConection.php");
	include("dbOracleConnection.php");
	
	$col = "b.flex_date01";
	$head = "Assignment Date Wise Import Container List";
	?>
	
	
			<!-- start: page -->
			
			
							<table width="100%" border ='1' cellpadding='0' cellspacing='0'>
								<tr bgcolor="#ffffff" align="center" height="100px">
									<td colspan="13" align="center">
										<table border=0 width="100%">
											<tr align="center">
												<img src="<?php echo ASSETS_WEB_PATH;?>fimg/cpaLogo.png" alt="Chattogram Port Authority Logo"/><br>
											</tr>
											<tr align="center">
												<td colspan="12"><font size="4"><b><u><?php echo $head; ?></u></b></font></td>
											</tr>
											<tr align="center">
												<td colspan="12"><font size="4"><b>DATE : <?php echo $fromdate; ?></b></font></td>
											</tr>
										</table>
									</td>
								</tr>
								<tr bgcolor="#ffffff" align="center" height="25px">
									<td colspan="15" align="center"></td>
								</tr>
							</table>
						
							<table width="100%" border ='1' cellpadding='0' cellspacing='0'>
								<tr  align="center">
									<td style="border-width:3px;border-style: double;"><b>SlNo.</b></td>
									<td style="border-width:3px;border-style: double;"><b>Container No.</b></td>
									<td style="border-width:3px;border-style: double;"><b>Rotation No.</b></td>
									<td style="border-width:3px;border-style: double;"><b>Type</b></td>
									<td style="border-width:3px;border-style: double;"><b>MLO</b></td>
									<td style="border-width:3px;border-style: double;"><b>Status</b></td>	
									<td style="border-width:3px;border-style: double;"><b>Assignment Type</b></td>		
									<td style="border-width:3px;border-style: double;"><b>C&F</b></td>		
									<td style="border-width:3px;border-style: double;"><b>Weight</b></td>
									<td style="border-width:3px;border-style: double;"><b>Current Position</b></td>
									<!--td style="border-width:3px;border-style: double;"><b>Discharge date</b></td-->
									<td style="border-width:3px;border-style: double;"><b>Assignment date</b></td>
									<td style="border-width:3px;border-style: double;"><b>Delivery/Empty Date</b></td>
									<td style="border-width:3px;border-style: double;"><b>Stripped By</b></td>
									<td style="border-width:3px;border-style: double;"><b>Remarks</b></td>
									
									
								</tr>

								<?php
								//echo $type;
								$yard_no = "";
								$ass_query="SELECT Distinct tmp.*
							   FROM (
							   SELECT a.id AS cont_no,a.gkey,
							   (SELECT ref_equip_type.id FROM inv_unit
							   INNER JOIN ref_equipment ON ref_equipment.gkey=inv_unit.eq_gkey
							   INNER JOIN ref_equip_type ON ref_equip_type.gkey=ref_equipment.eqtyp_gkey 
							   INNER JOIN inv_unit_fcy_visit ON inv_unit_fcy_visit.unit_gkey=inv_unit.gkey
							   WHERE inv_unit_fcy_visit.unit_gkey=a.gkey fetch FIRST 1 rows only) AS iso_code,
							   b.flex_string10 AS rot_no,
							   b.time_in AS dischargetime,
							   b.time_out AS delivery,
							   g.id AS mlo,
							   k.name as cf,
							   config_metafield_lov.mfdch_desc,
							   a.freight_kind AS statu,
							   a.goods_and_ctr_wt_kg AS weight,
							   
							   NVL((SELECT SUBSTR(srv_event_field_changes.new_value,7)
							   FROM srv_event
							   INNER JOIN srv_event_field_changes ON srv_event_field_changes.event_gkey=srv_event.gkey
							   WHERE srv_event.applied_to_gkey=a.gkey  AND srv_event.event_type_gkey IN(18,13,16) AND srv_event_field_changes.new_value IS NOT NULL AND srv_event_field_changes.new_value !='' AND srv_event_field_changes.new_value !='Y-CGP-.' AND srv_event.gkey<(SELECT srv_event.gkey FROM srv_event
							   INNER JOIN srv_event_field_changes ON srv_event_field_changes.event_gkey=srv_event.gkey
							   WHERE srv_event.event_type_gkey=4 AND srv_event.applied_to_gkey=a.gkey AND metafield_id='unitFlexString01' AND new_value IS NOT NULL ORDER BY srv_event_field_changes.gkey DESC fetch first 1 rows only) ORDER BY srv_event.gkey DESC fetch first 1 rows only),(SELECT substr(srv_event_field_changes.new_value,7)
							   FROM srv_event
							   INNER JOIN srv_event_field_changes ON srv_event_field_changes.event_gkey=srv_event.gkey
							   WHERE srv_event.applied_to_gkey=a.gkey  AND srv_event.event_type_gkey IN(18,13,16) ORDER BY srv_event_field_changes.gkey DESC fetch first 1 rows only)) AS carrentPosition,
							   
							   (SELECT creator FROM srv_event WHERE applied_to_gkey=a.gkey AND event_type_gkey=30 ORDER BY gkey DESC fetch first 1 rows only ) as stripped_by,
							   
							   (SELECT srv_event.created FROM  srv_event 
							   INNER JOIN srv_event_field_changes ON srv_event_field_changes.event_gkey=srv_event.gkey
							   WHERE applied_to_gkey=a.gkey AND event_type_gkey=4 AND srv_event_field_changes.new_value='E' fetch first 1 rows only ) AS proEmtyDate,
							   b.flex_date01 AS assignmentdate, 
								(Case when UPPER (a.flex_string15) like  '%STAY%' then '1'
								
								else '0'
								End) as stay ,
							   Y.id AS shp_id
							   
							   FROM inv_unit a
							   INNER JOIN inv_unit_fcy_visit b ON b.unit_gkey=a.gkey
							   INNER JOIN  ( ref_bizunit_scoped g        
							   LEFT JOIN ( ref_agent_representation X        
							   LEFT JOIN ref_bizunit_scoped Y ON X.agent_gkey=Y.gkey )  ON g.gkey=X.bzu_gkey        
							   )  ON g.gkey = a.line_op
							   INNER JOIN config_metafield_lov ON a.flex_string01=config_metafield_lov.mfdch_value
							   
							   INNER JOIN
								   inv_goods j ON j.gkey = a.goods
							   LEFT JOIN
								   ref_bizunit_scoped k ON k.gkey = j.consignee_bzu
							   WHERE to_char(b.flex_date01,'yyyy-mm-dd')='$fromdate' AND config_metafield_lov.mfdch_value NOT IN ('CANCEL','OCD','APPCUS','APPOTH','APPREF')
							   ) tmp WHERE shp_id='$agentCode'";
							    
											
											// previously shp_id='login_id'
								//echo $ass_query;
								//return;
								$numRows=0;
								$query1 = oci_parse($con_sparcsn4_oracle,$ass_query);
								oci_execute($query1);
								$results1=array();
								$numRows =oci_fetch_all($query1, $results1, null, null, OCI_FETCHSTATEMENT_BY_ROW);
								oci_free_statement($query1);
								$query1 = oci_parse($con_sparcsn4_oracle,$ass_query);
								oci_execute($query1);

								

								$i=0;
								$j=0;
								$j20=0;
								$j40=0;
								$a20 = 0;
								$a40 = 0;
								$b20 = 0;
								$a40 = 0;
								$c20 = 0;
								$a40 = 0;
								$allCont="";
								$yard="";
								$shift="";
								$tot20 = 0;
								$tot40 = 0;
								$stayed=0;
								include("FrontEnd/mydbPConnection.php");
								$carrentPosition="";
								$gkey="";
								while(($row=oci_fetch_object($query1))!=false){
									
											$gkey=$row->GKEY;
										
											$carrentPosition=$row->CARRENTPOSITION;
				

											$lastUpdateStr="SELECT ctmsmis.mis_exp_unit_load_failed.last_update
											FROM ctmsmis.mis_exp_unit_load_failed WHERE ctmsmis.mis_exp_unit_load_failed.gkey='$gkey'";
											$lastUpdateQuery=mysqli_query($con_sparcsn4,$lastUpdateStr);
											$lastUpdate="";
											while($lastUpdateRow=mysqli_fetch_object($lastUpdateQuery)){
												$lastUpdate=$lastUpdateRow->last_update;
											}

											$userIDStr="SELECT ctmsmis.mis_exp_unit_load_failed.user_id
											FROM ctmsmis.mis_exp_unit_load_failed WHERE ctmsmis.mis_exp_unit_load_failed.gkey='$gkey'";
											$userIDStrQuery=mysqli_query($con_sparcsn4,$userIDStr);
											$userId="";
											while($userIdRow=mysqli_fetch_object($userIDStrQuery)){
												$userId=$userIdRow->user_id;
											}

											$yardStr="SELECT ctmsmis.cont_yard('$carrentPosition') AS Yard_No";
											$yardQuery=mysqli_query($con_sparcsn4,$yardStr);
											$yardNo="";
											while($yardRow=mysqli_fetch_object($yardQuery)){
												$yardNo=$yardRow->Yard_No;
											}

											$blockStr="SELECT ctmsmis.cont_block('$carrentPosition','$yardNo') AS Block_No";
											$blockQuery=mysqli_query($con_sparcsn4,$blockStr);
											$blockNo="";
											while($blockRow=mysqli_fetch_object($blockQuery)){
												$blockNo=$blockRow->Block_No;
											}
											if($numRows >0 && $yardNo!=NULL ){




								$i++;
								$stayed=$stayed+$row->stay;
								//if($yard_no=="GCB")
								//{
									if($i==$numRows)
										$allCont .=$row->CONT_NO;
									else
										$allCont .=$row->CONT_NO.", ";
								//}
								$sqlIsoCode="select cont_iso_type from igm_detail_container where cont_number='$row->CONT_NO'";
								$sqlIsoCode=mysqli_query($con_cchaportdb,$sqlIsoCode);								
								//echo "select cont_iso_type from igm_detail_container where cont_number='$row->cont_no";
								$rtnIsoCode=mysqli_fetch_object($sqlIsoCode);
								$iso=$rtnIsoCode->cont_iso_type;
								if(substr($iso,0,1)==2)
									$j20=$j20+1;
								else
									$j40=$j40+1;
									
								if(substr($iso,0,1)==2)
								{
									if($row->SHIFT=="Shift A")
										$a20 = $a20+1;
									if($row->SHIFT=="Shift B")
										$b20 = $b20+1;
									if($row->SHIFT=="Shift C")
										$c20 = $c20+1;
								}
								else
								{
									if($row->SHIFT=="Shift A")
										$a40 = $a40+1;
									if($row->SHIFT=="Shift B")
										$b40 = $b40+1;
									if($row->SHIFT=="Shift C")
										$c40 = $c40+1;
								}
									
								if($shift==$row->SHIFT or $i==1)
								{
									if(substr($iso,0,1)==2)
										$tot20 = $tot20+1;
									else 
										$tot40 = $tot40+1;
								}
								/*
								if($totalcon==$row->cont_no or $i==1)
								{
									echo "test";
									if(substr($iso,0,1)==2)
										$tot20 = $tot20+1;
									else 
										$tot40 = $tot40+1;
									
									echo $tot40;
									
								}*/
								
								if($yard!=$yardNo)
								{
									$yard=$yardNo;
									if($i!=1)
									{
									?>
									<tr>
										<td colspan="15"><b><?php  echo "Total 20'=>".$tot20." & 40'=>".$tot40;?></b></td>
									</tr>
									<?php
										if(substr($iso,0,1)==2)
										{
											$tot20 = 1;
											$tot40 = 0;
										}
										else
										{
											$tot20 = 0;
											$tot40 = 1;
										}
									}
									?>
									<tr>
										<td colspan="15"><b><?php  echo $yardNo;?></b></td>
									</tr>
									<?php
									$i=1;
								}?>
								<?php 
								if($shift!=$row->SHIFT)
								{	
									$shift=$row->SHIFT;		
									if($i!=1)
									{
										if(substr($iso,0,1)==2)
										{
											$tot20 = $tot20;
										}
										else
										{
											$tot40 = $tot40;
										}
									?>
									<tr>
										<td colspan="15"><b><?php  echo "Total 20'=>".$tot20." & 40'=>".$tot40;?></b></td>
									</tr>
									<?php
										if(substr($iso,0,1)==2)
										{
											$tot20 = 1;
											$tot40 = 0;
										}
										else
										{
											$tot20 = 0;
											$tot40 = 1;
										}
									}
									?>
									<tr>
										<td colspan="15"><b><?php  echo $row->SHIFT;?></b></td>
									</tr>	
									<?php	
									$i=1;
								}
								$shift=$row->SHIFT;	
								?>
								
								<?php if (($lastUpdate!="") or  ($lastUpdate!=null))    {?>
									  <tr bgcolor="#ec7063" align="center">
								<?php } else if (($row->DELIVERY )=="" or ($row->DELIVERY)==null) {?>
									<tr  bgcolor="#F2DC5D" align="center">
								<?php } else {?>
								<tr align="center">
								<?php } ?>
								        <td><?php  echo $i;?></td>
								        <td align="center"><?php if($row->CONT_NO) echo $row->CONT_NO; else echo "&nbsp;";?></td>
										<td align="center"><?php if($row->ROT_NO ) echo $row->ROT_NO; else echo "&nbsp;";?></td>
										<td align="center"><?php if($iso) echo $iso; else echo "&nbsp;";?></td>
										<td align="center"><?php if($row->MLO) echo $row->MLO; else echo "&nbsp;";?></td>
										<td align="center"><?php if($row->STATU) echo $row->STATU; else echo "&nbsp;";?></td>
										<td align="center"><?php if($row->MFDCH_DESC) echo $row->MFDCH_DESC; else echo "&nbsp;";?></td>
										<td align="center"><?php if($row->CF ) echo $row->CF; else echo "&nbsp;";?></td>
										<td align="center"><?php if($row->WEIGHT) echo $row->WEIGHT; else echo "&nbsp;";?></td>
										<td align="center"><?php if($row->CARRENTPOSITION) echo $row->CARRENTPOSITION; else echo "&nbsp;";?></td>
										<td align="center"><?php if($row->ASSIGNMENTDATE) echo $row->ASSIGNMENTDATE; else echo "&nbsp;";?></td>
										<td colspan="2" align="center"><?php if($row->DELIVERY) echo $row->DELIVERY; else echo "&nbsp;";?></td>
										<td align="center"><?php if($row->STRIPPED_BY) echo $row->STRIPPED_BY; else echo "&nbsp;";?></td>
										<td align="center"><?php if (($lastUpdate!="") or  ($lastUpdate!=null)) echo "Tried to Exp. Load:".$lastUpdate." by ".$userId; else echo "&nbsp;";?></td>
								
									</tr>
								<?php

								} }
								
								?>
									<tr>
										<td colspan="15"><b><?php  echo "Total 20'=>".$tot20." & 40'=>".$tot40;?></b></td>
										
									</tr>
									<?php 
									if($yard_no=="GCB")
									{
									?>
									<tr>
										<td colspan="15"><?php echo $allCont;?></td>
									</tr>
									<?php 
									}
									?>
							</table>
			<!-- end: page -->
			
	</div>
	<?php } ?>

