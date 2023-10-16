
<?php  
	include("dbConection.php");
	include("dbOracleConnection.php");

	$sql="select vvd_gkey from vsl_vessel_visit_details where ib_vyg='$ddl_imp_rot_no'";

	$sqlVvvGkeyRes=oci_parse($con_sparcsn4_oracle,$sql);

	oci_execute($sqlVvvGkeyRes);
	
	$vvdGkey = "";
	$cond = "";
	
	while(($row = oci_fetch_object($sqlVvvGkeyRes)) != false)
	{
		$vvdGkey = $row->VVD_GKEY;
	}
	
	$sql1="SELECT vsl_vessels.name,COALESCE(vsl_vessel_visit_details.flex_string02,vsl_vessel_visit_details.flex_string03) AS berth_op,COALESCE(argo_quay.id,'') AS berth,
	argo_carrier_visit.ata,argo_carrier_visit.atd FROM vsl_vessel_visit_details
	INNER JOIN argo_carrier_visit ON argo_carrier_visit.cvcvd_gkey=vsl_vessel_visit_details.vvd_gkey
	INNER JOIN vsl_vessel_berthings ON vsl_vessel_berthings.vvd_gkey=vsl_vessel_visit_details.vvd_gkey
	INNER JOIN vsl_vessels ON vsl_vessels.gkey=vsl_vessel_visit_details.vessel_gkey
	INNER JOIN argo_quay ON argo_quay.gkey=vsl_vessel_berthings.quay
	WHERE vsl_vessel_visit_details.vvd_gkey='$vvdGkey'";
	
	$resSql1 = oci_parse($con_sparcsn4_oracle, $sql1);
	oci_execute($resSql1);
	$vsl_name = "";
	$ata = "";
	$berth="";
	$berth_op="";
	while(($row1=oci_fetch_object($resSql1)) !=false)
	{
		$vsl_name = $row1->NAME;
		$ata = $row1->ATA ;
		$berth=$row1->BERTH;
		$berth_op=$row1->BERTH_OP;
	}
	
	?>
<html>
<title>MLO WISE LOADED CONTAINER LIST</title>
<body>
<table width="100%" border ='1' cellpadding='0' cellspacing='0'>
<tr bgcolor="#ffffff" align="center" height="100px">
		<td colspan="12" align="center"><img width="250px" height="80px" src="<?php echo ASSETS_WEB_PATH?>fimg/cpanew.jpg"></td>
		</tr>
		<tr bgcolor="#ffffff" align="center" height="100px">
		<td colspan="14" align="center"><font size="5"><b>MLO WISE LOADED CONTAINER LIST FOR <?php echo $ddl_imp_rot_no; ?></b></font></td>
		
	</tr>
	<tr bgcolor="#ffffff" align="center" height="25px">
		<td colspan="2" align="center"><b>Vessel Name:</b></font></td>
		<td colspan="2" align="center"><b><?php echo $vsl_name ?></b></font></td>
		<td colspan="2" align="center"><b>Berth: </b></font></td>
		<td colspan="2" align="center"><b> <?php echo $berth; ?></b></font></td>
		<td colspan="2" align="center"><b>Berth Operator:</b></font></td>
		<td colspan="4" align="center"><b><?php echo $berth_op; ?></b></font></td>
	</tr>
	<tr bgcolor="#ffffff" align="center" height="25px">
		<td colspan="15" align="center"></td>
	</tr>
	<tr bgcolor="#aaffff" align="center">
		<td><b>S/L</b></td>
		<td><b>Container</b></td>
		<td><b>Size</b></td>
		<td><b>Height</b></td>
		<td><b>Freight Kind</b></td>
		<td><b>Position</b></td>
		<td><b>Seal No</b></td>
		<td><b>Weight</b></td>
		<td><b>Port of Discharge</b></td>
		<td><b>Trailer No</b></td>
		<td><b>Coming From</b></td>
		<td><b>Crain Id</b></td>
	</tr>
