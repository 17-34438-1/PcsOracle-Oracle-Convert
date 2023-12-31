
<html>
<head>
		<link href="//fonts.googleapis.com/css?family=Open+Sans:300,400,600,700,800" rel="stylesheet" type="text/css">

		<!-- Invoice Print Style -->
		<link rel="stylesheet" href="<?php echo ASSETS_PATH;?>stylesheets/invoice-print.css" />
		<table align="center" width="98%">
			<tr>
				<td align="center"><font size="5"><b>CHITTAGONG PORT AUTHORITY</b></font></td>
			</tr>
			<tr>
				<td align="center"><b>VAT Reg:2041001546</b></td>
			</tr>
			<tr>
				<td align="center"><b><?php echo $invoiceDesc;?></b></td>
			</tr>
			<?php
			/* if($version!="")
			{ */
			?>
			<!--tr>
				<td align="center">Version:&nbsp;<?php // echo $version; ?></td>
			</tr-->
			<?php
			//}
			?>
		</table>	
		<!--div align="center">(LCL BILL)</div-->

</head>
<body>	
		<div align ="center">
			<table align="center">

				<tr>
					<td align="left">Bill No</td>
					<td align="left">: &nbsp;&nbsp;<?php echo $draftNumber;?></td>
					<td align="left"></td>
					<td align="left">Rotation No</td>
					<td align="left">: &nbsp;&nbsp;<?php echo $ibVisitId;?></td>
					<td align="left"></td>
					<td align="left">Date </td>
					<td align="left">: &nbsp;&nbsp;<?php echo $created;?></td>
					<td align="left"></td>
					
				</tr>
				<tr>
					<td align="left">Vessel</td>
					<td align="left">:&nbsp;&nbsp;<?php echo $ibCarrierName;?></td>
					<td align="left"></td>
					<td align="left">MLO</td>
					<td align="left">:&nbsp;&nbsp;<?php echo $customerId;?></td>
					<td align="left"></td>
					<td align="left">Agent </td>
					<td align="left">: &nbsp;&nbsp;<?php echo $concustomerid;?></td>
					<td align="left"></td>
					
				</tr>
				<tr>
					<td align="left">MLO Name </td>
                                        <td  colspan="2" align="left">:&nbsp;&nbsp;<?php echo $customerName;?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
					<!--<td align="left"></td>-->
                                        <td align="left">Agent Name  :</td>
                                        <td colspan="5" align="left"> &nbsp;&nbsp;<?php echo $concustomername;?></td>
