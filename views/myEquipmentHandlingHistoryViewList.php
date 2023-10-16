
<?php if($_POST['options']=='html'){?>
<HTML>
	<HEAD>
		<TITLE>Bearth Operator Report</TITLE>
		<meta Http-Equiv="Cache-Control" Content="no-cache">
		<meta Http-Equiv="Pragma" Content="no-cache">
		<meta Http-Equiv="Expires" Content="0"> 
		<!--
		<meta http-equiv="refresh" content="20">
		-->
		<LINK href="../css/report.css" type="text/css rel=stylesheet">
	    <style type="text/css">
<!--
.style1 {font-size: 12px}
-->
        </style>
		
	
	
</HEAD>
<BODY>

	<?php } 
	else if($_POST['options']=='xl'){
		header("Content-type: application/octet-stream");
		header("Content-Disposition: attachment; filename=RTG_PERFORMANCE.xls;");
		header("Content-Type: application/ms-excel");
		header("Pragma: no-cache");
		header("Expires: 0");
		

	}
	
	include("dbOracleConnection.php");
	
	?>
<html>
<title>EQUIPMENT HANDLING PERFORMANCE HISTORY</title>
<body>
<table width="100%" border ='0' cellpadding='0' cellspacing='0'>
<tr bgcolor="#ffffff" align="center" height="100px">
		<td colspan="13" align="center">
			<table border=0 width="100%">
				
				
				<tr align="center">
					<?php 
					if($type=="html")
					{
					?>
					<td colspan="12" align="center"><img width="250px" height="80px" src="<?php echo IMG_PATH?>cpanew.jpg"></td>
					<?php
					}
					else
					{
					?>
					<td colspan="12"><font size="4"><b>CHITTAGONG PORT AUTHORITY,CHITTAGONG</b></font></td>
					<?php
					}
					?>
				</tr>
			
				<tr align="center">
					<td colspan="12"><font size="4"><b><u>EQUIPMENT HANDLING PERFORMANCE HISTORY</u></b></font></td>
				</tr>
				<tr align="center">
					<td colspan="12"><font size="4"><b></b></font></td>
				</tr>
				<tr>
					<!--td colspan="3" align="center"><font size="4"><b>DATE: <?php  Echo $fromdate;?></b></font></td-->
					<td colspan="3" align="center"><font size="4"><b>DATE: <?php if($todate==""){ Echo $fromdate; } else { echo $fromdate." To ".$todate;}?></b></font></td>
					<td colspan="3" align="center"><font size="4"><b>Shift: <?php  Echo $shift;?></b></font></td>
				</tr>

			</table>
		
		</td>
		
	</tr>
	
	<tr bgcolor="#ffffff" align="center" height="25px">
		<td colspan="15" align="center"></td>
		
	</tr>
	</table>
	<table width="100%" border ='1' cellpadding='0' cellspacing='0'>
	<tr  align="center" bgcolor="#e6e6e6">
		<td style="border-width:3px;border-style: double;"><b>SlNo.</b></td>
		<td style="border-width:3px;border-style: double;"><b>RTG # NO.</b></td>
		<td style="border-width:3px;border-style: double;"><b>Start VMT Log in Time</b></td>
		<td style="border-width:3px;border-style: double;"><b>End VMT Log out Time</b></td>
		<td style="border-width:3px;border-style: double;"><b>ID NO</b></td>
		<td style="border-width:3px;border-style: double;"><b>RTG Operator Name</b></td>		
		<td style="border-width:3px;border-style: double;"><b>Import Receiving</b></td>
		<td style="border-width:3px;border-style: double;"><b>Keep Down / Delivery</b></td>
		<td style="border-width:3px;border-style: double;"><b>Delivery (OCD / Off Dock)</b></td>
		<td style="border-width:3px;border-style: double;"><b>Shifting</b></td>
		<td style="border-width:3px;border-style: double;"><b>Total Handling Boxes</b></td>
	</tr>

