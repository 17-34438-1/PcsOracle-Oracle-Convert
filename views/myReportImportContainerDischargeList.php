<?php if($_POST['options']=='html'){?>
<HTML>
	<HEAD>
		<TITLE>Berth Operator</TITLE>
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
		header("Content-Disposition: attachment; filename=IMPORT_SUMMERY.xls;");
		header("Content-Type: application/ms-excel");
		header("Pragma: no-cache");
		header("Expires: 0");

	}
	?>	
	<?php 
	if($_POST['options']=='html') 
	{
	?>
	<?php 
	//include("FrontEnd/dbConection.php");
	include("dbOracleConnection.php");
	
	$sql="select vvd_gkey from vsl_vessel_visit_details where ib_vyg='$ddl_imp_rot_no'";
	//$sql=mysqli_query($con_sparcsn4,$sql);
	$vvdGkeyRes = oci_parse($con_sparcsn4_oracle,$sql);
	oci_execute($vvdGkeyRes);
	// $results=array();
	// $numrows =oci_fetch_all($vvdGkeyRes, $results, null, null, OCI_FETCHSTATEMENT_BY_ROW);
	// oci_free_statement($vvdGkeyRes);
	// $vvdGkeyRes=oci_parse($con_sparcsn4_oracle, $sql);
	// oci_execute($vvdGkeyRes);
	$vvdGkey = "";
	$cond = "";
	while (($row = oci_fetch_object($vvdGkeyRes)) != false)
	{
		$vvdGkey = $row->VVD_GKEY;
	}

	
	


	if($numrows==1)
	{
	
		while (($row = oci_fetch_object($vvdGkeyRes)) != false)
		{
			$vvdGkey = $row->VVD_GKEY;
		}
		
		$cond = "vsl_vessel_visit_details.vvd_gkey='$vvdGkey'";
	}


	else if($numrows > 1)
	{
		while (($row = oci_fetch_object($vvdGkeyRes)) != false)
		{
			
			$vvdGkey = $vvdGkey."','".$row->VVD_GKEY;
		}
		$vvdGkey = substr($vvdGkey,2);
		$vvdGkey = $vvdGkey."'";
		$cond = "vsl_vessel_visit_details.vvd_gkey='$vvdGkey'";
	}
	
	$sql1="select vsl_vessels.name as vsl_name,NVL(vsl_vessel_visit_details.flex_string02,
	NVL(vsl_vessel_visit_details.flex_string03,'')) as berth_op,NVL(argo_quay.id,'') as berth,
	to_char(vsl_vessel_visit_details.published_eta,'yyyy-mm-dd') AS ata,
	argo_carrier_visit.atd from vsl_vessel_visit_details
	inner join argo_carrier_visit on argo_carrier_visit.cvcvd_gkey=vsl_vessel_visit_details.vvd_gkey
	inner join vsl_vessel_berthings on vsl_vessel_berthings.vvd_gkey=vsl_vessel_visit_details.vvd_gkey
	inner join vsl_vessels on vsl_vessels.gkey=vsl_vessel_visit_details.vessel_gkey
	inner join argo_quay on argo_quay.gkey=vsl_vessel_berthings.quay
	where ".$cond;
	
	//$sql1=mysqli_query($con_sparcsn4,$sql1);
	$sqlRes1=oci_parse($con_sparcsn4_oracle,$sql1);
	oci_execute($sqlRes1);
	$vsl_name = "";
	$ata = "";
	while(($row1=oci_fetch_object($sqlRes1))!=false)
	{
		$vsl_name = $row1->VSL_NAME;
		$ata = $row1->ATA;
	}
	
	?>
	
	
			<!-- start: page -->
			
			<section class="panel">
				<div class="panel-body">
					<div class="invoice">
						<header class="clearfix">
							<div class="row">
								<div class="col-sm-12 text-center mt-md mb-md">
									<div class="ib">
										<img src="<?php echo ASSETS_WEB_PATH;?>fimg/cpaLogo.png" alt="Chattogram Port Authority Logo"/><br>
										<h4 class="h4 mt-none mb-sm text-dark text-bold">Chattogram Port Authority</h4>
										<h5 align="center" class="h5 mt-none mb-sm text-dark text-bold">Import Container Discharging Report</h5>
									</div>
								</div>
							</div>
							<div class="row">
								<div class="col-sm-offset-2 col-sm-2 mt-md">
									<!--h4 class="h4 mt-none mb-sm text-dark text-bold"><?php  Echo $row1->VSL_NAME;?></h4-->
									<h4 class="h4 mt-none mb-sm text-dark text-bold"><?php  Echo $vsl_name;?></h4>
								</div>
								<div class="col-sm-2 mt-md">
									<h5 class="h5 mt-none mb-sm text-dark"><b>Voy:- <?php Echo  $voysNo;?></b></h5>
								</div>
								<div class="col-sm-2 mt-md">
									<h5 class="h5 mt-none mb-sm text-dark"><b>Imp.Rot No:- <?php  Echo $ddl_imp_rot_no;?></b></h5>
								</div>
								<div class="col-sm-2 mt-md">
									<!--h5 class="h5 mt-none mb-sm text-dark"><b>ETA :-  <?php  Echo $row1->ATA;?></b></h5-->
									<h5 class="h5 mt-none mb-sm text-dark"><b>ETA :-  <?php  Echo $ata;?></b></h5>
								</div>
							</div>
						</header>
						<div class="panel-body">
							<table class="table table-bordered table-responsive table-hover table-striped mb-none">
								<thead>
									<tr class="gridDark">
										<th class="text-center" colspan="13">IMPORT CONTAINER BALANCE ON BOARD LIST:</th>
									</tr>
									<tr class="gridDark">
										<th class="text-center">SlNo</th>
										<th class="text-center">Container No.</th>									
										<th class="text-center">Discharge Time.</th>									
										<th class="text-center">C/L Date</th>									
										<th class="text-center">Location</th>									
										<th class="text-center">Seal No</th>									
										<th class="text-center">Type</th>									
										<th class="text-center">MLO</th>									
										<th class="text-center">Status</th>									
										<th class="text-center">Weight</th>									
										<th class="text-center">IMCO</th>									
										<th class="text-center">Commodity</th>									
										<th class="text-center">Remarks</th>									
										<!--th class="text-center">User Id</th-->									
									</tr>
								</thead>
								<tbody>
									<?php
										$i=0;
										$j=0;
										$mlo="";
										/* $query="SELECT CONCAT(SUBSTRING(sparcsn4.inv_unit.id,1,4),' ',SUBSTRING(sparcsn4.inv_unit.id,5)) AS id,sparcsn4.inv_unit_fcy_visit.time_in AS fcy_time_in,
										sparcsn4.inv_unit_fcy_visit.last_pos_slot AS location,sparcsn4.inv_unit.seal_nbr1 AS sealno,
										sparcsn4.ref_equip_type.id AS iso,sparcsn4.ref_bizunit_scoped.id AS mlo,sparcsn4.inv_unit.freight_kind,sparcsn4.inv_unit.goods_and_ctr_wt_kg AS weight,
										ref_commodity.short_name,sparcsn4.inv_unit.remark
										FROM  sparcsn4.inv_unit 
										INNER JOIN sparcsn4.inv_unit_fcy_visit ON sparcsn4.inv_unit_fcy_visit.unit_gkey=sparcsn4.inv_unit.gkey
										INNER JOIN sparcsn4.argo_carrier_visit ON sparcsn4.argo_carrier_visit.gkey=sparcsn4.inv_unit_fcy_visit.actual_ib_cv
										INNER JOIN sparcsn4.vsl_vessel_visit_details ON sparcsn4.vsl_vessel_visit_details.vvd_gkey=sparcsn4.argo_carrier_visit.cvcvd_gkey
										LEFT JOIN sparcsn4.inv_goods ON sparcsn4.inv_goods.gkey=sparcsn4.inv_unit.goods
										LEFT JOIN sparcsn4.ref_commodity ON sparcsn4.ref_commodity.gkey=sparcsn4.inv_goods.commodity_gkey
										INNER JOIN sparcsn4.inv_unit_equip ON sparcsn4.inv_unit_equip.unit_gkey=sparcsn4.inv_unit.gkey
										INNER JOIN sparcsn4.ref_equipment ON sparcsn4.ref_equipment.gkey=sparcsn4.inv_unit_equip.eq_gkey
										INNER JOIN sparcsn4.ref_equip_type ON sparcsn4.ref_equip_type.gkey=sparcsn4.ref_equipment.eqtyp_gkey
										INNER JOIN sparcsn4.ref_bizunit_scoped ON sparcsn4.ref_bizunit_scoped.gkey=sparcsn4.inv_unit.line_op
										WHERE sparcsn4.vsl_vessel_visit_details.vvd_gkey='$vvdGkey' AND category='IMPRT' AND sparcsn4.inv_unit_fcy_visit.transit_state='S20_INBOUND'"; */
										
										
										$query="SELECT  CONCAT(CONCAT(substr(inv_unit.id,1,4),' '),substr(inv_unit.id,5)) AS id,inv_unit_fcy_visit.time_in AS fcy_time_in,
										inv_unit_fcy_visit.last_pos_slot AS location,inv_unit.seal_nbr1 AS sealno,
										ref_equip_type.id AS iso,ref_bizunit_scoped.id AS mlo,inv_unit.freight_kind,inv_unit.goods_and_ctr_wt_kg AS weight,
										ref_commodity.short_name,inv_unit.remark,
										(SELECT time_discharge_complete
										FROM vsl_vessel_visit_details
										INNER JOIN argo_visit_details ON argo_visit_details.gkey = vsl_vessel_visit_details.vvd_gkey
										WHERE ib_vyg=inv_unit_fcy_visit.flex_string10 FETCH FIRST 1 ROWS ONLY) AS cl_date
										FROM  inv_unit 
										INNER JOIN inv_unit_fcy_visit ON inv_unit_fcy_visit.unit_gkey=inv_unit.gkey
										INNER JOIN argo_carrier_visit ON argo_carrier_visit.gkey=inv_unit_fcy_visit.actual_ib_cv
										INNER JOIN vsl_vessel_visit_details ON vsl_vessel_visit_details.vvd_gkey=argo_carrier_visit.cvcvd_gkey
										LEFT JOIN inv_goods ON inv_goods.gkey=inv_unit.goods
										LEFT JOIN ref_commodity ON ref_commodity.gkey=inv_goods.commodity_gkey
										INNER JOIN ref_equipment ON ref_equipment.gkey=inv_unit.eq_gkey
										INNER JOIN ref_equip_type ON ref_equip_type.gkey=ref_equipment.eqtyp_gkey
										INNER JOIN ref_bizunit_scoped ON ref_bizunit_scoped.gkey=inv_unit.line_op
										WHERE ".$cond." AND category='IMPRT' AND inv_unit_fcy_visit.transit_state='S20_INBOUND'";
										$query=oci_parse($con_sparcsn4_oracle,$query);
										oci_execute($query);
										while(($row=oci_fetch_object($query))!=false){
											$i++;
									?>
									<tr class="gradeX">
										<td align="center"> <?php  echo $i;?> </td>
										<td align="center"><?php if($row->ID) echo $row->ID; else echo "&nbsp;";?></td>
										<td align="center"><?php if($row->FCY_TIME_IN) echo $row->FCY_TIME_IN ; else echo "&nbsp;";?></td>
										
										<td align="center"><?php if($row->CL_DATE ) echo $row->CL_DATE; else echo "&nbsp;";?></td>
										
										<td align="center"><?php if($row->LOCATION ) echo $row->LOCATION; else echo "&nbsp;";?></td>
										<td align="center"><?php if($row->SEALNO ) echo $row->SEALNO; else echo "&nbsp;";?></td>
										<td align="center"><?php if($row->ISO ) echo $row->ISO; else echo "&nbsp;";?></td>
										<td align="center"><?php if($row->MLO ) echo $row->MLO; else echo "&nbsp;";?></td>
										<td align="center"><?php if($row->FREIGHT_KIND ) echo $row->FREIGHT_KIND; else echo "&nbsp;";?></td>
										<td align="center"><?php if($row->WEIGHT  ) echo $row->WEIGHT; else echo "&nbsp;";?></td>
										<td align="center"><?php  echo "&nbsp;";?></td>
										<td align="center"><?php if($row->SHORT_NAME ) echo $row->SHORT_NAME; else echo "&nbsp;";?></td>
										<td align="center"><?php echo "&nbsp;";?></td>
										<!--td align="center"><?php if($row->USER_ID ) echo $row->USER_ID; else echo "&nbsp;";?></td-->
									</tr>
									<?php } ?>
								</tbody>
							</table>
						</div>
						<div class="panel-body">
							<table class="table table-bordered table-responsive table-hover table-striped mb-none">
								<thead>
									<tr class="gridDark">
										<th class="text-center" colspan="13"> IMPORT CONTAINER DISCHARGED LIST: </th>
									</tr>
									<tr class="gridDark">
										<th class="text-center">SlNo</th>
										<th class="text-center">Container No.</th>									
										<th class="text-center">Discharge Time.</th>									
										<th class="text-center">C/L Date.</th>									
										<th class="text-center">Location</th>									
										<th class="text-center">Seal No</th>									
										<th class="text-center">Type</th>									
										<th class="text-center">MLO</th>									
										<th class="text-center">Status</th>									
										<th class="text-center">Weight</th>									
										<th class="text-center">IMCO</th>									
										<th class="text-center">Commodity</th>									
										<th class="text-center">Remarks</th>									
										<!--th class="text-center">User Id</th-->									
									</tr>
								</thead>
								<tbody>
									<?php
											$i=0;
											$j=0;
											$mlo="";
										/* $query="SELECT CONCAT(SUBSTRING(sparcsn4.inv_unit.id,1,4),' ',SUBSTRING(sparcsn4.inv_unit.id,5)) AS id,sparcsn4.inv_unit_fcy_visit.time_in AS fcy_time_in,
										sparcsn4.inv_unit_fcy_visit.last_pos_slot AS location,sparcsn4.inv_unit.seal_nbr1 AS sealno,
										sparcsn4.ref_equip_type.id AS iso,sparcsn4.ref_bizunit_scoped.id AS mlo,sparcsn4.inv_unit.freight_kind,sparcsn4.inv_unit.goods_and_ctr_wt_kg AS weight,
										ref_commodity.short_name,sparcsn4.inv_unit.remark
										FROM  sparcsn4.inv_unit 
										INNER JOIN sparcsn4.inv_unit_fcy_visit ON sparcsn4.inv_unit_fcy_visit.unit_gkey=sparcsn4.inv_unit.gkey
										INNER JOIN sparcsn4.argo_carrier_visit ON sparcsn4.argo_carrier_visit.gkey=sparcsn4.inv_unit_fcy_visit.actual_ib_cv
										INNER JOIN sparcsn4.vsl_vessel_visit_details ON sparcsn4.vsl_vessel_visit_details.vvd_gkey=sparcsn4.argo_carrier_visit.cvcvd_gkey
										LEFT JOIN sparcsn4.inv_goods ON sparcsn4.inv_goods.gkey=sparcsn4.inv_unit.goods
										LEFT JOIN sparcsn4.ref_commodity ON sparcsn4.ref_commodity.gkey=sparcsn4.inv_goods.commodity_gkey
										INNER JOIN sparcsn4.inv_unit_equip ON sparcsn4.inv_unit_equip.unit_gkey=sparcsn4.inv_unit.gkey
										INNER JOIN sparcsn4.ref_equipment ON sparcsn4.ref_equipment.gkey=sparcsn4.inv_unit_equip.eq_gkey
										INNER JOIN sparcsn4.ref_equip_type ON sparcsn4.ref_equip_type.gkey=sparcsn4.ref_equipment.eqtyp_gkey
										INNER JOIN sparcsn4.ref_bizunit_scoped ON sparcsn4.ref_bizunit_scoped.gkey=sparcsn4.inv_unit.line_op
										WHERE sparcsn4.vsl_vessel_visit_details.vvd_gkey='$vvdGkey' AND category='IMPRT' AND sparcsn4.inv_unit_fcy_visit.time_in IS NOT NULL"; */

										//**** inv_unit_equip table is mising in this query
										
										$query="SELECT CONCAT(CONCAT(substr(inv_unit.id,1,4),' '),substr(inv_unit.id,5)) AS id,inv_unit_fcy_visit.time_in AS fcy_time_in,
										inv_unit_fcy_visit.last_pos_slot AS location,inv_unit.seal_nbr1 AS sealno,
										ref_equip_type.id AS iso,ref_bizunit_scoped.id AS mlo,inv_unit.freight_kind,inv_unit.goods_and_ctr_wt_kg AS weight,
										ref_commodity.short_name,inv_unit.remark,
										(SELECT time_discharge_complete
										FROM vsl_vessel_visit_details
										INNER JOIN argo_visit_details ON argo_visit_details.gkey = vsl_vessel_visit_details.vvd_gkey
										WHERE ib_vyg=inv_unit_fcy_visit.flex_string10 FETCH FIRST 1 ROWS ONLY) AS cl_date
										FROM  inv_unit 
										INNER JOIN inv_unit_fcy_visit ON inv_unit_fcy_visit.unit_gkey=inv_unit.gkey
										INNER JOIN argo_carrier_visit ON argo_carrier_visit.gkey=inv_unit_fcy_visit.actual_ib_cv
										INNER JOIN vsl_vessel_visit_details ON vsl_vessel_visit_details.vvd_gkey=argo_carrier_visit.cvcvd_gkey
										LEFT JOIN inv_goods ON inv_goods.gkey=inv_unit.goods
										LEFT JOIN ref_commodity ON ref_commodity.gkey=inv_goods.commodity_gkey
										INNER JOIN ref_equipment ON ref_equipment.gkey=inv_unit.eq_gkey
										INNER JOIN ref_equip_type ON ref_equip_type.gkey=ref_equipment.eqtyp_gkey
										INNER JOIN ref_bizunit_scoped ON ref_bizunit_scoped.gkey=inv_unit.line_op
										WHERE ".$cond." AND category='IMPRT' AND inv_unit_fcy_visit.time_in IS NOT NULL";
								
										$query=oci_parse($con_sparcsn4_oracle,$query);
										oci_execute($query);
										while(($row=oci_fetch_object($query))!=false){
										$i++;
									?>
									<tr class="gradeX">
										<td align="center"> <?php  echo $i;?> </td>
										<td align="center"><?php if($row->ID) echo $row->ID; else echo "&nbsp;";?></td>
										<td align="center"><?php if($row->FCY_TIME_IN ) echo $row->FCY_TIME_IN; else echo "&nbsp;";?></td>
										
										<td align="center"><?php if($row->CL_DATE ) echo $row->CL_DATE; else echo "&nbsp;";?></td>
										
										<td align="center"><?php if($row->LOCATION ) echo $row->LOCATION; else echo "&nbsp;";?></td>
										<td align="center"><?php if($row->SEALNO ) echo $row->SEALNO; else echo "&nbsp;";?></td>
										<td align="center"><?php if($row->ISO ) echo $row->ISO; else echo "&nbsp;";?></td>
										<td align="center"><?php if($row->MLO ) echo $row->MLO; else echo "&nbsp;";?></td>
										<td align="center"><?php if($row->FREIGHT_KIND ) echo $row->FREIGHT_KIND; else echo "&nbsp;";?></td>
										<td align="center"><?php if($row->WEIGHT ) echo $row->WEIGHT; else echo "&nbsp;";?></td>
										<td align="center"><?php  echo "&nbsp;";?></td>
										<td align="center"><?php if($row->SHORT_NAME ) echo $row->SHORT_NAME; else echo "&nbsp;";?></td>
										<td align="center"><?php echo "&nbsp;";?></td>
										<!--td align="center"><?php if($row->USER_ID ) echo $row->USER_ID; else echo "&nbsp;";?></td-->
									</tr>
									<?php } ?>
								</tbody>
							</table>
						</div>
						<?php
							/* $sqlSummery="SELECT gkey,
							IFNULL(SUM(onboard_LD_20),0) AS onboard_LD_20,
							IFNULL(SUM(onboard_LD_40),0) AS onboard_LD_40,
							IFNULL(SUM(onboard_MT_20),0) AS onboard_MT_20,
							IFNULL(SUM(onboard_MT_40),0) AS onboard_MT_40,
							IFNULL(SUM(onboard_LD_tues),0) AS onboard_LD_tues,
							IFNULL(SUM(onboard_MT_tues),0) AS onboard_MT_tues

							 FROM (
							SELECT DISTINCT sparcsn4.inv_unit.gkey AS gkey,
							(CASE WHEN RIGHT(nominal_length,2) = 'NOM20' AND freight_kind IN ('FCL','LCL')  THEN 1  
							ELSE NULL END) AS onboard_LD_20, 
							(CASE WHEN RIGHT(nominal_length,2) > '20' AND freight_kind IN ('FCL','LCL')  THEN 1  
							ELSE NULL END) AS onboard_LD_40,
							(CASE WHEN RIGHT(nominal_length,2) = '20' AND freight_kind ='MTY'  THEN 1  
							ELSE NULL END) AS onboard_MT_20, 
							(CASE WHEN RIGHT(nominal_length,2) > '20' AND freight_kind ='MTY'  THEN 1  
							ELSE NULL END) AS onboard_MT_40, 
							(CASE WHEN RIGHT(nominal_length,2)=20 AND freight_kind IN ('FCL','LCL') THEN 1 
							ELSE (CASE WHEN RIGHT(nominal_length,2)>20 AND freight_kind IN ('FCL','LCL') THEN 2 ELSE NULL END) END) AS onboard_LD_tues, 
							(CASE WHEN RIGHT(nominal_length,2)=20 AND freight_kind='MTY' THEN 1 
							ELSE (CASE WHEN RIGHT(nominal_length,5)>20 AND freight_kind='MTY' THEN 2 ELSE NULL END) END) AS onboard_MT_tues

							FROM sparcsn4.inv_unit
							INNER JOIN sparcsn4.inv_unit_fcy_visit ON sparcsn4.inv_unit_fcy_visit.unit_gkey=sparcsn4.inv_unit.gkey
							INNER JOIN sparcsn4.argo_carrier_visit ON sparcsn4.argo_carrier_visit.gkey=sparcsn4.inv_unit_fcy_visit.actual_ib_cv
							INNER JOIN sparcsn4.vsl_vessel_visit_details ON sparcsn4.vsl_vessel_visit_details.vvd_gkey=sparcsn4.argo_carrier_visit.cvcvd_gkey
							INNER JOIN sparcsn4.inv_unit_equip ON sparcsn4.inv_unit_equip.unit_gkey=sparcsn4.inv_unit.gkey
							INNER JOIN sparcsn4.ref_equipment ON sparcsn4.ref_equipment.gkey=sparcsn4.inv_unit_equip.eq_gkey
							INNER JOIN sparcsn4.ref_equip_type ON sparcsn4.ref_equip_type.gkey=sparcsn4.ref_equipment.eqtyp_gkey
							WHERE  sparcsn4.vsl_vessel_visit_details.vvd_gkey='$vvdGkey' AND category='IMPRT' AND sparcsn4.inv_unit_fcy_visit.transit_state ='S20_INBOUND'
							) AS tmp"; */
							
							$sqlSummery="SELECT 
							NVL(SUM(onboard_LD_20),0) AS onboard_LD_20,
							NVL(SUM(onboard_LD_40),0) AS onboard_LD_40,
							NVL(SUM(onboard_MT_20),0) AS onboard_MT_20,
							NVL(SUM(onboard_MT_40),0) AS onboard_MT_40,
							NVL(SUM(onboard_LD_tues),0) AS onboard_LD_tues,
							NVL(SUM(onboard_MT_tues),0) AS onboard_MT_tues
							
							FROM (
							SELECT DISTINCT inv_unit.gkey AS gkey,
							(CASE WHEN substr(nominal_length,-2) = 'NOM20' AND freight_kind IN ('FCL','LCL')  THEN 1  
							ELSE NULL END) AS onboard_LD_20, 
							(CASE WHEN substr(nominal_length,-2) > '20' AND freight_kind IN ('FCL','LCL')  THEN 1  
							ELSE NULL END) AS onboard_LD_40,
							(CASE WHEN substr(nominal_length,-2) = '20' AND freight_kind ='MTY'  THEN 1  
							ELSE NULL END) AS onboard_MT_20, 
							(CASE WHEN substr(nominal_length,-2) > '20' AND freight_kind ='MTY'  THEN 1  
							ELSE NULL END) AS onboard_MT_40, 
							(CASE WHEN substr(nominal_length,-2)='20' AND freight_kind IN ('FCL','LCL') THEN 1 
							ELSE (CASE WHEN substr(nominal_length,2)>'20' AND freight_kind IN ('FCL','LCL') THEN 2 ELSE NULL END) END) AS onboard_LD_tues, 
							(CASE WHEN substr(nominal_length,-2)=20 AND freight_kind='MTY' THEN 1 
							ELSE (CASE WHEN substr(nominal_length,-5)>'20' AND freight_kind='MTY' THEN 2 ELSE NULL END) END) AS onboard_MT_tues
							
							FROM inv_unit
							INNER JOIN inv_unit_fcy_visit ON inv_unit_fcy_visit.unit_gkey=inv_unit.gkey
							INNER JOIN argo_carrier_visit ON argo_carrier_visit.gkey=inv_unit_fcy_visit.actual_ib_cv
							INNER JOIN vsl_vessel_visit_details ON vsl_vessel_visit_details.vvd_gkey=argo_carrier_visit.cvcvd_gkey
							INNER JOIN ref_equipment ON ref_equipment.gkey=inv_unit.eq_gkey
							INNER JOIN ref_equip_type ON ref_equip_type.gkey=ref_equipment.eqtyp_gkey
							WHERE ".$cond." AND category='IMPRT' AND inv_unit_fcy_visit.transit_state ='S20_INBOUND'
							)";
						//	$sqlSummery=mysqli_query($con_sparcsn4,$sqlSummery);
							//$rowSummery=mysqli_fetch_object($sqlSummery);

							$sqlsqlSummeryRes=oci_parse($con_sparcsn4_oracle,$sqlSummery);
	                        oci_execute($sqlsqlSummeryRes);
							$rowSummery=oci_fetch_object($sqlsqlSummeryRes);


							/* $sqlSummery2="SELECT gkey,
							IFNULL(SUM(balance_LD_20),0) AS balance_LD_20,
							IFNULL(SUM(balance_LD_40),0) AS balance_LD_40,
							IFNULL(SUM(balance_MT_20),0) AS balance_MT_20,
							IFNULL(SUM(balance_MT_40),0) AS balance_MT_40,
							IFNULL(SUM(balance_LD_tues),0) AS balance_LD_tues,
							IFNULL(SUM(balance_MT_tues),0) AS balance_MT_tues

							 FROM (
							SELECT DISTINCT sparcsn4.inv_unit.gkey AS gkey,
							(CASE WHEN RIGHT(nominal_length,2) = '20' AND freight_kind IN ('FCL','LCL')  THEN 1  
							ELSE NULL END) AS balance_LD_20, 
							(CASE WHEN RIGHT(nominal_length,2) > '20' AND freight_kind IN ('FCL','LCL')  THEN 1  
							ELSE NULL END) AS balance_LD_40,
							(CASE WHEN RIGHT(nominal_length,2) = '20' AND freight_kind ='MTY'  THEN 1  
							ELSE NULL END) AS balance_MT_20, 
							(CASE WHEN RIGHT(nominal_length,2) > '20' AND freight_kind ='MTY'  THEN 1  
							ELSE NULL END) AS balance_MT_40, 
							(CASE WHEN RIGHT(nominal_length,2) = 20 AND freight_kind IN ('FCL','LCL') THEN 1 
							ELSE (CASE WHEN RIGHT(nominal_length,2) > 20 AND freight_kind IN ('FCL','LCL') THEN 2 ELSE NULL END) END) AS balance_LD_tues, 
							(CASE WHEN RIGHT(nominal_length,2) = 20 AND freight_kind='MTY' THEN 1 
							ELSE (CASE WHEN RIGHT(nominal_length,2) > 20 AND freight_kind='MTY' THEN 2 ELSE NULL END) END) AS balance_MT_tues

							FROM  sparcsn4.inv_unit 
							INNER JOIN sparcsn4.inv_unit_fcy_visit ON sparcsn4.inv_unit_fcy_visit.unit_gkey=sparcsn4.inv_unit.gkey
							INNER JOIN sparcsn4.argo_carrier_visit ON sparcsn4.argo_carrier_visit.gkey=sparcsn4.inv_unit_fcy_visit.actual_ib_cv
							INNER JOIN sparcsn4.vsl_vessel_visit_details ON sparcsn4.vsl_vessel_visit_details.vvd_gkey=sparcsn4.argo_carrier_visit.cvcvd_gkey
							LEFT JOIN sparcsn4.inv_goods ON sparcsn4.inv_goods.gkey=sparcsn4.inv_unit.goods
							LEFT JOIN sparcsn4.ref_commodity ON sparcsn4.ref_commodity.gkey=sparcsn4.inv_goods.commodity_gkey
							INNER JOIN sparcsn4.inv_unit_equip ON sparcsn4.inv_unit_equip.unit_gkey=sparcsn4.inv_unit.gkey
							INNER JOIN sparcsn4.ref_equipment ON sparcsn4.ref_equipment.gkey=sparcsn4.inv_unit_equip.eq_gkey
							INNER JOIN sparcsn4.ref_equip_type ON sparcsn4.ref_equip_type.gkey=sparcsn4.ref_equipment.eqtyp_gkey
							INNER JOIN sparcsn4.ref_bizunit_scoped ON sparcsn4.ref_bizunit_scoped.gkey=sparcsn4.inv_unit.line_op
							WHERE sparcsn4.vsl_vessel_visit_details.vvd_gkey='$vvdGkey' AND category='IMPRT' AND sparcsn4.inv_unit_fcy_visit.transit_state !='S20_INBOUND') AS tmp"; */
							
							$sqlSummery2= "SELECT
							NVL(SUM(balance_LD_20),0) AS balance_LD_20,
							NVL(SUM(balance_LD_40),0) AS balance_LD_40,
							NVL(SUM(balance_MT_20),0) AS balance_MT_20,
							NVL(SUM(balance_MT_40),0) AS balance_MT_40,
							NVL(SUM(balance_LD_tues),0) AS balance_LD_tues,
							NVL(SUM(balance_MT_tues),0) AS balance_MT_tues
							
							 FROM (
							SELECT 
							(CASE WHEN substr(nominal_length,-2) = '20' AND freight_kind IN ('FCL','LCL')  THEN 1  
							ELSE NULL END) AS balance_LD_20, 
							(CASE WHEN substr(nominal_length,-2) > '20' AND freight_kind IN ('FCL','LCL')  THEN 1  
							ELSE NULL END) AS balance_LD_40,
							(CASE WHEN substr(nominal_length,-2) = '20' AND freight_kind ='MTY'  THEN 1  
							ELSE NULL END) AS balance_MT_20, 
							(CASE WHEN substr(nominal_length,-2) > '20' AND freight_kind ='MTY'  THEN 1  
							ELSE NULL END) AS balance_MT_40, 
							(CASE WHEN substr(nominal_length,-2) = '20' AND freight_kind IN ('FCL','LCL') THEN 1 
							ELSE (CASE WHEN substr(nominal_length,-2) > '20' AND freight_kind IN ('FCL','LCL') THEN 2 ELSE NULL END) END) AS balance_LD_tues, 
							(CASE WHEN substr(nominal_length,-2) = '20' AND freight_kind='MTY' THEN 1 
							ELSE (CASE WHEN substr(nominal_length,-2) > '20' AND freight_kind='MTY' THEN 2 ELSE NULL END) END) AS balance_MT_tues
							
							FROM  inv_unit 
							INNER JOIN inv_unit_fcy_visit ON inv_unit_fcy_visit.unit_gkey=inv_unit.gkey
							INNER JOIN argo_carrier_visit ON argo_carrier_visit.gkey=inv_unit_fcy_visit.actual_ib_cv
							INNER JOIN vsl_vessel_visit_details ON vsl_vessel_visit_details.vvd_gkey=argo_carrier_visit.cvcvd_gkey
							LEFT JOIN inv_goods ON inv_goods.gkey=inv_unit.goods
							LEFT JOIN ref_commodity ON ref_commodity.gkey=inv_goods.commodity_gkey
							INNER JOIN ref_equipment ON ref_equipment.gkey=inv_unit.eq_gkey
							INNER JOIN ref_equip_type ON ref_equip_type.gkey=ref_equipment.eqtyp_gkey
							INNER JOIN ref_bizunit_scoped ON ref_bizunit_scoped.gkey=inv_unit.line_op
							WHERE ".$cond." AND category='IMPRT' AND inv_unit_fcy_visit.transit_state !='S20_INBOUND'
							)";
							//$sqlSummery2=mysqli_query($con_sparcsn4,$sqlSummery2);
							//$rowSummery2=mysqli_fetch_object($sqlSummery2);

							$sqlSummery2Res=oci_parse($con_sparcsn4_oracle,$sqlSummery2);
	                        oci_execute($sqlSummery2Res);
							$rowSummery2=oci_fetch_object($sqlSummery2Res);
						?>
						<div class="panel-body">
							<table class="table table-bordered table-responsive table-hover table-striped mb-none">
								<thead>
									<tr class="gridDark">
										<th class="text-center" colspan="6"> DISCHARGED </th>
										<th class="text-center" colspan="6"> BALANCE ON BOARD </th>
									</tr>
									<tr class="gridDark">
										<th class="text-center" colspan="2">LADEN</th>
										<th class="text-center" colspan="2">EMPTY</th>
										<th class="text-center" colspan="2">TUES</th>
										<th class="text-center" colspan="2">LADEN</th>
										<th class="text-center" colspan="2">EMPTY</th>
										<th class="text-center" colspan="2">TUES</th>								
									</tr>
									<tr class="gridDark">
										<th class="text-center">20</th>
										<th class="text-center">40</th>
										<th class="text-center">20</th>
										<th class="text-center">40</th>
										<th class="text-center">LD</th>
										<th class="text-center">MT</th>
										<th class="text-center">20</th>
										<th class="text-center">40</th>
										<th class="text-center">20</th>
										<th class="text-center">40</th>
										<th class="text-center">LD</th>
										<th class="text-center">MT</th>							
									</tr>
								</thead>
								<tbody>
									<tr class="gradeX">
										<td align="center"><?php if($rowSummery2->BALANCE_LD_20) echo $rowSummery2->BALANCE_LD_20 ; else echo "&nbsp;"; ?></td>
										<td align="center"><?php if($rowSummery2->BALANCE_LD_40) echo $rowSummery2->BALANCE_LD_40; else echo "&nbsp;"; ?></td>
										<td align="center"><?php if($rowSummery2->BALANCE_MT_20) echo $rowSummery2->BALANCE_MT_20 ; else echo "&nbsp;"; ?></td>
										<td align="center"><?php if($rowSummery2->BALANCE_MT_40) echo $rowSummery2->BALANCE_MT_40; else echo "&nbsp;"; ?></td>
										<td align="center"><?php if($rowSummery2->BALANCE_LD_TUES) echo $rowSummery2->BALANCE_LD_TUES ; else echo "&nbsp;"; ?></td>
										<td align="center"><?php if($rowSummery2->BALANCE_MT_TUES) echo $rowSummery2->BALANCE_MT_TUES; else echo "&nbsp;"; ?></td>
										
										<td align="center"><?php if($rowSummery->ONBOARD_LD_20) echo $rowSummery->ONBOARD_LD_20 ; else echo "&nbsp;"; ?></td>
										<td align="center"><?php if($rowSummery->ONBOARD_LD_40) echo $rowSummery->ONBOARD_LD_40; else echo "&nbsp;"; ?></td>
										<td align="center"><?php if($rowSummery->ONBOARD_MT_20) echo $rowSummery->ONBOARD_MT_20; else echo "&nbsp;"; ?></td>
										<td align="center"><?php if($rowSummery->ONBOARD_MT_40) echo $rowSummery->ONBOARD_MT_40; else echo "&nbsp;"; ?></td>
										<td align="center"><?php if($rowSummery->ONBOARD_LD_TUES ) echo $rowSummery->ONBOARD_LD_TUES; else echo "&nbsp;"; ?></td>
										<td align="center"><?php if($rowSummery->ONBOARD_MT_TUES) echo $rowSummery->ONBOARD_MT_TUES; else echo "&nbsp;"; ?></td>
									</tr>
								</tbody>
							</table>
						</div>
					</div>
				</div>
			</section>
			<!-- end: page -->
			
	</div>
	
	<?php 
	} 
	else 
	{ 
	?>
	<?php 
	//include("FrontEnd/dbConection.php");
	include("dbOracleConnection.php");
	
	$sql="select vvd_gkey from vsl_vessel_visit_details where ib_vyg='$ddl_imp_rot_no'";
	//$sql=mysqli_query($con_sparcsn4,$sql);
	$sqlVvvGkeyRes=oci_parse($con_sparcsn4_oracle,$sql);
	oci_execute($sqlVvvGkeyRes);
	// $row=mysqli_fetch_object($sql);
	// $vvdGkey=$row->vvd_gkey;
	
	// intakhab - 2022-05-19
	$vvdGkey = "";
	$cond = "";
	if(oci_num_rows($sqlVvvGkeyRes)==1)
	{
		while(($row = oci_fetch_object($sqlVvvGkeyRes)) != false)
		{
			$vvdGkey = $row->VVD_GKEY;
		}
		$cond = "vsl_vessel_visit_details.vvd_gkey='$vvdGkey'";
	}
	else if(oci_num_rows($sqlVvvGkeyRes)>1)	
	{
		while(($row = oci_fetch_object($sqlVvvGkeyRes)) !=false)
		{
			// $vvdGkey = "'".$vvdGkey."','".$row->vvd_gkey;
			
			$vvdGkey = $vvdGkey."','".$row->VVD_GKEY;
		}
		$vvdGkey = substr($vvdGkey,2);
		$vvdGkey = $vvdGkey."'";
		$cond = "vsl_vessel_visit_details.vvd_gkey IN($vvdGkey)";
	}
	
	// intakhab - 2022-05-19
	
	// $sql="call ctmsmis.update_containers_by_vvd_gkey($vvdGkey)";
	// $res=mysqli_query($con_sparcsn4,$sql);
	//$res=mysql_query($sql);
	//echo$vvdGkey;
	/* $sql1="select vsl_vessels.name as vsl_name,ifnull(sparcsn4.vsl_vessel_visit_details.flex_string02,
	ifnull(sparcsn4.vsl_vessel_visit_details.flex_string03,'')) as berth_op,ifnull(sparcsn4.argo_quay.id,'') as berth,DATE(sparcsn4.vsl_vessel_visit_details.published_eta) AS ata,
	sparcsn4.argo_carrier_visit.atd from vsl_vessel_visit_details
	inner join sparcsn4.argo_carrier_visit on sparcsn4.argo_carrier_visit.cvcvd_gkey=sparcsn4.vsl_vessel_visit_details.vvd_gkey
	inner join sparcsn4.vsl_vessel_berthings on sparcsn4.vsl_vessel_berthings.vvd_gkey=sparcsn4.vsl_vessel_visit_details.vvd_gkey
	inner join sparcsn4.vsl_vessels on sparcsn4.vsl_vessels.gkey=sparcsn4.vsl_vessel_visit_details.vessel_gkey
	inner join sparcsn4.argo_quay on sparcsn4.argo_quay.gkey=sparcsn4.vsl_vessel_berthings.quay
	where vsl_vessel_visit_details.vvd_gkey=$vvdGkey"; */
	
	$sql1="select vsl_vessels.name as vsl_name,NVL(vsl_vessel_visit_details.flex_string02,
	NVL(vsl_vessel_visit_details.flex_string03,'')) as berth_op,NVL(argo_quay.id,'') as berth,
	to_char(vsl_vessel_visit_details.published_eta,'yyyy-mm-dd') AS ata,
	argo_carrier_visit.atd from vsl_vessel_visit_details
	inner join argo_carrier_visit on argo_carrier_visit.cvcvd_gkey=vsl_vessel_visit_details.vvd_gkey
	inner join vsl_vessel_berthings on vsl_vessel_berthings.vvd_gkey=vsl_vessel_visit_details.vvd_gkey
	inner join vsl_vessels on vsl_vessels.gkey=vsl_vessel_visit_details.vessel_gkey
	inner join argo_quay on argo_quay.gkey=vsl_vessel_berthings.quay
	where ".$cond;
