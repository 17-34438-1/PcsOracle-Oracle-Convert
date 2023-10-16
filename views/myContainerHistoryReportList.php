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
	$ddl_imp_rot_no=@$_REQUEST['ddl_imp_rot_no']; 

	include("dbConection.php");
	include("dbOracleConnection.php");	
	
	$sql=oci_parse($con_sparcsn4_oracle,"select vvd_gkey from vsl_vessel_visit_details where ib_vyg='$ddl_imp_rot_no'");
	oci_execute($sql);
	$row=oci_fetch_object($sql);
	$vvdGkey=@$row->VVD_GKEY;
	
	$sql1="call ctmsmis.update_containers_by_vvd_gkey($vvdGkey)";
	$res=mysqli_query($con_sparcsn4,$sql1);
	
	// $todate;
	
	?>
<html>
<title>CONTAINER HISTORY REPORT</title>
<body>
<table width="100%" border ='0' cellpadding='0' cellspacing='0'>
<tr bgcolor="#ffffff" align="center" height="100px">
		<td colspan="13" align="center">
			<table border=0 width="100%">
				
				<tr align="center">
					<td colspan="12" align="center"><img align="middle" src="<?php echo IMG_PATH?>cpanew.jpg"></td>
				</tr>
			
				<tr align="center">
					<td colspan="12"><font size="4"><b><u>CONTAINER HISTORY REPORT</u></b></font></td>
				</tr>
				<tr align="center">
					<td colspan="12"><font size="4"><b></b></font></td>
				</tr>
				<tr align="center">
					<td colspan="12"><font size="4"><b>Container No : <?php echo $container_no;?></b></font></td>
				</tr>
			</table>
		
		</td>
		
	</tr>
	
	<tr bgcolor="#ffffff" align="center" height="25px">
		<td colspan="15" align="center"></td>
		
	</tr>
	</table>
	<?php
		if($_POST['options'] == 'xl'){
			echo "<table width='100%'' border ='1' cellpadding='0' cellspacing='0'>";
		}else if($_POST['options'] == 'html'){
			echo "<table class='table table-bordered table-responsive table-hover table-striped mb-none'>";
		}
	?>

	<tr  align="center">
		<td style="border-width:3px;border-style: double;"><b>SlNo.</b></td>
		<td style="border-width:3px;border-style: double;"><b>CREATOR.</b></td>
		<td style="border-width:3px;border-style: double;"><b>CREATED TIME.</b></td>
		<td style="border-width:3px;border-style: double;"><b>EVENT NAME.</b></td>
		<td style="border-width:3px;border-style: double;"><b>EVENT DESCRIPTION.</b></td>
		<td style="border-width:3px;border-style: double;"><b>NOTE</b></td>
		<td style="border-width:3px;border-style: double;"><b>Value</b></td>

	</tr>

<?php
	//echo $col;
	
	//echo $container_no;
		//$container_no=$_REQUEST['container_no']; 
	/*$query=mysqli_query($con_sparcsn4,"select id,placed_by,creator,created,placed_time,new_value,prior_value,description,note,
	concat( ifnull((prior_value),new_value),'>',ifnull((new_value),prior_value)) as vl 
	
	from (
	select srv_event_types.id,
	srv_event_types.description,srv_event.note,
	
	srv_event.placed_by,srv_event.creator,srv_event.created,srv_event.placed_time,
	(select sparcsn4.srv_event_field_changes.new_value from sparcsn4.srv_event_field_changes where sparcsn4.srv_event_field_changes.event_gkey=sparcsn4.srv_event.gkey order by sparcsn4.srv_event.placed_by desc limit 1) as new_value,
	(select sparcsn4.srv_event_field_changes.prior_value from sparcsn4.srv_event_field_changes where sparcsn4.srv_event_field_changes.event_gkey=sparcsn4.srv_event.gkey order by sparcsn4.srv_event.placed_by desc limit 1) as prior_value
	FROM sparcsn4.inv_unit
	INNER JOIN sparcsn4.srv_event ON srv_event.applied_to_gkey=inv_unit.gkey
	INNER JOIN sparcsn4.srv_event_types ON srv_event_types.gkey=srv_event.event_type_gkey
	WHERE inv_unit.id='$container_no' and inv_unit.category='IMPRT'ORDER BY srv_event.placed_time 
	) as t 
	");*/

	$query=oci_parse($con_sparcsn4_oracle," select id,placed_by,creator,to_char(created,'yyyy-mm-dd hh24:mi:ss') as created ,
	to_char(placed_time,'yyyy-mm-dd hh24:mi:ss') as placed_time,new_value,prior_value,description,note,
	concat(concat( NVL((prior_value),new_value),'>'),NVL((new_value),prior_value)) as vl 
	from (
	select srv_event_types.id,
	srv_event_types.description,srv_event.note,
	
	srv_event.placed_by,srv_event.creator,srv_event.created,srv_event.placed_time,
	(select srv_event_field_changes.new_value from srv_event_field_changes where srv_event_field_changes.event_gkey=srv_event.gkey
	order by srv_event.placed_by desc FETCH FIRST 1 ROWS ONLY ) as new_value,
	(select srv_event_field_changes.prior_value from srv_event_field_changes where srv_event_field_changes.event_gkey=srv_event.gkey 
	order by srv_event.placed_by desc FETCH FIRST 1 ROWS ONLY ) as prior_value
	FROM inv_unit
	INNER JOIN srv_event ON srv_event.applied_to_gkey=inv_unit.gkey
	INNER JOIN srv_event_types ON srv_event_types.gkey=srv_event.event_type_gkey
	WHERE inv_unit.id='$container_no' and inv_unit.category='IMPRT'ORDER BY srv_event.placed_time 
	)  t 
	");
	oci_execute($query);
 
	$i=0;
	$j=0;
	
	
	while(($row=oci_fetch_object($query)) != false){
	$i++;
	
		
	
?>
<tr align="center">
		<td><?php  echo $i;?></td>
		<td><?php if($row->PLACED_BY) echo $row->PLACED_BY; else echo "&nbsp;";?></td>
		<td><?php if($row->CREATED ) echo $row->CREATED; else echo "&nbsp;";?></td>
		<td><?php if($row->ID) echo $row->ID; else echo "&nbsp;";?></td>
		<td><?php if($row->DESCRIPTION) echo $row->DESCRIPTION; else echo "&nbsp;";?></td>
		<td><?php if($row->NOTE) echo $row->NOTE; else echo "&nbsp;";?></td>
		<td><?php if($row->VL) echo $row->VL; else echo "&nbsp;";?></td>
		<!--td><?php
		if(is_null($row->NEW_VALUE))
		echo $row->PRIOR_VALUE; else echo $row->PRIOR_VALUE;">".$row->NEW_VALUE;
		
		?></td-->
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
