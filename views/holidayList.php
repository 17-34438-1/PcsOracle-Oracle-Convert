<script>
	function validate()
		{
			if (confirm("Do you want to detete ?") == true)
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
			<h2><?php echo $title;?></h2>
		
			<div class="right-wrapper pull-right">
				
			</div>
		</header>

		<!-- start: page -->
			<div class="row">
				<div class="col-lg-12">	
					<section class="panel">
						<div class="panel-body">
							<div class="row">
								<div class="col-sm-12 text-center">
									<?php echo $msg; ?>
								</div>
							</div>
							<table class="table table-bordered table-hover table-striped mb-none" id="datatable-default">
								<thead>
									<tr>
										<th class="text-center">SL No</th>
										<th class="text-center">Holiday Name</th>
										<th class="text-center">From Date</th>
										<th class="text-center">To Date</th>
										<th class="text-center">Action</th>
										<th class="text-center">Action</th>
									</tr>
								</thead>
								<tbody>
									<?php for($i=0;$i<count($holidayList);$i++){ ?>
									<tr class="gradeX">
										<td align="center"><?php echo $i+1;?></td>
										<td align="center"><?php echo $holidayList[$i]['holiday_name']?></td>
										<td align="center"><?php echo $holidayList[$i]['start_date']?></td>
										<td align="center"><?php echo $holidayList[$i]['end_date']?></td>
										<td align="center">
											<form action="<?php echo site_url('report/HolidayUpdate');?>" method="POST">
												<input type="hidden" name="holi_declare_id" id="holi_declare_id" value="<?php echo $holidayList[$i]['id'];?>">
												<button type="submit" name="btnEdit" id="btnEdit" class="btn btn-success btn-sm">Edit</button>
											</form>
										</td>
										<td align="center">
											<form action="<?php echo site_url('report/HolidayDelete');?>" method="POST" onsubmit="return validate();">
												<input type="hidden" name="deleteID" id="deleteID" value="<?php echo $holidayList[$i]['id'];?>">
												<button type="submit" name="btnDelete" id="btnDelete" class="btn btn-danger btn-sm">Delete</button>
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
		<!-- end: page -->
	</section>
	</div>