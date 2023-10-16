<style type="text/css" media="print">
	@page {
		size: auto;   /* auto is the initial value */
		margin: 0;  /* this affects the margin in the printer settings */
	}
</style>
		
		<!-- start: page -->
			<section class="panel">
				<div class="panel-body">
					<div class="invoice">
						<header class="clearfix">
							<div class="row">
								<div class="col-sm-12 text-center mt-md mb-md">
									<div class="ib">
										<img src="<?php echo ASSETS_WEB_PATH;?>fimg/cpaLogo.png" alt="Chattogram Port Authority Logo"/><br>
										<h4 class="h4 mt-none mb-sm text-dark text-bold">Chattogram Port Authority</h4>
										<h5 align="center" class="h5 mt-none mb-sm text-dark text-bold">VAT Reg: 2041001546</h5>
										<h5 align="center" class="h5 mt-none mb-sm text-dark text-bold">
											<?php echo isset($bill_rslt[0]['invoiceDesc']) ? $bill_rslt[0]['invoiceDesc'] : "" ;?>
										</h5>
									</div>
								</div>
							</div>
							<div class="row">
								<div class="col-sm-6 mt-md">
									<h6 class="h6 mt-none mb-sm text-dark text-bold">BILL NO : <?php echo isset($bill_rslt[0]['draftNumber']) ? $bill_rslt[0]['draftNumber'] : "" ;?></h6>
									<h6 class="h6 mt-none mb-sm text-dark text-bold">VESSEL NAME : <?php echo isset($bill_rslt[0]['vesselName']) ? $bill_rslt[0]['vesselName'] : "" ;?></h6>
									<h6 class="h6 mt-none mb-sm text-dark text-bold">ROT NO : <?php echo isset($bill_rslt[0]['ibVoyageNbr']) ? $bill_rslt[0]['ibVoyageNbr'] : "";?></h6>
									<h6 class="h6 mt-none mb-sm text-dark text-bold">NAME OF MASTER : <?php echo isset($bill_rslt[0]['captain']) ? $bill_rslt[0]['captain'] : "";?></h6>
									<h6 class="h6 mt-none mb-sm text-dark text-bold">DATE OF BERTHING : <?php echo isset($bill_rslt[0]['ATA']) ? $bill_rslt[0]['ATA'] : "";?></h6>
									<h6 class="h6 mt-none mb-sm text-dark text-bold">DATE OF LEAVING : <?php echo isset($bill_rslt[0]['ATD']) ? $bill_rslt[0]['ATD'] : "";?></h6>
									<h6 class="h6 mt-none mb-sm text-dark text-bold">AGENT CODE : <?php echo isset($bill_rslt[0]['payeecustomerkey']) ? $bill_rslt[0]['payeecustomerkey'] : "";?></h6>											
									<h6 class="h6 mt-none mb-sm text-dark text-bold">AGENT NAME : <?php echo isset($bill_rslt[0]['customerName']) ? $bill_rslt[0]['customerName'] : "";?></h6>
									<h6 class="h6 mt-none mb-sm text-dark text-bold">AGENT ADERESS : <?php echo isset($bill_rslt[0]['agent_address']) ? $bill_rslt[0]['agent_address'] : "";?></h6>											
									<h6 class="h6 mt-none mb-sm text-dark text-bold">GRT OF VESSEL : <?php echo isset($bill_rslt[0]['grossRevenueTons']) ? $bill_rslt[0]['grossRevenueTons'] : "";?></h6>
									<h6 class="h6 mt-none mb-sm text-dark text-bold">
										US EX RATE : 
											<?php if (strpos(isset($bill_rslt[0]['exchangeRate']) ? $bill_rslt[0]['exchangeRate'] : "" , '.') !== false) {
												echo isset($bill_rslt[0]['exchangeRate']) ? $bill_rslt[0]['exchangeRate'] : "";
											} else {
												echo isset($bill_rslt[0]['exchangeRate']) ? $bill_rslt[0]['exchangeRate'] : "".".00";
											}?>
									</h6>
								</div>
								<div class="col-sm-4 col-md-offset-2 text-left mt-md mb-md text-bold">
									<h6 class="h6 mt-none mb-sm text-dark text-bold">BILL DATE : <?php echo DATE(isset($bill_rslt[0]['created']) ? $bill_rslt[0]['created'] : "");?></h6>											
									<h6 class="h6 mt-none mb-sm text-dark text-bold">CREATOR : <?php echo isset($bill_rslt[0]['creator']) ? $bill_rslt[0]['creator'] : "";?></h6>												
									<h6 class="h6 mt-none mb-sm text-dark text-bold">FLAG : <?php echo isset($bill_rslt[0]['flagcountry']) ? $bill_rslt[0]['flagcountry'] : "";?></h6>											
									<h6 class="h6 mt-none mb-sm text-dark text-bold">B.B/O.A.DATE : <?php echo isset($bill_rslt[0]['ffd']) ? $bill_rslt[0]['ffd'] : "";?></h6>											
									<h6 class="h6 mt-none mb-sm text-dark text-bold">
										 
										TIME :<?php echo isset($bill_rslt[0]['inboundpiloton']) ? $bill_rslt[0]['inboundpiloton'] : "";?> 
										TO : <?php echo isset($bill_rslt[0]['inboundpilotoff']) ? $bill_rslt[0]['inboundpilotoff'] : ""; ?> HRS
									</h6>
									<h6 class="h6 mt-none mb-sm text-dark text-bold">
										 
										TIME :<?php echo isset($bill_rslt[0]['onboundpiloton']) ? $bill_rslt[0]['onboundpiloton'] : "";?> 
										TO : <?php echo isset($bill_rslt[0]['onboundpilotoff']) ? $bill_rslt[0]['onboundpilotoff'] : "";?> HRS
									</h6>
									<h6 class="h6 mt-none mb-sm text-dark text-bold">DECK CARGO : <?php echo isset($bill_rslt[0]['cargo']) ? $bill_rslt[0]['cargo'] : "";?></h6>											
								</div>
							</div>
						</header>
						<div class="panel-body">
							<table class="table table-responsive table-hover mb-none">
								<thead>
									<tr class="gridDark">
										<th colspan="2" class="text-center">DESCRIPTION</th>
										<th class="text-center">A/C</th>									
										<th class="text-center">RATE USD</th>									
										<th class="text-center">BAS</th>									
										<th class="text-center">UNIT</th>									
										<th class="text-center">MOVE</th>									
										<th class="text-center">AMOUNT(US$)</th>									
										<th class="text-center">VAT(US$)</th>
									</tr>
								</thead>
								<tbody>
									<?php 
										$total=0;
										$usTotal=0;
										for($i=0; $i<count($bill_rslt); $i++) { ?>
											<tr class="gradeX">
												<td colspan="2" align="center"> <?php echo $bill_rslt[$i]['description'];?> </td>
												<td align="center"><?php echo $bill_rslt[$i]['glcode'];?></td>
												<td align="center">
													<?php if (strpos($bill_rslt[$i]['rateBilled'], '.') !== false) {
														echo $bill_rslt[$i]['rateBilled'];
													} else {
														echo $bill_rslt[$i]['rateBilled'].".00";
													}?>
												</td>
												<td align="center"><?php echo $bill_rslt[$i]['quantityUnit'];?></td>
												<td align="center"><?php echo $bill_rslt[$i]['quantityBilled'];?></td>
												<td align="center"><?php echo $bill_rslt[$i]['move'];?></td>
												<td align="center">
													<?php if (strpos($bill_rslt[$i]['totusd'], '.') !== false) {
														echo $bill_rslt[$i]['totusd'];
													} else {
														echo $bill_rslt[$i]['totusd'].".000";
													}?>
												</td>
												<td align="center">
													<?php if (strpos($bill_rslt[$i]['vatusd'], '.') !== false) {
														echo $bill_rslt[$i]['vatusd'];
													} else {
														echo $bill_rslt[$i]['vatusd'].".000";
													}?>
												</td>
											</tr>
									<?php 
										$total=$total+$bill_rslt[$i]['bdChraged'];
										$usTotal=$usTotal+$bill_rslt[$i]['totusd'];
									} 
									
									?>
										<tr align="center" class="gradeX">
											<th colspan="7" class="text-center">Total US$:</th>
											<th class="text-center">
												<?php if (strpos($usTotal, '.') !== false) {
													echo $usTotal;
												} else {
													echo $usTotal.".00";
												}?>
											</th>
											<th class="text-center">
												<?php  if (strpos(isset($bill_rslt[0]['vatusd']) ? $bill_rslt[0]['vatusd'] : "", '.') !== false) {
													echo isset($bill_rslt[0]['vatusd']) ? $bill_rslt[0]['vatusd'] : "";
												} else {
													echo isset($bill_rslt[0]['vatusd']) ? $bill_rslt[0]['vatusd'] : "".".00";
												}?>
											</th>
										</tr>
										<tr align="center" class="gradeX">
											<th colspan="7" class="text-center">TOTAL TK</th>
											<th class="text-center">
												<?php if (strpos($total, '.') !== false) {
													echo $total;
												} else {
													echo $total.".00";
												}?>
											</th>
											<th class="text-center">
												<?php  if (strpos(isset($bill_rslt[0]['bdVat']) ? $bill_rslt[0]['bdVat'] : "", '.') !== false) {
													echo isset($bill_rslt[0]['bdVat']) ? $bill_rslt[0]['bdVat'] : "";
												} else {
													echo isset($bill_rslt[0]['bdVat']) ? $bill_rslt[0]['bdVat'] : "".".00";
												}?>
											</th>
										</tr>
								</tbody>
							</table>
						</div>
						<div class="row">
							<div class="col-sm-12 mt-md">
								<p>&nbsp;</p>
							</div>
							<div class="col-sm-9 col-sm-offset-3 mt-md">
								<p><b>IN WORDS:- </b> <?php  echo numtowords($total)." Only";?></p>
							</div>
							<div class="col-sm-9 col-sm-offset-3 mt-md">
								<p><b>VAT:- </b> <?php $bdVat=isset($bill_rslt[0]['bdVat']) ? $bill_rslt[0]['bdVat'] : 0;  echo numtowords($bdVat)." Only";?></p>
							</div>
						</div>
						<div class="invoice-summary">
							<div class="row">
								<div class="col-sm-3">
									<table class="table h5 text-dark">
										<tbody>
											<tr class="b-top-none">
												<td colspan="2">&nbsp;</td>
												<td class="text-left">&nbsp;</td>
											</tr>
											<tr>
												<td colspan="2">COMPUTER OPERATOR</td>
												<td class="text-left"></td>
											</tr>
										</tbody>
									</table>
								</div>
								<div class="col-sm-2 col-md-offset-2">
									<table class="table h5 text-dark">
										<tbody>
											<tr class="b-top-none">
												<td colspan="2">&nbsp;</td>
												<td class="text-left">&nbsp;</td>
											</tr>
											<tr>
												<td colspan="2">CHECKED BY</td>
												<td class="text-left"></td>
											</tr>
										</tbody>
									</table>
								</div>
								<div class="col-sm-2 col-md-offset-2">
									<table class="table h5 text-dark">
										<tbody>
											<tr class="b-top-none">
												<td colspan="2">&nbsp;</td>
												<td class="text-left">&nbsp;</td>
											</tr>
											<tr>
												<td colspan="2">FOR CF & AC</td>
												<td class="text-left"></td>
											</tr>
										</tbody>
									</table>
								</div>
							</div>
							<div class="row">
								<div class="col-sm-12 mt-md">
									<p>&nbsp;</p>
								</div>
								<div class="col-sm-12 mt-md">
									<p>
									BILL DATE:-  <?php echo DATE(isset($bill_rslt[0]['created']) ? $bill_rslt[0]['created'] : "");?><br>
									PRINT DATE:- <?php echo date("D M j G:i:s"); ?>
									</p>
								</div>
								<div class="col-sm-12 mt-md">
									<p>
										Note: Water Supply Charge calculated by per 1000 Litres
									</p>
								</div>
							</div>
						</div>
						<div class="text-right mr-lg">
							<form class="form-horizontal form-bordered" method="POST" action="<?php echo site_url('Report/viewBillPrint') ?>" target="_blank">
									
								<input type="hidden" name="draftNumber" id="draftNumber" value="<?php echo $draftNumber?>" />
								<input type="hidden" name="cnt_code" id="cnt_code" value="<?php echo $cnt_code?>" />
								<input type="hidden" name="bill_type" id="bill_type" value="<?php echo $bill_type?>" />
								
								<button type="submit" class="mb-xs mt-xs mr-xs btn btn-success"><i class="fa fa-print"></i> Print</button>
							</form>
						</div>
					</div>
				</div>
			</section>
			<!-- end: page -->
	</div>
