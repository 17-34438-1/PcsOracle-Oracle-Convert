<?php

include("mydbPConnection.php");
//count($rtnTruckNumber);
//for($i=0;$i<count($rtnTruckNumber);$i++)
	
	$cartDtl_str="SELECT do_truck_details_entry.id AS trucVisitId,verify_info_fcl_id,verify_other_data_id,import_rotation,cont_no,
		delv_pack,actual_delv_pack, igm_pack_unit.Pack_Unit AS actual_delv_unit, agent_name,agent_code, traffic_chk_st,traffic_chk_by,
		traffic_chk_time,yard_security_chk_st,yard_security_chk_by,yard_security_chk_time,cnf_chk_st,cnf_chk_by,cnf_chk_time,
		truck_id,gate_no,driver_name,driver_gate_pass,assistant_name,assistant_gate_pass,igm_detail_cont_id,
		igm_details.BL_No,igm_masters.Vessel_Name,
		oracle_nts_data.be_no, oracle_nts_data.be_dt, oracle_nts_data.verify_no,oracle_nts_data.cp_no,oracle_nts_data.unit_number,oracle_nts_data.office_code,cnf_lic_no, substr(igm_details.Description_of_Goods,1,100) as Description_of_Goods,substr(igm_details.Pack_Marks_Number,1,100) AS Pack_Marks_Number,Consignee_name,Notify_name,
		agent_name AS jetty_sirkar_name,card_number AS jetty_sirkar_gate_pass,
		(SELECT agent_photo
		FROM vcms_vehicle_agent
		WHERE card_number=jetty_sirkar_gate_pass AND agent_photo!=''
		LIMIT 1) AS jetty_sirkar_photo,
		agent_photo AS jetty_sirkar_photo_old
		FROM do_truck_details_entry
		LEFT JOIN verify_info_fcl ON verify_info_fcl.id=do_truck_details_entry.verify_info_fcl_id
		LEFT JOIN vcms_vehicle_agent ON verify_info_fcl.jetty_sirkar_id = vcms_vehicle_agent.id
		LEFT JOIN igm_detail_container ON igm_detail_container.id=verify_info_fcl.igm_detail_cont_id
		LEFT JOIN igm_details ON igm_detail_container.igm_detail_id=igm_details.id
		LEFT JOIN igm_masters ON igm_masters.id=igm_details.IGM_id
		LEFT JOIN igm_pack_unit ON igm_pack_unit.id=do_truck_details_entry.actual_delv_unit
		LEFT JOIN oracle_nts_data ON oracle_nts_data.igm_detail_id=igm_details.id
		WHERE do_truck_details_entry.id='$visitId' ORDER BY cp_date DESC  LIMIT 1";
	
	$rtnCartTicket = $this->bm->dataSelectDb1($cartDtl_str);
	$verify_info_fcl_id = "";
	$verify_other_data_id = "";

	if(count($rtnCartTicket)>0){
		$verify_info_fcl_id = $rtnCartTicket[0]['verify_info_fcl_id'];
		$verify_other_data_id = $rtnCartTicket[0]['verify_other_data_id'];
	}

	if(is_null($verify_info_fcl_id)){
		$cartDtl_str = "SELECT do_truck_details_entry.id AS trucVisitId,do_truck_details_entry.import_rotation,cont_no,
		delv_pack,actual_delv_pack, igm_pack_unit.Pack_Unit AS actual_delv_unit,  agent_name,agent_code, traffic_chk_st,traffic_chk_by,
		traffic_chk_time,yard_security_chk_st,yard_security_chk_by, yard_security_chk_time,cnf_chk_st,cnf_chk_by,cnf_chk_time,
		truck_id,gate_no,driver_name,driver_gate_pass,assistant_name,assistant_gate_pass,
		igm_supplimentary_detail.BL_No,igm_masters.Vessel_Name,		
		oracle_nts_data.be_no, oracle_nts_data.be_dt, oracle_nts_data.verify_no,oracle_nts_data.unit_number,oracle_nts_data.cp_no,oracle_nts_data.office_code,cnf_lic_no, substr(igm_supplimentary_detail.Description_of_Goods,1,100) as Description_of_Goods , substr(igm_supplimentary_detail.Pack_Marks_Number,1,100) AS Pack_Marks_Number,Consignee_name,
		agent_name AS jetty_sirkar_name,card_number AS jetty_sirkar_gate_pass,
		(SELECT agent_photo 
		FROM vcms_vehicle_agent 
		WHERE card_number=jetty_sirkar_gate_pass AND agent_photo!='' 
		LIMIT 1) AS jetty_sirkar_photo,
		agent_photo AS jetty_sirkar_photo_old
		FROM do_truck_details_entry
		LEFT JOIN lcl_dlv_assignment ON lcl_dlv_assignment.id=do_truck_details_entry.verify_other_data_id
		LEFT JOIN vcms_vehicle_agent ON lcl_dlv_assignment.jetty_sirkar_id = vcms_vehicle_agent.id
		INNER JOIN igm_supplimentary_detail ON igm_supplimentary_detail.id=lcl_dlv_assignment.igm_sup_dtl_id
		LEFT JOIN igm_masters ON igm_masters.id=igm_supplimentary_detail.igm_master_id
		LEFT JOIN igm_pack_unit ON igm_pack_unit.id=do_truck_details_entry.actual_delv_unit
		LEFT JOIN oracle_nts_data ON oracle_nts_data.igm_sup_detail_id=igm_supplimentary_detail.id
		WHERE do_truck_details_entry.id='$visitId' ORDER BY cp_date DESC  LIMIT 1";
		
		$rtnCartTicket = $this->bm->dataSelectDb1($cartDtl_str);
	}
	// echo $cartDtl_str;return;
	$cont_no=$rtnCartTicket[0]['cont_no'];
	$rot_no=$rtnCartTicket[0]['import_rotation'];
	$blno = $rtnCartTicket[0]['BL_No'];

	$oracleNtsQuery = "SELECT * FROM oracle_nts_data WHERE bl_no='$blno' AND imp_rot_no='$rot_no' ORDER BY cp_date DESC  LIMIT 1";
	$oracleNts=mysqli_query($con_cchaportdb,$oracleNtsQuery);
	
	$rowcount=mysqli_num_rows($oracleNts);
	//echo $rowcount;
	//print_r($allHBL);
	if($allHBL=="")
		$allHBL="''";
	
	if($rowcount<1)
	{
		
		$oracleNtsQuery = "SELECT * FROM oracle_nts_data WHERE bl_no IN ($allHBL) AND imp_rot_no='$rot_no' ORDER BY cp_date DESC  LIMIT 1";
		$oracleNts=mysqli_query($con_cchaportdb,$oracleNtsQuery);
	}
	
	$cpNo = "";
	$verify_no = "";
	$beNo = "";
	$beDate = "";
	$officeCode = "";
	
	while($oracleRow=mysqli_fetch_object($oracleNts)){
		$cpNo = $oracleRow->cp_no;
		$verify_no = $oracleRow->verify_no;
		$beNo = $oracleRow->be_no;
		$beDate = $oracleRow->be_dt;
		$officeCode = $oracleRow->office_code;
	}
	
	$jetty_sirkar_name=$rtnCartTicket[0]['jetty_sirkar_name'];
	$jetty_sirkar_gate_pass=$rtnCartTicket[0]['jetty_sirkar_gate_pass'];
	$jetty_sirkar_photo=$rtnCartTicket[0]['jetty_sirkar_photo'];

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
	
	<?php
		if($rtnCartTicket[0]['actual_delv_pack']>0) { 
			require_once 'phpqrcode/qrlib.php';
			$destination_folder = $_SERVER['DOCUMENT_ROOT']."/pcs/assets/images/qrcode/";
					
			$file = $trucVisitId.".png";
			$file1 = $destination_folder.$file;
			$path = IMG_PATH."qrcode/".$file;
			$text =$trucVisitId;
			QRcode::png($text, $file1, 'L', 10, 2);		
	?>
	<div style="position:absolute;top:15px;right:50px;width:30%;text-align:right">
		<?php  echo "Print Time: ".date("Y-m-d h:i:s");?>
	</div>
	<table width="100%" border="0" style="padding-top:0px;margin-top:0px;padding-bottom:5px;margin-bottom:5px;">
		<!-- <tr align="center">
			<td colspan="5" align="center" style="font-size:20px; font-weight: bold;"><?php //echo $rslt_CNFName[0]['name'];?></td>
		</tr> -->
		<!-- <tr align="center">
			<td colspan="5" align="center" style="font-size:16px; font-weight: bold;"> </td>
		</tr> -->
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
		<tr>
			<td colspan="5" style="padding-top:0px;margin-top:0px;">
				<table align="center" style="padding-top:0px;margin-top:0px;">
					<tr>
						<td width="25%" align="right">
							<img src="<?php echo $path;?>" height="68" width="68">
						</td>
						<td align="center" width="50%">
							<p style="font-size:16px; "> CHITTAGONG PORT AUTHORITY </p>
							<p style="margin-top:-3px;">www.cpatos.gov.bd</p>
							<p style="font-size:14px; font-weight: bold;"><u>CART TICKET</u></p>
							<p style="font-size:16px; font-weight: bold;">
								<?php 
									$cfName = "";
									for($z=0;$z<count($CNFresult);$z++){
										$cfName = $CNFresult[$z]['name'];
									}
									echo $cfName;
								?>
							</p>
						</td>
						<td width="25%" align="center">
							<?php								
								$barcodeText = $visitId;
							?>
							<barcode code="<?php echo $barcodeText; ?>" type="C128A" size="0.5" height="2" />
							<br>
							<?php echo sprintf("%010s", $text); ?>
						</td>
						<?php
						if($jetty_sirkar_photo!="")
						{
						?>
						<td>
							<img src="<?php echo '/biometricPhoto/'.$jetty_sirkar_gate_pass.'/'.$jetty_sirkar_photo; ?>" height="50px" width="50px">
							<p style="padding-top:5px;"><font size="1">Jetty Sircar<br/><?php // echo $jetty_sirkar_name; ?></font></p>
						</td>
						<?php
						}
						?>
					</tr>
				</table>
			</td>
		
		<!-- <tr>
			<td colspan="5" align="center" style="font-size:13px; font-weight: bold;"><u>CART TICKET</u></td>
		</tr> -->
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
	
	<table border="0" width="100%" style="padding-top:1px;margin-top:1px;">
		<tr>
			<td >Yard/Shed No.</td>
			<td colspan="2" style="border-bottom:1px solid black; "><?php  echo $yardNo; //echo $rtnCartTicket[0]['shed_yard'];?></td>
			<td align="center" >Release Order No. , CP:</td>
			<td colspan="2 "style="border-bottom:1px solid black;"><b><?php echo $cpNo;?></b></td>
			<td align="center">Of</td>
			<td style="border-bottom:1px solid black;"></td>
		</tr>
		<tr>
			<td >Ex. S/S. M/V</td>
			<td style="border-bottom:1px solid black;"><?php echo $rtnCartTicket[0]['Vessel_Name'];?></td>
			<td align="center">No</td>
			<td style="border-bottom:1px solid black;"></td>
			<td align="center">Consignee:</td>
			<td colspan="3" style="border-bottom:1px solid black;"><?php echo $rtnCartTicket[0]['Notify_name'];?></td>
		</tr>
		<tr>
			<td>B/E No.</td>
			<td style="border-bottom:1px solid black;" colspan="3"><b>
				<?php 
					$val = "";
					if($beNo != ""){
						$val.="C".$beNo." , ";
					}

					if($officeCode != ""){
						$val.="Office: ".$officeCode." , ";
					}

					if($beDate != ""){
						$val.= " Date: ".$beDate;
					}
						echo $val;

				?></b>
			</td>
			<td align="center">Truck No.</td>
			<th align="left" style="border-bottom:1px solid black;font-family: ind_bn_1_001"><font size='6'><b><?php echo $rtnCartTicket[0]['truck_id'];?></b></font></th>
			<td align="center">Gate No.</td>	
			<td align="center" style="border-bottom:1px solid black;"><?php echo $rtnCartTicket[0]['gate_no'];?></td>
		</tr>
		<tr>
			<td>Verify: </td>
			<td style="border-bottom:1px solid black;"><b><?php echo $verify_no; ?></b></td>
		</tr>
	</table>
			
	<table border="1" width="100%" align="center" style="padding-bottom:1px;margin-bottom:1px;">
		<tr>			
			<th style="font-size:11px;">Marks</th>
			<!-- <th style="font-size:11px;">BL</th> -->
			<th style="font-size:11px;">Description</th>
			<th style="font-size:11px;">Quantity</th>
			<th style="font-size:11px;">Unit</th>
			<!--th style="font-size:11px;">Tally</th-->
			<th style="font-size:11px;">Consecutive Carts Total</th>
		</tr>
		<?php if($cntHBL > 0) { ?>
		<tr align="center">			
			<td style="font-size:11px;text-align:center;"></td>
			<!-- <td style="font-size:11px;text-align:center;display:none;"></td> -->
			<td style="font-size:11px;text-align:center;display:none;"></td>
			<td rowspan="<?php echo $cntHBL+1; ?>" align="center" style="font-size:11px;"><font size="5"><?php echo $rtnCartTicket[0]['actual_delv_pack'];?></font></td>
			<td rowspan="<?php echo $cntHBL+1; ?>" align="center" style="font-size:11px;">
				<?php if($assignment_type=="OCD") echo $rtnCartTicket[0]['cont_no']."<br>"."<b>ON-CHASSIS</b>"; else echo $rtnCartTicket[0]['actual_delv_unit'];?>
			</td>
			<td rowspan="<?php echo $cntHBL+1; ?>" align="center" style="font-size:11px;"><?php echo $rtnCartTicket[0]['delv_pack'];?></td>
		</tr>
		<?php for($k=0;$k<count($resHBL);$k++) { ?>
		<tr align="center">			
			<td width="30%" style="font-size:11px;text-align:center;"><?php echo $rtnCartTicket[0]['Pack_Marks_Number'];?></td>
			<!-- <td width="30%" style="font-size:11px;text-align:center;"><?php echo $resHBL[$k]['BL_No']; ?></td> -->
			<td width="30%" style="font-size:11px;text-align:center;"><?php echo $resHBL[$k]['Description_of_Goods']; ?></td>
		</tr>
		<?php } ?>
		<!-- <tr>
			<td rowspan="2" style="border:0px;">&nbsp;</td>
			<td rowspan="2" style="border:0px;">&nbsp;</td>
			<td style="border:0px;">&nbsp;</td>
			<td style="border:0px;">&nbsp;</td>
			<td style="border:0px;">&nbsp;</td>
		</tr>
		<tr>
			<td style="border:0px;">&nbsp;</td>
			<td style="border:0px;">&nbsp;</td>
			<td style="border:0px;">&nbsp;</td>
		</tr> -->
		<?php  } else { ?>
		<tr align="center">
			<td rowspan="2" width="30%" style="font-size:10px;text-align:center;"><?php echo $rtnCartTicket[0]['Pack_Marks_Number'];?></td>
			<!-- <td rowspan="2" width="10%" style="font-size:10px;text-align:center;"><?php echo $rtnCartTicket[0]['BL_No'];?></td> -->
			<td rowspan="2" width="30%" style="font-size:10px;text-align:center"><?php echo $rtnCartTicket[0]['Description_of_Goods'];?></td>
			<td align="center" style="font-size:12px;"><b><?php echo $rtnCartTicket[0]['actual_delv_pack'];?></b></td>
			<td align="center" style="font-size:10px;">
				<?php if($assignment_type=="OCD") echo $rtnCartTicket[0]['cont_no']."<br>"."<b>ON-CHASSIS</b>"; else echo $rtnCartTicket[0]['actual_delv_unit'];?>
			</td>
			<td align="center" style="font-size:10px;"><?php echo $rtnCartTicket[0]['delv_pack'];?></td>
		</tr>
		<!-- <tr align="center">
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
		</tr> -->
		<!-- <tr align="center">
			<td rowspan="2">&nbsp;</td>
			<td rowspan="2">&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
		</tr> -->
		<!-- <tr align="center">
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
		</tr> -->
		<?php } ?>	
	</table>
	<!-- <p style="padding-left:20px;">Container: <b><?php echo $rtnCartTicket[0]['cont_no'];?></b></p> -->
		
		
	<table border="0" width="100%" style="padding-top:0px;margin-top:0px;">
		<!--<tr>
			<td>&nbsp;</td>
		</tr>-->
		<tr style="padding-top:0px;margin-top:0px;">
			<td colspan="8">Container: <b><?php echo $rtnCartTicket[0]['cont_no'];?></b></td>
		</tr>
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
			
			$cfLicNo = $rtnCartTicket[0]['cnf_lic_no'];
			$cfLicArr = explode("/",$cfLicNo);
			
			// Example : 301760986CF is 986/76
			if(strlen($cfLicArr[0])==2)
				$cfLoginId = "301".$cfLicArr[1]."00".$cfLicArr[0]."CF";
			else if(strlen($cfLicArr[0])==3)
				$cfLoginId = "301".$cfLicArr[1]."0".$cfLicArr[0]."CF";
			else if(strlen($cfLicArr[0])==4)
				$cfLoginId = "301".$cfLicArr[1].$cfLicArr[0]."CF";
			else
				$cfLoginId = "";
			
			$sql_cnf = "SELECT u_name FROM users WHERE login_id='$cfLoginId'";
			
			$rslt_cnfName = $this->bm->dataSelectDb1($sql_cnf);

			$cnfName = "";
			if(count($rslt_cnfName)>0){
				// $cnfName = $rslt_cnfName[0]['Organization_Name'];
				$cnfName = $rslt_cnfName[0]['u_name'];
			}
			// C&F Name - 2021-07-10
			
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
			<td style="border:0px solid;width:15%;">&nbsp;</td>
			<td style="border:0px solid;width:20%;">&nbsp;</td>
			<td style="border:0px solid;" colspan="3"></td>
			<td style="border:0px solid;width:12%"></td>
			<td style="border:0px solid;" colspan="2"></td>
		</tr>
		<tr>
			<td style="border:0px solid;width:14%;">Jetty Sircar & Lic No:</td>
			<td style="border:0px solid;width:20%;" ><b><?php echo  $rtnCartTicket[0]['agent_name'].'-'. $rtnCartTicket[0]['agent_code']; ?></b></td>
			<td style="border:0px solid;width:10%;">ASI Name:</td>
			<td style="border:0px solid;width:15%;"><b><font size="4"><?php echo $asiName;?></font></b></td>			
			<td style="border:0px solid;width:15%;" colspan="2">Delivery Clerk:</td>
			<td style="border:0px solid;width:15%;"  align="left"><b><font size="4"><?php echo $trafficName;?></font></b></td>
			<td style="border:0px solid;"></td>
		</tr>
		<tr>
			<td style="border:0px solid;width:14%;">Confirmation :</td>
			<td style="border:0px solid;width:15%;"><b><?php echo $rtnCartTicket[0]['cnf_chk_by'];?></b></td>			
			<td style="border:0px solid;width:10%;">Confirmation :</td>
			<td style="border:0px solid;width:15%;"><b><font size="5"><?php echo $rtnCartTicket[0]['yard_security_chk_by'];?></font></b></td>			
			<td style="border:0px solid;width:20%;" colspan="2">Confirmation :</td>
			<td style="border:0px solid;width:15%;"  align="left"><b><font size="5"><?php echo $rtnCartTicket[0]['traffic_chk_by'];?></font></b></td>
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

	</table>
	
	<?php
		// require_once 'phpqrcode/qrlib.php';
		// $destination_folder = $_SERVER['DOCUMENT_ROOT']."/pcs/assets/images/qrcode/";
				
		// $file = $trucVisitId.".png";
		// $file1 = $destination_folder.$file;
		// $path = IMG_PATH."qrcode/".$file;
		// $text =$trucVisitId;
		// QRcode::png($text, $file1, 'L', 10, 2);		
	?>
	<!-- <table border="0" width="60%" align="center">
		<tr>
			<td align="left">

				<img src="<?php //echo $path;?>" height="68" width="68">

			</td>
			<td align="center">							
				<?php								
					//$barcodeText = $trucVisitId;
				?>
				<barcode code="<?php //echo $barcodeText; ?>" type="C128A" size="0.5" height="2" />
				<br>
				<?php //echo sprintf("%010s", $text); ?>
				<?php //echo $trucVisitId; ?>						
			</td>			
		</tr>
	</table> -->
	
	<!-- <div style="position:absolute;bottom:25px;right:50px;width:20%;text-align:right">
		<?php  //echo "Print Time: ".date("Y-m-d h:i:s");?>
	</div> -->
	
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

			<?php
				require_once 'phpqrcode/qrlib.php';
				$destination_folder = $_SERVER['DOCUMENT_ROOT']."/pcs/assets/images/qrcode/";
						
				$file = $trucVisitId.".png";
				$file1 = $destination_folder.$file;
				$path = IMG_PATH."qrcode/".$file;
				$text =$trucVisitId;
				QRcode::png($text, $file1, 'L', 10, 2);		
			?>
			<div style="position:absolute;top:15px;right:50px;width:30%;text-align:right">
				<?php  //echo "Print Time: ".date("Y-m-d h:i:s");?>
			</div>
			<table width="100%" border="0" style="padding-top:0px;margin-top:0px;padding-bottom:5px;margin-bottom:5px;">
				<!-- <tr align="center">
					<td colspan="5" align="center" style="font-size:20px; font-weight: bold;"><?php //echo $rslt_CNFName[0]['name'];?></td>
				</tr> -->
				<!-- <tr align="center">
					<td colspan="5" align="center" style="font-size:16px; font-weight: bold;"> </td>
				</tr> -->
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
				<tr>
					<td colspan="5" style="padding-top:0px;margin-top:0px;">
						<table align="center" style="padding-top:0px;margin-top:0px;">
							<tr>
								<td width="25%" align="right">
									<img src="<?php echo $path;?>" height="68" width="68">
								</td>
								<td align="center" width="50%">
									<p style="font-size:16px; "> CHITTAGONG PORT AUTHORITY </p>
									<p style="margin-top:-3px;">www.cpatos.gov.bd</p>
									<p style="font-size:13px; font-weight: bold;"><u>CART TICKET</u></p>
									<p style="font-size:16px; font-weight: bold;">
										<?php 
											$cfName = "";
											for($z=0;$z<count($CNFresult);$z++){
												$cfName = $CNFresult[$z]['name'];
											}
											echo $cfName;
										?>
									</p>
								</td>
								<td width="25%" align="center">
									<?php								
										$barcodeText = $trucVisitId;
									?>
									<barcode code="<?php echo $barcodeText; ?>" type="C128A" size="0.5" height="2" />
									<br>
									<?php echo sprintf("%010s", $text); ?>
								</td>
							<tr/>
						</table>
					</td>
				</tr>
				<!-- <tr>
					<td colspan="5" align="center" style="font-size:13px; font-weight: bold;"><u>CART TICKET</u></td>
				</tr> -->
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
			
			<table border="0" width="100%" style="padding-top:1px;margin-top:1px;">
				<tr>
					<td >Yard/Shed No.</td>
					<td colspan="2" style="border-bottom:1px solid black; "><?php  echo $yardNo; //echo $rtnCartTicket[0]['shed_yard'];?></td>
					<td align="center" >Release Order No. , CP: </td>
					<td colspan="2 "style="border-bottom:1px solid black;"><b><?php echo $cpNo;?></b></td>
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
					<td style="border-bottom:1px solid black;" colspan="3"><b>
						<?php 
							$val = "";
							if($beNo != ""){
								$val.="C".$beNo." , ";
							}

							if($officeCode != ""){
								$val.="Office: ".$officeCode." , ";
							}

							if($beDate != ""){
								$val.= " Date: ".$beDate;
							}
								echo $val;

						?></b>
					</td>
					<td align="center">Truck No.</td>
					<th align="left" style="border-bottom:1px solid black;font-family: ind_bn_1_001"><font size='6'><b><?php echo $rtnCartTicket[0]['truck_id'];?></b></font></th>
					<td align="center">Gate No.</td>	
					<td align="center" style="border-bottom:1px solid black;"><?php echo $rtnCartTicket[0]['gate_no'];?></td>
				</tr>
				<tr>
					<td>Verify: </td>
					<td style="border-bottom:1px solid black;"><b><?php echo $verify_no; ?></b></td>
				</tr>
			</table>
					
			<table border="1" width="100%" align="center" style="padding-bottom:1px;margin-bottom:1px;">
				<tr>
					<th style="font-size:11px;">Marks</th>
					<th style="font-size:11px;">Description</th>
					<th style="font-size:11px;">Quantity</th>
					<th style="font-size:11px;">Unit</th>
					<!--th style="font-size:11px;">Tally</th-->
					<th style="font-size:11px;">Consecutive Carts Total</th>
				</tr>
				<tr align="center">
					<td rowspan="2" width="30%" style="font-size:10px;"><?php echo $rtnCartTicket[0]['Pack_Marks_Number'];?></td>
					<td rowspan="2" width="40%" style="font-size:10px;"><?php echo $rtnCartTicket[0]['Description_of_Goods'];?></td>
					<td align="center" style="font-size:10px;"><font size="5"><b><?php echo $rsltExtraTrucks[$i]['pack_num'];?></b></font></td>
					<td align="center" style="font-size:10px;">
						<?php if($assignment_type=="OCD") echo $rtnCartTicket[0]['cont_no']."<br>"."<b>ON-CHASSIS</b>"; else echo $rsltExtraTrucks[$i]['actual_delv_unit'];?>
					</td>
					<td align="center" style="font-size:10px;"><?php echo $rtnCartTicket[0]['delv_pack'];?></td>
				</tr>
				<!-- <tr align="center">
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
				</tr> -->
			</table>
			<!-- <p style="padding-left:20px;">Container: <b><?php echo $rsltExtraTrucks[$i]['cont_no'];?></b></p> -->
				
				
			<table border="0" width="100%" style="padding-top:0px;margin-top:0px;"> 
				<tr>
					<td colspan="8">Container: <b><?php echo $rsltExtraTrucks[$i]['cont_no'];?></b></td>
				</tr>
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
				<tr>
					<td style="border:0px solid;width:15%;">&nbsp;</td>
					<td style="border:0px solid;width:20%;">&nbsp;</td>
					<td style="border:0px solid;" colspan="3"></td>
					<td style="border:0px solid;width:12%"></td>
					<td style="border:0px solid;" colspan="2"></td>
				</tr>
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
				
				<!-- <tr>
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
					<td style="border:0px solid;width:14%;" align="center" ><b>---------------------------------<br/>Gate Sergeant </b></td>
				</tr> -->
				
				<!-- <tr>
					<td style="border:0px solid;" align="right"><Date:></td>
					<td style="border:0px solid;"></td>
					<td style="border:0px solid;"></td>
					<td style="border:0px solid;"></td>
					<td style="border:0px solid;" colspan="2"><Consignee's Signature: ></td>
					<td style="border:0px solid;"></td>
					<td style="border:0px solid;"></td>
				</tr> -->
				
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
				<!-- <tr>
					<td colspan="8" style="border-bottom:1px solid black; width:100%">&nbsp;</td>
				</tr> -->
				<!-- <tr>
					<td colspan="8" style="font-size:11px;"><N.B.: Loss of Cart Ticket must immediately be reported to the Shed Master of Shed Foreman. Unused Cart Ticket must be returned to the delivery Foreman on the same day they were issued></td>
				</tr> -->
				<!--tr>
					<td colspan="8">&nbsp;</td>
				</tr-->
			</table>
			<?php
				// require_once 'phpqrcode/qrlib.php';
				// $destination_folder = $_SERVER['DOCUMENT_ROOT']."/pcs/assets/images/qrcode/";
						
				// $file = $trucVisitId.".png";
				// $file1 = $destination_folder.$file;
				// $path = IMG_PATH."qrcode/".$file;
				// $text =$trucVisitId;
				// QRcode::png($text, $file1, 'L', 10, 2);		
			?>
			<!-- <table border="0" width="60%" align="center">
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
			</table> -->
			
			<!-- <div style="position:absolute;bottom:25px;right:50px;width:20%;text-align:right">
				<?php  echo "Print Time: ".date("Y-m-d h:i:s");?>
			</div> -->
		<?php } ?>
		<?php } ?>
	<!--For showing extra trucks-------------------- ends-->
	
		<?php //$mpdf->AddPage();?>
	</div>
	</div>

