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
                OFFICE OF THE DEPUTY TRAFFIC MANAGER (OPS)
            </p>
            <p
                style="font-size: 7px; color: #0B0B61; font-family: Impact, Charcoal, sans-serif; text-transform: uppercase">
                PERFORMANCE OF GENERAL CARGO VESSEL DURING LAST 24 HRS.CLOSING AT 8.00 HRS. ON
                <?php  echo $TO_DATE;?> IN TERMS OF M.TON
            </p>
        </td>
        <td style="width: 10%; font-size: 10px; text-align: right; vertical-align: bottom;">
            <b>Date/Time:</b> <?php echo date("Y-m-d H:i:s"); ?>
        </td>
    </tr>
</table>
<table border="1" style="border-collapse:collapse;" align="center" width="85%">
    <thead>
        <tr>
            <th style="text-align: center; font-size: 10px;" rowspan="3">BERTH NO</th>
            <th style="text-align: center; font-size: 10px;" rowspan="3">NAME OF VESSEL</th>
            <th style="text-align: center; font-size: 10px;" rowspan="3">LENGTH</th>
            <th style="text-align: center; font-size: 10px;" rowspan="3">LAST PORT</th>
            <th style="text-align: center; font-size: 10px;" rowspan="3">SHIPPING AGENT</th>
            <th style="text-align: center; font-size: 10px;" rowspan="3">DESCRIPTION OF GOODS </th>
            <th style="text-align: center; font-size: 10px;" rowspan="3">BEARTH OPERATOR </th>
            <th style="text-align: center; font-size: 10px;" colspan="2">ARRIVAL</th>
            <th style="text-align: center; font-size: 10px;" colspan="2">SALING</th>
            <th style="text-align: center; font-size: 10px;" colspan="10">IMPORT</th>
            <th style="text-align: center; font-size: 10px;" colspan="6">EXPORT</th>
            <th style="text-align: center; font-size: 10px;" rowspan="3">Program</th>
        </tr>
        <tr>
            <th style="text-align: center; font-size: 10px;">DT </th>
            <th style="text-align: center; font-size: 10px;">TIME </th>
            <th style="text-align: center; font-size: 10px;">DT </th>
            <th style="text-align: center; font-size: 10px;">TIME</th>
            <th style="text-align: center; font-size: 10px;" colspan="4">LAST 24 HOURS DISCHARGE </th>
            <th style="text-align: center; font-size: 10px;" colspan="4">TOTAL DISCHARGE </th>
            <th style="text-align: center; font-size: 10px;" colspan="2">BALANCE ON BOARD </th>
            <th style="text-align: center; font-size: 10px;" colspan="4"> LAST 24 HOURS DISCHARGE </th>
            <th style="text-align: center; font-size: 10px;" colspan="2">BALANCE TO BE SHIFTED </th>
        </tr>
        <tr>
            <th style="text-align: center; font-size: 10px;"></th>
            <th style="text-align: center; font-size: 10px;"></th>
            <th style="text-align: center; font-size: 10px;"></th>
            <th style="text-align: center; font-size: 10px;"></th>
            <th style="text-align: center; font-size: 10px;">QTY</th>
            <th style="text-align: center; font-size: 10px;">JETTY SIDE</th>
            <th style="text-align: center; font-size: 10px;">OVER SIDE</th>
            <th style="text-align: center; font-size: 10px;">TOTAL WEIGHT </th>
            <th style="text-align: center; font-size: 10px;">QTY</th>
            <th style="text-align: center; font-size: 10px;">JETTY SIDE</th>
            <th style="text-align: center; font-size: 10px;">OVER SIDE</th>
            <th style="text-align: center; font-size: 10px;">TOTAL WEIGHT </th>
            <th style="text-align: center; font-size: 10px;">QTY</th>
            <th style="text-align: center; font-size: 10px;">TOTAL WEIGHT </th>
            <th style="text-align: center; font-size: 10px;">QTY</th>
            <th style="text-align: center; font-size: 10px;">JETTY SIDE</th>
            <th style="text-align: center; font-size: 10px;">OVER SIDE</th>
            <th style="text-align: center; font-size: 10px;">TOTAL WEIGHT </th>
            <th style="text-align: center; font-size: 10px;">QTY</th>
            <th style="text-align: center; font-size: 10px;">TOTAL WEIGHT </th>
        </tr>
    </thead>
    <tbody>
    <?php 
    
     foreach($VESSELDATA as $data){  ?>
        <tr>
            <td style="text-align: center;"><?php  echo $data['BERTH']?></td>
            <td style="text-align: center;"><?php  echo $data['NAME']?></td>
            <td style="text-align: center;"></td>
            <td style="text-align: center;"></td>
            <td style="text-align: center;"><?php  echo $data['AGENT_CODE']?></td>
            <td style="text-align: center;"></td>
            <td style="text-align: center;"><?php  echo $data['BERTHOP']?></td>
            <td style="text-align: center;"><?php  echo $data['ATADATE']?></td>
            <td style="text-align: center;"><?php  echo $data['ATATIME']?></td>
            <td style="text-align: right;"><?php  echo $data['ATDDATE']?></td>

            <td style="text-align: center;"><?php  echo $data['ATDTIME']?></td>
            <td style="text-align: center;"></td>
            <td style="text-align: center;"></td>
            <td style="text-align: center;"></td>
            <td style="text-align: center;"></td>
            <td style="text-align: center;"></td>
            <td style="text-align: center;"></td>
            <td style="text-align: center;"></td>
            <td style="text-align: center;"></td>
            <td style="text-align: right;"></td>

            <td style="text-align: center;"></td>
            <td style="text-align: center;"></td>
            <td style="text-align: center;"></td>
            <td style="text-align: center;"></td>
            <td style="text-align: center;"></td>
            <td style="text-align: center;"></td>
            <td style="text-align: center;"></td>
            <td style="text-align: center;"></td>
            <td style="text-align: center;"></td>
            <td style="text-align: right;"></td>
        </tr>

        <?php  }  ?>
    </tbody>

