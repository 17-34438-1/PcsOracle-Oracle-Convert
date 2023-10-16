<table width="100%">
	<tr>
		<td align="center"><h2>CHITTAGONG PORT AUTHORITY</h2></td>
	</tr>
	<tr>
		<td align="center"><h3>STATEMENT SHOWING COLLECTION RECEIVABLE BY THE PORT AUTHORITY</h3></td>
	</tr>
	<tr>
		<td align="center">ON ACCOUNT OF JETTY & PRT CHARGES FROM SEA GOING VESSELS</td>
	</tr>
	<tr>
		<td align="center">For the month of <?php echo $periodicFromDate; ?> and <?php echo $periodicToDate; ?></td>		
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
		<th style="border:1px solid black">NAME OF VESSEL</th>
		<th style="border:1px solid black">BERTHING</th>
		<th style="border:1px solid black">BILL AMOUNT</th>
		<th style="border:1px solid black">VAT AMOUNT</th>
		<th style="border:1px solid black">AUTHORIZED DEALER</th>			
		<th style="border:1px solid black">VESSEL FLAG</th>			
	</tr>	
	<?php	
	$totAmt = 0;
	$totVat = 0;
		
	for($i=0;$i<count($rslt_periodicStatement);$i++)
	{
		$totAmt = $totAmt + $rslt_periodicStatement[$i]['amt'];
		$totVat = $totVat + $rslt_periodicStatement[$i]['vat'];
	?>	
	<tr>
		<td style="border:1px solid black" align="center"><?php echo $i+1; ?></td>
		<td style="border:1px solid black" align="center"><?php echo $rslt_periodicStatement[$i]['finalNumber']; ?></td>			
		<td style="border:1px solid black" align="center"><?php echo $rslt_periodicStatement[$i]['billing_date']; ?></td>			
		<td style="border:1px solid black" align="center"><?php echo $rslt_periodicStatement[$i]['rotation']; ?></td>			
		<td style="border:1px solid black" align="center"><?php echo $rslt_periodicStatement[$i]['vsl_name']; ?></td>			
		<td style="border:1px solid black" align="center"><?php echo $rslt_periodicStatement[$i]['ata']; ?></td>			
		<td style="border:1px solid black" align="center"><?php echo $rslt_periodicStatement[$i]['amt']; ?></td>			
		<td style="border:1px solid black" align="center"><?php echo $rslt_periodicStatement[$i]['vat']; ?></td>			
		<td style="border:1px solid black" align="center"><?php echo $rslt_periodicStatement[$i]['agent_name']; ?></td>			
		<td style="border:1px solid black" align="center"><?php echo $rslt_periodicStatement[$i]['flag']; ?></td>			
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