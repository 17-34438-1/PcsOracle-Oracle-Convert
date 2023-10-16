<?php if($_POST['options']=='html'){?>
<HTML>

<HEAD>
    <TITLE>ASSIGNMENT DATE WISE SMS STATUS REPORT</TITLE>
    <LINK href="../css/report.css" type=text/css rel=stylesheet>
    <style type="text/css">
    .style1 {
        font-size: 12px
    }
    </style>
</HEAD>

<BODY>

    <?php } 
	else if($_POST['options']=='xl'){
		header("Content-type: application/octet-stream");
		header("Content-Disposition: attachment; filename=ASSIGNMENT_DATE_WISE_SMS_STATUS_REPORT.xls;");
		header("Content-Type: application/ms-excel");
		header("Pragma: no-cache");
		header("Expires: 0");

	}
	//$ddl_imp_rot_no=$_REQUEST['ddl_imp_rot_no']; 

	//$con=mysql_connect("10.1.1.21", "sparcsn4","sparcsn4")or die("sparcsn4 database cannot connect"); 
	//mysql_select_db("ctmsmis")or die("cannot select DB");
	include("FrontEnd/dbConection.php");
	?>
    <html>
    <title>ASSIGNMENT DATE WISE SMS STATUS REPORT</title>

    <body>
        <?php include("FrontEnd/dbConection.php");?>
        <table width="100%" border='0' cellpadding='0' cellspacing='0'>
            <tr bgcolor="#ffffff" align="center" height="100px">
                <td colspan="13" align="center">
                    <table border=0 width="100%">
                        <tr align="center">
                            <td colspan="7"><img height="100px" src="<?php echo ASSETS_WEB_PATH;?>fimg/cpaLogo.png" />
                            </td>
                        </tr>
                        <tr align="center">
                            <td colspan="7">
                                <font size="4"><b> CHITTAGONG PORT AUTHORITY,CHITTAGONG</b></font>
                            </td>
                        </tr>

                        <tr align="center">
                            <td colspan="7">
                                <font size="4"><b><u>SMS REPORT ACCORDING TO TYPE</u></b></font>
                            </td>
                        </tr>
                        <tr align="center">
                            <td colspan="7">
                                <font size="4"><b>ASSIGNMENT DATE : <?php echo $fromdate;?> to <?php echo $todate;?></b>
                                </font>
                            </td>
                        </tr>

                        <tr align="center">
                            <td colspan="7">
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

        <br>
        <table align="center" width="85%" border='1' cellpadding='1' cellspacing='1'>
            <tr align="center" bgcolor="grey">
                <td style="border-width:1px;border-style: double;"><b>SL</b></td>
                <td style="border-width:1px;border-style: double;"><b>Cell no</b></td>
                <td style="border-width:1px;border-style: double;"><b>SMS Content</b></td>
                <td style="border-width:1px;border-style: double;"><b>SMS Sending Time</b></td>
                <td style="border-width:1px;border-style: double;"><b>SMS Type</b></td>
            </tr>

            <?php
				
				for($i=0;$i<count($rtnSmsRpt);$i++){
			
			?>
            <tr align="center">
                <td><?php  echo $i+1;?></td>
                <td><?php  echo $rtnSmsRpt[$i]['cell_no'];?></td>
                <td><?php  echo $rtnSmsRpt[$i]['sms'];?></td>
                <td><?php  echo $rtnSmsRpt[$i]['sms_sending_time'];?></td>
                <td><?php  echo $rtnSmsRpt[$i]['sms_type'];?></td>
            </tr>
            <?php }?>
        </table>
        <br />
        <br />
        <?php  
//mysql_close($con_sparcsn4);
if($_POST['options']=='html'){?>
    </BODY>

    </HTML>
    <?php }?>