<table border="0" align="center" width="90%">
	<tr>
		<?php if((isset($logo_pic)) or ($logo_pic != "")) { ?>
		<td align="left" width="20%">
			<?php if($Org_Type_id=="1") { ?>
				<!--img align="middle" width="80px" height="60px" src="<?php echo ASSETS_PATH?>mloLogo/<?php echo $mlo_logo; ?>"-->
				
				<img align="middle" width="80px" height="60px" alt="" 
					src="<?php echo $_SERVER['DOCUMENT_ROOT']."/assets/"?>mloLogo/<?php echo $mlo_logo; ?>">
			<?php } else { ?>
				<!--img align="middle" width="80px" height="60px" 
					src="<?php echo ASSETS_PATH?>organizationLogo/<?php echo $logo_pic; ?>"-->
					
				<img align="middle" width="80px" height="60px" 
					src="<?php echo $_SERVER['DOCUMENT_ROOT']."/assets/"?>organizationLogo/<?php echo $logo_pic; ?>" >
			<?php }?>
		</td>
		<th align="left" width="80%"><h1><?php echo $Organization_Name; ?></h1></th>
		<?php } else { ?>
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

<h2 align="center"><font style="border-bottom: 1px solid;">DELIVERY ORDER</font></h3>

<table border="0" style="border-collapse:collapse;" align="center" width="85%">
	<tr>
		<th valign="center" align="left" width="20%">ROTATION NO</th>
		<th valign="center" align="center" width="2%">:</th>		
		<td valign="center" align="left"><?php echo $Import_Rotation_No;?></td>
		<th valign="center" align="right" width="20%">BL NO</th>	
		<th valign="center" align="center" width="2%">:</th>	
		<td valign="center" align="right"><?php echo $blno;?></td>
		
	</tr>
	<tr>
		<td colspan="6"></td>
	</tr>
	<tr>		
		<th valign="center" align="left" width="20%">BE NO</th>
		<th valign="center" align="center" width="2%">:</th>		
		<td valign="center" align="left"><?php echo $Bill_of_Entry_No;?></td>	
		<th valign="center" align="right" width="20%">BE DATE</th>	
		<th valign="center" align="center" width="2%">:</th>	
		<td valign="center" align="right"><?php echo $Bill_of_Entry_Dt;?></td>
	</tr>
	<tr>
		<td colspan="6"></td>
	</tr>
	<tr>		
		<th valign="center" align="left" width="20%">C&F NAME</th>
		<th valign="center" align="center" width="2%">:</th>		
		<td valign="center" align="left" width="20%"><?php echo $cnfName;?></td>		
		<th valign="center" align="right" width="20%">C&F LIC</th>	
		<th valign="center" align="center" width="2%">:</th>
		<td valign="center" align="right"><?php echo $cnfLicenseNo;?></td>
	</tr>
	<tr>
		<td colspan="6"></td>
	</tr>
	<tr>		
		<th valign="center" align="left" width="20%">SUBMISSION DATE</th>	
		<th valign="center" align="center" width="2%">:</th>
		<td valign="center" align="left" width="20%"><?php echo $Submission_Date;?></td>		
		<th valign="center" align="right" width="20%">FROM PORT</th>	
		<th valign="center" align="center" width="2%">:</th>
		<td valign="center" align="right" width="5%"><?php echo $port_of_origin." ".$Port_of_Shipment;?></td>
	</tr>
	<tr>
		<td colspan="6"></td>
	</tr>
	<tr>		
		<th valign="center" align="left" width="20%">VESSEL</th>
		<th valign="center" align="center" width="2%">:</th>		
		<td valign="center" align="left"><?php echo $Vessel_Name;?></td>		
		<th valign="center" align="right" width="20%">VOYAGE NO</th>	
		<th valign="center" align="center" width="2%">:</th>
		<td valign="center" align="right" width="5%"><?php echo $Voy_No;?></td>
	</tr>
	<tr>
		<td colspan="6"></td>
	</tr>
	<tr>		
		<th valign="center" align="left" width="20%">RECEIPT NO</th>
		<th valign="center" align="center" width="2%">:</th>		
		<td valign="center" align="left"><?php echo $receipt_no;?></td>		
		<th valign="center" align="right" width="20%">RECEIPT DATE</th>	
		<th valign="center" align="center" width="2%">:</th>
		<td valign="center" align="right" width="5%"><?php echo $receipt_date;?></td>
	</tr>
	<tr>
		<td colspan="6"></td>
	</tr>
	<tr>		
		<th valign="center" align="left" width="20%">LINE NO</th>	
		<th valign="center" align="center" width="2%">:</th>
		<td valign="center" align="left"><?php echo $line_no;?></td>		
		<th valign="center" align="right" width="20%">REMARKS</th>	
		<th valign="center" align="center" width="2%">:</th>
		<td valign="center" align="right" width="5%"><?php echo $remarks;?></td>
	</tr>
</table>

<table border="1" style="border-collapse:collapse;" align="center" width="85%">
	<tr>
		<th valign="top" align="center">MARKS AND NUMBER</th>
		<th valign="top" align="center"><nobr>QUANTITY</nobr></th>
		<th valign="top" align="center">KIND OF PACKAGES</th>
		<th valign="top" align="center">DESCRIPTION OF GOODS</th>
		<th valign="top" align="center">GROSS WEIGHT</th>
	</tr>
	<tr>
		<td align="center" style="height: 160px;"><?php echo substr($Pack_Marks_Number,0,100);?></td>
		<td align="center"><?php echo $igm_pack_number;?></td>
		<td align="center"><?php echo $Pack_Description;?></td>
		<td align="center"><?php echo substr($Description_of_Goods,0,100);?></td>
		<td align="center"><?php echo $weight." ".$weight_unit;?></td>
	</tr>
	<tr>
		<th align="center">TOTAL</th>
		<td align="center"><?php echo $igm_pack_number." ".$Pack_Description;?></td>
		<td align="center">&nbsp;</td>
		<td align="center">&nbsp;</td>
		<td align="center"><?php echo $weight." ".$weight_unit;?></td>
	</tr>
</table>