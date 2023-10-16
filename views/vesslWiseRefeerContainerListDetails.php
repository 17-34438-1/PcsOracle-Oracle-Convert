<?php if(@$_POST['options']=='html'){?>
<HTML>
	<HEAD>
		<TITLE>Bearth Operator</TITLE>
		<LINK href="../css/report.css" type=text/css rel=stylesheet>
	    <style type="text/css">
<!--
.style1 {font-size: 12px}
-->
        </style>
</HEAD>
<BODY>

	<?php } 
	else if(@$_POST['options']=='xl'){
		header("Content-type: application/octet-stream");
		header("Content-Disposition: attachment; filename=IMPORT_SUMMERY.xls;");
		header("Content-Type: application/ms-excel");
		header("Pragma: no-cache");
		header("Expires: 0");

	}
	//$ddl_imp_rot_no=$_REQUEST['ddl_imp_rot_no']; 

	//$con=mysql_connect("10.1.1.21", "sparcsn4","sparcsn4")or die("sparcsn4 database cannot connect"); 
	//mysql_select_db("sparcsn4")or die("cannot select DB");
	include("dbConection.php");
	include("dbOracleConnection.php");	
	//echo $todate;
	
	?>
<html>
<title>Import Reffer Container Discharge List</title>
<body>
<table width="100%" border ='0' cellpadding='0' cellspacing='0'>
<tr bgcolor="#ffffff" align="center" height="100px">
		<td colspan="13" align="center">
			<table border=0 width="100%">
				<tr align="center">
					<td colspan="12" align="center"><img align="middle"  width="220px" height="70px" src="<?php echo IMG_PATH?>cpanew.jpg"></font></td>
				</tr>
				<tr align="center">
					<td colspan="12"><font size="4"><b> Container Terminal Operator</b></font></td>
				</tr>
				
				<tr align="center">
					<td colspan="12"><font size="4"><b><u>REEFER CONTAINER  LIST</u></b></font></td>
				</tr>
				<tr align="center">
					<td colspan="12"><font size="4"><b></b></font></td>
				</tr>

			</table>
		
		</td>
		
	</tr>
	
	<tr bgcolor="#ffffff" align="center" height="25px">
		<td colspan="15" align="center"></td>
		
	</tr>
	</table>
	<table class="table table-bordered table-responsive table-hover table-striped mb-none">
	<tr  align="center">
		<td style="border-width:3px;border-style: double;"><b>SlNo.</b></td>
		<td style="border-width:3px;border-style: double;"><b>Container No.</b></td>
		<td style="border-width:3px;border-style: double;"><b>Size.</b></td>
		<td style="border-width:3px;border-style: double;"><b>Height.</b></td>
		<td style="border-width:3px;border-style: double;"><b>MLO.</b></td>
		<td style="border-width:3px;border-style: double;"><b>Vessel Name.</b></td>
		<td style="border-width:3px;border-style: double;"><b>Rotation.</b></td>
		<td style="border-width:3px;border-style: double;"><b>Discharge Time.</b></td>
		<td style="border-width:3px;border-style: double;"><b>Yard Name.</b></td>
		<td style="border-width:3px;border-style: double;"><b>Block Name.</b></td>
		<td style="border-width:3px;border-style: double;"><b>Position.</b></td>
		<td style="border-width:3px;border-style: double;"><b>Power Request Time.</b></td>
		<td style="border-width:3px;border-style: double;"><b>Power Connect.</b></td>
		<td style="border-width:3px;border-style: double;"><b>Power Disconnect.</b></td>
		<td style="border-width:3px;border-style: double;"><b>Delivery Type.</b></td>
		<td style="border-width:3px;border-style: double;"><b>Assignment Date.</b></td>
		<td style="border-width:3px;border-style: double;"><b>Delivery Date.</b></td>
		
		
	</tr>

