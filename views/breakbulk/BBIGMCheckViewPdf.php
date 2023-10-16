<?php if(count($ROTATIONDATA)>0) : ?>
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
                BB IGM Check Report
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
            <span><b>VESSEL NAME :</b></span><span> <b><?php  echo $ROTATIONDATA[0]['VESSEL_NAME']?></b></span>
        </td>
        <td style="width: 10%; font-size: 10px; text-align: right;">
            <span><b>ROTATION :</b></span><span> <b><?php  echo $ROTATIONDATA[0]['ROTATION']?></b></span>
        </td>
    </tr>
</table>
<table border="1" style="border-collapse:collapse;" align="center" width="85%">    
    <thead>
        <tr>
         
             <th style="text-align: center; font-size: 10px;">BL NUMBER</th>
            <th style="text-align: center; width:180px; font-size: 10px;">SHIPPING AGENT NAME</th>
            <th style="text-align: center; width:50px; font-size: 10px;">SHIPPING AGENT CODE</th>
          
            <th style="text-align: center; font-size: 10px;">LINE NO</th>
            <th style="text-align: center; font-size: 10px;">PACK NUMBER</th>
            <th style="text-align: center; font-size: 10px;  width:50px;">PACK TYPE</th>
            <th style="text-align: center; font-size: 10px;">PACK MARKS NUMBER </th>
            <th style="text-align: center; font-size: 10px;">GROSS WEIGHT </th>
            <th style="text-align: center; font-size: 10px;">DESCRIPTION OF GOODS </th>
            <th style="text-align: center; font-size: 10px;">CONSIGNEE</th>
            <th style="text-align: center; font-size: 10px;">NOTIFY</th>
        
        </tr>
    </thead>
    <?php 
     foreach($ROTATIONDATA as $data){?>
    <tbody>
        <tr>
            <td style="text-align: center;"><?php  echo $data['BL_NO'];?></td>
            <td style="text-align: center;"><?php  echo $data['SHIPING_AGENT_NAME'];?></td>
            <td style="text-align: center;"><?php  echo $data['AGENT_CODE']; ?></td>
            <td style="text-align: center;"><?php  echo $data['LINE_NO'];?></td>
            <td style="text-align: center;"><?php  echo $data['QUENTITY'];?></td>
            <td style="text-align: center;"><?php  echo $data['PACKAGE_TYPE'];?></td>
            <td style="text-align: center;"><?php  echo $data['PACK_MARKS'];?></td>
            <td style="text-align: center;"><?php  echo $data['TOTAL_WEIGHT'];?></td>
            <td style="text-align: center;"><?php  echo $data['DESC_GOODS'];?></td>
            <td style="text-align: center;"><?php  echo $data['CONSIGNEE'];?></td>
            <td style="text-align: center;"><?php  echo $data['NOTIFY'];?></td>
        </tr>
    </tbody>
    <?php } ?>

    
</table>
<?php endif; ?>