<!--					<td align="left"></td>
					<td align="left"></td>
					<td align="left"></td>
                                        <td align="left"></td>-->
				</tr>
				
			</table>	
			<table align="center" width="98%">
				<tr>
					<td colspan="12">
						<hr style="margin:3px; border-top:1px dotted; color:black;"/>
					</td>
				</tr>
				<tr>
					<td align="center" style="width:50px" >SL</td>
					<td align="center">CONTAINER NO</td>
					<td align="center" style="width:30px">SIZE</td>
					<td align="center">HEIGHT</td>
					<td align="center">STATUS</td>
					<td align="center">IMT ROT</td>
					<!--<td align="center">IMT ATA</td>-->
					<td align="center">LANDING DATE</td>
					<td align="center">ISSUE</td>
					<td  align="center">DATE OF LETTER</td>
                                        <td align="center">From Depo</td>
					<td align="center">VAT(%)</td>
					<td align="center">Days</td>
				</tr>
				<tr>
					<td colspan="12">
						<hr style="margin:3px; border-top:1px dotted; color:black;"/>
					</td>
				</tr>
					
				<?php
				
				for($i=0; $i<count($rslt_detail); $i++) {?>
				
				<tr>
					<td align="center"><?php echo $i+1;?></td>
					<td align="center" ><?php echo $rslt_detail[$i]['unitId'] ?></td>
					<td align="center"><?php echo $rslt_detail[$i]['isoLength'];?></td>
					<td align="center"><?php echo $rslt_detail[$i]['isoHeight'];?></td>
					<td align="center"><?php echo $rslt_detail[$i]['freightKind'];?></td>
					<td align="center"><?php echo $rslt_detail[$i]['imp_rot'];?></td>
					<!--<td align="center"><?php echo $rslt_detail[$i]['imp_ata'];?></td>-->
					<td align="center"><?php echo $rslt_detail[$i]['imp_ata'];?></td>
					<td align="center"><?php echo $rslt_detail[$i]['depo_date'];?></td>
					<td align="center"><?php echo $rslt_detail[$i]['timeIn'];?></td>
					<td align="center"><?php echo $rslt_detail[$i]['depo_date'];?></td>
                                        <td align="center"><?php echo $rslt_detail[$i]['vatperc'];?></td>
					<td align="center"><?php echo $rslt_detail[$i]['days'];?></td>
				</tr>
				<?php  } ?>
				
			</table>	
			<table align="center" width="85%">	
				<tr>
					<td colspan="17">&nbsp;</td>
				</tr>
				<tr>
					<td colspan="2" align="left"><u>Equipment</u></td>
					<td align="center"></td>
					<td align="center"></td>
					<td align="center"></td>
					<td align="center"></td>
					<td colspan="2" align="right"><u>Vat</u></td>
					<td align="right"></td>
					<td align="right"></td>
					<td align="right"></td>
					<td align="right"></td>
					<td align="right"></td>
					<td align="center" style="width:90px"><u>Lift On / Lift Off:</u></td>
					<td align="right"></td>
					<td align="right"></td>
										<td align="right"></td>
					<td align="right"></td>
				</tr>
				<tr>
					<td style="border: 1px solid black; border-collapse: collapse; " align="left">Wholly:</td>
					<td style="border: 1px solid black; border-collapse: collapse; width:30px" align="center"><?php echo $equipmentW;?></td>
					<td style="border: 1px solid black; border-collapse: collapse;" align="left">No:</td>
					<td style="border: 1px solid black; border-collapse: collapse; width:30px" align="center" ><?php echo $equipmentN;?></td>
					<td style="border: 1px solid black; border-collapse: collapse;" align="center">Partly:</td>
					<td style="border: 1px solid black; border-collapse: collapse; width:30px" align="center"><?php echo $equipmentP;?></td>
					<td align="right" style="width:25px"></td>
					<td style="border: 1px solid black; border-collapse: collapse;" align="left">Vat:</td>
					<td style="border: 1px solid black; border-collapse: collapse; width:30px" align="center"><?php echo $vat;?></td>
					<td style="border: 1px solid black; border-collapse: collapse;" align="left">Non Vat:</td>
					<td style="border: 1px solid black; border-collapse: collapse; width:30px" align="center"><?php echo $nonvat;?></td>
					<td align="right" style="width:120px"></td>
					<td style="border: 1px solid black; border-collapse: collapse; width:40px" align="center">Yes:</td>
					<td style="border: 1px solid black; border-collapse: collapse; width:40px" align="center"><?php echo $lon;?></td>
					<td style="border: 1px solid black; border-collapse: collapse; width:40px" align="center">No:</td>
					<td style="border: 1px solid black; border-collapse: collapse; width:40px" align="center"><?php echo $nlon;?></td>
					<td align="right"></td>
				</tr>
				<tr>
					<td colspan="17">&nbsp;</td>
				</tr>
				<tr>
					<td colspan="17">&nbsp;</td>
				</tr>
			</table>
			<table align="center" width="85%">
				<tr>
					<td align="center"><u>Summary</u></td>
					<td align="center"><u>20 X 8.5</u></td>
					<td align="center"><u>20 X 9.5</u></td>
					<td align="center"><u>40 X 8.5</u></td>
					<td align="center"><u>40 X 9.5</u></td>
					<td align="center"><u>45 X 8.5</u></td>
					<td align="center"><u>45 X 9.5</u></td>
					<td align="center" style=" border-left: 1px solid black;"></td>
					<td align="center"><u>Total</u></td>
				</tr>
				<tr>
					<td align="center">FCL - </td>
					<td align="center"><?php echo $fcl_20_85;?></td>
					<td align="center"><?php echo $fcl_20_95;?></td>
					<td align="center"><?php echo $fcl_40_85;?></td>
					<td align="center"><?php echo $fcl_40_95;?></td>
					<td align="center"><?php echo $fcl_45_85;?></td>
					<td align="center"><?php echo $fcl_45_95;?></td>
					<td align="center" style=" border-left: 1px solid black;"></td>
					<td align="center"><?php echo $fcl;?></td>
				</tr>
				<tr>
					<td align="center">LCL - </td>
					<td align="center"><?php echo $lcl_20_85;?></td>
					<td align="center"><?php echo $lcl_20_95;?></td>
					<td align="center"><?php echo $lcl_40_85;?></td>
					<td align="center"><?php echo $lcl_40_95;?></td>
					<td align="center"><?php echo $lcl_45_85;?></td>
					<td align="center"><?php echo $lcl_45_95;?></td>
					<td align="center" style=" border-left: 1px solid black;"></td>					
					<td align="center"><?php echo $lcl;?></td>
				</tr>
				<tr>
					<td align="center">EMT - </td>
					<td align="center"><?php echo $mty_20_85;?></td>
					<td align="center"><?php echo $mty_20_95;?></td>
					<td align="center"><?php echo $mty_40_85;?></td>
					<td align="center"><?php echo $mty_40_95;?></td>
					<td align="center"><?php echo $mty_45_85;?></td>
					<td align="center"><?php echo $mty_45_95;?></td>
					<td align="center" style=" border-left: 1px solid black;"></td>					
					<td align="center"><?php echo $mty;?></td>
				</tr>
				<tr>
				    <td colspan="9"><hr style="border-top:1px dotted; color:black;"/></td>
				</tr>
				<tr>
					<td align="center"> </td>
					<td align="center"><?php echo $tot_20_85;?></td>
					<td align="center"><?php echo $tot_20_95;?></td>
					<td align="center"><?php echo $tot_40_85;?></td>
					<td align="center"><?php echo $tot_40_95;?></td>
					<td align="center"><?php echo $tot_45_85;?></td>
					<td align="center"><?php echo $tot_45_95;?></td>
					<td align="center" style=" border-left: 1px solid black;"></td>					
					<td align="center"><?php echo $tot;?></td>
				</tr>
			</table>
			
			<table align="center" width="90%">
				<tr>
					<td colspan="6">&nbsp;</td>
				</tr><tr>
					<td colspan="6">&nbsp;</td>
				</tr><tr>
					<td colspan="6">&nbsp;</td>
				</tr><tr>
					<td colspan="6">&nbsp;</td>
				</tr>
				<tr>
					<td colspan="6">Print Date : &nbsp;&nbsp; <?php echo $time; ?></td>
				</tr>
			</table>			
		</div>
	
</body>
<!--<script>
		window.print();
</script>-->
</html>
