
<BODY>
	<?php 
	include_once("FrontEnd/mydbPConnectionn4.php");
	include("dbOracleConnection.php");

	$sql="select vvd_gkey from vsl_vessel_visit_details where ib_vyg='$ddl_imp_rot_no'";
	$stid = oci_parse($con_sparcsn4_oracle, $sql);
	oci_execute($stid);
	$vvdGkey = "";
	while(($row_vvd_gkey=oci_fetch_object($stid)) !=false)
	{
		$vvdGkey=$row_vvd_gkey->VVD_GKEY;

	}


	$sql="call ctmsmis.update_containers_by_vvd_gkey($vvdGkey)";
	$res=mysqli_query($con_sparcsn4,$sql);
	
	// $sql1="SELECT vsl_vessels.name AS vsl_name,COALESCE(vsl_vessel_visit_details.flex_string02,vsl_vessel_visit_details.flex_string03) AS berthop,COALESCE(argo_quay.id,'') AS berth,
	// argo_carrier_visit.ata,argo_carrier_visit.atd FROM vsl_vessel_visit_details
	// INNER JOIN argo_carrier_visit ON argo_carrier_visit.cvcvd_gkey=vsl_vessel_visit_details.vvd_gkey
	// INNER JOIN vsl_vessel_berthings ON vsl_vessel_berthings.vvd_gkey=vsl_vessel_visit_details.vvd_gkey
	// INNER JOIN vsl_vessels ON vsl_vessels.gkey=vsl_vessel_visit_details.vessel_gkey
	// INNER JOIN argo_quay ON argo_quay.gkey=vsl_vessel_berthings.quay
	// WHERE vsl_vessel_visit_details.vvd_gkey=$vvdGkey";

	// $stid = oci_parse($con_sparcsn4_oracle, $sql1);
	// $row1=oci_execute($stid);

	$sql1="SELECT vsl_vessels.name,COALESCE(vsl_vessel_visit_details.flex_string02,vsl_vessel_visit_details.flex_string03) AS berthop,COALESCE(argo_quay.id,'') AS berth,
	argo_carrier_visit.ata,argo_carrier_visit.atd FROM vsl_vessel_visit_details
	INNER JOIN argo_carrier_visit ON argo_carrier_visit.cvcvd_gkey=vsl_vessel_visit_details.vvd_gkey
	INNER JOIN vsl_vessel_berthings ON vsl_vessel_berthings.vvd_gkey=vsl_vessel_visit_details.vvd_gkey
	INNER JOIN vsl_vessels ON vsl_vessels.gkey=vsl_vessel_visit_details.vessel_gkey
	INNER JOIN argo_quay ON argo_quay.gkey=vsl_vessel_berthings.quay
	WHERE vsl_vessel_visit_details.vvd_gkey='$vvdGkey'";
	
	$resSql1 = oci_parse($con_sparcsn4_oracle, $sql1);
	oci_execute($resSql1);
	$vsl_name = "";
	$ata = "";
	$atd = "";
	$berth="";
	while(($row1=oci_fetch_object($resSql1)) !=false)
	{
	$vsl_name = $row1->NAME;
	$ata = $row1->ATA ;

	$atd = $row1->ATD ;
	$berth=$row1->BERTHOP;
	}

	?>
