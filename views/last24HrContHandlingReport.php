<table cellspacing="1" cellpadding="1" align="center">
    <tr>
        <th colspan="13" align="center"><font size="5"><img src="<?php echo IMG_PATH?>cpanew.jpg" /></th>
    </tr>
    <tr>
        <th colspan="13" align="center"><font size="5">CHITTAGONG PORT AUTHORITY</font><br>CTMS Control at <?php echo $yard; ?></th>
    </tr>
    <tr>
        <td colspan="13" align="center"><font size="4">Last 24 hrs Yardwise import, export and delivery keepdown position in <?php echo $yard; ?><br>Date:  <?php echo $date; ?></font></td>
    </tr>
</table>

<table border="1" cellspacing="0" cellpadding="1" align="center" width="60%">
    <tr>
        <th>SL</th>
        <th>Yard No</th>
        <th>Total Import</th>
        <th>Total Export</th>
        <th>Total Keepdown</th>
    </tr>

    <?php
        $block = "";
        $imp20 = "";
        $totalImp20 = 0;
        $imp40 = "";
        $totalImp40 = 0;
        $keepDlv = "";
        $impteus = "";
        $totalTeus = 0;
        for($i=0;$i<count($mainResultList);$i++){
            $block = $mainResultList[$i]['Block_No'];
            $imp20 = $mainResultList[$i]['imp20'];
            $totalImp20+=$imp20;
            $imp40 = $mainResultList[$i]['imp40'];
            $totalImp40+=$imp40;
            $keepDlv = $mainResultList[$i]['keepDlv'];
            $impteus = $mainResultList[$i]['impteus'];
            $totalTeus+=$impteus;
    ?>

    <tr>
        <th><?php echo $i+1; ?></th>
        <th><?php echo $block; ?></th>
        <th><?php 
                if($imp20>0){
                    echo $imp20."*20\"";
                } 

                if($imp20>0 && $imp40>0){
                    echo ", ";
            ?>
            &nbsp;
            <?php
                }

                if($imp40>0){
                    echo $imp40."*40\"";
                }
            ?>
        </th>
        <th>&nbsp;</th>
        <th><?php echo $keepDlv; ?></th>
    </tr>
    <?php
        }
    ?>
</table>
<br/>
<table align="center">
    <tr>
        <th>Last 24hrs Total Cont. Handling:</th>
        <th align="left"><?php echo $totalImp40."*40\" (box)"; ?></th>
    </tr>
    <tr>
        <th>&nbsp;</th>
        <th align="left"><?php echo $totalImp20."*20\" (box)"; ?></th>
    </tr>
    <tr>
        <th>&nbsp;</th>
        <th align="left"><?php echo $totalTeus." (teus)" ?></th>
    </tr>
</table>
					