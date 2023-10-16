
<section class="panel">
    <div class="panel-body">
        <div class="invoice">

            <?php
                require_once 'phpqrcode/qrlib.php';
                $destination_folder = $_SERVER['DOCUMENT_ROOT']."/assets/images/qrcode/";		
                $file = $verify_number.".png";
                $file1 = $destination_folder.$file;
                //$path = IMG_PATH."qrcode/".$file;
                $text = $verify_number;
                QRcode::png($text, $file1, 'L', 10, 2);
            ?>

            

            <div class="table-responsive">
                <table align="center" width="90%" class="table table-bordered table-responsive table-hover table-striped mb-none">
                    <tr class="gradeX">
                        <td align="center" width="20%">
                            <img src="<?php echo $file1;?>" height="70" width="70">
                        </td>
                        <td align="center" width="60%">
                            <img src="<?php echo ASSETS_WEB_PATH;?>fimg/cpaLogo.png" alt="Chattogram Port Authority Logo"/><br>
                            <h2 class="h3 mt-none mb-sm text-dark text-bold" style="text-align:center;">CHITTAGONG PORT AUTHORITY</h2>
                            <h3 class="h4 mt-none mb-sm text-dark text-bold" style="text-align:center;">ONE STOP SERVICE CENTER</h3>
                        </td>
                        <td align="center" width="20%" class="fontSize">
                            <?php				
                                $text =$verify_number;						
                                $barcodeText = $text;
                            ?>
                            <barcode code="<?php echo $barcodeText; ?>" type="C128A" size="0.6" height="2" />
                            <br>
                            <?php echo sprintf("%010s", $text); ?>
                        </td>
                    </tr>
                </table>

                <table align="center" width="90%" class="table table-bordered table-responsive table-hover table-striped mb-none verify-table">
                    <tr class="gradeX">
                        <td class="fontSize">VERIFY NO : <?php echo $verify_number;?></td>
                        <td class="fontSize">UNIT NO : <?php echo $unit_no;?></td>
                        <td class="fontSize">CPA VAT REG NO : <?php echo $cpa_vat_reg_no;?></td>
                        <td class="fontSize">EX.RATE($) : <?php echo $ex_rate;?></td>
                    </tr>
                </table>

                <hr width="86%">

                <table align="center" width="90%" class="table table-bordered table-responsive table-hover table-striped mb-none">
                          <?php       
                    for($i=0;$i<count($rtnContainerList);$i++) { 
                     ?>
                <tr style="border-bottom:1px solid black">
                    <td class="fontSize">BILL NO : <?php echo $rtnContainerList[$i]['bill_no'];?></td>
                    <td class="fontSize">DATE : <?php echo date("y-m-d");?></td>
                    <td class="fontSize">ARRAIVAL DATE : <?php echo $rtnContainerList[$i]['arraival_date'];?></td>
                </tr>
                <tr style="border-bottom:1px solid black">
                    <td class="fontSize">ROT NO : <?php echo $rtnContainerList[$i]['import_rotation'];?></td>
                    <td class="fontSize">VSL NAME : <?php echo $rtnContainerList[$i]['vessel_name'];?></td>
                    <td class="fontSize">UNSTUFFING DATE : <?php echo $rtnContainerList[$i]['wr_date'];?></td>
                </tr>   
                <tr style="border-bottom:1px solid black">
                    <td class="fontSize">LINE/BL NO : <?php echo $rtnContainerList[$i]['bl_no'];?></td>
                    <td class="fontSize">W/R DATE : <?php echo $rtnContainerList[$i]['wr_date'];?></td>
                    <td class="fontSize">W/R BILL UPTO : <?php echo $rtnContainerList[$i]['wr_upto_date'];?></td>
                </tr>   
                <tr style="border-bottom:1px solid black">
                    <td class="fontSize">VAT REG NO : <?php echo $rtnContainerList[$i]['cpa_vat_reg_no'];?></td>
                    <td class="fontSize" colspan="2">IMPORTER : <?php echo strtoupper($rtnContainerList[$i]['importer_name']);?></td>
                </tr>   
                <tr style="border-bottom:1px solid black">
                    <td class="fontSize">C&F LC NO : <?php echo $rtnContainerList[$i]['cnf_lic_no'];?></td>
                    <td class="fontSize" colspan="2">C&F AGENT : <?php echo $rtnContainerList[$i]['cnf_agent'];?></td>
                </tr>   
                <tr style="border-bottom:1px solid black">
                    <td class="fontSize">BE NO : <?php echo $rtnContainerList[$i]['be_no'];?></td>
                    <td class="fontSize" colspan="2">BE DATE : <?php echo $rtnContainerList[$i]['be_date'];?></td>
                </tr>   
                <tr style="border-bottom:1px solid black">
                    <td class="fontSize">ADO NO : <?php echo $rtnContainerList[$i]['ado_no'];?></td>
                    <td class="fontSize">ADO DATE : <?php echo $rtnContainerList[$i]['ado_date'];?></td>
                    <td class="fontSize">ADO VALID UPTO : <?php echo $rtnContainerList[$i]['ado_valid_upto'];?></td>
                </tr>   
                <tr style="border-bottom:1px solid black">
                    <td class="fontSize" colspan="3">MANIFEST QTY : <?php echo $rtnContainerList[$i]['manifest_qty']."*".$rtnContainerList[$i]['cont_size']."*".$rtnContainerList[$i]['cont_height'];?></td>
                </tr>
                    <?Php }?>
                </table>
            </div>

            <div align="center" class="fontSize"><u>QNTY FOR WHICH CHARGE MADE</u></div>

            <div class="table-responsive">
                <table align="center" width="90%" class="table table-bordered table-responsive table-hover table-striped mb-none" style="border-top:1px solid #000;border-bottom:1px solid #000;">
                    <thead>
                        <tr class="gridDark">
                            <th align="left" width="12%">CODE</th>
                            <th align="center">DESCRIPTION</th>
                            <th align="right" width="10%">RATE(T/$)</th>
                            <th align="right" width="10%">QNTY</th>
                            <th align="right" width="10%">DAYS</th>
                            <th align="right">PORT(TK)</th>
                            <th align="right">VAT(TK)</th>
                            <th align="right">MLWF(TK)</th>
                                            
                            </tr>
                    </thead>
                    <tbody>
                                <?php       
                        $totmlwf = 0;
                        $totVat = 0;
                        for($i=0;$i<count($chargeList);$i++) 
                        { 
                            $totmlwf = $totmlwf+$chargeList[$i]['mlwfTK'];
                    ?>
                        <tr class="gradeX"> 
                        
                        <td class="fontSize"><?php echo $chargeList[$i]['gl_code']?></td>
                        <td class="fontSize"><?php echo $chargeList[$i]['description']?></td>
                        <td class="fontSize" align="right"><?php echo $chargeList[$i]['tarrif_rate']?></td>
                        <td class="fontSize" align="right"><?php echo $chargeList[$i]['Qty']?></td>
                        <td class="fontSize" align="right"><?php if($chargeList[$i]['gl_code']!=206031 && $chargeList[$i]['gl_code']!=206033 && $chargeList[$i]['gl_code']!=206035 && $chargeList[$i]['gl_code']!=206037 && $chargeList[$i]['gl_code']!=206039 && $chargeList[$i]['gl_code']!=206041 && $chargeList[$i]['qday']=1) {?>
                            <?php echo "";} else {?>
                            <?php echo $chargeList[$i]['qday'];}?>
                        </td>
                        <td class="fontSize" align="right"><?php echo $chargeList[$i]['amt']?></td>
                        <td class="fontSize" align="right"><?php echo $chargeList[$i]['vatTK']; $totVat+=$chargeList[$i]['vatTK']; ?></td>
                        <td class="fontSize" align="right"><?php echo $chargeList[$i]['mlwfTK']; //$totMlwf+=$chargeList[$i]['vatTK'];?></td>
                        </tr>
                        <?php
                        }
                    ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</section>
