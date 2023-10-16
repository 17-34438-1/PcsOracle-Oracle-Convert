<body>
    <table border="0" align="center" width="70%">
        <tr height="100px">
            <td align="center" valign="middle">
                <img src="<?php echo ASSETS_WEB_PATH;?>fimg/cpaLogo.png">
                <h1 style="font-size:25px;">Chittagong Port Authority</h1>
                <h3 style="font-size:17px;">Truck Gate In Out Report</h3>
                <h4 style="font-size:14px;">On Date: <?php echo $date; ?></h4>
            </td>
        </tr>
    </table>
    <table border="1" align="center" width="80%" cellpadding="5">
        <tr>
            <th>SL</th>
            <th>Gate No</th>
			<th>Total</th>
            <th>In</th>
            <th>Out</th>
            <th>Inside Port</th>
			<th>Gate in Pending</th>
        </tr>

        <?php
            include("mydbPConnection.php");
            $gateQuery = "SELECT DISTINCT(gate_no) AS gtNo FROM do_truck_details_entry";
            $gateResult = mysqli_query($con_cchaportdb,$gateQuery);
            
            $gate = "";
            $i=0;
            $totalGateIn = 0;
            $totalGateOut = 0;
			
			$truckGrandTotal = 0;
			$grandTotalPending = 0;
            while($row = mysqli_fetch_object($gateResult)){
                $gate = $row->gtNo;
                $i++;

                $dataQuery="SELECT 
				(SELECT COUNT(*) 
				FROM do_truck_details_entry WHERE gate_no='$gate' AND date(last_update)='$date') AS totalTruck,
                (SELECT COUNT(*) 
                FROM do_truck_details_entry WHERE gate_in_status= '1' AND gate_no='$gate' AND DATE(gate_in_time) = '$date') AS gateIn,
                (SELECT COUNT(*) 
                FROM do_truck_details_entry WHERE gate_in_status= '1' AND gate_out_status='1' AND gate_no='$gate' AND DATE(gate_out_time) = '$date') AS gateOut,
				(SELECT COUNT(*) 
				FROM do_truck_details_entry WHERE gate_in_status= '0' AND gate_no='$gate' AND DATE(last_update) = '$date') AS gateInPending";

                $dataResult = mysqli_query($con_cchaportdb,$dataQuery);
                
                $gateIn = "";
                $gateOut ="";
                $inside = "";
                while($dataRow = mysqli_fetch_object($dataResult)){
					$totalTruck = $dataRow->totalTruck;
                    $gateIn = $dataRow->gateIn;
                    $gateOut = $dataRow->gateOut;
					$gateInPending = $dataRow->gateInPending;
					
                    $inside = $gateIn - $gateOut;
                    $totalGateIn+=$gateIn;
                    $totalGateOut+=$gateOut;
					
					$truckGrandTotal+=$totalTruck;
					$grandTotalPending+=$gateInPending;
                }

        ?>

        <tr>
            <td align="center"><?php echo $i; ?></td>
            <td align="center"><?php echo $gate; ?></td>
            <td align="center"><?php echo $totalTruck; ?></td>
            <td align="center"><?php echo $gateIn; ?></td>
            <td align="center"><?php echo $gateOut; ?></td>
            <td align="center"><?php echo $inside; ?></td>
            <td align="center"><?php echo $gateInPending; ?></td>
        </tr>

        <?php
            }
        ?>

        <tr>
            <th class="text-center" colspan="2">Total</th>
            <td align="center"><?php echo $truckGrandTotal;?></td>
            <td align="center"><?php echo $totalGateIn;?></td>
            <td align="center"><?php echo $totalGateOut;?></td>
            <td align="center"><?php echo $totalGateIn - $totalGateOut ;?></td>
            <td align="center"><?php echo $grandTotalPending ;?></td>
        </tr>
    </table>
</body>