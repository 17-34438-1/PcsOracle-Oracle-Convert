<?php if($_POST['options']=='html'){?>
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
	else if($_POST['options']=='xl'){
		header("Content-type: application/octet-stream");
		header("Content-Disposition: attachment; filename=IMPORT_SUMMERY.xls;");
		header("Content-Type: application/ms-excel");
		header("Pragma: no-cache");
		header("Expires: 0");

	}
$ddl_imp_rot_no=$_REQUEST['ddl_imp_rot_no']; 

	include("dbConection.php");
	include("dbOracleConnection.php");
	
	$sql=oci_parse($con_sparcsn4_oracle,"select vvd_gkey from vsl_vessel_visit_details where ib_vyg='$ddl_imp_rot_no'");
	oci_execute($sql);
	$row=oci_fetch_object($sql);
	$vvdGkey=$row->VVD_GKEY;

	//$sql="call ctmsmis.update_containers_by_vvd_gkey($vvdGkey)";
	//$res=mysqli_query($con_sparcsn4,$sql);
	//echo$vvdGkey;
	$sql1=oci_parse($con_sparcsn4_oracle,"SELECT vsl_vessels.name AS vsl_name,vsl_vessel_visit_details.ob_vyg AS ex_Roation,
	NVL(vsl_vessel_visit_details.flex_string02,NVL(vsl_vessel_visit_details.flex_string03,'')) AS berth_op,
	NVL(argo_quay.id,'') AS berth,
	ref_bizunit_scoped.id AS local_agent,
	to_char(vsl_vessel_visit_details.published_eta,'yyyy-mm-dd') AS published_eta,
	CONCAT(CONCAT(to_char(vsl_vessel_visit_details.start_work,'HH24'),''),to_char(vsl_vessel_visit_details.start_work,'MI')) AS discharge_start_time,
	to_char(vsl_vessel_visit_details.start_work,'yyyy-mm-dd') AS discharge_start,
	CONCAT(CONCAT(to_char(vsl_vessel_visit_details.end_work,'HH24'),''),to_char(vsl_vessel_visit_details.end_work,'MI')) AS discharge_completed_time,
	to_char(vsl_vessel_visit_details.end_work,'yyyy-mm-dd') AS discharge_completed,
	(SELECT ata FROM vsl_vessel_berthings 
	WHERE vsl_vessel_berthings.vvd_gkey=vsl_vessel_visit_details.vvd_gkey ORDER BY vsl_vessel_berthings.ata DESC FETCH FIRST 1 ROWS ONLY)AS ata,
	(SELECT atd FROM vsl_vessel_berthings 
	WHERE vsl_vessel_berthings.vvd_gkey=vsl_vessel_visit_details.vvd_gkey ORDER BY vsl_vessel_berthings.atd DESC FETCH FIRST 1 ROWS ONLY)AS atd
	FROM vsl_vessel_visit_details
	INNER JOIN argo_carrier_visit ON argo_carrier_visit.cvcvd_gkey=vsl_vessel_visit_details.vvd_gkey
	INNER JOIN vsl_vessel_berthings ON vsl_vessel_berthings.vvd_gkey=vsl_vessel_visit_details.vvd_gkey
	INNER JOIN vsl_vessels ON vsl_vessels.gkey=vsl_vessel_visit_details.vessel_gkey
	INNER JOIN ref_bizunit_scoped ON ref_bizunit_scoped.gkey=vsl_vessels.owner_gkey
	INNER JOIN argo_quay ON argo_quay.gkey=vsl_vessel_berthings.quay
	WHERE vsl_vessel_visit_details.vvd_gkey='$vvdGkey'");
	oci_execute($sql1);
	$row1=oci_fetch_object($sql1);
	
	?>
<html>
<title>Import Container Discharge List</title>
<body>
<table width="100%" border ='0' cellpadding='0' cellspacing='0'>
<tr bgcolor="#ffffff" height="100px">
		<td>
			<table border=0 width="100%">
				<tr align="center">
					<!--td colspan="13"><font size="4"><b>CHITTAGONG PORT AUTHORITY,CHITTAGONG</b></font></td-->
					<td colspan="13" align="center"><img width="250px" height="80px" src="<?php echo IMG_PATH?>cpanew.jpg"></td>
				</tr>
			
				<tr align="center">
					<td colspan="12"><font size="4"><b>24 HRS.WORK DONE REPORT CLOSSING AT 0800 HRS.OF <?php  Echo $fromdate;?></b></font></td>
				</tr>
				<tr align="center">
					<td colspan="12"><font size="4"><b></b></font></td>
				</tr>
			</table>
			<table border=0 width="40%">
				<tr>
					<td><font size="4"><b>DATE</b></font></td>
					<td> - </td>
					<td><font size="4"><b><font size="4"><?php  Echo $fromdate;?></b></font></td>
				</tr>
				<tr>
					<td><font size="4"><b>VESSEL NAME</b></font></td>
					<td> - </td>
					<td><font size="4"><b><font size="4"><?php  Echo $row1->VSL_NAME;?></b></font></td>
				</tr>
				<tr>
					<td><font size="4"><b>VOYAGE NO</b></font></td>
					<td> - </td>
					<td><font size="4"><b><?php  Echo $voysNo;?></b></font></td>					
				</tr>
				<tr>
					<td><font size="4"><b>IMP.ROT</b></font></td>					
					<td> - </td>
					<td><font size="4"><b><?php  Echo $ddl_imp_rot_no;?></b></font></td>					
				</tr>
				<tr>
					<td><font size="4"><b> EXP.ROT</b></font></td>				
					<td> - </td>
					<td><font size="4"><b><?php  Echo  $row1->EX_ROATION;?></b></font></td>					
				</tr>
				<tr>
					<td><font size="4"><b>BERTH NO</b></font></td>					
					<td> - </td>
					<td><font size="4"><b><?php  Echo  $row1->BERTH;?></b></font></td>
					
				</tr>
				<tr>
					<td><font size="4"><b>BERTH OPERATOR</b></font>
					<td> - </td>
					<td><font size="4"><b><?php  Echo  $row1->BERTH_OP;?></b></font></td>
				</tr>
				<tr>
					<td><font size="4"><b>SHIPPING AGENT</b></font></td>
					<td> - </td>
					<td><font size="4"><b><?php  Echo  $row1->LOCAL_AGENT;?></b></font></td>					
				</tr>
				<tr>
					<td><font size="4"><b>ARRIVED ON</b></font></td>
					<td> - </td>
					<td><font size="4"><b><?php  Echo  $row1->ATA;?></b></font></td>					
				</tr>
				<tr>
					<td><font size="4"><b>EXPECTED TIME OF ARRIVED</b></font></td>
					<td> - </td>
					<td><font size="4"><b><?php  Echo $row1->PUBLISHED_ETA;?></b></font></td>					
				</tr>
				<tr></tr>
				<tr></tr>
			</table>
		
		</td>
		
	</tr>
	
	<tr bgcolor="#ffffff" align="center" height="25px">
		<td colspan="15" align="center"></td>
		
	</tr>
	</table>
	




<?php

$sqlSummery3=oci_parse($con_sparcsn4_oracle,"
SELECT
NVL(SUM(discharge_done_LD_20),0) AS discharge_done_LD_20,
NVL(SUM(discharge_done_LD_40),0) AS discharge_done_LD_40,
NVL(SUM(discharge_done_MT_20),0) AS discharge_done_MT_20,
NVL(SUM(discharge_done_MT_40),0) AS discharge_done_MT_40,
NVL(SUM(dischage_LD_tues),0) AS dischage_LD_tues,
NVL(SUM(discharge_MT_tues),0) AS discharge_MT_tues

FROM 
(
SELECT 
(CASE WHEN siz = 20
AND fcy_time_in >to_date(concat('$fromdate', ' 08:00:00'),'YYYY-MM-DD HH24-MI-SS')+1
AND fcy_time_in <to_date(CONCAT('$fromdate',' 08:00:01'),'YYYY-MM-DD HH24-MI-SS')
AND freight_kind IN ('FCL','LCL')  THEN 1 
ELSE NULL END) AS discharge_done_LD_20, 
(CASE WHEN siz !=20
AND fcy_time_in >to_date(concat('$fromdate', ' 08:00:00'),'YYYY-MM-DD HH24-MI-SS')+1
AND fcy_time_in <to_date(CONCAT('$fromdate',' 08:00:01'),'YYYY-MM-DD HH24-MI-SS') 
AND freight_kind IN ('FCL','LCL')  THEN 1  
ELSE NULL END) AS discharge_done_LD_40,
(CASE WHEN siz = 20
AND fcy_time_in >to_date(concat('$fromdate', ' 08:00:00'),'YYYY-MM-DD HH24-MI-SS')+1
AND fcy_time_in <to_date(CONCAT('$fromdate',' 08:00:01'),'YYYY-MM-DD HH24-MI-SS') 
AND freight_kind ='MTY'  THEN 1  
ELSE NULL END) AS discharge_done_MT_20, 
(CASE WHEN siz !=20
AND fcy_time_in >to_date(concat('$fromdate', ' 08:00:00'),'YYYY-MM-DD HH24-MI-SS')+1
AND fcy_time_in <to_date(CONCAT('$fromdate',' 08:00:01'),'YYYY-MM-DD HH24-MI-SS')
AND freight_kind ='MTY'  THEN 1 
ELSE NULL END) AS discharge_done_MT_40,
(CASE WHEN siz=20 
AND fcy_time_in >to_date(concat('$fromdate', ' 08:00:00'),'YYYY-MM-DD HH24-MI-SS')+1
AND fcy_time_in <to_date(CONCAT('$fromdate',' 08:00:01'),'YYYY-MM-DD HH24-MI-SS') 
AND freight_kind IN ('FCL','LCL') THEN 1
ELSE (CASE WHEN siz>20
AND fcy_time_in >to_date(concat('$fromdate', ' 08:00:00'),'YYYY-MM-DD HH24-MI-SS')+1
AND fcy_time_in <to_date(CONCAT('$fromdate',' 08:00:01'),'YYYY-MM-DD HH24-MI-SS')
AND freight_kind IN ('FCL','LCL') THEN 2 ELSE NULL END) END) AS dischage_LD_tues, 
(CASE WHEN siz=20 
AND fcy_time_in >to_date(concat('$fromdate', ' 08:00:00'),'YYYY-MM-DD HH24-MI-SS')+1
AND fcy_time_in <to_date(CONCAT('$fromdate',' 08:00:01'),'YYYY-MM-DD HH24-MI-SS')
AND freight_kind='MTY' THEN 1 
ELSE (CASE WHEN siz>20
AND fcy_time_in >to_date(concat('$fromdate', ' 08:00:00'),'YYYY-MM-DD HH24-MI-SS')+1
AND fcy_time_in <to_date(CONCAT('$fromdate',' 08:00:01'),'YYYY-MM-DD HH24-MI-SS')
AND freight_kind='MTY' THEN 2 ELSE NULL END) END) AS discharge_MT_tues
FROM
(
SELECT substr(ref_equip_type.nominal_length,-2) AS siz, inv_unit_fcy_visit.time_in AS  fcy_time_in,inv_unit.freight_kind
FROM inv_unit
INNER JOIN ref_equipment ON ref_equipment.gkey=inv_unit.eq_gkey 
INNER JOIN ref_equip_type ON ref_equip_type.gkey=ref_equipment.eqtyp_gkey
INNER JOIN inv_unit_fcy_visit ON inv_unit_fcy_visit.unit_gkey=inv_unit.gkey 
INNER JOIN argo_carrier_visit ON inv_unit_fcy_visit.actual_ib_cv=argo_carrier_visit.gkey 
INNER JOIN vsl_vessel_visit_details ON argo_carrier_visit.cvcvd_gkey=vsl_vessel_visit_details.vvd_gkey  
WHERE  vsl_vessel_visit_details.vvd_gkey='$vvdGkey' AND category='IMPRT'
) tmp WHERE fcy_time_in IS NOT NULL
)final");
oci_execute($sqlSummery3);

$rowSummery3="";
//if(!is_bool($sqlSummery3)){
	$rowSummery3=oci_fetch_object($sqlSummery3);
//}


$qq="SELECT 
NVL(SUM(onboard_LD_20),0) AS onboard_LD_20,
NVL(SUM(onboard_LD_40),0) AS onboard_LD_40,
NVL(SUM(onboard_MT_20),0) AS onboard_MT_20,
NVL(SUM(onboard_MT_40),0) AS onboard_MT_40, 
NVL(SUM(onboard_LD_tues),0) AS onboard_LD_tues,
NVL(SUM(onboard_MT_tues),0) AS onboard_MT_tues

FROM (
SELECT DISTINCT inv_unit.gkey AS gkey, inv_unit_fcy_visit.time_in AS fcy_time_in,
(CASE WHEN substr(ref_equip_type.nominal_length,-2) = '20' AND freight_kind IN ('FCL','LCL')  THEN 1  
ELSE NULL END) AS onboard_LD_20, 
(CASE WHEN substr(ref_equip_type.nominal_length,-2) > '20' AND freight_kind IN ('FCL','LCL')  THEN 1  
ELSE NULL END) AS onboard_LD_40,
(CASE WHEN substr(ref_equip_type.nominal_length,-2) = '20' AND freight_kind ='MTY'  THEN 1 
ELSE NULL END) AS onboard_MT_20, 
(CASE WHEN substr(ref_equip_type.nominal_length,-2) > '20' AND freight_kind ='MTY'  THEN 1  
ELSE NULL END) AS onboard_MT_40,
(CASE WHEN substr(ref_equip_type.nominal_length,-2)=20 AND freight_kind IN ('FCL','LCL') THEN 1 
ELSE (CASE WHEN substr(ref_equip_type.nominal_length,-2)>20 AND freight_kind IN ('FCL','LCL') THEN 2 ELSE NULL END) END) AS onboard_LD_tues, 
(CASE WHEN substr(ref_equip_type.nominal_length,-2)=20 AND freight_kind='MTY' THEN 1 
ELSE (CASE WHEN substr(ref_equip_type.nominal_length,-2)>20 AND freight_kind='MTY' THEN 2 ELSE NULL END) END) AS onboard_MT_tues


FROM inv_unit
INNER JOIN ref_equipment ON ref_equipment.gkey=inv_unit.eq_gkey 
INNER JOIN ref_equip_type ON ref_equip_type.gkey=ref_equipment.eqtyp_gkey
INNER JOIN inv_unit_fcy_visit ON inv_unit_fcy_visit.unit_gkey=inv_unit.gkey 
INNER JOIN argo_carrier_visit ON inv_unit_fcy_visit.actual_ib_cv=argo_carrier_visit.gkey 
INNER JOIN vsl_vessel_visit_details ON argo_carrier_visit.cvcvd_gkey=vsl_vessel_visit_details.vvd_gkey 
WHERE  vsl_vessel_visit_details.vvd_gkey='$vvdGkey'  AND inv_unit.category='IMPRT' 
AND inv_unit_fcy_visit.transit_state='S20_INBOUND' AND inv_unit_fcy_visit.time_in IS NULL
)  tmp WHERE fcy_time_in IS NULL";

//echo $qq;
$sqlSummery=oci_parse($con_sparcsn4_oracle,$qq);
oci_execute($sqlSummery);
$rowSummery = "";

//if(!is_bool($sqlSummery)){
	$rowSummery=oci_fetch_object($sqlSummery);
//}

$sqlSummery2=oci_parse($con_sparcsn4_oracle,"SELECT 
NVL(SUM(balance_LD_20),0) AS balance_LD_20,
NVL(SUM(balance_LD_40),0) AS balance_LD_40,
NVL(SUM(balance_MT_20),0) AS balance_MT_20,
NVL(SUM(balance_MT_40),0) AS balance_MT_40,
NVL(SUM(balance_LD_tues),0) AS balance_LD_tues,
NVL(SUM(balance_MT_tues),0) AS balance_MT_tues

 FROM (
SELECT DISTINCT inv_unit.gkey AS gkey, inv_unit_fcy_visit.time_in AS fcy_transit_state,
(CASE WHEN  substr(ref_equip_type.nominal_length,-2) = '20' AND freight_kind IN ('FCL','LCL')  THEN 1  
ELSE NULL END) AS balance_LD_20, 
(CASE WHEN  substr(ref_equip_type.nominal_length,-2) > '20' AND freight_kind IN ('FCL','LCL')  THEN 1 
ELSE NULL END) AS balance_LD_40,
(CASE WHEN  substr(ref_equip_type.nominal_length,-2) = '20' AND freight_kind ='MTY'  THEN 1  
ELSE NULL END) AS balance_MT_20, 
(CASE WHEN  substr(ref_equip_type.nominal_length,-2) > '20' AND freight_kind ='MTY'  THEN 1 
ELSE NULL END) AS balance_MT_40, 
(CASE WHEN  substr(ref_equip_type.nominal_length,-2)=20 AND freight_kind IN ('FCL','LCL') THEN 1 
ELSE (CASE  WHEN substr(ref_equip_type.nominal_length,-2)>20 AND freight_kind IN ('FCL','LCL') THEN 2 ELSE NULL END) END) AS balance_LD_tues, 
(CASE WHEN  substr(ref_equip_type.nominal_length,-2)=20 AND freight_kind='MTY' THEN 1 
ELSE (CASE  WHEN substr(ref_equip_type.nominal_length,-2)>20 AND freight_kind='MTY' THEN 2 ELSE NULL END) END) AS balance_MT_tues

FROM inv_unit
INNER JOIN inv_unit_fcy_visit ON inv_unit_fcy_visit.unit_gkey=inv_unit.gkey
INNER JOIN argo_carrier_visit ON argo_carrier_visit.gkey=inv_unit_fcy_visit.actual_ob_cv
INNER JOIN ref_equipment ON ref_equipment.gkey=inv_unit.eq_gkey
INNER JOIN ref_equip_type ON ref_equip_type.gkey=ref_equipment.eqtyp_gkey
WHERE  argo_carrier_visit.cvcvd_gkey=$vvdGkey' AND category='IMPRT' AND inv_unit_fcy_visit.time_in NOT IN ('S20_INBOUND')
)  tmp");
oci_execute($sqlSummery2);
$rowSummery2="";
//if(!is_bool($sqlSummery2)){
	$rowSummery2=oci_fetch_object($sqlSummery2);
//}


?>
<table width="100%" border ='0' cellpadding='0' cellspacing='0'>
<tr><td colspan="12" align="center"><font size="4"><b><u>IMPORT</u></b></font></td></tr>

</table>
<table width="100%" border ='1' cellpadding='0' cellspacing='0'>
	<tr>
		<td colspan="6" align="center">DISCHARGED</td>
		<td colspan="6" align="center">TOTAL DISCHARGED</td>
		<td colspan="6" align="center">BALANCE ON BOARD</td>
	</tr>
	<tr>
		<td colspan="2" align="center">LADEN</td>
		<td colspan="2" align="center">EMPTY</td>
		<td colspan="2" align="center">TUES</td>
		<td colspan="2" align="center">LADEN</td>
		<td colspan="2" align="center">EMPTY</td>
		<td colspan="2" align="center">TUES</td>
		<td colspan="2" align="center">LADEN</td>
		<td colspan="2" align="center">EMPTY</td>
		<td colspan="2" align="center">TUES</td>
		
	</tr>
	<tr>
		<td align="center">20</td>
		<td align="center">40</td>
		<td align="center">20</td>
		<td align="center">40</td>
		<td align="center">LD</td>
		<td align="center">MT</td>
		<td align="center">20</td>
		<td align="center">40</td>
		<td align="center">20</td>
		<td align="center">40</td>
		<td align="center">LD</td>
		<td align="center">MT</td>
		<td align="center">20</td>
		<td align="center">40</td>
		<td align="center">20</td>
		<td align="center">40</td>
		<td align="center">LD</td>
		<td align="center">MT</td>
	</tr>
	<tr>
		
	
		<td align="center"><a href="<?php echo site_url('report/getLast24HourDischargeImportContainerList/20/'.$fromdate.'/'.$vvdGkey.'/discharge_done_LD_20') ?>" target="_BLANK"><?php if($rowSummery3->DISCHARGE_DONE_LD_20) echo $rowSummery3->DISCHARGE_DONE_LD_20; else echo ""; ?></a></td>
		<td align="center"><a href="<?php echo site_url('report/getLast24HourDischargeImportContainerList/20/'.$fromdate.'/'.$vvdGkey.'/discharge_done_LD_40') ?>" target="_BLANK"><?php if($rowSummery3->DISCHARGE_DONE_LD_40) echo $rowSummery3->DISCHARGE_DONE_LD_40; else echo ""; ?></a></td>
		<td align="center"><a href="<?php echo site_url('report/getLast24HourDischargeImportContainerList/20/'.$fromdate.'/'.$vvdGkey.'/discharge_done_MT_20') ?>" target="_BLANK"><?php if($rowSummery3->DISCHARGE_DONE_MT_20) echo $rowSummery3->DISCHARGE_DONE_MT_20; else echo ""; ?></a></td>
		<td align="center"><a href="<?php echo site_url('report/getLast24HourDischargeImportContainerList/20/'.$fromdate.'/'.$vvdGkey.'/discharge_done_MT_40') ?>" target="_BLANK"><?php if($rowSummery3->DISCHARGE_DONE_MT_40) echo $rowSummery3->DISCHARGE_DONE_MT_40; else echo ""; ?></a></td>
		<td align="center"><?php if($rowSummery3->DISCHAGE_LD_TUES) echo $rowSummery3->DISCHAGE_LD_TUES; else echo ""; ?></td>
		<td align="center"><?php if($rowSummery3->DISCHARGE_MT_TUES) echo $rowSummery3->DISCHARGE_MT_TUES; else echo ""; ?></td>
		
		<td align="center"><a href="<?php echo site_url('report/getLast24HourDischargeImportContainerList/20/'.$fromdate.'/'.$vvdGkey.'/balance_LD_20') ?>" target="_BLANK"><?php if($rowSummery2->BALANCE_LD_20 ) echo $rowSummery2->BALANCE_LD_20; else echo ""; ?></a></td>
		<td align="center"><a href="<?php echo site_url('report/getLast24HourDischargeImportContainerList/20/'.$fromdate.'/'.$vvdGkey.'/balance_LD_40') ?>" target="_BLANK"><?php if($rowSummery2->BALANCE_LD_40) echo $rowSummery2->BALANCE_LD_40; else echo ""; ?></a></td>
		<td align="center"><a href="<?php echo site_url('report/getLast24HourDischargeImportContainerList/20/'.$fromdate.'/'.$vvdGkey.'/balance_MT_20') ?>" target="_BLANK"><?php if($rowSummery2->BALANCE_MT_20) echo $rowSummery2->BALANCE_MT_20; else echo ""; ?></a></td>
		<td align="center"><a href="<?php echo site_url('report/getLast24HourDischargeImportContainerList/20/'.$fromdate.'/'.$vvdGkey.'/balance_MT_40') ?>" target="_BLANK"><?php if($rowSummery2->BALANCE_MT_40) echo $rowSummery2->BALANCE_MT_40; else echo ""; ?></a></td>
		<td align="center"><?php if( $rowSummery2->BALANCE_LD_TUES) echo $rowSummery2->BALANCE_LD_TUES; else echo ""; ?></td>
		<td align="center"><?php if( $rowSummery2->BALANCE_MT_TUES) echo $rowSummery2->BALANCE_MT_TUES; else echo ""; ?></td>
		
		<td align="center"><a href="<?php echo site_url('report/getLast24HourDischargeImportContainerList/20/'.$fromdate.'/'.$vvdGkey.'/onboard_LD_20') ?>" target="_BLANK"><?php if($rowSummery->ONBOARD_LD_20 ) echo $rowSummery->ONBOARD_LD_20; else echo ""; ?></a></td>
		<td align="center"><a href="<?php echo site_url('report/getLast24HourDischargeImportContainerList/20/'.$fromdate.'/'.$vvdGkey.'/onboard_LD_40') ?>" target="_BLANK"><?php if($rowSummery->ONBOARD_LD_40) echo $rowSummery->onboard_LD_40; else echo ""; ?></a></td>
		<td align="center"><a href="<?php echo site_url('report/getLast24HourDischargeImportContainerList/20/'.$fromdate.'/'.$vvdGkey.'/onboard_MT_20') ?>" target="_BLANK"><?php if($rowSummery->ONBOARD_MT_20 ) echo $rowSummery->ONBOARD_MT_20; else echo ""; ?></a></td>
		<td align="center"><a href="<?php echo site_url('report/getLast24HourDischargeImportContainerList/20/'.$fromdate.'/'.$vvdGkey.'/onboard_MT_40') ?>" target="_BLANK"><?php if($rowSummery->ONBOARD_MT_40) echo $rowSummery->ONBOARD_MT_40; else echo ""; ?></a></td>
		<td align="center"><?php if($rowSummery->ONBOARD_LD_TUES) echo $rowSummery->ONBOARD_LD_TUES; else echo ""; ?></td>
		<td align="center"><?php if($rowSummery->ONBOARD_MT_TUES) echo $rowSummery->ONBOARD_MT_TUES; else echo ""; ?></td>

		
	</tr>
</table>

<!--EXPORT-->

<?php

$sqlSummery4=oci_parse($con_sparcsn4_oracle,"

SELECT 
NVL(SUM(discharge_done_LD_20),0) AS discharge_done_LD_20,
NVL(SUM(discharge_done_LD_40),0) AS discharge_done_LD_40,
NVL(SUM(discharge_done_MT_20),0) AS discharge_done_MT_20,
NVL(SUM(discharge_done_MT_40),0) AS discharge_done_MT_40,
NVL(SUM(dischage_LD_tues),0) AS dischage_LD_tues,
NVL(SUM(discharge_MT_tues),0) AS discharge_MT_tues

FROM (
SELECT DISTINCT inv_unit.gkey AS gkey,
(CASE WHEN substr(ref_equip_type.nominal_length,-2) = 20
AND inv_unit_fcy_visit.time_in >to_date(concat('$fromdate', ' 08:00:00'),'YYYY-MM-DD HH24-MI-SS')+1
AND inv_unit_fcy_visit.time_in <to_date(CONCAT('$fromdate',' 08:00:01'),'YYYY-MM-DD HH24-MI-SS') 
AND freight_kind IN ('FCL','LCL')  THEN 1  
ELSE NULL END) AS discharge_done_LD_20, 
(CASE WHEN substr(ref_equip_type.nominal_length,-2) !=20
AND inv_unit_fcy_visit.time_in >to_date(concat('$fromdate', ' 08:00:00'),'YYYY-MM-DD HH24-MI-SS')+1
AND inv_unit_fcy_visit.time_in <to_date(CONCAT('$fromdate',' 08:00:01'),'YYYY-MM-DD HH24-MI-SS') 
AND inv_unit.freight_kind IN ('FCL','LCL')  THEN 1  
ELSE NULL END) AS discharge_done_LD_40,
(CASE WHEN substr(ref_equip_type.nominal_length,-2) = 20
AND inv_unit_fcy_visit.time_in >to_date(concat('$fromdate', ' 08:00:00'),'YYYY-MM-DD HH24-MI-SS')+1
AND inv_unit_fcy_visit.time_in <to_date(CONCAT('$fromdate',' 08:00:01'),'YYYY-MM-DD HH24-MI-SS')
AND inv_unit.freight_kind ='MTY'  THEN 1  
ELSE NULL END) AS discharge_done_MT_20, 
(CASE WHEN substr(ref_equip_type.nominal_length,-2) !=20
AND inv_unit_fcy_visit.time_in >to_date(concat('$fromdate', ' 08:00:00'),'YYYY-MM-DD HH24-MI-SS')+1
AND inv_unit_fcy_visit.time_in <to_date(CONCAT('$fromdate',' 08:00:01'),'YYYY-MM-DD HH24-MI-SS')
AND inv_unit.freight_kind ='MTY'  THEN 1  
ELSE NULL END) AS discharge_done_MT_40, 
(CASE WHEN substr(ref_equip_type.nominal_length,-2)=20 
AND inv_unit_fcy_visit.time_in >to_date(concat('$fromdate', ' 08:00:00'),'YYYY-MM-DD HH24-MI-SS')+1
AND inv_unit_fcy_visit.time_in <to_date(CONCAT('$fromdate',' 08:00:01'),'YYYY-MM-DD HH24-MI-SS')
AND freight_kind IN ('FCL','LCL') THEN 1 
ELSE (CASE WHEN substr(ref_equip_type.nominal_length,-2)>20 
AND inv_unit_fcy_visit.time_in >to_date(concat('$fromdate', ' 08:00:00'),'YYYY-MM-DD HH24-MI-SS')+1
AND inv_unit_fcy_visit.time_in <to_date(CONCAT('$fromdate',' 08:00:01'),'YYYY-MM-DD HH24-MI-SS')
AND freight_kind IN ('FCL','LCL') THEN 2 ELSE NULL END) END) AS dischage_LD_tues, 
(CASE WHEN substr(ref_equip_type.nominal_length,-2)=20 
AND inv_unit_fcy_visit.time_in >to_date(concat('$fromdate', ' 08:00:00'),'YYYY-MM-DD HH24-MI-SS')+1
AND inv_unit_fcy_visit.time_in <to_date(CONCAT('$fromdate',' 08:00:01'),'YYYY-MM-DD HH24-MI-SS') 
AND inv_unit.freight_kind='MTY' THEN 1 
ELSE (CASE WHEN substr(ref_equip_type.nominal_length,-2)>20 
AND inv_unit_fcy_visit.time_in >to_date(concat('$fromdate', ' 08:00:00'),'YYYY-MM-DD HH24-MI-SS')+1
AND inv_unit_fcy_visit.time_in <to_date(CONCAT('$fromdate',' 08:00:01'),'YYYY-MM-DD HH24-MI-SS')
AND inv_unit.freight_kind='MTY' THEN 2 ELSE NULL END) END) AS discharge_MT_tues

FROM inv_unit
INNER JOIN inv_unit_fcy_visit ON inv_unit_fcy_visit.unit_gkey=inv_unit.gkey
INNER JOIN argo_carrier_visit ON argo_carrier_visit.gkey=inv_unit_fcy_visit.actual_ob_cv
INNER JOIN ref_equipment ON ref_equipment.gkey=inv_unit.eq_gkey
INNER JOIN ref_equip_type ON ref_equip_type.gkey=ref_equipment.eqtyp_gkey
WHERE argo_carrier_visit.cvcvd_gkey='$vvdGkey' AND category='EXPRT' AND inv_unit_fcy_visit.time_in IS NOT NULL
)  tmp");
oci_execute($sqlSummery4);

$rowSummery4="";
//if(!is_bool($sqlSummery4)){
	$rowSummery4=oci_fetch_object($sqlSummery4);
//}

$sqlSummery5=oci_parse($con_sparcsn4_oracle,"SELECT 
nvl(SUM(balance_LD_20),0) AS balance_LD_20,
nvl(SUM(balance_LD_40),0) AS balance_LD_40,
nvl(SUM(balance_MT_20),0) AS balance_MT_20,
nvl(SUM(balance_MT_40),0) AS balance_MT_40,
nvl(SUM(balance_LD_tues),0) AS balance_LD_tues,
nvl(SUM(balance_MT_tues),0) AS balance_MT_tues

 FROM (
SELECT DISTINCT inv_unit.gkey AS gkey, inv_unit_fcy_visit.time_in AS fcy_transit_state,

(CASE WHEN  substr(ref_equip_type.nominal_length,-2) = '20' AND freight_kind IN ('FCL','LCL')  THEN 1  
ELSE NULL END) AS balance_LD_20, 
(CASE WHEN  substr(ref_equip_type.nominal_length,-2) > '20' AND freight_kind IN ('FCL','LCL')  THEN 1  
ELSE NULL END) AS balance_LD_40,
(CASE WHEN  substr(ref_equip_type.nominal_length,-2) = '20' AND freight_kind ='MTY'  THEN 1  
ELSE NULL END) AS balance_MT_20, 
(CASE WHEN  substr(ref_equip_type.nominal_length,-2) > '20' AND freight_kind ='MTY'  THEN 1  
ELSE NULL END) AS balance_MT_40, 
(CASE WHEN  substr(ref_equip_type.nominal_length,-2)=20 AND freight_kind IN ('FCL','LCL') THEN 1 
ELSE (CASE  WHEN substr(ref_equip_type.nominal_length,-2)>20 AND freight_kind IN ('FCL','LCL') THEN 2 ELSE NULL END) END) AS balance_LD_tues, 
(CASE WHEN  substr(ref_equip_type.nominal_length,-2)=20 AND freight_kind='MTY' THEN 1 
ELSE (CASE  WHEN substr(ref_equip_type.nominal_length,-2)>20 AND freight_kind='MTY' THEN 2 ELSE NULL END) END) AS balance_MT_tues

FROM inv_unit
INNER JOIN inv_unit_fcy_visit ON inv_unit_fcy_visit.unit_gkey=inv_unit.gkey
INNER JOIN argo_carrier_visit ON argo_carrier_visit.gkey=inv_unit_fcy_visit.actual_ob_cv
INNER JOIN ref_equipment ON ref_equipment.gkey=inv_unit.eq_gkey 
INNER JOIN ref_equip_type ON ref_equip_type.gkey=ref_equipment.eqtyp_gkey
WHERE  argo_carrier_visit.cvcvd_gkey='$vvdGkey' AND category='EXPRT' AND inv_unit_fcy_visit.time_in NOT IN ('S20_INBOUND')
)  tmp");
oci_execute($sqlSummery5);

$rowSummery5="";
//if(!is_bool($sqlSummery5)){
	$rowSummery5=oci_fetch_object($sqlSummery5);
//}

$sqlSummery6=oci_parse($con_sparcsn4_oracle,"SELECT 
NVL(SUM(onboard_LD_20),0) AS onboard_LD_20,
NVL(SUM(onboard_LD_40),0) AS onboard_LD_40,
NVL(SUM(onboard_MT_20),0) AS onboard_MT_20,
NVL(SUM(onboard_MT_40),0) AS onboard_MT_40,
NVL(SUM(onboard_LD_tues),0) AS onboard_LD_tues,
NVL(SUM(onboard_MT_tues),0) AS onboard_MT_tues

 FROM (
SELECT DISTINCT inv_unit.gkey AS gkey, inv_unit_fcy_visit.time_in AS fcy_time_in,
(CASE WHEN substr(ref_equip_type.nominal_length,-2) = '20' AND freight_kind IN ('FCL','LCL')  THEN 1  
ELSE NULL END) AS onboard_LD_20, 
(CASE WHEN substr(ref_equip_type.nominal_length,-2) > '20' AND freight_kind IN ('FCL','LCL')  THEN 1  
ELSE NULL END) AS onboard_LD_40,
(CASE WHEN substr(ref_equip_type.nominal_length,-2) = '20' AND freight_kind ='MTY'  THEN 1 
ELSE NULL END) AS onboard_MT_20, 
(CASE WHEN substr(ref_equip_type.nominal_length,-2) > '20' AND freight_kind ='MTY'  THEN 1  
ELSE NULL END) AS onboard_MT_40, 
(CASE WHEN substr(ref_equip_type.nominal_length,-2)=20 AND freight_kind IN ('FCL','LCL') THEN 1 
ELSE (CASE WHEN substr(ref_equip_type.nominal_length,-2)>20 AND freight_kind IN ('FCL','LCL') THEN 2 ELSE NULL END) END) AS onboard_LD_tues, 
(CASE WHEN substr(ref_equip_type.nominal_length,-2)=20 AND freight_kind='MTY' THEN 1
ELSE (CASE WHEN substr(ref_equip_type.nominal_length,2)>20 AND freight_kind='MTY' THEN 2 ELSE NULL END) END) AS onboard_MT_tues
FROM inv_unit

INNER JOIN ref_equipment ON ref_equipment.gkey=inv_unit.eq_gkey 
INNER JOIN ref_equip_type ON ref_equip_type.gkey=ref_equipment.eqtyp_gkey
INNER JOIN inv_unit_fcy_visit ON inv_unit_fcy_visit.unit_gkey=inv_unit.gkey 
INNER JOIN argo_carrier_visit ON inv_unit_fcy_visit.actual_ib_cv=argo_carrier_visit.gkey 
INNER JOIN vsl_vessel_visit_details ON argo_carrier_visit.cvcvd_gkey=vsl_vessel_visit_details.vvd_gkey 
WHERE  vsl_vessel_visit_details.vvd_gkey='$vvdGkey'  AND inv_unit.category='EXPRT' 
AND inv_unit_fcy_visit.transit_state='S20_INBOUND' AND inv_unit_fcy_visit.time_in IS NULL
)  tmp WHERE fcy_time_in IS NULL");
oci_execute($sqlSummery6);

$rowSummery6="";
//if(!is_bool($sqlSummery6)){
	$rowSummery6=oci_fetch_object($sqlSummery6);
//}

?>
<table width="100%" border ='0' cellpadding='0' cellspacing='0'>
<tr><td colspan="12" align="center"><font size="4"><b><u></u></b></font></td></tr>
<tr><td colspan="12" align="center"><font size="4"><b></b></font></td></tr>
</table>
<table width="100%" border ='0' cellpadding='0' cellspacing='0'>
<tr><td colspan="12" align="center"><font size="4"><b><u>EXPORT</u></b></font></td></tr>

</table>
<table width="100%" border ='1' cellpadding='0' cellspacing='0'>
	<tr>
		<td colspan="6" align="center">SHIPMENT</td>
		<td colspan="6" align="center">TOTAL ON BOARD</td>
		<td colspan="6" align="center">BALANCE TO SHIPMENT</td>
	</tr>
	<tr>
		<td colspan="2" align="center">LADEN</td>
		<td colspan="2" align="center">EMPTY</td>
		<td colspan="2" align="center">TUES</td>
		<td colspan="2" align="center">LADEN</td>
		<td colspan="2" align="center">EMPTY</td>
		<td colspan="2" align="center">TUES</td>
		<td colspan="2" align="center">LADEN</td>
		<td colspan="2" align="center">EMPTY</td>
		<td colspan="2" align="center">TUES</td>
		
	</tr>
	<tr>
		<td align="center">20</td>
		<td align="center">40</td>
		<td align="center">20</td>
		<td align="center">40</td>
		<td align="center">LD</td>
		<td align="center">MT</td>
		<td align="center">20</td>
		<td align="center">40</td>
		<td align="center">20</td>
		<td align="center">40</td>
		<td align="center">LD</td>
		<td align="center">MT</td>
		<td align="center">20</td>
		<td align="center">40</td>
		<td align="center">20</td>
		<td align="center">40</td>
		<td align="center">LD</td>
		<td align="center">MT</td>
	</tr>
	<tr>
		
	
		<td align="center"><a href="<?php echo site_url('report/getLast24HourDischargeExportContainerList/20/'.$fromdate.'/'.$vvdGkey.'/discharge_done_LD_20') ?>" target="_BLANK"><?php if($rowSummery4->DISCHARGE_DONE_LD_20 ) echo $rowSummery4->DISCHARGE_DONE_LD_20; else echo ""; ?></a></td>
		<td align="center"><a href="<?php echo site_url('report/getLast24HourDischargeExportContainerList/20/'.$fromdate.'/'.$vvdGkey.'/discharge_done_LD_40') ?>" target="_BLANK"><?php if($rowSummery4->DISCHARGE_DONE_LD_40) echo $rowSummery4->DISCHARGE_DONE_LD_40; else echo ""; ?></a></td>
		<td align="center"><a href="<?php echo site_url('report/getLast24HourDischargeExportContainerList/20/'.$fromdate.'/'.$vvdGkey.'/discharge_done_MT_20') ?>" target="_BLANK"><?php if($rowSummery4->DISCHARGE_DONE_MT_20) echo $rowSummery4->DISCHARGE_DONE_MT_20; else echo ""; ?></a></td>
		<td align="center"><a href="<?php echo site_url('report/getLast24HourDischargeExportContainerList/20/'.$fromdate.'/'.$vvdGkey.'/discharge_done_MT_40') ?>" target="_BLANK"><?php if($rowSummery4->DISCHARGE_DONE_MT_40) echo $rowSummery4->DISCHARGE_DONE_MT_40; else echo ""; ?></a></td>
		<td align="center"><?php if($rowSummery4->DISCHAGE_LD_TUES ) echo $rowSummery4->DISCHAGE_LD_TUES; else echo ""; ?></td>
		<td align="center"><?php if($rowSummery4->DISCHARGE_MT_TUES ) echo $rowSummery4->DISCHARGE_MT_TUES; else echo ""; ?></td>
		
		<td align="center"><a href="<?php echo site_url('report/getLast24HourDischargeExportContainerList/20/'.$fromdate.'/'.$vvdGkey.'/balance_LD_20') ?>" target="_BLANK"><?php if($rowSummery5->BALANCE_LD_20 ) echo $rowSummery5->BALANCE_LD_20; else echo ""; ?></a></td>
		<td align="center"><a href="<?php echo site_url('report/getLast24HourDischargeExportContainerList/20/'.$fromdate.'/'.$vvdGkey.'/balance_LD_40') ?>" target="_BLANK"><?php if($rowSummery5->BALANCE_LD_40) echo $rowSummery5->BALANCE_LD_40; else echo ""; ?></a></td>
		<td align="center"><a href="<?php echo site_url('report/getLast24HourDischargeExportContainerList/20/'.$fromdate.'/'.$vvdGkey.'/balance_MT_20') ?>" target="_BLANK"><?php if($rowSummery5->BALANCE_MT_20) echo $rowSummery5->BALANCE_MT_20; else echo ""; ?></a></td>
		<td align="center"><a href="<?php echo site_url('report/getLast24HourDischargeExportContainerList/20/'.$fromdate.'/'.$vvdGkey.'/balance_MT_40') ?>" target="_BLANK"><?php if($rowSummery5->BALANCE_MT_40) echo $rowSummery5->BALANCE_MT_40; else echo ""; ?></a></td>
		<td align="center"><?php if($rowSummery5->BALANCE_LD_TUES) echo $rowSummery5->BALANCE_LD_TUES; else echo ""; ?></td>
		<td align="center"><?php if($rowSummery5->BALANCE_MT_TUES) echo $rowSummery5->BALANCE_MT_TUES; else echo ""; ?></td>
		
		<td align="center"><a href="<?php echo site_url('report/getLast24HourDischargeExportContainerList/20/'.$fromdate.'/'.$vvdGkey.'/onboard_LD_20') ?>" target="_BLANK"><?php if($rowSummery6->ONBOARD_LD_20 ) echo $rowSummery6->ONBOARD_LD_20; else echo ""; ?></a></td>
		<td align="center"><a href="<?php echo site_url('report/getLast24HourDischargeExportContainerList/20/'.$fromdate.'/'.$vvdGkey.'/onboard_LD_40') ?>" target="_BLANK"><?php if($rowSummery6->ONBOARD_LD_40) echo $rowSummery6->ONBOARD_LD_40; else echo ""; ?></a></td>
		<td align="center"><a href="<?php echo site_url('report/getLast24HourDischargeExportContainerList/20/'.$fromdate.'/'.$vvdGkey.'/onboard_MT_20') ?>" target="_BLANK"><?php if($rowSummery6->ONBOARD_MT_20 ) echo $rowSummery6->ONBOARD_MT_20; else echo ""; ?></a></td>
		<td align="center"><a href="<?php echo site_url('report/getLast24HourDischargeExportContainerList/20/'.$fromdate.'/'.$vvdGkey.'/onboard_MT_40') ?>" target="_BLANK"><?php if($rowSummery6->ONBOARD_MT_40) echo $rowSummery6->ONBOARD_MT_40; else echo ""; ?></a></td>
		<td align="center"><?php if($rowSummery6->ONBOARD_LD_TUES ) echo $rowSummery6->ONBOARD_LD_TUES; else echo ""; ?></td>
		<td align="center"><?php if($rowSummery6->ONBOARD_MT_TUES) echo $rowSummery6->ONBOARD_MT_TUES; else echo ""; ?></td>

		
	</tr>
</table>
<!--last-->
<table width="100%" border ='0' cellpadding='0' cellspacing='0'>
<tr><td colspan="12" align="center"><font size="4"><b><u></u></b></font></td></tr>
<tr><td colspan="12" align="center"><font size="4"><b></b></font></td></tr>
</table>
<table width="100%" border ='0' cellpadding='0' cellspacing='0'>
<tr><td colspan="12" align="center"><font size="4"><b><u>PROGRAM</u></b></font></td></tr>

</table>
<table width="100%" border ='1' cellpadding='0' cellspacing='0'>
	<tr>
		<td colspan="6" align="center">IMPORT-01</td>
		<td colspan="6" align="center">EXPORT-01</td>
	</tr>
</table>
<table border=0 width="100%">
				
				
				<tr align="center">
					<td colspan="12"><font size="4"></font></td>
				
				<tr align="center">
					<td colspan="12"><font size="4"><b></b></font></td>
				</tr>
				
				<tr align="center">
					<td colspan="12"><font size="4"></font></td>
				
				<tr align="center">
					<td colspan="12"><font size="4"><b></b></font></td>
				</tr>
				<tr  align="left">
				<td align="left"><font size="4"><b>BERTHED - </b></font>
				<font size="4"><b><?php  Echo $row1->ATA;?></b></font>
				</td>
				</tr>
				<tr>
				<td  align="left"><font size="4"><b> COMMENCE DISCHARGE -</b></font>
				<font size="4"><b><?php  Echo $row1->DISCHARGE_START_TIME ;?> HRS<?php  Echo $row1->DISCHARGE_START;?></b></font>
				</td>
				</tr>
				
				<tr>
				<td align="left">
				<font size="4"><b>COMPLETED DISCHARGE -</b></font>
				<font size="4"><b><?php  Echo $row1->DISCHARGE_COMPLETED_TIME ;?> HRS<?php  Echo $row1->DISCHARGE_COMPLETED;?></b></font>
				</td>
				</tr>
				<tr>
				<td align="left">
				<font size="4"><b>COMMENCE LOAD -</b></font>
				
				</td>
				</tr>
				<tr>
				<td align="left">
				<font size="4"><b>COMPLETED LOAD -</b></font>
				
				</td>
				</tr>
				<tr>
				<td align="left">
				<font size="4"><b>SAILED -</b></font>
				<font size="4"><b><?php  Echo $row1->ATD;?></b></font>
				</td>
				</tr>
				<tr align="center">
					<td colspan="12"><font size="4"></font></td>
				</tr>
				<tr align="center">
					<td colspan="12"><font size="4"><b></b></font></td>
				</tr>
				
				<tr align="center">
					<td colspan="12"><font size="4"></font></td>
				</tr>
				<tr align="right">
					<td colspan="12"><font size="4"><b>Prepared by -</b></font></td>
				</tr>
				
			</table>
<?php 
mysqli_close($con_sparcsn4);
if($_POST['options']=='html'){?>	
	</BODY>
</HTML>
<?php }?>
