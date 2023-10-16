
<html>
<head>

		<link href="http://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700,800|Shadows+Into+Light" rel="stylesheet" type="text/css">

		<!-- Vendor CSS -->
		<link rel="stylesheet" href="<?php echo ASSETS_PATH;?>vendor/bootstrap/css/bootstrap.css" />
		<!-- Theme CSS -->
		<link rel="stylesheet" href="<?php echo ASSETS_PATH;?>stylesheets/theme.css" />

		<!-- Skin CSS -->
		<link rel="stylesheet" href="<?php echo ASSETS_PATH;?>stylesheets/skins/default.css" />

		<!----------------------------------------------------->

		<table align="center" width="95%">
			<tr>
				<td align="center"><font size="5"><b>CHITTAGONG PORT AUTHORITY</b></font></td>
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
			<table align="center" width="95%">

				<tr>
					<td align="left" style="width:15%">Bill No</td>
					<td align="left" style="width:15%">: &nbsp;&nbsp;<?php echo $bill_rslt[0]['draftNumber'];?></td>
					<td align="left" style="width:10%"></td>
					<td align="left">Bill Date</td>
					<td align="left">: &nbsp;&nbsp;<?php echo $bill_rslt[0]['created'];?></td>
					
				</tr>
				<tr>
					<td align="left">Vessel Name</td>
					<td align="left" style="width:15%">: &nbsp;&nbsp;<?php echo $bill_rslt[0]['vesselName'];?></td>
					<td align="left"></td>
					<td align="left" style="width:10%">Vessel Flag</td>
					<td align="left" style="width:25%">: &nbsp;&nbsp;<?php echo $bill_rslt[0]['flagcountry'];?></td>
				</tr>
				<tr>
					<td align="left">Rotation No</td>
					<td align="left">: &nbsp;&nbsp;<?php echo $bill_rslt[0]['ibVoyageNbr'];?></td>
					<td align="left"></td>
					<td align="left">Jetty Name</td>
					<td align="left">: &nbsp;&nbsp;</td>					
				</tr>
				<tr>
					<td align="left">Name of Master</td>
					<td align="left">: &nbsp;&nbsp;<?php echo $bill_rslt[0]['captain'];?></td>
					<td align="left"></td>
					<td align="left"></td>
					<td align="left"></td>
				</tr>
				<tr>
					<td align="left">Date of Bearthing</td>
					<td align="left">: &nbsp;&nbsp;<?php echo $bill_rslt[0]['ATA'];?></td>
					<td align="left"></td>
				    <td align="left">Time</td>
					<td align="left">: &nbsp;&nbsp;.00 To: .00 HRS</td>
				</tr>
				<tr>
					<td align="left">Date of Leaving</td>
					<td align="left">: &nbsp;&nbsp;<?php echo $bill_rslt[0]['ATD'];?></td> 
					<td align="left"></td>
					<td align="left">Time</td>
					<td align="left">: &nbsp;&nbsp;.00 To: .00 HRS</td>
				</tr>
				<tr>
					<td align="left">Agent Code</td>
					<td align="left">: &nbsp;&nbsp;<?php echo $bill_rslt[0]['payeecustomerkey'];?></td> 
					<td align="left"></td>
					<td align="left"></td>
					<td align="left"></td>
				</tr>
				<tr>
					<td align="left">Agent Name</td>
					<td align="left">: &nbsp;&nbsp;<?php echo $bill_rslt[0]['customerName'];?></td> 
					<td align="left"></td>
					<td align="left"></td>
					<td align="left"></td>
				</tr>
				<tr>
					<td align="left">Agent Address</td>
					<td align="left">: &nbsp;&nbsp;<?php echo $bill_rslt[0]['agent_address'];?></td> 
					<td align="left"></td>
					<td align="left"></td>
					<td align="left"></td>
				</tr>
				<tr>
					<td align="left">GRT of Vessel</td>
					<td align="left">: &nbsp;&nbsp;<?php echo number_format($bill_rslt[0]['grossRevenueTons'],4);?></td>
					<td align="left"></td>
					<td align="left">Deck Cargo</td>
					<td align="left">: &nbsp;&nbsp;<?php echo $bill_rslt[0]['cargo'];?></td>
				</tr>
				<tr>
					<td align="left">Ex.Rate</td>
					<td align="left">: &nbsp;&nbsp;<?php echo number_format($bill_rslt[0]['exchangeRate'],4);?></td>
					<td align="left"></td>
					<td align="left">O.A. Date</td>
					<td align="left">: &nbsp;&nbsp;<?php echo $bill_rslt[0]['onboundpiloton'];?></td>
				</tr>
				
			</table>	
			<table align="center" width="95%">
				<tr>
					<td colspan="9">
						<hr style=" border-top:1px dotted; color:black;"/>
					</td>
				</tr>
				<tr>
					<td align="left">DESCRIPTION</td>
					<td align="right">A/C</td>
					<td align="right">RATE</td>
					<td align="center">BASE</td>
					<td align="right">UNIT</td>
					<td align="right">MOVE</td>
					<td align="right">AMOUNT ($)</td>
					<td align="right">VAT</td>
				</tr>
				<tr>
					<td colspan="9">
						<hr style=" border-top:1px dotted; color:black;"/>
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
				
				for($i=0; $i<count($bill_rslt); $i++) 
				{
				?>
				
				<tr>
					<td align="left" ><?php echo $bill_rslt[$i]['description']; ?></td>					
					<td align="right" ><?php echo $bill_rslt[$i]['glcode']; ?></td>											
					<td align="right" ><?php echo $bill_rslt[$i]['rateBilled']; ?></td>											
					<td align="center" ><?php echo $bill_rslt[$i]['quantityUnit']; ?></td>											
					<td align="right" ><?php echo $bill_rslt[$i]['quantityBilled']; ?></td>											
					<td align="right" ><?php echo $bill_rslt[$i]['move']; ?></td>											
					<td align="right" ><?php echo $bill_rslt[$i]['bdChraged']; ?></td>											
					<td align="right" ><?php echo $bill_rslt[$i]['bdVat']; ?></td>											
				</tr>
				<?php  
				} 
				?>
				
			</table>	
			<table align="center" width="85%">	
				<!--tr>
					<td colspan="6">&nbsp;</td>
				</tr>
				<tr>
					<td colspan="6" align="left">Net Payable BDT :&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b><?php  echo number_format($sumTotal, 2); ?></b></td>
				</tr>
				<tr>	
					<td colspan="6" align="left">In Words :&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b><?php  echo numtowords($sumTotal)." only"; ?></b></td>
					
				</tr-->
				
				<tr>	
					<td colspan="6" align="left"><b>Remarks :</td>
					
				</tr>
			
				<tr><td></td></tr>
				<!--tr>
					<td colspan="6" align="left"><b>Ex. Currency is taken on the basis of vessel's arrival date. </b></td>
				</tr-->
			</table>
			
			<table align="right" style="margin-right:50px;width:40%">
				<tr>
					<td style="border-bottom: 1px solid black;"></td>
					<td style="border-bottom: 1px solid black;" align="right">Amount</td>
					<td style="border-bottom: 1px solid black;" align="right">Vat</td>
				</tr>
				<tr>
					<td align="right">Total US $</td>
					<td align="right"><?php echo $bill_rslt[0]['totusd']; ?></td>
					<td align="right"><?php echo $bill_rslt[0]['vatusd']; ?></td>
				</tr>
				<tr>
					<td align="right">Total TK</td>
					<td align="right"><?php echo $bill_rslt[0]['bdChraged']; ?></td>
					<td align="right"><?php echo $bill_rslt[0]['bdVat']; ?></td>
				</tr>
			</table>
			
			<table align="center" width="85%">
				<tr>
					<td><b>Inward TK</b> <?php echo numtowords($bill_rslt[0]['bdChraged']) ?></td>
				</tr><tr>
					<td><b>Inward VAT</b> <?php echo numtowords($bill_rslt[0]['bdVat']) ?></td>
				</tr>
			</table>
			
			<table align="center" width="85%" border=0>
				<tr>
					<td colspan="12">&nbsp;</td>
				</tr>
				<tr>
					<td colspan="12">&nbsp;</td>
				</tr>
				<tr>
					<td colspan="12">&nbsp;</td>
				</tr>
				<tr>
					<td colspan="12">&nbsp;</td>
				</tr>
				<tr>
					<td colspan="12">&nbsp;</td>
				</tr>
				<tr>
					<td colspan="12">&nbsp;</td>
				</tr>
				<tr>
					<td colspan="12">&nbsp;</td>
				</tr>
				<tr>
					<td>&nbsp;</td>
					<td align="center" colspan="2"><?php echo $bill_rslt[0]['creator'];?></td>
					<td>&nbsp;</td>
					
					<td>&nbsp;</td>
					<td colspan="2">&nbsp;</td>
					<td>&nbsp;</td>
					
					<td>&nbsp;</td>
					<td colspan="2">&nbsp;</td>
					<td>&nbsp;</td>
				</tr>
				<tr>
					<td>&nbsp;</td>
					<td align="center" colspan="2">---------------------------</td>
					<td>&nbsp;</td>
					
					<td>&nbsp;</td>
					<td align="center" colspan="2">&nbsp;</td>
					<td>&nbsp;</td>
					
					<td>&nbsp;</td>
					<td align="center" colspan="2">---------------------------</td>
					<td>&nbsp;</td>
				</tr>
				<tr>
					<td>&nbsp;</td>
					<td align="center" colspan="2"><b>Computer Operator</b></td>
					<td>&nbsp;</td>
					
					<td>&nbsp;</td>
					<td colspan="2">&nbsp;</td>
					<td>&nbsp;</td>
					
					<td>&nbsp;</td>
					<td align="center" colspan="2"><b>For C.F. & A.O. CPA</b></td>
					<td>&nbsp;</td>
				</tr>
				<tr>
					<td colspan="12">&nbsp;</td>
				</tr>
				<tr>
					<td colspan="12">&nbsp;</td>
				</tr>
				<tr>
					<td colspan="12">&nbsp;</td>
				</tr>
				<tr>
					<td colspan="12">&nbsp;</td>
				</tr>
				<tr>
					<td colspan="12">&nbsp;</td>
				</tr>
				<tr>
					<td colspan="12"><?php echo $print_time[0]['Time']; ?></td>
				</tr>
			</table>			
			
			<!--table align="center" width="85%">
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
					<td colspan="6">&nbsp;</td>
				</tr>
				<tr>
					<td colspan="6">&nbsp;</td>
				</tr>

				<tr>
					<td align="center" colspan="2"></td>
					<td align="center" colspan="2"><?php echo $bill_rslt[0]['creator'];?></td>
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