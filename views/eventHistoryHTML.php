<html>
	<head>
		
	</head>
	<body>
		<?php 
			//echo $rotation;
			include("FrontEnd/dbConection.php");
			include("dbOracleConnection.php");
			
			$strQuery = "select vsl_vessels.name,vsl_vessel_visit_details.vvd_gkey
			from vsl_vessel_visit_details
			inner join vsl_vessels on vsl_vessels.gkey=vsl_vessel_visit_details.vessel_gkey
			where vsl_vessel_visit_details.ib_vyg='$rotation'";

			$strQueryRes=oci_parse($con_sparcsn4_oracle,$strQuery);
			oci_execute($strQueryRes);
			$strQueryRow=oci_fetch_object($strQueryRes);
			$vvd_gkey="";
			$vvd_gkey=$strQueryRow->VVD_GKEY;
			$vslName = $strQueryRow->NAME;

			$str = "SELECT ctmsmis.berth_for_vessel('$vvd_gkey') AS berth";
			$result = mysqli_query($con_sparcsn4,$str);
			$row = mysqli_fetch_object($result);
			$berth = $row->berth;
			$sub = substr($row->berth,0,3);

			
			$strQuery1 = "select srv_event_types.id,srv_event_types.description,srv_event.created,srv_event.creator
			from vsl_vessel_visit_details
			inner join srv_event on srv_event.applied_to_gkey=vsl_vessel_visit_details.vvd_gkey
			inner join srv_event_types on srv_event_types.gkey=srv_event.event_type_gkey
			where vsl_vessel_visit_details.ib_vyg='$rotation' and srv_event.applied_to_class='VV'
			and srv_event_types.id not like 'UPDATE%' and srv_event_types.id not like 'PHASE%'";	
			$strQueryRes1=oci_parse($con_sparcsn4_oracle,$strQuery1);	
			oci_execute($strQueryRes1);	
			
		?>
		<table align="center" border="0">
			<tr>
				
			</tr>
			<tr>
				<td>
					<h2 align="center">CHITTAGONG PORT AUTHORITY</h2>
				</td>
			</tr>
			<tr>
				<td>
					<h3 align="center"><?php echo $title;?></h3>
				</td>
			</tr>
			<tr>
				<td>
					<h3 align="center"><?php echo "Rotation : ".$rotation.", Vessel : ".$vslName.", Berth : ".$berth;?></h3>
				</td>
			</tr>
			<tr>
				<td>
					<table border="0" cellspacing="1" cellpadding="5">
						<tr bgcolor="#9999CC">
							<th>Event Id</th><th>Event Description</th><th>Created</th><th>Created By</th>
						</tr>
						<?php 
							$billName = "";
								while (($row2 = oci_fetch_object($strQueryRes1)) != false)
							{
								$eventType="";
								$eventType=$row2->ID;
								$eventTypeQuery="SELECT DISTINCT bill_type FROM ctmsmis.mis_vsl_bill_tarrif WHERE ctmsmis.mis_vsl_bill_tarrif.id=
								IF('$eventType'='PD_SEA_VESSEL',REPLACE('$eventType','_',' '),'$eventType') 
								AND ctmsmis.mis_vsl_bill_tarrif.berth_time=1";
								$eventTypeRes= mysqli_query($con_sparcsn4,$eventTypeQuery);
								$eventTypeRow=mysqli_fetch_object($eventTypeRes);
							
								$bill_type=$eventTypeRow->bill_type;
								$bill_name="";
								if($bill_type==101){
									$bill_name="JETTY CHARGES ON VESSEL";
								}
								else{
									$bill_name="BILL FOR PORT & PILOTAGE CHARGES ON VESSEL";
								}

							if($billName!=$bill_name)
							{
							  $billName = $bill_name;
						?>
							<tr bgcolor="#999999">
								<td colspan="4"><?php echo $bill_name;?></td>
							</tr>
						<?php
							}
						?>
							<tr bgcolor="#CCCCCC">
								<td><?php echo $row2->ID;?></td>
								<td><?php echo $row2->DESCRIPTION;?></td>
								<td><?php echo $row2->CREATED;?></td>
								<td><?php echo $row2->CREATOR;?></td>
							</tr>
						<?php 
							}
							if($sub=="CCT")
							{
						?>						
							<tr bgcolor="#999999">
								<td colspan="4"><?php echo "GANTRY CRANE CHARGES ON CONTAINER";?></td>
							</tr>
						<?php 
							}
						?>
					</table>
				</td>
			</tr>
		</table>
		<?php //mysql_close($con_sparcsn4);?>
	</body>
</html>