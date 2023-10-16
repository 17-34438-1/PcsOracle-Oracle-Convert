<?php

include("mydbPConnection.php");
//count($rtnTruckNumber);
//for($i=0;$i<count($rtnTruckNumber);$i++)
	
	$cartDtl_str="SELECT do_truck_details_entry.id AS trucVisitId,verify_info_fcl_id,verify_other_data_id,import_rotation,cont_no,
		delv_pack,actual_delv_pack, igm_pack_unit.Pack_Unit AS actual_delv_unit, agent_name,agent_code, traffic_chk_st,traffic_chk_by,
		traffic_chk_time,yard_security_chk_st,yard_security_chk_by,yard_security_chk_time,cnf_chk_st,cnf_chk_by,cnf_chk_time,
		truck_id,gate_no,driver_name,driver_gate_pass,assistant_name,assistant_gate_pass,igm_detail_cont_id,
		igm_details.BL_No,igm_masters.Vessel_Name,				
		cnf_lic_no, substr(igm_details.Description_of_Goods,1,100) as Description_of_Goods,igm_details.Pack_Marks_Number,Consignee_name
		FROM do_truck_details_entry
		LEFT JOIN verify_info_fcl ON verify_info_fcl.id=do_truck_details_entry.verify_info_fcl_id
		LEFT JOIN vcms_vehicle_agent ON verify_info_fcl.jetty_sirkar_id = vcms_vehicle_agent.id
		LEFT JOIN igm_detail_container ON igm_detail_container.id=verify_info_fcl.igm_detail_cont_id
		LEFT JOIN igm_details ON igm_detail_container.igm_detail_id=igm_details.id
		LEFT JOIN igm_masters ON igm_masters.id=igm_details.IGM_id
		LEFT JOIN igm_pack_unit ON igm_pack_unit.id=do_truck_details_entry.actual_delv_unit
		WHERE do_truck_details_entry.id='$trucVisitId'";
	
	$rtnCartTicket = $this->bm->dataSelectDb1($cartDtl_str);
	$verify_info_fcl_id = "";
	$verify_other_data_id = "";

	if(count($rtnCartTicket)>0){
		$verify_info_fcl_id = $rtnCartTicket[0]['verify_info_fcl_id'];
		$verify_other_data_id = $rtnCartTicket[0]['verify_other_data_id'];
	}

	// if(is_null($verify_info_fcl_id)){
	// 	$cartDtl_str = "SELECT do_truck_details_entry.id AS trucVisitId,do_truck_details_entry.import_rotation,cont_no,
	// 	delv_pack,actual_delv_pack, actual_delv_unit,traffic_chk_st,traffic_chk_by,
	// 	traffic_chk_time,yard_security_chk_st,yard_security_chk_by,yard_security_chk_time,cnf_chk_st,cnf_chk_by,cnf_chk_time,
	// 	truck_id,gate_no,driver_name,driver_gate_pass,assistant_name,assistant_gate_pass,igm_sup_detail_container.igm_sup_detail_id,
	// 	igm_supplimentary_detail.BL_No,igm_masters.Vessel_Name,				
	// 	cnf_lic_no, igm_supplimentary_detail.Description_of_Goods,igm_supplimentary_detail.Pack_Marks_Number,Consignee_name
	// 	FROM do_truck_details_entry
	// 	LEFT JOIN verify_other_data ON verify_other_data.id=do_truck_details_entry.verify_other_data_id
	// 	LEFT JOIN shed_tally_info ON shed_tally_info.id = verify_other_data.shed_tally_id
	// 	LEFT JOIN igm_sup_detail_container ON igm_sup_detail_container.id = shed_tally_info.igm_sup_detail_id
	// 	LEFT JOIN igm_supplimentary_detail ON shed_tally_info.igm_sup_detail_id=igm_supplimentary_detail.id
	// 	LEFT JOIN igm_masters ON igm_masters.id=igm_supplimentary_detail.igm_master_id
	// 	WHERE do_truck_details_entry.id='$trucVisitId'";
		
	// 	$rtnCartTicket = $this->bm->dataSelectDb1($cartDtl_str);
	// }

	if(is_null($verify_info_fcl_id)){
		$cartDtl_str = "SELECT do_truck_details_entry.id AS trucVisitId,do_truck_details_entry.import_rotation,cont_no,
		delv_pack,actual_delv_pack, igm_pack_unit.Pack_Unit AS actual_delv_unit,  agent_name,agent_code, traffic_chk_st,traffic_chk_by,
		traffic_chk_time,yard_security_chk_st,yard_security_chk_by, yard_security_chk_time,cnf_chk_st,cnf_chk_by,cnf_chk_time,
		truck_id,gate_no,driver_name,driver_gate_pass,assistant_name,assistant_gate_pass,
		igm_supplimentary_detail.BL_No,igm_masters.Vessel_Name,				
		cnf_lic_no, igm_supplimentary_detail.Description_of_Goods, igm_supplimentary_detail.Pack_Marks_Number,Consignee_name
		FROM do_truck_details_entry
		LEFT JOIN lcl_dlv_assignment ON lcl_dlv_assignment.id=do_truck_details_entry.verify_other_data_id
		LEFT JOIN vcms_vehicle_agent ON lcl_dlv_assignment.jetty_sirkar_id = vcms_vehicle_agent.id
		INNER JOIN igm_supplimentary_detail ON igm_supplimentary_detail.id=lcl_dlv_assignment.igm_sup_dtl_id
		LEFT JOIN igm_masters ON igm_masters.id=igm_supplimentary_detail.igm_master_id
		LEFT JOIN igm_pack_unit ON igm_pack_unit.id=do_truck_details_entry.actual_delv_unit
		WHERE do_truck_details_entry.id='$trucVisitId'";
		
		$rtnCartTicket = $this->bm->dataSelectDb1($cartDtl_str);
	}
	
	$cont_no=$rtnCartTicket[0]['cont_no'];
	$rot_no=$rtnCartTicket[0]['import_rotation'];
	$blno = $rtnCartTicket[0]['BL_No'];

	$manif = str_replace("/"," ",$rot_no);
					
	$sql_be = "SELECT reg_no,reg_date FROM sad_info 
	INNER JOIN sad_item ON sad_item.sad_id = sad_info.id
	WHERE manif_num = '$manif' AND sum_declare = '$blno' LIMIT 1";

	$rslt_be=mysqli_query($con_cchaportdb,$sql_be);
	$be_no = "";
	$be_date = "";
	while($row_be=mysqli_fetch_object($rslt_be)){
		$be_no = $row_be->reg_no;
		$be_date = $row_be->reg_date;
	}
	 
	include("dbConection.php");

	$YardQuery = "SELECT DISTINCT Yard_No
	FROM ctmsmis.tmp_vcms_assignment  
	WHERE cont_no = '$cont_no' AND rot_no = '$rot_no'";

	$rsltYard = mysqli_query($con_sparcsn4,$YardQuery);

	$yardNo="";
	while($yardResult=mysqli_fetch_object($rsltYard)){
		$yardNo = $yardResult->Yard_No;
	}
				
	
