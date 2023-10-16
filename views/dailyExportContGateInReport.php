<html>
	<head>
		 <!--meta http-equiv="refresh" content="20"-->
		 <style>
			body{font-family: "Calibri";}
		 </style>
	</head>
	<body>		
		<?php
		include('dbConection.php');
		include("dbOracleConnection.php");


		 $sql_vslName="SELECT vsl_vessels.name FROM vsl_vessel_visit_details vvd
		 INNER JOIN vsl_vessels ON vsl_vessels.gkey=vvd.vessel_gkey
		 WHERE vvd.ib_vyg='$rotation'";

	
		$rslt_vslName = oci_parse($con_sparcsn4_oracle, $sql_vslName);
        oci_execute($rslt_vslName);

	
		
	
		$vsl_name="";
		
	
	while(($row=oci_fetch_object($rslt_vslName))!=false)
		{
		   $vsl_name=$row->NAME;
		}
		?>
		<table width="100%">
			<tr>
				<td align="center"><font size="5">Daily Export Container Gate In <br/> Rotation : <?php echo $rotation; ?> and Vessel :  <?php echo $vsl_name; ?></td>
			</tr>
		</table>
		<table width="100%" border ='1' cellpadding='0' cellspacing='0'>					
			<tr align="center" bgcolor="#D8D0CE">
				<td><b>SlNo.</b></td>
				<td><b>Container No</b></td>
				<td><b>Status</b></td>										
				<td><b>State</b></td>										
				<td><b>Last Position</b></td>
				<td><b>Bat Number</b></td>
				<!--td><b>Advised Time</b></td-->
				<td><b>Gate In Time</b></td>
				<td><b>Keep Down TIme</b></td>
				<td><b>Loading Time</b></td>
			</tr>
			<?php
			
	        $sql_dailyExportContGateIn="select tbl.*,to_char( tbl.time_load + NUMTODSINTERVAL(ranNum,'MINUTE'),'YYYY-MM-DD HH24-MI-SS') as time_load
			From(
			SELECT a.id,SUBSTR(b.transit_state,5) AS state,b.last_pos_slot,b.last_pos_name,rtvd.bat_nbr,b.create_time AS preAd,rtvd.entered_yard,
			(SELECT created FROM srv_event WHERE srv_event.applied_to_gkey=a.gkey AND srv_event.event_type_gkey=23) AS KeepDown,
			(SELECT FLOOR(DBMS_RANDOM.VALUE()*8) FROM dual) as ranNum,b.time_load,a.freight_kind,vsl_vessels.name
			
			FROM road_truck_transactions rtt 
			INNER JOIN road_truck_visit_details rtvd ON rtvd.tvdtls_gkey=rtt.truck_visit_gkey
			INNER JOIN inv_unit a ON a.gkey=rtt.unit_gkey
			INNER JOIN inv_unit_fcy_visit b ON b.unit_gkey=a.gkey
			INNER JOIN argo_carrier_visit arcar ON arcar.gkey=b.actual_ob_cv
			INNER JOIN vsl_vessel_visit_details vvd ON vvd.vvd_gkey=arcar.cvcvd_gkey
			INNER JOIN vsl_vessels ON vsl_vessels.gkey=vvd.vessel_gkey
			INNER JOIN vsl_vessels ON vsl_vessels.gkey=vvd.vessel_gkey
			WHERE vvd.ib_vyg='$rotation' AND b.time_load IS NOT NULL
			ORDER BY b.time_load ASC
			)tbl";
			
			
			$rslt_dailyExportContGateIn = oci_parse($con_sparcsn4_oracle, $sql_dailyExportContGateIn);
            oci_execute($rslt_dailyExportContGateIn);
			
		  
			$i=0;
	
			while(($row_dailyExportContGateIn= oci_fetch_object($rslt_dailyExportContGateIn)) != false)
			{
				$i++;
			?>
				<tr>
					<td><?php echo $i; ?></td>
					<td><?php echo $row_dailyExportContGateIn->ID; ?></td>
					<td><?php echo $row_dailyExportContGateIn->FREIGHT_KIND; ?></td>					
					<td><?php echo $row_dailyExportContGateIn->STATE; ?></td>					
					<td><?php echo $row_dailyExportContGateIn->LAST_POS_NAME; ?></td>
					<td><?php echo $row_dailyExportContGateIn->BAT_NBR; ?></td>
					<!--td><?php echo $row_dailyExportContGateIn->PREAD; ?></td-->
					<td><?php echo $row_dailyExportContGateIn->ENTERED_YARD; ?></td>
					<td><?php echo $row_dailyExportContGateIn->KEEPDOWN; ?></td>
					<td><?php echo $row_dailyExportContGateIn->TIME_LOAD; ?></td>
				</tr>
				
			<?php
			}
			?>
			<?php 
				
			?>
		</table>
		<?php
		  
		    //oci_free_statement($rslt_vslName );
			// var_dump($rslt_vslName);
			//oci_free_statement($rslt_dailyExportContGateIn);
			oci_close($con_sparcsn4_oracle); 
			// var_dump($row_dailyExportContGateIn);
		    mysqli_close($con_sparcsn4);
		
		?>
	</body>
</html>
