<?php if($_POST['fileOptions']=='html'){?>
<HTML>
	<HEAD>
		<TITLE>Berth Operator Report</TITLE>
		<LINK href="../css/report.css" type=text/css rel=stylesheet>
	    <style type="text/css">
<!--
.style1 {font-size: 12px}
-->
        </style>
</HEAD>
<BODY>

	<?php } 
	else if($_POST['fileOptions']=='xl'){
		header("Content-type: application/octet-stream");
		header("Content-Disposition: attachment; filename=EXPORT_SUMMERY.xls;");
		header("Content-Type: application/ms-excel");
		header("Pragma: no-cache");
		header("Expires: 0");

	}
// $ddl_imp_rot_no=$_REQUEST['ddl_imp_rot_no']; 

	include("dbConection.php");
	include("dbOracleConnection.php");
	
	// $sql=mysql_query("select vvd_gkey from sparcsn4.vsl_vessel_visit_details where ib_vyg='$ddl_imp_rot_no'");
	// $row=mysql_fetch_object($sql);
	// $vvdGkey=$row->vvd_gkey;
	
	// $sql="call ctmsmis.update_containers_by_vvd_gkey($vvdGkey)";
	// $res=mysql_query($sql);
	
	if($type=="assign")
	{
		$col = "b.flex_date01";
		$head = "Assignment Date Wise Import Container List";
	}
	
	elseif($type=="assigne")
	{
		$col = "b.flex_date01";
		$head = "Assignment Date(E) Wise Import Container List";
	}
	else
	{
		
		$col = "b.time_out";		
		$head = "Date Wise Delivery Import Container List";
	}
	
	?>
<html>
<title>PROPOSED EMPTY AND EMPTY  CONTAINER REPORT</title>
<body>
<table width="100%" border ='0' cellpadding='0' cellspacing='0'>
<tr bgcolor="#ffffff" align="center" height="100px">
		<td colspan="13" align="center">
			<table border=0 width="100%">
				
				<tr align="center">
					<td colspan="12"><img align="middle" src="<?php echo IMG_PATH?>cpanew.jpg"></td>
				</tr>
			
				<tr align="center">
					<td colspan="12"><font size="4"><b><u>PROPOSED EMPTY AND EMPTY  CONTAINER REPORT</u></b></font></td>
				</tr>
				<tr align="center">
					<td colspan="12"><font size="4"><b></b></font></td>
				</tr>

				<tr align="center">
					<td colspan="12"><font size="4"><b><u><?php echo $head;?></u></b></font></td>
				</tr>

			</table>
		
		</td>
		
	</tr>
	
	<tr bgcolor="#ffffff" align="center" height="25px">
		<td colspan="15" align="center"></td>
		
	</tr>
	</table>
	<table width="100%" border ='1' cellpadding='0' cellspacing='0'>
	<tr  align="center">
		<td style="border-width:3px;border-style: double;"><b>SlNo.</b></td>
		<td style="border-width:3px;border-style: double;"><b>Container No.</b></td>
		<td style="border-width:3px;border-style: double;"><b>Size.</b></td>
		<td style="border-width:3px;border-style: double;"><b>Height.</b></td>
		<td style="border-width:3px;border-style: double;"><b>Rotation No.</b></td>
		<td style="border-width:3px;border-style: double;"><b>Type</b></td>
		<td style="border-width:3px;border-style: double;"><b>Destination</b></td>
		<td style="border-width:3px;border-style: double;"><b>MLO</b></td>
		<td style="border-width:3px;border-style: double;"><b>Status</b></td>		
		<td style="border-width:3px;border-style: double;"><b>Weight</b></td>
		<td style="border-width:3px;border-style: double;"><b>Yard</b></td>
		<td style="border-width:3px;border-style: double;"><b>Position</b></td>
		<td style="border-width:3px;border-style: double;"><b>Discharge date</b></td>
		<td style="border-width:3px;border-style: double;"><b>Assignment Type</b></td>
		<td style="border-width:3px;border-style: double;"><b>Assignment date</b></td>
		<td style="border-width:3px;border-style: double;"><b>Propose Empty Date(E)</b></td>
		<td style="border-width:3px;border-style: double;"><b>Empty/Delivery Date</b></td>
	</tr>

