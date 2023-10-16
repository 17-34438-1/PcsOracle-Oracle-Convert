<?php
header("Content-type: application/octet-stream");
	header("Content-Disposition: attachment; filename=Goods_Report_Sugar.xls;");
	header("Content-Type: application/ms-excel");
	header("Pragma: no-cache");
	header("Expires: 0");
		
include('mydbPConnection.php');
include("dbOracleConnection.php");
?>
<table border="1">
	<tr>
		<th>Year</th>
		<th>Rotaton</th>
		<th>Vessel</th>
		<th>Container No</th>
		<th>Container Size</th>
		<!--th>Visit State</th-->
		<th>Category</th>
		<!--th>Commudity Code</th-->
		<th>Commudity Desc</th>
		<th>Tonage</th>
		<th>File Clearence Date</th>
	</tr>
<?php
for($i=0;$i<count($rslt_active_import_cont);$i++)
{
	$cont_no=$rslt_active_import_cont[$i]['id'];
	$visit_state=$rslt_active_import_cont[$i]['visit_state'];
	$category=$rslt_active_import_cont[$i]['category'];
	
	$sql_cont_size="SELECT cont_size FROM igm_detail_container WHERE cont_number='$cont_no'";
	$rslt_cont_size=mysqli_query($con_cchaportdb,$sql_cont_size);
	$row_cont_size=mysqli_fetch_object($rslt_cont_size);
	$cont_size=$row_cont_size->cont_size;
	
	//4(rice) added on 20190402
	// $sql_igm_info="SELECT YEAR(file_clearence_date) AS yr,igm_detail_container.commudity_code,commudity_desc,cont_gross_weight/1000 AS cont_gross_weight,file_clearence_date
	// FROM igm_details 
	// INNER JOIN igm_detail_container ON igm_detail_container.igm_detail_id=igm_details.id 
	// INNER JOIN commudity_detail ON commudity_detail.`commudity_code`=igm_detail_container.`commudity_code`
	// WHERE igm_detail_container.commudity_code IN(4,8,9,10,36,37) AND igm_detail_container.cont_number IN('$cont_no') ORDER BY igm_detail_container.id DESC LIMIT 1";
	
	// $sql_igm_info="SELECT YEAR(file_clearence_date) AS yr,igm_detail_container.commudity_code,commudity_desc,ROUND(cont_gross_weight/1000, 2) AS cont_gross_weight,file_clearence_date
	// FROM igm_details 
	// INNER JOIN igm_detail_container ON igm_detail_container.igm_detail_id=igm_details.id 
	// INNER JOIN commudity_detail ON commudity_detail.`commudity_code`=igm_detail_container.`commudity_code`
	// WHERE igm_detail_container.commudity_code IN('4','5','8','37','10') AND igm_detail_container.cont_number IN('$cont_no') 
	// AND DATE(file_clearence_date) BETWEEN '2016-01-01' AND DATE(NOW()) ORDER BY igm_detail_container.id DESC LIMIT 1";
	
	// $sql_igm_info="SELECT YEAR(file_clearence_date) AS yr,igm_detail_container.commudity_code,commudity_desc,ROUND(cont_gross_weight/1000, 2) AS cont_gross_weight,file_clearence_date
	// FROM igm_details 
	// INNER JOIN igm_detail_container ON igm_detail_container.igm_detail_id=igm_details.id 
	// INNER JOIN commudity_detail ON commudity_detail.`commudity_code`=igm_detail_container.`commudity_code`
	// WHERE igm_detail_container.commudity_code IN('4','5','8','37','10') AND igm_detail_container.cont_number IN('$cont_no') 
	// ORDER BY igm_detail_container.id DESC LIMIT 1";

	//onion - report on 2019-09-26
	// $sql_igm_info="SELECT YEAR(file_clearence_date) AS yr,igm_detail_container.commudity_code,commudity_desc,ROUND(cont_gross_weight/1000, 2) AS cont_gross_weight,file_clearence_date
	// FROM igm_details 
	// INNER JOIN igm_detail_container ON igm_detail_container.igm_detail_id=igm_details.id 
	// INNER JOIN commudity_detail ON commudity_detail.`commudity_code`=igm_detail_container.`commudity_code`
	// WHERE igm_detail_container.commudity_code IN('37') AND igm_detail_container.cont_number IN('$cont_no') 
	// AND DATE(file_clearence_date) BETWEEN '2019-08-01' AND DATE(NOW()) ORDER BY igm_detail_container.id DESC LIMIT 1";	
	
	// sugar and peas - report on 2020-02-27
	$sql_igm_info="SELECT YEAR(igm_details.file_clearence_date) AS yr,igm_details.Import_Rotation_No,igm_masters.Vessel_Name,igm_detail_container.commudity_code,commudity_desc,ROUND(cont_gross_weight/1000, 2) AS cont_gross_weight,igm_details.file_clearence_date
	FROM igm_details 
	INNER JOIN igm_masters ON igm_masters.id=igm_details.IGM_id
	INNER JOIN igm_detail_container ON igm_detail_container.igm_detail_id=igm_details.id 
	INNER JOIN commudity_detail ON commudity_detail.`commudity_code`=igm_detail_container.`commudity_code`
	WHERE igm_detail_container.commudity_code IN('8') AND igm_detail_container.cont_number IN('$cont_no')
	ORDER BY igm_detail_container.id DESC LIMIT 1";	
	$rslt_igm_info=mysqli_query($con_cchaportdb,$sql_igm_info);
	
	while($row_igm_info=mysqli_fetch_object($rslt_igm_info))
	{
?>
	<tr>
		<td><?php echo $row_igm_info->yr ?></td>
		<td><?php echo $row_igm_info->Import_Rotation_No; ?></td>
		<td><?php echo $row_igm_info->Vessel_Name; ?></td>
		<td><?php echo $cont_no; ?></td>
		<td><?php echo $cont_size; ?></td>
		<!--td><?php echo $visit_state; ?></td-->
		<td><?php echo $category; ?></td>
		<!--td><?php echo $row_igm_info->commudity_code; ?></td-->
		<td><?php echo $row_igm_info->commudity_desc; ?></td>
		<td><?php echo $row_igm_info->cont_gross_weight; ?></td>
		<td><?php echo $row_igm_info->file_clearence_date; ?></td>
	</tr>
<?php
	}
}
?>
</table>