<?php
$rotation=$rot;
	
		
/* $query=mysql_query("
SELECT inv_unit.gkey,inv_unit.id,inv_unit_fcy_visit.time_in,inv_unit.power_rqst_time,sparcsn4.inv_unit_fcy_visit.time_out,
ctmsmis.mis_inv_unit.vsl_name,
ctmsmis.mis_inv_unit.vsl_visit_dtls_ib_vyg,
ctmsmis.mis_inv_unit.rfr_connect,
ctmsmis.mis_inv_unit.rfr_disconnect AS rfr_dis_connect,
(SELECT size FROM ctmsmis.mis_inv_unit WHERE ctmsmis.mis_inv_unit.gkey=sparcsn4.inv_unit.gkey) AS size,
(SELECT height FROM ctmsmis.mis_inv_unit WHERE ctmsmis.mis_inv_unit.gkey=sparcsn4.inv_unit.gkey) AS height,
(SELECT mlo FROM ctmsmis.mis_inv_unit WHERE ctmsmis.mis_inv_unit.gkey=sparcsn4.inv_unit.gkey) AS mlo,

IF(sparcsn4.inv_unit_fcy_visit.last_pos_slot='',
(SELECT SUBSTRING(sparcsn4.srv_event_field_changes.new_value,7)
FROM sparcsn4.srv_event
INNER JOIN sparcsn4.srv_event_field_changes ON sparcsn4.srv_event_field_changes.event_gkey=sparcsn4.srv_event.gkey
WHERE sparcsn4.srv_event.applied_to_gkey=sparcsn4.inv_unit.gkey AND sparcsn4.srv_event.event_type_gkey IN(18) AND sparcsn4.srv_event_field_changes.new_value IS NOT NULL AND sparcsn4.srv_event_field_changes.new_value !='' AND sparcsn4.srv_event_field_changes.new_value !='Y-CGP-.' AND sparcsn4.srv_event.gkey<(SELECT sparcsn4.srv_event.gkey FROM sparcsn4.srv_event 
INNER JOIN sparcsn4.srv_event_field_changes ON sparcsn4.srv_event_field_changes.event_gkey=sparcsn4.srv_event.gkey
WHERE sparcsn4.srv_event.event_type_gkey IN(25,29,30) AND sparcsn4.srv_event.applied_to_gkey=sparcsn4.inv_unit.gkey ORDER BY sparcsn4.srv_event_field_changes.gkey DESC LIMIT 1) ORDER BY sparcsn4.srv_event.gkey DESC LIMIT 1)
,sparcsn4.inv_unit_fcy_visit.last_pos_slot) AS last_pos_slot,

(SELECT ctmsmis.cont_yard(IF(sparcsn4.inv_unit_fcy_visit.last_pos_slot='',
(SELECT SUBSTRING(sparcsn4.srv_event_field_changes.new_value,7)
FROM sparcsn4.srv_event
INNER JOIN sparcsn4.srv_event_field_changes ON sparcsn4.srv_event_field_changes.event_gkey=sparcsn4.srv_event.gkey
WHERE sparcsn4.srv_event.applied_to_gkey=sparcsn4.inv_unit.gkey AND sparcsn4.srv_event.event_type_gkey IN(18) AND sparcsn4.srv_event_field_changes.new_value IS NOT NULL AND sparcsn4.srv_event_field_changes.new_value !='' AND sparcsn4.srv_event_field_changes.new_value !='Y-CGP-.' AND sparcsn4.srv_event.gkey<(SELECT sparcsn4.srv_event.gkey FROM sparcsn4.srv_event 
INNER JOIN sparcsn4.srv_event_field_changes ON sparcsn4.srv_event_field_changes.event_gkey=sparcsn4.srv_event.gkey
WHERE sparcsn4.srv_event.event_type_gkey IN(25,29,30) AND sparcsn4.srv_event.applied_to_gkey=sparcsn4.inv_unit.gkey ORDER BY sparcsn4.srv_event_field_changes.gkey DESC LIMIT 1) ORDER BY sparcsn4.srv_event.gkey DESC LIMIT 1)
,sparcsn4.inv_unit_fcy_visit.last_pos_slot))) AS yard,
(SELECT ctmsmis.cont_block(IF(sparcsn4.inv_unit_fcy_visit.last_pos_slot='',
(SELECT SUBSTRING(sparcsn4.srv_event_field_changes.new_value,7)
FROM sparcsn4.srv_event
INNER JOIN sparcsn4.srv_event_field_changes ON sparcsn4.srv_event_field_changes.event_gkey=sparcsn4.srv_event.gkey
WHERE sparcsn4.srv_event.applied_to_gkey=sparcsn4.inv_unit.gkey AND sparcsn4.srv_event.event_type_gkey IN(18) AND sparcsn4.srv_event_field_changes.new_value IS NOT NULL AND sparcsn4.srv_event_field_changes.new_value !='' AND sparcsn4.srv_event_field_changes.new_value !='Y-CGP-.' AND sparcsn4.srv_event.gkey<(SELECT sparcsn4.srv_event.gkey FROM sparcsn4.srv_event 
INNER JOIN sparcsn4.srv_event_field_changes ON sparcsn4.srv_event_field_changes.event_gkey=sparcsn4.srv_event.gkey
WHERE sparcsn4.srv_event.event_type_gkey IN(25,29,30) AND sparcsn4.srv_event.applied_to_gkey=sparcsn4.inv_unit.gkey ORDER BY sparcsn4.srv_event_field_changes.gkey DESC LIMIT 1) ORDER BY sparcsn4.srv_event.gkey DESC LIMIT 1)
,sparcsn4.inv_unit_fcy_visit.last_pos_slot),yard)) AS block,
sparcsn4.inv_unit.flex_string01,sparcsn4.inv_unit_fcy_visit.flex_date01
FROM sparcsn4.inv_unit
LEFT JOIN ctmsmis.mis_inv_unit ON ctmsmis.mis_inv_unit.gkey = inv_unit.gkey
INNER JOIN sparcsn4.inv_unit_fcy_visit ON inv_unit_fcy_visit.unit_gkey=inv_unit.gkey
WHERE ctmsmis.mis_inv_unit.iso_grp IN ('RE','RS','RT') AND sparcsn4.inv_unit.category='IMPRT' AND ctmsmis.mis_inv_unit.vsl_visit_dtls_ib_vyg='$rotation' ORDER BY yard
 "); */
 /*$query=mysqli_query($con_sparcsn4,"SELECT sparcsn4.inv_unit.gkey, sparcsn4.inv_unit.id,sparcsn4.inv_unit_fcy_visit.time_in, sparcsn4.inv_unit.power_rqst_time, 
sparcsn4.inv_unit_fcy_visit.time_out,

sparcsn4.vsl_vessels.name AS vsl_name,
sparcsn4.vsl_vessel_visit_details.ib_vyg AS vsl_visit_dtls_ib_vyg,
sparcsn4.inv_unit_fcy_visit.flex_date03 AS rfr_connect,
sparcsn4.inv_unit_fcy_visit.flex_date04 AS rfr_dis_connect,
	(SELECT RIGHT(sparcsn4.ref_equip_type.nominal_length,2) FROM sparcsn4.inv_unit_equip 
INNER JOIN sparcsn4.ref_equipment ON sparcsn4.ref_equipment.gkey=sparcsn4.inv_unit_equip.eq_gkey 
INNER JOIN sparcsn4.ref_equip_type ON sparcsn4.ref_equip_type.gkey=sparcsn4.ref_equipment.eqtyp_gkey 
WHERE sparcsn4.inv_unit_equip.unit_gkey=inv_unit.gkey) AS size,

((SELECT RIGHT(sparcsn4.ref_equip_type.nominal_height,2) FROM sparcsn4.inv_unit_equip 
INNER JOIN sparcsn4.ref_equipment ON sparcsn4.ref_equipment.gkey=sparcsn4.inv_unit_equip.eq_gkey 
INNER JOIN sparcsn4.ref_equip_type ON sparcsn4.ref_equip_type.gkey=sparcsn4.ref_equipment.eqtyp_gkey 
WHERE sparcsn4.inv_unit_equip.unit_gkey=inv_unit.gkey)/10) AS height,

sparcsn4.ref_bizunit_scoped.id AS mlo,


IF(sparcsn4.inv_unit_fcy_visit.last_pos_slot='',
(SELECT SUBSTRING(sparcsn4.srv_event_field_changes.new_value,7)
FROM sparcsn4.srv_event
INNER JOIN sparcsn4.srv_event_field_changes ON sparcsn4.srv_event_field_changes.event_gkey=sparcsn4.srv_event.gkey
WHERE sparcsn4.srv_event.applied_to_gkey=sparcsn4.inv_unit.gkey AND sparcsn4.srv_event.event_type_gkey IN(18) AND sparcsn4.srv_event_field_changes.new_value IS NOT NULL AND sparcsn4.srv_event_field_changes.new_value !='' AND sparcsn4.srv_event_field_changes.new_value !='Y-CGP-.' AND sparcsn4.srv_event.gkey<(SELECT sparcsn4.srv_event.gkey FROM sparcsn4.srv_event 
INNER JOIN sparcsn4.srv_event_field_changes ON sparcsn4.srv_event_field_changes.event_gkey=sparcsn4.srv_event.gkey
WHERE sparcsn4.srv_event.event_type_gkey IN(25,29,30) AND sparcsn4.srv_event.applied_to_gkey=sparcsn4.inv_unit.gkey ORDER BY sparcsn4.srv_event_field_changes.gkey DESC LIMIT 1) ORDER BY sparcsn4.srv_event.gkey DESC LIMIT 1)
,sparcsn4.inv_unit_fcy_visit.last_pos_slot) AS last_pos_slot,


(SELECT ctmsmis.cont_yard(IF(sparcsn4.inv_unit_fcy_visit.last_pos_slot='',
(SELECT SUBSTRING(sparcsn4.srv_event_field_changes.new_value,7)
FROM sparcsn4.srv_event
INNER JOIN sparcsn4.srv_event_field_changes ON sparcsn4.srv_event_field_changes.event_gkey=sparcsn4.srv_event.gkey
WHERE sparcsn4.srv_event.applied_to_gkey=sparcsn4.inv_unit.gkey AND sparcsn4.srv_event.event_type_gkey IN(18) AND sparcsn4.srv_event_field_changes.new_value IS NOT NULL AND sparcsn4.srv_event_field_changes.new_value !='' AND sparcsn4.srv_event_field_changes.new_value !='Y-CGP-.' AND sparcsn4.srv_event.gkey<(SELECT sparcsn4.srv_event.gkey FROM sparcsn4.srv_event 
INNER JOIN sparcsn4.srv_event_field_changes ON sparcsn4.srv_event_field_changes.event_gkey=sparcsn4.srv_event.gkey
WHERE sparcsn4.srv_event.event_type_gkey IN(25,29,30) AND sparcsn4.srv_event.applied_to_gkey=sparcsn4.inv_unit.gkey ORDER BY sparcsn4.srv_event_field_changes.gkey DESC LIMIT 1) ORDER BY sparcsn4.srv_event.gkey DESC LIMIT 1)
,sparcsn4.inv_unit_fcy_visit.last_pos_slot))) AS yard,
(SELECT ctmsmis.cont_block(IF(sparcsn4.inv_unit_fcy_visit.last_pos_slot='',
(SELECT SUBSTRING(sparcsn4.srv_event_field_changes.new_value,7)
FROM sparcsn4.srv_event
INNER JOIN sparcsn4.srv_event_field_changes ON sparcsn4.srv_event_field_changes.event_gkey=sparcsn4.srv_event.gkey
WHERE sparcsn4.srv_event.applied_to_gkey=sparcsn4.inv_unit.gkey AND sparcsn4.srv_event.event_type_gkey IN(18) AND sparcsn4.srv_event_field_changes.new_value IS NOT NULL AND sparcsn4.srv_event_field_changes.new_value !='' AND sparcsn4.srv_event_field_changes.new_value !='Y-CGP-.' AND sparcsn4.srv_event.gkey<(SELECT sparcsn4.srv_event.gkey FROM sparcsn4.srv_event 
INNER JOIN sparcsn4.srv_event_field_changes ON sparcsn4.srv_event_field_changes.event_gkey=sparcsn4.srv_event.gkey
WHERE sparcsn4.srv_event.event_type_gkey IN(25,29,30) AND sparcsn4.srv_event.applied_to_gkey=sparcsn4.inv_unit.gkey ORDER BY sparcsn4.srv_event_field_changes.gkey DESC LIMIT 1) ORDER BY sparcsn4.srv_event.gkey DESC LIMIT 1)
,sparcsn4.inv_unit_fcy_visit.last_pos_slot),yard)) AS block,

sparcsn4.inv_unit.flex_string01,sparcsn4.inv_unit_fcy_visit.flex_date01
FROM sparcsn4.inv_unit
INNER JOIN sparcsn4.ref_bizunit_scoped ON inv_unit.line_op = sparcsn4.ref_bizunit_scoped.gkey

INNER JOIN sparcsn4.inv_unit_fcy_visit ON inv_unit_fcy_visit.unit_gkey=inv_unit.gkey
INNER JOIN sparcsn4.inv_unit_equip ON inv_unit.gkey=inv_unit_equip.unit_gkey 
INNER JOIN sparcsn4.ref_equipment ON inv_unit_equip.eq_gkey=ref_equipment.gkey
INNER JOIN sparcsn4.ref_equip_type ON ref_equipment.eqtyp_gkey=ref_equip_type.gkey 

INNER JOIN sparcsn4.argo_carrier_visit ON (argo_carrier_visit.gkey=inv_unit.cv_gkey OR argo_carrier_visit.gkey=inv_unit.declrd_ib_cv) 
INNER JOIN sparcsn4.argo_visit_details ON argo_carrier_visit.cvcvd_gkey=argo_visit_details.gkey 
INNER JOIN sparcsn4.vsl_vessel_visit_details ON vsl_vessel_visit_details.vvd_gkey=argo_visit_details.gkey 
INNER JOIN sparcsn4.vsl_vessels ON vsl_vessel_visit_details.vessel_gkey=vsl_vessels.gkey

WHERE sparcsn4.ref_equip_type.iso_group  IN ('RE','RS','RT') AND sparcsn4.inv_unit.category='IMPRT' AND sparcsn4.inv_unit_fcy_visit.flex_string10='$rotation'");*/

