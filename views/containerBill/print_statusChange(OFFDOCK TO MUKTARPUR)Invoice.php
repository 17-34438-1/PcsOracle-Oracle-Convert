<!--<style>
	table, th, td {
		color: black;
	}
	@media print 
	{
	  @page { margin: 0; }
	  body  { margin: 1.6cm; }
	}
</style>-->
<html>	
<head>
		<!--<title>Container Loading Bill (PCT)</title>-->
		<!-- Web Fonts  -->
		<link href="//fonts.googleapis.com/css?family=Open+Sans:300,400,600,700,800" rel="stylesheet" type="text/css">

		<!-- Invoice Print Style -->
		<link rel="stylesheet" href="<?php echo ASSETS_PATH;?>stylesheets/invoice-print.css" />
		<table align="center" width="95%">
			<tr>
				<td align="center"><font size="4"><b>CHITTAGONG PORT AUTHORITY</b></font></td>
			</tr>
			<tr>
				<td align="center"><b>VAT Reg:2041001546</b></td>
			</tr>
			<tr>
				<td align="center"><b><?php echo $invoiceDesc;?></b></td>
			</tr>
			<?php
			//if($version!="")
			//{
			?>
			<!--tr>
				<td align="center">Version:&nbsp;<?php echo $version; ?></td>
			</tr-->
			<?php
			//}
			?>			
		</table>	
		<!--div align="center">(LCL BILL)</div-->

