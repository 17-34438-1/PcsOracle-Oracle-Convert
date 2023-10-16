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
//$ddl_imp_rot_no=$_REQUEST['ddl_imp_rot_no']; 

	include("FrontEnd/dbConection.php");
	include("dbOracleConnection.php");	
	
	$sqlRes="select vvd_gkey from vsl_vessel_visit_details where ib_vyg='$ddl_imp_rot_no'";
	$sql=oci_parse($con_sparcsn4_oracle,$sqlRes);
	oci_execute($sql);

	$vvdGkey="";
	while(($row1=oci_fetch_object($sql)) !=false)
	{
		$vvdGkey=$row1->VVD_GKEY;
	
	}
	
	
	$sql="call ctmsmis.update_containers_by_vvd_gkey($vvdGkey)";
	$res=mysqli_query($con_sparcsn4,$sql);
	
	$sql1="select vsl_vessels.name as vsl_name,
	NVL(vsl_vessel_visit_details.flex_string02,NVL(vsl_vessel_visit_details.flex_string03,'')) as berth_op,
	NVL(argo_quay.id,'') as berth,argo_carrier_visit.ata,argo_carrier_visit.atd from vsl_vessel_visit_details
	inner join argo_carrier_visit on argo_carrier_visit.cvcvd_gkey=vsl_vessel_visit_details.vvd_gkey
	inner join vsl_vessel_berthings on vsl_vessel_berthings.vvd_gkey=vsl_vessel_visit_details.vvd_gkey
	inner join vsl_vessels on vsl_vessels.gkey=vsl_vessel_visit_details.vessel_gkey
	inner join argo_quay on argo_quay.gkey=vsl_vessel_berthings.quay
	where vsl_vessel_visit_details.vvd_gkey='$vvdGkey'";
	$sqlRes1=oci_parse($con_sparcsn4_oracle,$sql1);
	oci_execute($sqlRes1);

	$vsl_name = "";
	$ata = "";
	$atd = "";
	$berth="";

	while(($row2=oci_fetch_object($sqlRes1)) !=false)
	{
		$vsl_name = $row2->VSL_NAME;
		$ata = $row2->ATA ;

		$atd = $row2->ATD ;
		$berth=$row2->BERTH_OP;
	}

	
	?>
<html>
<title>Export Container Loading List</title>
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
					<tr>
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
					<td colspan="12"><font size="4"><b><u>Export Container Loading Report</u></b></font></td>
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
		<td style="border-width:3px;border-style: double;"><b>ISO Type</b></td>
		<td style="border-width:3px;border-style: double;"><b>Type</b></td>
		<td style="border-width:3px;border-style: double;"><b>MLO</b></td>
		<td style="border-width:3px;border-style: double;"><b>Status</b></td>		
		<td style="border-width:3px;border-style: double;"><b>Weight</b></td>
		<td style="border-width:3px;border-style: double;"><b>POD</b></td>
		<td style="border-width:3px;border-style: double;"><b>Stowage</b></td>
		<td style="border-width:3px;border-style: double;"><b>Loaded Time</b></td>
		<td style="border-width:3px;border-style: double;"><b>Coming From</b></td>
		<td style="border-width:3px;border-style: double;"><b>Commodity</b></td>
		<td style="border-width:3px;border-style: double;"><b>Remarks</b></td>
		<td style="border-width:3px;border-style: double;"><b>User Id</b></td>
		<?php 
		if($button_show==1){
		?>
			<td style="border-width:3px;border-style: double;"><b>Action</b></td>
		<?php } ?>
	</tr>

