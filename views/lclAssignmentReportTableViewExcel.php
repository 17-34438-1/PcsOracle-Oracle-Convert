
<?php 
	header("Content-type: application/octet-stream");
	header("Content-Disposition: attachment; filename=LCL-Assignment-Report.xls;");
	header("Content-Type: application/ms-excel");
	header("Pragma: no-cache");
	header("Expires: 0");
   //$rot=$_REQUEST['rot']; 	
?>

	
	<table align="center" width="50%" border ='1' cellpadding='0' cellspacing='0'>
		<tr>
			<th colspan="12" align="center"><font size="4">CHITTAGONG PORT AUTHORITY</font><br><?php echo $title; ?></th>
		</tr>
		
		<tr bgcolor="#A9A9A9" align="center" height="25px">
			<!--th style="border:1px solid black; font-size:11px">Y/N</th-->
			<th style="border:1px solid black; font-size:11px">SL</th>
			<th style="border:1px solid black; font-size:11px"><nobr>Container No</nobr></th>
			<th style="border:1px solid black; font-size:11px">Size</th>
			<th style="border:1px solid black; font-size:11px">Height</th>
			<th style="border:1px solid black; font-size:11px">Rotation</th>
			<th style="border:1px solid black; font-size:11px"><nobr>Vessel Name</nobr></th>
			<th style="border:1px solid black; font-size:11px">MLO</th>
			<th style="border:1px solid black; font-size:11px">STV</th>
			<th style="border:1px solid black; font-size:11px"><nobr>Cont at Shed</nobr></th>
			<th style="border:1px solid black; font-size:11px"><nobr>Description of Cargo</nobr></th>
			<th style="border:1px solid black; font-size:11px"><nobr>Landing Date</nobr></th>
			<th style="border:1px solid black; font-size:11px">Remarks</th>			
		</tr>
		<?php
		for($i=0;$i<count($lclAssignmentList);$i++) 
		{
		//$shed = $lclAssignmentList[$i]['cont_loc_shed'];
		?>	
		<tr>
			<!--td>
				<img src="<?php echo IMG_PATH;?>lclcheck.jpg">
			</td-->
			 <td align="center" style="font-size:11px">
				<?php echo $i+1;?>
			</td >
			<td align="center" style="font-size:11px">
			   <?php echo $lclAssignmentList[$i]['cont_number'];?>
			</td>
			<td align="center" style="font-size:11px">
			   <?php echo $lclAssignmentList[$i]['cont_size']?>
			</td>
			<td align="center" style="font-size:11px">
				<?php echo $lclAssignmentList[$i]['cont_height'];?>
			</td>
			<td align="center" style="font-size:11px">
				<?php echo $lclAssignmentList[$i]['Import_Rotation_No'];?>
			</td>   
			<td align="center" style="font-size:11px">
				<?php echo $lclAssignmentList[$i]['Vessel_Name'];?>
			</td>  
			<td align="center" style="font-size:11px">
				<?php echo $lclAssignmentList[$i]['mlocode'];?>
			</td>          
			<td align="center" style="font-size:11px">
				<?php echo $lclAssignmentList[$i]['stv'];?>
			</td>
			<td align="center" style="font-size:11px">
				<?php echo $lclAssignmentList[$i]['cont_loc_shed'];?>
			</td>  
			<td align="center" style="font-size:11px">
				<?php echo $lclAssignmentList[$i]['description_cargo'];?>
			</td> 
			<td align="center" style="font-size:11px">
				<?php echo $lclAssignmentList[$i]['landing_time'];?>
			</td> 
			<td align="center" style="font-size:11px">
				<?php echo $lclAssignmentList[$i]['remarks'];?>
			</td> 		  
		</tr>		
		<?php
		} 
		?>
	</table>