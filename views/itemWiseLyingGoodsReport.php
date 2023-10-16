<?php
header("Content-type: application/octet-stream");
header("Content-Disposition: attachment; filename=Lying_Goods_Report.xls;");
header("Content-Type: application/ms-excel");
header("Pragma: no-cache");
header("Expires: 0");
include('mydbPConnection.php');

?>
<table border="1">
    <?php
    if($options == 'details')
	{
    ?>
        <h4><span><b>Lying Commodity Details From <?php echo $goodsfromdate;?> To <?php echo $goodstodate;?>
                    <?php if($search_value){ ?>
                        Importer Name: <?php echo $search_value;?>
                    <?php } ?>
               </b></span></h4>
    <tr>
        <!--        <th>Year</th>-->
        <?php if(!$search_value){ ?>
        <th>Importer Name</th>
        <?php } ?>

        <th>Rotaton No</th>
        <th>Container No</th>
        <th>Container Size</th>
        <th>Commdity Desc</th>
        <th>Tonage</th>
        <th>Goods Description</th>
        <th>Notify Name</th>
        <th>Notify Desc</th>

    </tr>
    <?php
    $total = 0;
	foreach($containerResult as $cntReslt)
	{
		$ids[] = $cntReslt['id'];
	}
	 $cont_ids=join("','",$ids);
	

	for($i=0;$i<count($containerResult);$i++)
    {
        $cont_no=$containerResult[$i]['id'];
	/* 	if($search_value)		// importer
		{
			$sql_igm_info = "SELECT igm_details.Import_Rotation_No,igm_masters.Vessel_Name,igm_detail_container.cont_number,igm_detail_container.cont_size,igm_detail_container.cont_height,
			commudity_detail.commudity_desc,
			SUM(igm_detail_container.cont_gross_weight)/1000 AS tonage, 
			igm_details.Notify_name, igm_details.NotifyDesc, igm_details.Description_of_Goods
			FROM igm_details 
			INNER JOIN igm_masters ON igm_masters.id=igm_details.IGM_id
			INNER JOIN igm_detail_container ON igm_detail_container.igm_detail_id=igm_details.id 
			LEFT JOIN commudity_detail ON commudity_detail.`commudity_code`=igm_detail_container.`commudity_code`
			WHERE UPPER(igm_details.Description_of_Goods) LIKE UPPER('% Rice%') UPPER(igm_details.Description_of_Goods) not LIKE UPPER('%Rice cooker%')  AND Notify_name LIKE '%$search_value%'
			AND igm_detail_container.cont_number IN('$cont_no')
			ORDER BY igm_detail_container.id DESC LIMIT 1";			
		}			   
		else
		{ */
			$sql_igm_info = "SELECT igm_details.Import_Rotation_No,igm_detail_container.cont_number,igm_detail_container.cont_size,igm_detail_container.cont_height,
			commudity_detail.commudity_desc,
			SUM(igm_detail_container.cont_gross_weight)/1000 AS tonage, 
			igm_details.Notify_name, igm_details.NotifyDesc, igm_details.Description_of_Goods
			FROM igm_detail_container
			INNER JOIN igm_details ON igm_details.id=igm_detail_container.igm_detail_id 
			INNER JOIN commudity_detail ON commudity_detail.`commudity_code`=igm_detail_container.`commudity_code`
			WHERE igm_details.Import_Rotation_No LIKE '2022/%' AND 
			igm_detail_container.cont_number IN('$cont_ids') 
			AND UPPER(igm_details.Description_of_Goods) LIKE UPPER('%search_value%')


			UNION ALL
			
			SELECT igm_supplimentary_detail.Import_Rotation_No,igm_sup_detail_container.cont_number,igm_sup_detail_container.cont_size,igm_sup_detail_container.cont_height,
			commudity_detail.commudity_desc,
			SUM(igm_sup_detail_container.cont_gross_weight)/1000 AS tonage, 
			igm_supplimentary_detail.Notify_name, igm_supplimentary_detail.NotifyDesc, igm_supplimentary_detail.Description_of_Goods
			FROM igm_sup_detail_container
			INNER JOIN igm_supplimentary_detail ON igm_supplimentary_detail.id=igm_sup_detail_container.igm_sup_detail_id 
			INNER JOIN commudity_detail ON commudity_detail.`commudity_code`=igm_sup_detail_container.`commudity_code`
			WHERE igm_supplimentary_detail.Import_Rotation_No LIKE '2022/%' AND 
			igm_sup_detail_container.cont_number IN('$cont_ids') AND UPPER(igm_supplimentary_detail.Description_of_Goods) LIKE UPPER('%search_value%')";

			/* $sql_igm_info = "SELECT igm_details.Import_Rotation_No,igm_masters.Vessel_Name,igm_detail_container.cont_number,igm_detail_container.cont_size,igm_detail_container.cont_height,
			commudity_detail.commudity_desc,
			SUM(igm_detail_container.cont_gross_weight)/1000 AS tonage, 
			igm_details.Notify_name, igm_details.NotifyDesc, igm_details.Description_of_Goods
			FROM igm_details 
			INNER JOIN igm_masters ON igm_masters.id=igm_details.IGM_id
			INNER JOIN igm_detail_container ON igm_detail_container.igm_detail_id=igm_details.id 
			LEFT JOIN commudity_detail ON commudity_detail.`commudity_code`=igm_detail_container.`commudity_code`
			WHERE UPPER(igm_details.Description_of_Goods) LIKE UPPER('% Rice%') and UPPER(igm_details.Description_of_Goods) not LIKE UPPER('%Rice cooker%') AND igm_detail_container.cont_number IN('$cont_no')
			ORDER BY igm_detail_container.id DESC LIMIT 1"; */
			
			// $sql_igm_info = "SELECT igm_details.Import_Rotation_No,igm_masters.Vessel_Name,igm_detail_container.cont_number,igm_detail_container.cont_size,igm_detail_container.cont_height,
			// commudity_detail.commudity_desc,
			// SUM(igm_detail_container.cont_gross_weight)/1000 AS tonage, 
			// igm_details.Notify_name
			// FROM igm_details 
			// INNER JOIN igm_masters ON igm_masters.id=igm_details.IGM_id
			// INNER JOIN igm_detail_container ON igm_detail_container.igm_detail_id=igm_details.id 
			// INNER JOIN commudity_detail ON commudity_detail.`commudity_code`=igm_detail_container.`commudity_code`
			// WHERE (Description_of_Goods LIKE 'MAIZE%' OR Description_of_Goods LIKE '% MAIZE' OR Description_of_Goods LIKE '% MAIZE %') AND igm_detail_container.cont_number IN('$cont_no')
			// ORDER BY igm_detail_container.id DESC LIMIT 1";
		//}
        $rslt_igm_info = mysqli_query($con_cchaportdb,$sql_igm_info);

        while($row_igm_info = mysqli_fetch_object($rslt_igm_info))
        {
			if($row_igm_info->cont_number!="" or $row_igm_info->cont_number!=null)
			{
				//echo $sql_igm_info;
            ?>
        <tr>
            <?php if(!$search_value){ ?>
                <td><?php echo $row_igm_info->Notify_name; ?></td>
            <?php } ?>
            <td><?php echo $row_igm_info->Import_Rotation_No; ?></td>
            <td><?php echo $row_igm_info->cont_number; ?></td>
            <td><?php echo $row_igm_info->cont_size;  ?></td>
            <td><?php echo $row_igm_info->commudity_desc; ?></td>
            <td><?php echo $row_igm_info->tonage; ?></td>
            <td><?php echo $row_igm_info->Description_of_Goods; ?></td>
            <td><?php echo $row_igm_info->Notify_name; ?></td>
            <td><?php echo $row_igm_info->NotifyDesc;  ?></td>
        </tr>
          
            <?php
			$total = $total +  $row_igm_info->tonage;
			}
        }
    }

     ?>


    <tr>
        <?php if(!$search_value){ ?>
            <td align="center"></td>
        <?php } ?>
        <td align="center"></td>
        <td align="center"></td>
        <td align="center"></td>
        <td align="center"></td>
        <td align="center"><b>Total</b></td>
        <td align="center"><b><?php echo $total; ?></b></td>
        <td align="center"></td>

    </tr>
    <?php } else {


		?>

          <h4><span><b>Commodity Summary Month: <?php echo $mnt ?><b></b></span></h4>

        <tr>
            <?php if($group_by == 'rotation'){ ?>
                <th>Rotation No</th>
            <?php }else{ ?>
                <th>Importer Name</th>
            <?php }?>
            <th>Month</th>
            <th>Commudity Desc</th>
            <th>Tonage</th>
            <th>Notify Name</th>
        </tr>
        <?php
		    $total = 0;
			
	foreach($containerResult as $cntReslt)
	{
		$ids[] = $cntReslt['id'];
	}
	 $cont_ids=join("','",$ids);
	

		
		 if($group_by == 'rotation')
			{
                $lyingGoodsQuery ="SELECT val,mnt,commudity_desc,(SUM(cont_gross_weight)/1000) AS tonnagefrom, Notify_name
					FROM (
					SELECT igm_details.Import_Rotation_No AS val,MONTHNAME(file_clearence_date) AS mnt,commudity_desc,cont_gross_weight,Notify_name FROM igm_details 
					INNER JOIN igm_detail_container ON igm_detail_container.igm_detail_id=igm_details.id 
					INNER JOIN commudity_detail  ON commudity_detail.commudity_code = igm_detail_container.commudity_code
					WHERE igm_detail_container.commudity_code='$item' AND DATE(igm_details.file_clearence_date) BETWEEN '$goodsfromdate' AND '$goodstodate'
					 AND igm_detail_container.cont_number IN('$cont_ids')
					) 
					AS tbl GROUP BY val";

               // $goodsResult = $this->bm->dataSelectDb1($goodsQuery);
               // $data['mnt'] = $goodsResult[0]['mnt'];
			   
				}
			else{
                $lyingGoodsQuery ="SELECT val,mnt,commudity_desc,(SUM(cont_gross_weight)/1000) AS tonnagefrom, Notify_name
					FROM (
					SELECT igm_details.Notify_name AS val,MONTHNAME(file_clearence_date) AS mnt,commudity_desc,cont_gross_weight,Notify_name FROM igm_details 
					INNER JOIN igm_detail_container ON igm_detail_container.igm_detail_id=igm_details.id 
					INNER JOIN commudity_detail  ON commudity_detail.commudity_code = igm_detail_container.commudity_code
					WHERE igm_detail_container.commudity_code='$item' AND DATE(igm_details.file_clearence_date) BETWEEN '$goodsfromdate' AND '$goodstodate'
					AND igm_detail_container.cont_number IN('$cont_ids') AND Notify_name LIKE '%$search_value%' 
					) 
					AS tbl GROUP BY val";

               // $goodsResult = $this->bm->dataSelectDb1($goodsQuery);
               // $data['mnt'] = $goodsResult[0]['mnt'];
            }
		
		 $rslt_igm_info = mysqli_query($con_cchaportdb,$lyingGoodsQuery);
		
		while($row_igm_info = mysqli_fetch_object($rslt_igm_info))
        {

				//echo $sql_igm_info;
            ?>
        <tr>
            
            <td><?php echo $row_igm_info->val; ?></td>
            <td><?php echo $row_igm_info->mnt; ?></td>
            <td><?php echo $row_igm_info->commudity_desc; ?></td>
            <td><?php echo $row_igm_info->tonnagefrom;  ?></td>
            <td><?php echo $row_igm_info->Notify_name; ?></td>
        </tr>
          
            <?php
			$total = $total +  $row_igm_info->tonnagefrom;
			}

		
        ?>
        <tr>
            <td></td>
            <td></td>
            <td align="center"><b>Total</b></td>
            <td align="center"><b><?php echo $total; ?></b></td>
            <td></td>
        </tr>
    <?php } ?>


</table>