<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
       
    </head>
    <body>

	<?php
			include("mydbPConnection.php");
			$truckQuery="SELECT do_truck_details_entry.id AS visit_id,do_truck_details_entry.import_rotation AS rotation,do_truck_details_entry.cont_no AS cont_no, verify_info_fcl_id,truck_id,verify_other_data_id,gate_no,
			driver_name,driver_gate_pass,assistant_name,assistant_gate_pass,visit_time_slot_start,visit_time_slot_end,paid_status,paid_method, paid_amt,paid_collect_dt,paid_collect_by,collect_gate_no,truck_agency_name,u_name,
			LEFT(do_truck_details_entry.update_by,9) AS agencyCode FROM do_truck_details_entry INNER JOIN users ON users.login_id = do_truck_details_entry.update_by 
			WHERE do_truck_details_entry.id='$trucVisitId'";

			$rsltTruck = mysqli_query($con_cchaportdb,$truckQuery);
			$visit_id = "";
			$rot_no = "";
			$cont_no = "";
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

			while($truckResult = mysqli_fetch_object($rsltTruck)){
				$visit_id = $truckResult->visit_id;
				$rot_no = $truckResult->rotation;
				$cont_no = $truckResult->cont_no;
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
			
			$jettySarkarQuery="";
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
				//die("Unable to connect. Please try again.");
			}
			$agentName = "";
			$agentCode = "";
			if($jettySarkarQuery !=""){
				$rsltJetty = mysqli_query($con_cchaportdb,$jettySarkarQuery);
				while($jettyInfo = mysqli_fetch_object($rsltJetty)){
					$agentName = $jettyInfo->agent_name;
					$agentCode = $jettyInfo->agent_code;
				}
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
			INNER JOIN sparcsn4.vsl_vessel_visit_details ON sparcsn4.vsl_vessel_visit_details.ib_vyg=b.flex_string10
			LEFT JOIN sparcsn4.ref_bizunit_scoped k ON k.gkey = j.consignee_bzu
			INNER JOIN sparcsn4.config_metafield_lov ON a.flex_string01 = config_metafield_lov.mfdch_value
			WHERE a.id='$cont_no' AND sparcsn4.vsl_vessel_visit_details.ib_vyg ='$rot_no'";

			$rsltCnf = mysqli_query($con_sparcsn4,$cnfQuery);
			$cnf="";
			$cnf_addr = "";
			$cnf_lic = "";
			$assignmentType="";
			while($cnfResult=mysqli_fetch_object($rsltCnf)){
				$cnf = $cnfResult->cnf;
				$cnf_addr = $cnfResult->cnf_addr;
				$cnf_lic = $cnfResult->cnf_lic;
				$assignmentType = $cnfResult->mfdch_value;
			}

			//$BlockQuery = "SELECT DISTINCT Block_No FROM ctmsmis.tmp_vcms_assignment WHERE cont_no = '$cont_no' ORDER BY flex_date01 LIMIT 1";
			$BlockQuery = "SELECT DISTINCT Block_No FROM ctmsmis.tmp_vcms_assignment 
			WHERE cont_no = '$cont_no' AND cf_lic='$cnf_lic' AND cf_lic!='' AND assignmentDate>=DATE(NOW())
			AND mfdch_value NOT IN('CANCEL','APPREF','APPCUS','APPOTH','--')
			ORDER BY flex_date01 LIMIT 1";

			$rsltBlock = mysqli_query($con_sparcsn4,$BlockQuery);

			$blockNo="";
			while($BlockResult=mysqli_fetch_object($rsltBlock)){
				$blockNo = $BlockResult->Block_No;
			}

		?>
			
        <div class="ticket">
			<div align="center">
				<img style="height:90px;width:90px;" src="<?php echo ASSETS_WEB_PATH;?>fimg/cpaLogo.png">
				<p style="margin-top:-3px; font-size: 20px;" align="center">www.cpatos.gov.bd</p>
			</div>
            <p align="center" style="margin-top:-5px; font-size: 20px;" >Vehicle Pass (1 day)</p>
            
			<table align="center"  style="font-size: 20px;" >
                    <tr>
                        <td><b>Visit Id </b>: <?php echo $visit_id; ?></td>
                    </tr>
					<tr>
                        <td><b>Cont No. </b>: <?php echo $cont_no; ?></td>
                    </tr>
					<tr>
                        <td><b>C&F Name. </b>: <?php echo $cnf; ?></td>
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
                        <td><b>Transport Agency : <?php echo $truck_agency_name; ?></b></td>
                    </tr>
            </table>
			<br/>

			<?php
				require_once 'phpqrcode/qrlib.php';
				$destination_folder = $_SERVER['DOCUMENT_ROOT']."/pcs/assets/images/qrcode/";
				$rot = $rot_no;
				$cont = $cont_no;		
				$file = $trucVisitId.".png";
				$file1 = $destination_folder.$file;
				$path = IMG_PATH."qrcode/".$file;
				$text =$trucVisitId;
				QRcode::png($text, $file1, 'L', 10, 2);		
			?>
			<table align="center" style="border-top:none; margin-top:-8px;" >
				<tr style="border-top:none;">
					<td align="center" style="border-top:none;">
						<img src="<?php echo $path;?>" height="380px" width="380px">
					</td>
				</tr>
			</table>

			<br/>
			<table  align="center" style="font-size: 20px;" >
				<tr>
					<td align="center">Issued from <?php if($collect_gate_no!=''){echo $collect_gate_no;}else{ echo "3,4,5"; } ?> at <b><?php echo $paid_collect_dt ?></b></td>
				</tr>
				<tr>
					<td align="center">Valid Till: <b><?php echo $validDate = date('Y-m-d H:i:s', strtotime($paid_collect_dt . ' +1 day')); ?></b></td>
				</tr>
				<tr>
					<td align="center">Printed By: <b><?php echo $this->session->userdata('User_Name');?></b></td>
				</tr>

			

			</table>

			
        </div>
	</body>
</html>