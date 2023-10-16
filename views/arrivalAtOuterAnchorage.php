<table align="center" width="850px">
	<tr>
		<td align="center"><img align="middle" width="235px" height="75px" src="<?php echo IMG_PATH?>cpanew.jpg"></td>
	</tr>
</table>

<table border="1" style="border-collapse:collapse;" align="center" width="85%">
	<form method="POST" enctype="multipart/form-data" action="<?php echo site_url("report/shedDeliveryOrderInfoEntry") ?>">
		<tr>
			<th valign="center" align="center" style="font-size:20px;"><b>Outer Anchorage Report from <?php echo $fromDate;?> to <?php echo $toDate;?></b></th>
		</tr>							
</table>

<table border="1" style="border-collapse:collapse;text-align:center;" align="center" width="85%">
	<thead>
        <tr>
            <th rowspan="2">SL</th>
            <th rowspan="2">Date</th>
            <th rowspan="2">Ship Name</th>
            <th rowspan="2">Nationality</th>
            <th colspan="2">Tonage</th>
            <th rowspan="2">Length</th>
            <th rowspan="2">Master Name</th>
            <th rowspan="2">Local Agent</th>
        </tr>

        <tr>
            <th>GT</th>
            <th>NT</th>
        </tr>
    </thead>

    <tr>
        <th colspan="9" style="font-size:18px;"><b>Arrival</b></th>
    </tr>

	<?php 
        $j=1;
        for($i=0;$i<count($pilotageData);$i++)
		{ 
			$vvd_gkey = $pilotageData[$i]['vvd_gkey'];
			$arrival_query = "SELECT * FROM doc_vsl_arrival WHERE vvd_gkey = '$vvd_gkey'";
			$arrival_data = $this->bm->dataSelectDB1($arrival_query);
			$pilot_id = "";
			$pilot_name ="";
			if(count($arrival_data)>0)
			{
				$pilot_id = $arrival_data[0]['pilot_name'];
				$pilotNameQuery = "SELECT u_name FROM users WHERE login_id = '$pilot_id'";
				$pilotName_data = $this->bm->dataSelectDB1($pilotNameQuery);
				if(count($pilotName_data)>0)
				{
					$pilot_name = $pilotName_data[0]['u_name'];
				}
        
    ?>
		<tr align="center">
			<td><?php echo $j;?></td>
			<td><?php echo $pilotageData[$i]['date'];?></td>												
			<td><?php echo $pilotageData[$i]['vsl_name'];?></td>
			<td><?php echo $pilotageData[$i]['cntry_name'];?></td>
			<td><?php echo $pilotageData[$i]['grt'];?></td>
			<td><?php echo $pilotageData[$i]['nrt'];?></td>
			<td><?php echo $pilotageData[$i]['loa_cm'];?></td>
			<td><?php echo $pilot_name; ?></td>
			<td><?php echo $pilotageData[$i]['name'];?></td>
		</tr>
	<?php 
        $j++;
			}
        } 
    ?>

    <tr>
        <th colspan="9" style="font-size:18px;"><b>Depart</b></th>
    </tr>

	<?php 
        $j=1;
        for($i=0;$i<count($pilotageData);$i++){ 
        $vvd_gkey = $pilotageData[$i]['vvd_gkey'];
        $depart_query = "SELECT * FROM doc_vsl_depart WHERE vvd_gkey = '$vvd_gkey'";
        $depart_data = $this->bm->dataSelectDB1($depart_query);
        $pilot_id ="";
        $pilot_name = "";
        if(count($depart_data)>0)
		{
            $pilot_id = $depart_data[0]['pilot_name'];
            $pilotNameQuery = "SELECT u_name FROM users WHERE login_id = '$pilot_id'";
            $pilotName_data = $this->bm->dataSelectDB1($pilotNameQuery);
            if(count($pilotName_data)>0){
                $pilot_name = $pilotName_data[0]['u_name'];
            }
        
    ?>
	<tr align="center">
		<td><?php echo $j;?></td>
		<td><?php echo $pilotageData[$i]['date'];?></td>												
		<td><?php echo $pilotageData[$i]['vsl_name'];?></td>
		<td><?php echo $pilotageData[$i]['cntry_name'];?></td>
		<td><?php echo $pilotageData[$i]['grt'];?></td>
        <td><?php echo $pilotageData[$i]['nrt'];?></td>
        <td><?php echo $pilotageData[$i]['loa_cm'];?></td>
        <td><?php echo $pilot_name; ?></td>
        <td><?php echo $pilotageData[$i]['name'];?></td>
	</tr>
	<?php 
        $j++;
		}
        } 
    ?>

<tr>
        <th colspan="9" style="font-size:18px;"><b>Shift</b></th>
    </tr>

	<?php 
        $j = 1;
        for($i=0;$i<count($pilotageData);$i++){ 
        $vvd_gkey = $pilotageData[$i]['vvd_gkey'];
        $shift_query = "SELECT * FROM doc_vsl_shift WHERE vvd_gkey = '$vvd_gkey'";
        $shift_data = $this->bm->dataSelectDB1($shift_query);
        $pilot_id ="";
        $pilot_name = "";
        if(count($shift_data)>0)
		{
            $pilot_id = $shift_data[0]['pilot_name'];
            $pilotNameQuery = "SELECT u_name FROM users WHERE login_id = '$pilot_id'";
            $pilotName_data = $this->bm->dataSelectDB1($pilotNameQuery);
            $pilot_name = "";
            if(count($pilotName_data)>0){
                $pilot_name = $pilotName_data[0]['u_name'];
            }

    ?>
	<tr align="center">
		<td><?php echo $j;?></td>
		<td><?php echo $pilotageData[$i]['date'];?></td>												
		<td><?php echo $pilotageData[$i]['vsl_name'];?></td>
		<td><?php echo $pilotageData[$i]['cntry_name'];?></td>
		<td><?php echo $pilotageData[$i]['grt'];?></td>
        <td><?php echo $pilotageData[$i]['nrt'];?></td>
        <td><?php echo $pilotageData[$i]['loa_cm'];?></td>
        <td><?php echo $pilot_name; ?></td>
        <td><?php echo $pilotageData[$i]['name'];?></td>
	</tr>
	<?php 
        $j++;
		}
        } 
    ?>
</table>