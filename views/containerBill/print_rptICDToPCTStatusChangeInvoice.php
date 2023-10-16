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
		<title>Container Status Change(ICD to PCT)</title>
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
			<table align="center" width="98%" style="font-size:13px;">

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
					<td align="left">: &nbsp;&nbsp;<?php echo $ibCarrierName;?></td>
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
			<table align="center" width="95%" style="font-size:11px;">
				<tr>
					<td colspan="10">
						<hr style="margin: 3px; border-top:1px dotted; color:black;"/>
					</td>
				</tr>
				<tr>
					<td align="left">SL</td>
					<td align="center" style="width:170px;">PARTICULARS</td>
					<td align="right" style="width:40px;">SIZE</td>
					<td align="right" style="width:40px;">HEIGHT</td>
					<td align="center" style="width:30px;">QTY</td>
					<td align="center" style="width:30px;">DAYS</td>
					<td align="center">RATE</td>
					<td align="center">AMOUNT BDT</td>
					<td align="center">VAT BDT</td>
					<td align="right">TOTAL BDT</td>

				</tr>
				</table>
				<table align="center" width="98%" style="font-size:13px;">
				<tr>
					<td colspan="10">
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
					<td align="left"><?php echo $i+1;?></td>
					<td align="center" style="width:170px;"><?php if ($bill_rslt[$i]['usd']!="" or  null) echo $bill_rslt[$i]['usd'];  echo $bill_rslt[$i]['Particulars'];?></td>
					<td align="right" style="width:40px;"><?php echo $bill_rslt[$i]['size'];
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
					<td align="right" style="width:40px;"><?php echo number_format($bill_rslt[$i]['height'],1);?></td>
					<td align="center" style="width:30px;"><?php echo $bill_rslt[$i]['qty'];?></td>
					<td align="center" style="width:30px;"><?php echo $bill_rslt[$i]['days2'];?></td>
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
						<hr style="margin: 3px; border-top:1px dotted; color:black;"/>
					</td>
				</tr>
				<tr>
					<td align="center"><nobr>20 => <?php echo $qtytot20;?></nobr></td>
					<td align="center"><nobr>40 => <?php echo $qtytot40;?></nobr></td>
					<td align="center"><nobr>45 => <?php echo $qtytot45;?></nobr></td>
					<td align="center" colspan="3"></td>
					<td align="center"><nobr>Total Taka:</nobr></td>
					<td align="right" style="width:80px;"><?php echo number_format ($sumAmt, 2);?></td>
					<td align="right" style="width:80px;"><?php  echo number_format ($sumVat, 2);?></td>
					<td align="right" style="width:90px;"><?php  echo number_format ($sumTotal, 2); ?></td>
				</tr>
				<tr>
					<td colspan="10">
						<hr style="padding: 0px; margin: 0px; border-top:1px dotted; color:black;"/>
					</td>
				</tr>
				<tr>
					<td align="left" colspan="10">$Shows Rate in US Dollar</td>
				</tr>
				
			</table>	
			<table align="center" width="85%" style="font-size:14px;">	
				<tr>
					<td colspan="6">&nbsp;</td>
				</tr>
				<tr>
					<td colspan="6" align="left">Net Payable BDT :&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b><?php  echo number_format($sumTotal,2); ?></b></td>
				</tr>	
				<tr>	
					<td colspan="6" align="left">In Words :&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b><?php echo numtowords($sumTotal)." Only"; ?></b></td>
					
				</tr>
				<tr>	
					<td colspan="6" align="left">Ex. Currency is taken on the basis of vessel arrival date.</td>
				</tr>
				<tr>	
					<td colspan="6" align="left">Remarks &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; :<b> <?php echo $remarks; ?></b></td>
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


			</table>	
			<table align="center" width="90%" style="font-size:14px;">
				<tr>
					<td colspan="6">Print Date : &nbsp;&nbsp; <?php echo $time; ?></td>
				</tr>
			</table>
		</div>
	
		<!--<script>
			window.print();
		</script>-->
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