<?php function numtowords($num){ 
$decones = array( 
            '01' => "One", 
            '02' => "Two", 
            '03' => "Three", 
            '04' => "Four", 
            '05' => "Five", 
            '06' => "Six", 
            '07' => "Seven", 
            '08' => "Eight", 
            '09' => "Nine", 
            10 => "Ten", 
            11 => "Eleven", 
            12 => "Twelve", 
            13 => "Thirteen", 
            14 => "Fourteen", 
            15 => "Fifteen", 
            16 => "Sixteen", 
            17 => "Seventeen", 
            18 => "Eighteen", 
            19 => "Nineteen" 
            );
$ones = array( 
            0 => " ",
            1 => "One",     
            2 => "Two", 
            3 => "Three", 
            4 => "Four", 
            5 => "Five", 
            6 => "Six", 
            7 => "Seven", 
            8 => "Eight", 
            9 => "Nine", 
            10 => "Ten", 
            11 => "Eleven", 
            12 => "Twelve", 
            13 => "Thirteen", 
            14 => "Fourteen", 
            15 => "Fifteen", 
            16 => "Sixteen", 
            17 => "Seventeen", 
            18 => "Eighteen", 
            19 => "Nineteen" 
            ); 
$tens = array( 
            0 => "",
            2 => "Twenty", 
            3 => "Thirty", 
            4 => "Forty", 
            5 => "Fifty", 
            6 => "Sixty", 
            7 => "Seventy", 
            8 => "Eighty", 
            9 => "Ninety" 
            ); 
$hundreds = array( 
            "Hundred", 
            "Thousand", 
            "Million", 
            "Billion", 
            "Trillion", 
            "Quadrillion" 
            ); //limit t quadrillion 
$num = number_format($num,2,".",","); 
$num_arr = explode(".",$num); 
$wholenum = $num_arr[0]; 
$decnum = $num_arr[1]; 
$whole_arr = array_reverse(explode(",",$wholenum)); 
krsort($whole_arr); 
$rettxt = ""; 
foreach($whole_arr as $key => $i){ 
    if($i < 20){ 
        $rettxt .= $ones[$i]; 
    }
    elseif($i < 100){ 
        $rettxt .= $tens[substr($i,0,1)]; 
        $rettxt .= " ".$ones[substr($i,1,1)]; 
    }
    else{ 
        $rettxt .= $ones[substr($i,0,1)]." ".$hundreds[0]; 
        //$rettxt .= " ".$tens[substr($i,1,1)]; 
        $rettxt .= " ".$ones[substr($i,2,1)]; 
    } 
    if($key > 0){ 
        $rettxt .= " ".$hundreds[$key]." "; 
    } 

} 
$rettxt = $rettxt." Taka";

if($decnum > 0){ 
    $rettxt .= " and "; 
    if($decnum < 20){ 
        $rettxt .= $decones[$decnum]; 
    }
    elseif($decnum < 100){ 
        $rettxt .= $tens[substr($decnum,0,1)]; 
        $rettxt .= " ".$ones[substr($decnum,1,1)]; 
    }
    $rettxt = $rettxt." Paisa"; 
} 
return $rettxt;} ?>

<script>
	window.print();
</script>