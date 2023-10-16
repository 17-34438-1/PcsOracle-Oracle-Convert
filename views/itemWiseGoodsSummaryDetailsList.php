<?php
header("Content-type: application/octet-stream");
header("Content-Disposition: attachment; filename=Goods_Report.xls;");
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
        <h4><span><b>Commudity Details From <?php echo $goodsfromdate;?> To <?php echo $goodstodate;?>
                    <?php if($search_value){ ?>
                        Importer Name: <?php echo $search_value;?>
                    <?php } ?>
               </b></span></h4>
    <tr>
        <?php if(!$search_value){ ?>
        <th>Importer Name</th>
        <?php } ?>

        <th>Rotaton No</th>
        <th>Vessel Name</th>
        <th>Container No</th>
        <th>Container Size</th>
        <th>Commudity Desc</th>
        <th>Tonage</th>
        <th>Notify Desc</th>   
    </tr>
    <?php
    $total = 0;
    for($i=0;$i<count($goodsResult);$i++)
    {
    ?>
        <tr>
            <?php 
			if(!$search_value)
			{ 
			?>
 
 
 <td><?php echo $goodsResult[$i]['Notify_name'] ?></td>
            <?php 
			} 
			?>
            <td><?php echo $goodsResult[$i]['Import_Rotation_No'] ?></td>
            <td><?php echo $goodsResult[$i]['Vessel_Name']; ?></td>
            <td><?php echo $goodsResult[$i]['cont_number']; ?></td>
            <td><?php echo $goodsResult[$i]['cont_size']; ?></td>
            <td><?php echo $goodsResult[$i]['commudity_desc']; ?></td>
            <td><?php echo $goodsResult[$i]['tonage']; ?></td>
            <td><?php echo $goodsResult[$i]['NotifyDesc']; ?></td>
        </tr>
    <?php
        $total = $total + $goodsResult[$i]['tonage'];
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
    <?php } else { ?>

          <h4><span><b>Commudity Summary Month: <?php echo $mnt ?><b></b></span></h4>

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
        for($i=0;$i<count($goodsResult);$i++)
        {
            ?>
            <tr>
                <td><?php echo $goodsResult[$i]['val'] ?></td>
                <td><?php echo $goodsResult[$i]['mnt'] ?></td>
                <td><?php echo $goodsResult[$i]['commudity_desc']; ?></td>
                <td><?php echo $goodsResult[$i]['tonnagefrom']; ?></td>
                <td><?php echo $goodsResult[$i]['Notify_name']; ?></td>
            </tr>
            <?php
            $total = $total + $goodsResult[$i]['tonnagefrom'];
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