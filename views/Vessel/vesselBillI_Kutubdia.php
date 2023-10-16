
<html>
<head>

		<link href="http://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700,800|Shadows+Into+Light" rel="stylesheet" type="text/css">

		<!-- Vendor CSS -->
		<link rel="stylesheet" href="<?php echo ASSETS_PATH;?>vendor/bootstrap/css/bootstrap.css" />
		<!-- Theme CSS -->
		<link rel="stylesheet" href="<?php echo ASSETS_PATH;?>stylesheets/theme.css" />

		<!-- Skin CSS -->
		<link rel="stylesheet" href="<?php echo ASSETS_PATH;?>stylesheets/skins/default.css" />

</head>
<body>	
		<div align ="center">

			<!----------------------------------------------------->
			<p align="right" style="margin-right:20px;"><font size="4">Print Date: <?php echo $print_time[0]['Time']; ?></font></p>

			<table align="center" width="95%">
				<!-- <tr>
					<td align="right" class="padding_top_bottom"><font size="5"><b>Print Date : </b> <?php //echo $print_time[0]['Time']; ?></font></td>
				</tr> -->
				<tr>
					<td align="center"><font size="6"><b>CHITTAGONG PORT AUTHORITY</b></font></td>
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

			<table align="center" width="95%">

				<tr>
					<td align="left" style="width:15%"><font size="5"><b>Bill No</b></font></td>
					<td align="center" width="2%">:</td>
					<td align="left" style="width:15%"><font size="5"><?php echo strtoupper($bill_rslt[0]['draftNumber']);?></font></td>
					<td width="2%"></td>
					<td align="left"><font size="5"><b>Bill Date</b></font></td>
					<td align="center" width="2%">:</td>
					<td align="left"><font size="5"><?php echo $bill_rslt[0]['created'];?></font></td>
					
				</tr>
				<tr>
					<td align="left" class="padding_top_bottom"><font size="5"><b>Vessel Name</b></font></td>
					<td align="center" width="2%">:</td>
					<td align="left" class="padding_top_bottom" style="width:15%"><font size="5"><?php echo $bill_rslt[0]['vesselName'];?></font></td>
					<td width="2%"></td>
					<td align="left" class="padding_top_bottom" style="width:10%"><font size="5"><b>Vessel Flag</b></font></td>
					<td align="center" width="2%">:</td>
					<td align="left" class="padding_top_bottom" style="width:25%"><font size="5"><?php echo $bill_rslt[0]['flagcountry'];?></font></td>
				</tr>
				<tr>
					<td align="left" class="padding_top_bottom"><font size="5"><b>Rotation No</b></font></td>
					<td align="center" width="2%">:</td>
					<td align="left" class="padding_top_bottom"><font size="5"><?php echo $bill_rslt[0]['ibVoyageNbr'];?></font></td>
					<td width="2%"></td>
					<td align="left" class="padding_top_bottom"><font size="5"><b>Jetty Name</b></font></td>
					<td align="center" width="2%">:</td>
					<td align="left" class="padding_top_bottom"><font size="5"></font></td>					
				</tr>
				<tr>
					<td align="left" class="padding_top_bottom"><font size="5"><b>Name of Master</b></font></td>
					<td align="center" width="2%">:</td>
					<td align="left" class="padding_top_bottom"><font size="5"><?php echo $bill_rslt[0]['captain'];?></font></td>
					<td width="2%"></td>
					<td align="left"></td>
					<td align="left"></td>
				</tr>
				<tr>
					<td align="left" class="padding_top_bottom"><font size="5"><b>Date of Bearthing</b></font></td>
					<td align="center" width="2%">:</td>
					<td align="left" class="padding_top_bottom"><font size="5"><?php echo $bill_rslt[0]['ATA'];?></font></td>
					<td width="2%"></td>
				    <td align="left" class="padding_top_bottom"><font size="5"><b>Time</b></font></td>
					<td align="center" width="2%">:</td>
					<td align="left" class="padding_top_bottom"><font size="5">.00 To: .00 HRS</font></td>
				</tr>
				<tr>
					<td align="left" class="padding_top_bottom"><font size="5"><b>Date of Leaving</b></font></td>
					<td align="center" width="2%">:</td>
					<td align="left" class="padding_top_bottom"><font size="5"><?php echo $bill_rslt[0]['ATD'];?></font></td> 
					<td width="2%"></td>
					<td align="left" class="padding_top_bottom"><font size="5"><b>Time</b></font></td>
					<td align="center" width="2%">:</td>
					<td align="left" class="padding_top_bottom"><font size="5">.00 To: .00 HRS</font></td>
				</tr>
				<tr>
					<td align="left" class="padding_top_bottom"><font size="5"><b>Agent Code</b></font></td>
					<td align="center" width="2%">:</td>
					<td align="left" class="padding_top_bottom"><font size="5"><?php echo $bill_rslt[0]['payeecustomerkey'];?></font></td> 
					<!-- <td width="2%"></td>
					<td align="left"><font size="5"><b>Print Date</b></font></td>
					<td align="center" width="2%">:</td>
					<td align="left"><font size="5"><?php //echo $print_time[0]['Time']; ?></font></td> -->
				</tr>
				<tr>
					<td align="left" class="padding_top_bottom"><font size="5"><b>Agent Name</b></font></td>
					<td align="center" width="2%">:</td>
					<td align="left" colspan="2" class="padding_top_bottom"><font size="5"><?php echo $bill_rslt[0]['customerName'];?></font></td> 
					<!-- <td width="2%"></td>
					<td align="left"></td>
					<td align="left"></td> -->
				</tr>
				<tr>
					<td align="left" class="padding_top_bottom"><font size="5"><b>Agent Address</b></font></td>
					<td align="center" width="2%">:</td>
					<td align="left" colspan="5" class="padding_top_bottom"><font size="5"><?php echo $bill_rslt[0]['agent_address'];?></font></td> 
					<!-- <td width="2%"></td>
					<td align="left"></td>
					<td align="left"></td> -->
				</tr>
				<tr>
					<td align="left" class="padding_top_bottom"><font size="5"><b>GRT of Vessel</b></font></td>
					<td align="center" width="2%">:</td>
					<td align="left" class="padding_top_bottom">
						<font size="5"><?php echo number_format($bill_rslt[0]['grossRevenueTons'],4);?></font>
					</td>
					<td width="2%"></td>
					<td align="left" class="padding_top_bottom"><font size="5"><b>Deck Cargo</b></font></td>
					<td align="center" width="2%">:</td>
					<td align="left" class="padding_top_bottom"><font size="5"><?php echo $bill_rslt[0]['cargo'];?></font></td>
				</tr>
				<tr>
					<td align="left" class="padding_top_bottom"><font size="5"><b>Ex.Rate</b></font></td>
					<td align="center" width="2%">:</td>
					<td align="left" class="padding_top_bottom"><font size="5"><?php echo number_format($bill_rslt[0]['exchangeRate'],4);?></font></td>
					<td width="2%"></td>
					<td align="left" class="padding_top_bottom"><font size="5"><b>O.A. Date</b></font></td>
					<td align="center" width="2%">:</td>
					<td align="left" class="padding_top_bottom"><font size="5"><b><?php echo $bill_rslt[0]['onboundpiloton'];?></b></font></td>
				</tr>
				
			</table>	
			<table align="center" width="95%">
				<tr>
					<td colspan="9">
						<hr style=" border-top:1px dotted; color:black;"/>
					</td>
				</tr>
				<tr>
					<td align="left"><font size="5"><b>DESCRIPTION</b></font></td>
					<td align="right"><font size="5"><b>A/C</b></font></td>
					<td align="right"><font size="5"><b>RATE</b></font></td>
					<td align="center"><font size="5"><b>BASE</b></font></td>
					<td align="right"><font size="5"><b>UNIT</b></font></td>
					<td align="right"><font size="5"><b>MOVE</b></font></td>
					<td align="right"><font size="5"><b>AMOUNT ($)</b></font></td>
					<td align="right"><font size="5"><b>VAT</b></font></td>
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

				$totDollarAmt = 0;
				$totDollarVat = 0;
				
				for($i=0; $i<count($bill_rslt); $i++) 
				{
					$totDollarAmt = $totDollarAmt + $bill_rslt[$i]['totusd'];
					$totDollarVat = $totDollarVat + $bill_rslt[$i]['vatusd'];
				?>
				
				<tr>
					<td align="left" class="padding_top_bottom"><font size="5"><?php echo $bill_rslt[$i]['description']; ?></font></td>					
					<td align="right" class="padding_top_bottom"><font size="5"><?php echo $bill_rslt[$i]['glcode']; ?></font></td>											
					<td align="right" class="padding_top_bottom_right">
						<font size="5"><?php echo number_format($bill_rslt[$i]['rateBilled'],4); ?></font>
					</td>											
					<td align="center" class="padding_top_bottom"><font size="5"><?php echo $bill_rslt[$i]['quantityUnit']; ?></font></td>											
					<td align="right" class="padding_top_bottom"><font size="5"><?php echo $bill_rslt[$i]['quantityBilled']; ?></font></td>											
					<td align="right" class="padding_top_bottom"><font size="5"><?php echo $bill_rslt[$i]['move']; ?></font></td>											
					<td align="right" class="padding_top_bottom"><font size="5"><?php echo $bill_rslt[$i]['totusd']; ?></font></td>											
					<td align="right" class="padding_top_bottom"><font size="5"><?php echo $bill_rslt[$i]['vatusd']; ?></font></td>											
				</tr>
				<?php  
				} 
				?>
				
			</table>	
			
			
			<table align="right" style="margin-right:50px;width:40%">
				<tr>
					<td style="border-bottom: 1px solid black;"></td>
					<td style="border-bottom: 1px solid black;" align="right"><font size="5"><b>Amount</b></font></td>
					<td style="border-bottom: 1px solid black;" align="right"><font size="5"><b>Vat</b></font></td>
				</tr>
				<tr>
					<td align="right"><font size="5"><b>Total US $</b></font></td>
					
					<td align="right"><font size="5"><?php echo $totDollarAmt; ?></font></td>
					<td align="right"><font size="5"><?php echo $totDollarVat; ?></font></td>
				</tr>
				<tr>
					<td align="right"><font size="5"><b>Total TK</b></font></td>
					<td align="right"><font size="5"><?php echo number_format(($totDollarAmt*$bill_rslt[0]['exchangeRate']),4); ?></font></td>
					<td align="right"><font size="5"><?php echo number_format(($totDollarVat*$bill_rslt[0]['exchangeRate']),4); ?></font></td>
				</tr>
			</table>
			
			<table align="center" width="85%">
				<tr>
					<td><font size="5"><b>Inward TK</b> <?php echo numtowords($totDollarAmt*$bill_rslt[0]['exchangeRate']) ?></font></td>
				</tr><tr>
					<td><font size="5"><b>Inward VAT</b> <?php echo numtowords($totDollarVat*$bill_rslt[0]['exchangeRate']) ?></font></td>
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
					<td align="center" colspan="2">
						<img src="<?php echo $_SERVER['DOCUMENT_ROOT']."/pcs/resources/images/signature_vessel_bill/".$bill_rslt[0]['creator'].".jpg" ?>" align="center" alt="No Image Found" width="100" height="50">
					</td>
					<td>&nbsp;</td>
					
					<td>&nbsp;</td>
					<td colspan="2">&nbsp;</td>
					<td>&nbsp;</td>
					
					<td>&nbsp;</td>
					<td align="center" colspan="2">
						<?php
						if($bill_rslt[0]['acc_apprv_by'])
						{
						?>
						<img src="<?php echo $_SERVER['DOCUMENT_ROOT']."/pcs/resources/images/signature_vessel_bill/".$bill_rslt[0]['acc_apprv_by'].".jpg" ?>" align="center" alt="No Image Found" width="100" height="50">
						<?php
						}
						?>
					</td>
					<td>&nbsp;</td>
				</tr>
				<tr>
					<td>&nbsp;</td>
					<td align="center" colspan="2">
						<font size="5"><b><?php echo $billOpName; ?></b></font>
					</td>
					<td>&nbsp;</td>
					
					<td>&nbsp;</td>
					<td align="center" colspan="2">&nbsp;</td>
					<td>&nbsp;</td>
					
					<td>&nbsp;</td>
					<td align="center" colspan="2">
						<font size="5"><b><?php echo $aprvByName; ?></b></font>
					</td>
					<td>&nbsp;</td>
				</tr>
				<tr>
					<td>&nbsp;</td>
					<td align="center" colspan="2"><b>&nbsp;</b></td>
					<td>&nbsp;</td>
					
					<td>&nbsp;</td>
					<td colspan="2">&nbsp;</td>
					<td>&nbsp;</td>
					
					<td>&nbsp;</td>
					<td align="center" colspan="2"><font size="5"><b>For C.F. & A.O. CPA</b></font></td>
					<td>&nbsp;</td>
				</tr>
			</table>
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