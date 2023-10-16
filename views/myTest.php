<?php if($_POST['fileOptions']=='html'){ ?>
<HTML>
	<HEAD>
		<TITLE>Assignment Report</TITLE>
		<LINK href="../css/report.css" type=text/css rel=stylesheet>
	    <style type="text/css">
<!--
.style1 {font-size: 12px}
-->
        </style>
</HEAD>
<BODY>

<?php } else if($_POST['fileOptions']=='xl'){
		header("Content-type: application/octet-stream");
		header("Content-Disposition: attachment; filename=Report.xls;");
		header("Content-Type: application/ms-excel");
		header("Pragma: no-cache");
		header("Expires: 0");
	}
?>

<?php
	include("dbConection.php");
	
	include("mydbPConnection.php");
	
	if($type=="deli1")
	{
		$col = "b.time_out";		
		$head = "Date Wise Delivery Import Container List";
	}
	
	elseif($type=="assign1")
	{
		$col = "b.flex_date01";
		$head = "Assignment Date Wise Import Container List";
	}
	else
	{
		$col = "b.time_in";
		$head = "Discharge Import Container List";
	}
	
	if($this->session->userdata('login_id')=="pass")
	{
		//$substr1=" AND config_metafield_lov.mfdch_value!='CANCEL'";
		$substr1=" AND mfdch_value!='CANCEL'";
		$substr2=" order by sl,Yard_No,shift,proEmtyDate";
		$yardStatus=0;
	}
	else
	{
		//$substr1="AND config_metafield_lov.mfdch_value NOT IN ('CANCEL','OCD','APPCUS','APPOTH','APPREF')";
		$substr1="AND mfdch_value NOT IN ('CANCEL','OCD','APPCUS','APPOTH','APPREF')";
		if($yard_no=="all")
		{
			$substr2=" order by sl,Yard_No,shift,proEmtyDate";
			$yardStatus=0;
		}
		else
		{
			$substr2=" where Yard_No='$yard_no' order by sl,Yard_No,shift,proEmtyDate";
			$yardStatus=1;
		}
		
	}
?>

<table width="100%" border ='0' cellpadding='0' cellspacing='0'>
	<tr bgcolor="#ffffff" align="center" height="100px">
		<td colspan="13" align="center">
			<table border=0 width="100%">
				
				<tr align="center">
					<td colspan="12"><img align="middle" src="<?php echo IMG_PATH?>cpanew.jpg"></td>
				</tr>
			
				<tr align="center">
					<td colspan="12"><font size="4"><b><?php echo $head; ?> </b></font></td>
				</tr>
				<tr align="center">
					<td colspan="12"><font size="4"><b>DATE : <?php echo $fromdate; ?>&nbsp;&nbsp;Yard NO : <?php echo $yard_no; ?></b></font></td>
				</tr>
			</table>		
		</td>
	</tr>
	<tr bgcolor="#ffffff" align="center" height="25px">
		<td colspan="15" align="center"></td>		
	</tr>
</table>
<?php
	$str="SELECT a.cont_no,a.iso_code,a.weight,a.assignmentDate AS assignmentdate,a.unit_gkey,a.goods,a.cf_name AS cf,a.mfdch_desc,
			a.mlo AS mlo,a.rot_no,a.cont_status AS statu,
			(SELECT ctmsmis.mis_exp_unit_load_failed.last_update
			FROM ctmsmis.mis_exp_unit_load_failed WHERE ctmsmis.mis_exp_unit_load_failed.gkey=a.unit_gkey) AS last_update,
			(SELECT ctmsmis.mis_exp_unit_load_failed.user_id
			FROM ctmsmis.mis_exp_unit_load_failed WHERE ctmsmis.mis_exp_unit_load_failed.gkey=a.unit_gkey) AS user_id,
			IF(UCASE(a.stay) LIKE '%STAY%',1,0) AS stay
			FROM ctmsmis.tmp_oracle_assignment a
			WHERE a.assignmentDate BETWEEN '$fromdate' AND '$fromdate'  $substr1";
	$query=mysqli_query($con_sparcsn4,$str);
	$numRows=mysqli_num_rows($query);

	$i=0;
	$j=0;
	$j20=0;
	$j40=0;
	$a20 = 0;
	$a40 = 0;
	$b20 = 0;
	$a40 = 0;
	$c20 = 0;
	$a40 = 0;
	$allCont="";
	$yard="";
	$shift="";
	$tot20 = 0;
	$tot40 = 0;
	$stayed=0;   //stayed
