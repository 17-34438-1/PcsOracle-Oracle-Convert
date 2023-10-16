<?php if(@$_POST['options']=='html'){?>

<?php } 
else if(@$_POST['options']=='xl'){
	header("Content-type: application/octet-stream");
	header("Content-Disposition: attachment; filename=APPRAISE_RE_SLOT_LOCATION.xls;");
	header("Content-Type: application/ms-excel");
	header("Pragma: no-cache");
	header("Expires: 0");

}
include("dbConection.php");
include("dbOracleConnection.php");
?>
<html>

<body>
<table width="100%" border ='0' cellpadding='0' cellspacing='0' align="center">
<tr align="center">
	<!--td colspan="10" align="center"><font size="4"><b>CHITTAGONG PORT AUTHORITY,CHITTAGONG</b></font></td-->
	<td colspan="10" align="center"><img align="middle"  width="220px" height="70px" src="<?php echo IMG_PATH?>cpanew.jpg"></td>
</tr><tr align="center">
	<td colspan="10" align="center"><font size="4"><b>APPRAISE RE-SLOT LOCATION</b></font></td>
	
</tr>	
<tr align="center">
	<td colspan="10" align="center"><font size="4"><b>
		<?php 
		/*$searchFrom = $fromDt." ".$fromTime.":00"; 
		$searchTo = $fromDt." ".$toTime.":00"; 
		echo " FROM : ".$searchFrom." TO : ".$searchTo; */
		echo " DATE : ".$fromDt;
		?>
		</b></font>
	</td>
</tr>	

</table>
<table width="100%" border ='1' cellpadding='0' cellspacing='0'>
<thead>
<tr  align="center">
	<th style=""><b>Sl No.</b></th>
	<th style=""><b>Container No.</b></th>
	<th style=""><b>Length</b></th>
	<!--th style=""><b>Height</b></th-->
	<th style=""><b>Location</b></th>
	<th style=""><b>Assignment Type</b></th>
	<th style=""><b>Vsl.Ref</b></th>		
	<th style=""><b>To Pos Slot</b></th>
	<th style=""><b>Trailer</b></th>
	<!--th style=""><b>Carried Time</b></th-->
	<th style=""><b>Remarks</b></th>
</tr>
</thead>
<tbody>
<?php
/*( select sparcsn4.vsl_vessels.name from sparcsn4.vsl_vessels
	inner join sparcsn4.vsl_vessel_visit_details on vsl_vessels.gkey=vsl_vessel_visit_details.vessel_gkey
	inner join sparcsn4.argo_visit_details ON sparcsn4.vsl_vessel_visit_details.vvd_gkey=sparcsn4.argo_visit_details.gkey
	INNER JOIN sparcsn4.argo_carrier_visit ON sparcsn4.argo_visit_details.gkey=sparcsn4.argo_carrier_visit.cvcvd_gkey
	where sparcsn4.argo_carrier_visit.gkey=inv_unit.declrd_ib_cv or sparcsn4.argo_carrier_visit.gkey=inv_unit.cv_gkey
	) as vesselName,*/
			$textAr = explode(",", $contList);
	$textAr = array_filter($textAr,'trim'); // remove any extra \r characters left behind
	$allCont="";
	foreach ($textAr as $line) {
		// processing here. 
		 $allCont = $allCont."'".trim(str_replace(' ','',$line))."',";
	}
	$selectedCont = rtrim($allCont,",");
	
	
