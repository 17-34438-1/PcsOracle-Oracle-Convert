
<html>
<head>
		<table align="center" width="95%">
			<tr>
				<td align="center"><font size="5"><b>CHITTAGONG PORT AUTHORITY</b></font></td>
			</tr>
			<tr>
				<td align="center"><b>VAT Reg:2041001546</b></td>
			</tr>
			<tr>
				<td align="center"><b><?php echo $rslt_detail[0]['invoiceDesc'];?></b></td>
			</tr>
			<?php
			if($version!="")
			{
			?>
			<tr>
				<td align="center">Version:&nbsp;<?php echo $version; ?></td>
			</tr>
			<?php
			}
			?>
		</table>	
		<!--div align="center">(LCL BILL)</div-->

</head>
<body>	
		<div align ="center">
			<table align="center">

				<tr>
					<td align="left" colspan="12">Int Ref No : <?php echo $rslt_detail[0]['draftNumber'];?></td>
				</tr>
				<tr>
					<td align="left">Agent </td>
					<td align="left">: &nbsp;&nbsp;<?php echo $rslt_detail[0]['concustomerid'];?></td>
					<td align="left"></td>
					<td align="left">Import MLO</td>
					<td align="left">:&nbsp;&nbsp;<?php echo $rslt_detail[0]['customerId'];?></td>
					<td align="left"></td>
					<td align="left">Rate</td>
					<td align="left">:&nbsp;&nbsp;<?php echo $rslt_detail[0]['exchangeRate'];?></td>
					<td align="left"></td>
					<td align="left">Date</td>
					<td align="left">:&nbsp;&nbsp;<?php echo $rslt_detail[0]['yardOutDate'];?></td>
					<td align="left" ></td>
					
				</tr>
				
			</table>	
			<table align="center" width="95%">
				
				<tr>
					<td colspan="12"></td>
					<td colspan="2">ICD</td>
					<td colspan="2">PAN</td>
					<td colspan="2">HANDOVER</td>
					<td colspan="5"></td>
				</tr>
				<tr>
					<td align="center" style="width:50px" >SL</td>
					<td align="center">CONTAINER NO</td>
					<td align="center" style="width:30px">SIZE</td>
					<td align="center">HGHT</td>
					<td align="center">STAT</td>
					<td align="center">ROT</td>
					<td align="center">TYPE</td>
					<!--<td align="center">IMT ATA</td>-->
					<td align="center">VESSEL NAME</td>
					<td align="center">BERTH</td>
					<td align="center">LAND DT</td>
					<td  align="center">DEL DT</td>
                    <td align="center">UnStuff DT</td>
                    <td align="center">To</td>
                    <td align="center">From</td>
					<td align="center">To</td>
                    <td align="center">From</td>
                    <td align="center">DT</td>
                    <td align="center">MLO</td>
                    <td align="center">To Depo</td>
                    <td align="center">Depo Name</td>
					<td align="center">Days</td>
				</tr>
				<tr>
					<td colspan="23">
						<hr style=" border-top:1px dotted; color:black;"/>
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
					<td align="center"><?php echo $rslt_detail[$i]['ibVisitId'];?></td>
					<td align="center"></td>
					<td align="center"><?php echo $rslt_detail[$i]['ibCarrierName'];?></td>
					<td align="center"><?php echo $rslt_detail[$i]['berth'];?></td>
					<td align="center"><?php echo $rslt_detail[$i]['timeIn'];?></td>
					<td align="center"><?php echo $rslt_detail[$i]['eventTo'];?></td>
                    <td align="center"><?php echo $rslt_detail[$i]['unstuffingDate'];?></td>					
                    <td align="center" colspan="2"><?php echo $rslt_detail[$i]['timeIn_icd'];?></td>
                    <td align="center" colspan="2"><?php echo $rslt_detail[$i]['timeIn_pan'];?></td>                  
                    <td align="center"></td>
                    <td align="center"></td> 
					<td align="center"><?php echo $rslt_detail[$i]['yardOutDate1'];?></td>
					<td align="center"><?php echo $rslt_detail[$i]['depoName'];?></td>
					<td align="center"><?php echo $rslt_detail[$i]['days'];?></td>
				</tr>
				<?php  } ?>
				
			</table>	
			<!--table align="center" width="85%">	
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
					<td style="border: 1px solid black; border-collapse: collapse; width:30px" align="center"><?php echo $summary_bill_detail[0]['equipmentW'];?></td>
					<td style="border: 1px solid black; border-collapse: collapse;" align="left">No:</td>
					<td style="border: 1px solid black; border-collapse: collapse; width:30px" align="center" ><?php echo $summary_bill_detail[0]['equipmentN'];?></td>
					<td style="border: 1px solid black; border-collapse: collapse;" align="center">Partly:</td>
					<td style="border: 1px solid black; border-collapse: collapse; width:30px" align="center"><?php echo $summary_bill_detail[0]['equipmentP'];?></td>
					<td align="right" style="width:25px"></td>
					<td style="border: 1px solid black; border-collapse: collapse;" align="left">Vat:</td>
					<td style="border: 1px solid black; border-collapse: collapse; width:30px" align="center"><?php echo $summary_bill_detail[0]['vat'];?></td>
					<td style="border: 1px solid black; border-collapse: collapse;" align="left">Non Vat:</td>
					<td style="border: 1px solid black; border-collapse: collapse; width:30px" align="center"><?php echo $summary_bill_detail[0]['nonvat'];?></td>
					<td align="right" style="width:120px"></td>
					<td style="border: 1px solid black; border-collapse: collapse; width:40px" align="center">Yes:</td>
					<td style="border: 1px solid black; border-collapse: collapse; width:40px" align="center"><?php echo $summary_bill_detail[0]['LON'];?></td>
					<td style="border: 1px solid black; border-collapse: collapse; width:40px" align="center">No:</td>
					<td style="border: 1px solid black; border-collapse: collapse; width:40px" align="center"><?php echo $summary_bill_detail[0]['NLON'];?></td>
					<td align="right"></td>
				</tr>
				<tr>
					<td colspan="17">&nbsp;</td>
				</tr>
				<tr>
					<td colspan="17">&nbsp;</td>
				</tr>
			</table-->
			<!--table align="center" width="85%">
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
					<td align="center"><?php echo $summary_bill_detail[0]['fcl_20_85'];?></td>
					<td align="center"><?php echo $summary_bill_detail[0]['fcl_20_95'];?></td>
					<td align="center"><?php echo $summary_bill_detail[0]['fcl_40_85'];?></td>
					<td align="center"><?php echo $summary_bill_detail[0]['fcl_40_95'];?></td>
					<td align="center"><?php echo $summary_bill_detail[0]['fcl_45_85'];?></td>
					<td align="center"><?php echo $summary_bill_detail[0]['fcl_45_95'];?></td>
					<td align="center" style=" border-left: 1px solid black;"></td>
					<td align="center"><?php echo $summary_bill_detail[0]['fcl'];?></td>
				</tr>
				<tr>
					<td align="center">LCL - </td>
					<td align="center"><?php echo $summary_bill_detail[0]['lcl_20_85'];?></td>
					<td align="center"><?php echo $summary_bill_detail[0]['lcl_20_95'];?></td>
					<td align="center"><?php echo $summary_bill_detail[0]['lcl_40_85'];?></td>
					<td align="center"><?php echo $summary_bill_detail[0]['lcl_40_95'];?></td>
					<td align="center"><?php echo $summary_bill_detail[0]['lcl_45_85'];?></td>
					<td align="center"><?php echo $summary_bill_detail[0]['lcl_45_95'];?></td>
					<td align="center" style=" border-left: 1px solid black;"></td>					
					<td align="center"><?php echo $summary_bill_detail[0]['lcl'];?></td>
				</tr>
				<tr>
					<td align="center">EMT - </td>
					<td align="center"><?php echo $summary_bill_detail[0]['mty_20_85'];?></td>
					<td align="center"><?php echo $summary_bill_detail[0]['mty_20_95'];?></td>
					<td align="center"><?php echo $summary_bill_detail[0]['mty_40_85'];?></td>
					<td align="center"><?php echo $summary_bill_detail[0]['mty_40_95'];?></td>
					<td align="center"><?php echo $summary_bill_detail[0]['mty_45_85'];?></td>
					<td align="center"><?php echo $summary_bill_detail[0]['mty_45_95'];?></td>
					<td align="center" style=" border-left: 1px solid black;"></td>					
					<td align="center"><?php echo $summary_bill_detail[0]['mty'];?></td>
				</tr>
				<tr>
				    <td colspan="9"><hr style="border-top:1px dotted; color:black;"/></td>
				</tr>
				<tr>
					<td align="center"> </td>
					<td align="center"><?php echo $summary_bill_detail[0]['tot_20_85'];?></td>
					<td align="center"><?php echo $summary_bill_detail[0]['tot_20_95'];?></td>
					<td align="center"><?php echo $summary_bill_detail[0]['tot_40_85'];?></td>
					<td align="center"><?php echo $summary_bill_detail[0]['tot_40_95'];?></td>
					<td align="center"><?php echo $summary_bill_detail[0]['tot_45_85'];?></td>
					<td align="center"><?php echo $summary_bill_detail[0]['tot_45_95'];?></td>
					<td align="center" style=" border-left: 1px solid black;"></td>					
					<td align="center"><?php echo $summary_bill_detail[0]['tot'];?></td>
				</tr>
			</table-->
			
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
					<td colspan="6">Print Date : &nbsp;&nbsp; <?php echo $print_time[0]['Time']; ?></td>
				</tr>
			</table>			
		</div>
	
</body>
</html>