<html>
<title>MLO WISE LOADED CONTAINER LIST</title>
<body>
<table width="100%" border ='0' cellpadding='0' cellspacing='0'>
<tr bgcolor="#ffffff" align="center" height="100px">
		<td width="30%" align="centre"><img align="middle"  src="<?php echo IMG_PATH?>cpanew.jpg"></td>
		<td width="70%" align="center">
			<table border=0 width="100%">
				<tr>
					<td ><font size="4">M.V. </font></td>
					<td colspan="3" style="text-decoration: underline;text-decoration-style:dotted;"><font size="4"><b> <?php echo $vsl_name; ?></b></font></td>
					<td><font size="4">VOY</font></td>
					<td colspan="3" style="text-decoration: underline;text-decoration-style:dotted;"><font size="4"><b> <?php echo $voysNo; ?></b></font></td>
					<td ><font size="4">ROT/NO</font></td>
					<td colspan="3" style="text-decoration: underline;text-decoration-style:dotted;"><font size="4"><b> <?php echo $ddl_imp_rot_no; ?></b></font></td>
				</tr>
				<tr>
					<td colspan="2"><font size="4">Arrived on</font></td>
					<td colspan="2" style="text-decoration: underline;text-decoration-style:dotted;"><font size="4"><b> <?php echo $ata; ?></b></font></td>
					<td colspan="2" ><font size="4">Sailed on</font></td>
					<td colspan="2" style="text-decoration: underline;text-decoration-style:dotted;"><font size="4"><b> <?php echo $atd; ?></b></font></td>
					<td colspan="2" ><font size="4">Berth No</font></td>
					<td colspan="2" style="text-decoration: underline;text-decoration-style:dotted;"><font size="4"><b> <?php echo $berth; ?></b></font></td>
				</tr>

				<tr>
					<td colspan="7" align="center"><font size="5"><b> <u>MLO WISE EXPORT SUMMARY</u></b></font></td>
					
				</tr>

			</table>
		
		</td>
		
	</tr>
	
	<tr bgcolor="#ffffff" align="center" height="25px">
		<td colspan="15" align="center"></td>
		
	</tr>
</table>
	<table width="100%" border ='1' cellpadding='0' cellspacing='0'>
	<tr>
		<td></td>
		<td colspan="11" align="center"><b>LADEN</b></td>
		<td colspan="11" align="center"><b>EMPTY</b></td>
		<td></td>
		<td></td>
	</tr>
	<tr height="40px" align="center">
		<td><b>MLO'S</b></td>		
		<td><b>CODE</b></td>		
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
		<td><b>TOTAL CONTS</b></td>
		<td><b>TOTAL TEUS</b></td>
	
	</tr>
	
	

<?php
// 	$query="select gkey,mlo,
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
// IFNULL(SUM(tues),0) AS tues
//  from (
// select distinct ctmsmis.mis_exp_unit.gkey as gkey,cont_mlo as mlo,
// (CASE WHEN cont_size = '20' and cont_height='86' AND mis_exp_unit.cont_status in ('FCL','LCL') AND isoGroup not in ('RS','RT','RE','UT','TN','TD','TG','PF','PC')  THEN 1  
// ELSE NULL END) AS D_20, 
// (CASE WHEN cont_size = '40' and cont_height='86' AND mis_exp_unit.cont_status in ('FCL','LCL') AND isoGroup not in ('RS','RT','RE','UT','TN','TD','TG','PF','PC')  THEN 1  
// ELSE NULL END) AS D_40, 
// (CASE WHEN cont_size = '40' and cont_height='96' AND mis_exp_unit.cont_status in ('FCL','LCL') AND isoGroup not in ('RS','RT','RE','UT','TN','TD','TG','PF','PC')  THEN 1  
// ELSE NULL END) AS H_40, 
// (CASE WHEN cont_size = '45' and cont_height='96' AND mis_exp_unit.cont_status in ('FCL','LCL') AND isoGroup not in ('RS','RT','RE','UT','TN','TD','TG','PF','PC')  THEN 1
// 	  WHEN cont_size = '42' and cont_height='90' AND mis_exp_unit.cont_status in ('FCL','LCL') AND isoGroup not in ('RS','RT','RE','UT','TN','TD','TG','PF','PC')  THEN 1
// ELSE NULL END) AS H_45,


// (CASE WHEN cont_size = '20' and cont_height='86' AND mis_exp_unit.cont_status in ('FCL','LCL') AND isoGroup in ('RS','RT','RE')  THEN 1  
// ELSE NULL END) AS R_20, 
// (CASE WHEN cont_size = '40' AND mis_exp_unit.cont_status in ('FCL','LCL') AND isoGroup in ('RS','RT','RE')  THEN 1  
// ELSE NULL END) AS RH_40,