<?php
$cond="";
if($fromdate!="" and $todate!="")	
{
	if($fromTime=="" or $toTime==""){
		// $cond = " and date(mis_exp_unit.last_update) between '$fromdate' and '$todate'";
		$cond = " and to_char(inv_unit_fcy_visit.time_load,'yyyy-mm-dd') between '$fromdate' and '$todate'";

	}
	
	else
	{
		$frmDate = $fromdate." ".$fromTime.":00";
		$tDate = $todate." ".$toTime.":00";
		// $cond = " and mis_exp_unit.last_update between '$frmDate' and '$tDate'";
		$cond = " and to_char(inv_unit_fcy_visit.time_load,'yyyy-mm-dd') between '$frmDate' and '$tDate'";

	}
}	
	//echo$vvdGkey;
	$i=0;
	$j=0;
	
	$mlo="";
	// $strQuery = "SELECT ctmsmis.mis_exp_unit.gkey,CONCAT(SUBSTRING(ctmsmis.mis_exp_unit.cont_id,1,4),' ',SUBSTRING(ctmsmis.mis_exp_unit.cont_id,5)) AS id,mis_exp_unit.isoType AS iso,
	// (CASE 
	// WHEN mis_exp_unit.cont_size= '20' AND mis_exp_unit.cont_height = '86' AND mis_exp_unit.isoGroup NOT IN ('RS','RT','RE','UT','TN','TD','TG','PF','PC') THEN '2D'
	// WHEN mis_exp_unit.cont_size = '40' AND mis_exp_unit.cont_height='86' AND mis_exp_unit.isoGroup NOT IN ('RS','RT','RE','UT','TN','TD','TG','PF','PC') THEN '4D'
	// WHEN mis_exp_unit.cont_size = '40' AND mis_exp_unit.cont_height='96' AND mis_exp_unit.isoGroup NOT IN ('RS','RT','RE','UT','TN','TD','TG','PF','PC') THEN '4H'
	// WHEN mis_exp_unit.cont_size = '45' AND mis_exp_unit.cont_height='96' AND mis_exp_unit.isoGroup NOT IN ('RS','RT','RE','UT','TN','TD','TG','PF','PC') THEN '45H'
	// WHEN mis_exp_unit.cont_size = '20' AND mis_exp_unit.cont_height='86' AND mis_exp_unit.isoGroup IN ('RS','RT','RE') THEN '2RF'
	// WHEN mis_exp_unit.cont_size = '40' AND mis_exp_unit.isoGroup IN ('RS','RT','RE') THEN '4RH'
	// WHEN mis_exp_unit.cont_size = '20' AND mis_exp_unit.cont_height='86' AND mis_exp_unit.isoGroup IN ('UT') THEN '2OT'
	// WHEN mis_exp_unit.cont_size = '40' AND mis_exp_unit.isoGroup IN ('UT') THEN '4OT'
	// WHEN mis_exp_unit.cont_size = '20' AND mis_exp_unit.cont_height='86' AND mis_exp_unit.isoGroup IN ('PF','PC') THEN '2FR'
	// WHEN mis_exp_unit.cont_size = '40' AND mis_exp_unit.isoGroup IN ('PF','PC') THEN '4FR'
	// WHEN mis_exp_unit.cont_size = '20' AND mis_exp_unit.cont_height='86' AND mis_exp_unit.isoGroup IN ('TN','TD','TG') THEN '2TK'
	// ELSE ''
	// END
	// ) AS TYPE,
	// mis_exp_unit.cont_mlo AS mlo,
	// cont_status AS freight_kind,ctmsmis.mis_exp_unit.goods_and_ctr_wt_kg AS weight,ctmsmis.mis_exp_unit.coming_from AS coming_from,
	// ctmsmis.mis_exp_unit.pod,ctmsmis.mis_exp_unit.stowage_pos,ctmsmis.mis_exp_unit.last_update,ctmsmis.mis_exp_unit.user_id
	// FROM ctmsmis.mis_exp_unit 
	// WHERE mis_exp_unit.vvd_gkey='$vvdGkey' AND mis_exp_unit.preAddStat='0' AND mis_exp_unit.delete_flag='0' AND snx_type=0  ";
	


	$strQuery = "SELECT inv_unit.gkey,inv_unit.id AS cont_id,ref_equip_type.id AS iso,
	(CASE 
	WHEN substr(ref_equip_type.nominal_length,-2)= '20' AND substr(ref_equip_type.nominal_height,-2) = '86' AND ref_equip_type.id NOT IN ('RS','RT','RE','UT','TN','TD','TG','PF','PC') THEN '2D'
	WHEN substr(ref_equip_type.nominal_length,-2) = '40' AND substr(ref_equip_type.nominal_height,-2)='86' AND ref_equip_type.id NOT IN ('RS','RT','RE','UT','TN','TD','TG','PF','PC') THEN '4D'
	WHEN substr(ref_equip_type.nominal_length,-2) = '40' AND substr(ref_equip_type.nominal_height,-2)='96' AND ref_equip_type.id NOT IN ('RS','RT','RE','UT','TN','TD','TG','PF','PC') THEN '4H'
	WHEN substr(ref_equip_type.nominal_length,-2) = '45' AND substr(ref_equip_type.nominal_height,-2)='96' AND ref_equip_type.id NOT IN ('RS','RT','RE','UT','TN','TD','TG','PF','PC') THEN '45H'
	WHEN substr(ref_equip_type.nominal_length,-2) = '20' AND substr(ref_equip_type.nominal_height,-2)='86' AND ref_equip_type.id IN ('RS','RT','RE') THEN '2RF'
	WHEN substr(ref_equip_type.nominal_length,-2) = '40' AND ref_equip_type.id IN ('RS','RT','RE') THEN '4RH'
	WHEN substr(ref_equip_type.nominal_length,-2) = '20' AND substr(ref_equip_type.nominal_height,-2)='86' AND ref_equip_type.id IN ('UT') THEN '2OT'
	WHEN substr(ref_equip_type.nominal_length,-2) = '40' AND ref_equip_type.id IN ('UT') THEN '4OT'
	WHEN substr(ref_equip_type.nominal_length,-2) = '20' AND substr(ref_equip_type.nominal_height,-2)='86' AND ref_equip_type.id IN ('PF','PC') THEN '2FR'
	WHEN substr(ref_equip_type.nominal_length,-2) = '40' AND ref_equip_type.id IN ('PF','PC') THEN '4FR'
	WHEN substr(ref_equip_type.nominal_length,-2) = '20' AND substr(ref_equip_type.nominal_height,-2)='86' AND ref_equip_type.id IN ('TN','TD','TG') THEN '2TK'
	ELSE ''
	END
	) AS TYPE,
	vsl_vessel_visit_details.ib_vyg,g.id AS mlo, inv_unit_fcy_visit.ARRIVE_POS_SLOT as stowage_pos, 
	inv_unit.freight_kind,inv_unit.goods_and_ctr_wt_kg AS weight,REF_ROUTING_POINT.ID as pod,ref_commodity.short_name,
	(select ref_bizunit_scoped.id from ref_bizunit_scoped
	inner join road_trucks on road_trucks.trkco_gkey=ref_bizunit_scoped.gkey
	inner join ROAD_TRUCK_VISIT_DETAILS on ROAD_TRUCK_VISIT_DETAILS.truck_gkey=road_trucks.gkey
	inner join road_truck_transactions on road_truck_transactions.truck_visit_gkey=road_truck_visit_details.tvdtls_gkey
	where road_truck_transactions.unit_gkey=inv_unit.gkey fetch first 1 rows only) AS coming_from,
	
	vsl_vessel_visit_details.vvd_gkey,
	(select srv_event.placed_time from srv_event where srv_event.applied_to_gkey=inv_unit.gkey and event_type_gkey IN(31488) 
	ORDER BY srv_event.gkey DESC fetch first 1 rows only) as last_update 
	FROM  inv_unit
	inner join inv_unit_fcy_visit on inv_unit_fcy_visit.unit_gkey=inv_unit.gkey
	INNER JOIN ( ref_bizunit_scoped g LEFT JOIN ( ref_agent_representation X LEFT JOIN ref_bizunit_scoped Y ON X.agent_gkey=Y.gkey ) ON g.gkey=X.bzu_gkey ) ON g.gkey = inv_unit.line_op
	inner join argo_carrier_visit on argo_carrier_visit.gkey=inv_unit_fcy_visit.actual_ob_cv
	inner join vsl_vessel_visit_details on vsl_vessel_visit_details.vvd_gkey=argo_carrier_visit.cvcvd_gkey
	INNER JOIN vsl_vessels ON vsl_vessels.gkey=vsl_vessel_visit_details.vessel_gkey 
	LEFT JOIN inv_goods ON inv_goods.gkey=inv_unit.goods
    LEFT JOIN ref_commodity ON ref_commodity.gkey=inv_goods.commodity_gkey
	INNER JOIN ref_equipment ON ref_equipment.gkey=inv_unit.eq_gkey
	INNER JOIN ref_equip_type ON ref_equip_type.gkey=ref_equipment.eqtyp_gkey
	INNER JOIN ref_bizunit_scoped  ON inv_unit.line_op = ref_bizunit_scoped.gkey 
	INNER JOIN REF_ROUTING_POINT ON INV_UNIT.POD1_GKEY = REF_ROUTING_POINT.GKEY
	WHERE vsl_vessel_visit_details.vvd_gkey='$vvdGkey'";
	
	
	$sqlQueryRes = oci_parse($con_sparcsn4_oracle,$strQuery);
	oci_execute($sqlQueryRes);


	// $query=mysqli_query($con_sparcsn4,$strQuery);
	// //$query=mysql_query($strQuery);
	// while($row=mysqli_fetch_object($query)){
	
	// $gKey=$row->gkey;
	// $sqlQuery="SELECT * FROM inv_unit 
	// LEFT JOIN inv_goods ON inv_goods.gkey=inv_unit.goods
	// LEFT JOIN ref_commodity ON ref_commodity.gkey=inv_goods.commodity_gkey
	// WHERE  inv_unit.gkey='$gKey'";
	// $sqlQueryRes = oci_parse($con_sparcsn4_oracle,$sqlQuery);
	// oci_execute($sqlQueryRes);
	// $short_name="";

	while(($row=oci_fetch_object($sqlQueryRes)) !=false)
	{
	




	$i++;
	
		
	
?>
	<tr align="center">
		<td><?php  echo $i;?></td>
		<td><?php if($row->ID) echo $row->ID; else echo "&nbsp;";?></td>
		<td><?php if($row->ISO) echo $row->ISO; else echo "&nbsp;";?></td>
		<td><?php if($row->TYPE) echo $row->TYPE; else echo "&nbsp;";?></td>
		<td><?php if($row->MLO) echo $row->MLO; else echo "&nbsp;";?></td>
		<td><?php if($row->FREIGHT_KIND) echo $row->FREIGHT_KIND; else echo "&nbsp;";?></td>
		<td><?php if($row->WEIGHT) echo $row->WEIGHT; else echo "&nbsp;";?></td>
		
		
		<td><?php if($row->POD) echo $row->POD; else echo "&nbsp;";?></td>
		<td><?php if($row->STOWAGE_POS) echo $row->STOWAGE_POS; else echo "&nbsp;";?></td>
		<td><?php echo "&nbsp;";?></td>
		
		<td><?php if($row->COMING_FROM) echo $row->COMING_FROM; else echo "&nbsp;";?></td>
		<td><?php if($row->SHORT_NAME) echo $row->SHORT_NAME; else echo "&nbsp;";?></td>
		<td><?php echo "&nbsp;";?></td>
		
		
	
				
	</tr>

<?php 
} ?>
</table>





