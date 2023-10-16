<table border="0" align="center" width="90%">
	<tr>
		<?php if((isset($logo_pic)) or ($logo_pic != "")) { ?>
		<td align="left" width="20%">
			<?php if($Org_Type_id=="1") { ?>
				<!--img align="middle" width="80px" height="60px" src="<?php echo ASSETS_PATH?>mloLogo/<?php echo $mlo_logo; ?>"-->
				
				<img align="middle" width="80px" height="60px" 
					src="<?php echo $_SERVER['DOCUMENT_ROOT']."/assets/"?>mloLogo/<?php echo $mlo_logo; ?>">
			<?php } else { ?>
				<!--img align="middle" width="80px" height="60px" 
					src="<?php echo ASSETS_PATH?>organizationLogo/<?php echo $logo_pic; ?>"-->
					
				<img align="middle" width="80px" height="60px" 
					src="<?php echo $_SERVER['DOCUMENT_ROOT']."/assets/"?>organizationLogo/<?php echo $logo_pic; ?>">
			<?php }?>
		</td>
		<th align="left" width="80%"><h1><?php echo $Organization_Name; ?></h1></th>
		<?php } else { ?>
		<!--td align="center"><img align="middle" width="235px" height="75px" src="<?php echo IMG_PATH?>cpanew.jpg"></td-->
		<th align="center" colspan="2"><h1><?php echo $Organization_Name; ?></h1></th>
		<?php } ?>		
	</tr>
	<tr>
		<td align="center" colspan="2" style="font-size:12;"><?php echo $Address_1.", ".$Address_2;?></td>
	</tr>
	<tr>
		<td align="center" colspan="2" style="font-size:12;"><?php echo "License No: ".$License_No.", AIN No: ".$AIN_No_New.", Contact No: ".$Cell_No_1;?></td>
	</tr>
	<hr style="color:#000000;">
</table>
<table border="1" style="border-collapse:collapse;" align="center" width="85%">
	<form method="POST" enctype="multipart/form-data" action="<?php echo site_url("Report/shedDeliveryOrderInfoEntry") ?>">
		<tr>
			<th colspan="4" valign="center" align="center" style="font-size:20px;"><b>DELIVERY ORDER</b></th>
			<td colspan="2" valign="center">
				<strong>BL No:</strong> <?php echo $blno;?><br>
			</td>
			<!--td colspan="1" valign="center">
				<strong>Status:</strong> <?php echo @$contList[0]['cont_status'];?><br>
			</td-->
		</tr>
		<tr>
			<td rowspan="5" colspan="3" valign="top">			
				<strong>Notify Party(Complete Name & Address)</strong>
				<p>
					<?php echo $Notify_name;?><br>
					<?php echo $Notify_address;?>
				</p>
			</td>
			<td>
				<b>Vessel</b><br><?php echo $Vessel_Name;?>
			</td>
			<td>
				<b>Voyage No</b><br><?php echo $Voy_No;?>
			</td>
			<td>
				<b>Print Date</b><br><?php echo date("Y-m-d H:i:s"); ?>
			</td>
		</tr>
		<tr>
			<td>
				<b>Place of Receipt</b><br><?php echo $port_of_origin ;?>
			</td>
			<td colspan="2" rowspan="4" valign="top">
				<strong>Other Numbering Identification</strong><br>
				<p>
					<font style="margin-left:2px;margin-right:4px;color:#000000;"><b>EDO No:</b></font> <?php echo $edo_number; //$shedMloDo;?><br>
				</p>
				<p>
					<font style="margin-left:2px;margin-right:4px;color:#000000;"><b>Reg No:</b></font> <?php echo $Import_Rotation_No;?><br>
				</p>
				<p>
					<font style="margin-left:2px;margin-right:4px;color:#000000;"><b>BE No:</b></font> <?php echo $Bill_of_Entry_No;?><br>
				</p>
				<p>
					<font style="margin-left:2px;margin-right:4px;color:#000000;"><b>BE Date:</b></font> <?php echo $Bill_of_Entry_Dt;?><br>
				</p>
				<p>
					<font style="margin-left:2px;margin-right:4px;color:#000000;"><b>Office Code:</b></font> <?php echo $office_code;?><br>
				</p>
				<p>
					<font style="margin-left:2px;margin-right:4px;color:#000000;"><b>Remarks:</b></font> <?php echo $remarks;?><br>
				</p>
				<p>
					<font style="margin-left:4px;margin-right:4px;color:#000000;"><b>Date:</b></font> <?php echo $Submission_Date;?><br>
				</p>
			</td>
		</tr>
		<tr>
			<td>
				<b>Port of Loading</b><br>
				<?php echo $Port_of_Shipment;?>
			</td>
		</tr>
		<tr>
			<td>
				<b>Port of Discharge</b><br>
				CHATTOGRAM,BANGLADESH
			</td>
		</tr>
		<tr>
			<td>
				<b>Place of Delivery</b><br>
				<?php echo $Port_of_Destination; ?>
			</td>
		</tr>
		<tr>
			<td colspan="4" valign="top">
				<strong>Consignee(Complete Name & Address)</strong>
				<p>
					<?php echo $Consignee_name;?><br>
					<?php echo $Consignee_address;?>
				</p>
			</td>
			<td colspan="2" valign="top">
				<strong>Shipper/Exporter(Complete Name & Address)</strong>
				<p>
							
				</p>
			</td>
		</tr>
		<tr>
			<th valign="top" align="center">Quantity</th>
			<th valign="top" align="center"><nobr>Kind of Packages</nobr></th>
			<th valign="top" align="center">Description of Goods</th>
			<th valign="top" align="center">Marks and Numbers</th>
			<th valign="top" align="center">Gross Weight</th>
			<th valign="top" align="center">Measurement</th>
		</tr>
		<tr>
			<td align="center"><?php echo $igm_pack_number;?></td>
			<td align="center"><?php echo $Pack_Description;?></td>
			<td valign="top" align="center"><?php echo substr($Description_of_Goods,0,100);?></td>
			<td align="center"><?php echo $Pack_Marks_Number; ?></td>
			<td align="center"><?php echo $weight.$weight_unit;?></td>
			<td align="center"><?php echo $measurement;?></td>
		</tr>
		<!--tr>
			<td colspan="4" valign="top">
				<strong>Kind of Packages,Description of Goods ,Marks and Numbers, Container No/Seal No.</strong>
				<p>
					<?php echo $Description_of_Goods;?><br>
				</p>
				<p>
					<?php echo $Pack_Description;?><br>
				</p>
				<p>
					<?php echo $Pack_Marks_Number;?><br>
				</p>
			</td>
			<td valign="top" align="center">
				<b>Gross Weight</b>
				<p>
					<?php echo $weight.$weight_unit;?>
				</p>
			</td>
			<td valign="top" align="center">
				<b>Measurement</b>
				<p>
					<?php echo $measurement;?>
				</p>
			</td>
		</tr-->
		<?php if($type_of_bl=="HB" and $edoIGMType=="GM") { ?>
		<tr>
			<td colspan="2" align="center">
				<b>Applied at: </b> <?php echo $edoAppliedTime;?>
			</td>
			<td colspan="2" align="center">
				<b>Forwarded at: </b> <?php echo $edoForwardingTime;?>
			</td>
			<td colspan="2" align="center">
				<b>Issued at:  </b> <?php echo $edoUploadingTime;?>
			</td>			
		</tr>
		<?php } else { ?>
		<tr>
			<td colspan="3" align="center">
				<b>Applied at: </b> <?php echo $edoAppliedTime;?>
			</td>
			<td colspan="3" align="center">
				<b>Issued at:  </b> <?php echo $edoUploadingTime;?>
			</td>			
		</tr>
		<?php } ?>
		<tr>
			<td colspan="2" align="center">
				<b>C&F Lic:  </b> <?php echo $cnfLicenseNo;?>
			</td>
			<td colspan="2" align="center">
				<b>C&F Name:  </b> <?php echo $cnfName;?>
			</td>
			<td colspan="2" align="center">
				<b>Valid upto: </b> <?php echo $valid_upto_dt;?>
			</td>
		</tr>							
