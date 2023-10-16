<?php if(@$_POST['fileOptions']=='html'){?>
<HTML>
	<HEAD>
		<TITLE></TITLE>
		<LINK href="../css/report.css" type=text/css rel=stylesheet>
	    <style type="text/css">
<!--
.style1 {font-size: 12px}
-->
        </style>
</HEAD>
<BODY>

	<?php } 
	else if(@$_POST['fileOptions']=='xl'){
		header("Content-type: application/octet-stream");
		header("Content-Disposition: attachment; filename=EXPORT_SUMMERY.xls;");
		header("Content-Type: application/ms-excel");
		header("Pragma: no-cache");
		header("Expires: 0");

	}
	include("dbConection.php");
	include("dbOracleConnection.php");
	?>
<html>
<title> </title>
<body>
<table width="100%" border ='0' cellpadding='0' cellspacing='0'>
<tr bgcolor="#ffffff" align="center" height="100px">
		<td colspan="13" align="center">
			<table border=0 width="100%">
				
				<tr align="center">
					<td colspan="12"><img align="middle" width="220px" height="70px" src="<?php echo IMG_PATH?>cpanew.jpg"></td>
				</tr>
			
				<tr align="center">
					<td colspan="12"><font size="4"><b><!--?php echo $head; ?--> List of Not Stripped Assignment Delivery Containers</b></font></td>
				</tr>
				<tr align="center">
					<td colspan="12"><font size="4"><b>DATE : <?php echo $fromdate; ?>&nbsp;&nbsp;Terminal : <?php echo $yard_no; ?></b></font></td>
				</tr>


			</table>
		
		</td>
		
	</tr>
	
	<tr bgcolor="#ffffff" align="center" height="25px">
		<td colspan="15" align="center"></td>
		
	</tr>
	</table>
	<table class="table table-bordered table-responsive table-hover table-striped mb-none">
	<tr  align="center">
		<td style="border-width:3px;border-style: double;"><b>SlNo.</b></td>
		<td style="border-width:3px;border-style: double;"><b>Container No.</b></td>
		<td style="border-width:3px;border-style: double;"><b>Rotation No.</b></td>
		<td style="border-width:3px;border-style: double;"><b>Type</b></td>
		<td style="border-width:3px;border-style: double;"><b>MLO</b></td>
		<td style="border-width:3px;border-style: double;"><b>Status</b></td>		
		<td style="border-width:3px;border-style: double;"><b>Weight</b></td>
		<td style="border-width:3px;border-style: double;"><b>Assignment Type</b></td>
		<td style="border-width:3px;border-style: double;"><b>C&F Name</b></td>
		<td style="border-width:3px;border-style: double;"><b>Current Position</b></td>
		<td style="border-width:3px;border-style: double;"><b>Discharge date</b></td>
		<td style="border-width:3px;border-style: double;"><b>Assignment date</b></td>
		<!--td style="border-width:3px;border-style: double;"><b>Propose Empty Date(E)</b></td>
		<td style="border-width:3px;border-style: double;"><b>Delivery/Empty Date</b></td-->
		
		
	</tr>

