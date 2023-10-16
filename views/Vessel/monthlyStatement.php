<table width="100%">
	<tr>
		<td align="center"><h2>CHITTAGONG PORT AUTHORITY</h2></td>
	</tr>
	<tr>
		<td align="center">BILL INFORMATION OF 'JL'</td>
	</tr>
	<tr>
		<td align="center">For the month of <?php echo $monthName; ?> and <?php echo $monthlyStatementYear; ?></td>		
	</tr>
</table>
<table width="100%">
	<tr>
		<td colspan="10">Print date : <?php echo date('Y-m-d'); ?></td>
	</tr>
	<tr>
		<td colspan="10">&nbsp;</td>
	</tr>
	<tr>
		<th style="border:1px solid black">SL.</th>
		<th style="border:1px solid black">BILL NO</th>
		<th style="border:1px solid black">BILL DATE</th>
		<th style="border:1px solid black">REGISTRATION NO</th>
		<th style="border:1px solid black">REGISTRATION DATE</th>
		<th style="border:1px solid black">PARTY DESCRIPTION</th>
		<th style="border:1px solid black">BILL AMOUNT</th>
		<th style="border:1px solid black">VAT AMOUNT</th>
	</tr>	
	<?php	
	
	$totAmt = 0;
	$totVat = 0;
	
	for($i=0;$i<count($rslt_monthlyStatement);$i++)
	{
		$totAmt = $totAmt + $rslt_monthlyStatement[$i]['amt'];
		$totVat = $totVat + $rslt_monthlyStatement[$i]['vat'];
	?>	
	<tr>
		<td style="border:1px solid black" align="center"><?php echo $i+1; ?></td>
		<td style="border:1px solid black" align="center"><?php echo $rslt_monthlyStatement[$i]['finalNumber']; ?></td>			
		<td style="border:1px solid black" align="center"><?php echo $rslt_monthlyStatement[$i]['billing_date']; ?></td>			
		<td style="border:1px solid black" align="center"><?php echo $rslt_monthlyStatement[$i]['rotation']; ?></td>			
		<td style="border:1px solid black" align="center"><?php echo $rslt_monthlyStatement[$i]['ata']; ?></td>			
		<td style="border:1px solid black" align="center"><?php echo $rslt_monthlyStatement[$i]['agent_name']; ?></td>			
		<td style="border:1px solid black" align="center"><?php echo $rslt_monthlyStatement[$i]['amt']; ?></td>			
		<td style="border:1px solid black" align="center"><?php echo $rslt_monthlyStatement[$i]['vat']; ?></td>			
	</tr>
	<?php		
	}
	?>
	<tr>
		<td></td>
		<td></td>
		<td></td>
		<td></td>
		<td></td>
		<td align="center"><b>Total</b></td>
		<td align="center"><b><?php echo $totAmt; ?></b></td>
		<td align="center"><b><?php echo $totVat; ?></b></td>
		<td></td>			
		<td></td>			
	</tr>
</table>