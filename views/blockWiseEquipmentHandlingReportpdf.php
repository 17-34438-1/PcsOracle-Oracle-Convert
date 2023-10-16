<html>
	<body>
		<div class="pagewidth">
            <table border="0" cellspacing="0" width="100%">
                <tr>
                    <td colspan="4" align="center"><img src="<?php echo IMG_PATH?>cpanew.jpg"></td>
                </tr>
                <tr>
                    <td colspan="4" align="center">
                        <h3>BlockWise Equipment Handling Report<br/>
                        Date: <?php echo $fromDate." to ".$toDate; ?> <br/>
                        Yard: <?php echo $yard; ?> | Block: <?php echo $block; ?> | Equipment: <?php echo $equipment; ?>
                        </h3>
                    </td>
                </tr>
            </table>

            <table border="1" cellspacing="0" width="60%" align="center">
                <thead>
                    <tr>
                        <th width="7%" rowspan="2">SL. No.</th>
                        <th rowspan="2">Block No</th>
                        <th rowspan="2">Equipment Name</th>
                        <th rowspan="2">Import</th>
                        <th rowspan="2">Export</th>
                        <th colspan="2">Total</th>
                    </tr>
                    <tr>
                        <th>Box</th>
                        <th>Teus</th>
                    </tr>
                </thead>

                <?php
                    $block_no = "";
                    $full_name = "";
                    $import = "";
                    $totalImport = 0;
                    $export = "";
                    $totalExport = 0;
                    $box = 0;
                    $totalBox = 0;
                    $con20 = 0;
                    $con40 = 0;
                    $teus = 0;
                    $totalTeus = 0;

                    for($i=0;$i<count($rslt);$i++)
                    {
                     
                        $block_no = $rslt[$i]['SEL_BLOCK'];
                        $full_name = $rslt[$i]['FULL_NAME'];
                        $import = $rslt[$i]['IMPRT'];
                        $totalImport += $import;
                        $export = $rslt[$i]['EXPRT'];
                        $totalExport += $export;
                        $box = $import+$export;
                        $totalBox += $box;
                        $con20 = $rslt[$i]['CON20'];
                        $con40 = $rslt[$i]['CON40'];
                        $teus = ($con20*1)+($con40*2);
                        $totalTeus += $teus;
                ?>
                <tr>
                    <td align="center"><?php echo $i+1; ?></td>
                    <td align="center"><?php echo $block_no; ?></td>
                    <td align="center"><?php echo $full_name; ?></td>
                    <td align="center"><?php echo $import; ?></td>
                    <td align="center"><?php echo $export; ?></td>
                    <td align="center"><?php echo $box; ?></td>
                    <td align="center"><?php echo $teus; ?></td>
                </tr>
                <?php
                    }
                ?>
                <tr>
                    <th align="right" colspan="3">Total: </th>
                    <th align="center"><?php echo $totalImport; ?></th>
                    <th align="center"><?php echo $totalExport; ?></th>
                    <th align="center"><?php echo $totalBox; ?></th>
                    <th align="center"><?php echo $totalTeus; ?></th>
                </tr>
            </table>
            
        </div>
	</body>
</html>