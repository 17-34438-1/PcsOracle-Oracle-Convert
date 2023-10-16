
<section class="panel">
    <div class="panel-body">
        <div class="invoice">


            <header class="clearfix">
                <!-- <div class="row">
                    <div class="col-sm-12 text-center mt-md mb-md">
                        <div class="ib" align="center">
                            <img src="<?php echo ASSETS_WEB_PATH;?>fimg/cpaLogo.png" alt="Chattogram Port Authority Logo"/><br>
                            <h3 class="h3 mt-none mb-sm text-dark text-bold" style="text-align:center;">CHATTOGRAM PORT AUTHORITY</h3>
                            <h4 class="h4 mt-none mb-sm text-dark text-bold" style="text-align:center;">ONE STOP SERVICE CENTER</h4>
                        </div>
                    </div>
                </div> -->

   

                <table align="center" width="75%" class="table table-bordered table-responsive table-hover table-striped mb-none">
                    <tr class="gradeX">
                        <!--td align="center" width="20%">
                            <img src="<?php echo $path;?>" height="70" width="70">
                        </td-->
                        <td align="center" width="60%">
                            <img src="<?php echo ASSETS_WEB_PATH;?>fimg/cpaLogo.png" alt="Chattogram Port Authority Logo"/><br>
                            <h3 class="h3 mt-none mb-sm text-dark text-bold" style="text-align:center;">CHATTOGRAM PORT AUTHORITY</h3>
                            <h4 class="h4 mt-none mb-sm text-dark text-bold" style="text-align:center;">ONE STOP SERVICE CENTER</h4>
                        </td>
                  
                    </tr>
                </table>
        
            </header>
			<div class="table-responsive">
                <table align="center" width="75%" class="table table-bordered table-responsive table-hover table-striped mb-none">
                         <tr class="gradeX">
                            <td>ROTATION: <?php echo $imp_rot;?></td>
                            <td>BL : <?php echo $bl_no;?></td>
                            <!--td>CPA VAT REG NO : <?php echo $cpa_vat_reg_no;?></td-->
                            <td>EX.RATE($) : <?php echo $getExRateValue;?></td>
                         </tr>
                </table>

            </div>

            <hr width="75%">
              <!--div class="table-responsive">
                <table align="center" width="90%" class="table table-bordered table-responsive table-hover table-striped mb-none">
                          <?php       
                    for($i=0;$i<count($rtnContainerList);$i++) { 
                     ?>
                <tr style="border-bottom:1px solid black">
                    <td>BILL NO : <?php echo $rtnContainerList[$i]['bill_no'];?></td>
                    <td>DATE : <?php echo date("y-m-d");?></td>
                    <td>ARRAIVAL DATE : <?php echo $rtnContainerList[$i]['arraival_date'];?></td>
                </tr>
                <tr style="border-bottom:1px solid black">
                    <td>ROT NO : <?php echo $rtnContainerList[$i]['import_rotation'];?></td>
                    <td>VSL NAME : <?php echo $rtnContainerList[$i]['vessel_name'];?></td>
                    <td>UNSTUFFING DATE : <?php echo $rtnContainerList[$i]['wr_date'];?></td>
                </tr>   
                <tr style="border-bottom:1px solid black">
                    <td>LINE/BL NO : <?php echo $rtnContainerList[$i]['bl_no'];?></td>
                    <td>W/R DATE : <?php echo $rtnContainerList[$i]['wr_date'];?></td>
                    <td>W/R BILL UPTO : <?php echo $rtnContainerList[$i]['wr_upto_date'];?></td>
                </tr>   
                <tr style="border-bottom:1px solid black">
                    <td>VAT REG NO : <?php echo $rtnContainerList[$i]['cpa_vat_reg_no'];?></td>
                    <td>IMPORTER : <?php echo $rtnContainerList[$i]['importer_name'];?></td>
                </tr>   
                <tr style="border-bottom:1px solid black">
                    <td>C&F LC NO : <?php echo $rtnContainerList[$i]['cnf_lic_no'];?></td>
                    <td>C&F AGENT : <?php echo $rtnContainerList[$i]['cnf_agent'];?></td>
                </tr>   
                <tr style="border-bottom:1px solid black">
                    <td>BE NO : <?php echo $rtnContainerList[$i]['be_no'];?></td>
                    <td>BE DATE : <?php echo $rtnContainerList[$i]['be_date'];?></td>
                </tr>   
                <tr style="border-bottom:1px solid black">
                    <td>ADO NO : <?php echo $rtnContainerList[$i]['ado_no'];?></td>
                    <td>ADO DATE : <?php echo $rtnContainerList[$i]['ado_date'];?></td>
                    <td>ADO VALID UPTO : <?php echo $rtnContainerList[$i]['ado_valid_upto'];?></td>
                </tr>   
                <tr style="border-bottom:1px solid black">
                    <td>MANIFEST QTY : <?php echo $rtnContainerList[$i]['manifest_qty']."*".$rtnContainerList[$i]['cont_size']."*".$rtnContainerList[$i]['cont_height'];?></td>
                </tr>
                    <?Php }?>
                </table>
            </div-->
        <div align="center"><u>QNTY FOR WHICH CHARGE MADE</u></div>
           <div class="table-responsive">
                <table align="center" width="75%" class="table table-bordered table-responsive table-hover table-striped mb-none" style="border-top:1px solid #000;border-bottom:1px solid #000;">
                        <thead>
                            <tr class="gridDark">
                                <th align="center">CODE</th>
                                <th align="center">DESCRIPTION</th>
                                <th align="center">RATE(T/$)</th>
                                <th align="center">QNTY</th>
                                <th align="center">DAYS</th>
                                <th align="center">PORT(TK)</th>
                                <th align="center">VAT(TK)</th>
                                <th align="center">MLWF(TK)</th>
                                               
                             </tr>
                        </thead>
                        <tbody>
                            <?php       
                    $totmlwf = 0;
                    $totVat = 0;
                    $totAmt = 0;
                    $totVat = 0;
                    $tot_qday = 0;
                    for($i=0;$i<count($chargeList);$i++) 
                    {                        
					?>
                     <tr> 
                     
                      <td align="center">
                       <?php echo $chargeList[$i]['gl_code']?>
                      </td>
                      <td align="center">
                       <?php echo $chargeList[$i]['description']?>
                      </td>
                      <td align="center">
                       <?php echo $chargeList[$i]['tarrif_rate']?>
                      </td>
                      <td align="center">
                       <?php echo $chargeList[$i]['Qty']?>
                      </td>
                      <td align="center">
                       <?php if($chargeList[$i]['gl_code']!=206031 && $chargeList[$i]['gl_code']!=206033 && $chargeList[$i]['gl_code']!=206035 && $chargeList[$i]['gl_code']!=206037 && $chargeList[$i]['gl_code']!=206039 && $chargeList[$i]['gl_code']!=206041 && $chargeList[$i]['qday']=1) {?>
                        <?php echo "-";} else {?>
                        <?php echo $chargeList[$i]['qday'];}  $tot_qday = $tot_qday+$chargeList[$i]['qday'];?>
                      </td>
                      <td align="center">
                       <?php echo $chargeList[$i]['amt']; $totAmt=$totAmt+$chargeList[$i]['amt']?>
                      </td>
                      <td align="center">
                       <?php echo $chargeList[$i]['vatTK']; $totVat=$totVat+$chargeList[$i]['vatTK']; ?>
                      </td>
                      <td align="center">
                       <?php 
							if($chargeList[$i]['gl_code']=="501005" || $chargeList[$i]['gl_code']=="502000N")
							{
								$mlwfTK=$chargeList[$i]['amt']*0.04;
							}
							else
							{
								$mlwfTK=0;
							}						   
							echo $mlwfTK; 
							$totmlwf = $totmlwf+$mlwfTK;
							?>
                      </td>
                    </tr>
                     <?php
                    }
                   ?>
					<tr>
						<td colspan="8"><hr/></td>
					</tr>
				   	<tr>
							<td colspan="3">
								SHOW RATE IN US$ TOTAL DAYS : <?php echo $tot_qday;?>
							</td>
							<td align="right" colspan="2">
								TOTAL(TK) : <?php// echo $totAmt;?>
							</td>
					
							<!--td colspan="4">NET AMOUNT : <?php //$ait=($totAmt * 0.1); $other= $totAmt - $ait; echo "( ".$other." + AIT 10% ".$ait." )"?></td-->
							<td align="center"><b><?php echo $totAmt; ?></b></td>
							<td align="center"><b><?php echo $totVat; ?></b></td>
							<td align="center"><b><?php echo $totmlwf; ?></b></td>
	
					</tr>				   
                  </tbody>
                </table>
            </div>
           


            <div class="row">   
                    <div class="col-sm-12 text-center mt-md mb-md">
                        <div class="ib">
                <div class="table-responsive">
                <table align="center" width="75%" class="table table-bordered table-responsive table-hover table-striped mb-none">
					<!--tr>
						<td>
							SHOW RATE IN US$ TOTAL DAYS : <?php echo $tot_qday;?>
						</td>
						<td align="center">
							TOTAL(TK) : <?php// echo $totAmt;?>
						</td>
				
						<td colspan="4">NET AMOUNT : <?php //$ait=($totAmt * 0.1); $other= $totAmt - $ait; echo "( ".$other." + AIT 10% ".$ait." )"?></td>
						<td align="center"><b><?php echo $totAmt; ?></b></td>
						<td align="center"><b><?php echo $totVat; ?></b></td>
						<td align="center"><b><?php echo $totmlwf; ?></b></td>
						<td align="center"></td>
						<td align="center"></td>
					</tr-->
					<tr>
						<td colspan="7">
							<b>NET PAYABLE (TK) : <?php echo $totAmt + $totmlwf+ $totVat;?></b>
						</td>
					</tr>
					<tr>
						<td colspan="7">
							REMARKS :  <?php echo numtowords($totAmt+$totmlwf+$totVat); ?>
							
						</td>
					</tr>
					<!--tr>
						<td colspan="5">
							<b>SHOULD BE PAID ON BEFORE <?php //for($i=0;$i<count($rtnContainerList);$i++) {  echo $rtnContainerList[$i]['wr_upto_date'];}?></b> 
						</td>
					</tr-->
                </table>
            </div>
        </div>
    </div>
  </div>

     

                 <!--div class="row">
                    <div class="col-sm-12 text-center mt-md mb-md">
                        <div class="ib">
                        <table style="margin-left:7%;">
                            <tr>
                                <td>Powered By<td>
                                <td><img style="width:80px;" src="<?php echo IMG_PATH?>datasoft_logo.gif"><td>
                            </tr>
                        </table>
                        </div>
                    </div>
                </div-->
        </div>
        <div class="text-right mr-lg">
       <!-- <a href="<?php echo site_url('ShedBillController/getShedBillFCLPdf/'.'print'.'/'.$verify_number)?>" target="_blank" class="btn btn-primary ml-sm"><i class="fa fa-print"></i> Print</a> -->
        </div>
    </div>
</section>
<!-- end: page -->
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
