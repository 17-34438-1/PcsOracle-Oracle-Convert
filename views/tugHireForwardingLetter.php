<table border="0" align="center" width="90%" style="border-collapse:collapse; margin-top:20px">
	<tr>
		<td width="20%" rowspan="4">
			<img align="center" width="150px" style="margin:0px;padding:0px;" height="90px" src="<?php echo ASSETS_PATH?>images/cpa_logo.png">
		</td>
		<td width="80%" align="center" style="font-size:20px"><b> বন্দর কর্তৃপক্ষ </b></td>
	</tr>
	<tr><td width="80%" align="center" style="font-size:15px"><b> নৌ বিভাগ</b></td></tr>
	<tr>
		<td width="80%" align="center" style="font-size:15px">
			<b> বন্দর ভবন, পোষ্ট বক্স নং-২০১৩, বন্দর, চট্টগ্রাম-৪১০০, বাংলাদেশ।</b>
		</td>
	</tr>
	<tr>
		<td width="80%" align="center" style="font-size:15px">
			<b> পোর্ট বিজে, ফ্যাক্স : ৮৮-০৩১-২৫১০৮৮৯, ফোন : পিএবিএক্সঃ ০৩১-২৫২২২০০-২৯। </b>
		</td>
	</tr>
</table>
<hr style="border-bottom: 1px solid black;display:none;" width="100%">
<table border="0" align="center" width="90%" style="border-collapse:collapse;margin-top:10px;">
	<tr>
		<td width="50%">
			প্রধান অর্থ ও হিসাব রক্ষণ কর্মকর্তা  
		</td>
		<td width="50%" align="right"> তারিখ : <?php echo Bengali_DTN(date('d/m/Y', strtotime($forward_date))); ?></td>
	</tr>
	<tr>
		<td width="50%" colspan="2">
			চট্টগ্রাম বন্দর কর্তৃপক্ষ<br/>
			চট্টগ্রাম ।<br/>
		</td>
	</tr>
</table>
<table border="0" align="center" width="90%" style="border-collapse:collapse;margin-top:10px;">
	<tr><td><p style="margin-left: 50px;"><u><b> বিষয়: নৌযান ব্যবহারের তালিকা প্রেরণ প্রসঙ্গে । </b></u></p></td></tr>
</table>
<table border="0" align="center" width="90%" style="border-collapse:collapse;margin-top:10px;">
	<tr>
		<td>
			<p>
				<span style="margin-left: 50px;">উপরোক্ত </span>বিষয়ে অত্র বিভাগের নৌযান কর্তৃক বিভিন্ন এজেন্সীর অধীনে সম্পাদিত কার্য সম্বলিত বিবরণ যথাযথ ভাবে লিপিবদ্ধ করে পোর্ট মাশুল বই নং-৯৯ এর ক্রমিক নং-৪৯০২-৪৯০৪ পরবর্তী প্রয়োজনীয় ব্যবস্থা গ্রহণের জন্য এতদসঙ্গে সংযুক্ত করে প্রেরণ করা হলো।
			</p>
		</td>
	</tr>
</table>
<table border="0" align="center" width="90%" style="border-collapse:collapse;margin-top:20px;">
	<tr>
		<td>
			সংযুক্তি :-
			<ul type="none">
				<li>১। নৌযান ব্যবহারের তালিকা ( ২x৩) ৬ পাতা ।</li>
				<li>২। পোর্টমাণ্ডল ৪৯০২-৪৯০৪ পাতা।</li>
			</ul>
		</td>
	</tr>
</table>
<table border="0" align="center" width="90%" style="border-collapse:collapse;margin-top:30px;">
	<tr>
		<td width="80%">&nbsp;</td>
		<td align="center" width="20%">
			<img height="50px" width="120px" src="http://cpatos.gov.bd/tosapi/ahm_pilot_signature/HMaster.png"/>
		</td>
	</tr>
	<tr>
		<td width="80%">&nbsp;</td>
		<td align="center" width="20%">
			হারবার মাস্টার<br/>
			<u>চট্টগ্রাম বন্দর কর্তৃপক্ষ</u>
		</td>
	</tr>
</table>
<table border="0" align="center" width="90%" style="border-collapse:collapse;margin-top:20px;">
	<tr>
		<td>
			অনুলিপি :-
			<ul type="none">
				<li>১। প্রধান নিরীক্ষা কর্মকর্তা/চবক এর অবগতির জন্য ।</li>
			</ul>
		</td>
	</tr>
</table>
<table border="0" align="center" width="90%" style="border-collapse:collapse;margin-top:30px;">
	<tr>
		<td width="80%">&nbsp;</td>
		<td align="center" width="20%">
			<img height="50px" width="120px" src="http://cpatos.gov.bd/tosapi/ahm_pilot_signature/HMaster.png"/>
		</td>
	</tr>
	<tr>
		<td width="80%">&nbsp;</td>
		<td align="center" width="20%">
			হারবার মাস্টার<br/>
			<u>চট্টগ্রাম বন্দর কর্তৃপক্ষ</u>
		</td>
	</tr>
</table>

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