</head>
<body>	
		<div align ="center">
			<table align="center" width="100%" style="font-size:13px;">

				<tr>
					<td align="left" style="width:15%">Bill No</td>
					<td align="left" style="width:15%">: &nbsp;&nbsp;<?php echo $draftNumber;?></td>
					<td align="left" style="width:10%"></td>
					<td align="left">Bill Creator</td>
					<td align="left">: &nbsp;&nbsp;<?php echo $created_user;?></td>
				</tr>
				<tr>
					<td align="left">Nature Bill</td>
					<td align="left"><nobr>:&nbsp;LOADING BILL (PCT)<!--?php echo $bill_rslt[0]['vesselName'];?--></nobr></td>
					<td align="left"></td>
					<td align="left" style="width:10%">Bill Date</td>
					<td align="left" style="width:25%">: &nbsp;&nbsp;<?php echo $billingDate;?></td>
				</tr>
				<tr>
					<td align="left">MLO</td>
					<td align="left">: &nbsp;&nbsp;<?php echo $payCustomerId;?></td>
					<td align="left"></td>
					<td align="left">Rotation No</td>
					<td align="left">: &nbsp;&nbsp;<?php echo $ibVisitId;?></td>
				</tr>
				<tr>
					<td align="left">MLO Name</td>
					<td align="left">: &nbsp;&nbsp;<?php echo $payCustomername;?></td>
					<td align="left"></td>
					<td align="left">Vessel</td>
					<td align="left">: &nbsp;&nbsp;<?php echo $vsl_name;?></td>
				</tr>
				<tr>
					<td align="left">S.Agent</td>
					<td align="left">: &nbsp;&nbsp;<?php echo $customerId;?></td>
					<td align="left"></td>
				    <td align="left">Arrival Date</td>
					<td align="left">: &nbsp;&nbsp;<?php echo $eta;?></td>
				</tr>
				<tr>
					<td align="left">S.Agent Name</td>
					<td align="left">: &nbsp;&nbsp;<?php echo $customerName;?></td> 
					<td align="left"></td>
					<td align="left">Sailing</td>
					<td align="left">: &nbsp;&nbsp;<?php echo $etd;?></td>
				</tr>
				<tr>
					<td align="left">Ex. Rate</td>
					<td align="left">: &nbsp;&nbsp;<?php echo $exchangeRate;?></td>
					<td align="left"></td>
					<td align="left">Berth</td>
					<td align="left">: &nbsp;&nbsp;<?php echo $berth;?></td>
				</tr>
				
			</table>	
			<table align="center" width="100%" style="font-size:13px;">
				<tr>
					<td colspan="10">
						<hr style="margin:3px; border-top:1px dotted; color:black;"/>
					</td>
				</tr>
				<tr>
					<td align="left" style="width:5%">SL</td>
					<td align="left">PARTICULARS</td>
					<td  align="right">SIZE</td>
					<td align="right">HEIGHT</td>
					<td align="center">QTY</td>
					<td align="center">DAYS</td>
					<td align="center">RATE</td>
					<td align="center"><nobr>AMOUNT BDT</nobr></td>
					<td align="center"><nobr>VAT BDT</nobr></td>
					<td align="right"><nobr>TOTAL BDT</nobr></td>

				</tr>
				<tr>
					<td colspan="10">
						<hr style="margin:3px; border-top:1px dotted; color:black;"/>
					</td>
				</tr>
					
				<?php
				$size20=0;
				$size40=0;
				$size45=0;

                $sumAmt=0;
                $sumVat=0;
                $sum=0;
                $sumTotal=0;
				
				for($i=0; $i<count($bill_rslt); $i++) {?>
				<tr>
				
				<?php
					$port_date="";
					$portInt1Day="";
					$clDateInt4Day="";
					$depoInt1Day="";
					$freight_kind="";
					$fcy_time_in="";
					$fcy_time_out="";
					$depo_Int_Zero_Day="";
					$id="";
					$description="";
					$depo_date="";
					$fcy_time_out=$bill_rslt[$i]['fcy_time_out'];
					$fcy_time_in=$bill_rslt[$i]['fcy_time_in'];
					$port_date=$bill_rslt[$i]['port_date'];
					$portInt1Day=$bill_rslt[$i]['portInt1Day'];
					$clDateInt4Day=$bill_rslt[$i]['clDateInt4Day'];
					$depoInt1Day=$bill_rslt[$i]['depoInt1Day'];
					$depo_Int_Zero_Day=$bill_rslt[$i]['depo_Int_Zero_Day'];
					$id=$bill_rslt[$i]['id'];
					$depo_date=$bill_rslt[$i]['depo_date'];
					$description=$bill_rslt[$i]['description'];
					
					$depoDateRes="";
					if($depoInt1Day==null){
						
					    $freight_kind_qu="SELECT inv_unit.freight_kind 
						FROM inv_unit INNER JOIN inv_unit_fcy_visit ON inv_unit_fcy_visit.unit_gkey=inv_unit.gkey 
						WHERE id='$id' AND category='IMPRT' AND to_char(inv_unit_fcy_visit.time_in,'YYYY-MM-DD HH24:MI:SS') < '$fcy_time_in' ORDER BY inv_unit.gkey DESC fetch first 1 rows only";
						$rslt_freight_kind = $this->bm->dataSelect($freight_kind_qu);
						
						$freight_kind="";
					    $freight_kind=$rslt_freight_kind[0]['FREIGHT_KIND'];
						$portDate="";
						if($freight_kind=='LCL'){	
						$portDate=$port_date;
						}else{
						$portDate=$portInt1Day;
						}
						$depoDateRes=$portDate;
					}
					else{
						
						 $depoDateRes=$depoInt1Day;
					}
					
						$clDate_Res_final="";
						if($depoDateRes==null){
						 $clDate_Res_final=$clDateInt4Day;  
						}else{
						$clDate_Res_final= $depoDateRes;
						}
					   $dayDiffRes1="";
					   $difference1_qu="SELECT DATEDIFF('$fcy_time_out','$clDate_Res_final')+1 AS day1";
					   $rslt_difference1=$this->bm->dataSelectDb2($difference1_qu);
					   $dayDiffRes1=$rslt_difference1[0]['day1'];  
					   $depoDateRes1="";
					if($depo_Int_Zero_Day==null){
						$freight_kind_qu2=" SELECT inv_unit.freight_kind
						FROM inv_unit    
						INNER JOIN inv_unit_fcy_visit ON inv_unit_fcy_visit.unit_gkey=inv_unit.gkey  
						WHERE id='$id' AND category='IMPRT'  
						AND to_char(inv_unit_fcy_visit.time_in,'YYYY-MM-DD HH24:MI:SS') < '$fcy_time_in'
						ORDER BY inv_unit.gkey DESC fetch first 1 rows only";
							
						$rslt_freight_kind2 = $this->bm->dataSelect($freight_kind_qu2);
						$freight_kind1="";
						$freight_kind1=$rslt_freight_kind2[0]['FREIGHT_KIND'];
						$portDate1="";
						if($freight_kind1=='LCL'){	
						
						$portDate1=$bill_rslt[$i]['port_date'];
						}else{
						$portDate1=$bill_rslt[$i]['portInt1Day'];
						}
						$depoDateRes1=$portDate1;
					}else{
						
						$depoDateRes1=$bill_rslt[$i]['depo_Int_Zero_Day'];	
					}
						$clDate_Res_final2="";
						if($depoDateRes1==null){
						  $clDate_Res_final2=$bill_rslt[$i]['clDateInt4Day'];  
						}else{
						$clDate_Res_final2= $depoDateRes1;
						}
						
					   $difference2_qu="SELECT DATEDIFF('$fcy_time_out','$clDate_Res_final2')+1 AS day2";
					   $rslt_difference1=$this->bm->dataSelectDb2($difference2_qu);
					   $dayDiffRes2=$rslt_difference1[0]['day2'];
				  
				      
					  $days="";
					   if($dayDiffRes1<1){
						  $days='0';
					   }else{
							$days=$dayDiffRes2;
					   }
					   
					   	    $days2_qu="select IF('$depo_date' IS NOT NULL,
								IF('$description' LIKE 'Storage%',$days,0),
									IF('$description' LIKE '%1 to 7 days%',
										IF($days>=7,7,$days),
										IF('$description' LIKE '%8 to 20 days%',
											IF($days-7>=13,13,$days-7),
												IF('$description' LIKE 'Storage%',$days-20,0)
										)
									)
							) AS days2";
							
							$rslt_days2 = $this->bm->dataSelectDb2($days2_qu);
							$days2="";
						    $days2=$rslt_days2[0]['days2'];
					
				?>
					
					<td align="center" style="width:5%"><font style="border: 0px solid black;"><?php echo $i+1;?></font></td>
					<td align="left" ><?php if ($bill_rslt[$i]['usd']!="" or  null) echo $bill_rslt[$i]['usd'];  echo $bill_rslt[$i]['Particulars'];?></td>
					<td align="right"><?php echo $bill_rslt[$i]['size'];
								if($bill_rslt[$i]['size']=="20")
								{
									$size20++;
								}
								else if($bill_rslt[$i]['size']=="40")
								{
									$size40++;
								}	
								else
									$size45++;
					?></td>
					<td align="right"><?php echo number_format($bill_rslt[$i]['height'],1);?></td>
					<td align="center"><?php echo $bill_rslt[$i]['qty'];?></td>
				
					<td align="center"><?php echo $days2 ; ?></td>
					<td align="right"><?php echo number_format($bill_rslt[$i]['rateBilled'],4); ?></td>
					<td align="right"><?php echo number_format($bill_rslt[$i]['amt'],2);												
										$sumAmt=$sumAmt+$bill_rslt[$i]['amt'];?></td>
					<td align="right"><?php echo number_format($bill_rslt[$i]['vat'],2);
											$sumVat=$sumVat+$bill_rslt[$i]['vat'];?></td>
					<td align="right"><?php $sum= $bill_rslt[$i]['amt'] + $bill_rslt[$i]['vat'];
											echo number_format($sum,2);	
										$sumTotal=$sumTotal+$sum; ?></td>	
				</tr>
				<?php  } ?>
				<tr>
					<td colspan="10">
						<hr style="margin:3px; border-top:1px dotted; color:black;"/>
					</td>
				</tr>
				<tr>
					<td align="center"><nobr>20 => <?php echo $qtytot20;?></nobr></td>
					<td align="center"><nobr>40 => <?php echo $qtytot40;?></nobr></td>
					<td align="center"><nobr>45 => <?php echo $qtytot45;?></nobr></td>
					<td align="center" colspan="3"></td>
					<td align="center"><nobr>Total Taka:</nobr></td>
					<td align="right"><?php echo number_format ($sumAmt, 2);?></td>
					<td align="right"><?php  echo number_format ($sumVat, 2);?></td>
					<td align="right"><?php  echo number_format ($sumTotal, 2); ?></td>
				</tr>
				<tr>
					<td colspan="10">
						<hr style="margin:3px; border-top:1px dotted; color:black;"/>
					</td>
				</tr>
				<tr>
					<td align="left" colspan="10">$Shows Rate in US Dollar</td>
				</tr>
				
			</table>	
			<table align="center" width="100%" style="font-size:13px;">	
				<tr>
					<td colspan="6">&nbsp;</td>
				</tr>
				<tr>
					<td colspan="6" align="left">Net Payable BDT :&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b><?php  echo number_format($sumTotal,2); ?></b></td>
				</tr>	
				<tr>	
					<td colspan="6" align="left">In Words :&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>
					<?php 
					$part = explode(".",$sumTotal);
					//echo count($part);
					$taka = "";
					$paisa = "";
					if(count($part)==2)
					{
						$taka = convertNumberToWord($part[0])." Taka";
						$paisa = " and ".convertNumberToWord($part[1])." Paisa";
					}
					else
					{
						$taka = convertNumberToWord($part[0])." Taka";
					}
					echo $taka.$paisa." Only"; ?></b></td>
					
				</tr>
				<tr>	
					<td colspan="6" align="left">Ex. Currency is taken on the basis of vessel arrival date.</td>
				</tr>
				<tr>	
					<td colspan="6" align="left">Remarks &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; : <?php echo $remarks; ?></td>
				</tr>
			</table>
			<table align="center" width="90%">
				<tr>
					<td colspan="6"></td>
				</tr>
				<tr>
					<td colspan="6">&nbsp;</td>
				</tr>
				<tr>
					<td colspan="6">&nbsp;</td>
				</tr>
				<tr>
					<td colspan="6">&nbsp;</td>
				</tr>

				<tr>
					<td align="center" colspan="2"></td>
					<td align="center" colspan="2"><?php echo $created_user;?></td>
					<td align="center" colspan="2"></td>
				</tr>
				<tr>
					<td align="center" colspan="2">---------------------------</td>
					<td align="center" colspan="2">---------------------------</td>
					<td align="center" colspan="2">---------------------------</td>
				</tr>
				<tr>
					<td align="center" colspan="2">
						<b>Bank's Seal</b>
					</td>
					<td align="center" colspan="2">
						<b>Computer Operator</b>
					</td>
					<td align="center" colspan="2">
						<b>For Terminal Officer(A&G)</b>
					</td>
				</tr>
				<tr>
					<td align="center" colspan="2"></td>
					<td align="center" colspan="2">TM Office/CPA</td>
					<td align="center" colspan="2">TM Office/CPA</td>
				</tr>
				
				<tr>
					<td colspan="6">&nbsp;</td>
				</tr>
				<tr>
					<td colspan="6">&nbsp;</td>
				</tr>
				<tr>
					<td colspan="6">&nbsp;</td>
				</tr>

			</table>	
			<table align="center" width="90%" style="font-size:14px;">
				<tr>
					<td colspan="6">Print Date : &nbsp;&nbsp; <?php echo $Time; ?></td>
				</tr>
			</table>
		</div>
	
		<!--script>
			window.print();
		</script-->
	</body>
