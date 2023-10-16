<!--<style>
table, th, td {
	color: black;
}
@media print 
{
  @page { margin: 0; }
  body  { margin: 1.6cm; }
}
</style> -->
<html>
	<head>
		<!--<title >Container Discharging Bill (PCT)</title> -->
		<!-- Web Fonts  -->
		<!--link href="//fonts.googleapis.com/css?family=Open+Sans:300,400,600,700,800" rel="stylesheet" type="text/css">


		<!-- Invoice Print Style -->
		<link rel="stylesheet" href="<?php echo ASSETS_PATH;?>stylesheets/invoice-print.css" />
		<table align="center" width="95%">
			<tr>
				<td align="center" style="width:536px; height:20px"><font size="4" ><b>CHITTAGONG PORT AUTHORITY</b></font></td>
			</tr>
			<tr>
				<td align="center"><b>VAT Reg:2041001546</b></td>
			</tr>
			<tr>
				<td align="center"><b><?php echo $bill_rslt[0]['invoiceDesc'];?></b></td>
			</tr>
			<?php
			//if($version!="")
			//{
			?>
			<tr>
				<!--td align="center">Version:&nbsp;<?php echo $version; ?></td-->
			</tr>
			<?php
			//}
			?>			
		</table>	
		<!--div align="center">(LCL BILL)</div-->

