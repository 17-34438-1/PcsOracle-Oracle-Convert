
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
					<td colspan="12"><font size="4"><b><u>OPERATOR'S RTG HANDLING PERFORMANCE REPORT</u></b></font></td>
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
		<td style="border-width:3px;border-style: double;"><b>Operator</b></td>	
		<td style="border-width:3px;border-style: double;"><b>Equipment</b></td>	
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
	


	
	$query="SELECT placed_by,full_name,SUM(impRcv) AS impRcv,SUM(keepDlv) AS keepDlv,SUM(dlvOcdOffDock) AS dlvOcdOffDock,SUM(shift) AS shift
	FROM (
	SELECT placed_by,
	(CASE WHEN move_kind='DSCH' THEN 1 ELSE 0 END) AS impRcv,
	(CASE WHEN move_kind='YARD' THEN 1 ELSE 0 END) AS keepDlv,
	(CASE WHEN move_kind IN('DLVR','SHOB') THEN 1 ELSE 0 END) AS dlvOcdOffDock,
	(CASE WHEN move_kind='SHFT' THEN 1 ELSE 0 END) AS shift,full_name
	FROM
	(
	SELECT  substr(concat(concat(REGEXP_SUBSTR( placed_by, '[^:]+', 1, 1),':'),REGEXP_SUBSTR( placed_by, '[^:]+', 1, 2 )),5) AS placed_by,full_name,move_kind
	FROM srv_event 
	INNER JOIN inv_move_event ON inv_move_event.mve_gkey=srv_event.gkey
	INNER JOIN xps_che ON (xps_che.gkey=inv_move_event.che_fetch OR xps_che.gkey=inv_move_event.che_carry OR xps_che.gkey=inv_move_event.che_put)
	WHERE srv_event.created
	between to_date(concat('2022-08-14',' 08:00:00'),'YYYY-MM-DD HH24-MI-SS' ) and to_date(concat('2022-08-14',' 20:00:00'),'YYYY-MM-DD HH24-MI-SS')
	AND creator='-xps-' AND (full_name LIKE 'RTG%')
	AND move_kind='DSCH' AND ((SELECT COUNT(placed_by) FROM xps_user WHERE  xps_user.name = substr(concat(concat(REGEXP_SUBSTR( placed_by, '[^:]+', 1, 1),':'),REGEXP_SUBSTR( placed_by, '[^:]+', 1, 2 )),5))<1)
	UNION ALL
	SELECT  substr(concat(concat(REGEXP_SUBSTR( placed_by, '[^:]+', 1, 1),':'),REGEXP_SUBSTR( placed_by, '[^:]+', 1, 2 )),5) AS placed_by,full_name,move_kind
	FROM srv_event 
	INNER JOIN inv_move_event ON inv_move_event.mve_gkey=srv_event.gkey
	INNER JOIN xps_che ON (xps_che.gkey=inv_move_event.che_fetch OR xps_che.gkey=inv_move_event.che_carry OR xps_che.gkey=inv_move_event.che_put)
	WHERE srv_event.created
	$cond
	AND creator='-xps-' AND (full_name LIKE 'RTG%')
	AND move_kind='SHFT' AND ((SELECT COUNT(placed_by) FROM xps_user
	WHERE  xps_user.name = substr(concat(concat(REGEXP_SUBSTR( placed_by, '[^:]+', 1, 1),':'),REGEXP_SUBSTR( placed_by, '[^:]+', 1, 2 )),5))<1)
	UNION ALL
	SELECT  substr(concat(concat(REGEXP_SUBSTR( placed_by, '[^:]+', 1, 1),':'),REGEXP_SUBSTR( placed_by, '[^:]+', 1, 2 )),5) AS placed_by,full_name,move_kind
	FROM srv_event 
	INNER JOIN inv_move_event ON inv_move_event.mve_gkey=srv_event.gkey
	INNER JOIN xps_che ON (xps_che.gkey=inv_move_event.che_fetch OR xps_che.gkey=inv_move_event.che_carry OR xps_che.gkey=inv_move_event.che_put)
	WHERE srv_event.created
	$cond
	AND creator='-xps-' AND (full_name LIKE 'RTG%')
	AND move_kind='DLVR' AND ((SELECT COUNT(placed_by) FROM xps_user 
	WHERE  xps_user.name = substr(concat(concat(REGEXP_SUBSTR( placed_by, '[^:]+', 1, 1),':'),REGEXP_SUBSTR( placed_by, '[^:]+', 1, 2 )),5))<1)
	UNION ALL
	SELECT substr(concat(concat(REGEXP_SUBSTR( placed_by, '[^:]+', 1, 1),':'),REGEXP_SUBSTR( placed_by, '[^:]+', 1, 2 )),5) AS placed_by,full_name,move_kind
	FROM srv_event 
	INNER JOIN inv_move_event ON inv_move_event.mve_gkey=srv_event.gkey
	INNER JOIN xps_che ON (xps_che.gkey=inv_move_event.che_fetch OR xps_che.gkey=inv_move_event.che_carry OR xps_che.gkey=inv_move_event.che_put)
	WHERE srv_event.created 
	$cond
	AND creator='-xps-' AND (full_name LIKE 'RTG%')
	AND move_kind='SHOB' AND ((SELECT COUNT(placed_by) FROM xps_user 
	WHERE  xps_user.name = substr(concat(concat(REGEXP_SUBSTR( placed_by, '[^:]+', 1, 1),':'),REGEXP_SUBSTR( placed_by, '[^:]+', 1, 2 )),5))<1)
	UNION ALL
	SELECT  substr(concat(concat(REGEXP_SUBSTR( placed_by, '[^:]+', 1, 1),':'),REGEXP_SUBSTR( placed_by, '[^:]+', 1, 2 )),5) AS placed_by,full_name,move_kind
	FROM srv_event 
	INNER JOIN inv_move_event ON inv_move_event.mve_gkey=srv_event.gkey
	INNER JOIN xps_che ON (xps_che.gkey=inv_move_event.che_fetch OR xps_che.gkey=inv_move_event.che_carry OR xps_che.gkey=inv_move_event.che_put)
	WHERE srv_event.created
	$cond
	AND creator='-xps-' AND (full_name LIKE 'RTG%')
	AND move_kind='YARD' AND ((SELECT COUNT(placed_by) FROM xps_user
	WHERE  xps_user.name = substr(concat(concat(REGEXP_SUBSTR( placed_by, '[^:]+', 1, 1),':'),REGEXP_SUBSTR( placed_by, '[^:]+', 1, 2 )),5))<1)
	)  )  GROUP BY placed_by,full_name ORDER BY placed_by,full_name";
	

	$stid = oci_parse($con_sparcsn4_oracle, $query);
    oci_execute($stid);
	$m=0;
	$n=0;
	$imRtotal=0;
	$keepDTotal=0;
	$dOffTotal=0;
	$shiftTotal=0;
	while (($row = oci_fetch_object($stid)) != false){
	$m++;
	$imRtotal=$imRtotal+$row->IMPRCV;
	$keepDTotal=$keepDTotal+$row->KEEPDLV;
	$dOffTotal=$dOffTotal+$row-> DLVOCDOFFDOCK;
	$shiftTotal=$shiftTotal+$row->SHIFT;
	

	?>
	
	<tr align="center">
			<td><?php  echo $m;?></td>
			<td><?php if($row->PLACED_BY) echo $row->PLACED_BY; else echo "&nbsp;";?></td>
			<td><?php if($row->PLACED_BY) echo $row->FULL_NAME; else echo "&nbsp;";?></td>
			
			<td><?php if($row->IMPRCV) echo $row->IMPRCV; else echo "&nbsp;"; ?></td>	
			<td><?php  if($row->KEEPDLV) echo $row->KEEPDLV; else echo "&nbsp;";?></td>	
			<td><?php  if($row->DLVOCDOFFDOCK) echo $row->DLVOCDOFFDOCK; else echo "&nbsp;";?></td>	
			<td><?php  if($row->SHIFT) echo $row->SHIFT; else echo "&nbsp;";?></td>	
			<td><?php echo $row->IMPRCV+$row->KEEPDLV+$row->DLVOCDOFFDOCK+$row->SHIFT;?></td>	

		</tr>
		<?php
	
	}
	
	?>
	
		
	
		
		<!--tr bgcolor="#E0FFFF" align="center"><td colspan="6"><font size="4"><b>Total:</b></font></td>
		<td><font size="4"><b><?php // echo $imRtotal;?></b></font></td>
		<td><font size="4"><b><?php // echo $keepDTotal;?></b></font></td>
		<td><font size="4"><b><?php // echo $dOffTotal;?></b></font></td>
		<td><font size="4"><b><?php // echo $shiftTotal;?></b></font></td>
		<td><font size="4"><b><?php // echo $total=$imRtotal+ $keepDTotal+$dOffTotal+$shiftTotal;?></b></font></td>
		</tr-->
</table>

<?php 
oci_free_statement($stid);
oci_close($con_sparcsn4_oracle);
if($_POST['options']=='html'){?>
	
	</BODY>
</HTML>
<?php }?>
