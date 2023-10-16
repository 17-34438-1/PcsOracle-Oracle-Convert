<table align="center" width="850px">
	<tr>
		<td align="center"><img align="middle" width="235px" height="75px" src="<?php echo IMG_PATH?>cpanew.jpg"></td>
	</tr>
	<tr>
		<td align="center"><b>Delivery Order(Break Bulk)</b></td>
	</tr>
</table>
<table border="1" style="border-collapse:collapse;margin-bottom:20px;" align="center" width="80%">
	<tr>
		<td colspan="4" valign="center" align="center"><h1>DELIVERY ORDER</h1></td>
		<td colspan="2" valign="center"><strong>BL No:</strong> <?php echo $doInfo[0]['BL_No'];?></td>
	</tr>
	<tr>
		<td rowspan="5" colspan="3" valign="top">			
			<strong>Notify Party(Complete Name & Address)</strong>
			<p>
				<?php echo $doInfo[0]['Notify_name'];?><br>
				<?php echo $doInfo[0]['Notify_address'];?>
			</p>
		</td>
		<td>
			<b>Vessel</b><br><?php echo $doInfo[0]['Vessel_Name'];?>
		</td>
		<td>
			<b>Voyage No</b><br><?php echo $doInfo[0]['Voy_No'];?>
		</td>
		<td>
			<b>Print Date</b><br><?php echo date("Y-m-d H:i:s"); ?>
		</td>
	</tr>
	<tr>
		<td>
			<b>Place of Receipt</b><br>CHATTOGRAM PORT AUTHORITY
		</td>
		<td colspan="2" rowspan="4" valign="top">
			<strong>Other Numbering Identification</strong>
			<p>
				<font style="margin-left:2px;margin-right:4px;color:#0865fc;"><b>Reg No:</b></font> <?php echo $doInfo[0]['Import_Rotation_No'];?><br>
			</p>
			<p>
				<font style="margin-left:2px;margin-right:4px;color:#0865fc;"><b>BE No:</b></font> <?php echo $doInfo[0]['Bill_of_Entry_No'];?><br>
			</p>
			<p>
				<font style="margin-left:4px;margin-right:4px;color:#0865fc;"><b>Date:</b></font> <?php echo $doInfo[0]['Submission_Date'];?><br>
			</p>
		</td>
	</tr>
	<tr>
		<td>
			<b>Port of Loading</b><br>
			<?php echo $doInfo[0]['port_of_origin']." ".$doInfo[0]['Port_of_Shipment'];?>
		</td>
	</tr>
	<tr>
		<td>
			<b>Port of Discharge</b><br>
			CHATTOGRAM,BANGLADESH, Bangladesh
		</td>
	</tr>
	<tr>
		<td>
			<b>Place of Delivery</b><br>
			&nbsp;
		</td>
	</tr>
	<tr>
		<td colspan="3" valign="top">
			<strong>Consignee(Complete Name & Address)</strong>
			<p>
				<?php echo $doInfo[0]['Consignee_name'];?><br>
				<?php echo $doInfo[0]['Consignee_address'];?>
			</p>
		</td>
		<td colspan="3" valign="top">
			<strong>Shipper/Exporter(Complete Name & Address)</strong>
			<p>
						
			</p>
		</td>
	</tr>
	<tr>
		<td colspan="4" valign="top">
			<p>
				<b>Pack Description</b>
			</p>
			<p>
				Pack Number: <?php echo $doInfo[0]['Pack_Number'];?><br>
				Description: <?php echo $doInfo[0]['Pack_Description'];?><br>
					  Marks: <?php echo $doInfo[0]['Pack_Marks_Number'];?><br>
			</p>
		</td>
		<td valign="top" align="center">				
			<p>
				<b>Gross Weight</b>
				<?php echo $doInfo[0]['weight'].$doInfo[0]['weight_unit'];?>
			</p>				
			<p>
				<b>Delivered Weight</b><br>
				<?php echo $deliveredQty[0]['delv_quantity'].$doInfo[0]['weight_unit'];?>
			</p>
		</td>
		<td valign="top" align="center">
			<b>Measurement</b>
			<?php echo $deliveredQty[0]['measurement'];?>
		</td>
	</tr>	
</table>
<table border="1" style="border-collapse:collapse;margin-bottom:20px;" align="center" width="80%">
	<tr>
		<th>Container No</th>
		<th>Seal No</th>
		<th>Seal Value</th>
		<th>Size/Type/Height</th>
		<th>Pkgs</th>
		<th>Weight</th>
		<th>Measurement</th>
		<th>Real Bond/Pick up No</th>
	</tr>
	<tr>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
	</tr>
</table>
<table border="1" style="border-collapse:collapse;margin-bottom:20px;" align="center" width="80%">
	<tr>
		<th>Sl</th>
		<th>Truck No</th>
		<th>Quantity</th>
	</tr>
	<?php 
		$totalPack = 0;
		$totQty = 0;
		for ($i=0;$i<count($truckDtls);$i++) {
			$totQty +=  $truckDtls[$i]["delv_pack"];
	?>
	<tr>
		<td align="center"><?php echo $i+1;?></td>
		<td align="center"><?php echo $truckDtls[$i]['truck_id'];?></td>
		<td align="center"><?php echo $truckDtls[$i]['delv_pack'];?></td>
	</tr>
	<?php } ?>
	<tr>
		<th colspan="2">Total</th>
		<td align="center"><?php echo $totQty; ?></td>
	</tr>
</table>