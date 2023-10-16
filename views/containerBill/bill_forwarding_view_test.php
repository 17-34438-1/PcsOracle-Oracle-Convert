<body>	
	<table border="0" align="center" width="90%" style="border-collapse:collapse;">
		<tr>
			<td align="center">
				<font size="6"><strong>Chattogram Port Authority</strong></font><br>
				<font size="5"><strong>Nature of Bill :</strong> <?php echo $rslt_bill_dtl[0]['description'];?></font><br>		
				<font size="5"><?php echo "Bill Generated From  ".$fromDate."  To  ".$toDate?></font><br>		
			</td>
		</tr>
	</table>
	
	<table border="1" align="center" width="98%" style="border-collapse:collapse;">
		<thead>			
			<tr>
				<th style="border:1px solid black; font-size:12px;  border-collapse: collapse;" align="center">Billing Date</th>
				<th style="border:1px solid black; font-size:12px;  border-collapse: collapse;" align="center">Vessel Name</th>
				<th style="border:1px solid black; font-size:12px;  border-collapse: collapse;" align="center">Rotation No</th>
				<th style="border:1px solid black; font-size:12px;  border-collapse: collapse;" align="center">Arv Date</th>
				<th style="border:1px solid black; font-size:12px;  border-collapse: collapse;" align="center">Agent</th>
				<th style="border:1px solid black; font-size:12px;  border-collapse: collapse;" align="center"><nobr>Bill No</nobr></th>
				<th style="border:1px solid black; font-size:12px;  border-collapse: collapse;" align="center"><nobr>MLO</nobr></th>
				<th style="border:1px solid black; font-size:12px;  border-collapse: collapse;" align="center"><nobr>Total(Tk)</nobr></th>
				<th style="border:1px solid black; font-size:12px;  border-collapse: collapse;" align="center"><nobr>Bill Name</nobr></th>
				<th style="border:1px solid black; font-size:12px;  border-collapse: collapse;" align="center"><nobr>Paid</nobr></th>
				
			</tr>
		</thead>		
		<?php
		$sum=0;
		for($i=0;$i<count($rslt_bill_dtl);$i++){
		?>
		<tr>
			<td align="center"><?php echo $rslt_bill_dtl[$i]['billing_date'];?></td>
			<td align="center"><?php echo $rslt_bill_dtl[$i]['vsl_name'];?></td>
			<td align="center"><?php echo $rslt_bill_dtl[$i]['imp_rot'];?></td>
			<td align="center"><?php echo $rslt_bill_dtl[$i]['arv_date'];?></td>
			<td align="center"><?php echo $rslt_bill_dtl[$i]['agent_code'];?></td>
			<td align="center"><?php echo $rslt_bill_dtl[$i]['bill_no'];?></td>
			<td align="center"><?php echo $rslt_bill_dtl[$i]['mlo_code'];?></td>
			<td align="center"><?php $sum+=$rslt_bill_dtl[$i]['tot']; echo $rslt_bill_dtl[$i]['tot'];   ?></td>
			<td align="center"><nobr><?php echo $rslt_bill_dtl[$i]['description'];?></nobr></td>
			<td align="center"><?php echo $rslt_bill_dtl[$i]['pod'];?></td>
		</tr>		
		<?php }?>
		<tr>
			<td colspan="5" align="center"> Total of Bill Generation : <?php echo $i; ?></td>
			<td colspan="5" align="center"> Grand Total : <?php echo $sum; ?></td>
			
		</tr>
	</table>
</body>