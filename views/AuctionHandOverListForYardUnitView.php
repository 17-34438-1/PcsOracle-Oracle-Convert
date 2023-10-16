
	<?php
	include("mydbPConnection.php");
	include("dbConection.php");
	$counter=count($result);
	$j=0;
	
	
	$agent="";
	$rl_no="";
	$rl_date="";
	$remarks="";
	
	for($i=0;$i<count($result);$i++)		// main loop
		{			
				$rl_no = $result[$i]['rl_no'];
				$rl_date = $result[$i]['rl_date'];
				$obpc_number = $result[$i]['obpc_number'];
	
				$obpc_date = $result[$i]['obpc_date'];
				
				$remarks = $result[$i]['remarks'];
				$blNo = $result[$i]['bl_no'];
				$house_bl = $result[$i]['houseBL'];
				$master_bl = $result[$i]['masterBL'];
				$rotation = $result[$i]['rotation_no'];
				$pack_Marks_Number = $result[$i]['pack_Marks_Number'];
				$description_of_Goods = $result[$i]['description_of_Goods'];
				$notify_name = $result[$i]['Notify_name_'];
				$Notify_address = $result[$i]['Notify_sup_name_'];
				$mlocode = $result[$i]['mlocode'];
				$ff_name = $result[$i]['ff_name'];
				$ff_addr = $result[$i]['ff_addr'];
				$quantity = $result[$i]['quantity'];
				}
		?>
		

	<body>	
	<table border="0" align="center" width="100%" style="border-collapse:collapse;margin-bottom:10px;">
		<tr>
			<td align="center" colspan="3">
				<font size="6"><strong>টার্মিনাল ম্যানেজার এর দপ্তর</strong></font><br>
				<font size="4"><strong>ইউনিট  : <?php echo  $section; ?></strong></font><br>
			</td>
		</tr>
		<tr>
			<td style="width:50%">নং-পপ/টিএম/ইউ-০১/অকশনকনটে/নি:হেফাজত/২০২৩/২২তারিখ:</td>			
		</tr>	
		<tr>
			<td style="width:50%"><br/></td>			
		</tr>
		<tr>
			<td style="width:50%">বরাবর, <br/>
				সহকারী টার্মিনাল ম্যানেজার (শি: এন্ড ই:)  <br/>
				চট্টগ্রাম বন্দর কর্তৃপক্ষ </td>			
		</tr>
		<tr>
			<td style="width:50%"><br/></td>			
		</tr>
		<tr>
			<td>  বিষয়: ৩০ দিনের অধিক পড়ে থাকা এফ সি এল কন্টেনার সমূহ ওয়ানস্টপ সার্ভিস সেন্টার থেকে প্রাপ্ত আর এল মুলে নিরাপত্তা হেফাজতে রাখার প্রয়োজনীয়  ব্যবস্তা গ্ৰহণ করার অনুরোধ করা হল নিম্নে কন্টেনার সমূহ বিপরীতে তথ্য প্রেরণ করা হল। 
			</td>			
		</tr>
		<tr>
			<td> জনাব, <br/>
				আপনারসদয় অবগতি ও প্রয়োজনীয় ব্যবস্থা গ্রহণের জন্য জানানো যাচ্ছে যে, ৩০ দিনের অধিক অবস্থানরত কন্টেইনারসমূহ নিরাপত্তা হেফাজতে সংরক্ষণ করা হয়েছে। কন্টেইনার বাস্তব পরিসংখ্যান তালিকা নিম্মরূপ: 
			</td>			
		</tr>
	</table>
	<table border="1" align="center" width="100%" style="border-collapse:collapse;">
		<thead>
			<tr>
				<th class="text-center">SL</th>
				<th class="text-center">Master BL</th>
				<th class="text-center">House BL</th>
				<th class="text-center">Container No</th>
				<th class="text-center">Size</th>
				<th class="text-center">Height</th>
				<th class="text-center">MLO</th>
				<th class="text-center">Gross Wt</th>
				<th class="text-center">Status</th>
				<th class="text-center">RL No</th>
				<th class="text-center">RL Date</th>
				<th class="text-center">OPBC No</th>
				<th class="text-center">OPBC Date</th>
				<th class="text-center">Yard</th>			
			</tr>
			
		<tbody>
			<?php
				
				$agent="";
				$rl_no="";
				$rl_date="";
				$remarks="";
				
				for($i=0;$i<count($result);$i++)		// main loop
				{


					$remarks = $result[$i]['remarks'];
					$blNo = $result[$i]['bl_no'];
					$house_bl = $result[$i]['houseBL'];
					$master_bl = $result[$i]['masterBL'];
					$rotation = $result[$i]['rotation_no'];
					$cont = $result[$i]['cont'];
					$size = $result[$i]['size'];
					$height = $result[$i]['height'];
					$weight = $result[$i]['weight'];
					$cont_status = $result[$i]['cont_status'];
					$rl_no = $result[$i]['rl_no'];
					$rl_date = $result[$i]['rl_date'];
					$obpc_number = $result[$i]['obpc_number'];
					$obpc_date = $result[$i]['obpc_date'];
					$terminal = $result[$i]['terminal'];
					$block = $result[$i]['block'];
		?>			
 				
				<tr align="center">
					<td align="center" ><?php echo $i+1; ?></td>
					<td align="center" ><?php echo $master_bl; ?></td>
					<td align="center" ><?php echo $house_bl; ?></td>
					<td align="center" ><?php echo $cont; ?></td>
					<td align="center" ><?php echo $size; ?></td>
					<td align="center" ><?php echo $height; ?></td>
					<td align="center" ><?php echo $mlocode; ?></td>
					<td align="center" ><?php echo $weight; ?></td>
					<td align="center" ><?php echo $cont_status; ?></td>
					<td align="center" ><?php echo $rl_no; ?></td>
					<td align="center" ><?php echo $rl_date; ?></td>
					<td align="center" ><?php echo $obpc_number; ?></td>
					<td align="center" ><?php echo $obpc_date; ?></td>
					<td align="center" ><?php echo $terminal.'('.$block.')'; ?></td>
				
				</tr>
			
			<?php } ?>	

			
		</tbody>
		
	</table>
	
	<br/>
	<br/>

	<table  border="1" align="center" width="100%" style="border-collapse:collapse;">
		<tr>
				<th>Yard Name:</th>
			<?php
		 	for($k=0; $k < count($summary_result);$k++)		
				{ 
					$block=$summary_result[$k]['block_cpa']; ?>
					
					<th><?php echo $block; ?></th>
						
			<?php } ?>
		</tr>
		<tr>
				<th> 20'</th>
			<?php
		 	for($k=0; $k < count($summary_result);$k++)		
				{ 
					$fcl_20=$summary_result[$k]['fcl_20']; ?>					
					 <td align="center"><?php echo $fcl_20; ?></td>						
			<?php } ?>
		</tr>	
		<tr>
				<th> 40'</th>
			<?php
		 	for($k=0; $k < count($summary_result);$k++)		
				{ 
					$fcl_40=$summary_result[$k]['fcl_40']; ?>					
					 <td align="center"><?php echo $fcl_40; ?></td>						
			<?php } ?>
		</tr>	
	</table>
	<?php //mysqli_close($con_cchaportdb); ?>
	<script>
		//window.print();
	</script>
</body>
</div>
<?php
		
		
		mysqli_close($con_cchaportdb);
?>

	