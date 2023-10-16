	<div class="panel-body">
		<div class="form-group">
			<div class="col-md-1">
				<div class="input-group mb-md">
					<img align="center" width="150px" style="margin:0px;padding:0px;" height="90px" src="<?php echo ASSETS_PATH?>images/cpa_logo.png">

				</div>
			</div>
			<div class="col-md-1">
				<table align="center">
					<tr>
						<td align="center" style="font-family: ind_bn_1_001"><font size=4 >চট্টগ্রাম বন্দর কর্তৃপক্ষ</font>
								<br/>নৌ বিভাগ 
						</td>
					</tr>
				</table>
			
			</div>
		</div>
			
							
			<table width=100%>
				<tr><td style="font-family: ind_bn_1_001">নং :  <?php echo $fileNo; ?></td></tr>
				<tr><td align="right" style="font-family: ind_bn_1_001"> তারিখ :  <?php echo $filedt; ?>&nbsp;&nbsp;&nbsp;</td></tr>

				<tr>
					<td align="left" style="font-family: ind_bn_1_001">
					বরাবরে  <br/>
					উপ-প্রধান অর্থ ও হিসাব রক্ষণ কর্মকর্তা <br/>
					চট্টগ্রাম বন্দর কর্তৃপক্ষ <br/>
					চট্টগ্রাম । <br/>
					বিষয়: <?php echo $filesub; ?><br/>
					</td>
				</tr>
				<tr><td> <br/></td></tr>
			
				 <br/>
				<tr>
					<td align="left" style="font-family: ind_bn_1_001">
						উপরোক্ত বিষয়ে জানানো যাচ্ছে যে , অত্র বিভাগের বার্থিং শাখায় জমাকৃত বাণিজ্যিক জাহাজের  &nbsp;<?php echo $no_vsl;?> &nbsp; টি পাইলটিং এর আগমন নির্গমন  পেপার পরবর্তী প্রয়োজনীয় ব্যবস্থা গ্রহণের জন্য এতদসঙ্গে সংযুক্ত করে প্রেরণ করা হল। 
					</td>
				</tr>
			</table>

				<table border="1" style="border-collapse:collapse" width=100%>
						
						<thead>
							<tr>							
								<th class="text-center">SL</th>
								<th class="text-center">NAME OF THE SHIP</th>
								<th class="text-center">ARRIVAL DATE</th>		
							</tr>

							
						</thead>

					<?php 
							for($i=0;$i<count($departData);$i++){
						?>

						<tr align="center">
	
							<td><?php echo $i+1;?></td>
							<td><?php echo $departData[$i]['vsl_name'];?></td>												
							<td><?php echo $departData[$i]['ata'];?></td>												
						
												
						</tr>
						
						<?php 
							} 
						?>
											
					</table>	

				
					<table width=100%>				
						<tr>
							<td align="left" style="font-family: ind_bn_1_001">
								এতদসঙ্গে সংশ্লিষ্ট শিপিং এজেন্ট এর অনুকূলে প্রেরিত বিলের  &nbsp;<?php echo $no_vsl;?>&nbsp;    কপি অনুলিপি অত্র দপ্তরে সংরক্ষণের জন্য প্রেরণের অনুরোধ করা হলো।  <br/>
								<u>সংযুক্ত : &nbsp;&nbsp;<?php echo $no_vsl;?>&nbsp;সেট পাইলটেজ পেপার।</u>
						</tr>
						<tr><td> <br/></td></tr>
						<tr>
							<td align="right" style="font-family: ind_bn_1_001">
									হারবার মাস্টার &nbsp;&nbsp;&nbsp;&nbsp; <br/>
								<u>চট্টগ্রাম বন্দর কর্তৃপক্ষ</u> 
							</td>
						</tr>
						
					</table>

<script>
	window.print();
</script>