<html>
<!--head>
     <meta http-equiv="refresh" content="20">
     <style>
        body{font-family: "Calibri";}
     </style>
</head-->
<body>
<div>
    <div align="center">
        <table>
            <tr>
                <td  align="center">
                    <img src="<?php echo ASSETS_WEB_PATH;?>fimg/cpaLogo.png" alt="Chattogram Port Authority Logo"/>
                </td>
            </tr>
        </table>
    </div>
    <div align="center">
        <?php include("dbConection.php");?>
        <table>
            <tr style="margin:5px;">
                <td colspan="12"><font size="5"><b>Equipment wise Delivery & Receiving Report </b></font><font size="4"><?php echo $date; ?></font></td>
            </tr>
        </table>
        <table class="table table-responsive table-bordered table-striped mb-none">
            <thead>
            <tr align="center" class="gridDark">
                <td align="center"><b>SlNo.</b></td>
                <td align="center"><b>Equipment</b></td>
                <td align="center"><b>Delivery</b></td>
                <td align="center"><b>Receiving</b></td>
            </tr>
            </thead>
          <tbody>



            <?php

            //echo$vvdGkey;
            $strQuery = "SELECT eq,created_by,SUM(impRcv) AS impRcv,SUM(keepDlv) AS keepDlv,SUM(dlvOcdOffDock) AS dlvOcdOffDock
						FROM( SELECT full_name AS eq,created_by, (CASE WHEN move_kind='DSCH' THEN 1 ELSE 0 END) AS impRcv,
						(CASE WHEN move_kind='YARD' THEN 1 ELSE 0 END) AS keepDlv, (CASE WHEN move_kind IN('DLVR','SHOB') THEN 1 ELSE 0 END) AS dlvOcdOffDock FROM ( SELECT full_name,move_kind, (SELECT placed_by 
						FROM sparcsn4.srv_event WHERE srv_event.gkey=sparcsn4.inv_move_event.mve_gkey) AS created_by 
						FROM sparcsn4.inv_move_event INNER JOIN sparcsn4.xps_che ON (sparcsn4.xps_che.gkey=sparcsn4.inv_move_event.che_fetch 
						OR sparcsn4.xps_che.gkey=sparcsn4.inv_move_event.che_carry OR sparcsn4.xps_che.gkey=sparcsn4.inv_move_event.che_put)
						WHERE DATE(t_put) = '$date' AND move_kind='DSCH'
						UNION ALL
						SELECT full_name,move_kind, (SELECT placed_by FROM sparcsn4.srv_event WHERE srv_event.gkey=sparcsn4.inv_move_event.mve_gkey) AS created_by 
						FROM sparcsn4.inv_move_event INNER JOIN sparcsn4.xps_che ON (sparcsn4.xps_che.gkey=sparcsn4.inv_move_event.che_fetch
						OR sparcsn4.xps_che.gkey=sparcsn4.inv_move_event.che_carry OR sparcsn4.xps_che.gkey=sparcsn4.inv_move_event.che_put)
						WHERE DATE (t_put) ='$date' AND move_kind='SHFT' 
						UNION ALL
						SELECT full_name,move_kind, (SELECT placed_by FROM sparcsn4.srv_event 
						WHERE srv_event.gkey=sparcsn4.inv_move_event.mve_gkey) AS created_by FROM sparcsn4.inv_move_event 
						INNER JOIN sparcsn4.xps_che ON (sparcsn4.xps_che.gkey=sparcsn4.inv_move_event.che_fetch
						OR sparcsn4.xps_che.gkey=sparcsn4.inv_move_event.che_carry OR sparcsn4.xps_che.gkey=sparcsn4.inv_move_event.che_put) 
						WHERE DATE(t_put) = '$date'  AND move_kind='DLVR' 
						UNION ALL 
						SELECT full_name,move_kind, (SELECT placed_by FROM sparcsn4.srv_event 
						WHERE srv_event.gkey=sparcsn4.inv_move_event.mve_gkey) AS created_by FROM sparcsn4.inv_move_event 
						INNER JOIN sparcsn4.xps_che ON (sparcsn4.xps_che.gkey=sparcsn4.inv_move_event.che_fetch 
						OR sparcsn4.xps_che.gkey=sparcsn4.inv_move_event.che_carry OR sparcsn4.xps_che.gkey=sparcsn4.inv_move_event.che_put)
						WHERE DATE(t_put) ='$date'  AND move_kind='SHOB'
						UNION ALL 
						SELECT full_name,move_kind, (SELECT placed_by FROM sparcsn4.srv_event 
						WHERE srv_event.gkey=sparcsn4.inv_move_event.mve_gkey) AS created_by FROM sparcsn4.inv_move_event 
						INNER JOIN sparcsn4.xps_che ON (sparcsn4.xps_che.gkey=sparcsn4.inv_move_event.che_fetch 
						OR sparcsn4.xps_che.gkey=sparcsn4.inv_move_event.che_carry OR sparcsn4.xps_che.gkey=sparcsn4.inv_move_event.che_put)
						WHERE DATE(t_put) = '$date' 
						AND move_kind='YARD'
						ORDER BY full_name,move_kind) AS t WHERE full_name LIKE 'RTG%' OR full_name LIKE 'SC%' OR full_name LIKE 'MHC%' OR full_name LIKE 'QGC%'
						OR full_name LIKE 'RMG%' OR full_name LIKE 'RST%' OR full_name LIKE 'FLT%' OR full_name LIKE 'CM%' ) AS f GROUP BY eq";

            //echo $strQuery;
            $query=mysqli_query($con_sparcsn4,$strQuery);

            $i = 0;
            while($row=mysqli_fetch_object($query)){
                $i++;
                ?>
                <tr align="center">
                    <td align="center"><?php  echo $i;?></td>
                    <td align="center"><?php if($row->eq) echo $row->eq; else echo "&nbsp;";?></td>
                    <td align="center"><?php if($row->impRcv) echo $row->impRcv; else echo "&nbsp;";?></td>
                    <td align="center"><?php if($row->keepDlv) echo $row->keepDlv; else echo "&nbsp;";?></td>
                </tr>

            <?php } ?>
          </tbody>
        </table>
    </div>
</div>

<?php mysqli_close($con_sparcsn4); ?>
<div class="text-right mr-lg">

    <a href="<?php echo site_url('report/EquipmentWiseDeliveryRecevingReport/'.'print'.'/'.$date)?>" target="_blank" class="btn btn-primary ml-sm"><i class="fa fa-print"></i> Print</a>
</div>
</body>
</html>