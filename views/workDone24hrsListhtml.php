<?php if(@$_POST['options']=='html'){?>
	<?php } 
	else if(@$_POST['options']=='xl'){
		header("Content-type: application/octet-stream");
		header("Content-Disposition: attachment; filename=LAST_24_HOUR_CONTAINER_HANDLING.xls;");
		header("Content-Type: application/ms-excel");
		header("Pragma: no-cache");
		header("Expires: 0");

	}
	include("dbConection.php");
	include("dbOracleConnection.php");
	?>
<html>
<body style="padding:10px;">
<table width="100%" border ='0' cellpadding='0' cellspacing='0'>
<tr bgcolor="#ffffff" align="center" height="100px">
		<td colspan="13" align="center">
			<table border=0 width="100%">
				
				<tr >
					<td align="center" colspan="12"><img align="middle"  width="220px" height="70px" src="<?php echo IMG_PATH?>cpanew.jpg"></td>
				</tr>
				<tr align="center">
					<td colspan="12"><font size="4"><b><?php echo $title;?></b></font></td>
				</tr>
				<tr align="center">
					<td colspan="12"><font size="4"><b></b></font></td>
				</tr>

				

			</table>
		
		</td>
		
	</tr>
	</table>
	<?php 
	$strInfoQry="SELECT vsl_vessel_visit_details.ib_vyg,
	vsl_vessel_visit_details.ob_vyg,
	vsl_vessel_visit_details.vvd_gkey,
	ref_bizunit_scoped.id AS shipping_agent,
	vsl_vessels.name,
	to_date(to_char(argo_carrier_visit.ata,'yyyy-mm-dd'),'yyyy-mm-dd') as arrived_date,
	to_date(to_char(argo_carrier_visit.atd,'yyyy-mm-dd'),'yyyy-mm-dd') as departure_date
	
	FROM vsl_vessel_visit_details
	INNER JOIN vsl_vessels ON vsl_vessels.gkey=vsl_vessel_visit_details.vessel_gkey
	INNER JOIN argo_carrier_visit ON argo_carrier_visit.cvcvd_gkey=vsl_vessel_visit_details.vvd_gkey
	INNER JOIN ref_bizunit_scoped on ref_bizunit_scoped.gkey=vsl_vessel_visit_details.bizu_gkey
	WHERE  vsl_vessel_visit_details.ib_vyg='$rotation_no'";
	$rtnInfoQry=oci_parse($con_sparcsn4_oracle,$strInfoQry);
	oci_execute($rtnInfoQry);


	$j=0;

	$arv_dt="";
	$dep_dt="";