?>
<table width="100%" border ='1' cellpadding='0' cellspacing='0' style="border-collapse: collapse;">
	<tr align="center">
		<td style="border-width:3px;"><b>SlNo.</b></td>
		<td style="border-width:3px;"><b>Container No.</b></td>
		<td style="border-width:3px;"><b>Rotation No.</b></td>
		<td style="border-width:3px;"><b>Type</b></td>
		<td style="border-width:3px;"><b>MLO</b></td>
		<td style="border-width:3px;"><b>Status</b></td>	
		<td style="border-width:3px;"><b>Assignment Type</b></td>			
		<td style="border-width:3px;"><b>C&F Name</b></td>
		<td style="border-width:3px;"><b>Weight</b></td>
		<td style="border-width:3px;"><b>Current Position</b></td>
		<td style="border-width:3px;"><b>Assignment date</b></td>
		<td style="border-width:3px;"><b>Delivery/Empty Date</b></td>
		<td style="border-width:3px;"><b>Stripped By</b></td>
		<td style="border-width:3px;"><b>Remarks</b></td>
	</tr>
	<?php
		$i=0;
		while($row=mysqli_fetch_object($query)){
			
			$i++;
			$unitGKey="";
			$goods="";
			$unitGKey=$row->unit_gkey;			
			$goods=$row->goods;
			
			$strQuery="SELECT   
			(CASE WHEN b.time_out >= to_date(CONCAT('$fromdate',' 08:00:00'),'YYYY-MM-DD HH24-MI-SS') 
			AND b.time_out < to_date(CONCAT('$fromdate',' 16:00:00'),'YYYY-MM-DD HH24-MI-SS') THEN 'Shift A'
			WHEN b.time_out >= to_date(CONCAT('$fromdate',' 16:00:00'),'YYYY-MM-DD HH24-MI-SS') 
			AND b.time_out <
			to_date(CONCAT('$fromdate',' 00:00:00'),'YYYY-MM-DD HH24-MI-SS')+1 THEN 'Shift B'
			WHEN b.time_out >= to_date(CONCAT('$fromdate',' 00:00:00'),'YYYY-MM-DD HH24-MI-SS')+1
			AND b.time_out < to_date(CONCAT('$fromdate',' 08:00:00'),'YYYY-MM-DD HH24-MI-SS')+1 THEN 'Shift C'
			END) AS shift, (CASE WHEN b.time_out IS NULL THEN 2 ELSE 1 END) AS sl
			FROM inv_unit_fcy_visit b
			WHERE b.unit_gkey='$unitGKey'";
			
	 ?>
	 <tr>
		<td align="center"><?php echo $i;?></td>
		<td align="center"><?php if($row->cont_no) echo $row->cont_no; else echo "&nbsp;";?></td>
		<td align="center">
			<?php
				include("dbOracleConnection.php");				
				$j=0;
				$stid=oci_parse($con_sparcsn4_oracle,"SELECT * FROM inv_unit_fcy_visit FETCH FIRST 5 ROWS ONLY");
				oci_execute($stid);	
				while(($roworacle = oci_fetch_object($stid))!= false){					
					echo $roworacle->TIME_OUT;
					echo "<br>";
					echo $roworacle->GKEY;
					echo "<br>";
				}
			?>
		</td>
		<td align="center"></td>
		<td align="center"><?php if($row->mlo) echo $row->mlo; else echo "&nbsp;";?></td>
		<td align="center"><?php if($row->statu) echo $row->statu; else echo "&nbsp;";?></td>
		<td align="center"><?php if($row->mfdch_desc) echo $row->mfdch_desc; else echo "&nbsp;";?></td>
		<td align="center"><?php if($row->cf) echo $row->cf; else echo "&nbsp;";?></td>
		<td align="center"><?php if($row->weight) echo $row->weight; else echo "&nbsp;";?></td>
		<td align="center"><?php echo $carrierPosition;?></td>
		<td align="center"><?php if($row->assignmentdate) echo $row->assignmentdate; else echo "&nbsp;";?></td>
		<td align="center"></td>
		<td align="center"></td>
		<td align="center">
			<?php 
				if (($row->last_update!="") or  ($row->last_update!=null)) {
					echo "Tried to Exp. Load:".$row->last_update." by ".$row->user_id;
				} else{
					echo "&nbsp;";
				}
			?>
		</td>
	 </tr>
	<?php } ?>
</table>

<?php
	if($_POST['fileOptions']=='html'){?>	
		</BODY>
	</HTML>
<?php }?>