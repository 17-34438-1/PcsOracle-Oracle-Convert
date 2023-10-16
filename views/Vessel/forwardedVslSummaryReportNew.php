<table width="100%" style="border-collapse: collapse;" align="center">
	<thead>
		<tr bgcolor="#ffffff" height="50px">
			<th align="center" colspan="12"><font size="6"><b>CHATTOGRAM PORT AUTHORITY</b></font></th>
		</tr>
		<tr bgcolor="#ffffff" height="50px">
			<th align="center" colspan="12"><font size="5"><b>(MARINE DEPARTMENT)</b></font></th>
		</tr>
		<tr bgcolor="#ffffff" height="50px">
			<th align="center" colspan="12"><font size="5"><b><?php echo $title; ?></b></font></th>
		</tr>
		<tr bgcolor="#ffffff" height="50px">
			<th align="center" colspan="12"><font size="4"><?php echo "From : ".$fromDate." To : ".$toDate; ?></font></th>
		</tr>
	</thead>
</table>
<table width="80%" style="border-collapse: collapse;" align="center">	
	<tbody>
		<tr>			
			<th colspan='2' style="border:1px solid black; border-collapse: collapse;" align="center" class="text-center"><h3>Forward & Bill Info (Container & Not Entering)</h3></th>			
		</tr>
		<tr>			
			<th style="border:1px solid black; border-collapse: collapse;" align="center" class="text-center">Summary</th>
			<th style="border:1px solid black; border-collapse: collapse;" align="center" class="text-center">Total Number</th>
		</tr>
		<tr border ='1' cellpadding='0' cellspacing='0' style="border-collapse: collapse;">
			<td style="border:1px solid black; font-size:12px;  border-collapse: collapse;" align="center" >
				Forwarded By Master
			</td>
			<td style="border:1px solid black; font-size:12px;  border-collapse: collapse;" align="center" >
				<?php echo $totContVslSum+$totNEVslSum;?>
			</td>
		</tr>
		<tr border ='1' cellpadding='0' cellspacing='0' style="border-collapse: collapse;">
			<td style="border:1px solid black; font-size:12px;  border-collapse: collapse;" align="center" >
				Bill Generated
			</td>
			<td style="border:1px solid black; font-size:12px;  border-collapse: collapse;" align="center" >
				<?php echo $totBillGenSum;?>
			</td>
		</tr>
		<tr border ='1' cellpadding='0' cellspacing='0' style="border-collapse: collapse;">
			<td style="border:1px solid black; font-size:12px;  border-collapse: collapse;" align="center" >
				Pending Bill Generation
			</td>
			<td style="border:1px solid black; font-size:12px;  border-collapse: collapse;" align="center" >
				<?php echo $totPendingBill;?>
			</td>
		</tr>
		<tr border ='1' cellpadding='0' cellspacing='0' style="border-collapse: collapse;">
			<td style="border:1px solid black; font-size:12px;  border-collapse: collapse;" align="center" >
				Forwarded To Agent
			</td>
			<td style="border:1px solid black; font-size:12px;  border-collapse: collapse;" align="center" >
				<?php echo $totBillAprvSum;?>
			</td>
		</tr>
		<tr border ='1' cellpadding='0' cellspacing='0' style="border-collapse: collapse;">
			<td style="border:1px solid black; font-size:12px;  border-collapse: collapse;" align="center" >
				<b>Grand Total</b>
			</td>
			<td style="border:1px solid black; font-size:12px;  border-collapse: collapse;" align="center" >
				<?php echo $totContVslSum+$totNEVslSum+$totBillGenSum+$totPendingBill+$totBillAprvSum;?>
			</td>
		</tr>
	</tbody>
</table>

<table width="80%" style="border-collapse: collapse;" align="center">	
	<tbody>
		<tr>			
			<th colspan='2' style="border:1px solid black; border-collapse: collapse;" align="center" class="text-center"><h3>Vessel Handling By Apps</h3></th>			
		</tr>
		<tr>			
			<th style="border:1px solid black; border-collapse: collapse;" align="center" class="text-center">Vessel Phase</th>
			<th style="border:1px solid black; border-collapse: collapse;" align="center" class="text-center">Total Number</th>
		</tr>
		<tr border ='1' cellpadding='0' cellspacing='0' style="border-collapse: collapse;">
			<td style="border:1px solid black; font-size:12px;  border-collapse: collapse;" align="center" >
				Arrival
			</td>
			<td style="border:1px solid black; font-size:12px;  border-collapse: collapse;" align="center" >
				<?php echo $totVslArrive;?>
			</td>
		</tr>
		<tr border ='1' cellpadding='0' cellspacing='0' style="border-collapse: collapse;">
			<td style="border:1px solid black; font-size:12px;  border-collapse: collapse;" align="center" >
				Depart
			</td>
			<td style="border:1px solid black; font-size:12px;  border-collapse: collapse;" align="center" >
				<?php echo $totVslDepart;?>
			</td>
		</tr>
		<tr border ='1' cellpadding='0' cellspacing='0' style="border-collapse: collapse;">
			<td style="border:1px solid black; font-size:12px;  border-collapse: collapse;" align="center" >
				Shifting
			</td>
			<td style="border:1px solid black; font-size:12px;  border-collapse: collapse;" align="center" >
				<?php echo $totVslShift;?>
			</td>
		</tr>
		<tr border ='1' cellpadding='0' cellspacing='0' style="border-collapse: collapse;">
			<td style="border:1px solid black; font-size:12px;  border-collapse: collapse;" align="center" >
				<b>Grand Total</b>
			</td>
			<td style="border:1px solid black; font-size:12px;  border-collapse: collapse;" align="center" >
				<?php echo $totVslArrive+$totVslDepart+$totVslShift;?>
			</td>
		</tr>		
	</tbody>
</table>