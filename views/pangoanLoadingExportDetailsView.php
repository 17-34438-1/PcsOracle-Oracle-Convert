<?php if($_POST['options']=='html'){?>
<HTML>
	<HEAD>
		<TITLE>Export Loading</TITLE>
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
		header("Content-Disposition: attachment; filename=EXPORT_LOADING/$ddl_imp_rot_no.xls;");
		header("Content-Type: application/ms-excel");
		header("Pragma: no-cache");
		header("Expires: 0");

	}
$ddl_imp_rot_no=$_REQUEST['ddl_imp_rot_no']; 

	include("dbConection.php");
	include("dbOracleConnection.php");

	
	
	$sql=oci_parse($con_sparcsn4_oracle,"select vvd_gkey from vsl_vessel_visit_details where ib_vyg='$ddl_imp_rot_no'");
	$row=oci_execute($sql);
	
	$vvdGkey=0;
	while(($row=oci_fetch_object($sql)) !=false)
			{
				$vvdGkey = $row->VVD_GKEY;
				
			}


	$sql="call ctmsmis.update_containers_by_vvd_gkey($vvdGkey)";
	$res=mysqli_query($con_sparcsn4,$sql);
	


	$sql1=oci_parse($con_sparcsn4_oracle,"select vsl_vessels.name as vsl_name,COALESCE(vsl_vessel_visit_details.flex_string02,COALESCE(vsl_vessel_visit_details.flex_string03,'')) as berth_op,COALESCE(argo_quay.id,'') as berth,argo_carrier_visit.ata,argo_carrier_visit.atd from vsl_vessel_visit_details
	inner join argo_carrier_visit on argo_carrier_visit.cvcvd_gkey=vsl_vessel_visit_details.vvd_gkey
	inner join vsl_vessel_berthings on vsl_vessel_berthings.vvd_gkey=vsl_vessel_visit_details.vvd_gkey
	inner join vsl_vessels on vsl_vessels.gkey=vsl_vessel_visit_details.vessel_gkey
	inner join argo_quay on argo_quay.gkey=vsl_vessel_berthings.quay
	where vsl_vessel_visit_details.vvd_gkey=$vvdGkey
			
			");

			$row1=oci_execute($sql1);
			$vsl_name = "";
			$ata = "";
			while(($row1=oci_fetch_object($sql1)) !=false)
			{
				$vsl_name = $row1->VSL_NAME;
				$ata = $row1->ATA ;
			}



	$cond="";
	if($fromdate!="" and $todate!="")	
	{
		if($fromTime!="")
			$frmDate = $fromdate." ".$fromTime.":00";
		
		if($toTime!="")
			$tDate = $todate." ".$toTime.":00";
		
		$cond = " and mis_exp_unit.vvd_gkey='$vvdGkey' and mis_exp_unit.last_update between '$frmDate' and '$tDate'";
	}
	else
	{
		$cond = " and mis_exp_unit.vvd_gkey='$vvdGkey'";
	}
	?>
<html>
<title>Export Loading</title>
<body>
<?php 

// $sqlMloQry="SELECT DISTINCT r.id AS mlo
// FROM sparcsn4.inv_unit
// INNER JOIN sparcsn4.inv_unit_fcy_visit ON sparcsn4.inv_unit_fcy_visit.unit_gkey=sparcsn4.inv_unit.gkey
// INNER JOIN sparcsn4.argo_carrier_visit ON sparcsn4.argo_carrier_visit.gkey=sparcsn4.inv_unit_fcy_visit.actual_ob_cv
// INNER JOIN sparcsn4.vsl_vessel_visit_details ON sparcsn4.vsl_vessel_visit_details.vvd_gkey=sparcsn4.argo_carrier_visit.cvcvd_gkey
// INNER JOIN (sparcsn4.ref_bizunit_scoped r      
// LEFT JOIN (sparcsn4.ref_agent_representation X      
// LEFT JOIN sparcsn4.ref_bizunit_scoped Y ON X.agent_gkey=Y.gkey) ON r.gkey=X.bzu_gkey) ON r.gkey = sparcsn4.inv_unit.line_op
// WHERE ib_vyg='$ddl_imp_rot_no' ORDER BY r.id";


// $rsltMloQuery=mysqli_query($con_sparcsn4,$sqlMloQry);
// while($rowMlo=mysqli_fetch_object($rsltMloQuery))
// {



	
$sqlMloQry="SELECT DISTINCT r.id AS mlo 
FROM inv_unit
INNER JOIN inv_unit_fcy_visit ON inv_unit_fcy_visit.unit_gkey=inv_unit.gkey
INNER JOIN argo_carrier_visit ON argo_carrier_visit.gkey=inv_unit_fcy_visit.actual_ob_cv
INNER JOIN argo_visit_details ON argo_visit_details.gkey=argo_carrier_visit.cvcvd_gkey 
INNER JOIN vsl_vessel_visit_details ON vsl_vessel_visit_details.vvd_gkey=argo_visit_details.gkey 
INNER JOIN  ( ref_bizunit_scoped r        
		LEFT JOIN ( ref_agent_representation X        
		LEFT JOIN ref_bizunit_scoped Y ON X.agent_gkey=Y.gkey )  ON r.gkey=X.bzu_gkey        )  ON r.gkey = inv_unit.line_op
		WHERE ib_vyg='$ddl_imp_rot_no' ORDER BY r.id
" ;



$rsltMloQuery=oci_parse($con_sparcsn4_oracle,$sqlMloQry);
$rsltMlo=oci_execute($rsltMloQuery);
$results=array();
$nrows = oci_fetch_all($rsltMloQuery, $results, null, null, OCI_FETCHSTATEMENT_BY_ROW);



oci_free_statement($rsltMloQuery);

$rsltMloQuery=oci_parse($con_sparcsn4_oracle,$sqlMloQry);
$rsltMlo=oci_execute($rsltMloQuery);
$mlo_details="";
if($nrows>0){


while(($mlo_info=oci_fetch_object($rsltMlo)) !=false)

{
	$mlo_details=$mlo_info->MLO;
}
}	
?>
<table width="100%" border ='0' cellpadding='0' cellspacing='0'>
	<tr>
		<td colspan="7" align="center"><img align="middle" src="<?php echo IMG_PATH?>cpanew.jpg"></td>
	</tr>
	<tr>
		<td colspan="7" align="center"><font size="3"><b>OFFICE OF THE TERMINAL MANAGER</b></font></td>
	</tr>
	<tr>
		<td colspan="7" align="center"><font size="3"><b>MLO WISE PANGOAN LOADING DETAIL</b></font></td>					
	</tr>
	<tr bgcolor="#ffffff" align="center" height="25px">
		<td colspan="5" align="center"></td>
	</tr>
	<tr bgcolor="#ffffff" align="center" height="25px">
		<td colspan="9" align="center"></td>
	</tr>
</table>

	<table width="100%" border ='1' cellpadding='0' cellspacing='0'>
	<thead>
	<tr bgcolor="#ffffff" align="center">
		<td colspan="3" align="centre"><font size="3"><b>VESSEL-<?php echo $vsl_name; ?></b></font></td>
		<td colspan="3" align="centre"><font size="3"><b>VOY- <?php echo $voysNo; ?></b></font></td>
		<td colspan="3" align="centre"><font size="3"><b>EXP.ROT- <?php echo $ddl_imp_rot_no; ?></b></font></td>
	
	</tr>

	<tr  align="center">
		<td style="border-width:3px;border-style: double;"><b>SlNo.</b></td>
		<td style="border-width:3px;border-style: double;"><b>Container No.</b></td>
		
		<td style="border-width:3px;border-style: double;"><b>Size.</b></td>
		<td style="border-width:3px;border-style: double;"><b>Height.</b></td>
		<td style="border-width:3px;border-style: double;"><b>ISOCode</b></td>
		<td style="border-width:3px;border-style: double;"><b>Status</b></td>
		<td style="border-width:3px;border-style: double;"><b>Seal NO</b></td>		
		<td style="border-width:3px;border-style: double;"><b>MLO</b></td>
	
		<td style="border-width:3px;border-style: double;"><b>Weight</b></td>
	
	
		
	</tr>
	</thead>
	<tbody>

<?php
		
	
	// $query=mysqli_query($con_sparcsn4,"SELECT sparcsn4.inv_unit.gkey,r.id AS mlo,category,freight_kind,inv_unit.id AS cont_id,
	// (SELECT RIGHT(sparcsn4.ref_equip_type.nominal_length,2) 
	// FROM sparcsn4.inv_unit_equip 
	// INNER JOIN sparcsn4.ref_equipment ON sparcsn4.ref_equipment.gkey=sparcsn4.inv_unit_equip.eq_gkey 
	// INNER JOIN sparcsn4.ref_equip_type ON sparcsn4.ref_equip_type.gkey=sparcsn4.ref_equipment.eqtyp_gkey 
	// WHERE sparcsn4.inv_unit_equip.unit_gkey=inv_unit.gkey) AS size,
	// ((SELECT RIGHT(sparcsn4.ref_equip_type.nominal_height,2) 
	// FROM sparcsn4.inv_unit_equip 
	// INNER JOIN sparcsn4.ref_equipment ON sparcsn4.ref_equipment.gkey=sparcsn4.inv_unit_equip.eq_gkey 
	// INNER JOIN sparcsn4.ref_equip_type ON sparcsn4.ref_equip_type.gkey=sparcsn4.ref_equipment.eqtyp_gkey 
	// WHERE sparcsn4.inv_unit_equip.unit_gkey=inv_unit.gkey)/10) AS height,
	// sparcsn4.ref_equip_type.id AS iso,
	// sparcsn4.inv_unit.seal_nbr1 AS seal_no,
	// sparcsn4.inv_unit.goods_and_ctr_wt_kg AS weight,
	// sparcsn4.ref_routing_point.id AS pod
	// FROM sparcsn4.inv_unit
	// INNER JOIN sparcsn4.inv_unit_equip ON sparcsn4.inv_unit_equip.unit_gkey=sparcsn4.inv_unit.gkey
	// INNER JOIN sparcsn4.ref_equipment ON sparcsn4.ref_equipment.gkey=sparcsn4.inv_unit_equip.eq_gkey
	// INNER JOIN sparcsn4.ref_equip_type ON sparcsn4.ref_equip_type.gkey=sparcsn4.ref_equipment.eqtyp_gkey
	// INNER JOIN sparcsn4.inv_unit_fcy_visit ON sparcsn4.inv_unit_fcy_visit.unit_gkey=sparcsn4.inv_unit.gkey
	// INNER JOIN sparcsn4.argo_carrier_visit ON sparcsn4.argo_carrier_visit.gkey=sparcsn4.inv_unit_fcy_visit.actual_ob_cv
	// INNER JOIN sparcsn4.vsl_vessel_visit_details ON sparcsn4.vsl_vessel_visit_details.vvd_gkey=sparcsn4.argo_carrier_visit.cvcvd_gkey
	// INNER JOIN sparcsn4.ref_routing_point ON sparcsn4.ref_routing_point.gkey=sparcsn4.inv_unit.pod1_gkey
	// INNER JOIN (sparcsn4.ref_bizunit_scoped r      
	// LEFT JOIN (sparcsn4.ref_agent_representation X      
	// LEFT JOIN sparcsn4.ref_bizunit_scoped Y ON X.agent_gkey=Y.gkey) ON r.gkey=X.bzu_gkey) ON r.gkey = sparcsn4.inv_unit.line_op
	// WHERE ib_vyg='$ddl_imp_rot_no' AND r.id='$rowMlo->mlo'
	// ORDER BY mlo");


	// $query=

	// "
	// SELECT
	
	
		
	// (SELECT rt.truck_id FROM road_truck_transactions rtt
	// INNER JOIN road_trucks rt ON rt.trkco_gkey=rtt.trkco_gkey
	// WHERE rtt.unit_gkey=inv_unit.gkey fetch first 1 rows only)  AS truck_no1,
	
	
	// (select substr(ref_equip_type.nominal_length,-2) from ref_equip_type 
	// INNER JOIN ref_equipment ON ref_equipment.eqtyp_gkey=ref_equip_type.gkey
	// INNER JOIN inv_unit ON inv_unit.eq_gkey=ref_equipment.gkey
	// INNER JOIN inv_unit_fcy_visit ON inv_unit_fcy_visit.unit_gkey=inv_unit.gkey
	// where inv_unit_fcy_visit.unit_gkey=inv_unit.gkey fetch first 1 rows only
	// ) as siz,
	
	
	// 	(select substr(ref_equip_type.nominal_height,-2) from ref_equip_type 
	// INNER JOIN ref_equipment ON ref_equipment.eqtyp_gkey=ref_equip_type.gkey
	// INNER JOIN inv_unit ON inv_unit.eq_gkey=ref_equipment.gkey
	// INNER JOIN inv_unit_fcy_visit ON inv_unit_fcy_visit.unit_gkey=inv_unit.gkey
	// where inv_unit_fcy_visit.unit_gkey=inv_unit.gkey fetch first 1 rows only
	// ) as height,
	
	
	
	// (
	// SELECT SUBSTR(id,1,3) 
	
	// FROM argo_quay 
	// INNER JOIN vsl_vessel_berthings brt ON brt.quay=argo_quay.gkey 
	// WHERE brt.vvd_gkey=vsl_vessel_visit_details.vvd_gkey ORDER BY brt.ata DESC fetch first 1 rows only)AS berth,
	// ref_equip_type.id AS iso,
	// inv_unit.freight_kind,
	// r.id AS mlo,
	// inv_unit.seal_nbr1 AS seal_no2,
	// (SELECT rt.truck_id FROM road_truck_transactions rtt
	// INNER JOIN road_trucks rt ON rt.trkco_gkey=rtt.trkco_gkey
	// WHERE rtt.unit_gkey=inv_unit.gkey fetch first 1 rows only) AS truck_no,
	
	
	// inv_unit_fcy_visit.last_pos_slot AS stowage_pos,
	
	// inv_unit.goods_and_ctr_wt_kg AS weight
	// FROM inv_unit
	// INNER JOIN inv_unit_fcy_visit ON inv_unit_fcy_visit.unit_gkey=inv_unit.gkey
	// INNER JOIN argo_carrier_visit ON argo_carrier_visit.gkey=inv_unit_fcy_visit.actual_ob_cv
	// INNER JOIN argo_visit_details ON argo_visit_details.gkey=argo_carrier_visit.cvcvd_gkey 
	// INNER JOIN vsl_vessel_visit_details ON vsl_vessel_visit_details.vvd_gkey=argo_visit_details.gkey 
	
	// INNER JOIN ref_equipment ON ref_equipment.gkey=inv_unit.eq_gkey
	// 	  INNER JOIN ref_equip_type ON ref_equipment.eqtyp_gkey=ref_equip_type.gkey 
	// INNER JOIN  ( ref_bizunit_scoped r        
	//  LEFT JOIN ( ref_agent_representation X        
	//  LEFT JOIN ref_bizunit_scoped Y ON X.agent_gkey=Y.gkey )  ON r.gkey=X.bzu_gkey        )  ON r.gkey = inv_unit.line_op
	// WHERE $cond  r.id='$mlo_details'
	// ORDER BY r.id,inv_unit.id";


	$query=

	"
	SELECT
	
	
		
	(SELECT rt.truck_id FROM road_truck_transactions rtt
	INNER JOIN road_trucks rt ON rt.trkco_gkey=rtt.trkco_gkey
	WHERE rtt.unit_gkey=inv_unit.gkey fetch first 1 rows only)  AS truck_no1,
	
	
	(select substr(ref_equip_type.nominal_length,-2) from ref_equip_type 
	INNER JOIN ref_equipment ON ref_equipment.eqtyp_gkey=ref_equip_type.gkey
	INNER JOIN inv_unit ON inv_unit.eq_gkey=ref_equipment.gkey
	INNER JOIN inv_unit_fcy_visit ON inv_unit_fcy_visit.unit_gkey=inv_unit.gkey
	where inv_unit_fcy_visit.unit_gkey=inv_unit.gkey fetch first 1 rows only
	) as siz,
	
	
		(select substr(ref_equip_type.nominal_height,-2) from ref_equip_type 
	INNER JOIN ref_equipment ON ref_equipment.eqtyp_gkey=ref_equip_type.gkey
	INNER JOIN inv_unit ON inv_unit.eq_gkey=ref_equipment.gkey
	INNER JOIN inv_unit_fcy_visit ON inv_unit_fcy_visit.unit_gkey=inv_unit.gkey
	where inv_unit_fcy_visit.unit_gkey=inv_unit.gkey fetch first 1 rows only
	) as height,
	
	
	
	(
	SELECT SUBSTR(id,1,3) 
	
	FROM argo_quay 
	INNER JOIN vsl_vessel_berthings brt ON brt.quay=argo_quay.gkey 
	WHERE brt.vvd_gkey=vsl_vessel_visit_details.vvd_gkey ORDER BY brt.ata DESC fetch first 1 rows only)AS berth,
	ref_equip_type.id AS iso,
	inv_unit.freight_kind,
	r.id AS mlo,
	inv_unit.seal_nbr1 AS seal_no2,
	(SELECT rt.truck_id FROM road_truck_transactions rtt
	INNER JOIN road_trucks rt ON rt.trkco_gkey=rtt.trkco_gkey
	WHERE rtt.unit_gkey=inv_unit.gkey fetch first 1 rows only) AS truck_no,
	
	
	inv_unit_fcy_visit.last_pos_slot AS stowage_pos,
	
	inv_unit.goods_and_ctr_wt_kg AS weight
	FROM inv_unit
	INNER JOIN inv_unit_fcy_visit ON inv_unit_fcy_visit.unit_gkey=inv_unit.gkey
	INNER JOIN argo_carrier_visit ON argo_carrier_visit.gkey=inv_unit_fcy_visit.actual_ob_cv
	INNER JOIN argo_visit_details ON argo_visit_details.gkey=argo_carrier_visit.cvcvd_gkey 
	INNER JOIN vsl_vessel_visit_details ON vsl_vessel_visit_details.vvd_gkey=argo_visit_details.gkey 
	
	INNER JOIN ref_equipment ON ref_equipment.gkey=inv_unit.eq_gkey
		  INNER JOIN ref_equip_type ON ref_equipment.eqtyp_gkey=ref_equip_type.gkey 
	INNER JOIN  ( ref_bizunit_scoped r        
	 LEFT JOIN ( ref_agent_representation X        
	 LEFT JOIN ref_bizunit_scoped Y ON X.agent_gkey=Y.gkey )  ON r.gkey=X.bzu_gkey        )  ON r.gkey = inv_unit.line_op
	WHERE   r.id='$mlo_details'
	ORDER BY r.id,inv_unit.id";

	


	$i=0;
	$j=0;
	$mlo="";
	$weight=0;
	$totWeight=0;

	$queryInfo=oci_parse($con_sparcsn4_oracle,$query);
	$query1=oci_execute($queryInfo);

	$results=array();
	$nrows = oci_fetch_all($queryInfo, $results, null, null, OCI_FETCHSTATEMENT_BY_ROW);
	
	
	
	oci_free_statement($queryInfo);
	
	$queryInfo=oci_parse($con_sparcsn4_oracle,$query);
	$query1=oci_execute($queryInfo);
	$mlo_details="";
	if($nrows>0){


	while(($row=oci_fetch_object($query1)) !=false)

	{

	$i++;
	


@$querydep="SELECT COUNT(ref_bizunit_scoped.id) AS cnt
FROM ref_bizunit_scoped
INNER JOIN road_trucking_companies ON road_trucking_companies.trkc_id = ref_bizunit_scoped.gkey
WHERE road_trucking_companies.trkc_id=
(
SELECT  trkco_gkey FROM road_truck_visit_details WHERE truck_id='$row->TRUCK_NO' 
) ";

$querydepresult=oci_parse($con_sparcsn4_oracle,$querydep);
$depocont=oci_execute($querydepresult);

	 $offdeo = $depocont->CNT;


// $querydepresult = mysqli_query($con_sparcsn4,$querydep);
// 	$depocont=mysqli_fetch_object($querydepresult);
// 	$offdeo = $depocont->cnt;
		
	
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
				<td align="center"><?php echo "";?></td>
			
				<td align="center"><b><?php echo $weight;?></b></td>
			
		</tr>
		
	<?php 

	}
	$j=1;
	$weight=$row->WEIGHT;


	}else{
		$j++;
		$weight=$weight+$row->WEIGHT;
		
	}
	$mlo=$row->MLO;
	
	?>
<tr align="center">
		<td><?php  echo $i;?></td>
		<td><?php if($row->CONT_ID ) echo $row->CONT_ID; else echo "&nbsp;";?></td>
		
		<td><?php if($row->SIZ) echo $row->SIZ; else echo "&nbsp;";?></td>
		<td><?php if($row->HEIGHT) echo $row->HEIGHT; else echo "&nbsp;";?></td>
		<td><?php if($row->ISO) echo $row->ISO; else echo "&nbsp;";?></td>
		<td><?php if($row->FREIGHT_KIND) echo $row->FREIGHT_KIND; else echo "&nbsp;";?></td>
		
		<td><?php if($row->SEAL_NO) echo $row->SEAL_NO; else echo "&nbsp;";?></td>
		<td><?php if($row->MLO) echo $row->MLO; else echo "&nbsp;";?></td>
	
			<?php 
			if($row->TRUCK_NO1>0)
			{ 
				echo  "<strong>".strtoupper($row->TRUCK_NO1). "</strong>";
			}
			elseif($offdeo>0 and $row->TRUCK_NO1!="")
			{
				echo "Depot";
			}
			else 
			{
				echo "Port";
			}?>
		</td-->
		
	
		
		
				
	</tr>

<?php $totWeight = $totWeight + $row->WEIGHT; 

} ?>
<?php } ?>
	


	<tr align="center">
			<td align="center"><b><?php  echo "Total";?></b></td>
			<td align="center"><?php  echo "&nbsp;";?></td>
			<td align="center"><?php  echo "&nbsp;";?></td>		
			<td align="center"><b><?php echo "";?></b></td>
			<td align="center"><b><?php echo "";?></b></td>
			<td align="center"><b><?php echo "";?></b></td>
			<td align="center"><b><?php echo "";?></b></td>
			<td align="center"><b><?php echo "&nbsp;";?></b></td>
		
			<td align="center"><b><?php echo $weight;?></b></td>
		
	</tr>
	</tbody>
</table>
<div class="pageBreak"></div>




<br />
<br />


<?php 
mysqli_close($con_sparcsn4);
oci_close($con_sparcsn4_oracle);
if($_POST['options']=='html'){?>	
	</BODY>
</HTML>
<?php }?>
