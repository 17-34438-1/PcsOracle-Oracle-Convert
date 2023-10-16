<?php

include("mydbPConnection.php");
//count($rtnTruckNumber);
//for($i=0;$i<count($rtnTruckNumber);$i++)

	   $cartDtl_str="SELECT do_truck_details_entry.id AS trucVisitId,import_rotation,cont_no,
				delv_pack,actual_delv_pack, actual_delv_unit,traffic_chk_st,traffic_chk_by,
				traffic_chk_time,yard_security_chk_st,yard_security_chk_by,yard_security_chk_time,cnf_chk_st,cnf_chk_by,cnf_chk_time,
				truck_id,gate_no,driver_name,driver_gate_pass,assistant_name,assistant_gate_pass,igm_detail_cont_id,
				igm_details.BL_No,igm_masters.Vessel_Name,				
				cnf_lic_no, igm_details.Description_of_Goods,igm_details.Pack_Marks_Number,Consignee_name
				FROM do_truck_details_entry
				LEFT JOIN verify_info_fcl ON verify_info_fcl.id=do_truck_details_entry.verify_info_fcl_id
				LEFT JOIN igm_detail_container ON igm_detail_container.id=verify_info_fcl.igm_detail_cont_id
				LEFT JOIN igm_details ON igm_detail_container.igm_detail_id=igm_details.id
				LEFT JOIN igm_masters ON igm_masters.id=igm_details.IGM_id
				WHERE do_truck_details_entry.id='$trucVisitId'";
				
	
/* 	$rslt_cartDtl=mysqli_query($con_cchaportdb,$cartDtl_str);
	while($row_cartDtl=mysqli_fetch_object($rslt_cartDtl))
	{ */
	
 	$rtnCartTicket = $this->bm->dataSelectDb1($cartDtl_str);

	

	/* $rtnCartTicket = $this->bm->dataSelectDb1($sqlCartTicket);
	$data['rtnCartTicket']=$rtnCartTicket; */  
	
?>
<html>
	<head>
		<!--style>
			 table {border-collapse: collapse;}
		</style-->
	</head>
