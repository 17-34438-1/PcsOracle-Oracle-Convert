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
		header("Content-Disposition: attachment; filename=MLO_WISE_SUMMERY.xls;");
		header("Content-Type: application/ms-excel");
		header("Pragma: no-cache");
		header("Expires: 0");

	}

	include_once("mydbPConnectionn4.php");
	
	
	include_once("mydbPConnectionn4.php");
	include("dbOracleConnection.php");


	$sql=oci_parse($con_sparcsn4_oracle,"select vvd_gkey from vsl_vessel_visit_details where ib_vyg='$ddl_imp_rot_no'");
	$row=oci_execute($sql);
	
	$vvdGkey=0;
	while(($row=oci_fetch_object($sql)) !=false)
			{
				$vvdGkey = $row->VVD_GKEY;
				
			}



	$cond="";
	if($fromdate!="" and $todate!="")	
	{
		if($fromTime!="")
			$frmDate = $fromdate." ".$fromTime.":00";
		else
			$frmDate = $fromdate." 00:00:00";
		
		if($toTime!="")
			$tDate = $todate." ".$toTime.":00";
		else
			$tDate = $todate." 23:59:59";
		
		$cond = " AND mis_exp_unit.last_update BETWEEN '$frmDate' and '$tDate'";
	}
	else
	{
		$cond = " ";
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

			oci_execute($sql1);
			$vsl_name = "";
			$ata = "";
			$atd = "";
			$berth = "";
			while(($row1=oci_fetch_object($sql1)) !=false)
			{
				$vsl_name = $row1->VSL_NAME;
				$ata = $row1->ATA ;
				$atd = $row1->ATD ;
				$berth = $row1->BERTH;

			}




	
	?>
<html>
<title>MLO WISE LOADED CONTAINER LIST</title>
<body>
<table width="100%" border ='0' cellpadding='0' cellspacing='0'>
<tr bgcolor="#ffffff" align="center" height="100px">
		<td width="30%" align="centre"><img align="middle"  src="<?php echo IMG_PATH?>cpanew.jpg"></td>
</tr>
				<tr>
					<td colspan="7" align="center"><font size="5"><b> <u>OFFICE OF THE TERMINAL MANAGER</u></b></font></td>							
				</tr>
				<tr>
					<td colspan="7" align="center"><font size="4"><b> <u>MLO WISE PANGOAN LOADING SUMMARY</u></b></font></td>					
				</tr>
<tr><td>&nbsp;</td></tr>				
<tr>		
		
		<td width="90%" align="center">
			<table border=0 width="90%">
				<tr>
					<td ><font size="4">VESSEL: </font></td>
					<td style="text-decoration: underline;text-decoration-style:dotted;"><font size="4"><b> <?php echo $vsl_name; ?></b></font></td>
					<td><font size="4">VOY:</font></td>
					<td style="text-decoration: underline;text-decoration-style:dotted;"><font size="4"><b> <?php echo $voysNo; ?></b></font></td>
					<td ><font size="4"> EXPORT ROT:</font></td>
					<td  style="text-decoration: underline;text-decoration-style:dotted;"><font size="4"><b> <?php echo $ddl_imp_rot_no; ?></b></font></td>
					<td ><font size="4">SAILED DATE:</font></td>
					<td  style="text-decoration: underline;text-decoration-style:dotted;"><font size="4"><b> <?php echo $atd; ?></b></font></td>
					<td><font size="4">BERTH NO:</font></td>
					<td style="text-decoration: underline;text-decoration-style:dotted;"><font size="4"><b> <?php echo $berth; ?></b></font></td>
				</tr>
				<!--tr>
					<td colspan="2"><font size="4">Arrived on</font></td>
					<td colspan="2" style="text-decoration: underline;text-decoration-style:dotted;"><font size="4"><b> <?php echo $ata; ?></b></font></td>
					
				</tr-->
			</table>
		</td>
	</tr>
	
	<tr bgcolor="#ffffff" align="center" height="25px">
		<td colspan="15" align="center"></td>		
	</tr>
</table>
	<table class="table table-bordered table-responsive table-hover table-striped mb-none">
	<tr>
		<td rowspan="2" align="center"><b>MLO</b></td>
		<td colspan="11" align="center"><b>LADEN</b></td>
		<td colspan="11" align="center"><b>EMPTY</b></td>
		<td rowspan="2" align="center"><b>TOTAL CONTS</b></td>
		<td rowspan="2" align="center"><b>TOTAL TEUS</b></td>
		<td rowspan="2" align="center"><b>WEIGHT</b></td>
	</tr>
	<tr height="40px" align="center">
		<!--td><b>MLO'S</b></td-->		
		<!--td><b>CODE</b></td-->		
		<td><b>2D</b></td>
		<td><b>4D</b></td>
		<td><b>4H</b></td>
		<td><b>45H</b></td>		
		<td><b>4RH</b></td>
		<td><b>2RF</b></td>
		<td><b>2OT</b></td>
		<td><b>2FR</b></td>
		<td><b>2TK</b></td>
		<td><b>4FR</b></td>
		<td><b>4OT</b></td>
		<td><b>2D</b></td>
		<td><b>4D</b></td>
		<td><b>4H</b></td>
		<td><b>45H</b></td>		
		<td><b>4RH</b></td>
		<td><b>2RF</b></td>
		<td><b>2OT</b></td>
		<td><b>2FR</b></td>
		<td><b>2TK</b></td>
		<td><b>4FR</b></td>
		<td><b>4OT</b></td>				
		<!--td><b>TOTAL CONTS</b></td>
		<td><b>TOTAL TEUS</b></td-->
	
	</tr>
	
	

<?php


// $query=mysqli_query($con_sparcsn4,"SELECT mlo,(SELECT NAME FROM sparcsn4.ref_bizunit_scoped WHERE id=mlo AND NAME !='NULL' LIMIT 1) AS mlo_name,
// IFNULL(SUM(D_20),0) AS D_20,
// IFNULL(SUM(D_40),0) AS D_40,
// IFNULL(SUM(H_40),0) AS H_40,
// IFNULL(SUM(H_45),0) AS H_45,

// IFNULL(SUM(R_20),0) AS R_20,
// IFNULL(SUM(RH_40),0) AS RH_40,

// IFNULL(SUM(OT_20),0) AS OT_20,
// IFNULL(SUM(OT_40),0) AS OT_40,

// IFNULL(SUM(FR_20),0) AS FR_20,
// IFNULL(SUM(FR_40),0) AS FR_40,

// IFNULL(SUM(TK_20),0) AS TK_20,

// IFNULL(SUM(MD_20),0) AS MD_20,
// IFNULL(SUM(MD_40),0) AS MD_40,
// IFNULL(SUM(MH_40),0) AS MH_40,
// IFNULL(SUM(MH_45),0) AS MH_45,

// IFNULL(SUM(MR_20),0) AS MR_20,
// IFNULL(SUM(MRH_40),0) AS MRH_40,

// IFNULL(SUM(MOT_20),0) AS MOT_20,
// IFNULL(SUM(MOT_40),0) AS MOT_40,

// IFNULL(SUM(MFR_20),0) AS MFR_20,
// IFNULL(SUM(MFR_40),0) AS MFR_40,

// IFNULL(SUM(MTK_20),0) AS MTK_20,

// IFNULL(SUM(grand_tot),0) AS grand_tot,
// IFNULL(SUM(tues),0) AS tues,
// SUM(goods_and_ctr_wt_kg) AS weight
// FROM (
// SELECT DISTINCT sparcsn4.inv_unit.gkey,r.id AS mlo,category,freight_kind,inv_unit.goods_and_ctr_wt_kg,
// (CASE WHEN RIGHT(sparcsn4.ref_equip_type.nominal_length,2)='20' AND RIGHT(sparcsn4.ref_equip_type.nominal_height,2)='86' AND freight_kind IN ('FCL','LCL') AND ref_equip_type.iso_group NOT IN ('RS','RT','RE','UT','TN','TD','TG','PF','PC') THEN 1  
// ELSE NULL END) AS D_20,
// (CASE WHEN RIGHT(sparcsn4.ref_equip_type.nominal_length,2)='40' AND RIGHT(sparcsn4.ref_equip_type.nominal_height,2)='86' AND freight_kind IN ('FCL','LCL') AND ref_equip_type.iso_group NOT IN ('RS','RT','RE','UT','TN','TD','TG','PF','PC') THEN 1  
// ELSE NULL END) AS D_40,

// (CASE WHEN RIGHT(sparcsn4.ref_equip_type.nominal_length,2)='40' AND RIGHT(sparcsn4.ref_equip_type.nominal_height,2)='96' AND freight_kind IN ('FCL','LCL') AND ref_equip_type.iso_group NOT IN ('RS','RT','RE','UT','TN','TD','TG','PF','PC') THEN 1  
// ELSE NULL END) AS H_40,

// (CASE WHEN RIGHT(sparcsn4.ref_equip_type.nominal_length,2)='45' AND RIGHT(sparcsn4.ref_equip_type.nominal_height,2)='96' AND freight_kind IN ('FCL','LCL') AND ref_equip_type.iso_group NOT IN ('RS','RT','RE','UT','TN','TD','TG','PF','PC')
// THEN 1
// WHEN RIGHT(sparcsn4.ref_equip_type.nominal_length,2) = '42' AND RIGHT(sparcsn4.ref_equip_type.nominal_height,2)='90' AND freight_kind IN ('FCL','LCL') AND ref_equip_type.iso_group NOT IN ('RS','RT','RE','UT','TN','TD','TG','PF','PC')  THEN 1
// ELSE NULL END) AS H_45,

// (CASE WHEN RIGHT(sparcsn4.ref_equip_type.nominal_length,2)='20' AND RIGHT(sparcsn4.ref_equip_type.nominal_height,2)='86' AND freight_kind IN ('FCL','LCL') AND ref_equip_type.iso_group IN ('RS','RT','RE')  THEN 1  
// ELSE NULL END) AS R_20, 

// (CASE WHEN RIGHT(sparcsn4.ref_equip_type.nominal_length,2)='40' AND freight_kind IN ('FCL','LCL') AND ref_equip_type.iso_group IN ('RS','RT','RE') THEN 1  
// ELSE NULL END) AS RH_40,

// (CASE WHEN RIGHT(sparcsn4.ref_equip_type.nominal_length,2)='20' AND RIGHT(sparcsn4.ref_equip_type.nominal_height,2)='86' AND freight_kind IN ('FCL','LCL') AND ref_equip_type.iso_group IN ('UT')  THEN 1  
// ELSE NULL END) AS OT_20,
// (CASE WHEN RIGHT(sparcsn4.ref_equip_type.nominal_length,2)='40' AND freight_kind IN ('FCL','LCL') AND ref_equip_type.iso_group IN ('UT')  THEN 1  
// ELSE NULL END) AS OT_40,

// (CASE WHEN RIGHT(sparcsn4.ref_equip_type.nominal_length,2)='20' AND RIGHT(sparcsn4.ref_equip_type.nominal_height,2)='86' AND freight_kind IN ('FCL','LCL') AND ref_equip_type.iso_group IN ('PF','PC')  THEN 1  
// ELSE NULL END) AS FR_20,
// (CASE WHEN RIGHT(sparcsn4.ref_equip_type.nominal_length,2)='40' AND freight_kind IN ('FCL','LCL') AND ref_equip_type.iso_group IN ('PF','PC')  THEN 1  
// ELSE NULL END) AS FR_40,

// (CASE WHEN RIGHT(sparcsn4.ref_equip_type.nominal_length,2)='20' AND RIGHT(sparcsn4.ref_equip_type.nominal_height,2)='86' AND freight_kind IN ('FCL','LCL') AND ref_equip_type.iso_group IN ('TN','TD','TG')  THEN 1  
// ELSE NULL END) AS TK_20,

// (CASE WHEN RIGHT(sparcsn4.ref_equip_type.nominal_length,2)='20' AND RIGHT(sparcsn4.ref_equip_type.nominal_height,2)='86' AND freight_kind='MTY' AND ref_equip_type.iso_group NOT IN ('RS','RT','RE','UT','TN','TD','TG','PF','PC')  THEN 1  
// ELSE NULL END) AS MD_20, 
// (CASE WHEN RIGHT(sparcsn4.ref_equip_type.nominal_length,2)='40' AND RIGHT(sparcsn4.ref_equip_type.nominal_height,2)='86' AND freight_kind='MTY' AND ref_equip_type.iso_group NOT IN ('RS','RT','RE','UT','TN','TD','TG','PF','PC')  THEN 1  
// ELSE NULL END) AS MD_40,

// (CASE WHEN RIGHT(sparcsn4.ref_equip_type.nominal_length,2)='40' AND RIGHT(sparcsn4.ref_equip_type.nominal_height,2)='96' AND freight_kind='MTY' AND ref_equip_type.iso_group NOT IN ('RS','RT','RE','UT','TN','TD','TG','PF','PC')  THEN 1  
// ELSE NULL END) AS MH_40, 

// (CASE WHEN RIGHT(sparcsn4.ref_equip_type.nominal_length,2)='45' AND RIGHT(sparcsn4.ref_equip_type.nominal_height,2)='96' AND freight_kind='MTY' AND ref_equip_type.iso_group NOT IN ('RS','RT','RE','UT','TN','TD','TG','PF','PC')  THEN 1  
// ELSE NULL END) AS MH_45,

// (CASE WHEN RIGHT(sparcsn4.ref_equip_type.nominal_length,2)='20' AND RIGHT(sparcsn4.ref_equip_type.nominal_height,2)='86' AND freight_kind='MTY' AND ref_equip_type.iso_group IN ('RS','RT','RE')  THEN 1  
// ELSE NULL END) AS MR_20, 

// (CASE WHEN RIGHT(sparcsn4.ref_equip_type.nominal_length,2) = '40' AND freight_kind='MTY' AND ref_equip_type.iso_group IN ('RS','RT','RE')  THEN 1  
// ELSE NULL END) AS MRH_40,

// (CASE WHEN RIGHT(sparcsn4.ref_equip_type.nominal_length,2)='20' AND RIGHT(sparcsn4.ref_equip_type.nominal_height,2)='86' AND freight_kind='MTY' AND ref_equip_type.iso_group IN ('UT')  THEN 1  
// ELSE NULL END) AS MOT_20,
// (CASE WHEN RIGHT(sparcsn4.ref_equip_type.nominal_length,2) = '40' AND freight_kind='MTY' AND ref_equip_type.iso_group IN ('UT')  THEN 1  
// ELSE NULL END) AS MOT_40,

// (CASE WHEN RIGHT(sparcsn4.ref_equip_type.nominal_length,2)='20' AND RIGHT(sparcsn4.ref_equip_type.nominal_height,2)='86' AND freight_kind='MTY' AND ref_equip_type.iso_group IN ('PF','PC')  THEN 1  
// ELSE NULL END) AS MFR_20,
// (CASE WHEN RIGHT(sparcsn4.ref_equip_type.nominal_length,2)='40' AND freight_kind='MTY' AND ref_equip_type.iso_group IN ('PF','PC')  THEN 1  
// ELSE NULL END) AS MFR_40,

// (CASE WHEN RIGHT(sparcsn4.ref_equip_type.nominal_length,2) = '20' AND RIGHT(sparcsn4.ref_equip_type.nominal_height,2)='86' AND freight_kind='MTY' AND ref_equip_type.iso_group IN ('TN','TD','TG')  THEN 1  
// ELSE NULL END) AS MTK_20,

// (CASE WHEN RIGHT(sparcsn4.ref_equip_type.nominal_length,2) IN('20','40','45','42')  THEN 1 ELSE NULL END) AS grand_tot,

// (CASE WHEN RIGHT(sparcsn4.ref_equip_type.nominal_length,2)=20  THEN 1 ELSE 2 END) AS tues  

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
// WHERE ib_vyg='$ddl_imp_rot_no') AS tmp GROUP BY mlo WITH ROLLUP");

 $query="
	
SELECT gkey,mlo,mlo_name,
NVL(SUM(D_20),0) AS D_20,
NVL(SUM(D_40),0) AS D_40,
NVL(SUM(H_40),0) AS H_40,
NVL(SUM(H_45),0) AS H_45,

NVL(SUM(R_20),0) AS R_20,
NVL(SUM(RH_40),0) AS RH_40,

NVL(SUM(OT_20),0) AS OT_20,
NVL(SUM(OT_40),0) AS OT_40,

NVL(SUM(FR_20),0) AS FR_20,
NVL(SUM(FR_40),0) AS FR_40,

NVL(SUM(TK_20),0) AS TK_20,

NVL(SUM(MD_20),0) AS MD_20,
NVL(SUM(MD_40),0) AS MD_40,
NVL(SUM(MH_40),0) AS MH_40,
NVL(SUM(MH_45),0) AS MH_45,

NVL(SUM(MR_20),0) AS MR_20,
NVL(SUM(MRH_40),0) AS MRH_40,

NVL(SUM(MOT_20),0) AS MOT_20,
NVL(SUM(MOT_40),0) AS MOT_40,

NVL(SUM(MFR_20),0) AS MFR_20,
NVL(SUM(MFR_40),0) AS MFR_40,

NVL(SUM(MTK_20),0) AS MTK_20,

NVL(SUM(grand_tot),0) AS grand_tot,
NVL(SUM(tues),0) AS tues,
SUM(goods_and_ctr_wt_kg) AS weight



FROM (
SELECT DISTINCT inv_unit.gkey AS gkey,r.id AS mlo,r.name AS mlo_name,inv_unit.goods_and_ctr_wt_kg,
(CASE WHEN  substr(ref_equip_type.nominal_length,-2) = '20' AND (substr(ref_equip_type.nominal_height,-2))='86' AND freight_kind IN ('FCL','LCL') AND ref_equip_type.iso_group NOT IN ('RS','RT','RE','UT','TN','TD','TG','PF','PC')  THEN 1  
ELSE NULL END) AS D_20, 
(CASE WHEN substr(ref_equip_type.nominal_length,-2) = '40' AND (substr(ref_equip_type.nominal_height,-2))='86' AND freight_kind IN ('FCL','LCL') AND ref_equip_type.iso_group NOT IN ('RS','RT','RE','UT','TN','TD','TG','PF','PC')  THEN 1  
ELSE NULL END) AS D_40, 
(CASE WHEN substr(ref_equip_type.nominal_length,-2) = '40' AND (substr(ref_equip_type.nominal_height,-2))='96' AND freight_kind IN ('FCL','LCL') AND ref_equip_type.iso_group NOT IN ('RS','RT','RE','UT','TN','TD','TG','PF','PC')  THEN 1  
ELSE NULL END) AS H_40, 
(CASE WHEN substr(ref_equip_type.nominal_length,-2) = '45' AND (substr(ref_equip_type.nominal_height,-2))='96' AND freight_kind IN ('FCL','LCL') AND ref_equip_type.iso_group NOT IN ('RS','RT','RE','UT','TN','TD','TG','PF','PC')  THEN 1
WHEN substr(ref_equip_type.nominal_length,-2) = '42' AND (substr(ref_equip_type.nominal_height,-2))='90' AND freight_kind IN ('FCL','LCL') AND ref_equip_type.iso_group NOT IN ('RS','RT','RE','UT','TN','TD','TG','PF','PC')  THEN 1
ELSE NULL END) AS H_45,


(CASE WHEN substr(ref_equip_type.nominal_length,-2) = '20' AND (substr(ref_equip_type.nominal_height,-2))='86' AND freight_kind IN ('FCL','LCL') AND ref_equip_type.iso_group IN ('RS','RT','RE')  THEN 1  
ELSE NULL END) AS R_20, 
(CASE WHEN substr(ref_equip_type.nominal_length,-2) = '40' AND freight_kind IN ('FCL','LCL') AND ref_equip_type.iso_group IN ('RS','RT','RE')  THEN 1  
ELSE NULL END) AS RH_40,

(CASE WHEN substr(ref_equip_type.nominal_length,-2) = '20' AND (substr(ref_equip_type.nominal_height,-2))='86' AND freight_kind IN ('FCL','LCL') AND ref_equip_type.iso_group IN ('UT')  THEN 1  
ELSE NULL END) AS OT_20,
(CASE WHEN substr(ref_equip_type.nominal_length,-2) = '40' AND freight_kind IN ('FCL','LCL') AND ref_equip_type.iso_group IN ('UT')  THEN 1  
ELSE NULL END) AS OT_40,

(CASE WHEN substr(ref_equip_type.nominal_length,-2) = '20' AND (substr(ref_equip_type.nominal_height,-2))='86' AND freight_kind IN ('FCL','LCL') AND ref_equip_type.iso_group IN ('PF','PC')  THEN 1  
ELSE NULL END) AS FR_20,
(CASE WHEN substr(ref_equip_type.nominal_length,-2) = '40' AND freight_kind IN ('FCL','LCL') AND ref_equip_type.iso_group IN ('PF','PC')  THEN 1  
ELSE NULL END) AS FR_40,

(CASE WHEN substr(ref_equip_type.nominal_length,-2) = '20' AND (substr(ref_equip_type.nominal_height,-2))='86' AND freight_kind IN ('FCL','LCL') AND ref_equip_type.iso_group IN ('TN','TD','TG')  THEN 1  
ELSE NULL END) AS TK_20,

(CASE WHEN substr(ref_equip_type.nominal_length,-2) = '20' AND (substr(ref_equip_type.nominal_height,-2))='86' AND freight_kind ='MTY' AND ref_equip_type.iso_group NOT IN ('RS','RT','RE','UT','TN','TD','TG','PF','PC')  THEN 1  
ELSE NULL END) AS MD_20, 
(CASE WHEN substr(ref_equip_type.nominal_length,-2) = '40' AND (substr(ref_equip_type.nominal_height,-2))='86' AND freight_kind ='MTY' AND ref_equip_type.iso_group NOT IN ('RS','RT','RE','UT','TN','TD','TG','PF','PC')  THEN 1  
ELSE NULL END) AS MD_40, 
(CASE WHEN substr(ref_equip_type.nominal_length,-2) = '40' AND (substr(ref_equip_type.nominal_height,-2))='96' AND freight_kind ='MTY' AND ref_equip_type.iso_group NOT IN ('RS','RT','RE','UT','TN','TD','TG','PF','PC')  THEN 1  
ELSE NULL END) AS MH_40, 
(CASE WHEN substr(ref_equip_type.nominal_length,-2) = '45' AND (substr(ref_equip_type.nominal_height,-2))='96' AND freight_kind ='MTY' AND ref_equip_type.iso_group NOT IN ('RS','RT','RE','UT','TN','TD','TG','PF','PC')  THEN 1  
ELSE NULL END) AS MH_45,

(CASE WHEN substr(ref_equip_type.nominal_length,-2) = '20' AND (substr(ref_equip_type.nominal_height,-2))='86' AND freight_kind ='MTY' AND ref_equip_type.iso_group IN ('RS','RT','RE')  THEN 1  
ELSE NULL END) AS MR_20, 
(CASE WHEN substr(ref_equip_type.nominal_length,-2) = '40' AND freight_kind ='MTY' AND ref_equip_type.iso_group IN ('RS','RT','RE')  THEN 1  
ELSE NULL END) AS MRH_40,

(CASE WHEN substr(ref_equip_type.nominal_length,-2) = '20' AND (substr(ref_equip_type.nominal_height,-2))='86' AND freight_kind ='MTY' AND ref_equip_type.iso_group IN ('UT')  THEN 1  
ELSE NULL END) AS MOT_20,
(CASE WHEN substr(ref_equip_type.nominal_length,-2) = '40' AND freight_kind ='MTY' AND ref_equip_type.iso_group IN ('UT')  THEN 1  
ELSE NULL END) AS MOT_40,

(CASE WHEN substr(ref_equip_type.nominal_length,-2) = '20' AND (substr(ref_equip_type.nominal_height,-2))='86' AND freight_kind ='MTY' AND ref_equip_type.iso_group IN ('PF','PC')  THEN 1  
ELSE NULL END) AS MFR_20,
(CASE WHEN substr(ref_equip_type.nominal_length,-2) = '40' AND freight_kind ='MTY' AND ref_equip_type.iso_group IN ('PF','PC')  THEN 1  
ELSE NULL END) AS MFR_40,

(CASE WHEN substr(ref_equip_type.nominal_length,-2) = '20' AND (substr(ref_equip_type.nominal_height,-2))='86' AND freight_kind ='MTY' AND ref_equip_type.iso_group IN ('TN','TD','TG')  THEN 1  
ELSE NULL END) AS MTK_20,
(CASE WHEN substr(ref_equip_type.nominal_length,-2) IN('20','40','45','42')  THEN 1 ELSE NULL END) AS grand_tot,
(CASE WHEN substr(ref_equip_type.nominal_length,-2)=20  THEN 1 ELSE 2 END) AS tues  




FROM inv_unit
INNER JOIN inv_unit_fcy_visit ON inv_unit_fcy_visit.unit_gkey=inv_unit.gkey
INNER JOIN argo_carrier_visit ON argo_carrier_visit.gkey=inv_unit_fcy_visit.actual_ob_cv
INNER JOIN argo_visit_details ON argo_visit_details.gkey=argo_carrier_visit.cvcvd_gkey 
INNER JOIN vsl_vessel_visit_details ON vsl_vessel_visit_details.vvd_gkey=argo_visit_details.gkey 
INNER JOIN ref_equipment ON ref_equipment.gkey=inv_unit.eq_gkey
INNER JOIN ref_equip_type ON ref_equip_type.gkey=ref_equipment.eqtyp_gkey
INNER JOIN  ( ref_bizunit_scoped r        
LEFT JOIN ( ref_agent_representation X        
LEFT JOIN ref_bizunit_scoped Y ON X.agent_gkey=Y.gkey )  ON r.gkey=X.bzu_gkey        )  ON r.gkey = inv_unit.line_op
WHERE vsl_vessel_visit_details.ib_vyg='$ddl_imp_rot_no'
)
GROUP BY gkey,mlo,mlo_name";


// $QueryOne=oci_parse($con_sparcsn4_oracle,$query);

// $rowName=oci_execute($QueryOne);

$queryInfo=oci_parse($con_sparcsn4_oracle,$query);
$query1=oci_execute($queryInfo);
// $results=array();
// $nrows = oci_fetch_all($queryInfo, $results, null, null, OCI_FETCHSTATEMENT_BY_ROW);



// oci_free_statement($queryInfo);
// $queryInfo=oci_parse($con_sparcsn4_oracle,$query);
// $query1=oci_execute($queryInfo);



	$i=0;
	$j=0;
	
	$mlo="";
	while(($row=oci_fetch_object($queryInfo)) !=false)
{ 
	$i++;
?>
<tr align="center">
		<!--td><?php if($row->MLO) echo $row->MLO_NAME; else echo "<b>TOTAL</b>";?></td-->
		<td><?php if($row->MLO) echo $row->MLO; else echo "<b>TOTAL</b>";?></td>
		<td><?php if($row->D_20) echo $row->D_20; else echo "&nbsp;";?></td>
		<td><?php if($row->D_40) echo $row->D_40; else echo "&nbsp;";?></td>
		<td><?php if($row->H_40) echo $row->H_40; else echo "&nbsp;";?></td>
		<td><?php if($row->H_45) echo $row->H_45; else echo "&nbsp;";?></td>
		
		<td><?php if($row->RH_40) echo $row->RH_40; else echo "&nbsp;";?></td>
		<td><?php if($row->R_20) echo $row->R_20; else echo "&nbsp;";?></td>
		
		
		<td><?php if($row->OT_20) echo $row->OT_20; else echo "&nbsp;";?></td>
		<td><?php if($row->FR_20) echo $row->FR_20; else echo "&nbsp;";?></td>
		<td><?php if($row->TK_20) echo $row->TK_20; else echo "&nbsp;";?></td>
		
		<td><?php if($row->FR_40) echo $row->FR_40; else echo "&nbsp;";?></td>
		<td><?php if($row->OT_40) echo $row->OT_40; else echo "&nbsp;";?></td>
		
		<td><?php if($row->MD_20) echo $row->MD_20; else echo "&nbsp;";?></td>
		<td><?php if($row->MD_40) echo $row->MD_40; else echo "&nbsp;";?></td>
		<td><?php if($row->MH_40) echo $row->MH_40; else echo "&nbsp;";?></td>
		<td><?php if($row->MH_45) echo $row->MH_45; else echo "&nbsp;";?></td>
		
		<td><?php if($row->MRH_40) echo $row->MRH_40; else echo "&nbsp;";?></td>
		<td><?php if($row->MR_20) echo $row->MR_20; else echo "&nbsp;";?></td>		
		
		
		<td><?php if($row->MOT_20) echo $row->MOT_20; else echo "&nbsp;";?></td>
		<td><?php if($row->MFR_20) echo $row->MFR_20; else echo "&nbsp;";?></td>
		<td><?php if($row->MTK_20) echo $row->MTK_20; else echo "&nbsp;";?></td>
		
		<td><?php if($row->MFR_40) echo $row->MFR_40; else echo "&nbsp;";?></td>
		<td><?php if($row->MOT_40) echo $row->MOT_40; else echo "&nbsp;";?></td>
		
		<td><?php if($row->GRAND_TOT) echo $row->GRAND_TOT; else echo "&nbsp;";?></td>
		<td><?php if($row->TUES) echo $row->TUES; else echo "&nbsp;";?></td>
		<td><?php if($row->WEIGHT) echo $row->WEIGHT; else echo "&nbsp;";?></td>
	</tr>

<?php } ?>

</table>
<?php 
mysqli_close($con_sparcsn4);
oci_close($con_sparcsn4_oracle);
if($_POST['options']=='html'){?>	
	</BODY>
</HTML>
<?php }?>
