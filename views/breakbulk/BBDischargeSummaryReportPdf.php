<?php if(count($DSData)>0) : ?>
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
                Cargo Vessel Discharge Summary Report
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
            <span><b>VESSEL NAME :</b></span><span> <b><?php  echo $DSData[0]['VESSEL_NAME']?></b></span>
        </td>
        <td style="width: 10%; font-size: 10px; text-align: right;">
            <span><b>ROTATION :</b></span><span> <b><?php  echo $DSData[0]['ROTATION']?></b></span>
        </td>
    </tr>
</table>
                     <?php 
                       $totalQty=0;
                       $totalWt=0;
                       $i=1;
                       foreach($DSData as $data){ 
                        $totalQty=$totalQty+$data['TOTAL_QTY'];
                        $totalWt=$totalWt+$data['TOTAL_WEIGHT'];
                         } 
                     ?>
<table border="1" style="border-collapse:collapse;" align="center" width="85%">    
    <thead>
        <tr>
            <th>SL</th>
            <th style="text-align: center; width:180px; font-size: 10px;">SHIPPING AGENT NAME</th>
            <th style="text-align: center; width:50px; font-size: 10px;">SHIPPING AGENT CODE</th>
            <th style="text-align: center; font-size: 10px;">BL</th>
            <th style="text-align: center; font-size: 10px;">PORT OF ORGIN</th>
            <th style="text-align: center; font-size: 10px;">PACKAGE TYPE</th>
            <th style="text-align: center; font-size: 10px;  width:50px;">QUENTITY</th>
            <th style="text-align: center; font-size: 10px;">WEIGHT </th>
        </tr>
    </thead>
    <?php foreach($DSData as $data){?>
    <tbody>
        <tr>
            <td><?php  echo $i++?></td>
            <td style="text-align: center;"><?php  echo $data['SHIPING_AGENT_NAME']?></td>
            <td style="text-align: center;"><?php  echo $DSAgentData[0]['AGENT_CODE']?></td>
            <td style="text-align: center;"><?php  echo $data['BL_NO']?></td>
            <td style="text-align: center;"><?php  echo $data['PORT_OF_ORIGIN']?></td>
            <td style="text-align: center;"><?php  echo $data['PACKAGE_TYPE']?></td>
            <td style="text-align: center;"><?php  echo $data['TOTAL_QTY']?></td>
            <td style="text-align: center;"><?php  echo $data['TOTAL_WEIGHT']?></td>
        </tr>
    </tbody>
    <?php } ?>
    <tr>
        <td colspan="6" style="text-align: right;"><b>Total</b></td>
        <td style="text-align: center;"><b><?php  echo $totalQty?></b></td>
        <td style="text-align: center;"><b><?php  echo $totalWt?></b></td>
    </tr>
</table>
<?php endif; ?>