<!-- end: page -->
</div>

<div class="row fixed-table">   
    <div class="col-sm-12 text-center mt-md mb-md">
        <div class="ib">
            <div class="table-responsive">
                <table align="center" width="90%" class="table table-bordered table-responsive table-hover table-striped mb-none">
                    <tr>
                        <td class="fontSize" colspan="3">SHOW RATE IN US$ TOTAL DAYS : <?php echo $tot_qday;?></b></td>
                        <td class="fontSize" colspan="2">TOTAL(TK) : <?php echo $tot_sum;?></b></td>
                    </tr>
                    <tr >
                        <td class="fontSize" colspan="4">NET AMOUNT : <?php $ait=($tot_sum * 0.1); $other= $tot_sum - $ait; echo "( ".$other." + AIT 10% ".$ait." )"?></td>
                        <td class="fontSize">PORT : <?php echo $tot_sum; ?></b></td>
                        <td class="fontSize">VAT : <?php echo $totVat; ?></b></td>
                        <td class="fontSize">MLWF: <?php echo $totmlwf; ?></b></td>
                    </tr>
                    <tr>
                        <td class="fontSize" align="center" colspan="5"><b>NET PAYABLE (TK) : <?php echo $tot_sum + $totmlwf+ $totVat;?></b></td>
                    </tr>
                    <tr>
                        <td class="fontSize" colspan="7">REMARKS :  <?php echo strtoupper(numtowords($tot_sum+$totmlwf+$totVat)); ?></td>
                    </tr>
                    <tr>
                        <td class="fontSize" colspan="7"><b>SHOULD BE PAID ON BEFORE <?php for($i=0;$i<count($rtnContainerList);$i++) {  echo $rtnContainerList[$i]['wr_upto_date'];}?></b></td>
                    </tr>
                </table>
            </div>
        </div>
    </div>

    <?php

        if($rcvstat==1)
        {
            
        ?>
        <div class="row"> 
            
            <table style="margin-left:8%;border: 2px solid black">
                <tr class="gradeX">
                    <td class="fontSize"><nobr>BANK NAME</nobr></td>
                    <td class="fontSize">:</td>
                    <td class="fontSize"><?php echo $cpbankname;?></td>
                </tr>
                <tr class="gradeX">
                    <td class="fontSize">CP NO</td>
                    <td class="fontSize">:</td>
                    <td class="fontSize"><?php echo $cpnoview;?></td>
                </tr>
                <tr class="gradeX">
                    <td class="fontSize">DATE</td>
                    <td class="fontSize">:</td>
                    <td class="fontSize"><?php echo $recv_time;?></td>
                </tr>
                <tr class="gradeX">
                    <td class="fontSize">RECEIVE BY</td>
                    <td class="fontSize">:</td>
                    <td class="fontSize">S : </td>
                </tr>
                <tr class="gradeX">
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td class="fontSize">N : <?php echo $recv_by;?></td>
                </tr>
                <tr class="gradeX">
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td class="fontSize">D : </td>
                </tr >
            </table>
        </div>
    <?php } ?>
