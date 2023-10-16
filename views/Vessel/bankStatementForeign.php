<table width="100%">
	<tr>
		<td align="center">CHITTAGONG PORT AUTHORITY</td>
	</tr>
	<tr>
		<td align="center">STATEMENT SHOWING COLLECTION RECEIVABLE BY THE PORT AUTHORITY ON ACCOUNT OF JETTY & PORT CHARGES</td>
	</tr>
	<tr>
		<td align="center">FROM SEA GOING VESSELS FOR <?php echo $bankStatementDate; ?></td>
	</tr>
</table>
<table border='1' width="100%">
	<tr>
		<th>SL</th>
		<th>BILL NO</th>
		<th>DATE</th>
		<th>NAME OF VESSEL</th>
		<th>ARV DATE</th>
		<th>US $</th>
		<th>AMOUNT (TK)</th>
		<th>VAT (TK)</th>
		<th>AUTHORIZED DEALERS</th>
		<th>FLAG</th>
	</tr>
	<?php
		$totUSD = 0;
		$totBDT = 0;
		$totVAT = 0;
		
		for($i=0;$i<count($rslt_bankStatement);$i++)
		{
			$totUSD = $totUSD + $rslt_bankStatement[$i]['usd'];
			$totBDT = $totBDT + $rslt_bankStatement[$i]['totbsd'];
			$totVAT = $totVAT + $rslt_bankStatement[$i]['vat'];
	?>
	<tr>
		<td align="center"><?php echo $i+1; ?></td>		
		<td align="center"><?php echo $rslt_bankStatement[$i]['draftNumber']; ?></td>		
		<td align="center"><?php echo $rslt_bankStatement[$i]['billing_date']; ?></td>		
		<td align="center"><?php echo $rslt_bankStatement[$i]['vsl_name']; ?></td>		
		<td align="center"><?php echo $rslt_bankStatement[$i]['ata']; ?></td>		
		<td align="center"><?php echo $rslt_bankStatement[$i]['usd']; ?></td>		
		<td align="center"><?php echo $rslt_bankStatement[$i]['totbsd']; ?></td>		
		<td align="center"><?php echo $rslt_bankStatement[$i]['vat']; ?></td>		
		<td align="center"><?php echo $rslt_bankStatement[$i]['agent_name']; ?></td>		
		<td align="center"><?php echo $rslt_bankStatement[$i]['flag']; ?></td>		
	</tr>
	<?php
		}		
	?>
	<tr>
		<td colspan="5" align="center">TOTAL AMOUNT FOR JETTY CHARGES</td>
		<td align="center"><?php echo $totUSD; ?></td>
		<td align="center"><?php echo number_format($totBDT,2); ?></td>
		<td align="center"><?php echo number_format($totVAT,2); ?></td>
		<td colspan="2" align="center">&nbsp;</td>
	<tr>
</table>