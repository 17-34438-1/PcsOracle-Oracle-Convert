<HTML>

<HEAD>
    <TITLE>BLOCKED CONTAINER LIST</TITLE>
    <LINK href="../css/report.css" type=text/css rel=stylesheet>
    <style type="text/css">

    </style>
</HEAD>

<BODY>


    <html>
    <title>OFFDOC Blocked Container List</title>


    <body>
        <table width="100%" border='0' cellpadding='0' cellspacing='0'>
            <tr bgcolor="#ffffff" align="center" height="100px">
                <td colspan="13" align="center">
                    <table border=0 width="100%">

                        <tr align="center">
                            <td colspan="12"><img align="middle" width="235px" height="75px"
                                    src="<?php echo IMG_PATH?>cpanew.jpg"></td>
                        </tr>

                        <tr align="center">
                            <td colspan="12">
                                <font size="4"><b><u><?php echo $UserName?></u></b></font>
                            </td>
                        </tr>
                        <tr align="center">
                            <td colspan="12">
                                <font size="4"><b></b></font>
                            </td>
                        </tr>



                    </table>

                </td>

            </tr>

            <tr bgcolor="#ffffff" align="center" height="25px">
                <td colspan="15" align="center"></td>

            </tr>
        </table>
        <table width="100%" border='1' cellpadding='0' cellspacing='0'>
            <tr bgcolor="#A9A9A9" align="center" height="25px">
                <td style="border-width:3px;border-style: double;"><b>SlNo.</b></td>
                <td style="border-width:3px;border-style: double;"><b>Container No.</b></td>
                <td style="border-width:3px;border-style: double;"><b>Vessel Name.</b></td>
                <td style="border-width:3px;border-style: double;"><b>Rotation.</b></td>
                <td style="border-width:3px;border-style: double;"><b>Size.</b></td>
                <td style="border-width:3px;border-style: double;"><b>Height.</b></td>
                <td style="border-width:3px;border-style: double;"><b>MLO</b></td>
                <td style="border-width:3px;border-style: double;"><b>Status</b></td>
                <td style="border-width:3px;border-style: double;"><b>Position</b></td>
                <td style="border-width:3px;border-style: double;"><b>IN Date</b></td>
                <td style="border-width:3px;border-style: double;"><b>Out Date</b></td>
                <td style="border-width:3px;border-style: double;"><b>Total Days</b></td>
            </tr>

            <?php
         include("dbConection.php");
         include("dbOracleConnection.php");
		
	//test purpose comment
	 $query=mysqli_query($con_sparcsn4,"SELECT *
					 FROM ctmsmis.mis_block_unit 
					 WHERE ctmsmis.mis_block_unit.offdoc_name ='$UserName'");
					
					

	
	
			
	$i=0;
	$j=0;
	
	$mlo="";
	while($row=mysqli_fetch_object($query)){
		
	$i++;
	$sqlvsl_name=mysqli_query($con_sparcsn4,"SELECT vsl_name FROM ctmsmis.mis_inv_unit WHERE id='$row->cont_id'");
	$rtnContvsl_name=mysqli_fetch_object($sqlvsl_name);
	$convsl_name=$rtnContvsl_name->vsl_name;
	$sqlib_vyg=mysqli_query($con_sparcsn4,"SELECT vsl_visit_dtls_ib_vyg FROM ctmsmis.mis_inv_unit WHERE id='$row->cont_id'");
	$rtnContib_vyg=mysqli_fetch_object($sqlib_vyg);
	$conib_vyg=$rtnContib_vyg->vsl_visit_dtls_ib_vyg;
	$sqlsize=mysqli_query($con_sparcsn4,"SELECT size FROM ctmsmis.mis_inv_unit WHERE id='$row->cont_id'");
	$rtnContsize=mysqli_fetch_object($sqlsize);
	$consize=$rtnContsize->size;
	$sqlheight=mysqli_query($con_sparcsn4,"SELECT height FROM ctmsmis.mis_inv_unit WHERE id='$row->cont_id'");
	$rtnContheight=mysqli_fetch_object($sqlheight);
	$conheight=$rtnContheight->height;
	$sqlmlo=mysqli_query($con_sparcsn4,"SELECT mlo FROM ctmsmis.mis_inv_unit WHERE id='$row->cont_id'");
	$rtnContmlo=mysqli_fetch_object($sqlmlo);
	$contmlo=$rtnContmlo->mlo;

	
	
	$freightQuary="SELECT freight_kind FROM inv_unit WHERE id='$row->cont_id' ORDER BY inv_unit.gkey DESC fetch first 1 rows only";
	$sqlfreight_kind = oci_parse($con_sparcsn4_oracle, $freightQuary);
    oci_execute($sqlfreight_kind);
	$rtnfreight_kind=oci_fetch_object($sqlfreight_kind);              
	$contfreight_kind=$rtnfreight_kind->FREIGHT_KIND;
	
	$lastPosQuaryN4="SELECT inv_unit_fcy_visit.last_pos_name
	FROM inv_unit 
	INNER JOIN inv_unit_fcy_visit ON inv_unit_fcy_visit.unit_gkey=inv_unit.gkey
	WHERE id='$row->cont_id' ORDER BY inv_unit.gkey DESC fetch first 1 rows only";
	$sqlpostion= oci_parse($con_sparcsn4_oracle, $lastPosQuaryN4);
    oci_execute($sqlpostion);
	$rtnConpostion=oci_fetch_object($sqlpostion); 
    $contpostion=$rtnConpostion->LAST_POS_NAME;

	
	$timeOutQuN4="SELECT inv_unit_fcy_visit.time_out
	FROM inv_unit 
	INNER JOIN inv_unit_fcy_visit ON inv_unit_fcy_visit.unit_gkey=inv_unit.gkey
	WHERE id='$row->cont_id' ORDER BY inv_unit.gkey DESC fetch first 1 rows only";
	$sqltime_out=oci_parse($con_sparcsn4_oracle,$timeOutQuN4);
	oci_execute($sqltime_out);
	$rtnContime_out=oci_fetch_object($sqltime_out);
	$conttime_out=$rtnContime_out->TIME_OUT;
	
	

	$timeInQuN4="SELECT inv_unit_fcy_visit.time_in
	FROM inv_unit 
	INNER JOIN inv_unit_fcy_visit ON inv_unit_fcy_visit.unit_gkey=inv_unit.gkey
	WHERE id='$row->cont_id' ORDER BY inv_unit.gkey ASC fetch first 1 rows only
	";
	$sqltime_in=oci_parse($con_sparcsn4_oracle,$timeInQuN4);
	oci_execute($sqltime_in);
	$rtnContime_in=oci_fetch_object($sqltime_in);
	$conttime_in=$rtnContime_in->TIME_IN;



	 $totalDaysQuN4="SELECT 
	extract(day from timeout - intime)AS totalDays
	FROM
	(
	SELECT inv_unit_fcy_visit.time_in AS intime,
	(SELECT inv_unit_fcy_visit.time_out
	FROM inv_unit 
	INNER JOIN inv_unit_fcy_visit ON inv_unit_fcy_visit.unit_gkey=inv_unit.gkey
	WHERE id='$row->cont_id' ORDER BY inv_unit.gkey DESC fetch first 1 rows only) AS timeout
	
	FROM inv_unit 
	INNER JOIN inv_unit_fcy_visit ON inv_unit_fcy_visit.unit_gkey=inv_unit.gkey
	WHERE id='$row->cont_id' ORDER BY inv_unit.gkey ASC fetch first 1 rows only
	)  tmp";

    $sqltotalDays=oci_parse($con_sparcsn4_oracle,$totalDaysQuN4);
    oci_execute($sqltotalDays);

	$rtnContotalDays=oci_fetch_object($sqltotalDays);
	$conttotalDays=$rtnContotalDays->TOTALDAYS;
		
	
?>
            <tr align="center">
                <td><?php  echo $i;?></td>
                <td><?php if($row->cont_id) echo $row->cont_id; else echo "&nbsp;";?></td>
                <td><?php if($convsl_name) echo $convsl_name; else echo "&nbsp;";?></td>
                <td><?php if($conib_vyg) echo $conib_vyg; else echo "&nbsp;";?></td>
                <td><?php if($consize) echo $consize; else echo "&nbsp;";?></td>
                <td><?php if($conheight) echo $conheight/10; else echo "&nbsp;";?></td>
                <td><?php if($contmlo) echo $contmlo; else echo "&nbsp;";?></td>
                <td><?php if($contfreight_kind) echo $contfreight_kind; else echo "&nbsp;";?></td>
                <td><?php if($contpostion) echo $contpostion; else echo "&nbsp;";?></td>
                <td><?php if($conttime_in) echo $conttime_in; else echo "&nbsp;";?></td>
                <td><?php if($conttime_out) echo $conttime_out; else echo "&nbsp;";?></td>
                <td><?php if($conttotalDays) echo $conttotalDays; else echo "&nbsp;";?></td>

            </tr>

            <?php 
			oci_free_statement($sqlfreight_kind);
            oci_free_statement($sqlpostion);
            oci_free_statement($sqltime_out);
            oci_free_statement($timeInQuN4);
            oci_free_statement($sqltotalDays);
			
			} 
			
			?>
        </table>

        <?php 
mysqli_close($con_sparcsn4);

oci_close($con_sparcsn4_oracle);
?>
    </BODY>

    </HTML>
