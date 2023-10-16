<!--<style>
table, th, td {
	color: black;
}
</style>-->
<html>
<head>
		<link href="//fonts.googleapis.com/css?family=Open+Sans:300,400,600,700,800" rel="stylesheet" type="text/css">

		<!-- Invoice Print Style -->
		<link rel="stylesheet" href="<?php echo ASSETS_PATH;?>stylesheets/invoice-print.css" />
		<table align="center" width="95%">
			<tr>
			
				<td align="center"><font size="5"><b>CHITTAGONG PORT AUTHORITY</b></font></td>
				<!-- <td align="left"><?php echo $status?></td> -->
			</tr>
			<tr>
				<td align="center"><b>VAT Reg:2041001546</b></td>
			</tr>
			<tr>
				<td align="center"><b><?php echo $invoiceDesc; //$bill_rslt[0]['invoiceDesc'];?></b></td>
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
			<table align="center" width="95%">

				<tr>
					<td align="left" style="width:15%">Bill No</td>
					<td align="left" style="width:15%">: &nbsp;&nbsp;<?php echo $draftNumber;?></td>
					<td align="left" style="width:10%"></td>
					<td align="left">Bill Creator</td>
					<td align="left">: &nbsp;&nbsp;<?php echo $created_user;?></td>
					
				</tr>
				<tr>
					<td align="left">Nature Bill</td>
					<td align="left">: &nbsp;&nbsp; <?php echo $invoiceDesc;?><?php //echo $vesselName;?></td>
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
			<table align="center" width="95%" border='0'>
				<tr>
					<td colspan="10">
						<hr style="margin:3px; border-top:1px dotted; color:black;"/>
					</td>
				</tr>
				<tr>
					<td style="width:50px" align="left">SL</td>
					<td style="width:200px" align="left">PARTICULARS</td>
					<td style="width:50px" align="right">SIZE</td>
					<td align="right">HEIGHT</td>
					<td align="center">QTY</td>
					<td align="right">DAYS</td>
					<td align="right">RATE</td>
					<td align="right">AMOUNT BDT</td>
					<td align="right">VAT BDT</td>
					<td align="right">TOTAL BDT</td>

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
				$usd="";
				$Particulars="";
				$height="";
				$Quantity="";
				$days2="";
				
				for($i=0; $i<count($bill_rslt); $i++) {?>
				
				<tr>
				<?php  
				 $usd=$bill_rslt[$i]['usd'];
				 $Particulars=$bill_rslt[$i]['Particulars'];
				 $size=$bill_rslt[$i]['size'];
				 $height=$bill_rslt[$i]['height'];
				 $Quantity=$bill_rslt[$i]['qty'];
				 $days2=$bill_rslt[$i]['days2'];
				 
				 //echo "<br>";
				//die();
				?>
					<td align="left"><?php echo $i+1;?></td>
					<td align="left" ><?php if ($usd!="" or  null) echo $usd;  echo $Particulars;?></td>
					<td align="right"><?php echo $size;
								if($size=="20")
								{
									$size20++;
								}
								else if($size=="40")
								{
									$size40++;
								}	
								else
									$size45++;
					?></td>
					<td align="right"><?php echo $height;?></td>
					<td align="center"><?php echo $Quantity;?></td>
					<td align="right"><?php echo $days2;?></td>
					<td align="right"><?php echo number_format($bill_rslt[$i]['rateBilled'],4); ?></td>
					<td align="right"><?php if(strpos($bill_rslt[$i]['amt'], '.') !== false) {
												echo $bill_rslt[$i]['amt'];
											} else {
												echo $bill_rslt[$i]['amt'].".00";
											}	
										$sumAmt=$sumAmt+$bill_rslt[$i]['amt'];?></td>
					<td align="right"><?php if(strpos($bill_rslt[$i]['vat'], '.') !== false) {
												echo $bill_rslt[$i]['vat'];
											} else {
												echo $bill_rslt[$i]['vat'].".00";
											}
											$sumVat=$sumVat+$bill_rslt[$i]['vat'];?></td>
					<td align="right"><?php $sum= $bill_rslt[$i]['amt'] + $bill_rslt[$i]['vat'];
											if(strpos($sum, '.') !== false) {
												echo $sum;
											} else {
												echo $sum.".00";
											}	
										$sumTotal=$sumTotal+$sum; ?></td>	
				</tr>
				<?php  } ?>
				<tr>
					<td colspan="10">
						<hr style="margin:3px; border-top:1px dotted; color:black;"/>
					</td>
				</tr>
				<tr>
					<td align="center"> 20 => <?php echo $qtytot20;?></td>
					<td align="center"> 40 => <?php echo $qtytot40;?></td>
					<td align="center"> 45 => <?php echo $qtytot45;?></td>
					<td align="center" colspan="3"></td>
					<td align="center">Total Taka:</td>
					<td align="right"><?php echo $sumAmt;?></td>
					<td align="right"><?php  echo $sumVat;?></td>
					<td align="right"><?php  echo $sumTotal; ?></td>
				</tr>
				<tr>
					<td colspan="10">
						<hr style="margin:3px; border-top:1px dotted; color:black;"/>
				</tr>
				<tr>
					<td align="left" colspan="10">$Shows Rate in US Dollar</td>
				</tr>
				
			</table>	
			<table align="center" width="85%">	
				<tr>
					<td colspan="6">&nbsp;</td>
				</tr>
				<tr>
					<td colspan="6" align="left">Net Payable BDT :&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php  echo $sumTotal; ?></td>
				</tr>	
				<tr>	
					<td colspan="6" align="left">In Words :&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b><?php  echo numtowords($sumTotal)." Only"; ?></b></td>
					
				</tr>
				<tr>	
					<td colspan="6" align="left">Ex. Currency is taken on the basis of vessel arrival date.</td>
				</tr>
				<tr>	
					<td colspan="6" align="left">Remarks &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; : <?php echo $remarks?></td>
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
			<table align="center" width="90%">
				<tr>
					<td colspan="6">Print Date : &nbsp;&nbsp; <?php echo $print_time[0]['Time']; ?></td>
				</tr>
				
				
			</table>
		</div>
