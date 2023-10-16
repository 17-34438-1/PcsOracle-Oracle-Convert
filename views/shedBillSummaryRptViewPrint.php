
<HTML>

<BODY>

<table align="center" width="70%" cellpadding='0' cellspacing='0'>
        <tr bgcolor="" >
            <td align="center" valign="middle" colspan="7" >
                <h3><font color="black">CHITTAGONG PORT AUTHORITY</font></h3>
            </td>
        </tr>
        <tr bgcolor="" >
            <td align="center" valign="middle" colspan="7" >
                <h4><font color="black">SUMMARY REPORT</font></h4>
            </td>
        </tr>

    <tr align="center" height="50px">

        <td colspan="7" align="center"><font size="3"><b><?php echo $title;?></b></font></td>
    </tr>
</table>

<table align="center" class="table table-responsive table-bordered table-striped mb-none">
    <thead>
    <tr align="center" class="gridDark">
        <th align="center"><b>NATURE OF BILL</b></th>
        <th align="center"><b>TOTAL BILL</b></th>
        <th align="center"><b>TOTAL PORT CHARGE</b></th>
        <th align="center"><b>TOTAL PORT CHARGE(90%)</b></th>
        <th align="center"><b>INCOME TAX ON PORT CHARGE(10%)</b></th>
        <th align="center"><b>TOTAL VAT</b></th>
        <th align="center"><b>TOTAL MLWF</b></th>
        <th align="center"><b>TOTAL TAKA</b></th>
        <th align="center"><b>REMARKS</b></th>
    </tr>
    </thead>
    <tbody>




    <?php
    include("mydbPConnection.php");
    $str="SELECT unit_no,count(distinct(shed_bill_details.bill_no))as ttlBill,SUM(amt) as amtTtl,(SUM(amt)*0.90) as portCharge,
			(SUM(amt)*0.10) as taxCharge,SUM(vatTK) as vatTKTtl,
			SUM(mlwfTK) as mlwfTKTtl,(SUM(amt)+SUM(vatTK)+SUM(mlwfTK)) as TotalTKTtl FROM shed_bill_master
			left JOIN shed_bill_details ON shed_bill_master.bill_no = shed_bill_details.bill_no
			WHERE  bill_date between '$from_dt' and '$to_dt'
			group by unit_no";
    $query=mysqli_query($con_cchaportdb,$str);

    //echo $str;
    $i=0;
    $j=0;
    //$transit_state="";
    while($row=mysqli_fetch_object($query)){
        $i++;
        ?>
        <tr align="center" class="gradeX" >
            <td align="center"><?php if($row->unit_no) echo $row->unit_no; else echo "&nbsp;";?></td>
            <td align="center"><?php if($row->ttlBill) echo $row->ttlBill; else echo "&nbsp;";?></td>
            <td align="center"><?php if($row->amtTtl) echo $row->amtTtl; else echo "&nbsp;";?></td>
            <td align="center"><?php if($row->portCharge) echo $row->portCharge; else echo "&nbsp;";?></td>
            <td align="center"><?php if($row->taxCharge) echo $row->taxCharge; else echo "&nbsp;";?></td>
            <td align="center"><?php if($row->vatTKTtl) echo $row->vatTKTtl; else echo "&nbsp;";?></td>
            <td align="center"><?php if($row->mlwfTKTtl) echo $row->mlwfTKTtl; else echo "&nbsp;";?></td>
            <td align="center"> <?php if($row->TotalTKTtl) echo $row->TotalTKTtl; else echo "&nbsp;";?></td>
            <td align="center"> <?php echo "&nbsp;";?></td>
        </tr>

        <?php

    }
    ?>

    <?php
    $str_tot_query="SELECT count(distinct(shed_bill_details.bill_no))as ttlBill,SUM(amt) as amtTtl,(SUM(amt)*0.90) as portCharge,
							(SUM(amt)*0.10) as taxCharge,SUM(vatTK) as vatTKTtl,
							SUM(mlwfTK) as mlwfTKTtl,(SUM(amt)+SUM(vatTK)+SUM(mlwfTK)) as TotalTKTtl FROM shed_bill_master
							left JOIN shed_bill_details ON shed_bill_master.bill_no = shed_bill_details.bill_no
							WHERE  bill_date between '$from_dt' and '$to_dt'";
    //echo $str_tot_query;
    $rslt_tot=mysqli_query($con_cchaportdb,$str_tot_query);
    //$rtn_tot=mysql_fetch_object($rslt_tot);
    while($row_tot=mysqli_fetch_object($rslt_tot)){
        //$j++;
        ?>

        <tr align="center" class="gradeX">
            <td align="center">GRAND TOTAL</td>
            <td align="center"><?php if($row_tot->ttlBill) echo $row_tot->ttlBill; else echo "&nbsp;";?></td>
            <td align="center"><?php if($row_tot->amtTtl) echo $row_tot->amtTtl; else echo "&nbsp;";?></td>
            <td align="center"><?php if($row_tot->portCharge) echo $row_tot->portCharge; else echo "&nbsp;";?></td>
            <td align="center"><?php if($row_tot->taxCharge) echo $row_tot->taxCharge; else echo "&nbsp;";?></td>
            <td align="center"><?php if($row_tot->vatTKTtl) echo $row_tot->vatTKTtl; else echo "&nbsp;";?></td>
            <td align="center"><?php if($row_tot->mlwfTKTtl) echo $row_tot->mlwfTKTtl; else echo "&nbsp;";?></td>
            <td align="center"><?php if($row_tot->TotalTKTtl) echo $row_tot->TotalTKTtl; else echo "&nbsp;";?></td>
            <td align="center"><?php echo "&nbsp;";?></td>
        </tr>
    <?php }?>

    <?php
    //$login_id = $this->session->userdata('login_id')
    //$login_id_trans=="";
    function Offdock($login_id)
    {
        if($login_id=='gclt')
        {
            return "GCL";
        }
        elseif($login_id=='saplw')
        {
            return "SAPE";
        }
        elseif($login_id=='ebil')
        {
            return "EBIL";
        }
        elseif($login_id=='cctcl')
        {
            return "CL";
        }
        elseif($login_id=='ktlt')
        {
            return "KTL";
        }
        elseif($login_id=='qnsc')
        {
            return "QNSC";
        }
        elseif($login_id=='ocl')
        {
            return "OCCL";
        }
        elseif($login_id=='vlsl')
        {
            return "VLSL";
        }
        elseif($login_id=='shml')
        {
            return "SHML";
        }
        elseif($login_id=='iqen')
        {
            return "IE";
        }
        elseif($login_id=='iltd')
        {
            return "IL";
        }

        elseif($login_id=='plcl')
        {
            return "PLCL";
        }
        elseif($login_id=='shpm')
        {
            return "SHPM";
        }
        elseif($login_id=='hsat')
        {
            return "HSAT";
        }
        elseif($login_id=='ellt')
        {
            return "ELL";
        }
        elseif($login_id=='bmcd')
        {
            return "BM";
        }
        elseif($login_id=='nclt')
        {
            return "NCL";
        }

        else
        {
            return "";
        }

    }
    mysqli_close($con_cchaportdb);
    ?>
    </tbody>
</table>
<br />
<br />
<div align="center">

    AUTHORISED SIGNATURE

</div>

</BODY>

</BODY>
</HTML>
<script>
    window.print();
</script>
