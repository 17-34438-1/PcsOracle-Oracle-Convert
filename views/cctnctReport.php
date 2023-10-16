<html>
	<head>
		<title>CHITTAGONG PORT AUTHORITY</title>
		<style>
			@media print {
				@page { margin: 0.5cm; }
				body { margin: 1.6cm; }
			}
		</style>
	</head>
	<body>
		<?php
		include("mydbPConnectionn4.php");
		include("dbOracleConnection.php");		
		$assignType="";			
		$length=count($rsltNCTCCT);
		for($i=0;$i<$length;$i++)
		{ 		
		$mfdch_value = $rsltNCTCCT[$i]['mfdch_value'];
		$mfdch_desc = $rsltNCTCCT[$i]['mfdch_desc'];
		?>
		<div class="pagewidth">
			<table style="border-collapse:collapse;" cellpadding="2px">
				<thead>
					<tr>
						<!--td colspan="12" align="center"><font size="5">CHITTAGONG PORT AUTHORITY</font></td-->
						<td colspan="12" align="center"><img width="200px" height="60px" src="<?php echo IMG_PATH?>cpanew.jpg"></td>
					</tr>
					<tr>
						<td colspan="12" align="center">OFFICE OF THE TERMINAL MANAGER</td>
					</tr>
					<tr>
						<td colspan="12" align="center">DELIVERY REPORT OF <?php echo $terminal?></td>
					</tr>
					<tr>
						<td colspan="8" style="padding-left:320px">Date: <?php echo $date?></td>
						<td colspan="4">Printed: <?php echo date('d/m/Y h:i:s')?></td>
					</tr>
					<tr>
						<td colspan="12"><b><?php echo "Assignment (Delivery): ".$mfdch_desc; ?></b></td>
					</tr>
					<tr>
						<th style="border:1px solid black" align="center" width="10px">SL</th>
						<th style="border:1px solid black" align="center" width="150px">C & F Agent</th>
						<th style="border:1px solid black" align="center" width="120px">Vessel Name</th>
						<th style="border:1px solid black" align="center" width="50px">Rot.No</th>
						<th style="border:1px solid black" align="center" width="50px">MLO</th>
						<th style="border:1px solid black" align="center" width="50px">Seal No</th>
						<th style="border:1px solid black" align="center">DLV(Y/N)</th>
						<th style="border:1px solid black" align="center" >Cont No.</th>
						<th style="border:1px solid black" align="center">Sz</th>
						<th style="border:1px solid black" align="center">Ht</th>
						<th style="border:1px solid black" align="center" width="50px">BL No.</th>
						<th style="border:1px solid black" align="center">From</th>
						<th style="border:1px solid black" align="center" width="50px">Remarks</th>
					</tr>
				</thead>				
				<?php 
				$strAllData = "SELECT DISTINCT * FROM ctmsmis.tmp_oracle_assignment
				WHERE assignmentDate = '$date' AND mfdch_value='$mfdch_value' AND Yard_No='$terminal' ORDER BY Yard_No,mfdch_value,flex_date01,bl_no";
				$resAllData = mysqli_query($con_sparcsn4,$strAllData);
				$j=0;
				$cnf="";
				$bl="";
				$t20=0;
				$t40=0;
				$tot = 0;
				while($rowAllData = mysqli_fetch_object($resAllData))
				{
					$tot++;
					if($cnf!=$rowAllData->cf_name or $bl!=$rowAllData->bl_no)
					{
						$j = $j+1;
						$cnf=$rowAllData->cf_name;
						$bl=$rowAllData->bl_no;
					}
					
					if($rowAllData->size==20)
						$t20 += 1;
					else
						$t40 += 1;
				?>
					<tr>
						<td style="border:1px solid black" align="center"><?php echo $j;?></td>
						<td style="border:1px solid black" align="center"><?php echo $rowAllData->cf_name; ?></td>
						<td style="border:1px solid black" align="center"><?php echo $rowAllData->v_name; ?></td>
						<td style="border:1px solid black" align="center"><?php echo $rowAllData->rot_no; ?></td>
						<td style="border:1px solid black" align="center"><?php echo $rowAllData->mlo; ?></td>
						<td style="border:1px solid black" align="center"><?php echo $rowAllData->seal_nbr1; ?></td>							
						<?php
							$cont_no=$rowAllData->cont_no;
							$cont_no=str_replace("-","",$cont_no);
								
							$sqlYN="SELECT time_out FROM inv_unit 
							INNER JOIN inv_unit_fcy_visit ON inv_unit_fcy_visit.unit_gkey=inv_unit.gkey
							WHERE inv_unit.id='$cont_no' AND category='IMPRT'
							order by inv_unit.gkey desc FETCH FIRST 1 ROWS ONLY";

							$resYN=oci_parse($con_sparcsn4_oracle,$sqlYN);
							oci_execute($resYN);
							$rowYN=oci_fetch_object($resYN);
							$yn=$rowYN->TIME_OUT ;
							if($yn!=null)
							{
						?>
								<td style="border:1px solid black; width:10px; background-color:#bbbaba;" align="center">Yes</td>
						<?php
							}
							else
							{
						?>
								<td style="border:1px solid black; width:10px;" align="center"></td>
						<?php
							}										
						?>
						
						<td style="border:1px solid black" align="center"><?php echo $rowAllData->cont_no; ?></td>
						<td style="border:1px solid black" align="center"><?php echo $rowAllData->size; ?></td>
						<td style="border:1px solid black" align="center"><?php echo number_format($rowAllData->height,1); ?></td>
						<td style="border:1px solid black" align="center"><?php echo $rowAllData->bl_no; ?></td>
						<td style="border:1px solid black" align="center"><?php echo $rowAllData->slot; ?></td>
						<td style="border:1px solid black" align="center"><?php echo $rowAllData->remarks; ?></td>
					</tr>
				<?php
				}
				?>
					<tr><td colspan="13"><hr></td></tr>
					<tr>
						<td>Total:</td>
						<td><?php echo $tot; ?></td>
						<td align="right">20 FT:</td>
						<td><?php echo $t20; ?></td>
						<td align="right">40 FT:</td>
						<td><?php echo $t40; ?></td>
						<td align="right">TEUS:</td>
						<td><?php echo $t20+$t40*2; ?></td>
					</tr>
			</table>
		</div>
		<?php
			if($i==$length-1)
			{
			?>
				<div class="pageBreakOff" style="page-break-after: avoid;"></div>
			<?php
			}
			else if($i<$length)  
			{
			?>
				<div class="pageBreak" style="page-break-after: always;"></div>
			<?php
			}
			?>
		<?php
		}
		?>

		<script>
			window.print();
		</script>
	</body>
</html>