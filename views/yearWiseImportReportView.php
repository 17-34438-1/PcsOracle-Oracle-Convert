<html>
<!--title>Year Wise Report</title-->
<body>
<table width="100%" border ='0' cellpadding='0' cellspacing='0'>
<tr bgcolor="#ffffff" align="center" height="100px">
		<td colspan="13" align="center">
			<table border=0 width="100%">
				
				<tr align="center">
					<td colspan="12"><img width="250px" height="80px" src="<?php echo IMG_PATH?>cpanew.jpg"> &nbsp;&nbsp;&nbsp;&nbsp;</td>
				</tr>
			
				<tr align="center">
					<td colspan="12"><font size="4"><b>Year Wise Container Report</u></b></font></td>
				</tr>
				<tr align="center">
					<td colspan="12"><font size="4">From Date  :  <?php echo $fromdate;?>&nbsp;&nbsp;&nbsp;&nbsp; To Date  :   <?php echo $todate;?></font></td>
				</tr>
			</table>
		
		</td>
		
	</tr>
	
	<tr bgcolor="#ffffff" align="center" height="25px">
		<td colspan="15" align="center"></td>
		
	</tr>
	</table>
	<table class="table table-bordered table-responsive table-hover table-striped mb-none">
	<tr align="center">
		<td style="border-width:3px;border-style: double;"><b>TOTAL</b></td>
		<td style="border-width:3px;border-style: double;"><b>FCL CONTAINER</b></td>
		<td style="border-width:3px;border-style: double;"><b>LCL CONTAINER</b></td>
		<td style="border-width:3px;border-style: double;"><b>EMPTY CONTAINER</b></td>
		<td style="border-width:3px;border-style: double;"><b>CONT 20</b></td>
		<td style="border-width:3px;border-style: double;"><b>CONT 40</b></td>
		<td style="border-width:3px;border-style: double;"><b>CONT 45</b></td>
		<td style="border-width:3px;border-style: double;"><b>TEUS</b></td>
	</tr>

<?php

	for($i=0;$i<count($reslt);$i++)
		{ ?>
		<tr align="center">
			<td width="20px"  align="center">
				<?php echo $reslt[$i]['TOT']; ?>
			</td>
			<td align="center">
				<?php echo $reslt[$i]['FCL_CONT']; ?>
			</td>
			<td align="center">
				<?php echo $reslt[$i]['LCL_CONT']; ?>
			</td>
			<td align="center">
				<?php echo $reslt[$i]['MTY_CONT']; ?>
			</td>
			<td align="center">
				<?php echo $reslt[$i]['CONT_20']; ?>
			</td>
			<td align="center">
				<?php echo $reslt[$i]['CONT_40']; ?>
			</td>
			<td align="center">
				<?php echo $reslt[$i]['CONT_45']; ?>
			</td>
			<td align="center">
				<?php $teus=($reslt[$i]['CONT_45']*2)+($reslt[$i]['CONT_40']*2)+$reslt[$i]['CONT_20']; 
				echo $teus; ?>
			</td>
		</tr>
		<?php } ?>
		</table>
	<br />
	<br />
<?php 
if(@$_POST['options']=='html'){?>	
	</BODY>
</HTML>
<?php }?>
