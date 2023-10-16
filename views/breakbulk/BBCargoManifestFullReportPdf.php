<?php if(count($ROTATIONDATA)>0) : ?>
<table border="0" style="border-collapse:collapse;" align="center" width="100%">
    <tr style="padding:0px">
        <td style="text-align: center; width: 70%;">
            <font style="font-size:16px; color: #0B0B61; font-family: Impact, Charcoal, sans-serif; text-transform: uppercase">
                <b>Government of the People's Republic of Bangladesh</b>
            </font><br>
            <font
                style="font-size: 10px; color: #0B0B61; font-family: Impact, Charcoal, sans-serif; text-transform: uppercase" >
                National Board of Revenue, Segunbagicha,Dhaka
                </font><br>
                <font
                style="font-size: 10px; color: #0B0B61; font-family: Impact, Charcoal, sans-serif; text-transform: uppercase">
                CARGO MANIFEST-FULL CARGO REPORT
                </font><br>
        </td>
        <td style="text-align: center;">
            <!-- <b>Date/Time:</b> <?php echo date("Y-m-d H:i:s"); ?> -->
            <table border="1" style="border-collapse:collapse;" align="center" width="100%">
               <tr>
                  <th style="text-align: left;">Voyage Number</th>
                  <td><?php  echo $ROTATIONDATA[0]['Voy_no']?></td>
               </tr>
               <tr>
                  <th style="text-align: left; width: 10%;">Date of</th>
                  <td style="width: 25%;"></td>
               </tr> 
               <tr>
                  <th style="text-align: left;">Date of Arrival</th>
                  <td><?php  echo $ROTATIONDATA[0]['date_of_arrival']?></td>
               </tr> 
               <tr>
                  <th style="text-align: left;">Vessel Name</th>
                  <td style="width: 10%;"><?php  echo $ROTATIONDATA[0]['VESSEL_NAME']?></td>
               </tr> 
               <tr>
                  <th style="text-align: left;">Flag</th>
                  <td><?php  echo (count($FLAGDATA)>0?$FLAGDATA[0]['CNTRY_NAME']:'') ?></td>
               </tr> 
               <tr>
                  <th style="text-align: left;">Shipping Line</th>
                  <td style="width: 25%;"><?php  echo $ROTATIONDATA[0]['SHIPING_AGENT_NAME']?></td>
               </tr>
               <tr>
                  <th style="text-align: left;">Reg. Num</th>
                  <td><?php  echo $ROTATIONDATA[0]['ROTATION']?></td>
               </tr>
               <tr>
                  <th style="text-align: left;">Reg. Date</th>
                  <td><?php  echo $ROTATIONDATA[0]['reg_date']?></td>
               </tr>
            </table>
        </td>
    </tr>
</table>
<?php 
     $i=0;
     foreach($ROTATIONDATA as $data){
        $i++;
     } 
        ?>
<table border="1" style="border-collapse:collapse;" align="center" width="100%">  
<tr> 
      <td>Office: Custom House, Chattogram</td>
      <td>Reg. Date : <?php  echo $ROTATIONDATA[0]['reg_date']?></td>
      <td>Date of Departture : </td>
      <td>Reg. Num : <?php  echo $ROTATIONDATA[0]['ROTATION']?> </td>
      <td>Voyage Num : <?php  echo $ROTATIONDATA[0]['Voy_no']?> </td>
      <td>Page : <?php  echo  $i ?></td>

    </tr>
</table>
  
<table id="myTable" border="1" style="border-collapse:collapse;" align="center" width="100%">    
    <thead> 
    <tr>
        <th style="text-align: center; font-size: 10px;width:15px;" rowspan="2">Loading Port</th>
        <th style="text-align: center; font-size: 10px; width:10px;" rowspan="2">Line</th>
        <th style="text-align: center; font-size: 10px; width:30px;" rowspan="2">B/L number Agents Code Agents Name</th>
        <th style="text-align: center; font-size: 10px; width:180px;" rowspan="2">Shipper Consignee Notify Number of Containers</th>
        <th style="text-align: center; font-size: 10px;" colspan="3">Description of Goods</th>
        <th style="text-align: center; font-size: 10px;" rowspan="2">DG Approval Status</th>
        <th style="text-align: center; width:50px; font-size: 10px;" colspan="2">BL Weight</th>
        </tr>
        <tr>
            <th>Container Number <br>Seal Number E/F Type<br> Offdock</th>
            <th>Number & Type of Packages</th>
            <th>Description of Goods <br>Shipping marks</th>
            <th>Ctn. Weight</th>
            <th>Cus.value</th>
       </tr>
    </thead>
     <?php 
     foreach($ROTATIONDATA as $data){ 
    ?>
        <tr>
            <td style="vertical-align: top;"><?php  echo $data['Port_of_Shipment'];?><br><?php  echo  (count($PALCENAME)>0?$PALCENAME[0]['PALCE_NAME']:'')?></td>
            <td style="vertical-align: top;"><?php  echo $data['LINE_NO'];?></td>
            <td style="vertical-align: top;"><?php  echo $data['BL_NO']; ?></td>
            <td style="vertical-align: top;">
                  <b>SH :</b><?php  echo $data['Exporter_name'];?><br><br>
                  <b>CN :</b><?php  echo $data['CONSIGNEE'];?><br><br>
                  <b>NY :</b><?php  echo $data['NOTIFY'];?><br><br>
                  <b>CT :</b>0
            </td>
            <td style="vertical-align: top; width:30px;"></td>
            <td style="vertical-align: top; width:30px;"><?php  echo $data['number_of_pack'];?> <br><br><?php  echo $data['PACKAGE_TYPE'];?></td>
            <td style="vertical-align: top; width:70px;" ><?php  echo $data['DESC_GOODS'];?></td>
            <td style="vertical-align: top;width:30px;"></td>
            <td style="vertical-align: top; width:30px;"><?php  echo $data['TOTAL_WEIGHT'];?></td>
            <td style="vertical-align: top; width:30px;"></td>
        </tr>
    </tbody>
    <?php } ?>
</table>
<?php endif; ?>