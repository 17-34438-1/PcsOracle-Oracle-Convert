<HTML>
	<HEAD>
		<TITLE>YARD WISE CONTAINER DELIVERY REPORT</TITLE>
		
	    <style type="text/css">
<!--
.style1 {font-size: 12px}
-->
        </style>
</HEAD>
<BODY>

	<?php		
		if($action=='xl')
		{
			header("Content-type: application/octet-stream");
			header("Content-Disposition: attachment; filename=DELIVERY_REPORT.xls;");
			header("Content-Type: application/ms-excel");
			header("Pragma: no-cache");
			header("Expires: 0");
		}
		include("dbConection.php");
		include("dbOracleConnection.php");
		$sql_cond="";
		if($yard_no!="")
		{
			if($block=="ALL")
			{
				$sql_cond="Yard_No= '$yard_no'";
			}
			else
			{
				$sql_cond="Yard_No='$yard_no' and Block_No='$block'";
			}
		}
				/*$sql="SELECT DISTINCT *
						FROM (
						SELECT a.id AS cont_no,
						b.flex_string04 AS rl_no, 
						b.flex_string05 AS rl_date, 
						b.flex_string07 AS opbc_no, 
						b.flex_string08 AS opbc_date,
						a.seal_nbr1 as seal_nbr,a.category,
						(select right(sparcsn4.ref_equip_type.nominal_length,2) from sparcsn4.inv_unit_equip
						inner join sparcsn4.ref_equipment on sparcsn4.ref_equipment.gkey=sparcsn4.inv_unit_equip.eq_gkey
						inner join  sparcsn4.ref_equip_type on sparcsn4.ref_equip_type.gkey=sparcsn4.ref_equipment.eqtyp_gkey
						where sparcsn4.inv_unit_equip.unit_gkey=a.gkey) as size,
						(select right(sparcsn4.ref_equip_type.nominal_height,2) from sparcsn4.inv_unit_equip
						inner join sparcsn4.ref_equipment on sparcsn4.ref_equipment.gkey=sparcsn4.inv_unit_equip.eq_gkey
						inner join  sparcsn4.ref_equip_type on sparcsn4.ref_equip_type.gkey=sparcsn4.ref_equipment.eqtyp_gkey
						where sparcsn4.inv_unit_equip.unit_gkey=a.gkey)/10 as height,
						b.time_in AS dischargetime,
						b.time_out as delivery,
						b.last_pos_name,
						g.id AS mlo,
						sparcsn4.config_metafield_lov.mfdch_desc,
						a.freight_kind AS statu,
						a.goods_and_ctr_wt_kg AS weight,

						(SELECT ctmsmis.cont_yard((SELECT SUBSTRING(sparcsn4.srv_event_field_changes.new_value,7)
						FROM sparcsn4.srv_event
						INNER JOIN sparcsn4.srv_event_field_changes ON sparcsn4.srv_event_field_changes.event_gkey=sparcsn4.srv_event.gkey
						WHERE sparcsn4.srv_event.applied_to_gkey=a.gkey AND sparcsn4.srv_event.event_type_gkey=18 LIMIT 1))) AS Yard_No,
						(SELECT ctmsmis.cont_block((SELECT SUBSTRING(sparcsn4.srv_event_field_changes.new_value,7)
						FROM sparcsn4.srv_event
						INNER JOIN sparcsn4.srv_event_field_changes ON sparcsn4.srv_event_field_changes.event_gkey=sparcsn4.srv_event.gkey
						WHERE sparcsn4.srv_event.applied_to_gkey=a.gkey AND sparcsn4.srv_event.event_type_gkey=18 LIMIT 1),Yard_No)) AS Block_No,
						b.flex_date01 AS assignmentdate,sparcsn4.vsl_vessel_visit_details.ib_vyg
						FROM ctmsmis.tmp_vcms_assignment 
						INNER JOIN sparcsn4.inv_unit a ON a.gkey=ctmsmis.tmp_vcms_assignment.unit_gkey
						INNER JOIN sparcsn4.inv_unit_fcy_visit b ON b.unit_gkey=a.gkey 
						INNER JOIN sparcsn4.ref_bizunit_scoped g ON a.line_op = g.gkey 
						INNER JOIN sparcsn4.argo_carrier_visit ON sparcsn4.argo_carrier_visit.gkey=b.actual_ib_cv
						INNER JOIN sparcsn4.vsl_vessel_visit_details ON sparcsn4.vsl_vessel_visit_details.vvd_gkey=sparcsn4.argo_carrier_visit.cvcvd_gkey
						INNER JOIN sparcsn4.config_metafield_lov ON a.flex_string01=config_metafield_lov.mfdch_value 
						INNER JOIN sparcsn4.inv_goods j ON j.gkey = a.goods 
						LEFT JOIN sparcsn4.ref_bizunit_scoped k ON k.gkey = j.consignee_bzu 
						WHERE ctmsmis.tmp_vcms_assignment.assignmentDate='$assDt'
						AND config_metafield_lov.mfdch_value NOT IN ('CANCEL','OCD','APPCUS','APPOTH','APPREF')
						) AS tmp where ".$sql_cond." and delivery between '$fromdate $fromTime:00' and '$todate $toTime:00'"; */
						$sql="SELECT DISTINCT *
						FROM (
						SELECT a.id AS cont_no,a.gkey,
						b.flex_string04 AS rl_no, 
						b.flex_string05 AS rl_date, 
						b.flex_string07 AS opbc_no, 
						b.flex_string08 AS opbc_date,
						a.seal_nbr1 as seal_nbr,a.category,
						(SELECT SUBSTR(ref_equip_type.nominal_length,-2) FROM inv_unit 
						INNER JOIN ref_equipment ON ref_equipment.gkey=inv_unit.eq_gkey 
						INNER JOIN ref_equip_type ON ref_equip_type.gkey=ref_equipment.eqtyp_gkey 
						WHERE b.unit_gkey=a.gkey fetch first 1 rows only ) AS siz, 
						
						(SELECT SUBSTR(ref_equip_type.nominal_height,-2) FROM inv_unit
						INNER JOIN ref_equipment ON ref_equipment.gkey=inv_unit.eq_gkey 
						INNER JOIN ref_equip_type ON ref_equip_type.gkey=ref_equipment.eqtyp_gkey
						WHERE b.unit_gkey=a.gkey  fetch first 1 rows only)/10 AS height,
						(SELECT SUBSTR(srv_event_field_changes.new_value,7)
						FROM srv_event
						INNER JOIN srv_event_field_changes ON srv_event_field_changes.event_gkey=srv_event.gkey
						WHERE srv_event.applied_to_gkey=a.gkey AND srv_event.event_type_gkey=18 FETCH FIRST 1 ROWS ONLY ) as lastPosSlot,
						b.time_in AS dischargetime,
						b.time_out as delivery,
						b.last_pos_name,
						g.id AS mlo,
						config_metafield_lov.mfdch_desc,
						a.freight_kind AS statu,
						a.goods_and_ctr_wt_kg AS weight,
						
						
						b.flex_date01 AS assignmentdate,vsl_vessel_visit_details.ib_vyg
						FROM inv_unit a
						INNER JOIN inv_unit_fcy_visit b ON b.unit_gkey=a.gkey 
						INNER JOIN ref_bizunit_scoped g ON a.line_op = g.gkey 
						INNER JOIN argo_carrier_visit ON argo_carrier_visit.gkey=b.actual_ib_cv
						INNER JOIN vsl_vessel_visit_details ON vsl_vessel_visit_details.vvd_gkey=argo_carrier_visit.cvcvd_gkey
						INNER JOIN config_metafield_lov ON a.flex_string01=config_metafield_lov.mfdch_value 
						INNER JOIN inv_goods j ON j.gkey = a.goods 
						LEFT JOIN ref_bizunit_scoped k ON k.gkey = j.consignee_bzu 
						WHERE  config_metafield_lov.mfdch_value NOT IN ('CANCEL','OCD','APPCUS','APPOTH','APPREF')
						)  tmp where  delivery between to_date('$fromdate $fromTime:00','yyyy-mm-dd hh24:mi:ss') 
						 and to_date('$todate $toTime:00','yyyy-mm-dd hh24:mi:ss')";

				$sqlRslt=oci_parse($con_sparcsn4_oracle,$sql);
				oci_execute($sqlRslt);								
	?>
			
		<TABLE width="100%">
			<TR><TD width="100%">
				<table class='table-header' border=0 width="100%">
					<tr><td colspan="2" align="center"><h1>YARD WISE CONTAINER DELIVERY REPORT</h1></td></tr>
					<tr>
						<tr>
							<th align="center" colspan="6">
								<h3 align="center">
								<?php 
									$strTitle = "";
									$strTitle2 = "";
									$strTitle3 = "";
									$strTitle = "SEARCH FOR TERMINAL : ".$yard_no." AND BLOCK : ".$block;
									$strTitle2 = "</br>DELIVERY DATE FROM : ".$fromdate." ".$fromTime.":00 TO : ".$todate." ".$toTime.":00";
									$strTitle3 = "</br>ASSIGNMENT DATE : ".$assDt;
									echo $strTitle.$strTitle2.$strTitle3;
								?>
								</h3>
							</th>
						</tr>
				</table>
			</TD></TR>
			<TR><TD>
					<table class="table table-bordered table-responsive table-hover table-striped mb-none">
					<tr>
						
						<th align="center">Sl</th>
						<th align="center">ROTATION</th>
						<th align="center">CONTAINER</th>						
						<th align="center">SEAL NO</th>						
						<th align="center">SIZE</th>						
						<th align="center">HEIGHT</th>						
						<th align="center">WEIGHT</th>						
						<th align="center">MLO</th>		
						<th align="center">CATEGORY</th>							
						<th align="center">FRIEGHT KIND</th>	
						<th align="center">R/L NO</th>
						<th align="center">R/L DATE</th>
						<th align="center">OPBC NO</th>
						<th align="center">OPBC DATE</th>						
						<!--th align="center">YARD</th-->
						<?php // if($block=="ALL") {?>		
						<th align="center">BLOCK</th>
						<?php// }?>
						<th align="center">DESCRIPTION OF GOODS</th>
						<th align="center">IMCO</th>
						<th align="center">UN</th>
						<th align="center">LAST POSITION</th>
						
						<th align="center">IMPORT CARGO</th>						
						<th align="center">NAVY EXPERT OFFICER's COMMENTS</th>						
						<th align="center">REMARKS</th>	
						
						<!--th align="center">LAST SLOT</th>											
						<th align="center">TIME IN</th-->								
					</tr>
					<?php
					$eq = "";
					$i=0;
					while (($row=oci_fetch_object($sqlRslt))!=false)						
					{
						$gKey="";
						$gKey=$row->GKEY;
						$ctmsStr="SELECT * FROM  ctmsmis.tmp_vcms_assignment
						WHERE ctmsmis.tmp_vcms_assignment.unit_gkey='$gKey' AND  ctmsmis.tmp_vcms_assignment.assignmentDate='$assDt'";
						$ctmsQuery=mysqli_query($con_sparcsn4,$ctmsStr);
						$numRows=0;
						$numRows=mysqli_num_rows($ctmsQuery);
						$viewStatus=0;
						$viewStatus2=0;
						$yardNo="";
						$blockNo="";
						if($numRows>0){
							    $viewStatus2=1;
								$lastPositionSlot="";
								$lastPositionSlot=$row->LASTPOSSLOT;
								$yardStr="SELECT ctmsmis.cont_yard('$lastPositionSlot') AS yard";
								$yardQuery=mysqli_query($con_sparcsn4,$yardStr);
								$yardRes=mysqli_fetch_object($yardQuery);
								$yardNo=$yardRes->yard;

								$blockStr="SELECT ctmsmis.cont_block('$lastPositionSlot','$yardNo') AS block";
								$blockQuery=mysqli_query($con_sparcsn4,$blockStr);
								$blockRes=mysqli_fetch_object($blockQuery);
								$blockNo=$blockRes->block;

								if($status==1){
									if($yardNo==$yard_no){
										$viewStatus=1;
									}
									else{
										$viewStatus=0;
									}
								}
								else if($status==2){
									if($yardNo==$yard_no && $blockNo==$block){
										$viewStatus=1;
									}
									else{
										$viewStatus=0;
									}
								}
								else{
									$viewStatus=0;
								}

						}
						if($viewStatus==1 && $viewStatus2==1){
							$i=$i+1;
							$cont = $row->CONT_NO;
							$rot = $row->IB_VYG;
					?>
						 <tr>
								<td  align="center"><?php echo $i;?></td>
								<td  align="center"><?php if($row->IB_VYG) echo($row->IB_VYG); else echo("&nbsp;");?></td>
								<td  align="center"><?php if($row->CONT_NO) echo($row->CONT_NO); else echo("&nbsp;");?></td>
								<td  align="center"><?php if($row->SEAL_NBR) echo($row->SEAL_NBR); else echo("&nbsp;");?></td>
								<td  align="center"><?php if($row->SIZ) echo($row->SIZ); else echo("&nbsp;");?></td>
								<td  align="center"><?php if($row->HEIGHT) echo($row->HEIGHT); else echo("&nbsp;");?></td>
								<td  align="center"><?php if($row->WEIGHT) echo($row->WEIGHT); else echo("&nbsp;");?></td>
								<td  align="center"><?php if($row->MLO) echo($row->MLO); else echo("&nbsp;");?></td>
								<td  align="center"><?php if($row->CATEGORY) echo($row->CATEGORY); else echo("&nbsp;");?></td>
								<td  align="center"><?php if($row->STATU) echo($row->STATU); else echo("&nbsp;");?></td>
								
								<td  align="center"><?php if($row->RL_NO) echo($row->RL_NO); else echo("&nbsp;");?></td>
								<td  align="center"><?php if($row->RL_DATE) echo($row->RL_DATE); else echo("&nbsp;");?></td>
								<td  align="center"><?php if($row->OPBC_NO) echo($row->OPBC_NO); else echo("&nbsp;");?></td>
								<td  align="center"><?php if($row->OPBC_DATE) echo($row->OPBC_DATE); else echo("&nbsp;");?></td>
								
								
								<!--td  align="center"><?php if($yardNo) echo($yardNo); else echo("&nbsp;");?></td-->
								<?php // if($block=="ALL") {?>	
								<td  align="center"><?php if($blockNo) echo($blockNo); else echo("&nbsp;");?></td>
								<?php // }?>
								<?php
									$query = "SELECT Description_of_Goods,cont_imo AS imco,cont_un AS un
									FROM igm_details 
									INNER JOIN igm_detail_container ON igm_detail_container.igm_detail_id = igm_details.id
									WHERE igm_detail_container.cont_number='$cont' AND igm_details.Import_Rotation_No='$rot'";
									$rslt = $this->bm->dataSelectDB1($query);
									$desc = "";
									$imco = "";
									$un = "";
									if(count($rslt)>0){
										$desc = $rslt[0]['Description_of_Goods'];
										$imco = $rslt[0]['imco'];
										$un = $rslt[0]['un'];
									}
								?>
								<td align="center"><?php echo $desc; ?></td>
								<td align="center"><?php echo $imco; ?></td>
								<td align="center"><?php echo $un; ?></td>
								<td  align="center"><?php if($row->LAST_POS_NAME) echo($row->LAST_POS_NAME); else echo("&nbsp;");?></td>
								
								<td  align="center"></td>
								<td  align="center"></td>
								<td  align="center"></td>
								<!--td  align="center"><?php if($row->LASTPOSSLOT) echo($row->LASTPOSSLOT); else echo("&nbsp;");?></td>
								<td  align="center"><?php if($row->TIME_IN) echo($row->TIME_IN); else echo("&nbsp;");?></td-->
						</tr>					 
					<?php 
					  }
					}
					?>
					      </table>  						 
						</TD></TR>
					</TABLE>
	
<?php 
mysqli_close($con_sparcsn4);
if(@$_POST['options']=='html'){?>		
	</BODY>
</HTML>
<?php }?>
