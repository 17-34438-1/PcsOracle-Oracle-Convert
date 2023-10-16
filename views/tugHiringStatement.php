<table border="0" align="center" width="100%" style="border-collapse:collapse;">
    <tr>
        <td align="center" width="70%">
            <h1>CHITTAGONG PORT AUTHORITY</h1>
            <h2>Marine Department</h2>
            <h2>Tug Hiring Statement</h2>
        </td>				
    </tr>	
</table>
<table border="0" align="center" width="80%" style="border-collapse:collapse;">
	<thead>
		<tr>
			<th style="border:1px solid black; border-collapse: collapse;" align="center"><b>SL.</b></th>
			<th style="border:1px solid black; border-collapse: collapse;" align="center"><b>Vessel Name</b></th>
			<th style="border:1px solid black; border-collapse: collapse;" align="center"><b>Rotation</b></th>
			<th style="border:1px solid black; border-collapse: collapse;" align="center"><b>Description</th>
			<th style="border:1px solid black; border-collapse: collapse;" align="center"><b>Total Hours</b></th>			
			<th style="border:1px solid black; border-collapse: collapse;" align="center"><b>Forwarding Date</b></th>			
		</tr>
	</thead>
	<?php for($i=0;$i<count($tugHireInfoByForwardingId);$i++) { ?>
	<tr border ='1' cellpadding='0' cellspacing='0' style="font-size:15px;  border-collapse: collapse;">
		<td  style="border:1px solid black; border-collapse: collapse;" align="center">
			<?php echo $i+1;?>
		</td>
		<td style="border:1px solid black; border-collapse: collapse;" align="center">
			<?php echo $tugHireInfoByForwardingId[$i]['vessel_name']?>
		</td>
		<td style="border:1px solid black; border-collapse: collapse;" align="center">
			<?php echo $tugHireInfoByForwardingId[$i]['rotation']?>
		</td>
		<td style="border:1px solid black; border-collapse: collapse;" align="center">
			<?php echo $tugHireInfoByForwardingId[$i]['description']?>
		</td>
		<td style="border:1px solid black; border-collapse: collapse;" align="center">
			<?php echo $tugHireInfoByForwardingId[$i]['total_hours']?>
		</td>	
		<td style="border:1px solid black; border-collapse: collapse;" align="center">
			<?php echo date('d/m/Y', strtotime($tugHireInfoByForwardingId[$i]['forward_date'])); ?>
		</td>
	</tr>
	<?php } ?>
</table>
<table border="0" align="center" width="100%" style="border-collapse:collapse;margin-top:10px;">
    <tr>
        <th align="center" width="18%">
            <img height="50px" width="120px" src="http://cpatos.gov.bd/tosapi/ahm_pilot_signature/23026.png"/>
        </th>
        <th>&nbsp;</th>
        <th align="center" width="18%">					
            &nbsp;
        </th>
        <th>&nbsp;</th>
        <th align="center" width="18%">					
            &nbsp;
        </th>
        <th>&nbsp;</th>
        <th align="center" width="18%">					
            <img height="50px" width="120px" src="http://cpatos.gov.bd/tosapi/ahm_pilot_signature/HMaster.png"/>					
        </th>				
    </tr>	
    <tr>
        <th align="center" width="18%" style="border-bottom: 1px solid black;"><h3>Initiator</h3></th>	
        <th>&nbsp;</th>
        <th align="center" width="18%">&nbsp;</th>
        <th>&nbsp;</th>				
        <th align="center" width="18%">&nbsp;</th>		
        <th>&nbsp;</th>
        <th align="center" width="18%" style="border-bottom: 1px solid black;"><h3>HM/CPA</h3></th>				
    </tr>		
</table>
