<?php if($_POST['options']=='html'){?>
	<?php } 
	else if($_POST['options']=='xl'){
		header("Content-type: application/octet-stream");
		header("Content-Disposition: attachment; filename=EXPORT_SUMMERY.xls;");
		header("Content-Type: application/ms-excel");
		header("Pragma: no-cache");
		header("Expires: 0");

	}
	include("FrontEnd/dbConection.php");
	include("dbOracleConnection.php");		
	?>
<html>
<body>
<table width="100%" border ='0' cellpadding='0' cellspacing='0'>
<tr bgcolor="#ffffff" align="center" height="100px">
		<td colspan="13" align="center">
			<table border=0 width="100%">
				
				
				<tr align="center">
					<td colspan="12"><font size="4"><b>CHITTAGONG PORT AUTHORITY,CHITTAGONG</b></font></td>
				</tr>
			
				<tr align="center">
					<td colspan="12"><font size="4"><b><u><?php echo $title;?></u></b></font></td>
				</tr>
				<tr align="center">
					<td colspan="12"><font size="4">Rotation No : <b><?php echo $rotation;?></b></font></td>
				</tr>
			</table>
		
		</td>
		
	</tr>
	
	<tr bgcolor="#ffffff" align="center" height="25px">
		<td colspan="15" align="center"></td>
		
	</tr>
	</table>
	<table width="100%" border ='1' cellpadding='0' cellspacing='0'>
	<tr  align="center">
		<th>SL</th>
		<th>Container</th>
		<th>MLO</th>
		<th>ISO</th>
		<th>Status</th>
		<th><nobr>Disch Time</nobr></th>
		<th><nobr>Yard Pos</nobr></th>
		<th>Destination</th>
		<th>User Id</th>
	</tr>

<?php

	$strQuery = "SELECT ctmsmis.mis_exp_unit.gkey,mis_exp_unit.cont_id AS id,mis_exp_unit.rotation,mis_exp_unit.isoType AS iso,
	(CASE 
	WHEN mis_exp_unit.cont_size= '20' AND mis_exp_unit.cont_height = '86' AND mis_exp_unit.isoGroup NOT IN ('RS','RT','RE','UT','TN','TD','TG','PF','PC') THEN '2D'
	WHEN mis_exp_unit.cont_size = '40' AND mis_exp_unit.cont_height='86' AND mis_exp_unit.isoGroup NOT IN ('RS','RT','RE','UT','TN','TD','TG','PF','PC') THEN '4D'
	WHEN mis_exp_unit.cont_size = '40' AND mis_exp_unit.cont_height='96' AND mis_exp_unit.isoGroup NOT IN ('RS','RT','RE','UT','TN','TD','TG','PF','PC') THEN '4H'
	WHEN mis_exp_unit.cont_size = '45' AND mis_exp_unit.cont_height='96' AND mis_exp_unit.isoGroup NOT IN ('RS','RT','RE','UT','TN','TD','TG','PF','PC') THEN '45H'
	WHEN mis_exp_unit.cont_size = '20' AND mis_exp_unit.cont_height='86' AND mis_exp_unit.isoGroup IN ('RS','RT','RE') THEN '2RF'
	WHEN mis_exp_unit.cont_size = '40' AND mis_exp_unit.isoGroup IN ('RS','RT','RE') THEN '4RH'
	WHEN mis_exp_unit.cont_size = '20' AND mis_exp_unit.cont_height='86' AND mis_exp_unit.isoGroup IN ('UT') THEN '2OT'
	WHEN mis_exp_unit.cont_size = '40' AND mis_exp_unit.isoGroup IN ('UT') THEN '4OT'
	WHEN mis_exp_unit.cont_size = '20' AND mis_exp_unit.cont_height='86' AND mis_exp_unit.isoGroup IN ('PF','PC') THEN '2FR'
	WHEN mis_exp_unit.cont_size = '40' AND mis_exp_unit.isoGroup IN ('PF','PC') THEN '4FR'
	WHEN mis_exp_unit.cont_size = '20' AND mis_exp_unit.cont_height='86' AND mis_exp_unit.isoGroup IN ('TN','TD','TG') THEN '2TK'
	ELSE ''
	END
	) AS TYPE,
	mis_exp_unit.cont_mlo AS mlo,ctmsmis.mis_exp_unit.current_position,
	cont_status AS freight_kind,ctmsmis.mis_exp_unit.goods_and_ctr_wt_kg AS weight,ctmsmis.mis_exp_unit.coming_from AS coming_from,
	ctmsmis.mis_exp_unit.pod,ctmsmis.mis_exp_unit.stowage_pos,ctmsmis.mis_exp_unit.last_update,
	ctmsmis.mis_exp_unit.user_id
	FROM ctmsmis.mis_exp_unit 
	WHERE  mis_exp_unit.snx_type=2 AND mis_exp_unit.rotation='$rotation'";
	

	$query=mysqli_query($con_sparcsn4,$strQuery);
	$i=0;
	$container="";
	while($row=mysqli_fetch_object($query)){
	$gKey="";
	$gKey=$row->gkey;
	$sqlQuery="select * from inv_unit  
	INNER JOIN inv_unit_fcy_visit ON inv_unit_fcy_visit.unit_gkey=inv_unit.gkey
	INNER JOIN inv_goods ON inv_goods.gkey=inv_unit.goods
	INNER JOIN ref_commodity ON ref_commodity.gkey=inv_goods.commodity_gkey
	where  inv_unit.gkey='$gKey'";
	$resPrevCont = oci_parse($con_sparcsn4_oracle,$sqlQuery);
	oci_execute($resPrevCont);
	$results=array();
	$numrow =oci_fetch_all($resPrevCont, $results, null, null, OCI_FETCHSTATEMENT_BY_ROW);
	oci_free_statement($resPrevCont);
	if($numrow>0){
	$resPrevCont = oci_parse($con_sparcsn4_oracle,$sqlQuery);
	oci_execute($resPrevCont);
	$rowPos = oci_fetch_object($resPrevCont);

	$i++;
	$container=$container.$row->id.",";
?>
	<tr align="center">
		<td><?php  echo $i;?></td>
		<td><?php if($row->id) echo $row->id; else echo "&nbsp;";?></td>
		<td><?php if($row->mlo) echo $row->mlo; else echo "&nbsp;";?></td>
		<td><?php if($row->iso) echo $row->iso; else echo "&nbsp;";?></td>
		<td><?php if($row->freight_kind) echo $row->freight_kind; else echo "&nbsp;";?></td>
		<td><?php if($row->last_update) echo $row->last_update; else echo "&nbsp;";?></td>
		<td><?php if($row->current_position) echo $row->current_position; else echo "&nbsp;";?></td>
		<td><?php if($rowPos->DESTINATION) echo $rowPos->DESTINATION ; else echo "&nbsp;";?></td>
		<td><?php if($row->user_id) echo $row->user_id; else echo "&nbsp;";?></td>
	</tr>
<?php } }?>
</table>
<?php if($_POST['options']=='html'){ ?>
	<!--div style="300px;">
		<table style="table-layout:fixed;width:200px;">
			<tr>
				<td  style="max-width: 50px;"><?php echo $container;?></td>
			</tr>
		</table>
	</div-->		
<?php }?>
<?php 
//mysql_close($con_sparcsn4);
if($_POST['options']=='html'){?>	
	</BODY>
</HTML>
<script>
window.print();
</script>
<?php }?>
