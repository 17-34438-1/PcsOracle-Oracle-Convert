<script>
	function chkDate()
		{
			var fdate = document.getElementById("from_date").value;
			var tdate = document.getElementById("to_date").value;
			if(fdate==tdate)
			{
				return true;
			}
			else if(fdate < tdate)
			{
				return true;
			}
			else if(fdate > tdate)
			{
				alert("Wrong combination of date !");
				return false;
			}
		}
</script>
<section role="main" class="content-body">
	<header class="page-header">
		<h2><?php echo $title;?></h2>	
		<div class="right-wrapper pull-right"></div>
	</header>
	<!-- start: Table -->
	<div class="row">
		<div class="col-lg-12">	
			<section class="panel">
				
				<div class="panel-body">
					<form class="form-horizontal form-bordered" method="POST"
						action="<?php echo site_url('Auction/AuctionHandOverReportListLCL') ?>" onsubmit="return chkDate();">
						<div class="form-group">
							<div class="row">
								<div class="col-sm-12 text-center">
									<?php  if(isset($msg)) {echo $msg;} ?>
								</div>
							</div>
							<div class="col-md-6 col-md-offset-3">
								<div class="input-group mb-md">
									<span class="input-group-addon span_width">Unit <span class="required">*</span></span>
									<select class="form-control" name="unit" id="unit" required>
										<option value="">--Select--</option>
											<option value="U1" <?php if($search=="1" and $unit=="U1") { echo "selected";} ?>>
												U1
											</option>
											<option value="U2" <?php if($search=="1" and $unit=="U2") { echo "selected";} ?>>
												U2
											</option>
											<option value="U3" <?php if($search=="1" and $unit=="U3") { echo "selected";} ?>>
												U3
											</option>
											<option value="U4" <?php if($search=="1" and $unit=="U4") { echo "selected";} ?>>
												U4
											</option>
											<option value="U5" <?php if($search=="1" and $unit=="U5") { echo "selected";} ?>>
												U5
											</option>
											<option value="U6" <?php if($search=="1" and $unit=="U6") { echo "selected";} ?>>
												U6
											</option>
											<option value="U7" <?php if($search=="1" and $unit=="U7") { echo "selected";} ?>>
												U7
											</option>
											<option value="U8" <?php if($search=="1" and $unit=="U8") { echo "selected";} ?>>
												U8
											</option>
											<option value="U9" <?php if($search=="1" and $unit=="U9") { echo "selected";} ?>>
												U9
											</option>
											<option value="U10" <?php if($search=="1" and $unit=="U10") { echo "selected";} ?>>
												U10
											</option>
											<option value="U11" <?php if($search=="1" and $unit=="U11") { echo "selected";} ?>>
												U11
											</option>
											<option value="U12" <?php if($search=="1" and $unit=="U12") { echo "selected";} ?>>
												U12
											</option>
									</select>
								</div>
								<div class="input-group mb-md">
									<span class="input-group-addon span_width">From Date <span class="required">*</span></span>
									<input type="date" name="from_date" id="from_date" class="form-control" 
										value="<?php if($search=="1") echo $from_date; else echo "";?>" required>
								</div>
								<div class="input-group mb-md">
									<span class="input-group-addon span_width">To Date <span class="required">*</span></span>
									<input type="date" name="to_date" id="to_date" class="form-control" 
										value="<?php if($search=="1") echo $to_date; else echo "";?>" required>
								</div>
							</div>						
							<div class="row">
								<div class="col-sm-12 text-center">
									<input type="hidden" name="search" id="search" class="form-control" value="<?php echo $search; ?>" required>
									<button type="submit" name="btnSearch" class="mb-xs mt-xs mr-xs btn btn-success">Search</button>
								</div>													
							</div>
						</div>
					</form>
				</div>
			</section>
			<section class="panel">
				<div class="panel-body">					
					<table class="table table-bordered mb-none" id="datatable-default">
						<thead>
							<tr>
								<th class="text-center">#Sl</th>
								<th class="text-center">Rotation No</th>
								<th class="text-center">Arrival Date</th>
								<th class="text-center">C/L Date</th>
								<th class="text-center">Unit</th>
								<th class="text-center">Action</th>					
								<!--th class="text-center">Notice</th-->					
							</tr>
						</thead>
						<tbody>
							<?php for($i=0;$i<count($auction_handover_List);$i++){ ?>
							<tr>
								<td align="center"><?php echo $i+1;?></td>
								<td align="center"><?php echo $auction_handover_List[$i]['rotation_no'];?></td>
								<td align="center"><?php echo $auction_handover_List[$i]['arrival_date'];?></td>
								<td align="center"><?php echo $auction_handover_List[$i]['cl_date'];?></td>
								<td align="center"><?php echo $auction_handover_List[$i]['unit'];?></td>
								<td align="center">
									<form action="<?php echo site_url("Auction/AuctionHandOverReportFormLCL");?>" method="post" 
										target="_blank">
										<input type="hidden" class="form-control" name="rotation" id="rotation" value="<?php echo $auction_handover_List[$i]['rotation_no']; ?>">
										<input type="hidden" class="form-control" name="action" id="action" value="print">
										<button type="submit" class="btn btn-primary">View</button>	
									</form>
								</td>
								<!--td align="center">
									<form action="<?php echo site_url("Report/AuctionHandNoticeGeneration");?>" method="post" target="_blank">
										<input type="hidden" class="form-control" name="rotation" id="rotation" value="<?php echo $auction_handover_List[$i]['rotation_no']; ?>">
										<input type="hidden" class="form-control" name="arrival_dt" id="arrival_dt" value="<?php echo $auction_handover_List[$i]['arrival_date'];?>">
										<input type="hidden" class="form-control" name="cl_dt" id="cl_dt" value="<?php echo $auction_handover_List[$i]['cl_date'];?>">
										<button type="submit" value="Search" name="action" id="action" class="btn btn-primary"> Notice</button>	
									</form>
								</td-->
							</tr>
							<?php } ?>
						</tbody>
					</table>
				</div>
			</section>	
		</div>
	</div>	
	<!-- end: Table -->
</section>
</div>