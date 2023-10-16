<div align ="center" style="margin:100px;">

	<!--div align="center" style="font-size:18px">
			<title><img align="middle"  width="220px" height="70px" src="<?php echo IMG_PATH?>cpanew.jpg"></title>
	</div>
		<div align="center"><font size="5"><b>INWARD & OUTWARD CONTAINER REGISTER</b></font></div-->

	<table width="100%">
	  <thead>
		<tr height="100px">
			<th align="center" colspan="10">
				<h2><img align="middle"  width="235px" height="75px" src="<?php echo IMG_PATH?>cpanew.jpg"></h2>
			</th>
		</tr>
		<tr bgcolor="#ffffff" height="50px">
			<th align="center" colspan="10"><font size="5"><b>Organization User Report</b></font></th>
		</tr>
		<tr bgcolor="#ffffff" height="50px">
			<th align="center" colspan="10"><font size="4"><b>Org.Type : <?php echo $orgTypeName; ?></b></font></th>
		</tr>
		
		<tr>
			<th style="border:1px solid black; font-size:12px;  border-collapse: collapse;" align="center"><b>SL.</b></th>
			<th style="border:1px solid black; font-size:12px;  border-collapse: collapse;" align="center"><b>ORGANISATION</b></th>
			<th style="border:1px solid black; font-size:12px;  border-collapse: collapse;" align="center"><b>LOGIN ID</b></th>
			<th style="border:1px solid black; font-size:12px;  border-collapse: collapse;" align="center"><b>USER</b></th>
			<th style="border:1px solid black; font-size:12px;  border-collapse: collapse;" align="center"><b>AIN</b></th>
			<th style="border:1px solid black; font-size:12px;  border-collapse: collapse;" align="center"><b>LICENCE</b></th>
		</tr>
		
		</thead>
		

		<?php
			//	include_once("mydbPConnection.php");
			for($i=0;$i<count($orgTypeList);$i++) { 
			?>
				<tr border ='1' cellpadding='0' cellspacing='0' style="font-size:12px;  border-collapse: collapse;">
					<td  style="border:1px solid black; font-size:12px;  border-collapse: collapse;" align="center">
						<?php echo $i+1;?>
					</td>
					<td style="border:1px solid black; font-size:12px;  border-collapse: collapse;" align="center">
						<?php echo $orgTypeList[$i]['Type_description']?>
					</td>
					<td style="border:1px solid black; font-size:12px;  border-collapse: collapse;" align="center">
						<?php echo $orgTypeList[$i]['login_id']?>
					</td>
					<td style="border:1px solid black; font-size:12px;  border-collapse: collapse;" align="center">
						<?php echo $orgTypeList[$i]['u_name']?>
					</td>
					<td style="border:1px solid black; font-size:12px;  border-collapse: collapse;" align="center">
						<?php echo $orgTypeList[$i]['AIN_No_New']?>
					</td>	
					<td style="border:1px solid black; font-size:12px;  border-collapse: collapse;" align="center">
						<?php echo $orgTypeList[$i]['License_No']?>
					</td>															
					
				</tr>
			<?php
			}
		?>
		
	</table>
</div>
<script>
	window.print();
</script>