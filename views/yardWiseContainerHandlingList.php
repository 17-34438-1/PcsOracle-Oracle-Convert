<?php
	if($fileOptions=='xl'){
		//$rota=str_replace('/', '-', $rot);

		//following five lines are for excel download
		header("Content-type: application/octet-stream");
		header("Content-Disposition: attachment; filename=YARD WISE CONTAINER RECEIVE REPORT.xls;");
		header("Content-Type: application/ms-excel");
		header("Pragma: no-cache");
		header("Expires: 0");
	}
?>
<HTML>
	<HEAD>
		<TITLE>YARD WISE CONTAINER RECEIVE REPORT</TITLE>
		
	    <style type="text/css">
<!--
.style1 {font-size: 12px}
-->
        </style>
</HEAD>
<BODY>

	<?php
		include("dbConection.php");
		include("mydbPConnection.php");
		include("dbOracleConnection.php");
		
		$sql_cond="";
		$status=0;
		if($yard_no!="")
		{
			if($block=="ALL")
			{
				$sql_cond=" WHERE Yard_No= '$yard_no'";
				$status=1;
			}
			else
			{
				// $sql_cond=" WHERE Yard_No='$yard_no' and Block_No='$block'";
				$sql_cond=" WHERE Yard_No='$yard_no' and carrentPosition='$block'";
				$status=2;
			}
		}
		
		$sql = "";
		if($searchBy == "All")
		{
			// $sql="select * from (select inv_unit.id,sparcsn4.vsl_vessel_visit_details.ib_vyg,(inv_unit.goods_and_ctr_wt_kg/1000) as weight,inv_unit.seal_nbr1 as seal_nbr,
			// (select right(sparcsn4.ref_equip_type.nominal_length,2) from sparcsn4.inv_unit_equip
			// inner join sparcsn4.ref_equipment on sparcsn4.ref_equipment.gkey=sparcsn4.inv_unit_equip.eq_gkey
			// inner join  sparcsn4.ref_equip_type on sparcsn4.ref_equip_type.gkey=sparcsn4.ref_equipment.eqtyp_gkey
			// where sparcsn4.inv_unit_equip.unit_gkey=sparcsn4.inv_unit.gkey) as size,
			// (select right(sparcsn4.ref_equip_type.nominal_height,2) from sparcsn4.inv_unit_equip
			// inner join sparcsn4.ref_equipment on sparcsn4.ref_equipment.gkey=sparcsn4.inv_unit_equip.eq_gkey
			// inner join  sparcsn4.ref_equip_type on sparcsn4.ref_equip_type.gkey=sparcsn4.ref_equipment.eqtyp_gkey
			// where sparcsn4.inv_unit_equip.unit_gkey=sparcsn4.inv_unit.gkey)/10 as height,
			// sparcsn4.ref_bizunit_scoped.id as MLO,inv_unit.category,inv_unit.freight_kind,
			// (SELECT ctmsmis.cont_yard(sparcsn4.inv_unit_fcy_visit.last_pos_slot)) AS Yard_No,
			// (SELECT ctmsmis.cont_block(sparcsn4.inv_unit_fcy_visit.last_pos_slot,Yard_No)) AS Block_No,
			// inv_unit_fcy_visit.last_pos_name,inv_unit_fcy_visit.last_pos_slot,
			// inv_unit_fcy_visit.time_in
			// from sparcsn4.inv_unit 
			// inner join sparcsn4.inv_unit_fcy_visit on sparcsn4.inv_unit_fcy_visit.unit_gkey=sparcsn4.inv_unit.gkey
			// inner join sparcsn4.argo_carrier_visit on sparcsn4.argo_carrier_visit.gkey=sparcsn4.inv_unit_fcy_visit.actual_ib_cv
			// inner join sparcsn4.vsl_vessel_visit_details on sparcsn4.vsl_vessel_visit_details.vvd_gkey=sparcsn4.argo_carrier_visit.cvcvd_gkey
			// inner join ref_bizunit_scoped on sparcsn4.inv_unit.line_op=sparcsn4.ref_bizunit_scoped.gkey
			// where sparcsn4.inv_unit_fcy_visit.time_in BETWEEN '$fromdate $fromTime:00' and '$todate $toTime:00' 
			// and sparcsn4.inv_unit.category='IMPRT') as tmp ".$sql_cond;		// sparcsn4.inv_unit_fcy_visit.transit_state='S40_YARD'
			
			$sql="SELECT * FROM (SELECT inv_unit.id,
			inv_unit_fcy_visit.flex_string04 AS rl_no,
			inv_unit_fcy_visit.flex_string05 AS rl_date,
			inv_unit_fcy_visit.flex_string07 AS opbc_no,
			inv_unit_fcy_visit.flex_string08 AS opbc_date,
			vsl_vessel_visit_details.ib_vyg,(inv_unit.goods_and_ctr_wt_kg/1000) AS weight,inv_unit.seal_nbr1 AS seal_nbr, 
			(SELECT SUBSTR(ref_equip_type.nominal_length,-2) FROM inv_unit 
			INNER JOIN ref_equipment ON ref_equipment.gkey=inv_unit.eq_gkey 
			INNER JOIN ref_equip_type ON ref_equip_type.gkey=ref_equipment.eqtyp_gkey 
			WHERE inv_unit_fcy_visit.unit_gkey=inv_unit.gkey) AS siz, 
			
			(SELECT SUBSTR(ref_equip_type.nominal_height,-2) FROM inv_unit
			INNER JOIN ref_equipment ON ref_equipment.gkey=inv_unit.eq_gkey 
			INNER JOIN ref_equip_type ON ref_equip_type.gkey=ref_equipment.eqtyp_gkey
			WHERE inv_unit_fcy_visit.unit_gkey=inv_unit.gkey)/10 AS height, 
			
			ref_bizunit_scoped.id AS MLO,inv_unit.category,inv_unit.freight_kind, 
			NVL((SELECT SUBSTR(srv_event_field_changes.new_value,7) FROM srv_event 
			INNER JOIN srv_event_field_changes ON srv_event_field_changes.event_gkey=srv_event.gkey 
			WHERE srv_event.applied_to_gkey=inv_unit.gkey AND srv_event.event_type_gkey IN(18,13,16) 
			AND srv_event_field_changes.new_value IS NOT NULL AND srv_event_field_changes.new_value !='' 
			AND srv_event_field_changes.new_value !='Y-CGP-.' 
			AND srv_event.created BETWEEN to_date('$fromdate $fromTime:00','yyyy-mm-dd hh24:mi:ss') 
			AND to_date('$todate $toTime:00','yyyy-mm-dd hh24:mi:ss')
			AND srv_event.gkey<(SELECT srv_event.gkey FROM srv_event 
			INNER JOIN srv_event_field_changes ON srv_event_field_changes.event_gkey=srv_event.gkey 
			WHERE srv_event.event_type_gkey=4 AND srv_event.applied_to_gkey=inv_unit.gkey 
			AND metafield_id='unitFlexString01' AND new_value IS NOT NULL ORDER BY srv_event_field_changes.gkey DESC fetch first 1 rows only) 
			ORDER BY srv_event.gkey DESC fetch first 1 rows only),
			(SELECT SUBSTR(srv_event_field_changes.new_value,7) 
			FROM srv_event INNER JOIN srv_event_field_changes ON srv_event_field_changes.event_gkey=srv_event.gkey 
			WHERE srv_event.applied_to_gkey=inv_unit.gkey AND srv_event.event_type_gkey IN(18,13,16) 
			ORDER BY srv_event_field_changes.gkey DESC fetch first 1 rows only)) AS carrentPosition, 
			
			inv_unit_fcy_visit.last_pos_name,inv_unit_fcy_visit.last_pos_slot, 
			to_char(inv_unit_fcy_visit.time_in,'yyyy-mm-dd hh24:mi:ss') as time_in
			FROM inv_unit 
			INNER JOIN inv_unit_fcy_visit ON inv_unit_fcy_visit.unit_gkey=inv_unit.gkey 
			INNER JOIN argo_carrier_visit ON argo_carrier_visit.gkey=inv_unit_fcy_visit.actual_ib_cv 
			INNER JOIN vsl_vessel_visit_details ON vsl_vessel_visit_details.vvd_gkey=argo_carrier_visit.cvcvd_gkey 
			INNER JOIN ref_bizunit_scoped ON inv_unit.line_op=ref_bizunit_scoped.gkey 
			
			 WHERE ((inv_unit_fcy_visit.time_in BETWEEN  to_date('$fromdate $fromTime:00','yyyy-mm-dd hh24:mi:ss') 
			 AND to_date('$todate $toTime:00','yyyy-mm-dd hh24:mi:ss')) 
			OR (inv_unit_fcy_visit.time_move BETWEEN to_date('$fromdate $fromTime:00','yyyy-mm-dd hh24:mi:ss')
			AND to_date('$todate $toTime:00','yyyy-mm-dd hh24:mi:ss')))
			AND inv_unit.category='IMPRT'
			 )  tmp ";		
		}
		else if($searchBy == "Rotation")
		{
			$sql="select * from (select inv_unit.id,
			inv_unit_fcy_visit.flex_string04 AS rl_no,
			inv_unit_fcy_visit.flex_string05 AS rl_date,
			inv_unit_fcy_visit.flex_string07 AS opbc_no,
			inv_unit_fcy_visit.flex_string08 AS opbc_date,
			vsl_vessel_visit_details.ib_vyg,(inv_unit.goods_and_ctr_wt_kg/1000) as weight,inv_unit.seal_nbr1 as seal_nbr,
			(SELECT SUBSTR(ref_equip_type.nominal_length,-2) FROM inv_unit 
			INNER JOIN ref_equipment ON ref_equipment.gkey=inv_unit.eq_gkey 
			INNER JOIN ref_equip_type ON ref_equip_type.gkey=ref_equipment.eqtyp_gkey 
			WHERE inv_unit_fcy_visit.unit_gkey=inv_unit.gkey) AS siz, 
			
			(SELECT SUBSTR(ref_equip_type.nominal_height,-2) FROM inv_unit
			INNER JOIN ref_equipment ON ref_equipment.gkey=inv_unit.eq_gkey 
			INNER JOIN ref_equip_type ON ref_equip_type.gkey=ref_equipment.eqtyp_gkey
			WHERE inv_unit_fcy_visit.unit_gkey=inv_unit.gkey)/10 AS height, 
			ref_bizunit_scoped.id as MLO,inv_unit.category,inv_unit.freight_kind,
			
			NVL((SELECT SUBSTR(srv_event_field_changes.new_value,7) FROM srv_event 
			INNER JOIN srv_event_field_changes ON srv_event_field_changes.event_gkey=srv_event.gkey 
			WHERE srv_event.applied_to_gkey=inv_unit.gkey AND srv_event.event_type_gkey IN(18,13,16) 
			AND srv_event_field_changes.new_value IS NOT NULL AND srv_event_field_changes.new_value !='' 
			AND srv_event_field_changes.new_value !='Y-CGP-.' 
			AND srv_event.created BETWEEN to_date('$fromdate $fromTime:00' ,'yyyy-mm-dd hh24:mi:ss') 
			AND to_date('$todate $toTime:00','yyyy-mm-dd hh24:mi:ss') 
			AND srv_event.gkey<(SELECT srv_event.gkey FROM srv_event 
			INNER JOIN srv_event_field_changes ON srv_event_field_changes.event_gkey=srv_event.gkey 
			WHERE srv_event.event_type_gkey=4 AND srv_event.applied_to_gkey=inv_unit.gkey 
			AND metafield_id='unitFlexString01' AND new_value IS NOT NULL ORDER BY srv_event_field_changes.gkey DESC FETCH FIRST 1 ROWS ONLY ) 
			ORDER BY srv_event.gkey DESC FETCH FIRST 1 ROWS ONLY ),
			(SELECT SUBSTR(srv_event_field_changes.new_value,7) 
			FROM srv_event INNER JOIN srv_event_field_changes ON srv_event_field_changes.event_gkey=srv_event.gkey 
			WHERE srv_event.applied_to_gkey=inv_unit.gkey AND srv_event.event_type_gkey IN(18,13,16) 
			ORDER BY srv_event_field_changes.gkey DESC FETCH FIRST 1 ROWS ONLY )) AS carrentPosition, 
			
			inv_unit_fcy_visit.last_pos_name,inv_unit_fcy_visit.last_pos_slot,
			to_char(inv_unit_fcy_visit.time_in,'yyyy-mm-dd hh24:mi:ss') as time_in
			from inv_unit 
			inner join inv_unit_fcy_visit on inv_unit_fcy_visit.unit_gkey=inv_unit.gkey
			inner join argo_carrier_visit on argo_carrier_visit.gkey=inv_unit_fcy_visit.actual_ib_cv
			inner join vsl_vessel_visit_details on vsl_vessel_visit_details.vvd_gkey=argo_carrier_visit.cvcvd_gkey
			inner join ref_bizunit_scoped on inv_unit.line_op=ref_bizunit_scoped.gkey
			where inv_unit_fcy_visit.time_in BETWEEN to_date('$fromdate $fromTime:00','yyyy-mm-dd hh24:mi:ss') 
			and to_date('$todate $toTime:00' ,'yyyy-mm-dd hh24:mi:ss') 
			and inv_unit.category='IMPRT' and vsl_vessel_visit_details.ib_vyg='$rotNum'
			)  tmp ";
		}

		
				// $sql="select * from (select inv_unit.id,sparcsn4.vsl_vessel_visit_details.ib_vyg,(inv_unit.goods_and_ctr_wt_kg/1000) as weight,inv_unit.seal_nbr1 as seal_nbr,
						// (select right(sparcsn4.ref_equip_type.nominal_length,2) from sparcsn4.inv_unit_equip
						// inner join sparcsn4.ref_equipment on sparcsn4.ref_equipment.gkey=sparcsn4.inv_unit_equip.eq_gkey
						// inner join  sparcsn4.ref_equip_type on sparcsn4.ref_equip_type.gkey=sparcsn4.ref_equipment.eqtyp_gkey
						// where sparcsn4.inv_unit_equip.unit_gkey=sparcsn4.inv_unit.gkey) as size,
						// (select right(sparcsn4.ref_equip_type.nominal_height,2) from sparcsn4.inv_unit_equip
						// inner join sparcsn4.ref_equipment on sparcsn4.ref_equipment.gkey=sparcsn4.inv_unit_equip.eq_gkey
						// inner join  sparcsn4.ref_equip_type on sparcsn4.ref_equip_type.gkey=sparcsn4.ref_equipment.eqtyp_gkey
						// where sparcsn4.inv_unit_equip.unit_gkey=sparcsn4.inv_unit.gkey)/10 as height,
						// sparcsn4.ref_bizunit_scoped.id as MLO,inv_unit.category,inv_unit.freight_kind,
						// (SELECT ctmsmis.cont_yard(sparcsn4.inv_unit_fcy_visit.last_pos_slot)) AS Yard_No,
						// (SELECT ctmsmis.cont_block(sparcsn4.inv_unit_fcy_visit.last_pos_slot,Yard_No)) AS Block_No,
						// inv_unit_fcy_visit.last_pos_name,inv_unit_fcy_visit.last_pos_slot,
						// inv_unit_fcy_visit.time_in
						// from sparcsn4.inv_unit 
						// inner join sparcsn4.inv_unit_fcy_visit on sparcsn4.inv_unit_fcy_visit.unit_gkey=sparcsn4.inv_unit.gkey
						// inner join sparcsn4.argo_carrier_visit on sparcsn4.argo_carrier_visit.gkey=sparcsn4.inv_unit_fcy_visit.actual_ib_cv
						// inner join sparcsn4.vsl_vessel_visit_details on sparcsn4.vsl_vessel_visit_details.vvd_gkey=sparcsn4.argo_carrier_visit.cvcvd_gkey
						// inner join ref_bizunit_scoped on sparcsn4.inv_unit.line_op=sparcsn4.ref_bizunit_scoped.gkey
						// where sparcsn4.inv_unit_fcy_visit.time_in BETWEEN '$fromdate $fromTime:00' and '$todate $toTime:00' 
						// and sparcsn4.inv_unit.category='IMPRT' and sparcsn4.vsl_vessel_visit_details.ib_vyg='$rotNum') as tmp ".$sql_cond;
						
				// $sql="select * from (select inv_unit.id,(inv_unit.goods_and_ctr_wt_kg/1000) as weight,inv_unit.seal_nbr1 as seal_nbr,
						// (select right(sparcsn4.ref_equip_type.nominal_length,2) from sparcsn4.inv_unit_equip
						// inner join sparcsn4.ref_equipment on sparcsn4.ref_equipment.gkey=sparcsn4.inv_unit_equip.eq_gkey
						// inner join  sparcsn4.ref_equip_type on sparcsn4.ref_equip_type.gkey=sparcsn4.ref_equipment.eqtyp_gkey
						// where sparcsn4.inv_unit_equip.unit_gkey=sparcsn4.inv_unit.gkey) as size,
						// (select right(sparcsn4.ref_equip_type.nominal_height,2) from sparcsn4.inv_unit_equip
						// inner join sparcsn4.ref_equipment on sparcsn4.ref_equipment.gkey=sparcsn4.inv_unit_equip.eq_gkey
						// inner join  sparcsn4.ref_equip_type on sparcsn4.ref_equip_type.gkey=sparcsn4.ref_equipment.eqtyp_gkey
						// where sparcsn4.inv_unit_equip.unit_gkey=sparcsn4.inv_unit.gkey)/10 as height,
						// sparcsn4.ref_bizunit_scoped.id as MLO,inv_unit.category,inv_unit.freight_kind,
						// (SELECT ctmsmis.cont_yard(sparcsn4.inv_unit_fcy_visit.last_pos_slot)) AS Yard_No,
						// (SELECT ctmsmis.cont_block(sparcsn4.inv_unit_fcy_visit.last_pos_slot,Yard_No)) AS Block_No,
						// inv_unit_fcy_visit.last_pos_name,inv_unit_fcy_visit.last_pos_slot,
						// inv_unit_fcy_visit.time_in
						// from sparcsn4.inv_unit 
						// inner join sparcsn4.inv_unit_fcy_visit on sparcsn4.inv_unit_fcy_visit.unit_gkey=sparcsn4.inv_unit.gkey
						// inner join sparcsn4.argo_carrier_visit on sparcsn4.argo_carrier_visit.gkey=sparcsn4.inv_unit_fcy_visit.actual_ib_cv
						// inner join sparcsn4.vsl_vessel_visit_details on sparcsn4.vsl_vessel_visit_details.vvd_gkey=sparcsn4.argo_carrier_visit.cvcvd_gkey
						// inner join ref_bizunit_scoped on sparcsn4.inv_unit.line_op=sparcsn4.ref_bizunit_scoped.gkey
						// where sparcsn4.inv_unit_fcy_visit.time_in BETWEEN '$fromdate $fromTime:00' and '$todate $toTime:00' 
						// and sparcsn4.inv_unit.category='IMPRT' and sparcsn4.vsl_vessel_visit_details.ib_vyg='$rotNum') as tmp
						// where ".$sql_cond;

				/*$sql = "select * from (select inv_unit.id,
						(select right(sparcsn4.ref_equip_type.nominal_length,2) from sparcsn4.inv_unit_equip
						inner join sparcsn4.ref_equipment on sparcsn4.ref_equipment.gkey=sparcsn4.inv_unit_equip.eq_gkey
						inner join  sparcsn4.ref_equip_type on sparcsn4.ref_equip_type.gkey=sparcsn4.ref_equipment.eqtyp_gkey
						where sparcsn4.inv_unit_equip.unit_gkey=sparcsn4.inv_unit.gkey) as size,
						(select right(sparcsn4.ref_equip_type.nominal_height,2) from sparcsn4.inv_unit_equip
						inner join sparcsn4.ref_equipment on sparcsn4.ref_equipment.gkey=sparcsn4.inv_unit_equip.eq_gkey
						inner join  sparcsn4.ref_equip_type on sparcsn4.ref_equip_type.gkey=sparcsn4.ref_equipment.eqtyp_gkey
						where sparcsn4.inv_unit_equip.unit_gkey=sparcsn4.inv_unit.gkey)/10 as height,
						sparcsn4.ref_bizunit_scoped.id as MLO,inv_unit.category,inv_unit.freight_kind,
						(SELECT ctmsmis.cont_yard(sparcsn4.inv_unit_fcy_visit.last_pos_slot)) AS Yard_No,
						(SELECT ctmsmis.cont_block(sparcsn4.inv_unit_fcy_visit.last_pos_slot,Yard_No)) AS Block_No,
						inv_unit_fcy_visit.last_pos_name,inv_unit_fcy_visit.last_pos_slot,
						inv_unit_fcy_visit.time_in

						from sparcsn4.inv_unit 
						inner join sparcsn4.inv_unit_fcy_visit on sparcsn4.inv_unit_fcy_visit.unit_gkey=sparcsn4.inv_unit.gkey
						inner join ref_bizunit_scoped on sparcsn4.inv_unit.line_op=sparcsn4.ref_bizunit_scoped.gkey
						where sparcsn4.inv_unit_fcy_visit.time_in BETWEEN '$fromdate $fromTime:00' and '$todate $toTime:00' 
						and sparcsn4.inv_unit.category='IMPRT') as tmp
						where ".$sql_cond;*/
			
			// echo $sql;return;
		$sqlRslt=oci_parse($con_sparcsn4_oracle,$sql);	
		oci_execute($sqlRslt);							
	?>
			
		<TABLE width="100%">
			<TR><TD width="100%">
				<table class='table-header' border=0 width="100%">
					<tr><td colspan="15" align="center"><h1>YARD WISE CONTAINER RECEIVE REPORT</h1></td></tr>
					<tr>
						<tr>
							<th align="center" colspan="15">
								<h3 align="center">
								<?php 
									$strTitle = "";
									$strTitle2 = "";
									$strTitle3 = "";
									$strTitle = "SEARCH FOR TERMINAL : ".$yard_no." AND BLOCK : ".$block;
									$strTitle2 = "</br>DATE FROM : ".$fromdate." ".$fromTime.":00 TO : ".$todate." ".$toTime.":00";
									$strTitle3 = "</br> ROTATION NO : ".$rotNum;
									
									if($rotNum == "")
										echo $strTitle.$strTitle2;
									else
										echo $strTitle.$strTitle2.$strTitle3;
								?>
								</h3>
							</th>
						</tr>
				</table>
			</TD></TR>
			<TR><TD>
					<table border=1 class="table table-bordered table-responsive table-hover table-striped mb-none">
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
						<th align="center">DESCRIPTION OF GOODS</th>						
						<th align="center">IMCO</th>						
						<th align="center">UN</th>		
						
						<th align="center">R/L NO</th>						
						<th align="center">R/L DATE</th>						
						<th align="center">OPBC NO</th>						
						<th align="center">OPBC DATE</th>						

						<th align="center">LOCATION</th>						
						<th align="center">IMPORT CARGO</th>						
						<th align="center">NAVY EXPERT OFFICER's COMMENTS</th>						
						<th align="center">REMARKS</th>						

						<!--th align="center">YARD</th-->
						<?php if($block=="ALL") {?>		
						<th align="center">BLOCK</th>
						<?php }?>
						<th align="center">LAST POSITION</th>
						<!--th align="center">LAST SLOT</th>											
						<th align="center">TIME IN</th-->								
					</tr>
					<?php
					$eq = "";
					$i=0;
					while (($row=oci_fetch_object($sqlRslt))!=false)						
					{

						$viewStatus=0;
						$lastPositionSlot="";
						$lastPositionSlot=$row->LAST_POS_SLOT;

						$yardStr="SELECT ctmsmis.cont_yard('$lastPositionSlot') AS yard";
						$yardQuery=mysqli_query($con_sparcsn4,$yardStr);
						$yardRes=mysqli_fetch_object($yardQuery);
						$yardNo="";
						$yardNo=$yardRes->yard;

						$blockStr="SELECT ctmsmis.cont_block('$lastPositionSlot','$yardNo') AS block";
						$blockQuery=mysqli_query($con_sparcsn4,$blockStr);
						$blockRes=mysqli_fetch_object($blockQuery);
						$blockNo="";
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
					
						if($viewStatus==1){
							$i=$i+1;
						
						$impRot = $row->IB_VYG;
						$contNo = $row->ID;
						
						$sql_igmData = "SELECT Description_of_Goods,imco,un,cont_imo
						FROM igm_details
						INNER JOIN igm_detail_container ON igm_detail_container.igm_detail_id = igm_details.id
						WHERE igm_details.Import_Rotation_No='$impRot' AND igm_detail_container.cont_number='$contNo'";
						$rslt_igmData=mysqli_query($con_cchaportdb,$sql_igmData);	
						
						$goodsDescription = "";
						$imco = "";
						$un = "";
						
						while($row_igmData = mysqli_fetch_object($rslt_igmData))
						{
							$goodsDescription = $row_igmData->Description_of_Goods;
							$imco = $row_igmData->imco;
							$un = $row_igmData->un;
							$cont_imo = $row_igmData->cont_imo;
						}
					?>
						<tr>
							<td  align="center"><?php echo $i;?></td>
							<td  align="center"><?php if($row->IB_VYG) echo($row->IB_VYG); else echo("&nbsp;");?></td>
							<td  align="center"><?php if($row->ID) echo($row->ID); else echo("&nbsp;");?></td>
							<td  align="center"><?php if($row->SEAL_NBR) echo($row->SEAL_NBR); else echo("&nbsp;");?></td>
							<td  align="center"><?php if($row->SIZ) echo($row->SIZ); else echo("&nbsp;");?></td>
							<td  align="center"><?php if($row->HEIGHT) echo($row->HEIGHT); else echo("&nbsp;");?></td>
							<td  align="center"><?php if($row->WEIGHT) echo($row->WEIGHT); else echo("&nbsp;");?></td>
							<td  align="center"><?php if($row->MLO) echo($row->MLO); else echo("&nbsp;");?></td>
							<td  align="center"><?php if($row->CATEGORY) echo($row->CATEGORY); else echo("&nbsp;");?></td>
							<td  align="center"><?php if($row->FREIGHT_KIND) echo($row->FREIGHT_KIND); else echo("&nbsp;");?></td>
							
							<td  align="center"><?php echo $goodsDescription; ?></td>
							<td  align="center"><?php echo $cont_imo; ?></td>
							<td  align="center"><?php echo $un; ?></td>
							<td  align="center"><?php if($row->RL_NO) echo($row->RL_NO); else echo("&nbsp;");?></td>							
							<td  align="center"><?php if($row->RL_DATE) echo($row->RL_DATE); else echo("&nbsp;");?></td>							
							<td  align="center"><?php if($row->OPBC_NO) echo($row->OPBC_NO); else echo("&nbsp;");?></td>							
							<td  align="center"><?php if($row->OPBC_DATE) echo($row->OPBC_DATE); else echo("&nbsp;");?></td>							
							
							<td  align="center"><?php if($row->CARRENTPOSITION) echo($row->CARRENTPOSITION); else echo("&nbsp;");?></td>
							<td  align="center"></td>
							<td  align="center"></td>
							<td  align="center"></td>
							
							<!--td  align="center"><?php if($yardNo) echo($yardNo); else echo("&nbsp;");?></td-->
							<?php if($block=="ALL") {?>	
							<td  align="center"><?php if($blockNo) echo($blockNo); else echo("&nbsp;");?></td>
							<?php }?>
							<td  align="center"><?php if($row->LAST_POS_NAME) echo($row->LAST_POS_NAME); else echo("&nbsp;");?></td>
							<!--td  align="center"><?php if($row->LAST_POS_SLOT) echo($row->LAST_POS_SLOT); else echo("&nbsp;");?></td>
							<td  align="center"><?php if($row->TIME_IN) echo($row->TIME_IN); else echo("&nbsp;");?></td-->
						</tr>						 
					<?php  }
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
