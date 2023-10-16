<html>	
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	</head>
	<body>
		<table width="100%" border ='0' cellpadding='0' cellspacing='0'>
			<tr bgcolor="#ffffff" align="center" height="100px">
				<td colspan="13" align="center">
					<table border=0 width="100%">	
						<tr>
							<td colspan="12" align="center"><img width="200px" height="60px" src="<?php echo IMG_PATH?>cpanew.jpg"></td>
						</tr>
						<tr align="center">
							<td colspan="12" align="center"><font size="4"><b>TRUCK REPORT</b></font></td>
						</tr>									
					</table>
				</td>
			</tr>	
			<tr bgcolor="#ffffff" align="center" height="25px">
				<td colspan="15" align="center"></td>				
			</tr>
		</table>
		<?php
		if(count($rslt_truckReport)>0)
			{			
		?>
			<table style="width:100%;border-collapse: collapse;" border="1">	
				<tr align="left">
					<?php
						$colspan = 0;
						if($uriSegment=="insidePort") {
							$colspan = 9;
						}
						else if($uriSegment=="gateOut" || $uriSegment=="total") {
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
					<td><p style="font-family: ind_bn_1_001"><?php echo $rslt_truckReport[$i]['truck_id']; ?></p></td>
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
	</body>
</html>