// (CASE WHEN cont_size = '20' and cont_height='86' AND mis_exp_unit.cont_status in ('FCL','LCL') AND isoGroup in ('UT')  THEN 1  
// ELSE NULL END) AS OT_20,
// (CASE WHEN cont_size = '40' AND mis_exp_unit.cont_status in ('FCL','LCL') AND isoGroup in ('UT')  THEN 1  
// ELSE NULL END) AS OT_40,

// (CASE WHEN cont_size = '20' and cont_height='86' AND mis_exp_unit.cont_status in ('FCL','LCL') AND isoGroup in ('PF','PC')  THEN 1  
// ELSE NULL END) AS FR_20,
// (CASE WHEN cont_size = '40' AND mis_exp_unit.cont_status in ('FCL','LCL') AND isoGroup in ('PF','PC')  THEN 1  
// ELSE NULL END) AS FR_40,

// (CASE WHEN cont_size = '20' and cont_height='86' AND mis_exp_unit.cont_status in ('FCL','LCL') AND isoGroup in ('TN','TD','TG')  THEN 1  
// ELSE NULL END) AS TK_20,

// (CASE WHEN cont_size = '20' and cont_height='86' AND mis_exp_unit.cont_status ='MTY' AND isoGroup not in ('RS','RT','RE','UT','TN','TD','TG','PF','PC')  THEN 1  
// ELSE NULL END) AS MD_20, 
// (CASE WHEN cont_size = '40' and cont_height='86' AND mis_exp_unit.cont_status ='MTY' AND isoGroup not in ('RS','RT','RE','UT','TN','TD','TG','PF','PC')  THEN 1  
// ELSE NULL END) AS MD_40, 
// (CASE WHEN cont_size = '40' and cont_height='96' AND mis_exp_unit.cont_status ='MTY' AND isoGroup not in ('RS','RT','RE','UT','TN','TD','TG','PF','PC')  THEN 1  
// ELSE NULL END) AS MH_40, 
// (CASE WHEN cont_size = '45' and cont_height='96' AND mis_exp_unit.cont_status ='MTY' AND isoGroup not in ('RS','RT','RE','UT','TN','TD','TG','PF','PC')  THEN 1  
// ELSE NULL END) AS MH_45,

// (CASE WHEN cont_size = '20' and cont_height='86' AND mis_exp_unit.cont_status ='MTY' AND isoGroup in ('RS','RT','RE')  THEN 1  
// ELSE NULL END) AS MR_20, 
// (CASE WHEN cont_size = '40' AND mis_exp_unit.cont_status ='MTY' AND isoGroup in ('RS','RT','RE')  THEN 1  
// ELSE NULL END) AS MRH_40,

// (CASE WHEN cont_size = '20' and cont_height='86' AND mis_exp_unit.cont_status ='MTY' AND isoGroup in ('UT')  THEN 1  
// ELSE NULL END) AS MOT_20,
// (CASE WHEN cont_size = '40' AND mis_exp_unit.cont_status ='MTY' AND isoGroup in ('UT')  THEN 1  
// ELSE NULL END) AS MOT_40,

// (CASE WHEN cont_size = '20' and cont_height='86' AND mis_exp_unit.cont_status ='MTY' AND isoGroup in ('PF','PC')  THEN 1  
// ELSE NULL END) AS MFR_20,
// (CASE WHEN cont_size = '40' AND mis_exp_unit.cont_status ='MTY' AND isoGroup in ('PF','PC')  THEN 1  
// ELSE NULL END) AS MFR_40,

// (CASE WHEN cont_size = '20' and cont_height='86' AND mis_exp_unit.cont_status ='MTY' AND isoGroup in ('TN','TD','TG')  THEN 1  
// ELSE NULL END) AS MTK_20,
// (CASE WHEN cont_size in('20','40','45','42')  THEN 1 ELSE NULL END) AS grand_tot,
// (CASE WHEN cont_size=20  THEN 1 ELSE 2 END) AS tues     
// FROM ctmsmis.mis_exp_unit
 
