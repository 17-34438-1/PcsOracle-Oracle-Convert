<?php 
	if($_POST['options']=='excel'){
		header("Content-type: application/octet-stream");
		header("Content-Disposition: attachment; filename=removal_list_report_view_excel.xls;");
		header("Content-Type: application/ms-excel");
		header("Pragma: no-cache");
		header("Expires: 0");
	}
?>
<table align="center" width="90%" border ='0' cellpadding='0' cellspacing='0'>
	<tr>
		<td>
			<table width="100%" border ='1'>
				<tr>
					<td colspan="12" width="80%" align="center" style="padding-left:38%;"><h1>Chittagong Port Authority</h1></td>
					<td colspan="4" width="20%" align="left">Serial No.:</td>
				</tr>
				<tr>
					<td colspan="12" width="80%" align="center" style="padding-left:40%;"><h3>Removal Tally of Overflow Yard</h3></td>
					<td colspan="4" width="20%" align="left">Date:</td>
				</tr>
			</table>
		</td>
	</tr>
	<tr align="center">
		<td>
			<table style="border-collapse:collapse;" border="1" width="100%">
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
					<th>From Slot</th>
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
					if($modify!="overflow"){
						$key=
						$key=$rslt_removal_list[$i]['key'];
						if($smsNumber==""){
						$query1="SELECT ctmsmis.mis_assignment_entry.phone_number
						FROM ctmsmis.mis_assignment_entry WHERE ctmsmis.mis_assignment_entry.unit_gkey='$key'";
						$res2=mysqli_query($con_sparcsn4,$query1);
						$row1=mysqli_fetch_object($res2);
						$smsNumber=$row1->phone_number;

						}
					}
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
	</tr>
</table>