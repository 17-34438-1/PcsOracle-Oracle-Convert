<html>
	<head>
		
	</head>
	<body>
		
		<?php

            include("mydbPConnection.php");

            $login_id = $this->session->userdata("login_id");
            $allGatePassQuery = "SELECT id,import_rotation,cont_no,truck_id,gate_no,driver_name,driver_gate_pass,assistant_name,assistant_gate_pass FROM do_truck_details_entry WHERE gate_in_status=0 AND update_by = '$login_id'";
            $allGatePassData = mysqli_query($con_cchaportdb,$allGatePassQuery);

            $trucVisitId = "";
            $rot_no = "";
            $cont_no = "";
            $i=0;
            while($allGatePassDataResult = mysqli_fetch_object($allGatePassData))
            {
                $trucVisitId = $allGatePassDataResult->id;
                $rot_no = $allGatePassDataResult->import_rotation;
                $cont_no = $allGatePassDataResult->cont_no;

				$igmQuery="SELECT  igm_detail_container.cont_number, cont_seal_number,cont_status,cont_height,cont_iso_type, Description_of_Goods,Vessel_Name,Name_of_Master,igm_masters.Import_Rotation_No,truck_no_by,Bill_of_Entry_No
				FROM  igm_details
				INNER JOIN igm_masters ON  igm_masters.id=igm_details.IGM_id
				INNER JOIN igm_detail_container ON igm_detail_container.igm_detail_id=igm_details.id
				LEFT JOIN verify_info_fcl ON igm_detail_container.igm_detail_id=verify_info_fcl.igm_detail_cont_id
				LEFT JOIN  shed_tally_info ON shed_tally_info.igm_detail_id=igm_details.id
				LEFT JOIN verify_other_data ON shed_tally_info.id=verify_other_data.shed_tally_id
				WHERE igm_details.Import_Rotation_No='$rot_no' AND igm_detail_container.cont_number='$cont_no'";
	
				$rsltIgm = mysqli_query($con_cchaportdb,$igmQuery);
				$beNo = "";
				$Vessel_Name="";
				$Import_Rotation_No = "";
				$description_of_Goods = "";
				$cont_number = "";
				while($igmResult = mysqli_fetch_object($rsltIgm)){
					$Vessel_Name = $igmResult->Vessel_Name;
					$Import_Rotation_No = $igmResult->Import_Rotation_No;
					$description_of_Goods = $igmResult->Description_of_Goods;
					$cont_number = $igmResult->cont_number;
					$beNo = $igmResult->Bill_of_Entry_No;
				}
	
	
				$truckQuery="SELECT verify_info_fcl_id,truck_id,verify_other_data_id,gate_no,driver_name,driver_gate_pass,assistant_name,assistant_gate_pass,visit_time_slot_start,visit_time_slot_end,paid_status, paid_method, paid_amt,paid_collect_dt,paid_collect_by,collect_gate_no,truck_agency_name
				FROM cchaportdb.do_truck_details_entry  
				WHERE id='$trucVisitId'";
	
				$rsltTruck = mysqli_query($con_cchaportdb,$truckQuery);
	
				$gate_no = "";
				$truck_id = "";
				$driver_name = "";
				$driver_gate_pass = "";
				$assistant_name = "";
				$assistant_gate_pass = "";
				$visit_time_slot_start = "";
				$visit_time_slot_end = "";
				$method = "";
				$paid_status = "";
				$paid_amt = "";
				$paid_collect_dt = "";
				$paid_collect_by = "";
				$collect_gate_no = "";
				$verify_info_fcl_id = "";
				$verifyOtherDataId = "";
				$truck_agency_name = "";
				$driverImg = "";
				$helperImg = "";
				while($truckResult = mysqli_fetch_object($rsltTruck)){
					$gate_no = $truckResult->gate_no;
					$truck_id = $truckResult->truck_id;
					$driver_name = $truckResult->driver_name;
					$driver_gate_pass = $truckResult->driver_gate_pass;
					$assistant_name = $truckResult->assistant_name;
					$assistant_gate_pass = $truckResult->assistant_gate_pass;
					$visit_time_slot_start = $truckResult->visit_time_slot_start;
					$visit_time_slot_end = $truckResult->visit_time_slot_end;
					$method = $truckResult->paid_method;
					$paid_status = $truckResult->paid_status;
					$paid_amt = $truckResult->paid_amt;
					$paid_collect_dt = $truckResult->paid_collect_dt;
					$paid_collect_by = $truckResult->paid_collect_by;
					$collect_gate_no = $truckResult->collect_gate_no;
					$verify_info_fcl_id = $truckResult->verify_info_fcl_id;
					$verifyOtherDataId = $truckResult->verify_other_data_id;
					$truck_agency_name = $truckResult->truck_agency_name;
					
					$sql_driverImg = "SELECT nid_number,agent_photo
					FROM vcms_vehicle_agent
					WHERE card_number='$driver_gate_pass'";
					$rslt_driverImg = mysqli_query($con_cchaportdb,$sql_driverImg);
					
					while($row_driverImg = mysqli_fetch_object($rslt_driverImg))
					{							
						$driverImg = "vcmsImage/".$row_driverImg->nid_number."/".$row_driverImg->agent_photo;							
					}
					
					$sql_helperImg = "SELECT nid_number,agent_photo
					FROM vcms_vehicle_agent
					WHERE card_number='$assistant_gate_pass'";
					$rslt_helperImg = mysqli_query($con_cchaportdb,$sql_helperImg);
					
					while($row_helperImg = mysqli_fetch_object($rslt_helperImg))
					{							
						$helperImg = "vcmsImage/".$row_helperImg->nid_number."/".$row_helperImg->agent_photo;
					}
				}
	
				if(is_null($verify_info_fcl_id)){
				
					$jettySarkarQuery = "SELECT DISTINCT agent_name,agent_code FROM vcms_vehicle_agent 
					INNER JOIN lcl_dlv_assignment ON lcl_dlv_assignment.jetty_sirkar_id = vcms_vehicle_agent.id
					INNER JOIN do_truck_details_entry ON do_truck_details_entry.verify_other_data_id = lcl_dlv_assignment.id
					WHERE do_truck_details_entry.verify_other_data_id='$verifyOtherDataId'";
				}else if(is_null($verifyOtherDataId)){
					$jettySarkarQuery = "SELECT DISTINCT agent_name,agent_code,verify_info_fcl.cnf_lic_no FROM vcms_vehicle_agent 
					INNER JOIN verify_info_fcl ON verify_info_fcl.jetty_sirkar_id = vcms_vehicle_agent.id
					INNER JOIN do_truck_details_entry ON do_truck_details_entry.verify_info_fcl_id = verify_info_fcl.id
					WHERE do_truck_details_entry.verify_info_fcl_id='$verify_info_fcl_id'";
				}
	
				$rsltJetty = mysqli_query($con_cchaportdb,$jettySarkarQuery);
	
				$agentName = "";
				$agentCode = "";
				while($jettyInfo = mysqli_fetch_object($rsltJetty)){
					$agentName = $jettyInfo->agent_name;
					$agentCode = $jettyInfo->agent_code;
				}
	
				include("mydbPConnectionn4.php");
				include("dbOracleConnection.php");
	
				
				

				$cnfQuery="SELECT DISTINCT a.gkey,a.id AS cont_no,k.name  AS cnf, k.id AS cnf_lic,
				(k.address_line1 || NVL(k.address_line2,'')) AS cnf_addr,
				a.gkey, a.id AS cont_no, j.bl_nbr, k.gkey AS bizu_gkey, config_metafield_lov.mfdch_value,
				mfdch_desc, b.flex_date01,b.last_pos_slot,
				(SELECT inv_unit_fcy_visit.flex_string10 FROM inv_unit
				INNER JOIN inv_unit_fcy_visit ON inv_unit_fcy_visit.unit_gkey=inv_unit.gkey
				WHERE inv_unit.id=a.id ORDER BY inv_unit_fcy_visit.flex_date01 DESC FETCH FIRST 1 ROWS only) AS rot_no,
				
				(SELECT SUBSTR(nominal_length, -2) FROM ref_equip_type
				INNER JOIN ref_equipment ON ref_equipment.eqtyp_gkey = ref_equip_type.gkey
				INNER JOIN inv_unit ON inv_unit.eq_gkey = ref_equipment.gkey
				INNER JOIN inv_unit_fcy_visit  ON inv_unit_fcy_visit.unit_gkey = inv_unit.gkey
				WHERE inv_unit_fcy_visit.unit_gkey = a.gkey FETCH FIRST 1 ROWS only )  AS siz,
				
				CAST((SELECT (SUBSTR(ref_equip_type.nominal_height,-2)/10) FROM ref_equip_type
				INNER JOIN ref_equipment ON ref_equipment.eqtyp_gkey = ref_equip_type.gkey
				INNER JOIN inv_unit ON inv_unit.eq_gkey = ref_equipment.gkey
				INNER JOIN inv_unit_fcy_visit  ON inv_unit_fcy_visit.unit_gkey = inv_unit.gkey
				WHERE inv_unit_fcy_visit.unit_gkey = a.gkey FETCH FIRST 1 ROWS only ) AS DECIMAL(10,1))  AS height,
				
				(SELECT vsl_vessels.name FROM vsl_vessels
				INNER JOIN vsl_vessel_visit_details ON vsl_vessel_visit_details.vessel_gkey=vsl_vessels.gkey
				WHERE vsl_vessel_visit_details.ib_vyg=b.flex_string10 FETCH FIRST 1 ROWS only) AS v_name
				
				FROM inv_unit a
				INNER JOIN inv_unit_fcy_visit b ON b.unit_gkey = a.gkey
				INNER JOIN inv_goods j ON j.gkey = a.goods
				INNER JOIN vsl_vessel_visit_details ON vsl_vessel_visit_details.ib_vyg=b.flex_string10
				LEFT JOIN ref_bizunit_scoped k ON k.gkey = j.consignee_bzu
				INNER JOIN config_metafield_lov ON a.flex_string01 = config_metafield_lov.mfdch_value
				WHERE a.id='$cont_no' AND vsl_vessel_visit_details.ib_vyg ='$rot_no'";
	
				
				$rsltCnf  = oci_parse($con_sparcsn4_oracle, $cnfQuery);
                oci_execute($rsltCnf);
				$cnf="";
				$cnf_addr = "";
				$cnf_lic = "";
				$assignmentType="";
				

				while(($cnfResult= oci_fetch_object($rsltCnf)) != false){
					
					$cnf = $cnfResult->CNF;
					$cnf_addr = $cnfResult->CNF_ADDR;
				
					$cnf_lic = $cnfResult->CNF_LIC;
					$assignmentType = $cnfResult->MFDCH_VALUE;
					$last_pos_slot=$cnfResult->LAST_POS_SLOT;
					$cnfQuery2="SELECT ctmsmis.cont_yard('$last_pos_slot') AS Yard_No";
					$rsltQuery2 = mysqli_query($con_sparcsn4,$cnfQuery2);
					$result2=mysqli_fetch_object($rsltQuery2);
					$yardNumber=$result2->Yard_No;

					$cnfQuery3="SELECT ctmsmis.cont_block('$last_pos_slot', '$yardNumber') AS Block_No";
					$rsltQuery3 = mysqli_query($con_sparcsn4,$cnfQuery3);
					$result3=mysqli_fetch_object($rsltQuery3);
					$blockNo=$result3->Block_No;
				}
	
				$YardQuery = "SELECT DISTINCT Yard_No
				FROM ctmsmis.tmp_vcms_assignment  
				WHERE cont_no = '$cont_no' AND rot_no = '$rot_no'";
	
				$rsltYard = mysqli_query($con_sparcsn4,$YardQuery);
	
				$yardNo="";
				while($yardResult=mysqli_fetch_object($rsltYard)){
					$yardNo = $yardResult->Yard_No;
				}

				if($i>0){
					echo "<pagebreak>";
				}
	
			?>
			
			<?php
				if($paid_status==1){
			?>
				<p align="center"><img src="<?php echo ASSETS_WEB_PATH;?>fimg/cpaLogo.png"></p>
				<p style="margin-top:-3px;" align="center">www.cpatos.gov.bd</p>
			<?php
				}else{
			?>
				<h2 align="center"><?php echo $cnf; ?></h2>
				<h4 align="center"><?php echo $cnf_addr; ?></h4>
				<h4 align="center">C&F LICENSE NO: <?php echo $cnf_lic; ?></h4>
			<?php
				}
			?>
			
			<table border="0" align="center" width="90%" style="margin-bottom:0px;padding-bottom:0px;">
				<tr>
					<?php
						if($paid_status==1){
					?>
						<td colspan="3" align="left">&nbsp;</td>
					<?php
						}else{
					?>
						<td colspan="3" align="left">To,</td>
					<?php
						}
					?>
				</tr>
				<tr>
					<?php
						if($paid_status==1){
					?>
						<td width="30%">&nbsp;</td>
					<?php
						}else{
					?>
						<td align="left">Gate Sergeant</td>
					<?php
						}
					?>
					<?php
						if($paid_status==1){
					?>
						<td align="center" style="font-size:16px;"><b><u>Vehicle Pass (1 day)</u></b></td>
					<?php
						}else{
					?>
						<td align="center" style="font-size:16px;"><b><u>Application for Vehicle Gate Pass</u></b></td>
					<?php
						}
					?>
	
					<td align="right">Date: <?php echo DATE("Y-m-d"); ?></td>
				</tr>
	
				<?php
					if($paid_status==0){
				?>
					<tr>
						<td colspan="3" align="left">Sir,</td>
					</tr>
					<tr>
						<td colspan="3" align="left">You are requested to issue an entry pass for transportation of goods in the described TRUCK / LONG VEHICLE / COVERED VAN / TRAILER inside jetty.</td>
					</tr>
				
				<?php
					}
				?>
	
				<!-- <tr>
					<td colspan="3" width="100%">
						<table border="0" cellpadding="5" style="border:1px solid #000;" align="center" width="100%">
							<tr>
								<td align="right" width="100px;"><nobr>Truck Visit No.: </nobr></td>
								<th align="left"><b><nobr><?php echo $trucVisitId;?></nobr></b></th>
								<td align="right" width="100px;">Assignment Type: </td>
								<td align="left"><b> <?php echo $assignmentType; ?> </b></td>
								<td align="right">From: </td>
								<th align="left" width="140px;"><b><?php echo $visit_time_slot_start; ?></b></th>
								<td align="right">To: </td>
								<th align="left" width="140px;"><b><?php echo $visit_time_slot_end; ?></b></th>
							</tr>
						</table>
					</td>
				</tr> -->
			</table>
	
			<table border="0" cellpadding="5" style="border:1px solid #000;" align="center" width="90%">
				<tr>
					<td align="right" width="100px;"><nobr>Truck Visit No.: </nobr></td>
					<th align="left"><b><nobr><?php echo $trucVisitId;?></nobr></b></th>
					<td align="right" width="100px;">Assignment Type: </td>
					<td align="left"><b> <?php echo $assignmentType; ?> </b></td>
					<td align="right">From: </td>
					<th align="left" width="140px;"><b><?php echo $visit_time_slot_start; ?></b></th>
					<td align="right">To: </td>
					<th align="left" width="140px;"><b><?php echo $visit_time_slot_end; ?></b></th>
				</tr>
			</table>
			
			<table border="0" align="center" width="90%" cellpadding="5" style="margin-top:0px;padding-top:0px;margin-bottom:0px;padding-bottom:0px;">
				<tr>
					<th colspan="3" style="font-size:15px;" align="left"><u>Jetty Sarkar Information</u></th>
					<th colspan="3" style="font-size:15px;" align="left"><u>Transport Agency Information</u></th>
					<!-- <th>&nbsp;</th> -->
				</tr>
				<tr>
					<td>1. Vessel</td>
					<td>:</td>
					<th align="left"><?php echo $Vessel_Name; ?></th>
					<td>1. Gate No</td>
					<td>:</td>
					<th align="left"><?php if($collect_gate_no!=''){echo $collect_gate_no;}else{ echo "3,4,5"; } ?></th>
					<!-- <th rowspan='4'><img src='<?php echo PASS_PATH.$driverImg; ?>' height='120px' width='120px'></th> -->
	
				</tr>
				
				<tr>
					<td>2. Rotation</td>
					<td>:</td>
					<th align="left"><?php echo $Import_Rotation_No; ?></th>
					<td>2. Truck No</td>
					<td>:</td>
					<th align="left"><p style="font-family: ind_bn_1_001"><?php echo $truck_id; ?></p></th>
				</tr>
				
				<tr>
					<td>3. B/E No</td>
					<td>:</td>
					<th align="left"><?php echo $beNo; ?></th>
					<td>3. Driver Name</td>
					<td>:</td>
					<th align="left"><?php echo $driver_name; ?></th>
				</tr>
				
				<tr>
					<td>4. Shed / Yard No</td>
					<td>:</td>
					<th align="left"><?php echo $yardNo; ?></th>
					<td>4. ID Card (Driver)</td>
					<td>:</td>
					<th align="left"><?php echo $driver_gate_pass; ?></th>
				</tr>
				
				<tr>
					<td>5. Jetty Sarkar Name</td>
					<td>:</td>
					<th align="left"><?php echo $agentName; ?></th>
					<!--td>5. Union Membership No. (Driver)</td>
					<td>:</td>
					<th align="left">&nbsp;</th-->
					<td>5. Assistant Name</td>
					<td>:</td>
					<th align="left"><?php echo $assistant_name; ?></th>
					<!-- <th rowspan='4'><img src='<?php echo PASS_PATH.$helperImg; ?>' height='120px' width='120px'></th> -->
				</tr>
				
				<tr>
					<td>6. Jetty Sarkar Lic. No.</td>
					<td>:</td>
					<th align="left"><?php echo $agentCode; ?></th>
					<td>6. ID Card (Assistant)</td>
					<td>:</td>
					<th align="left"><?php echo $assistant_gate_pass; ?></th>
				</tr>
				
				<tr>
					<td>7. Container No.</td>
					<td>:</td>
					<th align="left"><?php echo $cont_number; ?></th>
					<!--td>7. ID Card (Assistant)</td>
					<td>:</td>
					<th align="left"><?php echo $assistant_gate_pass; ?></th-->
					<td>7. Transport Agency Name</td>
					<td>:</td>
					<th align="left"><?php echo $truck_agency_name; ?></th>
				</tr>
	
				<tr>
					<td rowspan="2">8. Goods Details</td>
					<td rowspan="2">:</td>
					<th align="left" rowspan="2"><?php echo substr($description_of_Goods,0,50);?></th>
					<!--td>8. Union Membership No. (Assistant)</td>
					<td>:</td>
					<th align="left">&nbsp;</th-->
				</tr>
				
				
			</table>
			
			<!-- <table border="0" cellpadding="3" style="border:1px solid #000;" align="right" >
				<tr>
					<td align="left">Fees</td>
					<td>:</td>
					<th align="left"><b><?php echo $paid_amt;?></b></th> 
				</tr>
				<tr>
					<td align="left">Payment Mathod</td>
					<td>:</td>
					<th align="left"><b><?php echo ucfirst($method);?></b></th> 
				</tr>
				<tr>
					<td align="left">Payment Status</td>
					<td>:</td>
					<th align="left"><b><?php if($paid_status==1){echo "Paid";}else{ echo "Not Paid";} ?></b></th>
				</tr>
			</table> -->
	
			<?php
			require_once 'phpqrcode/qrlib.php';
			// $destination_folder = $_SERVER['DOCUMENT_ROOT']."/pcs/assets/images/qrcode/";
			$destination_folder = $_SERVER['DOCUMENT_ROOT']."/assets/images/qrcode/";
			$rot = $rot_no;
			$cont = $cont_no;		
			$file = $trucVisitId.".png";
			$file1 = $destination_folder.$file;
			//$path = IMG_PATH."qrcode/".$file;
			$text =$trucVisitId;
			QRcode::png($text, $file1, 'L', 10, 2);		
			?>
			
			<table border="0" width="90%" align="center">
				<tr>
					
					<td align="left" width="33%">
	
						<img src="<?php echo $file1;?>" height="70" width="70">
	
					</td>
	
					<td align="center" width="33%">
	
						<?php		
							$rot = $rot_no;
							$cont = $cont_no;		
							$text =$trucVisitId;						
							$barcodeText = $text;
						?>
						<barcode code="<?php echo $barcodeText; ?>" type="C128A" size="0.6" height="2" />
						<br>
						<?php echo sprintf("%010s", $text); ?>
					</td>
	
					<td align="right" width="34%">
						<table border="0" cellpadding="3" style="border:1px solid #000;" align="right" >
							<tr>
								<td align="left">Fees</td>
								<td>:</td>
								<th align="left"><b><?php echo $paid_amt;?></b></th> 
							</tr>
							<tr>
								<td align="left">Payment Mathod</td>
								<td>:</td>
								<th align="left"><b><?php echo ucfirst($method);?></b></th> 
							</tr>
							<tr>
								<td align="left">Payment Status</td>
								<td>:</td>
								<th align="left"><b><?php if($paid_status==1){echo "Paid";}else{ echo "Not Paid";} ?></b></th>
							</tr>
						</table>
					</td>
					
				</tr>
				<!--tr>
					<td>
						&nbsp;
					</td>
					<td align="right" style="padding-right:25px;">
						<?php echo $text; ?>
					</td>
				</tr-->
				<?php 
					if($paid_status==1){
				?>
					<tr>
						<td colspan="3" align="center">Issued from <?php if($collect_gate_no!=''){echo $collect_gate_no;}else{ echo "3,4,5"; } ?> at <b><?php echo $paid_collect_dt ?></b></td>
					</tr>
					<tr>
						<td colspan="3" align="center">Valid Till: <b><?php echo $validDate = date('Y-m-d H:i:s', strtotime($paid_collect_dt . ' +1 day')); ?></b></td>
					</tr>
				<?php
					}
				?>
			</table>

			<div style="position:absolute;bottom:25px;right:50px;width:20%;text-align:right">
				<?php  echo "Print Time: ".date("Y-m-d h:i:s");?>
			</div>
        
        <?php
            $i++;
            }
        ?>
	</body>
</html>
