<?php
include("mydbPConnection.php");
for($i=0;$i<$rtnTruckNumber[0]['no_of_truck'];$i++)
{

	if($contStatus=="LCL")
	{
		$sqlCartTicket="SELECT igm_detail_container.id,igm_detail_container.cont_height,igm_masters.Vessel_Name,igm_details.Import_Rotation_No,igm_detail_container.cont_number,
		igm_details.Pack_Number,igm_details.Pack_Description,igm_details.BL_No,igm_detail_container.cont_size,igm_detail_container.cont_status,
		igm_detail_container.cont_weight,IFNULL(do_information.verify_no,0) AS verify_number,
		shed_bill_master.cnf_agent AS cnf_name,
		shed_bill_master.be_no,
		shed_bill_master.be_date,
		do_information.custom_rel_order_num,
		LEFT(Description_of_Goods,30) AS Description_of_Goods,
		Pack_Marks_Number,Notify_name,
		truck_id,gate_no
		FROM  igm_details
		INNER JOIN igm_detail_container ON igm_detail_container.igm_detail_id=igm_details.id
		INNER JOIN igm_masters ON igm_details.IGM_id=igm_masters.id
		INNER JOIN verify_info_fcl ON verify_info_fcl.igm_detail_id=igm_details.id
		LEFT JOIN do_information ON do_information.verify_no=verify_info_fcl.verify_number
		LEFT JOIN shed_bill_master ON shed_bill_master.verify_no= do_information.verify_no
		WHERE do_information.verify_no='$verifyNo'";
	}
	else
	{
		// $sqlCartTicket="SELECT igm_detail_container.id,igm_detail_container.cont_height,igm_masters.Vessel_Name,igm_details.Import_Rotation_No,igm_detail_container.cont_number,
		// igm_details.Pack_Number,igm_details.Pack_Description,igm_details.BL_No,igm_detail_container.cont_size,igm_detail_container.cont_status,
		// igm_detail_container.cont_weight,IFNULL(do_truck_details_entry.verify_number,0) AS verify_number,
		// shed_bill_master.cnf_agent AS cnf_name,
		// shed_bill_master.be_no,
		// shed_bill_master.be_date,'' AS custom_rel_order_num,
		// LEFT(Description_of_Goods,30) AS Description_of_Goods,
		// Pack_Marks_Number,Notify_name,
		// truck_id,gate_no
		// FROM  igm_details
		// INNER JOIN igm_detail_container ON igm_detail_container.igm_detail_id=igm_details.id
		// INNER JOIN igm_masters ON igm_details.IGM_id=igm_masters.id
		// INNER JOIN verify_info_fcl ON verify_info_fcl.igm_detail_id=igm_details.id
		// LEFT JOIN do_truck_details_entry ON do_truck_details_entry.verify_number=verify_info_fcl.verify_number
		// LEFT JOIN shed_bill_master ON shed_bill_master.verify_no= do_truck_details_entry.verify_number
		// WHERE do_truck_details_entry.verify_number='$verifyNo'";
		
		$sqlCartTicket="SELECT igm_detail_container.id,igm_detail_container.cont_height,igm_masters.Vessel_Name,igm_details.Import_Rotation_No,igm_detail_container.cont_number,
		igm_details.Pack_Number,igm_details.Pack_Description,igm_details.BL_No,igm_detail_container.cont_size,igm_detail_container.cont_status,
		igm_detail_container.cont_weight,IFNULL(do_truck_details_entry.verify_number,0) AS verify_number,
		shed_bill_master.cnf_agent AS cnf_name,
		shed_bill_master.be_no,
		shed_bill_master.be_date,
		'' AS custom_rel_order_num,
		LEFT(Description_of_Goods,30) AS Description_of_Goods,
		Pack_Marks_Number,Notify_name,
		truck_id,gate_no
		FROM  igm_details
		INNER JOIN igm_detail_container ON igm_detail_container.igm_detail_id=igm_details.id
		INNER JOIN igm_masters ON igm_details.IGM_id=igm_masters.id
		INNER JOIN verify_info_fcl ON verify_info_fcl.igm_detail_id=igm_details.id
		LEFT JOIN do_truck_details_entry ON do_truck_details_entry.verify_number=verify_info_fcl.verify_number
		LEFT JOIN shed_bill_master ON shed_bill_master.verify_no= do_truck_details_entry.verify_number
		WHERE do_truck_details_entry.verify_number='$verifyNo'";
	}

    $rtnCartTicket = $this->bm->dataSelectDb1($sqlCartTicket);
    $data['rtnCartTicket']=$rtnCartTicket;

    ?>
    <html>
    <head>

    </head>
    <body>
    <div class="cartPortrait">
        <?php
        if($i < count($rtnTruckNumber_2)-1)
        {
        ?>
        <div class="tblBreak">
            <?php
            }
            else
            {?>
            <div class="pageBreakOff">
                <?php
                }
                ?>

                <table width="100%" border="0" >
                    <tr align="center">
                        <!--td colspan="5" style="font-size:30px; font-weight: bold;">ORION INFUSION LTD.</td-->
                        <td colspan="5" align="center" style="font-size:20px; font-weight: bold;"><?php echo $rtnCartTicket[0]['cnf_name'];?></td>
                    </tr>
                    <tr align="center">
                        <td colspan="5" align="center" style="font-size:16px; font-weight: bold;">SELF C&F </td>
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
                            <p> CHITTAGONG PORT AUTHORITY </p>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="5" align="center" style="font-size:16px; font-weight: bold;"><u>CART TICKET</u></td>
                    </tr>
                    <tr>
                        <td width="70px" align="right">Rot No.&nbsp;&nbsp;&nbsp;</td>
                        <td style="border-bottom:1px solid black"><?php echo $rtnCartTicket[0]['Import_Rotation_No'];?></td>
                        <td>&nbsp;</td>
                        <td width="50">Job No.</td>
                        <td style="border-bottom:1px solid black"></td>
                    </tr>
                    <tr>
                        <td width="30px" align="right">Line No.&nbsp;&nbsp;&nbsp;</td>
                        <td style="border-bottom:1px solid black"><?php echo $rtnCartTicket[0]['BL_No'];?></td>
                    </tr>
                    <!--<tr>
                        <td>&nbsp;</td>
                    </tr>-->
                </table>

                <table border="0" width="100%">
                    <tr>
                        <td >Shed No.</td>
                        <td colspan="2" style="border-bottom:1px solid black; "><?php //echo $rtnCartTicket[0]['shed_yard'];?></td>
                        <td align="center" >Release Order No.</td>
                        <td colspan="2 "style="border-bottom:1px solid black;"><?php echo $rtnCartTicket[0]['custom_rel_order_num'];?></td>
                        <td align="center">Of</td>
                        <td style="border-bottom:1px solid black;"></td>
                    </tr>
                    <tr>
                        <td >Ex. S/S. M/V</td>
                        <td style="border-bottom:1px solid black;"><?php echo $rtnCartTicket[0]['Vessel_Name'];?></td>
                        <td align="center">No</td>
                        <td style="border-bottom:1px solid black;"></td>
                        <td align="center">Consignee:</td>
                        <td colspan="3" style="border-bottom:1px solid black;"><?php echo $rtnCartTicket[0]['cnf_name'];?></td>
                    </tr>
                    <tr>
                        <td>B/E No.</td>
                        <td style="border-bottom:1px solid black;"><?php echo $rtnCartTicket[0]['be_no'];?></td>
                        <td align="center">of</td>
                        <td style="border-bottom:1px solid black;"><?php echo $rtnCartTicket[0]['be_date'];?></td>
                        <td align="center">Truck No.</td>
                        <td style="border-bottom:1px solid black;"><?php echo $rtnTruckNumber_2[$i]['truck_id'];?></td>
                        <td align="center">Gate No.</td>
                        <td align="center" style="border-bottom:1px solid black;" ><?php echo $rtnTruckNumber_2[$i]['gate_no'];?></td>
                    </tr>
                    <!--tr>
                        <td>&nbsp;</td>
                    </tr-->
                </table>

                <table border="1" width="100%" align="center">
                    <tr>
                        <th style="font-size:11px;">Marks</th>
                        <th style="font-size:11px;">Description</th>
                        <th style="font-size:11px;">Number</th>
                        <th style="font-size:11px;">Tally</th>
                        <th style="font-size:11px;">Consecutive Carts Total</th>
                    </tr>
                    <tr align="center">
                        <td rowspan="2" width="30%" style="font-size:11px;"><?php echo $rtnCartTicket[0]['Pack_Marks_Number'];?></td>
                        <td rowspan="2" width="40%" style="font-size:11px;"><?php echo $rtnCartTicket[0]['Description_of_Goods'];?></td>
                        <td align="center" style="font-size:11px;"><?php echo $rtnTruckNumber_2[$i]['delv_pack'];?></td>
                        <td>&nbsp;</td>
                        <td align="center" style="font-size:11px;"><?php echo $rtnCartTicket[0]['Pack_Number'];?></td>
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
                        <td colspan="3" style="width:40%; font-size:11px;">For-<?php echo $rtnCartTicket[0]['cnf_name'];?></td>
                        <td colspan="2" style="width:40%; font-size:11px;" align="right">Checked and found ok</td>
                    </tr>
                    <tr>
                        <td colspan="8">&nbsp;</td>
                    </tr>
                    <tr>
                        <td style="font-size:11px;" align="right">Date</td>
                        <td style="border-bottom:1px solid black; width:15%; font-size:12px;"></td>
                        <td style="font-size:11px;">Consignee's Signature</td>
                        <td style="width:10%"></td>
                        <td style="font-size:11px;">Delivery Clerk</td>
                        <td style="width:10%"></td>
                        <td style="font-size:11px;">Gate Sargent</td>
                        <td style="width:10%"></td>
                    </tr>
                    <tr>
                        <td colspan="8" style="border-bottom:1px solid black; width:100%">&nbsp;</td>
                    </tr>
                    <tr>
                        <td colspan="8" style="font-size:11px;">N.B.: Loss of Cart Ticket must immediately be reported to the Shed Master of Shed Foreman. Unused Cart Ticket must be returned to the delivery Foreman on the same day they were issued</td>
                    </tr>
                    <tr>
                        <td colspan="8">&nbsp;</td>
                    </tr>
                </table>
                <?php //$mpdf->AddPage();?>
				<table>
					<tr>
						<td>							
							<?php								
								$barcodeText = $verifyNo;
							?>
							<barcode code="<?php echo $barcodeText; ?>" type="C128A" size="0.5" height="2" />							
						</td>
					</tr>
				</table>
            </div>
        </div>
    </body>
    </html>
    <?php
}
?>