$cont_id="";
/*$strQuery = "SELECT * FROM (
	SELECT id,inv_unit_fcy_visit.time_out,category,inv_unit.remark,
	(select right(sparcsn4.ref_equip_type.nominal_height,2)/10 from ref_equip_type 
	INNER JOIN sparcsn4.ref_equipment ON ref_equipment.eqtyp_gkey=ref_equip_type.gkey
	INNER JOIN sparcsn4.inv_unit_equip ON inv_unit_equip.eq_gkey=ref_equipment.gkey
	where sparcsn4.inv_unit_equip.unit_gkey=inv_unit.gkey
	) as height,

	(select right(sparcsn4.ref_equip_type.nominal_length,2) from ref_equip_type 
	INNER JOIN sparcsn4.ref_equipment ON ref_equipment.eqtyp_gkey=ref_equip_type.gkey
	INNER JOIN sparcsn4.inv_unit_equip ON inv_unit_equip.eq_gkey=ref_equipment.gkey
	where sparcsn4.inv_unit_equip.unit_gkey=inv_unit.gkey
	) as size,
	
	( select sparcsn4.vsl_vessels.name from sparcsn4.vsl_vessel_visit_details
	inner join sparcsn4.vsl_vessels on sparcsn4.vsl_vessels.gkey=sparcsn4.vsl_vessel_visit_details.vessel_gkey
	where sparcsn4.vsl_vessel_visit_details.ib_vyg=sparcsn4.inv_unit_fcy_visit.flex_string10 
	order by vvd_gkey desc limit 1
	) as vesselName,
	(select inv_goods.destination from inv_goods where inv_goods.gkey=inv_unit.goods) as destination,
	(SELECT fm_pos_slot FROM inv_move_event WHERE inv_move_event.ufv_gkey=inv_unit_fcy_visit.gkey AND move_kind IN ('YARD','SHFT') ORDER BY mve_gkey DESC LIMIT 1) AS fm_pos_slot,
	(SELECT to_pos_slot FROM inv_move_event WHERE inv_move_event.ufv_gkey=inv_unit_fcy_visit.gkey AND move_kind IN ('YARD','SHFT') ORDER BY mve_gkey DESC LIMIT 1) AS to_pos_slot,
	(SELECT short_name FROM xps_che 
	INNER JOIN inv_move_event ON xps_che.gkey=inv_move_event.che_carry  WHERE inv_move_event.ufv_gkey=inv_unit_fcy_visit.gkey AND move_kind IN ('YARD','SHFT')ORDER BY mve_gkey DESC LIMIT 1) AS che_carry,
	(SELECT move_kind FROM inv_move_event WHERE inv_move_event.ufv_gkey=inv_unit_fcy_visit.gkey AND move_kind IN ('YARD','SHFT')ORDER BY mve_gkey DESC LIMIT 1) AS move_kind,
	sparcsn4.config_metafield_lov.mfdch_desc,
	(SELECT t_put FROM inv_move_event WHERE inv_move_event.ufv_gkey=inv_unit_fcy_visit.gkey AND move_kind IN ('YARD','SHFT')ORDER BY mve_gkey DESC LIMIT 1) AS carriedTime,
	inv_unit.freight_kind,
	DATE(inv_unit_fcy_visit.flex_date01) flex_date01,

	(SELECT ctmsmis.cont_yard((SELECT SUBSTRING(sparcsn4.srv_event_field_changes.new_value,7)
	FROM sparcsn4.srv_event
	INNER JOIN sparcsn4.srv_event_field_changes ON sparcsn4.srv_event_field_changes.event_gkey=sparcsn4.srv_event.gkey
	WHERE sparcsn4.srv_event.applied_to_gkey=inv_unit.gkey AND sparcsn4.srv_event.event_type_gkey=18 LIMIT 1))) AS Yard_No

	FROM sparcsn4.inv_unit 
	INNER JOIN sparcsn4.inv_unit_fcy_visit ON inv_unit_fcy_visit.unit_gkey=inv_unit.gkey

	INNER JOIN sparcsn4.config_metafield_lov ON inv_unit.flex_string01=config_metafield_lov.mfdch_value


	 WHERE DATE(inv_unit_fcy_visit.flex_date01) ='$fromDt' and id in($selectedCont)


	) AS ass order by id";*/
	