</head>
<body>	
		<div align ="center">
			<table align="center">

				<tr>
			
					<td align="left" style="width:15%; font-size:14px;">Bill No</td>
					<td align="left" style="width:15%;  font-size:14px;">: &nbsp;&nbsp;<?php echo $bill_rslt[0]['draftNumber'];?></td>
					<td align="left" style="width:10%;  font-size:14px;"></td>
					<td align="left" style="font-size:14px;">Bill Creator</td>
					<td align="left" style="font-size:14px;">: &nbsp;&nbsp;<?php echo $bill_rslt[0]['created_user'];?></td>
					
				</tr>
				<tr>
					<td align="left" style="font-size:14px;">Nature Bill</td>
					<td align="left" style="font-size:13px;"><nobr>: DISCHARGING BILL (PCT) </nobr><!--?php echo $bill_rslt[0]['vesselName'];?--></td>
					<td align="left" ></td>
					<td align="left" style="width:10%; font-size:14px;">Bill Date</td>
					<td align="left" style="width:25%; font-size:14px;">: &nbsp;&nbsp;<?php echo $bill_rslt[0]['billingDate'];?></td>
				</tr>
				<tr>
					<td align="left" style="font-size:14px;">MLO</td>
					<td align="left" style="font-size:14px;">: &nbsp;&nbsp;<?php echo $bill_rslt[0]['payCustomerId'];?></td>
					<td align="left"></td>
					<td align="left" style="font-size:14px;">Rotation No</td>
					<td align="left" style="font-size:14px;">: &nbsp;&nbsp;<?php echo $bill_rslt[0]['ibVisitId'];?></td>
				</tr>
				<tr>
					<td align="left" style="font-size:14px;">MLO Name</td>
					<td align="left" style="font-size:14px;">: &nbsp;&nbsp;<?php echo $bill_rslt[0]['payCustomername'];?></td>
					<td align="left"></td>
					<td align="left" style="font-size:14px;">Vessel</td>
					<td align="left" style="font-size:13px;">: &nbsp;&nbsp;<?php echo $bill_rslt[0]['ibCarrierName'];?></td>
				</tr>
				<tr>
					<td align="left" style="font-size:14px;">S.Agent</td>
					<td align="left" style="font-size:14px;">: &nbsp;&nbsp;<?php echo $bill_rslt[0]['customerId'];?></td>
					<td align="left"  style="font-size:14px;"></td>
				    <td align="left" style="font-size:14px;">Arrival Date</td>
					<td align="left" style="font-size:14px;">: &nbsp;&nbsp;<?php echo $bill_rslt[0]['eta'];?></td>
				</tr>
				<tr>
					<td align="left" style="font-size:14px;">S.Agent Name</td>
					<td align="left" style="font-size:14px;">: &nbsp;&nbsp;<?php echo $bill_rslt[0]['customerName'];?></td> 
					<td align="left"></td>
					<td align="left" style="font-size:14px;">Sailing</td>
					<td align="left" style="font-size:14px;">: &nbsp;&nbsp;<?php echo $bill_rslt[0]['etd'];?></td>
				</tr>
				<tr>
					<td align="left" style="font-size:14px;">Ex.Rate</td>
					<td align="left" style="font-size:14px;">: &nbsp;&nbsp;<?php echo number_format($bill_rslt[0]['exchangeRate'],4);?></td>
					<td align="left" style="font-size:14px;"></td>
					<td align="left" style="font-size:14px;">Berth</td>
					<td align="left" style="font-size:14px;" >: &nbsp;&nbsp;<?php echo $bill_rslt[0]['berth'];?></td>
				</tr>
				
			</table>	
		<table align="center" width="100%">
				<tr>
					<td colspan="9">
						<hr style="margin:3px; border-top:1px dotted; color:black;"/>
					</td>
				</tr>
				<tr>
					<td style="width:40px; font-size:14px; " align="left">SL</td>
					<td align="left" style="font-size:14px;">PARTICULARS</td>
					<td align="right" style="font-size:14px;">SIZE</td>
					<td align="right" style="font-size:14px;">HEIGHT</td>
					<td align="center" style="font-size:14px;">QTY</td>
					<td align="right" style="font-size:14px;">RATE</td>
					<td align="right" style="font-size:14px;">AMOUNT BDT</td>
					<td align="right" style="font-size:14px;">VAT BDT</td>
					<td align="right" style="font-size:14px;">TOTAL BDT</td>

				</tr>
				<tr >
					<td colspan="9">
						<hr style="margin: 3px; border-top:1px dotted; color:black;"/>
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
					<td align="left" style="font-size:12px;"><?php echo $i+1;?></td>
					<td align="left" style="font-size:12px;"><?php if ($bill_rslt[$i]['usd']!="" or  null) echo $bill_rslt[$i]['usd'];  echo $bill_rslt[$i]['Particulars']; echo '   ('.$bill_rslt[$i]['wpn'].')';?></td>
					<td align="right" style="font-size:12px;"><?php echo $bill_rslt[$i]['size'];
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
					<td align="right" style="font-size:12px;"><?php echo number_format($bill_rslt[$i]['height'],1);?></td>
					<td align="center" style="font-size:12px;"><?php echo $bill_rslt[$i]['qty'];?></td>
					<td align="right" style="font-size:12px;"><?php echo number_format($bill_rslt[$i]['rateBilled'],4)?></td>
					<td align="right" style="font-size:12px;"><?php echo number_format($bill_rslt[$i]['amt'],2);
											
										$sumAmt=$sumAmt+$bill_rslt[$i]['amt'];?></td>
					<td align="right" style="font-size:12px;"><?php echo number_format($bill_rslt[$i]['vat'],2);
											$sumVat=$sumVat+$bill_rslt[$i]['vat'];?></td>
					<td align="right" style="font-size:12px;"><?php $sum= $bill_rslt[$i]['amt'] + $bill_rslt[$i]['vat']; echo number_format($sum,2); $sumTotal=$sumTotal+$sum;?></td>	
				</tr>
				<?php  } ?>
				<tr>
					<td colspan="9">
						<hr style="margin: 3px; border-top:1px dotted; color:black;"/>
					</td>
				</tr>
				<tr>
					<td align="center" style="font-size:12px;"><nobr> 20 => </nobr><?php echo$bill_rslt[0]['qtytot20'];?></td>
					<td align="center" style="font-size:12px;"><nobr> 40 => </nobr><?php echo $bill_rslt[0]['qtytot40'];?></td>
					<td align="center" style="font-size:12px;"><nobr> 45 => </nobr><?php echo $bill_rslt[0]['qtytot45'];?></td>
					<td align="center" colspan="2" ></td>
					<td align="center">Total Taka:</td>
					<td align="right" style="font-size:12px;"><?php echo number_format($sumAmt,2);?></td>
					<td align="right" style="font-size:12px;"><?php  echo number_format($sumVat,2); ?></td>
					<td align="right" style="font-size:12px;"><?php  echo number_format($sumTotal,2); ?></td>
				</tr>
				<tr>
					<td colspan="9">
						<hr style="margin:3px; border-top:1px dotted; color:black;"/>
					</td>
				</tr>
			</table>	
			 
			
			<table align="center" width="85%">	
				<tr>
					<td colspan="6">&nbsp;</td>
				</tr>
				<tr>
					<td colspan="6" align="left">Net Payable BDT :&nbsp;&nbsp;&nbsp;&nbsp;<b><?php  echo number_format($sumTotal, 2); ?></b></td>
				</tr>
				<tr>	
					<td colspan="6" align="left">In Words :&nbsp;&nbsp;&nbsp;&nbsp;<b><?php  echo numtowords($sumTotal)." only"; ?></b></td>
					
				</tr>
				<?php //if( $bill_rslt[0]['remarks']=="RECTIFIED"){?>
				<tr>	
					<td colspan="6" align="left"><b>Remarks :&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b><?php  echo $bill_rslt[0]['remarks']; ?></b></td>
					
				</tr>
				<?php // } ?>
				<tr><td></td></tr>
				<tr>
					<td colspan="6" align="left"><b>Ex. Currency is taken on the basis of vessel's arrival date. </b></td>
				</tr>
			</table>
			<table align="center" width="85%">
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
					<td align="center" colspan="2"><?php echo $bill_rslt[0]['created_user'];?></td>
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
					<td colspan="6"><?php echo $print_time[0]['Time']; ?></td>
				</tr>
				
				
			</table>
		</div>
		
	
       <!--<script>
		window.print();
			//window.print();
		</script>-->
	</body>
</html>
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
    $paise = ($decimal) ? "And " . ($words[$decimal - $decimal%10]) ." " .($words[$decimal%10])." Paisa"  : '';
    return ($Rupees ?  $Rupees." Taka " : '') . $paise;
}
?>

