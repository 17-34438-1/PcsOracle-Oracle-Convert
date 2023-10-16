<?php if(@$_POST['fileOptions']=='html'){?>
<HTML>
	<HEAD>
		<TITLE>Bearth Operator Report</TITLE>
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
$ddl_imp_rot_no=@$_REQUEST['ddl_imp_rot_no']; 

	include("dbConection.php");
	include("dbOracleConnection.php");
	
	$sql=oci_parse($con_sparcsn4_oracle,"select vvd_gkey from vsl_vessel_visit_details where ib_vyg='$ddl_imp_rot_no'");
	oci_execute($sql);
	$rowVessel=oci_fetch_object($sql);
	$vvdGkey=$rowVessel->vvd_gkey;
	
	$sql="call ctmsmis.update_containers_by_vvd_gkey($vvdGkey)";
	$res=mysqli_query($con_sparcsn4,$sql);
	
	if($type=="deli")
	{
		$col = "b.time_out";		
		$head = "Date Wise Delivery Import Container List";
	}
	
	elseif($type=="assign")
	{
		$col = "b.flex_date01";
		$head = "Assignment Date Wise Import Container List";
	}
	else
	{
		$col = "b.time_in";
		$head = "Discharge Import Container List";
	}
	
	?>
<table width="100%" border ='0' cellpadding='0' cellspacing='0'>
<tr bgcolor="#ffffff" align="center" height="100px">
		<td colspan="13" align="center">
			<table border=0 width="100%">
				
				<!--tr align="center">
					<td colspan="12"><img align="middle" src="<?php echo IMG_PATH?>cpanew.jpg"></td>
				</tr-->	
				<tr>
					<td colspan="12" align="center"><img width="200px" height="60px" src="<?php echo IMG_PATH?>cpanew.jpg"></td>
				</tr>				
				<tr align="center">
					<td colspan="12"><font size="4"><b><?php echo $head; ?></b></font></td>
				</tr>
				<tr align="center">
					<td colspan="12"><font size="4"><b>DATE : <?php echo $fromdate; ?>&nbsp;&nbsp;Yard NO : <?php echo $yard_no; ?></b></font></td>
				</tr>
			</table>
		
		</td>
		
	</tr>
	</table>

<?php

$queryStr="SELECT DISTINCT *

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
((SELECT SUBSTR(srv_event_field_changes.new_value,7)
FROM srv_event
INNER JOIN srv_event_field_changes ON srv_event_field_changes.event_gkey=srv_event.gkey
WHERE srv_event.applied_to_gkey=a.gkey AND srv_event.event_type_gkey=31450 FETCH FIRST 1 ROWS ONLY)) AS yardValue,

(SELECT ref_equip_type.id FROM inv_unit
INNER JOIN ref_equipment ON ref_equipment.gkey=inv_unit.eq_gkey
INNER JOIN ref_equip_type ON ref_equip_type.gkey=ref_equipment.eqtyp_gkey 
INNER JOIN inv_unit_fcy_visit  ON inv_unit_fcy_visit.unit_gkey=inv_unit.gkey
WHERE inv_unit_fcy_visit.unit_gkey=a.gkey FETCH FIRST 1 ROWS ONLY) AS iso_code,

b.flex_string10 AS rot_no,
b.time_in AS dischargetime,
b.time_out AS delivery,
g.id AS mlo,
k.name as cf,
config_metafield_lov.mfdch_desc,
a.freight_kind AS statu,
a.goods_and_ctr_wt_kg AS weight,
(SELECT SUBSTR(srv_event_field_changes.new_value,7)
FROM srv_event
INNER JOIN srv_event_field_changes ON srv_event_field_changes.event_gkey=srv_event.gkey
WHERE srv_event.applied_to_gkey=a.gkey AND srv_event.event_type_gkey=31450 FETCH FIRST 1 ROWS ONLY) AS carrentPosition,

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
WHERE ( b.flex_date01 BETWEEN  TO_DATE(CONCAT('$fromdate', ' 00:00:00'),'YYYY-MM-DD HH24-MI-SS') 
AND TO_DATE(CONCAT('$fromdate', ' 23:59:59'),'YYYY-MM-DD HH24-MI-SS') ) 
AND config_metafield_lov.mfdch_value NOT IN ('CANCEL','OCD','APPCUS','APPOTH','APPREF')
)  tmp  order by sl,shift,proEmtyDate";
	$query=oci_parse($con_sparcsn4_oracle,$queryStr);
	oci_execute($query);
	

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
	include("mydbPConnection.php");
	$result=array();
	$numRows= oci_fetch_all($query, $result, null, null, OCI_FETCHSTATEMENT_BY_ROW);
	oci_free_statement($query);
	//$numRows=mysqli_num_rows($query);
	$query=oci_parse($con_sparcsn4_oracle,$queryStr);
	oci_execute($query);

	while(($row=oci_fetch_object($query)) != false) {
	$yardName="";
	$yardValue="";
	$yardValue=$row->YARDVALUE;
	$yardQuery="SELECT ctmsmis.cont_yard('$yardValue') AS Yard_No";
    $queryYardRes=mysqli_query($con_sparcsn4,$yardQuery);
	$yardRowsNum=mysqli_num_rows($queryYardRes);
	$yardRow=mysqli_fetch_object($queryYardRes);
	$yardName=$yardRow->Yard_No;
	
	if($yardName==$yard_no)	{
	$i++;
	$stayed=$stayed+$row->STAY;
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
		if($row->SHIFT =="Shift A")
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
			@$b40 = $b40+1;
		if($row->SHIFT=="Shift C")
			@$c40 = $c40+1;
	}
		
	if($shift==$row->SHIFT or $i==1)
	{
		if(substr($iso,0,1)==2)
			$tot20 = $tot20+1;
		else 
			$tot40 = $tot40+1;
	}
	if(@$totalcon==$row->CONT_NO or $i==1)
	{
		if(substr($iso,0,1)==2)
			$tot20 = $tot20+1;
		else 
			$tot40 = $tot40+1;
	}
	if($yard!=$yardRow->Yard_No)
	{
		$yard=$yardRow->Yard_No;
		if($i!=1)
		{
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
		$i=1;
	}
	if($shift!=$row->SHIFT)
	{	
		$shift=$row->SHIFT;		
		if($i!=1)
		{
			if(substr($iso,0,1)==2)
			{
				$tot20 = $tot20-1;
			}
			else
			{
				$tot40 = $tot40-1;
			}
		
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
		$i=1;
	}
		$shift=$row->SHIFT;	
  }
} 
	mysqli_close($con_cchaportdb);
	?>
	<br/>
	<table class="table table-bordered table-responsive table-hover table-striped mb-none">
		<thead>
			<tr>
				<th rowspan="2">UNIT/YARD</th>
				<th rowspan="2">SHIFT</th>
				<th colspan="3">TOTAL ASSIGNMENT</th>
				<th colspan="3">STRIPPED BY HHT</th>
				<th colspan="3">BALANCE</th>
				<th rowspan="2">REMARKS</th>
			</tr>
			<tr>
				<th>20(L)</th>
				<th>40(L)</th>
				<th>Total</th>
				<th>20(E)</th>
				<th>40(E)</th>
				<th>Total</th>
				<th>20(L)</th>
				<th>40(L)</th>
				<th>Total</th>
			</tr>
		</thead>
		<tbody>
			<tr align="center">
				<td><?php echo $yard_no; ?></td>
				<td>A</td>
				<td><?php echo $j20;?></td>
				<td><?php echo $j40;?></td>
				<td><?php echo $j20+$j40?></td>
				<td><?php echo $a20;?></td>
				<td><?php echo $a40;?></td>
				<td><a href="<?php echo site_url('report/shiftWiseContainerReport/A/'.$yard_no.'/'.$fromdate);?>" target="_blank"><?php echo $a20+$a40;?></a></td>
				<td>
					<?php 
						$balA20 = $j20-$a20;
						echo $balA20;
					?>
				</td>
				<td>
					<?php 
						$balA40 = $j40-$a40;
						echo $balA40;
					?>
				</td>
				<td><a href="<?php echo site_url('report/shiftWiseContainerReport/N/'.$yard_no.'/'.$fromdate);?>" target="_blank"><?php echo $balA20+$balA40;?></td>
				<td width="150"></td>
			</tr>
			<tr align="center">
				<td><?php echo $yard_no; ?></td>
				<td>B</td>
				<td><?php echo $balA20;?></td>
				<td><?php echo $balA40;?></td>
				<td><?php echo $balA20+$balA40?></td>
				<td><?php echo $b20;?></td>
				<td><?php echo @$b40;?></td>
				<td><a  href="<?php echo site_url('report/shiftWiseContainerReport/B/'.$yard_no.'/'.$fromdate);?>" target="_blank"><?php echo $b20+@$b40;?></a></td>
				<td>
					<?php 
						$balB20 = $balA20-$b20;
						echo $balB20;
					?>
				</td>
				<td>
					<?php 
						@$balB40 = $balA40-$b40;
						echo $balB40;
					?>
				</td>
				<td><a href="<?php echo site_url('report/shiftWiseContainerReport/N/'.$yard_no.'/'.$fromdate);?>" target="_blank"><?php echo $balB20+$balB40;?></td>
				<td></td>
			</tr>
			<tr align="center">
				<td><?php echo $yard_no; ?></td>
				<td>C</td>
				<td><?php echo $balB20;?></td>
				<td><?php echo $balB40;?></td>
				<td><?php echo $balB20+$balB40?></td>
				<td><?php echo $c20;?></td>
				<td><?php echo @$c40;?></td>
				<td><a  href="<?php echo site_url('report/shiftWiseContainerReport/C/'.$yard_no.'/'.$fromdate);?>" target="_blank"><?php echo $c20+@$c40;?></a></td>
				<td>
					<?php 
						$balC20 = $balB20-$c20;
						echo $balC20;
					?>
				</td>
				<td>
					<?php 
						@$balC40 = $balB40-$c40;
						echo $balC40;
					?>
				</td>
				<td><a href="<?php echo site_url('report/shiftWiseContainerReport/N/'.$yard_no.'/'.$fromdate);?>" target="_blank"><?php echo $balC20+$balC40;?></td>
				<td><a href="<?php echo site_url('report/shiftWiseContainerReport/Stay/'.$yard_no.'/'.$fromdate);?>" target="_blank"><?php echo "Stayed: ". $stayed; ?></a></td>
			</tr>
		</tbody>
	</table>

	
	<?php 
	mysqli_close($con_sparcsn4);
	if(@$_POST['fileOptions']=='html'){?>	
		</BODY>
	</HTML>
<?php }?>
