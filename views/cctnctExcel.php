<?php 
	if(@$_POST['option']=='xl'){
		header("Content-type: application/octet-stream");
		header("Content-Disposition: attachment; filename=CCT_NCT_EXCEL.xls;");
		header("Content-Type: application/ms-excel");
		header("Pragma: no-cache");
		header("Expires: 0");
	}
	
	include("mydbPConnectionn4.php");
	include("dbOracleConnection.php");			
	$assignType="";			
	$length=count($rsltNCTCCT);
	for($i=0;$i<$length;$i++)
	{ 		
		$mfdch_value = $rsltNCTCCT[$i]['mfdch_value'];
		$mfdch_desc = $rsltNCTCCT[$i]['mfdch_desc'];
?>
	
	<table style="border-collapse:collapse;" cellpadding="2px">
		<?php
		if($i!=0)
		{	 
		?>
		<tr>
			<td colspan="13">&nbsp;</td>
		</tr>
		<?php
		}
		?>
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
			<!--td colspan="4">Printed: <?php echo date('d/m/Y h:i:s')?></td-->
		</tr>
		<tr>
			<td colspan="13"><b><?php echo "Assignment (Delivery): ".$mfdch_desc; ?></b></td>
		</tr>
		<!--tr>
			<td style="border:1px solid black" align="center" width="10px">SL</td>
			<td style="border:1px solid black" align="center" width="150px">C & F Agent</td>
			<td style="border:1px solid black" align="center" width="120px">Vessel Name</td>
			<td style="border:1px solid black" align="center" width="50px">Rot.No</td>
			<td style="border:1px solid black" align="center" width="50px">MLO</td>
			<td style="border:1px solid black" align="center"><font size="2">DLV(Y/N)</font></td>
			<td style="border:1px solid black" align="center" >Cont No.</td>
			<td style="border:1px solid black" align="center">Sz</td>
			<td style="border:1px solid black" align="center">Ht</td>
			<td style="border:1px solid black" align="center" width="50px">BL No.</td>
			<td style="border:1px solid black" align="center">From</td>
			<td style="border:1px solid black" align="center" width="50px">Remarks</td>
		</tr-->
		<tr>
			<td style="border:1px solid black" align="center"><nobr>SL</nobr></td>
			<td style="border:1px solid black" align="center"><nobr>C & F Agent</nobr></td>
			<td style="border:1px solid black" align="center"><nobr>Vessel Name</nobr></td>
			<td style="border:1px solid black" align="center"><nobr>Rot.No</nobr></td>
			<td style="border:1px solid black" align="center"><nobr>MLO</nobr></td>
			<td style="border:1px solid black" align="center" width="50px"><nobr>Seal No</nobr></td>
			<td style="border:1px solid black" align="center"><nobr>DLV(Y/N)</nobr></td>
			<td style="border:1px solid black" align="center"><nobr>Cont No.</nobr></td>
			<td style="border:1px solid black" align="center"><nobr>Size</nobr></td>
			<td style="border:1px solid black" align="center"><nobr>Height</nobr></td>
			<td style="border:1px solid black" align="center"><nobr>BL No.</nobr></td>
			<td style="border:1px solid black" align="center"><nobr>From</nobr></td>
			<td style="border:1px solid black" align="center"><nobr>Remarks</nobr></td>
		</tr>
		<?php 
			// $strAllData = "SELECT DISTINCT * FROM ctmsmis.tmp_assignment_type_new
			// WHERE mfdch_value='$mfdch_value' and Yard_No='$terminal' ORDER BY Yard_No,mfdch_value,flex_date01,line_no";

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
			<tr>
				<td colspan="13">&nbsp;</td>
			</tr>
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
		//$login_id = $this->session->userdata('login_id')
		//$login_id_trans=="";
		  function Offdock($login_id)
			{
				if($login_id=='gclt')
				{
					return "GCL";
				}
				elseif($login_id=='saplw')
				{
					return "SAPE";
				}
				elseif($login_id=='ebil')
				{
					return "EBIL";
				}
				elseif($login_id=='cctcl')
				{
					return "CL";
				}
				elseif($login_id=='ktlt')
				{
					return "KTL";
				}
				elseif($login_id=='qnsc')
				{
					return "QNSC";
				}
				elseif($login_id=='ocl')
				{
					return "OCCL";
				}
				elseif($login_id=='vlsl')
				{
					return "VLSL";
				}
				elseif($login_id=='shml')
				{
					return "SHML";
				}
				elseif($login_id=='iqen')
				{
					return "IE";
				}
				elseif($login_id=='iltd')
				{
					return "IL";
				}
				
				elseif($login_id=='plcl')
				{
					return "PLCL";
				}
				elseif($login_id=='shpm')
				{
					return "SHPM";
				}
				elseif($login_id=='hsat')
				{
					return "HSAT";
				}
				elseif($login_id=='ellt')
				{
					return "ELL";
				}
				elseif($login_id=='bmcd')
				{
					return "BM";
				}
				elseif($login_id=='nclt')
				{
					return "NCL";
				}
				
				else
				{
					return "";
				}
				
			}
 ?>



