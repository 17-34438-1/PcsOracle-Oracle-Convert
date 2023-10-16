<script>
	function confirmDeletion(){
		if(confirm("Do you want to delete ?"))
		  {
			  return true;
		  }
		  else
		  {
			  return false;
		  }
	}
</script>
<section role="main" class="content-body">
    <header class="page-header">
        <h2><?php echo $title;?></h2>

        <div class="right-wrapper pull-right">

        </div>
    </header>

    <!-- start: page -->
    <div class="row">
        <div class="col-lg-12">
            <section class="panel">
                <header class="panel-heading">

                </header>
                <div class="panel-body">
					<div align="center"><b><?php echo $msg;?></b></div>
                    <form class="form-horizontal form-bordered" method="POST"
                        action="<?php echo site_url('EDOController/dateTokenDistributionFormAction') ?>">
                        <div class="form-group">
                            <label class="col-md-3 control-label">&nbsp;</label>
                            <div class="col-md-6">
                                <div class="input-group mb-md">
                                    <span class="input-group-addon span_width"> From Date <span
                                            class="required">*</span></span>
                                    <input type="date" id="fromdate" name="fromdate"
                                        value="<?php if($flag=="search"){ echo $fromDate; } ?>"
                                        class="form-control login_input_text" />
                                </div>
                                <div class="input-group mb-md">
                                    <span class="input-group-addon span_width"> To Date <span
                                            class="required">*</span></span>
                                    <input type="date" id="todate" name="todate"
                                        value="<?php if($flag=="search"){ echo $toDate; } ?>"
                                        class="form-control login_input_text" />
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-12 text-center">
                                    <!--button type="button" class="mb-xs mt-xs mr-xs btn btn-primary">Primary</button-->
                                    <button type="submit" class="mb-xs mt-xs mr-xs btn btn-success">Search</button>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-12 text-center">

                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </section>

        </div>
    </div>
    <?php if($flag=="search") { ?>
    <div class="row">
        <?php include("mydbPConnection.php"); ?>
        <div class="col-lg-12">
            <div class="panel-body">
                <section class="panel">
                    <table class="table table-bordered table-hover table-striped mb-none" id="datatable-default">
                        <thead>
                            <tr>
                                <th>#Sl</th>
                                <th class="text-center">FF Name</th>
                                <th class="text-center">AIN No</th>
                                <th class="text-center">Quantity</th>
                                <!--th class="text-center">Used</th-->
                                <th class="text-center">Action</th>

                            </tr>
                        </thead>

                        <tbody>
                            <?php 
                                $totalQty = 0;
                                for($i=0;$i<count($rsltTokenCount);$i++){ 
                                    $id = $rsltTokenCount[$i]['id'];
                                    $totalQty = $totalQty+$rsltTokenCount[$i]['Quantity'];
                                    $tbl_name = $rsltTokenCount[$i]['tbl_name'];
                                    $ff_ain = $rsltTokenCount[$i]['ff_ain'];
									$used_token = "";
									if($tbl_name=="token_distribution_transaction"){
										$sql_used_token = "SELECT COUNT(*) AS used FROM token_distribution WHERE transaction_id='$id' AND used_st='1'";
									} else {
										$sql_used_token = "SELECT COUNT(*) AS used FROM token_distribution 
															WHERE ff_ain='$ff_ain' AND used_st='1' AND (DATE(entry_time) BETWEEN '$fromDate' AND '$toDate')";
									}
									$res_used_token = mysqli_query($con_cchaportdb,$sql_used_token);
									while($row = mysqli_fetch_object($res_used_token)){
										$used_token = $row->used;
									}
                            ?>
                            <tr>
                                <td align="center"><?php echo $i+1; ?></td>
                                <td align="center"><?php echo $rsltTokenCount[$i]['ff_name']; ?></td>
                                <td align="center"><?php echo $rsltTokenCount[$i]['ff_ain']; ?></td>
                                <td align="center"> <?php echo $rsltTokenCount[$i]['Quantity']; ?> </td>
                                <!--td align="center"> <?php echo $used_token; ?> </td-->
                                <td align="center"> 
									<?php if($used_token == "0") { ?>
									<form class="form-horizontal form-bordered" method="POST" 
										action="<?php echo site_url('EDOController/deleteTokenDistribution') ?>" onsubmit="return confirmDeletion();">
										<input type="hidden" name="tbl_name" id="tbl_name" value="<?php echo $tbl_name; ?>">
										<input type="hidden" name="id" id="id" value="<?php echo $id; ?>">
										<input type="hidden" name="ff_ain" id="ff_ain" value="<?php echo $ff_ain; ?>">
										<input type="hidden" name="fromDate" id="fromDate" value="<?php echo $fromDate; ?>">
										<input type="hidden" name="toDate" id="toDate" value="<?php echo $toDate; ?>">
										<button type="submit" class="mb-xs mt-xs mr-xs btn btn-danger">
											Delete
										</button>
									</form> 
									<?php } ?>
								</td>
                            </tr>
                            <?php } ?>
                        </tbody>
                        <?php if(count($rsltTokenCount) > 0) { ?>
                        <tr>
                            <td align="center" colspan="3"><b>Total</b></td>
                            <td align="center" colspan="2"><?php echo  $totalQty; ?></td>
                        </tr>
                        <?php } ?>
                    </table>
                </section>
                <div class="row">
                    <div class="col-sm-12 text-center">
                        <form class="form-horizontal form-bordered" method="POST" action="<?php echo site_url('EDOController/dateTokenDistributionFormAction') ?>" target="_blank">
                            <input type="hidden" name="fromdate" id="fromdate" value="<?php echo $fromDate; ?>">
                            <input type="hidden" name="todate" id="todate" value="<?php echo $toDate; ?>">
                            <input type="hidden" name="pdfView" id="pdfView" value="pdf">
                            <button type="submit" name="btnApply" class="mb-xs mt-xs mr-xs btn btn-primary"  <?php if(count($rsltTokenCount) == 0) { ?> style="display: none;" <?php } ?>>
                                Print
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php } ?>
    <!-- end: page -->
</section>
</div>