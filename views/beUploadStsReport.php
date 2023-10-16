<html>
    <body>
        <table width="100%" border="0">
            <tr height="100px">
                <td align="center" valign="middle" colspan="7">
                    <h1><img align="middle" src="<?php echo IMG_PATH?>cpanew.jpg"></h1>                
                </td>
            </tr>
            <tr>
                <td align="center" colspan="2"><b>Legend:</b></td>  
                <td><div style="width: 230px;"><div style='background-color:green;width:150px;float:left;'>&nbsp;</div><div style="float:right;" align="left"> All Correct</div></div></td> 
                <td><div style="width: 290px;"><div style='background-color:#fc9c95;width:150px;float:left;'>&nbsp;</div><div style="float:right;"> B/E Late Uploaded</div></div></td> 
                <td><div style="width: 280px;"><div style='background-color:red;width:150px;float:left;'>&nbsp;</div><div style="float:right;"> B/E Not Uploaded</div></div></td> 
            </tr>
        </table>

        <table align="center" width="80%" border="1" cellpadding="0" cellspacing="0">
            <tr style="background-color:#ddd;">
                <th style="padding:5px;">Sl</th>
                <th>Rotation</th>
                <th>BL</th>
                <th>MLO CODE</th>
                <th>MLO LINE NO</th>
                <th>FF LINE</th>
                <th>BE NO</th>
                <th>BE DATE</th>	
                <th>BE OFFICE CODE</th>
                <th>DO NO</th>
                <th>DO DATE</th>
                <th>DO VALID UPTO</th>
                <th>DO ISSUED BY</th>
                <!-- <th>DO QTY</th>
                <th>DO UNIT</th>
                <th>DO WEIGHT</th> -->
                <th>CUS RO NO</th>
                <th>BE EXIT NO</th>
                <th>C&F AIN</th>
                <th>C&F NAME</th>
                <!-- <th>EDO RECEIVE DATE</th> -->
                <th>B/E UPLOAD AT</th>
                <th>EDO APPROVE AT</th>
                <th>APPROVED BY</th>
            </tr>

            <?php
                $i = 0;

                $reg_no = "";
                $bl_no = "";
                $mlo_code = "";
                $mlo_line_no = "";
                $ff_line = "";
                $be_no = "";
                $be_dt = "";
                $be_office_code = "";
                $do_no = "";
                $do_dt = "";
                $do_valid_upto = "";
                $do_issued_by = "";
                $do_qty = "";
                $do_unit = "";
                $do_weight = "";
                $cus_ro_no = "";
                $be_exit_no = "";
                $cnf_ain = "";
                $cnf_name = "";
                $edo_data_rcv_date = "";

                while(count($data)>$i)
                {
                    $reg_no = $data[$i]->reg_no;
                    $bl_no = $data[$i]->bl_no;
                    $mlo_code = $data[$i]->mlo_code;
                    $mlo_line_no = $data[$i]->mlo_line_no;
                    $ff_line = $data[$i]->ff_line;
                    $be_no = $data[$i]->be_no;
                    $be_dt = $data[$i]->be_dt;
                    $be_office_code = $data[$i]->be_office_code;
                    $do_no = $data[$i]->do_no;
                    $do_dt = $data[$i]->do_dt;
                    $do_valid_upto = $data[$i]->do_valid_upto;
                    $do_issued_by = $data[$i]->do_issued_by;
                    $do_qty = $data[$i]->do_qty;
                    $do_unit = $data[$i]->do_unit;
                    $do_weight = $data[$i]->do_weight;
                    $cus_ro_no = $data[$i]->cus_ro_no;
                    $be_exit_no = $data[$i]->be_exit_no;
                    $cnf_ain = $data[$i]->cnf_ain;
                    $cnf_name = $data[$i]->cnf_name;
                    $edo_data_rcv_date = $data[$i]->edo_data_rcv_date;

                    $manif = str_replace('/',' ',$reg_no);

                    $sadQuery = "SELECT * FROM sad_info
                    INNER JOIN sad_item ON sad_item.sad_id=sad_info.id
                    WHERE manif_num LIKE '%$manif%' AND sum_declare='$bl_no'";
                    $sadResult = $this->bm->dataSelectDB1($sadQuery);
                    
                    $entry_dt = "";
                    $cpaChkTime = "";
                    $check_st = "";
                    $st = "";
                    $u_name = "";
                    $style = 'red';
                    if(count($sadResult)>0)
                    {
                        $entry_dt = $sadResult[0]['entry_dt'];
                        $cpaChkTimeQuery = "SELECT IF('$entry_dt'<cpa_check_time,1,0) AS st,cpa_check_time,check_st,u_name FROM shed_mlo_do_info LEFT JOIN users ON users.login_id = shed_mlo_do_info.cpa_checked_by WHERE imp_rot = '$reg_no' AND bl_no = '$bl_no'";
                        $cpaChkTimeRslt = $this->bm->dataSelectDB1($cpaChkTimeQuery);

                        if(count($cpaChkTimeRslt)>0)
                        {
                            $check_st = $cpaChkTimeRslt[0]['check_st'];
                            if($check_st == 1)
                            {
                                $u_name = $cpaChkTimeRslt[0]['u_name'];
                                $cpaChkTime = $cpaChkTimeRslt[0]['cpa_check_time'];
                                $st = $cpaChkTimeRslt[0]['st'];
                                if($st == 1)
                                {
                                    $style = 'normal';
                                }
                                else
                                {
                                    $style = 'simpleRed';
                                }
                            }
                        }
                        //return;
                    }

                    // return;
                    $i++;
            ?>

            <tr <?php if($style == "red"){ echo "style='background-color:red;color:#fff;'";}else if($style == "simpleRed"){ echo "style='background-color:#fc9c95;color:#fff;'";}else{ echo "style='background-color:green;color:#fff;'";}?>>
                <td style="padding-left:5px;"><?php echo $i; ?></td>
                <td style="padding-left:5px;"><?php echo $reg_no; ?></td>
                <td style="padding-left:5px;"><?php echo $bl_no; ?></td>
                <td style="padding-left:5px;"><?php echo $mlo_code; ?></td>
                <td style="padding-left:5px;"><?php echo $mlo_line_no; ?></td>
                <td style="padding-left:5px;"><?php echo $ff_line; ?></td>
                <td style="padding-left:5px;"><?php echo $be_no; ?></td>
                <td style="padding-left:5px;"><?php echo $be_dt; ?></td>
                <td style="padding-left:5px;"><?php echo $be_office_code; ?></td>
                <td style="padding-left:5px;"><?php echo $do_no; ?></td>
                <td style="padding-left:5px;"><?php echo $do_dt; ?></td>
                <td style="padding-left:5px;"><?php echo $do_valid_upto; ?></td>
                <td style="padding-left:5px;"><?php echo $do_issued_by; ?></td>
                <!-- <td><?php //echo $do_qty; ?></td>
                <td><?php //echo $do_unit; ?></td>
                <td><?php //echo $do_weight; ?></td> -->
                <td style="padding-left:5px;"><?php echo $cus_ro_no; ?></td>
                <td style="padding-left:5px;"><?php echo $be_exit_no; ?></td>
                <td style="padding-left:5px;"><?php echo $cnf_ain; ?></td>
                <td style="padding-left:5px;"><?php echo $cnf_name; ?></td>
                <!-- <td><?php //echo $edo_data_rcv_date; ?></td> -->
                <td style="padding-left:5px;"><?php echo $entry_dt; ?></td>
                <td style="padding-left:5px;"><?php echo $cpaChkTime; ?></td>
                <td style="padding-left:5px;"><?php echo $u_name; ?></td>
            </tr>

            <?php
                }

                if($i==0){
            ?>
            <tr>
                <td align="center" colspan="20"><?php echo "<font color='red' size='5'>No Data Found or CONNECTION ISSUE</font>"; ?></td>
            </tr>
            <?php
                }
            ?>
        </table>
    </body>
</html>