<?php
	
	/*$str="SELECT * FROM 
	(
	SELECT DISTINCT *,
	(case 
	when delivery >= concat('$fromdate',' 08:00:00') AND delivery <concat('$fromdate',' 16:00:00') then 'Shift A'
	when delivery >= concat('$fromdate',' 16:00:00') AND delivery <concat(date_add('$fromdate',interval 1 day),' 00:00:00') then 'Shift B'
	when delivery >= concat(date_add('$fromdate',interval 1 day),' 00:00:00') AND delivery <concat(date_add('$fromdate',interval 1 day),' 08:00:00') then 'Shift C'
	end) as shift,
	(case when delivery is null then 2 else 1 end) as sl
	FROM (
	SELECT a.id AS cont_no,
	(SELECT sparcsn4.ref_equip_type.id FROM sparcsn4.inv_unit_equip
	INNER JOIN sparcsn4.ref_equipment ON sparcsn4.ref_equipment.gkey=sparcsn4.inv_unit_equip.eq_gkey
	INNER JOIN sparcsn4.ref_equip_type ON sparcsn4.ref_equip_type.gkey=sparcsn4.ref_equipment.eqtyp_gkey 
	WHERE sparcsn4.inv_unit_equip.unit_gkey=a.gkey LIMIT 1) AS iso_code,
	b.flex_string10 AS rot_no,
	b.time_in AS dischargetime,
	b.time_out AS delivery,
	g.id AS mlo,
	k.name as cf,
	sparcsn4.config_metafield_lov.mfdch_desc,
	a.freight_kind AS statu,
	a.goods_and_ctr_wt_kg AS weight,
	(SELECT ctmsmis.mis_exp_unit_load_failed.last_update
	FROM ctmsmis.mis_exp_unit_load_failed WHERE ctmsmis.mis_exp_unit_load_failed.gkey=a.gkey) AS last_update,
	(SELECT ctmsmis.mis_exp_unit_load_failed.user_id
	FROM ctmsmis.mis_exp_unit_load_failed WHERE ctmsmis.mis_exp_unit_load_failed.gkey=a.gkey) AS user_id,

	IFNULL((SELECT SUBSTRING(sparcsn4.srv_event_field_changes.new_value,7)
			FROM sparcsn4.srv_event
			INNER JOIN sparcsn4.srv_event_field_changes ON sparcsn4.srv_event_field_changes.event_gkey=sparcsn4.srv_event.gkey
			WHERE sparcsn4.srv_event.applied_to_gkey=a.gkey  AND sparcsn4.srv_event.event_type_gkey IN(18,13,16) AND sparcsn4.srv_event_field_changes.new_value IS NOT NULL AND sparcsn4.srv_event_field_changes.new_value !='' AND sparcsn4.srv_event_field_changes.new_value !='Y-CGP-.' AND sparcsn4.srv_event.gkey<(SELECT sparcsn4.srv_event.gkey FROM sparcsn4.srv_event
			INNER JOIN sparcsn4.srv_event_field_changes ON sparcsn4.srv_event_field_changes.event_gkey=sparcsn4.srv_event.gkey
			WHERE sparcsn4.srv_event.event_type_gkey=4 AND sparcsn4.srv_event.applied_to_gkey=a.gkey AND metafield_id='unitFlexString01' AND new_value IS NOT NULL ORDER BY sparcsn4.srv_event_field_changes.gkey DESC LIMIT 1) ORDER BY sparcsn4.srv_event.gkey DESC LIMIT 1),(SELECT SUBSTRING(sparcsn4.srv_event_field_changes.new_value,7)
			FROM sparcsn4.srv_event
			INNER JOIN sparcsn4.srv_event_field_changes ON sparcsn4.srv_event_field_changes.event_gkey=sparcsn4.srv_event.gkey
			WHERE sparcsn4.srv_event.applied_to_gkey=a.gkey  AND sparcsn4.srv_event.event_type_gkey IN(18,13,16) ORDER BY sparcsn4.srv_event_field_changes.gkey DESC LIMIT 1)) AS carrentPosition,

	(SELECT ctmsmis.cont_yard(carrentPosition)) AS Yard_No,

	(SELECT ctmsmis.cont_block(carrentPosition,Yard_No)) AS Block_No,
	(SELECT creator FROM sparcsn4.srv_event WHERE applied_to_gkey=a.gkey AND event_type_gkey=30 ORDER BY gkey DESC LIMIT 1) as stripped_by,
	(SELECT sparcsn4.srv_event.created FROM  sparcsn4.srv_event 
	INNER JOIN sparcsn4.srv_event_field_changes ON sparcsn4.srv_event_field_changes.event_gkey=sparcsn4.srv_event.gkey
	WHERE applied_to_gkey=a.gkey AND event_type_gkey=4 AND sparcsn4.srv_event_field_changes.new_value='E' LIMIT 1) AS proEmtyDate,
	b.flex_date01 AS assignmentdate, if(ucase(a.flex_string15) like '%STAY%',1,0) as stay

	FROM sparcsn4.inv_unit a
	INNER JOIN sparcsn4.inv_unit_fcy_visit b ON b.unit_gkey=a.gkey
	INNER JOIN sparcsn4.ref_bizunit_scoped g ON a.line_op = g.gkey
	INNER JOIN sparcsn4.config_metafield_lov ON a.flex_string01=config_metafield_lov.mfdch_value

	INNER JOIN
			sparcsn4.inv_goods j ON j.gkey = a.goods
	LEFT JOIN
			sparcsn4.ref_bizunit_scoped k ON k.gkey = j.consignee_bzu
	WHERE date(b.flex_date01)='$fromdate' AND config_metafield_lov.mfdch_value NOT IN ('CANCEL','OCD','APPCUS','APPOTH','APPREF')
	) AS tmp where Yard_No='$yard_no' order by sl,Yard_No,shift,proEmtyDate) AS final WHERE delivery IS NULL
	";*/

