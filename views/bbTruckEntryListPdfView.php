
<html>
<head>
	<table width="100%" border="0">
		<tr height="100px">
			<td align="center" valign="middle">
				<h1><img align="middle"  width="200px" height="65px" src="<?php echo IMG_PATH?>cpanew.jpg"></h1>
			
			</td>
		</tr>
	</table>
		
		<div align="center"></div>
		<div align="center"><b><?php echo $title;?></b></div>

</head>
<body>	

 <table align="center" width="80%" border="1" cellpadding="0" cellspacing="0" style="font-size:11px;  border-collapse: collapse;">
		<tr>
			<th>Sl</th>
			<th>Shed No</th>
			<th>CNF Name</th>
			<th>Entry Date</th>
			<th>From</th>
			<th>To</th>
			<th>Truck Quantity</th>	
		</tr>
		<?php
		
		for($i=0;$i<count($rslt_truckEntryList);$i++)
		{							
		?>
		<tr>
			<td align="center">
				<?php echo $i+1; ?>
			</td>							
			<td align="center">
				<?php echo $rslt_truckEntryList[$i]['shed_no']; ?>
			</td>
			<td  align="center">
				<?php echo $rslt_truckEntryList[$i]['cnf_name']; ?>
			</td>
			<td align="center">
				<?php echo $rslt_truckEntryList[$i]['truck_entry_date']; ?>
			</td>
			<td align="center">
				<?php echo $rslt_truckEntryList[$i]['truck_from_time']; ?>
			</td>
			<td  align="center">
				<?php echo $rslt_truckEntryList[$i]['truck_to_time']; ?>
			</td>
			<td align="center">
				<?php echo $rslt_truckEntryList[$i]['truck_quantity']; ?>
			</td>

		
		<?php
		}
		?>							
	</table>
 </body>
</html>
