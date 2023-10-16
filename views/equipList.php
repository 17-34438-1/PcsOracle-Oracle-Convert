<HTML>
	<HEAD>
		<TITLE>EQUIPMENT LIST</TITLE>
		
	    <style type="text/css">
<!--
.style1 {font-size: 12px}
-->
        </style>
</HEAD>
<BODY>	
		<TABLE width="90%" align="center">
			<TR><TD width="100%">
				<table class='table-header' border=0 width="100%">
					<tr><td align="center"><h1>EQUIPMENT LIST</h1></td></tr>
                    <tr>
                        <th align="center">
                            <h3 align="center">
                            <?php 
                                echo $strTitle2 = "DATE FROM : ".$fromDate." TO : ".$toDate;
                            ?>
                            </h3>
                        </th>
                    </tr>
				</table>
			</TD></TR>
			<TR><TD>
				<table border=1 class="table table-bordered table-responsive table-hover table-striped mb-none">
                    <thead>
                        <tr>
                            <th align="center" rowspan="2">Sl</th>
                            <th align="center" rowspan="2">আবেদনকারীর নাম</th>						
                            <th align="center" colspan="2">কন্টেঃ সংখ্যা</th>						
                            <th align="center" rowspan="2">মালামালের বিবরণ</th>						
                            <th align="center" colspan="2">মালামালের ওজন</th>						
                            <th align="center" rowspan="2">RRC</th>						
                            <th align="center" colspan="3">ফর্ক লিস্ট</th>						
                            <th align="center" colspan="4">মোবাইল ক্রেন</th>		
                            <th align="center" rowspan="2">ইয়ার্ড/শেড</th>								
                        </tr>
                        <tr>
                            <th align="center">২০'</th>
                            <th align="center">৪০'</th>
                            <th align="center">মোট ওজন</th>
                            <th align="center">প্রতি প্যাকেজে সর্বোচ্চ ওজন</th>
                            <th align="center">০৩ টন</th>
                            <th align="center">০৫ টন</th>
                            <th align="center">১০ টন/২০ টন</th>
                            <th align="center">১০ টন</th>
                            <th align="center">২০ টন</th>
                            <th align="center">৩০ টন</th>
                            <th align="center">৫০ টন</th>
                        </tr>
					</thead>
                        <?php 
                            for($i=0;$i<count($rslt);$i++){
                        ?>
                        <tr>
                            <td align="center"><?=$i+1; ?></td>
                            <td align="center"><?=$rslt[$i]['u_name']; ?></td>
                            <td align="center"><?=$rslt[$i]['cont_20']; ?></td>
                            <td align="center"><?=$rslt[$i]['cont_40']; ?></td>
                            <td align="center"><?=$rslt[$i]['pkg_qty_desc']; ?></td>
                            <td align="center"><?=$rslt[$i]['pkg_wt']; ?></td>
                            <td align="center"><?="NULL"; ?></td>
                            <td align="center"><?="NULL"; ?></td>
                            <td align="center"><?=$rslt[$i]['hyster_3t']; ?></td>
                            <td align="center"><?=$rslt[$i]['hyster_5t']; ?></td>
                            <td align="center"><?=$rslt[$i]['hyster_10t']; ?></td>
                            <td align="center"><?=$rslt[$i]['mbl_10t']; ?></td>
                            <td align="center"><?=$rslt[$i]['mbl_20t']; ?></td>
                            <td align="center"><?=$rslt[$i]['mbl_30t']; ?></td>
                            <td align="center"><?=$rslt[$i]['mbl_50t']; ?></td>
                            <td align="center"><?=$rslt[$i]['shed_yard']; ?></td>
                        </tr>
                        <?php
                            }
                        ?>
                    <tbody>

                    </tbody>
                </table>  						 
            </TD></TR>
        </TABLE>
	