$str="SELECT * FROM 
(
SELECT DISTINCT *

FROM (
SELECT a.id AS cont_no,
(CASE
WHEN b.time_out >= to_date(CONCAT('$fromdate',' 08:00:00'),'YYYY-MM-DD HH24-MI-SS') 
AND b.time_out < to_date(CONCAT('$fromdate',' 16:00:00'),'YYYY-MM-DD HH24-MI-SS') THEN 'Shift A'

WHEN b.time_out >= to_date(CONCAT('$fromdate',' 16:00:00'),'YYYY-MM-DD HH24-MI-SS') 
AND b.time_out < to_date(CONCAT('$fromdate',' 00:00:00'),'YYYY-MM-DD HH24-MI-SS')+1 THEN 'Shift B'

WHEN b.time_out >= to_date(CONCAT('$fromdate',' 00:00:00'),'YYYY-MM-DD HH24-MI-SS')+1
AND b.time_out < to_date(CONCAT('$fromdate',' 08:00:00'),'YYYY-MM-DD HH24-MI-SS')+1 THEN 'Shift C'
END) AS shift,
(case when b.time_out is null then 2 else 1 end) as sl,

(SELECT ref_equip_type.id FROM inv_unit
INNER JOIN ref_equipment ON ref_equipment.gkey=inv_unit.eq_gkey
INNER JOIN ref_equip_type ON ref_equip_type.gkey=ref_equipment.eqtyp_gkey 
INNER JOIN inv_unit_fcy_visit  ON inv_unit_fcy_visit.unit_gkey=inv_unit.gkey
WHERE inv_unit_fcy_visit.unit_gkey=a.gkey FETCH FIRST 1 ROWS ONLY ) AS iso_code,
b.flex_string10 AS rot_no,
b.time_in AS dischargetime,
b.time_out AS delivery,
g.id AS mlo,
k.name as cf,
config_metafield_lov.mfdch_desc,
a.freight_kind AS statu,
a.goods_and_ctr_wt_kg AS weight,

NVL((SELECT substr(srv_event_field_changes.new_value,7)
FROM srv_event
INNER JOIN srv_event_field_changes ON srv_event_field_changes.event_gkey=srv_event.gkey
WHERE srv_event.applied_to_gkey=a.gkey  AND srv_event.event_type_gkey IN(18,13,16) AND srv_event_field_changes.new_value IS NOT NULL AND srv_event_field_changes.new_value !='' AND srv_event_field_changes.new_value !='Y-CGP-.' AND srv_event.gkey<(SELECT srv_event.gkey FROM srv_event
INNER JOIN srv_event_field_changes ON srv_event_field_changes.event_gkey=srv_event.gkey
WHERE srv_event.event_type_gkey=4 AND srv_event.applied_to_gkey=a.gkey AND metafield_id='unitFlexString01' AND new_value IS NOT NULL ORDER BY srv_event_field_changes.gkey DESC FETCH FIRST 1 ROWS ONLY) ORDER BY srv_event.gkey DESC FETCH FIRST 1 ROWS ONLY),(SELECT substr(srv_event_field_changes.new_value,7)
FROM srv_event
INNER JOIN srv_event_field_changes ON srv_event_field_changes.event_gkey=srv_event.gkey
WHERE srv_event.applied_to_gkey=a.gkey  AND srv_event.event_type_gkey IN(18,13,16) ORDER BY srv_event_field_changes.gkey DESC FETCH FIRST 1 ROWS ONLY)) AS carrentPosition,

(SELECT creator FROM srv_event WHERE applied_to_gkey=a.gkey AND event_type_gkey=30 ORDER BY gkey DESC FETCH FIRST 1 ROWS ONLY) as stripped_by,
(SELECT srv_event.created FROM  srv_event 
INNER JOIN srv_event_field_changes ON srv_event_field_changes.event_gkey=srv_event.gkey
WHERE applied_to_gkey=a.gkey AND event_type_gkey=4 AND srv_event_field_changes.new_value='E' FETCH FIRST 1 ROWS ONLY) AS proEmtyDate,
b.flex_date01 AS assignmentdate, 
(case 
when upper(a.flex_string15) like '%STAY%' THEN  1 else 0
end) as stay 

FROM inv_unit a
INNER JOIN inv_unit_fcy_visit b ON b.unit_gkey=a.gkey
INNER JOIN ref_bizunit_scoped g ON a.line_op = g.gkey
INNER JOIN config_metafield_lov ON a.flex_string01=config_metafield_lov.mfdch_value

INNER JOIN
inv_goods j ON j.gkey = a.goods
LEFT JOIN
ref_bizunit_scoped k ON k.gkey = j.consignee_bzu
WHERE to_char(b.flex_date01,'yyyy-mm-dd')='$fromdate' 
AND config_metafield_lov.mfdch_value NOT IN ('CANCEL','OCD','APPCUS','APPOTH','APPREF')
) tmp order by sl,shift,proEmtyDate ) final WHERE delivery IS NULL";

	$query=oci_parse($con_sparcsn4_oracle,$str);
	oci_execute($query);
