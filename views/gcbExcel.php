<html>
<title>GCB EXCEL</title>
<body>

<?php 

	// header("Content-type: application/octet-stream");
	// header("Content-Disposition: attachment; filename=GCB_EXCEL.xlsx;");
	// header("Content-Type: application/ms-excel");
	// header("Pragma: no-cache");
	// header("Expires: 0");

	header("Content-type: application/octet-stream");
	header("Content-Disposition: attachment; filename=GCB_EXCEL.xls;");
	header("Content-Type: application/ms-excel");
	header("Pragma: no-cache");
	header("Expires: 0");
	
	include("mydbPConnectionn4.php");	
	include("dbOracleConnection.php");		
	//	$assignType="";			
	$length=count($rsltGCB);
	for($i=0;$i<$length;$i++)
	{
		$block_no = $rsltGCB[$i]['Block_No'];
		$sql_assign = "";
		if($yard=="ALLBLOCK")
		{
			if($assigntype =="ALLASSIGN")
				$sql_assign = "select distinct mfdch_value,mfdch_desc from ctmsmis.tmp_oracle_assignment where Yard_No='$terminal' and Block_No='$block_no' and date(flex_date01)='$date'";
			else
				$sql_assign = "select distinct mfdch_value,mfdch_desc from ctmsmis.tmp_oracle_assignment where Yard_No='$terminal' AND Block_No='$block_no' and mfdch_value='$assigntype' and date(flex_date01)='$date'";
		}
		else
		{
			if($assigntype =="ALLASSIGN")
				$sql_assign = "select distinct mfdch_value,mfdch_desc from ctmsmis.tmp_oracle_assignment where Yard_No='$terminal' AND Block_No='$block_no' and date(flex_date01)='$date'";
			else
				$sql_assign = "select distinct mfdch_value,mfdch_desc from ctmsmis.tmp_oracle_assignment where Yard_No='$terminal' AND Block_No='$block_no' AND mfdch_value='$assigntype' and date(flex_date01)='$date'";
		}
			//echo $sql_assign;
		$res_assign=mysqli_query($con_sparcsn4,$sql_assign);
		$k = 0;
		$totAssign = mysqli_num_rows($res_assign);
		while($row_assign = mysqli_fetch_object($res_assign))
		{
			$k++;
			$mfdch_value=$row_assign->mfdch_value;
			$mfdch_desc=$row_assign->mfdch_desc;
	?>
	
			<table style="border-collapse:collapse;" cellpadding="2px">
				<tr>
					<td colspan="13" align="center"><font size="5">CHITTAGONG PORT AUTHORITY</font></td>
				</tr>
				<tr>
					<td colspan="13" align="center">OFFICE OF THE TERMINAL MANAGER</td>
				</tr>
				<tr>
					<td colspan="13" align="center">DELIVERY REPORT OF <?php echo $terminal?></td>
				</tr>
				<tr>
					<td colspan="13" align="center">Date: <?php echo $date?></td>
				</tr>
				<tr>
					<td colspan="2"><b><?php echo "Block: ".$block_no ?></b></td>
					<td colspan="11"><b><?php echo "Assignment (Delivery): ".$mfdch_desc; ?></b></td>
				</tr>
				<tr style="height:20px">
					<td style="border:1px solid black" align="center" width="10px" >SL</td>
					<td style="border:1px solid black" align="center" width="150px">C & F Agent</td>
					<td style="border:1px solid black" align="center" width="120px">Vessel Name</td>
					<td style="border:1px solid black" align="center" width="50px">Rot.No</td>
					<td style="border:1px solid black" align="center" width="50px">MLO</td>
					<td style="border:1px solid black" align="center" width="50px">Seal No</td>
					<td style="border:1px solid black; width:80px" align="center" width="50px">DLV(Y/N)</font></td>
					<td style="border:1px solid black" align="center" >Cont No.</td>
					<td style="border:1px solid black" align="center">Size</td>
					<td style="border:1px solid black" align="center">Height</td>
					<td style="border:1px solid black" align="center" width="50px">BL No.</td>
					<td style="border:1px solid black" align="center">From</td>
					<td style="border:1px solid black" align="center" width="50px">Remarks</td>
				</tr>
				<?php 
				 $strAllData = "SELECT DISTINCT * FROM ctmsmis.tmp_oracle_assignment
				 WHERE mfdch_value='$mfdch_value' and Block_No='$block_no' and assignmentDate='$date' ORDER BY Yard_No,Block_No,mfdch_value,flex_date01";
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
				//	if($cnf!=$rowAllData->cf_name or $bl!=$rowAllData->line_no)
					if($cnf!=$rowAllData->cf_name )
					{
						$j = $j+1;
						$cnf=$rowAllData->cf_name;
						//$bl=$rowAllData->line_no;
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
				<tr>
					<td>Total:</td>
					<td align="left"><?php echo $tot; ?></td>
					<td align="right">20 FT:</td>
					<td align="left"><?php echo $t20; ?></td>
					<td align="right">40 FT:</td>
					<td align="left"><?php echo $t40; ?></td>
					<td align="right">TEUS:</td>
					<td align="left"><?php echo $t20+$t40*2; ?></td>
				</tr>
			</table>
<?php			
		}
	}
 ?>
 
 </body>
</html>
