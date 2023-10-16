<?php if($_POST['options']=='html'){?>
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
	else if($_POST['options']=='xl'){
		header("Content-type: application/octet-stream");
		header("Content-Disposition: attachment; filename=IMPORT_SUMMERY.xls;");
		header("Content-Type: application/ms-excel");
		header("Pragma: no-cache");
		header("Expires: 0");

	}

	
	
	?>
<html>
<title>Rotation Wise Export Container  Report</title>
<body>
<table width="100%" border ='0' cellpadding='0' cellspacing='0'>
<tr bgcolor="#ffffff" align="center" height="100px">
		<td colspan="13" align="center">
			<table border=0 width="100%">
				
				<!--tr align="center">
					<td colspan="12"><font size="4"><b> CHITTAGONG PORT AUTHORITY,CHITTAGONG</b></font></td>
				</tr-->
				<?php
				if($_POST['options']=='html')
				{
				?>
					<tr>
						<td colspan="12" align="center"><img width="250px" height="80px" src="<?php echo ASSETS_WEB_PATH?>fimg/cpanew.jpg"></td>
					</tr>
				<?php
				}
				else
				{
				?>
					<tr align="center">
						<td colspan="12"><font size="4"><b>CHITTAGONG PORT AUTHORITY,CHITTAGONG</b></font></td>
					</tr>
				<?php
				}
				?>
			
				<tr align="center">
					<td colspan="12"><font size="4"><b><u>Rotation Wise Export Container  Report</u></b></font></td>
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
		<td style="border-width:1px;border-style: double;" rowspan="2"><b>SlNo.</b></td>
		<td style="border-width:1px;border-style: double;" rowspan="2"><b>Rotation</b></td>
		<td style="border-width:1px;border-style: double;" rowspan="2"><b>Vessel Name.</b></td>
		<td style="border-width:1px;border-style: double;" rowspan="2"><b>Phase.</b></td>
		<td style="border-width:1px;border-style: double;" rowspan="2"><b>ATA.</b></td>
		<td style="border-width:1px;border-style: double;" rowspan="2"><b>ATD.</b></td>
		<td style="border-width:1px;border-style: double;" rowspan="2"><b>Berth Operator.</b></td>
		<td style="border-width:1px;border-style: double;" rowspan="2"><b>Total Unit.</b></td>
		<td style="border-width:1px;border-style: double;" colspan="2"><b>MLO Wise Loaded</b></td>
		<td style="border-width:1px;border-style: double;" colspan="2"><b>Import Container</b></td>
	</tr>
	<tr align="center">
		<td style="border-width:1px;border-style: double;"><b>Details</b></td>
		<td style="border-width:1px;border-style: double;"><b>Summary</b></td>
		<td style="border-width:1px;border-style: double;"><b>Balance/Discharge List</b></td>
		<td style="border-width:1px;border-style: double;"><b>Summary</b></td>
	</tr>

<?php
	// echo $todate;
	// echo $fromdate;


include("FrontEnd/mydbPConnectionctms.php");	
include("dbConection.php");
include("dbOracleConnection.php");




$query="
SELECT vsl_vessel_visit_details.ib_vyg ,
vsl_vessels.name ,
				argo_carrier_visit.phase ,
 argo_carrier_visit.ata,
  argo_carrier_visit.atd ,
vsl_vessel_visit_details.flex_string02 ,
COUNT(inv_unit.gkey) AS exp_cont
FROM inv_unit 
INNER JOIN inv_unit_fcy_visit  ON  inv_unit_fcy_visit.unit_gkey = inv_unit.gkey
INNER JOIN argo_carrier_visit ON inv_unit_fcy_visit.actual_ob_cv=argo_carrier_visit.gkey 
INNER JOIN vsl_vessel_visit_details ON vsl_vessel_visit_details.vvd_gkey=argo_carrier_visit.cvcvd_gkey
INNER JOIN vsl_vessels ON vsl_vessels.gkey=vsl_vessel_visit_details.vessel_gkey
WHERE to_char(argo_carrier_visit.ata,'YYYY-MM-DD')  BETWEEN '$fromdate' and '$todate'
GROUP BY vsl_vessel_visit_details.ib_vyg,name,phase,ata,atd,vsl_vessel_visit_details.flex_string02   
";



 $stid = oci_parse($con_sparcsn4_oracle, $query);
 oci_execute($stid);

 $i=0;
 $j=0;
 $ib_vyg="";
 $vsl_name = "";
	$ata = "";
	$atd = "";
	$berth="";
	$phase="";
 while (($row = oci_fetch_object($stid)) != false)

 {
	$ib_vyg=$row->IB_VYG;
	$vsl_name = $row->NAME;
	 $ata = $row->ATA ;

	 $atd = $row->ATD ;
	 $berth=$row->BERTHOP;
	 $phase=$row->PHASE;

	 $bop=$row->EXP_CONT;

	

	



	 $i++;

	?>

<tr align="center">
		<td><?php  echo $i;?></td>
		<td><?php echo $ib_vyg;?></td>
		<td><?php echo $vsl_name;?></td>
		<td><?php  echo $phase;?></td>
		<td><?php echo $ata;?></td>
		<td><?php echo $atd; ?></td>
		<td></td>
		<td><?php echo $exp_cont;?></td>
		<td>
			<a href="<?php echo site_url('report/myAllReportView/'.$ib_vyg.'/1/detail');?>" target="_blank">View</a>
		</td>
		<td>
			<a href="<?php echo site_url('report/myAllReportView/'.$ib_vyg.'/1/summary');?>" target="_blank"><font color="#990000">View</font></a>
		</td>	
		<td>
			<a href="<?php echo site_url('report/myAllReportView/'.$ib_vyg.'/2/detail');?>" target="_blank">View</a>
		</td>
		<td>
			<a href="<?php echo site_url('report/myAllReportView/'.$ib_vyg.'/2/summary');?>" target="_blank"><font color="#990000">View</font></a>
		</td>		
	</tr>


<?php
 }
?>

</table>
<br />
<br />



<?php 
//mysql_close($con_ctmsmis);
if($_POST['options']=='html'){?>	
	</BODY>
</HTML>
<?php }?>








 