</div>


<?php 
// function numtowords($num){ 
// $decones = array( 
            // '01' => "One", 
            // '02' => "Two", 
            // '03' => "Three", 
            // '04' => "Four", 
            // '05' => "Five", 
            // '06' => "Six", 
            // '07' => "Seven", 
            // '08' => "Eight", 
            // '09' => "Nine", 
            // 10 => "Ten", 
            // 11 => "Eleven", 
            // 12 => "Twelve", 
            // 13 => "Thirteen", 
            // 14 => "Fourteen", 
            // 15 => "Fifteen", 
            // 16 => "Sixteen", 
            // 17 => "Seventeen", 
            // 18 => "Eighteen", 
            // 19 => "Nineteen" 
            // );
// $ones = array( 
            // 0 => " ",
            // 1 => "One",     
            // 2 => "Two", 
            // 3 => "Three", 
            // 4 => "Four", 
            // 5 => "Five", 
            // 6 => "Six", 
            // 7 => "Seven", 
            // 8 => "Eight", 
            // 9 => "Nine", 
            // 10 => "Ten", 
            // 11 => "Eleven", 
            // 12 => "Twelve", 
            // 13 => "Thirteen", 
            // 14 => "Fourteen", 
            // 15 => "Fifteen", 
            // 16 => "Sixteen", 
            // 17 => "Seventeen", 
            // 18 => "Eighteen", 
            // 19 => "Nineteen" 
            // ); 
// $tens = array( 
            // 0 => "",
            // 2 => "Twenty", 
            // 3 => "Thirty", 
            // 4 => "Forty", 
            // 5 => "Fifty", 
            // 6 => "Sixty", 
            // 7 => "Seventy", 
            // 8 => "Eighty", 
            // 9 => "Ninety" 
            // ); 
// $hundreds = array( 
            // "Hundred", 
            // "Thousand", 
            // "Million", 
            // "Billion", 
            // "Trillion", 
            // "Quadrillion" 
            // ); //limit t quadrillion 
// $num = number_format($num,2,".",","); 
// $num_arr = explode(".",$num); 
// $wholenum = $num_arr[0]; 
// $decnum = $num_arr[1]; 
// $whole_arr = array_reverse(explode(",",$wholenum)); 
// krsort($whole_arr); 
// $rettxt = ""; 
// foreach($whole_arr as $key => $i){ 
    // if($i < 20){ 
        // $rettxt .= $ones[$i]; 
    // }
    // elseif($i < 100){ 
        // $rettxt .= $tens[substr($i,0,1)]; 
        // $rettxt .= " ".$ones[substr($i,1,1)]; 
    // }
    // else{ 
        // $rettxt .= $ones[substr($i,0,1)]." ".$hundreds[0]; 
        // $rettxt .= " ".$tens[substr($i,1,1)]; 
        // $rettxt .= " ".$ones[substr($i,2,1)]; 
    // } 
    // if($key > 0){ 
        // $rettxt .= " ".$hundreds[$key]." "; 
    // } 

// } 
// $rettxt = $rettxt." Taka";

// if($decnum > 0){ 
    // $rettxt .= " and "; 
    // if($decnum < 20){ 
        // $rettxt .= $decones[$decnum]; 
    // }
    // elseif($decnum < 100){ 
        // $rettxt .= $tens[substr($decnum,0,1)]; 
        // $rettxt .= " ".$ones[substr($decnum,1,1)]; 
    // }
    // $rettxt = $rettxt." Paisa"; 
// } 
// return $rettxt;}
?>


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
