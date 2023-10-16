<?php if(@$_POST['options']=='html'){?>
<HTML>
	<HEAD>
		<TITLE>Pangoan Container Discharge</TITLE>
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
	else if($_POST['options']=='xl'){
		header("Content-type: application/octet-stream");
		header("Content-Disposition: attachment; filename=IMPORT_DISCHARGE/$ddl_imp_rot_no.xls;");
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
	
	$sql="call ctmsmis.update_containers_by_vvd_gkey($vvdGkey)";
	$res=mysqli_query($con_sparcsn4,$sql);
	
	$sql1=oci_parse($con_sparcsn4_oracle,"select vsl_vessels.name as vsl_name,NVL(vsl_vessel_visit_details.flex_string02,
	NVL(vsl_vessel_visit_details.flex_string03,'')) as berth_op,NVL(argo_quay.id,'') as berth,
	argo_carrier_visit.ata,argo_carrier_visit.atd from vsl_vessel_visit_details
	inner join argo_carrier_visit on argo_carrier_visit.cvcvd_gkey=vsl_vessel_visit_details.vvd_gkey
	inner join vsl_vessel_berthings on vsl_vessel_berthings.vvd_gkey=vsl_vessel_visit_details.vvd_gkey
	inner join vsl_vessels on vsl_vessels.gkey=vsl_vessel_visit_details.vessel_gkey
	inner join argo_quay on argo_quay.gkey=vsl_vessel_berthings.quay
	where vsl_vessel_visit_details.vvd_gkey='$vvdGkey'");
	oci_execute($sql1);
	$row1=oci_fetch_object($sql1);
	
	$cond="";
	if($fromdate!="" and $todate!="")	
	{
		if($fromTime!="")
			$frmDate = $fromdate." ".$fromTime.":00";
		
		if($toTime!="")
			$tDate = $todate." ".$toTime.":00";
		
		//$cond = " AND time_in between to_date('$frmDate','yyyy-mm-dd hh24:mi:ss') and to_date('$tDate','yyyy-mm-dd hh24:mi:ss')";
		$ctmsSqlQuery="select * ctmsmis.mis_disch_cont where disch_dt between '$frmDate' and '$tDate'";
		$ctmsSqlQueryRes=mysqli_query($con_sparcsn4,$ctmsSqlQuery);
		$totalGkey=0;
		$gKeys="";
		$totalGkey=mysqli_num_rows($ctmsSqlQueryRes);
		if($totalGkey!=0){
			$k=0;
			while($rowRes=mysqli_fetch_object($ctmsSqlQueryRes)){
				$k++;
				$gkey="";
				$gkey=$rowRes->gkey;
				if($k==$totalGkey){
					$gKeys=	$gKeys. "'" .$gkey. "'";	
			
				}
				else {
					$gKeys=	$gKeys. "'" .$gkey. "'". ",";	
				}

	
			 }

			 $cond = " AND inv_unit.gkey IN ($gKeys) ";
			
		}
		else{
			$cond = " AND inv_unit.gkey IN ('') ";

		}
	}
	else
	{
		$cond = " ";
	}
        
        
        $sqlVoy=oci_parse($con_sparcsn4_oracle,"SELECT argo_carrier_visit.id  as voy FROM vsl_vessel_visit_details 		
		INNER JOIN argo_carrier_visit ON vsl_vessel_visit_details.vvd_gkey=argo_carrier_visit.cvcvd_gkey
		WHERE ib_vyg='$ddl_imp_rot_no'");
			   	oci_execute($sqlVoy);
	$rowResult=oci_fetch_object($sqlVoy);
	$voy=$rowResult->VOY;
        
        
	?>
<html>
<title>Pangoan Container Discharge List</title>
<body>

<?php 

$sqlMloQry="SELECT DISTINCT r.id AS totMlo
FROM inv_unit
INNER JOIN inv_unit_fcy_visit ON inv_unit_fcy_visit.unit_gkey=inv_unit.gkey
INNER JOIN argo_carrier_visit ON argo_carrier_visit.gkey=inv_unit_fcy_visit.actual_ib_cv
INNER JOIN vsl_vessel_visit_details ON vsl_vessel_visit_details.vvd_gkey=argo_carrier_visit.cvcvd_gkey                     
INNER JOIN  ( ref_bizunit_scoped r      
LEFT JOIN ( ref_agent_representation X      
LEFT JOIN ref_bizunit_scoped Y ON X.agent_gkey=Y.gkey )  ON r.gkey=X.bzu_gkey      
)  ON r.gkey = inv_unit.line_op
INNER JOIN inv_unit_fcy_visit ON inv_unit_fcy_visit.unit_gkey=inv_unit.gkey   
INNER JOIN ref_equipment ON ref_equipment.gkey=inv_unit.eq_gkey
INNER JOIN ref_equip_type ON ref_equip_type.gkey=ref_equipment.eqtyp_gkey        
WHERE vsl_vessel_visit_details.vvd_gkey='$vvdGkey' ". $cond." order by totMlo";

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
		<td colspan="7" align="center"><font size="3"><b>MLO WISE PANGAON DISCHARGING DETAIL</b></font></td>					
	</tr>
	<tr bgcolor="#ffffff" align="center" height="25px">
		<td colspan="5" align="center"></td>
	</tr>
</table>
<!--table width="100%" border ='0' cellpadding='0' cellspacing='0'>
	<thead>
		<tr bgcolor="#ffffff" align="center">
			<td  align="centre"><font size="3"><b>VESSEL-<?php echo $row1->VSL_NAME; ?></b></font></td>
			<td  align="centre"><font size="3"><b>VOY- <?php echo $voysNo; ?></b></font></td>
			<td  align="centre"><font size="3"><b>IMP.ROT- <?php echo $ddl_imp_rot_no; ?></b></font></td>
			<td  align="centre"><font size="3"><b>ARRIVED DATE- <?php echo $row1->ATA; ?></b></font></td>
			<td  align="centre"><font size="3"><b>BERTH-<?php echo $row1->BERTH; ?></b></font></td>
		</tr>
	</thead>
</table-->	

<table width="100%" border ='1' cellpadding='0' cellspacing='0'>
	<thead>
		<tr bgcolor="#ffffff" align="center">
			<td colspan="2"  align="centre"><font size="3"><b>VESSEL-<?php echo $row1->VSL_NAME; ?></b></font></td>
			<td  colspan="2" align="centre"><font size="3"><b>VOY- <?php echo $voy; ?></b></font></td>
			<td  colspan="2" align="centre"><font size="3"><b>IMP.ROT- <?php echo $ddl_imp_rot_no; ?></b></font></td>
			<td  colspan="2" align="centre"><font size="3"><b>ARRIVED DATE- <?php echo $row1->ATA; ?></b></font></td>
			<td  colspan="2" align="centre"><font size="3"><b>BERTH-<?php echo $row1->BERTH; ?></b></font></td>
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
			<!--<td style="border-width:3px;border-style: double;"><b>OffDoc/Port</b></td>-->
			
			<!--<td style="border-width:3px;border-style: double;"><b>Yard</b></td>-->
			<td style="border-width:3px;border-style: double;"><b>Weight</b></td>
			<!--<td style="border-width:3px;border-style: double;"><b>Discharge Time</b></td>-->
			<!--<td style="border-width:3px;border-style: double;"><b>Job Done Time</b></td>-->
			<!--<td style="border-width:3px;border-style: double;"><b>Remarks</b></td>-->
			
		</tr>
	</thead>
	<tbody>

<?php
//IFNULL((SELECT disch_dt FROM ctmsmis.mis_disch_cont WHERE gkey=sparcsn4.inv_unit.gkey),sparcsn4.inv_unit_fcy_visit.time_in) AS time_in,


	$sqlQuery="SELECT * FROM
	(
	SELECT inv_unit.gkey, inv_unit.id as cont_no, substr(ref_equip_type.nominal_length,-2) AS siz,
	substr(ref_equip_type.nominal_height,-2)  AS height,
	ref_equip_type.id AS iso,ref_equip_type.iso_group AS iso_group,		
	r.id AS mlo,category,freight_kind, inv_unit.seal_nbr1 AS seal_nbr1,
	inv_unit.goods_and_ctr_wt_kg AS weight
	FROM inv_unit
	INNER JOIN inv_unit_fcy_visit ON inv_unit_fcy_visit.unit_gkey=inv_unit.gkey
	INNER JOIN argo_carrier_visit ON argo_carrier_visit.gkey=inv_unit_fcy_visit.actual_ib_cv
	INNER JOIN vsl_vessel_visit_details ON vsl_vessel_visit_details.vvd_gkey=argo_carrier_visit.cvcvd_gkey                     
	INNER JOIN  ( ref_bizunit_scoped r      
	LEFT JOIN ( ref_agent_representation X      
	LEFT JOIN ref_bizunit_scoped Y ON X.agent_gkey=Y.gkey )  ON r.gkey=X.bzu_gkey      
	)  ON r.gkey = inv_unit.line_op
	INNER JOIN inv_unit_fcy_visit ON inv_unit_fcy_visit.unit_gkey=inv_unit.gkey   
	INNER JOIN ref_equipment ON ref_equipment.gkey=inv_unit.eq_gkey
	INNER JOIN ref_equip_type ON ref_equip_type.gkey=ref_equipment.eqtyp_gkey                  
	WHERE ib_vyg='$ddl_imp_rot_no' ". $cond." ORDER BY inv_unit.id
	)  tbl where mlo= '".$rowMlo->TOTMLO."' order by mlo";

	$query=oci_parse($con_sparcsn4_oracle,$sqlQuery);
	oci_execute($query);
	$i=0;
	$j=0;
	$mlo="";
	$weight=0;
	$$totWeight=0;
	
	while(($row=oci_fetch_object($query))!=false){
	$i++;
	

?>
<?php 
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
				<td align="center"><?php echo "";?></td><!--
				<td align="center"><?php echo "&nbsp;";?></td>
				<td align="center"><?php echo "&nbsp;";?></td>-->
				<td align="center"><b><?php echo $weight;?></b></td>
<!--				<td align="center"><b><?php echo "";?></b></td>
				<td align="center"><b><?php echo "" ;?></b></td>-->
				<!--<td align="center"><b><?php echo "";?></b></td>-->
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
		<td><?php if($row->CONT_NO) echo $row->CONT_NO; else echo "&nbsp;";?></td>
		
		<td><?php if($row->SIZ) echo $row->SIZ; else echo "&nbsp;";?></td>
		<td><?php if($row->HEIGHT) echo $row->HEIGHT; else echo "&nbsp;";?></td>
		<td><?php if($row->ISO) echo $row->ISO; else echo "&nbsp;";?></td>
		<td><?php if($row->ISO) echo $row->ISO_GROUP; else echo "&nbsp;";?></td>
		<td><?php if($row->FREIGHT_KIND) echo $row->FREIGHT_KIND; else echo "&nbsp;";?></td>
		<td><?php if($row->SEAL_NBR1) echo $row->SEAL_NBR1; else echo "&nbsp;";?></td>
		<td><?php if($row->MLO) echo $row->MLO; else echo "&nbsp;";?></td>
		<!--td><?php if($row->FRMPOS) echo $row->FRMPOS; else echo "&nbsp;";?></td>
		<td><?php if($row->TRUCK_ID) echo $row->TRUCK_ID; else echo "&nbsp;";?></td-->
		<!--<td><?php if($row->DESTI) echo $row->DESTI; else echo "&nbsp;";?></td>-->
		<!--<td><?php if($row->YARD_NO) echo $row->YARD_NO; else echo "&nbsp;";?></td>-->
		<td><?php if($row->WEIGHT) echo $row->WEIGHT; else echo "&nbsp;";?></td>
		<!--<td><?php if($row->TIME_IN) echo $row->TIME_IN; else echo "&nbsp;";?></td>-->
		<!--<td><?php if($row->TIMEIN) echo $row->TIMEIN; else echo "&nbsp;";?></td>-->
		<!--<td><?php if($row->REMARK) echo $row->REMARK;  else echo "&nbsp;";?></td>-->
		
		
				
	</tr>

<?php $totWeight = $totWeight + $row->WEIGHT; } ?>
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
<!--			<td align="center"><b><?php echo "&nbsp;";?></b></td>
			<td align="center"><b><?php echo "&nbsp;";?></b></td>-->
			<td align="center"><b><?php echo $weight;?></b></td>
<!--			<td align="center"><b><?php echo "";?></b></td>
			<td align="center"><b><?php echo "" ;?></b></td>
			<td align="center"><b><?php echo "";?></b></td>-->
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