</html>
<?php function numtowords($number){ 
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
	//echo $number;
    while ($i < $digits_length) {
        $divider = ($i == 2) ? 10 : 100;		
        $number = floor($no % $divider);
        $no = floor($no / $divider);
		//echo $i.' '.$divider.' '.$number.' '.$no.'<br>';
		//echo 40140%100;
        $i += $divider == 10 ? 1 : 2;
        if ($number) {
            $plural = (($counter = count($str)) && $number > 9) ? '' : null;            
            $str [] = ($number < 21) ? $words[$number] . ' ' . $digits[$counter] . $plural : $words[floor($number / 10) * 10] . ' ' . $words[$number % 10] . ' ' . $digits[$counter] . $plural;
        } else {
            $str [] = null;
        }  
    }
    
    $Rupees = implode(' ', array_reverse($str));
    $paise = ($decimal) ? "And " . ($words[$decimal - $decimal%10]) ." " .($words[$decimal%10])." Paisa"  : '';
    return ($Rupees ?  $Rupees." Taka " : '') . $paise;
}

function convertNumberToWord($num = false)
{
    $num = str_replace(array(',', ' '), '' , trim($num));
    if(! $num) {
        return false;
    }
    $num = (int) $num;
    $words = array();
    $list1 = array('', 'One', 'Two', 'Three', 'Four', 'Five', 'Six', 'Seven', 'Eight', 'Nine', 'Ten', 'Eleven',
        'Twelve', 'Thirteen', 'Fourteen', 'Fifteen', 'Sixteen', 'Seventeen', 'Eighteen', 'Nineteen'
    );
    $list2 = array('', 'Ten', 'Twenty', 'Thirty', 'Forty', 'Fifty', 'Sixty', 'Seventy', 'Eighty', 'Ninety', 'Hundred');
    $list3 = array('', 'Thousand', 'Million', 'Billion', 'Trillion', 'Quadrillion', 'quintillion', 'sextillion', 'septillion',
        'octillion', 'nonillion', 'decillion', 'undecillion', 'duodecillion', 'tredecillion', 'quattuordecillion',
        'quindecillion', 'sexdecillion', 'septendecillion', 'octodecillion', 'novemdecillion', 'vigintillion'
    );
    $num_length = strlen($num);
    $levels = (int) (($num_length + 2) / 3);
    $max_length = $levels * 3;
    $num = substr('00' . $num, -$max_length);
    $num_levels = str_split($num, 3);
    for ($i = 0; $i < count($num_levels); $i++) {
        $levels--;
        $hundreds = (int) ($num_levels[$i] / 100);
        $hundreds = ($hundreds ? ' ' . $list1[$hundreds] . ' Hundred' . ' ' : '');
        $tens = (int) ($num_levels[$i] % 100);
        $singles = '';
        if ( $tens < 20 ) {
            $tens = ($tens ? ' ' . $list1[$tens] . ' ' : '' );
        } else {
            $tens = (int)($tens / 10);
            $tens = ' ' . $list2[$tens] . ' ';
            $singles = (int) ($num_levels[$i] % 10);
            $singles = ' ' . $list1[$singles] . ' ';
        }
        $words[] = $hundreds . $tens . $singles . ( ( $levels && ( int ) ( $num_levels[$i] ) ) ? ' ' . $list3[$levels] . ' ' : '' );
    } //end for loop
    $commas = count($words);
    if ($commas > 1) {
        $commas = $commas - 1;
    }
    return implode(' ', $words);
}
 ?>
