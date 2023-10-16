<html>
<head>
	<style>
		@media print 
		{
			@page { margin: 0.5; }
			body { margin: 1.6cm; }
		}
	</style>
	<div align="center" style="">
		<!--title><b>CHITTAGONG PORT AUTHORITY</b></title-->
		<img width="200px" height="60px" src="<?php echo IMG_PATH?>cpanew.jpg">
	</div>
	<div align="center"><b>ONE STOP SERVICE CENTER</b></div>
	<div align="center"><b>View Certification</b></div>
</head>
<body>	
	<div align ="center">
		<table align="center" width=85% border="1" style="font-size:12px; border-collapse: collapse;">
			<tr>
				<td align="center" style="width:13%;"><b>Rotation</b></td>
				<td align="center" style="width:2%"><b>:</b></td>
				<td align="center" style="width:35%"><?php echo $ddl_imp_rot_no; ?></td>
				
				<td align="center" style="width:13%;"><b>BL No</b></td>
				<td align="center" style="width:2%"><b>:</b></td>
				<td align="center" style="width:35%"><?php echo $ddl_bl_no; ?></td>
			</tr>
			<tr>
				<!--td align="center" style="background-color:#006bff45;"><b>Discharge Time</b></th>
				<td align="center"><b>:</b></td>
				<td align="center"><?php echo $dischargeTime;; ?></td-->
				
				<td align="center"><b>Dest.</b></td>
				<td align="center"><b>:</b></td>
				<td align="center"><?php if($contStatus!="FCL"){ print($rtnContainerList[0]['off_dock_id']);} ?></td>
				
				<td align="center"><b>Marks & Number</b></td>
				<td align="center"><b>:</b></td>
				<td align="center"><?php echo str_replace(',',', ',$rtnContainerList[0]['Pack_Marks_Number']); ?></td>
			</tr>
			<tr align="center">																
				<td align="center"><b>Consignee Description</b></td>
				<td align="center"><b>:</b></td>
				<td align="center"><?php print($rtnContainerList[0]['ConsigneeDesc']);  ?></td>
				
				<td align="center"><b>Status</b></td>
				<td align="center"><b>:</b></td>
				<td align="center"><?php print($rtnContainerList[0]['cont_status']); ?></td>
			</tr>							
			<!--tr align="center">	
				<td style="background-color:#006bff45;"><b>Yard / Shed</b></td>
				<td align="center"><b>:</b></td>
				<td><?php if($contStatus!="FCL"){print($rtnContainerList[$i]['shed_yard']);}  ?></td>
				
				<td style="background-color:#006bff45;"><b>Position</b></td>
				<td align="center"><b>:</b></td>
				<td><?php if($contStatus!="FCL"){print($rtnContainerList[$i]['shed_loc']);}  ?></td>				
			</tr-->							
			<tr align="center">					
				<td align="center"><b>Offdock Name</b></td>
				<td align="center"><b>:</b></td>
				<td align="center"><?php print($rtnContainerList[0]['offdock_name']); ?></td>
				
				<td align="center"><b>Notify Description</b></td>
				<td align="center"><b>:</b></td>
				<td align="center"><?php print($rtnContainerList[0]['NotifyDesc']); ?></td>
			</tr>
			<tr align="center">																
				<!--td style="background-color:#006bff45;"><b>Cont. Type</b></td>
				<td align="center"><b>:</b></td>
				<td><?php print($rtnContainerList[0]['cont_iso_type']); ?></td-->
				
				<td align="center"><b>Receive Pack</td>
				<td align="center"><b>:</b></td>
				<td align="center"><?php print($rtnContainerList[0]['rcv_pack']); ?></td>
				
				<?php if ($contStatus=='LCL') { ?>
				<td align="center" style="background-color:#006bff45;"><b>Unstuffing Date</td>
				<td align="center"><b>:</b></td>
				<td><?php print($rtnContainerList[0]['ustuffing_dt']); ?></td>
				<?php }  else { ?>
				<td colspan="3"></td>
				<?php } ?>
			</tr>	
			<tr align="center">																
				
				<td align="center"><b>Vessel Name</td>
				<td align="center"><b>:</b></td>
				<td align="center"><?php print($rtnContainerList[0]['Vessel_Name']); ?></td>
				
				<td colspan="3"></td>
			</tr>		
		</table>
		<br>
		<!--table class="table table-bordered table-striped table-hover" cellspacing="2" cellpadding="1"  width="100%" style="margin-right:180px"-->
		<table align="center" width=85% border="1" style="font-size:12px; border-collapse: collapse;">
			<tr>
				<td align="center"><b>Container</b></td>
				<td align="center"><b>Seal</b></td>
				<td align="center"><b>Size</b></td>
				<td align="center"><b>Height</b></td>
				<td align="center"><b>Position</b></td>
				<td align="center"><b>Yard/Shed</b></td>
			</tr>

			<?php
			include("dbConection.php");
			include("dbOracleConnection.php");

			for($i=0;$i<count($rtnContainerList);$i++)
			{
				$contMain = $rtnContainerList[$i]['cont_number'];
				$rotMain = $rtnContainerList[$i]['Import_Rotation_No'];
				 
				
				
				$strQuerypos="SELECT inv_unit_fcy_visit.last_pos_slot AS pos
				FROM inv_unit
				INNER JOIN inv_unit_fcy_visit ON inv_unit_fcy_visit.unit_gkey=inv_unit.gkey
				INNER JOIN argo_carrier_visit ON argo_carrier_visit.gkey=inv_unit_fcy_visit.actual_ib_cv
				INNER JOIN vsl_vessel_visit_details ON vsl_vessel_visit_details.vvd_gkey=argo_carrier_visit.cvcvd_gkey
				WHERE inv_unit.id='$contMain' AND ib_vyg='$rotMain'
				";
				$strPosForFcl= oci_parse($con_sparcsn4_oracle,$strQuerypos);
                oci_execute($strPosForFcl);
				
				$pos="";
				$yard_No="";
				
			
				while(($row= oci_fetch_object($strPosForFcl)) != false)
				{
					$pos = $row->POS;
					// $last_pos_slot=$row->LAST_POS_SLOT;
										
					$strQuerypos2="SELECT ctmsmis.cont_yard('$pos') AS Yard_No";
					$strQuery3Res = mysqli_query($con_sparcsn4,$strQuerypos2);
					$row2 = mysqli_fetch_object($strQuery3Res);
					$yard_No = $row2->Yard_No;
				}										
			?>
			<tr>
				<td align="center"><?php echo $rtnContainerList[$i]['cont_number'];  ?></td>
				<td align="center"><?php echo $rtnContainerList[$i]['cont_seal_number']; ?></td>
				<td align="center"><?php echo $rtnContainerList[$i]['cont_size']; ?></td>
				<td align="center"><?php echo $rtnContainerList[$i]['cont_height']; ?></td>
				<td align="center"><?php if($pos=="") {echo $position_data;} else {echo $pos;} ?></td>
				<td align="center"><?php if($yard_No=="") {echo $yard_data;} else {echo $yard_No;} ?></td>
			</tr>
		<?php 								 							
			}
			oci_free_statement($strPosForFcl);
			oci_close($con_sparcsn4_oracle);
		?>			
		</table>
	</div>
	<script>
		window.print();
	</script>
</body>
</html>