<?php
		
	$query=oci_parse($con_sparcsn4_oracle,"SELECT DISTINCT * FROM (
	SELECT a.id AS cont_no,
	(SELECT inv_goods.destination FROM inv_goods WHERE inv_goods.gkey=a.goods) AS destination,
	b.time_in AS dischargetime,
	b.time_out AS delivery,
	g.id AS mlo,
	a.flex_string01 AS assignmenttype,
	a.gkey,
	a.freight_kind AS statu,
	a.goods_and_ctr_wt_kg AS weight,
	ref_equip_type.iso_group AS iso_code,
	vsl_vessel_visit_details.ib_vyg AS rot_no, 
	substr(ref_equip_type.nominal_height,-2) as hight,
	substr(ref_equip_type.nominal_length,-2) AS siz,
	(SELECT substr(srv_event_field_changes.new_value,7)
	FROM srv_event
	INNER JOIN srv_event_field_changes ON srv_event_field_changes.event_gkey=srv_event.gkey
	WHERE srv_event.applied_to_gkey=a.gkey AND srv_event.event_type_gkey IN(31450,31443,31447) AND srv_event_field_changes.new_value IS NOT NULL 
	AND srv_event_field_changes.new_value !='' AND srv_event_field_changes.new_value !='Y-CGP-.' 
	AND srv_event.gkey<(SELECT srv_event.gkey FROM srv_event 
	INNER JOIN srv_event_field_changes ON srv_event_field_changes.event_gkey=srv_event.gkey
	WHERE srv_event.event_type_gkey IN(31473) AND srv_event.applied_to_gkey=a.gkey
	ORDER BY srv_event_field_changes.gkey DESC FETCH FIRST 1 ROWS ONLY) ORDER BY srv_event.gkey DESC  FETCH FIRST 1 ROWS ONLY) AS carrentPosition,
	
	(SELECT srv_event.created FROM  srv_event 
	INNER JOIN srv_event_field_changes ON srv_event_field_changes.event_gkey=srv_event.gkey
	WHERE applied_to_gkey=a.gkey AND event_type_gkey=31426 AND srv_event_field_changes.new_value='E'  FETCH FIRST 1 ROWS ONLY) AS proEmtyDate,
	b.time_out AS emptyDate,
	b.flex_date01 AS assignmentdate
	FROM inv_unit a
	INNER JOIN inv_unit_fcy_visit b ON b.unit_gkey=a.gkey
	INNER JOIN argo_carrier_visit ON argo_carrier_visit.gkey=a.declrd_ib_cv
	INNER JOIN argo_visit_details ON argo_visit_details.gkey=argo_carrier_visit.cvcvd_gkey
	INNER JOIN vsl_vessel_visit_details ON vsl_vessel_visit_details.vvd_gkey=argo_visit_details.gkey
	INNER JOIN ref_bizunit_scoped g ON a.line_op = g.gkey
	INNER JOIN config_metafield_lov ON a.flex_string01=config_metafield_lov.mfdch_value
	INNER JOIN
	inv_goods j ON j.gkey = a.goods
	LEFT JOIN
	ref_bizunit_scoped k ON k.gkey = j.consignee_bzu
	INNER JOIN ref_equipment ON ref_equipment.gkey=a.eq_gkey
	INNER JOIN ref_equip_type ON ref_equip_type.gkey=ref_equipment.eqtyp_gkey
	WHERE (to_char(b.time_out,'yyyy-mm-dd') BETWEEN '$fromdate' AND '$todate') AND a.seal_nbr3='E'
	) tmp order by emptyDate");
	oci_execute($query);
	

	$i=0;
	$j=0;
	
	$mlo="";
	if(!is_bool($query)){
	
	while(($row=oci_fetch_object($query)) != false ){
	$gkey="";	
	$gkey=$row->GKEY;
	$yardValue="";
	$yardName="";
	$yardValueQuery="SELECT substr(srv_event_field_changes.new_value,7) as yardValue
	FROM srv_event
	INNER JOIN srv_event_field_changes ON srv_event_field_changes.event_gkey=srv_event.gkey
	WHERE srv_event.applied_to_gkey='$gkey' AND srv_event.event_type_gkey='31450' FETCH FIRST 1 ROWS ONLY";
	$yardValueQueryRes=oci_parse($con_sparcsn4_oracle,$yardValueQuery);
	oci_execute($yardValueQueryRes);
	$yardValueRow=oci_fetch_object($yardValueQueryRes);
	$yardValue=$yardValueRow->YARDVALUE;
	$yardNameQuery="SELECT ctmsmis.cont_yard('$yardValue')AS Yard_No";
	$yardNameQueryRes=mysqli_query($con_sparcsn4,$yardNameQuery);
	$yardNameQueryRow=mysqli_fetch_object($yardNameQueryRes);
	$yardName=$yardNameQueryRow->Yard_No;
		
	$i++;
	include("mydbPConnection.php");
	$sqlIsoCode=mysqli_query($con_cchaportdb,"select cont_iso_type from igm_detail_container where cont_number='$row->cont_no'");
	
	//echo "select cont_iso_type from igm_detail_container where cont_number='$row->cont_no";
	$rtnIsoCode=mysqli_fetch_object($sqlIsoCode);
	$iso=$rtnIsoCode->cont_iso_type;	
	
?>
<tr align="center">
		<td><?php  echo $i;?></td>
		<td><?php if($row->CONT_NO) echo $row->CONT_NO; else echo "&nbsp;";?></td>
		<td><?php if($row->SIZ) echo $row->SIZ; else echo "&nbsp;";?></td>
		<td><?php if($row->HEIGHT/10) echo $row->HEIGHT/10; else echo "&nbsp;";?></td>
		<td><?php if($row->ROT_NO) echo $row->ROT_NO; else echo "&nbsp;";?></td>
		<td><?php if($iso) echo $iso; else echo "&nbsp;";?></td>
		<td><?php if($row->DESTINATION) echo $row->DESTINATION; else echo "&nbsp;";?></td>
		<td><?php if($row->MLO) echo $row->MLO; else echo "&nbsp;";?></td>
		<td><?php if($row->STATU) echo $row->STATU; else echo "&nbsp;";?></td>
		<td><?php if($row->WEIGHT) echo $row->WEIGHT; else echo "&nbsp;";?></td>
		<td><?php if($yardName) echo $yardName; else echo "&nbsp;";?></td>
		<td><?php if($row->CARRENTPOSITION) echo $row->CARRENTPOSITION; else echo "&nbsp;";?></td>
		<td><?php if($row->DISCHARGETIME ) echo $row->DISCHARGETIME; else echo "&nbsp;";?></td>
		<td><?php if($row->ASSIGNMENTTYPE) echo $row->ASSIGNMENTTYPE; else echo "&nbsp;";?></td>
		<td><?php if($row->ASSIGNMENTDATE ) echo $row->ASSIGNMENTDATE; else echo "&nbsp;";?></td>
		
		<td><?php if($row->PROEMTYDATE) echo $row->PROEMTYDATE; else echo "&nbsp;";?></td>
		<td><?php if($row->EMPTYDATE) echo $row->EMPTYDATE; else echo "&nbsp;";?></td>
		
				
	</tr>

	<?php 
		mysqli_close($con_cchaportdb);
	
		
  }

} 
?>
</table>
<br />
<br />

