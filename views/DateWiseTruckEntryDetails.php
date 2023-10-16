<title><?php echo $title; ?></title>
<body>
	<table width="100%" border ='0' cellpadding='0' cellspacing='0'>
		<tr bgcolor="#ffffff" align="center" height="100px">
			<td colspan="13" align="center">
				<table border=0 width="100%">				
					<tr>
						<td colspan="12" align="center"><img width="250px" height="80px" src="<?php echo IMG_PATH?>cpanew.jpg"></td>
					</tr>			
					<tr align="center">
						<td colspan="12"><font size="4"><b><?php echo $title;?> from the date <?php echo $fromDate; ?> to <?php echo $toDate; ?></b></font></td>
					</tr>
					<tr align="center">
						<td colspan="12"><font size="4"><b></b></font></td>
					</tr>				
				</table>		
			</td>		
		</tr>		
	</table>
	<table width="80%" border ='1' cellpadding='0' cellspacing='0' align="center" style="border-collapse: collapse">
		<tr align="center">
			<th><b>Sl.</b></th>
			<th><b>Entry Date</b></th>		
			<th><b>Container No.</b></th>			
			<th><b>Truck Id</b></th>
            <th><b>C&F License No.</b></th>
			<th><b>C&F Name</b></th>	
		</tr>
		<?php
		for($i=0;$i<count($detailsRslt);$i++)
		{
		?>
		<tr>
			<td align="center"><?php echo $i+1; ?></td>
			<td align="center"><?php echo $detailsRslt[$i]['entryDt']; ?></td>		
			<td align="center"><?php echo $detailsRslt[$i]['cont_no']; ?></td>		
			<td align="center"><?php echo $detailsRslt[$i]['truck_id']; ?></td>		
			<td align="center"><?php echo $detailsRslt[$i]['cnf_lic_no']; ?></td>
            <td align="center"><?php echo $detailsRslt[$i]['cf_name']; ?></td>						
		</tr>
		<?php
		}
		?>
	</table>
</body>