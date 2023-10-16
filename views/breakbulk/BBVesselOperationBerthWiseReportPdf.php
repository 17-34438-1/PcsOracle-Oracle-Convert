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
               Berth Wise Vessel Report
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
            <span><b>FROM DATE :</b></span><span> <b><?php  echo $FROM_DATE; ?></b></span>
        </td>
        <td style="width: 10%; font-size: 10px; text-align: right;">
            <span><b>TO DATE :</b></span><span> <b><?php  echo $TO_DATE;?></b></span>
        </td>

       
       
    </tr>
</table>
<table border="1" style="border-collapse:collapse;" align="center" width="85%">    
    <thead>
        <tr>
            <th style="text-align: center; font-size: 10px;">SL</th>
            <th style="text-align: center; font-size: 10px;">Vessel Name</th>
            <th style="text-align: center; font-size: 10px;">Rotation</th>
            <th style="text-align: center; font-size: 10px;">Berth No</th>
            <th style="text-align: center; font-size: 10px;">Berth Name </th>
            <th style="text-align: center; font-size: 10px;">ATA </th>
            <th style="text-align: center; font-size: 10px;">ATD </th>
            <th style="text-align: center; font-size: 10px;">PHASE </th>
            <th style="text-align: center; font-size: 10px;">TOTAL QUENTITY </th>
            <th style="text-align: center; font-size: 10px;">TOTAL WEIGHT </th>
                
        </tr>
    </thead>
    <?php 
    $SL =0;
     foreach($VESSELDATA as $data){
      $SL++;
  

      ?>
    <tbody>
        <tr>
            <td style="text-align: center;"><?php echo $SL;?></td>
            <td style="text-align: center;"><?php echo $data['NAME'];?></td>
            <td style="text-align: center;"><?php echo $data['IB_VYG'];?></td>
            <td style="text-align: center;"><?php echo $data['BERTH']; ?></td>
            <td style="text-align: center;"><?php echo $data['BOPTR']?></td>
            <td style="text-align: center;"><?php echo $data['ATA'];?></td>
            <td style="text-align: center;"><?php echo $data['ATD'];?></td>
            <td style="text-align: center;"><?php echo $data['PHASE'];?></td>
           
            <?php  
             $isFound = 0;
             foreach($VESSELIGMDATA as $dataIGM){
            
             if($data['IB_VYG'] == $dataIGM['Import_Rotation_No'].trim()){
                $isFound = 1;
               ?>
               <td style="text-align: center;"><?php  echo $dataIGM['TOTAL_QUENTITY'];?></td>
               <td style="text-align: right;"><?php  echo $dataIGM['TOTAL_WEIGHT'];?></td>
                   
           <?php  } 
              } 
              if( $isFound === 0){ 
              ?>
               <td style="text-align: center;"></td>
               <td style="text-align: center;"></td>
            

               <?php }
           ?>
            
        </tr>
              


    </tbody>
    <?php } 
     ?>
</table>