<br />
<br />
<?php
$cond="";
if($fromdate!="" and $todate!="")	
{
	if($fromTime=="" or $toTime==""){
		//$cond = " and date(mis_exp_unit.last_update) between '$fromdate' and '$todate'";
		$cond = " and to_char(inv_unit_fcy_visit.time_load,'yyyy-mm-dd') between '$fromdate' and '$todate'";
	}
		

	else
	{
		$frmDate = $fromdate." ".$fromTime.":00";
		$tDate = $todate." ".$toTime.":00";
		//$cond = " and mis_exp_unit.last_update between '$frmDate' and '$tDate'";
		$cond = " and to_char(inv_unit_fcy_visit.time_load,'yyyy-mm-dd') between '$frmDate' and '$tDate'";

	}
}


//  $onboard_LD_20=0;
//  $onboard_LD_40=0;
//  $onboard_MT_20=0;
//  $onboard_MT_40=0;
//  $onboard_LD_tues=0;
//  $onboard_MT_tues=0;

// $sqlSummery="SELECT DISTINCT ctmsmis.mis_exp_unit.gkey AS gkey, mis_exp_unit.cont_size
// FROM ctmsmis.mis_exp_unit
// WHERE  mis_exp_unit.vvd_gkey='$vvdGkey' AND mis_exp_unit.preAddStat='0' AND snx=0";
// $sqlSummery=mysqli_query($con_sparcsn4,$sqlSummery);
// $rowSummery=mysqli_fetch_object($sqlSummery);