/* 	$rslt_cartDtl=mysqli_query($con_cchaportdb,$cartDtl_str);
	while($row_cartDtl=mysqli_fetch_object($rslt_cartDtl))
	{ */
	
 	//$rtnCartTicket = $this->bm->dataSelectDb1($cartDtl_str);

	

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
	
	<?php if($rtnCartTicket[0]['actual_delv_pack']>0) { ?>
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
				<p style="margin-top:-3px;">www.cpatos.gov.bd</p>
			</td>
		</tr>
		<tr>
			<td colspan="5" align="center" style="font-size:13px; font-weight: bold;"><u>CART TICKET</u></td>
		</tr>
		<tr>
			<td width="70px" align="right">Visit Id.&nbsp;&nbsp;&nbsp;</td>
			<td style="border-bottom:1px solid black"><?php echo $rtnCartTicket[0]['trucVisitId'];?></td>
		</tr>
		<tr>
			<td width="70px" align="right">Rot No.&nbsp;&nbsp;&nbsp;</td>
			<td style="border-bottom:1px solid black"><?php echo $rtnCartTicket[0]['import_rotation'];?></td>
			<td colspan="2" align="right">Head Delivery Clerk.</td>
			<td style="border-bottom:1px solid black"></td>			
		</tr>
		<tr>
			<td width="30px" align="right">BL No.&nbsp;&nbsp;&nbsp;</td>
			<td style="border-bottom:1px solid black">
				<?php 
					if($cntHBL > 0)
					{
						for($j=0;$j<count($resHBL);$j++)
						{ 
							echo $resHBL[$j]['BL_No'];
							if($j != ($cntHBL-1))
							{
								echo ",";
							}
							if($j!=0 and $j%5==0)
							{
								echo "<br>";
							}							
						}
					}
					else
					{
						echo $rtnCartTicket[0]['BL_No'];
					}									
				?>
			</td>
			<td>&nbsp;</td>
			<td width="50" align="right">Job No.</td>
			<td style="border-bottom:1px solid black"></td>
		</tr>
		<!--<tr>
			<td>&nbsp;</td>
		</tr>-->
	</table>
	
	<table border="0" width="100%">
		<tr>
			<td >Yard/Shed No.</td>
			<td colspan="2" style="border-bottom:1px solid black; "><?php  echo $yardNo; //echo $rtnCartTicket[0]['shed_yard'];?></td>
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
			<td style="border-bottom:1px solid black;"><?php echo $be_no;?></td>
			<td align="center">of</td>
			<td style="border-bottom:1px solid black;"><?php echo $be_date;?></td>
			<td align="center">Truck No.</td>
			<th align="left" style="border-bottom:1px solid black;font-family: ind_bn_1_001"><font size='6'><b><?php echo $rtnCartTicket[0]['truck_id'];?></b></font></th>
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
			<th style="font-size:11px;">BL</th>
			<th style="font-size:11px;">Description</th>
			<th style="font-size:11px;">Quantity</th>
			<th style="font-size:11px;">Unit</th>
			<!--th style="font-size:11px;">Tally</th-->
			<th style="font-size:11px;">Consecutive Carts Total</th>
		</tr>
		<?php if($cntHBL > 0) { ?>
		<tr align="center">			
			<td style="font-size:11px;text-align:center;"></td>
			<td style="font-size:11px;text-align:center;display:none;"></td>
			<td style="font-size:11px;text-align:center;display:none;"></td>
			<td rowspan="<?php echo $cntHBL+1; ?>" align="center" style="font-size:11px;"><font size="5"><?php echo $rtnCartTicket[0]['actual_delv_pack'];?></font></td>
			<td rowspan="<?php echo $cntHBL+1; ?>" align="center" style="font-size:11px;"><?php echo $rtnCartTicket[0]['actual_delv_unit'];?></td>
			<td rowspan="<?php echo $cntHBL+1; ?>" align="center" style="font-size:11px;"><?php echo $rtnCartTicket[0]['delv_pack'];?></td>
		</tr>
		<?php for($k=0;$k<count($resHBL);$k++) { ?>
		<tr align="center">			
			<td width="30%" style="font-size:11px;text-align:center;"><?php echo $rtnCartTicket[0]['Pack_Marks_Number'];?></td>
			<td width="30%" style="font-size:11px;text-align:center;"><?php echo $resHBL[$k]['BL_No']; ?></td>
			<td width="30%" style="font-size:11px;text-align:center;"><?php echo $resHBL[$k]['Description_of_Goods']; ?></td>
		</tr>
		<?php } ?>
		<tr>
			<td rowspan="2">&nbsp;</td>
			<td rowspan="2">&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
		</tr>
		<tr>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
		</tr>
		<?php  } else { ?>
		<tr align="center">
			<td rowspan="2" width="30%" style="font-size:11px;text-align:center;"><?php echo $rtnCartTicket[0]['Pack_Marks_Number'];?></td>
			<td rowspan="2" width="30%" style="font-size:11px;text-align:center"><?php echo $rtnCartTicket[0]['Description_of_Goods'];?></td>
			<td rowspan="2" width="10%" style="font-size:11px;text-align:center;"><?php echo $rtnCartTicket[0]['BL_No'];?></td>
			<td align="center" style="font-size:11px;"><?php echo $rtnCartTicket[0]['actual_delv_pack'];?></td>
			<td align="center" style="font-size:11px;"><?php echo $rtnCartTicket[0]['actual_delv_unit'];?></td>
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
			<td>&nbsp;</td>
		</tr>
		<tr align="center">
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
		</tr>
		<?php } ?>	
	</table>
	<p style="padding-left:20px;">Container: <b><?php echo $rtnCartTicket[0]['cont_no'];?></b></p>
		
		
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
		<?php
			$cnfUser = $rtnCartTicket[0]['cnf_chk_by'];
			$sql_cnf = "SELECT u_name FROM users WHERE login_id='$cnfUser'";
			$rslt_cnfName = $this->bm->dataSelectDb1($sql_cnf);
			$cnfName = "";
			if(count($rslt_cnfName)>0){
				$cnfName = $rslt_cnfName[0]['u_name'];
			}

			$asiUser = $rtnCartTicket[0]['yard_security_chk_by'];
			$sql_asi = "SELECT u_name FROM users WHERE login_id='$asiUser'";
			$rslt_asiName = $this->bm->dataSelectDb1($sql_asi);
			$asiName = "";
			if(count($rslt_asiName)>0){
				$asiName = $rslt_asiName[0]['u_name'];
			}

			$trafficUser = $rtnCartTicket[0]['traffic_chk_by'];
			$sql_traffic = "SELECT u_name FROM users WHERE login_id='$trafficUser'";
			$rslt_traffic = $this->bm->dataSelectDb1($sql_traffic);
			$trafficName = "";
			if(count($rslt_traffic)>0){
				$trafficName = $rslt_traffic[0]['u_name'];
			}

		?>
		<tr>
			<td style="border:0px solid;width:15%;">C&F Name:</td>
			<td style="border:0px solid;width:20%;"><b><?php echo $cnfName;?></b></td>
			<td style="border:0px solid;" colspan="3"></td>
			<td style="border:0px solid;width:12%"></td>
			<td style="border:0px solid;" colspan="2"></td>
		</tr>
		<tr>
			<td style="border:0px solid;width:15%;">Jetty Sircar & Lic No:</td>
			<td style="border:0px solid;width:20%;" ><b><?php echo  $rtnCartTicket[0]['agent_name'].'-'. $rtnCartTicket[0]['agent_code']; ?></b></td>
			<td style="border:0px solid;width:10%;">ASI Name:</td>
			<td style="border:0px solid;width:15%;"><b><?php echo $asiName;?></b></td>			
			<td style="border:0px solid;width:15%;" colspan="2">Delivery Clerk:</td>
			<td style="border:0px solid;width:15%;"  align="left"><b><?php echo $trafficName;?></b></td>
			<td style="border:0px solid;"></td>
		</tr>
		<tr>
			<td style="border:0px solid;width:15%;">Confirmation :</td>
			<td style="border:0px solid;width:15%;"><b><?php echo $rtnCartTicket[0]['cnf_chk_by'];?></b></td>			
			<td style="border:0px solid;width:10%;">Confirmation :</td>
			<td style="border:0px solid;width:15%;"><b><?php echo $rtnCartTicket[0]['yard_security_chk_by'];?></b></td>			
			<td style="border:0px solid;width:20%;" colspan="2">Confirmation :</td>
			<td style="border:0px solid;width:15%;"  align="left"><b><?php echo $rtnCartTicket[0]['traffic_chk_by'];?></b></td>
			<td style="border:0px solid;"></td>
		</tr>		
		<tr>
			<td style="border:0px solid;width:14%;">Confirmed at:</td>
			<td style="border:0px solid;width:16%;"><b><?php echo $rtnCartTicket[0]['cnf_chk_time'];?></b></td>			
			<td style="border:0px solid;width:10%;">Confirmed at:</td>
			<td style="border:0px solid;width:16%;"><b><?php echo $rtnCartTicket[0]['yard_security_chk_time'];?></b></td>			
			<td style="border:0px solid;width:20%;" colspan="2">Confirmed at:</td>
			<td style="border:0px solid;width:16%;"><b><?php echo $rtnCartTicket[0]['traffic_chk_time'];?></b></td>
			<td style="border:0px solid;font-weight:bold;" align="center"><b>--------------------------<br/>Gate Sergeant </b></td>
		</tr>		
		<tr>
			<td style="border:0px solid;" colspan="8">&nbsp;</td>
		</tr>
		<tr>
			<td style="border:0px solid;" colspan="8">&nbsp;</td>
		</tr>
		<tr>
			<td style="border:0px solid;" colspan="8">&nbsp;</td>
		</tr>

		<tr>
			<td style="border:0px solid;" colspan="7">&nbsp;</td>
			<td style="border:0px solid;width:14%;" align="center" ><!--b>---------------------------------<br/>Gate Sergeant </b--></td>
		</tr>
		
		<tr>
			<td style="border:0px solid;" align="right"><!--Date:--></td>
			<!--td style="border-bottom:1px solid;width:15%;"></td-->
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
		<!--tr>
			<td colspan="8">&nbsp;</td>
		</tr-->
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
	
	<div style="position:absolute;bottom:25px;right:50px;width:20%;text-align:right">
		<?php  echo "Print Time: ".date("Y-m-d h:i:s");?>
	</div>
	
	<?php } ?>
	<!------!>
	<!--For showing extra trucks-------------------- starts-->
	<?php if(count($rsltExtraTrucks) > 0) { ?>
		
		<?php for($i=0;$i<count($rsltExtraTrucks);$i++){ ?>
			<?php if($rtnCartTicket[0]['actual_delv_pack']>0) {?>				
				<pagebreak />
			<?php } else if(($rtnCartTicket[0]['actual_delv_pack']==0) and ($i<count($rsltExtraTrucks)) and ($i!=0)) {?>
				<pagebreak />
			<?php } ?>
			
			<!--
			<?php // if($rtnCartTicket[0]['actual_delv_pack']>0) { ?>
				<pagebreak />
			<?php // } ?>
			
			<?php //if($rtnCartTicket[0]['actual_delv_pack']>0) { ?>
				<pagebreak />
			<?php //} else if($rtnCartTicket[0]['actual_delv_pack']==0 and $i==(count($rsltExtraTrucks)-1)) { ?>
				<pagebreak />
			<?php //} ?>
			-->
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
						<p style="margin-top:-3px;">www.cpatos.gov.bd</p>
					</td>
				</tr>
				<tr>
					<td colspan="5" align="center" style="font-size:13px; font-weight: bold;"><u>CART TICKET</u></td>
				</tr>
				<tr>
					<td width="70px" align="right">Visit Id.&nbsp;&nbsp;&nbsp;</td>
					<td style="border-bottom:1px solid black"><?php echo $rtnCartTicket[0]['trucVisitId'];?></td>
				</tr>
				<tr>
					<td width="70px" align="right">Rot No.&nbsp;&nbsp;&nbsp;</td>
					<td style="border-bottom:1px solid black"><?php echo $rtnCartTicket[0]['import_rotation'];?></td>
					<td colspan="2" align="right">Head Delivery Clerk.</td>
					<td style="border-bottom:1px solid black"></td>						
				</tr>
				<tr>
					<td width="30px" align="right">BL No.&nbsp;&nbsp;&nbsp;</td>
					<td style="border-bottom:1px solid black"><?php echo $rtnCartTicket[0]['BL_No'];?></td>
					<td>&nbsp;</td>
					<td width="50" align="right">Job No.</td>
					<td style="border-bottom:1px solid black"></td>
				</tr>
				<!--<tr>
					<td>&nbsp;</td>
				</tr>-->
			</table>
			
			<table border="0" width="100%">
				<tr>
					<td >Yard/Shed No.</td>
					<td colspan="2" style="border-bottom:1px solid black; "><?php  echo $yardNo; //echo $rtnCartTicket[0]['shed_yard'];?></td>
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
					<td style="border-bottom:1px solid black;"><?php echo $be_no;?></td>
					<td align="center">of</td>
					<td style="border-bottom:1px solid black;"><?php echo $be_date;?></td>
					<td align="center">Truck No.</td>
					<th align="left" style="border-bottom:1px solid black;font-family: ind_bn_1_001"><b><?php echo $rtnCartTicket[0]['truck_id'];?></b></th>
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
					<th style="font-size:11px;">Unit</th>
					<!--th style="font-size:11px;">Tally</th-->
					<th style="font-size:11px;">Consecutive Carts Total</th>
				</tr>
				<tr align="center">
					<td rowspan="2" width="30%" style="font-size:11px;"><?php echo $rtnCartTicket[0]['Pack_Marks_Number'];?></td>
					<td rowspan="2" width="40%" style="font-size:11px;"><?php echo $rtnCartTicket[0]['Description_of_Goods'];?></td>
					<td align="center" style="font-size:11px;"><?php echo $rsltExtraTrucks[$i]['pack_num'];?></td>
					<td align="center" style="font-size:11px;"><?php echo $rsltExtraTrucks[$i]['actual_delv_unit'];?></td>
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
			<p style="padding-left:20px;">Container: <b><?php echo $rsltExtraTrucks[$i]['cont_no'];?></b></p>
				
				
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
				<?php
					$cnfUser = $rtnCartTicket[0]['cnf_chk_by'];
					$sql_cnf = "SELECT u_name FROM users WHERE login_id='$cnfUser'";
					$rslt_cnfName = $this->bm->dataSelectDb1($sql_cnf);
					$cnfName = "";
					if(count($rslt_cnfName)>0){
						$cnfName = $rslt_cnfName[0]['u_name'];
					}

					$asiUser = $rtnCartTicket[0]['yard_security_chk_by'];
					$sql_asi = "SELECT u_name FROM users WHERE login_id='$asiUser'";
					$rslt_asiName = $this->bm->dataSelectDb1($sql_asi);
					$asiName = "";
					if(count($rslt_asiName)>0){
						$asiName = $rslt_asiName[0]['u_name'];
					}

					$trafficUser = $rtnCartTicket[0]['traffic_chk_by'];
					$sql_traffic = "SELECT u_name FROM users WHERE login_id='$trafficUser'";
					$rslt_traffic = $this->bm->dataSelectDb1($sql_traffic);
					$trafficName = "";
					if(count($rslt_traffic)>0){
						$trafficName = $rslt_traffic[0]['u_name'];
					}

				?>
				<tr>
					<td style="border:0px solid;width:15%;">C&F Name:</td>
					<td style="border:0px solid;width:20%;"><b><?php echo $cnfName;?></b></td>
					<td style="border:0px solid;" colspan="3"></td>
					<td style="border:0px solid;width:12%"></td>
					<td style="border:0px solid;" colspan="2"></td>
				</tr>
				<tr>
					<td style="border:0px solid;" colspan="8">&nbsp;</td>
				</tr>
				<tr>
					<td style="border:0px solid;width:15%;">Jetty Sarkar & Lic No:</td>
					<td style="border:0px solid;width:20%;" ><b><?php echo  $rtnCartTicket[0]['agent_name'].'-'. $rtnCartTicket[0]['agent_code']; ?></b></td>					
					<td style="border:0px solid;width:10%;">ASI Name:</td>
					<td style="border:0px solid;width:15%;"><b><?php echo $asiName;?></b></td>					
					<td style="border:0px solid;width:15%;" colspan="2">Delivery Clerk:</td>
					<td style="border:0px solid;width:15%;"  align="left"><b><?php echo $trafficName;?></b></td>
					<td style="border:0px solid;"></td>
				</tr>
				<tr>
					<td style="border:0px solid;width:15%;">Confirmation :</td>
					<td style="border:0px solid;width:15%;"><b><?php echo $rtnCartTicket[0]['cnf_chk_by'];?></b></td>
					
					<td style="border:0px solid;width:10%;">Confirmation :</td>
					<td style="border:0px solid;width:15%;"><b><?php echo $rtnCartTicket[0]['yard_security_chk_by'];?></b></td>					
					<td style="border:0px solid;width:20%;" colspan="2">Confirmation :</td>
					<td style="border:0px solid;width:15%;"  align="left"><b><?php echo $rtnCartTicket[0]['traffic_chk_by'];?></b></td>
					<td style="border:0px solid;"></td>
				</tr>				
				<tr>
					<td style="border:0px solid;width:14%;">Confirmed at:</td>
					<td style="border:0px solid;width:16%;"><b><?php echo $rtnCartTicket[0]['cnf_chk_time'];?></b></td>					
					<td style="border:0px solid;width:10%;">Confirmed at:</td>
					<td style="border:0px solid;width:16%;"><b><?php echo $rtnCartTicket[0]['yard_security_chk_time'];?></b></td>					
					<td style="border:0px solid;width:20%;" colspan="2">Confirmed at:</td>
					<td style="border:0px solid;width:16%;"><b><?php echo $rtnCartTicket[0]['traffic_chk_time'];?></b></td>
					<td style="border:0px solid;font-weight:bold;" align="center"><b>--------------------------<br/>Gate Sergeant </b></td>
				</tr>
				
				<tr>
					<td style="border:0px solid;" colspan="8">&nbsp;</td>
				</tr>
				<tr>
					<td style="border:0px solid;" colspan="8">&nbsp;</td>
				</tr>
				<tr>
					<td style="border:0px solid;" colspan="8">&nbsp;</td>
				</tr>

				<tr>
					<td style="border:0px solid;" colspan="7">&nbsp;</td>
					<td style="border:0px solid;width:14%;" align="center" ><!--b>---------------------------------<br/>Gate Sergeant </b--></td>
				</tr>
				
				<tr>
					<td style="border:0px solid;" align="right"><!--Date:--></td>
					<!--td style="border-bottom:1px solid;width:15%;"></td-->
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
				<!--tr>
					<td colspan="8">&nbsp;</td>
				</tr-->
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
			
			<div style="position:absolute;bottom:25px;right:50px;width:20%;text-align:right">
				<?php  echo "Print Time: ".date("Y-m-d h:i:s");?>
			</div>
		<?php } ?>
		<?php } ?>
	<!--For showing extra trucks-------------------- ends-->
	
		<?php //$mpdf->AddPage();?>
	</div>
	</div>
	
	<script>
		window.print();
	</script>
</body>
</html>

