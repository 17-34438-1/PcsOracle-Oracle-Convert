

<HTML>
	<HEAD>
		<TITLE>OFFDOCK WISE BLOCKED CONTAINER LIST</TITLE>
		<LINK href="../css/report.css" type=text/css rel=stylesheet>
	    <style type="text/css">
<!--
.style1 {font-size: 12px}
-->
        </style>
</HEAD>
<BODY>

<table width="100%" border ='1' cellpadding='0' cellspacing='0'>
<tr bgcolor="#ffffff" align="center" height="50px">
		<td colspan="14" align="center"><font size="5"><b>OFFDOCK WISE BLOCKED CONTAINER LIST</b></font></td>
		
	</tr>
	<tr bgcolor="#ffffff" align="center" height="25px">
		<td colspan="15" align="center"></td>
		
	</tr>
	<tr bgcolor="#A9A9A9" align="center" height="25px">
		<td style="border-width:3px;border-style: double;"><b>SlNo.</b></td>
		<td style="border-width:3px;border-style: double;"><b>Container No.</b></td>
		<td style="border-width:3px;border-style: double;"><b>Vessel Name.</b></td>
		<td style="border-width:3px;border-style: double;"><b>Rotation.</b></td>
		<td style="border-width:3px;border-style: double;"><b>Size.</b></td>
		<td style="border-width:3px;border-style: double;"><b>Height.</b></td>
		<td style="border-width:3px;border-style: double;"><b>MLO</b></td>
		<td style="border-width:3px;border-style: double;"><b>Status</b></td>
		<td style="border-width:3px;border-style: double;"><b>Position</b></td>
		<td style="border-width:3px;border-style: double;"><b>IN Date</b></td>
		<td style="border-width:3px;border-style: double;"><b>Out Date</b></td>
		<td style="border-width:3px;border-style: double;"><b>Total Days</b></td>
	</tr>

