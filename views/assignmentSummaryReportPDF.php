<body>
	<table width="100%" border ='0' cellpadding='0' cellspacing='0'>
		<tr bgcolor="#ffffff" align="center" height="100px">
			<td colspan="13" align="center">
				<table border='0' width="100%">
					<tr align="center">
						<td align="center"><img  width="150px" src="<?php echo IMG_PATH?>cpanew.jpg"></td>
					</tr>
					<tr>
						<th><font size="4"><b>ASSIGNMENT SUMMARY</b></font></th>
					</tr>
					<tr>
						<th><font size="4"><b>DATE : <?php echo $assignment_date; ?></b></font></th>
					</tr>
				</table>
			</td>
		</tr>	
	</table>
	<table width="80%" border ='1' cellpadding='0' cellspacing='0' style="border-collapse:collapse;" align="center">
		<tr>
			<th rowspan="2"><b>Sl</b></th>
			<th rowspan="2"><b>Assignment Type</b></th>
			<th colspan="6"><b>Yardwise Container</b></th>	
		</tr>			
		<tr>			
			<th><b>CCT</b></th>			
			<th><b>NCT</b></th>			
			<th><b>GCB</b></th>			
			<th><b>SCY</b></th>			
			<th><b>OFY</b></th>	
			<th><b>TOTAL</b></th>			
		</tr>
		<?php
		$tot_cct=0;
		$tot_nct=0;
		$tot_gcb=0;
		$tot_scy=0;
		$tot_ofy=0;
		
		$j=0;
		
		for($i=0;$i<8;$i++)
		{
			if($title_array[$i]==$rslt_assignment_summary[$j]['title'])
			{
				$main_title=$title_array[$i];
				$cnt_cct=$rslt_assignment_summary[$j]['cnt_cct'];
				$cnt_nct=$rslt_assignment_summary[$j]['cnt_nct'];
				$cnt_gcb=$rslt_assignment_summary[$j]['cnt_gcb'];
				$cnt_scy=$rslt_assignment_summary[$j]['cnt_scy'];
				$cnt_ofy=$rslt_assignment_summary[$j]['cnt_ofy'];
				
				$j++;
			}
			else
			{
				$main_title=$title_array[$i];
				$cnt_cct=0;
				$cnt_nct=0;
				$cnt_gcb=0;
				$cnt_scy=0;
				$cnt_ofy=0;
			}
			
			$tot_cct=$tot_cct+$cnt_cct;
			$tot_nct=$tot_nct+$cnt_nct;
			$tot_gcb=$tot_gcb+$cnt_gcb;
			$tot_scy=$tot_scy+$cnt_scy;
			$tot_ofy=$tot_ofy+$cnt_ofy;	
		?>
		<tr>
			<td align="center"><?php echo $i+1; ?></td>
			<td align="center"><?php echo $main_title; ?></td>
			<td align="center"><?php echo $cnt_cct; ?></td>
			<td align="center"><?php echo $cnt_nct; ?></td>
			<td align="center"><?php echo $cnt_gcb; ?></td>
			<td align="center"><?php echo $cnt_scy; ?></td>
			<td align="center"><?php echo $cnt_ofy; ?></td>	
			<td align="center"><b><?php echo $cnt_cct+$cnt_nct+$cnt_gcb+$cnt_scy+$cnt_ofy; ?></b></td>
		</tr>
		<?php
		}
		?>
		<tr>
			<td align="center"></td>
			<td align="center"><b>Grand Total</b></td>			
			<td align="center"><b><?php echo $tot_cct; ?></b></td>			
			<td align="center"><b><?php echo $tot_nct; ?></b></td>			
			<td align="center"><b><?php echo $tot_gcb; ?></b></td>			
			<td align="center"><b><?php echo $tot_scy; ?></b></td>			
			<td align="center"><b><?php echo $tot_ofy; ?></b></td>	
			<td align="center"><b><?php echo $tot_cct+$tot_nct+$tot_gcb+$tot_scy+$tot_ofy; ?></b></td>			
		</tr>
	</table>
</body>