
<HTML>
<BODY>

    <table class="table table-responsive table-bordered table-striped mb-none" align="center">
        <tbody>

        <tr class="gradeX">
            <td  align="center">
                <h3><font color="black">CHITTAGONG PORT AUTHORITY</font></h3>
            </td>
        </tr>
        <tr class="gradeX">
            <td  align="center">
                <h4><font color="black">HEADWISE SUMMARY FOR SHED BILL</font></h4>
            </td>
        </tr>

        <tr class="gradeX">
            <td align="center"><font size="3"><b><?php echo $title;?></b></font></td>
        </tr>
        </tbody>
    </table>
    <table class="table table-responsive table-bordered table-striped mb-none">
        <thead>
        <tr class="gridDark">
            <th align="center"><b>CODE</b></th>
            <th align="center"><b>DESCRIPTION</b></th>
            <th align="center"><b>PORT (TK)</b></th>
            <th align="center"><b>VAT (TK)</b></th>
            <th align="center"><b>MLWF (TK)</b></th>
            <th align="center"><b>TOTAL (TK)</b></th>
        </tr>
        </thead>
        <tbody>

<?php
	include("mydbPConnection.php");
	$str="SELECT gl_code,description,amt,vatTK,mlwfTK,(amt+vatTK+mlwfTK) as TotalTK FROM shed_bill_master
			INNER JOIN shed_bill_details ON shed_bill_master.bill_no = shed_bill_details.bill_no
			WHERE  shed_bill_master.unit_no='$unitNo' and bill_date between '$from_dt' and '$to_dt'";
		//	echo $str;
$query=mysqli_query($con_cchaportdb,$str);

	$i=0;
	$j=0;	

	while($row=mysqli_fetch_object($query)){
	$i++;
	?>
	<tr align="center" class="gradeX">
		<td align="center"><?php if($row->gl_code) echo $row->gl_code; else echo "&nbsp;";?></td>
		<td align="center"><?php if($row->description) echo $row->description; else echo "&nbsp;";?></td>
		<td align="center"><?php if($row->amt) echo $row->amt; else echo "&nbsp;";?></td>
		<td align="center"><?php if($row->vatTK) echo $row->vatTK; else echo "&nbsp;";?></td>
		<td align="center"><?php if($row->mlwfTK) echo $row->mlwfTK; else echo "&nbsp;";?></td>
		<td align="center"><?php if($row->TotalTK) echo $row->TotalTK; else echo "&nbsp;";?></td>
	</tr>
	
		<?php 
	
		}
		?>
	
		<?php
			$str_tot_query="SELECT SUM(amt) as amtTtl,SUM(vatTK) as vatTKTtl,SUM(mlwfTK) as mlwfTKTtl,(SUM(amt)+SUM(vatTK)+SUM(mlwfTK)) as TotalTKTtl FROM shed_bill_master
								INNER JOIN shed_bill_details ON shed_bill_master.bill_no = shed_bill_details.bill_no
								WHERE  shed_bill_master.unit_no='$unitNo' and bill_date between '$from_dt' and '$to_dt'";

        $rslt_tot=mysqli_query($con_cchaportdb,$str_tot_query);
			while($row_tot=mysqli_fetch_object($rslt_tot)){
	//$j++;	
		?>
		
		<tr align="center" class="gradeX">
			<td align="center" colspan=2><b>BILL TOTAL :</b> </td>
			<td align="center"><?php if($row_tot->amtTtl) echo $row_tot->amtTtl; else echo "&nbsp;";?></td>
			<td align="center"><?php if($row_tot->vatTKTtl) echo $row_tot->vatTKTtl; else echo "&nbsp;";?></td>
			<td align="center"><?php if($row_tot->mlwfTKTtl) echo $row_tot->mlwfTKTtl; else echo "&nbsp;";?></td>
			<td align="center"><?php if($row_tot->TotalTKTtl) echo $row_tot->TotalTKTtl; else echo "&nbsp;";?></td>
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
<br/>
<br/>

<div style="width:80%" align="right"> 
	COMPUTER INCHARGE
</div>
<div style="width:80%" align="right"> 
	COMPUTER CENTER UNIT NO : <?php echo $unitNo;?>
</div>
<div style="width:80%" align="right"> 
	CPA
</div>

	</BODY>
</HTML>

<script>
    window.print();
</script>