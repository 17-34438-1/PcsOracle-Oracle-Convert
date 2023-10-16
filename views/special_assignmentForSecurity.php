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
				<table align="center">
					<tr>
						<td  align="center"><img align="middle"  width="240px" height="80px" src="<?php echo IMG_PATH?>cpanew.jpg"></td>
					</tr>
					<tr>
						<td ><font size="4"><b>ON DATED URGENT DELIVERY ASSIGNMENT - DATE: <?php echo $dt ?></b></font></td>
					</tr>
				</table>
			</div>			
			<div align="center">
				<!--table>
				
					
					<!--tr style="margin:5px;">
						<td colspan="6"><font size="4"><b>YARD : </font><font size="4"><?php echo $yard; ?></font></b></td>
						<td colspan="6"><font size="4"><b>TYPE : </font><font size="4"><?php echo $val; ?></font></b></td>
					</tr-->
					<!--tr><td>&nbsp;</td><tr>
				</table-->
				<table width="90%" border ='1' cellpadding='0' cellspacing='0' style="border-collapse: collapse;">
					<tr align="center" bgcolor="#D8D0CE">
						<th><b>Sl.</b></th>
						<th><b>Container</b></th>
						<th><b>Rotation</b></th>
						<th><b>Vessel Name</b></th>
						<th><b>Size</b></th>
						<th><b>Height</b></th>
						<th><b>Assignment Type</b></th>
						<th><b>C&F </b></th>
						<!--td><b>C&F Address</b></td-->
						<!--td><b>MLO</b></td-->
						<th><b>Yard</b></th>
						<th><b>Block</b></th>
						<th><b>BL</b></th>

					</tr>
					<?php
					include('dbConection.php');
					$cont_list="";
					for($i=0;$i<count($rslt_special_assignment);$i++)
					{
						$cont_list=$cont_list.", ".$rslt_special_assignment[$i]['cont_no'];
						$last_pos_slot="";
						$yard="";
						$block="";
						$last_pos_slot=$rslt_special_assignment[$i]['LAST_POS_SLOT'];
						$sql1="SELECT ctmsmis.cont_yard('$last_pos_slot') AS Yard_No";
						$sqlRslt1=mysqli_query($con_sparcsn4, $sql1);
						$row1=mysqli_fetch_object($$sqlRslt1);
						$yard=$row1->Yard_No;

						$sql2="SELECT ctmsmis.cont_block('$last_pos_slot', '$yard') AS Block_No";
                        $sqlRslt2=mysqli_query($con_sparcsn4, $sql2);
						$row2=mysqli_fetch_object($sqlRslt2);	
						$block=$row2->Block_No;

					?>
					<tr align="center">
						<td><?php echo $i+1;?></td>
						<td><?php echo $rslt_special_assignment[$i]['CONT_NO']; ?></td>
						<td><?php echo $rslt_special_assignment[$i]['ROT_NO']; ?></td>
						<td><?php echo $rslt_special_assignment[$i]['V_NAME']; ?></td>
						<td><?php echo $rslt_special_assignment[$i]['SIZ']; ?></td>
						<td><?php echo $rslt_special_assignment[$i]['HEIGHT']; ?></td>
						<td><?php echo $rslt_special_assignment[$i]['MFDCH_DESC']; ?></td>
						<td><?php echo $rslt_special_assignment[$i]['CNF']; ?></td>
						
						<td><?php echo $yard ; ?></td>
						<td><?php echo $block; ?></td>
						<td><?php echo $rslt_special_assignment[$i]['BL_NBR']; ?></td>

					</tr>
					<?php 
					} 
					$cont_list=substr($cont_list,2,strlen($cont_list));
					?>
				</table>
				</br>
				<!--table width="90%" border ='1' cellpadding='0' cellspacing='0' style="border-collapse: collapse;">
					<tr>
						<td>&nbsp;Number of Container : <?php echo $i; ?></td>
					</tr>
					<tr>
						<td>&nbsp;<?php echo $cont_list; ?></td>
					</tr>
				</table-->
			</div>
		</div>
	</body>
</html>