<?php
	$cond = "";
	if($shift=="timewise")
		
		$cond = "between to_date(concat('$fromdate',' $fromtime'),'YYYY-MM-DD HH24-MI-SS' ) and to_date(concat('$fromdate',' $fromtime'),'YYYY-MM-DD HH24-MI-SS')";
	else if($shift=="Day")
		
		$cond = "between to_date(concat('$fromdate',' 08:00:00'),'YYYY-MM-DD HH24-MI-SS' ) and to_date(concat('$fromdate',' 20:00:00'),'YYYY-MM-DD HH24-MI-SS')";
	else
		
		$cond = "between to_date(concat('$fromdate',' 20:00:00'),'YYYY-MM-DD HH24-MI-SS' ) and to_date(concat('$fromdate, ' 08:00:00'),'YYYY-MM-DD HH24-MI-SS')+1";

	$cond1 = "";
	if($shift=="timewise")
		$cond1 = "between concat('$fromdate',' $fromtime') and concat('$todate',' $totime')";
	else if($shift=="Day")
		$cond1 = "between concat('$fromdate',' 08:00:00') and concat('$fromdate',' 20:00:00')";
	else
		$cond1 = "between concat('$fromdate',' 20:00:00') and concat(DATE_ADD('$fromdate', interval 1 day),' 08:00:00')";
	


	$strQuery = "select eq,created_by,sum(impRcv) as impRcv,sum(keepDlv) as keepDlv,sum(dlvOcdOffDock) as dlvOcdOffDock,sum(shift) as shift
	from(
	select full_name as eq,created_by,
	(case when move_kind='DSCH' then 1 else 0 end) as impRcv,
	(case when move_kind='YARD' then 1 else 0 end) as keepDlv,
	(case when move_kind in('DLVR','SHOB') then 1 else 0 end) as dlvOcdOffDock,
	(case when move_kind='SHFT' then 1 else 0 end) as shift
	from
	(
	select full_name,move_kind,
	(select  placed_by from srv_event where srv_event.gkey=inv_move_event.mve_gkey)  as created_by
	from inv_move_event 
	inner join xps_che on (xps_che.gkey=inv_move_event.che_fetch or xps_che.gkey=inv_move_event.che_carry or xps_che.gkey=inv_move_event.che_put)
	where t_put
	$cond
	and (full_name like 'RTG%') and move_kind='DSCH' 
	
	union all
	
	select full_name,move_kind,
	(select  placed_by from srv_event where srv_event.gkey=inv_move_event.mve_gkey)  as created_by
	from inv_move_event 
	inner join xps_che on (xps_che.gkey=inv_move_event.che_fetch or xps_che.gkey=inv_move_event.che_carry or xps_che.gkey=inv_move_event.che_put)
	where t_put 
	$cond
	and (full_name like 'RTG%') and move_kind='SHFT' 
	
	union all
	
	select full_name,move_kind,
	(select  placed_by from srv_event where srv_event.gkey=inv_move_event.mve_gkey)  as created_by
	from inv_move_event 
	inner join xps_che on (xps_che.gkey=inv_move_event.che_fetch or xps_che.gkey=inv_move_event.che_carry or xps_che.gkey=inv_move_event.che_put)
	where t_put
	$cond
	and (full_name like 'RTG%') and move_kind='DLVR' 
	
	union all
	
	select full_name,move_kind,
	(select  placed_by from srv_event where srv_event.gkey=inv_move_event.mve_gkey)  as created_by
	from inv_move_event 
	inner join xps_che on (xps_che.gkey=inv_move_event.che_fetch or xps_che.gkey=inv_move_event.che_carry or xps_che.gkey=inv_move_event.che_put)
	where t_put
	$cond
	and (full_name like 'RTG%') and move_kind='SHOB' 
	
	union all
	
	select full_name,move_kind,
	(select  placed_by from srv_event where srv_event.gkey=inv_move_event.mve_gkey)  as created_by
	from inv_move_event 
	inner join xps_che on (xps_che.gkey=inv_move_event.che_fetch or xps_che.gkey=inv_move_event.che_carry or xps_che.gkey=inv_move_event.che_put)
	where t_put 
	$cond
	and (full_name like 'RTG%') and move_kind='YARD' order by full_name,move_kind)) group by eq,created_by";
	//echo $strQuery;				

	$stid = oci_parse($con_sparcsn4_oracle, $strQuery);
    oci_execute($stid);

	$i=0;
	$j=0;
	$imRtotal=0;
	$keepDTotal=0;
	$dOffTotal=0;
	$shiftTotal=0;
	include("FrontEnd/mydbPConnectionctms.php");
	while(($row=oci_fetch_object($stid))!=false){
		
	$i++;
	
	

	 $sqlLogTime=mysqli_query($con_ctmsmis,"select logDate,logBy from ctmsmis.mis_equip_log_in_out_info where logEquip='$row->EQ' and logType='in' and  logDate $cond1 order by logDate limit 1");

				
	$rtnLogTime=mysqli_fetch_object($sqlLogTime);

	$log_In = "";	
	$logBy = "";    //Operator

	if($rtnLogTime!=null){
		$log_In=$rtnLogTime->logDate;
		$logBy=$rtnLogTime->logBy;
	}
	
	
	$sqlLogOut=mysqli_query($con_ctmsmis,"select logDate from ctmsmis.mis_equip_log_in_out_info where logEquip='$row->EQ' and logType='out' and  logDate $cond1  order by logDate desc limit 1");
				
	$rtnLogOut=mysqli_fetch_object($sqlLogOut);
	
	$log_Out = "";
	
	if($rtnLogOut!=null){
		$log_Out=$rtnLogOut->logDate;
	}
	// echo $log_Out;
	
	$imRtotal=$imRtotal+$row->IMPRCV;
	$keepDTotal=$keepDTotal+$row->KEEPDLV;
	$dOffTotal=$dOffTotal+$row->DLVOCDOFFDOCK;
	$shiftTotal=$shiftTotal+$row->SHIFT;
	
	?>
	<tr align="center">
			<td><?php  echo $i;?></td>
			<td><?php if($row->EQ) echo $row->EQ; else echo "&nbsp;";?></td>
			<td><?php if($log_In) echo $log_In; else echo "&nbsp;";?></td>
			<td><?php if($log_Out) echo $log_Out; else echo "&nbsp;";?></td>
			
			<td><?php if(isset($row->SHORT_NAME)) echo $row->SHORT_NAME; else echo "&nbsp;";?></td>
			<td><?php if($logBy) echo $logBy; else echo "&nbsp;";?></td>
			<!--td><?php 
			$operator = explode(':',$row->CREATED_BY);
			 echo $operator[1];
			?></td-->	
			<td><?php echo $row->IMPRCV; ?></td>	
			<td><?php echo $row->KEEPDLV;?></td>	
			<td><?php echo $row->DLVOCDOFFDOCK;?></td>	
			<td><?php echo $row->SHIFT;?></td>	
			<td><?php echo $total=$row->IMPRCV+$row->KEEPDLV+$row->DLVOCDOFFDOCK+$row->SHIFT;?></td>	

		</tr>

	<?php  }
	mysqli_close($con_ctmsmis);
	?>
		
		<tr bgcolor="#E0FFFF" align="center"><td colspan="6"><font size="4"><b>Total:</b></font></td>
		<td><font size="4"><b><?php  echo $imRtotal;?></b></font></td>
		<td><font size="4"><b><?php  echo $keepDTotal;?></b></font></td>
		<td><font size="4"><b><?php  echo $dOffTotal;?></b></font></td>
		<td><font size="4"><b><?php  echo $shiftTotal;?></b></font></td>
		<td><font size="4"><b><?php  echo $total=$imRtotal+ $keepDTotal+$dOffTotal+$shiftTotal;?></b></font></td>
		</tr>
</table>

<?php 
oci_free_statement($stid);
oci_close($con_sparcsn4_oracle);
if($_POST['options']=='html'){?>
	
	</BODY>
</HTML>
<?php }?>
