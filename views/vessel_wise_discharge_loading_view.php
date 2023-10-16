<html>
	<body>
		<div>
			<div align="center">
				<table>
					<tr>
						<td  align="center">
                            <img src="<?php echo ASSETS_WEB_PATH;?>fimg/cpaLogo.png" alt="Chattogram Port Authority Logo"/>
                        </td>
					</tr>
				</table>
			</div>			
			<div align="center">
				<?php include("dbConection.php");
				include("dbOracleConnection.php");	
				?>
				<table>
					<tr style="margin:5px;">
						<td colspan="12"><font size="5"><b>Vessel Wise Discharge, Loading at </b></font><font size="4"><?php echo date("d/m/Y h:i:s")?></font></td>
					</tr>
				</table>
                <br>
                <table class="table table-responsive table-bordered table-striped mb-none">
                    <thead>
					<tr align="center"  class="gridDark">
						<td rowspan="3"><b>SlNo.</b></td>
						<td rowspan="3"><b>Berth</b></td>
						<td rowspan="3"><b>Rotation</b></td>
						<td rowspan="3"><b>Vessel Name</b></td>
						<td rowspan="3"><b>Berthed On</b></td>
						<td colspan="6"><b>Import</b></td>
						<td colspan="6"><b>Export</b></td>
					</tr>
					<tr align="center"  class="gridDark">
						<td colspan="2"><b>Total Container</b></td>
						<td colspan="2"><b>Discharge Container</b></td>		
						<td colspan="2"><b>Balance</b></td>
						<td colspan="2"><b>Total Container</b></td>
						<td colspan="2"><b>Loaded On Board</b></td>		
						<td colspan="2"><b>Balance To Be Shipped</b></td>
					</tr>
					<tr align="center"  class="gridDark">
						<td><b>Box</b></td>
						<td><b>Teus</b></td>
						<td><b>Box</b></td>
						<td><b>Teus</b></td>
						<td><b>Box</b></td>
						<td><b>Teus</b></td>
						
						<td><b>Box</b></td>
						<td><b>Teus</b></td>
						<td><b>Box</b></td>
						<td><b>Teus</b></td>
						<td><b>Box</b></td>
						<td><b>Teus</b></td>
					</tr>
                    </thead>
                    <tbody>


				<?php
					$strQuery = "SELECT tbl4.*,
					CONCAT(CONCAT(CONCAT(CONCAT( CONCAT(dd,' d '),hh),'h '),mm),'m') AS ocupai
					FROM (
					SELECT tbl3.*,
					CAST(h/24 AS INT) AS dd,
					CAST( MOD(h,24) as INT) AS hh
					FROM (
					SELECT tbl2.*,
					CAST( diff/60 as INT) AS h,
					CAST( MOD(diff,60) as INT) AS mm,
					(CASE
					WHEN SUBSTR(berth,1)='G' THEN '1' 
					ELSE
					(CASE
					WHEN SUBSTR(berth,1)='C' THEN '2' 
					ELSE
					'3'
					END)
					END) as sl
					FROM
					(
					SELECT * FROM (
					SELECT vsl_vessel_visit_details.vvd_gkey,vsl_vessels.name,vsl_vessel_visit_details.ib_vyg,
					SUBSTR(argo_carrier_visit.phase,3) AS phase_str,ref_bizunit_scoped.id AS agent,
					(SELECT argo_quay.id FROM argo_quay
					INNER JOIN vsl_vessel_berthings ON vsl_vessel_berthings.quay=argo_quay.gkey
					WHERE vsl_vessel_berthings.vvd_gkey=vsl_vessel_visit_details.vvd_gkey
					ORDER BY vsl_vessel_berthings.ata DESC fetch first 1 rows only ) AS berth,argo_carrier_visit.ata,argo_visit_details.etd,
					(CURRENT_DATE-ata) AS dif,
					Extract(minute from (ata-CURRENT_DATE))AS diff,
					
					NVL(vsl_vessels.service_registry_nbr,'') AS capacity
					FROM argo_carrier_visit
					INNER JOIN argo_visit_details ON argo_visit_details.gkey=argo_carrier_visit.cvcvd_gkey
					INNER JOIN vsl_vessel_visit_details ON vsl_vessel_visit_details.vvd_gkey=argo_visit_details.gkey
					INNER JOIN vsl_vessels ON vsl_vessels.gkey=vsl_vessel_visit_details.vessel_gkey
					INNER JOIN ref_bizunit_scoped ON ref_bizunit_scoped.gkey=vsl_vessel_visit_details.bizu_gkey
					WHERE argo_carrier_visit.phase IN ('30ARRIVED','40WORKING')
					ORDER BY argo_carrier_visit.phase,vsl_vessels.name
					) tbl 
					) tbl2 WHERE berth IS NOT NULL order by sl,berth 
					)tbl3 ) tbl4";
					
					//echo $strQuery;
					$query=oci_parse($con_sparcsn4_oracle,$strQuery);
					oci_execute($query);


					$i=0;
					$tImpB = 0;
					$tImpT = 0;
					$tDischB = 0;
					$tDischT = 0;
					$tBalB = 0;
					$tBalT = 0;
					
					$tExpLdB = 0;
					$tExpLdT = 0;
					$tExpMtB = 0;
					$tExpMtT = 0;
					
					$tLdLB = 0;
					$tLdLT = 0;
					$tLdMtB = 0;
					$tLdMtT = 0;
					
					$tLdBalB = 0;
					$tLdBalT = 0;
					$tMtBalB = 0;
					$tMtBalT = 0;
					
					while(($row=oci_fetch_object($query))!=false){
					$i++;
					
					$sqlGetTotImportCont="SELECT 
					(SUM(tot20)+SUM(tot40)) AS totbox,(SUM(tot20)+SUM(tot40)*2) AS totteus,
					(SUM(dis20)+SUM(dis40)) AS disbox,(SUM(dis20)+SUM(dis40)*2) AS disteus,
					((SUM(tot20)+SUM(tot40))-(SUM(dis20)+SUM(dis40))) AS balbox,((SUM(tot20)+SUM(tot40)*2)-(SUM(dis20)+SUM(dis40)*2)) AS balteus
					FROM(
					SELECT 
					(CASE WHEN SUBSTR(ref_equip_type.nominal_length,-2)=20 THEN 1 ELSE 0 END) AS tot20,
					(CASE WHEN SUBSTR(ref_equip_type.nominal_length,-2)!=20 THEN 1 ELSE 0 END) AS tot40,
					(CASE WHEN SUBSTR(ref_equip_type.nominal_length,-2)=20 AND time_in IS NOT NULL THEN 1 ELSE 0 END) AS dis20,
					(CASE WHEN SUBSTR(ref_equip_type.nominal_length,-2)!=20 AND time_in IS NOT NULL THEN 1 ELSE 0 END) AS dis40
					FROM inv_unit 
					INNER JOIN inv_unit_fcy_visit ON inv_unit_fcy_visit.unit_gkey=inv_unit.gkey 
					INNER JOIN argo_carrier_visit ON argo_carrier_visit.gkey=inv_unit_fcy_visit.actual_ib_cv
					INNER JOIN ref_equipment ON ref_equipment.gkey=inv_unit.eq_gkey
					INNER JOIN ref_equip_type ON ref_equip_type.gkey=ref_equipment.eqtyp_gkey
					WHERE inv_unit.category='IMPRT' AND argo_carrier_visit.cvcvd_gkey='$row->VVD_GKEY'
					) tbl";
					
					$queryTotImportCont=oci_parse($con_sparcsn4_oracle,$sqlGetTotImportCont);
					oci_execute($queryTotImportCont);
					$rowTotImportCont=oci_fetch_object($queryTotImportCont);
					
					$sqlGetTotExportCont="SELECT 
					(SUM(totld20)+SUM(totld40)) AS totldbox,
					(SUM(totld20)+SUM(totld40)*2) AS totldteus,
					(SUM(totmt20)+SUM(totmt40)) AS totmtbox,
					(SUM(totmt20)+SUM(totmt40)*2) AS totmtteus,
					(SUM(loadedld20)+SUM(loadedld40)) AS loadedldbox,
					(SUM(loadedld20)+SUM(loadedld40)*2) AS loadedldteus,
					(SUM(loadedmt20)+SUM(loadedmt40)) AS loadedmtbox,
					(SUM(loadedmt20)+SUM(loadedmt40)*2) AS loadedmtteus,
					((SUM(totld20)+SUM(totld40))-(SUM(loadedld20)+SUM(loadedld40))) AS balldbox,
					((SUM(totld20)+SUM(totld40)*2)-(SUM(loadedld20)+SUM(loadedld40)*2)) AS balldteus,
					((SUM(totmt20)+SUM(totmt40))-(SUM(loadedmt20)+SUM(loadedmt40))) AS balmtbox,
					((SUM(totmt20)+SUM(totmt40)*2)-(SUM(loadedmt20)+SUM(loadedmt40)*2)) AS balmtteus
					FROM(
					SELECT 
					(CASE WHEN SUBSTR(ref_equip_type.nominal_length,-2)=20 AND inv_unit.freight_kind!='MTY' THEN 1 ELSE 0 END) AS totld20,
					(CASE WHEN SUBSTR(ref_equip_type.nominal_length,-2)=20 AND inv_unit.freight_kind='MTY' THEN 1 ELSE 0 END) AS totmt20,
					(CASE WHEN SUBSTR(ref_equip_type.nominal_length,-2)!=20 AND inv_unit.freight_kind!='MTY' THEN 1 ELSE 0 END) AS totld40,
					(CASE WHEN SUBSTR(ref_equip_type.nominal_length,-2)!=20 AND inv_unit.freight_kind='MTY' THEN 1 ELSE 0 END) AS totmt40,
					(CASE WHEN SUBSTR(ref_equip_type.nominal_length,-2)=20 AND inv_unit.freight_kind!='MTY' AND time_load IS NOT NULL THEN 1 ELSE 0 END) AS loadedld20,
					(CASE WHEN SUBSTR(ref_equip_type.nominal_length,-2)=20 AND inv_unit.freight_kind ='MTY' AND time_load IS NOT NULL THEN 1 ELSE 0 END) AS loadedmt20,
					(CASE WHEN SUBSTR(ref_equip_type.nominal_length,-2)!=20 AND inv_unit.freight_kind !='MTY' AND time_load IS NOT NULL THEN 1 ELSE 0 END) AS loadedld40,
					(CASE WHEN SUBSTR(ref_equip_type.nominal_length,-2)!=20 AND inv_unit.freight_kind ='MTY' AND time_load IS NOT NULL THEN 1 ELSE 0 END) AS loadedmt40
					FROM inv_unit 
					INNER JOIN inv_unit_fcy_visit ON inv_unit_fcy_visit.unit_gkey=inv_unit.gkey 
					INNER JOIN argo_carrier_visit ON argo_carrier_visit.gkey=inv_unit_fcy_visit.actual_ob_cv
					INNER JOIN ref_equipment ON ref_equipment.gkey=inv_unit.eq_gkey
					INNER JOIN ref_equip_type ON ref_equip_type.gkey=ref_equipment.eqtyp_gkey
					WHERE inv_unit.category='EXPRT' AND argo_carrier_visit.cvcvd_gkey='$row->VVD_GKEY'
					)  tbl";
					
				//	echo "<br><br>";
					$queryTotExportCont=oci_parse($con_sparcsn4_oracle,$sqlGetTotExportCont);
					oci_execute($queryTotExportCont);
					$rowTotExportCont=oci_fetch_object($queryTotExportCont);
					
					$emptyBalTeus = $row->CAPACITY-($rowTotExportCont->LOADEDLDTEUS+$rowTotExportCont->LOADEDMTTEUS);
					
					$tImpB += $rowTotImportCont->TOTBOX;
					$tImpT += $rowTotImportCont->TOTTEUS;
					$tDischB += $rowTotImportCont->DISBOX;
					$tDischT += $rowTotImportCont->DISTEUS;
					$tBalB += $rowTotImportCont->BALBOX;
					$tBalT += $rowTotImportCont->BALTEUS;
					
					$tExpLdB += $rowTotExportCont->TOTLDBOX;
					$tExpLdT += $rowTotExportCont->TOTLDTEUS;
					$tExpMtB += $rowTotExportCont->TOTMTBOX;
					$tExpMtT += $rowTotExportCont->TOTMTTEUS;
					
					$tLdLB += $rowTotExportCont->LOADEDLDBOX;
					$tLdLT += $rowTotExportCont->LOADEDLDTEUS;
					$tLdMtB += $rowTotExportCont->LOADEDMTBOX;
					$tLdMtT += $rowTotExportCont->LOADEDMTTEUS;
					
					$tLdBalB += $rowTotExportCont->BALLDBOX;
					$tLdBalT += $rowTotExportCont->BALLDTEUS;
					$tMtBalB += $rowTotExportCont->BALMTBOX;
					$tMtBalT += $rowTotExportCont->BALMTTEUS;
					//$tMtBalT += $emptyBalTeus;
				?>
				<tr align="center" class="gradeX">
						<td><?php  echo $i;?></td>
						<td><?php if($row->NAME) echo $row->BERTH; else echo "&nbsp;";?></td>
						<td><?php if($row->IB_VYG) echo $row->IB_VYG; else echo "&nbsp;";?></td>		
						<td><?php if($row->NAME) echo $row->NAME; else echo "&nbsp;";?></td>
						<td><?php echo substr($row->ATA,0,-3); ?></td>
						<td><?php echo $rowTotImportCont->TOTBOX; ?></td>
						<td><?php echo $rowTotImportCont->TOTTEUS; ?></td>
						<td><?php echo $rowTotImportCont->DISBOX; ?></td>
						<td><?php echo $rowTotImportCont->DISTEUS; ?></td>
						<td><?php echo $rowTotImportCont->BALBOX; ?></td>
						<td><?php echo $rowTotImportCont->BALTEUS; ?></td>
						
						<!--td><?php echo $rowTotExportCont->TOTLDBOX; ?></td>
						<td><?php echo $rowTotExportCont->TOTLDTEUS; ?></td>
						<td><?php echo $rowTotExportCont->TOTMTBOX; ?></td>
						<td><?php echo $rowTotExportCont->TOTMTTEUS; ?></td-->
						
						<td><?php echo $rowTotExportCont->TOTLDBOX+$rowTotExportCont->TOTMTBOX; ?></td>
						<td><?php echo $rowTotExportCont->TOTLDTEUS+$rowTotExportCont->TOTMTTEUS; ?></td>
						
						<!--td><?php echo $rowTotExportCont->LOADEDLDBOX; ?></td>
						<td><?php echo $rowTotExportCont->LOADEDLDTEUS; ?></td>
						<td><?php echo $rowTotExportCont->LOADEDMTBOX; ?></td>
						<td><?php echo $rowTotExportCont->LOADEDMTTEUS; ?></td-->	
						
						<td><?php echo $rowTotExportCont->LOADEDLDBOX+$rowTotExportCont->LOADEDMTBOX; ?></td>
						<td><?php echo $rowTotExportCont->LOADEDLDTEUS+$rowTotExportCont->LOADEDMTTEUS; ?></td>
						
						<!--td><?php echo $rowTotExportCont->BALLDBOX; ?></td>
						<td><?php echo $rowTotExportCont->BALLDTEUS; ?></td>	
						<td><?php echo $rowTotExportCont->BALMTBOX; ?></td>
						<td><?php echo $rowTotExportCont->BALMTTEUS; ?></td-->
						
						<td><?php echo $rowTotExportCont->BALLDBOX+$rowTotExportCont->BALMTBOX; ?></td>
						<td><?php echo $rowTotExportCont->BALLDTEUS+$rowTotExportCont->BALMTTEUS; ?></td>
						<!--td><?php echo $emptyBalTeus; ?></td-->
				</tr>

				<?php } ?>
				<tr align="center" class="gradeX">
						<th colspan="5">Total</th>
						<th><?php echo $tImpB; ?></th>
						<th><?php echo $tImpT; ?></th>
						<th><?php echo $tDischB; ?></th>
						<th><?php echo $tDischT; ?></th>
						<th><?php echo $tBalB; ?></th>
						<th><?php echo $tBalT; ?></th>
						
						<!--th><?php echo $tExpLdB; ?></th>
						<th><?php echo $tExpLdT; ?></th>
						<th><?php echo $tExpMtB; ?></th>
						<th><?php echo $tExpMtT; ?></th-->
						
						<th><?php echo $tExpLdB+$tExpMtB; ?></th>
						<th><?php echo $tExpLdT+$tExpMtT; ?></th>
						
						<!--th><?php echo $tLdLB; ?></th>
						<th><?php echo $tLdLT; ?></th>						
						<th><?php echo $tLdMtB; ?></th>						
						<th><?php echo $tLdMtT; ?></th-->	
						
						<th><?php echo $tLdLB+$tLdMtB; ?></th>
						<th><?php echo $tLdLT+$tLdMtT; ?></th>
						
						<!--th><?php echo $tLdBalB; ?></th>						
						<th><?php echo $tLdBalT; ?></th>						
						<th><?php echo $tMtBalB; ?></th>						
						<th><?php echo $tMtBalT; ?></th-->	

						<th><?php echo $tLdBalB+$tMtBalB; ?></th>
						<th><?php echo $tLdBalT+$tMtBalT; ?></th>
				</tr>
                    </tbody>
				</table>
			</div>
		</div>
		<br>
		<?php mysqli_close($con_sparcsn4); ?>
        <div class="text-right mr-lg">

            <a href="<?php echo site_url('report/vessel_wise_discharge_loading/'.'print')?>" target="_blank" class="btn btn-primary ml-sm"><i class="fa fa-print"></i> Print</a>
        </div>

	</body>
</html>