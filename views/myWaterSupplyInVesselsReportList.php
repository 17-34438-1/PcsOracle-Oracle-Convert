<?php if(@$_POST['options']=='html'){?>
<HTML>
	<HEAD>
		<TITLE>Bearth Operator</TITLE>
		<LINK href="../css/report.css" type=text/css rel=stylesheet>
	    <style type="text/css">
<!--
.style1 {font-size: 12px}
-->
        </style>
</HEAD>
<BODY>

	<?php } 
	else if(@$_POST['options']=='xl'){
		header("Content-type: application/octet-stream");
		header("Content-Disposition: attachment; filename=IMPORT_SUMMERY.xls;");
		header("Content-Type: application/ms-excel");
		header("Pragma: no-cache");
		header("Expires: 0");

	}
	$ddl_imp_rot_no=$_REQUEST['ddl_imp_rot_no']; 

	//$con=mysql_connect("10.1.1.21", "sparcsn4","sparcsn4")or die("sparcsn4 database cannot connect"); 
	//mysql_select_db("sparcsn4")or die("cannot select DB");
	include("dbConection.php");
	include("dbOracleConnection.php");
	$sql=oci_parse($con_sparcsn4_oracle,"select vvd_gkey from vsl_vessel_visit_details where ib_vyg='$ddl_imp_rot_no'");
	oci_execute($sql);
	$row=oci_fetch_object($sql);
	$vvdGkey=$row->VVD_GKEY;
	
	$sql1="call ctmsmis.update_containers_by_vvd_gkey($vvdGkey)";
	$res=mysqli_query($con_sparcsn4,$sql1);
	//echo $todate;
	
	?>
<html>
<title>Import Reffer Container Discharge List</title>
<body>
<table width="100%" border ='0' cellpadding='0' cellspacing='0'>
<tr bgcolor="#ffffff" align="center" height="100px">
		<td colspan="13" align="center">
			<table border=0 width="100%">
				
				<tr align="center">
					<td colspan="12" align="center"><img align="middle"  width="220px" height="70px" src="<?php echo IMG_PATH?>cpanew.jpg"></td>
				</tr>
			
				<tr align="center">
					<td colspan="12"><font size="4"><b><u>WATER SUPPLY IN THE VESSELS REPORT</u></b></font></td>
				</tr>
				<tr align="center">
					<td colspan="12"><font size="4"><b></b></font></td>
				</tr>

			</table>
		
		</td>
		
	</tr>
	
	<tr bgcolor="#ffffff" align="center" height="25px">
		<td colspan="15" align="center"></td>
		
	</tr>
	</table>
	<?php
		if($type == 'xl'){
			echo "<table width='100%'' border ='1' cellpadding='0' cellspacing='0'>";
		}else if($type == 'html'){
			echo "<table class='table table-bordered table-responsive table-hover table-striped mb-none'>";
		}
	?>
	
	<tr  align="center">
		<td style="border-width:3px;border-style: double;"><b>SlNo.</b></td>
		<td style="border-width:3px;border-style: double;"><b>BERTH NO.</b></td>
		<td style="border-width:3px;border-style: double;"><b>VESSEL NAME.</b></td>
		<td style="border-width:3px;border-style: double;"><b>ROTATION.</b></td>
		<td style="border-width:3px;border-style: double;"><b>ATA.</b></td>
		<td style="border-width:3px;border-style: double;"><b>ATD.</b></td>
		<td style="border-width:3px;border-style: double;"><b>QUANTITY.</b></td>
		<td style="border-width:3px;border-style: double;"><b>QUANTITY UNIT.</b></td>
		<td style="border-width:3px;border-style: double;"><b>USER NAME</b></td>
		<td style="border-width:3px;border-style: double;"><b>PLACE OF DATE TIME.</b></td>
	</tr>

<?php
	//echo $col;
	
	//echo $todate;
	
	
		
/*$query=mysqli_query($con_sparcsn4,"
	SELECT 
	(SELECT id FROM sparcsn4.argo_quay 
	INNER JOIN sparcsn4.vsl_vessel_berthings brt ON brt.quay=sparcsn4.argo_quay.gkey 
	WHERE brt.vvd_gkey=sparcsn4.vsl_vessel_visit_details.vvd_gkey ORDER BY brt.ata ASC LIMIT 1)AS berth,
	vsl_vessels.name AS vsl_name,vsl_vessel_visit_details.ib_vyg AS rotation,srv_event.quantity,
	srv_event.quantity_unit,srv_event.creator,srv_event.placed_time,srv_event.applied_to_natural_key,
	sparcsn4.vsl_vessel_visit_details.start_work,
	(SELECT ata FROM sparcsn4.vsl_vessel_berthings 
	WHERE vsl_vessel_berthings.vvd_gkey=sparcsn4.vsl_vessel_visit_details.vvd_gkey ORDER BY vsl_vessel_berthings.ata ASC LIMIT 1)AS ata,
	(SELECT atd FROM sparcsn4.vsl_vessel_berthings 
	WHERE vsl_vessel_berthings.vvd_gkey=sparcsn4.vsl_vessel_visit_details.vvd_gkey ORDER BY vsl_vessel_berthings.ata ASC  LIMIT 1)AS atd
	
	FROM sparcsn4.vsl_vessel_visit_details
	INNER JOIN sparcsn4.argo_visit_details ON argo_visit_details.gkey=vsl_vessel_visit_details.vvd_gkey
	INNER JOIN sparcsn4.argo_carrier_visit ON argo_carrier_visit.cvcvd_gkey=argo_visit_details.gkey
	INNER JOIN sparcsn4.vsl_vessels ON vsl_vessels.gkey=vsl_vessel_visit_details.vessel_gkey
	INNER JOIN sparcsn4.srv_event ON srv_event.applied_to_gkey=vsl_vessel_visit_details.vvd_gkey
	WHERE  DATE(srv_event.created) BETWEEN '$fromdate' AND '$todate' AND event_type_gkey=169 or vsl_vessel_visit_details.vvd_gkey='$vvdGkey' AND event_type_gkey=169 
	GROUP BY vsl_vessels.name ORDER BY ata
 ");*/