$strQuery = "SELECT * FROM (
	SELECT id,inv_unit_fcy_visit.time_out,category,inv_unit.remark,
	(SELECT substr(ref_equip_type.nominal_height,-2)/10 FROM ref_equip_type 
	INNER JOIN ref_equipment ON ref_equipment.eqtyp_gkey=ref_equip_type.gkey
	INNER JOIN inv_unit ON inv_unit.eq_gkey=ref_equipment.gkey
	INNER JOIN inv_unit_fcy_visit  ON inv_unit_fcy_visit.unit_gkey=inv_unit.gkey
	WHERE inv_unit_fcy_visit.unit_gkey=inv_unit.gkey  FETCH FIRST 1 ROWS ONLY
	) AS height,
	
	(SELECT substr(ref_equip_type.nominal_length,-2) FROM ref_equip_type 
	INNER JOIN ref_equipment ON ref_equipment.eqtyp_gkey=ref_equip_type.gkey
	INNER JOIN inv_unit ON inv_unit.eq_gkey=ref_equipment.gkey
	INNER JOIN inv_unit_fcy_visit  ON inv_unit_fcy_visit.unit_gkey=inv_unit.gkey
	WHERE inv_unit_fcy_visit.unit_gkey=inv_unit.gkey  FETCH FIRST 1 ROWS ONLY
	) AS siz,
	
	( SELECT vsl_vessels.name FROM vsl_vessel_visit_details
	INNER JOIN vsl_vessels ON vsl_vessels.gkey=vsl_vessel_visit_details.vessel_gkey
	WHERE vsl_vessel_visit_details.ib_vyg=inv_unit_fcy_visit.flex_string10 
	ORDER BY vvd_gkey DESC FETCH FIRST 1 ROWS ONLY 
	) AS vesselName,
	(SELECT inv_goods.destination FROM inv_goods WHERE inv_goods.gkey=inv_unit.goods) AS destination,
	(SELECT fm_pos_slot FROM inv_move_event WHERE inv_move_event.ufv_gkey=inv_unit_fcy_visit.gkey AND move_kind IN ('YARD','SHFT') ORDER BY mve_gkey DESC FETCH FIRST 1 ROWS ONLY ) AS fm_pos_slot,
	(SELECT to_pos_slot FROM inv_move_event WHERE inv_move_event.ufv_gkey=inv_unit_fcy_visit.gkey AND move_kind IN ('YARD','SHFT') ORDER BY mve_gkey DESC FETCH FIRST 1 ROWS ONLY ) AS to_pos_slot,
	(SELECT short_name FROM xps_che 
	INNER JOIN inv_move_event ON xps_che.gkey=inv_move_event.che_carry  WHERE inv_move_event.ufv_gkey=inv_unit_fcy_visit.gkey AND move_kind IN ('YARD','SHFT')ORDER BY mve_gkey DESC FETCH FIRST 1 ROWS ONLY ) AS che_carry,
	(SELECT move_kind FROM inv_move_event WHERE inv_move_event.ufv_gkey=inv_unit_fcy_visit.gkey AND move_kind IN ('YARD','SHFT')ORDER BY mve_gkey DESC FETCH FIRST 1 ROWS ONLY ) AS move_kind,
	config_metafield_lov.mfdch_desc,
	(SELECT t_put FROM inv_move_event WHERE inv_move_event.ufv_gkey=inv_unit_fcy_visit.gkey AND move_kind IN ('YARD','SHFT')ORDER BY mve_gkey DESC FETCH FIRST 1 ROWS ONLY ) AS carriedTime,
	inv_unit.freight_kind,
	to_date(to_char(inv_unit_fcy_visit.flex_date01,'yyyy-mm-dd'),'yyyy-mm-dd')  flex_date01
	
	
	FROM inv_unit 
	INNER JOIN inv_unit_fcy_visit ON inv_unit_fcy_visit.unit_gkey=inv_unit.gkey
	INNER JOIN config_metafield_lov ON inv_unit.flex_string01=config_metafield_lov.mfdch_value
	
	WHERE to_date(to_char(inv_unit_fcy_visit.flex_date01,'yyyy-mm-dd'),'yyyy-mm-dd') =to_date('$fromDt','yyyy-mm-dd') AND id IN($selectedCont)
	)  ass ORDER BY id";

