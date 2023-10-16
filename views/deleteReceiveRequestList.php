<!doctype html>
<script>
    function chkConfirm()
	{
		if (confirm("Do you want to accept this request?") == true)
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

            <!--div class="row">
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
            </div-->

    <!-- <div class="row">
        <div class="col-lg-12"> -->
            <section class="panel">              
                <div class="panel-body">
					<table class="table table-bordered table-striped mb-none" id="datatable-default" >
						<thead>
							<tr class="gridDark">
								<th>Sl</th>
								<th>Bill No</th>
								<th>CP No</th>
								<th>Verify No</th>										
								<th>CNF Agent</th>										
								<th>Request By</th>										
								<th>Request Time</th>										
								<th>Action</th>										
							</tr>
						</thead>
						<tbody>
							<?php
							for($i=0;$i<count($rslt_dltRcvRqst);$i++)
							{
							?>
								<tr>
									<td><?php echo $i+1; ?></td>
									<td><?php echo $rslt_dltRcvRqst[$i]['bill_no']; ?></td>
									<td>
										<?php
											$bn=$rslt_dltRcvRqst[$i]['bn'];
											include("mydbPConnection.php");
											$sqlcpno="SELECT gkey,bill_no,cp_no,RIGHT(cp_year,2) AS cp_year,cp_bank_code,cp_unit FROM bank_bill_recv WHERE bill_no='$bn'";											
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
											
										</td>
									<td><?php echo $rslt_dltRcvRqst[$i]['verify_no']; ?></td>
									<td><?php echo $rslt_dltRcvRqst[$i]['cnf_agent']; ?></td>
									<td><?php echo $rslt_dltRcvRqst[$i]['rcv_delete_by']; ?></td>
									<td><?php echo $rslt_dltRcvRqst[$i]['rcv_delete_time']; ?></td>
									<td>
										<form action="<?php echo site_url('ShedBillController/acceptDltRqst'); ?>" method="post" onsubmit="return chkConfirm()">
											<input type="hidden" name="verifyno" value="<?php echo $rslt_dltRcvRqst[$i]['verify_no'];?>">
											<input type="hidden" name="shedbill" value="<?php echo $rslt_dltRcvRqst[$i]['bn'];?>">   
											<input type="submit" name="btnAccept" id="btnAccept" value="Accept" class="mb-xs mt-xs mr-xs btn btn-xs btn-primary" />
										</form>
									</td>
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