$str="
SELECT inv_unit.gkey, inv_unit.id,to_char(inv_unit_fcy_visit.time_in,'yyyy-mm-dd hh24:mi:ss') as time_in, inv_unit.power_rqst_time, 
to_char(inv_unit_fcy_visit.time_out,'yyyy-mm-dd hh24:mi:ss') as time_out ,
vsl_vessels.name AS vsl_name,
vsl_vessel_visit_details.ib_vyg AS vsl_visit_dtls_ib_vyg,
inv_unit_fcy_visit.flex_date03 AS rfr_connect,
inv_unit_fcy_visit.flex_date04 AS rfr_dis_connect,
(SELECT SUBSTR(ref_equip_type.nominal_length,-2) FROM inv_unit
INNER JOIN ref_equipment ON ref_equipment.gkey=inv_unit.eq_gkey 
INNER JOIN ref_equip_type ON ref_equip_type.gkey=ref_equipment.eqtyp_gkey 
INNER JOIN inv_unit_fcy_visit ON inv_unit_fcy_visit.unit_gkey=inv_unit.gkey
WHERE inv_unit_fcy_visit.unit_gkey=inv_unit.gkey fetch first 1 rows only) AS siz,

((SELECT SUBSTR(ref_equip_type.nominal_height,-2) FROM inv_unit
INNER JOIN ref_equipment ON ref_equipment.gkey=inv_unit.eq_gkey 
INNER JOIN ref_equip_type ON ref_equip_type.gkey=ref_equipment.eqtyp_gkey 
INNER JOIN inv_unit_fcy_visit ON inv_unit_fcy_visit.unit_gkey=inv_unit.gkey
WHERE inv_unit_fcy_visit.unit_gkey=inv_unit.gkey fetch first 1 rows only)/10) AS height,
ref_bizunit_scoped.id AS mlo,
(CASE
WHEN inv_unit_fcy_visit.last_pos_slot='' 
Then (SELECT SUBSTR(srv_event_field_changes.new_value,7)
FROM srv_event
INNER JOIN srv_event_field_changes ON srv_event_field_changes.event_gkey=srv_event.gkey
WHERE srv_event.applied_to_gkey=inv_unit.gkey AND srv_event.event_type_gkey IN(18) AND srv_event_field_changes.new_value IS NOT NULL AND srv_event_field_changes.new_value !='' AND srv_event_field_changes.new_value !='Y-CGP-.' AND srv_event.gkey<(SELECT srv_event.gkey FROM srv_event 
INNER JOIN srv_event_field_changes ON srv_event_field_changes.event_gkey=srv_event.gkey
WHERE srv_event.event_type_gkey IN(25,29,30) AND srv_event.applied_to_gkey=inv_unit.gkey 
ORDER BY srv_event_field_changes.gkey DESC fetch first 1 rows only) ORDER BY srv_event.gkey DESC  fetch first 1 rows only)
else inv_unit_fcy_visit.last_pos_slot 
END ) as last_pos_slot,

