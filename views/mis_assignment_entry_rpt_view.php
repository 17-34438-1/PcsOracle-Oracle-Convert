<html>
	<head>
		 <meta http-equiv="refresh" content="20">
	</head>
	<body>
		<div>	
			<?php 
				include("dbConection.php");
				include("mydbPConnection.php");
			?>
			
			<table width="100%" border ='0' cellpadding='0' cellspacing='0'>
			<tr bgcolor="#ffffff" align="center" height="100px">
					<td colspan="13" align="center">
						<table border=0 width="100%">
							<tr align="center">
								<td colspan="7"><img height="100px" src="<?php echo IMG_PATH;?>cpa_logo.png" /></td>
							</tr>
							<tr align="center">
								<td colspan="7"><font size="4"><b> CHITTAGONG PORT AUTHORITY,CHITTAGONG</b></font></td>
							</tr>
						
							<tr align="center">
								<td colspan="7"><font size="4"><b><u>ASSIGNMENT REPORT</u></b></font></td>
							</tr>
							
							<tr align="center">
								<td colspan="7"><font size="4"><b></b></font></td>
							</tr>

						</table>
					
					</td>
					
				</tr>
				
				<tr bgcolor="#ffffff" align="center" height="25px">
					<td colspan="15" align="center"></td>
					
				</tr>
			</table>
			<table class="table table-bordered table-responsive table-hover table-striped mb-none">
			<tr align="center" bgcolor="grey">
				<td style="border-width:1px;border-style: double;" ><b>SL</b></td>
				<td style="border-width:1px;border-style: double;"><b>CONTAINER NO</b></td>
				<td style="border-width:1px;border-style: double;" ><b>CNF</b></td>
				<td style="border-width:1px;border-style: double;" ><b>MOBILE NO</b></td>
				<td style="border-width:1px;border-style: double;" ><b>BL</b></td>
				<td style="border-width:1px;border-style: double;" ><b>ROTATION</b></td>
				<td style="border-width:1px;border-style: double;" ><b>ASSIGNMENT TYPE</b></td>
				<td style="border-width:1px;border-style: double;" ><b>STRIPING PROPOSE DATE</b></td>
				<td style="border-width:1px;border-style: double;" ><b>LINE NO</b></td>
				<td style="border-width:1px;border-style: double;" ><b>REMARKS</b></td>
			</tr>

		<?php
		    include("dbOracleConnection.php");	
			$strAssignment= "SELECT gkey,cont_id,BL_No,rotation,mfdch_value,propose_date,mis_assignment_entry.unit_gkey,phone_number,
			IFNULL(remarks,'') AS remarks FROM ctmsmis.mis_assignment_entry
			ORDER BY propose_date DESC";
			//echo $strAssignment;
			$queryAssignment=mysqli_query($con_sparcsn4,$strAssignment);
			$i=0;
			while($rowAssignment=mysqli_fetch_object($queryAssignment))
			{
			$gkey="";	
			$consignee="";
			$gkey=$rowAssignment->gkey;
			$consignee=$rowAssignment->consignee;
			$name="";
			$sqlQuery="select ref_bizunit_scoped.name from inv_unit 
			INNER JOIN inv_unit_fcy_visit ON inv_unit_fcy_visit.unit_gkey=inv_unit.gkey
			LEFT JOIN ref_bizunit_scoped ON ref_bizunit_scoped.id='$consignee'
			WHERE  inv_unit_fcy_visit.flex_date01 IS NOT NULL AND inv_unit.gkey='$gkey'";
			$sqlQueryRes = oci_parse($con_sparcsn4_oracle,$sqlQuery);
			oci_execute($sqlQueryRes);
			$sqlQueryRow=oci_fetch_object($sqlQueryRes);
			oci_free_statement($sqlQueryRes);
			
			$name=$sqlQueryRow->NAME;
			$i++;		
			
			$strLineNumber="SELECT Line_No FROM cchaportdb.igm_details WHERE BL_No='".$rowAssignment->BL_No."'";
			
			//echo $strLineNumber;
			$queryLineNumber=mysqli_query($con_cchaportdb,$strLineNumber);
			$rowLine=mysqli_fetch_object($queryLineNumber);
		?>
		<tr align="center">
				<td><?php echo $i;?></td>
				<td><?php echo $rowAssignment->cont_id; ?></td>
				<td><?php echo $name; ?></td>
				<td><?php echo $rowAssignment->phone_number; ?></td>
				<td><?php echo $rowAssignment->BL_No; ?></td>
				<td><?php echo $rowAssignment->rotation; ?></td>
				<td><?php echo $rowAssignment->mfdch_value; ?></td>
				<td><?php echo $rowAssignment->propose_date; ?></td>
				<td><?php echo $rowLine->Line_No; ?></td>
				<td><?php if($rowAssignment->remarks!='null') echo $rowAssignment->remarks; else echo "&nbsp;";?></td>
			</tr>
		<?php 
			//}
		} 
		?>		
		</table>
		<br />
		<?php if($login_id=="admin"){?>
		<table align="center">
			<tr><td><a href="<?php echo site_url('report/downloadAssignmentSnx') ?>">Download SNX</a></td></tr>
		</table>
		<?php } 
		mysqli_close($con_sparcsn4);
		mysqli_close($con_cchaportdb);
		oci_close($con_sparcsn4_oracle);
		?>
		</div>
	</body>
</html>