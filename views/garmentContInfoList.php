
<?php 


	include("dbConection.php");
	include("dbOracleConnection.php");
	
	// $sql=mysqli_query($con_sparcsn4,"select vvd_gkey from sparcsn4.vsl_vessel_visit_details where ib_vyg='$ddl_imp_rot_no'");
	// $row=mysqli_fetch_object($sql);
	// $vvdGkey=$row->vvd_gkey;

	$Query1="select vvd_gkey from vsl_vessel_visit_details where ib_vyg='$ddl_imp_rot_no' fetch first 1 rows only";
	
    $sql= oci_parse($con_sparcsn4_oracle,$Query1);
    oci_execute($sql);
	//$rowVVD = oci_fetch_object($sql);
	 $vvdGkey="";
	 while(($rowVVD=oci_fetch_object($sql))!=false){
		 $vvdGkey=$rowVVD->VVD_GKEY;
	 }
	// $row=mysqli_fetch_object($sql);
	 //$vvdGkey=$rowVVD->VVD_GKEY;
	
	// $sql1=mysqli_query($con_sparcsn4,"SELECT vsl_vessels.name,DATE(ata) AS arrival
	// FROM sparcsn4.vsl_vessel_visit_details
	// INNER JOIN sparcsn4.argo_carrier_visit ON  sparcsn4.argo_carrier_visit.cvcvd_gkey=sparcsn4.vsl_vessel_visit_details.vvd_gkey
	// INNER JOIN sparcsn4.vsl_vessels ON sparcsn4.vsl_vessels.gkey=sparcsn4.vsl_vessel_visit_details.vessel_gkey
	// WHERE vsl_vessel_visit_details.vvd_gkey=$vvdGkey");
	// $row1=mysqli_fetch_object($sql1);

    $Query2="SELECT vsl_vessels.name,to_char(ata,'YYYY-MM-DD') AS arrival
	FROM vsl_vessel_visit_details
	INNER JOIN argo_carrier_visit ON  argo_carrier_visit.cvcvd_gkey=vsl_vessel_visit_details.vvd_gkey
	INNER JOIN vsl_vessels ON vsl_vessels.gkey=vsl_vessel_visit_details.vessel_gkey
	WHERE vsl_vessel_visit_details.vvd_gkey=$vvdGkey";

	$sql1= oci_parse($con_sparcsn4_oracle,$Query2);
    oci_execute($sql1);
	$arrival="";
	$name="";
	while(($row1= oci_fetch_object($sql1))!=false){
		$arrival=$row1->ARRIVAL;
		$name=$row1->NAME;
	}

	
	
	
	?>
<html>
<!--title>GARMENTS ITEM CONTAINER LIST</title-->
<body>
<table width="100%" border ='0' cellpadding='0' cellspacing='0'>
	<tr bgcolor="#ffffff" align="center" height="100px">
		<td colspan="12" align="center"><img align="middle"  width="235px" height="75px" src="<?php echo IMG_PATH?>cpanew.jpg"><br/><font size="4"><b>GARMENTS ITEM CONTAINER LIST</b></font></td>
	</tr>
	<!--tr bgcolor="#ffffff" align="center" height="100px">
		<td colspan="14" align="center"><font size="5"><b>GARMENTS ITEM CONTAINER LIST</b></font></td>
	</tr-->
	<tr bgcolor="#ffffff" align="center" height="25px">
		<td colspan="7" align="center"><b>Vessel Name:  </b><?php echo $name .","; ?><b>  Rotation:  </b><?php echo $ddl_imp_rot_no.","; ?><b>  Date of Arrival:  </b><?php echo $arrival; ?></td>		
	</tr>
	<tr bgcolor="#ffffff" align="center" height="25px">
		<td colspan="10" align="center"></td>		
	</tr>
</table>
	<table align="center" width="100%" border ='1' cellpadding='0' cellspacing='0'>
	<tr bgcolor="#aaffff" align="center">
		<td align="center" style="width:8%"><b>S/L</b></td>
		<td align="center" style="width:18%"><b>Container</b></td>
		<td align="center" style="width:8%"><b>Size</b></td>
		<td align="center" style="width:8%"><b>Height</b></td>
		<td align="center" style="width:22%"><b>Discharge Time</b></td>
		<td align="center" style="width:22%"><b>Gateout Time</b></td>
		<td align="center" style="width:8%"><b>Dwel Time</b></td>
	</tr>