<?php
include("dbConection.php");
include("dbOracleConnection.php");

$sqlSummery2=oci_parse($con_sparcsn4_oracle,"SELECT 
SUM(cont_no) AS cont_no,
SUM(impt20_not_done) AS impt20_not_done,
SUM(impt40_not_done) AS impt40_not_done,
SUM(impt20_done) AS impt20_done,
SUM(impt40_done) AS impt40_done

FROM (

SELECT 
(CASE WHEN a.id IS NOT NULL THEN 1 ELSE 0 END) AS cont_no,

(CASE WHEN (substr(ref_equip_type.nominal_length,-2))=20  AND time_out IS NULL THEN 1 ELSE 0 END) AS impt20_not_done,
(CASE WHEN (substr(ref_equip_type.nominal_length,-2))!=20  AND time_out IS NULL THEN 1 ELSE 0 END) AS impt40_not_done,
(CASE WHEN (substr(ref_equip_type.nominal_length,-2))=20  AND time_out IS NOT NULL THEN 1 ELSE 0 END) AS impt20_done,
(CASE WHEN (substr(ref_equip_type.nominal_length,-2))!=20  AND time_out IS NOT NULL THEN 1 ELSE 0 END) AS impt40_done
FROM inv_unit a
INNER JOIN inv_unit_fcy_visit b ON b.unit_gkey=a.gkey
INNER JOIN ref_equipment ON ref_equipment.gkey=a.eq_gkey
INNER JOIN ref_equip_type ON ref_equip_type.gkey=ref_equipment.eqtyp_gkey
WHERE  to_date(to_char(b.flex_date01,'yyyy-mm-dd'),'yyyy-mm-dd') BETWEEN  
to_date('$fromdate','yyyy-mm-dd') AND  to_date('$todate','yyyy-mm-dd') AND a.seal_nbr3='E'
) tmp");
oci_execute($sqlSummery2);

$cont_no = "";
$impt20_not_done="";
$impt40_not_done = "";

if(!is_bool($sqlSummery2)){
	$rowSummery2=oci_fetch_object($sqlSummery2);
	$cont_no = $rowSummery2->CONT_NO;
	$impt20_not_done = $rowSummery2->IMPT20_NOT_DONE;
	$impt40_not_done = $rowSummery2->IMPT40_NOT_DONE;
}

?>
<table width="100%" border ='0' cellpadding='0' cellspacing='0'>
<tr><td colspan="12" align="center"><font size="4"><b><u> Summary For Not Yet Delivery/Empty Report</u></b></font></td></tr>
<tr><td colspan="12" align="center"><font size="4"><b>&nbsp;</b></font></td></tr>
</table>
<table width="40%" border ='1' cellpadding='0' cellspacing='0' align="center">
	<tr>
		<td colspan="6" align="center">Delivery/Empty List</td>
	
	</tr>
	
	<tr>
		<td style="width:160px;" align="center" >Total Container</td>
		<td align="center">20</td>
		<td align="center">40</td>
		
	</tr>
	<tr>
		
	
		<td align="center"><?php if($cont_no) echo $cont_no; else echo "&nbsp;"; ?></td>
		<td align="center"><?php if($impt20_not_done) echo $impt20_not_done; else echo "&nbsp;"; ?></td>
		<td align="center"><?php if($impt40_not_done) echo $impt40_not_done; else echo "&nbsp;"; ?></td>
		
	</tr>
</table>
<?php 
mysqli_close($con_sparcsn4);
if($_POST['fileOptions']=='html'){?>	
	</BODY>
</HTML>
<?php }?>
