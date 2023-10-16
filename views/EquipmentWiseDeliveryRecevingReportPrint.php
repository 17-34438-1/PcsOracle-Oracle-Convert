<html>

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
            // $strQuery = "SELECT eq,created_by,SUM(impRcv) AS impRcv,SUM(keepDlv) AS keepDlv,SUM(dlvOcdOffDock) AS dlvOcdOffDock
			// 			FROM( SELECT full_name AS eq,created_by, (CASE WHEN move_kind='DSCH' THEN 1 ELSE 0 END) AS impRcv,
			// 			(CASE WHEN move_kind='YARD' THEN 1 ELSE 0 END) AS keepDlv, (CASE WHEN move_kind IN('DLVR','SHOB') THEN 1 ELSE 0 END) AS dlvOcdOffDock FROM ( SELECT full_name,move_kind, (SELECT placed_by 
			// 			FROM sparcsn4.srv_event WHERE srv_event.gkey=sparcsn4.inv_move_event.mve_gkey) AS created_by 
			// 			FROM sparcsn4.inv_move_event INNER JOIN sparcsn4.xps_che ON (sparcsn4.xps_che.gkey=sparcsn4.inv_move_event.che_fetch 
			// 			OR sparcsn4.xps_che.gkey=sparcsn4.inv_move_event.che_carry OR sparcsn4.xps_che.gkey=sparcsn4.inv_move_event.che_put)
			// 			WHERE DATE(t_put) = '$date' AND move_kind='DSCH'
			// 			UNION ALL
			// 			SELECT full_name,move_kind, (SELECT placed_by FROM sparcsn4.srv_event WHERE srv_event.gkey=sparcsn4.inv_move_event.mve_gkey) AS created_by 
			// 			FROM sparcsn4.inv_move_event INNER JOIN sparcsn4.xps_che ON (sparcsn4.xps_che.gkey=sparcsn4.inv_move_event.che_fetch
			// 			OR sparcsn4.xps_che.gkey=sparcsn4.inv_move_event.che_carry OR sparcsn4.xps_che.gkey=sparcsn4.inv_move_event.che_put)
			// 			WHERE DATE (t_put) ='$date' AND move_kind='SHFT' 
			// 			UNION ALL
			// 			SELECT full_name,move_kind, (SELECT placed_by FROM sparcsn4.srv_event 
			// 			WHERE srv_event.gkey=sparcsn4.inv_move_event.mve_gkey) AS created_by FROM sparcsn4.inv_move_event 
			// 			INNER JOIN sparcsn4.xps_che ON (sparcsn4.xps_che.gkey=sparcsn4.inv_move_event.che_fetch
			// 			OR sparcsn4.xps_che.gkey=sparcsn4.inv_move_event.che_carry OR sparcsn4.xps_che.gkey=sparcsn4.inv_move_event.che_put) 
			// 			WHERE DATE(t_put) = '$date'  AND move_kind='DLVR' 
			// 			UNION ALL 
			// 			SELECT full_name,move_kind, (SELECT placed_by FROM sparcsn4.srv_event 
			// 			WHERE srv_event.gkey=sparcsn4.inv_move_event.mve_gkey) AS created_by FROM sparcsn4.inv_move_event 
			// 			INNER JOIN sparcsn4.xps_che ON (sparcsn4.xps_che.gkey=sparcsn4.inv_move_event.che_fetch 
			// 			OR sparcsn4.xps_che.gkey=sparcsn4.inv_move_event.che_carry OR sparcsn4.xps_che.gkey=sparcsn4.inv_move_event.che_put)
			// 			WHERE DATE(t_put) ='$date'  AND move_kind='SHOB'
			// 			UNION ALL 
			// 			SELECT full_name,move_kind, (SELECT placed_by FROM sparcsn4.srv_event 
			// 			WHERE srv_event.gkey=sparcsn4.inv_move_event.mve_gkey) AS created_by FROM sparcsn4.inv_move_event 
			// 			INNER JOIN sparcsn4.xps_che ON (sparcsn4.xps_che.gkey=sparcsn4.inv_move_event.che_fetch 
			// 			OR sparcsn4.xps_che.gkey=sparcsn4.inv_move_event.che_carry OR sparcsn4.xps_che.gkey=sparcsn4.inv_move_event.che_put)
			// 			WHERE DATE(t_put) = '$date' 
			// 			AND move_kind='YARD'
			// 			ORDER BY full_name,move_kind) AS t WHERE full_name LIKE 'RTG%' OR full_name LIKE 'SC%' OR full_name LIKE 'MHC%' OR full_name LIKE 'QGC%'
			// 			OR full_name LIKE 'RMG%' OR full_name LIKE 'RST%' OR full_name LIKE 'FLT%' OR full_name LIKE 'CM%' ) AS f GROUP BY eq";
            include("dbOracleConnection.php");

           
            $strQuery = "
  
            SELECT eq,created_by,SUM(impRcv) AS impRcv,SUM(keepDlv) AS keepDlv,SUM(dlvOcdOffDock) AS dlvOcdOffDock,SUM(shift) AS shift
          FROM(
          SELECT full_name AS eq,created_by,
          (CASE WHEN move_kind='DSCH' THEN 1 ELSE 0 END) AS impRcv,
          (CASE WHEN move_kind='YARD' THEN 1 ELSE 0 END) AS keepDlv,
          (CASE WHEN move_kind IN('DLVR','SHOB') THEN 1 ELSE 0 END) AS dlvOcdOffDock,
          (CASE WHEN move_kind='SHFT' THEN 1 ELSE 0 END) AS shift
          FROM
          (
          SELECT full_name,move_kind,
          (SELECT  placed_by FROM srv_event WHERE srv_event.gkey=inv_move_event.mve_gkey)  AS created_by
          FROM inv_move_event 
          INNER JOIN xps_che ON (xps_che.gkey=inv_move_event.che_fetch OR xps_che.gkey=inv_move_event.che_carry OR xps_che.gkey=inv_move_event.che_put)
          WHERE to_date(t_put)='$date'  AND move_kind='DSCH'
          UNION ALL
          
          SELECT full_name,move_kind,
          (
          SELECT  placed_by FROM srv_event WHERE srv_event.gkey=inv_move_event.mve_gkey)  AS created_by
          FROM inv_move_event 
          INNER JOIN xps_che ON (xps_che.gkey=inv_move_event.che_fetch OR xps_che.gkey=inv_move_event.che_carry OR xps_che.gkey=inv_move_event.che_put)
          WHERE to_date(t_put)='$date'  AND move_kind='SHFT'
          
          UNION ALL
          
          SELECT full_name,move_kind,
          (
          SELECT  placed_by FROM srv_event WHERE srv_event.gkey=inv_move_event.mve_gkey)  AS created_by
          FROM inv_move_event 
          INNER JOIN xps_che ON (xps_che.gkey=inv_move_event.che_fetch OR xps_che.gkey=inv_move_event.che_carry OR xps_che.gkey=inv_move_event.che_put)
          WHERE to_date(t_put)='$date'  AND move_kind='DLVR'
          
          UNION ALL
          
          SELECT full_name,move_kind,
          (
          SELECT  placed_by FROM srv_event WHERE srv_event.gkey=inv_move_event.mve_gkey)  AS created_by
          FROM inv_move_event 
          INNER JOIN xps_che ON (xps_che.gkey=inv_move_event.che_fetch OR xps_che.gkey=inv_move_event.che_carry OR xps_che.gkey=inv_move_event.che_put)
          WHERE to_date(t_put)='$date'  AND move_kind='SHOB'
          UNION ALL
          
          SELECT full_name,move_kind,
          (
          SELECT  placed_by FROM srv_event WHERE srv_event.gkey=inv_move_event.mve_gkey)  AS created_by
          FROM inv_move_event 
          INNER JOIN xps_che ON (xps_che.gkey=inv_move_event.che_fetch OR xps_che.gkey=inv_move_event.che_carry OR xps_che.gkey=inv_move_event.che_put)
          WHERE to_date(t_put)='$date'  AND move_kind='YARD'
          
              ) t WHERE full_name LIKE 'RTG%' OR full_name LIKE 'SC%' OR full_name LIKE 'MHC%' OR full_name LIKE 'QGC%'
                                  OR full_name LIKE 'RMG%' OR full_name LIKE 'RST%' OR full_name LIKE 'FLT%' OR full_name LIKE 'CM%'   )  GROUP BY eq,created_by ORDER BY eq,created_by
              
             ";

             $query = oci_parse($con_sparcsn4_oracle, $strQuery);
             oci_execute($query);


                $i++;
                while(($row = oci_fetch_object($queryresult))!= false){                
                $i++;
                ?>
                <tr align="center">
                    <td align="center"><?php  echo $i;?></td>
                    <td align="center"><?php if($row->EQ) echo $row->EQ; else echo "&nbsp;";?></td>
                    <td align="center"><?php if($row->IMPRCV) echo $row->IMPRCV; else echo "&nbsp;";?></td>
                    <td align="center"><?php if($row->KEEPDIV) echo $row->KEEPDIV; else echo "&nbsp;";?></td>
                </tr>

            <?php } ?>
            </tbody>
        </table>
    </div>
</div>

<?php mysqli_close($con_sparcsn4); 

oci_close($con_sparcsn4_oracle);?>

</body>
</html>
<script>
    window.print();
</script>