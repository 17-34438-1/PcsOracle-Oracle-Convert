
<?php $ddl_imp_rot_no=$_REQUEST['ddl_imp_rot_no']; 
	include("dbOracleConnection.php");
	include("dbConection.php");
	 $con=mysqli_connect("10.1.1.21", "sparcsn4","sparcsn4")or die("cannot connect"); 
	 mysqli_select_db($con,"sparcsn4")or die("cannot select DB");








	$sql="select vvd_gkey from vsl_vessel_visit_details where ib_vyg='$ddl_imp_rot_no'";

	$sqlVvvGkeyRes=oci_parse($con_sparcsn4_oracle,$sql);
	oci_execute($sqlVvvGkeyRes);
	
	$vvdGkey = "";
	$cond = "";
	
	while(($row = oci_fetch_object($sqlVvvGkeyRes)) != false)
	{
		$vvdGkey = $row->VVD_GKEY;
	}

	// print_r($vvdGkey) ;

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
	$berth_op="";

	while(($row1=oci_fetch_object($resSql1)) !=false)
	{
		$vsl_name = $row1->NAME;
		$ata = $row1->ATA ;
		$atd = $row1->ATD ;
		$berth_op=$row1->BERTHOP;
		$berth=$row1->BERTH;
	}

	?>
<html>
<title>MLO WISE LOADED CONTAINER LIST</title>
<body>
<table width="100%" border ='1' cellpadding='0' cellspacing='0'>
	
		<tr>
			<td colspan="12" align="center"><img width="250px" height="80px" src="<?php echo ASSETS_WEB_PATH?>fimg/cpanew.jpg"></td>
		</tr>
		<tr bgcolor="#ffffff" align="center" height="100px">
		<td colspan="14" align="center"><font size="5"><b>MLO WISE LOADED CONTAINER LIST FOR <?php echo $ddl_imp_rot_no; ?></b></font></td>
		
	</tr>
	<tr bgcolor="#ffffff" align="center" height="25px">
		<td colspan="2" align="center"><b>Vessel Name:</b></font></td>
		<td colspan="2" align="center"><b><?php echo $vsl_name; ?></b></font></td>
		<td colspan="2" align="center"><b>Berth: </b></font></td>
		<td colspan="2" align="center"><b> <?php echo $berth; ?></b></font></td>
		<td colspan="2" align="center"><b>Berth Operator:</b></font></td>
		<td colspan="4" align="center"><b><?php echo $berth_op; ?></b></font></td>
		
	</tr>
	<tr bgcolor="#ffffff" align="center" height="25px">
		<td colspan="15" align="center"></td>
		
	</tr>
	

	<tr bgcolor="#aaffff" align="center">
		<td><b>S/L</b></td>
		<td><b>Container</b></td>
		<td><b>Size</b></td>
		<td><b>Height</b></td>
		<td><b>Freight Kind</b></td>
		
		
		<td><b>Position</b></td>
		<td><b>Seal No</b></td>
		<td><b>Weight</b></td>
		<td><b>Port of Discharge</b></td>
		<td><b>Trailer No</b></td>
		<td><b>Crain Id</b></td>
	</tr>
	<tr>
		<td colspan="11">&nbsp; </td>
	</tr>

<?php
	
	//echo "select id,size,height,freight_kind,mlo,mlo_name,stowage_pos,pod,seal,mis_exp_unit.goods_and_ctr_wt_kg as goods_and_ctr_wt_kg,truck_no,craine_id from ctmsmis.mis_exp_unit inner join ctmsmis.mis_inv_unit on mis_inv_unit.gkey=mis_exp_unit.gkey where mis_exp_unit.vvd_gkey=$vvdGkey";

// $query="select id,size,height,freight_kind,mlo,mlo_name,stowage_pos,pod,seal_no,
// mis_exp_unit.goods_and_ctr_wt_kg as goods_and_ctr_wt_kg,truck_no,craine_id 
// from ctmsmis.mis_exp_unit
//  inner join ctmsmis.mis_inv_unit on mis_inv_unit.gkey=mis_exp_unit.gkey
//  where mis_exp_unit.vvd_gkey='$vvdGkey' AND mis_exp_unit.preAddStat='0' and snx_type=0 ORDER BY freight_kind ";





