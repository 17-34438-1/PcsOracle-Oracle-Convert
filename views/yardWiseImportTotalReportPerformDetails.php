<html>
	<!--head>
		 <meta http-equiv="refresh" content="20">
		 <style>
			body{font-family: "Calibri";}
		 </style>
	</head-->
	<body>
		<div>
			<div align="center">
				<table>
					<tr>
						<td  align="center"><img align="middle"  width="240px" height="80px" src="<?php echo IMG_PATH?>cpanew.jpg"></td>
					</tr>
				</table>
			</div>			
			<div align="center">
				<table>
					<tr style="margin:5px;">
						<td colspan="12"><font size="4"><b>YARD WISE ASSIGNMENT REPORT DETAILS : </font><font size="4"></font><?php echo $date; ?></b></td>
					</tr>
					<tr style="margin:5px;">
						<td colspan="6"><font size="4"><b>YARD : </font><font size="4"><?php echo $yard; ?></font></b></td>
						<td colspan="6"><font size="4"><b>TYPE : </font><font size="4"><?php echo $val; ?></font></b></td>
					</tr>
					<tr><td>&nbsp;</td><tr>
				</table>
				<table class="table table-bordered table-responsive table-hover table-striped mb-none">
					<tr align="center" bgcolor="#D8D0CE">
						<td><b>Sl No.</b></td>
						<td><b>Container no</b></td>
						<td><b>Status</b></td>
						<td><b>Discharge time</b></td>
						<td><b>Delivery time</b></td>
						<td><b>Assignment type</b></td>
						<td><b>Assignment date</b></td>
						</tr>
				<?php
					$cont_list="";
					for($i=0;$i<count($rslt_cont);$i++){
					$cont_list=$rslt_cont[$i]['CONT'].",".$cont_list;
				?>
				<tr align="center">
						<td><?php  echo $i+1;?></td>
						<td><?php  echo $rslt_cont[$i]['CONT'];?></td>
						<td><?php  echo $rslt_cont[$i]['STATU'];?></td>
						<td><?php  echo $rslt_cont[$i]['DISCHARGETIME'];?></td>
						<td><?php  echo $rslt_cont[$i]['DELIVERY'];?></td>
						<td><?php  echo $rslt_cont[$i]['MFDCH_DESC'];?></td>
						<td><?php  echo $rslt_cont[$i]['ASSIGNMENTDATE'];?></td>
				</tr>

				<?php } ?>
				

				</table>
				</br>
				
				
			</div>
			<table width="100%" border ='1' cellpadding='' cellspacing='0'  style="border-collapse: collapse; table-layout:fixed;">
                <tr>
                    <td  style="padding:5px; white-space: -o-pre-wrap; word-wrap: break-word;white-space: pre-wrap; white-space: -moz-pre-wrap; white-space: -pre-wrap; "><?php  echo substr($cont_list, 0, -1);?></td>
                </tr>
            </table>
		</div>
	</body>
</html>