// $gKey1="";
// $contSize="";
// $gKey1=$rowSummery->gkey;
// $contSize=$rowSummery->cont_size;

// $sqlQuery1="SELECT * FROM  inv_unit WHERE inv_unit.gkey='$gKey1'";
// $sqlQueryRes1 = oci_parse($con_sparcsn4_oracle,$sqlQuery1);
// oci_execute($sqlQueryRes1);
// $result1=array();
// $numrows1 =oci_fetch_all($sqlQueryRes1, $result1, null, null, OCI_FETCHSTATEMENT_BY_ROW);
// oci_free_statement($sqlQueryRes1);
// $sqlQueryRes1 = oci_parse($con_sparcsn4_oracle,$sqlQuery);
// oci_execute($sqlQueryRes1);	
// $sqlQueryRow1 = oci_fetch_object($sqlQueryRes1);
// $freight_kind="";
// $freight_kind=$sqlQueryRow1->FREIGHT_KIND;

// if($contSize=20 && ($freight_kind=='FCL' || $freight_kind=='LCL')){
// 	$onboard_LD_20=$onboard_LD_20+1;
// }
// else{
// 	$onboard_LD_20=$onboard_LD_20+0;
// }
// if($contSize=20 && ($freight_kind=='FCL' || $freight_kind=='LCL')){
// 	$onboard_LD_40=$onboard_LD_40+1;
// }
// else{
// 	$onboard_LD_40=$onboard_LD_40+0;
// }

