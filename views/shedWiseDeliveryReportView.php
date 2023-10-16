
<body>
	<table border="0" align="center" width="95%" style="border-collapse:collapse;margin-bottom:10px;">
		<tr>
			<td align="center">
				<img align="center" width="150px" style="margin:0px;padding:0px;" height="90px" src="<?php echo ASSETS_PATH?>images/cpa_logo.png">
			</td>
		</tr>
		<tr>
			<td align="center">
				<font size="6"><strong>Chattogram Port Authority</strong></font><br>
				<font size="4"><strong>Head Delivery Report For Shed: <?php echo $shed; ?></strong></font>	
				<br/>	
				<font size="4">From: <strong> <?php echo $from_date; ?></strong>   To: <strong> <?php echo $to_date; ?></strong></font>			
			</td>
		</tr>
	</table>
	<table border="1" align="center" width="95%" style="border-collapse:collapse;">
		<thead>
			<tr>
				<th class="text-center">SL</th>
				<th class="text-center">VISIT ID</th>
				<th class="text-center">VESSEL NAME</th>
				<th class="text-center">ROTATION</th>
				<th class="text-center">B/E No</th>
				<th class="text-center">B/L No</th>
				<th class="text-center">CP No</th>
				<th class="text-center">PACKAGES</th>
				<th class="text-center">WEIGHT</th>
				<th class="text-center">C & F NAME</th>
				<th class="text-center">JETTY SIRCIR</th>
				<th class="text-center">TRUCK ID</th>
				<th class="text-center">LOAD CONFIRMED BY</th>
			</tr>			
		</thead>
		<tbody>
			<?php 
				$pack_sum=0;
				for($i=0;$i<count($dlv_list);$i++){ 
			?>
			<tr>
				<td align="center" height="20px"><?php echo $i+1;?></td>				
				<td align="center" height="20px"><?php echo $dlv_list[$i]['visit_id'];?></td>	
				<td align="center" height="20px"><?php echo $dlv_list[$i]['Vessel_Name'];?></td>				
				
				<td align="center" height="20px"><?php echo $dlv_list[$i]['imp_rot_no'];?></td>				
				<td align="center" height="20px"><?php echo $dlv_list[$i]['be_no'];?></td>				
				<td align="center" height="20px"><?php echo $dlv_list[$i]['BL_No'];?></td>				
				<td align="center" height="20px"><?php echo $dlv_list[$i]['cp_no'];?></td>				
				<td align="center" height="20px"><?php $pack_sum+=$dlv_list[$i]['actual_delv_pack']; echo $dlv_list[$i]['actual_delv_pack'].'  '.$dlv_list[$i]['Pack_Unit'];?></td>	
				<td align="center" height="20px"><?php echo $dlv_list[$i]['weight'];?></td>				
				<td align="center" height="20px"><?php echo $dlv_list[$i]['cnf_name'];?></td>				
				
				<td align="center" height="20px"><?php echo $dlv_list[$i]['agent_name'];?></td>	
				<td align="center" height="20px"><?php echo $dlv_list[$i]['truck_id'];?></td>				
				
				<td align="center" height="20px"><?php echo $dlv_list[$i]['load_by'];?></td>				
						
			</tr>
			<?php } ?>
			<tr>
				<td colspan="6"></td>
				<td><b>Total Pack:</b></td>
				<td align="center"><b><?php echo $pack_sum; ?> </b></td>
				<td colspan="5"></td>
			</tr>
		
		</tbody>
	</table>
	
</body>