</body>
<!--<script>
		window.print();
	</script>
</html>
<?php function numtowords($number){ 
  $decimal = round($number - ($no = floor($number)), 2) * 100;
    $hundred = null;
    $digits_length = strlen($no);
    $i = 0;
    $str = array();
    $words = array(0 => '', 1 => 'One', 2 => 'Two',
        3 => 'Three', 4 => 'Four', 5 => 'Five', 6 => 'Six',
        7 => 'Seven', 8 => 'Eight', 9 => 'Nine',
        10 => 'Ten', 11 => 'Eleven', 12 => 'Twelve',
        13 => 'Thirteen', 14 => 'Fourteen', 15 => 'Fifteen',
        16 => 'Sixteen', 17 => 'Seventeen', 18 => 'Eighteen',
        19 => 'Nineteen', 20 => 'Twenty', 30 => 'Thirty',
        40 => 'Forty', 50 => 'Fifty', 60 => 'Sixty',
        70 => 'Seventy', 80 => 'Eighty', 90 => 'Ninety');
    $digits = array('', 'Hundred','Thousand','Lac', 'Crore');
    while( $i < $digits_length ) {
        $divider = ($i == 2) ? 10 : 100;
        $number = floor($no % $divider);
        $no = floor($no / $divider);
        $i += $divider == 10 ? 1 : 2;
        if ($number) {
            $plural = (($counter = count($str)) && $number > 9) ? ' ' : null;
            $hundred = ($counter == 1 && $str[0]) ? ' and ' : null;
            $str [] = ($number < 21) ? $words[$number].' '. $digits[$counter]. $plural.' '.$hundred:$words[floor($number / 10) * 10].' '.$words[$number % 10]. ' '.$digits[$counter].$plural.' '.$hundred;
        } else $str[] = null;
    }
    $Rupees = implode('', array_reverse($str));
    $paise = ($decimal) ? " and " . ($words[$decimal / 10] . " " . $words[$decimal % 10]) . ' Paisa' : '';
    return ($Rupees ? $Rupees . 'Taka ' : '') . $paise ;
}
 ?>