<br/>
<br/>
<br/>
<br/>
<hr/>

    <?php
        require_once 'phpqrcode/qrlib.php';
        $destination_folder = $_SERVER['DOCUMENT_ROOT']."/pcs/assets/images/qrcode/";		
        $file = $visitId.".png";
        $file1 = $destination_folder.$file;
        $path = IMG_PATH."qrcode/".$file;
        $text =$visitId;
        QRcode::png($text, $file1, 'L', 10, 2);		
    ?>
    <table align="center" width="80%" style="font-size:12px">				
        <tr align="center">
            <td align="center">
                <img src="<?php echo $path;?>" height="68" width="68">
            </td>

            <td align="center">
                <img align="middle"  height="70px" width="210px" src="<?php echo IMG_PATH?>cpanew.jpg">
                <p style="margin-top:-3px;">www.cpatos.gov.bd</p>
            </td>

            <td align="center">
                <?php			
                    $text =$visitId;						
                    $barcodeText = $text;
                ?>
                    <barcode code="<?php echo $barcodeText; ?>" type="C128A" size="0.5" height="2" />
                    <br>
                <?php echo sprintf("%010s", $text); ?>
            </td>
        </tr>
        <tr align="center">
            <th colspan="3" align="center"><b><font size=5><b>Invoice / Challan</b></font></b></td>
        </tr>
        <tr align="center">
            <th colspan="3" align="center"><b><font size=3>Visit ID : <?php echo $visitId;?></font></b></th>
        </tr>
		<tr align="center">
            <th colspan="3" align="center"><b><font size=5><?php echo @$CNFresult[0]['name'];?></font></b></th>
        </tr>
    </table>
    <!--table align="center" width="80%" style="font-size:12px">
				<tr style="border-bottom:1px solid black">
					<td><b><font size=3>Visit ID : <?php echo $visitId;?></font></b></td>
				</tr>			
			</table-->
    <table align="center" width="80%" border="1" style="font-size:12px;  border-collapse: collapse;">
        <tr>
            <th rowspan="2"> C&F Detail </th>
            <th> Address</th>
            <td><?php echo @$CNFresult[0]['address_line1'];?></td>
        </tr>
        <tr>
            <th> Phone</th>
            <td><?php echo @$CNFresult[0]['sms_number'];?></td>
        </tr>
        <tr>
            <th rowspan="2"> Importer Detail</th>
            <th> Name </th>
            <td><?php echo $resQuery[0]['Notify_name'];?></td>
        </tr>
        <tr>
            <th> Address</th>
            <td><?php 
				$notify_address = $resQuery[0]['Notify_address'];
				echo preg_replace('/[^A-Za-z0-9,;()@&\-]/', ' ', $notify_address);
			?></td>
        </tr>
    </table>
    <table align="center" width=80% border="1" style="font-size:10px; border-collapse: collapse;">
        <thead style="">
            <tr>
                <th align="center">TRUCK NO</th>
                <th align="center">DESCRIPTION OF GOODS</th>
                <th align="center">QUANTITY</th>
				<th align="center">UNIT</th>
                <!-- <th align="center">CONTAINER</th> -->
                <th align="center">REMARKS</th>
            </tr>
        </thead>
        <tbody>
            <tr>
				<td align="center"><p style="font-family:ind_bn_1_001; font-size:13px" ><font size="6"><b><?php echo $resQuery[0]['truck_id'];?></b></font></p></td>
                <td align="left"> 
					  	<?php 
                            include("mydbPConnection.php");
						  	$description = $resQuery[0]['Description_of_Goods'];
							$cont = $resQuery[0]['cont_no'];
							$rot = $resQuery[0]['import_rotation'];

							$query = "SELECT igm_supplimentary_detail.Description_of_Goods
							FROM igm_supplimentary_detail
							INNER JOIN igm_sup_detail_container ON igm_sup_detail_container.igm_sup_detail_id=igm_supplimentary_detail.id
							WHERE igm_supplimentary_detail.Import_Rotation_No='$rot' AND igm_sup_detail_container.cont_number='$cont'";

							$rslt = $this->bm->dataSelectDb1($query);
							$descOfGoods = "";
							for($i=0;$i<count($rslt);$i++){
								$descOfGoods = $rslt[$i]['Description_of_Goods'];
							}
                            
                            if($i>0)
                                echo substr($descOfGoods,0,100);
                            else
                                echo substr($description,0,100);

							$extraContPackQuery = "SELECT SUM(pack_num) AS pack_num FROM do_truck_details_additional_cont WHERE truck_visit_id = '$visitId'";
							$extraContPackRslt = $this->bm->dataSelectDb1($extraContPackQuery);
							$extraContPack = 0;
							for($i=0;$i<count($extraContPackRslt);$i++){
								$extraContPack = $extraContPackRslt[$i]['pack_num'];
							}
						?> 

					  </td>
                <td align="center"><b> <font size="5"><?php 
                    // $sts = $resQuery[0]['add_truck_st'];
                    $qty = @$resQuery[0]['actual_delv_pack'] + $extraContPack;
                    // if($sts == 1){
                    //     include("mydbPConnection.php");
                    //     $packNumQuery = "SELECT SUM(pack_num) AS pack_num FROM do_truck_details_additional_cont WHERE truck_visit_id = '$visitId'";
					// 	$packNumResult = mysqli_query($con_cchaportdb,$packNumQuery);
                    //     $packNumRow = mysqli_fetch_object($packNumResult);
                    //     if(count($packNumRow)>0){
                    //         $addQty = $packNumRow->pack_num;
                    //     }
                    //     $qty+=$addQty;
                    // }
                    echo $qty;
                    
                ?> </font></b></td>
				<td align="center"><?php echo $resQuery[0]['actual_delv_unit']; ?></td>
                <!-- <td align="center"><?php echo $cont; ?></td> -->
                <td align="center"></td>
            </tr>
        </tbody>
    </table>

    <table align="center" width="80%" style="font-size:14px; margin-top:20px;">
		<tr>
			<td colspan="6">&nbsp;</td>
		</tr>
		
        <tr>
            <td>---------------------------------------</td>
			<td></td>
			<td></td>
			<td></td>
			<td></td>
			<td>---------------------------------------</td>
        </tr>
        <tr>
            <td>Signature(Jetty Sircar)</td>
			<td></td>
			<td></td>
			<td></td>
			<td></td>
			<td>Signature(Gate Sergeant)</td>
        </tr>
    </table>

    <div style="position:absolute;top:25px;right:100px;width:30%;text-align:right">
        <?php // echo "Print Time: ".date("Y-m-d h:i:s");?>
    </div>

</body>
</html>

