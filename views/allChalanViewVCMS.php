<html>
	<body>
        <?php
			$visitId = "";
			for($i=0;$i<count($resVisitId);$i++)
			{
				 $visitId = $resVisitId[$i]['id'];
				$CNFStr1="SELECT distinct(ref_bizunit_scoped.name) as name, address_line1,ref_bizunit_scoped.sms_number
						FROM inv_unit 
						INNER JOIN inv_goods ON inv_goods.gkey=inv_unit.goods
						LEFT JOIN ref_bizunit_scoped ON ref_bizunit_scoped.gkey=inv_goods.consignee_bzu
						WHERE ref_bizunit_scoped.id = '$CNFLicenceNo'";
				      $CNFresult = $this->bm->dataSelect($CNFStr1);
				
				
				
				$queryStr="SELECT do_truck_details_entry.truck_id,do_truck_details_entry.actual_delv_pack,
				Pack_Unit,do_truck_details_entry.gate_no,do_truck_details_entry.cont_no,do_truck_details_entry.import_rotation,
				verify_info_fcl.igm_detail_cont_id,
				verify_info_fcl.igm_detail_id,SUBSTR(igm_details.Description_of_Goods,1,100) AS Description_of_Goods,
				igm_details.Notify_name,igm_details.Notify_address
				FROM do_truck_details_entry
				INNER JOIN verify_info_fcl ON do_truck_details_entry.verify_info_fcl_id=verify_info_fcl.id
				INNER JOIN igm_details ON igm_details.id=verify_info_fcl.igm_detail_id
				INNER JOIN igm_pack_unit ON igm_pack_unit.id = do_truck_details_entry.actual_delv_unit
				WHERE do_truck_details_entry.id='$visitId'";

				$resQuery = $this->bm->dataSelectDb1($queryStr);
				
				if(count($resQuery) == 0){
					$queryStr="SELECT do_truck_details_entry.truck_id,do_truck_details_entry.actual_delv_pack,
					Pack_Unit,do_truck_details_entry.gate_no,do_truck_details_entry.cont_no,do_truck_details_entry.import_rotation,
					Description_of_Goods,
					Notify_name,Notify_address
					FROM do_truck_details_entry
					INNER JOIN lcl_dlv_assignment ON do_truck_details_entry.verify_other_data_id=lcl_dlv_assignment.id
					INNER JOIN igm_supplimentary_detail ON igm_supplimentary_detail.id=lcl_dlv_assignment.igm_sup_dtl_id
					INNER JOIN igm_pack_unit ON igm_pack_unit.id = do_truck_details_entry.actual_delv_unit
					WHERE do_truck_details_entry.id='$visitId'";
					$resQuery = $this->bm->dataSelectDb1($queryStr);
				}

				if(count($resQuery) == 0){
					$queryStr="SELECT lcl_dlv_assignment.igm_sup_dtl_id,do_truck_details_entry.truck_id,do_truck_details_entry.actual_delv_pack,
					Pack_Unit,do_truck_details_entry.gate_no,do_truck_details_entry.cont_no,do_truck_details_entry.import_rotation,
					SUBSTR(Description_of_Goods, 1, 100) AS Description_of_Goods,
					Notify_name,Notify_address
					FROM do_truck_details_entry
					INNER JOIN lcl_dlv_assignment ON do_truck_details_entry.verify_other_data_id=lcl_dlv_assignment.id
					INNER JOIN igm_details ON igm_details.id=lcl_dlv_assignment.igm_sup_dtl_id
					INNER JOIN igm_pack_unit ON igm_pack_unit.id = do_truck_details_entry.actual_delv_unit
					WHERE do_truck_details_entry.id='$visitId'";
					$resQuery = $this->bm->dataSelectDb1($queryStr);
				}
				
			
			//return;
			if($i!=0){
		?>
			<pagebreak />
		<?php
			}
			require_once 'phpqrcode/qrlib.php';
			//$destination_folder = $_SERVER['DOCUMENT_ROOT']."/pcs/assets/images/qrcode/";		
			$destination_folder = $_SERVER['DOCUMENT_ROOT']."/assets/images/qrcode/";	
			$file = $visitId.".png";
			$file1 = $destination_folder.$file;
			//$path = IMG_PATH."qrcode/".$file;
			$text =$visitId;
			QRcode::png($text, $file1, 'L', 10, 2);
        ?>	

			<table align="center" width="80%" style="font-size:12px">				
				<tr align="center">
					<td align="center">
						<img src="<?php echo $file1;?>" height="100" width="100">
					</td>

					<td align="center">
						<img align="middle" src="<?php echo IMG_PATH?>cpanew.jpg">
						<p style="margin-top:-3px;">www.cpatos.gov.bd</p>
					</td>

					<td align="center">
						<?php			
							$text =$visitId;						
							$barcodeText = $text;
						?>
							<barcode code="<?php echo $barcodeText; ?>" type="C128A" size="0.6" height="2" />
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
					<th colspan="3" align="center"><b><font size=5><?php echo @$CNFresult[0]['NAME'];?></font></b></th>
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
					<td><?php echo @$CNFresult[0]['ADDRESS_LINE1'];?></td>	   
				</tr>
				<tr>
					<th> Phone</th>
					<td><?php echo @$CNFresult[0]['SMS_NUMBER'];?></td>
				</tr>
				<tr>
					<th rowspan="2"> Importer Detail</th>
					<th> Name </th>
					<td><?php echo $resQuery[0]['Notify_name'];?></td>
				</tr>
				<tr>		  
					<th> Address</th>
					<td><?php echo $resQuery[0]['Notify_address'];?></td>
				</tr>		 
			</table>			
			<table  align="center" width=80% border="1" style="font-size:10px; border-collapse: collapse;" > 
				<thead style="">
					<tr >		
						<th align="center" >TRUCK NO</th>
						<th align="center" >DESCRIPTION OF GOODS</th>
						<th align="center" >QUANTITY</th>
						<th align="center">UNIT</th>
						<th align="center" >REMARKS</th>						
					</tr>
				</thead>
				<tbody>
					<tr> 
					  <td align="center"><p style="font-family:ind_bn_1_001; font-size:13px"><b><?php echo $challanTruck = $resQuery[0]['truck_id'];?></b></p></td>
					  <td align="left"> 
					  <?php 
                            include("mydbPConnection.php");
						  	$description = $resQuery[0]['Description_of_Goods'];
							$cont = $resQuery[0]['cont_no'];
							$rot = $resQuery[0]['import_rotation'];

							$query = "SELECT SUBSTR(igm_supplimentary_detail.Description_of_Goods,1,100) AS Description_of_Goods
							FROM igm_supplimentary_detail
							INNER JOIN igm_sup_detail_container ON igm_sup_detail_container.igm_sup_detail_id=igm_supplimentary_detail.id
							WHERE igm_supplimentary_detail.Import_Rotation_No='$rot' AND igm_sup_detail_container.cont_number='$cont'";

							$rslt = $this->bm->dataSelectDb1($query);
							$descOfGoods = "";
							for($j=0;$j<count($rslt);$j++){
								$descOfGoods = $rslt[$j]['Description_of_Goods'];
							}
                            
                            if($j>0)
							echo substr($descOfGoods,0,100);
                            else
                                echo $description;
						?>

					  </td>
					  <td align="center"><b>
						<?php 
							// $sts = $resQuery[0]['add_truck_st'];
							$qty = @$resQuery[0]['actual_delv_pack'];
							//if($sts == 1){
								include("mydbPConnection.php");
								$packNumQuery = "SELECT SUM(pack_num) AS pack_num FROM do_truck_details_additional_cont WHERE truck_visit_id = '$visitId'";
								$packNumResult = mysqli_query($con_cchaportdb,$packNumQuery);
								$packNumRow = mysqli_fetch_object($packNumResult);
								$addQty = 0;
								if(count($packNumRow)>0){
									$addQty = $packNumRow->pack_num;
								}
								$qty+=$addQty;
							// }
							echo $qty;
							$totalQty = $qty;
						?>
						</b>
					  </td>
					  <td align="center"><?php echo $resQuery[0]['Pack_Unit']; ?></td>
					  <td align="center"></td>
					</tr>

					<!--     part BL starts Here      -->
					<?php
						$additionalBlQuery = "SELECT bl_no FROM do_truck_details_additional_bl_lcl WHERE truck_visit_id = '$visitId'";
						$additionalBlData = $this->bm->dataSelectDB1($additionalBlQuery);

						if(count($additionalBlData)>0){
							$partChallan_bl_no = "";
							for($a=0;$a<count($additionalBlData);$a++){
								$partChallan_bl_no = $additionalBlData[$a]['bl_no'];

								$ntsQueryChallan = "SELECT * FROM oracle_nts_data WHERE bl_no = '$partChallan_bl_no'";
								$ntsDataChallan = $this->bm->dataSelectDB1($ntsQueryChallan);

								$partChallan_rot_no = "";

								for($b=0;$b<count($ntsDataChallan);$b++)
								{
									$partChallan_rot_no = $ntsDataChallan[$b]['imp_rot_no'];
								}

								$partChallanQuery="SELECT SUBSTR(igm_supplimentary_detail.Description_of_Goods,1,100) AS Description_of_Goods
								FROM igm_sup_detail_container
								INNER JOIN igm_supplimentary_detail ON igm_sup_detail_container.igm_sup_detail_id=igm_supplimentary_detail.id
								WHERE igm_supplimentary_detail.BL_No='$partChallan_bl_no' AND igm_supplimentary_detail.Import_Rotation_No='$partChallan_rot_no'
								
								UNION
								
								SELECT SUBSTR(igm_details.Description_of_Goods,1,100) AS Description_of_Goods
								FROM igm_detail_container
								INNER JOIN igm_details ON igm_detail_container.igm_detail_id=igm_details.id
								WHERE igm_details.BL_No='$partChallan_bl_no' AND igm_details.Import_Rotation_No='$partChallan_rot_no'";
								$partChallanData = $this->bm->dataSelectDB1($partChallanQuery);

								$challandescOfGoods = "";
								for($data = 0; $data<count($partChallanData);$data++){
									$challandescOfGoods = $partChallanData[$data]['Description_of_Goods'];
								}

								$partChallan_qtyQuery = "SELECT igm_pack_unit.Pack_Unit AS pack_unit, pack_num FROM do_truck_details_additional_bl_lcl 
								INNER JOIN igm_pack_unit ON igm_pack_unit.id = do_truck_details_additional_bl_lcl.pack_unit
								WHERE bl_no = '$partChallan_bl_no'";
								$partChallan_qtyData = $this->bm->dataSelectDB1($partChallan_qtyQuery);

								$partChallan_qty = "";
								$partChallan_unit = "";
								for($data = 0; $data<count($partChallan_qtyData);$data++){
									$partChallan_qty = $partChallan_qtyData[$data]['pack_num'];
									$partChallan_unit = $partChallan_qtyData[$data]['pack_unit'];
								}

					?>
						
						<tr>
							<td align="center"><p style="font-family:ind_bn_1_001; font-size:13px" ><font size="13px"><b><?php echo $challanTruck;?></b></font></p></td>
							<td align="left"><?php echo $challandescOfGoods; ?></td>
							<td align="center"><b>
								<?php 
									echo $partChallan_qty; 
									$totalQty+=$partChallan_qty;
								?></b>
							</td>
							<td align="center"><?php echo $partChallan_unit; ?></td>
							<td align="center"></td>
						</tr>

					<?php
							}
						}

						if(count($additionalBlData)>0){
					?>
						<tr>
							<td align="right" colspan="2"><font size='5'><b>Total: </b></font></td>
							<td align="center"><font size='5'><b><?php echo $totalQty; ?></b></font></td>
							<td></td>
							<td></td>
						</tr>
					<?php
						}
					?>
					<!--     part BL ends Here      -->
				</tbody>
			</table>

			<table align="center" width="80%" style="font-size:14px; margin-top:20px;">

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
				<?php  echo "Print Time: ".date("Y-m-d h:i:s");?>
			</div>
	<?php
		}
	?>
	</body>
</html>