<?php

	include("dbConection.php");
	include("dbOracleConnection.php");
	
	$query=mysqli_query($con_sparcsn4,"SELECT *
						 FROM ctmsmis.mis_block_unit 
						 WHERE offdoc_code IS NOT NULL");
	
	$i=0;
	$j=0;
	
	$offdoc_name="";
	while($row=mysqli_fetch_object($query)){
	$i++;

	$convsl_name="";
	$conib_vyg="";
	$consize="";
	$conheight="";
	$contmlo = "";

	/*$dtl_query = mysqli_query($con_sparcsn4,"SELECT RIGHT(nominal_length,2) AS size, RIGHT(sparcsn4.ref_equip_type.nominal_height,2)/10 AS height,
	ref_bizunit_scoped.id AS mlo,ref_bizunit_scoped.name AS mlo_name, sparcsn4.vsl_vessel_visit_details.ib_vyg AS rotation, sparcsn4.vsl_vessels.name AS vsl_name 
	FROM  inv_unit 
	INNER JOIN sparcsn4.inv_unit_equip ON sparcsn4.inv_unit_equip.unit_gkey=inv_unit.gkey 
	INNER JOIN sparcsn4.ref_equipment ON sparcsn4.inv_unit_equip.eq_gkey=sparcsn4.ref_equipment.gkey
	INNER JOIN sparcsn4.ref_equip_type ON sparcsn4.ref_equipment.eqtyp_gkey=sparcsn4.ref_equip_type.gkey
	INNER JOIN sparcsn4.inv_unit_fcy_visit ON sparcsn4.inv_unit_fcy_visit.unit_gkey=sparcsn4.inv_unit.gkey
	LEFT JOIN sparcsn4.ref_bizunit_scoped  ON inv_unit.line_op = ref_bizunit_scoped.gkey 
	LEFT JOIN sparcsn4.argo_carrier_visit ON sparcsn4.inv_unit_fcy_visit.actual_ob_cv = sparcsn4.argo_carrier_visit.gkey
	LEFT JOIN sparcsn4.vsl_vessel_visit_details ON sparcsn4.argo_carrier_visit.cvcvd_gkey=sparcsn4.vsl_vessel_visit_details.vvd_gkey
	LEFT JOIN sparcsn4.vsl_vessels ON sparcsn4.vsl_vessels.gkey=sparcsn4.vsl_vessel_visit_details.vessel_gkey 
	WHERE sparcsn4.inv_unit.id='$row->cont_id' ORDER BY sparcsn4.inv_unit.create_time DESC LIMIT 1");*/

	$dtl_query="SELECT substr(nominal_length,-2)" . " \"size\" ".", substr(nominal_height,-2)/10" . " \"height\" ".",
	ref_bizunit_scoped.id AS mlo,ref_bizunit_scoped.name AS mlo_name, vsl_vessel_visit_details.ib_vyg AS rotation, vsl_vessels.name AS vsl_name 
	FROM  inv_unit 
	INNER JOIN ref_equipment ON inv_unit.eq_gkey=ref_equipment.gkey
	INNER JOIN ref_equip_type ON ref_equipment.eqtyp_gkey=ref_equip_type.gkey
	INNER JOIN inv_unit_fcy_visit ON inv_unit_fcy_visit.unit_gkey=inv_unit.gkey
	LEFT JOIN ref_bizunit_scoped  ON inv_unit.line_op = ref_bizunit_scoped.gkey 
	LEFT JOIN argo_carrier_visit ON inv_unit_fcy_visit.actual_ob_cv = argo_carrier_visit.gkey
	LEFT JOIN vsl_vessel_visit_details ON argo_carrier_visit.cvcvd_gkey=vsl_vessel_visit_details.vvd_gkey
	LEFT JOIN vsl_vessels ON vsl_vessels.gkey=vsl_vessel_visit_details.vessel_gkey 
	WHERE inv_unit.id='$row->cont_id' ORDER BY inv_unit.create_time DESC FETCH FIRST 1 ROWS ONLY";
	$res1=oci_parse($con_sparcsn4_oracle,$dtl_query);
	oci_execute($res1);
	$data = oci_fetch_object($res1);
	
	$convsl_name = @$data->VSL_NAME ;
	$conib_vyg = @$data->ROTATION ;
	$consize = @$data->SIZE;
	$conheight = @$data->HEIGHT;
	$contmlo = @$data->MLO_NAME;

	//$sqlvsl_name=mysqli_query($con_sparcsn4,"SELECT vsl_name FROM ctmsmis.mis_inv_unit WHERE id='$row->cont_id'");
	//$rtnContvsl_name = "";

	// if(!is_bool($sqlvsl_name)){
	// 	$rtnContvsl_name=mysqli_fetch_object($sqlvsl_name);
	// 	$convsl_name=$rtnContvsl_name->vsl_name;
	// }


	// $sqlib_vyg=mysqli_query($con_sparcsn4,"SELECT  inv_unit_fcy_visit.flex_string10 AS vsl_visit_dtls_ib_vyg FROM sparcsn4.inv_unit_fcy_visit
	// INNER JOIN sparcsn4.inv_unit ON inv_unit_fcy_visit.unit_gkey=sparcsn4.inv_unit.gkey
	// WHERE sparcsn4.inv_unit.id='$row->cont_id'");

	// $rtnContib_vyg="";
	// $conib_vyg="";
	// if(!is_bool($sqlib_vyg)){
	// 	$rtnContib_vyg=mysqli_fetch_object($sqlib_vyg);
	// 	$conib_vyg=$rtnContib_vyg->vsl_visit_dtls_ib_vyg;
	// }

	// $sqlsize=mysqli_query($con_sparcsn4,"SELECT RIGHT(nominal_length,2) AS size FROM sparcsn4.ref_equip_type
	// INNER JOIN sparcsn4.ref_equipment ON sparcsn4.ref_equipment.eqtyp_gkey=sparcsn4.ref_equip_type.gkey
	// INNER JOIN sparcsn4.inv_unit_equip ON sparcsn4.inv_unit_equip.eq_gkey=sparcsn4.ref_equipment.gkey
	// INNER JOIN sparcsn4.inv_unit ON sparcsn4.inv_unit_equip.unit_gkey=inv_unit.gkey 
	// WHERE sparcsn4.inv_unit.id='$row->cont_id'");
	
	// $rtnContsize="";
	// $consize="";
	// if(!is_bool($sqlsize)){
	// 	$rtnContsize=mysqli_fetch_object($sqlsize);
	// 	$consize=$rtnContsize->size;
	// }

	// $sqlheight=mysqli_query($con_sparcsn4,"SELECT height FROM ctmsmis.mis_inv_unit WHERE id='$row->cont_id'");

	// $rtnContheight="";
	// $conheight="";
	// if(!is_bool($sqlheight)){
	// $rtnContheight=mysqli_fetch_object($sqlheight);
	// $conheight=$rtnContheight->height;
	// }
	
	// $sqlmlo=mysqli_query($con_sparcsn4,"SELECT mlo FROM ctmsmis.mis_inv_unit WHERE id='$row->cont_id'");

	// $rtnContmlo = "";
	// $contmlo = "";

	// if(!is_bool($sqlmlo)){
	// 	$rtnContmlo = mysqli_fetch_object($sqlmlo);
	// 	$contmlo = $rtnContmlo->mlo;
	// }

	$sqlfreight_kind=oci_parse($con_sparcsn4_oracle,"SELECT freight_kind FROM inv_unit WHERE id='$row->cont_id' ORDER BY inv_unit.gkey DESC FETCH FIRST 1 ROWS ONLY");
	oci_execute($sqlfreight_kind);

	// $rtnfreight_kind = "";
	// $contfreight_kind = "";

	// if(!is_bool($sqlfreight_kind)){
		$rtnfreight_kind = oci_fetch_object($sqlfreight_kind);
		$contfreight_kind = @$rtnfreight_kind->FREIGHT_KIND ;
	// }

	$sqlpostion=oci_parse($con_sparcsn4_oracle,"SELECT inv_unit_fcy_visit.last_pos_name
							FROM inv_unit 
							INNER JOIN inv_unit_fcy_visit ON inv_unit_fcy_visit.unit_gkey=inv_unit.gkey
							WHERE id='$row->cont_id' ORDER BY inv_unit.gkey DESC FETCH FIRST 1 ROWS ONLY");
	oci_execute($sqlpostion);						
	$rtnConpostion=oci_fetch_object($sqlpostion);
	$contpostion=@$rtnConpostion->LAST_POS_NAME;
	
	$sqltime_out=oci_parse($con_sparcsn4_oracle,"SELECT inv_unit_fcy_visit.time_out
							FROM inv_unit 
							INNER JOIN inv_unit_fcy_visit ON inv_unit_fcy_visit.unit_gkey=inv_unit.gkey
							WHERE id='$row->cont_id' ORDER BY inv_unit.gkey DESC FETCH FIRST 1 ROWS ONLY");
	oci_execute($sqltime_out);						
	$rtnContime_out=oci_fetch_object($sqltime_out);
	$conttime_out=@$rtnContime_out->TIME_OUT;

	$sqltime_in=oci_parse($con_sparcsn4_oracle,"SELECT inv_unit_fcy_visit.time_in
							FROM inv_unit 
							INNER JOIN inv_unit_fcy_visit ON inv_unit_fcy_visit.unit_gkey=inv_unit.gkey
							WHERE id='$row->cont_id' ORDER BY inv_unit.gkey ASC FETCH FIRST 1 ROWS ONLY");
	oci_execute($sqltime_in);
	$rtnContime_in=oci_fetch_object($sqltime_in);
	$conttime_in=@$rtnContime_in->TIME_IN ;

	$sqltotalDays=oci_parse($con_sparcsn4_oracle,"SELECT 
	intime,timeout,extract(day from timeout-intime) as totalDays
	
	FROM
	(
	SELECT inv_unit_fcy_visit.time_in AS intime,
	(SELECT inv_unit_fcy_visit.time_out
	FROM inv_unit 
	INNER JOIN inv_unit_fcy_visit ON inv_unit_fcy_visit.unit_gkey=inv_unit.gkey
		WHERE id='$row->cont_id' ORDER BY inv_unit.gkey DESC FETCH FIRST 1 ROWS ONLY) AS timeout                 
	
	FROM inv_unit 
	INNER JOIN inv_unit_fcy_visit ON inv_unit_fcy_visit.unit_gkey=inv_unit.gkey
	WHERE id='$row->cont_id' ORDER BY inv_unit.gkey ASC FETCH FIRST 1 ROWS ONLY
	)");
	oci_execute($sqltotalDays);
	$rtnContotalDays=oci_fetch_object($sqltotalDays);
	$conttotalDays=@$rtnContotalDays->TOTALDAYS;
		
	
	if($offdoc_name!=$row->offdoc_name){
		if($j>0){
		?>
		<tr bgcolor="#DCDCDC" valign="center"><td colspan="2"><font size="4"><b>&nbsp;&nbsp;Total Container:</b></font></td><td  colspan="15">&nbsp;&nbsp;<font size="4"><b><?php  echo $j;?></b></font></td></tr>
		<?php
		}
		?>
		<tr bgcolor="#F0F4CA" valign="center"><td  colspan="15">&nbsp;&nbsp;<font size="4"><b><?php  if($row->offdoc_name) echo $row->offdoc_name; else echo "&nbsp;"; ?></b></font></td></tr>
		<?php
		
		
		$j=1;
		
	}else{
		$j++;
		
	}
	$offdoc_name=$row->offdoc_name;
	
	
?>

<tr align="center">
		<td><?php  echo $i;?></td>
        <td><?php if($row->cont_id) echo $row->cont_id; else echo "&nbsp;";?></td>
		<td><?php if($convsl_name) echo $convsl_name; else echo "&nbsp;";?></td>
		<td><?php if($conib_vyg) echo $conib_vyg; else echo "&nbsp;";?></td>
		<td><?php if($consize) echo $consize; else echo "&nbsp;";?></td>
		<td><?php if($conheight) echo $conheight/10; else echo "&nbsp;";?></td>
		<td><?php if($contmlo) echo $contmlo; else echo "&nbsp;";?></td>
		<td><?php if($contfreight_kind) echo $contfreight_kind; else echo "&nbsp;";?></td>
		<td><?php if($contpostion) echo $contpostion; else echo "&nbsp;";?></td>
		<td><?php if($conttime_in) echo $conttime_in; else echo "&nbsp;";?></td>
		<td><?php if($conttime_out) echo $conttime_out; else echo "&nbsp;";?></td>
		<td><?php if($conttotalDays) echo $conttotalDays; else echo "&nbsp;";?></td>
		
	</tr>

<?php }

 ?>
<tr bgcolor="#DCDCDC" valign="center"><td colspan="2"><font size="4"><b>&nbsp;&nbsp;Total Container :</b></font></td><td  colspan="15">&nbsp;&nbsp;<font size="4"><b><?php  echo $j;?></b></font></td></tr>
<tr bgcolor="#E0FFFF" valign="center"><td colspan="2"><font size="4"><b>&nbsp;&nbsp;Grand Total:</b></font></td><td  colspan="15">&nbsp;&nbsp;<font size="4"><b><?php  echo $i;?></b></font></td></tr>
</table>
<?php
mysqli_close($con_sparcsn4);
?>	
	</BODY>
</HTML>
