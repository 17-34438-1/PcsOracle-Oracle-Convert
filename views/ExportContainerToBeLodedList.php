<?php if($_POST['options']=='html'){?>
<HTML>
	<HEAD>
		<TITLE>Bearth Operator Report</TITLE>
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
		header("Content-Disposition: attachment; filename=EXPORT_SUMMERY.xls;");
		header("Content-Type: application/ms-excel");
		header("Pragma: no-cache");
		header("Expires: 0");

	}
$ddl_imp_rot_no=$_REQUEST['ddl_imp_rot_no']; 


	include("dbConection.php");
	include("dbOracleConnection.php");



	$sql="select vvd_gkey from vsl_vessel_visit_details where ib_vyg='$ddl_imp_rot_no'";

	$vvdGkeyRes=oci_parse($con_sparcsn4_oracle, $sql);
	oci_execute($vvdGkeyRes);
	// $vvdGkey=$vvdGkey->VVD_GKEY;

	// oci_execute($strQuery2Res);
	
	$vvdGkey="";
	while(($row1=oci_fetch_object($vvdGkeyRes)) !=false)
	{
		$vvdGkey=$row1->VVD_GKEY;
	}

	
	$sql="call ctmsmis.update_containers_by_vvd_gkey($vvdGkey)";
	$res=mysqli_query($con_sparcsn4,$sql);
	

	


	$sql1="SELECT vsl_vessels.name AS vsl_name,COALESCE(vsl_vessel_visit_details.flex_string02,vsl_vessel_visit_details.flex_string03) AS berthop,COALESCE(argo_quay.id,'') AS berth,
	argo_carrier_visit.ata,argo_carrier_visit.atd FROM vsl_vessel_visit_details
	INNER JOIN argo_carrier_visit ON argo_carrier_visit.cvcvd_gkey=vsl_vessel_visit_details.vvd_gkey
	INNER JOIN vsl_vessel_berthings ON vsl_vessel_berthings.vvd_gkey=vsl_vessel_visit_details.vvd_gkey
	INNER JOIN vsl_vessels ON vsl_vessels.gkey=vsl_vessel_visit_details.vessel_gkey
	INNER JOIN argo_quay ON argo_quay.gkey=vsl_vessel_berthings.quay
	WHERE vsl_vessel_visit_details.vvd_gkey='$vvdGkey'";
	
	$stid = oci_parse($con_sparcsn4_oracle, $sql1);
	oci_execute($stid);
	$vsl_name="";
	$ata="";
	while(($row2=oci_fetch_object($stid)) !=false)
	{
		$vsl_name=$row2->VSL_NAME;
		$ata=$row2->ATA;
	}





	?>
<html>
<title>Export Container Balance To Be Loaded List</title>
<body>
<table width="100%" border ='0' cellpadding='0' cellspacing='0'>
<tr bgcolor="#ffffff" align="center" height="100px">
		<td colspan="13" align="center">
			<table border=0 width="100%">
				
				
				<!--tr align="center">
					<td colspan="12"><font size="4"><b>CHITTAGONG PORT AUTHORITY,CHITTAGONG</b></font></td>
				</tr-->
				<?php
				if($_POST['options']=='html')
				{
				?>
				<tr align="center">
					<td colspan="12" align="center"><img width="250px" height="80px" src="<?php echo ASSETS_WEB_PATH?>fimg/cpanew.jpg"></td>
				</tr>
				<?php
				}
				else
				{
				?>
				<tr align="center">
					<td colspan="12"><font size="4"><b>CHITTAGONG PORT AUTHORITY,CHITTAGONG</b></font></td>
				</tr>
				<?php
				}
				?>
			
				<tr align="center">
					<td colspan="12"><font size="4"><b><u>Export Container Balance To Be Loaded Report</u></b></font></td>
				</tr>
				<tr align="center">
					<td colspan="12"><font size="4"><b></b></font></td>
				</tr>

				<tr>
					<td colspan="3" align="center"><font size="4"><b> <?php  Echo $vsl_name;?></b></font></td>
					<td colspan="3" align="center"><font size="4"><b>Voy: <?php  Echo $voysNo;?></b></font></td>
					<td colspan="3" align="center"><font size="4"><b>EXP ROT.: <?php  Echo $ddl_imp_rot_no;?></b></font></td>
					<td colspan="3" align="center"><font size="4"><b><?php  Echo $ata;?></b></font></td>
					
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
		<td style="border-width:3px;border-style: double;"><b>Type</b></td>
		<td style="border-width:3px;border-style: double;"><b>MLO</b></td>
		<td style="border-width:3px;border-style: double;"><b>Status</b></td>		
		<td style="border-width:3px;border-style: double;"><b>Weight</b></td>
		<td style="border-width:3px;border-style: double;"><b>POD</b></td>
		<td style="border-width:3px;border-style: double;"><b>Stowage</b></td>
		<td style="border-width:3px;border-style: double;"><b>User Id</b></td>
		
	</tr>

