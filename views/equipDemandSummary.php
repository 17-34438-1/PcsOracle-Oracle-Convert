<html>
	<head>
		<title>RELEASE ORDER FOR DELEVERY</title>
		<style>
			 table {border-collapse: collapse;}
			 .left{
					width:300px;
					float:left;
					height:100%;
				}
				.middle{
					margin-left:20px;
					width:300px;
					float:left;
					height:100%;
				}
				.right{
					margin-left:20px;
				}
				
				#borderDiv{
					border-bottom:1px dotted red;
				}

                th, td {
                    padding: 3px;
                }
				
				@media print {
							@page { 
									size: auto;/* auto is the initial value */
									margin: 5px;
									}
							@page port { size: portrait; }
							  .portrait { page: port; page-break-after: always;}
							@page land { size: landscape; }
							  .landscape { page: land;											
											-webkit-transform: rotate(-90deg) scale(.75,1.15);
											-moz-transform: rotate(-90deg) scale(.80);											
											zoom: 90%;	
											width: 100%;
											height: 100%;
										}       
							body  { margin: 0 cm;}
						.breakPage { page-break-after: auto; }
						.left{
							width:350px;
							float:left;
							height:100%;
						}
						.middle{
							margin-left:20px;
							width:350px;
							float:right;
							height:100%;
						}
						div.fixed {
							position: absolute;
							bottom: 0;
							right: 0;
							width: 100%;
							align:left;
						}
						
						#borderDiv{
							border-bottom:none;
						}
					}
		</style>
	</head>
	<body>
        <div class="container"> <!-- style="padding:20px;" -->
            <table width="100%" style="background-color:#ecedf0">
                <tr>
                    <td width="35%" align="center" valign="middle">
                        <img width="125px" height="80px" src="http://122.152.54.185:80/PcsOracle/assets/images/cpa_logo.png">
                    </td>
                    <td width="30%" align="center" valign="middle">
                        <h2>চট্টগ্রাম বন্দর কর্তৃপক্ষ</h2>
                        <h4>www.cpatos.gov.bd</h4>
                        <h4>টার্মিনাল ম্যানেজার দপ্তর</h4>
                    </td>
                    <td width="35%" align="center" valign="middle">
                        &nbsp;
                    </td>
                </tr>
                <tr>
                    <td>
                        <font size="3">টিএম/ইক্যুঃঅপাঃপ্লানিং/যাঃ উঃ/চাহিদা/২৩</font>
                    </td>
                    <td>
                        
                    </td>
                    <td align="right">
                    <font size="3">তারিখঃ <?php echo Bengali_DTN(date("d/m/Y"))." খ্রিঃ";?></font>
                    </td>
                </tr>
            </table>

            <br/>
            
            <table width="100%" cellpadding="1" style="background-color:#ecedf0">
                <tr>
                    <td>বরাবর</td>
                </tr>
                <tr>
                    <td>ওয়ার্কশপ ম্যানেজার</td>
                </tr>
                <tr>
                    <td>চট্টগ্রাম বন্দর কর্তৃপক্ষ।</td>
                </tr>
                <tr>
                    <td>বিষয়ঃ <u>চাহিদা মোতাবেক যান্ত্রিক উপকরণ সরবরাহ প্রসঙ্গে।</u></td>
                </tr>
                <tr>
                    <td>
                        নিম্নোক্ত যান্ত্রিক উপকরণগুলি <?php echo Bengali_DTN(date("d/m/Y"));?> ইং তারিখে কন্টেইনারবাহীত মালামাল ডেলিভারী এবং আনস্টাফিং কাজের চাহিদা মোতাবেক সরবরাহের জন্য অনুরোধ করা গেল।
                    </td>
                </tr>
            </table>
            <br/>

            <table border="1" width="100%" style="background-color:#ecedf0">
                    <tr> 
                        <td colspan="11" align="center"><font size="3"><b>চাহিদা (ডেলিভারী / হোয়েস্টিং )</b></font></td>
                    </tr>
                    <tr>
                        <td rowspan="2" align="center">ইয়ার্ড/শেড</td>
                        <td colspan="2" align="center">কন্টেইনার সংখ্যা</td>
                        <td colspan="8" align="center">যান্ত্রিক উপকরণ ও ক্ষমতা</td>
                    </tr>
                    <tr>
                        <td align="center">২০"</td>
                        <td align="center">৪০"</td>
                        <td align="center">ক্রেন ৫০ টন</td>
                        <td align="center">ক্রেন ৩০ টন</td>
                        <td align="center">ক্রেন ২০ টন</td>
                        <td align="center">ক্রেন ১০ টন</td>
                        <td align="center">ফর্ক লিফট ২০/১০/১৬ টন</td>
                        <td align="center">ফর্ক লিফট ০৫ টন</td>
                        <td align="center">ফর্ক লিফট ০৩ টন</td>
                        <td align="center">RRC</td>
                    </tr>

                    <?php
                    
                        $yard = null;
                        $cont_40 = 0;
                        $cont_20 = 0;
                        $mbl_10t = 0;
                        $mbl_20t = 0;
                        $mbl_30t = 0;
                        $mbl_50t = 0;
                        $hyster_10t = 0;
                        $hyster_5t = 0;
                        $hyster_3t = 0;

                        $totalCont_40 = 0;
                        $totalCont_20 = 0;
                        $totalMbl_10t = 0;
                        $totalMbl_20t = 0;
                        $totalMbl_30t = 0;
                        $totalMbl_50t = 0;
                        $totalHyster_10t = 0;
                        $totalHyster_5t = 0;
                        $totalHyster_3t = 0;

                        for($i=0;$i<count($equipRslt);$i++)
                        {
                            $yard = $equipRslt[$i]['shed_yard'];
                            $cont_40 = $equipRslt[$i]['cont_40'];
                            $cont_20 = $equipRslt[$i]['cont_20'];
                            $mbl_10t = $equipRslt[$i]['mbl_10t'];
                            $mbl_20t = $equipRslt[$i]['mbl_20t'];
                            $mbl_30t = $equipRslt[$i]['mbl_30t'];
                            $mbl_50t = $equipRslt[$i]['mbl_50t'];
                            $hyster_10t = $equipRslt[$i]['hyster_10t'];
                            $hyster_5t = $equipRslt[$i]['hyster_5t'];
                            $hyster_3t = $equipRslt[$i]['hyster_3t'];

                            $totalCont_40 += $cont_40;
                            $totalCont_20 += $cont_20;
                            $totalMbl_10t += $mbl_10t;
                            $totalMbl_20t += $mbl_20t;
                            $totalMbl_30t += $mbl_30t;
                            $totalMbl_50t += $mbl_50t;
                            $totalHyster_10t += $hyster_10t;
                            $totalHyster_5t += $hyster_5t;
                            $totalHyster_3t += $hyster_3t;
                    ?>
                        <tr>
                            <td><?=$yard;?></td>
                            <td><?=Bengali_DTN($cont_20);?></td>
                            <td><?=Bengali_DTN($cont_40);?></td>
                            <td><?=Bengali_DTN($mbl_50t);?></td>
                            <td><?=Bengali_DTN($mbl_30t);?></td>
                            <td><?=Bengali_DTN($mbl_20t);?></td>
                            <td><?=Bengali_DTN($mbl_10t);?></td>
                            <td><?=Bengali_DTN($hyster_10t);?></td>
                            <td><?=Bengali_DTN($hyster_5t);?></td>
                            <td><?=Bengali_DTN($hyster_3t);?></td>
                            <td></td>
                        </tr>
                    <?php
                        }
                    ?>

                        <tr>
                            <td>মোট = </td>
                            <td><?=Bengali_DTN($totalCont_20);?></td>
                            <td><?=Bengali_DTN($totalCont_40);?></td>
                            <td><?=Bengali_DTN($totalMbl_50t);?></td>
                            <td><?=Bengali_DTN($totalMbl_30t);?></td>
                            <td><?=Bengali_DTN($totalMbl_20t);?></td>
                            <td><?=Bengali_DTN($totalMbl_10t);?></td>
                            <td><?=Bengali_DTN($totalHyster_10t);?></td>
                            <td><?=Bengali_DTN($totalHyster_5t);?></td>
                            <td><?=Bengali_DTN($totalHyster_3t);?></td>
                            <td></td>
                        </tr>

                    <!-- <tr>
                        <td>এনসিটি</td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>সিসিটি</td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>ইয়ার্ড - ১</td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>ইয়ার্ড - ২</td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>ইয়ার্ড - ৩</td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>ইয়ার্ড - MN</td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>ইয়ার্ড - ৫</td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>ইয়ার্ড - NCY</td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>ইয়ার্ড - ৬</td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>ইয়ার্ড - ৭</td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>ইয়ার্ড - ৮</td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>ইয়ার্ড - JR</td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>ইয়ার্ড - AB</td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>ইয়ার্ড - CW/D (Ref.)</td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>ইয়ার্ড - ৯/১০</td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>ইয়ার্ড - ১১</td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>ইয়ার্ড - SCY (গুপ্তা ইয়ার্ড)</td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>শেড</td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>-->
            </table>

            <br/>

            <table border="1" width="100%" style="background-color:#ecedf0">
                <tr> 
                    <td colspan="11" align="center"><font size="3">চাহিদা ( এল সি এল কন্টেইনার আনস্টাফিং )</font></td>
                </tr>
                <tr>
                    <td align="center">বার্থ অপারেটর</td>
                    <td align="center">জাহাজের নাম</td>
                    <td align="center">আঃ পাঃ নং</td>
                    <td align="center">শেড নং</td>
                    <td align="center">বুশকার</td>
                    <td align="center">লং ট্রলি</td>
                    <td align="center">FLT ৩ টন</td>
                    <td align="center">FLT ৫ টন</td>
                </tr>
                <!-- <tr>
                    <td>বশির আহমেদ</td>
                    <td></td>
                    <td></td>
                    <td>8</td>
                    <td></td>
                    <td></td>
                    <td>২</td>
                    <td></td>
                </tr>
                <tr>
                    <td>এফ,কিউ, খাঁন এন্ড ব্রাদার্স লিঃ</td>
                    <td></td>
                    <td></td>
                    <td>6</td>
                    <td></td>
                    <td></td>
                    <td>১</td>
                    <td></td>
                </tr>
                <tr>
                    <td>এম,এইচ,চৌধুরী লিঃ</td>
                    <td></td>
                    <td></td>
                    <td>13+P</td>
                    <td></td>
                    <td></td>
                    <td>২</td>
                    <td></td>
                </tr>
                <tr>
                    <td>ফজলী সন্স লিঃ</td>
                    <td></td>
                    <td></td>
                    <td>9+P</td>
                    <td></td>
                    <td></td>
                    <td>১</td>
                    <td></td>
                </tr>
                <tr>
                    <td>এ এন্ড জে ট্রেডার্স লিঃ</td>
                    <td></td>
                    <td></td>
                    <td>N</td>
                    <td></td>
                    <td></td>
                    <td>১</td>
                    <td></td>
                </tr>
                <tr>
                    <td>এভারেস্ট এন্টারপ্রাইজ</td>
                    <td></td>
                    <td></td>
                    <td>12</td>
                    <td></td>
                    <td></td>
                    <td>২</td>
                    <td></td>
                </tr>
                <tr>
                    <td>সাইফ পাওয়ার টেক লিঃ</td>
                    <td></td>
                    <td></td>
                    <td>CFS+D+P</td>
                    <td></td>
                    <td></td>
                    <td>৯</td>
                    <td></td>
                </tr>
                <tr>
                    <td>মোট = </td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td>১৮</td>
                    <td></td>
                </tr> -->
            </table>
            <br/>
            <table border="0" width="100%" style="background-color:#ecedf0">
                <tr>
                    <td width="70%">
                        &nbsp;
                    </td>
                    <td width="30%" align="center">
                        টার্মিনাল অফিসার (কন্ট্রোল)<br/>
                        পক্ষে টার্মিনাল ম্যানেজার<br/>
                        চট্টগ্রাম বন্দর কর্তৃপক্ষ
                    </td>
                </tr>
                <tr>
                    <td width="70%">
                        অনুলিপিঃ<br/>
                        ১। টিআই / ইনচার্জ-ইক্যুইপমেন্ট বুকিং ও সুপারভিশন(মোবাইল-৩)/টাওয়ার ভবন এর অবগতি ও উপরোক্ত যান্ত্রিক <br/><span style="margin-left:17px;">উপকরণগুলো কেন্দ্রীয় কারখানায় গিয়ে বুকিং নিশ্চিত করার জন্য বলা গেল।</span>
                    </td>
                    <td width="30%" align="center">
                        &nbsp;
                    </td>
                </tr>
                <tr>
                    <td width="70%">
                        &nbsp;
                    </td>
                    <td width="30%" align="center">
                        টার্মিনাল অফিসার (কন্ট্রোল)<br/>
                        পক্ষে টার্মিনাল ম্যানেজার<br/>
                        চট্টগ্রাম বন্দর কর্তৃপক্ষ
                    </td>
                </tr>
            </table>
            <br/>
        </div>
	</body>
</html>

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