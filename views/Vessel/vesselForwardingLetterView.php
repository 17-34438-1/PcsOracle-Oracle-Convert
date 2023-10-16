<div class="panel-body">
		<div class="form-group">
			<div class="col-md-1">
				<div class="input-group mb-md" align="center">
					<img align="center" width="150px" style="margin:0px;padding:0px;" height="90px" src="<?php echo ASSETS_PATH?>images/cpa_logo.png">

				</div>
			</div>
			<div class="col-md-1">
				<table align="center">
					<tr>
						<td align="center" style="font-family: ind_bn_1_001"><font size=4 ><u>চট্টগ্রাম বন্দর কর্তৃপক্ষ</u></font>
								<br/> 
								<?php 
									$org_Type_id = $this->session->userdata('org_Type_id');
									if($org_Type_id == '82'){
										echo "<u>হিসাব বিভাগ</u>";
									}else{
										echo "<u>নৌ বিভাগ</u>";
									}
								?>
						</td>
					</tr>

					<?php
						if($action == "Vatiary" || $action == "Kutubdia"){
					?>
					<tr>
						<td align="center" style="font-family: ind_bn_1_001">
							<u>
								টেলেক্সঃ ৬৭৬২৫৬ পোর্ট বিজে, ফ্যাক্সঃ- ৮৮-০৩১-২৫১০৮৮৯, ফোনঃ পি এ বি এক্সঃ ২৫২২২০০-৯৯
							</u>	
						</td>
					</tr>
					<?php
						}
					?>

				</table>
			
			</div>
		</div>
			
							
			<table width=100%>
				<tr><td style="font-family: ind_bn_1_001">নং :  <?php echo $fileNo; ?></td></tr>
				<tr><td align="right" style="font-family: ind_bn_1_001"> তারিখঃ  <?php echo $filedt; ?>&nbsp;&nbsp;&nbsp;</td></tr>

				<tr>
					<td align="left" style="font-family: ind_bn_1_001">
					বরাবরে  <br/>
					উপ-প্রধান অর্থ ও হিসাব রক্ষণ কর্মকর্তা <br/>
					চট্টগ্রাম বন্দর কর্তৃপক্ষ <br/>
					চট্টগ্রাম । <br/><br/>
					বিষয়ঃ <?php echo $filesub; ?><br/>
					</td>
				</tr>
				<tr><td> <br/></td></tr>
			
				 <br/>
				<tr>
					<td align="left" style="font-family: ind_bn_1_001">
					<?php
						if($action == "Vatiary" || $action == "Kutubdia"){
					?>
						<!-- __/__/____ ই; হতে __/__/____ ই; বন্দর <?php // echo $action == "Vatiary"?"ভাটিয়ারী":"কুতুবদিয়া"; ?> হতে চলিয়া -->
						<?php echo $action=="Vatiary"? substr($filesub,0,126):substr($filesub,0,128); ?> যাওয়া সংক্রান্ত জাহাজের বিবরণ সম্বলিত এক খানা তালিকা অর্থ ও হিসাব বিভাগ পরবর্তী ব্যবস্থা গ্রহণের জন্য এতদসঙ্গে সংযুক্ত করে প্রেরণ করা হল।
					<?php
						}
						else
						{
					?>
					উপরোক্ত বিষয়ে জানানো যাচ্ছে যে , অত্র বিভাগের বার্থিং শাখায় জমাকৃত বাণিজ্যিক জাহাজের  &nbsp;<?php echo $no_vsl;?> &nbsp; টি পাইলটিং এর আগমন নির্গমন  পেপার পরবর্তী প্রয়োজনীয় ব্যবস্থা গ্রহণের জন্য এতদসঙ্গে সংযুক্ত করে প্রেরণ করা হল। 
					<?php
						}
					?>
					</td>
				</tr>
			</table>
				
				<br/>
				
				<table border="1" style="border-collapse:collapse" width=100%>
						
						<thead>
							<tr>							
								<th class="text-center">SL</th>
								<th class="text-center">VESSEL NAME</th>
								<th class="text-center">ROTATION</th>
								<th class="text-center">ARRIVAL DATE</th>		
								<th class="text-center">DEPARTURE DATE</th>		
							</tr>
						</thead>

					<?php 
							for($i=0;$i<count($departData);$i++){
						?>

						<tr align="center">
	
							<td><?php echo $i+1;?></td>
							<td><?php echo $departData[$i]['vsl_name'];?></td>												
							<td><?php echo $departData[$i]['ib_vyg'];?></td>												
							<td><?php echo $departData[$i]['ata'];?></td>												
							<td><?php echo $departData[$i]['atd'];?></td>												
						
												
						</tr>
						
						<?php 
							} 
						?>
											
					</table>	

					<br/>
				
					<table width=100%>				
						<tr>
							<td align="left" style="font-family: ind_bn_1_001">
								<?php
									if($action == "Vatiary" || $action == "Kutubdia"){
										echo "";
									}else{
								?>
								এতদসঙ্গে সংশ্লিষ্ট শিপিং এজেন্ট এর অনুকূলে প্রেরিত বিলের  &nbsp;<?php echo $no_vsl;?>&nbsp; কপি অনুলিপি অত্র দপ্তরে সংরক্ষণের জন্য প্রেরণের অনুরোধ করা হলো।  <br/>
								<?php
									}
								?>
								<u>সংযুক্ত : 
									<?php 
										if($action == "Vatiary" || $action == "Kutubdia"){ 
									?>
										বর্ণনামতে
									<?php
										}else{
									?>
										&nbsp;&nbsp;<?php echo $no_vsl;?>&nbsp;সেট পাইলটেজ পেপার।
									<?php 
										} 
									?>
								</u>
						</tr>
						<tr><td> <br/></td></tr>
						
					</table>
					
					<?php
						if($action == "Vatiary" || $action == "Kutubdia")
						{
					?>

					<table width=100%>
						<tr>
							<td align="right" style="font-family: ind_bn_1_001">
									স্বাক্ষরিত &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<br/>
									হারবার মাস্টার &nbsp;&nbsp;&nbsp;&nbsp; <br/>
								<u>চট্টগ্রাম বন্দর কর্তৃপক্ষ</u> 
							</td>
						</tr>
						<tr><td> <br/><br/></td></tr>
						<tr>
							<td align="left" style="font-family: ind_bn_1_001">
								অনুলিপিঃ<br/>
								১। প্রধান নিরীক্ষা কর্মকর্তা / চবক বর্ণিত জাহাজের একখানা তালিকা প্রয়োজনীয় ব্যবস্থা গ্রহণের জন্য এতদসঙ্গে সংযুক্ত করা হল।<br/>
								২। উদ্বর্তন নিরীক্ষা কর্মকর্তা / চবক এর অবগতির জন্য।<br/>
								৩। বারথিং শাখা (প্রয়োজনীয় ব্যবস্থা গ্রহণের জন্য)
							</td>
						</tr>
					</table>

					<?php
						}
					?>

					<table width=100%>
						<tr>
							<td align="right" style="font-family: ind_bn_1_001">
								<img height="50px" width="120px" src="http://cpatos.gov.bd/tosapi/ahm_pilot_signature/HMaster.png"/>&nbsp;&nbsp;&nbsp; <br/>
									হারবার মাস্টার &nbsp;&nbsp;&nbsp;&nbsp; <br/>
								<u>চট্টগ্রাম বন্দর কর্তৃপক্ষ</u> 
							</td>
						</tr>
					</table>

<!-- <script>
	window.print();
</script> -->