</table>
<table border="1" style="border-collapse:collapse;text-align:center;" align="center" width="85%">
	<tr>
		<th width="15%">Container No</th>
		<th width="15%">Seal No</th>
		<th width="15%">Size/Type/Height/Status/Location</th>
		<th width="15%">Weight</th>
		<th width="15%">Quantity</th>
		<th width="15%">Valid Upto</th>
	</tr>
	<?php for($i=0;$i<count($contList);$i++) { ?>
	<tr align="center">
		<td width="15%"><?php echo $contList[$i]['cont_number'];?></td>
		<td width="15%"><?php echo $contList[$i]['cont_seal_number'];?></td>												
		<td width="15%">
			<?php 
				echo $contList[$i]['cont_size']."/".$contList[$i]['cont_type']."/".$contList[$i]['cont_height']."/".$contList[$i]['cont_status']."/".
				$contList[$i]['cont_location_code'];?>
		</td>
		<td width="15%"><?php echo $contList[$i]['Cont_gross_weight'];?></td>
		<td width="15%"><?php echo $contList[$i]['cont_number_packaages'];?></td>
		<td width="15%"><?php echo $contList[$i]['valid_upto_date'];?></td>
	</tr>
	<?php } ?>
</table>

<?php
	require_once 'phpqrcode/qrlib.php';
	$destination_folder = $_SERVER['DOCUMENT_ROOT']."/assets/images/edo/";		
	$file = $ShedMloDOList[0]['id'].".png";
	$file1 = $destination_folder.$file;
	//$path = IMG_PATH."edo/".$file;
	$text =$ShedMloDOList[0]['id'];;
	QRcode::png($text, $file1, 'L', 10, 2);		
?>

<table border="0" style="border-collapse:collapse;text-align:center;" align="center" width="85%">
	<tr>
		<td>
			<img src="<?php echo $file1;?>" height="70" width="70">
		</td>
		<td>
			<?php				
				$text =$ShedMloDOList[0]['id'];						
				$barcodeText = $text;
			?>
			<barcode code="<?php echo $barcodeText; ?>" type="C128A" size="0.6" height="2" />
			<br>
			<?php echo sprintf("%010s", $text); ?>
		</td>
	</tr>
</table>