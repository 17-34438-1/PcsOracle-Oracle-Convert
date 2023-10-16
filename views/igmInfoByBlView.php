<?php if($_POST['fileOptions']=='html'){?>
<HTML>
	<HEAD>
		<TITLE>IGM Info by BL Number(s) REPORT</TITLE>
		<LINK href="../css/report.css" type=text/css rel=stylesheet>
	    <style type="text/css">
<!--
.style1 {font-size: 12px}
-->
        </style>
</HEAD>
<BODY>

	<?php } 
	else if($_POST['fileOptions']=='xl'){
		header("Content-type: application/octet-stream");
		header("Content-Disposition: attachment; filename=IGM_Info_by_BL.xls;");
		header("Content-Type: application/ms-excel");
		header("Pragma: no-cache");
		header("Expires: 0");

	} ?>
	<div align ="center" style="margin:100px;">

	<?php
		if($fileType == "xl"){
			echo "<table width='100%'>";
		}else if($fileType == "html"){
			echo "<table class='table table-bordered table-responsive table-hover table-striped mb-none'>";
		}
	?>
	  <thead>
		<tr height="100px">
			<th align="center" colspan="13">
				<h2><img align="middle"  width="235px" height="75px" style="margin-left:500px;" src="<?php echo IMG_PATH?>cpanew.jpg"></h2>
			</th>
		</tr>
		<tr bgcolor="#ffffff" height="50px">
			<th align="center" colspan="13"><font size="5"><b><?php echo $title; ?></b></font></th>
		</tr>
		<!--tr bgcolor="#ffffff" height="50px"  colspan="10">
			<th colspan="5" align="left"><font size="5"><b><?php echo "Gate:  ". $gate;?></b></font></td>
			<!--th colspan="3" align="center"><font size="5"><b>File No: </b></font></td>
			<th colspan="3" ><font size="5"><b>Duty Hours:</b></font></td-->
			<!--th colspan="5" align="right"><font size="5"><b><?php echo "Date:  ". $date; ?></b></font></td>
		</tr-->
		<tr bgcolor="#ffffff" height="50px"  colspan="10">
			<th colspan="13" align="left"><?php echo "Bl Number(s):  ". $bl_nums;?></td>
			
		</th>
		
		<?php 
				/*  $loadin=0;
				$loadout=0;
				$mtyin=0;
				$mtyout=0; */
		?> 
		
		<tr>
			<th  style="border:1px solid black; font-size:12px;  border-collapse: collapse;" align="center"><b>SL.</b></th>
			<th  style="border:1px solid black; font-size:12px;  border-collapse: collapse;" align="center"><b>ROTATION</b></th>
			<th  style="border:1px solid black; font-size:12px;  border-collapse: collapse;" align="center"><b>VESSEL NAME</b></th>
			<th  style="border:1px solid black; font-size:12px;  border-collapse: collapse;" align="center"><b>LINE NO</b></th>
			<th  style="border:1px solid black; font-size:12px;  border-collapse: collapse;" align="center"><b>CONTAINER</b></th>
			<th  style="border:1px solid black; font-size:12px;  border-collapse: collapse;" align="center"><b>SIZE</b></th>
			<th  style="border:1px solid black; font-size:12px;  border-collapse: collapse;" align="center"><b>HEIGHT</b></th>
			<th  style="border:1px solid black; font-size:12px;  border-collapse: collapse;" align="center"><b>STATUS</b></th>
			<th  style="border:1px solid black; font-size:12px;  border-collapse: collapse;" align="center"><b>WEIGHT</b></th>
			<th  style="border:1px solid black; font-size:12px;  border-collapse: collapse;" align="center"><b>SEAL NO</b></th>
			<th  style="border:1px solid black; font-size:12px;  border-collapse: collapse;" align="center"><b>CONSIGNEE CODE</b></th>
			<th  style="border:1px solid black; font-size:12px;  border-collapse: collapse;" align="center"><b>CONSIGNEE NAME</b></th>
			<th  style="border:1px solid black; font-size:12px;  border-collapse: collapse;" align="center"><b>CPNSIGNEE ADDR.</b></th>

		</tr>
		
		</thead>
		

		<?php
			//	include_once("mydbPConnection.php");
			for($i=0;$i<count($result);$i++) { 
			?>
				<tr border ='1' cellpadding='0' cellspacing='0' style="font-size:12px;  border-collapse: collapse;">
					<td  style="border:1px solid black; font-size:12px;  border-collapse: collapse;" align="center">
						<?php echo $i+1;?>
					</td>
					<td style="border:1px solid black; font-size:12px;  border-collapse: collapse;" align="center">
						<?php echo $result[$i]['Import_Rotation_No']?>
					</td>
					<td style="border:1px solid black; font-size:12px;  border-collapse: collapse;" align="center">
						<?php echo $result[$i]['vsl_name']?>
					</td>
					<td style="border:1px solid black; font-size:12px;  border-collapse: collapse;" align="center">
						<?php echo $result[$i]['Line_No']?>
					</td>
					<td style="border:1px solid black; font-size:12px;  border-collapse: collapse;" align="center">
						<?php echo $result[$i]['cont_number']?>
					</td>
					<td style="border:1px solid black; font-size:12px;  border-collapse: collapse;" align="center">
						<?php echo $result[$i]['cont_size']?>
					</td>
					<td style="border:1px solid black; font-size:12px;  border-collapse: collapse;" align="center">
						<?php echo $result[$i]['cont_height']?>
					</td>
					<td style="border:1px solid black; font-size:12px;  border-collapse: collapse;" align="center">
						<?php echo $result[$i]['cont_status']?>
					</td>
					<td style="border:1px solid black; font-size:12px;  border-collapse: collapse;" align="center">
						<?php echo $result[$i]['cont_gross_weight']?>
					</td>
					<td style="border:1px solid black; font-size:12px;  border-collapse: collapse;" align="center">
						<?php echo $result[$i]['cont_seal_number']?>
					</td>
					<td style="border:1px solid black; font-size:12px;  border-collapse: collapse;" align="center">
						<?php echo $result[$i]['Consignee_code']?>
					</td>
					<td style="border:1px solid black; font-size:12px;  border-collapse: collapse;" align="center">
						<?php echo $result[$i]['Consignee_name']?>
					</td>
					<td style="border:1px solid black; font-size:12px;  border-collapse: collapse;" align="center">
						<?php echo $result[$i]['Consignee_address']?>
					</td>					
							
				</tr>
			<?php
			}
		?>
		<!--tr><td colspan='5' border='0'>Total Container :</td></tr-->
		<!--tr bgcolor="#ffffff" height="50px">
			<th align="center" colspan="10">&nbsp;</th>
		</tr>
		<tr bgcolor="#ffffff" height="50px">
			<th align="center" colspan="10">&nbsp;</th>
		</tr>
		<tr bgcolor="#ffffff" height="50px">
			<th align="center" colspan="10"><font size="5"><b>INWARD CONTAINER REGISTER SUMMARY IN <?php echo "GATE:  ". $gate;?> </b></font></th>
		</tr>
		<tr>
			<td  align="center" colspan="10">
				<table width="40%" border="1" style="border-collapse:collapse; font-size:12px;" >
					<tr>
						<td colspan='2'>LOAD</td>
						<td colspan='2'>EMPTY</td>
						<td colspan='2' align='center'>TOTAL</td>
					</tr>
					<tr>
						<td>IN</td>
						<td>OUT</td>
						<td>IN</td>
						<td>OUT</td>
						<td>IN</td>
						<td>OUT</td>
					</tr>
					<tr>
						<td><?php echo $loadin; ?></td>
						<td><?php echo $loadout; ?></td>
						<td><?php echo $mtyin; ?></td>
						<td><?php echo $mtyout; ?></td>
						<td><?php echo $loadin+$mtyin; ?></td>
						<td><?php echo $loadout+$mtyout; ?></td>
					</tr>
				</table>
			
			</td>
		
		
		</tr-->
		
	</table>
</div>
<?php
if($_POST['fileOptions']=='html'){?>	
		</BODY>
	</HTML>
<?php }?>