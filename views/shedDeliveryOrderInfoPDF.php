<table align="center" width="850px">
	<tr>
		<td align="center"><img align="middle" width="235px" height="75px" src="<?php echo IMG_PATH?>cpanew.jpg"></td>
	</tr>
	<tr>
		<!--td align="center"><b>Delivery Order</b></td-->
	</tr>
</table>
<table border="1" style="border-collapse:collapse;" align="center" width="85%">
	<form method="POST" enctype="multipart/form-data" action="<?php echo site_url("report/shedDeliveryOrderInfoEntry") ?>">
		<tr>
			<th colspan="4" valign="center" align="center" style="font-size:20px;"><b>DELIVERY ORDER</b></th>
			<td colspan="2" valign="center">
				<strong>BL No:</strong> <?php echo $doInfo[0]['BL_No'];?><br>
				<strong>BL Type:</strong> <?php echo $type_of_bl;?>
			</td>
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
				<strong>Other Numbering Identification</strong><br>
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
				<strong>Kind of Packages,Description of Goods ,Marks and Numbers, Container No/Seal No.</strong>
				<p>
					<?php echo $doInfo[0]['Description_of_Goods'];?><br>
				</p>
				<p>
					<?php echo $doInfo[0]['Pack_Description'];?><br>
				</p>
				<p>
					<?php echo $doInfo[0]['Pack_Marks_Number'];?><br>
				</p>
			</td>
			<td valign="top" align="center">
				<b>Gross Weight</b>
				<p>
					<?php echo $doInfo[0]['weight'].$doInfo[0]['weight_unit'];?>
				</p>
			</td>
			<td valign="top" align="center">
				<b>Measurement</b>
				<p>
					<?php echo $ShedMloDOList[0]['measurement'];?>
				</p>
			</td>
		</tr>
		<tr>
			<td colspan="4" align="center">
				<b>CNF Name:  </b> <?php if(isset($cnf_name[0]['name'])){echo $cnf_name[0]['name'];}?>
			</td>
			<td colspan="2" align="center">
				<b>Valid upto: </b> <?php echo $ShedMloDOList[0]['valid_upto_dt'];?>
			</td>
		</tr>							
</table>
<table border="1" style="border-collapse:collapse;text-align:center;" align="center" width="85%">
	<tr>
		<th>Container No</th>
		<th>Seal No</th>
		<th>Size/Type/Height</th>
		<th>Weight</th>
		<th>Pack Number</th>
	</tr>
	<?php for($i=0;$i<count($contList);$i++) { ?>
	<tr align="center">
		<td><?php echo $contList[$i]['cont_number'];?></td>
		<td><?php echo $contList[$i]['cont_seal_number'];?></td>												
		<td>
			<?php echo $contList[$i]['cont_size']."/".$contList[$i]['cont_type']."/".$contList[$i]['cont_height'];?>
		</td>
		<td><?php echo $contList[$i]['cont_weight'];?></td>
		<td><?php echo $contList[$i]['Pack_Number'];?></td>
	</tr>
	<?php } ?>
</table>