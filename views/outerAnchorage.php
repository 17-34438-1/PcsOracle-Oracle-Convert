<table align="center" width="850px">
	<tr>
		<td align="center"><img align="middle" width="235px" height="75px" src="<?php echo IMG_PATH?>cpanew.jpg"></td>
	</tr>
</table>

<table border="1" style="border-collapse:collapse;" align="center" width="90%">
	<tr>
		<th valign="center" align="center" style="font-size:20px;"><b>Vessel Movement Report from <?php echo $fromDate;?> to <?php echo $toDate;?></b></th>
	</tr>							
</table>

<table border="1" style="border-collapse:collapse;text-align:center;" align="center" width="90%">
	

    <tr>
        <th colspan="10" style="font-size:18px;"><b>Arrival</b></th>
    </tr>

	<?php 
		$basic_class="";
        for($i=0;$i<count($arrivalData);$i++)
		{ 
			$vvd_gkey = $arrivalData[$i]['VVD_GKEY'];
			$arrival_query = "SELECT * FROM doc_vsl_arrival WHERE vvd_gkey = '$vvd_gkey'";
			$arrival_data = $this->bm->dataSelectDB1($arrival_query);
			$pilot_id = "";
			$pilot_name ="";
			if(count($arrival_data)>0)
			{
				$pilot_id = $arrival_data[0]['pilot_name'];
				$pilotNameQuery = "SELECT u_name FROM users WHERE login_id = '$pilot_id' AND login_id != 'devpilot'";
				$pilotName_data = $this->bm->dataSelectDB1($pilotNameQuery);
				if(count($pilotName_data)>0){
					$pilot_name = $pilotName_data[0]['u_name'];
				}
			}
			
			if($basic_class == "" || $basic_class!=$arrivalData[$i]['BASIC_CLAS'])
			{
    ?>
	<tr>
        <td align="left" colspan="10" style="padding-left:7px"><b><?php echo $arrivalData[$i]['BASIC_CLAS']; ?></b></td>
    </tr> 
	<tr>
        <td colspan="10">&nbsp;</td>
    </tr> 
	<tr>
		<th rowspan="2">SL</th>
		<th rowspan="2">Date</th>
		<th rowspan="2">Rotation</th>
		<th rowspan="2">Vessel Name</th>
		<th rowspan="2">Country</th>
		<th colspan="2">Tonage</th>
		<th rowspan="2">Length</th>
		<th rowspan="2">Pilot Name</th>
		<th rowspan="2">Local Agent</th>
	</tr>

	<tr>
		<th>GT</th>
		<th>NT</th>
	</tr>
	<?php
			}
			$basic_class=$arrivalData[$i]['BASIC_CLAS'];
	?>
	<tr align="center">
		<td><?php echo $i+1;?></td>
		<td><?php echo $arrivalData[$i]['DAT'];?></td>												
		<td><?php echo $arrivalData[$i]['IB_VYG'];?></td>
		<td><?php echo $arrivalData[$i]['VSL_NAME'];?></td>
		<td><?php echo $arrivalData[$i]['CNTRY_NAME'];?></td>
		<td><?php echo $arrivalData[$i]['GRT'];?></td>
        <td><?php echo $arrivalData[$i]['NRT'];?></td>
        <td><?php echo $arrivalData[$i]['LOA_METER'];?></td>
        <td><?php echo $pilot_name; ?></td>
        <td><?php echo $arrivalData[$i]['NAME'];?></td>
	</tr>
	<?php 
        } 
    ?>

    <tr>
        <th colspan="10" style="font-size:18px;"><b>Depart</b></th>
    </tr>

	<?php 		
		$basic_class="";
        for($i=0;$i<count($departData);$i++)
		{ 
			$vvd_gkey = $departData[$i]['VVD_GKEY'];
			$depart_query = "SELECT * FROM doc_vsl_depart WHERE vvd_gkey = '$vvd_gkey'";
			$depart_data = $this->bm->dataSelectDB1($depart_query);
			$pilot_id ="";
			$pilot_name = "";
			if(count($depart_data)>0)
			{
				$pilot_id = $depart_data[0]['pilot_name'];
				$pilotNameQuery = "SELECT u_name FROM users WHERE login_id = '$pilot_id' AND login_id != 'devpilot'";
				$pilotName_data = $this->bm->dataSelectDB1($pilotNameQuery);
				if(count($pilotName_data)>0)
				{
					$pilot_name = $pilotName_data[0]['u_name'];
				}
			}
			
			if($basic_class == "" || $basic_class!=$departData[$i]['BASIC_CLAS'])
			{
    ?>
	<tr>
        <td align="left" colspan="10" style="padding-left:7px"><b><?php echo $departData[$i]['BASIC_CLAS']; ?></b></td>
    </tr> 
	<tr>
        <td colspan="10">&nbsp;</td>
    </tr> 
	<tr>
		<th rowspan="2">SL</th>
		<th rowspan="2">Date</th>
		<th rowspan="2">Rotation</th>
		<th rowspan="2">Vessel Name</th>
		<th rowspan="2">Country</th>
		<th colspan="2">Tonage</th>
		<th rowspan="2">Length</th>
		<th rowspan="2">Pilot Name</th>
		<th rowspan="2">Local Agent</th>
	</tr>

	<tr>
		<th>GT</th>
		<th>NT</th>
	</tr>
	<?php
			}
			$basic_class=$departData[$i]['BASIC_CLAS'];
	?>
	<tr align="center">
		<td><?php echo $i+1;?></td>
		<td><?php echo $departData[$i]['DAT'];?></td>												
		<td><?php echo $departData[$i]['IB_VYG'];?></td>
		<td><?php echo $departData[$i]['VSL_NAME'];?></td>
		<td><?php echo $departData[$i]['CNTRY_NAME'];?></td>
		<td><?php echo $departData[$i]['GRT'];?></td>
        <td><?php echo $departData[$i]['NRT'];?></td>
        <td><?php echo $departData[$i]['LOA_METER'];?></td>
        <td><?php echo $pilot_name; ?></td>
        <td><?php echo $departData[$i]['NAME'];?></td>

		
	</tr>
	<?php 
        } 
    ?>

	<tr>
        <th colspan="10" style="font-size:18px;"><b>Shift</b></th>
    </tr>

	<?php 
		$basic_class="";
        for($i=0;$i<count($shiftData);$i++)
		{ 
			$vvd_gkey = $shiftData[$i]['VVD'];
			$shift_query = "SELECT * FROM doc_vsl_shift WHERE vvd_gkey = '$vvd_gkey'";
			$shift_data = $this->bm->dataSelectDB1($shift_query);
			$pilot_id ="";
			$pilot_name = "";
			if(count($shift_data)>0)
			{
				$pilot_id = $shift_data[0]['pilot_name'];
				$pilotNameQuery = "SELECT u_name FROM users WHERE login_id = '$pilot_id' AND login_id != 'devpilot'";
				$pilotName_data = $this->bm->dataSelectDB1($pilotNameQuery);
				$pilot_name = "";
				if(count($pilotName_data)>0)
				{
					$pilot_name = $pilotName_data[0]['u_name'];
				}
			}

			if($basic_class == "" || $basic_class!=$shiftData[$i]['BASIC_CLASS'])
			{
    ?>
	<tr>
        <td align="left" colspan="10" style="padding-left:7px"><b><?php echo $shiftData[$i]['BASIC_CLASS']; ?></b></td>
    </tr> 
	<tr>
        <td colspan="10">&nbsp;</td>
    </tr> 
	<tr>
		<th rowspan="2">SL</th>
		<th rowspan="2">Date</th>
		<th rowspan="2">Rotation</th>
		<th rowspan="2">Vessel Name</th>
		<th rowspan="2">Country</th>
		<th colspan="2">Tonage</th>
		<th rowspan="2">Length</th>
		<th rowspan="2">Pilot Name</th>
		<th rowspan="2">Local Agent</th>
	</tr>

	<tr>
		<th>GT</th>
		<th>NT</th>
	</tr>
	<?php
			}
			$basic_class=$shiftData[$i]['BASIC_CLASS'];
	?>
	<tr align="center">
		<td><?php echo $i+1;?></td>
		<td><?php echo substr($shiftData[$i]['DAT'],0,10);?></td>												
		<td><?php echo $shiftData[$i]['IB_VYG'];?></td>
		<td><?php echo $shiftData[$i]['VSL_NAME'];?></td>
		<td><?php echo $shiftData[$i]['CNTRY_NAME'];?></td>
		<td><?php echo $shiftData[$i]['GRT'];?></td>
        <td><?php echo $shiftData[$i]['NRT'];?></td>
        <td><?php echo $shiftData[$i]['LOA_METER'];?></td>
        <td><?php echo $pilot_name; ?></td>
        <td><?php echo $shiftData[$i]['NAME'];?></td>
	</tr>
	<?php 
        } 
    ?>
</table>