
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
	include("dbConection.php");
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
					<td colspan="12"><font size="4"><b><u>TIMEWISE EQUIPMENT HANDLING PERFORMANCE HISTORY</u></b></font></td>
				</tr>
				<tr align="center">
					<td colspan="12"><font size="4"><b></b></font></td>
				</tr>
				<tr>
					<!--td colspan="3" align="center"><font size="4"><b>DATE: <?php  Echo $fromdate;?></b></font></td-->
					<td colspan="12" align="center"><font size="4"><b>DATE: <?php if($todate==""){ Echo $fromdate; } else { echo $fromdate." To ".$todate;}?> &nbsp;&nbsp;&nbsp;  SHIFT: <?php  Echo $shift;?></b></font></td>

				</tr>

			</table>
		
		</td>
		
	</tr>
	
	<tr bgcolor="#ffffff" align="center" height="25px">
		<td colspan="15" align="center"></td>
		
	</tr>
	</table>
	<table width="100%" border ='1' cellpadding='0' cellspacing='0'>
	<?php

     if($shift=="Day") { ?>
	<tr align="center" bgcolor="#e6e6e6">
		<td style="border-width:3px;border-style: double;"><b>SlNo.</b></td>
		<td style="border-width:3px;border-style: double;"><b>RTG # NO.</b></td>
		<td style="border-width:3px;border-style: double;"><b>0800-0900</b></td>
		<td style="border-width:3px;border-style: double;"><b>0900-1000</b></td>
		<td style="border-width:3px;border-style: double;"><b>1000-1100</b></td>
		<td style="border-width:3px;border-style: double;"><b>1100-1200</b></td>
		<td style="border-width:3px;border-style: double;"><b>1200-1300</b></td>
		<td style="border-width:3px;border-style: double;"><b>1300-1400</b></td>
		<td style="border-width:3px;border-style: double;"><b>1400-1500</b></td>
		<td style="border-width:3px;border-style: double;"><b>1500-1600</b></td>
		<td style="border-width:3px;border-style: double;"><b>1600-1700</b></td>
		<td style="border-width:3px;border-style: double;"><b>1700-1800</b></td>
		<td style="border-width:3px;border-style: double;"><b>1800-1900</b></td>
		<td style="border-width:3px;border-style: double;"><b>1900-2000</b></td>
		<td style="border-width:3px;border-style: double;"><b>Total Handling Boxes</b></td>
	</tr>
	 <?php } 
	 if($shift=="Night") {?>	
	 <tr  align="center" bgcolor="#e6e6e6">
		<td style="border-width:3px;border-style: double;"><b>SlNo.</b></td>
		<td style="border-width:3px;border-style: double;"><b>RTG # NO.</b></td>
		<td style="border-width:3px;border-style: double;"><b>2000-2100</b></td>
		<td style="border-width:3px;border-style: double;"><b>2100-2200</b></td>
		<td style="border-width:3px;border-style: double;"><b>2200-2300</b></td>
		<td style="border-width:3px;border-style: double;"><b>2300-0000</b></td>
		<td style="border-width:3px;border-style: double;"><b>0000-0100</b></td>
		<td style="border-width:3px;border-style: double;"><b>0100-0200</b></td>
		<td style="border-width:3px;border-style: double;"><b>0200-0300</b></td>
		<td style="border-width:3px;border-style: double;"><b>0300-0400</b></td>
		<td style="border-width:3px;border-style: double;"><b>0400-0500</b></td>
		<td style="border-width:3px;border-style: double;"><b>0500-0600</b></td>
		<td style="border-width:3px;border-style: double;"><b>0600-0700</b></td>
		<td style="border-width:3px;border-style: double;"><b>0700-0800</b></td>
		<td style="border-width:3px;border-style: double;"><b>Total Handling Boxes</b></td>
	</tr>
	 <?php } ?>

