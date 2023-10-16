<html>

<body>
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
                <img src="<?php echo $path;?>" height="100" width="100">
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
            <th colspan="3" align="center"><b><font size=6><b>Invoice / Challan</b></font></b></td>
        </tr>
        <tr align="center">
            <th colspan="3" align="center"><b><font size=4>Visit ID : <?php echo $visitId;?></font></b></th>
        </tr>
    </table>
    <!--table align="center" width="80%" style="font-size:12px">
				<tr style="border-bottom:1px solid black">
					<td><b><font size=3>Visit ID : <?php echo $visitId;?></font></b></td>
				</tr>			
			</table-->
    <table align="center" width="80%" border="1" style="font-size:12px;  border-collapse: collapse;">
        <tr>
            <th rowspan="3"> C&F Detail </th>
            <th> Name </th>
            <td><?php echo @$CNFresult[0]['name'];?></td>
        </tr>
        <tr>
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
            <td><?php echo $resQuery[0]['Notify_address'];?></td>
        </tr>
    </table>
    <table align="center" width=80% border="1" style="font-size:10px; border-collapse: collapse;">
        <thead style="">
            <tr>
                <th align="center">TRUCK NO</th>
                <th align="center">DESCRIPTION OF GOODS</th>
                <th align="center">QUANTITY</th>
                <th align="center">CONTAINER</th>
                <th align="center">REMARKS</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td align="center"><p style="font-family:ind_bn_1_001; font-size:13px" ><b> <?php echo $resQuery[0]['truck_id'];?> </b></p></td>
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
                <td align="center"><b> <?php 
                    // $sts = $resQuery[0]['add_truck_st'];
                    $qty = @$resQuery[0]['actual_delv_pack'];
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
                    
                ?> </b></td>
                <td align="center"><?php echo $cont; ?></td>
                <td align="center"></td>
            </tr>
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
	<?php mysqli_close($con_cchaportdb); ?>
</body>

</html>