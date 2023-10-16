<?php if($_POST['options']=='html'){?>
<html>
	<head>
	
	</head>
	<body>
	<?php } 
	 else if($_POST['options']=='xl'){
	  header("Content-type: application/octet-stream");
	  header("Content-Disposition: attachment; filename=misA23_1_".str_replace('-','_',$fromdate).".xls;");
	  header("Content-Type: application/ms-excel");
	  header("Pragma: no-cache");
	  header("Expires: 0");

	 }
	 ?>
		<table align="center">
			<tr align="center">
				<td>&nbsp;</td>
			</tr>
			<tr align="center">
				<td>
					<h2><img align="middle"  width="235px" height="75px" src="<?php echo IMG_PATH?>cpanew.jpg"><br/>OFFICE OF THE TERMINAL MANAGER</h2>
					<p><b><?php echo "PERFORMANCE OF CONTAINER VESSELS DURING LAST 24 HRS. CLOSING AT 08:00 HRS. On ".$fromdate." IN TERM OF BOXES AND TUES";?></b></p>
				</td>
			</tr>
			<tr align="center">
				<td>
					<table border="1" cellspacing="0" cellpadding="0">
						<tr align="center" style="font-size: 75%;">
							<th rowspan="4">BERTH NO</th><th rowspan="4">NAME OF VESSELS</th><th rowspan="4">SHIPPING AGENT</th><th rowspan="4">BERTH OPERATOR</th><th colspan="2" rowspan="2">ARRIVAL</th><th colspan="2" rowspan="2">SAILING</th><th colspan="18">IMPORT</th><th colspan="4" rowspan="2">SPACE ALLOCATION</th><th colspan="18">EXPORT</th><th rowspan="3" colspan="2">PROGRAM</th>
						</tr>
						<tr align="center" style="font-size: 75%;">
							<th colspan="6">LAST 24 HRS. DISCHARGED</th><th colspan="6">TOTAL DISCHARGED</th><th colspan="6">BALANCE ON BOARD</th><th colspan="6">LAST 24 HRS. SHIPMENT</th><th colspan="6">TOTAL SHIPMENT</th><th colspan="6">BALANCE TO BE SHIPPED</th>
						</tr>
						<tr align="center" style="font-size: 75%;">
							<th rowspan="2">DATE</th><th rowspan="2">TIME</th><th rowspan="2">DATE</th><th rowspan="2">TIME</th><th colspan="2">LOAD</th><th colspan="2">EMPTY</th><th colspan="2">TEUS</th><th colspan="2">LOAD</th><th colspan="2">EMPTY</th><th colspan="2">TEUS</th><th colspan="2">LOAD</th><th colspan="2">EMPTY</th><th colspan="2">TEUS</th><th colspan="2">FCL</th><th colspan="2">LCL</th><th colspan="2">LOAD</th><th colspan="2">EMPTY</th><th colspan="2">TEUS</th><th colspan="2">LOAD</th><th colspan="2">EMPTY</th><th colspan="2">TEUS</th><th colspan="2">LOAD</th><th colspan="2">EMPTY</th><th colspan="2">TEUS</th>
						</tr>
						<tr align="center" style="font-size: 75%;">
							<th>20</th><th>40</th><th>20</th><th>40</th><th>LD</th><th>MT</th><th>20</th><th>40</th><th>20</th><th>40</th><th>LD</th><th>MT</th><th>20</th><th>40</th><th>20</th><th>40</th><th>LD</th><th>MT</th><th>20</th><th>40</th><th>20</th><th>40</th><th>20</th><th>40</th><th>20</th><th>40</th><th>LD</th><th>MT</th><th>20</th><th>40</th><th>20</th><th>40</th><th>LD</th><th>MT</th><th>20</th><th>40</th><th>20</th><th>40</th><th>LD</th><th>MT</th><th>IMP</th><th>EXP</th>
						</tr>
						
						<?php 
						include("dbConection.php");
						include("dbOracleConnection.php");	

						// $str ="select 
						// if(substr(sparcsn4.argo_quay.id,1,1)='G',1,if(substr(sparcsn4.argo_quay.id,1,1)='C',2,3)) as berth_sl,
						// sparcsn4.argo_quay.id as berth,Y.id as agent,
						// sparcsn4.argo_carrier_visit.id,
						// sparcsn4.vsl_vessel_visit_details.flex_date07 as ffd,
						// sparcsn4.vsl_vessels.name,
						// ifnull(sparcsn4.vsl_vessel_visit_details.flex_string03,sparcsn4.vsl_vessel_visit_details.flex_string02) as berthop,
						// sparcsn4.vsl_vessel_berthings.ata ata,
						// sparcsn4.argo_carrier_visit.atd atd,
						// '' as pr_imp,
						// '' as pr_exp,
						// (IFNULL(sparcsn4.argo_carrier_visit.atd,(SELECT sparcsn4.argo_visit_details.etd FROM sparcsn4.argo_visit_details WHERE sparcsn4.argo_visit_details.gkey=sparcsn4.argo_carrier_visit.cvcvd_gkey LIMIT 1))) AS etd,sparcsn4.argo_carrier_visit.gkey,sparcsn4.argo_carrier_visit.cvcvd_gkey


						// FROM sparcsn4.argo_carrier_visit
						

						// inner join sparcsn4.vsl_vessel_visit_details on sparcsn4.vsl_vessel_visit_details.vvd_gkey=sparcsn4.argo_carrier_visit.cvcvd_gkey
						// inner join sparcsn4.vsl_vessels on sparcsn4.vsl_vessels.gkey=sparcsn4.vsl_vessel_visit_details.vessel_gkey
						// inner join sparcsn4.vsl_vessel_berthings on sparcsn4.vsl_vessel_berthings.vvd_gkey=sparcsn4.vsl_vessel_visit_details.vvd_gkey
						// inner join sparcsn4.argo_quay on sparcsn4.argo_quay.gkey=sparcsn4.vsl_vessel_berthings.quay
						// INNER JOIN
						//   ( sparcsn4.ref_bizunit_scoped r
						//  LEFT JOIN ( sparcsn4.ref_agent_representation X
						//  LEFT JOIN sparcsn4.ref_bizunit_scoped Y ON X.agent_gkey=Y.gkey )
						//  ON r.gkey=X.bzu_gkey
						//   )  ON r.gkey = sparcsn4.argo_carrier_visit.operator_gkey

						// where (
						// (sparcsn4.argo_carrier_visit.ata between concat(DATE_ADD('$fromdate', INTERVAL -1 DAY),' 08:00:00') and concat('$fromdate',' 08:00:01'))
						// or(sparcsn4.argo_carrier_visit.ata < concat('$fromdate',' 08:00:01') and sparcsn4.argo_carrier_visit.atd is null)
						// or (sparcsn4.argo_carrier_visit.atd between concat(DATE_ADD('$fromdate', INTERVAL -1 DAY),' 08:00:00') and concat('$fromdate',' 08:00:01')) 
						// ) 
						// and sparcsn4.argo_carrier_visit.carrier_mode='VESSEL' and  sparcsn4.argo_carrier_visit.phase!='80CANCELED'
						// order by 1,2";



						$str ="    SELECT 
   
						(Case
						when substr(argo_quay.id,1,1)='G' then 1
						else
						(Case
						when substr(argo_quay.id,1,1)='C' then 2
						else
						3
						end)
						End) as berth_sl,
						to_char( vsl_vessel_berthings.ata, 'dd-mm-yyyy') AS ata_dt,
				
						argo_quay.id AS berth,Y.id AS agent,
						argo_carrier_visit.id,
						vsl_vessel_visit_details.flex_date07 AS ffd,
						vsl_vessels.name,
						vsl_vessel_berthings.ata ata,
						argo_carrier_visit.atd atd,
						'' AS pr_imp,
						'' AS pr_exp,
						NVL(vsl_vessel_visit_details.flex_string03,vsl_vessel_visit_details.flex_string02) AS berthop,
						argo_carrier_visit.gkey,
						argo_carrier_visit.cvcvd_gkey,
						vsl_vessel_visit_details.ib_vyg, 
						(NVL(argo_carrier_visit.atd,(SELECT argo_visit_details.etd FROM argo_visit_details WHERE argo_visit_details.gkey=argo_carrier_visit.cvcvd_gkey FETCH FIRST 1 ROWS ONLY))) AS etd
						FROM argo_carrier_visit
						INNER JOIN vsl_vessel_visit_details ON vsl_vessel_visit_details.vvd_gkey=argo_carrier_visit.cvcvd_gkey
						INNER JOIN vsl_vessels ON vsl_vessels.gkey=vsl_vessel_visit_details.vessel_gkey
						INNER JOIN vsl_vessel_berthings ON vsl_vessel_berthings.vvd_gkey=vsl_vessel_visit_details.vvd_gkey
						INNER JOIN argo_quay ON argo_quay.gkey=vsl_vessel_berthings.quay
						INNER JOIN
						( ref_bizunit_scoped r
						LEFT JOIN ( ref_agent_representation X
						LEFT JOIN ref_bizunit_scoped Y ON X.agent_gkey=Y.gkey )
						ON r.gkey=X.bzu_gkey
						)  ON r.gkey = argo_carrier_visit.operator_gkey
				
				
				  		WHERE 
						(  
						(argo_carrier_visit.ata  between to_date(concat('2022-12-08',' 08:00:00'),'YYYY-MM-DD HH24-MI-SS' )-1 and to_date(concat('2022-12-08',' 20:00:00'),'YYYY-MM-DD HH24-MI-SS'))
						OR(argo_carrier_visit.ata < to_date(CONCAT('2022-12-08',' 08:00:01'),'YYYY-MM-DD HH24-MI-SS') AND argo_carrier_visit.atd IS NULL)
						OR(argo_carrier_visit.atd  between to_date(concat('2022-12-08',' 08:00:00'),'YYYY-MM-DD HH24-MI-SS' )-1 and to_date(concat('2022-12-08',' 20:00:00'),'YYYY-MM-DD HH24-MI-SS'))
						)
						AND argo_carrier_visit.carrier_mode='VESSEL' AND  argo_carrier_visit.phase!='80CANCELED'
						ORDER BY 1,2";


						//echo $str;
						$res =oci_parse($con_sparcsn4_oracle,$str);	
						$row = oci_execute($res);
						$ald20 = 0;
						$ald40 = 0;
						$amt20 = 0;
						$amt40 = 0;
						$cld20 = 0;
						$cld40 = 0;
						$cmt20 = 0;
						$cmt40 = 0;
						
						$dld20 = 0;
						$dld40 = 0;
						$dmt20 = 0;
						$dmt40 = 0;
						$arr = array();
						$b = "";
						$imp_tues = 0;
						$exp_tues = 0;
						$totImpVsl = 0;
						$totExpVsl = 0;
						$totImpExpVsl = 0;
						while($row=oci_fetch_object($res)!=false) 
						{
							
							$agent =$row->AGENT;
							$arcarId =$row->ID;
							$ffd =$row->FFD;
							$vslname =$row->NAME;
							$berth =$row->BERTH;
							$berthop =$row->BERTHOP;
							$ata =$row->ATA;
							$atd =$row->ATD;
							$pr_imp =$row->PR_IMP;
							$pr_exp =$row->PR_EXP;
							$etd =$row->ETD ;
							$gkey =$row->GKEY;
							$cvcvd_gkey =$row->CVCVD_GKEY;
							$sld = 0;
							if($atd !=null)
							{	
								$sld = 1;
							}
							
							$berthopPart = explode(" ", $berthop);	
							
							$ataPart = explode(" ", $ata);
							$ataDatePart = explode("-", $ataPart[0]);
							
							$atdPart = explode(" ", $atd);							
							$atdDatePart = explode("-", $atdPart[0]);
						

							$strGetGky = "SELECT inv_unit.gkey
							FROM inv_unit
							INNER JOIN inv_unit_fcy_visit ON inv_unit_fcy_visit.unit_gkey=inv_unit.gkey
							WHERE inv_unit_fcy_visit.actual_ib_cv=$gkey AND inv_unit.gkey NOT IN
							(
							SELECT inv_unit.gkey
							FROM inv_unit
							INNER JOIN inv_unit_fcy_visit ON inv_unit_fcy_visit.unit_gkey=inv_unit.gkey
							WHERE inv_unit_fcy_visit.actual_ib_cv=$gkey
							)
							";
							
							$resGetGky = oci_parse($con_sparcsn4_oracle,$strGetGky);
							$row = oci_execute($resGetGky);
							while($rowGetGky = oci_fetch_object($resGetGky))
							{
								$missingGky = $rowGetGky->gkey;

								$strDscTime1 = "
								SELECT ctmsmis.mis_disch_cont.gkey
								FROM ctmsmis.mis_disch_cont
								WHERE ctmsmis.mis_disch_cont.gkey='$missingGky'";



								$strDscTime = "select inv_move_event.t_discharge from inv_unit_fcy_visit 
								inner join inv_move_event on inv_move_event.ufv_gkey=inv_unit_fcy_visit.gkey
								where unit_gkey=$missingGky and inv_move_event.move_kind='DSCH'";
								//echo $strDscTime."<br>";
								$resDscTime = mysqli_query($con_sparcsn4,$strDscTime1);
								$resDscTime = mysqli_query($con_sparcsn4,$strDscTime);
								$rowDscTime = mysqli_fetch_object($resDscTime);
								$disTime = $rowDscTime->t_discharge;
								$numrow = mysqli_num_rows($resDscTime);								
								if($numrow>0 and $disTime!=null)
								{
									mysqli_query($con_sparcsn4,"insert into ctmsmis.mis_disch_cont(gkey,disch_dt) values('$missingGky','$disTime')");
								}
							}
							
						

							$strImportData = "SELECT inv_unit.id,freight_kind,category,inv_unit_fcy_visit.actual_ob_cv,
							SUBSTR(ref_equip_type.nominal_length,2) AS siz,inv_unit.gkey,ref_equip_type.nominal_length
							FROM inv_unit
							INNER JOIN inv_unit_fcy_visit ON inv_unit_fcy_visit.unit_gkey=inv_unit.gkey
							INNER JOIN argo_carrier_visit ON argo_carrier_visit.gkey=inv_unit_fcy_visit.actual_ib_cv
							INNER JOIN ref_equipment ON ref_equipment.gkey=inv_unit.eq_gkey
							INNER JOIN ref_equip_type ON ref_equip_type.gkey=ref_equipment.eqtyp_gkey
							WHERE  inv_unit_fcy_visit.actual_ob_cv='$gkey'";

							$query=oci_parse($con_sparcsn4_oracle,$strImportData);
							$row=oci_execute($query);


						
					


							$import_dis_20_load_cur=0;
							$import_dis_40_load_cur=0;
							$import_dis_20_mty_cur=0;
							$import_dis_40_mty_cur=0;

							$import_dis_20_load_tot=0;
							$import_dis_40_load_tot=0;
							$import_dis_20_mty_tot= 0;
							$import_dis_40_mty_tot=0;


							$tot_imp_loaded_on_vsl_20_cur=0;
							$tot_imp_loaded_on_vsl_40_cur= 0;
							$tot_imp_loaded_on_vsl_20_mty=0;
							$tot_imp_loaded_on_vsl_40_mty=0;

							

							$import_bal_20_load_tot=0;
							$import_bal_40_load_tot=0;
							$import_bal_20_mty_tot=0;
							$import_bal_40_mty_tot=0;
	   
							while(($row=oci_fetch_object($query)) != false) {
							   $gKey="";
							   $freightKind="";
							   $nominalLength="";
							   
							   $gKey=$row->GKEY;
							   $freightKind=$row->FREIGHT_KIND ;
							   $category=$row->CATEGORY;

							   $nominalLength=$row->NOMINAL_LENGTH;
							 

			

							      $strImportData = "
								  SELECT 
								  (CASE WHEN ctmsmis.mis_disch_cont.disch_dt >CONCAT(DATE_ADD('$fromdate', INTERVAL -1 DAY),' 08:00:00') 
								  AND ctmsmis.mis_disch_cont.disch_dt <CONCAT('$fromdate',' 08:00:01')
								  AND '$category' ='IMPRT' AND RIGHT('$nominalLength',2) =20 AND '$freight_kind' IN('FCL','LCL')
								  THEN 1 ELSE 0 END) AS import_dis_20_load_cur
								  
								  
								  ,(CASE WHEN ctmsmis.mis_disch_cont.disch_dt >CONCAT(DATE_ADD('$fromdate', INTERVAL -1 DAY),' 08:00:00') 
								  AND ctmsmis.mis_disch_cont.disch_dt <CONCAT('$fromdate',' 08:00:01')
								  AND '$category' ='IMPRT' AND RIGHT('$nominalLength',2) !=20 AND '$freight_kind' IN('FCL','LCL')

								  THEN 1 ELSE 0 END) AS import_dis_40_load_cur
								  
								  
								  ,(CASE WHEN ctmsmis.mis_disch_cont.disch_dt >CONCAT(DATE_ADD('$fromdate', INTERVAL -1 DAY),' 08:00:00') 
								  AND ctmsmis.mis_disch_cont.disch_dt <CONCAT('$fromdate',' 08:00:01')
								  AND '$category' ='IMPRT' AND RIGHT('$nominalLength',2) =20 AND '$freight_kind'='MTY'

								  THEN 1 ELSE 0 END) AS import_dis_20_mty_cur
								  
								  
								  ,(CASE WHEN ctmsmis.mis_disch_cont.disch_dt >CONCAT(DATE_ADD('$fromdate', INTERVAL -1 DAY),' 08:00:00') 
								  AND ctmsmis.mis_disch_cont.disch_dt <CONCAT('$fromdate',' 08:00:01')
								  AND '$category' ='IMPRT' AND RIGHT('$nominalLength',2) !=20 AND '$freight_kind'='MTY'
								  THEN 1 ELSE 0 END) AS import_dis_40_mty_cur
								  
								  ,(CASE WHEN ctmsmis.mis_disch_cont.disch_dt <CONCAT('$fromdate',' 08:00:01')
								  AND '$category' ='IMPRT' AND RIGHT('$nominalLength',2) =20 AND '$freight_kind' IN('FCL','LCL')
								  THEN 1 ELSE 0 END) AS import_dis_20_load_tot
								  ,(CASE WHEN ctmsmis.mis_disch_cont.disch_dt <CONCAT('$fromdate',' 08:00:01')
								  AND '$category' ='IMPRT' AND RIGHT('$nominalLength',2) !=20 AND '$freight_kind' IN('FCL','LCL')
								  THEN 1 ELSE 0 END) AS import_dis_40_load_tot
								  ,(CASE WHEN ctmsmis.mis_disch_cont.disch_dt <CONCAT('$fromdate',' 08:00:01')
								  AND '$category' ='IMPRT' AND RIGHT('$nominalLength',2) =20 AND '$freight_kind'='MTY'
								  THEN 1 ELSE 0 END) AS import_dis_20_mty_tot
								  ,(CASE WHEN ctmsmis.mis_disch_cont.disch_dt <CONCAT('$fromdate',' 08:00:01')
								  AND '$category' ='IMPRT' AND RIGHT('$nominalLength',2) !=20 AND '$freight_kind'='MTY'
								  THEN 1 ELSE 0 END) AS import_dis_40_mty_tot
								  
								  ,(CASE WHEN '$category' ='IMPRT' AND RIGHT('$nominalLength',2) =20 AND '$freight_kind' IN('FCL','LCL')
								  THEN 1 ELSE 0 END) AS tot_imp_loaded_on_vsl_20_cur
								  ,(CASE WHEN '$category' ='IMPRT' AND RIGHT('$nominalLength',2) !=20 AND '$freight_kind' IN('FCL','LCL')
								  THEN 1 ELSE 0 END) AS tot_imp_loaded_on_vsl_40_cur
								  ,(CASE WHEN '$category' ='IMPRT' AND RIGHT('$nominalLength',2) =20 AND '$freight_kind'='MTY'
								  THEN 1 ELSE 0 END) AS tot_imp_loaded_on_vsl_20_mty
								  ,
								  
								  
								  (CASE WHEN '$category' ='IMPRT' AND RIGHT('$nominalLength',2) !=20 AND '$freight_kind'='MTY'
								  THEN 1 ELSE 0 END) AS tot_imp_loaded_on_vsl_40_mty
								  FROM ctmsmis.mis_disch_cont
								  WHERE ctmsmis.mis_disch_cont.gkey='$gKey' 
								  ";

								  $resImportData =mysqli_query($con_sparcsn4,$strImportData);
								  $rowImportData=mysqli_fetch_object($resImportData);

							//    $qurey1Res = mysqli_fetch_object($query1);
							//    $numrows1=mysqli_num_rows($query1);
	   
							   if($rowImportData>0){
	   
								$import_dis_20_load_cur=$import_dis_20_load_cur+$rowImportData->$import_dis_20_load_cur;
								$import_dis_40_load_cur=$import_dis_40_load_cur+$rowImportData->$import_dis_40_load_cur;
								$import_dis_20_mty_cur=$import_dis_20_mty_cur+$rowImportData->$import_dis_20_mty_cur;
								$import_dis_40_mty_cur=$import_dis_40_mty_cur+$rowImportData->$import_dis_40_mty_cur;
						
	
								$import_dis_20_load_tot=$import_dis_20_load_tot+$rowImportData->$import_dis_20_load_tot;
								$import_dis_40_load_tot=$import_dis_40_load_tot+$rowImportData->$import_dis_40_load_tot;
								$import_dis_20_mty_tot= $import_dis_20_mty_tot+$rowImportData->$import_dis_20_mty_tot;
								$import_dis_40_mty_tot=$import_dis_40_mty_tot+$rowImportData->$import_dis_40_mty_tot;
	
								$tot_imp_loaded_on_vsl_20_cur=$tot_imp_loaded_on_vsl_20_cur+$rowImportData->$tot_imp_loaded_on_vsl_20_cur;
								$tot_imp_loaded_on_vsl_40_cur= $tot_imp_loaded_on_vsl_40_cur+$rowImportData->tot_imp_loaded_on_vsl_40_cur;
								$tot_imp_loaded_on_vsl_20_mty= $tot_imp_loaded_on_vsl_20_mty+$rowImportData->$tot_imp_loaded_on_vsl_20_mty;
								$tot_imp_loaded_on_vsl_40_mty=$tot_imp_loaded_on_vsl_40_mty+$rowImportData->$tot_imp_loaded_on_vsl_40_mty;
	
								$tot_imp_loaded_on_vsl_20_cur-$import_dis_20_load_tot=$import_bal_20_load_tot;
								$tot_imp_loaded_on_vsl_40_cur-$import_dis_40_load_tot=$import_bal_40_load_tot;
								$tot_imp_loaded_on_vsl_20_mty-$import_dis_20_mty_tot=$import_bal_20_mty_tot;
								$tot_imp_loaded_on_vsl_40_mty-$import_dis_40_mty_tot= $import_bal_40_mty_tot;

							
								   
							   }
	   
	   
						   }





					


							$strExportData = "SELECT inv_unit.id,freight_kind,category,inv_unit_fcy_visit.actual_ob_cv,
							SUBSTR(ref_equip_type.nominal_length,2) AS siz,inv_unit.gkey,ref_equip_type.nominal_length
							FROM inv_unit
							INNER JOIN inv_unit_fcy_visit ON inv_unit_fcy_visit.unit_gkey=inv_unit.gkey
							INNER JOIN argo_carrier_visit ON argo_carrier_visit.gkey=inv_unit_fcy_visit.actual_ib_cv
							INNER JOIN ref_equipment ON ref_equipment.gkey=inv_unit.eq_gkey
							INNER JOIN ref_equip_type ON ref_equip_type.gkey=ref_equipment.eqtyp_gkey
							WHERE  inv_unit_fcy_visit.actual_ob_cv='$gkey'";

							$query1=oci_parse($con_sparcsn4_oracle,$strExportData);
							$row=oci_execute($query);


							$exp_dis_20_load_cur=0;
							$exp_dis_40_load_cur=0;
							$exp_dis_20_mty_cur=0;
							$exp_dis_40_mty_cur=0;

							$exp_dis_20_load_tot=0;
							$exp_dis_40_load_tot=0;
							$exp_dis_20_mty_tot=0;
							$exp_dis_40_mty_tot=0;

							$tot_exp_loaded_on_vsl_20_cur=0;
							$tot_exp_loaded_on_vsl_40_cur=0;
							$tot_exp_loaded_on_vsl_20_mty=0;
							$tot_exp_loaded_on_vsl_40_mty=0;

							$exp_bal_20_load_tot=0;
							$exp_bal_40_load_tot=0;
							$exp_bal_20_mty_tot=0;
							$exp_bal_40_mty_tot=0;
	   
							while(($row=oci_fetch_object($query1)) != false) {
							   $gKey="";
							   $freightKind="";
							   $nominalLength="";
							   
							   $gKey=$row->GKEY;
							   $freightKind=$row->FREIGHT_KIND ;
							   $category=$row->CATEGORY;

							   $nominalLength=$row->NOMINAL_LENGTH;
							 

							      $str1 = "
								
							SELECT (
								SELECT ctmsmis.berth_for_cont('',ctmsmis.mis_exp_unit.last_update)) AS brt,
								(CASE WHEN ctmsmis.mis_exp_unit.last_update >CONCAT(DATE_ADD('$fromdate', INTERVAL -1 DAY),' 08:00:00')
								AND ctmsmis.mis_exp_unit.last_update <CONCAT('$fromdate',' 08:00:01')
								AND '$category' ='EXPRT' AND RIGHT('$nominalLength',2) =20 
								AND ctmsmis.mis_exp_unit.cont_status IN('FCL','LCL') 
								THEN 1 ELSE 0 END) AS exp_dis_20_load_cur
								,(CASE WHEN ctmsmis.mis_exp_unit.last_update >CONCAT(DATE_ADD('$fromdate', INTERVAL -1 DAY),' 08:00:00')
								AND ctmsmis.mis_exp_unit.last_update <CONCAT('$fromdate',' 08:00:01')
								AND '$category' ='EXPRT' AND RIGHT('$nominalLength',2) !=20 
								AND ctmsmis.mis_exp_unit.cont_status IN('FCL','LCL') 
								THEN 1 ELSE 0 END) AS exp_dis_40_load_cur
								,(CASE WHEN ctmsmis.mis_exp_unit.last_update >CONCAT(DATE_ADD('$fromdate', INTERVAL -1 DAY),' 08:00:00')
								AND ctmsmis.mis_exp_unit.last_update <CONCAT('$fromdate',' 08:00:01')
								AND '$category' ='EXPRT' AND RIGHT('$nominalLength',2) =20 
								AND '$freight_kind' ='MTY' 
								THEN 1 ELSE 0 END) AS exp_dis_20_mty_cur
								,(CASE WHEN ctmsmis.mis_exp_unit.last_update >CONCAT(DATE_ADD('$fromdate', INTERVAL -1 DAY),' 08:00:00')
								AND ctmsmis.mis_exp_unit.last_update <CONCAT('$fromdate',' 08:00:01')
								AND '$category' ='EXPRT' AND RIGHT('$nominalLength',2) !=20 
								AND '$freight_kind' ='MTY' 
								THEN 1 ELSE 0 END) AS exp_dis_40_mty_cur
	
								,(CASE WHEN ctmsmis.mis_exp_unit.last_update <CONCAT('$fromdate',' 08:00:01')
								AND '$category'='EXPRT' AND RIGHT('$nominalLength',2) =20 
								AND '$freight_kind' IN('FCL','LCL') 
								THEN 1 ELSE 0 END) AS exp_dis_20_load_tot
								,(CASE WHEN ctmsmis.mis_exp_unit.last_update <CONCAT('$fromdate',' 08:00:01')
								AND '$category' ='EXPRT' AND RIGHT('$nominalLength',2) !=20 
								AND '$freight_kind' IN('FCL','LCL') 
								THEN 1 ELSE 0 END) AS exp_dis_40_load_tot
								,(CASE WHEN ctmsmis.mis_exp_unit.last_update <CONCAT('$fromdate',' 08:00:01')
								AND '$category' ='EXPRT' AND RIGHT('$nominalLength',2) =20 
								AND '$freight_kind' ='MTY'
								THEN 1 ELSE 0 END) AS exp_dis_20_mty_tot
								,(CASE WHEN ctmsmis.mis_exp_unit.last_update <CONCAT('$fromdate',' 08:00:01')
								AND '$category'='EXPRT' AND RIGHT('$nominalLength',2) !=20 
								AND '$freight_kind' ='MTY'
								THEN 1 ELSE 0 END) AS exp_dis_40_mty_tot
	
								,(CASE WHEN '$category' ='EXPRT' AND RIGHT('$nominalLength',2) =20 
								AND '$freight_kind' IN('FCL','LCL')
								THEN 1 ELSE 0 END) AS tot_exp_loaded_on_vsl_20_cur
								,(CASE WHEN '$category' ='EXPRT' AND RIGHT('$nominalLength',2) !=20 
								AND '$freight_kind' IN('FCL','LCL')
								THEN 1 ELSE 0 END) AS tot_exp_loaded_on_vsl_40_cur
								,(CASE WHEN '$category' ='EXPRT' AND RIGHT('$nominalLength',2) =20 
								AND '$freight_kind' ='MTY'
								THEN 1 ELSE 0 END) AS tot_exp_loaded_on_vsl_20_mty
								,(CASE WHEN '$category' ='EXPRT' AND RIGHT('$nominalLength',2) !=20 
								AND '$freight_kind' ='MTY'
								THEN 1 ELSE 0 END) AS tot_exp_loaded_on_vsl_40_mty
	
								FROM ctmsmis.mis_disch_cont
								WHERE ctmsmis.mis_disch_cont.gkey='$gKey' 
								  ";

							

							   $resExportData =mysqli_query($con_sparcsn4,$strExportData);
							   $rowExportData=mysqli_fetch_object($resExportData);
	   
							   if($resExportData>0){
	   

							

								$exp_dis_20_load_cur=$exp_dis_20_load_cur+$resExportData->$exp_dis_20_load_cur;
								$exp_dis_40_load_cur=$exp_dis_40_load_cur+$resExportData->$exp_dis_40_load_cur;
								$exp_dis_20_mty_cur=$exp_dis_20_mty_cur+$resExportData->$exp_dis_20_mty_cur;
								$exp_dis_40_mty_cur=$exp_dis_40_mty_cur+$resExportData->$exp_dis_40_mty_cur;
						

								$exp_dis_20_load_tot=$exp_dis_20_load_tot+$resExportData->$exp_dis_20_load_tot;
								$exp_dis_40_load_tot=$exp_dis_40_load_tot+$resExportData->$exp_dis_40_load_tot;
								$exp_dis_20_mty_tot= $exp_dis_20_mty_tot+$resExportData->$exp_dis_20_mty_tot;
								$exp_dis_40_mty_tot=$exp_dis_40_mty_tot+$resExportData->$exp_dis_40_mty_tot;
	



								$tot_exp_loaded_on_vsl_20_cur=$tot_exp_loaded_on_vsl_20_cur+$resExportData->$tot_exp_loaded_on_vsl_20_cur;
								$tot_exp_loaded_on_vsl_40_cur= $tot_exp_loaded_on_vsl_40_cur+$resExportData->$tot_exp_loaded_on_vsl_40_cur;
								$tot_exp_loaded_on_vsl_20_mty= $tot_exp_loaded_on_vsl_20_mty+$resExportData->$tot_exp_loaded_on_vsl_20_mty;
								$tot_exp_loaded_on_vsl_40_mty=$tot_exp_loaded_on_vsl_40_mty+$resExportData->$tot_exp_loaded_on_vsl_40_mty;
	
								$exp_bal_20_load_tot=0;
								$exp_bal_40_load_tot=0;
								$exp_bal_20_mty_tot=0;
								$exp_bal_40_mty_tot=0;

							
								$tot_exp_loaded_on_vsl_20_cur-$exp_dis_20_load_tot=$exp_bal_20_load_tot;
								$tot_exp_loaded_on_vsl_40_cur-$exp_dis_40_load_tot=$exp_bal_40_load_tot;
								$tot_exp_loaded_on_vsl_20_mty-$exp_dis_20_mty_tot=$exp_bal_20_mty_tot;
								$tot_exp_loaded_on_vsl_40_mty-$exp_dis_40_mty_tot= $exp_bal_40_mty_tot;
								
							  
							}
	   
	   
						   }

























						
							$imp_tues2 = $rowImportData->import_dis_20_load_cur+$rowImportData->import_dis_40_load_cur*2+$rowImportData->import_dis_20_mty_cur+$rowImportData->import_dis_40_mty_cur*2;
							$exp_tues2 = $rowExportData->exp_dis_20_load_cur+$rowExportData->exp_dis_40_load_cur*2+$rowExportData->exp_dis_20_mty_cur+$rowExportData->exp_dis_40_mty_cur*2;
							
							$totImpBal = $rowImportData->import_bal_20_load_tot+$rowImportData->import_bal_40_load_tot+$rowImportData->import_bal_20_mty_tot+$rowImportData->import_bal_40_mty_tot;
							$expVslVal = "";
							if($totImpBal==0 and $sld==0)
							{
								$totExpVsl = $totExpVsl+1;
								$expVslVal = 2;
							}
							
							$totExpCont = $rowExportData->tot_exp_loaded_on_vsl_20_cur+$rowExportData->tot_exp_loaded_on_vsl_40_cur+$rowExportData->tot_exp_loaded_on_vsl_20_mty+$rowExportData->tot_exp_loaded_on_vsl_40_mty;
							$impVslVal = "";
							if($totExpCont==0  and $sld==0)
							{
								$totImpVsl = $totImpVsl+1;
								$impVslVal = 2;
							}
							
							$impExpVslVal = "";
							$both = 0;
							if($totImpBal>0 and $totExpCont>0  and $sld==0)
							{
								$totImpExpVsl = $totImpExpVsl+1;
								$impExpVslVal = "2+EXP";
								$both = 1;
							}
							
							if($b == $berth)
							{
								array_pop($arr);
								$imp_tues = $imp_tues+$imp_tues2;
								$exp_tues = $exp_tues+$exp_tues2;
							}
							else
							{
								$imp_tues = $imp_tues2;
								$exp_tues = $exp_tues2;
							}
							$b = $berth;
							$ar = array($b,$imp_tues,$exp_tues);
							array_push($arr,$ar);
						?>
						<tr style="font-size: 75%;">							
							<td><?php echo $berth; ?></td>
							<td><?php echo $vslname; ?></td>
							<td><?php echo $agent; ?></td>
							<td><?php echo $berthopPart[0]; ?></td>
							<td><?php if(sizeof($ataDatePart)>1){ echo $ataDatePart[2]."/".$ataDatePart[1]."/".substr($ataDatePart[0],-2);} else echo ""; ?></td>
							<td><?php echo substr($ataPart[1],0,5); ?></td>
							<td><?php if(sizeof($atdDatePart)>1){ echo $atdDatePart[2]."/".$atdDatePart[1]."/".substr($atdDatePart[0],-2);} else echo ""; ?></td>
							<td><?php echo substr($atdPart[1],0,5); ?></td>						
							<td align="center"><?php $ald20 = $ald20+$rowImportData->import_dis_20_load_cur; echo $rowImportData->import_dis_20_load_cur; ?></td>						
							<td align="center"><?php $ald40 = $ald40+$rowImportData->import_dis_40_load_cur; echo $rowImportData->import_dis_40_load_cur; ?></td>						
							<td align="center"><?php $amt20 = $amt20+$rowImportData->import_dis_20_mty_cur; echo $rowImportData->import_dis_20_mty_cur; ?></td>						
							<td align="center"><?php $amt40 = $amt40+$rowImportData->import_dis_40_mty_cur; echo $rowImportData->import_dis_40_mty_cur; ?></td>						
							<td align="center"><?php echo $rowImportData->import_dis_20_load_cur+$rowImportData->import_dis_40_load_cur*2; ?></td>						
							<td align="center"><?php echo $rowImportData->import_dis_20_mty_cur+$rowImportData->import_dis_40_mty_cur*2; ?></td>
							
							<td align="center"><?php echo $rowImportData->import_dis_20_load_tot; ?></td>						
							<td align="center"><?php echo $rowImportData->import_dis_40_load_tot; ?></td>						
							<td align="center"><?php echo $rowImportData->import_dis_20_mty_tot; ?></td>						
							<td align="center"><?php echo $rowImportData->import_dis_40_mty_tot; ?></td>						
							<td align="center"><?php echo $rowImportData->import_dis_20_load_tot+$rowImportData->import_dis_40_load_tot*2; ?></td>						
							<td align="center"><?php echo $rowImportData->import_dis_20_mty_tot+$rowImportData->import_dis_40_mty_tot*2; ?></td>
							
							<td align="center"><?php $cld20 = $cld20+$rowImportData->import_bal_20_load_tot; echo $rowImportData->import_bal_20_load_tot; ?></td>						
							<td align="center"><?php $cld40 = $cld40+$rowImportData->import_bal_40_load_tot; echo $rowImportData->import_bal_40_load_tot; ?></td>						
							<td align="center"><?php $cmt20 = $cmt20+$rowImportData->import_bal_20_mty_tot; echo $rowImportData->import_bal_20_mty_tot; ?></td>						
							<td align="center"><?php $cmt40 = $cmt40+$rowImportData->import_bal_40_mty_tot; echo $rowImportData->import_bal_40_mty_tot; ?></td>						
							<td align="center"><?php echo $rowImportData->import_bal_20_load_tot+$rowImportData->import_bal_40_load_tot*2; ?></td>						
							<td align="center"><?php echo $rowImportData->import_bal_20_mty_tot+$rowImportData->import_bal_40_mty_tot*2; ?></td>
							
							<td align="center">0</td>
							<td align="center">0</td>
							<td align="center">0</td>
							<td align="center">0</td>
							
							<td align="center"><?php $dld20 = $dld20+$rowExportData->exp_dis_20_load_cur; echo $rowExportData->exp_dis_20_load_cur; ?></td>						
							<td align="center"><?php $dld40 = $dld40+$rowExportData->exp_dis_40_load_cur; echo $rowExportData->exp_dis_40_load_cur; ?></td>						
							<td align="center"><?php $dmt20 = $dmt20+$rowExportData->exp_dis_20_mty_cur; echo $rowExportData->exp_dis_20_mty_cur; ?></td>						
							<td align="center"><?php $dmt40 = $dmt40+$rowExportData->exp_dis_40_mty_cur; echo $rowExportData->exp_dis_40_mty_cur; ?></td>						
							<td align="center"><?php echo $rowExportData->exp_dis_20_load_cur+$rowExportData->exp_dis_40_load_cur*2; ?></td>						
							<td align="center"><?php echo $rowExportData->exp_dis_20_mty_cur+$rowExportData->exp_dis_40_mty_cur*2; ?></td>
							
							<td align="center"><?php echo $rowExportData->exp_dis_20_load_tot; ?></td>						
							<td align="center"><?php echo $rowExportData->exp_dis_40_load_tot; ?></td>						
							<td align="center"><?php echo $rowExportData->exp_dis_20_mty_tot; ?></td>						
							<td align="center"><?php echo $rowExportData->exp_dis_40_mty_tot; ?></td>						
							<td align="center"><?php echo $rowExportData->exp_dis_20_load_tot+$rowExportData->exp_dis_40_load_tot*2; ?></td>						
							<td align="center"><?php echo $rowExportData->exp_dis_20_mty_tot+$rowExportData->exp_dis_40_mty_tot*2; ?></td>
							
							<td align="center"><?php echo $rowExportData->exp_bal_20_load_tot; ?></td>						
							<td align="center"><?php echo $rowExportData->exp_bal_40_load_tot; ?></td>						
							<td align="center"><?php echo $rowExportData->exp_bal_20_mty_tot; ?></td>						
							<td align="center"><?php echo $rowExportData->exp_bal_40_mty_tot; ?></td>						
							<td align="center"><?php echo $rowExportData->exp_bal_20_load_tot+$rowExportData->exp_bal_40_load_tot*2; ?></td>						
							<td align="center"><?php echo $rowExportData->exp_bal_20_mty_tot+$rowExportData->exp_bal_40_mty_tot*2; ?></td>
							<?php if($sld>0){?>
								<td align="center" colspan="2">SAILED</td>
							<?php } else if($both>0){?>
								<td align="center" colspan="2"><?php echo $impExpVslVal; ?></td>
							<?php } else {?>							
							<td align="center"><?php echo $impVslVal; ?></td>
							<td align="center"><?php echo $expVslVal; ?></td>
							<?php }?>
						</tr>	
						<?php
						}
						?>
						<tr style="font-size: 75%;">
							<td colspan="8" align="center"><b>TOTAL</b></td>
							<td align="center"><?php echo $ald20; ?></td>
							<td align="center"><?php echo $ald40; ?></td>
							<td align="center"><?php echo $amt20; ?></td>
							<td align="center"><?php echo $amt40; ?></td>
							<td align="center"><?php echo $ald20+$ald40*2; ?></td>
							<td align="center"><?php echo $amt20+$amt40*2; ?></td>
							
							<td colspan="6" align="center">&nbsp;</td>
							
							<td align="center"><?php echo $cld20; ?></td>
							<td align="center"><?php echo $cld40; ?></td>
							<td align="center"><?php echo $cmt20; ?></td>
							<td align="center"><?php echo $cmt40; ?></td>
							<td align="center"><?php echo $cld20+$cld40*2; ?></td>
							<td align="center"><?php echo $cmt20+$cmt40*2; ?></td>
							
							<td colspan="4" align="center"><b>TOTAL</b></td>
							
							<td align="center"><?php echo $dld20; ?></td>
							<td align="center"><?php echo $dld40; ?></td>
							<td align="center"><?php echo $dmt20; ?></td>
							<td align="center"><?php echo $dmt40; ?></td>
							<td align="center"><?php echo $dld20+$dld40*2; ?></td>
							<td align="center"><?php echo $dmt20+$dmt40*2; ?></td>
							
							<td colspan="6" rowspan="2" align="center"><b>GRAND TOTAL</b></td>
							<td align="center" colspan="2"><?php echo $ald20+$ald40+$amt20+$amt40+$dld20+$dld40+$dmt20+$dmt40; ?></td>
							<td align="left" colspan="6">BOX</td>
						</tr>
						<tr style="font-size: 75%;">
							<td colspan="8" align="center">&nbsp;</td>
							<td colspan="4" align="center"><?php echo $ald20+$ald40+$amt20+$amt40; ?></td>
							<td colspan="2" align="center"><?php echo $ald20+$ald40*2+$amt20+$amt40*2; ?></td>
							<td colspan="6" align="center">&nbsp;</td>
							<td colspan="4" align="center"><?php echo $cld20+$cld40+$cmt20+$cmt40; ?></td>
							<td colspan="2" align="center"><?php echo $cld20+$cld40*2+$cmt20+$cmt40*2; ?></td>
							<td colspan="4" align="center">&nbsp;</td>
							<td colspan="4" align="center"><?php echo $dld20+$dld40+$dmt20+$dmt40; ?></td>
							<td colspan="2" align="center"><?php echo $dld20+$dld40*2+$dmt20+$dmt40*2; ?></td>
							<td align="center" colspan="2"><?php echo $ald20+$ald40*2+$amt20+$amt40*2+$dld20+$dld40*2+$dmt20+$dmt40*2; ?></td>
							<td align="left" colspan="6">TEUS</td>
						</tr>
						
					</table>
				</td>
			</tr>
		</table>
		<table border="1" cellspacing="0" cellpadding="0" width="100%">
			<tr>
				<td style="width:30%" valign="top">
					<table border="1" cellspacing="0" cellpadding="0" align="center" width="100%">
						<tr style="font-size: 75%;">
							<th colspan="7">
								CONTAINER VESSELS INCOMING TODAY
							</th>
						</tr>
						<tr style="font-size: 75%;">
							<th rowspan="2">BERTH</th><th rowspan="2">NAME OF VESSEL</th><th colspan="2">AT O/A</th><th colspan="2">INDUCEMENT</th><th rowspan="2">TIDE</th>
						</tr>
						<tr style="font-size: 75%;">
							<th>DATE</th><th>TIME</th><th>IMPORT</th><th>EXPORT</th>
						</tr>
						<?php
					


							$strIncoming = "SELECT
							argo_carrier_visit.cvcvd_gkey,
							(SELECT NAME FROM vsl_vessel_visit_details
							INNER JOIN vsl_vessels ON vsl_vessels.gkey=vsl_vessel_visit_details.vessel_gkey
							WHERE vsl_vessel_visit_details.vvd_gkey=argo_carrier_visit.cvcvd_gkey) AS NAME,
							
							
							(SELECT vsl_vessel_visit_details.flex_date07 FROM vsl_vessel_visit_details
							WHERE vsl_vessel_visit_details.vvd_gkey=argo_carrier_visit.cvcvd_gkey) AS ffd,
							
							
							
							
							(SELECT COUNT(inv_unit.id) FROM inv_unit
							INNER JOIN inv_unit_fcy_visit ON inv_unit_fcy_visit.unit_gkey=inv_unit.gkey WHERE inv_unit_fcy_visit.actual_ib_cv=argo_carrier_visit.gkey) AS imp_tot,
							
							
							(SELECT COUNT(inv_unit.id) FROM inv_unit
							INNER JOIN inv_unit_fcy_visit ON inv_unit_fcy_visit.unit_gkey=inv_unit.gkey WHERE inv_unit_fcy_visit.actual_ob_cv=argo_carrier_visit.gkey) AS exp_tot
							
							
							FROM argo_carrier_visit
							INNER JOIN argo_visit_details ON argo_visit_details.gkey=argo_carrier_visit.cvcvd_gkey
							WHERE argo_carrier_visit.carrier_mode='VESSEL'
							
							
							AND argo_visit_details.eta BETWEEN to_date(CONCAT('$fromdate',' 08:00:00'),'YYYY-MM-DD HH24-MI-SS' ) AND to_date(CONCAT('$fromdate',' 20:00:00'),'YYYY-MM-DD HH24-MI-SS')+1
							AND (argo_carrier_visit.ata IS NULL OR argo_carrier_visit.ata BETWEEN to_date(CONCAT('$fromdate',' 09:00:00'),'YYYY-MM-DD HH24-MI-SS' ) AND to_date(CONCAT('$fromdate',' 20:00:00'),'YYYY-MM-DD HH24-MI-SS')+1)
							";

							$resIncoming=oci_parse($con_sparcsn4_oracle,$strIncoming);
							$rowIncoming=oci_execute($resIncoming);
							while(($rowIncoming=oci_fetch_object($resIncoming)) != false) {

							// $resIncoming = mysqli_query($con_sparcsn4,$strIncoming);


							$il = 0;
							$missingGky = $rowGetGky->gkey;
							// while($rowIncoming = mysqli_fetch_object($resIncoming))
							// {

								$strIncoming=" SELECT ctmsmis.berth_for_vessel($missingGky) AS berth";

								$strIncoming=" SELECT TideTime FROM ctmsmis.tmstideinformation WHERE TideDate='$fromdate'
								";

									 $resIncoming = mysqli_query($con_sparcsn4,$strIncoming);
									$rowIncoming = mysqli_fetch_object($resIncoming);
								$il++;
								if( $rowIncoming->FFD != null)
								{
									$ffdPart = explode(" ", $rowIncoming->FFD);	
								}
						?>
						<tr style="font-size: 75%;" align="center">
							<td><?php echo $rowIncoming->berth;?></td>
							<td><?php echo $rowIncoming->NAME;?></td>
							<td><?php echo $ffdPart[0];?></td>
							<td><?php echo $ffdPart[1];?></td>
							<td><?php echo $rowIncoming->IMP_TOT;?></td>
							<td><?php echo $rowIncoming->EXP_TOT;?></td>
							<td><?php echo $rowIncoming->TideTime;?></td>
						</tr>						
						<?php
							}?>
						
						<tr style="font-size: 75%;" align="center">
							<td>&nbsp;</td>
							<td rowspan="4" colspan="2" valign="middle">TOTAL VESSEL AT BERTH</td>
							<td rowspan="4" valign="middle"><?php echo $totImpVsl+$totExpVsl+$totImpExpVsl;?></td>
							<td colspan="2" align="left">IMPORT</td>
							<td><?php echo $totImpVsl;?></td>
						</tr>
						<tr style="font-size: 75%;" align="center">
							<td>&nbsp;</td>
							<td colspan="2" align="left">EXPORT</td>
							<td><?php echo $totExpVsl;?></td>
						</tr>
						<tr style="font-size: 75%;" align="center">
							<td>&nbsp;</td>
							<td colspan="2" align="left">IMPORT+EXPORT</td>
							<td><?php echo $totImpExpVsl;?></td>
						</tr>
						<tr style="font-size: 75%;" align="center">
							<td>&nbsp;</td>
							<td colspan="2" align="left">WAITING FOR SAIL</td>
							<td>0</td>
						</tr>
					</table>
				</td>
				<td colspan="4">
					&nbsp;
				</td>
				<td style="width:30%;" valign="top">
					<table border="1" cellspacing="0" cellpadding="0" align="center" width="100%">
						<tr style="font-size: 75%;">
							<th colspan="8">
								BERTH PERFORMANCE
							</th>
						</tr>
						<tr style="font-size: 75%;">
							<th rowspan="2"  colspan="2">BERTH NO.</th><th colspan="3">IN TEUS</th>
						</tr>
						<tr style="font-size: 75%;">
							<th colspan="2">IMPORT</th><th colspan="2">EXPORT</th><th colspan="2">TOTAL</th>
						</tr>
						<?php 
							$itot = 0;
							$etot = 0;
							for($i=0;$i<=sizeof($arr);$i++)
							{
								// $itot = $itot+$arr[$i][1];
								// $etot = $etot+$arr[$i][2];	
								if($arr[$i][0] !=null or $arr[$i][0] !="")
								{
						?>
						<tr style="font-size: 75%;" align="center"  style="">
							<td colspan="2"><?php echo $arr[$i][0];?></td>
							<td colspan="2"><?php echo $arr[$i][1];?></td>
							<td colspan="2"><?php echo $arr[$i][2];?></td>
							<td colspan="2"><?php echo $arr[$i][1]+$arr[$i][2];?></td>
						</tr>
						<?php								
							}
							}
						?>
						<tr>
							<td colspan="2">Total</td>
							<td colspan="2"><?php echo $itot;?></td>
							<td colspan="2"><?php echo $etot;?></td>
							<td colspan="2"><?php echo $itot+$etot;?></td>
						</tr>
					</table>
				</td>
				<td colspan="4">
					&nbsp;
				</td>
				<td style="width:30%;" valign="top">
					<table border="1" cellspacing="0" cellpadding="0" align="center" width="100%">
						<tr style="font-size: 75%;">
							<th colspan="14">
								CONTAINER VESSELS AT O/A
							</th>
						</tr>
						<tr style="font-size: 75%;">
							<th rowspan="2" colspan="2">SL NO.</th><th rowspan="2" colspan="2">VESSEL NAME</th><th colspan="4">REP. TIME AT O/A</th><th colspan="4">INDUCEMENT</th><th rowspan="2" colspan="2">FEEDER AGENT</th>
						</tr>
						<tr style="font-size: 75%;">
							<th colspan="2">DATE</th><th colspan="2">TIME</th><th colspan="2">IMPORT</th><th colspan="2">EXPORT</th>
						</tr>
						<?php
							//print_r($arr);
							$lp = sizeof($arr);
							for($t=0;$t<=$lp;$t++){
						?>
						<tr style="font-size: 75%;" align="center">
							<td colspan="2">&nbsp;</td>
							<td colspan="2">&nbsp;</td>
							<td colspan="2">&nbsp;</td>
							<td colspan="2">&nbsp;</td>
							<td colspan="2">&nbsp;</td>
							<td colspan="2">&nbsp;</td>
							<td colspan="2">&nbsp;</td>
						</tr>
						<?php } ?>
					</table>
				</td>
			</tr>
		</table>
		<?php 
if($_POST['options']=='html'){?> 
	</body>
</html>
<?php }?>