<!doctype html>
<script>
    function validate()
    {

        if( document.myForm.search_by.value == "" )
        {
            alert( "Please provide Search Type!" );
            document.myForm.search_by.focus() ;
            return false;
        }
        if( document.myForm.search_value.value == "" )
        {
            alert( "Please provide Search Type Value!" );
            //document.myForm.search_value.focus() ;
            return false;
        }


        return( true );
    }
	
	function chkConfirm()
	{
		if (confirm("Do you want to receive this bill?") == true)
		{
			return true ;
		}
		else
		{
			return false;
		}
	}
	
	function chkConfirmDlt()
	{
		if (confirm("Do you want to delete this?") == true)
		{
			return true ;
		}
		else
		{
			return false;
		}
	}
</script>
<style>
    label
    {
        color: black;
    }
</style>
<!--html class="fixed">
<head-->

    <?php //include("cssAssetsList.php"); ?>
<!--/head>

<body-->
  <section class="body">
    <?php
    //include("headerTop.php");
    ?>

        <!-- start: sidebar -->
        <?php
        //include("contentMenu.php");
        ?>
        <!-- end: sidebar -->
<section role="main" class="content-body">
<!--    --><?php
//    include("headerTop.php");
//    ?>
            <header class="page-header">
                <h2><?php echo $title; ?></h2>
            </header>

            <div class="row">
                <div class="col-lg-12">
                    <section class="panel">
                        <header class="panel-heading">
                            <h2 class="panel-title"></h2>
                        </header>
                        <div class="panel-body">
                            <form name="myForm" id="myForm"  class="form-horizontal form-bordered"
                                  action="<?php echo site_url("ShedBillController/shedBillList");?>" method="post" onsubmit="return validate()">

                                <div class="form-group">
                                    <label class="col-md-3 control-label" for="inputSuccess">Search By</label>
                                    <div class="col-md-3">
                                        <select class="form-control input-sm mb-md" name="search_by" id="search_by">
                                            <option value="" label="search" selected >--Select--</option>
                                            <option value="billNo" label="billNo" >Bill No</option>
                                            <option value="verifyNo" label="verifyNo" >Verify No</option>
                                            <option value="Unit" label="Unit" >Unit</option>
                                        </select>
                                    </div>

                                    <label class="col-md-2 control-label" for="inputDefault">Value</label>
                                    <div class="col-md-3">
                                        <input class="form-control input-sm mb-md" type="text" id="search_value" name="search_value" value="">
                                    </div>
                                </div>



                                <div class="form-group">
                                    <div class="col-md-12" align="center">
                                        <button type="submit" class="mb-xs mt-xs mr-xs btn btn-primary" value="Search">Search</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </section>
                </div>
            </div>

    <!-- <div class="row">
        <div class="col-lg-12"> -->
            <section class="panel">
              
                <div class="panel-body">
                   
                      
                            <table class="table table-bordered table-striped mb-none" id="datatable-default" >
                                <thead>
									<tr class="gridDark">
										<th>Bill No</th>
										<th>CP No</th>
										<th>Verify No</th>
										<th>Unit No</th>
										<th>Rotation</th>
										<th>CNF Agent</th>
										<th>Total Amount</th>
										<th>Total VAT</th>
										<th>Total Port</th>
										<th>Total MLWF</th>
										<th>Action</th>
										<?php
											$_SESSION['Control_Panel']=$this->session->userdata('Control_Panel');
											
											if($_SESSION['Control_Panel']==13 || $_SESSION['Control_Panel']==62)
											{
										?>
											<th>Action</th>
											
											<?php
											if($_SESSION['Control_Panel']==13)
											{
											?>
											<th>Action</th>
											<?php
											}
											?>
											
											
										<?php
											}else if($_SESSION['Control_Panel']==2){
										?>
											<th colspan="2" class="text-center">Pay Method</th>
										<?php									
											}
										?>
									</tr>
                                </thead>
                                <tbody>

<?php

