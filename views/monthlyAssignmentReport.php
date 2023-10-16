<section role="main" class="content-body">
	<header class="page-header">
		<!-- <h2><?php echo $title;?></h2> -->
	</header>

	<!-- start: page -->
		<div class="row">
			<div class="col-lg-12">						
				<section class="panel">
					<div class="panel-body">
                    <table width="60%" border ='0' align="center" cellpadding='0' cellspacing='0'>
                        <tr  align="center" height="100px">
                            <td colspan="13" align="center">
                                <table border=0 width="100%">
                                    <tr align="center">
                                        <td colspan="12"><font size="5"><b>Monthly Assignment Report</font></td>
                                    </tr>
                                    <tr align="center">
                                        <td colspan="12"><font size="4"><b>C&F : <?php echo $cnfName; ?>&nbsp;&nbsp;</font></td>
                                    </tr>
                                    <tr align="center">
                                        <td colspan="12"><font size="4"><b>Year : <?php echo $year; ?>&nbsp;&nbsp;</font></td>
                                    </tr>
                                    <tr align="center">
                                        <td colspan="12"><font size="4"><b>Month : <?php echo $month; ?>&nbsp;&nbsp;</font></td>
                                    </tr>
                                </table>
                            </td>
                        </tr>
                    </table>

						<table width="35%" border ='1' align="center" cellpadding='0' cellspacing='0'>
                            
                            <tr>
                                <th align="center">SL</th>
                                <th align="center">Date</th>
                                <th align="center">No. of Container</th>
                            </tr>
                            <tr>
                                <?php
                                    $monthName = "";
                                    $cntNo = "";
                                    $total = "";
                                    // echo count($yearlycnfReport);
                                    //var_dump($yearlycnfReport);
                                    //return;
                                    for($i=0;$i<count($monthlycnfReport);$i++){
                                        $data = $monthlycnfReport[$i]['assignDt'];
                                        $cntNo = $monthlycnfReport[$i]['cnt'];
                                        $total+=$cntNo;
                                        
                                ?>
                                    <tr>
                                        <td align="center"><?php echo $i+1;?></td>
                                        <td align="center"><?php echo $data ?></td>
                                        <td align="center"><?php echo $cntNo; ?></td>
                                    </tr>
                                <?php
                                    }
                                ?>
                            </tr>
                            <tr>
                                    <td colspan="2" align="center"><b>Total:</b></td>
                                    <td align="center"><b><?php echo $total; ?></b></td>
                            </tr>
                        </table>
					</div>
				</section>
			</div>
		</div>	
	<!-- end: page -->
</section>
</div>