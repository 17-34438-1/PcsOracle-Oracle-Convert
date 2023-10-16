<style type="text/css">
	table { page-break-inside:auto }
	tr    { page-break-inside:avoid; page-break-after:auto }
	thead { display:table-header-group }
	tfoot { display:table-footer-group }
</style>
<body>
	<?php include("mydbPConnection.php"); ?>
	<?php include("mydbPConnectionn4.php"); ?>
	<table border="0" align="center" width="90%" style="border-collapse:collapse;">
		<tr>
			<td align="center">
				<img align="center" width="160px" style="margin:0px;padding:0px;" height="90px" src="<?php echo ASSETS_PATH?>images/cpa_logo.png">
			</td>
		</tr>
		<tr>
			<td align="center">
				<font size="6"><strong>Chattogram Port Authority</strong></font><br>
				<font size="4"><strong>Date Wise Online Truck and Assignment Entry Report</strong></font><br>
				<font size="4"><strong>From Date : <?php echo $from_date; ?>, To Date : <?php echo $to_date; ?></strong></font><br>				
			</td>
		</tr>
	</table>
	<table border="1" align="center" width="95%" style="border-collapse:collapse;">
		<thead>
			<tr>
				<th class="text-center">Sl</th>
				<th class="text-center">Date</th>
				<th class="text-center">Total Assignment</th>
				<th class="text-center">Total C&F </th>
				<th class="text-center">Online Truck Entry by C&F </th>
				<th class="text-center">Gate 01</th>
				<th class="text-center">Gate 02</th>
				<th class="text-center">Gate 03</th>
				<th class="text-center">Gate 05</th>
				<th class="text-center">NCT3</th>
				<th class="text-center">OFY</th>
				<th class="text-center">Truck Entry by YardCounter</th>
				<th class="text-center">Total Truck</th>
				<th class="text-center">Total Gate Out</th>
				<th class="text-center">Cash Payment</th>
				<th class="text-center">Online Payment</th>
			</tr>
		</thead>
		<?php 
			$fromDtTime = "";
			$toDtTime = "";
			
			$todayDt = "";
			$nextDt = "";
			$prevDt = "";
			
			
			
			
			$begin = new DateTime($from_date);
			$end   = new DateTime($to_date);	
			
			$c = 0;
			$totalAssignment = 0;
			$totalGate1entry = 0;
			$totalGate2entry = 0;
			$totalGate3entry = 0;
			$totalGate5entry = 0;
			$totalYardCounterEntry = 0;
			$totalGateOut = 0;
			$totalCashPayment = 0;
			$totalOnlinePayment = 0;
			$totalNCT3Entry = 0;
			$totalOFYEntry = 0;
			$totalOtherGatesEntries = 0;
			$totalCF = 0;
			$totalCNFEntry = 0;
			$totalTruckEntry = 0;
			for($j = $begin; $j <= $end; $j->modify('+1 day')){
				
				
				
				$c++;
			
				$gate1entry = "";
				$gate2entry = "";
				$gate3entry = "";
				$gate5entry = "";
				$nct3Entry = "";
				$ofyEntry = "";
				$otherGatesEntries = "";
				$yardcounter = "";
				$gateOut = "";
				$cashPayment = "";
				$onlinePayment = "";
				$cntCF = "";
				$cnfEntry = "";
				$subTotalTruck = "";
				$subTotalAssignment = "";
				
				$i = $j->format("Y-m-d");
				
				$strNextDt = "SELECT ('$i' + INTERVAL 1 DAY) AS nextDt";
				$resNextDt = mysqli_query($con_cchaportdb,$strNextDt);
				while($nextDtRes = mysqli_fetch_object($resNextDt)){
					$nextDt = $nextDtRes->nextDt;
				}
				
				$strPrevDt = "select ('$i' - INTERVAL 1 DAY) as previousDt";
				$resPrevDt = mysqli_query($con_cchaportdb,$strPrevDt);
				while($prevDtRes = mysqli_fetch_object($resPrevDt)){
					$prevDt = $prevDtRes->previousDt;
				}
				
				$fromDtTime = $prevDt." 08:00:00";
				$toDtTime = $i." 07:59:59";
				
				$sqlTotalAssignment = "SELECT COUNT(*) AS subTotalAssignment FROM ctmsmis.tmp_vcms_assignment
										WHERE ctmsmis.tmp_vcms_assignment.assignmentDate='$i'";
				$rsltTotalAssignment=mysqli_query($con_sparcsn4,$sqlTotalAssignment);
				while($rowTotalAssignment=mysqli_fetch_object($rsltTotalAssignment)){
					$subTotalAssignment = $rowTotalAssignment->subTotalAssignment;
				}				
				$totalAssignment = $totalAssignment + $subTotalAssignment;
				
				$sql = "select
							(select count(*) from do_truck_details_entry 
								WHERE gate_in_by like '%gate1%' AND 
								(do_truck_details_entry.gate_in_time BETWEEN '$fromDtTime' AND '$toDtTime')
							)as gate1entry,
							(select count(*) from do_truck_details_entry 
								WHERE gate_in_by like '%gate2%' AND 
								(do_truck_details_entry.gate_in_time BETWEEN '$fromDtTime' AND '$toDtTime')
							) as gate2entry,
							(select count(*) from do_truck_details_entry 
								WHERE gate_in_by like '%gate3%' AND 
								(do_truck_details_entry.gate_in_time BETWEEN '$fromDtTime' AND '$toDtTime')
							) as gate3entry,
							(select count(*) from do_truck_details_entry 
								WHERE gate_in_by like '%gate5%' AND 
								(do_truck_details_entry.gate_in_time BETWEEN '$fromDtTime' AND '$toDtTime')
							) as gate5entry,
							(select count(*) from do_truck_details_entry 
								WHERE gate_in_by like '%nct%' AND 
								(do_truck_details_entry.gate_in_time BETWEEN '$fromDtTime' AND '$toDtTime')
							) as nct3Entry,
							(select count(*) from do_truck_details_entry 
								WHERE gate_in_by like '%ofy%' AND 
								(do_truck_details_entry.gate_in_time BETWEEN '$fromDtTime' AND '$toDtTime')
							) as ofyEntry,
							(select count(*) from do_truck_details_entry 
								WHERE gate_in_by = 'yardcounter' AND 
								(do_truck_details_entry.gate_in_time BETWEEN '$fromDtTime' AND '$toDtTime')
							) as yardcounter,
							(select count(*) from do_truck_details_entry 
								WHERE (do_truck_details_entry.gate_out_time BETWEEN '$fromDtTime' AND '$toDtTime')
							) as gateOut,
							(select count(*) from do_truck_details_entry 
								WHERE (do_truck_details_entry.paid_collect_dt BETWEEN '$fromDtTime' AND '$toDtTime') 
								and gate_in_status='1' and do_truck_details_entry.paid_method='cash'
							) as cashPayment,
							(select count(*) from do_truck_details_entry 
								WHERE (do_truck_details_entry.paid_collect_dt BETWEEN '$fromDtTime' AND '$toDtTime')
								and gate_in_status='1' and do_truck_details_entry.paid_method='online'
							) as onlinePayment,
							(select count(distinct(update_by)) from do_truck_details_entry 
								where SUBSTR(update_by,-2)='CF' and gate_in_status='1' and 
								(do_truck_details_entry.gate_in_time BETWEEN '$fromDtTime' AND '$toDtTime')
							) as cntCF,
							(SELECT COUNT(gate_in_by) FROM do_truck_details_entry 
								WHERE (gate_in_status='1') AND 
								(do_truck_details_entry.gate_in_time BETWEEN '$fromDtTime' AND '$toDtTime')  
								AND (SUBSTRING(ip_addr,1,5) != '10.1.') AND (SUBSTRING(ip_addr,1,8) != '192.168.')
							) AS cnfEntry,
							(SELECT COUNT(*) FROM do_truck_details_entry 
								WHERE gate_in_status='1' AND 
								(do_truck_details_entry.gate_in_time BETWEEN '$fromDtTime' AND '$toDtTime')
							) AS subTotalTruck";

				$rslt=mysqli_query($con_cchaportdb,$sql);
				while($row=mysqli_fetch_object($rslt)){
					$gate1entry = $row->gate1entry;
					$gate2entry = $row->gate2entry;
					$gate3entry = $row->gate3entry;
					$gate5entry = $row->gate5entry;
					$nct3Entry = $row->nct3Entry;
					$ofyEntry = $row->ofyEntry;
					$yardcounter = $row->yardcounter;
					$gateOut = $row->gateOut;
					$cashPayment = $row->cashPayment;
					$onlinePayment = $row->onlinePayment;
					$cntCF = $row->cntCF;
					$cnfEntry = $row->cnfEntry;
					$subTotalTruck = $row->subTotalTruck;
					$otherGatesEntries=($subTotalTruck)-($gate1entry+$gate2entry+$gate3entry+$gate5entry+$nct3Entry+$ofyEntry);
				}
				
				$totalGate1entry = $totalGate1entry + $gate1entry;
				$totalGate2entry = $totalGate2entry + $gate2entry;
				$totalGate3entry = $totalGate3entry + $gate3entry;
				$totalGate5entry = $totalGate5entry + $gate5entry;
				$totalNCT3Entry = $totalNCT3Entry + $nct3Entry;
				$totalYardCounterEntry = $totalYardCounterEntry + $yardcounter;
				$totalGateOut = $totalGateOut + $gateOut;
				$totalCashPayment = $totalCashPayment + $cashPayment;
				$totalOnlinePayment = $totalOnlinePayment + $onlinePayment;
				$totalOFYEntry = $totalOFYEntry + $ofyEntry;
				$totalCF = $totalCF + $cntCF;
				$totalCNFEntry = $totalCNFEntry + $cnfEntry;
				$totalTruckEntry = $totalTruckEntry + $subTotalTruck;
				$totalOtherGatesEntries = $totalOtherGatesEntries + $otherGatesEntries;
				
		?>
			<tr>
				<td align="center" height="20px"><?php echo $c;?></td>
				<td align="center" height="20px"><?php echo date("d.m.Y", strtotime($i));?></td>
				<td align="center" height="20px"><?php echo $subTotalAssignment; ?></td>
				<td align="center" height="20px"><?php echo $cntCF; ?></td>
				<td align="center" height="20px"><?php echo $cnfEntry; ?></td>
				<td align="center" height="20px"><?php echo $gate1entry; ?></td>
				<td align="center" height="20px"><?php echo $gate2entry; ?></td>
				<td align="center" height="20px"><?php echo $gate3entry; ?></td>
				<td align="center" height="20px"><?php echo $gate5entry; ?></td>
				<td align="center" height="20px"><?php echo $nct3Entry; ?></td>
				<td align="center" height="20px"><?php echo $ofyEntry; ?></td>
				<td align="center" height="20px"><?php echo $yardcounter; ?></td>
				<td align="center" height="20px"><?php echo $subTotalTruck; ?></td>
				<td align="center" height="20px"><?php echo $gateOut; ?></td>
				<td align="center" height="20px"><?php echo $cashPayment; ?></td>
				<td align="center" height="20px"><?php echo $onlinePayment; ?></td>
			</tr>				
		<?php } ?>
			<tr>
				<th colspan="2" class="text-center"><strong>Grand Total</strong></th>
				<th class="text-center"><strong><?php echo $totalAssignment; ?></strong></th>
				<th class="text-center"><strong><?php echo $totalCF; ?></strong></th>
				<th class="text-center"><strong><?php echo $totalCNFEntry; ?></strong></th>
				<th class="text-center"><strong><?php echo $totalGate1entry; ?></strong></th>
				<th class="text-center"><strong><?php echo $totalGate2entry; ?></strong></th>
				<th class="text-center"><strong><?php echo $totalGate3entry; ?></strong></th>
				<th class="text-center"><strong><?php echo $totalGate5entry; ?></strong></th>
				<th class="text-center"><strong><?php echo $totalNCT3Entry; ?></strong></th>
				<th class="text-center"><strong><?php echo $totalOFYEntry; ?></strong></th>
				<th class="text-center"><strong><?php echo $totalYardCounterEntry; ?></strong></th>
				<th class="text-center"><strong><?php echo $totalTruckEntry; ?></strong></th>
				<th class="text-center"><strong><?php echo $totalGateOut; ?></strong></th>
				<th class="text-center"><strong><?php echo $totalCashPayment; ?></strong></th>
				<th class="text-center"><strong><?php echo $totalOnlinePayment; ?></strong></th>
			</tr>
	</table>
	<?php mysqli_close($con_sparcsn4); ?>
	<?php mysqli_close($con_cchaportdb); ?>
	<script>
		window.print();
	</script>
</body>