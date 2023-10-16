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
					<tr>
						<td align="center" style="font-family: ind_bn_1_001">
							<u>
								টেলেক্সঃ ৬৭৬২৫৬ পোর্ট বিজে, ফ্যাক্সঃ- ৮৮-০৩১-২৫১০৮৮৯, ফোনঃ পি এ বি এক্সঃ ২৫২২২০০-৯৯
							</u>	
						</td>
					</tr>
				</table>
			
			</div>
		</div>
			
							
			<table width=100%>
				<tr><td style="font-family: ind_bn_1_001">নং :  <?php echo Bengali_DTN($fileNo); ?></td></tr>
				<tr><td align="right" style="font-family: ind_bn_1_001"> তারিখ :  <?php echo Bengali_DTN($filedt); ?>&nbsp;&nbsp;&nbsp;</td></tr>

				<tr>
					<td align="left" style="font-family: ind_bn_1_001">
					বরাবরে  <br/>
					উপ-প্রধান অর্থ ও হিসাব রক্ষণ কর্মকর্তা <br/>
					চট্টগ্রাম বন্দর কর্তৃপক্ষ <br/>
					চট্টগ্রাম । <br/><br/><br/>
					বিষয়: <u><?php echo Bengali_DTN($filesub); ?></u><br/>
					</td>
				</tr>
				<tr><td> <br/></td></tr>
			
				 <br/>
				<tr>
					<td align="left" style="font-family: ind_bn_1_001">
						<?php echo "গত ".Bengali_DTN(substr($filesub,0,39)); ?> ইং পর্যন্ত বন্দর বহিনোঙ্গর হতে যে সকল জাহাজ বিচিং এর উদ্দেশ্যে বিচিং ইয়ার্ডে গমণ করেছে সে সকল জাহাজের (০১) পাতার এক খানা তালিকা অর্থ ও হিসাব বিভাগে পরবর্তী প্রয়োজনীয় ব্যবস্থা গ্রহণের জন্য এতদসঙ্গে সংযুক্ত করে প্রেরণ করা হল। 
					</td>
				</tr>
			</table>

				<!-- <table border="1" style="border-collapse:collapse" width=100%>
						
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
						//for($i=0;$i<count($departData);$i++){
					?>

					<tr align="center">

						<td><?php //echo $i+1;?></td>
						<td><?php //echo $departData[$i]['vsl_name'];?></td>	
						<td><?php //echo $departData[$i]['ib_vyg'];?></td>																			
						<td><?php //echo $departData[$i]['ata'];?></td>												
						<td><?php //echo $departData[$i]['atd'];?></td>												
					
											
					</tr>
					
					<?php 
						//} 
					?>
										
				</table>	 -->

				<br/><br/>
					<table width=100%>				
						<tr>
							<td align="left" style="font-family: ind_bn_1_001">
								<!-- এতদসঙ্গে সংশ্লিষ্ট শিপিং এজেন্ট এর অনুকূলে প্রেরিত বিলের  &nbsp;<?php //echo $no_vsl;?>&nbsp;    কপি অনুলিপি অত্র দপ্তরে সংরক্ষণের জন্য প্রেরণের অনুরোধ করা হলো।  <br/><br/> -->
								সংযুক্তঃ বর্ণনামতে পাতা (__) __ খানা তালিকা
						</tr>
						<tr><td> <br/></td></tr>
						<!-- <tr>
							<td align="right" style="font-family: ind_bn_1_001">
									হারবার মাস্টার &nbsp;&nbsp;&nbsp;&nbsp; <br/>
								<u>চট্টগ্রাম বন্দর কর্তৃপক্ষ</u> 
							</td>
						</tr> -->
					</table>

					<table width=100%>
						<tr>
							<td align="right" style="font-family: ind_bn_1_001">
									<!-- স্বাক্ষরিত &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<br/> -->
									<img height="50px" width="120px" src="http://cpatos.gov.bd/tosapi/ahm_pilot_signature/HMaster.png"/>&nbsp;&nbsp;&nbsp; <br/>
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
								৩। বার্থিং শাখা (প্রয়োজনীয় ব্যবস্থা গ্রহণের জন্য)
							</td>
						</tr>
					</table>

					<table width=100%>
						<tr>
							<td align="right" style="font-family: ind_bn_1_001">
							<img height="50px" width="120px" src="http://cpatos.gov.bd/tosapi/ahm_pilot_signature/HMaster.png"/> &nbsp;&nbsp;<br/>
									হারবার মাস্টার &nbsp;&nbsp;&nbsp;&nbsp; <br/>
								<u>চট্টগ্রাম বন্দর কর্তৃপক্ষ</u> 
							</td>
						</tr>
					</table>

					<!-- <table width=100%>	
						<tr>
							<td align="center">
								<?php //if(count($departData)>0 )  { ?> <img height="50px" width="150px" src="http://cpatos.gov.bd/tosapi/ahm_pilot_signature/<?php //echo '23026';?>.png"/> <?php  //} else { echo ""; } ?>
							</td>

							<td align="center">
							<?php //if(count($departData)>0 )  { ?> <img height="50px" width="150px" src="http://cpatos.gov.bd/tosapi/ahm_pilot_signature/<?php //echo '24087';?>.png"/> <?php  //} else { echo ""; } ?>
							</td>

							<td align="center">
							<?php //if(count($departData)>0 )  { ?> <img height="50px" width="150px" src="http://cpatos.gov.bd/tosapi/ahm_pilot_signature/<?php //echo '12369';?>.png"/> <?php  //} else { echo ""; } ?>
							</td>

							<td align="center">
							<?php //if(count($departData)>0 )  { ?> <img height="50px" width="150px" src="http://cpatos.gov.bd/tosapi/ahm_pilot_signature/<?php //echo 'HMaster';?>.png"/> <?php  //} else { echo ""; } ?>
							</td>
						</tr>

						<tr>
							<td align="center">
								<p><u>INITIATOR</u></p>
							</td>

							<td align="center">
								<p><u>SR.VTSSO/VTMIS</u></p>
							</td>

							<td align="center">
								<p><u>SUPDT(B)</u></p>
							</td>

							<td align="center">
								<p><u>HM/CPA</u></p>
							</td>
						</tr>

					</table> -->