$name="";
$ob_vyg="";
$ib_vyg="";
$vvd_gkey_value=0;
$shifting_agent="";
$arrival_date="";
$departure_date="";
	while(($berth_row = oci_fetch_object($rtnInfoQry)) != false){
		$j++;
		$vvd_gkey_value=$rowInfoQry->VVD_GKEY;

		$arv_dt = $berth_row->ATA;
		$dep_dt = $berth_row->ATD;
		$name= $berth_row->NAME;
		$ob_vyg=$berth_row->OB_VYG;
		$ib_vyg=$berth_row->IB_VYG;
		$shifting_agent=$berth_row->SHIPPING_AGENT;
		$arrival_date=$berth_row->ARRIVAL_DATE;
		$departure_date=$berth_row->DEPARTURE_DATE;
	}

	$berthStr="select ctmsmis.berth_for_vessel('$vvd_gkey_value') as berth";
	$berthQuery = mysqli_query($con_sparcsn4,$berthStr);
	$berthRes = mysqli_fetch_object($berthQuery);
	?>
	<table width="40%" border ='0' cellpadding='0' cellspacing='0'>
	    <tr>
			<td><font size="4"><b> Date : <?php echo $work_date;?></b></font></td>
		</tr>
		<tr><td>VESSEL NAME - </td><td><?php echo $name;?></td></tr>
		<tr><td>VOYAGE - </td><td><?php echo " ";?></td></tr>
		<tr><td>IMP.ROT. - </td><td><?php echo $ib_vyg;?></td></tr>
		<tr><td>EXP.ROT. - </td><td><?php echo $ob_vyg;?></td></tr>
		<tr><td>BERTH NO - </td><td><?php echo $berthRes->berth;?></td></tr>
		<tr><td>SHIPPING AGENT - </td><td><?php echo $shifting_agent ;?></td></tr>
		<tr><td>ARRIVED DATE - </td><td><?php echo $arrival_date;?></td></tr>
		<tr><td>EXPECTED TIME OF DEPARTURE - </td><td><?php echo $departure_date;?></td></tr>
	</table>
	
	</br>
	</br>
	<?php
	

					$str="SELECT inv_unit.id,freight_kind,
					substr(ref_equip_type.nominal_length,2) as siz,inv_unit.gkey,ref_equip_type.nominal_length
					FROM inv_unit
					INNER JOIN inv_unit_fcy_visit ON inv_unit_fcy_visit.unit_gkey=inv_unit.gkey
					INNER JOIN argo_carrier_visit ON argo_carrier_visit.gkey=inv_unit_fcy_visit.actual_ib_cv
					INNER JOIN ref_equipment on ref_equipment.gkey=inv_unit.eq_gkey
					INNER JOIN ref_equip_type on ref_equip_type.gkey=ref_equipment.eqtyp_gkey
					WHERE  argo_carrier_visit.cvcvd_gkey='$vvd_gkey_value'";
					$query=oci_parse($con_sparcsn4_oracle,$str);
					oci_execute($query);

					 $disch_load20=0; 
					 $disch_load40=0;
					 $disch_mty20=0;
				     $disch_mty40=0;
					 $load_teus=0;
					 $mty_teus=0;

                     $tot_disch_load20=0; 
					 $tot_disch_load40=0;
					 $tot_disch_mty20=0;
				     $tot_disch_mty40=0;
					 $tot_disch_load_teus=0;
					 $tot_disch_mty_teus=0;

					 $bal_load20=0;
					 $bal_load40=0;
					 $bal_mty20=0;
					 $bal_mty40=0;
					 $bal_load_teus=0;
					 $bal_mty_teus=0;

					 while(($row=oci_fetch_object($query)) != false) {
						$gKey="";
						$freightKind="";
						$nominalLength="";
						
						$gKey=$row->GKEY;
						$freightKind=$row->FREIGHT_KIND ;
						$nominalLength=$row->NOMINAL_LENGTH;
						$str1 = "SELECT 
						(CASE WHEN '$freightKind' != 'MTY' AND RIGHT('$nominalLength',2)=20
						AND ctmsmis.mis_disch_cont.disch_dt IS NOT NULL AND ctmsmis.mis_disch_cont.disch_dt>= CONCAT(DATE(SUBDATE('$work_date',1)), ' 08:00:00')
						AND ctmsmis.mis_disch_cont.disch_dt<'$work_date 08:00:00'
						THEN 1 ELSE 0 END) AS disch_load20,
						(CASE WHEN '$freightKind' != 'MTY' AND RIGHT('$nominalLength',2)!=20 
						AND ctmsmis.mis_disch_cont.disch_dt IS NOT NULL AND ctmsmis.mis_disch_cont.disch_dt>= CONCAT(DATE(SUBDATE('$work_date',1)), ' 08:00:00')
						AND ctmsmis.mis_disch_cont.disch_dt<'$work_date 08:00:00'
						THEN 1 ELSE 0 END) AS disch_load40,
						(CASE WHEN '$freightKind' = 'MTY' AND RIGHT('$nominalLength',2)=20
						AND ctmsmis.mis_disch_cont.disch_dt IS NOT NULL AND ctmsmis.mis_disch_cont.disch_dt>= CONCAT(DATE(SUBDATE('$work_date',1)), ' 08:00:00')
						AND ctmsmis.mis_disch_cont.disch_dt<'$work_date 08:00:00'
						THEN 1 ELSE 0 END) AS disch_mty20,
						(CASE WHEN '$freightKind' = 'MTY' AND RIGHT('$nominalLength',2)!=20
						AND ctmsmis.mis_disch_cont.disch_dt IS NOT NULL AND ctmsmis.mis_disch_cont.disch_dt>= CONCAT(DATE(SUBDATE('$work_date',1)), ' 08:00:00')
						AND ctmsmis.mis_disch_cont.disch_dt<'$work_date 08:00:00'
						THEN 1 ELSE 0 END) AS disch_mty40,
						
						(CASE WHEN '$freightKind' != 'MTY' AND RIGHT('$nominalLength',2)=20
						AND ctmsmis.mis_disch_cont.disch_dt IS NOT NULL
						THEN 1 ELSE 0 END) AS tot_disch_load20,
						(CASE WHEN '$freightKind' != 'MTY' AND RIGHT('$nominalLength',2)!=20 
						AND ctmsmis.mis_disch_cont.disch_dt IS NOT NULL
						THEN 1 ELSE 0 END) AS tot_disch_load40,
						(CASE WHEN '$freightKind' = 'MTY' AND RIGHT('$nominalLength',2)=20
						AND ctmsmis.mis_disch_cont.disch_dt IS NOT NULL
						THEN 1 ELSE 0 END) AS tot_disch_mty20,
						(CASE WHEN '$freightKind' = 'MTY' AND RIGHT('$nominalLength',2)!=20
						AND ctmsmis.mis_disch_cont.disch_dt IS NOT NULL
						THEN 1 ELSE 0 END) AS tot_disch_mty40,
						
						(CASE WHEN '$freightKind' != 'MTY' AND RIGHT('$nominalLength',2)=20
						AND ctmsmis.mis_disch_cont.disch_dt IS NULL
						THEN 1 ELSE 0 END) AS bal_load20,
						(CASE WHEN '$freightKind' != 'MTY' AND RIGHT('$nominalLength',2)!=20 
						AND ctmsmis.mis_disch_cont.disch_dt IS NULL
						THEN 1 ELSE 0 END) AS bal_load40,
						(CASE WHEN '$freightKind' = 'MTY' AND RIGHT('$nominalLength',2)=20
						AND ctmsmis.mis_disch_cont.disch_dt IS NULL
						THEN 1 ELSE 0 END) AS bal_mty20,
						(CASE WHEN '$freightKind' = 'MTY' AND RIGHT('$nominalLength',2)!=20
						AND ctmsmis.mis_disch_cont.disch_dt IS NULL
						THEN 1 ELSE 0 END) AS bal_mty40
						FROM ctmsmis.mis_disch_cont
						WHERE ctmsmis.mis_disch_cont.gkey='$gKey'";
						$query1 = mysqli_query($con_sparcsn4,$str1);
						$qurey1Res = mysqli_fetch_object($query1);
						$numrows1=mysqli_num_rows($query1);

						if($numrows1>0){

							$disch_load20=$disch_load20+$qurey1Res->disch_load20; 
							$disch_load40=$disch_load40+$qurey1Res->disch_load40;
							$disch_mty20=$disch_mty20+$qurey1Res->disch_mty20;
							$disch_mty40=$disch_mty40+$qurey1Res->disch_mty40;
						

							$tot_disch_load20=$tot_disch_load20+$qurey1Res->tot_disch_load20; 
							$tot_disch_load40=$tot_disch_load40+$qurey1Res->tot_disch_load40;
							$tot_disch_mty20=$tot_disch_mty20+$qurey1Res->tot_disch_mty20;
							$tot_disch_mty40=$tot_disch_mty40+$qurey1Res->tot_disch_mty40;
						
							$bal_load20=$bal_load20+$qurey1Res->bal_load20;
							$bal_load40=$bal_load40+$qurey1Res->bal_load40;
							$bal_mty20=$bal_mty20+$qurey1Res->bal_mty20;
							$bal_mty40=$bal_mty40+$qurey1Res->bal_mty40;
							

							
						}


					}


	?>
	<div class="table-responsive">
	<table class="table table-bordered table-responsive table-hover table-striped mb-none">
		<thead>
			<tr class="gridDark">
				<td align="center" colspan="18"><b>IMPORT</b></td>
			</tr>
		</thead>
		<tbody>
			<tr class="gradeX">
				<td align="center" colspan="6"><b>DISCHARGED</b></td>
				<td align="center" colspan="6"><b>TOTAL DISCHARGED</b></td>
				<td align="center" colspan="6"><b>BALANCE ON BOARD</b></td>
			</tr>
			<tr class="gradeX">
				<td align="center" colspan="2"><b>LADEN</b></td>
				<td align="center" colspan="2"><b>EMPTY</b></td>
				<td align="center" colspan="2"><b>TEUS</b></td>
				<td align="center" colspan="2"><b>LADEN</b></td>
				<td align="center" colspan="2"><b>EMPTY</b></td>
				<td align="center" colspan="2"><b>TEUS</b></td>
				<td align="center" colspan="2"><b>LADEN</b></td>
				<td align="center" colspan="2"><b>EMPTY</b></td>
				<td align="center" colspan="2"><b>TEUS</b></td>
			</tr>
			<tr class="gradeX">
				<td><b>20</b></td>
				<td><b>40</b></td>
				<td><b>20</b></td>
				<td><b>40</b></td>
				<td><b>LT</b></td>
				<td><b>MT</b></td>
				<td><b>20</b></td>
				<td><b>40</b></td>
				<td><b>20</b></td>
				<td><b>40</b></td>
				<td><b>LT</b></td>
				<td><b>MT</b></td>
				<td><b>20</b></td>
				<td><b>40</b></td>
				<td><b>20</b></td>
				<td><b>40</b></td>
				<td><b>LT</b></td>
				<td><b>MT</b></td>
			</tr>
			<tr class="gradeX">
			<td><?php echo $disch_load20;?></td>
			<td><?php echo $disch_load40;?></td>
			<td><?php echo $disch_mty20;?></td>
			<td><?php echo $disch_mty40;?></td>
			<td><?php echo ($disch_load20+$disch_load40)*2 ;?></td>
			<td><?php echo ($disch_mty20+$disch_mty40)*2;?></td>
			
			
			<td><?php echo $tot_disch_load20;?></td>
			<td><?php echo $tot_disch_load40;?></td>
			<td><?php echo $tot_disch_mty20;?></td>
			<td><?php echo $tot_disch_mty40;?></td>
			<td><?php echo ($tot_disch_load20+$tot_disch_load40)*2;?></td>
			<td><?php echo ($tot_disch_mty20+$tot_disch_mty40)*2;?></td>
			
			<td><?php echo $bal_load20;?></td>
			<td><?php echo $bal_load40;?></td>
			<td><?php echo $bal_mty20;?></td>
			<td><?php echo $bal_mty40;?></td>
			<td><?php echo ($bal_load20+$bal_load40)*2;?></td>
			<td><?php echo ($bal_mty20+$bal_mty40)*2;?></td> 
			</tr>
		</tbody>
	</table>
	<div>
	</br>
	</br>
	
		<?php
	

					$strExportInfo="select 
					sum(disch_load20) as disch_load20, 
					sum(disch_load40) as disch_load40,
					sum(disch_mty20) as disch_mty20,
					sum(disch_mty40) as disch_mty40,
					(sum(disch_load20)+sum(disch_load40)*2) as load_teus,
					(sum(disch_mty20)+sum(disch_mty40)*2) as mty_teus,
					
					sum(tot_disch_load20) as tot_disch_load20, 
					sum(tot_disch_load40) as tot_disch_load40,
					sum(tot_disch_mty20) as tot_disch_mty20,
					sum(tot_disch_mty40) as tot_disch_mty40,
					(sum(tot_disch_load20)+sum(tot_disch_load40)*2) as tot_disch_load_teus,
					(sum(tot_disch_mty20)+sum(tot_disch_mty40)*2) as tot_disch_mty_teus,
					
					sum(bal_load20) as bal_load20, 
					sum(bal_load40) as bal_load40,
					sum(bal_mty20) as bal_mty20,
					sum(bal_mty40) as bal_mty40,
					(sum(bal_load20)+sum(bal_load40)*2) as bal_load_teus,
					(sum(bal_mty20)+sum(bal_mty40)*2) as bal_mty_teus    
					
					from
					(
					SELECT inv_unit.id,freight_kind,inv_unit_fcy_visit.time_load,
					substr(ref_equip_type.nominal_length,-2) as siz,
					
					(case when freight_kind != 'MTY' and substr(ref_equip_type.nominal_length,-2)=20
					and inv_unit_fcy_visit.time_load is not null and inv_unit_fcy_visit.time_load>= to_date(concat(to_date('$work_date','yyyy-mm-dd')-1,' 08:00:00'),'yyyy-mm-dd hh:mi:ss')
					and inv_unit_fcy_visit.time_load < to_date(concat($work_date, ' 08:00:00'),'yyyy-mm-dd hh24:mi:ss')
					then 1 else 0 end) as disch_load20,
					(case when freight_kind != 'MTY' and substr(ref_equip_type.nominal_length,-2)!=20 
					and inv_unit_fcy_visit.time_load is not null and inv_unit_fcy_visit.time_load>= to_date(concat(to_date('$work_date','yyyy-mm-dd')-1,' 08:00:00'),'yyyy-mm-dd hh:mi:ss')
					and inv_unit_fcy_visit.time_load < to_date(concat($work_date, ' 08:00:00'),'yyyy-mm-dd hh24:mi:ss')
					then 1 else 0 end) as disch_load40,
					(case when freight_kind = 'MTY' and substr(ref_equip_type.nominal_length,-2)=20
					and inv_unit_fcy_visit.time_load is not null and inv_unit_fcy_visit.time_load>= to_date(concat(to_date('$work_date','yyyy-mm-dd')-1,' 08:00:00'),'yyyy-mm-dd hh:mi:ss')
					and inv_unit_fcy_visit.time_load < to_date(concat($work_date, ' 08:00:00'),'yyyy-mm-dd hh24:mi:ss')
					then 1 else 0 end) as disch_mty20,
					(case when freight_kind = 'MTY' and substr(ref_equip_type.nominal_length,-2)!=20
					and inv_unit_fcy_visit.time_load is not null and inv_unit_fcy_visit.time_load>= to_date(concat(to_date('$work_date','yyyy-mm-dd')-1,' 08:00:00'),'yyyy-mm-dd hh:mi:ss')
					and inv_unit_fcy_visit.time_load < to_date(concat($work_date, ' 08:00:00'),'yyyy-mm-dd hh24:mi:ss')
					then 1 else 0 end) as disch_mty40,
					
					(case when freight_kind != 'MTY' and substr(ref_equip_type.nominal_length,-2)=20
					and inv_unit_fcy_visit.time_load is not null
					then 1 else 0 end) as tot_disch_load20,
					(case when freight_kind != 'MTY' and substr(ref_equip_type.nominal_length,-2)!=20 
					and inv_unit_fcy_visit.time_load is not null
					then 1 else 0 end) as tot_disch_load40,
					(case when freight_kind = 'MTY' and substr(ref_equip_type.nominal_length,-2)=20
					and inv_unit_fcy_visit.time_load is not null
					then 1 else 0 end) as tot_disch_mty20,
					(case when freight_kind = 'MTY' and substr(ref_equip_type.nominal_length,-2)!=20
					and inv_unit_fcy_visit.time_load is not null
					then 1 else 0 end) as tot_disch_mty40,
					
					(case when freight_kind != 'MTY' and substr(ref_equip_type.nominal_length,-2)=20
					and inv_unit_fcy_visit.time_load is null
					then 1 else 0 end) as bal_load20,
					(case when freight_kind != 'MTY' and substr(ref_equip_type.nominal_length,-2)!=20 
					and inv_unit_fcy_visit.time_load is null
					then 1 else 0 end) as bal_load40,
					(case when freight_kind = 'MTY' and substr(ref_equip_type.nominal_length,-2)=20
					and inv_unit_fcy_visit.time_load is null
					then 1 else 0 end) as bal_mty20,
					(case when freight_kind = 'MTY' and substr(ref_equip_type.nominal_length,-2)!=20
					and inv_unit_fcy_visit.time_load is null
					then 1 else 0 end) as bal_mty40
					FROM inv_unit
					INNER JOIN inv_unit_fcy_visit ON inv_unit_fcy_visit.unit_gkey=inv_unit.gkey
					INNER JOIN argo_carrier_visit ON argo_carrier_visit.gkey=inv_unit_fcy_visit.actual_ob_cv
					INNER JOIN ref_equipment on ref_equipment.gkey=inv_unit.eq_gkey
					INNER JOIN ref_equip_type on ref_equip_type.gkey=ref_equipment.eqtyp_gkey
					WHERE  argo_carrier_visit.cvcvd_gkey='$vvd_gkey_value'
					) tmp
					";
					

	$rtnExportInfo=oci_parse($con_sparcsn4_oracle,$strExportInfo);
	oci_execute($rtnExportInfo);
	$rowExportInfo=oci_fetch_object($rtnExportInfo);
	
	?>
	<div class="table-responsive">
	<table class="table table-bordered table-responsive table-hover table-striped mb-none">
		<thead>
			<tr class="gridDark">
				<td align="center" colspan="18"><b>EXPORT</b></td>
			</tr>
		</thead>
		<tbody>
			<tr class="gradeX">
				<td align="center" colspan="6"><b>LOADED</b></td>
				<td align="center" colspan="6"><b>TOTAL LOADED</b></td>
				<td align="center" colspan="6"><b>BALANCE TO BE SHIPPED</b></td>
			</tr>
			<tr class="gradeX">
				<td align="center" colspan="2"><b>LADEN</b></td>
				<td align="center" colspan="2"><b>EMPTY</b></td>
				<td align="center" colspan="2"><b>TEUS</b></td>
				<td align="center" colspan="2"><b>LADEN</b></td>
				<td align="center" colspan="2"><b>EMPTY</b></td>
				<td align="center" colspan="2"><b>TEUS</b></td>
				<td align="center" colspan="2"><b>LADEN</b></td>
				<td align="center" colspan="2"><b>EMPTY</b></td>
				<td align="center" colspan="2"><b>TEUS</b></td>
			</tr>
			<tr class="gradeX">
				<td><b>20</b></td>
				<td><b>40</b></td>
				<td><b>20</b></td>
				<td><b>40</b></td>
				<td><b>LT</b></td>
				<td><b>MT</b></td>
				<td><b>20</b></td>
				<td><b>40</b></td>
				<td><b>20</b></td>
				<td><b>40</b></td>
				<td><b>LT</b></td>
				<td><b>MT</b></td>
				<td><b>20</b></td>
				<td><b>40</b></td>
				<td><b>20</b></td>
				<td><b>40</b></td>
				<td><b>LT</b></td>
				<td><b>MT</b></td>
			</tr>
			<tr class="gradeX">
			<td><?php echo $rowExportInfo->DISCH_LOAD20 ;?></td>
			<td><?php echo $rowExportInfo->DISCH_LOAD40;?></td>
			<td><?php echo $rowExportInfo->DISCH_MTY20;?></td>
			<td><?php echo $rowExportInfo->DISCH_MTY40;?></td>
			<td><?php echo $rowExportInfo->LOAD_TEUS;?></td>
			<td><?php echo $rowExportInfo->MTY_TEUS;?></td>
			
			<td><?php echo $rowExportInfo->TOT_DISCH_LOAD20;?></td>
			<td><?php echo $rowExportInfo->TOT_DISCH_LOAD40;?></td>
			<td><?php echo $rowExportInfo->TOT_DISCH_MTY20; ?></td>
			<td><?php echo $rowExportInfo->TOT_DISCH_MTY40;?></td>
			<td><?php echo $rowExportInfo->TOT_DISCH_LOAD_TEUS;?></td>
			<td><?php echo $rowExportInfo->TOT_DISCH_MTY_TEUS;?></td>
			
			<td><?php echo $rowExportInfo->BAL_LOAD20;?></td>
			<td><?php echo $rowExportInfo->BAL_LOAD40;?></td>
			<td><?php echo $rowExportInfo->BAL_MTY20; ?></td>
			<td><?php echo $rowExportInfo->BAL_MTY40;?></td>
			<td><?php echo $rowExportInfo->BAL_LOAD_TEUS;?></td>
			<td><?php echo $rowExportInfo->BAL_MTY_TEUS;?></td>
			</tr>
		</tbody>
	</table>
</div>
</br>
</br>
<div class="table-responsive">
<table class="table table-bordered table-responsive table-hover table-striped mb-none">
	<thead>
		<tr>
			<td align="center" colspan="2" ><b>PROGRAM</b></td>
		</tr>
	</thead>
	<tbody>
		<tr>
			<td align="center" ><b>IMPORT-NIL</b></td>
			<td align="center" ><b>EXPORT-NIL</b></td>
		</tr>
	</tbody>
</table>
</div>
</br>
</br>
<table width="100%" border ='0' cellpadding='0' cellspacing='0' align="center">
	<tr>
		<td align="left" ><b><?php echo $work_date;?></b></td>
		<td align="right" ><b>SHIP/YARD PLANNER</b></td>
	</tr>
</table>
<?php if(@$_POST['options']=='html'){?>

<?php } ?>
<?php 
mysqli_close($con_sparcsn4);
