<?php
	if($_POST['fileOptions']=='xl'){
		header("Content-type: application/octet-stream; charset=utf-8");
		header("Content-Disposition: attachment; filename=Truck_Report.xls;");
		header("Content-Type: application/ms-excel; charset=utf-8");
		header("Pragma: no-cache");
		header("Expires: 0");
	}
	
	if(count($rslt_truckReport)>0)
	{			
	?>
	<meta http-equiv="content-type" content="application/xhtml+xml; charset=UTF-8" />
		<table style="width:100%;border-collapse: collapse;" border="1">	
			<tr class="gridDark" align="center">
				<?php
					$colspan = 0;
					if($uriSegment=="insidePort") {
						$colspan = 9;
					}
					else if($uriSegment=="gateOut" or $uriSegment=="total") {
						$colspan = 10;
					}
					else if($uriSegment=="notpaid") {
						$colspan = 7;
					}
					else
					{
						$colspan = 8;
					}
				?>
                        
                    <th colspan="<?php echo $colspan; ?>"><?php echo $title." of ".$truckDate; ?></th>
			</tr>
			<tr class="gridDark" align="center">
				<th>Sl.</th>
				<th>Visit ID</th>
				<th>Truck No</th>
				<th>Driver</th>
				<th>Helper</th>
				<th>C&F </th>
				<th>Container</th>
				
				<?php if($uriSegment!="notpaid" ){ ?>
					<th>Payment Colleted By</th>
				<?php } ?>

				<?php if($uriSegment=="insidePort" || $uriSegment=="gateOut" || $uriSegment=="total"){ ?>
					<th>Gate In Time</th>
				<?php } ?>

				<?php if($uriSegment=="gateOut" || $uriSegment=="total"){ ?>
					<th>Gate Out Time</th>
				<?php } ?>

			</tr>
			<?php
			for($i=0;$i<count($rslt_truckReport);$i++)
			{
			?>
			<tr class="gridLight" align="center">
				<td><?php echo $i+1; ?></td>
				<td><?php echo $rslt_truckReport[$i]['trucVisitId']; ?></td>
				<td><?php echo $rslt_truckReport[$i]['truck_id']; ?></td>
				<td><?php echo $rslt_truckReport[$i]['driver_name']; ?></td>
				<td><?php echo $rslt_truckReport[$i]['assistant_name']; ?></td>

				<td><?php echo $rslt_truckReport[$i]['cnf_name']; ?></td>
				<td><?php echo $rslt_truckReport[$i]['cont_no']; ?></td>
				<?php if($uriSegment!="notpaid" ){ ?>
					<td><?php if($rslt_truckReport[$i]['paid_status']==1){echo $rslt_truckReport[$i]['paid_collect_by'];} ?></td>
				<?php } ?>

				<?php if($uriSegment=="insidePort" || $uriSegment=="gateOut" || $uriSegment=="total") { ?>
					<td><?php echo $rslt_truckReport[$i]['gate_in_time']; ?></td>
				<?php
					} 
				?>

				<?php if($uriSegment=="gateOut" || $uriSegment=="total") { ?>
					<td><?php echo $rslt_truckReport[$i]['gate_out_time']; ?></td>
				<?php
					} 
				?>

			</tr>
			<?php
			}
			?>
			<tr class="gridDark">
				<th colspan="<?php echo $colspan; ?>"><b><?php echo "Total No of Truck : ".count($rslt_truckReport); ?></b></th>
			</tr>				
		</table>
	<?php
	}
	?>