<?php

include("mydbPConnection.php");
//count($rtnTruckNumber);
//for($i=0;$i<count($rtnTruckNumber);$i++)
	$igmTypeQuery = "SELECT igm_type FROM do_truck_details_entry
	INNER JOIN lcl_dlv_assignment ON do_truck_details_entry.verify_other_data_id = lcl_dlv_assignment.id
	WHERE do_truck_details_entry.id = '$visitId'";
	$igmTypeData = $this->bm->dataSelectDB1($igmTypeQuery);
	$igmType = "";
	for($a=0;$a<count($igmTypeData);$a++){
		$igmType = $igmTypeData[$a]['igm_type'];
	}
	
	$cartDtl_str="";
	
	if($igmType == "sup" or $igmType == "sup_dtl")
	{
	
		
		$cartDtl_str = "SELECT do_truck_details_entry.id AS trucVisitId,verify_other_data_id,import_rotation,cont_no,cont_size,delv_pack,actual_delv_pack, igm_pack_unit.Pack_Unit AS actual_delv_unit, agent_name,agent_code, traffic_chk_st,traffic_chk_by, traffic_chk_time,yard_security_chk_st,yard_security_chk_by,yard_security_chk_time,cnf_chk_st,cnf_chk_by,cnf_chk_time, truck_id,gate_no,driver_name,driver_gate_pass,assistant_name,assistant_gate_pass, igm_supplimentary_detail.BL_No,igm_masters.Vessel_Name, oracle_nts_data.be_no, oracle_nts_data.be_dt, oracle_nts_data.verify_no,oracle_nts_data.cp_no,oracle_nts_data.cp_date,oracle_nts_data.unit_number,oracle_nts_data.office_code,cnf_lic_no, SUBSTR(igm_supplimentary_detail.Description_of_Goods,1,100) AS Description_of_Goods,SUBSTR(igm_supplimentary_detail.Pack_Marks_Number,1,100) AS Pack_Marks_Number,Consignee_name, agent_name AS jetty_sirkar_name,card_number AS jetty_sirkar_gate_pass,
		(SELECT agent_photo FROM vcms_vehicle_agent WHERE vcms_vehicle_agent.card_number=jetty_sirkar_gate_pass AND vcms_vehicle_agent.agent_photo!='') AS jetty_sirkar_photo
		FROM do_truck_details_entry 
		LEFT JOIN lcl_dlv_assignment ON lcl_dlv_assignment.id = do_truck_details_entry.verify_other_data_id
		LEFT JOIN vcms_vehicle_agent ON lcl_dlv_assignment.jetty_sirkar_id = vcms_vehicle_agent.id
		INNER JOIN igm_supplimentary_detail ON lcl_dlv_assignment.igm_sup_dtl_id =igm_supplimentary_detail.id 
		INNER JOIN igm_sup_detail_container ON igm_sup_detail_container.igm_sup_detail_id=igm_supplimentary_detail.id 
		LEFT JOIN igm_masters ON igm_masters.id=igm_supplimentary_detail.igm_master_id
		LEFT JOIN igm_pack_unit ON igm_pack_unit.id=do_truck_details_entry.actual_delv_unit
		LEFT JOIN oracle_nts_data ON oracle_nts_data.igm_sup_detail_id=igm_supplimentary_detail.id
		WHERE do_truck_details_entry.id='$visitId'";
	}
	else
	{
		
		
		$cartDtl_str = "SELECT do_truck_details_entry.id AS trucVisitId,verify_other_data_id,import_rotation,cont_no,cont_size,delv_pack,actual_delv_pack, igm_pack_unit.Pack_Unit AS actual_delv_unit, agent_name,agent_code, traffic_chk_st,traffic_chk_by, traffic_chk_time,yard_security_chk_st,yard_security_chk_by,yard_security_chk_time,cnf_chk_st,cnf_chk_by,cnf_chk_time, truck_id,gate_no,driver_name,driver_gate_pass,assistant_name,assistant_gate_pass, igm_details.BL_No,igm_masters.Vessel_Name, oracle_nts_data.be_no, oracle_nts_data.be_dt, oracle_nts_data.verify_no,oracle_nts_data.cp_no,oracle_nts_data.cp_date,oracle_nts_data.unit_number,oracle_nts_data.office_code,cnf_lic_no, SUBSTR(igm_details.Description_of_Goods,1,100) AS Description_of_Goods,SUBSTR(igm_details.Pack_Marks_Number,1,100) AS Pack_Marks_Number,Consignee_name, agent_name AS jetty_sirkar_name,card_number AS jetty_sirkar_gate_pass,
		(SELECT agent_photo FROM vcms_vehicle_agent WHERE vcms_vehicle_agent.card_number=jetty_sirkar_gate_pass AND vcms_vehicle_agent.agent_photo!='') AS jetty_sirkar_photo		
		FROM do_truck_details_entry 
		LEFT JOIN lcl_dlv_assignment ON lcl_dlv_assignment.id = do_truck_details_entry.verify_other_data_id
		LEFT JOIN vcms_vehicle_agent ON lcl_dlv_assignment.jetty_sirkar_id = vcms_vehicle_agent.id
		INNER JOIN igm_details ON igm_details.id=lcl_dlv_assignment.igm_sup_dtl_id 
		INNER JOIN igm_detail_container ON igm_detail_container.igm_detail_id=igm_details.id 
		LEFT JOIN igm_masters ON igm_masters.id=igm_details.IGM_id 
		LEFT JOIN igm_pack_unit ON igm_pack_unit.id=do_truck_details_entry.actual_delv_unit
		LEFT JOIN oracle_nts_data ON oracle_nts_data.igm_detail_id=igm_details.id
		WHERE do_truck_details_entry.id='$visitId'";
	}
	
	// echo $cartDtl_str;return;
	
	
	
	$rtnCartTicket = $this->bm->dataSelectDb1($cartDtl_str);
	//$verify_info_fcl_id = "";
	
	// echo $cartDtl_str;return;
	$cont_no=$rtnCartTicket[0]['cont_no'];
	$cont_size=$rtnCartTicket[0]['cont_size'];
	$rot_no=$rtnCartTicket[0]['import_rotation'];
	$blno = $rtnCartTicket[0]['BL_No'];

	$oracleNtsQuery = "SELECT * FROM oracle_nts_data WHERE bl_no='$blno' AND imp_rot_no='$rot_no' ORDER BY cp_date DESC  LIMIT 1";
	$oracleNts=mysqli_query($con_cchaportdb,$oracleNtsQuery);
	$rowcount=mysqli_num_rows($oracleNts);
	//echo $rowcount;
	//print_r($allHBL);
	if($rowcount<1)
	{		
		$oracleNtsQuery = "SELECT cp_no,date(cp_date) AS cp_date,verify_no,be_no,be_dt,office_code FROM oracle_nts_data WHERE bl_no IN ($allHBL) AND imp_rot_no='$rot_no' ORDER BY cp_date DESC  LIMIT 1";
		$oracleNts=mysqli_query($con_cchaportdb,$oracleNtsQuery);
	}
	
	$cpNo = "";
	$cpDate = "";
	$verify_no = "";
	$beNo = "";
	$beDate = "";
	$officeCode = "";
	while($oracleRow=mysqli_fetch_object($oracleNts)){
		$cpNo = $oracleRow->cp_no;
		$cpDate = $oracleRow->cp_date;
		$verify_no = $oracleRow->verify_no;
		$beNo = $oracleRow->be_no;
		$beDate = $oracleRow->be_dt;
		$officeCode = $oracleRow->office_code;
	}
	
	$jetty_sirkar_name=$rtnCartTicket[0]['jetty_sirkar_name'];
	$jetty_sirkar_gate_pass=$rtnCartTicket[0]['jetty_sirkar_gate_pass'];
	$jetty_sirkar_photo=$rtnCartTicket[0]['jetty_sirkar_photo'];

	$manif = str_replace("/"," ",$rot_no);
					
	$sql_be = "SELECT reg_no,reg_date,place_dec FROM sad_info 
	INNER JOIN sad_item ON sad_item.sad_id = sad_info.id
	WHERE manif_num like '%$manif%' AND sum_declare = '$blno' LIMIT 1";

	$rslt_be=mysqli_query($con_cchaportdb,$sql_be);
	$be_no = "";
	$be_date = "";
	$exitNo = "";
	while($row_be=mysqli_fetch_object($rslt_be)){
		$be_no = $row_be->reg_no;
		$be_date = $row_be->reg_date;
		$exitNo = $row_be->place_dec;
	}
	 

	$YardQuery = "SELECT shed_yard FROM shed_tally_info WHERE cont_number='$cont_no'  AND import_rotation='$rot_no' ORDER BY id DESC LIMIT 1 ";

	$rsltYard = mysqli_query($con_cchaportdb,$YardQuery);

	$yardNo="";
	while($yardResult=mysqli_fetch_object($rsltYard)){
		$yardNo = $yardResult->shed_yard;
	}

	$challanBl = "";
				

	
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
			//$destination_folder = $_SERVER['DOCUMENT_ROOT']."/pcs/assets/images/qrcode/";
			$destination_folder = $_SERVER['DOCUMENT_ROOT']."/assets/images/qrcode/";
					
			$file = $trucVisitId.".png";
			$file1 = $destination_folder.$file;
			//$path = IMG_PATH."qrcode/".$file;
			$text =$trucVisitId;
			QRcode::png($text, $file1, 'L', 10, 2);		
	?>
	<div style="position:absolute;top:45px;right:45px;width:30%;text-align:right">
		<?php  echo "Print Time: ".date("Y-m-d h:i:s");?>
	</div>
	<table width="100%" border="0" style="padding-top:0px;margin-top:0px;padding-bottom:5px;margin-bottom:5px;">

		<tr>
			<td colspan="5" style="padding-top:0px;margin-top:0px;">
				<table align="center" style="padding-top:0px;margin-top:0px;">
					<tr>
						<td width="25%" align="right">
							<img src="<?php echo $file1;?>" height="68" width="68">
						</td>
						<td align="center" width="50%">
							<img align="middle"  height="60px" width="100px" src="<?php echo IMG_PATH?>cpa_logo.png">
							<p style="font-size:16px; "> CHITTAGONG PORT AUTHORITY </p>
							<p style="margin-top:-3px;">www.cpatos.gov.bd</p>
							<p style="font-size:14px; font-weight: bold;"><u>CART TICKET</u></p>
							<p style="font-size:16px; font-weight: bold;">
							<?php 
								$cfName = "";
								for($z=0;$z<count($CNFresult);$z++){
									$cfName = $CNFresult[$z]['NAME'];
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
							<img src="<?php echo 'http://cpatos.gov.bd/biometricPhoto/'.$jetty_sirkar_gate_pass.'/'.$jetty_sirkar_photo; ?>" height="50px" width="50px">
							<p style="padding-top:5px;"><font size="1">Jetty Sircar<br/><?php // echo $jetty_sirkar_name; ?></font></p>
						</td>
						<?php
						}
						?>
					</tr>
				</table>
			</td>
		

		<tr>
			<td width="70px" align="right">Visit Id.&nbsp;&nbsp;&nbsp;</td>
			<td style="border-bottom:1px solid black"><?php echo $rtnCartTicket[0]['trucVisitId'];?></td>
		</tr>
		<tr>
			<td width="70px" align="right">Rot No.&nbsp;&nbsp;&nbsp;</td>
			<td style="border-bottom:1px solid black"><?php $rotation=$rtnCartTicket[0]['import_rotation']; echo $rtnCartTicket[0]['import_rotation'];  ?></td>
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
							echo $challanBl = $resHBL[$j]['BL_No'];
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
						echo $challanBl = $rtnCartTicket[0]['BL_No'];
					}	

						$maskstr="SELECT SUBSTR(igm_details.Pack_Marks_Number,1,100) AS Pack_Marks_Number 
						FROM igm_details 
						WHERE igm_details.Import_Rotation_No='$rotation' AND igm_details.BL_No='$challanBl'
						UNION 
						SELECT SUBSTR(igm_supplimentary_detail.Pack_Marks_Number,1,100) AS Pack_Marks_Number 
						FROM igm_supplimentary_detail 
						WHERE igm_supplimentary_detail.Import_Rotation_No='$rotation' AND igm_supplimentary_detail.BL_No='$challanBl'";
						
							$rsltMarks = mysqli_query($con_cchaportdb,$maskstr);

							$Pack_Marks_Number ="";
							while($rsltMarksrslt=mysqli_fetch_object($rsltMarks)){
								$Pack_Marks_Number = $rsltMarksrslt->Pack_Marks_Number;
							}
				?>
			</td>
			<td>&nbsp;</td>
			<td width="50" align="right">Job No.</td>
			<td style="border-bottom:1px solid black"></td>
		</tr>

	</table>
	
	<table border="0" width="100%" style="padding-top:1px;margin-top:1px;">
		<tr>
			<td >Yard/Shed No.</td>
			<td colspan="2" style="border-bottom:1px solid black; "><b><?php  echo $yardNo; //echo $rtnCartTicket[0]['shed_yard'];?>  </b></td>
			<td align="center" >Release Order No. , CP:</td>
			<td colspan="2 "style="border-bottom:1px solid black;"><b><?php echo $cpNo." of ".substr($cpDate,0,10);?></b></td>
			<td align="center">Exit No: </td>
			<td style="border-bottom:1px solid black;"><?php echo $exitNo; ?></td>
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
				<?php echo $rtnCartTicket[0]['actual_delv_unit'];?>
			</td>
			<td rowspan="<?php echo $cntHBL+1; ?>" align="center" style="font-size:11px;"><?php echo $rtnCartTicket[0]['delv_pack'];?></td>
		</tr>
		<?php for($k=0;$k<count($resHBL);$k++) { ?>
		<tr align="center">			
			<td width="30%" style="font-size:11px;text-align:center;"><?php echo preg_replace('/[^A-Za-z0-9,;()@&\-]/', ' ', $Pack_Marks_Number);?></td>
			<!-- <td width="30%" style="font-size:11px;text-align:center;"><?php echo $resHBL[$k]['BL_No']; ?></td> -->
			<td width="30%" style="font-size:11px;text-align:center;"><?php echo preg_replace('/[^A-Za-z0-9,;()@&\-]/', ' ', $resHBL[$k]['Description_of_Goods']); ?></td>
		</tr>
		<?php } ?>

		<?php  } else { ?>
		<tr align="center">
			<td rowspan="2" width="30%" style="font-size:10px;text-align:center;"><?php echo preg_replace('/[^A-Za-z0-9,;()@&\-]/', ' ', $rtnCartTicket[0]['Pack_Marks_Number']);?></td>
			<!-- <td rowspan="2" width="10%" style="font-size:10px;text-align:center;"><?php echo $rtnCartTicket[0]['BL_No'];?></td> -->
			<td rowspan="2" width="30%" style="font-size:10px;text-align:center"><?php echo preg_replace('/[^A-Za-z0-9,;()@&\-]/', ' ', $rtnCartTicket[0]['Description_of_Goods']);?></td>
			<td align="center" style="font-size:12px;"><b><?php echo $rtnCartTicket[0]['actual_delv_pack'];?></b></td>
			<td align="center" style="font-size:10px;">
				<?php  echo $rtnCartTicket[0]['actual_delv_unit'];?>
			</td>
			<td align="center" style="font-size:10px;"><?php echo $rtnCartTicket[0]['delv_pack'];?></td>
		</tr>
	
		<?php } ?>	
	</table>
		
	
	<table border="0" width="100%" style="padding-top:0px;margin-top:0px;">
		<!--<tr>
			<td>&nbsp;</td>
		</tr>-->
		<tr style="padding-top:0px;margin-top:0px;">
			<td colspan="8">Container: <b><?php echo $rtnCartTicket[0]['cont_no']." X ".$cont_size."'";?></b></td>
		</tr>
		<tr>
			<td colspan="2" style="font-size:11px;">Total Packages (in words)</td>
			<td style="border-bottom:1px solid black;" colspan="6"><?php echo convertNumberToWord($rtnCartTicket[0]['actual_delv_pack'])." ".$rtnCartTicket[0]['actual_delv_unit']." Only.";?></td>
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
	
	<?php } ?>

	<!--For showing extra trucks-------------------- starts-->

	<?php if(count($rsltExtraTrucks) > 0) { ?>
		
		<?php for($i=0;$i<count($rsltExtraTrucks);$i++){ ?>
			<?php if($rtnCartTicket[0]['actual_delv_pack']>0) {?>				
				<pagebreak />
			<?php } else if(($rtnCartTicket[0]['actual_delv_pack']==0) and ($i<count($rsltExtraTrucks)) and ($i!=0)) {?>
				<pagebreak />
			<?php } ?>

			<?php
				require_once 'phpqrcode/qrlib.php';
				//$destination_folder = $_SERVER['DOCUMENT_ROOT']."/pcs/assets/images/qrcode/";
				$destination_folder = $_SERVER['DOCUMENT_ROOT']."/assets/images/qrcode/";
						
				$file = $trucVisitId.".png";
				$file1 = $destination_folder.$file;
				//$path = IMG_PATH."qrcode/".$file;
				$text =$trucVisitId;
				QRcode::png($text, $file1, 'L', 10, 2);		
			?>
			<div style="position:absolute;top:15px;right:50px;width:30%;text-align:right">
				<?php  //echo "Print Time: ".date("Y-m-d h:i:s");?>
			</div>
			<table width="100%" border="0" style="padding-top:0px;margin-top:0px;padding-bottom:5px;margin-bottom:5px;">

				<tr>
					<td colspan="5" style="padding-top:0px;margin-top:0px;">
						<table align="center" style="padding-top:0px;margin-top:0px;">
							<tr>
								<td width="25%" align="right">
									<img src="<?php echo $file1;?>" height="68" width="68">
								</td>
								<td align="center" width="50%">
									<img align="middle"  height="60px" width="120px" src="<?php echo IMG_PATH?>cpa_logo.png">
									<p style="font-size:16px; "> CHITTAGONG PORT AUTHORITY </p>
									<p style="margin-top:-3px;">www.cpatos.gov.bd</p>
									<p style="font-size:13px; font-weight: bold;"><u>CART TICKET</u></p>
									<p style="font-size:16px; font-weight: bold;">
										<?php 
											$cfName = "";
											for($z=0;$z<count($CNFresult);$z++){
												$cfName = $CNFresult[$z]['NAME'];
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
					<td style="border-bottom:1px solid black"><?php echo $challanBl = $rtnCartTicket[0]['BL_No'];?></td>
					<td>&nbsp;</td>
					<td width="50" align="right">Job No.</td>
					<td style="border-bottom:1px solid black"></td>
				</tr>

			</table>
			
			<table border="0" width="100%" style="padding-top:1px;margin-top:1px;">
				<tr>
					<td >Yard/Shed No.</td>
					<td colspan="2" style="border-bottom:1px solid black; "><?php  echo $yardNo; //echo $rtnCartTicket[0]['shed_yard'];?></td>
					<td align="center" >Release Order No. , CP: </td>
					<td colspan="2 "style="border-bottom:1px solid black;"><b><?php echo $cpNo." of ".substr($cpDate,0,10);?></b></td>
					<td align="center">Of</td>
					<td style="border-bottom:1px solid black;"></td>
				</tr>
				<tr>
					<td >Ex. S/S. M/V</td>
					<td style="border-bottom:1px solid black;"><?php echo $rtnCartTicket[0]['Vessel_Name'];?></td>
					<td align="center">No</td>
					<td style="border-bottom:1px solid black;"></td>
					<td align="center">Consignee:</td>
					<td colspan="3" style="border-bottom:1px solid black;"><?php echo preg_replace('/[^A-Za-z0-9,;()@&\-]/', ' ', $rtnCartTicket[0]['Consignee_name']);?></td>
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
					<td rowspan="2" width="30%" style="font-size:10px;"><?php echo preg_replace('/[^A-Za-z0-9,;()@&\-]/', ' ', $rtnCartTicket[0]['Pack_Marks_Number']);?></td>
					<td rowspan="2" width="40%" style="font-size:10px;"><?php echo preg_replace('/[^A-Za-z0-9,;()@&\-]/', ' ', $rtnCartTicket[0]['Description_of_Goods']);?></td>
					<td align="center" style="font-size:10px;"><font size="5"><b><?php echo $rsltExtraTrucks[$i]['pack_num'];?></b></font></td>
					<td align="center" style="font-size:10px;">
						<?php  echo $rsltExtraTrucks[$i]['actual_delv_unit'];?>
					</td>
					<td align="center" style="font-size:10px;"><?php echo $rtnCartTicket[0]['delv_pack'];?></td>
				</tr>

			</table>
				
			<table border="0" width="100%" style="padding-top:0px;margin-top:0px;"> 
				<tr>
					<td colspan="8">Container: <b><?php echo $rsltExtraTrucks[$i]['cont_no']." X ".$cont_size."'";?></b></td>
				</tr>
				<tr>
					<td colspan="2" style="font-size:11px;">Total Packages (in words)</td>
					<td style="border-bottom:1px solid black;" colspan="6"><?php echo convertNumberToWord($rsltExtraTrucks[$i]['pack_num'])." ".$rsltExtraTrucks[$i]['actual_delv_unit']." Only.";?></td>
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
			</table>
			
		<?php } ?>
		<?php } ?>
	<!--For showing extra trucks-------------------- ends-->
	
		<?php //$mpdf->AddPage();?>
	</div>
	</div>
	<!-- <pagebreak /> -->

					<!---------------------------        Part BL starts Here        -------------------------------->

	<?php
		if(count($additionalBlData)>0){
			$part_bl_no = "";
			for($a=0;$a<count($additionalBlData);$a++){
	?>
	<pagebreak />
	<?php
				$part_bl_no = $additionalBlData[$a]['bl_no'];

				$ntsQuery = "SELECT * FROM oracle_nts_data WHERE bl_no = '$part_bl_no'";
				$ntsData = $this->bm->dataSelectDB1($ntsQuery);

				$part_rot_no = "";
				$part_verify_no = "";
				$part_office_code = "";
				$part_be_no = "";
				$part_be_dt = "";
				$part_cp_no = "";
				$part_cp_date = "";

				for($b=0;$b<count($ntsData);$b++)
				{
					$part_rot_no = $ntsData[$b]['imp_rot_no'];
					$part_verify_no = $ntsData[$b]['verify_no'];
					$part_office_code = $ntsData[$b]['office_code'];
					$part_be_no = $ntsData[$b]['be_no'];
					$part_be_dt = $ntsData[$b]['be_dt'];
					$part_cp_no = $ntsData[$b]['cp_no'];
					$part_cp_date = $ntsData[$b]['cp_date'];
				}

				$part_manif = str_replace("/"," ",$part_rot_no);
				$sql_exitNo = "SELECT place_dec FROM sad_info 
				INNER JOIN sad_item ON sad_item.sad_id = sad_info.id
				WHERE manif_num like '%$part_manif%' AND sum_declare = '$part_bl_no' LIMIT 1";

				$part_rslt_be=mysqli_query($con_cchaportdb,$sql_exitNo);

				$exitNo = "";
				while($row_be=mysqli_fetch_object($part_rslt_be)){
					$exitNo = $row_be->place_dec;
				}


				$partBLQuery="SELECT igm_masters.Vessel_Name,SUBSTR(igm_supplimentary_detail.Pack_Marks_Number,1,100) as pack_marks,igm_supplimentary_detail.Consignee_name,igm_supplimentary_detail.Import_Rotation_No,igm_supplimentary_detail.master_BL_No,
				SUBSTR(igm_supplimentary_detail.Description_of_Goods,1,100) AS Description_of_Goods,
				igm_supplimentary_detail.BL_No,igm_sup_detail_container.cont_number,cont_size
				FROM igm_sup_detail_container
				INNER JOIN igm_supplimentary_detail ON igm_sup_detail_container.igm_sup_detail_id=igm_supplimentary_detail.id
				LEFT JOIN igm_masters ON igm_masters.id=igm_supplimentary_detail.igm_master_id
				WHERE igm_supplimentary_detail.BL_No='$part_bl_no' AND igm_supplimentary_detail.Import_Rotation_No='$part_rot_no'
				
				UNION
				
				SELECT igm_masters.Vessel_Name,SUBSTR(igm_details.Pack_Marks_Number,1,100) as pack_marks,igm_details.Consignee_name,igm_details.Import_Rotation_No,igm_details.BL_No,
				SUBSTR(igm_details.Description_of_Goods,1,100) AS Description_of_Goods,
				igm_details.BL_No,igm_detail_container.cont_number,cont_size
				FROM igm_detail_container
				INNER JOIN igm_details ON igm_detail_container.igm_detail_id=igm_details.id
				LEFT JOIN igm_masters ON igm_masters.id=igm_details.IGM_id
				WHERE igm_details.BL_No='$part_bl_no' AND igm_details.Import_Rotation_No='$part_rot_no'";
				$partBLData = $this->bm->dataSelectDB1($partBLQuery);

				$descOfGoods = "";
				$part_cont = "";
				$part_marks = ""; 
				$part_Consignee_name = "";
				$part_vsl = "";
				$part_cont_size = "";
				for($data = 0; $data<count($partBLData);$data++){
					$descOfGoods = preg_replace('/[^A-Za-z0-9,;()@&\-]/', ' ', $partBLData[$data]['Description_of_Goods']);
					$part_cont = $partBLData[$data]['cont_number'];
					$part_marks = preg_replace('/[^A-Za-z0-9,;()@&\-]/', ' ', $partBLData[$data]['pack_marks']);
					$part_Consignee_name = preg_replace('/[^A-Za-z0-9,;()@&\-]/', ' ', $partBLData[$data]['Consignee_name']);
					$part_vsl = $partBLData[$data]['Vessel_Name'];
					$part_cont_size = $partBLData[$data]['cont_size'];
				}

				$part_qtyQuery = "SELECT igm_pack_unit.Pack_Unit AS pack_unit, pack_num FROM do_truck_details_additional_bl_lcl 
				INNER JOIN igm_pack_unit ON igm_pack_unit.id = do_truck_details_additional_bl_lcl.pack_unit
				WHERE bl_no = '$part_bl_no' AND truck_visit_id = '$trucVisitId'";
				$part_qtyData = $this->bm->dataSelectDB1($part_qtyQuery);

				$part_qty = "";
				$part_unit = "";
				for($data = 0; $data<count($part_qtyData);$data++){
					$part_qty = $part_qtyData[$data]['pack_num'];
					$part_unit = $part_qtyData[$data]['pack_unit'];
				}

				$part_yardQuery = "SELECT shed_yard FROM shed_tally_info WHERE cont_number='$part_cont'  AND import_rotation='$part_rot_no' LIMIT 1 ";

				$part_yardRslt = $this->bm->dataSelectDB1($part_yardQuery);

				$part_yardNo="";
				for($data = 0; $data<count($part_yardRslt);$data++){
					$part_yardNo = $part_yardRslt[$data]['shed_yard'];
				}
	?>

	<?php
		if($rtnCartTicket[0]['actual_delv_pack']>0) { 
			require_once 'phpqrcode/qrlib.php';
			//$destination_folder = $_SERVER['DOCUMENT_ROOT']."/pcs/assets/images/qrcode/";
			$destination_folder = $_SERVER['DOCUMENT_ROOT']."/assets/images/qrcode/";
					
			$file = $trucVisitId.".png";
			$file1 = $destination_folder.$file;
			//$path = IMG_PATH."qrcode/".$file;
			$text =$trucVisitId;
			QRcode::png($text, $file1, 'L', 10, 2);		
	?>
	<div style="position:absolute;top:45px;right:45px;width:30%;text-align:right">
		<?php  echo "Print Time: ".date("Y-m-d h:i:s");?>
	</div>
	<table width="100%" border="0" style="padding-top:0px;margin-top:0px;padding-bottom:5px;margin-bottom:5px;">
		<tr>
			<td colspan="5" style="padding-top:0px;margin-top:0px;">
				<table align="center" style="padding-top:0px;margin-top:0px;">
					<tr>
						<td width="25%" align="right">
							<img src="<?php echo $file1;?>" height="68" width="68">
						</td>
						<td align="center" width="50%">
							<img align="middle"  height="60px" width="120px" src="<?php echo IMG_PATH?>cpa_logo.png">
							<p style="font-size:16px; "> CHITTAGONG PORT AUTHORITY </p>
							<p style="margin-top:-3px;">www.cpatos.gov.bd</p>
							<p style="font-size:14px; font-weight: bold;"><u>CART TICKET</u></p>
							<p style="font-size:16px; font-weight: bold;">
								<?php 
									$cfName = "";
									for($z=0;$z<count($CNFresult);$z++){
										$cfName = $CNFresult[$z]['NAME'];
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
		<tr>
			<td width="70px" align="right">Visit Id.&nbsp;&nbsp;&nbsp;</td>
			<td style="border-bottom:1px solid black"><?php echo $trucVisitId;?></td>
		</tr>
		<tr>
			<td width="70px" align="right">Rot No.&nbsp;&nbsp;&nbsp;</td>
			<td style="border-bottom:1px solid black"><?php echo $part_rot_no;?></td>
			<td colspan="2" align="right">Head Delivery Clerk.</td>
			<td style="border-bottom:1px solid black"></td>			
		</tr>
		<tr>
			<td width="30px" align="right">BL No.&nbsp;&nbsp;&nbsp;</td>
			<td style="border-bottom:1px solid black">
				<?php 
					echo $part_bl_no;									
				?>
			</td>
			<td>&nbsp;</td>
			<td width="50" align="right">Job No.</td>
			<td style="border-bottom:1px solid black"></td>
		</tr>
	</table>
	
	<table border="0" width="100%" style="padding-top:1px;margin-top:1px;">
		<tr>
			<td >Yard/Shed No.</td>
			<td colspan="2" style="border-bottom:1px solid black; "><b><?php  echo $part_yardNo; //echo $rtnCartTicket[0]['shed_yard'];?>  </b></td>
			<td align="center" >Release Order No. , CP:</td>
			<td colspan="2 "style="border-bottom:1px solid black;"><b><?php echo $part_cp_no." of ".substr($part_cp_date,0,10);?></b></td>
			<td align="center">Exit No: </td>
			<td style="border-bottom:1px solid black;"><?php echo $exitNo;?></td>
		</tr>
		<tr>
			<td >Ex. S/S. M/V</td>
			<td style="border-bottom:1px solid black;"><?php echo $part_vsl;?></td>
			<td align="center">No</td>
			<td style="border-bottom:1px solid black;"></td>
			<td align="center">Consignee:</td>
			<td colspan="3" style="border-bottom:1px solid black;"><?php echo $part_Consignee_name; ?></td>
		</tr>
		<tr>
			<td>B/E No.</td>
			<td style="border-bottom:1px solid black;" colspan="3"><b>
				<?php 
					$val = "";
					if($part_be_no != ""){
						$val.="C".$part_be_no." , ";
					}

					if($part_office_code != ""){
						$val.="Office: ".$part_office_code." , ";
					}

					if($part_be_dt != ""){
						$val.= " Date: ".$part_be_dt;
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
			<td style="border-bottom:1px solid black;"><b><?php echo $part_verify_no; ?></b></td>
		</tr>
	</table>
			
	<table border="1" width="100%" align="center" style="padding-bottom:1px;margin-bottom:1px;">
		<tr>			
			<th style="font-size:11px;">Marks</th>
			<th style="font-size:11px;">Description</th>
			<th style="font-size:11px;">Quantity</th>
			<th style="font-size:11px;">Unit</th>
			<th style="font-size:11px;">Consecutive Carts Total</th>
		</tr>
		<tr align="center">			
			<td style="font-size:11px;text-align:center;"><?php echo $part_marks; ?></td>
			<td style="font-size:11px;text-align:center;display:none;"><?php echo $descOfGoods; ?></td>
			<td align="center" style="font-size:11px;"><font size="5"><?php echo $part_qty; ?></font></td>
			<td align="center" style="font-size:11px;"><?php echo $part_unit;?></td>
			<td align="center" style="font-size:11px;"><?php echo $rtnCartTicket[0]['delv_pack'];?></td>
		</tr>	
	</table>
		
		
	<table border="0" width="100%" style="padding-top:0px;margin-top:0px;">
		<!--<tr>
			<td>&nbsp;</td>
		</tr>-->
		<tr style="padding-top:0px;margin-top:0px;">
			<td colspan="8">Container: <b><?php echo $part_cont." X ".$part_cont_size."'"; ?></b></td>
		</tr>
		<tr>
			<td colspan="2" style="font-size:11px;">Total Packages (in words)</td>
			<td style="border-bottom:1px solid black;" colspan="6"><?php echo convertNumberToWord($part_qty)." ".$part_unit." Only.";?></td>
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
	
	<?php } ?>
	
	</div>
	</div>
	
	<?php
			}
		}

		// if(count($additionalBlData)>0){
	?>

	<!-- <pagebreak /> -->

	<?php
		//}
	?>

<!--br/>
<br/>
<br/>
<br/>
<hr/>

    <?php
        // require_once 'phpqrcode/qrlib.php';
        // $destination_folder = $_SERVER['DOCUMENT_ROOT']."/pcs/assets/images/qrcode/";	
        // $destination_folder = $_SERVER['DOCUMENT_ROOT']."/assets/images/qrcode/";		
        // $file = $visitId.".png";
        // $file1 = $destination_folder.$file;
        // $path = IMG_PATH."qrcode/".$file;
        // $text =$visitId;
        // QRcode::png($text, $file1, 'L', 10, 2);		
    ?>
    <table align="center" width="80%" style="font-size:12px">				
        <tr align="center">
            <td align="center">
                <img src="<?php //echo $path;?>" height="68" width="68">
            </td>

            <td align="center">
                <img align="middle"  height="70px" width="210px" src="<?php //echo IMG_PATH?>cpanew.jpg">
                <p style="margin-top:-3px;">www.cpatos.gov.bd</p>
            </td>

            <td align="center">
                <?php			
                    // $text =$visitId;						
                    // $barcodeText = $text;
                ?>
                    <barcode code="<?php //echo $barcodeText; ?>" type="C128A" size="0.5" height="2" />
                    <br>
                <?php //echo sprintf("%010s", $text); ?>
            </td>
        </tr>
        <tr align="center">
            <th colspan="3" align="center"><b><font size=5><b>Invoice / Challan</b></font></b></td>
        </tr>
        <tr align="center">
            <th colspan="3" align="center"><b><font size=3>Visit ID : <?php //echo $visitId;?></font></b></th>
        </tr>
		<tr align="center">
            <th colspan="3" align="center"><b><font size=5><?php //echo @$CNFresult[0]['NAME'];?></font></b></th>
        </tr>
    </table>

    <table align="center" width="80%" border="1" style="font-size:12px;  border-collapse: collapse;">
        <tr>
            <th rowspan="2"> C&F Detail </th>
            <th> Address</th>
            <td><?php //echo @$CNFresult[0]['ADDRESS_LINE1'];?></td>
        </tr>
        <tr>
            <th> Phone</th>
            <td><?php //echo @$CNFresult[0]['SMS_NUMBER'];?></td>
        </tr>
        <tr>
            <th rowspan="2"> Importer Detail</th>
            <th> Name </th>
            <td><?php //echo $resQuery[0]['Notify_name'];?></td>
        </tr>
        <tr>
            <th> Address</th>
            <td><?php 
				// $notify_address = $resQuery[0]['Notify_address'];
				// $challanTruck = $resQuery[0]['truck_id'];
				// echo preg_replace('/[^A-Za-z0-9,;()@&\-]/', ' ', $notify_address);
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
                <th align="center">REMARKS</th>
            </tr>
        </thead>
        <tbody>
            <tr>
				<td align="center"><p style="font-family:ind_bn_1_001; font-size:13px" ><font size="6"><b><?php //echo $challanTruck;?></b></font></p></td>
                <td align="left"> 
					  	<?php 
                            // include("mydbPConnection.php");
							// $totalQty = 0;
							// $supquery = "SELECT igm_supplimentary_detail.Description_of_Goods
							// FROM igm_supplimentary_detail
							// WHERE igm_supplimentary_detail.Import_Rotation_No='$rot_no' AND igm_supplimentary_detail.BL_No='$challanBl'";

							// $suprslt = $this->bm->dataSelectDb1($supquery);
							// $descGoods = "";
							// for($si=0;$si<count($suprslt);$si++)
							// {
							// 	$descGoods = preg_replace('/[^A-Za-z0-9,;()@&\-]/', ' ', $suprslt[$si]['Description_of_Goods']);
							// }

							// if($si==0)
							// {
							// 	$query = "SELECT igm_details.Description_of_Goods
							// 	FROM igm_details
							// 	WHERE igm_details.Import_Rotation_No='$rot_no' AND igm_details.BL_No='$challanBl'";


							// 	$rslt = $this->bm->dataSelectDb1($query);
							// 	for($i=0;$i<count($rslt);$i++)
							// 	{
							// 		$descGoods = preg_replace('/[^A-Za-z0-9,;()@&\-]/', ' ', $rslt[$i]['Description_of_Goods']);
							// 	}
							// }
							
							// echo substr($descGoods,0,100);

							// $extraContPackQuery = "SELECT SUM(pack_num) AS pack_num FROM do_truck_details_additional_cont_lcl WHERE truck_visit_id = '$visitId'";
							// $extraContPackRslt = $this->bm->dataSelectDb1($extraContPackQuery);
							// $extraContPack = 0;
							// for($i=0;$i<count($extraContPackRslt);$i++){
							// 	$extraContPack = $extraContPackRslt[$i]['pack_num'];
							// }
						?> 

					  </td>
                <td align="center"><b> <font size="5"><?php 
                    // $qty = @$resQuery[0]['actual_delv_pack'] + $extraContPack;
                    
                    // echo $qty;
					// $totalQty = $qty;
                    
                ?> </font></b></td>
				<td align="center"><?php //echo $resQuery[0]['actual_delv_unit']; ?></td>
                <td align="center"></td>
            </tr>

			<?php
				// if(count($additionalBlData)>0){
				// 	$partChallan_bl_no = "";
				// 	for($a=0;$a<count($additionalBlData);$a++){
				// 		$partChallan_bl_no = $additionalBlData[$a]['bl_no'];

				// 		$ntsQueryChallan = "SELECT * FROM oracle_nts_data WHERE bl_no = '$partChallan_bl_no'";
				// 		$ntsDataChallan = $this->bm->dataSelectDB1($ntsQueryChallan);

				// 		$partChallan_rot_no = "";

				// 		for($b=0;$b<count($ntsDataChallan);$b++)
				// 		{
				// 			$partChallan_rot_no = $ntsDataChallan[$b]['imp_rot_no'];
				// 		}

				// 		$partChallanQuery="SELECT SUBSTR(igm_supplimentary_detail.Description_of_Goods,1,100) AS Description_of_Goods
				// 		FROM igm_sup_detail_container
				// 		INNER JOIN igm_supplimentary_detail ON igm_sup_detail_container.igm_sup_detail_id=igm_supplimentary_detail.id
				// 		WHERE igm_supplimentary_detail.BL_No='$partChallan_bl_no' AND igm_supplimentary_detail.Import_Rotation_No='$partChallan_rot_no'
						
				// 		UNION
						
				// 		SELECT SUBSTR(igm_details.Description_of_Goods,1,100) AS Description_of_Goods
				// 		FROM igm_detail_container
				// 		INNER JOIN igm_details ON igm_detail_container.igm_detail_id=igm_details.id
				// 		WHERE igm_details.BL_No='$partChallan_bl_no' AND igm_details.Import_Rotation_No='$partChallan_rot_no'";
				// 		$partChallanData = $this->bm->dataSelectDB1($partChallanQuery);

				// 		$challandescOfGoods = "";
				// 		for($data = 0; $data<count($partChallanData);$data++){
				// 			$challandescOfGoods = $partChallanData[$data]['Description_of_Goods'];
				// 		}

				// 		$partChallan_qtyQuery = "SELECT igm_pack_unit.Pack_Unit AS pack_unit, pack_num FROM do_truck_details_additional_bl_lcl 
				// 		INNER JOIN igm_pack_unit ON igm_pack_unit.id = do_truck_details_additional_bl_lcl.pack_unit
				// 		WHERE bl_no = '$partChallan_bl_no'";
				// 		$partChallan_qtyData = $this->bm->dataSelectDB1($partChallan_qtyQuery);

				// 		$partChallan_qty = "";
				// 		$partChallan_unit = "";
				// 		for($data = 0; $data<count($partChallan_qtyData);$data++){
				// 			$partChallan_qty = $partChallan_qtyData[$data]['pack_num'];
				// 			$partChallan_unit = $partChallan_qtyData[$data]['pack_unit'];
				// 		}

			?>
				
				<tr>
					<td align="center"><p style="font-family:ind_bn_1_001; font-size:13px" ><font size="6"><b><?php //echo $challanTruck;?></b></font></p></td>
					<td align="left"><?php //echo $challandescOfGoods; ?></td>
					<td align="center"><b><font size="5">
						<?php 
							// echo $partChallan_qty; 
							// $totalQty+=$partChallan_qty;
						?></font></b>
					</td>
					<td align="center"><?php //echo $partChallan_unit; ?></td>
					<td align="center"></td>
				</tr>

			<?php
				// 	}
				// }

				//if(count($additionalBlData)>0){
			?>
				<tr>
					<td align="right" colspan="2"><font size='5'><b>Total: </b></font></td>
					<td align="center"><font size='5'><b><?php //echo $totalQty; ?></b></font></td>
					<td></td>
					<td></td>
				</tr>
			<?php
				//}
			?>

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
    </div-->

</body>
</html>

<?php
	function convertNumberToWord($num = false)
	{
		$num = str_replace(array(',', ' '), '' , trim($num));
		if(! $num) {
			return false;
		}
		$num = (int) $num;
		$words = array();
		$list1 = array('', 'One', 'Two', 'Three', 'Four', 'Five', 'Six', 'Seven', 'Eight', 'Nine', 'Ten', 'Eleven',
			'Twelve', 'Thirteen', 'Fourteen', 'Fifteen', 'Sixteen', 'Seventeen', 'Eighteen', 'Nineteen'
		);
		$list2 = array('', 'Ten', 'Twenty', 'Thirty', 'Forty', 'Fifty', 'Sixty', 'Seventy', 'Eighty', 'Ninety', 'Hundred');
		$list3 = array('', 'Thousand', 'Million', 'Billion', 'Trillion', 'Quadrillion', 'quintillion', 'sextillion', 'septillion',
			'octillion', 'nonillion', 'decillion', 'undecillion', 'duodecillion', 'tredecillion', 'quattuordecillion',
			'quindecillion', 'sexdecillion', 'septendecillion', 'octodecillion', 'novemdecillion', 'vigintillion'
		);
		$num_length = strlen($num);
		$levels = (int) (($num_length + 2) / 3);
		$max_length = $levels * 3;
		$num = substr('00' . $num, -$max_length);
		$num_levels = str_split($num, 3);
		for ($i = 0; $i < count($num_levels); $i++) {
			$levels--;
			$hundreds = (int) ($num_levels[$i] / 100);
			$hundreds = ($hundreds ? ' ' . $list1[$hundreds] . ' Hundred' . ' ' : '');
			$tens = (int) ($num_levels[$i] % 100);
			$singles = '';
			if ( $tens < 20 ) {
				$tens = ($tens ? ' ' . $list1[$tens] . ' ' : '' );
			} else {
				$tens = (int)($tens / 10);
				$tens = ' ' . $list2[$tens] . ' ';
				$singles = (int) ($num_levels[$i] % 10);
				$singles = ' ' . $list1[$singles] . ' ';
			}
			$words[] = $hundreds . $tens . $singles . ( ( $levels && ( int ) ( $num_levels[$i] ) ) ? ' ' . $list3[$levels] . ' ' : '' );
		} //end for loop
		$commas = count($words);
		if ($commas > 1) {
			$commas = $commas - 1;
		}
		return implode(' ', $words);
	}
?>
