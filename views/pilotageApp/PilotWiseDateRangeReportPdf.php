
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
                <?php  echo $title?>
            </p>
        </td>
        <td style="width: 10%; font-size: 10px; text-align: right; vertical-align: bottom;">
            <b>Date/Time:</b> <?php echo date("Y-m-d H:i:s"); ?>
        </td>
    </tr>
</table>


             
<table border="1" style="border-collapse:collapse;" align="center" width="100%">    
    <thead>
        <tr>
            <th>SL</th>
            <th style="text-align: center; font-size: 10px;">USER ID</th>
            <th style="text-align: center; width:200px; font-size: 10px;">NAME</th>
            <th style="text-align: center; font-size: 10px;">TOTAL INCOMING VESSEL HANDELED</th>
            <th style="text-align: center; font-size: 10px;">TOTAL SHIFTING VESSEL HANDELED</th>
            <th style="text-align: center; font-size: 10px;">TOTAL OUTGOING VESSEL HANDELED</th>
            <th style="text-align: center; font-size: 10px;">TOTAL CANCEL VESSEL HANDELED</th>
            <th style="text-align: center; width:50px; font-size: 10px;">TOTAL</th>
            
        </tr>
    </thead>
   
    <tbody>

    <?php  
    $SL =0;
    $TOTAL=0;
    $GRANDTOTAL=0;
    $TOTALARRIVAL=0;
    $TOTALDEPART=0;
    $TOTALSHIFT=0;
    $TOTALCANCEL=0;
     foreach($PILOTDATA as $data){
      $SL++;
      $TOTAL=$data['total_doc_vsl_arrival']+$data['total_doc_vsl_shift']+$data['total_doc_vsl_depart']+$data['total_doc_vsl_cancel'];
      $TOTALARRIVAL+=$data['total_doc_vsl_arrival']; 
      $TOTALSHIFT+=$data['total_doc_vsl_shift']; 
      $TOTALDEPART+=$data['total_doc_vsl_depart'];   
      $TOTALCANCEL+=$data['total_doc_vsl_cancel'];
      
      $GRANDTOTAL+=$TOTAL;
          
          ?>
        <tr>
            <td><?php echo $SL;?></td>
            <td style="text-align: center;"><?php echo $data['pilot_name'];?></td>
            <td style="text-align: center;"><?php echo $data['u_name'];?></td>
            <td style="text-align: center;"><?php echo $data['total_doc_vsl_arrival'];?></td>
            <td style="text-align: center;"><?php echo $data['total_doc_vsl_shift'];?></td>
            <td style="text-align: center;"><?php echo $data['total_doc_vsl_depart'];?></td>
            <td style="text-align: center;"><?php echo $data['total_doc_vsl_cancel'];?></td>
            <td style="text-align: center;"><?php echo $TOTAL;?></td>
        </tr>
        <?php } ?>
    </tbody>
    
    <tr>
        <td colspan="3" style="text-align: right;"><b>Grand Total</b></td>
        <td style="text-align: center;"><b><?php echo $TOTALARRIVAL;?></b></td>
        <td style="text-align: center;"><b><?php echo $TOTALSHIFT;?></b></td>
        <td style="text-align: center;"><b><?php echo $TOTALDEPART;?></b></td>
        <td style="text-align: center;"><b><?php echo $TOTALCANCEL;?></b></td>
        <td style="text-align: center;"><b><?php echo $GRANDTOTAL;?></b></td>
    </tr>
</table>