(CASE
WHEN inv_unit_fcy_visit.last_pos_slot='' 
Then (SELECT SUBSTR(srv_event_field_changes.new_value,7)
FROM srv_event
INNER JOIN srv_event_field_changes ON srv_event_field_changes.event_gkey=srv_event.gkey
WHERE srv_event.applied_to_gkey=inv_unit.gkey AND srv_event.event_type_gkey IN(18) AND srv_event_field_changes.new_value IS NOT NULL AND srv_event_field_changes.new_value !='' AND srv_event_field_changes.new_value !='Y-CGP-.' AND srv_event.gkey<(SELECT srv_event.gkey FROM srv_event 
INNER JOIN srv_event_field_changes ON srv_event_field_changes.event_gkey=srv_event.gkey
WHERE srv_event.event_type_gkey IN(25,29,30) AND srv_event.applied_to_gkey=inv_unit.gkey
ORDER BY srv_event_field_changes.gkey DESC fetch first 1 rows only) ORDER BY srv_event.gkey DESC fetch first 1 rows only)
else inv_unit_fcy_visit.last_pos_slot 
END ) as yardValue,

(CASE
WHEN inv_unit_fcy_visit.last_pos_slot='' 
Then (SELECT SUBSTR(srv_event_field_changes.new_value,7)
FROM srv_event
INNER JOIN srv_event_field_changes ON srv_event_field_changes.event_gkey=srv_event.gkey
WHERE srv_event.applied_to_gkey=inv_unit.gkey AND srv_event.event_type_gkey IN(18) AND srv_event_field_changes.new_value IS NOT NULL AND srv_event_field_changes.new_value !='' AND srv_event_field_changes.new_value !='Y-CGP-.' AND srv_event.gkey<(SELECT srv_event.gkey FROM srv_event 
INNER JOIN srv_event_field_changes ON srv_event_field_changes.event_gkey=srv_event.gkey
WHERE srv_event.event_type_gkey IN(25,29,30) AND srv_event.applied_to_gkey=inv_unit.gkey 
ORDER BY srv_event_field_changes.gkey DESC fetch first 1 rows only) ORDER BY srv_event.gkey DESC fetch first 1 rows only)
else inv_unit_fcy_visit.last_pos_slot 
END ) as blockValue,
inv_unit.flex_string01, to_char(inv_unit_fcy_visit.flex_date01,'yyyy-mm-dd hh24:mi:ss') as flex_date01
FROM inv_unit
INNER JOIN ref_bizunit_scoped ON inv_unit.line_op = ref_bizunit_scoped.gkey
INNER JOIN inv_unit_fcy_visit ON inv_unit_fcy_visit.unit_gkey=inv_unit.gkey
INNER JOIN ref_equipment ON inv_unit.eq_gkey=ref_equipment.gkey
INNER JOIN ref_equip_type ON ref_equipment.eqtyp_gkey=ref_equip_type.gkey 
INNER JOIN argo_carrier_visit ON (argo_carrier_visit.gkey=inv_unit.cv_gkey OR argo_carrier_visit.gkey=inv_unit.declrd_ib_cv) 
INNER JOIN argo_visit_details ON argo_carrier_visit.cvcvd_gkey=argo_visit_details.gkey 
INNER JOIN vsl_vessel_visit_details ON vsl_vessel_visit_details.vvd_gkey=argo_visit_details.gkey 
INNER JOIN vsl_vessels ON vsl_vessel_visit_details.vessel_gkey=vsl_vessels.gkey
WHERE ref_equip_type.iso_group  IN ('RE','RS','RT') AND inv_unit.category='IMPRT' AND inv_unit_fcy_visit.flex_string10='$rotation'";
$query=oci_parse($con_sparcsn4_oracle,$str);
oci_execute($query);

 

	$i=0;
	$j=0;
	
	$mlo="";
	while(($row=oci_fetch_object($query)) != false){
		$yardValue="";
		$yardValue=$row->YARDVALUE;
		$yard="";
		$yardStr="SELECT ctmsmis.cont_yard('$yardValue') as yard";
		$yardQuery=mysqli_query($con_sparcsn4,$yardStr);
		$yardRes=mysqli_fetch_object($yardQuery);
		$yard=$yardRes->yard;

		$blockValue="";
		$blockValue=$row->BLOCKVALUE;
		$block="";
		$blockStr="SELECT ctmsmis.cont_block('$blockValue','$yard') as block";
		$blockQuery=mysqli_query($con_sparcsn4,$blockStr);
		$blockRes=mysqli_fetch_object($blockQuery);
		$block=$blockRes->block;
		
	$i++;
	
		
	
?>
<tr align="center">
		<td><?php  echo $i;?></td>
		<td><?php if($row->ID) echo $row->ID; else echo "&nbsp;";?></td>
		<td><?php if($row->SIZ) echo $row->SIZ; else echo "&nbsp;";?></td>
		<td><?php if($row->HEIGHT) echo $row->HEIGHT; else echo "&nbsp;";?></td>
		<td><?php if($row->MLO) echo $row->MLO; else echo "&nbsp;";?></td>
		<td><?php if($row->VSL_NAME) echo $row->VSL_NAME; else echo "&nbsp;";?></td>
		<td><?php if($row->VSL_VISIT_DTLS_IB_VYG) echo $row->VSL_VISIT_DTLS_IB_VYG; else echo "&nbsp;";?></td>
		<td><?php if($row->TIME_IN) echo $row->TIME_IN; else echo "&nbsp;";?></td>
		<td><?php if($yard) echo $yard; else echo "&nbsp;";?></td>
		<td><?php if($block) echo $block; else echo "&nbsp;";?></td>
		<td><?php if($row->LAST_POS_SLOT) echo $row->LAST_POS_SLOT; else echo "&nbsp;";?></td>
		<td><?php if($row->POWER_RQST_TIME) echo $row->POWER_RQST_TIME; else echo "&nbsp;";?></td>
		<td><?php if($row->RFR_CONNECT ) echo $row->RFR_CONNECT; else echo "&nbsp;";?></td>
		<td><?php if($row->RFR_DIS_CONNECT) echo $row->RFR_DIS_CONNECT; else echo "&nbsp;";?></td>
		<td><?php if($row->FLEX_STRING01) echo $row->FLEX_STRING01; else echo "&nbsp;";?></td>
		<td><?php if($row->FLEX_DATE01) echo $row->FLEX_DATE01; else echo "&nbsp;";?></td>
		<td><?php if($row->TIME_OUT) echo $row->TIME_OUT; else echo "&nbsp;";?></td>
				
	</tr>

<?php } ?>
</table>
<br />
<br />



<?php 
mysqli_close($con_sparcsn4);
if(@$_POST['options']=='html'){?>	
	</BODY>
</HTML>
<?php }?>
