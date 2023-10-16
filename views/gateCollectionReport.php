<body>
    <section class="panel">
        <div class="panel-body">
        <table cellspacing="1" cellpadding="1" align="center">
            <tr>
                <th colspan="13" align="center"><font size="5"><img src="<?php echo IMG_PATH?>cpanew.jpg" /></th>
            </tr>
            <!-- <tr>
                <th colspan="13" align="center"><font size="6">CHITTAGONG PORT AUTHORITY</font></th>
            </tr> -->
            <tr>
                <td colspan="13" align="center"><font size="6">Gate Collection Report <?php if($collectBy!=null){echo " for ".$collectBy;} if($slot!=null){echo " ".$slot." ";} ?><br>Date:  <?php echo $fromDate." to ".$toDate; ?></font></td>
            </tr>
        </table>
            <table border="1" cellspacing="5" cellpadding="5" align="center">
                <thead>
                    <tr>
                        <th class="text-center">SL</th>
                        <th class="text-center">Visit Id</th>
                        <th class="text-center">Truck Id</th>
                        <th class="text-center">Rotation</th>
                        <th class="text-center">Container</th>
                        <th class="text-center">C&F</th>
                        <th class="text-center">Method</th>
                        <th class="text-center">Payment Collection Time</th>
                        <th class="text-center">Collected By</th>
                        <th class="text-center">Amount</th>
                    </tr>
                </thead>

                <?php
                    $visitId = "";
                    $truckId = "";
                    $method = "";
                    $amount = "";
                    $collectionTime = "";
                    $collectedBy = "";
                    $totalCollection = "";
                    $cont = "";
                    $cnf = "";
                    $rotation;

                    for($i=0;$i<count($rslt);$i++){
                        $visitId = $rslt[$i]['id'];
                        $truckId = $rslt[$i]['truck_id'];
                        $method = $rslt[$i]['paid_method'];
                        $amount = $rslt[$i]['paid_amt'];
                        $collectionTime = $rslt[$i]['paid_collect_dt']; 
                        $collectedBy = $rslt[$i]['paid_collect_by'];
                        $cont = $rslt[$i]['cont_no'];
                        $cnf = $rslt[$i]['update_by'];
                        $rotation = $rslt[$i]['import_rotation'];
                        $totalCollection+=$amount;

                        $cnfNameQuery = "SELECT u_name FROM users WHERE login_id='$cnf'";
                        $rsltCnfName=$this->bm->dataSelectDb1($cnfNameQuery);
                        $cnfName = "";
                        if(count($rsltCnfName)>0){
                            $cnfName = $rsltCnfName[0]['u_name'];    
                        }
                ?>
                <tr>
                    <td class="text-center"><?php echo $i+1; ?></td>
                    <td class="text-center"><?php echo $visitId; ?></td>
                    <td class="text-center"><p style="font-family: ind_bn_1_001"><?php echo $truckId; ?></p></td>
                    <td class="text-center"><?php echo $rotation; ?></td>
                    <td class="text-center"><?php echo $cont; ?></td>
                    <td class="text-center"><?php echo $cnfName; ?></td>
                    <td class="text-center"><?php echo $method; ?></td>
                    <td class="text-center"><?php echo $collectionTime; ?></td>
                    <td class="text-center"><?php echo $collectedBy; ?></td>
                    <td class="text-center"><?php echo $amount; ?></td>
                </tr>
                <?php
                        }
                ?>
                <tr>
                    <td align="right" colspan="9"><b>Total Amount :</b></td>
                    <td class="text-center"><b><?php echo $totalCollection; ?></b></td>
                </tr>
            </table>
 
            <p style="padding-left:20px;"><b>Total Amount (in Words) </b>: <?php echo numtowords($totalCollection); ?></p>

        </div>
    </section>
</body>

<?php 
function numtowords($number){ 
//print($number."<br>");
    $no = round($number);
    $decimal = round($number - ($no = floor($number)), 2) * 100;    
    $digits_length = strlen($no);    
    $i = 0;
    $str = array();
    $words = array(
        0 => '',
        1 => 'One',
        2 => 'Two',
        3 => 'Three',
        4 => 'Four',
        5 => 'Five',
        6 => 'Six',
        7 => 'Seven',
        8 => 'Eight',
        9 => 'Nine',
        10 => 'Ten',
        11 => 'Eleven',
        12 => 'Twelve',
        13 => 'Thirteen',
        14 => 'Fourteen',
        15 => 'Fifteen',
        16 => 'Sixteen',
        17 => 'Seventeen',
        18 => 'Eighteen',
        19 => 'Nineteen',
        20 => 'Twenty',
        30 => 'Thirty',
        40 => 'Forty',
        50 => 'Fifty',
        60 => 'Sixty',
        70 => 'Seventy',
        80 => 'Eighty',
        90 => 'Ninety');
    $digits = array('', 'Hundred', 'Thousand', 'Lakh', 'Crore');
    while ($i < $digits_length) {
        $divider = ($i == 2) ? 10 : 100;
        $number = floor($no % $divider);
        $no = floor($no / $divider);
        $i += $divider == 10 ? 1 : 2;
        if ($number) {
            $plural = (($counter = count($str)) && $number > 9) ? '' : null;            
            $str [] = ($number < 21) ? $words[$number] . ' ' . $digits[$counter] . $plural : $words[floor($number / 10) * 10] . ' ' . $words[$number % 10] . ' ' . $digits[$counter] . $plural;
        } else {
            $str [] = null;
        }  
    }
    
    $Rupees = implode(' ', array_reverse($str));
	
	// 2021-03-09
	$paise = "";
	if($decimal > 9 and $decimal < 20)
	{
		$paise = $words[(string)$decimal]." Paisa";
	}
	else
	{
		$paise = ($decimal) ? "And " . ($words[(string)$decimal - (string)$decimal%10]) ." " .($words[(string)$decimal%10])." Paisa"  : '';
	}
	
    // $paise = ($decimal) ? "And " . ($words[(string)$decimal - (string)$decimal%10]) ." " .($words[(string)$decimal%10])." Paisa"  : '';
	
	// 2021-03-09
    return ($Rupees ?  $Rupees." Taka " : '') . $paise;
}
 ?>