// where  mis_exp_unit.vvd_gkey='$vvdGkey' AND mis_exp_unit.preAddStat='0' and snx_type=0 and cont_mlo is not null
// ) as tmp group by mlo WITH ROLLUP";


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
	SELECT DISTINCT inv_unit.gkey AS gkey,r.id AS mlo,r.name AS mlo_name,inv_unit.goods_and_ctr_wt_kg,vsl_vessel_visit_details.vvd_gkey,
	(CASE WHEN  SUBSTR(ref_equip_type.nominal_length,-2) = '20' AND (SUBSTR(ref_equip_type.nominal_height,-2))='86' AND freight_kind IN ('FCL','LCL') AND ref_equip_type.iso_group NOT IN ('RS','RT','RE','UT','TN','TD','TG','PF','PC')  THEN 1 
	ELSE NULL END) AS D_20,
	(CASE WHEN SUBSTR(ref_equip_type.nominal_length,-2) = '40' AND (SUBSTR(ref_equip_type.nominal_height,-2))='86' AND freight_kind IN ('FCL','LCL') AND ref_equip_type.iso_group NOT IN ('RS','RT','RE','UT','TN','TD','TG','PF','PC')  THEN 1 
	ELSE NULL END) AS D_40,
	(CASE WHEN SUBSTR(ref_equip_type.nominal_length,-2) = '40' AND (SUBSTR(ref_equip_type.nominal_height,-2))='96' AND freight_kind IN ('FCL','LCL') AND ref_equip_type.iso_group NOT IN ('RS','RT','RE','UT','TN','TD','TG','PF','PC')  THEN 1 
	ELSE NULL END) AS H_40,
	(CASE WHEN SUBSTR(ref_equip_type.nominal_length,-2) = '45' AND (SUBSTR(ref_equip_type.nominal_height,-2))='96' AND freight_kind IN ('FCL','LCL') AND ref_equip_type.iso_group NOT IN ('RS','RT','RE','UT','TN','TD','TG','PF','PC')  THEN 1
	WHEN SUBSTR(ref_equip_type.nominal_length,-2) = '42' AND (SUBSTR(ref_equip_type.nominal_height,-2))='90' AND freight_kind IN ('FCL','LCL') AND ref_equip_type.iso_group NOT IN ('RS','RT','RE','UT','TN','TD','TG','PF','PC')  THEN 1
	ELSE NULL END) AS H_45,
	
	
	(CASE WHEN SUBSTR(ref_equip_type.nominal_length,-2) = '20' AND (SUBSTR(ref_equip_type.nominal_height,-2))='86' AND freight_kind IN ('FCL','LCL') AND ref_equip_type.iso_group IN ('RS','RT','RE')  THEN 1 
	ELSE NULL END) AS R_20,
	(CASE WHEN SUBSTR(ref_equip_type.nominal_length,-2) = '40' AND freight_kind IN ('FCL','LCL') AND ref_equip_type.iso_group IN ('RS','RT','RE')  THEN 1 
	ELSE NULL END) AS RH_40,
	
	(CASE WHEN SUBSTR(ref_equip_type.nominal_length,-2) = '20' AND (SUBSTR(ref_equip_type.nominal_height,-2))='86' AND freight_kind IN ('FCL','LCL') AND ref_equip_type.iso_group IN ('UT')  THEN 1 
	ELSE NULL END) AS OT_20,
	(CASE WHEN SUBSTR(ref_equip_type.nominal_length,-2) = '40' AND freight_kind IN ('FCL','LCL') AND ref_equip_type.iso_group IN ('UT')  THEN 1 
	ELSE NULL END) AS OT_40,
	
	(CASE WHEN SUBSTR(ref_equip_type.nominal_length,-2) = '20' AND (SUBSTR(ref_equip_type.nominal_height,-2))='86' AND freight_kind IN ('FCL','LCL') AND ref_equip_type.iso_group IN ('PF','PC')  THEN 1 
	ELSE NULL END) AS FR_20,
	(CASE WHEN SUBSTR(ref_equip_type.nominal_length,-2) = '40' AND freight_kind IN ('FCL','LCL') AND ref_equip_type.iso_group IN ('PF','PC')  THEN 1 
	ELSE NULL END) AS FR_40,
	
	(CASE WHEN SUBSTR(ref_equip_type.nominal_length,-2) = '20' AND (SUBSTR(ref_equip_type.nominal_height,-2))='86' AND freight_kind IN ('FCL','LCL') AND ref_equip_type.iso_group IN ('TN','TD','TG')  THEN 1 
	ELSE NULL END) AS TK_20,
	
	(CASE WHEN SUBSTR(ref_equip_type.nominal_length,-2) = '20' AND (SUBSTR(ref_equip_type.nominal_height,-2))='86' AND freight_kind ='MTY' AND ref_equip_type.iso_group NOT IN ('RS','RT','RE','UT','TN','TD','TG','PF','PC')  THEN 1 
	ELSE NULL END) AS MD_20,
	(CASE WHEN SUBSTR(ref_equip_type.nominal_length,-2) = '40' AND (SUBSTR(ref_equip_type.nominal_height,-2))='86' AND freight_kind ='MTY' AND ref_equip_type.iso_group NOT IN ('RS','RT','RE','UT','TN','TD','TG','PF','PC')  THEN 1 
	ELSE NULL END) AS MD_40,
	(CASE WHEN SUBSTR(ref_equip_type.nominal_length,-2) = '40' AND (SUBSTR(ref_equip_type.nominal_height,-2))='96' AND freight_kind ='MTY' AND ref_equip_type.iso_group NOT IN ('RS','RT','RE','UT','TN','TD','TG','PF','PC')  THEN 1 
	ELSE NULL END) AS MH_40,
	(CASE WHEN SUBSTR(ref_equip_type.nominal_length,-2) = '45' AND (SUBSTR(ref_equip_type.nominal_height,-2))='96' AND freight_kind ='MTY' AND ref_equip_type.iso_group NOT IN ('RS','RT','RE','UT','TN','TD','TG','PF','PC')  THEN 1 
	ELSE NULL END) AS MH_45,
	
	(CASE WHEN SUBSTR(ref_equip_type.nominal_length,-2) = '20' AND (SUBSTR(ref_equip_type.nominal_height,-2))='86' AND freight_kind ='MTY' AND ref_equip_type.iso_group IN ('RS','RT','RE')  THEN 1 
	ELSE NULL END) AS MR_20,
	(CASE WHEN SUBSTR(ref_equip_type.nominal_length,-2) = '40' AND freight_kind ='MTY' AND ref_equip_type.iso_group IN ('RS','RT','RE')  THEN 1 
	ELSE NULL END) AS MRH_40,
	
	(CASE WHEN SUBSTR(ref_equip_type.nominal_length,-2) = '20' AND (SUBSTR(ref_equip_type.nominal_height,-2))='86' AND freight_kind ='MTY' AND ref_equip_type.iso_group IN ('UT')  THEN 1 
	ELSE NULL END) AS MOT_20,
	(CASE WHEN SUBSTR(ref_equip_type.nominal_length,-2) = '40' AND freight_kind ='MTY' AND ref_equip_type.iso_group IN ('UT')  THEN 1 
	ELSE NULL END) AS MOT_40,
	
	(CASE WHEN SUBSTR(ref_equip_type.nominal_length,-2) = '20' AND (SUBSTR(ref_equip_type.nominal_height,-2))='86' AND freight_kind ='MTY' AND ref_equip_type.iso_group IN ('PF','PC')  THEN 1 
	ELSE NULL END) AS MFR_20,
	(CASE WHEN SUBSTR(ref_equip_type.nominal_length,-2) = '40' AND freight_kind ='MTY' AND ref_equip_type.iso_group IN ('PF','PC')  THEN 1 
	ELSE NULL END) AS MFR_40,
	
	(CASE WHEN SUBSTR(ref_equip_type.nominal_length,-2) = '20' AND (SUBSTR(ref_equip_type.nominal_height,-2))='86' AND freight_kind ='MTY' AND ref_equip_type.iso_group IN ('TN','TD','TG')  THEN 1 
	ELSE NULL END) AS MTK_20,
	(CASE WHEN SUBSTR(ref_equip_type.nominal_length,-2) IN('20','40','45','42')  THEN 1 ELSE NULL END) AS grand_tot,
	(CASE WHEN SUBSTR(ref_equip_type.nominal_length,-2)=20  THEN 1 ELSE 2 END) AS tues 
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
	WHERE vsl_vessel_visit_details.vvd_gkey='$vvdGkey' 
	)
	GROUP BY gkey,mlo,mlo_name";

	$query = oci_parse($con_sparcsn4_oracle, $query);
	oci_execute($query);

	
	$i=0;
	$j=0;
	
	$mlo="";
	
	while(($row1=oci_fetch_object($query)) !=false){

	// }

	// while($row=mysqli_fetch_object($query)){
	$i++;
	
		
	
?>
<tr align="center">
		<!-- <td></td> -->
		<td><?php if($row1->MLO_NAME) echo $row1->MLO_NAME; else echo "&nbsp;";?></td>
		<td><?php if($row1->MLO) echo $row1->MLO; else echo "&nbsp;";?></td>
		<td><?php if($row1->D_20) echo $row1->D_20; else echo "&nbsp;";?></td>
		<td><?php if($row1->D_40) echo $row1->D_40; else echo "&nbsp;";?></td>
		<td><?php if($row1->H_40) echo $row1->H_40; else echo "&nbsp;";?></td>
		<td><?php if($row1->H_45) echo $row1->H_45; else echo "&nbsp;";?></td>
		
		<td><?php if($row1->RH_40) echo $row1->RH_40; else echo "&nbsp;";?></td>
		<td><?php if($row1->R_20) echo $row1->R_20; else echo "&nbsp;";?></td>
		
		
		<td><?php if($row1->OT_20) echo $row1->OT_20; else echo "&nbsp;";?></td>
		<td><?php if($row1->FR_20) echo $row1->FR_20; else echo "&nbsp;";?></td>
		<td><?php if($row1->TK_20) echo $row1->TK_20; else echo "&nbsp;";?></td>
		
		<td><?php if($row1->FR_40) echo $row1->FR_40; else echo "&nbsp;";?></td>
		<td><?php if($row1->OT_40) echo $row1->OT_40; else echo "&nbsp;";?></td>
		
		<td><?php if($row1->MD_20) echo $row1->MD_20; else echo "&nbsp;";?></td>
		<td><?php if($row1->MD_40) echo $row1->MD_40; else echo "&nbsp;";?></td>
		<td><?php if($row1->MH_40) echo $row1->MH_40; else echo "&nbsp;";?></td>
		<td><?php if($row1->MH_45) echo $row1->MH_45; else echo "&nbsp;";?></td>
		
		<td><?php if($row1->MRH_40) echo $row1->MRH_40; else echo "&nbsp;";?></td>
		<td><?php if($row1->MR_20) echo $row1->MR_20; else echo "&nbsp;";?></td>		
		
		
		<td><?php if($row1->MOT_20) echo $row1->MOT_20; else echo "&nbsp;";?></td>
		<td><?php if($row1->MFR_20) echo $row1->MFR_20; else echo "&nbsp;";?></td>
		<td><?php if($row1->MTK_20) echo $row1->MTK_20; else echo "&nbsp;";?></td>
		
		<td><?php if($row1->MFR_40) echo $row1->MFR_40; else echo "&nbsp;";?></td>
		<td><?php if($row1->MOT_40) echo $row1->MOT_40; else echo "&nbsp;";?></td>
		
		<td><?php if($row1->GRAND_TOT) echo $row1->GRAND_TOT; else echo "&nbsp;";?></td>
		<td><?php if($row1->TUES) echo $row1->TUES; else echo "&nbsp;";?></td>
	</tr>

<?php } ?>

</table>
<?php 
oci_close($con_sparcsn4_oracle);

//if($_POST['options']=='html'){?>	
	</BODY>
</HTML>
<?php //}?>
