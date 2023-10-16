<table align="center" width="90%" border ='0' cellpadding='0' cellspacing='0'>
	<tr>
		<td>
			<table width="100%" border ='0'>
				<tr>
					<?php
					if($modify=="overflow")
					{
					?>
					<td  width="80%" style="padding-left:38%;"><img src="<?php echo IMG_PATH?>cpanew.jpg" /></td>
					<td width="20%" align="left">
						<table width="100%">
							<tr>
								<td style="border: 1px solid black;">Serial No.:</td>
							</tr>
						</table>
					</td>
					<?php
					}
					else if($modify=="all")
					{
					?>
					<td  width="100%" style="padding-left:38%;"><img src="<?php echo IMG_PATH?>cpanew.jpg" /></td>
					<?php
					}
					?>
				</tr>
				<tr>
					<?php
					if($modify=="overflow")
					{
					?>
					<td width="80%" style="padding-left:40%;"><h3><?php echo $heading; ?></h3></td>
					<td width="20%" align="left">
						<table width="100%">
							<tr>
								<td style="border: 1px solid black;">Date:</td>
							</tr>
						</table>
					</td>
					<?php
					}
					else if($modify=="all")
					{
					?>
					<td width="100%" style="padding-left:40%;"><h3><?php echo $heading; ?></h3></td>
					<?php
					}
					?>
				</tr>
			</table>
		</td>
	</tr>
	<tr align="center">
		<td>
			<table style="border-collapse:collapse;" border="1">
				<tr>
					<th>SL.</th>
					<th>Assign Type</th>
					<th>C&F Name</th>
					<th>Cell No</th>
					<th>Container No.</th>
					<th>Size</th>
					<th>Height</th>
					<th>Seal No.</th>
					<th>MLO</th>
					<th>Status</th>
					<th>Vessel Name</th>
					<th>Rotation</th>
					<th>From Block</th>
					<th>From Yard</th>
					<th>Trailer No.</th>
					<th>Remarks</th>
				</tr>
				<?php
				include("dbConection.php");
				for($i=0;$i<count($rslt_removal_list);$i++)
				{
					$slot=$rslt_removal_list[$i]['slot'];
					$query="SELECT ctmsmis.cont_yard('$slot') AS Yard_No";
					$res1=mysqli_query($con_sparcsn4,$query);
					$row=mysqli_fetch_object($res1);
					$smsNumber="";
					$smsNumber=$rslt_removal_list[$i]['SMS_NUMBER'];
				?>
				<tr>
                    <td align="center"><?php echo $i+1;?></td>
					<td align="center"><?php echo $rslt_removal_list[$i]['MFDCH_VALUE']?></td>
					<td align="center"><?php echo $rslt_removal_list[$i]['CF']?></td>
					<td align="center"><?php echo $smsNumber;?></td>
					<td align="center"><?php echo $rslt_removal_list[$i]['CONT_NO']?></td>
					<td align="center"><?php echo $rslt_removal_list[$i]['SIZ']?></td>
					<td align="center"><?php echo $rslt_removal_list[$i]['HEIGHT']?></td>
					<td align="center"><?php echo $rslt_removal_list[$i]['SEAL_NBR1']?></td>
					<td align="center"><?php echo $rslt_removal_list[$i]['MLO']?></td>
					<td align="center"><?php echo $rslt_removal_list[$i]['CONT_STATUS']?></td>
					<td align="center"><?php echo $rslt_removal_list[$i]['V_NAME']?></td>
					<td align="center"><?php echo $rslt_removal_list[$i]['ROT_NO']?></td>
					<td align="center"><?php echo $rslt_removal_list[$i]['SLOT']?></td>
					<!--td align="center"><?php echo $rslt_removal_list[$i]['Yard_No']?></td-->
					<td align="center"><?php echo $row->Yard_No; ?></td>
					<td align="center">&nbsp;</td>
					<td align="center">&nbsp;</td>
				</tr>
				<?php
				}
				?>
			</table>
		</td>
	</tr>
	<tr>
		<?php
		if($modify=="overflow")
		{
		?>
		<td>
			<table border="0" width="100%">
				<tr>
					<td colspan="7">&nbsp;</td>
				</tr>
				<tr>
					<td colspan="7">&nbsp;</td>
				</tr>
				<tr>
					<td style="width:20%" align="center"><u>Sender</u> : <br>CCT/NCT</td>
					<td style="width:2%">&nbsp;</td>
					<td style="width:20%;border-bottom: 2px dotted black;">&nbsp;</td>
					<td style="width:9%">&nbsp;</td>
					<td style="width:20%;border-bottom: 2px dotted black;">&nbsp;</td>
					<td style="width:9%">&nbsp;</td>
					<td style="width:20%;border-bottom: 2px dotted black;">&nbsp;</td>
				</tr>
				<tr>
					<td colspan="7">&nbsp;</td>
				</tr>
				<tr>
					<td style="width:20%" align="center"><u>Receiver</u> : <br>Overflow</td>
					<td style="width:2%">&nbsp;</td>
					<td style="width:20%;border-bottom: 2px dotted black;">&nbsp;</td>
					<td style="width:9%">&nbsp;</td>
					<td style="width:20%;border-bottom: 2px dotted black;">&nbsp;</td>
					<td style="width:9%">&nbsp;</td>
					<td style="width:20%;border-bottom: 2px dotted black;">&nbsp;</td>
				</tr>
				<tr>
					<td colspan="7">&nbsp;</td>
				</tr>
				<tr>
					<td colspan="7">&nbsp;</td>
				</tr>				
			</table>
		</td>
		<?php
		}
		else if($modify=="all")
		{
		?>
		<td>&nbsp;</td>
		<?php
		}
		?>
	</tr>
</table>

<?php
	if(@$options == 'pdf'){
		echo "<script>
				window.print();
			</script>";
	}
?>
