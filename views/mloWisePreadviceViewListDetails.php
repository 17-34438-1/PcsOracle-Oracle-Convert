
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

	<?php 
	//$ddl_imp_rot_no=$_REQUEST['ddl_imp_rot_no']; 

	//$con=mysql_connect("10.1.1.21", "sparcsn4","sparcsn4")or die("sparcsn4 database cannot connect"); 
	//mysql_select_db("ctmsmis")or die("cannot select DB");
	
	?>
<html>
<title>MLO Wise Pre Advice Container Report</title>
<body>
<table width="100%" border ='0' cellpadding='0' cellspacing='0'>
<tr bgcolor="#ffffff" align="center" height="100px">
		<td colspan="13" align="center">
			<table border=0 width="100%">
				
				<tr align="center">
					<td colspan="12"><font size="4"><b> CHITTAGONG PORT AUTHORITY,CHITTAGONG</b></font></td>
				</tr>
			
				<tr align="center">
					<td colspan="12"><font size="4"><b><u>MLO Wise Pre Advice Container Report</u></b></font></td>
				</tr>
				<tr align="center">
					<td colspan="12"><font size="4"><b>NAME OF MLO: <?php echo $mlo; ?></b></font></td>
					
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
	<table width="100%" border ='1' cellpadding='0' cellspacing='0'>
	<tr align="center">
		<td style="border-width:1px;border-style: double;" ><b>SlNo.</b></td>
		<td style="border-width:1px;border-style: double;"><b>CONTAINER NO.</b></td>
		<td style="border-width:1px;border-style: double;" ><b>SIZE.</b></td>
		<td style="border-width:1px;border-style: double;" ><b>Weight.</b></td>
		<td style="border-width:1px;border-style: double;" ><b>SEAL NO.</b></td>
		<td style="border-width:1px;border-style: double;" ><b>MLO.</b></td>
		<td style="border-width:1px;border-style: double;" ><b>TYPE.</b></td>
		<td style="border-width:1px;border-style: double;" ><b>COMING FROM.</b></td>
		<td style="border-width:1px;border-style: double;" ><b>VESSEL NAME.</b></td>
		<td style="border-width:1px;border-style: double;" ><b>ROTATION.</b></td>
		<td style="border-width:1px;border-style: double;" ><b>PREADVISED DATE..</b></td>
		<td style="border-width:1px;border-style: double;" ><b>STATUS.</b></td>
		<td style="border-width:1px;border-style: double;" ><b>PORT.</b></td>
	</tr>
	

<?php
	//echo $todate;
include("FrontEnd/mydbPConnectionctms.php");
	
$querystr="
SELECT ctmsmis.mis_exp_unit_preadv_req.gkey AS gkey,mis_exp_unit_preadv_req.cont_id AS cont_id,mis_exp_unit_preadv_req.cont_mlo,
mis_exp_unit_preadv_req.cont_size AS cont_size,mis_exp_unit_preadv_req.isoType,'' AS loc,'' AS emtyDate,
mis_exp_unit_preadv_req.rotation,mis_exp_unit_preadv_req.cont_status,mis_exp_unit_preadv_req.pod,
goods_and_ctr_wt_kg,seal_no,last_update,cont_mlo,
(select code  from ctmsmis.offdoc where id= ctmsmis.mis_exp_unit_preadv_req.transOp) as offdock,
(SELECT NAME FROM sparcsn4.vsl_vessel_visit_details
 INNER JOIN sparcsn4.vsl_vessels ON sparcsn4.vsl_vessels.gkey=sparcsn4.vsl_vessel_visit_details.vessel_gkey
 WHERE ib_vyg=ctmsmis.mis_exp_unit_preadv_req.rotation) AS vsl_name
FROM  ctmsmis.mis_exp_unit_preadv_req 
WHERE  mis_exp_unit_preadv_req.rotation='$rot' AND cont_mlo='$mlo' order by cont_mlo,cont_id
 ";
 //echo $query;
	$i=0;
	$query=mysqli_query($con_ctmsmis,$querystr);
	while($row=mysqli_fetch_object($query)){
	$i++;
	
		
	
?>
<tr align="center">
		<td><?php  echo $i;?></td>
		<td><?php if($row->cont_id) echo $row->cont_id; else echo "&nbsp;";?></td>
		<td><?php if($row->cont_size) echo $row->cont_size; else echo "&nbsp;";?></td>
		<td><?php if($row->goods_and_ctr_wt_kg) echo $row->goods_and_ctr_wt_kg; else echo "&nbsp;";?></td>
		<td><?php if($row->seal_no) echo $row->seal_no; else echo "&nbsp;";?></td>
		<td><?php if($row->cont_mlo) echo $row->cont_mlo; else echo "&nbsp;";?></td>
		<td><?php if($row->isoType) echo $row->isoType; else echo "&nbsp;";?></td>
		<td><?php if($row->offdock) echo $row->offdock; else echo "&nbsp;";?></td>
		<td><?php if($row->vsl_name) echo $row->vsl_name; else echo "&nbsp;";?></td>
		<td><?php if($row->rotation) echo $row->rotation; else echo "&nbsp;";?></td>
		<td><?php if($row->last_update) echo $row->last_update; else echo "&nbsp;";?></td>
		<td><?php if($row->cont_status) echo $row->cont_status; else echo "&nbsp;";?></td>
		<td><?php if($row->pod) echo $row->pod; else echo "&nbsp;";?></td>
				
	</tr>
<?php } ?>
</table>
<br />
<br />



	
	</BODY>
</HTML>

