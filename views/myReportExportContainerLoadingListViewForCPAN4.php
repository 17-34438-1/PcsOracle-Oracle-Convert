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
	oci_execute($sql);
	$vvd_gkey ="";
	
	while(($row=oci_fetch_object($sql)) !=false)
	{
		$vvd_gkey = $row->VVD_GKEY;
		
	}


	// $row=mysqli_fetch_object($sql);
	// $vvdGkey=$row->vvd_gkey;
	
	$sql="call ctmsmis.update_containers_by_vvd_gkey($vvdGkey)";
	$res=mysqli_query($con_sparcsn4,$sql);
	

	$sql1=oci_parse($con_sparcsn4_oracle,"select vsl_vessels.name as vsl_name,COALESCE(vsl_vessel_visit_details.flex_string02,COALESCE(vsl_vessel_visit_details.flex_string03,'')) as berth_op,COALESCE(argo_quay.id,'') as berth,argo_carrier_visit.ata,argo_carrier_visit.atd from vsl_vessel_visit_details
	inner join argo_carrier_visit on argo_carrier_visit.cvcvd_gkey=vsl_vessel_visit_details.vvd_gkey
	inner join vsl_vessel_berthings on vsl_vessel_berthings.vvd_gkey=vsl_vessel_visit_details.vvd_gkey
	inner join vsl_vessels on vsl_vessels.gkey=vsl_vessel_visit_details.vessel_gkey
	inner join argo_quay on argo_quay.gkey=vsl_vessel_berthings.quay
	where vsl_vessel_visit_details.vvd_gkey=$vvdGkey
			
			");

			oci_execute($sql1);
			$vsl_name = "";
			$ata = "";
			$atd="";
			while(($row1=oci_fetch_object($sql1)) !=false)
			{
				$vsl_name = $row1->NAME;
				$ata = $row1->ATA ;
				$atd=$row1->ATD;
			}
			
	//$row1=mysqli_fetch_object($sql1);






	?>
<html>
<title>Export Loading</title>
<body>
<?php 


// $sqlMloQry="SELECT DISTINCT r.id AS mlo 
// FROM inv_unit
// INNER JOIN inv_unit_fcy_visit ON inv_unit_fcy_visit.unit_gkey=inv_unit.gkey
// INNER JOIN argo_carrier_visit ON argo_carrier_visit.gkey=inv_unit_fcy_visit.actual_ob_cv
// INNER JOIN argo_visit_details ON argo_visit_details.gkey=argo_carrier_visit.cvcvd_gkey 
// INNER JOIN vsl_vessel_visit_details ON vsl_vessel_visit_details.vvd_gkey=argo_visit_details.gkey 
// INNER JOIN  ( ref_bizunit_scoped r        
// 		LEFT JOIN ( ref_agent_representation X        
// 		LEFT JOIN ref_bizunit_scoped Y ON X.agent_gkey=Y.gkey )  ON r.gkey=X.bzu_gkey        )  ON r.gkey = inv_unit.line_op
// " ;




$sqlMloQry="SELECT DISTINCT r.id AS mlo 
FROM inv_unit
INNER JOIN inv_unit_fcy_visit ON inv_unit_fcy_visit.unit_gkey=inv_unit.gkey
INNER JOIN argo_carrier_visit ON argo_carrier_visit.gkey=inv_unit_fcy_visit.actual_ob_cv
INNER JOIN argo_visit_details ON argo_visit_details.gkey=argo_carrier_visit.cvcvd_gkey 
INNER JOIN vsl_vessel_visit_details ON vsl_vessel_visit_details.vvd_gkey=argo_visit_details.gkey 
INNER JOIN  ( ref_bizunit_scoped r        
		LEFT JOIN ( ref_agent_representation X        
		LEFT JOIN ref_bizunit_scoped Y ON X.agent_gkey=Y.gkey )  ON r.gkey=X.bzu_gkey        )  ON r.gkey = inv_unit.line_op
" ;
//echo $sqlMloQry;
$sql1=oci_parse($con_sparcsn4_oracle,$sqlMloQry);
oci_execute($sql1);

while(($row1=oci_fetch_object($sql1)) !=false){






?>
<table width="100%" border ='0' cellpadding='0' cellspacing='0'>
	<tr>
		<td colspan="7" align="center"><img align="middle"  src="<?php echo IMG_PATH?>cpanew.jpg"></td>
	</tr>
	<tr>
		<td colspan="7" align="center"><font size="3"><b>OFFICE OF THE TERMINAL MANAGER</b></font></td>
	</tr>
	<tr>
		<td colspan="7" align="center"><font size="3"><b>MLO WISE FINAL LOADING DETAIL</b></font></td>					
	</tr>
	<tr bgcolor="#ffffff" align="center" height="25px">
		<td colspan="5" align="center"></td>
	</tr>
	
	
	<tr bgcolor="#ffffff" align="center" height="25px">
		<td colspan="15" align="center"></td>
	</tr>
</table>

	<table width="100%" border ='1' cellpadding='0' cellspacing='0'>
	<thead>
	<tr bgcolor="#ffffff" align="center">
		<td colspan="2" align="centre"><font size="3"><b>VESSEL-<?php echo $vsl_name ; ?></b></font></td>
		<td colspan="2" align="centre"><font size="3"><b>VOY- <?php echo $voysNo; ?></b></font></td>
		<td colspan="2" align="centre"><font size="3"><b>EXP.ROT- <?php echo $ddl_imp_rot_no; ?></b></font></td>
		<td colspan="3" align="centre"><font size="3"><b>SAILED DATE- <?php echo $atd; ?></b></font></td>
		<td colspan="3" align="centre"><font size="3"><b>BERTH-<?php echo $row1->berth; ?></b></font></td>
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
	
		<td style="border-width:3px;border-style: double;"><b>OffDoc/Port</b></td>
		<td style="border-width:3px;border-style: double;"><b>Stowage</b></td>

		
		<td style="border-width:3px;border-style: double;"><b>Yard</b></td>
		<td style="border-width:3px;border-style: double;"><b>Weight</b></td>
	
	
		
	</tr>
	</thead>
	<tbody>

<?php
		

// $query=mysqli_query($con_sparcsn4,"SELECT CONCAT(SUBSTRING(sparcsn4.inv_unit.id,1,4),' ',SUBSTRING(sparcsn4.inv_unit.id,5)) AS id,
// RIGHT(sparcsn4.ref_equip_type.nominal_length,2) AS size,
// (RIGHT(sparcsn4.ref_equip_type.nominal_height,2)/10) AS height,

// (SELECT SUBSTRING(id,1,3) FROM sparcsn4.argo_quay 
// 	INNER JOIN sparcsn4.vsl_vessel_berthings brt ON brt.quay=sparcsn4.argo_quay.gkey 
// 	WHERE brt.vvd_gkey=sparcsn4.vsl_vessel_visit_details.vvd_gkey ORDER BY brt.ata DESC LIMIT 1)AS berth,
// sparcsn4.ref_equip_type.id AS iso,
// inv_unit.freight_kind,
// r.id AS mlo,
// inv_unit.seal_nbr1 AS seal_no2,
// (SELECT rt.truck_id FROM sparcsn4.road_truck_transactions rtt
// INNER JOIN sparcsn4.road_trucks rt ON rt.trkco_gkey=rtt.trkco_gkey
// WHERE rtt.unit_gkey=sparcsn4.inv_unit.gkey LIMIT 1) AS truck_no,

// (SELECT rt.truck_id FROM sparcsn4.road_truck_transactions rtt
// INNER JOIN sparcsn4.road_trucks rt ON rt.trkco_gkey=rtt.trkco_gkey
// WHERE rtt.unit_gkey=sparcsn4.inv_unit.gkey LIMIT 1) LIKE '%sp%'  AS truck_no1,

// sparcsn4.inv_unit_fcy_visit.last_pos_slot AS stowage_pos,

// inv_unit.goods_and_ctr_wt_kg AS weight
// FROM sparcsn4.inv_unit
// INNER JOIN sparcsn4.inv_unit_fcy_visit ON inv_unit_fcy_visit.unit_gkey=inv_unit.gkey
// INNER JOIN sparcsn4.argo_carrier_visit ON argo_carrier_visit.gkey=sparcsn4.inv_unit_fcy_visit.actual_ob_cv
// INNER JOIN sparcsn4.argo_visit_details ON sparcsn4.argo_visit_details.gkey=sparcsn4.argo_carrier_visit.cvcvd_gkey 
// INNER JOIN sparcsn4.vsl_vessel_visit_details ON sparcsn4.vsl_vessel_visit_details.vvd_gkey=sparcsn4.argo_visit_details.gkey 
// INNER JOIN sparcsn4.inv_unit_equip ON sparcsn4.inv_unit_equip.unit_gkey=inv_unit.gkey
// INNER JOIN sparcsn4.ref_equipment ON sparcsn4.ref_equipment.gkey=sparcsn4.inv_unit_equip.eq_gkey
// INNER JOIN sparcsn4.ref_equip_type ON sparcsn4.ref_equip_type.gkey=sparcsn4.ref_equipment.eqtyp_gkey
// INNER JOIN  ( sparcsn4.ref_bizunit_scoped r        
// 		LEFT JOIN ( sparcsn4.ref_agent_representation X        
// 		LEFT JOIN sparcsn4.ref_bizunit_scoped Y ON X.agent_gkey=Y.gkey )  ON r.gkey=X.bzu_gkey        )  ON r.gkey = inv_unit.line_op
// WHERE $cond  and r.id='".$rowMlo->mlo."'
// ORDER BY r.id,inv_unit.id");



$query=oci_execute($con_sparcsn4_oracle,

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
WHERE $cond  r.id='".$rowMlo->mlo."'
ORDER BY r.id,inv_unit.id
        
"


);
oci_execute($query);

	$i=0;
	$j=0;
	$mlo="";
	$weight=0;
	$totWeight=0;
	while(($row=oci_fetch_object($query)) !=false){
	// while($row=mysqli_fetch_object($query)){
	$i++;
	
// 	$querydep="select count(sparcsn4.ref_bizunit_scoped.id) as cnt
// from sparcsn4.ref_bizunit_scoped
// inner join sparcsn4.road_trucking_companies on sparcsn4.road_trucking_companies.trkc_id = sparcsn4.ref_bizunit_scoped.gkey
// where sparcsn4.road_trucking_companies.trkc_id=(select distinct trkco_gkey from road_truck_visit_details where truck_id='$row->truck_no' order by tvdtls_gkey desc limit 1) ";
// 	$querydepresult = mysqli_query($con_sparcsn4,$querydep);
// 	$depocont=mysqli_fetch_object($querydepresult);

	$querydep="SELECT COUNT(ref_bizunit_scoped.id) AS cnt
	FROM ref_bizunit_scoped
	INNER JOIN road_trucking_companies ON road_trucking_companies.trkc_id = ref_bizunit_scoped.gkey
	WHERE road_trucking_companies.trkc_id=
	(
	SELECT  trkco_gkey FROM road_truck_visit_details WHERE truck_id='$row->truck_no' 
	) ";


	$querydepresult=oci_parse($con_sparcsn4_oracle,$querydep);
	oci_execute($querydepresult);
	$offdeo ="";
	while(($row1=oci_fetch_object($querydepresult)) !=false){
		$offdeo = $row1->CNT;
	}		
	
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
				<td align="center"><?php echo "&nbsp;";?></td>
				<td align="center"><?php echo "&nbsp;";?></td>
				<td align="center"><b><?php echo "";?></b></td>
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
	$mlo=$row->mlo;
	
	?>
<tr align="center">
		<td><?php  echo $i;?></td>
		<td><?php if($row->ID) echo $row->ID; else echo "&nbsp;";?></td>
		
		<td><?php if($row->SIZE) echo $row->SIZE; else echo "&nbsp;";?></td>
		<td><?php if($row->HEIGHT) echo $row->HEIGHT; else echo "&nbsp;";?></td>
		<td><?php if($row->ISO) echo $row->ISO; else echo "&nbsp;";?></td>
		<td><?php if($row->FREIGHT_KIND) echo $row->FREIGHT_KIND; else echo "&nbsp;";?></td>
		
		<td><?php if($row->SEAL_NO2) echo $row->SEAL_NO2; else echo "&nbsp;";?></td>
		<td><?php if($row->MLO) echo $row->MLO; else echo "&nbsp;";?></td>
		<!--td><?php if($row->CRAINE_ID) echo $row->CRAINE_ID; else echo "&nbsp;";?></td>
		<td><?php if($row->TRUCK_NO) echo $row->TRUCK_NO; else echo "&nbsp;";?></td-->
		
		<td>
			<?php 
			if($row->TRUCK_NO1>0)
			{ 
				echo  "<strong>".strtoupper($row->TRUCK_NO). "</strong>";
			}
			elseif($offdeo>0 and $row->truck_no!="")
			{
				echo "Depot";
			}
			else 
			{
				echo "Port";
			}?>
		</td>
		
		<td><?php if($row->STOWAGE_POS) echo $row->STOWAGE_POS; else echo "&nbsp;";?></td>
		<!--td><?php if($row->POD) echo $row->POD; else echo "&nbsp;";?></td-->
		
		<td><?php if($row->BERTH) echo $row->BERTH; else echo "&nbsp;";?></td>
		<td><?php if($row->WEIGHT) echo $row->WEIGHT; else echo "&nbsp;";?></td>
		<!--td><?php if($row->last_update) echo $row->last_update; else echo "&nbsp;";?></td>
		<td><?php if($row->remark) echo $row->remark;  else echo "&nbsp;";?></td-->
		
		
				
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
			<td align="center"><b><?php echo "&nbsp;";?></b></td>
			<td align="center"><b><?php echo "&nbsp;";?></b></td>
			<!--td align="center"><b><?php echo "";?></b></td-->
			<td align="center"><b><?php echo $weight;?></b></td>
			<!--td align="center"><b><?php echo "" ;?></b></td>
			<td align="center"><b><?php echo "";?></b></td-->
	</tr>
	</tbody>
</table>
<div class="pageBreak"></div>
<?php } ?>
<br />
<br />


<?php 
mysqli_close($con_sparcsn4);
if($_POST['options']=='html'){?>	
	</BODY>
</HTML>
<?php }?>
