<?php if($_POST['options']=='html'){?>
<HTML>
	<HEAD>
		<TITLE>Import Container Discharge</TITLE>
		<LINK href="../css/report.css" type=text/css rel=stylesheet>
	    <style type="text/css">
<!--
.style1 {font-size: 12px}
-->
			@media print {
				@page {margin:0.1 -6cm}
				html {margin:0.1 6cm}
				.pageBreak {
					page-break-after: always;
				}
				.pageBreakOff {
					page-break-before: avoid;
				}
			}
        </style>
</HEAD>
<BODY>

	<?php } 
	else if(@$_POST['options']=='xl'){
		header("Content-type: application/octet-stream");
		header("Content-Disposition: attachment; filename=IMPORT_DISCHARGE/$ddl_imp_rot_no.xls;");
		header("Content-Type: application/ms-excel");
		header("Pragma: no-cache");
		header("Expires: 0");

	}
$ddl_imp_rot_no=$_REQUEST['ddl_imp_rot_no'];

	include("dbConection.php");
	include("dbOracleConnection.php");
	
	$sqlQuery=oci_parse($con_sparcsn4_oracle,"select vvd_gkey from vsl_vessel_visit_details where ib_vyg='$ddl_imp_rot_no'");
	oci_execute($sqlQuery);
	$vvdGkey="";
	while(($row=oci_fetch_object($sqlQuery))!=false){
	//$row=oci_fetch_object($sqlQuery);
	$vvdGkey=$row->VVD_GKEY;	
	}

	
	
	
	//$sql="call ctmsmis.update_containers_by_vvd_gkey($vvdGkey)";
	//$res=mysqli_query($con_sparcsn4,$sql);
	
	$sql1=oci_parse($con_sparcsn4_oracle,"select vsl_vessels.name as vsl_name,
	NVL(vsl_vessel_visit_details.flex_string02,NVL(vsl_vessel_visit_details.flex_string03,'')) as berth_op,
	NVL(argo_quay.id,'') as berth,argo_carrier_visit.ata,argo_carrier_visit.atd from vsl_vessel_visit_details
	inner join argo_carrier_visit on argo_carrier_visit.cvcvd_gkey=vsl_vessel_visit_details.vvd_gkey
	inner join vsl_vessel_berthings on vsl_vessel_berthings.vvd_gkey=vsl_vessel_visit_details.vvd_gkey
	inner join vsl_vessels on vsl_vessels.gkey=vsl_vessel_visit_details.vessel_gkey
	inner join argo_quay on argo_quay.gkey=vsl_vessel_berthings.quay
    where vsl_vessel_visit_details.vvd_gkey='$vvdGkey'");
    oci_execute($sql1);
	$row1=oci_fetch_object($sql1);
	
	$cond="";
	$cond1="";
	$status=0;
	if($fromdate!="" and $todate!="")	
	{
		if($fromTime!="")
			$frmDate = $fromdate." ".$fromTime.":00";
		
		if($toTime!="")
			$tDate = $todate." ".$toTime.":00";
		
		$cond = " AND disch_dt between '$frmDate' and '$tDate'";
		$cond1 = " AND time_in between to_date('$frmDate','yyyy-mm-dd hh24-mi-ss')  AND  to_date('$tDate','yyyy-mm-dd hh24-mi-ss')"; 
		$status=1;
	}
	else
	{
		$cond = " ";
		$cond1 = " ";
		$status=0;
	}
	?>
<html>
<title>Import Container Discharge List</title>
<body>

<?php 

$sqlMloQry="SELECT DISTINCT r.id AS totMlo
			FROM inv_unit 
			INNER JOIN inv_unit_fcy_visit ON inv_unit_fcy_visit.unit_gkey=inv_unit.gkey 
			INNER JOIN argo_carrier_visit ON argo_carrier_visit.gkey=inv_unit.declrd_ib_cv 
			INNER JOIN argo_visit_details ON argo_visit_details.gkey=argo_carrier_visit.cvcvd_gkey 
			INNER JOIN vsl_vessel_visit_details ON vsl_vessel_visit_details.vvd_gkey=argo_visit_details.gkey
			INNER JOIN  ( ref_bizunit_scoped r        
			LEFT JOIN ( ref_agent_representation X        
			LEFT JOIN ref_bizunit_scoped Y ON X.agent_gkey=Y.gkey )  ON r.gkey=X.bzu_gkey        )  ON r.gkey = inv_unit.line_op 
			WHERE inv_unit.category='IMPRT' AND vsl_vessel_visit_details.vvd_gkey='$vvdGkey'  $cond1 order by totMlo";
			
$rsltMloQuery=oci_parse($con_sparcsn4_oracle,$sqlMloQry);
oci_execute($rsltMloQuery);
while(($rowMlo=oci_fetch_object($rsltMloQuery))!=false)
{
	
?>

<table width="100%" border ='0' cellpadding='0' cellspacing='0'>
	
	<tr>
		<td colspan="7" align="center"><img align="middle"  src="<?php echo IMG_PATH?>cpanew.jpg"></td>
	</tr>
	<tr>
		<td colspan="7" align="center"><font size="3"><b>OFFICE OF THE TERMINAL MANAGER</b></font></td>
	</tr>
	<tr>
		<td colspan="7" align="center"><font size="3"><b>MLO WISE FINAL DISCHARGING DETAIL</b></font></td>					
	</tr>
	<tr bgcolor="#ffffff" align="center" height="25px">
		<td colspan="5" align="center"></td>
	</tr>
</table>
<!--table width="100%" border ='0' cellpadding='0' cellspacing='0'>
	<thead>
		<tr bgcolor="#ffffff" align="center">
			<td  align="centre"><font size="3"><b>VESSEL-<?php echo $row1->vsl_name; ?></b></font></td>
			<td  align="centre"><font size="3"><b>VOY- <?php echo $voysNo; ?></b></font></td>
			<td  align="centre"><font size="3"><b>IMP.ROT- <?php echo $ddl_imp_rot_no; ?></b></font></td>
			<td  align="centre"><font size="3"><b>ARRIVED DATE- <?php echo $row1->atd; ?></b></font></td>
			<td  align="centre"><font size="3"><b>BERTH-<?php echo $row1->berth; ?></b></font></td>
		</tr>
	</thead>
</table-->	

<table width="100%" border ='1' cellpadding='0' cellspacing='0'>
	<thead>
		<tr bgcolor="#ffffff" align="center">
			<td colspan="3"  align="centre"><font size="3"><b>VESSEL-<?php echo $row1->VSL_NAME; ?></b></font></td>
			<td  colspan="3" align="centre"><font size="3"><b>VOY- <?php echo $voysNo; ?></b></font></td>
			<td  colspan="3" align="centre"><font size="3"><b>IMP.ROT- <?php echo $ddl_imp_rot_no; ?></b></font></td>
			<td  colspan="3" align="centre"><font size="3"><b>ARRIVED DATE- <?php echo $row1->ATA; ?></b></font></td>
			<td  colspan="3" align="centre"><font size="3"><b>BERTH-<?php echo $row1->BERTH; ?></b></font></td>
		</tr>
		<tr  align="center">
			<td style="border-width:3px;border-style: double;"><b>SlNo.</b></td>
			<td style="border-width:3px;border-style: double;"><b>Container No.</b></td>
		
			<td style="border-width:3px;border-style: double;"><b>Size.</b></td>
			<td style="border-width:3px;border-style: double;"><b>Height.</b></td>
			<td style="border-width:3px;border-style: double;"><b>ISOCode</b></td>
			<td style="border-width:3px;border-style: double;"><b>ISOGroup</b></td>
			<td style="border-width:3px;border-style: double;"><b>Status</b></td>
			<td style="border-width:3px;border-style: double;"><b>Seal NO</b></td>		
			<td style="border-width:3px;border-style: double;"><b>MLO</b></td>
			<!--td style="border-width:3px;border-style: double;"><b>Equipment No.</b></td>
			<td style="border-width:3px;border-style: double;"><b>Trailer</b></td-->
			<td style="border-width:3px;border-style: double;"><b>OffDoc/Port</b></td>
			
			<td style="border-width:3px;border-style: double;"><b>Yard</b></td>
			<td style="border-width:3px;border-style: double;"><b>Weight</b></td>
			<td style="border-width:3px;border-style: double;"><b>Discharge Time</b></td>
			<td style="border-width:3px;border-style: double;"><b>Job Done Time</b></td>
			<td style="border-width:3px;border-style: double;"><b>Remarks</b></td>
			
		</tr>
	</thead>
	<tbody>

<?php


$sqlQuery="SELECT * FROM
(
SELECT CONCAT(CONCAT(substr(inv_unit.id,1,4),' '),substr(inv_unit.id,5)) AS cont_no,
r.id as mlo,
ref_equip_type.id AS iso,ref_equip_type.iso_group AS iso_group,
vsl_vessel_visit_details.vvd_gkey,inv_unit.gkey as gkey,

(SELECT  substr(ref_equip_type.nominal_length,-2) FROM inv_unit
INNER JOIN ref_equipment ON ref_equipment.gkey=inv_unit.eq_gkey 
INNER JOIN ref_equip_type ON ref_equip_type.gkey=ref_equipment.eqtyp_gkey
WHERE inv_unit.gkey=inv_unit_fcy_visit.unit_gkey) AS siz,

(SELECT  substr(ref_equip_type.nominal_height,-2) FROM inv_unit
INNER JOIN ref_equipment ON ref_equipment.gkey=inv_unit.eq_gkey 
INNER JOIN ref_equip_type ON ref_equip_type.gkey=ref_equipment.eqtyp_gkey
WHERE inv_unit.gkey=inv_unit_fcy_visit.unit_gkey) AS height,

inv_unit.freight_kind AS freight_kind,
CASE WHEN inv_goods.destination='2591' THEN 'Port'
WHEN inv_goods.destination IS NULL THEN ''
WHEN inv_goods.destination = '2592' THEN 'ICD'
WHEN inv_goods.destination = '2594' THEN 'Depot'
WHEN inv_goods.destination = '2595' THEN 'Depot'
WHEN inv_goods.destination = '2596' THEN 'Depot'
WHEN inv_goods.destination = '2597' THEN 'Depot'
WHEN inv_goods.destination = '2598' THEN 'Depot'
WHEN inv_goods.destination = '2599' THEN 'Depot'
WHEN inv_goods.destination = '2600' THEN 'Depot'
WHEN inv_goods.destination = '2601' THEN 'Depot'
WHEN inv_goods.destination = '2603' THEN 'Depot'
WHEN inv_goods.destination = '2620' THEN 'Depot'
WHEN inv_goods.destination = '2643' THEN 'Depot'
WHEN inv_goods.destination = '2646' THEN 'Depot'
WHEN inv_goods.destination = '2647' THEN 'other'
WHEN inv_goods.destination = '3328' THEN 'Depot'
WHEN inv_goods.destination = '3450' THEN 'Depot'
WHEN inv_goods.destination = '3697' THEN 'Depot'
WHEN inv_goods.destination = '3709' THEN 'Depot'
WHEN inv_goods.destination = '3725' THEN 'Depot'
WHEN inv_goods.destination = '4013' THEN 'Depot'
ELSE 'Depot' END AS desti,
inv_unit.seal_nbr1 AS seal_nbr1,'' AS remark,

(SELECT substr(srv_event_field_changes.new_value,7)
FROM srv_event
INNER JOIN srv_event_field_changes ON srv_event_field_changes.event_gkey=srv_event.gkey
WHERE srv_event.applied_to_gkey=inv_unit.gkey AND srv_event.event_type_gkey=18 fetch first 1 rows only) AS Yard_No,
inv_unit_fcy_visit.time_in as timein,
inv_unit.goods_and_ctr_wt_kg AS weight

FROM inv_unit 
INNER JOIN inv_unit_fcy_visit ON inv_unit_fcy_visit.unit_gkey=inv_unit.gkey
INNER JOIN argo_carrier_visit ON argo_carrier_visit.gkey=inv_unit.declrd_ib_cv
INNER JOIN argo_visit_details ON argo_visit_details.gkey=argo_carrier_visit.cvcvd_gkey
INNER JOIN vsl_vessel_visit_details ON vsl_vessel_visit_details.vvd_gkey=argo_visit_details.gkey
INNER JOIN inv_goods ON inv_goods.gkey=inv_unit.goods
INNER JOIN ref_equipment ON ref_equipment.gkey=inv_unit.eq_gkey
INNER JOIN ref_equip_type ON ref_equip_type.gkey=ref_equipment.eqtyp_gkey
INNER JOIN  ( ref_bizunit_scoped r  
LEFT JOIN ( ref_agent_representation X  
LEFT JOIN ref_bizunit_scoped Y ON X.agent_gkey=Y.gkey )               
ON r.gkey=X.bzu_gkey)  ON r.gkey = inv_unit.line_op 
WHERE inv_unit.category='IMPRT' AND vsl_vessel_visit_details.vvd_gkey='$vvdGkey' ORDER BY time_in,inv_unit.id
)  tbl where mlo= '$rowMlo->TOTMLO'
order by mlo";
	//echo $sqlQuery;
	$query=oci_parse($con_sparcsn4_oracle,$sqlQuery);
	oci_execute($query);
	$i=0;
	$j=0;
	$mlo="";
	$weight=0;
	$$totWeight=0;
	
	while(($row=oci_fetch_object($query))!= false){
	$i++;
	$key="";
	$yardNo="";
	$yardNo=$row->YARD_NO;
	$key=$row->GKEY;
	$showStatus=1;
	$query1="";
	$query1="SELECT disch_dt AS time_in, trailer AS truck_id, frmpos  FROM ctmsmis.mis_disch_cont WHERE gkey='$key' $cond ";
	$query1Res=mysqli_query($con_sparcsn4,$query1);
	$query1ResTotal=mysqli_num_rows($query1Res);
	$query1Row=mysqli_fetch_object($query1Res);

	$query2="SELECT ctmsmis.cont_yard('$yardNo')AS Yard_No ";
	$query2Res=mysqli_query($con_sparcsn4,$query2);
	$query2Row=mysqli_fetch_object($query2Res);




	if($status=1 && $query1ResTotal==0)	
	{
		$showStatus=0;
		
	}
	else{
		$showStatus=1;

	}

	

?>
<?php 
if($showStatus=1 ){

if($mlo!=$row->MLO)
{

	if($j>0){
		
		?>
		<tr align="center" >
				<td align="center"><b><?php  echo "Total";?></b></td>
				<td align="center"><?php  echo "&nbsp;";?></td>
				<td align="center"><?php  echo "&nbsp;";?></td>		
				<td align="center"><?php echo "";?></td>
				<td align="center"><?php echo "";?></td>
				<td align="center"><?php echo "";?></td>
				<td align="center"><?php echo "";?></td>
				<td align="center"><?php echo "&nbsp;";?></td>
				<td align="center"><?php echo "";?></td>
				<td align="center"><?php echo "&nbsp;";?></td>
				<td align="center"><?php echo "&nbsp;";?></td>
				<td align="center"><b><?php echo $weight;?></b></td>
				<td align="center"><b><?php echo "";?></b></td>
				<td align="center"><b><?php echo "" ;?></b></td>
				<td align="center"><b><?php echo "";?></b></td>
		</tr>
		
	<?php 

	}
	$j=1;
	$weight=$row->WEIGHT;

	//$agentVatAmnt=$row->vatTotal;
	//$agentGtAmnt=$row->GT;
	}else{
		$j++;
		$weight=$weight+$row->WEIGHT;
		//$agentVatAmnt=$agentVatAmnt+$row->vatTotal;
		//$agentGtAmnt=$agentGtAmnt+$row->GT;
	}
	$mlo=$row->MLO;
	
	?>
<tr align="center" >
		
		
		<td><?php  echo $j;?></td>
		<td><?php if($row->cont_no) echo $row->cont_no; else echo "&nbsp;";?></td>
		
		<td><?php if($row->SIZ) echo $row->SIZ ; else echo "&nbsp;";?></td>
		<td><?php if($row->HEIGHT ) echo $row->HEIGHT; else echo "&nbsp;";?></td>
		<td><?php if($row->ISO) echo $row->ISO; else echo "&nbsp;";?></td>
		<td><?php if($row->ISO) echo $row->ISO_GROUP ; else echo "&nbsp;";?></td>
		<td><?php if($row->FREIGHT_KIND) echo $row->FREIGHT_KIND; else echo "&nbsp;";?></td>
		<td><?php if($row->SEAL_NBR1 ) echo $row->SEAL_NBR1; else echo "&nbsp;";?></td>
		<td><?php if($row->MLO) echo $row->MLO; else echo "&nbsp;";?></td>
		<!--td><?php if($query1Row->frmpos) echo $query1Row->frmpos; else echo "&nbsp;";?></td>
		<td><?php if($query1Row->truck_id) echo $query1Row->truck_id; else echo "&nbsp;";?></td-->
		<td><?php if($row->DESTI ) echo $row->DESTI; else echo "&nbsp;";?></td>
		<td><?php if($query2Row->Yard_No) echo $query1Row->Yard_No; else echo "&nbsp;";?></td>
		<td><?php if($row->WEIGHT) echo $row->WEIGHT; else echo "&nbsp;";?></td>
		<td><?php if($query1Row->time_in) echo $query1Row->time_in; else echo "&nbsp;";?></td>
		<td><?php if($row->TIMEIN ) echo $row->TIMEIN; else echo "&nbsp;";?></td>
		<td><?php if($row->REMARKS ) echo $row->REMARKS;  else echo "&nbsp;";?></td>
		
		
				
	</tr>

<?php $totWeight = $totWeight + $row->WEIGHT; } } ?>
	<tr align="center">
			<td align="center"><b><?php  echo "Total";?></b></td>
			<td align="center"><?php  echo "&nbsp;";?></td>
			<td align="center"><?php  echo "&nbsp;";?></td>		
			<td align="center"><b><?php echo "";?></b></td>
			<td align="center"><b><?php echo "";?></b></td>
			<td align="center"><b><?php echo "";?></b></td>
			<td align="center"><b><?php echo "";?></b></td>
			<td align="center"><b><?php echo "&nbsp;";?></b></td>
			<td align="center"><b><?php echo "";?></b></td>
			<td align="center"><b><?php echo "&nbsp;";?></b></td>
			<td align="center"><b><?php echo "&nbsp;";?></b></td>
			<td align="center"><b><?php echo $weight;?></b></td>
			<td align="center"><b><?php echo "";?></b></td>
			<td align="center"><b><?php echo "" ;?></b></td>
			<td align="center"><b><?php echo "";?></b></td>
	</tr>
	

	
	</tbody>
</table>
<div class="pageBreak"></div>
<?php } ?> <!-- Close totalMlo While -->
<!--div style="page-break-before:avoid"></div>
<table width="100%" border ='1' cellpadding='0' cellspacing='0'>
		<tr align="center">
			<td align="left" style="width:68%;"><b><?php  echo "Grand Total";?></b></td>
			
			<td align="left"><b><?php echo $totWeight;?></b></td>
		</tr>
</table-->
<br />
<br />


<?php 
mysqli_close($con_sparcsn4);
if(@$_POST['options']=='html'){?>
	</BODY>
</HTML>
<?php }?>
