<table border="0" style="border-collapse:collapse;" align="center" width="85%">
    <tr>
        <td style="width: 25%; text-align: right" ;>
            <img src="<?php echo  ASSETS_WEB_PATH?>fimg/cpaLogo.png" alt="Logo" height="50">
        </td>
        <td style="width: 50%; text-align: center">
            <p
                style="font-size:16px; color: #0B0B61; font-family: Impact, Charcoal, sans-serif; text-transform: uppercase">
                <b>Chittagong Port Authority</b>
            </p>
            <p
                style="font-size: 10px; color: #0B0B61; font-family: Impact, Charcoal, sans-serif; text-transform: uppercase">
               BB Import Rotation Wise Onboard Cargo Report
            </p>
        </td>
        <td style="width: 10%; font-size: 10px; text-align: right; vertical-align: bottom;">
            <b>Date/Time:</b> <?php echo date("Y-m-d H:i:s"); ?>
        </td>
    </tr>
</table>

<table border="0" style="border-collapse:collapse;" align="center" width="85%">
    <tr>
        <td style="width: 10%; font-size: 10px; text-align: left;">
            <span><b>VESSEL NAME :</b></span><span> <b><?php  echo $ROTATIONIGMDATA[0]['VESSEL_NAME']?></b></span>
        </td>
        <td style="width: 10%; font-size: 10px; text-align: center;">
            <span><b>ROTATION :</b></span><span> <b><?php  echo $ROTATIONDATA[0]['ROTATION']?></b></span>
        </td>

        <td style="width: 10%; font-size: 10px; text-align: right;">
            <span><b>Birth Op :</b></span><span> <b><?php  echo $ROTATIONDATA[0]['BOPTR']?></b></span>
        </td>
       
    </tr>
    <tr>
        <td style="width: 10%; font-size: 10px; text-align: left;">
            <span><b>ATA :</b></span><span> <b><?php  echo $ROTATIONDATA[0]['ATA']?></b></span>
        </td>
        <td style="width: 10%; font-size: 10px; text-align: center;">
            <span><b>ATD :</b></span><span> <b><?php  echo $ROTATIONDATA[0]['ATD']?></b></span>
        </td>

        <td style="width: 10%; font-size: 10px; text-align: right;">
            <span><b>Birth :</b></span><span> <b><?php  echo $ROTATIONDATA[0]['BERTH']?></b></span>
        </td>
       
    </tr>
</table>
<table border="1" style="border-collapse:collapse;" align="center" width="85%">    
    <thead>
        <tr>
            <th style="text-align: center; font-size: 10px;">SL</th>
            <th style="text-align: center; font-size: 10px;">BL NUMBER</th>
            <th style="text-align: center; width:180px; font-size: 10px;">UNIT NUMBER</th>
            <th style="text-align: center; width:50px; font-size: 10px;">AGENT CODE</th>
            <th style="text-align: center; font-size: 10px;">LINE NO </th>
            <th style="text-align: center; font-size: 10px;">MARKS AND NUMBER </th>
            <th style="text-align: center; font-size: 10px;">DESCRIPTION OF GOODS </th>
            <th style="text-align: center; font-size: 10px;">IMPORTER </th>
            <th style="text-align: center; font-size: 10px;">EXPORTER </th>
            <th style="text-align: center; font-size: 10px;  width:50px;">PACKAGE TYPE</th>
            <th style="text-align: center; font-size: 10px;">QUENTITY</th>
            <th style="text-align: center; font-size: 10px;">PACKAGE WEIGHT</th>
            
        </tr>
    </thead>
    <?php 
    $SL =0;
    $TOTAL_QTY =0;
    $TOTAL_WEIGHT =0;
    
     foreach($ROTATIONDATA as $data){
      $SL++;
      $TOTAL_QTY = $TOTAL_QTY+ $data['LOT_QTY'];
      $TOTAL_WEIGHT = $TOTAL_WEIGHT+$data['LOT_WEIGHT'];



      $ACTUAL_BL = $data['BL'];
      $pos = strpos($ACTUAL_BL, '_');
      if($pos === false) {
      }else{
             $PARTS = explode('_', $ACTUAL_BL);
             $ACTUAL_BL = $PARTS[1];
        }
   

      ?>
    <tbody>
        <tr>
            <td style="text-align: center;"><?php echo $SL;?></td>
            <td style="text-align: center;"><?php echo $ACTUAL_BL;?></td>
            <td style="text-align: center;"><?php echo $data['UNIT_NUMBER'];?></td>
            <td style="text-align: center;"><?php echo $data['AGENT_CODE']; ?></td>
           
            <?php  
            
             foreach($ROTATIONIGMDATA as $dataIGM){
            

             if($ACTUAL_BL.trim() == $dataIGM['BL_NO'].trim()){
               ?>
               <td style="text-align: center;"><?php  echo $dataIGM['LINE_NO'];?></td>
               <td style="text-align: center;"><?php  echo $dataIGM['PACK_MARKS'];?></td>
               <td style="text-align: center;"><?php  echo $dataIGM['DESC_GOODS'];?></td>
               <td style="text-align: center;"><?php  echo $dataIGM['NOTIFY'];?></td>
               <td style="text-align: center;"><?php  echo $dataIGM['CONSIGNEE'];?></td>
               <td style="text-align: center;"><?php  echo $dataIGM['PACKAGE_TYPE'];?></td>
             
           <?php  } 
              }
           ?>
            <td style="text-align: center;"><?php echo $data['LOT_QTY'];?></td>
            <td style="text-align: center;"><?php echo $data['LOT_WEIGHT'];?></td>
           
        </tr>
              


    </tbody>
    <?php } 
    if($SL >0 ){?>
     <tr>
        <td colspan="10" style="text-align: right;"><b>Total</b></td>
        <td style="text-align: center;"><b><?php  echo $TOTAL_QTY ;?></b></td>
        <td style="text-align: center;"><b><?php  echo $TOTAL_WEIGHT; ?></b></td>
       
     </tr>
   <?php } ?>
</table>
