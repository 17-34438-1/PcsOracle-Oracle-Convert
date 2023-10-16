<?php
// echo "view";
include('dbConection.php');
include("dbOracleConnection.php");	
$rotation = $_GET['rot'];
//$rotation = "2016/3";
$strVslInfo = "select vsl_vessels.id,vsl_vessels.name
from vsl_vessel_visit_details
inner join vsl_vessels on vsl_vessels.gkey=vsl_vessel_visit_details.vessel_gkey
where vsl_vessel_visit_details.ib_vyg='$rotation'";
$rtnVslInfo = oci_parse($con_sparcsn4_oracle,$strVslInfo);
oci_execute($rtnVslInfo);
$rowVslInfo = oci_fetch_object($rtnVslInfo);
$vslId= $rowVslInfo->ID;
$vslName= $rowVslInfo->NAME;

$str = "select bay,pairedWith from ctmsmis.misBayView where vslId='$vslId' order by bay asc";
//echo $str;
$res = mysqli_query($con_sparcsn4,$str);
$mumrows = mysqli_num_rows($res);
$dBay = "";
$i=1;
while($row = mysqli_fetch_object($res))
{
	//echo "i=".$i."n=".$mumrows;
	$bay = $row->bay;
	if($bay<10)
		$bay="0".$bay;
	else
		$bay=$bay;
	$pairedWith = $row->pairedWith;
	if($pairedWith<10)
		$pairedWith="0".$pairedWith;
	else
		$pairedWith=$pairedWith;
		
	if($pairedWith!=0)
		$bayP = $bay."(".$pairedWith.")";
	else
		$bayP = $bay;
	if($i==$mumrows)	
		$dBay.=$bayP;
	elseif($i%5==0)
		$dBay.=$bayP.",<br/>";
	else 
		$dBay.=$bayP.", ";
	$i++;
}
?>

<table border="0">
	<tr>
		<td>
			Vessel:
		</td>
		<td>
			<b><?php echo $vslName;?></b>
		</td>
	</tr>
	<?php
	if($mumrows>0)
	{
	?>
	<tr>
		<td>
			Drawn Bay:
		</td>
		<td>
			<b><?php echo $dBay;?></b>
		</td>
	</tr>	
	<tr>
		<td colspan="2" align="center">
			<a href="<?php  echo site_url("report/blankBayView"); ?>?get=yes&vslId=<?php echo $vslId?>&vslName=<?php echo $vslName?>" target="_blank">View Layout</a>
		</td>
	</tr>
	<?php
	}
	?>
	<?php //mysqli_close($con_sparcsn4);?>
</table>