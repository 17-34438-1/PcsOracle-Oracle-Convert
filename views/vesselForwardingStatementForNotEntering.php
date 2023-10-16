<body>	
	<?php 
		include_once("mydbPConnection.php");
		$i=1; 
		for($p=1;$p<=$totalPage;$p++) {
		$limitStartingFrom = ($p-1)*15;
	?>
	<div <?php if($p<$totalPage) { ?> style="page-break-after: always" <?php } ?>>
		
		<table border="0" align="center" width="100%" style="border-collapse:collapse;">
			<tr>
				<td align="center" width="70%">
					<h1>CHATTOGRAM PORT AUTHORITY</h1>
					<h2>VESSEL FORWARDING STATEMENT (NOT ENTERING)</h2>
					<h2>From Date : <?php echo date('d/m/Y', strtotime($from_date)); ?>, To Date : <?php echo date('d/m/Y', strtotime($to_date)); ?></h2>
				</td>				
			</tr>	
		</table>
		<?php 
			if($login_id="HMaster"){
				$sqlReport = "SELECT outer_vsl_visit_info.id AS outer_vvi_id,outer_vsl_visit_info.imp_rot AS ib_vyg,outer_vsl_info.vsl_name,
					CONCAT(date_of_arrival,' ',time_of_arrival) AS ata,CONCAT(date_of_departure,' ',time_of_departure) AS atd,grt,nrt,
					flag AS cntry_name,agent_name AS name,master_forward_at AS forward_at,forward_remarks,
					outer_vsl_forward_info.master_forward_by,outer_vsl_forward_info.marine_forward_by
					FROM outer_vsl_forward_info
					INNER JOIN outer_vsl_visit_info ON outer_vsl_visit_info.id = outer_vsl_forward_info.vsl_visit_id
					INNER JOIN outer_vsl_info ON outer_vsl_info.id=outer_vsl_visit_info.outer_vsl_id
					INNER JOIN outer_agent_info ON outer_agent_info.id=outer_vsl_info.agent_id
					WHERE master_forward_stat='1' AND (master_forward_at BETWEEN '$from_date' AND '$to_date') limit $limitStartingFrom,15";
			} else {
				$sqlReport = "SELECT outer_vsl_visit_info.id AS outer_vvi_id,outer_vsl_visit_info.imp_rot AS ib_vyg,outer_vsl_info.vsl_name,
					CONCAT(date_of_arrival,' ',time_of_arrival) AS ata,CONCAT(date_of_departure,' ',time_of_departure) AS atd,grt,nrt,
					flag AS cntry_name,agent_name AS name,master_forward_at AS forward_at,forward_remarks,
					outer_vsl_forward_info.master_forward_by,outer_vsl_forward_info.marine_forward_by
					FROM outer_vsl_forward_info
					INNER JOIN outer_vsl_visit_info ON outer_vsl_visit_info.id = outer_vsl_forward_info.vsl_visit_id
					INNER JOIN outer_vsl_info ON outer_vsl_info.id=outer_vsl_visit_info.outer_vsl_id
					INNER JOIN outer_agent_info ON outer_agent_info.id=outer_vsl_info.agent_id
					WHERE master_forward_stat='1' AND master_forward_by='$login_id' 
					AND (master_forward_at BETWEEN '$from_date' AND '$to_date') limit $limitStartingFrom,15";
			}
				
				$resReport = mysqli_query($con_cchaportdb,$sqlReport);
				
				
		?>
		<table border="1" style="border-collapse:collapse;margin-top:10px;" align="center" width="100%">
			
				<tr>
					<th rowspan="2" class="text-center"><h2>Sl</h2></th>
					<th rowspan="2" class="text-center"><h2>Vessel Name</h2></th>
					<th rowspan="2" class="text-center"><h2>Date of Arrival</h2></th>
					<th rowspan="2" class="text-center"><h2>Date of Departure</h2></th>		
					<th rowspan="2" class="text-center"><h2>Rotation</h2></th>
					<th rowspan="2" class="text-center"><h2>Country</h2></th>
					<th colspan="2" class="text-center"><h2>Tonage</h2></th>   
					<th rowspan="2" class="text-center"><h2>Local Agent</h2></th>   
					<th rowspan="2" class="text-center"><h2>Remarks</h2></th>   
					<th rowspan="2" class="text-center"><h2>Forwarded to Acc at</h2></th>   
				</tr>
				<tr>
					<th>GT</th>
					<th>NT</th>
				</tr>
				
			<?php while($rtnReport=mysqli_fetch_object($resReport)) { ?>
				<tr>
					<td align="center" style="font-size: 13pt;"><font size="5"><?php echo $i++; ?></font></td> <!--<font size="5">...</font>-->
					<td align="center" style="font-size: 13pt;"><?php echo $rtnReport->vsl_name;?></td>
					<td align="center" style="font-size: 13pt;"><?php echo date('d/m/Y H:i:s', strtotime($rtnReport->ata));?></td>
					<td align="center" style="font-size: 13pt;"><?php echo date('d/m/Y H:i:s', strtotime($rtnReport->atd));?></td>
					<td align="center" style="font-size: 13pt;"><?php echo $rtnReport->ib_vyg;?></td>
					<td align="center" style="font-size: 13pt;"><?php echo $rtnReport->cntry_name;?></td>
					<td align="center" style="font-size: 13pt;"><?php echo $rtnReport->grt;?></td>
					<td align="center" style="font-size: 13pt;"><?php echo $rtnReport->nrt;?></td>
					<td align="center" style="font-size: 13pt;"><?php echo $rtnReport->name;?></td>
					<td align="center" style="font-size: 13pt;"><?php echo $rtnReport->forward_remarks;?></td>
					<td align="center" style="font-size: 13pt;"><?php echo date('d/m/Y H:i:s', strtotime($rtnReport->forward_at));?></td>
				</tr>			
			<?php } ?>
		</table>
	</div>
	<?php } ?>
	<?php if($totalRow > 0) { ?>
	<div class="container">
		<table border="0" align="center" width="100%" style="border-collapse:collapse;margin-top:10px;">
			<tr>
				<th align="center" width="18%" style="border-bottom: 1px solid black;"><h1>Marine</h1></th>	
				<th>&nbsp;</th>
				<th align="center" width="18%" style="border-bottom: 1px solid black;"><h1>SR.VTSSO/VTMIS</h1></th>
				<th>&nbsp;</th>				
				<th align="center" width="18%" style="border-bottom: 1px solid black;"><h1>SUPDT(DM ESTB)</h1></th>
				<th>&nbsp;</th>
				<th align="center" width="18%" style="border-bottom: 1px solid black;"><h1>SUPDT(B)</h1></th>		
				<th>&nbsp;</th>
				<th align="center" width="18%" style="border-bottom: 1px solid black;"><h1>HM/CPA</h1></th>				
			</tr>
			<tr>
				<th align="center" width="18%">
					<img height="50px" width="190px" src="http://cpatos.gov.bd/tosapi/ahm_pilot_signature/marine1.png"/>
				</th>
				<th>&nbsp;</th>
				<th align="center" width="18%">					
					<!--img height="50px" width="120px" src="http://cpatos.gov.bd/tosapi/ahm_pilot_signature/HMaster.png" /-->
				</th>
				<th>&nbsp;</th>
				<th align="center" width="18%">					
					<!--img height="50px" width="120px" src="http://cpatos.gov.bd/tosapi/ahm_pilot_signature/HMaster.png" /-->
				</th>
				<th>&nbsp;</th>
				<th align="center" width="18%">					
					<!--img height="50px" width="120px" src="http://cpatos.gov.bd/tosapi/ahm_pilot_signature/HMaster.png" /-->
				</th>
				<th>&nbsp;</th>
				<th align="center" width="18%">					
					<img height="50px" width="120px" src="http://cpatos.gov.bd/tosapi/ahm_pilot_signature/HMaster.png"/>					
				</th>				
			</tr>			
		</table>
	</div>
	<?php } ?>
</body>