// $query="SELECT mis_exp_unit.vvd_gkey,cont_id,cont_size,cont_height,cont_mlo,stowage_pos,
// pod,seal_no,mis_exp_unit.goods_and_ctr_wt_kg AS goods_and_ctr_wt_kg,truck_no,craine_id 
// FROM ctmsmis.mis_exp_unit 
// WHERE mis_exp_unit.vvd_gkey='$vvdGkey' AND preAddStat='0' AND snx_type=0 ORDER BY pod,cont_mlo

// ";


/*$query=mysql_query("select mis_inv_unit.id,size,height,freight_kind,mlo,mlo_name,stowage_pos,pod,seal_no,mis_exp_unit.goods_and_ctr_wt_kg as goods_and_ctr_wt_kg,truck_no,craine_id,vsl_name,ifnull(sparcsn4.vsl_vessel_visit_details.flex_string02,ifnull(sparcsn4.vsl_vessel_visit_details.flex_string03,'')) as berth_op,ifnull(sparcsn4.argo_quay.id,'') as berth from ctmsmis.mis_exp_unit 
inner join ctmsmis.mis_inv_unit on mis_inv_unit.gkey=mis_exp_unit.gkey 
inner join sparcsn4.vsl_vessel_visit_details on sparcsn4.vsl_vessel_visit_details.vvd_gkey=mis_exp_unit.vvd_gkey
inner join sparcsn4.vsl_vessel_berthings on sparcsn4.vsl_vessel_berthings.vvd_gkey=sparcsn4.vsl_vessel_visit_details.vvd_gkey
inner join sparcsn4.argo_quay on sparcsn4.argo_quay.gkey=sparcsn4.vsl_vessel_berthings.quay
where mis_exp_unit.vvd_gkey='$vvdGkey' order by mlo,id");*/


//echo "select id,size,height,freight_kind,mlo,mlo_name,stowage_pos,pod,seal_no,mis_exp_unit.goods_and_ctr_wt_kg as goods_and_ctr_wt_kg,truck_no,craine_id from ctmsmis.mis_exp_unit inner join ctmsmis.mis_inv_unit on mis_inv_unit.gkey=mis_exp_unit.gkey where mis_exp_unit.vvd_gkey='$vvdGkey' order by mlo,id";
	
	

