<html>
	<body>
        <?php
			$visitId = "";
			for($i=0;$i<count($resVisitId);$i++)
			{
				$visitId = $resVisitId[$i]['id'];
				$CNFStr1="SELECT distinct(ref_bizunit_scoped.name) as name, address_line1
						FROM inv_unit 
						INNER JOIN inv_goods ON inv_goods.gkey=inv_unit.goods
						LEFT JOIN ref_bizunit_scoped ON ref_bizunit_scoped.gkey=inv_goods.consignee_bzu
						WHERE ref_bizunit_scoped.id = '$CNFLicenceNo'";
				$CNFresult = $this->bm->dataSelect($CNFStr1);
				
				$queryStr="SELECT do_truck_details_entry.truck_id,do_truck_details_entry.actual_delv_pack,
				do_truck_details_entry.actual_delv_unit,do_truck_details_entry.gate_no,do_truck_details_entry.cont_no,do_truck_details_entry.import_rotation,
				verify_info_fcl.igm_detail_cont_id,
				verify_info_fcl.igm_detail_id,igm_details.Description_of_Goods,
				igm_details.Notify_name,igm_details.Notify_address
				FROM do_truck_details_entry
				INNER JOIN verify_info_fcl ON do_truck_details_entry.verify_info_fcl_id=verify_info_fcl.id
				INNER JOIN igm_details ON igm_details.id=verify_info_fcl.igm_detail_id
				WHERE do_truck_details_entry.id='$visitId'";

				$resQuery = $this->bm->dataSelectDb1($queryStr);

				if(count($resQuery) == 0){
					$queryStr="SELECT do_truck_details_entry.truck_id,do_truck_details_entry.actual_delv_pack,
					do_truck_details_entry.actual_delv_unit,do_truck_details_entry.gate_no,do_truck_details_entry.cont_no,do_truck_details_entry.import_rotation,
					Description_of_Goods,
					Notify_name,Notify_address
					FROM do_truck_details_entry
					INNER JOIN lcl_dlv_assignment ON do_truck_details_entry.verify_other_data_id=lcl_dlv_assignment.id
					INNER JOIN igm_supplimentary_detail ON igm_supplimentary_detail.id=lcl_dlv_assignment.igm_sup_dtl_id
					WHERE do_truck_details_entry.id='$visitId'";
					$resQuery = $this->bm->dataSelectDb1($queryStr);
				}
			
			//return;
			if($i!=0){
		?>
			<pagebreak />
		<?php
			}
        ?>	
			<table align="center" width="80%" style="font-size:12px">				
				<tr align="center">
					<td  align="center"><img align="middle" src="<?php echo IMG_PATH?>cpanew.jpg"></td>
				</tr>
				<tr align="center">
					<th align="center"><b><font size=6><b>Invoice / Challan</b></font></b></td>
				</tr>
				<tr align="center">
					<th align="center"><b><font size=4>Visit ID : <?php echo $visitId;?></font></b></th>
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
					<th> Name </th>
					<td><?php echo @$CNFresult[0]['name'];?></td>	   
				</tr>
				<tr>
					<th> Address</th>
					<td><?php echo @$CNFresult[0]['address_line1'];?></td>
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
			<table  align="center" width=80% border="1" style="font-size:12px; border-collapse: collapse;" > 
				<thead style="">
					<tr >		
						<th align="center" >TRUCK NO</th>
						<th align="center" >DESCRIPTION OF GOODS</th>
						<th align="center" >QUANTITY</th>
						<th align="center" >REMARKS</th>						
					</tr>
				</thead>
				<tbody>
					<tr> 
					  <td align="center"> <?php echo $resQuery[0]['truck_id'];?> </td>
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
                                echo $descOfGoods;
                            else
                                echo 	$description;
						?>

					  </td>
					  <td align="center"> <?php echo $resQuery[0]['actual_delv_pack'];?> </td>
					  <td align="center"></td>
					</tr>
				</tbody>
			</table>
			<?php
				require_once 'phpqrcode/qrlib.php';
				$destination_folder = $_SERVER['DOCUMENT_ROOT']."/pcs/assets/images/qrcode/";		
				$file = $visitId.".png";
				$file1 = $destination_folder.$file;
				$path = IMG_PATH."qrcode/".$file;
				$text =$visitId;
				QRcode::png($text, $file1, 'L', 10, 2);		
			?>
			<table border="0" width="60%" align="center" style="margin-top:20px;">
				<tr>					
					<td align="left">
						<img src="<?php echo $path;?>" height="100" width="100">
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
					<!--td align="center">
						<?php // echo "<img size='100px' height='100px' src='".<?php echo $photobase64; ?>."' />"
						?>						
					</td-->					
				</tr>
			</table>
			
			<div style="position:absolute;bottom:25px;right:50px;width:20%;text-align:right">
				<?php  echo "Print Time: ".date("Y-m-d h:i:s");?>
			</div>
	<?php
		}
		mysqli_close($con_cchaportdb);
	?>
	</body>
</html>