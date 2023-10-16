<?php if($_POST['options']=='html'){?>
<HTML>
	<HEAD>
</HEAD>
<BODY>

	<?php } 
	else if($_POST['options']=='xl'){
		header("Content-type: application/octet-stream");
		header("Content-Disposition: attachment; filename=EXPORT_CONTAINER_GATE_IN_LIST.xls;");
		header("Content-Type: application/ms-excel");
		header("Pragma: no-cache");
		header("Expires: 0");

	}
	
	?>

	<table border=0 width="100%">				
		<tr align="center">
			<!--td colspan="12" align="center"><img align="middle"  width="220px" height="70px" src="<?php echo ASSETS_WEB_PATH?>fimg/cpanew.jpg"></td-->
		</tr>
			
		<tr align="center">
			<td colspan="12" align="center"><font size="4"><b><u><?php echo $title;?></u></b></font></td>
		</tr>
	</table>
		

	<table width="100%" border ='1' cellpadding='0' cellspacing='0'>
	<thead>
	<tr align="center">
		<td align="center" style="border-width:1px;border-style: double;" ><b>Sl.NO</b></td>
		<td align="center" style="border-width:1px;border-style: double;"><b>CONTAINER NO</b></td>
		<td align="center" style="border-width:1px;border-style: double;" ><b>SIZE</b></td>
		<td align="center" style="border-width:1px;border-style: double;" ><b>HEIGHT</b></td>
		<td align="center" style="border-width:1px;border-style: double;" ><b>SEAL NO</b></td>
		<td align="center" style="border-width:1px;border-style: double;" ><b>MLO</b></td>
		<td align="center" style="border-width:1px;border-style: double;" ><b>TYPE</b></td>
		<td align="center" style="border-width:1px;border-style: double;" ><b>VESSEL NAME</b></td>
		<td align="center" style="border-width:1px;border-style: double;" ><b>TIME IN</b></td>
	</tr>
	</thead>
<?php

include('dbOracleConnection.php');
// Check Shipping Agent Exist , After Login Get the Shipping Agent Id.
$chkListQuery= "SELECT count(inv.id) chkNum											 
	FROM inv_unit inv  
	INNER JOIN inv_unit_fcy_visit ON inv_unit_fcy_visit.unit_gkey=inv.gkey
	INNER JOIN argo_carrier_visit ON argo_carrier_visit.gkey=inv_unit_fcy_visit.actual_ob_cv
	INNER JOIN vsl_vessel_visit_details ON vsl_vessel_visit_details.vvd_gkey=argo_carrier_visit.cvcvd_gkey
	INNER JOIN ref_bizunit_scoped g ON vsl_vessel_visit_details.bizu_gkey = g.gkey
	where g.id='$login_id_ship' and vsl_vessel_visit_details.ob_vyg='$rotation_no' 
	order by inv_unit_fcy_visit.time_in desc";
$rtnChkList=oci_parse($con_sparcsn4_oracle,$chkListQuery);
oci_execute($rtnChkList,OCI_DEFAULT);
$rowChkList = oci_fetch_object($rtnChkList);

if($rowChkList->CHKNUM > 0)
{
$expQuery="SELECT inv_unit.id,substr(ref_equip_type.nominal_length,-2) siz,
	substr(ref_equip_type.nominal_height,-2)/10 AS height,inv_unit.seal_nbr1,vsl_vessels.name,
	g.id AS MLO,inv_unit.category,inv_unit.freight_kind,
	vsl_vessel_visit_details.ob_vyg AS rotation,
	inv_unit_fcy_visit.time_in											 
	FROM inv_unit
	LEFT JOIN inv_unit_fcy_visit ON inv_unit_fcy_visit.unit_gkey=inv_unit.gkey
	LEFT JOIN argo_carrier_visit ON argo_carrier_visit.gkey=inv_unit_fcy_visit.actual_ob_cv
	LEFT JOIN vsl_vessel_visit_details ON vsl_vessel_visit_details.vvd_gkey=argo_carrier_visit.cvcvd_gkey
	LEFT JOIN vsl_vessels ON vsl_vessels.gkey=vsl_vessel_visit_details.vessel_gkey
	LEFT JOIN ref_bizunit_scoped g ON inv_unit.line_op = g.gkey
	LEFT JOIN ref_equipment ON inv_unit.eq_gkey=ref_equipment.gkey
	LEFT JOIN ref_equip_type ON ref_equipment.eqtyp_gkey=ref_equip_type.gkey 
	WHERE vsl_vessel_visit_details.ob_vyg='$rotation_no' and 
	inv_unit_fcy_visit.time_in is not null  
	ORDER BY inv_unit.gkey DESC";
$rtnExpQuery=oci_parse($con_sparcsn4_oracle,$expQuery);
oci_execute($rtnExpQuery,OCI_DEFAULT);
$i=0;	
while (($rowExpQuery = oci_fetch_object($rtnExpQuery)) != false){
	$i++;
?>
<tbody>
<tr align="center">
		<td align="center"><?php  echo $i;?></td>
		<td align="center"><?php if($rowExpQuery->ID) echo $rowExpQuery->ID; else echo "&nbsp;";?></td>
		<td align="center"><?php if($rowExpQuery->SIZ) echo $rowExpQuery->SIZ; else echo "&nbsp;";?></td>
		<td align="center"><?php if($rowExpQuery->HEIGHT) echo $rowExpQuery->HEIGHT; else echo "&nbsp;";?></td>
		<td align="center"><?php if($rowExpQuery->SEAL_NBR1) echo $rowExpQuery->SEAL_NBR1; else echo "&nbsp;";?></td>
		<td align="center"><?php if($rowExpQuery->MLO) echo $rowExpQuery->MLO; else echo "&nbsp;";?></td>
		<td align="center"><?php if($rowExpQuery->FREIGHT_KIND) echo $rowExpQuery->FREIGHT_KIND; else echo "&nbsp;";?></td>
		<td align="center"><?php if($rowExpQuery->NAME) echo $rowExpQuery->NAME; else echo "&nbsp;";?></td>
		<td align="center"><?php if($rowExpQuery->TIME_IN) echo $rowExpQuery->TIME_IN; else echo "&nbsp;";?></td>
	</tr>
</tbody>
<?php } } else { ?>
<tr align="center">
	<td colspan="9"> No Container Found </td>
</tr>
<?php } ?>
</table>
<?php 
//mysql_close($con_ctmsmis);
if($_POST['options']=='html'){?>	
	</BODY>
</HTML>
<?php }?>
<?php oci_close($con_sparcsn4_oracle); ?>