<body>
	
	
	<table width="100%" border="0" >
		<tr align="center">
			<!--td colspan="5" style="font-size:30px; font-weight: bold;">ORION INFUSION LTD.</td-->
			<td colspan="5" align="center" style="font-size:20px; font-weight: bold;"><?php //echo $rslt_CNFName[0]['name'];?></td>
		</tr>
		<tr align="center">
			<td colspan="5" align="center" style="font-size:16px; font-weight: bold;"> </td>
		</tr>
		<!--tr align="center">
			<td colspan="5">
				<p> 532/533, Sk. Mujib Road, Dewanhat, Chittagong </p>
			</td>
		</tr-->
		<!--tr align="center">
			<td colspan="5">
				<p> Phone: 031-718319, Mobile: 01819-841272, 01712-232155 </p>
			</td>
		</tr-->
		<tr >
			<td align="center" colspan="5">
				<p style="font-size:16px; "> CHITTAGONG PORT AUTHORITY </p>
			</td>
		</tr>
		<tr>
			<td colspan="5" align="center" style="font-size:13px; font-weight: bold;"><u>CART TICKET</u></td>
		</tr>
		<tr>
			<td width="70px" align="right">Rot No.&nbsp;&nbsp;&nbsp;</td>
			<td style="border-bottom:1px solid black"><?php echo $rtnCartTicket[0]['import_rotation'];?></td>
			<td>&nbsp;</td>
			<td width="50">Job No.</td>
			<td style="border-bottom:1px solid black"></td>
		</tr>
		<tr>
			<td width="30px" align="right">BL No.&nbsp;&nbsp;&nbsp;</td>
			<td style="border-bottom:1px solid black"><?php echo $rtnCartTicket[0]['BL_No'];?></td>
		</tr>
		<!--<tr>
			<td>&nbsp;</td>
		</tr>-->
	</table>
	
	<table border="0" width="100%">
		<tr>
			<td >Shed No.</td>
			<td colspan="2" style="border-bottom:1px solid black; "><?php //echo $rtnCartTicket[0]['shed_yard'];?></td>
			<td align="center" >Release Order No.</td>
			<td colspan="2 "style="border-bottom:1px solid black;"><?php //echo $rtnCartTicket[0]['cus_rel_odr_no'];?></td>
			<td align="center">Of</td>
			<td style="border-bottom:1px solid black;"></td>
		</tr>
		<tr>
			<td >Ex. S/S. M/V</td>
			<td style="border-bottom:1px solid black;"><?php echo $rtnCartTicket[0]['Vessel_Name'];?></td>
			<td align="center">No</td>
			<td style="border-bottom:1px solid black;"></td>
			<td align="center">Consignee:</td>
			<td colspan="3" style="border-bottom:1px solid black;"><?php echo $rtnCartTicket[0]['Consignee_name'];?></td>
		</tr>
		<tr>
			<td>B/E No.</td>
			<td style="border-bottom:1px solid black;"><?php //echo $rtnCartTicket[0]['be_no'];?></td>
			<td align="center">of</td>
			<td style="border-bottom:1px solid black;"><?php //echo $rtnCartTicket[0]['be_date'];?></td>
			<td align="center">Truck No.</td>
			<td style="border-bottom:1px solid black;"><?php echo $rtnCartTicket[0]['truck_id'];?></td>
			<td align="center">Gate No.</td>	
			<td align="center" style="border-bottom:1px solid black;"><?php echo $rtnCartTicket[0]['gate_no'];?></td>
		</tr>
		<!--tr>
			<td>&nbsp;</td>
		</tr-->
	</table>
			
	<table border="1" width="100%" align="center">
		<tr>
			<th style="font-size:11px;">Marks</th>
			<th style="font-size:11px;">Description</th>
			<th style="font-size:11px;">Quantity</th>
			<th style="font-size:11px;">Tally</th>
			<th style="font-size:11px;">Consecutive Carts Total</th>
		</tr>
		<tr align="center">
			<td rowspan="2" width="30%" style="font-size:11px;"><?php echo $rtnCartTicket[0]['Pack_Marks_Number'];?></td>
			<td rowspan="2" width="40%" style="font-size:11px;"><?php echo $rtnCartTicket[0]['Description_of_Goods'];?></td>
			<td align="center" style="font-size:11px;"><?php echo $rtnCartTicket[0]['actual_delv_pack'];?></td>
			<td>&nbsp;</td>
			<td align="center" style="font-size:11px;"><?php echo $rtnCartTicket[0]['delv_pack'];?></td>
		</tr>
		<tr align="center">
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
		</tr>
		<tr align="center">
			<td rowspan="2">&nbsp;</td>
			<td rowspan="2">&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
		</tr>
		<tr align="center">
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
		</tr>
		
	</table>
		
		
	<table border="0" width="100%">
		<!--<tr>
			<td>&nbsp;</td>
		</tr>-->
		<tr>
			<td colspan="2" style="font-size:11px;">Total Packages (in words)</td>
			<td style="border-bottom:1px solid black;" colspan="6"></td>
		</tr>
		<tr>
			<td colspan="3" style="font-size:11px;">Received the above in full.</td>
			<td colspan="3" style="width:40%; font-size:11px;">For-<?php //echo $rtnCartTicket[0]['cnf_name'];?></td>
			<td colspan="2" style="width:40%; font-size:11px;" align="right">Checked and found ok</td>
		</tr>
		<tr>
			<td colspan="8">&nbsp;</td>
		</tr>
		<tr>
			<td style="border:0px solid;width:15%;">Confirmation (C&F):</td>
			<td style="border:0px solid;width:15%;"><b><?php echo $rtnCartTicket[0]['cnf_chk_by'];?></b></td>
			<td style="border:0px solid;"></td>
			<td style="border:0px solid;width:15%;">Confirmation (ASI):</td>
			<td style="border:0px solid;width:15%;"><b><?php echo $rtnCartTicket[0]['yard_security_chk_by'];?></b></td>
			
			<td style="border:0px solid;width:15%;" colspan="2">Confirmation (Delivery Clerk):</td>
			<td style="border:0px solid;width:15%;"  align="center"><b><?php echo $rtnCartTicket[0]['traffic_chk_by'];?></b></td>
		</tr>
		
		<tr>
			<td style="border:0px solid;" colspan="8">&nbsp;</td>
		</tr>
		<tr>
			<td style="border:0px solid;" align="right">Date:</td>
			<td style="border-bottom:1px solid;width:15%;"></td>
			<td style="border:0px solid;"></td>
			<td style="border:0px solid;"></td>
			<td style="border:0px solid;"></td>
			<td style="border:0px solid;" colspan="2"><!--Consignee's Signature: --></td>
			<td style="border:0px solid;"></td>
			<td style="border:0px solid;"></td>
		</tr>
		<!--tr>
			<td style="font-size:11px;" align="right">Confirmation (C&F):</td>
			<td align="left"><b><?php echo $rtnCartTicket[0]['cnf_chk_by'];?></b></td>
			<td style="font-size:11px;" colspan="2">Confirmation (ASI):</td>	
			<td style="width:10%" align="left"><b><?php echo $rtnCartTicket[0]['yard_security_chk_by'];?></b></td>
			
			<td style="font-size:11px;">Confirmation (Delivery Clerk):</td>	
			<td style="width:10%"><b><?php echo $rtnCartTicket[0]['traffic_chk_by'];?></b></td>
		</tr>
		<tr>
			<td style="font-size:11px;" align="right">Date</td>
			<td style="border-bottom:1px solid black; width:15%; font-size:12px;" align="left"></td>
			<td style="width:5%"></td>
			<td style="font-size:11px;" colspan="3">Consignee's Signature</td>
			<td style="width:10%"></td>
		</tr-->
		<tr>
			<td colspan="8" style="border-bottom:1px solid black; width:100%">&nbsp;</td>
		</tr>
		<tr>
			<td colspan="8" style="font-size:11px;"><!--N.B.: Loss of Cart Ticket must immediately be reported to the Shed Master of Shed Foreman. Unused Cart Ticket must be returned to the delivery Foreman on the same day they were issued--></td>
		</tr>
		<tr>
			<td colspan="8">&nbsp;</td>
		</tr>
	</table>
	<?php
		require_once 'phpqrcode/qrlib.php';
		$destination_folder = $_SERVER['DOCUMENT_ROOT']."/pcs/assets/images/qrcode/";
				
		$file = $trucVisitId.".png";
		$file1 = $destination_folder.$file;
		$path = IMG_PATH."qrcode/".$file;
		$text =$trucVisitId;
		QRcode::png($text, $file1, 'L', 10, 2);		
		?>
	<table border="0" width="60%" align="center">
		<tr>
			<td align="left">

				<img src="<?php echo $path;?>" height="68" width="68">

			</td>
			<td align="center">							
				<?php								
					$barcodeText = $trucVisitId;
				?>
				<barcode code="<?php echo $barcodeText; ?>" type="C128A" size="0.5" height="2" />
				<br>
				<?php echo sprintf("%010s", $text); ?>
				<?php //echo $trucVisitId; ?>						
			</td>			
		</tr>
	</table>
		<?php //$mpdf->AddPage();?>
	</div>
	</div>
	<script>
		window.print();
	</script>
</body>
</html>