$query="
	SELECT inv_unit.gkey,inv_unit.id,inv_unit.projected_pod_gkey,inv_unit_fcy_visit.transit_state,SUBSTR(ref_equip_type.nominal_length,-2, LENGTH( ref_equip_type.nominal_length)) AS cont_size, SUBSTR(ref_equip_type.nominal_height, -2, LENGTH( ref_equip_type.nominal_height)) AS height,
	ref_bizunit_scoped.name AS mlo_name,inv_unit.seal_nbr1 AS seal_no,vsl_vessel_visit_details.vvd_gkey,
	ref_equip_type.id AS iso,ref_bizunit_scoped.id AS mlo,inv_unit.freight_kind,inv_unit.goods_and_ctr_wt_kg,
	ref_commodity.short_name AS commodity,inv_unit.remark as remarks, inv_unit_fcy_visit.ARRIVE_POS_SLOT as stowage_pos, inv_unit_fcy_visit.LAST_POS_NAME,
	argo_carrier_visit.id AS truck_no,
	REF_ROUTING_POINT.ID as pod, inv_unit_fcy_visit.LAST_POS_LOCTYPE AS coming_from
	
	FROM inv_unit
	INNER JOIN inv_unit_fcy_visit ON inv_unit_fcy_visit.unit_gkey=inv_unit.gkey
	INNER JOIN argo_carrier_visit ON argo_carrier_visit.gkey=inv_unit_fcy_visit.actual_ob_cv 
	INNER JOIN vsl_vessel_visit_details ON vsl_vessel_visit_details.vvd_gkey=argo_carrier_visit.cvcvd_gkey 
	INNER JOIN vsl_vessels ON vsl_vessels.gkey=vsl_vessel_visit_details.vessel_gkey 
	INNER JOIN ref_bizunit_scoped  ON inv_unit.line_op = ref_bizunit_scoped.gkey 
	INNER JOIN REF_ROUTING_POINT ON INV_UNIT.POD1_GKEY = REF_ROUTING_POINT.GKEY
	
	LEFT JOIN inv_goods ON inv_goods.gkey=inv_unit.goods 
	LEFT JOIN ref_commodity ON ref_commodity.gkey=inv_goods.commodity_gkey 
	INNER JOIN ref_equipment ON ref_equipment.gkey=INV_UNIT.eq_gkey
	INNER JOIN ref_equip_type ON ref_equip_type.gkey=ref_equipment.eqtyp_gkey
	INNER JOIN ref_equip_type ON ref_equip_type.gkey=ref_equipment.eqtyp_gkey 
	WHERE vsl_vessel_visit_details.vvd_gkey='$vvdGkey'";



	$query = oci_parse($con_sparcsn4_oracle,$query);
	oci_execute($query);
	$i=0;
	$j=0;
	
	$pod="";
	$freight_kind=0;
	while(($row=oci_fetch_object($query)) !=false)	
	{

	// $query=mysqli_query($con,$query);
	// $i=0;
	// $j=0;
	
	// $gKey=0;
	// while(@$row=mysqli_fetch_object($query)){

	$i++;
	
	// $gKey=$row->vvd_gkey;
	// $sqlQuery="SELECT substr(ref_equip_type.nominal_length,-2) as siz,substr(ref_equip_type.nominal_height,-2) as height,freight_kind
	// FROM inv_unit
	// INNER JOIN inv_unit_fcy_visit ON inv_unit_fcy_visit.unit_gkey=inv_unit.gkey
	// INNER JOIN argo_carrier_visit ON argo_carrier_visit.gkey=inv_unit_fcy_visit.actual_ob_cv
	// INNER JOIN argo_visit_details ON argo_visit_details.gkey=argo_carrier_visit.cvcvd_gkey 
	// INNER JOIN vsl_vessel_visit_details ON vsl_vessel_visit_details.vvd_gkey=argo_visit_details.gkey 
	//    INNER JOIN ref_equipment ON ref_equipment.gkey=inv_unit.eq_gkey
	// 			 INNER JOIN ref_equip_type ON ref_equipment.eqtyp_gkey=ref_equip_type.gkey 
	// 	WHERE  inv_unit.gkey='$gKey'";
	// $sqlQueryRes = oci_parse($con_sparcsn4_oracle,$sqlQuery);
	// oci_execute($sqlQueryRes);


	// $size=0;
	// $height=0;
	// $freight_kind=0;

	// while(($row2=oci_fetch_object($sqlQueryRes)) !=false)
	// {
	// 	$size = $row2->SIZ;
	// 	$height = $row2->HEIGHT;
	// 	$freight_kind = $row2->FREIGHT_KIND;


	
	// }



	if($freight_kind!=$row->FREIGHT_KIND){
		if($j>0){
		?>		
		<tr bgcolor="#aaffff" valign="center"><td colspan="2"><font size="4"><b>&nbsp;&nbsp;Total Container (<?php if($freight_kind=="FCL" or $freight_kind=="LCL") echo "LOAD"; else echo "EMPTY"; ?>):</b></font></td><td  colspan="15">&nbsp;&nbsp;<font size="4"><b><?php  echo $j;?></b></font></td></tr>
		<tr>
			<td colspan="11">&nbsp; </td>
		</tr>
		<?php
		}
		?>
		<tr bgcolor="#F0F4CA" valign="center"><td  colspan="15">&nbsp;&nbsp;<font size="4"><b><?php  if($row->FREIGHT_KIND=="FCL" or $row->FREIGHT_KIND=="LCL") echo "LOAD"; else echo "EMPTY";  ?></b></font></td></tr>
		
		<?php	
		$j=1;
		
	}else{
		$j++;
		
	}
	$freight_kind=$row->FREIGHT_KIND;
	
	
?>
<tr align="center">
		<td><?php if($row->ID) echo $j; else echo "&nbsp;";?></td>
		<td><?php if($row->ID) echo $row->ID; else echo "&nbsp;";?></td>
		<td><?php echo $row->CONT_SIZE;?></td>
		<td><?php echo $row->HEIGHT/10;?></td>
		<td><?php  echo $row->FREIGHT_KIND; ?></td>
		
		
		<td><?php if($row->STOWAGE_POS) echo $row->STOWAGE_POS; else echo "&nbsp;";?></td>
		<td><?php if($row->SEAL_NO) echo $row->SEAL_NO; else echo "&nbsp;";?></td>
		
		<td><?php if($row->GOODS_AND_CTR_WT_KG) echo $row->GOODS_AND_CTR_WT_KG; else echo "&nbsp;";?></td>
		<td><?php if($row->POD) echo $row->POD; else echo "&nbsp;";?></td>
		<td><?php if($row->TRUCK_NO) echo $row->TRUCK_NO; else echo "&nbsp;";?></td>
		<td><?php  echo "&nbsp;";?></td>
		
	</tr>
	

<?php } ?>

