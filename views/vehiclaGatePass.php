<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <style>
			* {
				font-size: 12px;
				font-family: 'Times New Roman';
			}

			td,
			th,
			tr,
			table {
				border-collapse: collapse;
			}

			

			.centered {
				text-align: center;
				align-content: center;
			}

			.ticket {
				width: 192px;
				max-width: 192px;
				margin:0 auto;
			}

			img {
				max-width: inherit;
				width: inherit;
			}

			@media print {
				.hidden-print,
				.hidden-print * {
					display: none !important;
				}
				@page {
					margin-left: 0.5in;
					margin-right: 0.5in;
					margin-top: 0;
					margin-bottom: 0;
				}
			}
			
			
			.button {
			  background-color: #008CBA; 
			  border: none;
			  color: white;
			  padding: 10px 10px;
			  text-align: center;
			  text-decoration: none;
			  display: inline-block;
			  font-size: 16px;
			  margin: 4px 2px;
			  cursor: pointer;
			  border-radius: 12px;
			}

		</style>
        <title>Vehicle Pass (1 day)</title>
    </head>
    <body>

		<?php
			
			$beNo = "";
			$Vessel_Name="";
			$Import_Rotation_No = "";
			$description_of_Goods = "";
			$cont_number = "";


			$visit_id = "";
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
			$cnfName = "";
			$cnfCode = "";

			$agentName = "";
			$agentCode = "";

			$cnf="";
			$cnf_addr = "";
			$cnf_lic = "";
			$assignmentType="";

			$blockNo="";

			if(($rot_no == "" || $rot_no == null) && ($cont_no == "" || $cont_no == null))
			{
				$visit_id = $trucVisitId;
				$cnfName = $u_name;
			}
			else
			{
				include("mydbPConnection.php");
				$igmQuery="SELECT  igm_detail_container.cont_number, cont_seal_number,cont_status,cont_height,cont_iso_type, Description_of_Goods,Vessel_Name,Name_of_Master,igm_masters.Import_Rotation_No,truck_no_by,Bill_of_Entry_No
				FROM  igm_details
				INNER JOIN igm_masters ON  igm_masters.id=igm_details.IGM_id
				INNER JOIN igm_detail_container ON igm_detail_container.igm_detail_id=igm_details.id
				LEFT JOIN verify_info_fcl ON igm_detail_container.igm_detail_id=verify_info_fcl.igm_detail_cont_id
				LEFT JOIN  shed_tally_info ON shed_tally_info.igm_detail_id=igm_details.id
				LEFT JOIN verify_other_data ON shed_tally_info.id=verify_other_data.shed_tally_id
				WHERE igm_details.Import_Rotation_No='$rot_no' AND igm_detail_container.cont_number='$cont_no'";

				$rsltIgm = mysqli_query($con_cchaportdb,$igmQuery);
				// $beNo = "";
				// $Vessel_Name="";
				// $Import_Rotation_No = "";
				// $description_of_Goods = "";
				// $cont_number = "";
				
				while($igmResult = mysqli_fetch_object($rsltIgm))
				{
					$Vessel_Name = $igmResult->Vessel_Name;
					$Import_Rotation_No = $igmResult->Import_Rotation_No;
					$description_of_Goods = $igmResult->Description_of_Goods;
					$cont_number = $igmResult->cont_number;
					$beNo = $igmResult->Bill_of_Entry_No;
				}


				$truckQuery="SELECT do_truck_details_entry.id AS visit_id, verify_info_fcl_id,truck_id,verify_other_data_id,gate_no,
				driver_name,driver_gate_pass,assistant_name,assistant_gate_pass,visit_time_slot_start,visit_time_slot_end,paid_status, 
				paid_method, paid_amt,paid_collect_dt,paid_collect_by,collect_gate_no,truck_agency_name,u_name,
				LEFT(do_truck_details_entry.update_by,9) AS agencyCode
				FROM do_truck_details_entry 
				INNER JOIN users ON users.login_id = do_truck_details_entry.update_by 
				WHERE do_truck_details_entry.id='$trucVisitId'";

				$rsltTruck = mysqli_query($con_cchaportdb,$truckQuery);

				// $visit_id = "";
				// $gate_no = "";
				// $truck_id = "";
				// $driver_name = "";
				// $driver_gate_pass = "";
				// $assistant_name = "";
				// $assistant_gate_pass = "";
				// $visit_time_slot_start = "";
				// $visit_time_slot_end = "";
				// $method = "";
				// $paid_status = "";
				// $paid_amt = "";
				// $paid_collect_dt = "";
				// $paid_collect_by = "";
				// $collect_gate_no = "";
				// $verify_info_fcl_id = "";
				// $verifyOtherDataId = "";
				// $truck_agency_name = "";
				// $driverImg = "";
				// $helperImg = "";
				// $cnfName = "";
				// $cnfCode = "";

				while($truckResult = mysqli_fetch_object($rsltTruck))
				{
					$visit_id = $truckResult->visit_id;
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
					$cnfName = $truckResult->u_name;
					$cnfCode = $truckResult->agencyCode;
					
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
					// $jettySarkarQuery = "SELECT DISTINCT agent_name,agent_code,verify_other_data.cnf_lic_no FROM vcms_vehicle_agent 
					// INNER JOIN verify_other_data ON verify_other_data.jetty_sirkar_id = vcms_vehicle_agent.id
					// INNER JOIN do_truck_details_entry ON do_truck_details_entry.verify_other_data_id = verify_other_data.id
					// WHERE do_truck_details_entry.verify_other_data_id='$verifyOtherDataId'";
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
				else
				{
					die("Unable to connect. Please try again.");
				}

				$rsltJetty = mysqli_query($con_cchaportdb,$jettySarkarQuery);

				// $agentName = "";
				// $agentCode = "";
				while($jettyInfo = mysqli_fetch_object($rsltJetty)){
					$agentName = $jettyInfo->agent_name;
					$agentCode = $jettyInfo->agent_code;
				}

				include("mydbPConnectionn4.php");

				$cnfQuery="SELECT DISTINCT a.gkey,a.id AS cont_no,k.name  AS cnf, k.id AS cnf_lic,
				CONCAT(k.address_line1,IFNULL(k.address_line2,'')) AS cnf_addr,
				a.gkey, a.id AS cont_no, j.bl_nbr, k.gkey AS bizu_gkey, config_metafield_lov.mfdch_value,
				mfdch_desc, 
				(SELECT ctmsmis.cont_yard(b.last_pos_slot)) AS Yard_No, b.flex_date01,
				(SELECT ctmsmis.cont_block(b.last_pos_slot, Yard_No)) AS Block_No,
				(SELECT inv_unit_fcy_visit.flex_string10 FROM sparcsn4.inv_unit
				INNER JOIN sparcsn4.inv_unit_fcy_visit ON inv_unit_fcy_visit.`unit_gkey`=inv_unit.`gkey`
				WHERE inv_unit.id=a.id ORDER BY inv_unit_fcy_visit.flex_date01 DESC LIMIT 1) AS rot_no,
				(SELECT RIGHT(nominal_length, 2) FROM sparcsn4.ref_equip_type
				INNER JOIN sparcsn4.ref_equipment ON sparcsn4.ref_equipment.eqtyp_gkey = sparcsn4.ref_equip_type.gkey
				INNER JOIN sparcsn4.inv_unit_equip ON sparcsn4.inv_unit_equip.eq_gkey = sparcsn4.ref_equipment.gkey
				WHERE sparcsn4.inv_unit_equip.unit_gkey = a.gkey)  AS size,
				CAST((SELECT (RIGHT(sparcsn4.ref_equip_type.nominal_height,2)/10) FROM sparcsn4.ref_equip_type
				INNER JOIN sparcsn4.ref_equipment ON sparcsn4.ref_equipment.eqtyp_gkey = sparcsn4.ref_equip_type.gkey
				INNER JOIN sparcsn4.inv_unit_equip ON sparcsn4.inv_unit_equip.eq_gkey = sparcsn4.ref_equipment.gkey
				WHERE sparcsn4.inv_unit_equip.unit_gkey = a.gkey) AS DECIMAL(10,1))  AS height,
				(SELECT sparcsn4.vsl_vessels.name FROM sparcsn4.vsl_vessels
				INNER JOIN sparcsn4.vsl_vessel_visit_details ON sparcsn4.vsl_vessel_visit_details.vessel_gkey=sparcsn4.vsl_vessels.gkey
				WHERE sparcsn4.vsl_vessel_visit_details.ib_vyg=b.flex_string10 LIMIT 1) AS v_name
				FROM sparcsn4.inv_unit a
				INNER JOIN sparcsn4.inv_unit_fcy_visit b ON b.unit_gkey = a.gkey
				INNER JOIN sparcsn4.inv_goods j ON j.gkey = a.goods
				INNER JOIN vsl_vessel_visit_details ON sparcsn4.vsl_vessel_visit_details.ib_vyg=b.flex_string10
				LEFT JOIN sparcsn4.ref_bizunit_scoped k ON k.gkey = j.consignee_bzu
				INNER JOIN sparcsn4.config_metafield_lov ON a.flex_string01 = config_metafield_lov.mfdch_value
				WHERE a.id='$cont_no' AND sparcsn4.vsl_vessel_visit_details.ib_vyg ='$rot_no'";

				$rsltCnf = mysqli_query($con_sparcsn4,$cnfQuery);
				// $cnf="";
				// $cnf_addr = "";
				// $cnf_lic = "";
				// $assignmentType="";
				while($cnfResult=mysqli_fetch_object($rsltCnf)){
					$cnf = $cnfResult->cnf;
					$cnf_addr = $cnfResult->cnf_addr;
					$cnf_lic = $cnfResult->cnf_lic;
					$assignmentType = $cnfResult->mfdch_value;
				}

				$BlockQuery = "SELECT DISTINCT Block_No FROM ctmsmis.tmp_vcms_assignment WHERE cont_no = '$cont_no' ORDER BY flex_date01 LIMIT 1";

				$rsltBlock = mysqli_query($con_sparcsn4,$BlockQuery);

				// $blockNo="";
				while($BlockResult=mysqli_fetch_object($rsltBlock)){
					$blockNo = $BlockResult->Block_No;
				}
			}
			

		?>
			
        <div class="ticket">
			<div align="center">
				<img style="height:90px;width:90px;" src="<?php echo ASSETS_WEB_PATH;?>fimg/cpaLogo.png">
				<p style="margin-top:-3px;">www.cpatos.gov.bd</p>
			</div>
            <p class="centered">Vehicle Pass (1 day)</p>
            
			<table>
                    <tr>
                        <td><b>Visit Id </b>: <?php echo $visit_id; ?></td>
                    </tr>
					<tr>
                        <td><b>Cont No. </b>: <?php echo $cont_no; ?></td>
                    </tr>
					<tr>
                        <td><b>C&F Name. </b>: <?php echo $cnfName; ?></td>
                    </tr>
					<tr>
                        <td><b>Truck No. </b>: <?php echo $truck_id; ?></td>
                    </tr>
					<tr>
                        <td><b>Gate No. </b>: <?php echo $gate_no; ?></td>
                    </tr>
					<tr>
                        <td><b>Block No. </b>: <?php echo $blockNo; ?></td>
                    </tr>
                    <tr>
                        <td><b>Driver : </b><?php echo $driver_name; ?></td>
                    </tr>
                    <tr>
                        <td><b>Driver Pass: </b><?php echo $driver_gate_pass; ?></td>
                    </tr>
                    <tr>
                        <td><b>Helper : </b><?php echo $assistant_name; ?></td>
                    </tr>
                    <tr>
                        <td><b>Helper Pass : </b><?php echo $assistant_gate_pass; ?></td>
                    </tr>
					<tr>
                        <td><b>Transport Agency : </b><?php echo $truck_agency_name; ?></td>
                    </tr>
            </table>
			<br/>

			<?php
				require_once 'phpqrcode/qrlib.php';
				$path_folder = "http://".$_SERVER['SERVER_NAME'].":".$_SERVER['SERVER_PORT']."/assets/images/qrcode/";
				$destination_folder = $_SERVER['DOCUMENT_ROOT']."/assets/images/qrcode/";
				$rot = $rot_no;
				$cont = $cont_no;		
				$file = $trucVisitId.".png";
				$file1 = $destination_folder.$file;
				$path = $path_folder.$file;
				$text =$trucVisitId;
				QRcode::png($text, $file1, 'L', 10, 2);			
			?>
			<table style="border-top:none;">
				<tr style="border-top:none;">
					<td align="center" style="border-top:none;">
						<img src="<?php echo $path;?>" height="70px" width="70px" style="padding-left:50px;">
					</td>
				</tr>
			</table>

			<!-- <table style="border-top:none;">
				<tr style="border-top:none;">
					<td align="center">
						<img src="<?php //echo $barcode; ?>">
					</td>
				</tr>
			</table> -->



			<br/>
			<table>
				<tr>
					<td align="center">Issued from <?php if($collect_gate_no!=''){echo $collect_gate_no;}else{ echo "3,4,5"; } ?> at <b><?php echo $paid_collect_dt ?></b></td>
				</tr>
				<tr>
					<td align="center">Valid Till: <b><?php echo $validDate = date('Y-m-d H:i:s', strtotime($paid_collect_dt . ' +1 day')); ?></b></td>
				</tr>
				<tr>
					<td align="center">Printed By: <?php echo $this->session->userdata('login_id');?></td>
				</tr>

				<tr>
					<td align="center">&nbsp;&nbsp;</td>
				</tr>
				<tr>
					<td align="center">&nbsp;&nbsp;</td>
				</tr>

			</table>

			
        </div>
		<div style="width:25%;padding-left:42%;">
			<div style="float:left">
				<button id="btnPrint" class="button" >Print</button>
			</div>
			<div style="float:right">
			<form action="http://192.168.3.30:8095/tosprint.php" method="post">
				<input type="hidden" name="VISITNO" value="<?php echo $visit_id; ?>">
				<input type="hidden" name="VHTYPE" value="Truck">
				<input type="hidden" name="FEE" value="57.50">
				<input type="hidden" name="VHLP" value="<?php echo $truck_id; ?>">
				<input type="hidden" name="DRIVERNAME" value="<?php echo $driver_name; ?>">
				<input type="hidden" name="DRIVERCARDNO" value="<?php echo $driver_gate_pass; ?>">
				<input type="hidden" name="HELPERNAME" value="<?php echo $assistant_name; ?>">
				<input type="hidden" name="HELPERCARDNO" value="<?php echo $assistant_gate_pass; ?>">
				<input type="hidden" name="AGENCYNAME" value="<?php echo $cnfName; ?>">
				<input type="hidden" name="AGENCYTYPE" value="C&F Agent">
				<input type="hidden" name="AGENCYCODE" value="<?php echo $cnfCode; ?>">
				<input type="hidden" name="VALIDTILL" value="<?php echo $validDate = date('Y-m-d H:i:s', strtotime($paid_collect_dt . ' +1 day')); ?>">
				<input type="hidden" name="USERNAME" value="<?php echo $login_id;?>">
				<input type="hidden" name="CONTAINER" value="<?php echo $cont_no; ?>">
				<input type="hidden" name="GATENAME" value="<?php echo $gate_no; ?>">
				<input type="hidden" name="BLOCKNO" value="<?php echo $blockNo; ?>">
				<input type="hidden" name="VEHICLEAGENCY" value="<?php echo $truck_agency_name; ?>">
				<input type="submit" value="Print to Biometric Printer" class="button">
			</form>
			</div>
		</div>
		
        <script>
			const $btnPrint = document.querySelector("#btnPrint");
			$btnPrint.addEventListener("click", () => {
				window.print();
			});

			<?php
				if($flag == 'security'){
			?>
				window.print();
			<?php
				}
			?>
		</script>
    </body>
</html>