<?php
	$cond = "";
	if($shift=="Day")	
	{
		
		 $strQuery = "SELECT distinct eq, (
		(SELECT COUNT(*) FROM inv_move_event WHERE che_fetch=tbl.gkey AND t_fetch BETWEEN to_date(CONCAT('$fromdate',' 08:00:00'),'YYYY-MM-DD HH24-MI-SS') AND to_date(CONCAT('$fromdate',' 08:59:59'),'YYYY-MM-DD HH24-MI-SS') AND inv_move_event.move_kind='YARD') + (SELECT COUNT(*) FROM inv_move_event WHERE che_put=tbl.gkey AND t_put BETWEEN to_date(CONCAT('$fromdate',' 08:00:00'),'YYYY-MM-DD HH24-MI-SS') AND to_date(CONCAT('$fromdate',' 08:59:59'),'YYYY-MM-DD HH24-MI-SS') AND inv_move_event.move_kind IN('DSCH','DLVR','SHFT'))
		) AS t_08_09,
		((SELECT COUNT(*) FROM inv_move_event WHERE che_fetch=tbl.gkey AND t_fetch BETWEEN to_date(CONCAT('$fromdate',' 09:00:00'),'YYYY-MM-DD HH24-MI-SS') AND to_date(CONCAT('$fromdate',' 09:59:59'),'YYYY-MM-DD HH24-MI-SS') AND inv_move_event.move_kind='YARD') + (SELECT COUNT(*) FROM inv_move_event WHERE che_put=tbl.gkey AND t_put BETWEEN to_date(CONCAT('$fromdate',' 09:00:00'),'YYYY-MM-DD HH24-MI-SS') AND to_date(CONCAT('$fromdate',' 09:59:59'),'YYYY-MM-DD HH24-MI-SS') AND inv_move_event.move_kind IN('DSCH','DLVR','SHFT')) ) AS t_09_10,
		((SELECT COUNT(*) FROM inv_move_event WHERE che_fetch=tbl.gkey AND t_fetch BETWEEN to_date(CONCAT('$fromdate',' 10:00:00'),'YYYY-MM-DD HH24-MI-SS') AND to_date(CONCAT('$fromdate',' 10:59:59'),'YYYY-MM-DD HH24-MI-SS') AND inv_move_event.move_kind='YARD') + (SELECT COUNT(*) FROM inv_move_event WHERE che_put=tbl.gkey AND t_put BETWEEN to_date(CONCAT('$fromdate',' 10:00:00'),'YYYY-MM-DD HH24-MI-SS') AND to_date(CONCAT('$fromdate',' 10:59:59'),'YYYY-MM-DD HH24-MI-SS') AND inv_move_event.move_kind IN('DSCH','DLVR','SHFT')) ) AS t_10_11,
		((SELECT COUNT(*) FROM inv_move_event WHERE che_fetch=tbl.gkey AND t_fetch BETWEEN to_date(CONCAT('$fromdate',' 11:00:00'),'YYYY-MM-DD HH24-MI-SS') AND to_date(CONCAT('$fromdate','11:59:59'),'YYYY-MM-DD HH24-MI-SS') AND inv_move_event.move_kind='YARD') + (SELECT COUNT(*) FROM inv_move_event WHERE che_put=tbl.gkey AND t_put BETWEEN to_date(CONCAT('$fromdate',' 11:00:00'),'YYYY-MM-DD HH24-MI-SS') AND to_date(CONCAT('$fromdate',' 11:59:59'),'YYYY-MM-DD HH24-MI-SS') AND inv_move_event.move_kind IN('DSCH','DLVR','SHFT')) ) AS t_11_12,
		((SELECT COUNT(*) FROM inv_move_event WHERE che_fetch=tbl.gkey AND t_fetch BETWEEN to_date(CONCAT('$fromdate',' 12:00:00'),'YYYY-MM-DD HH24-MI-SS') AND to_date(CONCAT('$fromdate',' 12:59:59'),'YYYY-MM-DD HH24-MI-SS') AND inv_move_event.move_kind='YARD') + (SELECT COUNT(*) FROM inv_move_event WHERE che_put=tbl.gkey AND t_put BETWEEN to_date(CONCAT('$fromdate',' 12:00:00'),'YYYY-MM-DD HH24-MI-SS') AND to_date(CONCAT('$fromdate',' 12:59:59'),'YYYY-MM-DD HH24-MI-SS') AND inv_move_event.move_kind IN('DSCH','DLVR','SHFT')) ) AS t_12_13, 
		((SELECT COUNT(*) FROM inv_move_event WHERE che_fetch=tbl.gkey AND t_fetch BETWEEN to_date(CONCAT('$fromdate',' 13:00:00'),'YYYY-MM-DD HH24-MI-SS') AND to_date(CONCAT('$fromdate',' 13:59:59'),'YYYY-MM-DD HH24-MI-SS') AND inv_move_event.move_kind='YARD') + (SELECT COUNT(*) FROM inv_move_event WHERE che_put=tbl.gkey AND t_put BETWEEN to_date(CONCAT('$fromdate',' 13:00:00'),'YYYY-MM-DD HH24-MI-SS') AND to_date(CONCAT('$fromdate',' 13:59:59'),'YYYY-MM-DD HH24-MI-SS') AND inv_move_event.move_kind IN('DSCH','DLVR','SHFT')) ) AS t_13_14,
		((SELECT COUNT(*) FROM inv_move_event WHERE che_fetch=tbl.gkey AND t_fetch BETWEEN to_date(CONCAT('$fromdate',' 14:00:00'),'YYYY-MM-DD HH24-MI-SS') AND to_date(CONCAT('$fromdate',' 14:59:59'),'YYYY-MM-DD HH24-MI-SS') AND inv_move_event.move_kind='YARD') + (SELECT COUNT(*) FROM inv_move_event WHERE che_put=tbl.gkey AND t_put BETWEEN to_date(CONCAT('$fromdate',' 14:00:00'),'YYYY-MM-DD HH24-MI-SS') AND to_date(CONCAT('$fromdate',' 14:59:59'),'YYYY-MM-DD HH24-MI-SS') AND inv_move_event.move_kind IN('DSCH','DLVR','SHFT')) ) AS t_14_15,
		((SELECT COUNT(*) FROM inv_move_event WHERE che_fetch=tbl.gkey AND t_fetch BETWEEN to_date(CONCAT('$fromdate',' 15:00:00'),'YYYY-MM-DD HH24-MI-SS') AND to_date(CONCAT('$fromdate',' 15:59:59'),'YYYY-MM-DD HH24-MI-SS') AND inv_move_event.move_kind='YARD') + (SELECT COUNT(*) FROM inv_move_event WHERE che_put=tbl.gkey AND t_put BETWEEN to_date(CONCAT('$fromdate',' 15:00:00'),'YYYY-MM-DD HH24-MI-SS') AND to_date(CONCAT('$fromdate',' 15:59:59'),'YYYY-MM-DD HH24-MI-SS') AND inv_move_event.move_kind IN('DSCH','DLVR','SHFT')) ) AS t_15_16,
		((SELECT COUNT(*) FROM inv_move_event WHERE che_fetch=tbl.gkey AND t_fetch BETWEEN to_date(CONCAT('$fromdate',' 16:00:00'),'YYYY-MM-DD HH24-MI-SS') AND to_date(CONCAT('$fromdate',' 16:59:59'),'YYYY-MM-DD HH24-MI-SS') AND inv_move_event.move_kind='YARD') + (SELECT COUNT(*) FROM inv_move_event WHERE che_put=tbl.gkey AND t_put BETWEEN to_date(CONCAT('$fromdate',' 16:00:00'),'YYYY-MM-DD HH24-MI-SS') AND to_date(CONCAT('$fromdate',' 16:59:59'),'YYYY-MM-DD HH24-MI-SS') AND inv_move_event.move_kind IN('DSCH','DLVR','SHFT')) ) AS t_16_17, 
		((SELECT COUNT(*) FROM inv_move_event WHERE che_fetch=tbl.gkey AND t_fetch BETWEEN to_date(CONCAT('$fromdate',' 17:00:00'),'YYYY-MM-DD HH24-MI-SS') AND to_date(CONCAT('$fromdate',' 17:59:59'),'YYYY-MM-DD HH24-MI-SS') AND inv_move_event.move_kind='YARD') + (SELECT COUNT(*) FROM inv_move_event WHERE che_put=tbl.gkey AND t_put BETWEEN to_date(CONCAT('$fromdate',' 17:00:00'),'YYYY-MM-DD HH24-MI-SS') AND to_date(CONCAT('$fromdate',' 17:59:59'),'YYYY-MM-DD HH24-MI-SS') AND inv_move_event.move_kind IN('DSCH','DLVR','SHFT')) ) AS t_17_18,
		((SELECT COUNT(*) FROM inv_move_event WHERE che_fetch=tbl.gkey AND t_fetch BETWEEN to_date(CONCAT('$fromdate',' 18:00:00'),'YYYY-MM-DD HH24-MI-SS') AND to_date(CONCAT('$fromdate',' 18:59:59'),'YYYY-MM-DD HH24-MI-SS') AND inv_move_event.move_kind='YARD') + (SELECT COUNT(*) FROM inv_move_event WHERE che_put=tbl.gkey AND t_put BETWEEN to_date(CONCAT('$fromdate',' 18:00:00'),'YYYY-MM-DD HH24-MI-SS') AND to_date(CONCAT('$fromdate',' 18:59:59'),'YYYY-MM-DD HH24-MI-SS') AND inv_move_event.move_kind IN('DSCH','DLVR','SHFT')) ) AS t_18_19,
		((SELECT COUNT(*) FROM inv_move_event WHERE che_fetch=tbl.gkey AND t_fetch BETWEEN to_date(CONCAT('$fromdate',' 19:00:00'),'YYYY-MM-DD HH24-MI-SS') AND to_date(CONCAT('$fromdate',' 19:59:59'),'YYYY-MM-DD HH24-MI-SS') AND inv_move_event.move_kind='YARD') + (SELECT COUNT(*) FROM inv_move_event WHERE che_put=tbl.gkey AND t_put BETWEEN to_date(CONCAT('$fromdate',' 19:00:00'),'YYYY-MM-DD HH24-MI-SS') AND to_date(CONCAT('$fromdate',' 19:59:59'),'YYYY-MM-DD HH24-MI-SS') AND inv_move_event.move_kind IN('DSCH','DLVR','SHFT')) ) AS t_19_20 
		
		FROM( 
		SELECT xps_che.gkey,full_name AS eq FROM xps_che WHERE full_name LIKE 'RTG%' AND full_name !='RTG200' ORDER BY full_name
		) tbl";
	}
			if($shift=="Night")	{
				 $strQuery ="SELECT distinct eq,
				((SELECT COUNT(*)
				FROM inv_move_event
				WHERE che_fetch=tbl.gkey AND t_fetch BETWEEN to_date(CONCAT('$fromdate',' 20:00:00'),'YYYY-MM-DD HH24-MI-SS') AND to_date(CONCAT('$fromdate','20:59:59'),'YYYY-MM-DD HH24-MI-SS')
				AND inv_move_event.move_kind='YARD')
				+
				(SELECT COUNT(*)
				FROM inv_move_event
				WHERE che_put=tbl.gkey AND t_put BETWEEN to_date(CONCAT('$fromdate',' 20:00:00'),'YYYY-MM-DD HH24-MI-SS') AND to_date(CONCAT('$fromdate',' 20:59:59'),'YYYY-MM-DD HH24-MI-SS')
				AND inv_move_event.move_kind IN('DSCH','DLVR','SHFT'))
				) AS t_08_09,
			((SELECT COUNT(*)
				FROM inv_move_event
				WHERE che_fetch=tbl.gkey AND t_fetch BETWEEN to_date(CONCAT('$fromdate',' 21:00:00'),'YYYY-MM-DD HH24-MI-SS') AND to_date(CONCAT('$fromdate',' 21:59:59'),'YYYY-MM-DD HH24-MI-SS')
				AND inv_move_event.move_kind='YARD')
				+
				(SELECT COUNT(*)
				FROM inv_move_event
				WHERE che_put=tbl.gkey AND t_put BETWEEN to_date(CONCAT('$fromdate',' 21:00:00'),'YYYY-MM-DD HH24-MI-SS') AND to_date(CONCAT('$fromdate',' 21:59:59'),'YYYY-MM-DD HH24-MI-SS')
				AND inv_move_event.move_kind IN('DSCH','DLVR','SHFT'))
				) AS t_09_10,
			((SELECT COUNT(*)
					FROM inv_move_event
					WHERE che_fetch=tbl.gkey AND t_fetch BETWEEN to_date(CONCAT('$fromdate',' 22:00:00'),'YYYY-MM-DD HH24-MI-SS') AND to_date(CONCAT('$fromdate',' 22:59:59'),'YYYY-MM-DD HH24-MI-SS')
					AND inv_move_event.move_kind='YARD')
					+
					(SELECT COUNT(*)
					FROM inv_move_event
					WHERE che_put=tbl.gkey AND t_put BETWEEN to_date(CONCAT('$fromdate',' 22:00:00'),'YYYY-MM-DD HH24-MI-SS') AND to_date(CONCAT('$fromdate',' 22:59:59'),'YYYY-MM-DD HH24-MI-SS')
					AND inv_move_event.move_kind IN('DSCH','DLVR','SHFT'))
					) AS t_10_11,
					((SELECT COUNT(*)
							FROM inv_move_event
							WHERE che_fetch=tbl.gkey AND t_fetch BETWEEN to_date(CONCAT('$fromdate',' 23:00:00'),'YYYY-MM-DD HH24-MI-SS') AND to_date(CONCAT('$fromdate','23:59:59'),'YYYY-MM-DD HH24-MI-SS')
							AND inv_move_event.move_kind='YARD')
							+
							(SELECT COUNT(*)
							FROM inv_move_event
							WHERE che_put=tbl.gkey AND t_put BETWEEN to_date(CONCAT('$fromdate',' 23:00:00'),'YYYY-MM-DD HH24-MI-SS') AND to_date(CONCAT('$fromdate',' 23:59:59'),'YYYY-MM-DD HH24-MI-SS')
							AND inv_move_event.move_kind IN('DSCH','DLVR','SHFT'))
							) AS t_11_12,
				((SELECT COUNT(*)
					FROM inv_move_event
					WHERE che_fetch=tbl.gkey AND t_fetch BETWEEN to_date(CONCAT('$fromdate',' 00:00:00'),'YYYY-MM-DD HH24-MI-SS')+  interval '1' day AND 
					to_date(CONCAT('$fromdate',' 00:59:59'),'YYYY-MM-DD HH24-MI-SS')+  interval '1' day
					AND inv_move_event.move_kind='YARD')
					+
					(SELECT COUNT(*)
					FROM inv_move_event
					WHERE che_put=tbl.gkey AND t_put BETWEEN to_date(CONCAT('$fromdate',' 00:00:00'),'YYYY-MM-DD HH24-MI-SS')+  interval '1' day AND
					to_date(CONCAT('$fromdate',' 00:59:59'),'YYYY-MM-DD HH24-MI-SS')+  interval '1' day
					AND inv_move_event.move_kind IN('DSCH','DLVR','SHFT'))
					) AS t_12_13,
				((SELECT COUNT(*)
					FROM inv_move_event
					WHERE che_fetch=tbl.gkey AND t_fetch BETWEEN to_date(CONCAT('$fromdate','01:00:00'),'YYYY-MM-DD HH24-MI-SS')+  interval '1' day  AND 
					to_date(CONCAT('$fromdate',' 01:59:59'),'YYYY-MM-DD HH24-MI-SS')+  interval '1' day
					AND inv_move_event.move_kind='YARD')
					+
					(SELECT COUNT(*)
					FROM inv_move_event
					WHERE che_put=tbl.gkey AND t_put BETWEEN to_date(CONCAT('$fromdate',' 01:00:00'),'YYYY-MM-DD HH24-MI-SS')+  interval '1' day AND 
					to_date(CONCAT('$fromdate',' 01:59:59'),'YYYY-MM-DD HH24-MI-SS')+  interval '1' day
					AND inv_move_event.move_kind IN('DSCH','DLVR','SHFT'))
					) AS t_13_14,
			((SELECT COUNT(*)
				FROM inv_move_event
				WHERE che_fetch=tbl.gkey AND t_fetch BETWEEN to_date(CONCAT('$fromdate',' 02:00:00'),'YYYY-MM-DD HH24-MI-SS')+ interval '1' day AND
				to_date(CONCAT('$fromdate',' 02:59:59'),'YYYY-MM-DD HH24-MI-SS')+  interval '1' day
				AND inv_move_event.move_kind='YARD')
				+
				(SELECT COUNT(*)
				FROM inv_move_event
				WHERE che_put=tbl.gkey AND t_put BETWEEN to_date(CONCAT('$fromdate',' 02:00:00'),'YYYY-MM-DD HH24-MI-SS')+  interval '1' day 
				AND to_date(CONCAT('$fromdate',' 02:59:59'),'YYYY-MM-DD HH24-MI-SS')+  interval '1' day
				AND inv_move_event.move_kind IN('DSCH','DLVR','SHFT'))
				) AS t_14_15,
			((SELECT COUNT(*)
				FROM inv_move_event
				WHERE che_fetch=tbl.gkey AND t_fetch BETWEEN to_date(CONCAT('$fromdate',' 03:00:00'),'YYYY-MM-DD HH24-MI-SS')+  interval '1' day
				AND to_date(CONCAT('$fromdate','03:59:59'),'YYYY-MM-DD HH24-MI-SS')+  interval '1' day
				AND inv_move_event.move_kind='YARD')
				+
				(SELECT COUNT(*)
				FROM inv_move_event
				WHERE che_put=tbl.gkey AND t_put BETWEEN to_date(CONCAT('$fromdate',' 03:00:00'),'YYYY-MM-DD HH24-MI-SS')+  interval '1' day AND
				to_date(CONCAT('$fromdate',' 03:59:59'),'YYYY-MM-DD HH24-MI-SS')+  interval '1' day
				AND inv_move_event.move_kind IN('DSCH','DLVR','SHFT'))
				) AS t_15_16,
			((SELECT COUNT(*)
			FROM inv_move_event
			WHERE che_fetch=tbl.gkey AND t_fetch BETWEEN to_date(CONCAT('$fromdate',' 04:00:00'),'YYYY-MM-DD HH24-MI-SS')+  interval '1' day AND 
			to_date(CONCAT('$fromdate',' 04:59:59'),'YYYY-MM-DD HH24-MI-SS')+  interval '1' day
			AND inv_move_event.move_kind='YARD')
			+
			(SELECT COUNT(*)
			FROM inv_move_event
			WHERE che_put=tbl.gkey AND t_put BETWEEN to_date(CONCAT('$fromdate',' 04:00:00'),'YYYY-MM-DD HH24-MI-SS')+  interval '1' day AND 
			to_date(CONCAT('$fromdate',' 04:59:59'),'YYYY-MM-DD HH24-MI-SS')+  interval '1' day
			AND inv_move_event.move_kind IN('DSCH','DLVR','SHFT'))
			) AS t_16_17,
			((SELECT COUNT(*)
			FROM inv_move_event
			WHERE che_fetch=tbl.gkey AND t_fetch BETWEEN to_date(CONCAT('$fromdate','05:00:00'),'YYYY-MM-DD HH24-MI-SS')+  interval '1' day AND to_date(CONCAT('$fromdate',' 05:59:59'),'YYYY-MM-DD HH24-MI-SS')+  interval '1' day
			AND inv_move_event.move_kind='YARD')
			+
			(SELECT COUNT(*)
			FROM inv_move_event
			WHERE che_put=tbl.gkey AND t_put BETWEEN to_date(CONCAT('$fromdate',' 05:00:00'),'YYYY-MM-DD HH24-MI-SS')+  interval '1' day AND 
			to_date(CONCAT('$fromdate',' 05:59:59'),'YYYY-MM-DD HH24-MI-SS')+  interval '1' day
			AND inv_move_event.move_kind IN('DSCH','DLVR','SHFT'))
			) AS t_17_18,
			((SELECT COUNT(*)
			FROM inv_move_event
			WHERE che_fetch=tbl.gkey AND t_fetch BETWEEN to_date(CONCAT('$fromdate',' 06:00:00'),'YYYY-MM-DD HH24-MI-SS')+  interval '1' day AND
			to_date(CONCAT('$fromdate',' 06:59:59'),'YYYY-MM-DD HH24-MI-SS')+  interval '1' day
			AND inv_move_event.move_kind='YARD')
			+
			(SELECT COUNT(*)
			FROM inv_move_event
			WHERE che_put=tbl.gkey AND t_put BETWEEN to_date(CONCAT('$fromdate',' 06:00:00'),'YYYY-MM-DD HH24-MI-SS')+  interval '1' day AND
			to_date(CONCAT('$fromdate',' 06:59:59'),'YYYY-MM-DD HH24-MI-SS')+  interval '1' day
			AND inv_move_event.move_kind IN('DSCH','DLVR','SHFT'))
			) AS t_18_19,
			((SELECT COUNT(*)
			FROM inv_move_event
			WHERE che_fetch=tbl.gkey AND t_fetch BETWEEN to_date(CONCAT('$fromdate',' 07:00:00'),'YYYY-MM-DD HH24-MI-SS')+  interval '1' day AND 
			to_date(CONCAT('$fromdate',' 07:59:59'),'YYYY-MM-DD HH24-MI-SS')+  interval '1' day
			AND inv_move_event.move_kind='YARD')
			+
			(SELECT COUNT(*)
			FROM inv_move_event
			WHERE che_put=tbl.gkey AND t_put BETWEEN to_date(CONCAT('$fromdate',' 07:00:00'),'YYYY-MM-DD HH24-MI-SS')+  interval '1' day AND 
			to_date(CONCAT('$fromdate',' 07:59:59'),'YYYY-MM-DD HH24-MI-SS')+  interval '1' day
			AND inv_move_event.move_kind IN('DSCH','DLVR','SHFT'))
			) AS t_19_20
			
		       FROM(
			SELECT xps_che.gkey,full_name AS eq
			FROM xps_che 
			WHERE full_name LIKE 'RTG%' AND full_name !='RTG200' 
			ORDER BY full_name 
					)  tbl";
	
			}
			//echo $strQuery;				


	$total=0;
	$tot_sum=0;
	//day
	$sum_08_09=0;
	$sum_09_10=0;
	$sum_10_11=0;
	$sum_11_12=0;
	$sum_12_13=0;
	$sum_13_14=0;
	$sum_14_15=0;
	$sum_15_16=0;
	$sum_16_17=0;
	$sum_17_18=0;
	$sum_18_19=0;
	$sum_19_20=0;
	//night
	$sum_20_21=0;
	$sum_21_22=0;
	$sum_22_23=0;
	$sum_24_01=0;
	$sum_01_02=0;
	$sum_02_03=0;
	$sum_03_04=0;
	$sum_04_05=0;
	$sum_05_06=0;
	$sum_06_07=0;
	$sum_07_08=0;

	// $query=mysqli_query($con_sparcsn4,$strQuery);
	$query = oci_parse($con_sparcsn4_oracle,$strQuery);
	oci_execute($query);
	include("FrontEnd/mydbPConnectionctms.php");
	// while($row=mysqli_fetch_object($query)){
		$i = 1;
	while(($row= oci_fetch_object($query)) != false){
	
	?>
       <tr align="center">
	  
			<td><?php  echo $i;?></td>
			<td><?php if($row->EQ) echo $row->EQ; else echo "&nbsp;";?></td>
			<td <?php if($row->T_08_09 >=25) {?>  bgcolor="#f7dc6f" <?php } ?> ><?php if($row->T_08_09) echo $row->T_08_09; else echo "&nbsp;";  $sum_08_09+= $row->T_08_09;?></td>
			<td <?php if($row->T_09_10 >=25) {?>  bgcolor="#f7dc6f" <?php } ?> ><?php if($row->T_09_10) echo $row->T_09_10; else echo "&nbsp;";  $sum_09_10+= $row->T_09_10;?></td>
			<td <?php if($row->T_10_11 >=25) {?>  bgcolor="#f7dc6f" <?php } ?> ><?php if($row->T_10_11) echo $row->T_10_11; else echo "&nbsp;";  $sum_10_11+= $row->T_10_11;?></td>
			<td <?php if($row->T_11_12 >=25) {?>  bgcolor="#f7dc6f" <?php } ?> ><?php if($row->T_11_12) echo $row->T_11_12; else echo "&nbsp;";  $sum_11_12+= $row->T_11_12;?></td>
			<td <?php if($row->T_12_13 >=25) {?>  bgcolor="#f7dc6f" <?php } ?> ><?php if($row->T_12_13) echo $row->T_12_13; else echo "&nbsp;";  $sum_12_13+= $row->T_12_13;?></td>
			<td <?php if($row->T_13_14 >=25) {?>  bgcolor="#f7dc6f" <?php } ?> ><?php if($row->T_13_14) echo $row->T_13_14; else echo "&nbsp;";  $sum_13_14+= $row->T_13_14;?></td>
			<td <?php if($row->T_14_15 >=25) {?>  bgcolor="#f7dc6f" <?php } ?> ><?php if($row->T_14_15) echo $row->T_14_15; else echo "&nbsp;";  $sum_14_15+= $row->T_14_15;?></td>
			<td <?php if($row->T_15_16 >=25) {?>  bgcolor="#f7dc6f" <?php } ?> ><?php if($row->T_15_16) echo $row->T_15_16; else echo "&nbsp;";  $sum_15_16+= $row->T_15_16;?></td>
			<td <?php if($row->T_16_17 >=25) {?>  bgcolor="#f7dc6f" <?php } ?> ><?php if($row->T_16_17) echo $row->T_16_17; else echo "&nbsp;";  $sum_16_17+= $row->T_16_17;?></td>
			<td <?php if($row->T_17_18 >=25) {?>  bgcolor="#f7dc6f" <?php } ?> ><?php if($row->T_17_18) echo $row->T_17_18; else echo "&nbsp;";  $sum_17_18+= $row->T_17_18;?></td>
			<td <?php if($row->T_18_19 >=25) {?>  bgcolor="#f7dc6f" <?php } ?> ><?php if($row->T_18_19) echo $row->T_18_19; else echo "&nbsp;";  $sum_18_19+= $row->T_18_19;?></td>
			<td <?php if($row->T_19_20 >=25) {?>  bgcolor="#f7dc6f" <?php } ?> ><?php if($row->T_19_20) echo $row->T_19_20; else echo "&nbsp;";  $sum_19_20+= $row->T_19_20;?></td>			
			<td><?php $total=$row->T_08_09+$row->T_09_10+$row->T_10_11+ $row->T_11_12+ $row->T_12_13+ $row->T_13_14+ $row->T_14_15+ $row->T_15_16+ $row->T_16_17+ $row->T_17_18+ $row->T_18_19+ $row->T_19_20; echo $total; $tot_sum+=$total; ?></td>	

		</tr> 

	<?php $i++; }
		mysqli_close($con_ctmsmis);
	?>
		<tr bgcolor="#E0FFFF" align="center">
		   <td colspan="2"><font size="4"><b>Total:</b></font></td>
			<td><?php echo $sum_08_09;?></td>
			<td><?php echo $sum_09_10;?></td>
			<td><?php echo $sum_10_11;?></td>
			<td><?php echo $sum_11_12;?></td>
			<td><?php echo $sum_12_13;?></td>
			<td><?php echo $sum_13_14;?></td>
			<td><?php echo $sum_14_15;?></td>
			<td><?php echo $sum_15_16;?></td>
			<td><?php echo $sum_16_17;?></td>
			<td><?php echo $sum_17_18;?></td>
			<td><?php echo $sum_18_19;?></td>
			<td><?php echo $sum_19_20;?></td>			
			<td><?php $total=$sum_08_09+$sum_09_10+$sum_10_11+ $sum_11_12+ $sum_12_13+$sum_14_15+ $sum_15_16+ $sum_16_17+$sum_17_18+ $sum_18_19+ $sum_19_20; echo $total; ?></td>	

		</tr>


</table>

<?php 
// mysqli_close($con_sparcsn4);
oci_free_statement($query);
oci_close($con_sparcsn4_oracle);
if($_POST['options']=='html'){?>
	
	</BODY>
</HTML>
<?php }?>
