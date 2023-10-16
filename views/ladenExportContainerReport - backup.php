<?php
    if($options=='xl'){
		header("Content-type: application/octet-stream");
		header("Content-Disposition: attachment; filename=laden_Export_Container_Report.xls;");
		header("Content-Type: application/ms-excel");
		header("Pragma: no-cache");
		header("Expires: 0");
	}
?>

<table border="0" cellspacing="0" width="100%">
    <tr>
        <td colspan="4" align="center"><img src="<?php echo IMG_PATH?>cpanew.jpg"></td>
    </tr>
    <tr>
        <td colspan="4" align="center"><h3>Laden Export Container Report<br/>(Gate In to Shipment Time)<br/>Rotation No : <?php echo $rotation;?></h3></td>
    </tr>
<table>

<table border="1" cellspacing="0" width="100%">
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
        </tr>
    </thead>

    <?php
        $totalMinute = 0;
        for($i=0;$i<count($rslt);$i++)
        {
    ?>
        <tr>
            <td align="center"><?php echo $i+1; ?></td>
            <td align="center"><?php echo $rslt[$i]['id']; ?></td>
            <td align="center"><?php echo $rslt[$i]['time_load']; ?></td>
            <td align="center"><?php echo $rslt[$i]['gate_in']; ?></td>
            <td align="center">
                <?php 
                    $duration_string = explode('days ',$rslt[$i]['duration']); 
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
            <td align="center"><?php echo $rslt[$i]['size']; ?></td>
            <td align="center"><?php echo $rslt[$i]['MLO']; ?></td>
            <td align="center"><?php echo $rslt[$i]['bat_nbr']; ?></td>
            <td align="center"><?php echo $rslt[$i]['vsl_name']; ?></td>
            <td align="center"><?php echo $rslt[$i]['ib_vyg']; ?></td>
            <td align="center"><?php echo ""; ?></td>
            <td align="center"><?php echo $rslt[$i]['ctr_freight_kind']; ?></td>
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
<table>

<script>
    window.print();
</script>