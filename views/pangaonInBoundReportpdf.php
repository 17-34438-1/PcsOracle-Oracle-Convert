<html>

<head>
    <table align="center" width="95%">
        <tr>
            <td align="center"><img align="middle" width="200px" height="70px"
                    src="<?php echo ASSETS_WEB_PATH?>fimg/cpanew.jpg"></td>
        </tr>
        <tr>
            <td align="center">
                <font size="4"><b>Pangaon In Bound Report</b></font>
            </td>
        </tr>
        <tr>
            <td align="center">
                <font size="4"><b>Date : <?php echo $be_entry_date; ?></b></font>
            </td>
        </tr>
    </table>
</head>

<body>

    <table border="1" align="center" width="70%" style="border-collapse:collapse">
        <thead>
            <tr>
                <th>Sl</th>
                <th>Container No</th>
                <th>Frieght Kind</th>
                <th>Rotation No</th>
                <th>Vesel Name</th>
                <th>Size</th>
                <th>Height</th>
                <th>Last Position Slot</th>
        </thead>

        <?php
    include('mydbPConnection.php');
    for($i=0;$i<count($rslt_sql_pangaon_inbound);$i++)
    {
    ?>

        <tr>
            <td align="center"><?php echo $i+1;?></td>
            <td align="center"><?php echo $rslt_sql_pangaon_inbound[$i]['id']?></td>
            <td align="center"><?php echo $rslt_sql_pangaon_inbound[$i]['freight_kind']?></td>
            <td align="center"><?php echo $rslt_sql_pangaon_inbound[$i]['rot_no']?></td>
            <td align="center"><?php echo $rslt_sql_pangaon_inbound[$i]['v_name']?></td>
            <td align="center"><?php echo $rslt_sql_pangaon_inbound[$i]['size']?></td>
            <td align="center"><?php echo $rslt_sql_pangaon_inbound[$i]['height']?></td>
            <td align="center"><?php echo $rslt_sql_pangaon_inbound[$i]['last_pos_slot']?></td>
        </tr>
        <?php } ?>


    </table>

    <script>
    window.print();
    </script>

</body>

</html>