$query=oci_parse($con_sparcsn4_oracle,$strQuery);
oci_execute($query);

$i=0;
while(($row=oci_fetch_object($query)) != false) {
$i++;

?>
<tr align="center">
	<td><?php  echo $i;?></td>
	<td><?php if($row->ID) echo $row->ID; else echo "&nbsp;";?></td>
	<td><?php if($row->SIZ) echo $row->SIZ; else echo "&nbsp;";?></td>
	<!--td><?php if($row->HEIGHT) echo $row->HEIGHT; else echo "&nbsp;";?></td-->
	<td><?php if($row->FM_POS_SLOT) echo $row->FM_POS_SLOT; else echo "&nbsp;";?></td>		
	<td><?php if($row->MFDCH_DESC) echo $row->MFDCH_DESC; else echo "&nbsp;";?></td>
	<td><?php if($row->VESSELNAME) echo $row->VESSELNAME; else echo "&nbsp;";?></td>		
	<td><?php if($row->DESTINATION) echo $row->DESTINATION; else echo "&nbsp;";?></td>
	<td><?php if($row->CHE_CARRY) echo $row->che_carry; else echo "&nbsp;";?></td>
	<!--td><?php if($row->CARRIEDTIME) echo $row->CARRIEDTIME; else echo "&nbsp;";?></td-->
	<td><?php if($row->REMARK) echo $row->REMARK; else echo "&nbsp;";?></td>
</tr>
<?php } ?>
<?php if(@$_POST['options']=='html'){?>	
<!--tr>
<td colspan="11" align="center">
	<table>
		<tr>
			<b><u>Container's</u></b></br>
		</tr>
			<?php 
			/*$strQuery = "SELECT * FROM (
				SELECT id,inv_unit_fcy_visit.time_out,
				DATE(inv_unit_fcy_visit.flex_date01) flex_date01,
				(SELECT t_put FROM inv_move_event WHERE inv_move_event.ufv_gkey=inv_unit_fcy_visit.gkey AND move_kind IN ('YARD','SHFT')ORDER BY mve_gkey DESC LIMIT 1) AS carriedTime,
				(SELECT ctmsmis.cont_yard((SELECT SUBSTRING(sparcsn4.srv_event_field_changes.new_value,7)
				FROM sparcsn4.srv_event
				INNER JOIN sparcsn4.srv_event_field_changes ON sparcsn4.srv_event_field_changes.event_gkey=sparcsn4.srv_event.gkey
				WHERE sparcsn4.srv_event.applied_to_gkey=inv_unit.gkey AND sparcsn4.srv_event.event_type_gkey=18 LIMIT 1))) AS Yard_No

				FROM sparcsn4.inv_unit 
				INNER JOIN sparcsn4.inv_unit_fcy_visit ON inv_unit_fcy_visit.unit_gkey=inv_unit.gkey

				INNER JOIN sparcsn4.config_metafield_lov ON inv_unit.flex_string01=config_metafield_lov.mfdch_value


				 WHERE DATE(inv_unit_fcy_visit.flex_date01) ='$fromDt'


				) AS ass WHERE yard_no!='GCB' AND time_out IS NOT NULL AND carriedTime between '$searchFrom' and '$searchTo'";
			
			//echo $strQuery;
			$query=mysql_query($strQuery);

			$j=0;
			while($row1=mysql_fetch_object($query)){
			
			$j++;*/
			?>
		<tr>
			<?php //echo $row1->id; ?>,									
		</tr>
			<?php //}?>
		</tbody>
	</table>
</td>
</tr-->
<?php }?>
</table>
<br />
<br />

<?php 
mysqli_close($con_sparcsn4);
if(@$_POST['options']=='html'){?>	

<?php }?>