// if($contSize=20 && $freight_kind=='MTY' ){
// 	$onboard_MT_20=$onboard_MT_20+1;
// }
// else{
// 	$onboard_MT_20=$onboard_MT_20+0;
// }
// if($contSize=20 && $freight_kind=='MTY' ){
// 	$onboard_MT_40=$onboard_MT_40+1;
// }
// else{
// 	$onboard_MT_40=$onboard_MT_40+0;
// }

// if($contSize=20 && ($freight_kind=='FCL' || $freight_kind=='LCL')){
// 	$onboard_LD_tues=$onboard_LD_tues+1;
// }
// else if($contSize>20 && ($freight_kind=='FCL' || $freight_kind=='LCL')){
// 	$onboard_LD_tues=$onboard_LD_tues+2;
// }
// else{
// 	$onboard_LD_tues=$onboard_LD_tues+0;

// }

// if($contSize=20 && ($freight_kind=='FCL' || $freight_kind=='LCL')){
// 	$onboard_MT_tues=$onboard_MT_tues+1;
// }
// else if($contSize>20 && ($freight_kind=='FCL' || $freight_kind=='LCL')){
// 	$onboard_MT_tues=$onboard_MT_tues+2;
// }
// else{
// 	$onboard_MT_tues=$onboard_MT_tues+0;

// }


// $sqlSummery2="select
// NVL(SUM(balance_LD_20),0) AS balance_LD_20,
// NVL(SUM(balance_LD_40),0) AS balance_LD_40,
// NVL(SUM(balance_MT_20),0) AS balance_MT_20,
// NVL(SUM(balance_MT_40),0) AS balance_MT_40,
// NVL(SUM(balance_LD_tues),0) AS balance_LD_tues,
// NVL(SUM(balance_MT_tues),0) AS balance_MT_tues

//  from (
// select distinct inv_unit.gkey as gkey,
// (CASE WHEN substr(ref_equip_type.nominal_length,-2) = '20' AND freight_kind in ('FCL','LCL')  THEN 1  
// ELSE NULL END) AS balance_LD_20, 
// (CASE WHEN substr(ref_equip_type.nominal_length,-2) > '20' AND freight_kind in ('FCL','LCL')  THEN 1  
// ELSE NULL END) AS balance_LD_40,
// (CASE WHEN substr(ref_equip_type.nominal_length,-2) = '20' AND freight_kind ='MTY'  THEN 1  
// ELSE NULL END) AS balance_MT_20, 
// (CASE WHEN substr(ref_equip_type.nominal_length,-2) > '20' AND freight_kind ='MTY'  THEN 1  
// ELSE NULL END) AS balance_MT_40, 
// (CASE WHEN substr(ref_equip_type.nominal_length,-2)=20 AND freight_kind in ('FCL','LCL') THEN 1 
// ELSE (CASE WHEN substr(ref_equip_type.nominal_length,-2)>20 AND freight_kind in ('FCL','LCL') THEN 2 ELSE NULL END) END) AS balance_LD_tues, 
// (CASE WHEN substr(ref_equip_type.nominal_length,-2)=20 AND freight_kind='MTY' THEN 1 
// ELSE (CASE WHEN substr(ref_equip_type.nominal_length,-2)>20 AND freight_kind='MTY' THEN 2 ELSE NULL END) END) AS balance_MT_tues

// FROM inv_unit
// inner join inv_unit_fcy_visit on inv_unit_fcy_visit.unit_gkey=inv_unit.gkey
// inner join argo_carrier_visit on argo_carrier_visit.gkey=inv_unit_fcy_visit.actual_ob_cv
// inner join ref_equipment on ref_equipment.gkey=inv_unit.eq_gkey
// inner join ref_equip_type on ref_equip_type.gkey=ref_equipment.eqtyp_gkey
// where  argo_carrier_visit.cvcvd_gkey='$vvdGkey' and category='EXPRT' and transit_state not in ('S60_LOADED','S70_DEPARTED','S99_RETIRED')
// )  tmp";
// $sqlSummery2Res=oci_parse($con_sparcsn4_oracle,$sqlSummery2);
// oci_execute($sqlSummery2Res);
// $rowSummery2=oci_fetch_object($sqlSummery2);







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
<tr><td colspan="12" align="center"><font size="4"><b><u>Export Summery Report</u></b></font></td></tr>
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
<?php 
//mysql_close($con_sparcsn4);
if($_POST['options']=='html'){?>	
	</BODY>
</HTML>
<?php }?>
