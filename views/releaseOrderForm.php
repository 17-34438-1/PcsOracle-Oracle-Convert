<script>
	function chkConfirm()
	{
		if (confirm("Do you want to confirm?") == true)
		{			
			return true ;
		}
		else
		{
			return false;
		}
	}
		
</script>
<section role="main" class="content-body">
	<header class="page-header">
		<h2><?php  echo $title; ?></h2>
	</header>
          
	<div class="row">
		<div class="col-lg-12">
			<section class="panel">
				<!--header class="panel-heading">			
					<h2 class="panel-title">RELEASE ORDER FORM</h2>
				</header>
				<div class="panel-body" align="center">
					<form class="form-inline" action="<?php echo site_url('report/releaseorderpdf'); ?>" method="post" target="_blank">
						<div class="form-group">
							<label class="sr-only" for="exampleInputUsername2">Verification No:</label>
							<input type="text" class="form-control" name="verify_number" id="verify_number" placeholder="Verification No">
						</div>
						&nbsp;&nbsp;
						<div class="radio">
							<label>
								<input type="radio" name="options" id="options" value="RlsOrder" checked="TRUE">
								Release Order
							</label>
						</div>
						&nbsp;
						<div class="radio">
							<label>
								<input type="radio" name="options" id="options" value="Bill" checked="FALSE">
								BILL
							</label>
						</div>
						&nbsp;&nbsp;
						<button type="submit" class="btn btn-primary">Search</button>
						
					</form>
				</div-->
				<div class="panel-body">					
					<table class="table table-bordered table-hover table-striped table-condensed mb-none" id="datatable-default">
						<thead>
							<tr>
								<th class="text-center">SL No</th>
								<th class="text-center">Verify No</th>	
								<th class="text-center">CP No</th>	
								<th class="text-center">Rotation</th>
								<th class="text-center">Container</th>
								<th class="text-center">BL</th>
								<th class="text-center">PR Number</th>
								<th class="text-center">Action</th>	
								<th class="text-center">Action</th>									
								<th class="text-center">Action</th>									
							</tr>
						</thead>
						<tbody>
							<?php 
							for($i=0;$i<count($rslt_releaseOrderList);$i++)
							{ 
							?>
							<tr class="gradeX">
								<td align="center"><?php echo $i+1;?></td>
								<td align="center"><?php echo $rslt_releaseOrderList[$i]['verify_number']?></td>
								<td align="center">
								<?php
									$bn=$rslt_releaseOrderList[$i]['bn'];
									include("mydbPConnection.php");
									$sqlcpno="SELECT gkey,bill_no,cp_no,RIGHT(cp_year,2) AS cp_year,cp_bank_code,cp_unit
									FROM bank_bill_recv
									WHERE bill_no='$bn'";
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
										if($cpbankcode!="" && $cpno!="")
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
								<td align="center"><?php echo $rslt_releaseOrderList[$i]['rotation']?></td>
								<td align="center"><?php echo $rslt_releaseOrderList[$i]['cont_number']?></td>
								<td align="center"><?php echo $rslt_releaseOrderList[$i]['bl_no']?></td>
								<td align="center"><?php echo $rslt_releaseOrderList[$i]['pr_number']?></td>
								<td align="center">
									<form action="<?php echo site_url('ReleaseOrderController/releaseOrderViewTos');?>" method="POST" target="_blank">
										<input type="hidden" name="imp_rot" id="imp_rot" value="<?php echo $rslt_releaseOrderList[$i]['rotation'];?>"/>
										<input type="hidden" name="bl_no" id="bl_no" value="<?php echo $rslt_releaseOrderList[$i]['bl_no'];?>"/>
										<button type="submit" class="mb-xs mt-xs mr-xs btn btn-success btn-xs">Release Order</button>
									</form>
								</td>
								<td align="center">
									<!--form action="<?php echo site_url('ReleaseOrderController/releaseorderpdf');?>" method="POST" target="_blank">
										<input type="hidden" name="options" id="options" value="Bill" />
										<input type="hidden" name="verify_number" id="verify_number" value="<?php echo $rslt_releaseOrderList[$i]['verify_number'];?>"/>										
										<button type="submit" class="mb-xs mt-xs mr-xs btn btn-success btn-xs">Bill</button>
									</form-->
									<form action="<?php echo site_url('ShedBillController/getShedBillPdf');?>" method="post" target="_blank">
										<input type="hidden" id="sendVerifyNo" name="sendVerifyNo">	
										<input type="hidden" name="sendVerifyNo" id="sendVerifyNo" value="<?php echo $rslt_releaseOrderList[$i]['verify_number'];?>"/>										
										<button type="submit" class="mb-xs mt-xs mr-xs btn btn-success btn-xs">Bill</button>						
									</form> 	
								</td>
								<td align="center">
									<form action="<?php echo site_url('ReleaseOrderController/confirmByCounter');?>" method="POST" onsubmit="return chkConfirm()">
										<input type="hidden" name="cont_status" id="cont_status" value="<?php echo $rslt_releaseOrderList[$i]['cont_status'];?>"/>										
										<input type="hidden" name="verify_number" id="verify_number" value="<?php echo $rslt_releaseOrderList[$i]['verify_number'];?>"/>										
										<button type="submit" class="mb-xs mt-xs mr-xs btn btn-success btn-xs" <?php if($rslt_releaseOrderList[$i]['counter_confirm_flag']==1){?>disabled<?php } ?> >Confirm</button>
									</form>
								</td>
							</tr>
							<?php } ?>
						</tbody>
					</table>
				</div>
			</section>
		</div>
	</div>
</section>
