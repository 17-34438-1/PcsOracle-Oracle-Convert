<table width="100%">
	<tr>
		<td align="center"><h2>CHITTAGONG PORT AUTHORITY</h2></td>
	</tr>
	<tr>
		<td align="center">FOR THE MONTH OF <?php echo $monthName." ".$billwiseStatementYear;?></td>
	</tr>	
</table>
<table border='0' width="100%">
	
		<tr>
			<td width="5%" style="border-top: 1px dotted black;border-bottom: 1px dotted black;" >SL NO</td>
			<td width="40%" style="border-top: 1px dotted black;border-bottom: 1px dotted black;" >BILL NO</td>
			<td width="10%" style="border-top: 1px dotted black;border-bottom: 1px dotted black;" >DATE</td>
			<td width="10%" style="border-top: 1px dotted black;border-bottom: 1px dotted black;" align="right">AMOUNT (TK)</td>
			<td width="10%" style="border-top: 1px dotted black;border-bottom: 1px dotted black;" align="right">BILL NO</td>
			<td width="10%" style="border-top: 1px dotted black;border-bottom: 1px dotted black;" align="right">AMOUNT (TK)</td>
		</tr>
	
	<?php
	$billNo = "";
	$sl = 0;
	
	$totAmt = 0;
	$grandTotAmt = 0;
	
	for($i=0;$i<count($rslt_billwiseStatement);$i++)
	{
		$grandTotAmt = $grandTotAmt + $rslt_billwiseStatement[$i]['bdt_charges'];
		
		if($billNo != "" and $billNo!=$rslt_billwiseStatement[$i]['draftNumber2'])		
		{
	?>	
		<tr>
			<td style="border-top: 1px dotted black;" ></td>
			<td style="border-top: 1px dotted black;" ></td>
			<td style="border-top: 1px dotted black;" ></td>
			<td style="border-top: 1px dotted black;" align="right"><?php echo number_format($totAmt,2); ?></td>
			<td style="border-top: 1px dotted black;" align="right"><?php echo $billNo; ?></td>
			<td style="border-top: 1px dotted black;" align="right"><?php echo number_format($totAmt,2); ?></td>
		</tr>
		<tr>
			<td colspan="6">&nbsp;</td>
		</tr>
	<?php	
		}	
		if($billNo == "" or $billNo!=$rslt_billwiseStatement[$i]['draftNumber2'])
		{
			$sl++;
			$totAmt = 0;
			$totAmt = $totAmt + $rslt_billwiseStatement[$i]['bdt_charges'];			
	?>
		<tr>
			<td><?php echo $sl; ?></td>
			<td><?php echo $rslt_billwiseStatement[$i]['draftNumber2']; ?></td>
			<td><?php echo $rslt_billwiseStatement[$i]['billing_date']; ?></td>
			<td></td>
			<td></td>
			<td></td>
		</tr>
	<?php
		}
		else
		{
			$totAmt = $totAmt + $rslt_billwiseStatement[$i]['bdt_charges'];
		}
		$billNo=$rslt_billwiseStatement[$i]['draftNumber2'];
	?>
		<tr>
			<td><?php echo $rslt_billwiseStatement[$i]['gl_code']; ?></td>
			<td><?php echo $rslt_billwiseStatement[$i]['description']; ?></td>
			<td></td>
			<td align="right"><?php echo number_format($rslt_billwiseStatement[$i]['bdt_charges'],2); ?></td>
			<td></td>
			<td></td>
		</tr>
	<?php		
	}
	?>
		<tr>
			<td style="border-top: 1px dotted black;"></td>
			<td style="border-top: 1px dotted black;"></td>
			<td style="border-top: 1px dotted black;"></td>
			<td style="border-top: 1px dotted black;" align="right"><?php echo number_format($totAmt,2); ?></td>
			<td style="border-top: 1px dotted black;" align="right"><?php echo $billNo; ?></td>
			<td style="border-top: 1px dotted black;" align="right"><?php echo number_format($totAmt,2); ?></td>
		</tr>
		<tr>
			<td colspan="6">&nbsp;</td>			
		</tr>
		<tr>
			<td></td>
			<td colspan="2">GRAND TOTAL AMOUNT TK</td>			
			<td align="right"><?php echo number_format($grandTotAmt,2); ?></td>
			<td></td>
			<td align="right"><?php echo number_format($grandTotAmt,2); ?></td>
		</tr>
		<tr>
			<td colspan="6">&nbsp;</td>			
		</tr>
		<tr>
			<td></td>
			<td>TOTAL BILL PRINTED : <?php echo $sl; ?></td>			
			<td></td>
			<td></td>
			<td></td>
			<td></td>
		</tr>
</table>