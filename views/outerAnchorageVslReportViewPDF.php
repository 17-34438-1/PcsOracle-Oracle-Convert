<table width="100%" style="border-collapse: collapse;" align="center">
	<thead>
		<tr bgcolor="#ffffff" height="50px">
			<th align="center" colspan="12"><font size="6"><b>Chattogram Port Authority</b></font></th>
		</tr>
		<tr bgcolor="#ffffff" height="50px">
			<th align="center" colspan="12"><font size="5"><b><?php echo $title; ?></b></font></th>
		</tr>
		<?php if($vslType!=''){ ?>
		<tr bgcolor="#ffffff" height="50px">
			<th align="center" colspan="12"><font size="4"><b><?php echo "Vessel Type : ". $vslType; ?></b></font></th>
		</tr>
		<?php } ?>
		<tr bgcolor="#ffffff" height="50px">
			<th align="center" colspan="12"><font size="4"><b><?php if($_POST['searchType']=='arrival'){echo "Arrival Date : ";} else{ echo"Departure Date : "; }?></b><?php echo $formDate." To ".$toDate; ?></font></th>
		</tr>
		<!--tr>			
			<th style="border:1px solid black; border-collapse: collapse;" align="center" class="text-center">#Sl</th>
            <th style="border:1px solid black; border-collapse: collapse;" align="center" class="text-center">Rotation</th>
			<th style="border:1px solid black; border-collapse: collapse;" align="center" class="text-center">NAME OF VESSEL</th>
			<th style="border:1px solid black; border-collapse: collapse;" align="center" class="text-center">DATE OF ARRIVAL</th>
            <th style="border:1px solid black; border-collapse: collapse;" align="center" class="text-center">ARRIVAL TIME</th>
            <th style="border:1px solid black; border-collapse: collapse;" align="center" class="text-center">DATE OF DEPARTURE</th>
            <th style="border:1px solid black; border-collapse: collapse;" align="center" class="text-center">DEPARTURE TIME </th>
			<th style="border:1px solid black; border-collapse: collapse;" align="center" class="text-center">BEACHING AGENT</th>
            <th style="border:1px solid black; border-collapse: collapse;" align="center" class="text-center">FLAG</th>
            <th style="border:1px solid black; border-collapse: collapse;" align="center" class="text-center">GRT</th>
            <th style="border:1px solid black; border-collapse: collapse;" align="center" class="text-center">NRT</th>
            <th style="border:1px solid black; border-collapse: collapse;" align="center" class="text-center">REMARKS </th>            
        </tr-->
	</thead>
	
	<tbody>
		<?php 
		$vsl_type="";
		$totVsl = 0;
		for($i=0; $i < count($queryList); $i++) 
		{ 
			if($vsl_type=="" || $vsl_type!=$queryList[$i]['vsl_type'])	
			{
				$totVsl = 0;
				$totVsl++;
		?>
				<tr>
					&nbsp;
				</tr>
				<tr>
					<td colspan="12"  align="left"><b><font size='5'><?php echo $queryList[$i]['vsl_type']; ?></font></b></td>
				</tr>
				<tr>			
					<th style="border:1px solid black; border-collapse: collapse;" align="center" class="text-center">#Sl</th>
					<th style="border:1px solid black; border-collapse: collapse;" align="center" class="text-center">Rotation</th>
					<th style="border:1px solid black; border-collapse: collapse;" align="center" class="text-center">NAME OF VESSEL</th>
					<th style="border:1px solid black; border-collapse: collapse;" align="center" class="text-center">DATE OF ARRIVAL</th>
					<th style="border:1px solid black; border-collapse: collapse;" align="center" class="text-center">ARRIVAL TIME</th>
					<th style="border:1px solid black; border-collapse: collapse;" align="center" class="text-center">DATE OF DEPARTURE</th>
					<th style="border:1px solid black; border-collapse: collapse;" align="center" class="text-center">DEPARTURE TIME </th>
					<th style="border:1px solid black; border-collapse: collapse;" align="center" class="text-center">BEACHING AGENT</th>
					<th style="border:1px solid black; border-collapse: collapse;" align="center" class="text-center">FLAG</th>
					<th style="border:1px solid black; border-collapse: collapse;" align="center" class="text-center">GRT</th>
					<th style="border:1px solid black; border-collapse: collapse;" align="center" class="text-center">NRT</th>
					<th style="border:1px solid black; border-collapse: collapse;" align="center" class="text-center">REMARKS </th>            
				</tr>				
		<?php 
			}
			else
			{
				$totVsl++;
			}
		?>
			<tr border ='1' cellpadding='0' cellspacing='0' style="border-collapse: collapse;">
				<td style="border:1px solid black; border-collapse: collapse;" align="center">
					<?php echo $i+1;?>
				</td>
				<td style="border:1px solid black; border-collapse: collapse;" align="center" >
					<?php echo $queryList[$i]['imp_rot'];?>				
				</td>
				<td style="border:1px solid black; border-collapse: collapse;" align="center" >
					<?php echo $queryList[$i]['vsl_name'];?>				
				</td>
				<td style="border:1px solid black; border-collapse: collapse;" align="center">
					<?php echo $queryList[$i]['date_of_arrival'];?>
				</td>
				<td style="border:1px solid black; border-collapse: collapse;" align="center">
					<?php echo $queryList[$i]['time_of_arrival'];?>
				</td>
				<td style="border:1px solid black; border-collapse: collapse;" align="center">
					<?php echo $queryList[$i]['date_of_departure'];?>
				</td>
				<td style="border:1px solid black; border-collapse: collapse;" align="center">
					<?php echo $queryList[$i]['time_of_departure'];?>
				</td>
				<td style="border:1px solid black; border-collapse: collapse;" align="center">
					<?php echo $queryList[$i]['beaching_agent'];?>
				</td>
				<td style="border:1px solid black; border-collapse: collapse;" align="center">
					<?php echo $queryList[$i]['flag'];?>
				</td>
				<td style="border:1px solid black; border-collapse: collapse;" align="center">
					<?php echo $queryList[$i]['grt'];?>
				</td>		   
				<td style="border:1px solid black; border-collapse: collapse;" align="center">
					<?php echo $queryList[$i]['nrt'];?>
				</td>
				<td style="border:1px solid black; border-collapse: collapse;" align="center">
					<?php echo $queryList[$i]['remarks'];?>
				</td>                    					
			</tr>
		<?php 
			$vsl_type=$queryList[$i]['vsl_type'];			
				
			if($vsl_type!=$queryList[($i+1)]['vsl_type'])
			{
		?>
			<tr>
				<td colspan="12" style="border:1px solid black; font-size:12px;  border-collapse: collapse;" align="left">
					<?php echo "<b><font size='5'>Total : ".$totVsl."</font></b>"; ?>
				</td>
			</tr>	
		<?php
			}
			?>
		<?php
		}	// for loop 
		
		$inc = $i;
		
		if(count($rslt_contVsl)>0)
		{
		?>
			<tr>
				&nbsp;
			</tr>
			<tr>
				<td colspan="12"  align="left"><b><font size='5'><?php echo $rslt_contVsl[0]['VSL_TYPE']; ?></font></b></td>
			</tr>
			<tr>			
				<th style="border:1px solid black; border-collapse: collapse;" align="center" class="text-center">#Sl</th>
				<th style="border:1px solid black; border-collapse: collapse;" align="center" class="text-center">Rotation</th>
				<th style="border:1px solid black; border-collapse: collapse;" align="center" class="text-center">NAME OF VESSEL</th>
				<th style="border:1px solid black; border-collapse: collapse;" align="center" class="text-center">DATE OF ARRIVAL</th>
				<th style="border:1px solid black; border-collapse: collapse;" align="center" class="text-center">ARRIVAL TIME</th>
				<th style="border:1px solid black; border-collapse: collapse;" align="center" class="text-center">DATE OF DEPARTURE</th>
				<th style="border:1px solid black; border-collapse: collapse;" align="center" class="text-center">DEPARTURE TIME </th>
				<th style="border:1px solid black; border-collapse: collapse;" align="center" class="text-center">BEACHING AGENT</th>
				<th style="border:1px solid black; border-collapse: collapse;" align="center" class="text-center">FLAG</th>
				<th style="border:1px solid black; border-collapse: collapse;" align="center" class="text-center">GRT</th>
				<th style="border:1px solid black; border-collapse: collapse;" align="center" class="text-center">NRT</th>
				<th style="border:1px solid black; border-collapse: collapse;" align="center" class="text-center">REMARKS </th>            
			</tr>			
			<?php
			for($j=0;$j<count($rslt_contVsl);$j++)
			{
				$inc++;
			?>
			<tr border ='1' cellpadding='0' cellspacing='0' style="font-size:12px;  border-collapse: collapse;">
				<td style="border:1px solid black; font-size:12px;  border-collapse: collapse;" align="center" >
					<?php echo $inc;?>						
				</td>
				<td style="border:1px solid black; font-size:12px;  border-collapse: collapse;" align="center" >
					<?php echo $rslt_contVsl[$j]['IMP_ROT'];?>						
				</td>
				<td style="border:1px solid black; font-size:12px;  border-collapse: collapse;" align="center" >
					<?php echo $rslt_contVsl[$j]['VSL_NAME'];?>						
				</td>
				<td style="border:1px solid black; font-size:12px;  border-collapse: collapse;" align="center" >
					<?php echo $rslt_contVsl[$j]['DATE_OF_ARRIVAL'];?>
				</td>
				<td style="border:1px solid black; font-size:12px;  border-collapse: collapse;" align="center" >
					<?php echo $rslt_contVsl[$j]['TIME_OF_ARRIVAL'];?>
				</td>
				<td style="border:1px solid black; font-size:12px;  border-collapse: collapse;" align="center" >
					<?php echo $rslt_contVsl[$j]['DATE_OF_DEPARTURE'];?>
				</td>
				<td style="border:1px solid black; font-size:12px;  border-collapse: collapse;" align="center" >
					<?php echo $rslt_contVsl[$j]['TIME_OF_DEPARTURE'];?>
				</td>
				<td style="border:1px solid black; font-size:12px;  border-collapse: collapse;" align="center" >
					<?php echo $rslt_contVsl[$j]['BEACHING_AGENT'];?>
				</td>
				<td style="border:1px solid black; font-size:12px;  border-collapse: collapse;" align="center" >
					<?php echo $rslt_contVsl[$j]['FLAG'];?>
				</td>
				<td style="border:1px solid black; font-size:12px;  border-collapse: collapse;" align="center" >
					<?php echo $rslt_contVsl[$j]['GRT'];?>
				</td>                   
				<td style="border:1px solid black; font-size:12px;  border-collapse: collapse;" align="center" >
					<?php echo $rslt_contVsl[$j]['NRT'];?>
				</td>
				<td style="border:1px solid black; font-size:12px;  border-collapse: collapse;" align="center" >
					<?php echo $rslt_contVsl[$j]['REMARKS'];?>
				</td>                    				
			</tr>
			<?php
			}		// second for loop
			?>
			<tr>
				<td colspan="12" style="border:1px solid black; font-size:12px;  border-collapse: collapse;" align="left">
					<?php echo "<b><font size='5'>Total : ".count($rslt_contVsl)."</font></b>"; ?>
				</td>
			</tr>
			<tr>
				<td colspan="12" style="border:1px solid black; font-size:12px;  border-collapse: collapse;" align="left">
					<?php echo "<b><font size='5'>Total : ".(count($queryList)+count($rslt_contVsl))."</font></b>"; ?>
				</td>
			</tr>
		<?php
		}		// if container vessel
		?>
	</tbody>
</table>