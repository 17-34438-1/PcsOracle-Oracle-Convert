<html>
	<head>
		
	</head>
	<body>
		
		<?php
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


			$truckQuery="SELECT truck_id,gate_no,driver_name,driver_gate_pass,assistant_name,assistant_gate_pass,visit_time_slot_start,visit_time_slot_end,paid_status, paid_method, paid_amt
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
			$cnf="";
			$cnf_addr = "";
			$cnf_lic = "";
			$Yard_No = "";
			$assignmentType="";
			while($cnfResult=mysqli_fetch_object($rsltCnf)){
				$cnf = $cnfResult->cnf;
				$cnf_addr = $cnfResult->cnf_addr;
				$cnf_lic = $cnfResult->cnf_lic;
				$Yard_No = $cnfResult->Yard_No;
				$assignmentType = $cnfResult->mfdch_value;
			}

		?>

		<h2 align="center"><?php echo $cnf; ?></h2>
		<h4 align="center"><?php echo $cnf_addr; ?></h4>
		<h4 align="center">C&F LICENSE NO: <?php echo $cnf_lic; ?></h4>
		<table border="0" align="center" width="90%" style="margin-bottom:0px;padding-bottom:0px;">
			<tr>
				<!-- <td colspan="3" align="left">To,</td> -->
				<td colspan="3" align="left">&nbsp;</td>
			</tr>
			<tr>
				<!-- <td align="left">Gate Sergeant</td> -->
				<td align="left">&nbsp;</td>
				<!-- <td align="center" style="font-size:16px;"><b><u>Gate Pass for Vehicle</u></b></td> -->
				<td align="right" style="font-size:16px;"><b><u>Gate Pass for Vehicle</u></b></td>
				<td align="right">Date: <?php echo DATE("Y-m-d"); ?></td>
			</tr>
			<!-- <tr>
				<td colspan="3" align="left">Sir,</td>
			</tr>
			<tr>
				<td colspan="3" align="left">You are requested to issue an entry pass for transportation of goods in the described TRUCK / LONG VEHICLE / COVERED VAN / TRAILER inside jetty.</td>
			</tr> -->
			<tr>
				<td colspan="3" width="100%">
					<table border="0" cellpadding="5" style="border:1px solid #000;" align="center" width="100%">
						<tr>
							<td align="right" width="100px;"><nobr>Truck Visit No.: </nobr></td>
							<th align="left"><b><nobr><?php echo $trucVisitId;?></nobr></b></th>
							<td align="right" >Assignment Type: </td>
							<td align="left"><b> <?php echo $assignmentType; ?> </b></td>
							<td align="right">From: </td>
							<th align="left" width="140px;"><b><?php echo $visit_time_slot_start; ?></b></th>
							<td align="right">To: </td>
							<th align="left" width="140px;"><b><?php echo $visit_time_slot_end; ?></b></th>
						</tr>
					</table>
				</td>
			</tr>
		</table>
		
		<table border="0" align="center" width="90%" cellpadding="5" style="margin-top:0px;padding-top:0px;margin-bottom:0px;padding-bottom:0px;">
			<tr>
				<th colspan="2" style="font-size:15px;" align="left"><u>Jetty Sarkar Information</u></th>
				<th rowspan="11"> &nbsp;</th>
				<th colspan="2" style="font-size:15px;" align="left"><u>Transport Agency Information</u></th>
			</tr>
			<tr>
				<td>1. Vessel :</td>
				<th align="left"><?php echo $Vessel_Name; ?></th>
				<td>1. Gate No :</td>
				<th align="left"><?php echo $gate_no; ?></th>
			</tr>
			
			<tr>
				<td>2. Rotation :</td>
				<th align="left"><?php echo $Import_Rotation_No; ?></th>
				<td>2. Truck No :</td>
				<th align="left"><?php echo $truck_id; ?></th>
			</tr>
			
			<tr>
				<td>3. B/E No :</td>
				<th align="left"><?php echo $beNo; ?></th>
				<td>3. Driver Name :</td>
				<th align="left"><?php echo $driver_name; ?></th>
			</tr>
			
			<tr>
				<td>4. Shed / Yard No :</td>
				<th align="left"><?php echo $Yard_No; ?></th>
				<td>4. ID Card (Driver) :</td>
				<th align="left"><?php echo $driver_gate_pass; ?></th>
				
			</tr>
			
			<tr>
				<td>5. Jetty Sarkar Name :</td>
				<th align="left">&nbsp;</th>
				<td>5. Union Membership No. (Driver) :</td>
				<th align="left">&nbsp;</th>
			</tr>
			
			<tr>
				<td>6. Jetty Sarkar Lic. No. :</td>
				<th align="left">&nbsp;</th>
				<td>6. Assistant Name :</td>
				<th align="left"><?php echo $assistant_name; ?></th>
			</tr>
			
			<tr>
				<td>7. Container No. :</td>
				<th align="left"><?php echo $cont_number; ?></th>
				<td>7. ID Card (Assistant) :</td>
				<th align="left"><?php echo $assistant_gate_pass; ?></th>
			</tr>

			<tr>
				<td rowspan="2">8. Goods Details :</td>
				<th align="left" rowspan="2"><?php echo substr($description_of_Goods,0,50);?></th>
				<td>8. Union Membership No. (Assistant) :</td>
				<th align="left">&nbsp;</th>
			</tr>
			
			<tr>
				<td>9. Transport Agency Name :</td>
				<th align="left">&nbsp;</th>
			</tr>
		</table>
		
		<table border="0" cellpadding="5" style="border:1px solid #000;margin-right:20px;" align="right" >
			<tr>
				<td align="right">Payment Mathod : </td>
				<th align="left"><b><?php echo ucfirst($method);?></b></th> 
			</tr>
			<tr>
			<td align="right">Payment Status : </td>
				<th align="left"><b><?php if($paid_status==1){echo "Paid";}else{ echo "Not Paid";} ?></b></th>
			</tr>
		</table>

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
		
		<table border="0" width="60%" align="center">
			<tr>
				
				<td align="left">

					<img src="<?php echo $path;?>" height="100" width="100">

				</td>
				<td align="right">

					<?php		
						$rot = $rot_no;
						$cont = $cont_no;		
						$text =$trucVisitId;						
						$barcodeText = $text;
					?>
					<barcode code="<?php echo $barcodeText; ?>" type="C128A" size="0.6" height="3" />
				</td>
				
			</tr>
			<tr>
				<td>
					&nbsp;
				</td>
				<td align="right" style="padding-right:25px;">
					<?php echo $text; ?>
				</td>
			</tr>
		</table>
		
	</body>
</html>