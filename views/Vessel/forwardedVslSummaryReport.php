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
			<th style="border:1px solid black; border-collapse: collapse;" align="center" class="text-center">Forwarded Date</th>
			<th style="border:1px solid black; border-collapse: collapse;" align="center" class="text-center">Forwarded Vessel</th>
			<th style="border:1px solid black; border-collapse: collapse;" align="center" class="text-center">No. of Generated Bill</th>
		</tr>
		<?php 
		$vsl_type="";
		$totVsl = 0;
		
		include('dbConection.php');
		
		for($i=0; $i < count($rslt_hmSummary); $i++) 
		{
			if($vslType == "Not Entering Vessel")
			{
				$sql_cntNEBill = "SELECT COUNT(DISTINCT rotation) AS tot_bill
							FROM ctmsmis.mis_vsl_billing_detail_test
							WHERE DATE(ctmsmis.mis_vsl_billing_detail_test.billing_date)='".$rslt_hmSummary[$i]['forwarded_dt']."' AND bill_type='106'";
				$rslt_cntNEBill = mysqli_query($con_sparcsn4,$sql_cntNEBill);
							
				$tot_bill = 0;
				while($row_cntNEBill = mysqli_fetch_object($rslt_cntNEBill))
				{
					$tot_bill = $row_cntNEBill->tot_bill;
				}
				
				if($tot_bill!=0 or $rslt_hmSummary[$i]['forwarded_by_master']!=0)
				{
		?>
			<tr border ='1' cellpadding='0' cellspacing='0' style="border-collapse: collapse;">
				<td style="border:1px solid black; font-size:12px;  border-collapse: collapse;" align="center" >
					<?php echo $rslt_hmSummary[$i]['forwarded_dt'];?>
				</td>
				<td style="border:1px solid black; font-size:12px;  border-collapse: collapse;" align="center" >
					<?php echo $rslt_hmSummary[$i]['forwarded_by_master'];?>
				</td>
				<td style="border:1px solid black; font-size:12px;  border-collapse: collapse;" align="center" >
					<?php 
					echo $tot_bill;
					?>
				</td>
			</tr>
		<?php
				}	// not 0
			}	// vsl type
			else if($vslType == "Container Vessel")
			{
		?>
			<tr border ='1' cellpadding='0' cellspacing='0' style="border-collapse: collapse;">
				<td style="border:1px solid black; font-size:12px;  border-collapse: collapse;" align="center" >
					<?php echo $rslt_hmSummary[$i]['forwarded_dt'];?>
				</td>
				<td style="border:1px solid black; font-size:12px;  border-collapse: collapse;" align="center" >
					<?php echo $rslt_hmSummary[$i]['forwarded_by_master'];?>
				</td>
				<td style="border:1px solid black; font-size:12px;  border-collapse: collapse;" align="center" >
					<?php echo $rslt_hmSummary[$i]['tot_bill']; ?>
				</td>
			</tr>
		<?php		
			}
		}	// for loop 
		?>
	</tbody>
</table>