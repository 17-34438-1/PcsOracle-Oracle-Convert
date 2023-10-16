<title>Offhire Details</title>
<body>
<?php 
	for($j=0;$j<count($rslt_get_mlo);$j++)
	{
		$mlo=$rslt_get_mlo[$j]['mlo'];
	?>
		<table width="100%" border ='0' cellpadding='0' cellspacing='0'>
			<tr>
				<td colspan="3" align="center"><img width="200px" src="<?php echo IMG_PATH?>cpanew.jpg" /></td>
			</tr>
			<tr>
				<td colspan="3" align="center"><font size="3"><b>VAT Reg : 2041001546</b></font></td>
			</tr>
			<tr>
				<td colspan="3" align="center"><font size="3"><b>OFFHIRE DATA FORMAT FOR PREPARATION OF AGENT BILL DATE WISE</b></font></td>					
			</tr>
			<tr bgcolor="#ffffff" align="center" height="25px">
				<td align="center">Agent Name: <?php echo $rslt_get_mlo[$j]['CONCUSTOMERNAME']; ?></td>
				<td align="center">Import MLO: <?php echo $rslt_get_mlo[$j]['MLO']; ?></td>
				<td align="center">Date: <?php echo $offhire_date; ?></td>
			</tr>
			<tr bgcolor="#ffffff" align="center" height="25px">
				<td colspan="3" align="center"></td>
			</tr>
		</table>
		<table style="border-collapse:collapse" width="100%" border ='1' cellpadding='0' cellspacing='0'>
			<thead>
				<tr align="center">
					<td style="align:center"><b>SlNo.</b></td>
					<td style="align:center"><b>Container No.</b></td>
					<td style="align:center"><b>Size.</b></td>
					<td style="align:center"><b>Height</b></td>
					<td style="align:center"><b>Status</b></td>
					<td style="align:center"><b>Rot No</b></td>
					<td style="align:center"><b>Vessel Name</b></td>
					<td style="align:center"><b>Berth</b></td>
					<td style="align:center"><b>Land Date</b></td>
					<td style="align:center"><b>Dlv Date</b></td>
					<td style="align:center"><b>Unstaff Date</b></td>
					<td style="align:center"><b>To Depo</b></td>
					<td style="align:center"><b>Depo Name</b></td>
				</tr>
			</thead>
			<tbody>
			<?php
				include_once('mydbPConnectionn4.php');
				include("dbOracleConnection.php");
				
				$sql_offhire_details="SELECT inv_unit.gkey,road_truck_transactions.ctr_id AS unitId,
				(SELECT SUBSTR(ref_equip_type.nominal_length,-2) FROM ref_equip_type
				INNER JOIN ref_equipment ON ref_equipment.eqtyp_gkey=ref_equip_type.gkey
				INNER JOIN inv_unit ON inv_unit.eq_gkey=ref_equipment.gkey
				INNER JOIN inv_unit_fcy_visit ON inv_unit_fcy_visit.unit_gkey=inv_unit.gkey
				WHERE inv_unit_fcy_visit.unit_gkey=inv_unit.gkey) AS isoLength,
				(SELECT SUBSTR(ref_equip_type.nominal_height,-2) FROM ref_equip_type
				INNER JOIN ref_equipment ON ref_equipment.eqtyp_gkey=ref_equip_type.gkey
				INNER JOIN inv_unit ON inv_unit.eq_gkey=ref_equipment.gkey
				INNER JOIN inv_unit_fcy_visit ON inv_unit_fcy_visit.unit_gkey=inv_unit.gkey
				WHERE inv_unit_fcy_visit.unit_gkey=inv_unit.gkey)/10 AS isoHeight,road_truck_transactions.ctr_freight_kind AS freightKind,
				(CASE
				WHEN inv_unit.category='IMPRT' 
				then 
				inv_unit_fcy_visit.flex_string10
				else
				(SELECT fcy.flex_string10 FROM inv_unit_fcy_visit fcy 
				INNER JOIN inv_unit inv ON inv.gkey=fcy.unit_gkey 
				WHERE inv.id=inv_unit.id AND inv.category='IMPRT' AND fcy.time_out < inv_unit_fcy_visit.time_out
				ORDER BY inv.gkey DESC FETCH FIRST 1 ROWS ONLY)
				END) AS ibVisitId,
				
				
				TO_CHAR(inv_unit_fcy_visit.time_in,'YYYY-MM-DD') AS timeIn,
				TO_CHAR(road_truck_visit_details.created,'YYYY-MM-DD') AS yardOutDate1, 
				(CASE
				WHEN (SELECT inv.freight_kind FROM inv_unit_fcy_visit fcy 
				INNER JOIN inv_unit inv ON inv.gkey=fcy.unit_gkey 
				WHERE inv.id=inv_unit.id AND inv.category='IMPRT' AND fcy.time_out < inv_unit_fcy_visit.time_out 
				ORDER BY inv.gkey DESC FETCH FIRST 1 ROWS ONLY)='LCL'
				THEN
				(SELECT TO_CHAR(fcy.time_out,'YYYY-MM-DD HH24:MI:SS') AS time_out FROM inv_unit_fcy_visit fcy 
				INNER JOIN inv_unit inv ON inv.gkey=fcy.unit_gkey 
				WHERE inv.id=inv_unit.id AND inv.category='IMPRT' AND fcy.time_out < inv_unit_fcy_visit.time_out 
				ORDER BY inv.gkey DESC FETCH FIRST 1 ROWS ONLY)
				ELSE
				''
				END)  AS unstuffingDate,
				inv_unit_fcy_visit.last_pos_locid AS depoName,
				r.id AS customerId,Y.name AS concustomername,r.id AS mlo,'DRAFT' AS STATUS,inv_unit.category
				FROM inv_unit
				INNER JOIN inv_unit_fcy_visit ON inv_unit_fcy_visit.unit_gkey=inv_unit.gkey
				INNER JOIN road_truck_transactions ON road_truck_transactions.unit_gkey=inv_unit.gkey
				INNER JOIN road_truck_visit_details ON road_truck_transactions.truck_visit_gkey=road_truck_visit_details.tvdtls_gkey
				INNER JOIN  ( ref_bizunit_scoped r   
				LEFT JOIN ( ref_agent_representation X   
				LEFT JOIN ref_bizunit_scoped Y ON X.agent_gkey=Y.gkey )                
				ON r.gkey=X.bzu_gkey)  ON r.gkey = inv_unit.line_op
				WHERE  road_truck_visit_details.created >= to_date(CONCAT('$offhire_date',' 08:00:00'),'yyyy-mm-dd hh24-mi-ss')
				AND road_truck_visit_details.created <= to_date(CONCAT('$offhire_date',' 08:00:00'),'yyyy-mm-dd hh24-mi-ss')+1  
				AND road_truck_transactions.stage_id='Out Gate' AND road_truck_transactions.status !='CANCEL'
				AND road_truck_transactions.ctr_freight_kind='MTY' 
				AND r.id='$mlo' 
				ORDER BY line_id";
			//  25/10/2021 -- commented for HDS -- intakhab
			//	echo "<br>";
				$rslt_offhire_details=oci_parse($con_sparcsn4_oracle,$sql_offhire_details);
				oci_execute($rslt_offhire_details);
				
				$k=1;
		
				while(($row_offhire_details=oci_fetch_object($rslt_offhire_details)) !=false)
				{
					$berthQuery="SELECT argo_quay.id AS berth FROM argo_quay 
					INNER JOIN vsl_vessel_berthings ON vsl_vessel_berthings.quay=argo_quay.gkey 
					INNER JOIN vsl_vessel_visit_details ON vsl_vessel_visit_details.vvd_gkey=vsl_vessel_berthings.vvd_gkey 
					WHERE vsl_vessel_visit_details.ib_vyg='$berthRow->BERTH';
					ORDER BY vsl_vessel_visit_details.vvd_gkey DESC FETCH FIRST 1 ROWS ONLY";
					$berthRes=oci_parse($con_sparcsn4_oracle,$berthQuery);
					oci_execute($berthRes);
					$berthRow=oci_fetch_object($berthRes);
					$ibCarrierNameQuery="SELECT vsl_vessels.name AS ibCarrierName FROM vsl_vessels 
					INNER JOIN vsl_vessel_visit_details ON vsl_vessel_visit_details.vessel_gkey=vsl_vessels.gkey 
					WHERE vsl_vessel_visit_details.ib_vyg='$row_offhire_details->IBVISITID' FETCH FIRST 1 ROWS ONLY";
					$ibCarrierNameRes=oci_parse($con_sparcsn4_oracle,$ibCarrierNameQuery);
					oci_execute($ibCarrierNameRes);
					$ibCarrierNameRow=oci_fetch_object($ibCarrierNameRes);

			?>
				<tr align="center">
					<td align="center"><?php  echo $k; ?></td>
					<td align="center"><?php  echo $row_offhire_details->UNITID; ?></td>
					<td align="center"><?php  echo $row_offhire_details->ISOLENGTH; ?></td>
					<td align="center"><?php  echo $row_offhire_details->ISOHEIGHT; ?></td>
					<td align="center"><?php  echo $row_offhire_details->FREIGHTKIND; ?></td>
					<td align="center"><?php  echo $row_offhire_details->IBVISITID; ?></td>
					<td align="center"><?php  echo $ibCarrierNameRow->IBCARRIERNAME; ?></td>
					<td align="center"><?php  echo $berthRow->BERTH; ?></td>
					<!--td align="center"><?php  echo $row_offhire_details->TIMEIN; ?></td-->
					<td align="center"><?php  echo $row_offhire_details->EVENTTO; ?></td>
					<td align="center"><?php  echo $row_offhire_details->TIMEIN; ?></td>
					<td align="center"><?php  echo $row_offhire_details->UNSTUFFINGDATE; ?></td>
					<td align="center"><?php  echo $row_offhire_details->YARDOUTDATE1; ?></td>
					<td align="center"><?php  echo $row_offhire_details->DEPONAME; ?></td>
				</tr>
			<?php 
				$k=$k+1;
				}
			?>
			</tbody>
		</table>
<!--div class="pageBreak"></div-->
<?php } ?>