<tr bgcolor="#aaffff" valign="center"><td colspan="2"><font size="4"><b>&nbsp;&nbsp;Total Container (<?php if(@$row->FREIGHT_KIND=="FCL" or @$row->FREIGHT_KIND=="LCL") echo "LOAD"; else echo "EMPTY"; ?>):</b></font></td><td  colspan="15">&nbsp;&nbsp;<font size="4"><b><?php  echo $j;?></b></font></td></tr>
<tr bgcolor="#aaaaff" valign="center"><td colspan="2"><font size="4"><b>&nbsp;&nbsp;Grand Total:</b></font></td><td  colspan="15">&nbsp;&nbsp;<font size="4"><b><?php  echo $i;?></b></font></td></tr>
</table>
<br />
<br />
<?php
	$sqlSummery="		   
	SELECT 
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
	WHERE  vsl_vessel_visit_details.vvd_gkey=$vvdGkey  AND inv_unit.category='EXPRT' 
	AND inv_unit_fcy_visit.transit_state='S20_INBOUND' AND inv_unit_fcy_visit.time_in IS NULL
	)  tmp WHERE fcy_time_in IS NULL
	
	";


	$sqlSummery = oci_parse($con_sparcsn4_oracle,$sqlSummery);
	oci_execute($sqlSummery);

	$ONBOARD_LD_20="";
	$ONBOARD_LD_40=""; 
	$ONBOARD_MT_20="";
	$ONBOARD_MT_40="";
	$ONBOARD_LD_TUES="";
	$ONBOARD_MT_TUES="";
	while(($rowSummery=oci_fetch_object($sqlSummery)) !=false)	
	{

		$ONBOARD_LD_20=$rowSummery->ONBOARD_LD_20;
		$ONBOARD_LD_40=$rowSummery->ONBOARD_LD_40; 
		$ONBOARD_MT_20= $rowSummery->ONBOARD_MT_20;
		$ONBOARD_MT_40=$rowSummery->ONBOARD_MT_40;
		$ONBOARD_LD_TUES=$rowSummery->ONBOARD_LD_TUES;
		$ONBOARD_MT_TUES=$rowSummery->ONBOARD_MT_TUES;

	}


	$sqlSummery2="

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
?>
<table width="100%" border ='0' cellpadding='0' cellspacing='0'>
<tr><td colspan="12" align="center"><font size="4"><b><u>Export Summary Report</u></b></font></td></tr>
<tr><td colspan="12" align="center"><font size="4"><b>&nbsp;</b></font></td></tr>
</table>
<table width="100%" border ='1' cellpadding='0' cellspacing='0'>
	<tr>
		<td colspan="6" align="center">TOTAL ONBOARD</td>
		<td colspan="6" align="center">BALANCE TO LOAD</td>
	</tr>
	<tr>
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
	</tr>
	<tr>
	<td align="center"><?php  echo $ONBOARD_LD_20;  ?></td>
		<td align="center"><?php echo $ONBOARD_LD_40;?></td>
		<td align="center"><?php echo $ONBOARD_MT_20;  ?></td>
		<td align="center"><?php echo $ONBOARD_MT_40;  ?></td>
		<td align="center"><?php  echo $ONBOARD_LD_TUES;  ?></td>
		<td align="center"><?php  echo $ONBOARD_MT_TUES; ?></td>
	
	
		<td align="center"><?php echo $balance_LD_20;?></td>
		<td align="center"><?php  echo $balance_LD_40;  ?></td>
		<td align="center"><?php  echo $balance_MT_20; ?></td>
		<td align="center"><?php echo $balance_MT_40;?></td>
		<td align="center"><?php echo $balance_LD_tues;  ?></td>
		<td align="center"><?php echo $balance_MT_tues;  ?></td>
		
		

		
	</tr>
</table>

</body>
</html>