<html>
	<body>
		<div class="pagewidth">
			<table border="0" cellspacing="0" width="100%">
                <tr>
                    <td colspan="4" align="center"><img src="<?php echo IMG_PATH?>cpanew.jpg"></td>
                </tr>
                <tr>
                    <td colspan="4" align="center"><h3>Laden Export Container Report<br/>(Gate In to Shipment Time)<br/>
                        <?php
                            if($searchBy == 'rotation')
                            {
                        ?>
                            Rotation No : <?php echo $rotation;?></h3>
                        <?php
                            }else if($searchBy == 'date'){
                        ?>
                            Date : <?php echo $fromDate." - ".$toDate;?></h3>
                        <?php
                            }else{
                        ?>
                            Gate: <?php echo $gate; ?> | Date : <?php echo $fromDate." - ".$toDate;?></h3>
                        <?php
                            }
                        ?>
                    </td>
                </tr>
            </table>

            <table border="1" cellpadding="3" width="100%" style="border-spacing:0px;">
                <thead>
                    <tr>
                        <th>SL NO</th>
                        <th>Container Nbr</th>
                        <th>Loaded on Vessel</th>
                        <th>Gate in date</th>
                        <th>Duration</th>
                        <th>Size</th>
                        <th>Line Op</th>
                        <th>Trailer</th>
                        <th>Vessel Name</th>
                        <th>Rotation No</th>
                        <th>POD</th>
                        <th>Freight  Kind</th>
                        <th>Gate</th>
                    </tr>
                </thead>

                <?php 
                    $totalMinute = 0;
                    $id = "";
                    $time_load = "";
                    $gate_in = "";
                    $duration = "";
                    $size = "";
                    $MLO = "";
                    $bat_nbr = "";
                    $vsl_name = "";
                    $ib_vyg = "";
                    $ctr_freight_kind = "";
                    $gate = "";
                    for($i=0;$i<count($rslt);$i++)
                    {
                        $id = $rslt[$i]['ID'];
                        $time_load = $rslt[$i]['TIME_LOAD'];
                        $gate_in = $rslt[$i]['GATE_IN'];
                        $duration = $rslt[$i]['DURATION'];
                        $size = $rslt[$i]['SIZE'];
                        $MLO = $rslt[$i]['MLO'];
                        $bat_nbr = $rslt[$i]['BAT_NBR'];
                        $vsl_name = $rslt[$i]['VSL_NAME'];
                        $ib_vyg = $rslt[$i]['IB_VYG'];
                        $ctr_freight_kind =$rslt[$i]['CTR_FREIGHT_KIND'];
                        $gate = $rslt[$i]['GATE'];
                ?>
                <tr>
                    <td align="center" ><?php echo $i+1; ?></td>
                    <td align="center"><?php echo $id; ?></td>
                    <td align="center"><?php echo $time_load; ?></td>
                    <td align="center"><?php echo $gate_in; ?></td>
                    <td align="center">
                        <?php 
                            $duration_string = explode('days ',$duration); 
                            $duration = $duration_string[1];
                            $dura = explode(' ',$duration);
                            if($dura[0] >= 19){
                                $duration = "19 hours 00 minutes";
                            } 

                            // total minute calculation
                            $dura = explode(' ',$duration);
                            $totalMinute+=($dura[0]*60);
                            $totalMinute+=$dura[2];
                            echo $duration;
                        ?>
                    </td>
                    <td align="center"><?php echo $size; ?></td>
                    <td align="center"><?php echo $MLO; ?></td>
                    <td align="center"><?php echo $bat_nbr; ?></td>
                    <td align="center"><?php echo $vsl_name; ?></td>
                    <td align="center"><?php echo $ib_vyg; ?></td>
                    <td align="center"><?php echo ""; ?></td>
                    <td align="center"><?php echo $ctr_freight_kind; ?></td>
                    <td align="center"><?php echo $gate; ?></td>
                </tr>
                <?php
                    }
                    
                    if($i>0){
                        $avgTime = $totalMinute/$i;
                        $avgHours = floor($avgTime/60);
                        $avgMinute = $avgTime%60;
            
                        $avgTime = $avgHours." hours ".$avgMinute." munites";
                    }
                    else
                    {
                        $avgTime = "0 hours 0 munites";
                    }
                ?>

                <tr>
                    <td colspan="12"><b>&nbsp;</b></td>
                </tr>
                <tr>
                    <td colspan="12"><b>&nbsp;</b></td>
                </tr>
                <tr>
                    <td colspan="12"><b>** Average Time on vessel load <?php echo $avgTime; ?></b></td>
                </tr>
            </table>
					
		</div>
	</body>
</html>