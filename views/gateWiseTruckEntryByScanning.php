<table align="center" width="850px">
	<tr>
		<td align="center"><img align="middle" width="235px" height="75px" src="<?php echo IMG_PATH?>cpanew.jpg"></td>
	</tr>
</table>

<table border="1" style="border-collapse:collapse;" align="center" width="60%">
    <tr>
        <th valign="center" align="center" style="font-size:20px;"><b>Gate Wise Truck Entry By Scanning Security Devices from date: <?php echo $fromDate." to ".$toDate; ?></b></th>
    </tr>							
</table>
<?php
    if($action == "html"){
        echo "<br/>";
    }
?>
<!-- <br/> -->

<table border="1" style="border-collapse:collapse;text-align:center;" align="center" width="60%">

    <thead>
        <tr>
            <th style="font-size:18px;">Gate</th>
            <th style="font-size:18px;">Truck Quantity</th>
        </tr>
    </thead>

	<?php 
        $totalEntry = 0;
        $total = 0;

        $totalQuery = "SELECT COUNT(id) AS total FROM do_truck_details_entry WHERE DATE(last_update) BETWEEN '$fromDate' AND '$toDate'";
        $totalResult = $this->bm->dataSelectDB1($totalQuery);
        if(count($totalResult)>0){
            $totalEntry = $totalResult[0]['total'];
        }

        for($i=0;$i<count($gates);$i++)
        {
            $gate = $gates[$i]['gate'];
            $query = "SELECT section,COUNT(do_truck_details_entry.id) AS entry
            FROM do_truck_details_entry
            INNER JOIN users ON users.login_id = do_truck_details_entry.paid_collect_by
            WHERE entry_from = 'secur' AND DATE(last_update) BETWEEN '$fromDate' AND '$toDate' AND section = '$gate' GROUP BY 1";
            $result = $this->bm->dataSelectDB1($query);
            $entry = 0;
            if(count($result)>0){
                $entry = $result[0]['entry'];
                $total+=$entry;
            }
    ?>
	<tr align="center">
		<td style="font-size:16px;"><?php echo $gate;?></td>
		<td style="font-size:16px;"><?php echo $entry;?></td>												
		
	</tr>
	<?php 
        } 
    ?>

    <tr>
        <th style="font-size:18px;">Total (Scan)</th>
        <th style="font-size:18px;"><?php echo $total;?></th>
    </tr>

    <tr>
        <th style="font-size:18px;">Total Entry</th>
        <th style="font-size:18px;"><?php echo $totalEntry;?></th>
    </tr>

    <tr>
        <th style="font-size:18px;">Scan percentage</th>
        <th style="font-size:18px;">
            <?php
                $percentage = ($total*100)/$totalEntry;
                echo number_format($percentage, 2)."%";
            ?>
        </th>
    </tr>
</table>