<?php
	

	// $query=mysqli_query($con_sparcsn4," SELECT *,
	// (CASE 
	// 	WHEN duel<=4 AND time_out IS NOT NULL THEN 1
	// 	WHEN duel>4 AND duel<=12 AND time_out IS NOT NULL THEN 2
	// 	WHEN duel>12 AND duel<=28 AND time_out IS NOT NULL THEN 3
	// 	WHEN duel>28 OR time_out IS NULL THEN 4
	// ELSE 0 END) AS sl,
	// (CASE 
	// 	WHEN duel<=4 AND time_out IS NOT NULL THEN '0-4 days'
	// 	WHEN duel>4 AND duel<=12 AND time_out IS NOT NULL THEN '5-12 days'
	// 	WHEN duel>12 AND duel<=28 AND time_out IS NOT NULL THEN '13-28 days'
	// 	WHEN duel>28 OR time_out IS NULL THEN '29-56 or more days'
	// ELSE 0 END) AS dweldays
	// FROM (
	// SELECT inv_unit.id,sparcsn4.inv_unit_fcy_visit.time_in,sparcsn4.inv_unit_fcy_visit.time_out,
	// ref_commodity.short_name,TIMESTAMPDIFF(DAY,time_in,IFNULL(time_out,NOW())) AS duel,
	// RIGHT(sparcsn4.ref_equip_type.nominal_length,2) AS size,
	// RIGHT(sparcsn4.ref_equip_type.nominal_height,2)/10 AS height	   
	// FROM sparcsn4.inv_unit 
	// INNER JOIN sparcsn4.inv_unit_fcy_visit ON inv_unit_fcy_visit.unit_gkey=inv_unit.gkey 
	// INNER JOIN sparcsn4.inv_unit_equip ON inv_unit.gkey=inv_unit_equip.unit_gkey 
	// INNER JOIN sparcsn4.ref_equipment ON inv_unit_equip.eq_gkey=ref_equipment.gkey
	// INNER JOIN sparcsn4.ref_equip_type ON ref_equipment.eqtyp_gkey=ref_equip_type.gkey     
	// INNER JOIN sparcsn4.argo_carrier_visit ON  argo_carrier_visit.gkey=inv_unit.declrd_ib_cv
	// INNER JOIN sparcsn4.inv_goods ON inv_unit.goods=inv_goods.gkey
	// INNER JOIN sparcsn4.ref_commodity ON sparcsn4.inv_goods.commodity_gkey=ref_commodity.gkey
	// INNER JOIN sparcsn4.vsl_vessel_visit_details ON sparcsn4.vsl_vessel_visit_details.vvd_gkey=sparcsn4.argo_carrier_visit.cvcvd_gkey
	// WHERE sparcsn4.inv_unit.category='IMPRT'  
	// AND sparcsn4.vsl_vessel_visit_details.ib_vyg='$ddl_imp_rot_no'
	// ) AS a WHERE short_name LIKE '%gar%' ORDER BY 5");
	$query="SELECT a.*,
	(CASE 
	WHEN duel<=4 AND time_out IS NOT NULL THEN 1
	WHEN duel>4 AND duel<=12 AND time_out IS NOT NULL THEN 2
	WHEN duel>12 AND duel<=28 AND time_out IS NOT NULL THEN 3
	WHEN duel>28 OR time_out IS NULL THEN 4
	ELSE 0 END) AS sl,
	(CASE 
	WHEN duel<=4 AND time_out IS NOT NULL THEN '0-4 days' 
	WHEN duel>4 AND duel<=12 AND time_out IS NOT NULL THEN '5-12 days'
	WHEN duel>12 AND duel<=28 AND time_out IS NOT NULL THEN '13-28 days'
	WHEN duel>28 OR time_out IS NULL THEN '29-56 or more days'
	ELSE '0' END) AS dweldays
	
	FROM (
	SELECT inv_unit.id,inv_unit_fcy_visit.time_in,inv_unit_fcy_visit.time_out,
	ref_commodity.short_name,
	EXTRACT(DAY FROM time_in-NVL(time_out,CURRENT_DATE))AS duel,
	SUBSTR(ref_equip_type.nominal_length,-2) AS siz,
	SUBSTR(ref_equip_type.nominal_height,-2)/10 AS height	   
	FROM inv_unit 
	INNER JOIN inv_unit_fcy_visit ON inv_unit_fcy_visit.unit_gkey=inv_unit.gkey 
	INNER JOIN ref_equipment ON inv_unit.eq_gkey=ref_equipment.gkey
	INNER JOIN ref_equip_type ON ref_equipment.eqtyp_gkey=ref_equip_type.gkey     
	INNER JOIN argo_carrier_visit ON  argo_carrier_visit.gkey=inv_unit.declrd_ib_cv
	INNER JOIN inv_goods ON inv_unit.goods=inv_goods.gkey
	INNER JOIN ref_commodity ON inv_goods.commodity_gkey=ref_commodity.gkey
	INNER JOIN vsl_vessel_visit_details ON vsl_vessel_visit_details.vvd_gkey=argo_carrier_visit.cvcvd_gkey
	WHERE inv_unit.category='IMPRT'  
	AND vsl_vessel_visit_details.ib_vyg='$ddl_imp_rot_no'
	)  a WHERE short_name LIKE '%gar%' ORDER BY 5";
   
	$result1 = oci_parse($con_sparcsn4_oracle,$query);
	oci_execute($result1);
	
	$i=0;
	$j=0;
	
	$dweldays="";
	// while($row=mysqli_fetch_object($query))
	while(($row= oci_fetch_object($result1)) != false)
	{
	$i++;
	

	if($dweldays!=$row->DWELDAYS){
		if($j>0){
		?>	
		<tr bgcolor="#aaffff"><td valign="center" colspan="6"><font size="4"><b>&nbsp;&nbsp;Total Container (<?php echo $dweldays; ?>):</b></font></td><td align="center">&nbsp;&nbsp;<font size="4"><b><?php  echo $j;?></b></font></td></tr>
		<?php
		}
		?>
		<tr bgcolor="#F0F4CA"><td  valign="center" colspan="7">&nbsp;&nbsp;<font size="4"><b><?php  if($row->DWELDAYS) echo $row->DWELDAYS; else echo "&nbsp;"; ?></b></font></td></tr>
		<?php
		
		
		$j=1;
		
	}else{
		$j++;		
	}
	$dweldays=$row->DWELDAYS;
	
	
?>
     
<tr align="center">
<td align="center" ><?php if($row->ID) echo $j; else echo "&nbsp;";?></td>
		<td align="center" ><?php if($row->ID) echo $row->ID; else echo "&nbsp;";?></td>
		<td align="center" ><?php if($row->SIZ) echo $row->SIZ; else echo "&nbsp;"?></td>
		<td align="center" ><?php if($row->HEIGHT) echo $row->HEIGHT; else echo "&nbsp;";?></td>
		<td align="center" ><?php if($row->TIME_IN) echo $row->TIME_IN; else echo "&nbsp;";?></td>
		<td align="center" ><?php if($row->TIME_OUT) echo $row->TIME_OUT; else echo "Still in Yard";?></td>
		<td align="center"><?php echo $row->DUEL;?></td>
		
</tr>

<?php } ?>
<tr bgcolor="#aaffff" valign="center"><td colspan="6"><font size="4"><b>&nbsp;&nbsp;Total Container (<?php echo $dweldays; ?>):</b></font></td><td align="center" >&nbsp;&nbsp;<font size="4"><b><?php  echo $j;?></b></font></td></tr> 
<tr bgcolor="#aaaaff" valign="center"><td colspan="6"><font size="4"><b>&nbsp;&nbsp;Grand Total:</b></font></td><td align="center" >&nbsp;&nbsp;<font size="4"><b><?php  echo $i;?></b></font></td></tr>
</table>
<br />
<br />

<?php
mysqli_close($con_sparcsn4);
oci_close($con_sparcsn4_oracle);
 if($_POST['options']=='html'){?>		
	</BODY>
</HTML>
<?php }?>
