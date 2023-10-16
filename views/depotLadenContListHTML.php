

	<?php 
		header("Content-type: application/octet-stream");
		header("Content-Disposition: attachment; filename=DEPOT_LADEN_CONTAINER_LIST.xls;");
		header("Content-Type: application/ms-excel");
		header("Pragma: no-cache");
		header("Expires: 0");

			putenv('TZ=Asia/Dhaka');
			
			include("dbConection.php");
			include("dbOracleConnection.php");
		
			$str = "select now() as dt";
			$res = mysqli_query($con_sparcsn4,$str);
			$rowD = mysqli_fetch_object($res);
			$date = $rowD->dt;
			$strDepoName = "select distinct ctmsmis.offdoc.name from ctmsmis.offdoc where id='$depo'";
			$resDepoName = mysqli_query($con_sparcsn4,$strDepoName);
			$rowDepoName = mysqli_fetch_object($resDepoName);
			
			$DepoName = $rowDepoName->name;
			
			$cond = " and SUBSTR(nominal_length,-2)=20";
			if($size!="20")
			{
				$cond = " and SUBSTR(nominal_length,-2)!=20";
			}
		
			$str2 ="select inv_unit.id,inv_unit.category,inv_unit.freight_kind,inv_unit_fcy_visit.last_pos_slot,
			inv_unit_fcy_visit.flex_string10,NVL(r.id,'') as mlo,NVL(r.name,'') as mloName,NVL(Y.id,'') as agent,
			NVL(Y.name,'') as agentName,SUBSTR( nominal_length,-2) as siz,SUBSTR( nominal_height,-2)/10 as height,inv_goods.destination
			FROM inv_unit 
			INNER JOIN inv_unit_fcy_visit ON inv_unit_fcy_visit.unit_gkey=inv_unit.gkey 
			INNER JOIN ref_equipment ON inv_unit.eq_gkey=ref_equipment.gkey
			INNER JOIN ref_equip_type ON ref_equipment.eqtyp_gkey=ref_equip_type.gkey 
			INNER JOIN inv_goods ON inv_unit.goods=inv_goods.gkey
			inner join  ( ref_bizunit_scoped r  LEFT JOIN ( ref_agent_representation X  LEFT JOIN ref_bizunit_scoped Y ON X.agent_gkey=Y.gkey )  ON r.gkey=X.bzu_gkey ) 
			ON r.gkey = inv_unit.line_op
			where inv_unit.category='IMPRT' and inv_unit.visit_state ='1ACTIVE' and inv_unit_fcy_visit.transit_state='S40_YARD'
			and destination='$depo'  $cond";
			$result2 = oci_parse($con_sparcsn4_oracle, $str2);
            oci_execute($result2);
		?>
		<table align="center" border="0">
			<tr>
				
			</tr>
			<tr>
				<td>
					<h2 align="center">CHITTAGONG PORT AUTHORITY</h2>
				</td>
			</tr>
			<tr>
				<td>
					<h3 align="center"><?php echo "DEPOT LADEN CONTAINER AT CHITTAGONG PORT UP TO ".$date." OF ".$DepoName;?></h3>
				</td>
			</tr>
			<tr>
				<td>
					<table border="0" cellspacing="1" cellpadding="3">
						<tr>
							<th>SL.NO</th><th>CONTAINER NO.</th><th>CATEGORY.</th><th>STATUS</th><th>MLO</th><th>SIZE</th><th>HEIGHT</th><th>POSITION</th><th>ROTATION</th>
						</tr>
						<?php 
							$i = 0;
							// while($row2 = mysqli_fetch_object($result2))
							while(($row2= oci_fetch_object($result2)) != false)
							{	
							$i++;
						?>
							<tr>
								<td><?php echo $i;?></td>
								<td><?php echo $row2->ID;?></td>
								<td><?php echo $row2->CATEGORY;?></td>
								<td><?php echo $row2->FREIGHT_KIND;?></td>
								<td><?php echo $row2->MLO;?></td>
								<td><?php echo $row2->SIZ;?></td>
								<td><?php echo $row2->HEIGHT;?></td>
								<td><?php echo $row2->LAST_POS_SLOT;?></td>
								<td><?php echo $row2->FLEX_STRING10;?></td>
							</tr>
						<?php 
							}
						?>
					</table>
				</td>
			</tr>
			<?php mysqli_close($con_sparcsn4);?>
			<?php 
           oci_free_statement($result2);
           oci_close($con_sparcsn4_oracle);
          ?>
		</table>
