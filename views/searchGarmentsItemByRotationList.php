
<?php 


	include("dbConection.php");
	include("dbOracleConnection.php");
	
    $Query1="select vvd_gkey from vsl_vessel_visit_details where ib_vyg='$ddl_imp_rot_no' fetch first 1 rows only";
	$sql="";
	$sql_Res= oci_parse($con_sparcsn4_oracle,$Query1);
    oci_execute($sql_Res);
	
	
	//$rowVVD = oci_fetch_object($sql_Res);	
	$vvd_Gkey="";
	while(($rowVVD = oci_fetch_object($sql_Res))!=false){
     $vvd_Gkey=$rowVVD->VVD_GKEY;
	}
	
	

    $Query2="SELECT vsl_vessels.name,to_char(ata,'YYYY-MM-DD') AS arrival
	FROM vsl_vessel_visit_details
	INNER JOIN argo_carrier_visit ON  argo_carrier_visit.cvcvd_gkey=vsl_vessel_visit_details.vvd_gkey
	INNER JOIN vsl_vessels ON vsl_vessels.gkey=vsl_vessel_visit_details.vessel_gkey
	WHERE vsl_vessel_visit_details.vvd_gkey='$vvd_Gkey' ";
	$sql1= oci_parse($con_sparcsn4_oracle,$Query2);
    oci_execute($sql1);
	$row1= oci_fetch_object($sql1);


	
	?>
<html>
<title>ITEM CONTAINER LIST</title>
<body>
<table width="100%" border ='1' cellpadding='0' cellspacing='0'>
	<tr bgcolor="#ffffff" align="center" height="100px">
		<td colspan="12"><img align="middle"  width="235px" height="75px" src="<?php echo IMG_PATH?>cpanew.jpg"><br/><font size="4"><b>ITEM CONTAINER LIST</b></font></td>
	</tr>
	
	<tr bgcolor="#ffffff" align="center" height="25px">
		<td colspan="7"><b>Vessel Name:  </b><?php echo $row1->NAME.","; ?><b>  Rotation:  </b><?php echo $ddl_imp_rot_no.","; ?><b>  Search Item:  </b><?php echo $ddl_imp_item.","; ?><b>  Date of Arrival:  </b><?php echo $row1->ARRIVAL; ?></td>		
	</tr>
	<tr bgcolor="#ffffff" align="center" height="25px">
		<td colspan="15" align="center"></td>		
	</tr>
	<tr bgcolor="#aaffff" align="center">
		<td><b>S/L</b></td>
		<td><b>Container</b></td>
		<td><b>Size</b></td>
		<td><b>Height</b></td>
		<td><b>Discharge Time</b></td>
		<td><b>Gateout Time</b></td>
		<td><b>Dwel Time</b></td>
	</tr>

<?php
	


	$str="SELECT a.*,
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
	) a WHERE short_name LIKE '%".$ddl_imp_item."%' ORDER BY 5";

		
    $sql3 = oci_parse($con_sparcsn4_oracle,$str);
    oci_execute($sql3);



	
	$i=0;
	$j=0;
	
	$dweldays="";

	while(($row= oci_fetch_object($sql3)) != false)
	{
		$i++;
	
		if($dweldays!=$row->DWELDAYS)
		{
			if($j>0)
			{
		?>
		<tr bgcolor="#aaffff" valign="center"><td colspan="6"><font size="4"><b>&nbsp;&nbsp;Total Container (<?php echo $dweldays; ?>):</b></font></td><td  colspan="15">&nbsp;&nbsp;<font size="4"><b><?php  echo $j;?></b></font></td></tr>
		<?php
			}
		?>
		<tr bgcolor="#F0F4CA" valign="center"><td  colspan="15">&nbsp;&nbsp;<font size="4"><b><?php  if($row->DWELDAYS) echo $row->DWELDAYS; else echo "&nbsp;"; ?></b></font></td></tr>
		<?php				
		$j=1;		
		}
		else
		{
			$j++;		
		}
		$dweldays=$row->DWELDAYS;		
?>
	<tr align="center">
		<td><?php if($row->ID) echo $j; else echo "&nbsp;";?></td>
		<td><?php if($row->ID) echo $row->ID; else echo "&nbsp;";?></td>
		<td><?php if($row->SIZ) echo $row->SIZ; else echo "&nbsp;";?></td>
		<td><?php if($row->HEIGHT) echo $row->HEIGHT; else echo "&nbsp;";?></td>
		<td><?php if($row->TIME_IN) echo $row->TIME_IN; else echo "&nbsp;";?></td>
		
		
		<td><?php if($row->TIME_OUT) echo $row->TIME_OUT; else echo "Still in Yard";?></td>
		<td><?php echo $row->DUEL;?></td>
		
	</tr>

<?php } ?>
<tr bgcolor="#aaffff" valign="center"><td colspan="6"><font size="4"><b>&nbsp;&nbsp;Total Container (<?php echo $dweldays; ?>):</b></font></td><td  colspan="15">&nbsp;&nbsp;<font size="4"><b><?php  echo $j;?></b></font></td></tr>
<tr bgcolor="#aaaaff" valign="center"><td colspan="6"><font size="4"><b>&nbsp;&nbsp;Grand Total:</b></font></td><td  colspan="15">&nbsp;&nbsp;<font size="4"><b><?php  echo $i;?></b></font></td></tr>
</table>
<br />
<br />

<?php
mysqli_close($con_sparcsn4);
oci_free_statement($sql);
oci_free_statement($sql1);
oci_free_statement($query3);
oci_close($con_sparcsn4_oracle);

 if($_POST['options']=='html'){?>		
	</BODY>
</HTML>
<?php }?>