//echo "";

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
	include("mydbPConnection.php");
	$result=array();
	$numRows= oci_fetch_all($query, $result, null, null, OCI_FETCHSTATEMENT_BY_ROW);
	oci_free_statement($query);
	//$numRows=mysqli_num_rows($query);
	$query=oci_parse($con_sparcsn4_oracle,$str);
	oci_execute($query);
	
	while(($row=oci_fetch_object($query)) != false) {
		$gkey="";
		$carrierPosition="";
		$gkey=$row->GKEY;
		$carrierPosition=$row->CARRENTPOSITION;	
		$yardName="";
		$yardNameQuery="SELECT ctmsmis.cont_yard('$carrierPosition')AS Yard_No";
		$yardNameQueryRes=mysqli_query($con_sparcsn4,$yardNameQuery);
		$yardNameQueryRow=mysqli_fetch_object($yardNameQueryRes);
		$yardName=$yardNameQueryRow->Yard_No;
		
		$blockName="";
		$blockNameQuery="SELECT ctmsmis.cont_block('$carrierPosition','$yardName') AS Block_No";
		$blockNameQueryRes=mysqli_query($con_sparcsn4,$blockNameQuery);
		$blockNameQueryRow=mysqli_fetch_object($blockNameQueryRes);
		$blockName=$blockNameQueryRow->Block_No;
		//if($yardName==$yard_no){

	    $i++;
	//if($yard_no=="GCB")
	//{
		if($i==$numRows)
			$allCont .=$row->CONT_NO;
		else
			$allCont .=$row->CONT_NO.", ";
	//}
	$sqlIsoCode=mysqli_query($con_cchaportdb,"select cont_iso_type from igm_detail_container where cont_number='$row->CONT_NO'");
	
	//echo "select cont_iso_type from igm_detail_container where cont_number='$row->cont_no";
	$rtnIsoCode=mysqli_fetch_object($sqlIsoCode);
	$iso=$rtnIsoCode->cont_iso_type;
	if(substr($iso,0,1)==2)
		$j20=$j20+1;
	else
		$j40=$j40+1;
		
	if(substr($iso,0,1)==2)
	{
		if($row->SHIFT=="Shift A")
			$a20 = $a20+1;
		if($row->SHIFT=="Shift B")
			$b20 = $b20+1;
		if($row->SHIFT=="Shift C")
			$c20 = $c20+1;
	}
	else
	{
		if($row->SHIFT=="Shift A")
			$a40 = $a40+1;
		if($row->SHIFT=="Shift B")
			$b40 = $b40+1;
		if($row->SHIFT=="Shift C")
			$c40 = $c40+1;
	}
		
	if($shift==$row->SHIFT or $i==1)
	{
		if(substr($iso,0,1)==2)
			$tot20 = $tot20+1;
		else 
			$tot40 = $tot40+1;
	}
	/*
	if($totalcon==$row->cont_no or $i==1)
	{
		if(substr($iso,0,1)==2)
			$tot20 = $tot20+1;
		else 
			$tot40 = $tot40+1;
	}
	*/
//	if($yard!=$yardNameQueryRow->Yard_No)
	{
		$yard=$yardNameQueryRow->Yard_No;
		if($i!=1)
		{
		?>
		<tr>
			<td colspan="15"><b><?php  echo "Total 20'=>".$tot20." & 40'=>".$tot40;?></b></td>
		</tr>
		<?php
			if(substr($iso,0,1)==2)
			{
				$tot20 = 1;
				$tot40 = 0;
			}
			else
			{
				$tot20 = 0;
				$tot40 = 1;
			}
		}
		?>
		<!--tr>
			<td colspan="15"><b><?php  echo $yardNameQueryRow->Yard_No;?></b></td>
		</tr-->
		<?php
		$i=1;
	}
	if($shift!=$row->SHIFT)
	{	
		$shift=$row->SHIFT;		
		if($i!=1)
		{
			if(substr($iso,0,1)==2)
			{
				$tot20 = $tot20;
			}
			else
			{
				$tot40 = $tot40;
			}
		?>
		<tr>
			<td colspan="15"><b><?php  echo "Total 20'=>".$tot20." & 40'=>".$tot40;?></b></td>
		</tr>
		<?php
			if(substr($iso,0,1)==2)
			{
				$tot20 = 1;
				$tot40 = 0;
			}
			else
			{
				$tot20 = 0;
				$tot40 = 1;
			}
		}
		?>
		<tr>
			<td colspan="15"><b><?php  echo $row->SHIFT;?></b></td>
		</tr>	
		<?php	
		$i=1;
	}
	$shift=$row->SHIFT;	
	?>
	

	
	<?php if(($row->DELIVERY )=="" or ($row->DELIVERY)==null){?>
	<tr  bgcolor="#F2DC5D" align="center">
	<?php } else if (($row->DELIVERY=="") or  ($row->DELIVERY==null)) {?>
	<tr  bgcolor="#74BAE7" align="center">
	<?php } else {?>
	<tr align="center">
	<?php } ?>
			<td><?php  echo $i;?></td>
			<td><?php if($row->CONT_NO) echo $row->CONT_NO ; else echo "&nbsp;";?></td>
			<td><?php if($row->ROT_NO) echo $row->ROT_NO; else echo "&nbsp;";?></td>
			<td><?php if($iso) echo $iso; else echo "&nbsp;";?></td>
			<td><?php if($row->MLO) echo $row->MLO; else echo "&nbsp;";?></td>
			<td><?php if($row->STATU) echo $row->STATU; else echo "&nbsp;";?></td>
			<td><?php if($row->WEIGHT) echo $row->WEIGHT; else echo "&nbsp;";?></td>
			<td><?php if($row->MFDCH_DESC) echo $row->MFDCH_DESC; else echo "&nbsp;";?></td>
			<td><?php if($row->cf) echo $row->CF; else echo "&nbsp;";?></td>
			
			<td>
				<?php 
				
					if($blockNameQueryRow->Block_No)
					{
						if ($blockNameQueryRow->Block_No=="HS1" or $blockNameQueryRow->Block_No=="Y2")
							echo "UNIT-1 (".$blockNameQueryRow->Block_No.")";
						else if ($blockNameQueryRow->Block_No=="YMN")
							echo "UNIT-1 (MN)";
						else if ($blockNameQueryRow->Block_No=="Y3" or $blockNameQueryRow->Block_No=="HS6" or $blockNameQueryRow->Block_No=="HS9" or $blockNameQueryRow->Block_No=="Y10" or $blockNameQueryRow->Block_No=="Y11")
							echo "UNIT-2 (".$blockNameQueryRow->Block_No.")";
						else if ($blockNameQueryRow->Block_No=="HS7" or $blockNameQueryRow->Block_No=="Y8" or $blockNameQueryRow->Block_No=="Y8B" or $blockNameQueryRow->Block_No=="BAPX")
							echo "UNIT-3 (".$blockNameQueryRow->Block_No.")";
						else if ($blockNameQueryRow->Block_No=="Y5" or $blockNameQueryRow->Block_No=="Y6X")
							echo "UNIT-4 (".$blockNameQueryRow->Block_No.")";
						else if ($blockNameQueryRow->Block_No=="YCW" or $blockNameQueryRow->Block_No=="AB")
							echo "UNIT-5 (".$blockNameQueryRow->Block_No.")";
						else{
							echo $blockNameQueryRow->Block_No;
						}
					}
					else 
					{
						echo "&nbsp;";
					}
				
				?>
			</td>
			
			<td><font size="2"><?php if($row->DISCHARGETIME) echo $row->DISCHARGETIME; else echo "&nbsp;";?></font></td>
			<td><font size="2"><?php if($row->ASSIGNMENTDATE) echo $row->ASSIGNMENTDATE; else echo "&nbsp;";?></font></td>
			<!--td><?php if($row->PROEMTYDATE) echo $row->PROEMTYDATE; else echo "&nbsp;";?></td>
			<td><?php if($row->DELIVERY ) echo $row->DELIVERY; else echo "&nbsp;";?></td-->
		</tr>
	<?php
		//}

	} 
	mysqli_close($con_cchaportdb);
	?>
		<tr>
			<td colspan="15"><b><?php  echo "Total 20'=>".$tot20." & 40'=>".$tot40;?></b></td>			
		</tr>
		<?php 
		//if($yard_no=="GCB")
		//{
		?>
		<tr>
			<td colspan="15"><?php echo $allCont;?></td>
		</tr>



	
	<?php 
	mysqli_close($con_sparcsn4);
	if(@$_POST['options']=='html'){?>	
		</BODY>
	</HTML>
<?php }?>
