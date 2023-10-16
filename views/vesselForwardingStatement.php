<body>	
	<!--div class="container">
		<table border="0" align="center" width="100%" style="border-collapse:collapse;">
			<tr>
				<td align="center" width="70%">
					<h1>CHATTOGRAM PORT AUTHORITY</h1>
					<h2>VESSEL FORWARDING STATEMENT</h2>
					<h2>From Date : <?php echo date('d/m/Y', strtotime($from_date)); ?>, To Date : <?php echo date('d/m/Y', strtotime($to_date)); ?></h2>
				</td>				
			</tr>	
		</table>
	</div-->
	<?php 
		include_once("mydbPConnectionn4.php");
		$i=1; 
		for($p=1;$p<=$totalPage;$p++) {
		$limitStartingFrom = ($p-1)*20;
	?>
	
	<div <?php if($p<$totalPage) { ?> style="page-break-after: always" <?php } ?> >
		<table border="0" align="center" width="100%" style="border-collapse:collapse;">
			<tr>
				<td align="center" width="70%">
					<h1>CHATTOGRAM PORT AUTHORITY</h1>
					<h2>VESSEL FORWARDING STATEMENT</h2>
					<h2>From Date : <?= date('d/m/Y', strtotime($from_date)); ?>, To Date : <?= date('d/m/Y', strtotime($to_date)); ?></h2>
				</td>				
			</tr>	
		</table>
		<?php 
				if($login_id=="HMaster"){
					$sqlReport = "SELECT ctmsmis.vsl_vssel_info.vvd_gkey,DATE(ctmsmis.vsl_vssel_info.ata) AS ata,
					DATE(ctmsmis.vsl_vssel_info.atd) AS atd, ctmsmis.vsl_vssel_info.vsl_name AS vsl_name,
					ctmsmis.vsl_vssel_info.ves_captain, ctmsmis.vsl_vssel_info.loa_cm,
					ctmsmis.vsl_vssel_info.grt AS grt,ctmsmis.vsl_vssel_info.nrt AS nrt,ctmsmis.vsl_vssel_info.agent,
					ctmsmis.vsl_vssel_info.cntry_name, 
					
					CONCAT(DATE_FORMAT(ata,'%d/%m/%Y'),' ',TIME(ata)) AS ata,
					CONCAT(DATE_FORMAT(atd,'%d/%m/%Y'),' ',TIME(atd)) AS atd,
					ctmsmis.vsl_vssel_info.ib_vyg, 
					DATE_FORMAT(ctmsmis.vsl_forward_info.master_forward_at,'%d/%m/%Y %H:%i:%s') AS forwarded_dt,
					vsl_forward_info.master_forward_by AS forwarded_by
					FROM ctmsmis.vsl_forward_info
                    INNER JOIN ctmsmis.vsl_vssel_info ON ctmsmis.vsl_vssel_info.vvd_gkey=ctmsmis.vsl_forward_info.vvd_gkey
					WHERE master_forward_at >='$from_date' AND master_forward_at <= '$to_date' limit $limitStartingFrom,20";
				} else {
					$sqlReport = "SELECT ctmsmis.vsl_vssel_info.vvd_gkey,DATE(ctmsmis.vsl_vssel_info.ata) AS ata,
					DATE(ctmsmis.vsl_vssel_info.atd) AS atd, ctmsmis.vsl_vssel_info.vsl_name AS vsl_name,
					ctmsmis.vsl_vssel_info.ves_captain, ctmsmis.vsl_vssel_info.loa_cm,
					ctmsmis.vsl_vssel_info.grt AS grt,ctmsmis.vsl_vssel_info.nrt AS nrt,ctmsmis.vsl_vssel_info.agent,
					ctmsmis.vsl_vssel_info.cntry_name, 
					
					CONCAT(DATE_FORMAT(ata,'%d/%m/%Y'),' ',TIME(ata)) AS ata,
					CONCAT(DATE_FORMAT(atd,'%d/%m/%Y'),' ',TIME(atd)) AS atd,
					ctmsmis.vsl_vssel_info.ib_vyg, 
					DATE_FORMAT(ctmsmis.vsl_forward_info.master_forward_at,'%d/%m/%Y %H:%i:%s') AS forwarded_dt,
					vsl_forward_info.master_forward_by AS forwarded_by
					FROM ctmsmis.vsl_forward_info
                    INNER JOIN ctmsmis.vsl_vssel_info ON ctmsmis.vsl_vssel_info.vvd_gkey=ctmsmis.vsl_forward_info.vvd_gkey
					WHERE marine_forward_by='$login_id' AND  master_forward_at >='$from_date' AND master_forward_at <= '$to_date' limit $limitStartingFrom,20";
				}
				
				$resReport = mysqli_query($con_sparcsn4,$sqlReport);
				
				
		?>
		<table border="1" style="border-collapse:collapse;margin-top:12px;" align="center" width="100%">
			
			<tr>
				<th rowspan="2" class="text-center"><h2>Sl</h2></th>
				<th rowspan="2" class="text-center"><h2>Vessel Name</h2></th>
				<th rowspan="2" class="text-center"><h2>Date of Arrival</h2></th>
				<th rowspan="2" class="text-center"><h2>Date of Departure</h2></th>		
				<th rowspan="2" class="text-center"><h2>Rotation</h2></th>
				<th rowspan="2" class="text-center"><h2>Country</h2></th>
				<th colspan="2" class="text-center"><h2>Tonage</h2></th>   
				<th rowspan="2" class="text-center"><h2>Local Agent</h2></th>      
				<th rowspan="2" class="text-center"><h2>Forwarded to Acc at</h2></th> 
			</tr>
			<tr>
				<th><h2>GT</h2></th>
				<th><h2>NT</h2></th>
			</tr>
				
			<?php while($rtnReport=mysqli_fetch_object($resReport)) { ?>
			<tr>
				<td align="center" style="font-size: 12pt;"><?php echo $i++; ?></td>
				<td align="center" style="font-size: 12pt;"><?php echo $rtnReport->vsl_name;?></td>
				<td align="center" style="font-size: 12pt;"><?php echo $rtnReport->ata;?></nobr></td>
				<td align="center" style="font-size: 12pt;"><?php echo $rtnReport->atd;?></td>
				<td align="center" style="font-size: 12pt;"><?php echo $rtnReport->ib_vyg;?></td>
				<td align="center" style="font-size: 12pt;"><?php echo $rtnReport->cntry_name;?></td>
				<td align="center" style="font-size: 12pt;"><?php echo $rtnReport->grt;?></td>
				<td align="center" style="font-size: 12pt;"><?php echo $rtnReport->nrt;?></td>
				<td align="center" style="font-size: 12pt;"><?php echo $rtnReport->name;?></td>
				<td align="center" style="font-size: 12pt;"><?php echo $rtnReport->forwarded_dt;?></td>
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