<?php

	// $query="
	// SELECT ctmsmis.mis_exp_unit.gkey,ctmsmis.mis_exp_unit.vvd_gkey,CONCAT(SUBSTRING(ctmsmis.mis_exp_unit.cont_id,1,4),SUBSTRING(ctmsmis.mis_exp_unit.cont_id,5)) AS id, 
	// mis_exp_unit.cont_mlo AS mlo,ctmsmis.mis_exp_unit.craine_id,ctmsmis.mis_exp_unit.seal_no,cont_status AS freight_kind,
	// ctmsmis.mis_exp_unit.coming_from AS coming_from,ctmsmis.mis_exp_unit.pod,ctmsmis.mis_exp_unit.stowage_pos, 
	// ctmsmis.mis_exp_unit.user_id,ctmsmis.mis_exp_unit.goods_and_ctr_wt_kg AS goods_and_ctr_wt_kg,ctmsmis.mis_exp_unit.truck_no
	// FROM ctmsmis.mis_exp_unit 
	// WHERE mis_exp_unit.vvd_gkey='$vvdGkey' AND  mis_exp_unit.preAddStat='0' AND snx_type=0 AND mis_exp_unit.delete_flag='0' ORDER BY mis_exp_unit.cont_mlo,cont_status
	// ";
 $query="
	SELECT vsl_vessel_visit_details.vvd_gkey, CONCAT(CONCAT(substr(inv_unit.id,1,4),' '),substr(inv_unit.id,5)) AS id,inv_unit_fcy_visit.time_in AS fcy_time_in,
	SUBSTR(ref_equip_type.nominal_length,-2, LENGTH( ref_equip_type.nominal_length)) AS cont_size, SUBSTR(ref_equip_type.nominal_height, -2, LENGTH( ref_equip_type.nominal_height)) AS height,inv_unit.seal_nbr1 AS seal_no,
	ref_equip_type.id AS iso,ref_bizunit_scoped.id AS mlo,inv_unit.freight_kind,inv_unit.goods_and_ctr_wt_kg AS goods_and_ctr_wt_kg,
	ref_commodity.short_name,inv_unit.remark,
	(SELECT rt.truck_id FROM road_truck_transactions rtt
	INNER JOIN road_trucks rt ON rt.trkco_gkey=rtt.trkco_gkey
	WHERE rtt.unit_gkey=inv_unit.gkey fetch first 1 rows only) AS truck_no,inv_unit_fcy_visit.last_pos_slot AS stowage_pos,
	REF_ROUTING_POINT.ID as pod,inv_unit_fcy_visit.LAST_POS_LOCTYPE AS coming_from
	FROM  inv_unit 
	INNER JOIN inv_unit_fcy_visit ON inv_unit_fcy_visit.unit_gkey=inv_unit.gkey
	INNER JOIN argo_carrier_visit ON argo_carrier_visit.gkey=inv_unit_fcy_visit.actual_ib_cv
	INNER JOIN vsl_vessel_visit_details ON vsl_vessel_visit_details.vvd_gkey=argo_carrier_visit.cvcvd_gkey
	LEFT JOIN inv_goods ON inv_goods.gkey=inv_unit.goods
	LEFT JOIN ref_commodity ON ref_commodity.gkey=inv_goods.commodity_gkey
	INNER JOIN ref_equipment ON ref_equipment.gkey=inv_unit.eq_gkey
	INNER JOIN ref_equip_type ON ref_equip_type.gkey=ref_equipment.eqtyp_gkey
	INNER JOIN ref_bizunit_scoped ON ref_bizunit_scoped.gkey=inv_unit.line_op
	INNER JOIN REF_ROUTING_POINT ON INV_UNIT.POD1_GKEY = REF_ROUTING_POINT.GKEY
	WHERE  vsl_vessel_visit_details.vvd_gkey='$vvdGkey'
	";
	$query=oci_parse($con_sparcsn4_oracle,$query);
	oci_execute($query);

	

	
	$i=0;
	$j=0;
	
	$mlo="";
	
	while(($row=oci_fetch_object($query)) !=false){
	$i++;



		

	if($mlo!=$row->MLO){
		if($j>0){
		?>
		<tr bgcolor="#aaffff" valign="center"><td colspan="2"><font size="4"><b>&nbsp;&nbsp;Total Container (<?php echo $mlo; ?>):</b></font></td><td  colspan="15">&nbsp;&nbsp;<font size="4"><b><?php  echo $j;?></b></font></td></tr>
		<?php
		}
		?>
		<tr bgcolor="#F0F4CA" valign="center"><td  colspan="15">&nbsp;&nbsp;<font size="4"><b><?php  ?></b></font></td></tr>
		<?php
		$j=1;
		
	}else{
		$j++;
		
	}
	$mlo=$row->MLO;
	?>
	<tr align="center">
		<td><?php if($row->ID) echo $j; else echo "&nbsp;";?></td>
		<td><?php if($row->ID) echo $row->ID; else echo "&nbsp;";?></td>
	
		<td><?php if($row->CONT_SIZE) echo $row->CONT_SIZE; else echo "&nbsp;";?></td>

		<td><?php if($row->HEIGHT) echo $row->HEIGHT; else echo "&nbsp;";?></td>

		<td><?php if($row->FREIGHT_KIND) echo $row->FREIGHT_KIND; else echo "&nbsp;";?></td>
		<td><?php if($row->STOWAGE_POS) echo $row->STOWAGE_POS; else echo "&nbsp;";?></td>
		<td><?php if($row->SEAL_NO) echo $row->SEAL_NO; else echo "&nbsp;";?></td>
		<td><?php if($row->GOODS_AND_CTR_WT_KG) echo $row->GOODS_AND_CTR_WT_KG; else echo "&nbsp;";?></td>
		<td><?php if($row->POD) echo $row->POD; else echo "&nbsp;";?></td>
		<td><?php if($row->TRUCK_NO) echo $row->TRUCK_NO; else echo "&nbsp;";?></td>
		<td><?php if($row->COMING_FROM) echo $row->COMING_FROM; else echo "&nbsp;";?></td>
		<!-- <td><?php if($row->craine_id) echo $row->craine_id; else echo "&nbsp;";?></td> -->
		<td></td> 
	</tr>
<?php } ?>

<tr bgcolor="#aaffff" valign="center"><td colspan="2"><font size="4"><b>&nbsp;&nbsp;Total Container (<?php echo $mlo; ?>):</b></font></td><td  colspan="15">&nbsp;&nbsp;<font size="4"><b><?php  echo $j;?></b></font></td></tr>
<tr bgcolor="#aaaaff" valign="center"><td colspan="2"><font size="4"><b>&nbsp;&nbsp;Grand Total:</b></font></td><td  colspan="15">&nbsp;&nbsp;<font size="4"><b><?php  echo $i;?></b></font></td></tr>
</table>
<br />
<br />
<?php
?>
<?php
?>		
	</BODY>
</HTML>
<?php ?>