$query=oci_parse($con_sparcsn4_oracle,"
 SELECT DISTINCT vsl_vessels.name,
 (SELECT id FROM argo_quay 
 INNER JOIN vsl_vessel_berthings brt ON brt.quay=argo_quay.gkey 
 WHERE brt.vvd_gkey=vsl_vessel_visit_details.vvd_gkey ORDER BY brt.ata ASC FETCH FIRST 1 ROWS ONLY) AS berth,
 vsl_vessels.name AS vsl_name,vsl_vessel_visit_details.ib_vyg AS rotation,srv_event.quantity,
 srv_event.quantity_unit,srv_event.creator,to_char(srv_event.placed_time,'yyyy-mm-dd hh24:mi:ss') as placed_time,srv_event.applied_to_natural_key,
 vsl_vessel_visit_details.start_work,
 to_char((SELECT ata FROM vsl_vessel_berthings 
 WHERE vsl_vessel_berthings.vvd_gkey=vsl_vessel_visit_details.vvd_gkey ORDER BY vsl_vessel_berthings.ata ASC  FETCH FIRST 1 ROWS ONLY),'yyyy-mm-dd hh24:mi:ss') AS ata,
 to_char((SELECT atd FROM vsl_vessel_berthings 
 WHERE vsl_vessel_berthings.vvd_gkey=vsl_vessel_visit_details.vvd_gkey ORDER BY vsl_vessel_berthings.ata ASC  FETCH FIRST 1 ROWS ONLY),'yyyy-mm-dd hh24:mi:ss') AS atd
 FROM vsl_vessel_visit_details
 INNER JOIN argo_visit_details ON argo_visit_details.gkey=vsl_vessel_visit_details.vvd_gkey
 INNER JOIN argo_carrier_visit ON argo_carrier_visit.cvcvd_gkey=argo_visit_details.gkey
 INNER JOIN vsl_vessels ON vsl_vessels.gkey=vsl_vessel_visit_details.vessel_gkey
 INNER JOIN srv_event ON srv_event.applied_to_gkey=vsl_vessel_visit_details.vvd_gkey
 WHERE  CAST(srv_event.created AS DATE) BETWEEN to_date('$fromdate','yyyy-mm-dd') AND to_date('$todate','yyyy-mm-dd')
 AND event_type_gkey=169 or vsl_vessel_visit_details.vvd_gkey='$vvdGkey' AND event_type_gkey=169 
 ORDER BY ata
 ");

 oci_execute($query);
 

	$i=0;
	$j=0;
	
	$mlo="";
	while(($row=oci_fetch_object($query))!=false){
	$i++;
	
		
	
?>
<tr align="center">
		<td><?php  echo $i;?></td>
		<td><?php if($row->BERTH) echo $row->BERTH; else echo "&nbsp;";?></td>
		<td><?php if($row->VSL_NAME) echo $row->VSL_NAME; else echo "&nbsp;";?></td>
		<td><?php if($row->ROTATION) echo $row->ROTATION; else echo "&nbsp;";?></td>
		<td><?php if($row->ATA) echo $row->ATA; else echo "&nbsp;";?></td>
		<td><?php if($row->ATD) echo $row->ATD; else echo "&nbsp;";?></td>
		<td><?php if($row->QUANTITY) echo $row->QUANTITY; else echo "&nbsp;";?></td>
		<td><?php if($row->QUANTITY_UNIT) echo $row->QUANTITY_UNIT; else echo "&nbsp;";?></td>
		<td><?php if($row->CREATOR) echo $row->CREATOR; else echo "&nbsp;";?></td>
		<td><?php if($row->PLACED_TIME) echo $row->PLACED_TIME; else echo "&nbsp;";?></td>
	</tr>

<?php } ?>
</table>
<br />
<br />



<?php 
mysqli_close($con_sparcsn4);
if(@$_POST['options']=='html'){?>	
	</BODY>
</HTML>
<?php }?>
