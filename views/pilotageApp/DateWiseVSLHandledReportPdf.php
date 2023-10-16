
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
            <th style="text-align: center; font-size: 10px;">VESSEL NAME</th>
            <th style="text-align: center; font-size: 10px;">ROTATION</th>
            <th style="text-align: center; font-size: 10px;">DATE</th>
            
            
        </tr>
    </thead>
   
    <tbody>

    <?php  
    $SL =0;
    if(count($PILOTDATA>0)){
     foreach($PILOTDATA as $data){
      $SL++; ?>
        <tr>
            <td><?php echo $SL;?></td>
            <td style="text-align: center;"><?php echo $data['pilot_name'];?></td>
            <td style="text-align: center;"><?php echo $data['u_name'];?></td>
            <td style="text-align: center;"><?php echo $data['vsl_name'];?></td>
            <td style="text-align: center;"><?php echo $data['rotation'];?></td>
            <td style="text-align: center;"><?php echo $data['entry_date'];?></td>
        </tr>
        <?php }} ?>
    </tbody>
</table>
