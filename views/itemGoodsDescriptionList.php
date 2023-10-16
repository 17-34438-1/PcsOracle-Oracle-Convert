<?php
    header("Content-type: application/octet-stream");
    header("Content-Disposition: attachment; filename=Goods_Report.xls;");
    header("Content-Type: application/ms-excel");
    header("Pragma: no-cache");
    header("Expires: 0");
    include('mydbPConnection.php');
?>

<table border="1" align="center">
    <tr>
        <th>Year</th>
        <th>Rotaton</th>
        <th>Vessel</th>
        <th>Container No</th>
        <th>Container Size</th>
        <th>Category</th>
        <th>Commudity Desc</th>
        <th>Tonage</th>
        <th>File Clearence Date</th>
        <th>Importer Name</th>
        <th>Importer Address</th>
    </tr>
    <?php

    //echo count($containerResult);
    for($i=0;$i<count($containerResult);$i++)
    {
        $cont_no=$containerResult[$i]['id'];
        $visit_state=$containerResult[$i]['visit_state'];
        $category=$containerResult[$i]['category'];

        $sql_cont_size="SELECT cont_size FROM igm_detail_container WHERE cont_number='$cont_no'";
        $row_cont_size=$this->bm->dataSelectDB1($sql_cont_size);
        $cont_size="";

        for($j=0;$j<count($row_cont_size);$j++){
            $cont_size = $row_cont_size[$j]['cont_size'];
        }

		$sql_igm_info = "SELECT YEAR(igm_details.file_clearence_date) AS yr,igm_details.Import_Rotation_No,igm_masters.Vessel_Name,igm_detail_container.commudity_code,
		commudity_desc,ROUND(cont_gross_weight/1000, 2) AS cont_gross_weight,igm_details.file_clearence_date,
		igm_details.Notify_name,igm_details.Notify_address
		FROM igm_details 
		INNER JOIN igm_masters ON igm_masters.id=igm_details.IGM_id
		INNER JOIN igm_detail_container ON igm_detail_container.igm_detail_id=igm_details.id 
		INNER JOIN commudity_detail ON commudity_detail.`commudity_code`=igm_detail_container.`commudity_code`
		WHERE igm_detail_container.commudity_code IN('$item') AND igm_detail_container.cont_number IN('$cont_no')
		ORDER BY igm_detail_container.id DESC LIMIT 1";

        $row_igm_info = $this->bm->dataSelectDB1($sql_igm_info);

        for($k=0;$k<count($row_igm_info);$k++)
        {
            ?>
            <tr>
                <td><?php echo $row_igm_info[$k]['yr'] ?></td>
                <td><?php echo $row_igm_info[$k]['Import_Rotation_No']; ?></td>
                <td><?php echo $row_igm_info[$k]['Vessel_Name']; ?></td>
                <td><?php echo $cont_no; ?></td>
                <td><?php echo $cont_size; ?></td>
                <td><?php echo $category; ?></td>
                <td><?php echo $row_igm_info[$k]['commudity_desc']; ?></td>
                <td><?php echo $row_igm_info[$k]['cont_gross_weight']; ?></td>
                <td><?php echo $row_igm_info[$k]['file_clearence_date']; ?></td>
                <td><?php echo $row_igm_info[$k]['Notify_name']; ?></td>
                <td><?php echo $row_igm_info[$k]['Notify_address']; ?></td>
            </tr>
            <?php
        }
    }
    ?>
</table>