<?php
	




$query="
SELECT  CONCAT(CONCAT(substr(inv_unit.id,1,4),' '),substr(inv_unit.id,5)) AS id,inv_unit_fcy_visit.time_in AS fcy_time_in,
inv_unit_fcy_visit.last_pos_slot AS location,inv_unit.seal_nbr1 AS sealno,REF_ROUTING_POINT.ID as pod,inv_unit_fcy_visit.ARRIVE_POS_SLOT as stowage_pos,
ref_equip_type.id AS iso,ref_bizunit_scoped.id AS mlo,inv_unit.freight_kind,inv_unit.goods_and_ctr_wt_kg AS weight,
ref_commodity.short_name
FROM  inv_unit 
INNER JOIN inv_unit_fcy_visit ON inv_unit_fcy_visit.unit_gkey=inv_unit.gkey
INNER JOIN argo_carrier_visit ON argo_carrier_visit.gkey=inv_unit_fcy_visit.actual_ib_cv
INNER JOIN vsl_vessel_visit_details ON vsl_vessel_visit_details.vvd_gkey=argo_carrier_visit.cvcvd_gkey
LEFT JOIN inv_goods ON inv_goods.gkey=inv_unit.goods
LEFT JOIN ref_commodity ON ref_commodity.gkey=inv_goods.commodity_gkey
INNER JOIN ref_equipment ON ref_equipment.gkey=inv_unit.eq_gkey
INNER JOIN ref_equip_type ON ref_equip_type.gkey=ref_equipment.eqtyp_gkey
INNER JOIN ref_bizunit_scoped ON ref_bizunit_scoped.gkey=inv_unit.line_op
INNER JOIN REF_ROUTING_POINT ON INV_UNIT.POD1_GKEY = REF_ROUTING_POINT.GKEY
WHERE vsl_vessel_visit_details.vvd_gkey='$vvdGkey'";


// AND category='EXPRT' 
// AND transit_state NOT IN ('S60_LOADED','S70_DEPARTED','S99_RETIRED')

$res3=oci_parse($con_sparcsn4_oracle,$query);
oci_execute($res3);

	
	$i=0;
	$j=0;
	
	$mlo="";
	while(($row=oci_fetch_object($res3)) != false){
	$i++;
	
		
	
?>
<tr align="center">
		<td><?php  echo $i;?></td>
		<td><?php if($row->ID) echo $row->ID; else echo "&nbsp;";?></td>
		<td><?php if( $row->ISO) echo $row->ISO; else echo "&nbsp;";?></td>
		<td><?php if($row->MLO) echo $row->MLO; else echo "&nbsp;";?></td>
		<td><?php if($row->FREIGHT_KIND) echo $row->FREIGHT_KIND; else echo "&nbsp;";?></td>
		<td><?php if($row->WEIGHT) echo $row->WEIGHT; else echo "&nbsp;";?></td>
		<td><?php  echo "&nbsp;";?></td>
		<td><?php    echo "&nbsp;";?></td>
		<td><?php  echo "&nbsp;";?></td>
				
	</tr>

<?php } ?>
</table>
<br />
<br />
<?php






echo $sqlSummery2="

SELECT 
NVL(SUM(balance_LD_20),0) AS balance_LD_20,
NVL(SUM(balance_LD_40),0) AS balance_LD_40,
NVL(SUM(balance_MT_20),0) AS balance_MT_20,
NVL(SUM(balance_MT_40),0) AS balance_MT_40,
NVL(SUM(balance_LD_tues),0) AS balance_LD_tues,
NVL(SUM(balance_MT_tues),0) AS balance_MT_tues
FROM 
( 
SELECT DISTINCT inv_unit.gkey AS gkey, inv_unit_fcy_visit.time_in AS fcy_transit_state, 
(CASE WHEN substr(ref_equip_type.nominal_length,-2) = '20' AND freight_kind IN ('FCL','LCL') THEN 1 ELSE NULL END) AS balance_LD_20,
(CASE WHEN substr(ref_equip_type.nominal_length,-2) > '20' AND freight_kind IN ('FCL','LCL') THEN 1 ELSE NULL END) AS balance_LD_40,
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
WHERE argo_carrier_visit.cvcvd_gkey=$vvdGkey AND category='EXPRT' 
)tmp