for($i=0;$i<count($rtnbillno);$i++) {
	$cp="";
    ?>

    <tr class="gridDark"?>
        <td align="center" >
            <?php echo $rtnbillno[$i]['bill_no']?>
        </td>
        <td align="center"><nobr>
		<?php
			$bn=$rtnbillno[$i]['bn'];
			include("mydbPConnection.php");
			$sqlcpno="SELECT gkey,bill_no,cp_no,RIGHT(cp_year,2) AS cp_year,cp_bank_code,cp_unit FROM bank_bill_recv WHERE bill_no='$bn'";
			// $rtncpno=$this->bm->dataSelectDb1($sqlcpno);
			$rek = mysqli_query($con_cchaportdb,$sqlcpno);
              
			if($rek->num_rows > 0)
			{
				$rtncpno = mysqli_fetch_object($rek);

				$cpbankcode=$rtncpno->cp_bank_code;
				$cpno=$rtncpno->cp_no;
				$cpyear=$rtncpno->cp_year;
				$cpunit=$rtncpno->cp_unit;
				$num_length = strlen($cpno);
				$num_length = strlen($cpno);
				
				if($num_length == 4)
				{
					$newcpno=$cpno;
				}
				else if($num_length == 3)
				{
					$newcpno="0"."$cpno";
				}
				else if($num_length == 2)
				{
					$newcpno="00"."$cpno";
				}
				else if($num_length == 1)
				{
					$newcpno="000"."$cpno";
				}
				if($cpbankcode!=""&&$cpno!="")
				{
					echo $cpnoview=$cpbankcode.$cpunit."/".$cpyear."-"."$newcpno";
					$cp=$cpnoview;
				}
			}
			else
			{
				echo ""; 
			}
		?>
            </nobr>
        </td>
        <td align="center">
            <?php echo $rtnbillno[$i]['verify_no']?>
        </td>
        <td align="center">
            <?php
           $bn=$rtnbillno[$i]['bn'];

           include("mydbPConnection.php");
           $sqlcpno="SELECT cp_unit FROM bank_bill_recv WHERE bill_no='$bn'";
        
            $res = mysqli_query($con_cchaportdb,$sqlcpno);
              
         if($res->num_rows > 0){
            $rtncpno = mysqli_fetch_object($res);
            echo  $rtncpno->cp_unit;
         }else{
             echo ""; 
         }

            ?>
        </td>
        <td align="center">
            <?php echo $rtnbillno[$i]['import_rotation']?>
        </td>
        <td align="center">
            <?php echo $rtnbillno[$i]['cnf_agent']?>
        </td>
        <td align="center">
            <?php echo $rtnbillno[$i]['total_amt']?>
        </td>
        <td align="center">
            <?php echo $rtnbillno[$i]['total_vat']?>
        </td>
        <td align="center">
            <?php echo $rtnbillno[$i]['total_port']?>
        </td>
        <td align="center">
            <?php echo $rtnbillno[$i]['total_mlwf']?>
        </td>
        <td align="center">
            <?php
            $rcvstat = 0;
            $vrfno = $rtnbillno[$i]['verify_no'];
            include("mydbPConnection.php");
            $billrcvstat = "SELECT bill_rcv_stat FROM shed_bill_master WHERE verify_no='$vrfno'";

            $res = mysqli_query($con_cchaportdb,$billrcvstat);
            $rowVal = mysqli_fetch_object($res);
            $rcvstat=$rowVal->bill_rcv_stat;
            //echo $rcvstat;
            ?>
            <form action="<?php echo site_url('ShedBillController/getShedBillPdf');?>" method="POST" target="_blank">
                <input type="hidden" name="sendVerifyNo" value="<?php echo $rtnbillno[$i]['verify_no'];?>">
                <input type="submit" value="View" name="viewbill" class="mb-xs mt-xs mr-xs btn btn-xs btn-primary">
            </form>
        </td>
        <?php
        $_SESSION['Control_Panel']=$this->session->userdata('Control_Panel');
        if($_SESSION['Control_Panel']==13)
        {
            ?>
            <td align="center">
                <?php
                $rcvstat = 0;
                $vrfno = $rtnbillno[$i]['verify_no'];
                include("mydbPConnection.php");
                $billrcvstat = "SELECT bill_rcv_stat FROM shed_bill_master WHERE verify_no='$vrfno'";

                $res = mysqli_query($con_cchaportdb,$billrcvstat);
				
				while($rowVal = mysqli_fetch_object($res))
				{
					if($rowVal->bill_rcv_stat!=null or $rowVal->bill_rcv_stat!="")
						$rcvstat=$rowVal->bill_rcv_stat;
				}
				
                // $rowVal = mysqli_fetch_object($res);
                // $rcvstat=$rowVal->bill_rcv_stat;
                //echo $rcvstat;
                if($rcvstat==1)
                    $val="Received";
                else
                    $val="Receive";
                ?>
                <form action="<?php echo site_url('ShedBillController/shedreceive');?>" method="POST" onsubmit="return chkConfirm()">
                    <input type="hidden" name="verifyno" value="<?php echo $rtnbillno[$i]['verify_no'];?>">
                    <input type="hidden" name="shedbill" value="<?php echo $rtnbillno[$i]['bn'];?>">   
                    <input type="submit" name="receive" value="<?php echo $val;?>" class="mb-xs mt-xs mr-xs btn btn-xs btn-primary" <?php if($rcvstat==1) { ?> disabled="true" <?php } ?>>
                </form>
            </td>
			<td align="center">
				<form action="<?php echo site_url('ShedBillController/dltRcvBill');?>" method="POST" onsubmit="return chkConfirmDlt()">
                    <input type="hidden" name="verifyno" value="<?php echo $rtnbillno[$i]['verify_no'];?>">
                    <input type="hidden" name="shedbill" value="<?php echo $rtnbillno[$i]['bn'];?>">   
                    <input type="submit" name="receive" value="Delete" class="mb-xs mt-xs mr-xs btn btn-xs btn-primary" <?php if($rcvstat==0 or $rtnbillno[$i]['rcv_delete_stat']==1) { ?> disabled="true" <?php } ?>>
                </form>
            </td>
            <?php
        }
        else if($_SESSION['Control_Panel']==62)
        {
            ?>
            <td>
                <form action="<?php echo site_url('ShedBillController/billDeletePerform');?>" method="post" target="_blank">
                    <input type="hidden" name="vrfno" value="<?php echo $rtnbillno[$i]['verify_no'];?>" />
                    <input type="hidden" name="sdbillno" value="<?php echo $rtnbillno[$i]['bn'];?>" />
                    <input type="submit" name="delete" value="Delete" class="mb-xs mt-xs mr-xs btn btn-xs btn-danger" />
                </form>
            </td>
        <?php
        
        }
        else if($_SESSION['Control_Panel']==2)
        {

        ?>
            <td><nobr>
                <form action="<?php echo site_url('ShedBillController/shed_bill_cash_payment');?>" method="post" target="_blank">
					<input type="hidden" name="total_amt" value="<?php echo $rtnbillno[$i]['total_amt'];?>" />
                    <input type="submit" name="online" value="Online" class="mb-xs mt-xs mr-xs btn btn-xs btn-primary" <?php if($cp!="") {?> disabled <?php } ?>/>
                </form>
				</nobr>
            </td>
			
			<td>
                <form action="" method="post">
					<input type="hidden" name="total_amt" value="<?php echo $rtnbillno[$i]['total_amt'];?>" />
                    <input type="submit" name="Manual" value="Manual" class="mb-xs mt-xs mr-xs btn btn-xs btn-primary" <?php if($cp!="") {?> disabled <?php } ?> />
                </form>
            </td>
        <?php
        }
        ?>
    </tr>
    <?php
}
?>
</tbody>
</table>
</div>
</section>
</section>

<?php
	//include("jsAssetsList.php");
?>

</section>
<!--/body>
</html-->