//	$sql1=mysqli_query($con_sparcsn4,$sql1);
	//$row1=mysqli_fetch_object($sql1);

	$resSql1=oci_parse($con_sparcsn4_oracle,$sql1);
	oci_execute($resSql1);
	//$row1=oci_fetch_object($resSql1);
	
	$vsl_name = "";
	$ata = "";
	while(($row1=oci_fetch_object($resSql1)) !=false)
	{
		$vsl_name = $row1->VSL_NAME;
		$ata = $row1->ATA ;
	}
	
	?>
	
	
			<!-- start: page -->
			
			
							<table width="100%" border ='1' cellpadding='0' cellspacing='0'>
								<tr bgcolor="#ffffff" align="center" height="100px">
									<td colspan="13" align="center">
										<table border=0 width="100%">
											<tr align="center">
												<td colspan="13"><font size="4"><b>CHITTAGONG PORT AUTHORITY,CHITTAGONG</b></font></td>
											</tr>
											<tr align="center">
												<td colspan="12"><font size="4"><b><u>Import Container Discharging Report</u></b></font></td>
											</tr>
											<tr align="center">
												<td colspan="12"><font size="4"><b></b></font></td>
											</tr>
											<tr>
									
												<td colspan="3" align="center"><font size="4"><b> <?php  Echo $vsl_name;?></b></font></td>
												<td colspan="3" align="center"><font size="4"><b>Voy: <?php  Echo $voysNo;?></b></font></td>
												<td colspan="3" align="center"><font size="4"><b>Imp.Rot No.: <?php  Echo $ddl_imp_rot_no;?></b></font></td>
												<!--td colspan="0" align="center"><font size="4"><b>ETA :-<?php  Echo $row1->ata;?></b></font></td-->
												<td colspan="0" align="center"><font size="4"><b>ETA :-<?php  Echo $ata;?></b></font></td>
											</tr>
										</table>
									</td>
								</tr>
								<tr bgcolor="#ffffff" align="center" height="25px">
									<td colspan="15" align="center"></td>
								</tr>
							</table>
						
							<table class="table table-bordered table-responsive table-hover table-striped mb-none" border="1">
								<thead>
									<tr class="gridDark">
										<th class="text-center" colspan="12">IMPORT CONTAINER BALANCE ON BOARD LIST:</th>
									</tr>
									<tr class="gridDark">
										<th class="text-center">SlNo</th>
										<th class="text-center">Container No.</th>									
										<th class="text-center">Discharge Time.</th>	
										
										<th class="text-center">C/L Date.</th>
										
										<th class="text-center">Location</th>									
										<th class="text-center">Seal No</th>									
										<th class="text-center">Type</th>									
										<th class="text-center">MLO</th>									
										<th class="text-center">Status</th>									
										<th class="text-center">Weight</th>									
										<th class="text-center">IMCO</th>									
										<th class="text-center">Commodity</th>									
										<th class="text-center">Remarks</th>									
										<!--th class="text-center">User Id</th-->									
									</tr>
								</thead>
								<tbody>
									<?php
										$i=0;
										$j=0;
										$mlo="";
										/* $query="SELECT CONCAT(SUBSTRING(sparcsn4.inv_unit.id,1,4),' ',SUBSTRING(sparcsn4.inv_unit.id,5)) AS id,sparcsn4.inv_unit_fcy_visit.time_in AS fcy_time_in,
										sparcsn4.inv_unit_fcy_visit.last_pos_slot AS location,sparcsn4.inv_unit.seal_nbr1 AS sealno,
										sparcsn4.ref_equip_type.id AS iso,sparcsn4.ref_bizunit_scoped.id AS mlo,sparcsn4.inv_unit.freight_kind,sparcsn4.inv_unit.goods_and_ctr_wt_kg AS weight,
										ref_commodity.short_name,sparcsn4.inv_unit.remark
										FROM  sparcsn4.inv_unit 
										INNER JOIN sparcsn4.inv_unit_fcy_visit ON sparcsn4.inv_unit_fcy_visit.unit_gkey=sparcsn4.inv_unit.gkey
										INNER JOIN sparcsn4.argo_carrier_visit ON sparcsn4.argo_carrier_visit.gkey=sparcsn4.inv_unit_fcy_visit.actual_ib_cv
										INNER JOIN sparcsn4.vsl_vessel_visit_details ON sparcsn4.vsl_vessel_visit_details.vvd_gkey=sparcsn4.argo_carrier_visit.cvcvd_gkey
										LEFT JOIN sparcsn4.inv_goods ON sparcsn4.inv_goods.gkey=sparcsn4.inv_unit.goods
										LEFT JOIN sparcsn4.ref_commodity ON sparcsn4.ref_commodity.gkey=sparcsn4.inv_goods.commodity_gkey
										INNER JOIN sparcsn4.inv_unit_equip ON sparcsn4.inv_unit_equip.unit_gkey=sparcsn4.inv_unit.gkey
										INNER JOIN sparcsn4.ref_equipment ON sparcsn4.ref_equipment.gkey=sparcsn4.inv_unit_equip.eq_gkey
										INNER JOIN sparcsn4.ref_equip_type ON sparcsn4.ref_equip_type.gkey=sparcsn4.ref_equipment.eqtyp_gkey
										INNER JOIN sparcsn4.ref_bizunit_scoped ON sparcsn4.ref_bizunit_scoped.gkey=sparcsn4.inv_unit.line_op
										WHERE sparcsn4.vsl_vessel_visit_details.vvd_gkey='$vvdGkey' AND category='IMPRT' AND sparcsn4.inv_unit_fcy_visit.transit_state='S20_INBOUND'"; */
										
										$query="SELECT  CONCAT(CONCAT(substr(inv_unit.id,1,4),' '),substr(inv_unit.id,5)) AS id,inv_unit_fcy_visit.time_in AS fcy_time_in,
										inv_unit_fcy_visit.last_pos_slot AS location,inv_unit.seal_nbr1 AS sealno,
										ref_equip_type.id AS iso,ref_bizunit_scoped.id AS mlo,inv_unit.freight_kind,inv_unit.goods_and_ctr_wt_kg AS weight,
										ref_commodity.short_name,inv_unit.remark,
										
										(SELECT time_discharge_complete
										FROM vsl_vessel_visit_details
										INNER JOIN argo_visit_details ON argo_visit_details.gkey = vsl_vessel_visit_details.vvd_gkey
										WHERE ib_vyg=inv_unit_fcy_visit.flex_string10 FETCH FIRST 1 ROWS ONLY) AS cl_date
										
										FROM  inv_unit 
										INNER JOIN inv_unit_fcy_visit ON inv_unit_fcy_visit.unit_gkey=inv_unit.gkey
										INNER JOIN argo_carrier_visit ON argo_carrier_visit.gkey=inv_unit_fcy_visit.actual_ib_cv
										INNER JOIN vsl_vessel_visit_details ON vsl_vessel_visit_details.vvd_gkey=argo_carrier_visit.cvcvd_gkey
										LEFT JOIN inv_goods ON inv_goods.gkey=inv_unit.goods
										LEFT JOIN ref_commodity ON ref_commodity.gkey=inv_goods.commodity_gkey
										INNER JOIN ref_equipment ON ref_equipment.gkey=inv_unit.eq_gkey
										INNER JOIN ref_equip_type ON ref_equip_type.gkey=ref_equipment.eqtyp_gkey
										INNER JOIN ref_bizunit_scoped ON ref_bizunit_scoped.gkey=inv_unit.line_op
										WHERE ".$cond." AND category='IMPRT' AND inv_unit_fcy_visit.transit_state='S20_INBOUND'";
										//$query=mysqli_query($con_sparcsn4,$query);
										$res3=oci_parse($con_sparcsn4_oracle,$query);
										oci_execute($res3);
										while(($row=oci_fetch_object($res3)) != false){
											$i++;
									?>
									<tr class="gradeX">
										<td align="center"> <?php  echo $i;?> </td>
										<td align="center"><?php if($row->ID) echo $row->ID; else echo "&nbsp;";?></td>
										<td align="center"><?php if($row->FCY_TIME_IN) echo $row->FCY_TIME_IN ; else echo "&nbsp;";?></td>
										
										<td align="center"><?php if($row->CL_DATE ) echo $row->CL_DATE; else echo "&nbsp;";?></td>
										
										<td align="center"><?php if($row->LOCATION) echo $row->LOCATION; else echo "&nbsp;";?></td>
										<td align="center"><?php if($row->SEALNO ) echo $row->SEALNO; else echo "&nbsp;";?></td>
										<td align="center"><?php if($row->ISO) echo $row->ISO; else echo "&nbsp;";?></td>
										<td align="center"><?php if($row->MLO) echo $row->MLO; else echo "&nbsp;";?></td>
										<td align="center"><?php if($row->FREIGHT_KIND  ) echo $row->FREIGHT_KIND; else echo "&nbsp;";?></td>
										<td align="center"><?php if($row->WEIGHT ) echo $row->WEIGHT; else echo "&nbsp;";?></td>
										<td align="center"><?php  echo "&nbsp;";?></td>
										<td align="center"><?php if($row->SHORT_NAME ) echo $row->SHORT_NAME; else echo "&nbsp;";?></td>
										<td align="center"><?php echo "&nbsp;";?></td>
										<!--td align="center"><?php if($row->USER_ID ) echo $row->USER_ID; else echo "&nbsp;";?></td-->
									</tr>
									<?php } ?>
								</tbody>
							</table>
						
						

							<table class="table table-bordered table-responsive table-hover table-striped mb-none" border="1">
								<thead>
									<tr class="gridDark">
										<th class="text-center" colspan="12"> IMPORT CONTAINER DISCHARGED LIST: </th>
									</tr>
									<tr class="gridDark">
										<th class="text-center">SlNo</th>
										<th class="text-center">Container No.</th>									
										<th class="text-center">Discharge Time.</th>	
										
										<th class="text-center">C/L Date.</th>
										
										<th class="text-center">Location</th>									
										<th class="text-center">Seal No</th>									
										<th class="text-center">Type</th>									
										<th class="text-center">MLO</th>									
										<th class="text-center">Status</th>									
										<th class="text-center">Weight</th>									
										<th class="text-center">IMCO</th>									
										<th class="text-center">Commodity</th>									
										<th class="text-center">Remarks</th>									
																	
									</tr>
								</thead>
								<tbody>
									<?php
											$i=0;
											$j=0;
											$mlo="";
										/* $query="SELECT CONCAT(SUBSTRING(sparcsn4.inv_unit.id,1,4),' ',SUBSTRING(sparcsn4.inv_unit.id,5)) AS id,sparcsn4.inv_unit_fcy_visit.time_in AS fcy_time_in,
										sparcsn4.inv_unit_fcy_visit.last_pos_slot AS location,sparcsn4.inv_unit.seal_nbr1 AS sealno,
										sparcsn4.ref_equip_type.id AS iso,sparcsn4.ref_bizunit_scoped.id AS mlo,sparcsn4.inv_unit.freight_kind,sparcsn4.inv_unit.goods_and_ctr_wt_kg AS weight,
										ref_commodity.short_name,sparcsn4.inv_unit.remark
										FROM  sparcsn4.inv_unit 
										INNER JOIN sparcsn4.inv_unit_fcy_visit ON sparcsn4.inv_unit_fcy_visit.unit_gkey=sparcsn4.inv_unit.gkey
										INNER JOIN sparcsn4.argo_carrier_visit ON sparcsn4.argo_carrier_visit.gkey=sparcsn4.inv_unit_fcy_visit.actual_ib_cv
										INNER JOIN sparcsn4.vsl_vessel_visit_details ON sparcsn4.vsl_vessel_visit_details.vvd_gkey=sparcsn4.argo_carrier_visit.cvcvd_gkey
										LEFT JOIN sparcsn4.inv_goods ON sparcsn4.inv_goods.gkey=sparcsn4.inv_unit.goods
										LEFT JOIN sparcsn4.ref_commodity ON sparcsn4.ref_commodity.gkey=sparcsn4.inv_goods.commodity_gkey
										INNER JOIN sparcsn4.inv_unit_equip ON sparcsn4.inv_unit_equip.unit_gkey=sparcsn4.inv_unit.gkey
										INNER JOIN sparcsn4.ref_equipment ON sparcsn4.ref_equipment.gkey=sparcsn4.inv_unit_equip.eq_gkey
										INNER JOIN sparcsn4.ref_equip_type ON sparcsn4.ref_equip_type.gkey=sparcsn4.ref_equipment.eqtyp_gkey
										INNER JOIN sparcsn4.ref_bizunit_scoped ON sparcsn4.ref_bizunit_scoped.gkey=sparcsn4.inv_unit.line_op
										WHERE sparcsn4.vsl_vessel_visit_details.vvd_gkey='$vvdGkey' AND category='IMPRT' AND sparcsn4.inv_unit_fcy_visit.time_in IS NOT NULL"; */
										
										$query="SELECT CONCAT(CONCAT(substr(inv_unit.id,1,4),' '),substr(inv_unit.id,5)) AS id,inv_unit_fcy_visit.time_in AS fcy_time_in,
										inv_unit_fcy_visit.last_pos_slot AS location,inv_unit.seal_nbr1 AS sealno,
										ref_equip_type.id AS iso,ref_bizunit_scoped.id AS mlo,inv_unit.freight_kind,inv_unit.goods_and_ctr_wt_kg AS weight,
										ref_commodity.short_name,inv_unit.remark,
										
										(SELECT time_discharge_complete
										FROM vsl_vessel_visit_details
										INNER JOIN argo_visit_details ON argo_visit_details.gkey = vsl_vessel_visit_details.vvd_gkey
										WHERE ib_vyg=inv_unit_fcy_visit.flex_string10 FETCH FIRST 1 ROWS ONLY) AS cl_date
										FROM  inv_unit 
										INNER JOIN inv_unit_fcy_visit ON inv_unit_fcy_visit.unit_gkey=inv_unit.gkey
										INNER JOIN argo_carrier_visit ON argo_carrier_visit.gkey=inv_unit_fcy_visit.actual_ib_cv
										INNER JOIN vsl_vessel_visit_details ON vsl_vessel_visit_details.vvd_gkey=argo_carrier_visit.cvcvd_gkey
										LEFT JOIN inv_goods ON inv_goods.gkey=inv_unit.goods
										LEFT JOIN ref_commodity ON ref_commodity.gkey=inv_goods.commodity_gkey
										INNER JOIN ref_equipment ON ref_equipment.gkey=inv_unit.eq_gkey
										INNER JOIN ref_equip_type ON ref_equip_type.gkey=ref_equipment.eqtyp_gkey
										INNER JOIN ref_bizunit_scoped ON ref_bizunit_scoped.gkey=inv_unit.line_op
										WHERE ".$cond." AND category='IMPRT' AND inv_unit_fcy_visit.time_in IS NOT NULL";
										//$query=mysqli_query($con_sparcsn4,$query);
										$res4=oci_parse($con_sparcsn4_oracle,$query);
										oci_execute($res4);

										while(($row=oci_fetch_object($res4))!=false){
										$i++;
									?>
									<tr class="gradeX">
										
										<td align="center"> <?php  echo $i;?> </td>
										<td align="center"><?php if($row->ID) echo $row->ID; else echo "&nbsp;";?></td>
										<td align="center"><?php if($row->FCY_TIME_IN) echo $row->FCY_TIME_IN ; else echo "&nbsp;";?></td>
										
										<td align="center"><?php if($row->CL_DATE ) echo $row->CL_DATE; else echo "&nbsp;";?></td>
										
										<td align="center"><?php if($row->LOCATION) echo $row->LOCATION; else echo "&nbsp;";?></td>
										<td align="center"><?php if($row->SEALNO ) echo $row->SEALNO; else echo "&nbsp;";?></td>
										<td align="center"><?php if($row->ISO) echo $row->ISO; else echo "&nbsp;";?></td>
										<td align="center"><?php if($row->MLO) echo $row->MLO; else echo "&nbsp;";?></td>
										<td align="center"><?php if($row->FREIGHT_KIND  ) echo $row->FREIGHT_KIND; else echo "&nbsp;";?></td>
										<td align="center"><?php if($row->WEIGHT ) echo $row->WEIGHT; else echo "&nbsp;";?></td>
										<td align="center"><?php  echo "&nbsp;";?></td>
										<td align="center"><?php if($row->SHORT_NAME ) echo $row->SHORT_NAME; else echo "&nbsp;";?></td>
										<td align="center"><?php echo "&nbsp;";?></td>
										<!--td align="center"><?php if($row->USER_ID ) echo $row->USER_ID; else echo "&nbsp;";?></td-->
									</tr>
									<?php } ?>
								</tbody>
							</table>
						
						<?php
							/* $sqlSummery="SELECT gkey,
							IFNULL(SUM(onboard_LD_20),0) AS onboard_LD_20,
							IFNULL(SUM(onboard_LD_40),0) AS onboard_LD_40,
							IFNULL(SUM(onboard_MT_20),0) AS onboard_MT_20,
							IFNULL(SUM(onboard_MT_40),0) AS onboard_MT_40,
							IFNULL(SUM(onboard_LD_tues),0) AS onboard_LD_tues,
							IFNULL(SUM(onboard_MT_tues),0) AS onboard_MT_tues

							 FROM (
							SELECT DISTINCT sparcsn4.inv_unit.gkey AS gkey,
							(CASE WHEN RIGHT(nominal_length,2) = 'NOM20' AND freight_kind IN ('FCL','LCL')  THEN 1  
							ELSE NULL END) AS onboard_LD_20, 
							(CASE WHEN RIGHT(nominal_length,2) > '20' AND freight_kind IN ('FCL','LCL')  THEN 1  
							ELSE NULL END) AS onboard_LD_40,
							(CASE WHEN RIGHT(nominal_length,2) = '20' AND freight_kind ='MTY'  THEN 1  
							ELSE NULL END) AS onboard_MT_20, 
							(CASE WHEN RIGHT(nominal_length,2) > '20' AND freight_kind ='MTY'  THEN 1  
							ELSE NULL END) AS onboard_MT_40, 
							(CASE WHEN RIGHT(nominal_length,2)=20 AND freight_kind IN ('FCL','LCL') THEN 1 
							ELSE (CASE WHEN RIGHT(nominal_length,2)>20 AND freight_kind IN ('FCL','LCL') THEN 2 ELSE NULL END) END) AS onboard_LD_tues, 
							(CASE WHEN RIGHT(nominal_length,2)=20 AND freight_kind='MTY' THEN 1 
							ELSE (CASE WHEN RIGHT(nominal_length,5)>20 AND freight_kind='MTY' THEN 2 ELSE NULL END) END) AS onboard_MT_tues

							FROM sparcsn4.inv_unit
							INNER JOIN sparcsn4.inv_unit_fcy_visit ON sparcsn4.inv_unit_fcy_visit.unit_gkey=sparcsn4.inv_unit.gkey
							INNER JOIN sparcsn4.argo_carrier_visit ON sparcsn4.argo_carrier_visit.gkey=sparcsn4.inv_unit_fcy_visit.actual_ib_cv
							INNER JOIN sparcsn4.vsl_vessel_visit_details ON sparcsn4.vsl_vessel_visit_details.vvd_gkey=sparcsn4.argo_carrier_visit.cvcvd_gkey
							INNER JOIN sparcsn4.inv_unit_equip ON sparcsn4.inv_unit_equip.unit_gkey=sparcsn4.inv_unit.gkey
							INNER JOIN sparcsn4.ref_equipment ON sparcsn4.ref_equipment.gkey=sparcsn4.inv_unit_equip.eq_gkey
							INNER JOIN sparcsn4.ref_equip_type ON sparcsn4.ref_equip_type.gkey=sparcsn4.ref_equipment.eqtyp_gkey
							WHERE  sparcsn4.vsl_vessel_visit_details.vvd_gkey='$vvdGkey' AND category='IMPRT' AND sparcsn4.inv_unit_fcy_visit.transit_state ='S20_INBOUND'
							) AS tmp"; */
							
							$sqlSummery="SELECT
							NVL(SUM(onboard_LD_20),0) AS onboard_LD_20,
							NVL(SUM(onboard_LD_40),0) AS onboard_LD_40,
							NVL(SUM(onboard_MT_20),0) AS onboard_MT_20,
							NVL(SUM(onboard_MT_40),0) AS onboard_MT_40,
							NVL(SUM(onboard_LD_tues),0) AS onboard_LD_tues,
							NVL(SUM(onboard_MT_tues),0) AS onboard_MT_tues
							
							 FROM (
							SELECT DISTINCT inv_unit.gkey AS gkey,
							(CASE WHEN substr(nominal_length,-2) = 'NOM20' AND freight_kind IN ('FCL','LCL')  THEN 1  
							ELSE NULL END) AS onboard_LD_20, 
							(CASE WHEN substr(nominal_length,-2) > '20' AND freight_kind IN ('FCL','LCL')  THEN 1  
							ELSE NULL END) AS onboard_LD_40,
							(CASE WHEN substr(nominal_length,-2) = '20' AND freight_kind ='MTY'  THEN 1  
							ELSE NULL END) AS onboard_MT_20, 
							(CASE WHEN substr(nominal_length,-2) > '20' AND freight_kind ='MTY'  THEN 1  
							ELSE NULL END) AS onboard_MT_40, 
							(CASE WHEN substr(nominal_length,-2)='20' AND freight_kind IN ('FCL','LCL') THEN 1 
							ELSE (CASE WHEN substr(nominal_length,-2)>'20' AND freight_kind IN ('FCL','LCL') THEN 2 ELSE NULL END) END) AS onboard_LD_tues, 
							(CASE WHEN substr(nominal_length,2)='20' AND freight_kind='MTY' THEN 1 
							ELSE (CASE WHEN substr(nominal_length,-5)>'20' AND freight_kind='MTY' THEN 2 ELSE NULL END) END) AS onboard_MT_tues
							
							FROM inv_unit
							INNER JOIN inv_unit_fcy_visit ON inv_unit_fcy_visit.unit_gkey=inv_unit.gkey
							INNER JOIN argo_carrier_visit ON argo_carrier_visit.gkey=inv_unit_fcy_visit.actual_ib_cv
							INNER JOIN vsl_vessel_visit_details ON vsl_vessel_visit_details.vvd_gkey=argo_carrier_visit.cvcvd_gkey
							INNER JOIN ref_equipment ON ref_equipment.gkey=inv_unit.eq_gkey
							INNER JOIN ref_equip_type ON ref_equip_type.gkey=ref_equipment.eqtyp_gkey
							WHERE  ".$cond." AND category='IMPRT' AND inv_unit_fcy_visit.transit_state ='S20_INBOUND'
							)";
							//$sqlSummery=mysqli_query($con_sparcsn4,$sqlSummery);
						//	$rowSummery=mysqli_fetch_object($sqlSummery);

							$rowResSummery=oci_parse($con_sparcsn4_oracle,$sqlSummery);
							oci_execute($rowResSummery);
							$rowSummery=oci_fetch_object($rowResSummery);


							/* $sqlSummery2="SELECT gkey,
							IFNULL(SUM(balance_LD_20),0) AS balance_LD_20,
							IFNULL(SUM(balance_LD_40),0) AS balance_LD_40,
							IFNULL(SUM(balance_MT_20),0) AS balance_MT_20,
							IFNULL(SUM(balance_MT_40),0) AS balance_MT_40,
							IFNULL(SUM(balance_LD_tues),0) AS balance_LD_tues,
							IFNULL(SUM(balance_MT_tues),0) AS balance_MT_tues

							 FROM (
							SELECT DISTINCT sparcsn4.inv_unit.gkey AS gkey,
							(CASE WHEN RIGHT(nominal_length,2) = '20' AND freight_kind IN ('FCL','LCL')  THEN 1  
							ELSE NULL END) AS balance_LD_20, 
							(CASE WHEN RIGHT(nominal_length,2) > '20' AND freight_kind IN ('FCL','LCL')  THEN 1  
							ELSE NULL END) AS balance_LD_40,
							(CASE WHEN RIGHT(nominal_length,2) = '20' AND freight_kind ='MTY'  THEN 1  
							ELSE NULL END) AS balance_MT_20, 
							(CASE WHEN RIGHT(nominal_length,2) > '20' AND freight_kind ='MTY'  THEN 1  
							ELSE NULL END) AS balance_MT_40, 
							(CASE WHEN RIGHT(nominal_length,2) = 20 AND freight_kind IN ('FCL','LCL') THEN 1 
							ELSE (CASE WHEN RIGHT(nominal_length,2) > 20 AND freight_kind IN ('FCL','LCL') THEN 2 ELSE NULL END) END) AS balance_LD_tues, 
							(CASE WHEN RIGHT(nominal_length,2) = 20 AND freight_kind='MTY' THEN 1 
							ELSE (CASE WHEN RIGHT(nominal_length,2) > 20 AND freight_kind='MTY' THEN 2 ELSE NULL END) END) AS balance_MT_tues

							FROM  sparcsn4.inv_unit 
							INNER JOIN sparcsn4.inv_unit_fcy_visit ON sparcsn4.inv_unit_fcy_visit.unit_gkey=sparcsn4.inv_unit.gkey
							INNER JOIN sparcsn4.argo_carrier_visit ON sparcsn4.argo_carrier_visit.gkey=sparcsn4.inv_unit_fcy_visit.actual_ib_cv
							INNER JOIN sparcsn4.vsl_vessel_visit_details ON sparcsn4.vsl_vessel_visit_details.vvd_gkey=sparcsn4.argo_carrier_visit.cvcvd_gkey
							LEFT JOIN sparcsn4.inv_goods ON sparcsn4.inv_goods.gkey=sparcsn4.inv_unit.goods
							LEFT JOIN sparcsn4.ref_commodity ON sparcsn4.ref_commodity.gkey=sparcsn4.inv_goods.commodity_gkey
							INNER JOIN sparcsn4.inv_unit_equip ON sparcsn4.inv_unit_equip.unit_gkey=sparcsn4.inv_unit.gkey
							INNER JOIN sparcsn4.ref_equipment ON sparcsn4.ref_equipment.gkey=sparcsn4.inv_unit_equip.eq_gkey
							INNER JOIN sparcsn4.ref_equip_type ON sparcsn4.ref_equip_type.gkey=sparcsn4.ref_equipment.eqtyp_gkey
							INNER JOIN sparcsn4.ref_bizunit_scoped ON sparcsn4.ref_bizunit_scoped.gkey=sparcsn4.inv_unit.line_op
							WHERE sparcsn4.vsl_vessel_visit_details.vvd_gkey='$vvdGkey' AND category='IMPRT' AND sparcsn4.inv_unit_fcy_visit.transit_state !='S20_INBOUND') AS tmp"; */
							
							$sqlSummery2="SELECT 
							NVL(SUM(balance_LD_20),0) AS balance_LD_20,
							NVL(SUM(balance_LD_40),0) AS balance_LD_40,
							NVL(SUM(balance_MT_20),0) AS balance_MT_20,
							NVL(SUM(balance_MT_40),0) AS balance_MT_40,
							NVL(SUM(balance_LD_tues),0) AS balance_LD_tues,
							NVL(SUM(balance_MT_tues),0) AS balance_MT_tues
							
							 FROM (
							SELECT DISTINCT inv_unit.gkey AS gkey,
							(CASE WHEN substr(nominal_length,-2) = '20' AND freight_kind IN ('FCL','LCL')  THEN 1  
							ELSE NULL END) AS balance_LD_20, 
							(CASE WHEN substr(nominal_length,-2) > '20' AND freight_kind IN ('FCL','LCL')  THEN 1  
							ELSE NULL END) AS balance_LD_40,
							(CASE WHEN substr(nominal_length,-2) = '20' AND freight_kind ='MTY'  THEN 1  
							ELSE NULL END) AS balance_MT_20, 
							(CASE WHEN substr(nominal_length,-2) > '20' AND freight_kind ='MTY'  THEN 1  
							ELSE NULL END) AS balance_MT_40, 
							(CASE WHEN substr(nominal_length,-2) = '20' AND freight_kind IN ('FCL','LCL') THEN 1 
							ELSE (CASE WHEN substr(nominal_length,-2) > '20' AND freight_kind IN ('FCL','LCL') THEN 2 ELSE NULL END) END) AS balance_LD_tues, 
							(CASE WHEN substr(nominal_length,-2) = '20' AND freight_kind='MTY' THEN 1 
							ELSE (CASE WHEN substr(nominal_length,-2) > '20' AND freight_kind='MTY' THEN 2 ELSE NULL END) END) AS balance_MT_tues
							
							FROM  inv_unit 
							INNER JOIN inv_unit_fcy_visit ON inv_unit_fcy_visit.unit_gkey=inv_unit.gkey
							INNER JOIN argo_carrier_visit ON argo_carrier_visit.gkey=inv_unit_fcy_visit.actual_ib_cv
							INNER JOIN vsl_vessel_visit_details ON vsl_vessel_visit_details.vvd_gkey=argo_carrier_visit.cvcvd_gkey
							LEFT JOIN inv_goods ON inv_goods.gkey=inv_unit.goods
							LEFT JOIN ref_commodity ON ref_commodity.gkey=inv_goods.commodity_gkey
							INNER JOIN ref_equipment ON ref_equipment.gkey=inv_unit.eq_gkey
							INNER JOIN ref_equip_type ON ref_equip_type.gkey=ref_equipment.eqtyp_gkey
							INNER JOIN ref_bizunit_scoped ON ref_bizunit_scoped.gkey=inv_unit.line_op
							WHERE ".$cond." AND category='IMPRT' AND inv_unit_fcy_visit.transit_state !='S20_INBOUND'
							)";
							//$sqlSummery2=mysqli_query($con_sparcsn4,$sqlSummery2);
							//$rowSummery2=mysqli_fetch_object($sqlSummery2);
							$rowResSummery2=oci_parse($con_sparcsn4_oracle,$sqlSummery2);
							oci_execute($rowResSummery2);
							$rowSummery2=oci_fetch_object($rowResSummery2);

						?>
						
							<table class="table table-bordered table-responsive table-hover table-striped mb-none" border="1">
								<thead>
									<tr class="gridDark">
										<th class="text-center" colspan="6"> DISCHARGED </th>
										<th class="text-center" colspan="6"> BALANCE ON BOARD </th>
									</tr>
									<tr class="gridDark">
										<th class="text-center" colspan="2">LADEN</th>
										<th class="text-center" colspan="2">EMPTY</th>
										<th class="text-center" colspan="2">TUES</th>
										<th class="text-center" colspan="2">LADEN</th>
										<th class="text-center" colspan="2">EMPTY</th>
										<th class="text-center" colspan="2">TUES</th>								
									</tr>
									<tr class="gridDark">
										<th class="text-center">20</th>
										<th class="text-center">40</th>
										<th class="text-center">20</th>
										<th class="text-center">40</th>
										<th class="text-center">LD</th>
										<th class="text-center">MT</th>
										<th class="text-center">20</th>
										<th class="text-center">40</th>
										<th class="text-center">20</th>
										<th class="text-center">40</th>
										<th class="text-center">LD</th>
										<th class="text-center">MT</th>							
									</tr>
								</thead>
								<tbody>
									<tr class="gradeX">
								
										<td align="center"><?php if($rowSummery2->BALANCE_LD_20) echo $rowSummery2->BALANCE_LD_20 ; else echo "&nbsp;"; ?></td>
										<td align="center"><?php if($rowSummery2->BALANCE_LD_40) echo $rowSummery2->BALANCE_LD_40; else echo "&nbsp;"; ?></td>
										<td align="center"><?php if($rowSummery2->BALANCE_MT_20) echo $rowSummery2->BALANCE_MT_20 ; else echo "&nbsp;"; ?></td>
										<td align="center"><?php if($rowSummery2->BALANCE_MT_40) echo $rowSummery2->BALANCE_MT_40; else echo "&nbsp;"; ?></td>
										<td align="center"><?php if($rowSummery2->BALANCE_LD_TUES) echo $rowSummery2->BALANCE_LD_TUES ; else echo "&nbsp;"; ?></td>
										<td align="center"><?php if($rowSummery2->BALANCE_MT_TUES) echo $rowSummery2->BALANCE_MT_TUES; else echo "&nbsp;"; ?></td>
										
										<td align="center"><?php if($rowSummery->ONBOARD_LD_20) echo $rowSummery->ONBOARD_LD_20 ; else echo "&nbsp;"; ?></td>
										<td align="center"><?php if($rowSummery->ONBOARD_LD_40) echo $rowSummery->ONBOARD_LD_40; else echo "&nbsp;"; ?></td>
										<td align="center"><?php if($rowSummery->ONBOARD_MT_20) echo $rowSummery->ONBOARD_MT_20; else echo "&nbsp;"; ?></td>
										<td align="center"><?php if($rowSummery->ONBOARD_MT_40) echo $rowSummery->ONBOARD_MT_40; else echo "&nbsp;"; ?></td>
										<td align="center"><?php if($rowSummery->ONBOARD_LD_TUES ) echo $rowSummery->ONBOARD_LD_TUES; else echo "&nbsp;"; ?></td>
										<td align="center"><?php if($rowSummery->ONBOARD_MT_TUES) echo $rowSummery->ONBOARD_MT_TUES; else echo "&nbsp;"; ?></td>
									</tr>
								</tbody>
							</table>
						
					
			<!-- end: page -->
			
	</div>
	<?php } ?>