";
// $sqlSummery2=mysqli_query($con_sparcsn4,$sqlSummery2);
// $rowSummery2=mysqli_fetch_object($sqlSummery2);

	$sqlSummery2 = oci_parse($con_sparcsn4_oracle,$sqlSummery2);
	oci_execute($sqlSummery2);
	// $rowSummery2=oci_fetch_object($sqlSummery2);
	$balance_LD_20="";
	$balance_LD_40=""; 
	$balance_MT_20="";
	$balance_MT_40="";
	$balance_LD_tues="";
	$balance_MT_tues="";
	while(($rowSummery2=oci_fetch_object($sqlSummery2)) !=false)	
	{

		$balance_LD_20=$rowSummery2->BALANCE_LD_20;
		$balance_LD_40=$rowSummery2->BALANCE_LD_40; 
		$balance_MT_20= $rowSummery2->BALANCE_MT_20;
		$balance_MT_40=$rowSummery2->BALANCE_MT_40;
		$balance_LD_tues=$rowSummery2->BALANCE_LD_TUES;
		$balance_MT_tues=$rowSummery2->BALANCE_MT_TUES;

	}


// $sqlSummery2="select gkey,
// IFNULL(SUM(balance_LD_20),0) AS balance_LD_20,
// IFNULL(SUM(balance_LD_40),0) AS balance_LD_40,
// IFNULL(SUM(balance_MT_20),0) AS balance_MT_20,
// IFNULL(SUM(balance_MT_40),0) AS balance_MT_40,
// IFNULL(SUM(balance_LD_tues),0) AS balance_LD_tues,
// IFNULL(SUM(balance_MT_tues),0) AS balance_MT_tues

//  from (
// select distinct ctmsmis.mis_inv_unit.gkey as gkey,
// (CASE WHEN size = '20' AND freight_kind in ('FCL','LCL')  THEN 1  
// ELSE NULL END) AS balance_LD_20, 
// (CASE WHEN size > '20' AND freight_kind in ('FCL','LCL')  THEN 1  
// ELSE NULL END) AS balance_LD_40,
// (CASE WHEN size = '20' AND freight_kind ='MTY'  THEN 1  
// ELSE NULL END) AS balance_MT_20, 
// (CASE WHEN size > '20' AND freight_kind ='MTY'  THEN 1  
// ELSE NULL END) AS balance_MT_40, 
// (CASE WHEN size=20 AND freight_kind in ('FCL','LCL') THEN 1 
// ELSE (CASE WHEN size>20 AND freight_kind in ('FCL','LCL') THEN 2 ELSE NULL END) END) AS balance_LD_tues, 
// (CASE WHEN size=20 AND freight_kind='MTY' THEN 1 
// ELSE (CASE WHEN size>20 AND freight_kind='MTY' THEN 2 ELSE NULL END) END) AS balance_MT_tues

// FROM ctmsmis.mis_inv_unit 
// where  mis_inv_unit.vvd_gkey='$vvdGkey' and category='EXPRT' and fcy_transit_state not in ('S60_LOADED','S70_DEPARTED','S99_RETIRED')
// ) as tmp";
// $sqlSummery2=mysqli_query($con_sparcsn4,$sqlSummery2);
// @$rowSummery2=mysqli_fetch_object($sqlSummery2);


?>

<table width="100%" border ='0' cellpadding='0' cellspacing='0'>
<tr><td colspan="12" align="center"><font size="4"><b><u>Export Container Balance To Be Loaded Summary Report</u></b></font></td></tr>
<tr><td colspan="12" align="center"><font size="4"><b>&nbsp;</b></font></td></tr>
</table>
<table width="100%" border ='1' cellpadding='0' cellspacing='0'>
	<tr>
		
		<td colspan="6" align="center">BALANCE TO LOAD</td>
	</tr>
	<tr>
		
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
	</tr>
	<tr>
		
	


		
		<td align="center"><?php echo $balance_LD_20;?></td>
		<td align="center"><?php  echo $balance_LD_40;  ?></td>
		<td align="center"><?php  echo $balance_MT_20; ?></td>
		<td align="center"><?php echo $balance_MT_40;?></td>
		<td align="center"><?php echo $balance_LD_tues;  ?></td>
		<td align="center"><?php echo $balance_MT_tues;  ?></td>
		
		
	</tr>
</table>
<?php 
// mysql_close($con_sparcsn4);

oci_close($con_sparcsn4_oracle);

if($_POST['options']=='html'){?>	
	</BODY>
</HTML>
<?php }?>