<!-- <script>
	window.print();
</script> -->

<?php

function Bengali_DTN($NRS)
{
	$englDTN = array
		('1','2','3','4','5','6','7','8','9','0',
		'Saturday','Sunday','Monday','Tuesday','Wednesday','Thursday','Friday',
		'Sat','Sun','Mon','Tue','Wed','Thu','Fri',
		'am','pm','at','st','nd','rd','th',
		'January','February','March','April','May','June','July','August','September','October','November','December',
		'Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec',
		'2022','2023','2024','2025','2026','2027','2028','2029','2030');
	$bangDTN = array
		('১','২','৩','৪','৫','৬','৭','৮','৯','০',
		'শনিবার','রবিবার','সোমবার','মঙ্গলবার','বুধবার','বৃহস্পতিবার','শুক্রবার',
		'শনি','রবি','সোম','মঙ্গল','বুধ','বৃহঃ','শুক্র',
		'পূর্বাহ্ণ','অপরাহ্ণ','','','','','',
		'জানুয়ারি','ফেব্রুয়ারি','মার্চ','এপ্রিল','মে','জুন','জুলাই','আগস্ট','সেপ্টেম্বর','অক্টোবর','নভেম্বর','ডিসেম্বর',
		'জানু','ফেব্রু','মার্চ','এপ্রি','মে','জুন','জুলা','আগ','সেপ্টে','অক্টো','নভে','ডিসে',
		'২০২২','২০২৩','২০২৪','২০২৫','২০২৬','২০২৭','২০২৮','২০২৯','২০৩০');

		$converted = str_replace($englDTN, $bangDTN, $NRS);
		return $converted; 
}

?>