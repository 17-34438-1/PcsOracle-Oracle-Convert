<?php if(@$_POST['options']=='html'){?>
<html>
	<head>
		
	</head>
	<body>

	<?php } 
	else if(@$_POST['options']=='xl'){
		header("Content-type: application/octet-stream");
		header("Content-Disposition: attachment; filename=DEPOT_LADEN_CONTAINER.xls;");
		header("Content-Type: application/ms-excel");
		header("Pragma: no-cache");
		header("Expires: 0");

	}
			putenv('TZ=Asia/Dhaka');
			//echo $rotation;
			include("dbConection.php");
			include("dbOracleConnection.php");
			//echo $sValue;
			$str = "select now() as dt";
			$res = mysqli_query($con_sparcsn4,$str);
			$rowD = mysqli_fetch_object($res);
			$date = $rowD->dt;
			$cond = " and destination not in('2591','BDCGP')";
			if($sValue=="depot")
			{
				$cond = " and destination not in('2591','BDCGP','2592','5231','5232','5233','5234','5235','5236','5237','5238','AUCTION','BDMGL','BDPGN')";
			}
		
			//RETURN
			 $str2 ="select destination,SUM(cont_20) AS cont_20,SUM(cont_40) AS cont_40,(SUM(cont_20)+SUM(cont_40)) AS cont_tot,(SUM(cont_20)+SUM(cont_40)*2) AS teus 
			FROM(
			select tbl.*,(CASE WHEN destination_Res='BDMGL' THEN '5232' WHEN destination_Res='BDPGN' THEN '5235' ELSE destination_Res END ) AS destination
			from
			(
			SELECT inv_unit.id, sparcs_note,inv_goods.destination as destination_Res,
			(CASE WHEN SUBSTR( nominal_length,-2)=20 THEN 1 ELSE 0 END) AS cont_20,
			(CASE WHEN SUBSTR( nominal_length,-2)!=20 THEN 1 ELSE 0 END) AS cont_40
			
			FROM inv_unit 
			INNER JOIN inv_unit_fcy_visit ON inv_unit_fcy_visit.unit_gkey=inv_unit.gkey 
			INNER JOIN ref_equipment ON inv_unit.eq_gkey=ref_equipment.gkey 
			INNER JOIN ref_equip_type ON ref_equipment.eqtyp_gkey=ref_equip_type.gkey 
			INNER JOIN inv_goods ON inv_unit.goods=inv_goods.gkey 
			WHERE inv_unit.category='IMPRT' AND inv_unit.visit_state ='1ACTIVE' AND inv_unit_fcy_visit.transit_state='S40_YARD' AND destination IS NOT NULL AND destination!='' AND inv_unit.freight_kind !='MTY' 
			$cond AND sparcs_note!='CPA TO OFF DOCK' 
			UNION
			SELECT inv_unit.id, sparcs_note, inv_goods.destination as destination_Res,
			(CASE WHEN SUBSTR( nominal_length,-2)=20 THEN 1 ELSE 0 END) AS cont_20, 
			(CASE WHEN SUBSTR( nominal_length,-2)!=20 THEN 1 ELSE 0 END) AS cont_40
			FROM 
			inv_unit 
			INNER JOIN inv_unit_fcy_visit ON inv_unit_fcy_visit.unit_gkey=inv_unit.gkey 
			INNER JOIN ref_equipment ON inv_unit.eq_gkey=ref_equipment.gkey 
			INNER JOIN ref_equip_type ON ref_equipment.eqtyp_gkey=ref_equip_type.gkey 
			INNER JOIN inv_goods ON inv_unit.goods=inv_goods.gkey 
			 WHERE inv_unit.category='IMPRT' AND inv_unit.visit_state ='1ACTIVE' AND inv_unit_fcy_visit.transit_state='S40_YARD' 
			AND destination IS NOT NULL AND destination!='' AND inv_unit.freight_kind !='MTY' $cond 
			AND sparcs_note IS NULL 
			)tbl
			)tbl2 GROUP BY destination";

			

		
			$result2 = oci_parse($con_sparcsn4_oracle,$str2);
            oci_execute($result2);
			
			
		?>
		<table align="center" border="0">
			<tr>
				
			</tr>
			<tr>
				<td align="center">
					<img align="middle" src="<?php echo IMG_PATH?>cpanew.jpg">
				</td>
			</tr>
			<tr>
				<td>
					<h3 align="center"><?php echo $title." ".$date;?></h3>
				</td>
			</tr>
			<tr>
				<td>
					<?php 
						if($_POST['options'] == 'xl'){
							echo "<table border='0' cellspacing='1' cellpadding='3'>";
						}else if($_POST['options'] == 'html'){
							echo "<div class='table-responsive'><table class='table table-bordered table-responsive table-hover table-striped mb-none'>";
						}
					?>
					
						<tr <?php if($_POST['options']=='html'){?>bgcolor="#9999CC"<?php }?>>
							<th>SL.NO</th><th>DPOT CODE NO.</th><th>DEPOT CODE IN TRADITIONAL SYSTEM</th><th>DEPOT NAME</th><th>20'</th><th>40'</th><th>TOTAL BOX</th><th>TOTAL TEUS</th>
						</tr>
						<?php 
							$i = 0;
							$cont20 = 0;
							$cont40 = 0;
							$box = 0;
							$teus = 0;
							
							$destinatn="";
							$offdoc_code="";
							$offdoc_name="";
							while(($row2=oci_fetch_object($result2))!=false)
							{	
							$i++;
						    $cont20 = $cont20+$row2->CONT_20;
							$cont40 = $cont40+$row2->CONT_40;
							$box = $box+$row2->CONT_TOT;
							$teus = $teus+$row2->TEUS;
						    $destinatn=$row2->DESTINATION;
						   
							
							if($destinatn == 'AUCTION'){
                            $offdoc_code=$destinatn;
							}else{
								
							    $offdoc_code_qu="SELECT ctmsmis.offdoc.code FROM ctmsmis.offdoc WHERE id='$destinatn'";
								$offdoc_code_res=mysqli_query($con_sparcsn4,$offdoc_code_qu);
								while($offdoc_code_row=mysqli_fetch_object($offdoc_code_res)){
									 $offdoc_code=$offdoc_code_row->code;
								}
							}

							
							
							if($destinatn == 'AUCTION'){
								$offdoc_name=$destinatn;
								}else{
									
									$offdoc_name_qu="SELECT ctmsmis.offdoc.name FROM ctmsmis.offdoc WHERE id='$destinatn'";
									$offdoc_name_res=mysqli_query($con_sparcsn4,$offdoc_name_qu);
									while($offdoc_name_row=mysqli_fetch_object($offdoc_name_res)){
									 $offdoc_name=$offdoc_name_row->name;
									}
								}
						?>
							<tr <?php if(@$_POST['options']=='html'){?>bgcolor="#CCCCCC"<?php }?>>
								<td><?php echo $i;?></td>
								<td><?php echo $row2->DESTINATION;?></td>
								<td><?php echo $offdoc_code?></td>
								<td><?php echo $offdoc_name;?></td>
								<td>
									<?php 
										if(@$_POST['options']=='xl')
										{
											echo $row2->CONT_20;
										}
										else
										{
									?>
										<a href="<?php echo site_url('report/depotLadenContListView/'.$row2->DESTINATION.'/20') ?>"><?php echo $row2->CONT_20;?></a>
									<?php
										}
									?>
								</td>
								<td>
									<?php 
										if(@$_POST['options']=='xl')
										{
											echo $row2->CONT_40;
										}
										else
										{
									?>
										<a href="<?php echo site_url('report/depotLadenContListView/'.$row2->DESTINATION.'/40') ?>"><?php echo $row2->CONT_40;?></a>
									<?php
										}
									?>									
								</td>
								<td><?php echo $row2->CONT_TOT;?></td>
								<td><?php echo $row2->TEUS;?></td>
							</tr>
						<?php 
							}
						?>
							<tr <?php if($_POST['options']=='html'){?>bgcolor="#CCCCCC"<?php }?>>
								<td colspan="4"><b>Total</b></td>
								<td><b><?php echo $cont20;?></b></td>
								<td><b><?php echo $cont40;?></b></td>
								<td><b><?php echo $box;?></b></td>
								<td><b><?php echo $teus;?></b></td>
							</tr>
					</table>
					<?php
						if($_POST['options'] == 'html'){
							echo "</div>";
						}
					?>
				</td>
			</tr>
		</table>
<?php mysqli_close($con_sparcsn4);?>
<?php oci_close($con_sparcsn4_oracle) ?>
<?php if(@$_POST['options']=='html'){?>		
	</body>
</html>

<?php }?>	
