<title><?php if($_POST['fileOptions']=="html") echo $title; ?></title>
<body>
	<table width="100%" border ='0' cellpadding='0' cellspacing='0'>
		<tr bgcolor="#ffffff" align="center" height="100px">
			<td colspan="13" align="center">
				<table border=0 width="100%">				
					<tr>
						<td colspan="12" align="center"><img width="250px" height="80px" src="<?php echo IMG_PATH?>cpanew.jpg"></td>
					</tr>
					<tr align="center">
						<td colspan="12"><font size="4"><b><?php echo $title;?> from the date <?php echo $removalFromDate; ?> to <?php echo $removalToDate; ?></b></font></td>
					</tr>
					<tr align="center">
						<td colspan="12"><font size="4"><b></b></font></td>
					</tr>				
				</table>		
			</td>		
		</tr>		
	</table>
	<table width="80%" border ='1' cellpadding='0' cellspacing='0' align="center" style="border-collapse: collapse">
		<thead>
			<tr align="center">
				<th><b>Sl.</b></th>
				<th><b>Vessel Name</b></th>		
				<th><b>Rot No</b></th>
				<th><b>Container</b></th>
				<th><b>Deport Name</b></th>
				<th><b>Deport Code</b></th>
				<th><b>Size</b></th>
				<th><b>Gate</b></th>
				<th><b>Load Time</b></th>
				<th><b>Remarks</b></th>			
			</tr>
		</thead>
		
		<?php
		$grossTotalBox=0;
		$grossTotalTeus=0;
		for($i=0;$i<count($rslt_last24HrsCPAToOffdockRemovalDetail);$i++)
		{

			include("dbConection.php");
			
			
			$offDockQuary="SELECT ctmsmis.offdoc.code FROM ctmsmis.offdoc  WHERE ctmsmis.offdoc.id=$rslt_last24HrsCPAToOffdockRemoval[$i]['destination']";
			$offDockRslt=mysqli_query($con_sparcsn4,$offDockQuary);
			$rowOffDock=mysqli_fetch_object($offDockRslt);
			$offDock=$rowOffDock->code;
		?>
		<tbody>
			<tr>
				<td align="center"><?php echo $i+1; ?></td>
				<td align="center"><?php echo $rslt_last24HrsCPAToOffdockRemovalDetail[$i]['VSLNAME']; ?></td>		
				<td align="center"><?php echo $rslt_last24HrsCPAToOffdockRemovalDetail[$i]['ROT']; ?></td>		
				<td align="center"><?php echo $rslt_last24HrsCPAToOffdockRemovalDetail[$i]['ID']; ?></td>		
				<!-- <td align="center"><?php echo $rslt_last24HrsCPAToOffdockRemovalDetail[$i]['offdock']; ?></td>		 -->
				<td align="center"><?php echo $offDock; ?></td>	
				<<td align="center"><?php echo $rslt_last24HrsCPAToOffdockRemovalDetail[$i]['DESTINATION']; ?></td>		 
				<td align="center"><?php echo $rslt_last24HrsCPAToOffdockRemovalDetail[$i]['SIZ']; ?></td>						
				<td align="center"><?php echo $rslt_last24HrsCPAToOffdockRemovalDetail[$i]['GATE']; ?></td>						
				<td align="center"><?php echo $rslt_last24HrsCPAToOffdockRemovalDetail[$i]['TIME_LOAD']; ?></td>						
				<td align="center"><?php echo $rslt_last24HrsCPAToOffdockRemovalDetail[$i]['REMARK']; ?></td>					
			</tr>
		</tbody>
		<?php
			// $grossTotalBox=$grossTotalBox+$rslt_last24HrsCPAToOffdockRemovalDetail[$i]['totbox'];
			// $grossTotalTeus=$grossTotalTeus+$rslt_last24HrsCPAToOffdockRemovalDetail[$i]['totteus'];
		}
		?>
		<!--tr align="center">
			<td colspan=7 align="center"><b>Total</b></td>
			<td align="center"><?php echo $grossTotalBox; ?></td>
			<td align="center"><?php echo $grossTotalTeus; ?></td>
		</tr-->
	</table>
	<?php
	mysqli_close($con_sparcsn4);
	 ?>
</body>
