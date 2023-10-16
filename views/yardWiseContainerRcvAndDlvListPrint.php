
<?php

include("dbConection.php");
include("dbOracleConnection.php");	
?>
    <html>
    <title>Yard Wise Delivery and Receiving</title>
<body>
    <table width="100%" border ='0' cellpadding='0' cellspacing='0'>
        <tr bgcolor="#ffffff" align="center" height="100px">
            <td colspan="13" align="center">
                <table border=0 width="100%">
                    <tr>
                        <td colspan="12" align="center">
                            <img src="<?php echo ASSETS_WEB_PATH;?>fimg/cpaLogo.png" alt="Chattogram Port Authority Logo"/>
                        </td>
                    </tr>

                    <tr align="center">
                        <td colspan="12"><font size="4"><b><?php echo $title;?></b></font></td>
                    </tr>
                    <tr align="center">
                        <td colspan="12"><font size="4"><b></b></font></td>
                    </tr>

                    <tr>
                        <td colspan="3" align="center"><font size="4"><b> Date : <?php echo $search_dt;?></b></font></td>
                    </tr>

                </table>

            </td>

        </tr>

        <tr bgcolor="#ffffff" align="center" height="25px">
            <td colspan="15" align="center"></td>

        </tr>
    </table>

    <table class="table table-responsive table-bordered table-striped mb-none">
        <tr align="center"  class="gridDark">
            <td align="center"><b>Sl.</b></td>
            <td align="center"><b>Container No.</b></td>
            <td align="center"><b>Size</b></td>
            <td align="center"><b>Seal No</b></td>
            <td align="center"><b>Vessel Name</b></td>
            <td align="center"><b>Rot No</b></td>
            <td align="center"><b>MLO</b></td>
            <td align="center"><b>Depot</b></td>
            <td align="center"><b>Entered Yard</b></td>
            <td align="center"><b>Exited Yard</b></td>
            <td align="center"><b>Gate No</b></td>
            <td align="center"><b>User</b></td>
        </tr>

    <?php
      $cond = "";
      if($terminal=="GCB")
      {
          if($search_yard=="all")
          {
              $cond = " where terminal='$terminal'  AND  block_cpa!='NULL' ORDER BY block ASC";
          }
          else
          {
              $cond = " where terminal='$terminal' AND block_cpa='$search_yard' AND  block_cpa!='NULL'  ORDER BY block ASC";
          }
          
      }
      else
      {
          $cond = " where terminal='$terminal'  AND  block_cpa!='NULL' ORDER BY block ASC";
      }
      
      $where="";
      if($search_type=="delivery")
      {
          $where=" WHERE to_char(inv_unit_fcy_visit.time_out,'yyyy-mm-dd')='".$search_dt."' ";
      }
      else if($search_type=="receive")
      {
          $where=" WHERE to_char(inv_unit_fcy_visit.time_in,'yyyy-mm-dd')='".$search_dt."' ";
      }
      
      $totalRow=0;
      $blockListStr="SELECT DISTINCT block_cpa as block  FROM ctmsmis.yard_block ".$cond;
      $blockListQuery=mysqli_query($con_sparcsn4,$blockListStr);
      $totalRow=mysqli_num_rows($blockListQuery);
      $blockList="";
        $i=0;
        while($blockRow=mysqli_fetch_object($blockListQuery)){
            $blockString="";
            $blockString=$blockRow->block;

            if($i==($totalRow-1)){
                $blockList=$blockList."'".$blockString."'";
            }
            else{
                $blockList=$blockList."'".$blockString."',";

            }
            $i++;

        }
        $strQuery = "select *
        from 
        ( 
        SELECT inv.id as container, inv.seal_nbr1,inv.remark,
        vsl_vessels.name,vsl_vessel_visit_details.ib_vyg, inv_goods.destination,entered_yard,exited_yard,
        (select id from road_gates where gkey= road_truck_visit_details.gate_gkey) as GateNo,
        road_truck_visit_details.creator as us,
        inv_unit_fcy_visit.time_out, 
        (select substr(ref_equip_type.nominal_length,-2) 
        from ref_equip_type 
        INNER JOIN ref_equipment ON ref_equipment.eqtyp_gkey=ref_equip_type.gkey 
        INNER JOIN inv_unit ON inv_unit.eq_gkey=ref_equipment.gkey 
        INNER JOIN inv_unit_fcy_visit ON inv_unit_fcy_visit.unit_gkey=inv_unit.gkey
        where inv_unit_fcy_visit.unit_gkey=inv.gkey fetch first 1 rows only ) as siz,
        ref_bizunit_scoped.id as mlo 
        FROM inv_unit inv
        INNER JOIN srv_event ON  srv_event.applied_to_gkey=inv.gkey
        INNER JOIN inv_move_event ON inv_move_event.mve_gkey=srv_event.gkey
        INNER JOIN xps_che ON (xps_che.gkey=inv_move_event.che_fetch OR xps_che.gkey=inv_move_event.che_carry OR xps_che.gkey=inv_move_event.che_put)
        INNER JOIN xps_chezone ON  xps_chezone.che_id=xps_che.id
        INNER JOIN inv_unit_fcy_visit ON inv_unit_fcy_visit.unit_gkey=inv.gkey 
        INNER JOIN argo_carrier_visit ON argo_carrier_visit.gkey=inv_unit_fcy_visit.actual_ib_cv 
        INNER JOIN vsl_vessel_visit_details ON vsl_vessel_visit_details.vvd_gkey=argo_carrier_visit.cvcvd_gkey 
        INNER JOIN vsl_vessels ON vsl_vessels.gkey=vsl_vessel_visit_details.vessel_gkey 
        inner join inv_goods on inv_goods.gkey=inv.goods 
        inner join ref_bizunit_scoped on ref_bizunit_scoped.gkey=inv.line_op
        inner join road_truck_transactions on road_truck_transactions.unit_gkey=inv.gkey
        inner join road_truck_visit_details on road_truck_visit_details.tvdtls_gkey=road_truck_transactions.truck_visit_gkey
        ".$where." and inv_goods.destination not in('2591','2592','BDCGP') and sel_block IN($blockList)
        and inv_goods.destination is not null and road_truck_transactions.status !='CANCEL'
        )  tmp ";
       
                    
        //echo "<br><br>";
        //echo $strQuery;
        $query=oci_parse($con_sparcsn4_oracle,$strQuery);
        oci_execute($query);
        $i=0;
        $mlo="";
        $totCont = "";
        while(($row=oci_fetch_object($query))!=false){
            $destination="";
            $offdock="";
            $destination=$row->DESTINATION;
            $ctmsStr="";
            $ctmsStr="select ctmsmis.offdoc.code from ctmsmis.offdoc where ctmsmis.offdoc.id='$destination'";
            $ctmsQuery=mysqli_query($con_sparcsn4,$ctmsStr);
            while($offdockRow=mysqli_fetch_object($ctmsQuery)){
                $offdock=$offdockRow->code;
            }	
            $i++;
            $totCont = $totCont.$row->CONTAINER.", ";


            ?>
            <tr align="center" class="gradeX">
                <td><?php  echo $i;?></td>
                <td><?php if($row->CONTAINER) echo $row->CONTAINER; else echo "&nbsp;";?></td>
                <td><?php if($row->SIZ) echo $row->SIZ; else echo "&nbsp;";?></td>
                <td><?php if($row->SEAL_NBR1) echo $row->SEAL_NBR1; else echo "&nbsp;";?></td>
                <td><?php if($row->NAME) echo $row->NAME; else echo "&nbsp;";?></td>
                <td><?php if($row->IB_VYG) echo $row->IB_VYG; else echo "&nbsp;";?></td>
                <td><?php if($row->MLO) echo $row->MLO; else echo "&nbsp;";?></td>
                <td><?php if($offdock) echo $offdock; else echo "&nbsp;";?></td>		
                <td><?php if($row->ENTERED_YARD) echo $row->ENTERED_YARD; else echo "&nbsp;";?></td>		
                <td><?php if($row->EXITED_YARD) echo $row->EXITED_YARD; else echo "&nbsp;";?></td>		
                <td><?php if($row->GATENO) echo $row->GATENO; else echo "&nbsp;";?></td>		
                <td><?php if($row->US) echo $row->US; else echo "&nbsp;";?></td>	
                
            </tr>
        <?php } ?>
    </table>
    <br />
    <br />

    <table border="1">
        <tr>
            <?php echo $totCont; ?>
        </tr>

    </table>


<?php
mysqli_close($con_sparcsn4);
?>
</body>
    </html>
    <script>
        window.print();
    </script>