</table>

<table border="1" style="border-collapse:collapse;" align="center" width="100%">
    <thead>
        <tr>
        <th style="text-align: center; font-size: 10px;" colspan="7">GENERAL CARGO VESSELS READY FOR INCOMING</th>
      </tr>
        <tr>
            <th style="text-align: center; font-size: 10px;" rowspan="2">BERTH</th>
            <th style="text-align: center; font-size: 10px;" rowspan="2">NAME OF VESSEL</th>
            <th style="text-align: center; font-size: 10px;" colspan="2">AT O/A</th>
            <th style="text-align: center; font-size: 10px;" colspan="2">INDUCEMENT</th>
            <th style="text-align: center; font-size: 10px;" rowspan="2">TIDE</th>
        </tr>
        <tr>
            <th style="text-align: center; font-size: 10px;" >DATE </th>
            <th style="text-align: center; font-size: 10px;" >TIME </th>
            <th style="text-align: center; font-size: 10px;" >IMPORT </th>
            <th style="text-align: center; font-size: 10px;" >EXPORT </th>
        </tr>
        <thead>
        <tbody>
            <tr>
            <td style="text-align: center;"></td>
            <td style="text-align: center;"></td>
            <td style="text-align: center;"></td>
            <td style="text-align: center;"></td>
            <td style="text-align: center;"></td>
            <td style="text-align: center;"></td>
            <td style="text-align: center;"></td>
            </tr>
            <tr>
            <th style="text-align: center;" colspan="4"  rowspan="4">TOTAL VESSEL AT BERTH</th>
            <th style="text-align: left;" colspan="2">IMPORT</th>
            <td style="text-align: center;">4</td>
            </tr>
            <tr>
            <th style="text-align: left;" colspan="2">EXPORT</th>
            <td style="text-align: center;">4</td>
            </tr>
            <tr>
            <th style="text-align: left;" colspan="2">IMPORT+EXPORT</th>
            <td style="text-align: center;">4</td>
            </tr>
            <tr>
            <th style="text-align: left;" colspan="2">W.SAIL</th>
            <td style="text-align: center;">4</td>
            </tr>

        </tbody>
</table>          