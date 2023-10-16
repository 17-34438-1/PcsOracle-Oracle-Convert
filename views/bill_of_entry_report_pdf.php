<html>
<head>
    <table align="center" width="95%">
        <tr>
            <td  align="center"><img align="middle"  width="200px" height="70px" src="<?php echo ASSETS_WEB_PATH?>fimg/cpanew.jpg"></td>
        </tr>
        <tr>
            <td align="center"><font size="4"><b>DATE WISE BILL OF ENTRY REPORT</b></font></td>
        </tr>
        <tr>
            <td align="center"><font size="4"><b>Date : <?php echo $be_entry_date; ?></b></font></td>
        </tr>
    </table>
</head>
<body>

<table border="1" align="center" width="70%" style="border-collapse:collapse">
    <thead>
    <tr>
        <th>Sl</th>
        <th>Office Code</th>
        <th>C Number</th>
        <th>BE Date</th>
        <th>Exit Note Number</th>
        <th>Entry Date</th>
        <th>Total Container</th>
    </tr>
    </thead>
<!--    <tr>-->
<!--        <th>Sl</th>-->
<!--        <th>Office Code</th>-->
<!--        <th>C Number</th>-->
<!--        <th>BE Date</th>-->
<!--        <th>Entry Date</th>-->
<!--        <th>Total Container</th>-->
<!--    </tr>-->

    <?php
    include('mydbPConnection.php');
    $tot_entry=0;
    $exit_note_number = 0;
    $not_exit_note_number = 0;
    $j =0;
    $k =0;
    for($i=0;$i<count($rslt_be_report_datewise);$i++)
    {


    $reg_no=$rslt_be_report_datewise[$i]['reg_no'];
    $reg_date=$rslt_be_report_datewise[$i]['reg_date'];

    $sql_tot_cont="SELECT COUNT(*) AS tot_cont
							FROM sad_container
							INNER JOIN sad_info ON sad_info.id=sad_container.sad_id
							WHERE reg_no='$reg_no' and reg_date='$reg_date'";

    $rslt_tot_cont=mysql_query($sql_tot_cont);

    $row_tot_cont=mysql_fetch_object($rslt_tot_cont);
    $tot_cont=$row_tot_cont->tot_cont;
    ?>


        <?php
        if($rslt_be_report_datewise[$i]['place_dec'] == null){
            $j++;
        ?>
<!--            bgcolor="red"-->
        <tr  bgcolor="#F5B7B1">
            <td align="center"><?php echo $i+1;?></td>
            <td align="center"><?php echo $rslt_be_report_datewise[$i]['office_code']?></td>
            <td align="center"><?php echo $rslt_be_report_datewise[$i]['reg_no']?></td>
            <td align="center"><?php echo $rslt_be_report_datewise[$i]['reg_date']?></td>
            <td align="center"><?php echo $rslt_be_report_datewise[$i]['place_dec']?></td>
            <td align="center"><?php echo $rslt_be_report_datewise[$i]['entry_dt']?></td>

            <td class="gridLight" align="center"><?php echo $tot_cont; ?></td>
        </tr>
            <?php
            $not_exit_note_number = $not_exit_note_number + $i;
        }else{
            $k++;
            ?>

            <tr>
                <td align="center"><?php echo $i+1;?></td>
                <td align="center"><?php echo $rslt_be_report_datewise[$i]['office_code']?></td>
                <td align="center"><?php echo $rslt_be_report_datewise[$i]['reg_no']?></td>
                <td align="center"><?php echo $rslt_be_report_datewise[$i]['reg_date']?></td>
                <td align="center"><?php echo $rslt_be_report_datewise[$i]['place_dec']?></td>
                <td align="center"><?php echo $rslt_be_report_datewise[$i]['entry_dt']?></td>

                <td class="gridLight" align="center"><?php echo $tot_cont; ?></td>
            </tr>
            <?php
            $exit_note_number = $exit_note_number + $i;
        }
            ?>
            ?>
<!--        --><?php
//    }
//    ?>
    <?php
    // $tot_entry=$tot_entry+$rslt_be_report_datewise[$i]['tot_entry'];
   // $tot_entry=$tot_entry+$i;

    }
    ?>
    <tr rowspan="7">
 <td align="center" colspan="7">&nbsp;</td>

    </tr>
    <tr>
        <td align="center" colspan="2"><b>Bill of Entry</b></td>
        <td align="center"><b><?php echo $i; ?></b></td>
        <td align="center" colspan="1"><b>Exit Note Number</b></td>
        <td align="center"><b><?php echo $k; ?></b></td>
        <td align="center" colspan="1" bgcolor="#F5B7B1"><b>Not Exit Note Number</b></td>
        <td align="center" bgcolor="#F5B7B1"><b><?php echo $j; ?></b></td>
    </tr>
</table>

</body>
</html>
